<?php

namespace App\Http\Controllers\Admin;

use App\BoQ;
use App\BoqItem;
use DB;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRequestForInspectionRequest;
use App\Http\Requests\StoreRequestForInspectionRequest;
use App\Http\Requests\UpdateRequestForInspectionRequest;
use App\RequestForInspection;
use App\Team;
use App\User;
use App\Wbslevelfour;
use App\WbsLevelOne;
use App\WbsLevelThree;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RequestForInspectionController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('request_for_inspection_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RequestForInspection::with(['wbs_level_1', 'bill', 'wbs_level_3', 'wbs_level_4', 'items', 'requested_by', 'contact_person', 'construction_contract', 'team'])->select(sprintf('%s.*', (new RequestForInspection)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'request_for_inspection_show';
                $editGate      = 'request_for_inspection_edit';
                $deleteGate    = 'request_for_inspection_delete';
                $crudRoutePart = 'request-for-inspections';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('wbs_level_1_name', function ($row) {
                return $row->wbs_level_1 ? $row->wbs_level_1->name : '';
            });

            $table->addColumn('bill_name', function ($row) {
                return $row->bill ? $row->bill->name : '';
            });

            $table->addColumn('wbs_level_3_wbs_level_3_name', function ($row) {
                return $row->wbs_level_3 ? $row->wbs_level_3->wbs_level_3_name : '';
            });

            $table->addColumn('wbs_level_4_wbs_level_4_name', function ($row) {
                return $row->wbs_level_4 ? $row->wbs_level_4->wbs_level_4_name : '';
            });

            $table->editColumn('item', function ($row) {
                $labels = [];

                foreach ($row->items as $item) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $item->code);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('type_of_work', function ($row) {
                return $row->type_of_work ? RequestForInspection::TYPE_OF_WORK_SELECT[$row->type_of_work] : '';
            });
            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : "";
            });
            $table->editColumn('ref_no', function ($row) {
                return $row->ref_no ? $row->ref_no : "";
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : "";
            });
            $table->addColumn('requested_by_name', function ($row) {
                return $row->requested_by ? $row->requested_by->name : '';
            });

            $table->addColumn('contact_person_name', function ($row) {
                return $row->contact_person ? $row->contact_person->name : '';
            });

            $table->editColumn('ipa', function ($row) {
                return $row->ipa ? $row->ipa : "";
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('files_upload', function ($row) {
                if (!$row->files_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->files_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->editColumn('start_loop', function ($row) {
                return $row->start_loop ? $row->start_loop : "";
            });
            $table->editColumn('end_loop', function ($row) {
                return $row->end_loop ? $row->end_loop : "";
            });
            $table->editColumn('loop_file_upload', function ($row) {
                if (!$row->loop_file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->loop_file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'wbs_level_1', 'bill', 'wbs_level_3', 'wbs_level_4', 'item', 'requested_by', 'contact_person', 'construction_contract', 'files_upload', 'loop_file_upload']);

            return $table->make(true);
        }

        $wbs_level_ones         = WbsLevelOne::get();
        $bo_qs                  = BoQ::get();
        $wbs_level_threes       = WbsLevelThree::get();
        $wbslevelfours          = Wbslevelfour::get();
        $boq_items              = BoqItem::get();
        $users                  = User::get();
        $users                  = User::get();
        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();

        return view('admin.requestForInspections.index', compact('wbs_level_ones', 'bo_qs', 'wbs_level_threes', 'wbslevelfours', 'boq_items', 'users', 'users', 'construction_contracts', 'teams'));
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

    function itemBoQ(Request $request){
        $id = $request->get('select');
        $result = array();
        $query = DB::table('bo_qs')
        ->join('boq_items','bo_qs.id','=','boq_items.boq_id')
        ->select('boq_items.name','boq_items.id')
        ->where('bo_qs.id',$id)
        ->groupBy('boq_items.name','boq_items.id')
        ->orderBy('name')
        ->get();
        $output = '<option value="">' . trans('global.pleaseSelect') . '</option>';
        foreach ($query as $row){
            $output .= '<option value="'. $row->id .'">'. $row->name .'</option>';
        }
        echo $output;
    }

    function WbsTwo(Request $request){
        $id = $request->get('select');
        $result = array();
        $query = DB::table('wbs_level_ones')
        ->join('bo_qs','wbs_level_ones.id','=','bo_qs.wbs_lv_1_id')
        ->select('bo_qs.name','bo_qs.id')
        ->where('wbs_level_ones.id',$id)
        ->groupBy('bo_qs.name','bo_qs.id')
        ->orderBy('name')
        ->get();
        $output = '<option value="">' . trans('global.pleaseSelect') . '</option>';
        foreach ($query as $row){
            $output .= '<option value="'. $row->id .'">'. $row->name .'</option>';
        }
        echo $output;
    }

    function WbsThree(Request $request){
        $id = $request->get('select');
        $result = array();
        $query = DB::table('bo_qs')
        ->join('wbs_level_threes','bo_qs.id','=','wbs_level_threes.wbs_level_2_id')
        ->select('wbs_level_threes.wbs_level_3_name','wbs_level_threes.id')
        ->where('bo_qs.id',$id)
        ->groupBy('wbs_level_threes.wbs_level_3_name','wbs_level_threes.id')
        ->orderBy('wbs_level_3_name')
        ->get();
        $output = '<option value="">' . trans('global.pleaseSelect') . '</option>';
        foreach ($query as $row){
            $output .= '<option value="'. $row->id .'">'. $row->wbs_level_3_name .'</option>';
        }
        echo $output;
    }

    public function create()
    {
        abort_if(Gate::denies('request_for_inspection_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbs_level_1s = WbsLevelOne::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bills = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $items = BoqItem::all()->pluck('code', 'id');

        $requested_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contact_people = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.requestForInspections.create', compact('wbs_level_1s', 'bills', 'wbs_level_3s', 'wbs_level_4s', 'items', 'requested_bies', 'contact_people', 'construction_contracts'));
    }

    public function store(StoreRequestForInspectionRequest $request)
    {
        $data = $request->all();

        $subject = $data['subject'];
        $suffix_subject = intval(substr($data['subject'],-1));

        $work_type = $data['type_of_work'];

        $wbs1 = WbsLevelOne::where('id','=',$request->wbs_level_1_id)->value('code');
        $wbs2 = BoQ::where('id','=',$request->bill_id)->value('code');
        $wbs3 = WbsLevelThree::where('id','=',$request->wbs_level_3_id)->value('wbs_level_3_code');
        $wbs4 = Wbslevelfour::where('id','=',$request->wbs_level_4_id)->value('wbs_level_4_code');
        
        if(is_null($data['ref_no'])){
                if($work_type != "Segment Production"){
                    $order_num = RequestForInspection::where('wbs_level_3_id',$request->wbs_level_3_id)
                    ->count() + 1;
                    $order_number = $wbs1 . '/' .  $wbs2 . '/' . $wbs3 . '/' . substr("0000{$order_num}", -4);
                 }
                 else{
                    $order_num = RequestForInspection::where('wbs_level_3_id',$request->wbs_level_3_id)
                    ->where('type_of_work',$work_type)
                    ->count() + 1;
                    $order_number = $wbs1 . '/' .  $wbs2 . '/' . $wbs3 . '/' . substr("0000{$order_num}", -4) . 'Y';
                 }
             $data['ref_no'] = $order_number;
        }
        else{
            $order_number = $data['ref_on'];
        }

       
        $requestForInspection = RequestForInspection::create($data);
        $requestForInspection->items()->sync($request->input('items', []));

        foreach ($request->input('files_upload', []) as $file) {
            $requestForInspection->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('files_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $requestForInspection->id]);
        }

        //Loop panel
        if($data['end_loop'] > 0){
            for($i = 1; $i <= $data['end_loop']; $i++){
                if(intval($suffix_subject) > 0){
                    $loop_data['subject'] = substr($subject,0,strlen($subject)-1) . intval($suffix_subject + $i);
                }else{
                    $loop_data['subject'] = $data['subject'];
                }
                $loop_data['type_of_work'] = $data['type_of_work'];
                $loop_data['wbs_level_1_id'] = $data['wbs_level_1_id'];
                $loop_data['bill_id'] = $data['bill_id'];
                $loop_data['wbs_level_3_id'] = $data['wbs_level_3_id'];
                $loop_data['wbs_level_4_id'] = $data['wbs_level_4_id'];

                $order_num = intval(substr($order_number , -4)) + 1;
                if($work_type != "Segment Production"){
                    $order_ref = $wbs1 . '/' .  $wbs2 . '/' . $wbs3 . '/' . substr("0000{$order_num}", -4);
                }else{
                    $order_ref = $wbs1 . '/' .  $wbs2 . '/' . $wbs3 . '/' . substr("0000{$order_num}", -4) . 'Y';
                }
                $loop_data['ref_no'] = $order_ref;

                $order_number = $order_ref;

                $loop_data['location'] = $data['location'];
                $loop_data['requested_by_id'] = $data['requested_by_id'];
                $loop_data['submittal_date'] = $data['submittal_date'];
                $loop_data['contact_person_id'] = $data['contact_person_id'];
                $loop_data['replied_date'] = $data['replied_date'];
                $loop_data['ipa'] = $data['ipa'];
                $loop_data['construction_contract_id'] = $data['construction_contract_id'];

                $requestForInspection = RequestForInspection::create($loop_data);
                $requestForInspection->items()->sync($request->input('items', []));

                if(is_null($request->input('loop_file_upload', []))){
                    if(is_null($data['loop_file_upload'][$i-1])){
                        $requestForInspection->addMedia(storage_path('tmp/uploads/' . $data['loop_file_upload'][$i - 1]))
                        ->toMediaCollection('files_upload');
                    }
                }
            }
        }

        // foreach ($request->input('loop_file_upload', []) as $file) {
        //     $requestForInspection->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('loop_file_upload');
        // }


        return redirect()->route('admin.request-for-inspections.index');
    }

    public function edit(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbs_level_1s = WbsLevelOne::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bills = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $items = BoqItem::all()->pluck('code', 'id');

        $requested_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contact_people = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $requestForInspection->load('wbs_level_1', 'bill', 'wbs_level_3', 'wbs_level_4', 'items', 'requested_by', 'contact_person', 'construction_contract', 'team');

        return view('admin.requestForInspections.edit', compact('wbs_level_1s', 'bills', 'wbs_level_3s', 'wbs_level_4s', 'items', 'requested_bies', 'contact_people', 'construction_contracts', 'requestForInspection'));
    }

    public function update(UpdateRequestForInspectionRequest $request, RequestForInspection $requestForInspection)
    {
        $requestForInspection->update($request->all());
        $requestForInspection->items()->sync($request->input('items', []));

        if (count($requestForInspection->files_upload) > 0) {
            foreach ($requestForInspection->files_upload as $media) {
                if (!in_array($media->file_name, $request->input('files_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $requestForInspection->files_upload->pluck('file_name')->toArray();

        foreach ($request->input('files_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $requestForInspection->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('files_upload');
            }
        }

        if (count($requestForInspection->loop_file_upload) > 0) {
            foreach ($requestForInspection->loop_file_upload as $media) {
                if (!in_array($media->file_name, $request->input('loop_file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $requestForInspection->loop_file_upload->pluck('file_name')->toArray();

        foreach ($request->input('loop_file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $requestForInspection->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('loop_file_upload');
            }
        }

        return redirect()->route('admin.request-for-inspections.index');
    }

    public function show(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInspection->load('wbs_level_1', 'bill', 'wbs_level_3', 'wbs_level_4', 'items', 'requested_by', 'contact_person', 'construction_contract', 'team');

        return view('admin.requestForInspections.show', compact('requestForInspection'));
    }

    public function destroy(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInspection->delete();

        return back();
    }

    public function massDestroy(MassDestroyRequestForInspectionRequest $request)
    {
        RequestForInspection::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('request_for_inspection_create') && Gate::denies('request_for_inspection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new RequestForInspection();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
