<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreManualRequest;
use App\Http\Requests\UpdateManualRequest;
use App\Http\Resources\Admin\ManualResource;
use App\Manual;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManualsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('manual_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ManualResource(Manual::all());

    }

    public function store(StoreManualRequest $request)
    {
        $manual = Manual::create($request->all());

        if ($request->input('file_upload', false)) {
            $manual->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new ManualResource($manual))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(Manual $manual)
    {
        abort_if(Gate::denies('manual_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ManualResource($manual);

    }

    public function update(UpdateManualRequest $request, Manual $manual)
    {
        $manual->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$manual->file_upload || $request->input('file_upload') !== $manual->file_upload->file_name) {
                $manual->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }

        } elseif ($manual->file_upload) {
            $manual->file_upload->delete();
        }

        return (new ManualResource($manual))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);

    }

    public function destroy(Manual $manual)
    {
        abort_if(Gate::denies('manual_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manual->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

}
