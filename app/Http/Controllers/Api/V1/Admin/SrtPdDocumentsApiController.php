<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSrtPdDocumentRequest;
use App\Http\Requests\UpdateSrtPdDocumentRequest;
use App\Http\Resources\Admin\SrtPdDocumentResource;
use App\SrtPdDocument;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SrtPdDocumentsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('srt_pd_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtPdDocumentResource(SrtPdDocument::with(['refer_documents', 'operators', 'team'])->get());
    }

    public function store(StoreSrtPdDocumentRequest $request)
    {
        $srtPdDocument = SrtPdDocument::create($request->all());
        $srtPdDocument->operators()->sync($request->input('operators', []));

        if ($request->input('file_upload', false)) {
            $srtPdDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new SrtPdDocumentResource($srtPdDocument))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtPdDocument $srtPdDocument)
    {
        abort_if(Gate::denies('srt_pd_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtPdDocumentResource($srtPdDocument->load(['refer_documents', 'operators', 'team']));
    }

    public function update(UpdateSrtPdDocumentRequest $request, SrtPdDocument $srtPdDocument)
    {
        $srtPdDocument->update($request->all());
        $srtPdDocument->operators()->sync($request->input('operators', []));

        if ($request->input('file_upload', false)) {
            if (!$srtPdDocument->file_upload || $request->input('file_upload') !== $srtPdDocument->file_upload->file_name) {
                if ($srtPdDocument->file_upload) {
                    $srtPdDocument->file_upload->delete();
                }

                $srtPdDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($srtPdDocument->file_upload) {
            $srtPdDocument->file_upload->delete();
        }

        return (new SrtPdDocumentResource($srtPdDocument))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SrtPdDocument $srtPdDocument)
    {
        abort_if(Gate::denies('srt_pd_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtPdDocument->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
