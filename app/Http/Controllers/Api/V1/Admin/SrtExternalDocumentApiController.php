<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSrtExternalDocumentRequest;
use App\Http\Requests\UpdateSrtExternalDocumentRequest;
use App\Http\Resources\Admin\SrtExternalDocumentResource;
use App\SrtExternalDocument;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SrtExternalDocumentApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('srt_external_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtExternalDocumentResource(SrtExternalDocument::with(['docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team'])->get());
    }

    public function store(StoreSrtExternalDocumentRequest $request)
    {
        $srtExternalDocument = SrtExternalDocument::create($request->all());
        $srtExternalDocument->tos()->sync($request->input('tos', []));

        if ($request->input('file_upload', false)) {
            $srtExternalDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new SrtExternalDocumentResource($srtExternalDocument))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtExternalDocument $srtExternalDocument)
    {
        abort_if(Gate::denies('srt_external_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtExternalDocumentResource($srtExternalDocument->load(['docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team']));
    }

    public function update(UpdateSrtExternalDocumentRequest $request, SrtExternalDocument $srtExternalDocument)
    {
        $srtExternalDocument->update($request->all());
        $srtExternalDocument->tos()->sync($request->input('tos', []));

        if ($request->input('file_upload', false)) {
            if (!$srtExternalDocument->file_upload || $request->input('file_upload') !== $srtExternalDocument->file_upload->file_name) {
                if ($srtExternalDocument->file_upload) {
                    $srtExternalDocument->file_upload->delete();
                }

                $srtExternalDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($srtExternalDocument->file_upload) {
            $srtExternalDocument->file_upload->delete();
        }

        return (new SrtExternalDocumentResource($srtExternalDocument))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SrtExternalDocument $srtExternalDocument)
    {
        abort_if(Gate::denies('srt_external_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtExternalDocument->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
