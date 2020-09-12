<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSrtDocumentStatusRequest;
use App\Http\Requests\UpdateSrtDocumentStatusRequest;
use App\Http\Resources\Admin\SrtDocumentStatusResource;
use App\SrtDocumentStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SrtDocumentStatusApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('srt_document_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtDocumentStatusResource(SrtDocumentStatus::all());
    }

    public function store(StoreSrtDocumentStatusRequest $request)
    {
        $srtDocumentStatus = SrtDocumentStatus::create($request->all());

        return (new SrtDocumentStatusResource($srtDocumentStatus))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtDocumentStatus $srtDocumentStatus)
    {
        abort_if(Gate::denies('srt_document_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtDocumentStatusResource($srtDocumentStatus);
    }

    public function update(UpdateSrtDocumentStatusRequest $request, SrtDocumentStatus $srtDocumentStatus)
    {
        $srtDocumentStatus->update($request->all());

        return (new SrtDocumentStatusResource($srtDocumentStatus))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SrtDocumentStatus $srtDocumentStatus)
    {
        abort_if(Gate::denies('srt_document_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtDocumentStatus->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
