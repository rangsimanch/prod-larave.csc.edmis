<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DailyRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDailyRequestRequest;
use App\Http\Requests\UpdateDailyRequestRequest;
use App\Http\Resources\Admin\DailyRequestResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DailyRequestApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('daily_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DailyRequestResource(DailyRequest::with(['receive_by'])->get());

    }

    public function store(StoreDailyRequestRequest $request)
    {
        $dailyRequest = DailyRequest::create($request->all());

        if ($request->input('documents', false)) {
            $dailyRequest->addMedia(storage_path('tmp/uploads/' . $request->input('documents')))->toMediaCollection('documents');
        }

        return (new DailyRequestResource($dailyRequest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(DailyRequest $dailyRequest)
    {
        abort_if(Gate::denies('daily_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DailyRequestResource($dailyRequest->load(['receive_by']));

    }

    public function update(UpdateDailyRequestRequest $request, DailyRequest $dailyRequest)
    {
        $dailyRequest->update($request->all());

        if ($request->input('documents', false)) {
            if (!$dailyRequest->documents || $request->input('documents') !== $dailyRequest->documents->file_name) {
                $dailyRequest->addMedia(storage_path('tmp/uploads/' . $request->input('documents')))->toMediaCollection('documents');
            }

        } elseif ($dailyRequest->documents) {
            $dailyRequest->documents->delete();
        }

        return (new DailyRequestResource($dailyRequest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);

    }

    public function destroy(DailyRequest $dailyRequest)
    {
        abort_if(Gate::denies('daily_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyRequest->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

}
