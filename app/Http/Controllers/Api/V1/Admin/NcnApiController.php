<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreNcnRequest;
use App\Http\Requests\UpdateNcnRequest;
use App\Http\Resources\Admin\NcnResource;
use App\Ncn;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NcnApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ncn_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NcnResource(Ncn::with(['construction_contract', 'issue_by', 'related_specialist', 'leader', 'construction_specialist', 'team'])->get());
    }

    public function store(StoreNcnRequest $request)
    {
        $ncn = Ncn::create($request->all());

        foreach ($request->input('description_image', []) as $file) {
            $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
        }

        foreach ($request->input('file_attachment', []) as $file) {
            $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
        }

        return (new NcnResource($ncn))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ncn $ncn)
    {
        abort_if(Gate::denies('ncn_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NcnResource($ncn->load(['construction_contract', 'issue_by', 'related_specialist', 'leader', 'construction_specialist', 'team']));
    }

    public function update(UpdateNcnRequest $request, Ncn $ncn)
    {
        $ncn->update($request->all());

        if (count($ncn->description_image) > 0) {
            foreach ($ncn->description_image as $media) {
                if (!in_array($media->file_name, $request->input('description_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncn->description_image->pluck('file_name')->toArray();
        foreach ($request->input('description_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
            }
        }

        if (count($ncn->file_attachment) > 0) {
            foreach ($ncn->file_attachment as $media) {
                if (!in_array($media->file_name, $request->input('file_attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncn->file_attachment->pluck('file_name')->toArray();
        foreach ($request->input('file_attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
            }
        }

        return (new NcnResource($ncn))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Ncn $ncn)
    {
        abort_if(Gate::denies('ncn_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ncn->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
