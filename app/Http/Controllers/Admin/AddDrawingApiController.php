<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\AddDrawing;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAddDrawingRequest;
use App\Http\Requests\UpdateAddDrawingRequest;
use App\Http\Resources\Admin\AddDrawingResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddDrawingApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('add_drawing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddDrawingResource(AddDrawing::with(['activity_type', 'work_type', 'team'])->get());
    }

    public function store(StoreAddDrawingRequest $request)
    {
        $addDrawing = AddDrawing::create($request->all());

        if ($request->input('file_upload', false)) {
            $addDrawing->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new AddDrawingResource($addDrawing))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AddDrawing $addDrawing)
    {
        abort_if(Gate::denies('add_drawing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddDrawingResource($addDrawing->load(['activity_type', 'work_type', 'team']));
    }

    public function update(UpdateAddDrawingRequest $request, AddDrawing $addDrawing)
    {
        $addDrawing->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$addDrawing->file_upload || $request->input('file_upload') !== $addDrawing->file_upload->file_name) {
                $addDrawing->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($addDrawing->file_upload) {
            $addDrawing->file_upload->delete();
        }

        return (new AddDrawingResource($addDrawing))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AddDrawing $addDrawing)
    {
        abort_if(Gate::denies('add_drawing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addDrawing->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
