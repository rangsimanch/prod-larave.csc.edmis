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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use File;

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

            // $table->editColumn('img_user', function ($row) {
            //     if ($photo = $row->create_by_user->img_user) {
            //         return sprintf(
            //             '<img src="%s" width="50px" height="50px" class="avatar">',
            //             $photo->url,
            //             $photo->thumbnail
            //         );
            //     }
            //     else{
            //         return sprintf(
            //             '<img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/No_avatar.png" width="50px" height="50px" class="avatar">'
            //         );
            //     }
            // });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('construction_contract.name', function ($row) {
                return $row->construction_contract ? (is_string($row->construction_contract) ? $row->construction_contract : $row->construction_contract->name) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'tag', 'status', 'attachment', 'create_by_user', 'construction_contract']);

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
        $count_prev_att = 0;

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

            // Optional fields from form (null-able) - fallback to user's team if empty
            $work_point_name = trim($request->input('work_point_name', ''));
            $department_team = trim($request->input('department_team', ''));
            if($work_point_name === '') $work_point_name = $team;
            if($department_team === '') $department_team = $team;

            // Activity form language: 'english' (default) or 'thai'
            $activityLang = $request->input('activityLang', 'english');

            if($data['contracts'] != -1){
                $contract_code = $tasks->first()->construction_contract->code ?? '';
                $contract_name = $tasks->first()->construction_contract->name ?? '';
                $dk_start = $tasks->first()->construction_contract->dk_start ?? '';
                $dk_end = $tasks->first()->construction_contract->dk_end ?? '';
            }
            else{
                $contract_array = array();
                $dk_start_array = array();
                $dk_end_array = array();
                foreach($tasks as $task){
                    $contract_code = $task->construction_contract->code ?? '';
                    $dk_start = $task->construction_contract->dk_start_1 ?? '';
                    $dk_end = $task->construction_contract->dk_end_1 ?? '';
                    if(!in_array($contract_code, $contract_array, true)){
                        array_push($contract_array, $contract_code);
                        if($contract_code == "C4-1"){
                            $dk_start_array["A"] = $dk_start;
                            $dk_end_array["A"] = $dk_end;
                        }
                        if($contract_code == "C4-2"){
                            $dk_start_array["B"] = $dk_start;
                            $dk_end_array["B"] = $dk_end;
                        }
                        if($contract_code == "C3-3"){
                            $dk_start_array["C"] = $dk_start;
                            $dk_end_array["K"] = $dk_end;
                        }
                        if($contract_code == "C4-3"){
                            $dk_start_array["D"] = $dk_start;
                            $dk_end_array["D"] = $dk_end;
                        }
                        if($contract_code == "C4-4"){
                            $dk_start_array["E"] = $dk_start;
                            $dk_end_array["C"] = $dk_end;
                        }
                        if($contract_code == "C4-5"){
                            $dk_start_array["F"] = $dk_start;
                            $dk_end_array["E"] = $dk_end;
                        }
                        if($contract_code == "C4-6"){
                            $dk_start_array["G"] = $dk_start;
                            $dk_end_array["F"] = $dk_end;
                        }
                        if($contract_code == "C4-7"){
                            $dk_start_array["H"] = $dk_start;
                            $dk_end_array["G"] = $dk_end;
                        }
                        if($contract_code == "C3-1"){
                            $dk_start_array["I"] = $dk_start;
                            $dk_end_array["H"] = $dk_end;
                        }
                        if($contract_code == "C3-2"){
                            $dk_start_array["J"] = $dk_start;
                            $dk_end_array["I"] = $dk_end;
                        }
                        if($contract_code == "C1-1"){
                            $dk_start_array["K"] = $dk_start;
                            $dk_end_array["J"] = $dk_end;
                        }
                        if($contract_code == "C3-4"){
                            $dk_start_array["L"] = $dk_start;
                            $dk_end_array["L"] = $dk_end;
                        }
                        if($contract_code == "C2-1"){
                            $dk_start_array["M"] = $dk_start;
                            $dk_end_array["M"] = $dk_end;
                        }
                        if($contract_code == "C3-5"){
                            $dk_start_array["N"] = $dk_start;
                            $dk_end_array["N"] = $dk_end;
                        }
                    }
                }
                $contract_code =  implode(",",$contract_array);
                ksort($dk_start_array);
                krsort($dk_end_array);
                $dk_start_value = array_values($dk_start_array);
                $dk_end_value = array_values($dk_end_array);
                $dk_start = $dk_start_value[0];
                $dk_end = $dk_end_value[0];
                $contract_name =  '';
            }
            $dateType = '';

            // Report Type
            if($reportType == 'Weekly Report'){
                $dateType = 'Weekly No. ' . $date->format("W");
            }
            else{
                $dateType = $date->format("F Y");
            }

            //PDF Setting
            try {
                $mpdf = new \Mpdf\Mpdf([
                    'tempDir' =>  public_path('tmp'),
                    'fontdata'     => [
                        'sarabun_new' => [
                            'R' => 'THSarabunNew.ttf',
                            'B' => 'THSarabunNew Bold.ttf',
                            'I' => 'THSarabunNew Italic.ttf',
                        ],
                    ],
                    'mode' => '+aCJK',
                    "autoScriptToLang" => true,
                    "autoLangToFont" => true,
                    "allow_charset_conversion" => true,
                    "charset_in" => 'UTF-8',
                    'shrink_tables_to_fit' => 0,
                    'simpleTables' => true,
                ]);

                // Ensure table auto-shrink is really off.
                $mpdf->shrink_tables_to_fit = 0;
                $mpdf->allow_html_table_autosize = false;
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

                    $html = "<div style=\"text-align: center; font-weight: bold;  font-size: 60px;\">". $reportType ."</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 40px;\">Bangkok - Nakhon Ratchasima HSR</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 34px;\">" .  $dateType . "</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold;\"><img style=\"height: 220px;\" src=\"". public_path('png-asset/train_cover.png') ."\"\></div>";
                    $html .= "<br><div style=\"text-align: center; font-weight: bold; font-size: 34px;\"> Supervision Diary </div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 22px;\">Contract Section No. : " . $contract_code . ' ' . $contract_name  . "</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 22px;\">Supervision mileage chainage :". $dk_start ." to ". $dk_end ." Section </div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 26px;\">BANGKOK-NAKHON RATCHASIMA HIGH SPEED RAILWAY</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 20px;\">Supervision Unit : Chaina Railway International Corporation and Chaina Railway Design Group</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 20px;\">Thailand Railway Project Department of the Consortium</div>";
                    $html .= "<br><div style=\"text-align: center; font-weight: bold; font-size: 22px;\">By ". $recordby ."</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 21px;\">". $jobtitle ."</div>";
                    $html .= "<div style=\"text-align: center; font-weight: bold; font-size: 20px;\">". $team ."</div>";

                    // Force English cover to stay on one page (no overflow)
                    $mpdf->SetAutoPageBreak(false);
                    $mpdf->WriteHTML($html);
                    $mpdf->SetAutoPageBreak(true, 15);
                    $html = "";
                    if($contract_code == "C2-1"){
                        $mpdf->AddPage();
                        $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/coverpage.pdf'));
                        // Import the last page of the source PDF file
                        $tplId = $mpdf->ImportPage($pagecount);
                        $mpdf->UseTemplate($tplId);
                    }

                    // Thai Cover Page (for Weekly and Monthly reports)
                    if($reportType == 'Weekly Report' || $reportType == 'Monthly Report'){
                        $mpdf->AddPage();

                        // Thai month names
                        $thaiMonths = [
                            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                        ];

                        $monthNum = (int)$date->format('n');
                        $buddhistYear = (int)$date->format('Y') + 543;
                        $thaiDateLabel = $thaiMonths[$monthNum] . ' ' . $buddhistYear;

                        // Build contract sections string with DK ranges
                        $contractSections = '';
                        $seenContracts = [];
                        foreach($tasks as $task){
                            $cc = $task->construction_contract;
                            if(!$cc) continue;
                            $code = $cc->code ?? '';
                            if($code === '' || in_array($code, $seenContracts)) continue;
                            $seenContracts[] = $code;
                            $dkS = $cc->dk_start_1 ?? '';
                            $dkE = $cc->dk_end_1 ?? '';
                            // Add DK prefix if not already present
                            if($dkS !== '' && stripos($dkS, 'DK') !== 0) $dkS = 'DK' . $dkS;
                            if($dkE !== '' && stripos($dkE, 'DK') !== 0) $dkE = 'DK' . $dkE;
                            if($contractSections !== '') $contractSections .= '<br>';
                            $contractSections .= $code . ' (' . $dkS . ' - ' . $dkE . ')';
                        }
                        if($contractSections === ''){
                            $dkS = $dk_start;
                            $dkE = $dk_end;
                            if($dkS !== '' && stripos($dkS, 'DK') !== 0) $dkS = 'DK' . $dkS;
                            if($dkE !== '' && stripos($dkE, 'DK') !== 0) $dkE = 'DK' . $dkE;
                            $contractSections = $contract_code . ' (' . $dkS . ' - ' . $dkE . ')';
                        }

                        // Thai cover page HTML (font sizes in pt to match docx template: 24pt titles, 16pt info)
                        $thaiHtml  = "<div style=\"text-align: center; margin-top: 60px;\">";
                        $thaiHtml .= "<div style=\"font-weight: bold; font-size: 24pt; line-height: 1.5;\">โครงการรถไฟความเร็วสูงกรุงเทพฯ-นครราชสีมา</div>";
                        $thaiHtml .= "<div style=\"font-weight: bold; font-size: 24pt; line-height: 1.5;\">(เดือน ปี)</div>";
                        $thaiHtml .= "<div style=\"font-weight: bold; font-size: 24pt; line-height: 1.5; margin-bottom: 20px;\">" . $thaiDateLabel . "</div>";
                        $thaiHtml .= "<div style=\"font-weight: bold; font-size: 24pt; line-height: 1.5;\">สมุดบันทึกการควบคุมงานประจำวัน</div>";
                        $thaiHtml .= "</div>";

                        // Decorative horizontal line
                        $thaiHtml .= "<div style=\"border-top: 2px solid #333; margin: 40px 60px;\"></div>";

                        // Info section as a table for clean label/value alignment
                        $thaiHtml .= "<table style=\"width: 100%; border-collapse: collapse; margin-top: 20px;\" cellpadding=\"8\">";
                        $thaiHtml .= "<tr><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; white-space: nowrap; vertical-align: top; width: 35%;\">ช่วงสัญญา :</td><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; vertical-align: top;\">" . $contractSections . "</td></tr>";
                        $thaiHtml .= "<tr><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; white-space: nowrap; vertical-align: top;\">ชื่อจุดทำงาน :</td><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; vertical-align: top;\">" . $work_point_name . "</td></tr>";
                        $thaiHtml .= "<tr><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; white-space: nowrap; vertical-align: top;\">แผนกควบคุมงานหรือทีมควบคุมงาน :</td><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; vertical-align: top;\">" . $department_team . "</td></tr>";
                        $thaiHtml .= "<tr><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; white-space: nowrap; vertical-align: top;\">ชื่อ-นามสกุล วิศวกรผู้ควบคุมงาน :</td><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; vertical-align: top;\">" . $recordby . "</td></tr>";
                        $thaiHtml .= "<tr><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; white-space: nowrap; vertical-align: top;\">ตำแหน่ง ผู้ควบคุมงานหรือสาขา :</td><td style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word; vertical-align: top;\">" . $jobtitle . "</td></tr>";
                        $thaiHtml .= "</table>";

                        $mpdf->WriteHTML($thaiHtml);
                    }
                }

            if($activityLang !== 'thai'){
                $template_path = public_path('pdf-asset/activity.pdf');
            }
            else {
                $template_path = public_path('pdf-asset/activity_th.pdf');
            }

            $page_template = $mpdf->SetSourceFile($template_path, true);
            $import_page = $mpdf->importPage($page_template);
            $page_size = $mpdf->getTemplateSize($import_page);
            $mpdf->SetDocTemplate('');

            foreach($tasks as $task){

                $hasPreviousAttachment = count($task->pdf_attachment) > 0 || $count_prev_att > 0;

                if($hasPreviousAttachment){
                    $mpdf->SetDocTemplate('');
                }
                else {
                    $mpdf->SetDocTemplate($template_path, true);
                }

                if($activityLang !== 'thai'){
                    $mpdf->AddPage($page_size['orientation'],'','','','','','',60,55);
                }
                else {
                    // Top margin 35mm reserves space for date+weather header on every page.
                    // Bottom margin 40mm reserves space for fixed footer.
                    $mpdf->AddPage('P','','','','','','',45,60);
                }

                if($hasPreviousAttachment){
                    $mpdf->UseTemplate($import_page, 0, 0, $page_size['width'], $page_size['height'], true);
                    $count_prev_att = 0;
                }




                $description = str_replace("：", ":", $task->description ?? '');
                $description_set = str_split($description, 1500);

                $wind = $task->wind ?? '';
                if($wind == ''){
                    $wind = rand(150, 300) / 100;
                }
                $wind .=   ' m/sec';
                $due_date = $task->due_date ?? '';

                // Thai date format for activity header: (วันที่ 1 มิถุนายน ปี 2569 วันจันทร์)
                $due_date_thai = '';
                if($due_date !== ''){
                    $thaiMonthsFull = ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
                    $thaiDaysFull = ['อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์'];
                    $dueDateCarbon = Carbon::createFromFormat('d/m/Y', $due_date);
                    $due_date_thai = sprintf(
                        'วันที่ %d %s ปี %d วัน%s',
                        $dueDateCarbon->format('j'),
                        $thaiMonthsFull[(int)$dueDateCarbon->format('n') - 1],
                        (int)$dueDateCarbon->format('Y') + 543,
                        $thaiDaysFull[(int)$dueDateCarbon->format('w')]
                    );
                }

                $weather = $task->weather ?? '';
                if($weather == ''){
                    $weather = "Clouds";
                }
                $temperature = $task->temperature ?? '';
                if($temperature == ''){
                    $temperature = rand(2850, 3350) / 100;
                }
                $activity_name = $task->name ?? '';
                $contractNo = $task->construction_contract->code ?? '';

                $signature_html = '';
                if($activityLang === 'thai'){
                    // Thai: prepare variables, build full page as body HTML (no template, no header)
                    $weatherMap = [
                        'Clear' => 'แจ่มใส', 'Clouds' => 'มีเมฆ', 'Rain' => 'ฝนตก',
                        'Mist' => 'มีหมอก', 'Fog' => 'มีหมอกหนา', 'Haze' => 'มีหลัวควัน',
                        'Thunderstorm' => 'พายุฝนฟ้าคะนอง', 'Drizzle' => 'ฝนละออง',
                    ];
                    $weatherThai = $weatherMap[$weather] ?? $weather;
                }
                else {
                    $html = "<div style=\"font-size: 18px; position:absolute;top:990;left:95px;\">Construction Contract : ". $contractNo  ." </div>";
                    $html .= "<div style=\"text-decoration: underline;font-weight: bold; font-size: 18px; position:absolute;top:112px;left:140px;\">". $due_date . "</div>";
                    $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:95px;\">Weather : ". $weather ."</div>";
                    $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:300px;\">Wind : ". $wind ."</div>";
                    $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:155px;left:500px;\">Temperature : ". $temperature  ." °C</div>";
                    $html .= "<div style=\"font-weight: bold; font-size: 20px; position:absolute;top:990;left:580px;\">(". $recordby  .")</div>";
                }

                if(!is_null($task->create_by_user->signature)){
                    $url =  url($task->create_by_user->signature->getUrl());
                    $handle = curl_init($url);
                    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
                    $response = curl_exec($handle);
                    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                    curl_close($handle);


                    if($httpCode != 404){
                        if($activityLang === 'thai'){
                            // Thai: save signature for body append (flow layout)
                            $signature_html = "<img width=\"150\" src=\"" . $task->create_by_user->signature->getPath() . "\">";
                        }
                        else {
                            $html .= "<div style=\"font-weight: bold; position:absolute;top:900;left:620px;\">
                                    <img width=\"70%\" height=\"70%\" src=\"" . $task->create_by_user->signature->getPath()
                                    . "\"></div>";
                        }
                    }
                }

                if($activityLang !== 'thai'){
                    $mpdf->SetHTMLHeader($html,'0',true);

                    $html = "<div style=\" padding-left: 80px; padding-right:80px;\">
                                <div style=\"text-align: center;font-weight: bold; font-size: 22px;\">". nl2br(str_replace(';',"\r\n",$activity_name))  ."</div>
                                </div>";


                    $html .= "<div style=\" padding-left: 80px; padding-right:80px; padding-bottom:-15px; \">
                                    <div style=\"vertical-align: top; max-width: 50%; display: inline-block; font-size: 15px;\">".  nl2br(str_replace(';','\n',$description)) ."</div>
                                    </div><br>";
                }
                else {
                    // Thai: use SetHTMLHeader for date+weather (repeats on every page, same as English approach)
                    $thaiHeader = "<div style=\"font-weight: bold; font-size: 16pt; padding: 20px 30px 4px 10px;\">" . $due_date_thai . "</div>";
                    $thaiHeader .= "<div style=\"font-weight: bold; font-size: 16pt; padding: 0px 30px 4px 10px;\">อากาศ : " . $weatherThai . "&nbsp;&nbsp;&nbsp;ลม : " . $wind . "&nbsp;&nbsp;&nbsp;อุณหภูมิ : " . $temperature . " °C</div>";
                    $mpdf->SetHTMLHeader($thaiHeader, '0', true);

                    // Body: activity_name + description (same layout pattern as English)
                    $activity_name_lines = array_map('trim', explode(';', $activity_name));
                    $activity_name_clean = implode("\r\n", $activity_name_lines);
                    $html = "<div style=\"padding: 0 30px;\">
                                <div style=\"text-align: center; font-weight: bold; font-size: 16pt; margin-bottom: 20px;\">" . nl2br($activity_name_clean) . "</div>
                                </div>";
                    $html .= "<div style=\"padding: 0 15px 0 30px;\"><div style=\"vertical-align: top; max-width: 50%; display: inline-block; font-size: 14pt;\">" . nl2br(str_replace(';', "\n", $description)) . "</div></div>";
                    $html .= "<!--IMAGES_PLACEHOLDER-->";
                    $html .= "<!--FOOTER_PLACEHOLDER-->";
                }


                try{
                    $allowed = array('gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG');

                    if(count($task['attachment']) > 0){
                        $max_images = 10;

                        // Step 1: Collect valid images (skip broken ones via try/catch on Image::make)
                        // No cURL check needed - Image::make throws on corrupt files, which is faster
                        $valid_images = [];
                        $index = 0;
                        foreach($task['attachment'] as $picture){
                            if(count($valid_images) >= $max_images) break;

                            $ext = pathinfo(public_path($task->attachment[$index]->getUrl()), PATHINFO_EXTENSION);
                            if(!in_array($ext, $allowed)){
                                $index++;
                                continue;
                            }

                            try{
                                $url_path = $task->attachment[$index]->getPath();
                                // Check file exists and readable before processing
                                if(!file_exists($url_path) || !is_readable($url_path)){
                                    $index++;
                                    continue;
                                }
                                $img_height = ($activityLang !== 'thai') ? 126 : 180;
                                $img = (string) Image::make($url_path)
                                    ->orientate()
                                    ->resize(null, $img_height, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })
                                    ->encode('data-url');
                                $valid_images[] = $img;
                            }catch(\Throwable $e){
                                // Skip broken/corrupt images (NotReadableException, etc.)
                                // Report still generates without them
                            }

                            $index++;
                        }

                        // Step 2: Auto-layout based on valid image count (1-10)
                        $valid_count = count($valid_images);
                        if($valid_count > 0){
                            // English: reduced 30% from original. Thai: original sizes.
                            $en_sizes = [1 => '49%', 2 => '31%', 3 => '21%', 4 => '31%', 5 => '21%', 6 => '21%', 7 => '12%'];
                            $th_sizes = [1 => '70%', 2 => '45%', 3 => '30%', 4 => '45%', 5 => '30%', 6 => '30%', 7 => '18%'];
                            $sizes = ($activityLang !== 'thai') ? $en_sizes : $th_sizes;
                            if($valid_count == 1){
                                $cols = 1; $img_wh = $sizes[1];
                            }
                            elseif($valid_count == 2){
                                $cols = 2; $img_wh = $sizes[2];
                            }
                            elseif($valid_count == 3){
                                $cols = 3; $img_wh = $sizes[3];
                            }
                            elseif($valid_count == 4){
                                $cols = 2; $img_wh = $sizes[4];
                            }
                            elseif($valid_count == 5){
                                $cols = 3; $img_wh = $sizes[5];
                            }
                            elseif($valid_count == 6){
                                $cols = 3; $img_wh = $sizes[6];
                            }
                            else{
                                $cols = 5; $img_wh = $sizes[7];
                            }

                            // Build table layout for reliable grid in mPDF
                            $images_html = "<table style=\"width:100%; border-collapse:collapse; text-align:center;\"><tr>";
                            $col = 0;
                            foreach($valid_images as $img){
                                $images_html .= "<td style=\"padding:3px;\"><img width=\"" . $img_wh . "\" src=\"" . $img . "\"></td>";
                                $col++;
                                if($col >= $cols && $col < $valid_count){
                                    $images_html .= "</tr><tr>";
                                    $col = 0;
                                }
                            }
                            // Fill remaining cells in last row
                            while($col > 0 && $col < $cols){
                                $images_html .= "<td></td>";
                                $col++;
                            }
                            $images_html .= "</tr></table>";

                            // Thai: replace placeholder so images appear inside the bordered content box
                            // English: append after description
                            if($activityLang === 'thai'){
                                $html = str_replace('<!--IMAGES_PLACEHOLDER-->', $images_html, $html);
                            }
                            else {
                                $html .= $images_html;
                            }
                        }
                    }
                }catch(\Throwable $e){
                    print "Creating an mPDF object failed with" . $e->getMessage();
                }

                // Thai: clean up unused placeholders (no valid images case)
                if($activityLang === 'thai'){
                    $html = str_replace('<!--IMAGES_PLACEHOLDER-->', '', $html);
                }

                if($activityLang === 'thai'){
                    $footer = "<table style=\"width:100%;\"><tr>";
                    $footer .= "<td style=\"font-size: 16pt; word-break: break-all; word-wrap: break-word; vertical-align: bottom; width: 50%;\">สัญญาก่อสร้าง : " . $contractNo . "</td>";
                    $footer .= "<td style=\"text-align: right; vertical-align: bottom;\">";
                    if($signature_html !== ''){
                        $footer .= "<img width=\"120\" src=\"" . $task->create_by_user->signature->getPath() . "\"><br>";
                    }
                    $footer .= "<span style=\"font-weight: bold; font-size: 16pt; word-break: break-all; word-wrap: break-word;\">(" . $recordby . ")</span>";
                    $footer .= "</td>";
                    $footer .= "</tr></table>";
                    $mpdf->SetHTMLFooter($footer);
                    $html = str_replace('<!--FOOTER_PLACEHOLDER-->', '', $html);
                }

                $mpdf->WriteHTML($html);

                $html="";
                $mpdf->SetHTMLHeader($html,'0',true);
                $mpdf->SetDocTemplate("");
                $first_attachment_page = true;
                foreach($task->pdf_attachment as $pdf){
                    try{
                        $count_prev_att += 1;
                        $url =  url($pdf->getUrl());
                        $handle = curl_init($url);
                        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
                        $response = curl_exec($handle);
                        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                        curl_close($handle);
                        if($httpCode != 404){
                            $pagecount = $mpdf->SetSourceFile($pdf->getPath());
                            for($page = 1; $page <= $pagecount; $page++){
                                $tplId = $mpdf->importPage($page);
                                $size = $mpdf->getTemplateSize($tplId);
                                $mpdf->AddPage($size['orientation']);
                                // Clear footer after the content page is closed (first AddPage),
                                // so attachment pages have no footer but content pages keep it.
                                if($first_attachment_page){
                                    $mpdf->SetHTMLFooter('');
                                    $first_attachment_page = false;
                                }
                                $mpdf->UseTemplate($tplId, 0, 0, $size['width'], $size['height'], true);
                            }
                        }
                    }catch(exeption $e){
                        print "Creating an mPDF object failed with" . $e->getMessage();
                    }

                }
                // $mpdf->RestartDocTemplate();
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

        $user = User::with(['construction_contracts'])->where('id', auth()->id())->first();

        $user_contracts = [];
        foreach($user->construction_contracts as $contract){
            array_push($user_contracts, $contract->id);
        }

        $construction_contracts = ConstructionContract::whereIn('id', $user_contracts)->pluck('code','id')->prepend(trans('global.pleaseSelect'), '');

        $statuses = TaskStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tasks.create', compact('tags', 'statuses', 'construction_contracts'));
    }



    public function store(StoreTaskRequest $request)
    {
        $data = $request->all();
        $data['create_by_user_id'] = auth()->id();
        $task = Task::create($data);
        $task->tags()->sync($request->input('tags', []));

        foreach ($request->input('attachment', []) as $file) {
            $task->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $task->id]);
        }

        $index = 0;
        $index_number = substr("00{$index}", -2);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        foreach ($request->input('pdf_attachment', []) as $file) {
            $index++;
            $index_number = substr("00{$index}", -2);
            $inputFile = storage_path('tmp/uploads/' . basename($file));
            $renameFile = storage_path('tmp/uploads/' . 'CSC_ACTIVITY' . $randomString . '_' .  $task->startDate . '_' . $index_number . '.pdf');
            rename($inputFile, $renameFile);
            $outputFile = storage_path('tmp/uploads/' . 'Convert_' . 'CSC_ACTIVITY' . $randomString . '_' .  $task->startDate . '_' . $index_number . '.pdf');
            // Set the Ghostscript command
            $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$outputFile $renameFile";

            // Run the Ghostscript command
            shell_exec($command);

            // Add the converted PDF file to the media collection
            $task->addMedia($outputFile)->toMediaCollection('pdf_attachment');
        }

        return redirect()->route('admin.tasks.index');

    }

    public function edit(Task $task)
    {
        abort_if(Gate::denies('task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = TaskTag::all()->pluck('name', 'id');

        $user = User::with(['construction_contracts'])->where('id', $task->create_by_user->id)->first();

        $user_contracts = [];
        foreach($user->construction_contracts as $contract){
            array_push($user_contracts, $contract->id);
        }

        $construction_contracts = ConstructionContract::whereIn('id', $user_contracts)->pluck('code','id')->prepend(trans('global.pleaseSelect'), '');

        $statuses = TaskStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $task->load('tags', 'status', 'create_by_user', 'construction_contract', 'team');

        return view('admin.tasks.edit', compact('tags', 'statuses', 'task', 'construction_contracts'));
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

        if (count($task->pdf_attachment) > 0) {
            foreach ($task->pdf_attachment as $media) {
                if (! in_array($media->file_name, $request->input('pdf_attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $task->pdf_attachment->pluck('file_name')->toArray();
        $index = 0;
        $index_number = substr("00{$index}", -2);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        foreach ($request->input('pdf_attachment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $index++;
                $index_number = substr("00{$index}", -2);
                $inputFile = storage_path('tmp/uploads/' . basename($file));
                $renameFile = storage_path('tmp/uploads/' . 'CSC_ACTIVITY' . $randomString . '_' .  $task->startDate . '_' . $index_number . '.pdf');
                rename($inputFile, $renameFile);
                $outputFile = storage_path('tmp/uploads/' . 'Convert_' . 'CSC_ACTIVITY' . $randomString . '_' .  $task->startDate . '_' . $index_number . '.pdf');

                // Set the Ghostscript command
                $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$outputFile $renameFile";

                // Run the Ghostscript command
                shell_exec($command);

                // Add the converted PDF file to the media collection
                $task->addMedia($outputFile)->toMediaCollection('pdf_attachment');
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
