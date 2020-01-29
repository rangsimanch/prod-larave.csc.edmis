<?php

namespace App\Http\Controllers\Admin;

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
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RfaController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Rfa::with(['type', 'construction_contract','wbs_level_3', 'wbs_level_4', 'issueby', 'assign',  'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team'])->select(sprintf('%s.*', (new Rfa)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'rfa_show';
                $crudRoutePart = 'rfas';
                //

                if(!strcmp( $row->check_revision ? $row->check_revision : '' ,"f") && 
                !strcmp( $row->document_status ? $row->document_status->status_name : '' ,"Done")){
                    $revisionGate  = 'rfa_revision';
                }else{
                    $revisionGate  = 'rfa_not_revision';
                }

        

                //  Contructor Action
                if(!Gate::denies('rfa_edit_all')){
                    $editGate      = 'rfa_edit';
                    $deleteGate    = 'rfa_delete';
                            return view('partials.datatablesActions', compact(
                                'viewGate',
                                'editGate',
                                'deleteGate',
                                'revisionGate',
                                'crudRoutePart',
                                'row'
                            ));
                }
                else{
               if(!strcmp($row->issueby ? $row->issueby->name : '',Auth::user()->name)){
                    if(!strcmp($row->assign ? $row->assign->name : '',Auth::user()->name) || 
                        !strcmp($row->action_by ? $row->action_by->name : '',Auth::user()->name)
                        ){
                            $editGate      = 'rfa_edit';
                            $deleteGate    = 'rfa_delete';
                            return view('partials.datatablesActions', compact(
                                'viewGate',
                                'editGate',
                                'deleteGate',
                                'revisionGate',
                                'crudRoutePart',
                                'row'
                            ));
                        }
                    else{
                        $editGate      = 'rfa_edit';
                        $deleteGate    = 'rfa_delete';
                        return view('partials.datatablesActions', compact(
                            'viewGate',
                            'editGate',
                            'deleteGate',
                            'revisionGate',
                            'crudRoutePart',
                            'row'
                        ));
                    }
                }
               else{
                    if(!strcmp($row->assign ? $row->assign->name : '',Auth::user()->name) || 
                            !strcmp($row->action_by ? $row->action_by->name : '',Auth::user()->name)
                            ){
                                $editGate      = 'rfa_edit';
                                $deleteGate    = 'rfa_delete';
                                return view('partials.datatablesActions', compact(
                                    'viewGate',
                                    'editGate',
                                    'deleteGate',
                                    'revisionGate',
                                    'crudRoutePart',
                                    'row'
                                ));
                            }
                        else{
                            $editGate      = 'rfa_not_edit';
                            $deleteGate    = 'rfa_delete';
                            return view('partials.datatablesActions', compact(
                                'viewGate',
                                'editGate',
                                'deleteGate',
                                'revisionGate',
                                'crudRoutePart',
                                'row'
                            ));
                        }
                }
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
            $table->addColumn('for_status_name', function ($row) {
                return $row->for_status ? $row->for_status->name : '';
            });


            $table->editColumn('note_4', function ($row) {
                return $row->note_4 ? $row->note_4 : "";
            });

            $table->addColumn('document_status_status_name', function ($row) {
                    return $row->document_status ? $row->document_status->status_name : '';
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

            $table->rawColumns(['actions', 'placeholder', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'file_upload_1', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'action_by', 'create_by_user', 'update_by_user', 'approve_by_user', 'team', 'check_revision']);

            return $table->make(true);
        }

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


        return view('admin.rfas.index',compact('document_status','types','work_types','construction_contracts','wbs_level_3s','wbs_level_4s','submit_dates','receive_dates','comment_statuses','for_statuses','teams'));
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

        $types = Rfatype::all()->pluck('type_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $action_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rfa->load('type', 'construction_contract', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team');
       // $rfa->load(all());
        return view('admin.rfas.revision', compact('types', 'construction_contracts', 'wbs_level_3s', 'wbs_level_4s', 'issuebies', 'assigns', 'action_bies', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses', 'rfa'));
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
        $rvs_length = 4;
        $review_time = $request->review_time + 1;
        $data['review_time'] = $review_time;
        $revision_number = substr("0000{$review_time}", -$rvs_length);

        //RFA Code
        $rfa_code = $request->rfa_code;
        if(strcmp($request->check_revision,"f")){
            $data['rfa_code'] = $rfa_code . '_R' . $revision_number;
        }else{
            $rfa_code = substr($rfa_code,0,14);
            $data['rfa_code'] = $rfa_code . '_R' . $revision_number;
        }
        //Document Number
        $document_number = $request->document_number;
        if(strcmp($request->check_revision,"f")){
            $data['document_number'] = $document_number . "_R" . $revision_number;
        }
        else{
            $document_number = substr($document_number,0,22);
            $data['document_number'] = $document_number . "_R" . $revision_number;
        }
       

        $data['check_revision'] = "f";
        
        Rfa::where('id', $request->id)->update(['check_revision' => "t"]);

        $rfa = Rfa::create($data);
        
        
        
        $data_alert['alert_text'] = 'New RFA assign to you.';
        $data_alert['alert_link'] = route('admin.rfas.index');

        $userAlert = UserAlert::create($data_alert);
        $userAlert->users()->sync($data['assign_id']);

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

        return redirect()->route('admin.rfas.index');
    }


    public function create()
    {
        abort_if(Gate::denies('rfa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Rfatype::all()->pluck('type_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $action_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        $comment_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');  
        
        $document_statuses = RfaDocumentStatus::all()->pluck('status_name', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('admin.rfas.create', compact('document_statuses', 'types', 'construction_contracts', 'wbs_level_3s', 'wbs_level_4s', 'issuebies', 'assigns', 'action_bies', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses'));
    }

    public function store(StoreRfaRequest $request)
    {   $data = $request->all();
        $data['create_by_user_id'] = auth()->id();

        if($request->document_status_id == null){
        $data['document_status_id'] = 1;
        }
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
        $nextId = DB::table('rfas')->max('id') + 1;
        $str_length = 5;
        $doc_number = substr("00000{$nextId}", -$str_length);
        
        // Document Number
        $data['document_number'] = 'HSR1/' . $workcode  . '/' . $wbs3code . '/' . $wbs4code . '/' . $typecode . '/' . $doc_number; 

        //RFA Code
        $data['rfa_code'] = 'RFA' . '/' . $const_code . '/' . $doc_number;

        //Review Time
        $data['review_time'] = 0;
        
        $data['check_revision'] = "f";

        $rfa = Rfa::create($data);

        
        
        $data_alert['alert_text'] = 'New RFA assign to you.';
        $data_alert['alert_link'] = route('admin.rfas.index');

        $userAlert = UserAlert::create($data_alert);
        $userAlert->users()->sync($data['assign_id']);

        foreach ($request->input('file_upload_1', []) as $file) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload_1');
        }

        return redirect()->route('admin.rfas.index');
    }

    public function edit(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Rfatype::all()->pluck('type_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $action_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        

        $rfa->load('type', 'construction_contract', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team');

        return view('admin.rfas.edit', compact('types', 'construction_contracts', 'wbs_level_3s', 'wbs_level_4s', 'issuebies', 'assigns', 'action_bies', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses', 'rfa'));
    }

    public function update(UpdateRfaRequest $request, Rfa $rfa)
    {
        if($rfa->document_status_id == 1){
            if($request->action_by_id != null){
                $rfa['document_status_id'] = 2;
                if($rfa->rfa_code != null){
                    $rfa['incoming_number'] = "IN-" . $rfa->rfa_code ;
                }
            }
        }
        else if($rfa->document_status_id == 2){
            if($request->comment_status_id != null){
                $rfa['document_status_id'] = 3;
            }
        }
        else if($rfa->document_status_id == 3){
            if($request->for_status_id != null){
                $rfa['document_status_id'] = 4;
                if($rfa->rfa_code != null){
                    $rfa['incoming_number'] = "OUT-" . $rfa->rfa_code ;
                }
            }
        }
        


        $rfa->update($request->all());


        // $data_alert['alert_text'] = 'Please Update RFA.';
        // $data_alert['alert_link'] = route('admin.rfas.index');
        // $data_user_id = array($request['comment_by_id'],$request['information_by_id']);
        
        // $userAlert = UserAlert::create($data_alert);
        // $userAlert->users()->sync($data_user_id);


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

        return redirect()->route('admin.rfas.index');
    }

    public function show(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfa->load('type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team');

        return view('admin.rfas.show', compact('rfa'));
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

}
