<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\DroneVdoRecorded;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDroneVdoRecordedRequest;
use App\Http\Requests\StoreDroneVdoRecordedRequest;
use App\Http\Requests\UpdateDroneVdoRecordedRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DroneVdoRecordedController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('drone_vdo_recorded_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DroneVdoRecorded::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new DroneVdoRecorded)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'drone_vdo_recorded_show';
                $editGate      = 'drone_vdo_recorded_edit';
                $deleteGate    = 'drone_vdo_recorded_delete';
                $crudRoutePart = 'drone-vdo-recordeds';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('work_tiltle', function ($row) {
                return $row->work_tiltle ? $row->work_tiltle : "";
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

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'file_upload']);

            return $table->make(true);
        }

        return view('admin.droneVdoRecordeds.index');
    }

    public function create()
    {
        abort_if(Gate::denies('drone_vdo_recorded_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.droneVdoRecordeds.create', compact('construction_contracts'));
    }

    public function store(StoreDroneVdoRecordedRequest $request)
    {
        $droneVdoRecorded = DroneVdoRecorded::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $droneVdoRecorded->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $droneVdoRecorded->id]);
        }

        return redirect()->route('admin.drone-vdo-recordeds.index');
    }

    public function edit(DroneVdoRecorded $droneVdoRecorded)
    {
        abort_if(Gate::denies('drone_vdo_recorded_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $droneVdoRecorded->load('construction_contract', 'team');

        return view('admin.droneVdoRecordeds.edit', compact('construction_contracts', 'droneVdoRecorded'));
    }

    public function update(UpdateDroneVdoRecordedRequest $request, DroneVdoRecorded $droneVdoRecorded)
    {
        $droneVdoRecorded->update($request->all());

        if (count($droneVdoRecorded->file_upload) > 0) {
            foreach ($droneVdoRecorded->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $droneVdoRecorded->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $droneVdoRecorded->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.drone-vdo-recordeds.index');
    }

    public function show(DroneVdoRecorded $droneVdoRecorded)
    {
        abort_if(Gate::denies('drone_vdo_recorded_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $droneVdoRecorded->load('construction_contract', 'team');

        return view('admin.droneVdoRecordeds.show', compact('droneVdoRecorded'));
    }

    public function destroy(DroneVdoRecorded $droneVdoRecorded)
    {
        abort_if(Gate::denies('drone_vdo_recorded_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $droneVdoRecorded->delete();

        return back();
    }

    public function massDestroy(MassDestroyDroneVdoRecordedRequest $request)
    {
        DroneVdoRecorded::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('drone_vdo_recorded_create') && Gate::denies('drone_vdo_recorded_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DroneVdoRecorded();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
