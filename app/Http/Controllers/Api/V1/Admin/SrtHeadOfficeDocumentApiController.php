<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSrtHeadOfficeDocumentRequest;
use App\Http\Requests\UpdateSrtHeadOfficeDocumentRequest;
use App\Http\Resources\Admin\SrtHeadOfficeDocumentResource;
use App\SrtHeadOfficeDocument;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SrtHeadOfficeDocumentApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('srt_head_office_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtHeadOfficeDocumentResource(SrtHeadOfficeDocument::with(['refer_documents', 'operator', 'team'])->get());
    }

    public function store(StoreSrtHeadOfficeDocumentRequest $request)
    {
        $srtHeadOfficeDocument = SrtHeadOfficeDocument::create($request->all());

        if ($request->input('file_upload', false)) {
            $srtHeadOfficeDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new SrtHeadOfficeDocumentResource($srtHeadOfficeDocument))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        abort_if(Gate::denies('srt_head_office_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtHeadOfficeDocumentResource($srtHeadOfficeDocument->load(['refer_documents', 'operator', 'team']));
    }

    public function update(UpdateSrtHeadOfficeDocumentRequest $request, SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        $srtHeadOfficeDocument->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$srtHeadOfficeDocument->file_upload || $request->input('file_upload') !== $srtHeadOfficeDocument->file_upload->file_name) {
                if ($srtHeadOfficeDocument->file_upload) {
                    $srtHeadOfficeDocument->file_upload->delete();
                }

                $srtHeadOfficeDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($srtHeadOfficeDocument->file_upload) {
            $srtHeadOfficeDocument->file_upload->delete();
        }

        return (new SrtHeadOfficeDocumentResource($srtHeadOfficeDocument))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        abort_if(Gate::denies('srt_head_office_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtHeadOfficeDocument->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
