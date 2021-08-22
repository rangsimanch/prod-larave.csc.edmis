<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSrtOtherRequest;
use App\Http\Requests\UpdateSrtOtherRequest;
use App\Http\Resources\Admin\SrtOtherResource;
use App\SrtOther;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SrtOtherApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('srt_other_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtOtherResource(SrtOther::with(['docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team'])->get());
    }

    public function store(StoreSrtOtherRequest $request)
    {
        $srtOther = SrtOther::create($request->all());
        $srtOther->tos()->sync($request->input('tos', []));
        if ($request->input('file_upload', false)) {
            $srtOther->addMedia(storage_path('tmp/uploads/' . basename($request->input('file_upload'))))->toMediaCollection('file_upload');
        }

        return (new SrtOtherResource($srtOther))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SrtOther $srtOther)
    {
        abort_if(Gate::denies('srt_other_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SrtOtherResource($srtOther->load(['docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team']));
    }

    public function update(UpdateSrtOtherRequest $request, SrtOther $srtOther)
    {
        $srtOther->update($request->all());
        $srtOther->tos()->sync($request->input('tos', []));
        if ($request->input('file_upload', false)) {
            if (!$srtOther->file_upload || $request->input('file_upload') !== $srtOther->file_upload->file_name) {
                if ($srtOther->file_upload) {
                    $srtOther->file_upload->delete();
                }
                $srtOther->addMedia(storage_path('tmp/uploads/' . basename($request->input('file_upload'))))->toMediaCollection('file_upload');
            }
        } elseif ($srtOther->file_upload) {
            $srtOther->file_upload->delete();
        }

        return (new SrtOtherResource($srtOther))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SrtOther $srtOther)
    {
        abort_if(Gate::denies('srt_other_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtOther->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
