<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutLocation;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCloseOutLocationRequest;
use App\Http\Requests\UpdateCloseOutLocationRequest;
use App\Http\Resources\Admin\CloseOutLocationResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutLocationApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('close_out_location_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutLocationResource(CloseOutLocation::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreCloseOutLocationRequest $request)
    {
        $closeOutLocation = CloseOutLocation::create($request->all());

        return (new CloseOutLocationResource($closeOutLocation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CloseOutLocation $closeOutLocation)
    {
        abort_if(Gate::denies('close_out_location_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutLocationResource($closeOutLocation->load(['construction_contract', 'team']));
    }

    public function update(UpdateCloseOutLocationRequest $request, CloseOutLocation $closeOutLocation)
    {
        $closeOutLocation->update($request->all());

        return (new CloseOutLocationResource($closeOutLocation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutLocation $closeOutLocation)
    {
        abort_if(Gate::denies('close_out_location_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutLocation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
