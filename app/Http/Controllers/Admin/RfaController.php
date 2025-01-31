<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Illuminate\Support\Facades\Auth;
use DB;
use App\WorksCode;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRfaRequest;
use App\Http\Requests\StoreRfaRequest;
use App\Http\Requests\UpdateRfaRequest;
use App\Http\Requests\RevisionRfaRequest;
use App\Wbslevelfour;
use App\WbsLevelThree;
use App\WbsLevelOne;
use App\Rfa;
use App\RfaCommentStatus;
use App\RfaDocumentStatus;
use App\Rfatype;
use App\User;
use App\UserAlert;
use App\Team;
use App\SubmittalsRfa;
use Carbon\Carbon;
use DateTime;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\BoQ;
use setasign\Fpdi;


class RfaController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('rfa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Rfa::with(['document_status', 'boq', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'create_by_user', 'distribute_by', 'reviewed_by', 'wbs_level_one', 'team'])->select(sprintf('%s.*', (new Rfa())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'rfa_show';
                $editGate = 'rfa_edit';
                $deleteGate = 'rfa_delete';
                $crudRoutePart = 'rfas';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('cover_sheet', function ($row) {
                $cover_sheet = [];
                $rfa_id =  $row->id;
                $cover_sheet[] = '<a style="font-size:12px" class="btn btn-default" href="' . route('admin.rfas.createReportRFA',$row->id) .'" target="_blank">
                            RFA<br>Report </a>';
                return implode(' ', $cover_sheet);
            });

            $table->addColumn('document_status_status_name', function ($row) {
                if ($row->document_status->status_name == 'New')
                    return sprintf('<p style="color:#003399"><b>%s</b></p>',$row->document_status ? $row->document_status->status_name : '');
                else if($row->document_status->status_name == 'Distributed')
                    return sprintf('<p style="color:#ff9900"><b>%s</b></p>',$row->document_status ? $row->document_status->status_name : '');
                else if($row->document_status->status_name == 'Reviewed')
                    return sprintf('<p style="color:#6600cc"><b>%s</b></p>',$row->document_status ? $row->document_status->status_name : '');
                else if($row->document_status->status_name == 'Done')
                    return sprintf('<p style="color:#009933"><b>%s</b></p>',$row->document_status ? $row->document_status->status_name : '');
                else
                    return $row->document_status ? $row->document_status->status_name : '';
            });

            $table->editColumn('file_upload_1', function ($row) {
                if (!$row->file_upload_1) {
                    return '';
                }
                $links = [];
                foreach ($row->file_upload_1 as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('boq_name', function ($row) {
                return $row->boq ? $row->boq->name : '';
            });

            $table->addColumn('boq_sub', function ($row) {
                return $row->boq_sub ? $row->boq_sub->name : '';
            });

            $table->editColumn('title_eng', function ($row) {
                return $row->title_eng ? $row->title_eng : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('title_cn', function ($row) {
                return $row->title_cn ? $row->title_cn : '';
            });
            $table->editColumn('origin_number', function ($row) {
                return $row->origin_number ? $row->origin_number : '';
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });
            $table->editColumn('rfa_code', function ($row) {
                return $row->rfa_code ? $row->rfa_code : '';
            });

            $table->editColumn('review_time', function ($row) {
                return $row->review_time ? $row->review_time : '';
            });
            $table->addColumn('type_type_name', function ($row) {
                return $row->type ? $row->type->type_name : '';
            });

            $table->editColumn('type.type_code', function ($row) {
                return $row->type ? (is_string($row->type) ? $row->type : $row->type->type_code) : '';
            });
            $table->editColumn('worktype', function ($row) {
                return $row->worktype ? Rfa::WORKTYPE_SELECT[$row->worktype] : '';
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('construction_contract.name', function ($row) {
                return $row->construction_contract ? (is_string($row->construction_contract) ? $row->construction_contract : $row->construction_contract->name) : '';
            });
            $table->addColumn('wbs_level_3_wbs_level_3_code', function ($row) {
                return $row->wbs_level_3 ? $row->wbs_level_3->wbs_level_3_code : '';
            });

            $table->editColumn('wbs_level_3.wbs_level_3_name', function ($row) {
                return $row->wbs_level_3 ? (is_string($row->wbs_level_3) ? $row->wbs_level_3 : $row->wbs_level_3->wbs_level_3_name) : '';
            });
            $table->addColumn('wbs_level_4_wbs_level_4_code', function ($row) {
                return $row->wbs_level_4 ? $row->wbs_level_4->wbs_level_4_code : '';
            });

            $table->editColumn('wbs_level_4.wbs_level_4_name', function ($row) {
                return $row->wbs_level_4 ? (is_string($row->wbs_level_4) ? $row->wbs_level_4 : $row->wbs_level_4->wbs_level_4_name) : '';
            });
            $table->addColumn('issueby_name', function ($row) {
                return $row->issueby ? $row->issueby->name : '';
            });

            $table->addColumn('assign_name', function ($row) {
                return $row->assign ? $row->assign->name : '';
            });

            $table->addColumn('action_by_name', function ($row) {
                return $row->action_by ? $row->action_by->name : '';
            });

            $table->addColumn('comment_by_name', function ($row) {
                return $row->comment_by ? $row->comment_by->name : '';
            });

            $table->addColumn('information_by_name', function ($row) {
                return $row->information_by ? $row->information_by->name : '';
            });

            $table->addColumn('comment_status_name', function ($row) {
                return $row->comment_status ? $row->comment_status->name : '';
            });

            $table->addColumn('create_by_user_name', function ($row) {
                return $row->create_by_user ? $row->create_by_user->name : '';
            });

            $table->editColumn('incoming_number', function ($row) {
                return $row->incoming_number ? $row->incoming_number : '';
            });
            $table->editColumn('outgoing_number', function ($row) {
                return $row->outgoing_number ? $row->outgoing_number : '';
            });

            $table->editColumn('commercial_file_upload', function ($row) {
                if (!$row->commercial_file_upload) {
                    return '';
                }
                $links = [];
                foreach ($row->commercial_file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('distribute_by_name', function ($row) {
                return $row->distribute_by ? $row->distribute_by->name : '';
            });

            $table->addColumn('reviewed_by_name', function ($row) {
                return $row->reviewed_by ? $row->reviewed_by->name : '';
            });

            $table->editColumn('submittals_file', function ($row) {
                if (!$row->submittals_file) {
                    return '';
                }
                $links = [];
                foreach ($row->submittals_file as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('wbs_level_one_name', function ($row) {
                return $row->wbs_level_one ? $row->wbs_level_one->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'document_status', 'file_upload_1', 'boq','boq_sub', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'create_by_user', 'commercial_file_upload', 'distribute_by', 'reviewed_by', 'submittals_file', 'wbs_level_one','document_status_status_name','cover_sheet']);

            return $table->make(true);
        }

        $rfa_document_statuses  = RfaDocumentStatus::get();
        $bo_qs                  = BoQ::orderBy('code', 'asc')->get();
        $rfatypes               = Rfatype::get();
        $construction_contracts = ConstructionContract::where('id','!=',15)->get();
        $wbs_level_threes       = WbsLevelThree::orderBy('wbs_level_3_code', 'asc')->get();
        $wbslevelfours          = Wbslevelfour::orderBy('wbs_level_4_code', 'asc')->get();
        $users                  = User::get();
        $rfa_comment_statuses   = RfaCommentStatus::get();
        $wbs_level_ones         = WbsLevelOne::get();
        $teams                  = Team::get();

        return view('admin.rfas.index', compact('rfa_document_statuses', 'bo_qs', 'rfatypes', 'construction_contracts', 'wbs_level_threes', 'wbslevelfours', 'users', 'rfa_comment_statuses', 'wbs_level_ones', 'teams'));
    }

    function fetch(Request $request){
        $id = $request->get('select');
        $result = array();
        $query = DB::table('wbs_level_threes')
        ->join('wbslevelfours','wbs_level_threes.id','=','wbslevelfours.wbs_level_three_id')
        ->select('wbslevelfours.wbs_level_4_name','wbslevelfours.id')
        ->where('wbs_level_threes.id',$id)
        ->groupBy('wbslevelfours.wbs_level_4_name','wbslevelfours.id')
        ->orderBy('wbs_level_4_name')
        ->get();
        $output = '<option value="">' . trans('global.pleaseSelect') . '</option>';
        foreach ($query as $row){
            $output .= '<option value="'. $row->id .'">'. $row->wbs_level_4_name .'</option>';
        }
        echo $output;
    }

    public function revision(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_revision'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $types = Rfatype::all()->pluck('type_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        //Contract Check
            //Check is Admin

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::find([91,202,219,162,196])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::where('id',61)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''); //61->Li, 39->Paisan,  62->Liu

        $action_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $submittalsRfa = SubmittalsRfa::all()->where('on_rfa_id' , $rfa->id);

        $count_submittalsRfa = count($submittalsRfa);

        $review_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reviewed_bies = User::find([39,62])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');



        $rfa->load('type', 'construction_contract', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'reviewed_by', 'team');

        return view('admin.rfas.revision', compact('review_statuses', 'submittalsRfa', 'types', 'construction_contracts', 'wbs_level_3s', 'wbs_level_4s', 'issuebies', 'assigns', 'action_bies', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses', 'rfa', 'reviewed_bies','count_submittalsRfa'));
  }

    public function  storeRevision(RevisionRfaRequest $request, Rfa $rfa)
    {   $data = $request->all();
        $data['create_by_user_id'] = auth()->id();
        $data['document_status_id'] = 1;

        $data['title_eng'] = $request->title_eng;
        $data['title'] = $request->title;
        $data['title_cn'] = $request->title_cn;
        $data['type_id'] = $request->type_id;
        $data['worktype'] = $request->worktype;
        $data['wbs_level_3_id'] = $request->wbs_level_3_id;
        $data['wbs_level_4_id'] = $request->wbs_level_4_id;



        //Review Time
        $rvs_length = 2;
        $review_time = $request->review_time + 1;
        $data['review_time'] = $review_time;
        $revision_number = $review_time;

        //RFA Code
        $rfa_code = $request->rfa_code;
        if(strcmp($request->check_revision,"f")){
            $data['rfa_code'] = $rfa_code . '_R' . $revision_number;
        }else{
            $rfa_code = substr($rfa_code,0,15);
            $data['rfa_code'] = $rfa_code . '_R' . $revision_number;
        }
        //Document Number
        $document_number = $request->document_number;
        if(strcmp($request->check_revision,"f")){
            $data['document_number'] = $document_number . "_R" . $revision_number;
        }
        else{
            $document_number = substr($document_number,0,34);
            $data['document_number'] = $document_number . "_R" . $revision_number;
        }


        $data['check_revision'] = "f";
        $data['rfa_count'] = $request->rfa_count;

        Rfa::where('id', $request->id)->update(['check_revision' => "t"]);

        $rfa = Rfa::create($data);

         // Submittals
        if(count($request->only(['item'])) != 0){
             $item_no = $request->item ?? '';
             $description = $request->des ?? '';
             $qty_sets    = $request->qty ?? '';
             $on_rfa_id = DB::table('rfas')->max('id');
             for($count = 0; $count < count($item_no); $count++){
                 $dataSubmittals = array(
                     'item_no' => $item_no[$count],
                     'description' => $description[$count],
                     'qty_sets' => $qty_sets[$count],
                     'on_rfa_id' => $on_rfa_id
                 );
                 $insert_data[] = $dataSubmittals;
             }
            $submittalsRfa =  SubmittalsRfa::insert($insert_data);
        }

        $count_rfa = DB::table('rfas')->where([['cec_sign', '=', 1], ['cec_stamp', '=', 1], ['document_status_id', '=', 1]])->count();
        if($request->cec_sign == 1 && $request->cec_stamp == 1){
            //Alert Manager
            $data_alert['alert_text'] = 'You have new RFA to Distribute.';
            $data_alert['alert_link'] = route('admin.rfas.index');

            $userAlert = UserAlert::create($data_alert);
            $userAlert->users()->sync($data['assign_id']);
        }

        if (count($rfa->file_upload_1) > 0) {
            foreach ($rfa->file_upload_1 as $media) {
                if (!in_array($media->file_name, $request->input('file_upload_1', []))) {
                    $media->delete();
                }
            }
        }

        $media = $rfa->file_upload_1->pluck('file_name')->toArray();

        foreach ($request->input('file_upload_1', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload_1');
            }
        }

        foreach ($request->input('commercial_file_upload', []) as $file) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('commercial_file_upload');
        }

        foreach ($request->input('document_file_upload', []) as $file) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document_file_upload');
        }

        foreach ($request->input('submittals_file', []) as $file) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('submittals_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $rfa->id]);
        }


        return redirect()->route('admin.rfas.index');
    }


    public function create()
    {
        abort_if(Gate::denies('rfa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Rfatype::all()->pluck('type_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        //Contract Check
            //Check is Admin
            if(Auth::id() != 1){
                $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            else{
                $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::find([202,91,162,196,889])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::find([61, 773])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''); //61->Li,  62->Liu , 461-> Ma, 288->Jiang

        $action_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $document_statuses = RfaDocumentStatus::all()->pluck('status_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reviewed_bies = User::find([39,62])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boqs = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boq_subs = BoQ::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('admin.rfas.create', compact('document_statuses', 'types', 'construction_contracts', 'wbs_level_3s', 'wbs_level_4s', 'issuebies', 'assigns', 'action_bies', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses', 'reviewed_bies', 'boqs', 'boq_subs'));
    }

    public function store(StoreRfaRequest $request)
    {   $data = $request->all();
        $data['create_by_user_id'] = auth()->id();
        $data['document_status_id'] = 1;

            //Works Code
        $workcode_id = ConstructionContract::all()->pluck('works_code_id');
        $workcode = WorksCode::where('id','=',$workcode_id)->value('code');
            //WBS3,4 Code
        $wbs3code = WbsLevelThree::where('id','=',$request->wbs_level_3_id)->value('wbs_level_3_code');
        $wbs4code = Wbslevelfour::where('id','=',$request->wbs_level_4_id)->value('wbs_level_4_code');
            //Type Doc. Code
        $typecode = Rfatype::where('id','=',$request->type_id)->value('type_code');
            //ConstructionContart
        $const_code = ConstructionContract::where('id','=',$request->construction_contract_id)->value('code');
            //Next Number
        if(Rfa::orderBy('id', 'desc')->value('rfa_count') < 1300){
            $previousId = 1300;
        }
        else{
            $previousId = Rfa::orderBy('id', 'desc')->value('rfa_count');
        }
        $nextId = $previousId + 1;

        $data['clause'] = $data['clause'] . '.';

        $str_length = 4;
        $doc_number = substr("0000{$nextId}", -$str_length);
        // $cur_date = date("ymd");
        // $code_year = substr($cur_date,0,2);
        // $code_mouth = substr($cur_date,2,2);
        // $code_date = $code_year . "-" . $code_mouth;

        $submit_date = $data['submit_date'];
        $date_replace = str_replace("/","",$submit_date);
        $code_year = substr($date_replace,-2);
        $code_mouth = substr($date_replace,2,2);
        $code_date = $code_year . "-" . $code_mouth;

        if($request->origin_number == ''){
            $data['rfa_count'] = $nextId;
            //RFA Code
            $data['rfa_code'] = 'RFA' . '/' . $const_code . '/' .  $doc_number;
            // Document Number
//            $data['document_number'] = 'HSR1/' . $const_code . $workcode  . '/' . $wbs3code . '/' . $wbs4code . '/' . $typecode . '/' . $code_date . '/' . $doc_number;
            $data['document_number'] = 'HSR1/' . $const_code . '/' . $wbs3code . '/' . $wbs4code . '/' . $typecode . '/' . $code_date . '/' . $doc_number;

        }
        else{
            $data['rfa_code'] =  'RFA' . '/' . $const_code . '/' . substr($data['origin_number'],4);
            $data['origin_number'] = $request->origin_number;
            $data['document_number'] = 'HSR1/' . $const_code . '/' . $wbs3code . '/' . $wbs4code . '/' . $typecode . '/' . $code_date . '/' . $request->origin_number;
        }

        //Review Time
        $data['review_time'] = 0;
        //Revision Check
        $data['check_revision'] = "f";



        $rfa = Rfa::create($data);

        // Submittals
        if(count($request->only(['item'])) != 0){
            $item_no = $request->item;
            $description = $request->des;
            $qty_sets    = $request->qty;
            $on_rfa_id = DB::table('rfas')->max('id');
            for($count = 0; $count < count($item_no); $count++){
                $dataSubmittals = array(
                    'item_no' => $item_no[$count],
                    'description' => $description[$count],
                    'qty_sets' => $qty_sets[$count],
                    'on_rfa_id' => $on_rfa_id
                );
                $insert_data[] = $dataSubmittals;
            }
        $submittalsRfa =  SubmittalsRfa::insert($insert_data);
        }

        // $count_rfa = DB::table('rfas')->where([['cec_sign', '=', 1], ['cec_stamp', '=', 1], ['document_status_id', '=', 1]])->count();
        // if($request->cec_sign == 1 && $request->cec_stamp == 1){
        //     //Alert Manager
        //     $data_alert['alert_text'] = 'You have new RFA to Distribute.';
        //     $data_alert['alert_link'] = route('admin.rfas.index');
        //     $data_user_id = array($data['assign_id']);

        //     $userAlert = UserAlert::create($data_alert);
        //     $userAlert->users()->sync($data_user_id);

        //     //Alert Admin Pook
        //     $data_alert['alert_text'] = 'You have new RFA.';
        //     $data_alert['alert_link'] = route('admin.rfas.index');

        //     $userAlert = UserAlert::create($data_alert);
        //     $userAlert->users()->sync(11);

        //     //Alert Admin
        //     $data_alert['alert_text'] = 'You have new RFA.';
        //     $data_alert['alert_link'] = route('admin.rfas.index');

        //     $userAlert = UserAlert::create($data_alert);
        //     $userAlert->users()->sync(56);

        // }


        foreach ($request->input('file_upload_1', []) as $file) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload_1');
        }

        // foreach ($request->input('commercial_file_upload', []) as $file) {
        //     $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('commercial_file_upload');
        // }

        // foreach ($request->input('document_file_upload', []) as $file) {
        //     $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document_file_upload');
        // }

        foreach ($request->input('submittals_file', []) as $file) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('submittals_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $rfa->id]);
        }


        return redirect()->route('admin.rfas.index');
    }

    public function edit(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //Contract Check
            //Check is Admin
        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        $types = Rfatype::all()->pluck('type_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::find([202,91,219,162,196])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::find([61, 773])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $action_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $document_statuses = RfaDocumentStatus::pluck('status_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reviewed_bies = User::find([39,62])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boqs = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boq_subs = BoQ::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // if($rfa->document_status_id == 1){
        //     $action_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        //     $comment_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        //     $information_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        //     $rfa->load('action_by', 'comment_by', 'information_by');

        //     return view('admin.rfas.edit', compact('construction_contracts', 'action_bies', 'comment_bies', 'information_bies', 'rfa'));
        // }
        // else if($rfa->document_status_id == 2){
        //     $action_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        //     $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        //     $rfa->load('action_by');
        //     return view('admin.rfas.edit', compact('construction_contracts', 'action_bies', 'comment_statuses', 'rfa'));
        // }
        // else if($rfa->document_status_id == 3 || $rfa->document_status_id == 4){
        //     $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        //     $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        //     $reviewed_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        //     return view('admin.rfas.edit', compact('construction_contracts', 'for_statuses','comment_statuses', 'reviewed_bies', 'rfa'));
        // }

        $rfa->load('document_status', 'boq', 'boq_sub', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'create_by_user', 'distribute_by', 'reviewed_by', 'wbs_level_one', 'team');

        return view('admin.rfas.edit', compact('action_bies', 'assigns', 'boq_subs', 'boqs', 'comment_bies', 'comment_statuses', 'construction_contracts', 'document_statuses', 'for_statuses', 'information_bies', 'issuebies', 'reviewed_bies', 'rfa', 'types', 'wbs_level_3s', 'wbs_level_4s'));
    }

    public function update(UpdateRfaRequest $request, Rfa $rfa)
    {
        $state = $request->document_status_id;
        $data = $request->all();
        // $data['wbs_level_3_id'] = $request->wbs_level_3_id;
        // $data['wbs_level_4_id'] = $request->wbs_level_4_id;
        $data['spec_ref_no'] = $request->spec_ref_no;
        $data['clause'] = $request->clause;
        $data['contract_drawing_no'] = $request->contract_drawing_no;
        $data['qty_page'] = $request->qty_page;
        $data['note_1'] = $request->note_1;
        $data['assign_id'] = $request->assign_id;

        $data['update_by_user_id'] = auth()->id();
        $data['action_by_id'] = $request->action_by_id;
        $data['comment_by_id'] = $request->comment_by_id;
        $data['information_by_id'] = $request->information_by_id;
        $data['receive_date'] = $request->receive_date;
        $data['note_2'] = $request->note_2;
        $data['target_date'] = $request->target_date;
        $data['incoming_number'] = "IN-" . $rfa->origin_number;
        $data['distribute_by_id'] = Auth::id();
        $distribute_date = new DateTime();
        $data['distribute_date'] = $distribute_date->format('d/m/Y');
        $process_date = new DateTime();
        $data['process_date'] = $process_date->format('d/m/Y');
        $data['comment_status_id'] = $request->comment_status_id;
        $data['note_3'] = $request->note_3;
        $outgoing_date = new DateTime();
        $data['outgoing_date'] = $outgoing_date->format('d/m/Y');
        $data['outgoing_number'] = "OUT-" . $rfa->rfa_code;
        $data['reviewed_by_id'] = $request->reviewed_by_id;
        // $data['for_status_id'] = $request->for_status_id;
        $data['note_4'] = $request->note_4;

        if($data['comment_status_id']){
            $data['document_status_id'] = 4;
        }

        if($data['action_by_id'] != "" && $data['comment_status_id'] == ""){
            $data['document_status_id'] = 2;
        }

        $str_length = 4;
        $parts = explode('/', $rfa->document_number);
        $doc_number = end($parts);
        $const_code = ConstructionContract::where('id','=',$rfa->construction_contract_id)->value('code');

        //WBS3,4 Code
        if($rfa->isDirty('wbs_level_3_id')){
            $data['wbs_level_3_id'] = $rfa->wbs_level_3_id;
            $wbs3code = WbsLevelThree::where('id','=',$rfa->wbs_level_3_id)->value('wbs_level_3_code');
        }
        else{
            $data['wbs_level_3_id'] = $request->wbs_level_3_id;
            $wbs3code = WbsLevelThree::where('id','=',$request->wbs_level_3_id)->value('wbs_level_3_code');
        }


        $data['wbs_level_4_id'] = $rfa->wbs_level_4_id;
        $wbs4code = Wbslevelfour::where('id','=',$rfa->wbs_level_4_id)->value('wbs_level_4_code');


        if(!$rfa->isDirty('wbs_level_4_id')){
            $data['wbs_level_4_id'] = $request->wbs_level_4_id;
            $wbs4code = Wbslevelfour::where('id','=',$request->wbs_level_4_id)->value('wbs_level_4_code');
        }


        //Type Doc. Code
        $typecode = Rfatype::where('id','=',$request->type_id)->value('type_code');

        // $cur_date = date("ymd");
        // $code_year = substr($cur_date,0,2);
        // $code_mouth = substr($cur_date,2,2);
        // $code_date = $code_year . "-" . $code_mouth;

        $submit_date = $rfa->submit_date;
        $date_replace = str_replace("/","",$submit_date);
        $code_year = substr($date_replace,-2);
        $code_mouth = substr($date_replace,2,2);
        $code_date = $code_year . "-" . $code_mouth;

        if($request->origin_number == ''){
            $data['rfa_count'] = $doc_number;
            //RFA Code
            $data['rfa_code'] = 'RFA' . '/' . $const_code . '/' .  $doc_number;
            // Document Number
//            $data['document_number'] = 'HSR1/' . $const_code . $workcode  . '/' . $wbs3code . '/' . $wbs4code . '/' . $typecode . '/' . $code_date . '/' . $doc_number;
            $data['document_number'] = 'HSR1/' . $const_code . '/' . $wbs3code . '/' . $wbs4code . '/' . $typecode . '/' . $code_date . '/' . $doc_number;

        }
        else{
            $data['rfa_code'] =  'RFA' . '/' . $const_code . '/' . substr($data['origin_number'],4);
            $data['origin_number'] = $request->origin_number;
            $data['document_number'] = 'HSR1/' . $const_code . '/' . $wbs3code . '/' . $wbs4code . '/' . $typecode . '/' . $code_date . '/' . $request->origin_number;
        }



        $rfa->update($data);
        if (count($rfa->file_upload_1) > 0) {
            foreach ($rfa->file_upload_1 as $media) {
                if (!in_array($media->file_name, $request->input('file_upload_1', []))) {
                    $media->delete();
                }
            }
        }
        $media = $rfa->file_upload_1->pluck('file_name')->toArray();
        foreach ($request->input('file_upload_1', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $rfa->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload_1');
            }
        }

        if (count($rfa->commercial_file_upload) > 0) {
            foreach ($rfa->commercial_file_upload as $media) {
                if (!in_array($media->file_name, $request->input('commercial_file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $rfa->commercial_file_upload->pluck('file_name')->toArray();
        foreach ($request->input('commercial_file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $rfa->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('commercial_file_upload');
            }
        }

        if (count($rfa->document_file_upload) > 0) {
            foreach ($rfa->document_file_upload as $media) {
                if (!in_array($media->file_name, $request->input('document_file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $rfa->document_file_upload->pluck('file_name')->toArray();
        foreach ($request->input('document_file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $rfa->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('document_file_upload');
            }
        }

        if (count($rfa->submittals_file) > 0) {
            foreach ($rfa->submittals_file as $media) {
                if (!in_array($media->file_name, $request->input('submittals_file', []))) {
                    $media->delete();
                }
            }
        }
        $media = $rfa->submittals_file->pluck('file_name')->toArray();
        foreach ($request->input('submittals_file', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $rfa->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('submittals_file');
            }
        }

        if (count($rfa->work_file_upload) > 0) {
            foreach ($rfa->work_file_upload as $media) {
                if (!in_array($media->file_name, $request->input('work_file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $rfa->work_file_upload->pluck('file_name')->toArray();
        foreach ($request->input('work_file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $rfa->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('work_file_upload');
            }
        }
        return redirect()->route('admin.rfas.index');
    }

    public function show(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $submittalsRfa = SubmittalsRfa::all()->where('on_rfa_id' , $rfa->id);

        $rfa->load('type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'team','onRfaSubmittalsRfas', 'reviewed_by','distribute_by');

        return view('admin.rfas.show', compact('rfa','submittalsRfa'));
    }

    public function destroy(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfa->delete();

        return back();
    }

    public function massDestroy(MassDestroyRfaRequest $request)
    {
        Rfa::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('rfa_create') && Gate::denies('rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Rfa();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media', 'public');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function createReportRFA(Rfa $rfa)
    {
       $submittalsRfa = SubmittalsRfa::all()->where('on_rfa_id' , $rfa->id);

        $rfa->load('type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'team','onRfaSubmittalsRfas', 'reviewed_by','distribute_by','update_by_user');

        //Varible setting
        $stamp_position_top = 380;
        $stamp_position_left = 600;

        $signature_size_h = 60;
        $signature_size_w = 60;

        $multi_signature = false;
        $multi_stamp = false;

        if($rfa->construction_contract->code == "C2-1"){
            $issue_by = '( Sitthichai Pimsawat )';
            $issuer_jobtitle = 'ผู้จัดการโครงการ';
            $issue_position_lf = 255;
            $issue_position_lf_sub = 483;
            $jobittle_position_lf = 275;
            $constructor_name = 'Civil Construction Services & Products Company Limited';
            $constructor_code = 'CCSP';
            $logo_path =  public_path('png-asset/Stamp_CEC.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/Stamp_CEC.png');
            $signature_path =  public_path('png-asset/Signature_CEC.png');
            $signature_size_h = 40;
            $signature_size_w = 40;
            $signature_position_top = 425;
            $signature_position_left = 280;
            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;
        }


        if($rfa->construction_contract->code == "C3-1"){
            $multi_signature = true;
            $multi_stamp = true;
            $issue_by = '(นายศรายุธ ทองยศ) และ (Mr.Luan Zhiqiang)';
            $signature_path_2 =  public_path('png-asset/ITDCREC_signature_2-2.png');
            $signature_size_h_2 = 40;
            $signature_size_w_2 = 40;

            if($rfa->id < 102866) {
                $issue_by = '(นายศรายุธ ทองยศ) และ (Mr.Yu Dongxin)';
                $signature_path_2 =  public_path('png-asset/ITDCREC_signature_2.png');
                $signature_size_h_2 = 80;
                $signature_size_w_2 = 80;

            }

            $issuer_jobtitle = 'ผู้รับมอบอำนาจกระทำการแทน (Authorized Representatives) ITD-CREC No.10 Joint Venture';
            $issue_position_lf = 215;
            $issue_position_lf_sub = 485;
            $jobittle_position_lf = 165;
            $constructor_name = 'ITD-CREC No.10 Joint Venture';
            $constructor_code = 'ITD-CREC No.10 Joint Venture';
            $logo_path =  public_path('png-asset/ITD-CREC_logo.jpg');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 700;

            $stamp_path =  public_path('png-asset/ITDCREC_stamp_1.png');
            $stamp_path_2 =  public_path('png-asset/ITDCREC_stamp_2.png');

            $signature_path =  public_path('png-asset/ITDCREC_signature_1.png');

            $signature_size_h = 55;
            $signature_size_w = 55;



            $signature_position_top = 415;
            $signature_position_left = 220;

            $signature_position_top_2 = 425;
            $signature_position_left_2 = 290;

            $stamp_position_top = 380;
            $stamp_position_left = 450;

            $stamp_position_top_2 = 395;
            $stamp_position_left_2 = 550;

            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }


        if($rfa->construction_contract->code == "C3-2"){
            $issue_by = '( ธนนท์ ดอกลัดดา )';
            $issuer_jobtitle = 'ผู้จัดการโครงการ';
            $issue_position_lf = 260;
            $issue_position_lf_sub = 485;
            $jobittle_position_lf = 275;
            $constructor_name = 'Nawarat Patanakarn Public Company Limited';
            $constructor_code = 'NWR';
            $logo_path =  public_path('png-asset/NWR_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/NWR_stamp.png');
            $signature_path =  public_path('png-asset/NWR_signature.png');
            $signature_size_h = 55;
            $signature_size_w = 55;
            $signature_position_top = 415;
            $signature_position_left = 280;
            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C3-3"){
            $issue_by = '( กิตติพัฒน์ พฤกษ์ธนัทพงศ์ )';
            $issuer_jobtitle = 'รองผู้จัดการโครงการ';
            $issue_position_lf = 247;
            $issue_position_lf_sub = 480;
            $jobittle_position_lf = 275;
            $constructor_name = 'Thai Engineers & Industry Company Limited';
            $constructor_code = 'TEI';
            $logo_path = public_path('png-asset/TEI_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/TEI_stamp.png');
            $signature_path =  public_path('png-asset/TEI_signature.png');
            $signature_size_h = 60;
            $signature_size_w = 60;
            $signature_position_top = 415;
            $signature_position_left = 280;
            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C3-4"){
            $date_1 = new DateTime('2023-02-07');
            if($rfa->created_at > $date_1) {
                $issue_by = '( สุดเขต ไวทยาคม )';
                $issuer_jobtitle = 'ผู้อำนวยการโครงการ';
                $signature_path =  public_path('png-asset/ITD-3_signature.png');
                $signature_size_h = 100;
                $signature_size_w = 100;
                $issue_position_lf = 265;
                $issue_position_lf_sub = 480;
                $signature_position_top = 423;
                $signature_position_left = 250;
            }else {
                $issue_by = '( มฆา  อัศวราชันย์ )';
                $issuer_jobtitle = 'ผู้อำนวยการโครงการ';
                $signature_path =  public_path('png-asset/ITD_signature.png');
                $issue_position_lf = 260;
                $issue_position_lf_sub = 489;
                $signature_position_top = 409;
                $signature_position_left = 280;
            }

            $jobittle_position_lf = 275;

            $constructor_name = 'Italian-Thai Development PCL.';
            $constructor_code = 'ITD';
            $logo_path = public_path('png-asset/ITD_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/ITD_stamp.png');
            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C3-5"){
            $issuer_jobtitle = 'ผู้จัดการโครงการ';

            if($rfa->id < 80042) {
                $issue_by = '( MR.ZHANG DAPENG )';
                $signature_path =  public_path('png-asset/SPTK_signature.png');
                $signature_size_h = 60;
                $signature_size_w = 60;
                $signature_position_top = 425;
                $signature_position_left = 265;

            }
            else {
                // $issue_by = '( Mr.Qi Long )';
                $issue_by = '( นายนรุตม์ชัย  สัมมาวิจิตร )';
                $issuer_jobtitle = 'ผู้อำนวยการโครงการ';
                // $signature_path =  public_path('png-asset/SPTK_signature_2.png');
                $signature_path =  public_path('png-asset/SPTK_signature_3.png');

                $signature_size_h = 60;
                $signature_size_w = 60;
                $signature_position_top = 425;
                $signature_position_left = 265;
            }
            $issue_position_lf = 245;
            $issue_position_lf_sub = 480;
            $jobittle_position_lf = 275;
            $constructor_name = 'SPTK Joint Venture Company Limited';
            $constructor_code = 'SPTK';
            $logo_path =  public_path('png-asset/SPTK_stamp.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/SPTK_stamp.png');
            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C4-3"){
            $issue_by = '( Dong Weihong )';
            $issuer_jobtitle = 'For Project manager CAN';
            $signature_path = public_path('png-asset/CAN_signature.png');
            $jobittle_position_lf = 275;

             if ( $rfa->id <= 97608){
                $issue_by = '( Zhou Jia Yi )';
                $issuer_jobtitle = 'Project Manager';
                $signature_path = public_path('png-asset/CAN_signature_2.png');
                $jobittle_position_lf = 275;
            }

            if ($rfa->id > 76608 and $rfa->id < 77826 ){
                $issue_by = '( Wang Kan )';
                $issuer_jobtitle = 'For Project Manager';
                $signature_path = public_path('png-asset/CAN_signature_3.png');
                $jobittle_position_lf = 275;
            }

            if ($rfa->id > 97608 and $rfa->id <= 99346){
                $issue_by = '( Gao Liang )';
                $issuer_jobtitle = 'For Project Manager';
                $signature_path = public_path('png-asset/CAN_signature_4.png');
                $jobittle_position_lf = 275;
            }

            if($rfa->id > 99346){
                $issue_by = '( Zhou Jia Yi )';
                $issuer_jobtitle = 'Project Manager';
                $signature_path = public_path('png-asset/CAN_signature_2.png');
                $jobittle_position_lf = 275;
            }

            $issue_position_lf = 260;
            $issue_position_lf_sub = 489;
            $constructor_name = 'CAN Joint Venture';
            $constructor_code = 'CAN';
            $logo_path = public_path('png-asset/CAN_logo.png');
            $logo_h = 60;
            $logo_w = 60;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/CAN_stamp.png');
            $signature_size_h = 60;
            $signature_size_w = 60;
            $signature_position_top = 409;
            $signature_position_left = 260;
            $stamp_size_h = 160;
            $stamp_size_w = 160;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C4-4"){
            $issue_by = '( พิเชฐ  ภาพปัญญาพร )';
            $issuer_jobtitle = 'ผู้อำนวยการโครงการ';
            $issue_position_lf = 260;
            $issue_position_lf_sub = 489;
            $jobittle_position_lf = 275;
            $constructor_name = 'Italian-Thai Development PCL.';
            $constructor_code = 'ITD';
            $logo_path = public_path('png-asset/ITD_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/ITD_stamp.png');
            $signature_path = public_path('png-asset/ITD2_signature.png');;
            $signature_size_h = 60;
            $signature_size_w = 60;
            $signature_position_top = 409;
            $signature_position_left = 280;
            $stamp_position_top = 380;
            $stamp_position_left = 350;
            $stamp_size_h = 110;
            $stamp_size_w = 110;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C4-2"){
            $issue_by = '( นายธีรพงษ์ วิธีเจริญ )';
            $issuer_jobtitle = 'ผู้รับมอบอำนาจ';
            $issue_position_lf = 255;
            $issue_position_lf_sub = 489;
            $jobittle_position_lf = 275;
            $constructor_name = 'Unique Engineering and Construction Public Company Limited';
            $constructor_code = 'UNIQUE';
            $logo_path = public_path('png-asset/UNIQUE_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  '';
            $signature_path =  '';
            $signature_size_h = 50;
            $signature_size_w = 50;
            $signature_position_top = 415;
            $signature_position_left = 278;
            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C4-6"){
            $issue_by = '( นายธีรพงษ์ วิธีเจริญ )';
            $issuer_jobtitle = 'ผู้รับมอบอำนาจ';
            $issue_position_lf = 255;
            $issue_position_lf_sub = 489;
            $jobittle_position_lf = 275;
            $constructor_name = 'Unique Engineering and Construction Public Company Limited';
            $constructor_code = 'UNIQUE';
            $logo_path = public_path('png-asset/UNIQUE_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 109;
            $logo_left = 690;
            $stamp_path =  '';
            $signature_path =  '';
            $signature_size_h = 50;
            $signature_size_w = 50;
            $signature_position_top = 415;
            $signature_position_left = 278;
            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C4-7"){
            $issue_by = '( สุทิน สังข์หิรัญ )';
            $issuer_jobtitle = 'ผู้จัดการโครงการ';
            $issue_position_lf = 265;
            $issue_position_lf_sub = 423;
            $jobittle_position_lf = 275;
            $constructor_name = 'Civil Enginneering Public Company Limited';
            $constructor_code = 'CIVIL';
            $logo_path = public_path('png-asset/CIVIL_logo.png');
            $logo_h = 90;
            $logo_w = 90;
            $logo_top = 113;
            $logo_left = 630;
            $stamp_path =  public_path('png-asset/CIVIL_stamp.png');
            $signature_path =  public_path('png-asset/CIVIL_signature.png');
            $signature_size_h = 50;
            $signature_size_w = 50;
            $signature_position_top = 415;
            $signature_position_left = 278;
            $stamp_position_top = 295;
            $stamp_position_left = 500;
            $stamp_size_h = 120;
            $stamp_size_w = 120;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }




        $bill = $rfa->boq->name ?? '';
        $bill_sub = $rfa->boq_sub->name ?? '';

        $title_th = $rfa->title ?? '';
        $title_en = $rfa->title_eng ?? '';
        $document_number = $rfa->document_number ?? '';
        $check_box = "X";
        $rfa_code = str_replace("RFA-", "", $rfa->rfa_code);
        if(strlen($rfa_code) > 15){
            $revision_count = substr($rfa_code,15,2);
        }
        else{
            $revision_count = "0";
        }

        $incoming_no = $rfa->incoming_number ?? '';
        $receive_date = $rfa->receive_date ?? '';
        $spec_ref_no = $rfa->spec_ref_no ?? '';
        $clause = $rfa->clause ?? '' . ".";
        $contract_drawing_no = $rfa->contract_drawing_no ?? '';
        $qty_page = $rfa->qty_page ?? '';



        $submit_date = $rfa->submit_date ?? '';

        $assign_to = $rfa->assign->name ?? '';

        $distribute_date = $rfa->distribute_date ?? '';
        $done_date = $rfa->outgoing_date ?? '';

        $action_by = $rfa->action_by->name ?? '';
        $process_date = $rfa->process_date ?? '';

        $reviewed_by = $rfa->reviewed_by->name ?? '';
        $outgoing_number = $rfa->outgoing_number ?? '';
        $outgoing_date = $rfa->outgoing_date ?? '';

        $purpose_for = $rfa->purpose_for ??  '';

        $submittalsRfa = $submittalsRfa ?? '';

        $document_name = $rfa->attach_file_name;
        // $note_1 = wordwrap(htmlspecialchars($rfa->note_1, ENT_QUOTES) ?? '',500,"<br>\n");
        if($rfa->note_1 == "<p>&nbsp;</p>"){
            $str_note1 = '';
        }
        else {
            $str_note1 = $rfa->note_1;
        }
        $str_note1 = str_replace("<p>","",$str_note1);
        $str_note1 = str_replace("</p>","",$str_note1);

        $note_1 = htmlspecialchars($str_note1, ENT_QUOTES) ?? '';

        // $note_2 = wordwrap($rfa->note_2 ?? '',400,"<br>\n") ;
        $note_2 = htmlspecialchars($rfa->note_2, ENT_QUOTES);
        // $note_3 = wordwrap($rfa->note_3 ?? '',400,"<br>\n");
        $note_3 = htmlspecialchars($rfa->note_3, ENT_QUOTES);
        // $note_4 = wordwrap($rfa->note_4 ?? '',400,"<br>\n");
        $note_4 = htmlspecialchars($rfa->note_4, ENT_QUOTES);


        $wbslv1 = "HSR1";
        $wbslv2 = $rfa->construction_contract->code ?? '';
        $wbslv3 = $rfa->wbs_level_3->wbs_level_3_name ?? '';
        $wbslv4 = $rfa->wbs_level_4->wbs_level_4_name ?? '';
        $wbslv3_code = $rfa->wbs_level_3->wbs_level_3_code ?? '';
        $wbslv4_code = $rfa->wbs_level_4->wbs_level_4_code ?? '';

        $wbs = '';
        if($wbslv3_code != ''){
            if($rfa->wbs_level_4->wbs_level_4_name ?? '' != ''){
                $wbs = '1.' . $wbslv3 . ' 2.' . $wbslv4;
            }else{
                $wbs = '1.' . $wbslv3;
            }
        }
        $type = $rfa->type->type_code ?? '';
        $num_doc = substr($rfa_code,9,4);

        $receive_by  = $rfa->update_by_user->name ?? '';
        $receive_date = $rfa->receive_date ?? '';

        $distribute_date = $rfa->distribute_date ?? '' ;
        $done_date = $rfa->outgoing_date ?? '' ;

        $action_by = $rfa->action_by->name ?? '' ;
        $process_date = $rfa->process_date ?? '' ;

        $reviewed_by = $rfa->reviewed_by->name ?? '' ;
        $outgoing_number = $rfa->outgoing_number ?? '' ;
        $outgoing_date = $rfa->outgoing_date ?? '' ;

        $for_statuses = $rfa->for_status->id ?? '';
        $comment_status = $$rfa->comment_status->id ?? '';
        // try {
        //     $mpdf = new \Mpdf\Mpdf([
        //         'tempDir' =>  public_path('tmp'),
        //         // 'default_font' => 'sarabun_new',
        //         'mode' => '+aCJK',
        //         "autoScriptToLang" => true,
        //         "autoLangToFont" => true,
        //         "allow_charset_conversion" => true,
        //         "charset_in" => 'UTF-8',

        //     ]);

        //   } catch (\Mpdf\MpdfException $e) {
        //       print "Creating an mPDF object failed with" . $e->getMessage();
        //   }
                //PDF Setting
    try {
        $mpdf = new \Mpdf\Mpdf([
            // 'fontDir' => public_path('tmp/mpdf/ttfontdata') ,
            // 'fontDir' =>  public_path('tmp'),
            'fontdata'     => [
                'sarabun_new' => [
                    'R' => 'THSarabunNew.ttf',
                    'B' => 'THSarabunNew Bold.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                ],
            ],
            'default_font' => 'sarabun_new',
            // 'mode' => '+aCJK',
            // "autoScriptToLang" => true,
            // "autoLangToFont" => true,
            // "allow_charset_conversion" => true,
            // "charset_in" => 'UTF-8',
        ]);
        } catch (\Mpdf\MpdfException $e) {
            print "Creating an mPDF object failed with" . $e->getMessage();
        }
        //RFA Page
        $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/RFA-Form_empty_V.9.pdf'));
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        //Header
        $html = "<div style=\"font-size: 13px; font-weight: bold; position:absolute;top:84px;left:320px;\">" . $contract_name . "</div>";
            //Logo

        $html .= "<div style=\"font-size: 14px; position:absolute;top:". $logo_top ."px;left:". $logo_left ."px;\">
                    <img src=\"". $logo_path ."\" width=\"". $logo_w ."px\" higth=\"". $logo_h ."px\"> </div>";

        $html .= "<div style=\"font-size: 13px; position:absolute;top:120px;left:580px;\">" . $constructor_code . '.' . "</div>";
        $html .= "<div style=\"font-size: 13px; position:absolute;top:140px;left:508px;\">" . $constructor_name . '.' . "</div>";

        //Title
        $html .= "<div style=\"font-size: 12px; position:absolute;top:168px;left:55px;\">" . 'Bill :' . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:170px;left:80px;\">" . $bill . ', ' . $bill_sub . "</div>";

        $html .= "<div style=\"font-size: 12px; position:absolute;top:184px;left:55px;\">" . 'Title :' . "</div>";
        if(strlen($title_en) > 210){
            $html .= "<div style=\"font-size: 9px; padding-right:240px; position:absolute;top:184px;left:80px; LINE-HEIGHT:16px;\">"
            . $title_en . "</div>";
        }
        else{
            $html .= "<div style=\"font-size: 12px; padding-right:240px; position:absolute;top:184px;left:80px; LINE-HEIGHT:16px;\">"
            . $title_en . "</div>";
        }

        $html .= "<div style=\"font-size: 12px; position:absolute;top:235px;left:55px;\">" . 'หัวข้อ :' . "</div>";
        if(strlen($title_th) > 235){
            $html .= "<div style=\"font-size: 9px; padding-right:230px; position:absolute;top:235px;left:80px; LINE-HEIGHT:14px;\">"
            . $title_th . "</div>";
        }
        else{
            $html .= "<div style=\"font-size: 12px; padding-right:230px; position:absolute;top:235px;left:80px; LINE-HEIGHT:14px;\">"
            . $title_th . "</div>";
        }
        //No. Code.
        $html .= "<div style=\"font-size: 12px; position:absolute;top:30px;left:650px;\">" . $rfa_code . "</div>";
        $html .= "<div style=\"font-size: 10px; position:absolute;top:170px;left:477px;\">" . $document_number . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:170px;left:660px;\">" . $rfa_code . "</div>";
          //Date
        $html .= "<div style=\"font-size: 12px; position:absolute;top:217px;left:630px;\">" . $submit_date . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:238px;left:612px;font-weight: bold;\">" . "ผู้จัดการโครงการ/Project Manage (CSC)" . "</div>";

          //Document Name
        $document_name_fontsize = "10px";
        if(strlen($document_name) > 300) {
            $document_name_fontsize = "9px";
        }
        $html .= "<div style=\"font-size: " . $document_name_fontsize  . "; padding-right:230px; position:absolute;top:273px;left:163px;LINE-HEIGHT:15px;\">" . $document_name . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:265;left:630;\">" . $qty_page . '.' . "</div>";

          //WBS Spec.Ref Clase. Contract No.
        $html .= "<div style=\"font-size: 12px; padding-right:230px; position:absolute;top:318px;left:215px;LINE-HEIGHT:15px;\">" . $wbs . "</div>";
        $html .= "<div style=\"font-size: 12px; padding-right:60px; position:absolute;top:346px;left:235px;\">" . $spec_ref_no . "</div>";
        $html .= "<div style=\"font-size: 10px; padding-right:60px; position:absolute;top:289px;left:615px;LINE-HEIGHT:15px;\">" . $clause . "</div>";
        $html .= "<div style=\"font-size: 10px;  padding-right:60px; position:absolute;top:376px;left:210px;LINE-HEIGHT:16px;\">" . $contract_drawing_no . "</div>";
          //Note
        if(strlen($note_1) > 300) {
            $font_size = 10;
        }
        else {
            $font_size = 12;
        }
        $html .= "<div style=\"font-size:". $font_size ."px; padding-right:60px; position:absolute;top:410px;left:120px;LINE-HEIGHT:15px;\">"
        . $note_1 . "</div>";


        $html .= "<div style=\"font-size: 14px; position:absolute;top:453px;left:". $issue_position_lf ."px;\">" . $issue_by . "</div>";
        if($issuer_jobtitle == 'ผู้จัดการโครงการ'){
            $html .= "<div style=\"font-size: 10px; position:absolute;top:467px;left:280px\">" . $issuer_jobtitle . "</div>";
        }
        else{
            $html .= "<div style=\"font-size: 10px; position:absolute;top:467px;left:" . $jobittle_position_lf . "px\">" . $issuer_jobtitle . "</div>";
        }

        if($multi_signature){
             if($signature_path != ''){
                $html .= "<div style=\"font-size: 14px; position:absolute;top:" . $signature_position_top  ."px;left:". $signature_position_left ."px;\">
                    <img src=\"". $signature_path ."\" width=\"". $signature_size_w ."\" higth=\"". $signature_size_h ."\"> </div>";

             }
              if($signature_path_2 != ''){
                $html .= "<div style=\"font-size: 14px; position:absolute;top:" . $signature_position_top_2  ."px;left:". $signature_position_left_2 ."px;\">
                    <img src=\"". $signature_path_2 ."\" width=\"". $signature_size_w_2 ."\" higth=\"". $signature_size_h_2 ."\"> </div>";

              }
        }
        else{
             //Signature Manager
            if($signature_path != ''){
                $html .= "<div style=\"font-size: 14px; position:absolute;top:" . $signature_position_top  ."px;left:". $signature_position_left ."px;\">
                    <img src=\"". $signature_path ."\" width=\"". $signature_size_w ."\" higth=\"". $signature_size_h ."\"> </div>";
            }
        }

        if($multi_stamp){
            if($stamp_path != ''){
                $html .= "<div style=\"font-size: 14px; position:absolute;top:" . $stamp_position_top . "px;left:". $stamp_position_left ."px;\">
                    <img src=\"". $stamp_path ."\" width=\"". $stamp_size_w ."\" higth=\"". $stamp_size_h ."\" style=\"opacity: 0.8;\"> </div>";
            }
            if($stamp_path_2 != ''){
                $html .= "<div style=\"font-size: 14px; position:absolute;top:" . $stamp_position_top_2 . "px;left:". $stamp_position_left_2 ."px;\">
                    <img src=\"". $stamp_path_2 ."\" width=\"". $stamp_size_w ."\" higth=\"". $stamp_size_h ."\" style=\"opacity: 0.8;\"> </div>";
            }

        }
        else{
            //Stamp Organize
            if($stamp_path != ''){
                $html .= "<div style=\"font-size: 14px; position:absolute;top:" . $stamp_position_top . "px;left:". $stamp_position_left ."px;\">
                    <img src=\"". $stamp_path ."\" width=\"". $stamp_size_w ."\" higth=\"". $stamp_size_h ."\" style=\"opacity: 0.8;\"> </div>";
            }
        }

        //CSC Incoming
        $html .= "<div style=\"font-size: 13px; position:absolute;top:486px;left:377;\">ผู้รับ/Receiver :</div>";
        // $html .= "<div style=\"font-size: 14px; position:absolute;top:486px;left:477;\">" . $receive_by . "</div>";
        // $html .= "<div style=\"font-size: 14px; position:absolute;top:500px;left:477;\">" . $receive_date . "</div>";
        $html .= "<div style=\"font-size: 14px; padding-right:180px; position:absolute;top:610;left:120px;LINE-HEIGHT:15px;\">"
        . $note_2 . "</div>";

        // $html .= "<div style=\"font-size: 14px; position:absolute;top:675x;left:135;\">( Li Guanghe )</div>";
        // $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:687x;left:130;\">" . 'Chief Engineer' . "</div>";

        // $html .= "<div style=\"font-size: 14px; position:absolute;top:675x;left:350;\">( Wang Bo )</div>";
        // $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:687x;left:325;\">" . 'Deputy Chief Engineer' . "</div>";




        // if($assign_to == "Ma Shengshuang") {
        //     $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:675x;left:245;\">" . 'Deputy Project Manager' . "</div>";
        // }

        // if($assign_to == "Jiang Yalei"){
        //     $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:675x;left:225;\">" . 'Leader of Design Review Team' . "</div>";
        // }

        if($assign_to == "Li Guanghe"){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:652x;left:85;\">" . 'X' . "</div>";
        }
        if($assign_to == "Wang Bo"){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:652x;left:297;\">" . 'X' . "</div>";
        }



        // $html .= "<div style=\"font-size: 14px; position:absolute;top:675x;left:530;\">" . $receive_date . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:596x;left:489;\">" . 'Transportation Specialist' . "</div>";

        //CSC Outgoing (1)
        if($comment_status == 1){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:747;left:215;\">" . 'X' . "</div>";
        }
        else if($comment_status == 2){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:747;left:307;\">" . 'X' . "</div>";
        }
        else if($comment_status == 3){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:747;left:417;\">" . 'X' . "</div>";
        }
        else if($comment_status == 4){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:747;left:514;\">" . 'X' . "</div>";
        }
        else{

        }
        $html .= "<div style=\"font-size: 14px; padding-right:180px; position:absolute;top:778;left:120px;LINE-HEIGHT:15px;\">"
        . $note_3 . "</div>";

        $html .= "<div style=\"font-size: 13px; position:absolute;top:825;left:235;\">" . $action_by . "</div>";
        // $html .= "<div style=\"font-size: 13px; position:absolute;top:825;left:530;\">" . $distribute_date . "</div>";

        //CSC Outgoing (2)
        $html .= "<div style=\"font-size: 13px; position:absolute;top:871px;left:377;\">เลขที่ออก/Outgoing No. :</div>";
        // $html .= "<div style=\"font-size: 14px; position:absolute;top:871px;left:477;\">" . $outgoing_number . "</div>";
        // $html .= "<div style=\"font-size: 14px; position:absolute;top:885px;left:477;\">" . $outgoing_date . "</div>";


        $html .= "<div style=\"font-size: 12px; font-weight: bold; position:absolute;top:873px;left:230px;\">"
        . '('. $constructor_code . ')' . "</div>";

       if($for_statuses == 1){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:912;left:215;\">" . 'X' . "</div>";
        }
        else if($for_statuses == 2){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:912;left:307;\">" . 'X' . "</div>";
        }
        else if($for_statuses == 3){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:912;left:417;\">" . 'X' . "</div>";
        }
        else if($for_statuses == 4){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:912;left:514;\">" . 'X' . "</div>";
        }
        else{

        }
        $html .= "<div style=\"font-size: 14px; padding-right:180px; position:absolute;top:942;left:120px;LINE-HEIGHT:15px;\">"
        . $note_4 . "</div>";

        // $html .= "<div style=\"font-size: 14px; position:absolute;top:997x;left:135;\">( Li Guanghe )</div>";
        // $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:1010x;left:130;\">" . 'Chief Engineer' . "</div>";

        // $html .= "<div style=\"font-size: 14px; position:absolute;top:997x;left:350;\">( Wang Bo )</div>";
        // $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:1010x;left:325;\">" . 'Deputy Chief Engineer' . "</div>";




        // if($assign_to == "Ma Shengshuang") {
        //     $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:675x;left:245;\">" . 'Deputy Project Manager' . "</div>";
        // }

        // if($assign_to == "Jiang Yalei"){
        //     $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:675x;left:225;\">" . 'Leader of Design Review Team' . "</div>";
        // }

        if($assign_to == "Li Guanghe"){
         $html .= "<div style=\"font-size: 16px; position:absolute;top:990x;left:91;\">" . 'X' . "</div>";
        }
        if($assign_to == "Wang Bo"){
         $html .= "<div style=\"font-size: 16px; position:absolute;top:990x;left:303;\">" . 'X' . "</div>";

        }


        // $html .= "<div style=\"font-size: 14px; position:absolute;top:990px;left:530;\">" . $outgoing_date . "</div>";

        $mpdf->WriteHTML($html);
         //Add Document
        $allowed = array('pdf','PDF','Pdf');


        /// SUBMITTAL
        if(count($submittalsRfa) > 0){
            $mpdf->AddPage();
            $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/Submittals_Form.pdf'));
            $tplId = $mpdf->ImportPage($pagecount);
            $mpdf->UseTemplate($tplId);

            $html = "<div style=\"font-size: 14px; position:absolute;top:55px;left:678px;\">". $rfa_code ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:138px;left:520px;\">". $wbslv1 ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:138px;left:546px;\">". $wbslv2 ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:138px;left:576px;\">". $wbslv3_code . '.' ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:138px;left:608px;\">". $wbslv4_code . '.' ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:138px;left:648px;\">". $type ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:138px;left:678px;\">". $num_doc ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:138px;left:700px;\">". $revision_count ."</div>";

            $html .= "<div style=\"font-size: 10px; position:absolute;top:138px;left:722px;\">". $receive_date ."</div>";


            if($purpose_for == 1){
                 $html .= "<div style=\"font-size: 14px; position:absolute;top:153px;left:512px;\">X</div>";
             }
            else if($purpose_for == 2){
                 $html .= "<div style=\"font-size: 14px; position:absolute;top:153px;left:562px;\">X</div>";
            }
             else if($purpose_for == 3){
                  $html .= "<div style=\"font-size: 14px; position:absolute;top:153px;left:622px;\">X</div>";
            }
            else if($purpose_for == 4){
                  $html .= "<div style=\"font-size: 14px; position:absolute;top:153px;left:695px;\">X</div>";
            }

            $html .= "<div style=\"font-size: 10px; position:absolute;top:194px;left:118px;\">". $bill . '.'."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:208px;left:118px;\">". $title_th ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:195px;left:608px;\">". $assign_to ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:245px;left:230px;\">". $spec_ref_no ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:245px;left:685px;\">". $incoming_no ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:260px;left:670;\">". $submit_date ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:260px;left:118px;\">". $clause ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:280px;left:216px;\">". $contract_drawing_no ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:315px;left:130px;\">". $qty_page  . '.' ."</div>";

            $html .= "<div style=\"font-size: 12px; position:absolute;top:770px;left:". $issue_position_lf_sub ."px;\">"
            .  $issue_by  . "</div>";
            $html .= "<div style=\"font-size: 10px;  padding-right:180px; position:absolute;top:787px;left:485px; LINE-HEIGHT:9px;\">"
            . $issuer_jobtitle . '  (' . $constructor_code . ')' . "</div>";

            //Signature
            if($multi_signature){
                if($signature_path != ''){
                    $html .= "<div style=\"font-size: 14px; position:absolute;top:740px;left:480px;\">
                        <img src=\"". $signature_path ."\" width=\"" . $signature_size_w . "\" higth=\"". $signature_size_h ."\"> </div>";
                }
                if($signature_path_2 != ''){
                    $html .= "<div style=\"font-size: 14px; position:absolute;top:740px;left:550px;\">
                        <img src=\"". $signature_path_2 ."\" width=\"" . $signature_size_w_2 . "\" higth=\"". $signature_size_h_2 ."\"> </div>";
                }
            }
            else {
                if($signature_path != ''){
                    $html .= "<div style=\"font-size: 14px; position:absolute;top:740px;left:520px;\">
                        <img src=\"". $signature_path ."\" width=\"" . $signature_size_w . "\" higth=\"". $signature_size_h ."\"> </div>";
                }
            }
            if($multi_stamp){
                if($stamp_path != ''){
                    $html .= "<div style=\"font-size: 14px; position:absolute;top:500px;left:400px;\">
                        <img src=\"". $stamp_path ."\" width=\"200px\" higth=\"200px\" style=\"opacity: 0.5;\"> </div>";
                }
                if($stamp_path_2 != ''){
                    $html .= "<div style=\"font-size: 14px; position:absolute;top:530px;left:560px;\">
                        <img src=\"". $stamp_path_2 ."\" width=\"200px\" higth=\"200px\" style=\"opacity: 0.5;\"> </div>";
                }
            }
            else{
                if($stamp_path != ''){
                    $html .= "<div style=\"font-size: 14px; position:absolute;top:500px;left:560px;\">
                        <img src=\"". $stamp_path ."\" width=\"200px\" higth=\"200px\" style=\"opacity: 0.5;\"> </div>";
                }
            }

            $html .= "<div style=\"font-size: 10px; position:absolute;top:930px;left:695px;\">". $outgoing_number  ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:950px;left:680px;\">". $outgoing_date  ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:930;left:130px;\">". $qty_page  . '.' ."</div>";




            $top = 425;
            $index = 1;
            $mpdf->WriteHTML($html);

            foreach($submittalsRfa as $submittal){
                //purpose for HERE!!
                if($index%8 == 0){
                    $top = 425;
                    $mpdf->AddPage();
                    $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/Submittals_Form.pdf'));
                    $tplId = $mpdf->ImportPage($pagecount);
                    $mpdf->UseTemplate($tplId);
                    $mpdf->WriteHTML($html);

                }

                $htmlsub = "<div style=\"font-size: 8px; padding-right:660px; position:absolute;top:". $top ."px;left:52px;LINE-HEIGHT:12px;\">".
                $submittal['item_no'] . '  ' ."</div>";

                $htmlsub .= "<div style=\"font-size: 10px; padding-right:370px; position:absolute;top:". $top ."px;left:138px;LINE-HEIGHT:15px;\">".
                wordwrap(htmlspecialchars($submittal['description'], ENT_QUOTES),350,"<br>\n") ."</div>";

                $htmlsub .= "<div style=\"font-size: 10px; position:absolute;top:". $top ."px;left:445px;\">".
                htmlspecialchars($submittal['qty_sets']) ."</div>";

                $top += 35;
                $index++;
                $mpdf->WriteHTML($htmlsub);

            }

         }

        //Circulation of Work

        $mpdf->AddPage();
        $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/Circulation_General_All_Contract_Update_082023.pdf'));
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        $html = "<div style=\"font-size: 12px; font-weight: bold; position:absolute;top:75px;left:387px;\">". $wbslv2 ."</div>";

        $html .= "<div style=\"font-size: 14px; position:absolute;top:94px;left:110px;\">". $incoming_no ."</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:94px;left:340px;\">". $submit_date ."</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:94px;left:570;\">". $qty_page .'.' ."</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:115px;left:165px;\">". $rfa_code ."</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:155px;left:75px;\">". $title_th ."</div>";




        $rfa_type = $rfa->type->code ?? '';
        if($rfa_type == 'DWG'){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:245px;left:49px;\">". "X" ."</div>";
        }

        if($rfa_type == 'SUB'){
            $html .= "<div style=\"font-size: 16px; position:absolute;top:224px;left:49px;\">". "X" ."</div>";
        }

        $mpdf->WriteHTML($html);

        // foreach($rfa->file_upload_1 as $attacment){
        //     try{
        //         $pagecount = $mpdf->SetSourceFile($attacment->getPath());
        //         for($page = 1; $page <= $pagecount; $page++){
        //             // $mpdf->AddPage();
        //             $tplId = $mpdf->importPage($page);
        //             $size = $mpdf->getTemplateSize($tplId);
        //             $mpdf->AddPage($size['orientation']);
        //             // $mpdf->UseTemplate($tplId);
        //             $mpdf->UseTemplate($tplId, 0, 0, $size['width'], $size['height'], true);

        //         }
        //     }catch(exeption $e){
        //         print "Creating an mPDF object failed with" . $e->getMessage();
        //     }
        // }


        return $mpdf->Output();
    }

}

