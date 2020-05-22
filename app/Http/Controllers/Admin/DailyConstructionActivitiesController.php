<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\DailyConstructionActivity;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDailyConstructionActivityRequest;
use App\Http\Requests\StoreDailyConstructionActivityRequest;
use App\Http\Requests\UpdateDailyConstructionActivityRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DailyConstructionActivitiesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('daily_construction_activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DailyConstructionActivity::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new DailyConstructionActivity)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'daily_construction_activity_show';
                $editGate      = 'daily_construction_activity_edit';
                $deleteGate    = 'daily_construction_activity_delete';
                $crudRoutePart = 'daily-construction-activities';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('work_title', function ($row) {
                return $row->work_title ? $row->work_title : "";
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('image_upload', function ($row) {
                if (!$row->image_upload) {
                    return '';
                }

                $links = [];
                
                 foreach ($row->image_upload as $media) {
                        $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';      
                 }
                    

                  return implode(' ', $links);
                //  return $links;
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'image_upload']);

            return $table->make(true);
        }

        return view('admin.dailyConstructionActivities.index');
    }

    public function create()
    {
        abort_if(Gate::denies('daily_construction_activity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.dailyConstructionActivities.create', compact('construction_contracts'));
    }

    public function store(StoreDailyConstructionActivityRequest $request)
    {
        $dailyConstructionActivity = DailyConstructionActivity::create($request->all());

        foreach ($request->input('image_upload', []) as $file) {
            $dailyConstructionActivity->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('image_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $dailyConstructionActivity->id]);
        }

        return redirect()->route('admin.daily-construction-activities.index');
    }

    public function edit(DailyConstructionActivity $dailyConstructionActivity)
    {
        abort_if(Gate::denies('daily_construction_activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $dailyConstructionActivity->load('construction_contract', 'team');

        return view('admin.dailyConstructionActivities.edit', compact('construction_contracts', 'dailyConstructionActivity'));
    }

    public function update(UpdateDailyConstructionActivityRequest $request, DailyConstructionActivity $dailyConstructionActivity)
    {
        $dailyConstructionActivity->update($request->all());

        if (count($dailyConstructionActivity->image_upload) > 0) {
            foreach ($dailyConstructionActivity->image_upload as $media) {
                if (!in_array($media->file_name, $request->input('image_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $dailyConstructionActivity->image_upload->pluck('file_name')->toArray();

        foreach ($request->input('image_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $dailyConstructionActivity->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('image_upload');
            }
        }

        return redirect()->route('admin.daily-construction-activities.index');
    }

    public function show(DailyConstructionActivity $dailyConstructionActivity)
    {
        abort_if(Gate::denies('daily_construction_activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyConstructionActivity->load('construction_contract', 'team');

        return view('admin.dailyConstructionActivities.show', compact('dailyConstructionActivity'));
    }

    public function destroy(DailyConstructionActivity $dailyConstructionActivity)
    {
        abort_if(Gate::denies('daily_construction_activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyConstructionActivity->delete();

        return back();
    }

    public function massDestroy(MassDestroyDailyConstructionActivityRequest $request)
    {
        DailyConstructionActivity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('daily_construction_activity_create') && Gate::denies('daily_construction_activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DailyConstructionActivity();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
