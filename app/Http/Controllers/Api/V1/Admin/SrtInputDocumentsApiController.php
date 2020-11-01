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

        return new SrtInputDocumentResource(SrtInputDocument::with(['docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team'])->get());
    }

    public function store(StoreSrtInputDocumentRequest $request)
    {
        $srtInputDocument = SrtInputDocument::create($request->all());
        $srtInputDocument->tos()->sync($request->input('tos', []));

        if ($request->input('file_upload', false)) {
            $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        if ($request->input('file_upload_2', false)) {
            $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_2')))->toMediaCollection('file_upload_2');
        }

        if ($request->input('file_upload_3', false)) {
            $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_3')))->toMediaCollection('file_upload_3');
        }

        if ($request->input('file_upload_4', false)) {
            $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_4')))->toMediaCollection('file_upload_4');
        }

        if ($request->input('complete_file', false)) {
            $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('complete_file')))->toMediaCollection('complete_file');
        }

        return (new SrtInputDocumentResource($srtInputDocument))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtInputDocumentResource($srtInputDocument->load(['docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team']));
    }

    public function update(UpdateSrtInputDocumentRequest $request, SrtInputDocument $srtInputDocument)
    {
        $srtInputDocument->update($request->all());
        $srtInputDocument->tos()->sync($request->input('tos', []));

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

        if ($request->input('file_upload_2', false)) {
            if (!$srtInputDocument->file_upload_2 || $request->input('file_upload_2') !== $srtInputDocument->file_upload_2->file_name) {
                if ($srtInputDocument->file_upload_2) {
                    $srtInputDocument->file_upload_2->delete();
                }

                $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_2')))->toMediaCollection('file_upload_2');
            }
        } elseif ($srtInputDocument->file_upload_2) {
            $srtInputDocument->file_upload_2->delete();
        }

        if ($request->input('file_upload_3', false)) {
            if (!$srtInputDocument->file_upload_3 || $request->input('file_upload_3') !== $srtInputDocument->file_upload_3->file_name) {
                if ($srtInputDocument->file_upload_3) {
                    $srtInputDocument->file_upload_3->delete();
                }

                $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_3')))->toMediaCollection('file_upload_3');
            }
        } elseif ($srtInputDocument->file_upload_3) {
            $srtInputDocument->file_upload_3->delete();
        }

        if ($request->input('file_upload_4', false)) {
            if (!$srtInputDocument->file_upload_4 || $request->input('file_upload_4') !== $srtInputDocument->file_upload_4->file_name) {
                if ($srtInputDocument->file_upload_4) {
                    $srtInputDocument->file_upload_4->delete();
                }

                $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_4')))->toMediaCollection('file_upload_4');
            }
        } elseif ($srtInputDocument->file_upload_4) {
            $srtInputDocument->file_upload_4->delete();
        }

        if ($request->input('complete_file', false)) {
            if (!$srtInputDocument->complete_file || $request->input('complete_file') !== $srtInputDocument->complete_file->file_name) {
                if ($srtInputDocument->complete_file) {
                    $srtInputDocument->complete_file->delete();
                }

                $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $request->input('complete_file')))->toMediaCollection('complete_file');
            }
        } elseif ($srtInputDocument->complete_file) {
            $srtInputDocument->complete_file->delete();
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
