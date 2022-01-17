<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSwnRequest;
use App\Http\Requests\UpdateSwnRequest;
use App\Http\Resources\Admin\SwnResource;
use App\Swn;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SwnApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('swn_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SwnResource(Swn::with(['construction_contract', 'issue_by', 'responsible', 'related_specialist', 'leader', 'construction_specialist', 'team'])->get());
    }

    public function store(StoreSwnRequest $request)
    {
        $swn = Swn::create($request->all());

        foreach ($request->input('document_attachment', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('document_attachment');
        }

        foreach ($request->input('description_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
        }

        foreach ($request->input('rootcase_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
        }

        foreach ($request->input('containment_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
        }

        foreach ($request->input('corrective_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
        }

        return (new SwnResource($swn))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Swn $swn)
    {
        abort_if(Gate::denies('swn_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SwnResource($swn->load(['construction_contract', 'issue_by', 'responsible', 'related_specialist', 'leader', 'construction_specialist', 'team']));
    }

    public function update(UpdateSwnRequest $request, Swn $swn)
    {
        $swn->update($request->all());

        if (count($swn->document_attachment) > 0) {
            foreach ($swn->document_attachment as $media) {
                if (!in_array($media->file_name, $request->input('document_attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->document_attachment->pluck('file_name')->toArray();
        foreach ($request->input('document_attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('document_attachment');
            }
        }

        if (count($swn->description_image) > 0) {
            foreach ($swn->description_image as $media) {
                if (!in_array($media->file_name, $request->input('description_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->description_image->pluck('file_name')->toArray();
        foreach ($request->input('description_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
            }
        }

        if (count($swn->rootcase_image) > 0) {
            foreach ($swn->rootcase_image as $media) {
                if (!in_array($media->file_name, $request->input('rootcase_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->rootcase_image->pluck('file_name')->toArray();
        foreach ($request->input('rootcase_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
            }
        }

        if (count($swn->containment_image) > 0) {
            foreach ($swn->containment_image as $media) {
                if (!in_array($media->file_name, $request->input('containment_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->containment_image->pluck('file_name')->toArray();
        foreach ($request->input('containment_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
            }
        }

        if (count($swn->corrective_image) > 0) {
            foreach ($swn->corrective_image as $media) {
                if (!in_array($media->file_name, $request->input('corrective_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->corrective_image->pluck('file_name')->toArray();
        foreach ($request->input('corrective_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
            }
        }

        return (new SwnResource($swn))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Swn $swn)
    {
        abort_if(Gate::denies('swn_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $swn->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
