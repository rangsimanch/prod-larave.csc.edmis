<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreNcrRequest;
use App\Http\Requests\UpdateNcrRequest;
use App\Http\Resources\Admin\NcrResource;
use App\Ncr;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NcrApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ncr_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NcrResource(Ncr::with(['construction_contract', 'corresponding_ncn', 'prepared_by', 'contractor_manager', 'issue_by', 'construction_specialist', 'related_specialist', 'leader', 'team'])->get());
    }

    public function store(StoreNcrRequest $request)
    {
        $ncr = Ncr::create($request->all());

        foreach ($request->input('rootcase_image', []) as $file) {
            $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
        }

        foreach ($request->input('containment_image', []) as $file) {
            $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
        }

        foreach ($request->input('corrective_image', []) as $file) {
            $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
        }

        foreach ($request->input('file_attachment', []) as $file) {
            $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
        }

        return (new NcrResource($ncr))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ncr $ncr)
    {
        abort_if(Gate::denies('ncr_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NcrResource($ncr->load(['construction_contract', 'corresponding_ncn', 'prepared_by', 'contractor_manager', 'issue_by', 'construction_specialist', 'related_specialist', 'leader', 'team']));
    }

    public function update(UpdateNcrRequest $request, Ncr $ncr)
    {
        $ncr->update($request->all());

        if (count($ncr->rootcase_image) > 0) {
            foreach ($ncr->rootcase_image as $media) {
                if (!in_array($media->file_name, $request->input('rootcase_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncr->rootcase_image->pluck('file_name')->toArray();
        foreach ($request->input('rootcase_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
            }
        }

        if (count($ncr->containment_image) > 0) {
            foreach ($ncr->containment_image as $media) {
                if (!in_array($media->file_name, $request->input('containment_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncr->containment_image->pluck('file_name')->toArray();
        foreach ($request->input('containment_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
            }
        }

        if (count($ncr->corrective_image) > 0) {
            foreach ($ncr->corrective_image as $media) {
                if (!in_array($media->file_name, $request->input('corrective_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncr->corrective_image->pluck('file_name')->toArray();
        foreach ($request->input('corrective_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
            }
        }

        if (count($ncr->file_attachment) > 0) {
            foreach ($ncr->file_attachment as $media) {
                if (!in_array($media->file_name, $request->input('file_attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncr->file_attachment->pluck('file_name')->toArray();
        foreach ($request->input('file_attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
            }
        }

        return (new NcrResource($ncr))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Ncr $ncr)
    {
        abort_if(Gate::denies('ncr_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ncr->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
