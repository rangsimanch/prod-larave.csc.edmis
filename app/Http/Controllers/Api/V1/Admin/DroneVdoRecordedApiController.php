<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DroneVdoRecorded;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDroneVdoRecordedRequest;
use App\Http\Requests\UpdateDroneVdoRecordedRequest;
use App\Http\Resources\Admin\DroneVdoRecordedResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DroneVdoRecordedApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('drone_vdo_recorded_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DroneVdoRecordedResource(DroneVdoRecorded::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreDroneVdoRecordedRequest $request)
    {
        $droneVdoRecorded = DroneVdoRecorded::create($request->all());

        if ($request->input('file_upload', false)) {
            $droneVdoRecorded->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new DroneVdoRecordedResource($droneVdoRecorded))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DroneVdoRecorded $droneVdoRecorded)
    {
        abort_if(Gate::denies('drone_vdo_recorded_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DroneVdoRecordedResource($droneVdoRecorded->load(['construction_contract', 'team']));
    }

    public function update(UpdateDroneVdoRecordedRequest $request, DroneVdoRecorded $droneVdoRecorded)
    {
        $droneVdoRecorded->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$droneVdoRecorded->file_upload || $request->input('file_upload') !== $droneVdoRecorded->file_upload->file_name) {
                $droneVdoRecorded->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($droneVdoRecorded->file_upload) {
            $droneVdoRecorded->file_upload->delete();
        }

        return (new DroneVdoRecordedResource($droneVdoRecorded))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DroneVdoRecorded $droneVdoRecorded)
    {
        abort_if(Gate::denies('drone_vdo_recorded_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $droneVdoRecorded->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
