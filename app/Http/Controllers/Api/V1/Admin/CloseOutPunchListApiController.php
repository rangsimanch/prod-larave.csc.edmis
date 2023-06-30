<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutPunchList;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCloseOutPunchListRequest;
use App\Http\Requests\UpdateCloseOutPunchListRequest;
use App\Http\Resources\Admin\CloseOutPunchListResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutPunchListApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('close_out_punch_list_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutPunchListResource(CloseOutPunchList::with(['construction_contract', 'boqs', 'locations', 'work_types', 'reviewer', 'team'])->get());
    }

    public function store(StoreCloseOutPunchListRequest $request)
    {
        $closeOutPunchList = CloseOutPunchList::create($request->all());
        $closeOutPunchList->boqs()->sync($request->input('boqs', []));
        $closeOutPunchList->locations()->sync($request->input('locations', []));
        $closeOutPunchList->work_types()->sync($request->input('work_types', []));
        foreach ($request->input('files_report_before', []) as $file) {
            $closeOutPunchList->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_before');
        }

        foreach ($request->input('files_report_after', []) as $file) {
            $closeOutPunchList->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_after');
        }

        return (new CloseOutPunchListResource($closeOutPunchList))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CloseOutPunchList $closeOutPunchList)
    {
        abort_if(Gate::denies('close_out_punch_list_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutPunchListResource($closeOutPunchList->load(['construction_contract', 'boqs', 'locations', 'work_types', 'reviewer', 'team']));
    }

    public function update(UpdateCloseOutPunchListRequest $request, CloseOutPunchList $closeOutPunchList)
    {
        $closeOutPunchList->update($request->all());
        $closeOutPunchList->boqs()->sync($request->input('boqs', []));
        $closeOutPunchList->locations()->sync($request->input('locations', []));
        $closeOutPunchList->work_types()->sync($request->input('work_types', []));
        if (count($closeOutPunchList->files_report_before) > 0) {
            foreach ($closeOutPunchList->files_report_before as $media) {
                if (! in_array($media->file_name, $request->input('files_report_before', []))) {
                    $media->delete();
                }
            }
        }
        $media = $closeOutPunchList->files_report_before->pluck('file_name')->toArray();
        foreach ($request->input('files_report_before', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $closeOutPunchList->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_before');
            }
        }

        if (count($closeOutPunchList->files_report_after) > 0) {
            foreach ($closeOutPunchList->files_report_after as $media) {
                if (! in_array($media->file_name, $request->input('files_report_after', []))) {
                    $media->delete();
                }
            }
        }
        $media = $closeOutPunchList->files_report_after->pluck('file_name')->toArray();
        foreach ($request->input('files_report_after', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $closeOutPunchList->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files_report_after');
            }
        }

        return (new CloseOutPunchListResource($closeOutPunchList))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutPunchList $closeOutPunchList)
    {
        abort_if(Gate::denies('close_out_punch_list_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutPunchList->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
