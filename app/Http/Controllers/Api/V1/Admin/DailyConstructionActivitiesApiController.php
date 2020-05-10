<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DailyConstructionActivity;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDailyConstructionActivityRequest;
use App\Http\Requests\UpdateDailyConstructionActivityRequest;
use App\Http\Resources\Admin\DailyConstructionActivityResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DailyConstructionActivitiesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('daily_construction_activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DailyConstructionActivityResource(DailyConstructionActivity::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreDailyConstructionActivityRequest $request)
    {
        $dailyConstructionActivity = DailyConstructionActivity::create($request->all());

        if ($request->input('image_upload', false)) {
            $dailyConstructionActivity->addMedia(storage_path('tmp/uploads/' . $request->input('image_upload')))->toMediaCollection('image_upload');
        }

        return (new DailyConstructionActivityResource($dailyConstructionActivity))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DailyConstructionActivity $dailyConstructionActivity)
    {
        abort_if(Gate::denies('daily_construction_activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DailyConstructionActivityResource($dailyConstructionActivity->load(['construction_contract', 'team']));
    }

    public function update(UpdateDailyConstructionActivityRequest $request, DailyConstructionActivity $dailyConstructionActivity)
    {
        $dailyConstructionActivity->update($request->all());

        if ($request->input('image_upload', false)) {
            if (!$dailyConstructionActivity->image_upload || $request->input('image_upload') !== $dailyConstructionActivity->image_upload->file_name) {
                $dailyConstructionActivity->addMedia(storage_path('tmp/uploads/' . $request->input('image_upload')))->toMediaCollection('image_upload');
            }
        } elseif ($dailyConstructionActivity->image_upload) {
            $dailyConstructionActivity->image_upload->delete();
        }

        return (new DailyConstructionActivityResource($dailyConstructionActivity))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DailyConstructionActivity $dailyConstructionActivity)
    {
        abort_if(Gate::denies('daily_construction_activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyConstructionActivity->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
