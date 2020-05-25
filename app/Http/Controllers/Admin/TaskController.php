<?php

namespace App\Http\Controllers\Admin;

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
                        '<img src="https://i.webch7.com/images/theme2019/avatar.png" width="50px" height="50px" class="avatar">'
                    );
                }
                return '';
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
            return view('admin.tasks.index',compact('create_by_user','work_type','status'));

    }

    public function createReport(){
       
        if(auth()->user()->roles->contains(28)){
            $create_by_users = User::all()->where('team_id','3')->pluck('name','id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $create_by_users = User::all()->where('id',auth()->id())->pluck('name','id')->prepend(trans('global.pleaseSelect'), '');
        }
        return view('admin.tasks.createReport', compact('create_by_users'));
    }

    public function createReportTask(CreateReportTaskRequest $request){
        
        $ebits = ini_get('error_reporting');
        error_reporting($ebits ^ E_NOTICE);

        $data = $request->all();
        $tasks = Task::all()->wherebetween('due_date',[$data['startDate'],$data['endDate']])->where('create_by_user_id',$data['create_by_user_id'])->sortBy('due_date');
        $count_task = count($tasks);
        
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
            $contract_code = $tasks->first()->construction_contract->code ?? '';
            $contract_name = $tasks->first()->construction_contract->name ?? '';

            $dateType = '';
            
            // Report Type
            if($reportType == 'Daily Report'){
                $dateType = $date->format("D, d F Y");
            }
            else if($reportType == 'Weekly Report'){
                $dateType = 'Weekly No. ' . $date->format("W");
            }
            else{
                // $dateTypeA = $data['startDate'];
                $dateType = $date->format("F Y");

            }

            //PDF Setting
            try {
                $mpdf = new \Mpdf\Mpdf([
                    'tempDir' => '../vendor/mpdf/mpdf/tmp',
                    'default_font' => 'sarabun'
                ]);
              } catch (\Mpdf\MpdfException $e) {
                  print "Creating an mPDF object failed with" . $e->getMessage();
              }

            // Cover Page
                if($reportType != 'Daily Report'){
                $html = "<div style=\"text-align: center; font-weight: bold;  font-size: 70px;\">". $reportType ."</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 50px;\">Bangkok - Nakhon Ratchasima HSR</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 40px;\">" .  $dateType . "</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 40px;\"><img src=\"". public_path('png-asset/train_cover.png') ."\"\></div>";        
                    $html .= "<br></br><div style=\"text-align: center; font-weight: bold; font-size: 40px;\"> Supervision Diary </div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 30px;\">Contract Section No. : " . $contract_code . ' ' . $contract_name  . "</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 30px;\">BANGKOK-NAKHON RATCHASIMA HIGH SPEED RAILWAY</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 30px;\">Supevision Unit : State Railway of Thailand</div>";        
                    $html .= "<br></br><div style=\"text-align: center; font-weight: bold; font-size: 30px;\">By ". $recordby ."</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 25px;\">". $jobtitle ."</div>";        
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 25px;\">". $team ."</div>"; 
                           
                    $mpdf->WriteHTML($html);
                    
                    $mpdf->AddPage();
                    $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/coverpage.pdf'));
                    // Import the last page of the source PDF file
                    $tplId = $mpdf->ImportPage($pagecount);
                    $mpdf->UseTemplate($tplId);
                }

            // Activity
            // for($i = 0; $i < $count_task; $i++){
            foreach($tasks as $task){
                $mpdf->AddPage();
                $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/activity.pdf'));
               
                // Import the last page of the source PDF file
                $tplId = $mpdf->ImportPage($pagecount);
                $mpdf->UseTemplate($tplId);

                $wind = $task->wind ?? '';
                $wind .=   ' m/sec';
                $due_date = $task->due_date ?? ''; 
                $weather = $task->weather ?? '';
                $temperature = $task->temperature ?? '';
                $activity_name = $task->name ?? '';
                $description = $task->description ?? '';
                $descWordWrap =   wordwrap($description, 300,"<br>\n");
                $contractNo = $task->construction_contract->code ?? '';
                
                $html = "<div style=\"font-size: 18px; position:absolute;top:990;left:95px;\">Construction Contract : ". $contractNo  ." </div>";
                $html .= "<div style=\"text-decoration: underline;font-weight: bold; font-size: 18px; position:absolute;top:112px;left:140px;\">". $due_date ."</div>";
                $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:95px;\">Weather : ". $weather ."</div>";
                $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:300px;\">Wind : ". $wind ."</div>";
                $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:500px;\">Temperature : ". $temperature  ." °C</div>";
                
                $html .= "<br><br><br><br><br><br><br><br>
                            <div style=\" padding-left: 80px; padding-right:80px; \">
                            <div style=\"text-align: center;font-weight: bold; font-size: 22px;\">". nl2br(str_replace(';',"\r\n",$activity_name))  ."</div>
                            </div>";
                $html .= "<div style=\" padding-left: 80px; padding-right:80px; \">
                            <div style=\"vertical-align: top; max-width: 50%; display: inline-block; font-size: 20px;\">".  nl2br(str_replace(';','\n',$description)) ."</div>
                            </div>";
                
                $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:990;left:580px;\">(". $recordby  .")</div>";
                
                if(!is_null($task->create_by_user->signature)){
                    $html .= "<div style=\"font-weight: bold; position:absolute;top:950;left:630px;\">
                            <img width=\"60%\" height=\"auto\" src=\"" . $task->create_by_user->signature->getPath()
                            . "\"></div>";
                }
                
                // For Each Version
                    // foreach($task->attachment as $media){
                    //     $allowed = array('gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG');
                    //     if(in_array(pathinfo(public_path($media->getUrl()),PATHINFO_EXTENSION),$allowed)){
                    //         //Prod use $task->attachment[0]->getPath() 
                    //         $html .= "<img  width=\"30%\" height=\"auto\" src=\"" 
                    //             . $media->getUrl()
                    //             . "\">";
                    //     }
                    // }

                try{
                // Add Image       
                    $allowed = array('gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG');
                    if(count($task->attachment)  > 0){
                        if(count($task->attachment)  == 1){
                            if(in_array(pathinfo(public_path($task->attachment[0]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                //Prod use $task->attachment[0]->getPath() 
                                $html .= "<br><div style=\"text-align:center;\"> <img width=\"40%\" height=\"auto\" src=\"" 
                                    . $task->attachment[0]->getPath() 
                                    . "\"></div>";

                                //Dev use public_path($task->attachment[0]->getUrl())
                                    // $html .= "<br><div style=\"text-align:center;\"> <img width=\"40%\" height=\"auto\" src=\"" 
                                    //     . public_path($task->attachment[0]->getUrl())
                                    //     . "\"></div>";
                                    
                            }
                        
                        }

                        else if(count($task->attachment) == 2){
                            if(in_array(pathinfo(public_path($task->attachment[0]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                $html .= "<br><div style=\"text-align:center;\"> <img width=\"25%\" height=\"auto\" src=\"" 
                                    . $task->attachment[0]->getPath()  
                                    . "\">";
                            }
                            if(in_array(pathinfo(public_path($task->attachment[1]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                $html .= " <img width=\"25%\" height=\"auto\" src=\"" 
                                    . $task->attachment[1]->getPath() 
                                    . "\"></div>";
                            }
                        }
                        else if(count($task->attachment) == 3){
                            if(in_array(pathinfo(public_path($task->attachment[0]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                $html .= "<br><div style=\"text-align:center;\"> <img width=\"20%\" height=\"auto\" src=\"" 
                                    . $task->attachment[0]->getPath() 
                                    . "\">";
                            }
                            if(in_array(pathinfo(public_path($task->attachment[1]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                                    . $task->attachment[1]->getPath() 
                                    . "\">";
                            }
                            if(in_array(pathinfo(public_path($task->attachment[2]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                $html .= "<img width=\"20%\" height=\"auto\" src=\"" 
                                    . $task->attachment[2]->getPath() 
                                    . "\"></div>";
                            }

                        }
                        // else if(count($task->attachment) == 4){
                        //     if(in_array(pathinfo(public_path($task->attachment[0]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= "<br><div style=\"text-align:center;\"> <img width=\"25%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[0]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[1]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"25%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[1]->getPath() 
                        //             . "\"></div>";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[2]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= "<div style=\"text-align:center;\"> <img width=\"25%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[2]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[3]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"25%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[3]->getPath()  
                        //             . "\"></div>";
                        //     }
                        // }

                        else{
                            if(in_array(pathinfo(public_path($task->attachment[0]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                $html .= "<br><div style=\"text-align:center;\"> <img width=\"20%\" height=\"auto\" src=\"" 
                                    . $task->attachment[0]->getPath() 
                                    . "\">";
                            }
                            if(in_array(pathinfo(public_path($task->attachment[1]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                                    . $task->attachment[1]->getPath() 
                                    . "\">";
                            }
                            if(in_array(pathinfo(public_path($task->attachment[2]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                $html .= "<img width=\"20%\" height=\"auto\" src=\"" 
                                    . $task->attachment[2]->getPath() 
                                    . "\"></div>";
                            }
                        }
                    }else{
                        
                    }

                        // }
                        // else if(count($task->attachment) == 5){
                        //     if(in_array(pathinfo(public_path($task->attachment[0]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= "<br><div style=\"text-align:center;\"> <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[0]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[1]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[1]->getPath() 
                        //             . "\"></div>";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[2]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= "<div style=\"text-align:center;\"> <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[2]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[3]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[3]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[4]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[4]->getPath() 
                        //             . "\"></div>";
                        //     }

                        // }
                        // else if(count($task->attachment) == 6){
                        //     if(in_array(pathinfo(public_path($task->attachment[0]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= "<br><div style=\"text-align:center;\"> <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[0]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[1]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[1]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[2]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[2]->getPath() 
                        //             . "\"></div>";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[3]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= "<div style=\"text-align:center;\"><img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[3]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[4]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[4]->getPath() 
                        //             . "\">";
                        //     }
                        //     if(in_array(pathinfo(public_path($task->attachment[5]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                        //         $html .= " <img width=\"20%\" height=\"auto\" src=\"" 
                        //             . $task->attachment[5]->getPath() 
                        //             . "\"></div>";
                        //     }
                        // }
                    
                }catch(Exception $e){
                    print "Creating an mPDF object failed with" . $e->getMessage();
                }

                $mpdf->WriteHTML($html);
            }
            return $mpdf->Output();
        }
        else{
            return redirect()->back() ->with('alert', 'No Activity in date range!');
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
