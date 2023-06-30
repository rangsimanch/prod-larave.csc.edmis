<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutBuilding;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCloseOutBuildingRequest;
use App\Http\Requests\UpdateCloseOutBuildingRequest;
use App\Http\Resources\Admin\CloseOutBuildingResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutBuildingApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('close_out_building_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutBuildingResource(CloseOutBuilding::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreCloseOutBuildingRequest $request)
    {
        $closeOutBuilding = CloseOutBuilding::create($request->all());

        return (new CloseOutBuildingResource($closeOutBuilding))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CloseOutBuilding $closeOutBuilding)
    {
        abort_if(Gate::denies('close_out_building_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutBuildingResource($closeOutBuilding->load(['construction_contract', 'team']));
    }

    public function update(UpdateCloseOutBuildingRequest $request, CloseOutBuilding $closeOutBuilding)
    {
        $closeOutBuilding->update($request->all());

        return (new CloseOutBuildingResource($closeOutBuilding))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutBuilding $closeOutBuilding)
    {
        abort_if(Gate::denies('close_out_building_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutBuilding->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
