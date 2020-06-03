<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\AsBuildPlan;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAsBuildPlanRequest;
use App\Http\Requests\UpdateAsBuildPlanRequest;
use App\Http\Resources\Admin\AsBuildPlanResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AsBuildPlanApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('as_build_plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AsBuildPlanResource(AsBuildPlan::with(['drawing_references', 'construction_contract', 'team'])->get());
    }

    public function store(StoreAsBuildPlanRequest $request)
    {
        $asBuildPlan = AsBuildPlan::create($request->all());
        $asBuildPlan->drawing_references()->sync($request->input('drawing_references', []));

        if ($request->input('file_upload', false)) {
            $asBuildPlan->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        if ($request->input('special_file_upload', false)) {
            $asBuildPlan->addMedia(storage_path('tmp/uploads/' . $request->input('special_file_upload')))->toMediaCollection('special_file_upload');
        }

        return (new AsBuildPlanResource($asBuildPlan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AsBuildPlan $asBuildPlan)
    {
        abort_if(Gate::denies('as_build_plan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AsBuildPlanResource($asBuildPlan->load(['drawing_references', 'construction_contract', 'team']));
    }

    public function update(UpdateAsBuildPlanRequest $request, AsBuildPlan $asBuildPlan)
    {
        $asBuildPlan->update($request->all());
        $asBuildPlan->drawing_references()->sync($request->input('drawing_references', []));

        if ($request->input('file_upload', false)) {
            if (!$asBuildPlan->file_upload || $request->input('file_upload') !== $asBuildPlan->file_upload->file_name) {
                $asBuildPlan->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($asBuildPlan->file_upload) {
            $asBuildPlan->file_upload->delete();
        }

        if ($request->input('special_file_upload', false)) {
            if (!$asBuildPlan->special_file_upload || $request->input('special_file_upload') !== $asBuildPlan->special_file_upload->file_name) {
                $asBuildPlan->addMedia(storage_path('tmp/uploads/' . $request->input('special_file_upload')))->toMediaCollection('special_file_upload');
            }
        } elseif ($asBuildPlan->special_file_upload) {
            $asBuildPlan->special_file_upload->delete();
        }

        return (new AsBuildPlanResource($asBuildPlan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AsBuildPlan $asBuildPlan)
    {
        abort_if(Gate::denies('as_build_plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asBuildPlan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
