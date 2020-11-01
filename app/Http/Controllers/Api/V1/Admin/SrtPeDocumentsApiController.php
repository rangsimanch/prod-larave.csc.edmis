<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSrtPeDocumentRequest;
use App\Http\Requests\UpdateSrtPeDocumentRequest;
use App\Http\Resources\Admin\SrtPeDocumentResource;
use App\SrtPeDocument;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SrtPeDocumentsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('srt_pe_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtPeDocumentResource(SrtPeDocument::with(['refer_documents', 'operators', 'team'])->get());
    }

    public function store(StoreSrtPeDocumentRequest $request)
    {
        $srtPeDocument = SrtPeDocument::create($request->all());
        $srtPeDocument->operators()->sync($request->input('operators', []));

        if ($request->input('file_upload', false)) {
            $srtPeDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new SrtPeDocumentResource($srtPeDocument))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtPeDocument $srtPeDocument)
    {
        abort_if(Gate::denies('srt_pe_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtPeDocumentResource($srtPeDocument->load(['refer_documents', 'operators', 'team']));
    }

    public function update(UpdateSrtPeDocumentRequest $request, SrtPeDocument $srtPeDocument)
    {
        $srtPeDocument->update($request->all());
        $srtPeDocument->operators()->sync($request->input('operators', []));

        if ($request->input('file_upload', false)) {
            if (!$srtPeDocument->file_upload || $request->input('file_upload') !== $srtPeDocument->file_upload->file_name) {
                if ($srtPeDocument->file_upload) {
                    $srtPeDocument->file_upload->delete();
                }

                $srtPeDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($srtPeDocument->file_upload) {
            $srtPeDocument->file_upload->delete();
        }

        return (new SrtPeDocumentResource($srtPeDocument))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SrtPeDocument $srtPeDocument)
    {
        abort_if(Gate::denies('srt_pe_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtPeDocument->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
