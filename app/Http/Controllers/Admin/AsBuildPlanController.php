<?php

namespace App\Http\Controllers\Admin;

use App\AddDrawing;
use App\AsBuildPlan;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAsBuildPlanRequest;
use App\Http\Requests\StoreAsBuildPlanRequest;
use App\Http\Requests\UpdateAsBuildPlanRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Team;


class AsBuildPlanController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('as_build_plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AsBuildPlan::with(['drawing_references', 'construction_contract', 'team'])->select(sprintf('%s.*', (new AsBuildPlan)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'as_build_plan_show';
                $editGate      = 'as_build_plan_edit';
                $deleteGate    = 'as_build_plan_delete';
                $crudRoutePart = 'as-build-plans';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('shop_drawing_title', function ($row) {
                return $row->shop_drawing_title ? $row->shop_drawing_title : "";
            });
            $table->editColumn('drawing_reference', function ($row) {
                $labels = [];

                foreach ($row->drawing_references as $drawing_reference) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $drawing_reference->coding_of_drawing);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('coding_of_shop_drawing', function ($row) {
                return $row->coding_of_shop_drawing ? $row->coding_of_shop_drawing : "";
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('file_upload', function ($row) {
                if (!$row->file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('special_file_upload', function ($row) {
                if (!$row->special_file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->special_file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'drawing_reference', 'construction_contract', 'file_upload', 'special_file_upload']);

            return $table->make(true);
        }

        $add_drawings           = AddDrawing::get()->pluck('coding_of_drawing')->toArray();
        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('name')->toArray();

        return view('admin.asBuildPlans.index', compact('add_drawings', 'construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('as_build_plan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $drawing_references = AddDrawing::all()->pluck('coding_of_drawing', 'id');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.asBuildPlans.create', compact('drawing_references', 'construction_contracts'));
    }

    public function store(StoreAsBuildPlanRequest $request)
    {
        $asBuildPlan = AsBuildPlan::create($request->all());
        $asBuildPlan->drawing_references()->sync($request->input('drawing_references', []));

        foreach ($request->input('file_upload', []) as $file) {
            $asBuildPlan->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        foreach ($request->input('special_file_upload', []) as $file) {
            $asBuildPlan->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('special_file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $asBuildPlan->id]);
        }

        return redirect()->route('admin.as-build-plans.index');
    }

    public function edit(AsBuildPlan $asBuildPlan)
    {
        abort_if(Gate::denies('as_build_plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $drawing_references = AddDrawing::all()->pluck('coding_of_drawing', 'id');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $asBuildPlan->load('drawing_references', 'construction_contract', 'team');

        return view('admin.asBuildPlans.edit', compact('drawing_references', 'construction_contracts', 'asBuildPlan'));
    }

    public function update(UpdateAsBuildPlanRequest $request, AsBuildPlan $asBuildPlan)
    {
        $asBuildPlan->update($request->all());
        $asBuildPlan->drawing_references()->sync($request->input('drawing_references', []));

        if (count($asBuildPlan->file_upload) > 0) {
            foreach ($asBuildPlan->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $asBuildPlan->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $asBuildPlan->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        if (count($asBuildPlan->special_file_upload) > 0) {
            foreach ($asBuildPlan->special_file_upload as $media) {
                if (!in_array($media->file_name, $request->input('special_file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $asBuildPlan->special_file_upload->pluck('file_name')->toArray();

        foreach ($request->input('special_file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $asBuildPlan->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('special_file_upload');
            }
        }

        return redirect()->route('admin.as-build-plans.index');
    }

    public function show(AsBuildPlan $asBuildPlan)
    {
        abort_if(Gate::denies('as_build_plan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asBuildPlan->load('drawing_references', 'construction_contract', 'team');

        return view('admin.asBuildPlans.show', compact('asBuildPlan'));
    }

    public function destroy(AsBuildPlan $asBuildPlan)
    {
        abort_if(Gate::denies('as_build_plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asBuildPlan->delete();

        return back();
    }

    public function massDestroy(MassDestroyAsBuildPlanRequest $request)
    {
        AsBuildPlan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('as_build_plan_create') && Gate::denies('as_build_plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AsBuildPlan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
