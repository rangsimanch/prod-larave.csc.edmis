<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreNonConformanceNoticeRequest;
use App\Http\Requests\UpdateNonConformanceNoticeRequest;
use App\Http\Resources\Admin\NonConformanceNoticeResource;
use App\NonConformanceNotice;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NonConformanceNoticeApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('non_conformance_notice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NonConformanceNoticeResource(NonConformanceNotice::with(['csc_issuers', 'team'])->get());
    }

    public function store(StoreNonConformanceNoticeRequest $request)
    {
        $nonConformanceNotice = NonConformanceNotice::create($request->all());

        if ($request->input('attachment', false)) {
            $nonConformanceNotice->addMedia(storage_path('tmp/uploads/' . $request->input('attachment')))->toMediaCollection('attachment');
        }

        if ($request->input('file_upload', false)) {
            $nonConformanceNotice->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new NonConformanceNoticeResource($nonConformanceNotice))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(NonConformanceNotice $nonConformanceNotice)
    {
        abort_if(Gate::denies('non_conformance_notice_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NonConformanceNoticeResource($nonConformanceNotice->load(['csc_issuers', 'team']));
    }

    public function update(UpdateNonConformanceNoticeRequest $request, NonConformanceNotice $nonConformanceNotice)
    {
        $nonConformanceNotice->update($request->all());

        if ($request->input('attachment', false)) {
            if (!$nonConformanceNotice->attachment || $request->input('attachment') !== $nonConformanceNotice->attachment->file_name) {
                if ($nonConformanceNotice->attachment) {
                    $nonConformanceNotice->attachment->delete();
                }

                $nonConformanceNotice->addMedia(storage_path('tmp/uploads/' . $request->input('attachment')))->toMediaCollection('attachment');
            }
        } elseif ($nonConformanceNotice->attachment) {
            $nonConformanceNotice->attachment->delete();
        }

        if ($request->input('file_upload', false)) {
            if (!$nonConformanceNotice->file_upload || $request->input('file_upload') !== $nonConformanceNotice->file_upload->file_name) {
                if ($nonConformanceNotice->file_upload) {
                    $nonConformanceNotice->file_upload->delete();
                }

                $nonConformanceNotice->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($nonConformanceNotice->file_upload) {
            $nonConformanceNotice->file_upload->delete();
        }

        return (new NonConformanceNoticeResource($nonConformanceNotice))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(NonConformanceNotice $nonConformanceNotice)
    {
        abort_if(Gate::denies('non_conformance_notice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonConformanceNotice->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
