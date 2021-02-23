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


class RfaController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Rfa::with(['type', 'construction_contract','boq','wbs_level_3', 'wbs_level_4', 'issueby', 'assign',  'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'distribute_by', 'update_by_user', 'approve_by_user', 'reviewed_by', 'team'])->select(sprintf('%s.*', (new Rfa)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->addColumn('date_counter', '&nbsp;');
            $table->editColumn('date_counter', function ($row) {
                $get_target_date = str_replace('/', '-', $row->target_date ? $row->target_date : "");
                $str_to_date = strtotime($get_target_date);
                $format_date = date('m/d/Y',$str_to_date);
                $target_date = new DateTime($format_date);
                
                $cur_date = new DateTime();

                $document_status_id = $row->document_status ? $row->document_status->id : '';

                $counter_date = $cur_date->diff($target_date)->format("%a");

                if(!strcmp($document_status_id, '1')){
                    return '';
                }
                else if(!strcmp($document_status_id, '4')){
                    return '';
                }
                else{
                    if($counter_date < 1000){
                        return $counter_date + 2 . ' Days';
                    }
                    else{
                        return sprintf('Time Out');
                    }
                }
                //return $cur_date->format("d/m/Y");
            });

            $table->addColumn('actions', '&nbsp;');

            // $table->editColumn('actions', function ($row) {

            //     $viewGate      = 'rfa_show';
            //     $crudRoutePart = 'rfas';

            //     if(!strcmp( $row->check_revision ? $row->check_revision : '' ,"f")){
            //         $revisionGate  = 'rfa_revision';
            //     }else{
            //         $revisionGate  = 'rfa_not_revision';
            //     }

        
            //     $constructor = $row->issueby ? $row->issueby->id : '';
            //     $manager = $row->assign ? $row->assign->id : '';
            //     $engineer = $row->action_by ? $row->action_by->id : '';

            //     $document_status_id = $row->document_status ? $row->document_status->id : '';
            //     $sign_status = $row->cec_sign ? $row->cec_sign : '';
            //     $stamp_status = $row->cec_stamp ? $row->cec_stamp : '';


            //     //  EDIT ALL PERMISSION
            //     if(!Gate::denies('rfa_edit_all')){
            //         $editGate      = 'rfa_edit';
            //         $deleteGate    = 'rfa_delete';
            //                 return view('partials.datatablesActions', compact(
            //                     'viewGate',
            //                     'editGate',
            //                     'deleteGate',
            //                     'revisionGate',
            //                     'crudRoutePart',
            //                     'row'
            //                 ));
            //     }
            //     else{
            //         /////////////// New Status ///////////////
            //         if(!strcmp($document_status_id, '1')){
            //             //Before CEC Sign and Stamp 
            //             if(strcmp($sign_status, '2') == '0' or strcmp($stamp_status, '2') == '0' ){
            //                 //Check Permission CEC Manager and CEC Admin
            //                 if (!strcmp($constructor,Auth::id()) || !Gate::denies('rfa_cec_sign') ){
            //                         $editGate      = 'rfa_edit';
            //                         $deleteGate    = 'rfa_delete';
            //                         return view('partials.datatablesActions', compact(
            //                             'viewGate',
            //                             'editGate',
            //                             'deleteGate',
            //                             'revisionGate',
            //                             'crudRoutePart',
            //                             'row'
            //                         ));
            //                 }
                     
            //                 else{
            //                     $editGate      = 'rfa_not_edit';
            //                     $deleteGate    = 'rfa_not_delete';
            //                     return view('partials.datatablesActions', compact(
            //                         'viewGate',
            //                         'editGate',
            //                         'deleteGate',
            //                         'revisionGate',
            //                         'crudRoutePart',
            //                         'row'
            //                     ));
            //                 }
            //             }
            //         //After CEC Sign and Stamp
            //             else {
            //                 //Check CSC Manager
            //                 if ( strcmp(Auth::id(),'61') == 0){
            //                     $editGate      = 'rfa_edit';
            //                     $deleteGate    = 'rfa_delete';
            //                     return view('partials.datatablesActions', compact(
            //                         'viewGate',
            //                         'editGate',
            //                         'deleteGate',
            //                         'revisionGate',
            //                         'crudRoutePart',
            //                         'row'
            //                     ));
            //                 }
            //                 else{
            //                     $editGate      = 'rfa_not_edit';
            //                     $deleteGate    = 'rfa_not_delete';
            //                     return view('partials.datatablesActions', compact(
            //                         'viewGate',
            //                         'editGate',
            //                         'deleteGate',
            //                         'revisionGate',
            //                         'crudRoutePart',
            //                         'row'
            //                     ));
            //                 }
            //             }
            //         }

            //         // /////////////// Distribute Status ///////////////
            //         elseif(strcmp($document_status_id, '2') == 0){
            //             //Check Engineer and Specialist
            //             if (strcmp($engineer,Auth::id()) == 0 ){
            //                 $editGate      = 'rfa_edit';
            //                 $deleteGate    = 'rfa_delete';
            //                 return view('partials.datatablesActions', compact(
            //                     'viewGate',
            //                     'editGate',
            //                     'deleteGate',
            //                     'revisionGate',
            //                     'crudRoutePart',
            //                     'row'
            //                 ));
            //             }
            //             else{
            //                 $editGate      = 'rfa_not_edit';
            //                 $deleteGate    = 'rfa_not_delete';
            //                 return view('partials.datatablesActions', compact(
            //                     'viewGate',
            //                     'editGate',
            //                     'deleteGate',
            //                     'revisionGate',
            //                     'crudRoutePart',
            //                     'row'
            //                 ));
            //             }
            //         }
                
            //         // /////////////// Reviewed Status ///////////////
            //         else if(strcmp($document_status_id, '3') == 0){
            //         //Check CSC Manager
            //            if ( strcmp(Auth::id(),'61') == 0){
            //                 $editGate      = 'rfa_edit';
            //                 $deleteGate    = 'rfa_delete';
            //                 return view('partials.datatablesActions', compact(
            //                     'viewGate',
            //                     'editGate',
            //                     'deleteGate',
            //                     'revisionGate',
            //                     'crudRoutePart',
            //                     'row'
            //                 ));
            //             }
            //             else{
            //                 $editGate      = 'rfa_not_edit';
            //                 $deleteGate    = 'rfa_not_delete';
            //                 return view('partials.datatablesActions', compact(
            //                     'viewGate',
            //                     'editGate',
            //                     'deleteGate',
            //                     'revisionGate',
            //                     'crudRoutePart',
            //                     'row'
            //                 ));
            //             }
            //         }

            //         else{
            //             $editGate      = 'rfa_not_edit';
            //             $deleteGate    = 'rfa_not_delete';
            //             return view('partials.datatablesActions', compact(
            //                 'viewGate',
            //                 'editGate',
            //                 'deleteGate',
            //                 'revisionGate',
            //                 'crudRoutePart',
            //                 'row'
            //             ));
        
            //         }    

            //     }

            // });

            $table->editColumn('actions', function ($row) {
                if($row->document_status->id != 4){
                    $viewGate      = 'rfa_show';
                    $editGate      = 'rfa_edit';
                    $deleteGate    = 'rfa_delete';
                    $crudRoutePart = 'rfas';

                    return view('partials.datatablesActions', compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    ));
                }else
                {
                    $viewGate      = 'rfa_show';
                    $editGate      = 'rfa_not_edit';
                    $deleteGate    = 'rfa_delete';
                    $crudRoutePart = 'rfas';

                    return view('partials.datatablesActions', compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    ));
                }
            });
            

            $table->editColumn('title_eng', function ($row) {
                return $row->title_eng ? $row->title_eng : "";
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->editColumn('title_cn', function ($row) {
                return $row->title_cn ? $row->title_cn : "";
            });
            $table->editColumn('origin_number', function ($row) {
                return $row->origin_number ? $row->origin_number : "";
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : "";
            });
            $table->editColumn('rfa_code', function ($row) {
                return $row->rfa_code ? $row->rfa_code : "";
            });
            $table->addColumn('type_type_name', function ($row) {
                return $row->type ? $row->type->type_name : '';
            });
            $table->editColumn('review_time', function ($row) {
                return $row->review_time ? $row->review_time : "";
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

            $table->addColumn('comment_by_name', function ($row) {
                return $row->comment_by ? $row->comment_by->name : '';
            });

            $table->addColumn('information_by_name', function ($row) {
                return $row->information_by ? $row->information_by->name : '';
            });

            $table->editColumn('note_2', function ($row) {
                return $row->note_2 ? $row->note_2 : "";
            });
            $table->addColumn('comment_status_name', function ($row) {
                return $row->comment_status ? $row->comment_status->name : '';
            });

            $table->editColumn('note_3', function ($row) {
                return $row->note_3 ? $row->note_3 : "";
            });
            $table->editColumn('document_file_upload', function ($row) {
                if (!$row->document_file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->document_file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('document_ref', function ($row) {
                return $row->document_ref ? $row->document_ref : "";
            });
            $table->addColumn('for_status_name', function ($row) {
                return $row->for_status ? $row->for_status->name : '';
            });


            $table->editColumn('note_4', function ($row) {
                return $row->note_4 ? $row->note_4 : "";
            });

            $table->addColumn('document_status_status_name', function ($row) {
                    if ($row->document_status->status_name == 'New'){
                        return sprintf('<p style="color:#003399"><b>%s</b></p>',$row->document_status ? $row->document_status->status_name : '');
                    }
                    else if($row->document_status->status_name == 'Distributed'){
                        return sprintf('<p style="color:#ff9900"><b>%s</b></p>',$row->document_status ? $row->document_status->status_name : '');
                    }
                    else if($row->document_status->status_name == 'Reviewed'){
                        return sprintf('<p style="color:#6600cc"><b>%s</b></p>',$row->document_status ? $row->document_status->status_name : '');
                    }
                    else if($row->document_status->status_name == 'Done'){
                        return sprintf('<p style="color:#009933"><b>%s</b></p>',$row->document_status ? $row->document_status->status_name : '');
                    }
                    else{
                        return $row->document_status ? $row->document_status->status_name : '';
                    }

                
            });

            $table->addColumn('create_by_user_name', function ($row) {
                return $row->create_by_user ? $row->create_by_user->name : '';
            });

            $table->addColumn('update_by_user_name', function ($row) {
                return $row->update_by_user ? $row->update_by_user->name : '';
            });

            $table->addColumn('approve_by_user_name', function ($row) {
                return $row->approve_by_user ? $row->approve_by_user->name : '';
            });

            $table->addColumn('team_code', function ($row) {
                return $row->team ? $row->team->code : '';
            });

            $table->addColumn('check_revision', function ($row) {
                return $row->check_revision ? $row->check_revision : '';
            });

            $table->addColumn('boq_name', function ($row) {
                return $row->boq ? $row->boq->name : '';
            });

            $table->editColumn('qty_page', function ($row) {
                return $row->qty_page ? $row->qty_page : "";
            });
            $table->editColumn('spec_ref_no', function ($row) {
                return $row->spec_ref_no ? $row->spec_ref_no : "";
            });
            $table->editColumn('clause', function ($row) {
                return $row->clause ? $row->clause : "";
            });
            $table->editColumn('contract_drawing_no', function ($row) {
                return $row->contract_drawing_no ? $row->contract_drawing_no : "";
            });

            $table->addColumn('reviewed_by_name', function ($row) {
                return $row->reviewed_by ? $row->reviewed_by->name : '';
            });

            $table->rawColumns(['actions','boq', 'placeholder', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'file_upload_1', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'action_by', 'create_by_user', 'update_by_user', 'approve_by_user', 'commercial_file_upload', 'document_file_upload', 'team', 'check_revision','reviewed_by','document_status_status_name','submittals_file']);

            return $table->make(true);
        }

        $boqs =  BoQ::all()->sortBy('id')->pluck('name')->unique();
        $document_status =  RfaDocumentStatus::all()->sortBy('status_name')->pluck('status_name')->unique();
        $types = Rfatype::all()->sortBy('type_code')->pluck('type_code')->unique();
        $work_types = Rfa::all()->sortBy('worktype')->pluck('worktype')->unique();
        $construction_contracts = ConstructionContract::all()->sortBy('code')->pluck('code')->unique();
        $wbs_level_3s = WbsLevelThree::all()->sortBy('wbs_level_3_code')->pluck('wbs_level_3_code')->unique();
        $wbs_level_4s = Wbslevelfour::all()->sortBy('wbs_level_4_code')->pluck('wbs_level_4_code')->unique();
        $submit_dates = Rfa::all()->sortBy('submit_date')->pluck('submit_date')->unique();
        $receive_dates = Rfa::all()->sortBy('receive_date')->pluck('receive_date')->unique();
        $comment_statuses = RfaCommentStatus::all()->sortBy('name')->pluck('name')->unique();
        $for_statuses = RfaCommentStatus::all()->sortBy('name')->pluck('name')->unique();
        $teams = Team::all()->sortBy('code')->pluck('code')->unique();


        return view('admin.rfas.index',compact('boqs','document_status','types','work_types','construction_contracts','wbs_level_3s','wbs_level_4s','submit_dates','receive_dates','comment_statuses','for_statuses','teams'));
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
            if(Auth::id() != 1){
                $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            else{
                $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }

        
            
        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::where('id',91)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

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

        $issuebies = User::where('id',91)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::where('id',61)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''); //61->Li, 39->Paisan,  62->Liu 

        $action_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        $comment_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');  
        
        $document_statuses = RfaDocumentStatus::all()->pluck('status_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reviewed_bies = User::find([39,62])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boqs = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.rfas.create', compact('document_statuses', 'types', 'construction_contracts', 'wbs_level_3s', 'wbs_level_4s', 'issuebies', 'assigns', 'action_bies', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses', 'reviewed_bies', 'boqs'));
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

        $str_length = 6;
        $doc_number = substr("000000{$nextId}", -$str_length);
        $cur_date = date("ymd");
        $code_year = substr($cur_date,0,2);
        $code_mouth = substr($cur_date,2,2);
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
            $data['rfa_code'] = $request->origin_number;
            $data['document_number'] = 'HSR1/' . $const_code . '/' . $wbs3code . '/' . $wbs4code . '/' . $typecode . '/' . $code_date . '/' . substr($request->origin_number,4,4); 
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
        if($rfa->document_status_id == 1){
            $action_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $comment_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $information_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $rfa->load('action_by', 'comment_by', 'information_by');

            return view('admin.rfas.edit', compact('construction_contracts', 'action_bies', 'comment_bies', 'information_bies', 'rfa'));
        }
        else if($rfa->document_status_id == 2){
            $action_bies = User::where('team_id',3)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $rfa->load('action_by');
            return view('admin.rfas.edit', compact('construction_contracts', 'action_bies', 'comment_statuses', 'rfa'));
        }
        else if($rfa->document_status_id == 3){
            $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $reviewed_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            return view('admin.rfas.edit', compact('construction_contracts', 'for_statuses', 'reviewed_bies', 'rfa'));
        }

    }

    public function update(UpdateRfaRequest $request, Rfa $rfa)
    {
        if($rfa->document_status_id == 1){
            $data['action_by_id'] = $request->action_by_id;
            $data['comment_by_id'] = $request->comment_by_id;
            $data['information_by_id'] = $request->information_by_id;
            $data['receive_date'] = $request->receive_date;
            $data['note_2'] = $request->note_2;
            $data['target_date'] = $request->target_date;
            $data['incoming_number'] = "IN-" . $rfa->origin_number;
            $data['document_status_id'] = 2;
            $data['distribute_by_id'] = Auth::id();
            $distribute_date = new DateTime();
            $data['distribute_date'] = $distribute_date->format('d/m/Y');
            $rfa->update($data);

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
                    $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('work_file_upload');
                }
            }
        }

        else if($rfa->document_status_id == 2){
            $data['document_status_id'] = 3;
            $process_date = new DateTime();
            $data['process_date'] = $process_date->format('d/m/Y');
            $data['comment_status_id'] = $request->comment_status_id;
            $data['note_3'] = $request->note_3;
            $rfa->update($data);
            
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
                    $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document_file_upload');
                }
            }
        }

        else if($rfa->document_status_id == 3){
            $data['document_status_id'] = 4;
            $outgoing_date = new DateTime();
            $data['outgoing_date'] = $outgoing_date->format('d/m/Y');
            $data['outgoing_number'] = "OUT-" . $rfa->rfa_code;
            $data['reviewed_by_id'] = $request->reviewed_by_id;
            $data['for_status_id'] = $request->for_status_id;
            $data['note_4'] = $request->note_4;
            $rfa->update($data);

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
                    $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('commercial_file_upload');
                }
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

        $rfa->load('type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'team','onRfaSubmittalsRfas', 'reviewed_by','distribute_by');

        //Varible setting

        if($rfa->construction_contract->code == "C2-1"){
            $issue_by = 'Sitthichai Pimsawat';
            $constructor_name = 'Civil Construction Services & Products Company Limited';
            $constructor_code = 'CCSP';
            $logo_path =  public_path('png-asset/Stamp_CEC.png');
            $stamp_path =  public_path('png-asset/Stamp_CEC.png');
            $signature_path =  public_path('png-asset/Signature_CEC.png');
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C3-4"){
            $issue_by = '-';
            $constructor_name = 'Italian-Thai Development PLC';
            $constructor_code = 'ITD';
            $stamp_path =  '';
            $signature_path =  '';
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C4-7"){
            $issue_by = 'สุทิน สังข์หิรัญ';
            $constructor_name = 'Civil Enginneering Public Company Limited';
            $constructor_code = 'CIVIL';
            $logo_path = public_path('png-asset/CIVIL_logo.png');
            $stamp_path =  public_path('png-asset/CIVIL_stamp.png');
            $signature_path =  public_path('png-asset/CIVIL_signature.png');;
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }

        if($rfa->construction_contract->code == "C3-5"){
            $issue_by = 'Narutchai Sammawijitra';
            $constructor_name = 'SPTK Joint Venture Company Limited';
            $constructor_code = 'SPTK';
            $logo_path =  public_path('png-asset/Stamp_CEC.png');
            $stamp_path =  public_path('png-asset/SPTK_stamp.png');
            $signature_path =  public_path('png-asset/SPTK_signature.png');
            $contract_name = 'Contract ' . $rfa->construction_contract->code . ' : ' . $rfa->construction_contract->name;

        }


        $bill = $rfa->boq->name ?? '';
        $title_th = $rfa->title ?? '';
        $title_en = wordwrap($rfa->title_eng ?? '',300,"<br>\n");
        $document_number = $rfa->document_number ?? '';
        $check_box = "X";
        $rfa_code = '';
        if(isset($rfa->origin_number)){
            $rfa_code = $rfa->origin_number ?? '';
            if(strlen($rfa_code) > 8){
                $revision_count = substr($rfa_code,11,1);
            }
            else{
                $revision_count = "0";
            }
        }
        else{
            $rfa_code = $rfa->rfa_code ?? '';  
            if(strlen($rfa_code) > 14){
                $revision_count = substr($rfa_code,17,2);
            }
            else{
                $revision_count = "0";
            }
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
        
        $document_name = wordwrap($rfa->attach_file_name ?? '',300,"<br>\n");
        $note_1 = wordwrap($rfa->note_1 ?? '',300,"<br>\n");
        $note_2 = wordwrap($rfa->note_2 ?? '',300,"<br>\n");
        $note_3 = wordwrap($rfa->note_3 ?? '',300,"<br>\n");
        $note_4 = wordwrap($rfa->note_4 ?? '',300,"<br>\n");

        $wbslv1 = "HSR1";
        $wbslv2 = $rfa->construction_contract->code ?? '';
        $wbslv3 = $rfa->wbs_level_3->wbs_level_3_name ?? '';
        $wbslv4 = $rfa->wbs_level_4->wbs_level_4_name ?? '';
        $wbslv3_code = $rfa->wbs_level_3->wbs_level_3_code ?? '';
        $wbslv4_code = $rfa->wbs_level_4->wbs_level_4_code ?? '';

        $wbs = '';
        if($rfa->wbs_level_4->wbs_level_4_name ?? '' != ''){
            $wbs = '1.' . $wbslv3 . ' 2.' . $wbslv4;
        }else{
            $wbs = '1.' . $wbslv3;
        }
        $type = $rfa->type->type_code ?? '';
        $num_doc = substr($rfa_code,11,4);

        
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
        
                //PDF Setting
                try {
                    $mpdf = new \Mpdf\Mpdf([
                        'tempDir' => __DIR__ . public_path('tmp/'), 
                        'default_font' => 'sarabun_new'
                    ]);
                  } catch (\Mpdf\MpdfException $e) {
                      print "Creating an mPDF object failed with" . $e->getMessage();
                  }

    
          
        //RFA Page
        $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/RFA-Form_empty_V.2.pdf'));
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);        

        $html = "<div style=\"font-size: 13px; font-weight: bold; position:absolute;top:84px;left:320px;\">" . $contract_name . "</div>";
          //Title
        $html .= "<div style=\"font-size: 14px; position:absolute;top:168px;left:80px;\">" . $bill . '.' . "</div>";
        $html .= "<div style=\"font-size: 14px; padding-right:240px; position:absolute;top:184px;left:80px; LINE-HEIGHT:16px;\">" 
        . $title_en . "</div>";
        $html .= "<div style=\"font-size: 14px; padding-right:230px; position:absolute;top:217px;left:80px; LINE-HEIGHT:16px;\">" 
        . $title_th . "</div>";

        
        //Logo Header
        if($rfa->construction_contract->code != "C4-7"){
            $html .= "<div style=\"font-size: 14px; position:absolute;top:109px;left:690px;\">
                        <img src=\"". $logo_path ."\" width=\"45px\" higth=\"45px\"> </div>";
        }
        else{
            $html .= "<div style=\"font-size: 14px; position:absolute;top:113px;left:630px;\">
                        <img src=\"". $logo_path ."\" width=\"90px\" higth=\"90px\"> </div>";
        }
        $html .= "<div style=\"font-size: 13px; position:absolute;top:120px;left:580px;\">" . $constructor_code . '.' . "</div>";
        $html .= "<div style=\"font-size: 13px; position:absolute;top:140px;left:508px;\">" . $constructor_name . '.' . "</div>";



        //No. Code.
        $html .= "<div style=\"font-size: 14px; position:absolute;top:30px;left:650px;\">" . $rfa_code . "</div>";
        $html .= "<div style=\"font-size: 11px; position:absolute;top:170px;left:477px;\">" . $document_number . "</div>";
        $html .= "<div style=\"font-size: 11px; position:absolute;top:170px;left:660px;\">" . $rfa_code . "</div>";
          //Date
        $html .= "<div style=\"font-size: 14px; position:absolute;top:217px;left:630px;\">" . $submit_date . "</div>";
          //Document Name
        $html .= "<div style=\"font-size: 14px; padding-right:240px; position:absolute;top:269px;left:160px;LINE-HEIGHT:15px;\">" . $document_name . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:265;left:630;\">" . $qty_page . '.' . "</div>";
        
          //WBS Spec.Ref Clase. Contract No.
        $html .= "<div style=\"font-size: 14px; position:absolute;top:328px;left:225px;\">" . $wbs . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:344px;left:250px;\">" . $spec_ref_no . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:344px;left:630px;\">" . $clause . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:360px;left:210px;\">" . $contract_drawing_no . "</div>";
          //Note
        $html .= "<div style=\"font-size: 14px; padding-right:180px; position:absolute;top:380px;left:120px;LINE-HEIGHT:15px;\">" 
        . $note_1 . "</div>";
        
       
        //Signature
        $html .= "<div style=\"font-size: 12px; position:absolute;top:433px;left:260px;\">" . $issue_by . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:410px;left:280px;\">
            <img src=\"". $signature_path ."\" width=\"40px\" higth=\"40px\"> </div>";
        
        $html .= "<div style=\"font-size: 14px; position:absolute;top:300px;left:400px;\">
            <img src=\"". $stamp_path ."\" width=\"200px\" higth=\"200px\" style=\"opacity: 0.5;\"> </div>";
        

        //CSC Incoming 
        $html .= "<div style=\"font-size: 14px; position:absolute;top:485px;left:477;\">" . $incoming_no . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:500px;left:477;\">" . $receive_date . "</div>";
        $html .= "<div style=\"font-size: 14px; padding-right:180px; position:absolute;top:610;left:120px;LINE-HEIGHT:15px;\">" 
        . $note_2 . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:660x;left:270;\">" . 'Li Guanghe' . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:675x;left:530;\">" . $receive_date . "</div>";
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
        $html .= "<div style=\"font-size: 13px; position:absolute;top:825;left:530;\">" . $distribute_date . "</div>";

        //CSC Outgoing (2)
        $html .= "<div style=\"font-size: 14px; position:absolute;top:870px;left:477;\">" . $outgoing_number . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:885px;left:477;\">" . $outgoing_date . "</div>";

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

        $html .= "<div style=\"font-size: 14px; position:absolute;top:990x;left:270;\">" . 'Li Guanghe' . "</div>";

        $html .= "<div style=\"font-size: 14px; position:absolute;top:990px;left:530;\">" . $outgoing_date . "</div>";

        $mpdf->WriteHTML($html);
         //Add Document
        $allowed = array('pdf','PDF','Pdf');
        

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
            $html .= "<div style=\"font-size: 10px; position:absolute;top:260px;left:670;\">". $receive_date ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:260px;left:118px;\">". $clause ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:280px;left:216px;\">". $contract_drawing_no ."</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:315px;left:130px;\">". $qty_page  . '.' ."</div>";

            if($rfa->cec_sign == 1){
                $html .= "<div style=\"font-size: 14px; position:absolute;top:740px;left:520px;\">
                    <img src=\"". public_path('png-asset/Signature_CEC.png') ."\" width=\"40px\" higth=\"40px\"> </div>";
            }
            
            if($rfa->cec_stamp == 1){
                $html .= "<div style=\"font-size: 14px; position:absolute;top:500px;left:560px;\">
                <img src=\"". public_path('png-asset/Stamp_CEC.png') ."\" width=\"200px\" higth=\"200px\" style=\"opacity: 0.5;\"> </div>";
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
                $htmlsub = "<div style=\"font-size: 12px; padding-right:680px; position:absolute;top:". $top ."px;left:50px;LINE-HEIGHT:15px;\">". 
                $submittal['item_no'] . '  ' ."</div>";

                $htmlsub .= "<div style=\"font-size: 12px; padding-right:370px; position:absolute;top:". $top ."px;left:138px;LINE-HEIGHT:15px;\">". 
                wordwrap($submittal['description'],350,"<br>\n") ."</div>";  
                
                $htmlsub .= "<div style=\"font-size: 12px; position:absolute;top:". $top ."px;left:445px;\">". 
                $submittal['qty_sets'] ."</div>";

                $top += 35;
                $index++;
                $mpdf->WriteHTML($htmlsub);

            }
            
         }
        

        return $mpdf->Output();
    }

}

