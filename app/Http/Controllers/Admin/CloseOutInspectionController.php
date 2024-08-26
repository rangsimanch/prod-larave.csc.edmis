<?php

namespace App\Http\Controllers\Admin;

use App\BoQ;
use App\CloseOutInspection;
use App\CloseOutLocation;
use App\CloseOutWorkType;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCloseOutInspectionRequest;
use App\Http\Requests\StoreCloseOutInspectionRequest;
use App\Http\Requests\UpdateCloseOutInspectionRequest;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CloseOutInspectionController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('close_out_inspection_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CloseOutInspection::with(['construction_contract', 'boqs', 'locations', 'work_types', 'reviewer', 'team'])->select(sprintf('%s.*', (new CloseOutInspection)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'close_out_inspection_show';
                $editGate      = 'close_out_inspection_edit';
                $deleteGate    = 'close_out_inspection_delete';
                $crudRoutePart = 'close-out-inspections';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });

            $table->editColumn('boq', function ($row) {
                $labels = [];
                foreach ($row->boqs as $boq) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $boq->name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('location', function ($row) {
                $labels = [];
                foreach ($row->locations as $location) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $location->location_name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('sub_location', function ($row) {
                 $labels = [];
                 $data = [];
                 $data =  explode(",", $row->sub_location);
                 foreach($data as $sub_location_data) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $sub_location_data);
                 }
                return implode(' ', $labels);
            });
            $table->editColumn('work_type', function ($row) {
                $labels = [];
                foreach ($row->work_types as $work_type) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $work_type->work_type_name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('sub_worktype', function ($row) {
                 $labels = [];
                 $data = [];
                 $data =  explode(",", $row->sub_worktype);
                 foreach($data as $sub_worktype_data) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $sub_worktype_data);
                 }
                return implode(' ', $labels);
            });
            $table->editColumn('files_report_before', function ($row) {
                if (! $row->files_report_before) {
                    return '';
                }
                $links = [];
                foreach ($row->files_report_before as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->editColumn('files_report_after', function ($row) {
                if (! $row->files_report_after) {
                    return '';
                }
                $links = [];
                foreach ($row->files_report_after as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('review_status', function ($row) {
                return $row->review_status ? CloseOutInspection::REVIEW_STATUS_SELECT[$row->review_status] : '';
            });

            $table->addColumn('reviewer_name', function ($row) {
                return $row->reviewer ? $row->reviewer->name : '';
            });

            $table->editColumn('document_status', function ($row) {
                 if (CloseOutInspection::DOCUMENT_STATUS_SELECT[$row->document_status] == 'New'){
                    return sprintf('<p style="color:#003399"><b>%s</b></p>', $row->document_status ? CloseOutInspection::DOCUMENT_STATUS_SELECT[$row->document_status] : '');
                }
                else if(CloseOutInspection::DOCUMENT_STATUS_SELECT[$row->document_status] == 'Waiting for Revise'){
                    return sprintf('<p style="color:#E74C3C"><b>%s</b></p>', $row->document_status ? CloseOutInspection::DOCUMENT_STATUS_SELECT[$row->document_status] : '');
                }
                else if(CloseOutInspection::DOCUMENT_STATUS_SELECT[$row->document_status] == 'Done'){
                    return sprintf('<p style="color:#009933"><b>%s</b></p>', $row->document_status ? CloseOutInspection::DOCUMENT_STATUS_SELECT[$row->document_status] : '');
                }
                else{
                    return $row->document_status ? CloseOutInspection::DOCUMENT_STATUS_SELECT[$row->document_status] : '';
                }
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'boq', 'location', 'work_type', 'files_report_before', 'files_report_after', 'reviewer', 'sub_location', 'sub_worktype', 'document_status']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $bo_qs                  = BoQ::get();
        $close_out_locations    = CloseOutLocation::get();
        $close_out_work_types   = CloseOutWorkType::get();
        $users                  = User::get();
        $teams                  = Team::get();

        return view('admin.closeOutInspections.index', compact('construction_contracts', 'bo_qs', 'close_out_locations', 'close_out_work_types', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('close_out_inspection_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boqs = BoQ::pluck('name', 'id');

        $locations = CloseOutLocation::pluck('location_name', 'id');

        $work_types = CloseOutWorkType::pluck('work_type_name', 'id');

        $reviewers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.closeOutInspections.create', compact('boqs', 'construction_contracts', 'locations', 'reviewers', 'work_types'));
    }

    public function store(StoreCloseOutInspectionRequest $request)
    {
        $data = $request->all();
        $data['document_status'] = 1;
        $closeOutInspection = CloseOutInspection::create($data);
        $closeOutInspection->boqs()->sync($request->input('boqs', []));
        $closeOutInspection->locations()->sync($request->input('locations', []));
        $closeOutInspection->work_types()->sync($request->input('work_types', []));
        foreach ($request->input('files_report_before', []) as $file) {
            $closeOutInspection->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_before');
        }

        foreach ($request->input('files_report_after', []) as $file) {
            $closeOutInspection->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_after');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $closeOutInspection->id]);
        }

        return redirect()->route('admin.close-out-inspections.index');
    }

    public function edit(CloseOutInspection $closeOutInspection)
    {
        abort_if(Gate::denies('close_out_inspection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boqs = BoQ::pluck('name', 'id');

        $locations = CloseOutLocation::pluck('location_name', 'id');

        $work_types = CloseOutWorkType::pluck('work_type_name', 'id');

        $reviewers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $closeOutInspection->load('construction_contract', 'boqs', 'locations', 'work_types', 'reviewer', 'team');

        return view('admin.closeOutInspections.edit', compact('boqs', 'closeOutInspection', 'locations', 'reviewers', 'work_types'));
    }

    public function update(UpdateCloseOutInspectionRequest $request, CloseOutInspection $closeOutInspection)
    {
        $data = $request->all();
        $state = $closeOutInspection->document_status;

        // if(($request->filled('review_status') && $data['document_status'] != 3)){
        //     $data['document_status'] = 2;
        // }

        $closeOutInspection->update($data);
        $closeOutInspection->boqs()->sync($request->input('boqs', []));
        $closeOutInspection->locations()->sync($request->input('locations', []));
        $closeOutInspection->work_types()->sync($request->input('work_types', []));
        if (count($closeOutInspection->files_report_before) > 0) {
            foreach ($closeOutInspection->files_report_before as $media) {
                if (! in_array($media->file_name, $request->input('files_report_before', []))) {
                    $media->delete();
                }
            }
        }
        $media = $closeOutInspection->files_report_before->pluck('file_name')->toArray();
        foreach ($request->input('files_report_before', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $closeOutInspection->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_before');
            }
        }

        if (count($closeOutInspection->files_report_after) > 0) {
            foreach ($closeOutInspection->files_report_after as $media) {
                if (! in_array($media->file_name, $request->input('files_report_after', []))) {
                    $media->delete();
                }
            }
        }
        $media = $closeOutInspection->files_report_after->pluck('file_name')->toArray();
        foreach ($request->input('files_report_after', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $closeOutInspection->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_after');
            }
        }

        return redirect()->route('admin.close-out-inspections.index');
    }

    public function show(CloseOutInspection $closeOutInspection)
    {
        abort_if(Gate::denies('close_out_inspection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutInspection->load('construction_contract', 'boqs', 'locations', 'work_types', 'reviewer', 'team');

        return view('admin.closeOutInspections.show', compact('closeOutInspection'));
    }

    public function destroy(CloseOutInspection $closeOutInspection)
    {
        abort_if(Gate::denies('close_out_inspection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutInspection->delete();

        return back();
    }

    public function massDestroy(MassDestroyCloseOutInspectionRequest $request)
    {
        $closeOutInspections = CloseOutInspection::find(request('ids'));

        foreach ($closeOutInspections as $closeOutInspection) {
            $closeOutInspection->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('close_out_inspection_create') && Gate::denies('close_out_inspection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CloseOutInspection();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
