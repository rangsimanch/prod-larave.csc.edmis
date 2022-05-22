<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSrtExternalAgencyDocumentRequest;
use App\Http\Requests\UpdateSrtExternalAgencyDocumentRequest;
use App\Http\Resources\Admin\SrtExternalAgencyDocumentResource;
use App\SrtExternalAgencyDocument;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SrtExternalAgencyDocumentsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('srt_external_agency_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtExternalAgencyDocumentResource(SrtExternalAgencyDocument::with(['construction_contracts', 'team'])->get());
    }

    public function store(StoreSrtExternalAgencyDocumentRequest $request)
    {
        $srtExternalAgencyDocument = SrtExternalAgencyDocument::create($request->all());
        $srtExternalAgencyDocument->construction_contracts()->sync($request->input('construction_contracts', []));
        foreach ($request->input('file_upload', []) as $file) {
            $srtExternalAgencyDocument->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
        }

        return (new SrtExternalAgencyDocumentResource($srtExternalAgencyDocument))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtExternalAgencyDocument $srtExternalAgencyDocument)
    {
        abort_if(Gate::denies('srt_external_agency_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtExternalAgencyDocumentResource($srtExternalAgencyDocument->load(['construction_contracts', 'team']));
    }

    public function update(UpdateSrtExternalAgencyDocumentRequest $request, SrtExternalAgencyDocument $srtExternalAgencyDocument)
    {
        $srtExternalAgencyDocument->update($request->all());
        $srtExternalAgencyDocument->construction_contracts()->sync($request->input('construction_contracts', []));
        if (count($srtExternalAgencyDocument->file_upload) > 0) {
            foreach ($srtExternalAgencyDocument->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $srtExternalAgencyDocument->file_upload->pluck('file_name')->toArray();
        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $srtExternalAgencyDocument->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
            }
        }

        return (new SrtExternalAgencyDocumentResource($srtExternalAgencyDocument))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SrtExternalAgencyDocument $srtExternalAgencyDocument)
    {
        abort_if(Gate::denies('srt_external_agency_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtExternalAgencyDocument->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
