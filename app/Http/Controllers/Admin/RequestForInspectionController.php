<?php

namespace App\Http\Controllers\Admin;

use App\BoQ;
use App\BoqItem;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
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
use App\WbsLevelTwo;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RequestForInspectionController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('request_for_inspection_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RequestForInspection::with(['bill', 'item', 'wbs_level_1', 'wbs_level_2', 'wbs_level_3', 'wbs_level_4', 'contact_person', 'requested_by', 'construction_contract', 'team'])->select(sprintf('%s.*', (new RequestForInspection)->table));
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

            $table->addColumn('bill_name', function ($row) {
                return $row->bill ? $row->bill->name : '';
            });

            $table->addColumn('item_name', function ($row) {
                return $row->item ? $row->item->name : '';
            });

            $table->addColumn('wbs_level_1_name', function ($row) {
                return $row->wbs_level_1 ? $row->wbs_level_1->name : '';
            });

            $table->addColumn('wbs_level_2_name', function ($row) {
                return $row->wbs_level_2 ? $row->wbs_level_2->name : '';
            });

            $table->addColumn('wbs_level_3_wbs_level_3_name', function ($row) {
                return $row->wbs_level_3 ? $row->wbs_level_3->wbs_level_3_name : '';
            });

            $table->addColumn('wbs_level_4_wbs_level_4_name', function ($row) {
                return $row->wbs_level_4 ? $row->wbs_level_4->wbs_level_4_name : '';
            });

            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : "";
            });
            $table->editColumn('item_no', function ($row) {
                return $row->item_no ? $row->item_no : "";
            });
            $table->editColumn('ref_no', function ($row) {
                return $row->ref_no ? $row->ref_no : "";
            });

            $table->addColumn('contact_person_name', function ($row) {
                return $row->contact_person ? $row->contact_person->name : '';
            });

            $table->editColumn('type_of_work', function ($row) {
                return $row->type_of_work ? RequestForInspection::TYPE_OF_WORK_SELECT[$row->type_of_work] : '';
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : "";
            });
            $table->editColumn('ref_specification', function ($row) {
                return $row->ref_specification ? $row->ref_specification : "";
            });
            $table->addColumn('requested_by_name', function ($row) {
                return $row->requested_by ? $row->requested_by->name : '';
            });

            $table->editColumn('result_of_inspection', function ($row) {
                return $row->result_of_inspection ? RequestForInspection::RESULT_OF_INSPECTION_SELECT[$row->result_of_inspection] : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : "";
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

            $table->rawColumns(['actions', 'placeholder', 'bill', 'item', 'wbs_level_1', 'wbs_level_2', 'wbs_level_3', 'wbs_level_4', 'contact_person', 'requested_by', 'construction_contract', 'files_upload']);

            return $table->make(true);
        }

        $bo_qs                  = BoQ::get();
        $boq_items              = BoqItem::get();
        $wbs_level_ones         = WbsLevelOne::get();
        $wbs_level_twos         = WbsLevelTwo::get();
        $wbs_level_threes       = WbsLevelThree::get();
        $wbslevelfours          = Wbslevelfour::get();
        $users                  = User::get();
        $users                  = User::get();
        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();

        return view('admin.requestForInspections.index', compact('bo_qs', 'boq_items', 'wbs_level_ones', 'wbs_level_twos', 'wbs_level_threes', 'wbslevelfours', 'users', 'users', 'construction_contracts', 'teams'));
    }

    function fetcher(Request $request){
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

    public function create()
    {
        abort_if(Gate::denies('request_for_inspection_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bills = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $items = BoqItem::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_1s = WbsLevelOne::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_2s = WbsLevelTwo::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contact_people = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $requested_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.requestForInspections.create', compact('bills', 'items', 'wbs_level_1s', 'wbs_level_2s', 'wbs_level_3s', 'wbs_level_4s', 'contact_people', 'requested_bies', 'construction_contracts'));
    }

    public function store(StoreRequestForInspectionRequest $request)
    {
        $requestForInspection = RequestForInspection::create($request->all());

        foreach ($request->input('files_upload', []) as $file) {
            $requestForInspection->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('files_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $requestForInspection->id]);
        }

        return redirect()->route('admin.request-for-inspections.index');
    }

    public function edit(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bills = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $items = BoqItem::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_1s = WbsLevelOne::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_2s = WbsLevelTwo::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_3s = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = Wbslevelfour::all()->pluck('wbs_level_4_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contact_people = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $requested_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $requestForInspection->load('bill', 'item', 'wbs_level_1', 'wbs_level_2', 'wbs_level_3', 'wbs_level_4', 'contact_person', 'requested_by', 'construction_contract', 'team');

        return view('admin.requestForInspections.edit', compact('bills', 'items', 'wbs_level_1s', 'wbs_level_2s', 'wbs_level_3s', 'wbs_level_4s', 'contact_people', 'requested_bies', 'construction_contracts', 'requestForInspection'));
    }

    public function update(UpdateRequestForInspectionRequest $request, RequestForInspection $requestForInspection)
    {
        $requestForInspection->update($request->all());

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

        return redirect()->route('admin.request-for-inspections.index');
    }

    public function show(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInspection->load('bill', 'item', 'wbs_level_1', 'wbs_level_2', 'wbs_level_3', 'wbs_level_4', 'contact_person', 'requested_by', 'construction_contract', 'team');

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
