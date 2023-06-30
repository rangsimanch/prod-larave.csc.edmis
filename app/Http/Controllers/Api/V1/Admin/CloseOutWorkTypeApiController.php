<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutWorkType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCloseOutWorkTypeRequest;
use App\Http\Requests\UpdateCloseOutWorkTypeRequest;
use App\Http\Resources\Admin\CloseOutWorkTypeResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutWorkTypeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('close_out_work_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutWorkTypeResource(CloseOutWorkType::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreCloseOutWorkTypeRequest $request)
    {
        $closeOutWorkType = CloseOutWorkType::create($request->all());

        return (new CloseOutWorkTypeResource($closeOutWorkType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CloseOutWorkType $closeOutWorkType)
    {
        abort_if(Gate::denies('close_out_work_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutWorkTypeResource($closeOutWorkType->load(['construction_contract', 'team']));
    }

    public function update(UpdateCloseOutWorkTypeRequest $request, CloseOutWorkType $closeOutWorkType)
    {
        $closeOutWorkType->update($request->all());

        return (new CloseOutWorkTypeResource($closeOutWorkType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutWorkType $closeOutWorkType)
    {
        abort_if(Gate::denies('close_out_work_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutWorkType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
