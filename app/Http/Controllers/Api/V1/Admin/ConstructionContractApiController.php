<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConstructionContractRequest;
use App\Http\Requests\UpdateConstructionContractRequest;
use App\Http\Resources\Admin\ConstructionContractResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConstructionContractApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('construction_contract_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConstructionContractResource(ConstructionContract::with(['works_code'])->get());
    }

    public function store(StoreConstructionContractRequest $request)
    {
        $constructionContract = ConstructionContract::create($request->all());

        return (new ConstructionContractResource($constructionContract))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ConstructionContract $constructionContract)
    {
        abort_if(Gate::denies('construction_contract_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConstructionContractResource($constructionContract->load(['works_code']));
    }

    public function update(UpdateConstructionContractRequest $request, ConstructionContract $constructionContract)
    {
        $constructionContract->update($request->all());

        return (new ConstructionContractResource($constructionContract))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ConstructionContract $constructionContract)
    {
        abort_if(Gate::denies('construction_contract_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constructionContract->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
