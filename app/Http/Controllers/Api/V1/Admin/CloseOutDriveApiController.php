<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutDrive;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCloseOutDriveRequest;
use App\Http\Requests\UpdateCloseOutDriveRequest;
use App\Http\Resources\Admin\CloseOutDriveResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutDriveApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('close_out_drive_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutDriveResource(CloseOutDrive::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreCloseOutDriveRequest $request)
    {
        $closeOutDrive = CloseOutDrive::create($request->all());

        return (new CloseOutDriveResource($closeOutDrive))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CloseOutDrive $closeOutDrive)
    {
        abort_if(Gate::denies('close_out_drive_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutDriveResource($closeOutDrive->load(['construction_contract', 'team']));
    }

    public function update(UpdateCloseOutDriveRequest $request, CloseOutDrive $closeOutDrive)
    {
        $closeOutDrive->update($request->all());

        return (new CloseOutDriveResource($closeOutDrive))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutDrive $closeOutDrive)
    {
        abort_if(Gate::denies('close_out_drive_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutDrive->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
