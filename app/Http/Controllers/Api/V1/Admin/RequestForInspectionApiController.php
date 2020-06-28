<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRequestForInspectionRequest;
use App\Http\Requests\UpdateRequestForInspectionRequest;
use App\Http\Resources\Admin\RequestForInspectionResource;
use App\RequestForInspection;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestForInspectionApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('request_for_inspection_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RequestForInspectionResource(RequestForInspection::with(['bill', 'wbs_level_1', 'wbs_level_2', 'wbs_level_3', 'wbs_level_4', 'contact_person', 'requested_by', 'construction_contract', 'team'])->get());
    }

    public function store(StoreRequestForInspectionRequest $request)
    {
        $requestForInspection = RequestForInspection::create($request->all());

        if ($request->input('files_upload', false)) {
            $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('files_upload')))->toMediaCollection('files_upload');
        }

        return (new RequestForInspectionResource($requestForInspection))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RequestForInspectionResource($requestForInspection->load(['bill', 'wbs_level_1', 'wbs_level_2', 'wbs_level_3', 'wbs_level_4', 'contact_person', 'requested_by', 'construction_contract', 'team']));
    }

    public function update(UpdateRequestForInspectionRequest $request, RequestForInspection $requestForInspection)
    {
        $requestForInspection->update($request->all());

        if ($request->input('files_upload', false)) {
            if (!$requestForInspection->files_upload || $request->input('files_upload') !== $requestForInspection->files_upload->file_name) {
                $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('files_upload')))->toMediaCollection('files_upload');
            }
        } elseif ($requestForInspection->files_upload) {
            $requestForInspection->files_upload->delete();
        }

        return (new RequestForInspectionResource($requestForInspection))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInspection->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
