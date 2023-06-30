<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutInspection;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCloseOutInspectionRequest;
use App\Http\Requests\UpdateCloseOutInspectionRequest;
use App\Http\Resources\Admin\CloseOutInspectionResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutInspectionApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('close_out_inspection_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutInspectionResource(CloseOutInspection::with(['construction_contract', 'boqs', 'locations', 'work_types', 'reviewer', 'team'])->get());
    }

    public function store(StoreCloseOutInspectionRequest $request)
    {
        $closeOutInspection = CloseOutInspection::create($request->all());
        $closeOutInspection->boqs()->sync($request->input('boqs', []));
        $closeOutInspection->locations()->sync($request->input('locations', []));
        $closeOutInspection->work_types()->sync($request->input('work_types', []));
        foreach ($request->input('files_report_before', []) as $file) {
            $closeOutInspection->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_before');
        }

        foreach ($request->input('files_report_after', []) as $file) {
            $closeOutInspection->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_after');
        }

        return (new CloseOutInspectionResource($closeOutInspection))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CloseOutInspection $closeOutInspection)
    {
        abort_if(Gate::denies('close_out_inspection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutInspectionResource($closeOutInspection->load(['construction_contract', 'boqs', 'locations', 'work_types', 'reviewer', 'team']));
    }

    public function update(UpdateCloseOutInspectionRequest $request, CloseOutInspection $closeOutInspection)
    {
        $closeOutInspection->update($request->all());
        $closeOutInspection->boqs()->sync($request->input('boqs', []));
        $closeOutInspection->locations()->sync($request->input('locations', []));
        $closeOutInspection->work_types()->sync($request->input('work_types', []));
        if (count($closeOutInspection->files_report_before) > 0) {
            foreach ($closeOutInspection->files_report_before as $media) {
                if (! in_array($media->file_name, $request->input('files_report_before', []))) {
                    $media->delete();
                }
            }
        }
        $media = $closeOutInspection->files_report_before->pluck('file_name')->toArray();
        foreach ($request->input('files_report_before', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $closeOutInspection->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_before');
            }
        }

        if (count($closeOutInspection->files_report_after) > 0) {
            foreach ($closeOutInspection->files_report_after as $media) {
                if (! in_array($media->file_name, $request->input('files_report_after', []))) {
                    $media->delete();
                }
            }
        }
        $media = $closeOutInspection->files_report_after->pluck('file_name')->toArray();
        foreach ($request->input('files_report_after', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $closeOutInspection->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_after');
            }
        }

        return (new CloseOutInspectionResource($closeOutInspection))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutInspection $closeOutInspection)
    {
        abort_if(Gate::denies('close_out_inspection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutInspection->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
