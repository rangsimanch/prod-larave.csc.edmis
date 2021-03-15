<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRequestForInformationRequest;
use App\Http\Requests\UpdateRequestForInformationRequest;
use App\Http\Resources\Admin\RequestForInformationResource;
use App\RequestForInformation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestForInformationApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('request_for_information_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RequestForInformationResource(RequestForInformation::with(['construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'team'])->get());
    }

    public function store(StoreRequestForInformationRequest $request)
    {
        $requestForInformation = RequestForInformation::create($request->all());

        if ($request->input('attachment_files', false)) {
            $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachment_files'))))->toMediaCollection('attachment_files');
        }

        if ($request->input('file_upload', false)) {
            $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($request->input('file_upload'))))->toMediaCollection('file_upload');
        }

        return (new RequestForInformationResource($requestForInformation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RequestForInformationResource($requestForInformation->load(['construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'team']));
    }

    public function update(UpdateRequestForInformationRequest $request, RequestForInformation $requestForInformation)
    {
        $requestForInformation->update($request->all());

        if ($request->input('attachment_files', false)) {
            if (!$requestForInformation->attachment_files || $request->input('attachment_files') !== $requestForInformation->attachment_files->file_name) {
                if ($requestForInformation->attachment_files) {
                    $requestForInformation->attachment_files->delete();
                }

                $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachment_files'))))->toMediaCollection('attachment_files');
            }
        } elseif ($requestForInformation->attachment_files) {
            $requestForInformation->attachment_files->delete();
        }

        if ($request->input('file_upload', false)) {
            if (!$requestForInformation->file_upload || $request->input('file_upload') !== $requestForInformation->file_upload->file_name) {
                if ($requestForInformation->file_upload) {
                    $requestForInformation->file_upload->delete();
                }

                $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($request->input('file_upload'))))->toMediaCollection('file_upload');
            }
        } elseif ($requestForInformation->file_upload) {
            $requestForInformation->file_upload->delete();
        }

        return (new RequestForInformationResource($requestForInformation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInformation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
