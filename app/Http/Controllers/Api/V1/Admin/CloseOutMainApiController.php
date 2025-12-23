<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutMain;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCloseOutMainRequest;
use App\Http\Requests\UpdateCloseOutMainRequest;
use App\Http\Resources\Admin\CloseOutMainResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutMainApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('close_out_main_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutMainResource(CloseOutMain::with(['construction_contract', 'closeout_subject', 'closeout_urls', 'ref_rfas', 'team'])->get());
    }

    public function store(StoreCloseOutMainRequest $request)
    {
        $closeOutMain = CloseOutMain::create($request->all());
        $closeOutMain->closeout_urls()->sync($request->input('closeout_urls', []));
        $closeOutMain->ref_rfas()->sync($request->input('ref_rfas', []));
        foreach ($request->input('final_file', []) as $file) {
            $closeOutMain->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('final_file');
        }

        return (new CloseOutMainResource($closeOutMain))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CloseOutMain $closeOutMain)
    {
        abort_if(Gate::denies('close_out_main_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutMainResource($closeOutMain->load(['construction_contract', 'closeout_subject', 'closeout_urls', 'ref_rfas', 'team']));
    }

    public function update(UpdateCloseOutMainRequest $request, CloseOutMain $closeOutMain)
    {
        $closeOutMain->update($request->all());
        $closeOutMain->closeout_urls()->sync($request->input('closeout_urls', []));
        $closeOutMain->ref_rfas()->sync($request->input('ref_rfas', []));
        if (count($closeOutMain->final_file) > 0) {
            foreach ($closeOutMain->final_file as $media) {
                if (! in_array($media->file_name, $request->input('final_file', []))) {
                    $media->delete();
                }
            }
        }
        $media = $closeOutMain->final_file->pluck('file_name')->toArray();
        foreach ($request->input('final_file', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $closeOutMain->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('final_file');
            }
        }

        return (new CloseOutMainResource($closeOutMain))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutMain $closeOutMain)
    {
        abort_if(Gate::denies('close_out_main_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutMain->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
