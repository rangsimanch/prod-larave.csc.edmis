<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Image;
use PDF;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\CreateReportTaskRequest;
use App\Task;
use App\TaskStatus;
use App\TaskTag;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\ConstructionContract;

//use Request;

class TaskController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            if(auth()->user()->roles->contains(28)){
                $query = Task::with(['tags', 'status', 'create_by_user', 'construction_contract', 'team'])->select(sprintf('%s.*', (new Task)->table));
            }
            else{
                $query = Task::with(['tags', 'status', 'create_by_user', 'construction_contract', 'team'])->select(sprintf('%s.*', (new Task)->table))->where('create_by_user_id',auth()->id());
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'task_show';
                $editGate      = 'task_edit';
                $deleteGate    = 'task_delete';
                $crudRoutePart = 'tasks';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : "";
            });
            $table->editColumn('tag', function ($row) {
                $labels = [];

                foreach ($row->tags as $tag) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $tag->name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : "";
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });

            $table->editColumn('attachment', function ($row) {
                if (!$row->attachment) {
                    return '';
                }

                $links = [];

                foreach ($row->attachment as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->addColumn('create_by_user_name', function ($row) {
                return $row->create_by_user ? $row->create_by_user->name : '';
            });

            $table->editColumn('img_user', function ($row) {
                if ($photo = $row->create_by_user->img_user) {
                    return sprintf(
                        '<img src="%s" width="50px" height="50px" class="avatar">',
                        $photo->url,
                        $photo->thumbnail
                    );
                }
                else{
                    return sprintf(
                        '<img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/No_avatar.png" width="50px" height="50px" class="avatar">'
                    );
                }
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('construction_contract.name', function ($row) {
                return $row->construction_contract ? (is_string($row->construction_contract) ? $row->construction_contract : $row->construction_contract->name) : '';
            });

            $table->rawColumns(['actions','img_user', 'placeholder', 'tag', 'status', 'attachment', 'create_by_user', 'construction_contract']);

            return $table->make(true);
        }
            if(auth()->user()->roles->contains(28)){
                $create_by_user = User::all()->sortBy('name')->where('team_id','3')->pluck('name')->unique();
            }
            else{
                $create_by_user = User::all()->sortBy('name')->where('id',auth()->id())->pluck('name')->unique();
            }
            $work_type = TaskTag::all()->sortBy('name')->pluck('name')->unique();
            $status = TaskStatus::all()->sortBy('id')->pluck('name')->unique();
            return view('admin.tasks.index',compact('create_by_user','work_type','status','img_user'));

    }

    public function createReport(){
       
        if(auth()->user()->roles->contains(28) || auth()->user()->roles->contains(1) ){
            $create_by_users = User::all()->where('team_id','3')->pluck('name','id')->prepend(trans('global.pleaseSelect'), '');
            $contracts = ConstructionContract::all()->where('id','!=',15)->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $create_by_users = User::all()->where('id',auth()->id())->pluck('name','id')->prepend(trans('global.pleaseSelect'), '');
            $contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        return view('admin.tasks.createReport', compact('create_by_users', 'contracts'));
    }

    public function createReportTask(CreateReportTaskRequest $request){
        
        $ebits = ini_get('error_reporting');
        error_reporting($ebits ^ E_NOTICE);

        $data = $request->all();

         $StartDate = Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
         $EndDate =  Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
         
        // $tasks = Task::all()
        // ->whereBetween('due_date', array('01/06/2020', '05/06/2020'))
        // ->where('create_by_user_id',$data['create_by_user_id'])->sortBy('due_date');

        if($data['contracts'] != -1){
            $tasks = Task::with(['tags', 'status', 'create_by_user', 'construction_contract', 'team'])
            ->whereBetween('due_date',[$StartDate, $EndDate])
            ->where([ ['create_by_user_id',$data['create_by_user_id']], 
                    ['construction_contract_id', $data['contracts']] 
                    ])->orderBy('due_date')->get();
        }
        else{
            $tasks = Task::with(['tags', 'status', 'create_by_user', 'construction_contract', 'team'])
            ->whereBetween('due_date',[$StartDate, $EndDate])
            ->where('create_by_user_id',$data['create_by_user_id'])->orderBy('due_date')->get();
        }


        $count_task = count($tasks);

        // $count_task = $tasks->count();

        
        if($count_task > 0){ 
            //Conver Date
            // $date = date('m/d/Y',strtotime($data['startDate']));
            $date = \DateTime::createFromFormat('d/m/Y', $data['startDate']);
            //DATA PAGE
            $reportType = $data['reportType'];
            $create_by = User::all()->where('id',$data['create_by_user_id'])->first();
            $gender = '';
            
            if($create_by->gender == 'Male'){
                $gender = 'Mr.';
            }else{
                $gender = 'Ms.';
            }

            $recordby = $gender . ' ' . $create_by->name;
            $jobtitle = $create_by->jobtitle->name ?? '';
            $team = $create_by->team->name ?? '';
            
            if($data['contracts'] != -1){
                $contract_code = $tasks->first()->construction_contract->code ?? '';
                $contract_name = $tasks->first()->construction_contract->name ?? '';
            }
            else{
                $contract_code =  'All work of the whole line';
                $contract_name =  '';
            }



            $dateType = '';
            
            // Report Type
            // if($reportType == 'Daily Report'){
            //     $dateType = $date->format("d-m-Y");
            // }
            if($reportType == 'Weekly Report'){
                $dateType = 'Weekly No. ' . $date->format("W");
            }
            else{
                // $dateTypeA = $data['startDate'];
                $dateType = $date->format("F Y");

            }

            //PDF Setting
            try {
                $mpdf = new \Mpdf\Mpdf([
                    'tempDir' =>  public_path('tmp'), 
                    'default_font' => 'sarabun_new'
                ]);
              } catch (\Mpdf\MpdfException $e) {
                  print "Creating an mPDF object failed with" . $e->getMessage();
              }

            

            // Cover Page
                if($reportType != 'Daily Report'){
                    // $html = "<div style=\"text-align: center; font-weight: bold;  font-size: 70px;\">". $reportType ."</div>";
                    // $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 50px;\">Bangkok - Nakhon Ratchasima HSR</div>";        
                    // $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 40px;\">" .  $dateType . "</div>";        
                    // $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 40px;\"><img src=\"". public_path('png-asset/train_cover.png') ."\"\></div>";        
                    // $html .= "<br></br><div style=\"text-align: center; font-weight: bold; font-size: 40px;\"> Supervision Diary </div>";        
                    // $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 30px;\">Contract Section No. : " . $contract_code . ' ' . $contract_name  . "</div>";        
                    // $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 30px;\">BANGKOK-NAKHON RATCHASIMA HIGH SPEED RAILWAY</div>";        
                    // $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 30px;\">Supevision Unit : State Railway of Thailand</div>";        
                    // $html .= "<br></br><div style=\"text-align: center; font-weight: bold; font-size: 30px;\">By ". $recordby ."</div>";        
                    // $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 25px;\">". $jobtitle ."</div>";        
                    // $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 25px;\">". $team ."</div>"; 

                    $html = "<div style=\"text-align: center; font-weight: bold;  font-size: 70px;\">". $reportType ."</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 50px;\">Bangkok - Nakhon Ratchasima HSR</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 40px;\">" .  $dateType . "</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 40px;\"><img src=\"". public_path('png-asset/train_cover.png') ."\"\></div>";        
                    $html .= "<br></br><div style=\"text-align: center; font-weight: bold; font-size: 40px;\"> Supervision Diary </div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 26px;\">Contract Section No. : " . $contract_code . ' ' . $contract_name  . "</div>";      
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 26px;\">Supervision mileage chainage : DK 000 + 300 to DK 253 + 000 Section </div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 30px;\">BANGKOK-NAKHON RATCHASIMA HIGH SPEED RAILWAY</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 22px;\">Supervision Unit : Chaina Railway International Corporation and Chaina Railway Design Group</div>";   
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 22px;\">Thailand Railway Project Department of the Consortium</div>";        
                    $html .= "<br></br><div style=\"text-align: center; font-weight: bold; font-size: 24px;\">By ". $recordby ."</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 23px;\">". $jobtitle ."</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 22px;\">". $team ."</div>"; 
                           
                    $mpdf->WriteHTML($html);
                    
                    if($contract_code == "C2-1"){
                        $mpdf->AddPage();
                        $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/coverpage.pdf'));
                        // Import the last page of the source PDF file
                        $tplId = $mpdf->ImportPage($pagecount);
                        $mpdf->UseTemplate($tplId);
                    }
                }

            foreach($tasks as $task){
                // $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/activity.pdf'));
                $mpdf->SetDocTemplate(public_path('pdf-asset/activity.pdf'),true);
                $mpdf->AddPage('P','','','','','','',60,55);
                // Import the last page of the source PDF file
                // $tplId = $mpdf->ImportPage($pagecount);
                // $mpdf->UseTemplate($tplId);

                $description = $task->description ?? '';
                $description_set = str_split($description, 1500);
                
                $wind = $task->wind ?? '';
                if(is_null($wind)){
                    $wind = rand(150, 300) / 100;
                }
                $wind .=   ' m/sec';
                $due_date = $task->due_date ?? ''; 
                $weather = $task->weather ?? '';
                if(is_null($weather)){
                    $weather = "Clouds";
                }
                $temperature = $task->temperature ?? '';
                if(is_null($temperature)){
                    $temperature = rand(2850, 3350) / 100;
                }
                $activity_name = $task->name ?? '';
                $contractNo = $task->construction_contract->code ?? '';
     
                $html = "<div style=\"font-size: 18px; position:absolute;top:990;left:95px;\">Construction Contract : ". $contractNo  ." </div>";
                $html .= "<div style=\"text-decoration: underline;font-weight: bold; font-size: 18px; position:absolute;top:112px;left:140px;\">". $due_date ."</div>";
                $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:95px;\">Weather : ". $weather ."</div>";
                $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:300px;\">Wind : ". $wind ."</div>";
                $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:500px;\">Temperature : ". $temperature  ." °C</div>";
                $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:990;left:580px;\">(". $recordby  .")</div>";
                if(!is_null($task->create_by_user->signature)){
                    $html .= "<div style=\"font-weight: bold; position:absolute;top:930;left:630px;\">
                            <img width=\"60%\" height=\"60%\" src=\"" . $task->create_by_user->signature->getPath()
                            . "\"></div>";
                }
                
                $mpdf->SetHTMLHeader($html,'0',true);
                
                $html = "<div style=\" padding-left: 80px; padding-right:80px;\">
                                <div style=\"text-align: center;font-weight: bold; font-size: 22px;\">". nl2br(str_replace(';',"\r\n",$activity_name))  ."</div>
                                </div>";
                
                
                $html .= "<div style=\" padding-left: 80px; padding-right:80px; padding-bottom:-15px; \">
                                    <div style=\"vertical-align: top; max-width: 50%; display: inline-block; font-size: 18px;\">".  nl2br(str_replace(';','\n',$description)) ."</div>
                                    </div><br>";   
                
               
                try{
                    $allowed = array('gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG');

                    if(count($task['attachment'])  > 0){
                        $index = 0;
                        $count_img = 1;
                        $close_div = 0;
                        $count_attachment = count($task['attachment']);
                        if($count_attachment >= 6){
                            $img_wh = "20%";
                        }
                        else{
                            if($count_attachment % 2 == 0){
                                $img_wh = "20%";
                            }
                            else{
                                $img_wh = "25%";
                            }
                        }
                        $html .= "<div style=\"text-align:center;\"> ";

                        foreach($task['attachment'] as $picture){
                            if($index < 6){
                                if($count_attachment == 4){
                                    if($count_img == 3){
                                        $html .= "</div>";
                                        $html .= "<div style=\"text-align:center;\"> ";
                                    }
                                }
                                else{
                                    if($count_img == 4){
                                        $html .= "</div>";
                                        $html .= "<div style=\"text-align:center;\"> ";
                                    }
                                }

                                if(in_array(pathinfo(public_path($task->attachment[$index]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                   
                                    try{
                                        $img = (string) Image::make($task->attachment[$index]->getPath())->orientate()->resize(null, 180, function ($constraint) {
                                            $constraint->aspectRatio();
                                        })
                                        ->encode('data-url');

                                        $html .= "<img width=\"". $img_wh ."\" height=\"". $img_wh ."\" src=\"" 
                                            . $img
                                            . "\"> ";
                                    }catch(Exeption $e){
                                        
                                    }
                                }
                            }
                            $index++;
                            $count_img++;
                        }
                        $html .= "</div>";
                    }    
                }catch(Exception $e){
                    print "Creating an mPDF object failed with" . $e->getMessage();
                }

                $mpdf->WriteHTML($html); 
            }
            $filename =  $reportType . " " . $StartDate . " to " .  $EndDate . ".pdf";
            return $mpdf->Output($filename, 'I');
            // return redirect()->back() ->with('alert', $tasks);
        }
        else{
            return redirect()->back() ->with('alert', "No activity on date range");
        }
    }


    public function create()
    {
        abort_if(Gate::denies('task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = TaskTag::all()->pluck('name', 'id');

        $statuses = TaskStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tasks.create', compact('tags', 'statuses'));
    }


    
    public function store(StoreTaskRequest $request)
    {
        $data = $request->all();
        $data['create_by_user_id'] = auth()->id();
        $data['construction_contract_id'] = session()->get('construction_contract_id');
        $task = Task::create($data);
        $task->tags()->sync($request->input('tags', []));

        foreach ($request->input('attachment', []) as $file) {
            $task->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $task->id]);
        }

        return redirect()->route('admin.tasks.index');

    }

    public function edit(Task $task)
    {
        abort_if(Gate::denies('task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = TaskTag::all()->pluck('name', 'id');

        $statuses = TaskStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $task->load('tags', 'status', 'create_by_user', 'construction_contract', 'team');

        return view('admin.tasks.edit', compact('tags', 'statuses', 'task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->all());
        $task->tags()->sync($request->input('tags', []));

        if (count($task->attachment) > 0) {
            foreach ($task->attachment as $media) {
                if (!in_array($media->file_name, $request->input('attachment', []))) {
                    $media->delete();
                }

            }

        }

        $media = $task->attachment->pluck('file_name')->toArray();

        foreach ($request->input('attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $task->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
            }

        }

        return redirect()->route('admin.tasks.index');

    }

    public function show(Task $task)
    {
        abort_if(Gate::denies('task_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->load('tags', 'status', 'create_by_user', 'construction_contract', 'team');

        return view('admin.tasks.show', compact('task'));
    }

    public function destroy(Task $task)
    {
        abort_if(Gate::denies('task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->delete();

        return back();

    }

    public function massDestroy(MassDestroyTaskRequest $request)
    {
        Task::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('task_create') && Gate::denies('task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Task();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);

    }

}
