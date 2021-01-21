<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ContractAndComponent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreContractAndComponentRequest;
use App\Http\Requests\UpdateContractAndComponentRequest;
use App\Http\Resources\Admin\ContractAndComponentResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContractAndComponentsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('contract_and_component_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ContractAndComponentResource(ContractAndComponent::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreContractAndComponentRequest $request)
    {
        $contractAndComponent = ContractAndComponent::create($request->all());

        if ($request->input('file_upload', false)) {
            $contractAndComponent->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new ContractAndComponentResource($contractAndComponent))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ContractAndComponent $contractAndComponent)
    {
        abort_if(Gate::denies('contract_and_component_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ContractAndComponentResource($contractAndComponent->load(['construction_contract', 'team']));
    }

    public function update(UpdateContractAndComponentRequest $request, ContractAndComponent $contractAndComponent)
    {
        $contractAndComponent->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$contractAndComponent->file_upload || $request->input('file_upload') !== $contractAndComponent->file_upload->file_name) {
                if ($contractAndComponent->file_upload) {
                    $contractAndComponent->file_upload->delete();
                }

                $contractAndComponent->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($contractAndComponent->file_upload) {
            $contractAndComponent->file_upload->delete();
        }

        return (new ContractAndComponentResource($contractAndComponent))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ContractAndComponent $contractAndComponent)
    {
        abort_if(Gate::denies('contract_and_component_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contractAndComponent->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
