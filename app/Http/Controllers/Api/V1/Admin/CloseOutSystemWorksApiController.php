<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutSystemWork;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCloseOutSystemWorkRequest;
use App\Http\Requests\UpdateCloseOutSystemWorkRequest;
use App\Http\Resources\Admin\CloseOutSystemWorkResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutSystemWorksApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('close_out_system_work_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutSystemWorkResource(CloseOutSystemWork::with(['relate_building', 'team'])->get());
    }

    public function store(StoreCloseOutSystemWorkRequest $request)
    {
        $closeOutSystemWork = CloseOutSystemWork::create($request->all());

        return (new CloseOutSystemWorkResource($closeOutSystemWork))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CloseOutSystemWork $closeOutSystemWork)
    {
        abort_if(Gate::denies('close_out_system_work_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutSystemWorkResource($closeOutSystemWork->load(['relate_building', 'team']));
    }

    public function update(UpdateCloseOutSystemWorkRequest $request, CloseOutSystemWork $closeOutSystemWork)
    {
        $closeOutSystemWork->update($request->all());

        return (new CloseOutSystemWorkResource($closeOutSystemWork))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutSystemWork $closeOutSystemWork)
    {
        abort_if(Gate::denies('close_out_system_work_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutSystemWork->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
