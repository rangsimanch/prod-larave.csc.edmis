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

        return new RequestForInspectionResource(RequestForInspection::with(['wbs_level_1', 'bill', 'wbs_level_3', 'wbs_level_4', 'items', 'requested_by', 'contact_person', 'construction_contract', 'team'])->get());
    }

    public function store(StoreRequestForInspectionRequest $request)
    {
        $requestForInspection = RequestForInspection::create($request->all());
        $requestForInspection->items()->sync($request->input('items', []));

        if ($request->input('files_upload', false)) {
            $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('files_upload')))->toMediaCollection('files_upload');
        }

        if ($request->input('loop_file_upload', false)) {
            $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('loop_file_upload')))->toMediaCollection('loop_file_upload');
        }

        return (new RequestForInspectionResource($requestForInspection))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RequestForInspectionResource($requestForInspection->load(['wbs_level_1', 'bill', 'wbs_level_3', 'wbs_level_4', 'items', 'requested_by', 'contact_person', 'construction_contract', 'team']));
    }

    public function update(UpdateRequestForInspectionRequest $request, RequestForInspection $requestForInspection)
    {
        $requestForInspection->update($request->all());
        $requestForInspection->items()->sync($request->input('items', []));

        if ($request->input('files_upload', false)) {
            if (!$requestForInspection->files_upload || $request->input('files_upload') !== $requestForInspection->files_upload->file_name) {
                if ($requestForInspection->files_upload) {
                    $requestForInspection->files_upload->delete();
                }

                $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('files_upload')))->toMediaCollection('files_upload');
            }
        } elseif ($requestForInspection->files_upload) {
            $requestForInspection->files_upload->delete();
        }

        if ($request->input('loop_file_upload', false)) {
            if (!$requestForInspection->loop_file_upload || $request->input('loop_file_upload') !== $requestForInspection->loop_file_upload->file_name) {
                if ($requestForInspection->loop_file_upload) {
                    $requestForInspection->loop_file_upload->delete();
                }

                $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('loop_file_upload')))->toMediaCollection('loop_file_upload');
            }
        } elseif ($requestForInspection->loop_file_upload) {
            $requestForInspection->loop_file_upload->delete();
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
