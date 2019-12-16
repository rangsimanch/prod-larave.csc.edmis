<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRfaDocumentStatusRequest;
use App\Http\Requests\UpdateRfaDocumentStatusRequest;
use App\Http\Resources\Admin\RfaDocumentStatusResource;
use App\RfaDocumentStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfaDocumentStatusApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('rfa_document_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RfaDocumentStatusResource(RfaDocumentStatus::all());
    }

    public function store(StoreRfaDocumentStatusRequest $request)
    {
        $rfaDocumentStatus = RfaDocumentStatus::create($request->all());

        return (new RfaDocumentStatusResource($rfaDocumentStatus))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RfaDocumentStatus $rfaDocumentStatus)
    {
        abort_if(Gate::denies('rfa_document_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RfaDocumentStatusResource($rfaDocumentStatus);
    }

    public function update(UpdateRfaDocumentStatusRequest $request, RfaDocumentStatus $rfaDocumentStatus)
    {
        $rfaDocumentStatus->update($request->all());

        return (new RfaDocumentStatusResource($rfaDocumentStatus))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RfaDocumentStatus $rfaDocumentStatus)
    {
        abort_if(Gate::denies('rfa_document_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfaDocumentStatus->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
