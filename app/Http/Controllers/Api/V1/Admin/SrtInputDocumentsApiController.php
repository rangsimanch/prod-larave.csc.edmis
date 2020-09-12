<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSrtInputDocumentRequest;
use App\Http\Requests\UpdateSrtInputDocumentRequest;
use App\Http\Resources\Admin\SrtInputDocumentResource;
use App\SrtInputDocument;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SrtInputDocumentsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('srt_input_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtInputDocumentResource(SrtInputDocument::with(['docuement_status', 'team'])->get());
    }

    public function store(StoreSrtInputDocumentRequest $request)
    {
        $srtInputDocument = SrtInputDocument::create($request->all());

        if ($request->input('file_upload', false)) {
            $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new SrtInputDocumentResource($srtInputDocument))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtInputDocumentResource($srtInputDocument->load(['docuement_status', 'team']));
    }

    public function update(UpdateSrtInputDocumentRequest $request, SrtInputDocument $srtInputDocument)
    {
        $srtInputDocument->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$srtInputDocument->file_upload || $request->input('file_upload') !== $srtInputDocument->file_upload->file_name) {
                if ($srtInputDocument->file_upload) {
                    $srtInputDocument->file_upload->delete();
                }

                $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($srtInputDocument->file_upload) {
            $srtInputDocument->file_upload->delete();
        }

        return (new SrtInputDocumentResource($srtInputDocument))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtInputDocument->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
