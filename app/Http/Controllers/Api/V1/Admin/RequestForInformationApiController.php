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
use Carbon\Carbon;


class RequestForInformationApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('request_for_information_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $rfis = RequestForInformation::with(['construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'team'])
        // ->orderBy('updated_at', 'desc')->limit(500)->get();

        $rfis = RequestForInformation::with(['construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'team'])
        ->orderBy('id', 'asc')
        ->limit(5000)->get();




        return RequestForInformationResource::collection($rfis)->response()->setData(
            $rfis->map(function ($rfi) {
                $file_upload_link = [];
                foreach ($rfi->attachment_files as $media) {
                    $file_upload_link[] = $media->getUrl();
                }

                  $file_complete_link = [];
                foreach ($rfi->file_upload as $media) {
                    $file_complete_link[] = $media->getUrl();
                }


                $updatedDate = '';
                try {
                    $date = Carbon::parse($rfi->updated_at);
                    $updatedDate = $date->format('d/m/Y');
                }catch(exeption $e){
                    $updatedDate = '';
                }

                return [
                    'id' => $rfi->id,
                    'construction_contract' => $rfi->construction_contract ? $rfi->construction_contract->code : '',
                    'status' => $rfi->document_status ? $rfi->document_status->name : '' ,
                    'title' => $rfi->title ? $rfi->title : '',
                    'document_number' => $rfi->document_no ? $rfi->document_no : '',
                    'original_code' => $rfi->originator_code ? $rfi->originator_code : '',
                    'receiver' => $rfi->to ? $rfi->to->code : '',
                    'wbs4_name' => $rfi->wbs_level_3 ? $rfi->wbs_level_3->wbs_level_3_name : '',
                    'wbs5_name' => $rfi->wbs_level_4 ? $rfi->wbs_level_4->wbs_level_4_name : '',
                    'incoming_no' => $rfi->incoming_no ? $rfi->incoming_no : '',
                    'incoming_date' => $rfi->incoming_date,
                    'outgoing_no' => $rfi->outgoing_no ? $rfi->outgoing_no : '',
                    'outgoing_date' => $rfi->outgoing_date,
                    'request_by' => $rfi->request_by ? $rfi->request_by->name : '',
                    'authorised_rep' => $rfi->authorised_rep ? $rfi->authorised_rep->name : '',
                    'response_organization' => $rfi->response_organization ? $rfi->response_organization->code : '',
                    'submit_date' => $rfi->date,
                    'file_upload_link' => implode(', ', $file_upload_link),
                    'file_complete_link' => implode(', ', $file_upload_link),
                    'updated_at' => $updatedDate,
                    // add any other fields you want to include in the response
                ];
            })
        );
    }

    public function store(StoreRequestForInformationRequest $request)
    {
        // $requestForInformation = RequestForInformation::create($request->all());

        // if ($request->input('attachment_files', false)) {
        //     $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachment_files'))))->toMediaCollection('attachment_files');
        // }

        // if ($request->input('file_upload', false)) {
        //     $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($request->input('file_upload'))))->toMediaCollection('file_upload');
        // }

        // return (new RequestForInformationResource($requestForInformation))
        //     ->response()
        //     ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RequestForInformationResource($requestForInformation->load(['construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'team']));
    }

    public function update(UpdateRequestForInformationRequest $request, RequestForInformation $requestForInformation)
    {
        // $requestForInformation->update($request->all());

        // if ($request->input('attachment_files', false)) {
        //     if (!$requestForInformation->attachment_files || $request->input('attachment_files') !== $requestForInformation->attachment_files->file_name) {
        //         if ($requestForInformation->attachment_files) {
        //             $requestForInformation->attachment_files->delete();
        //         }

        //         $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachment_files'))))->toMediaCollection('attachment_files');
        //     }
        // } elseif ($requestForInformation->attachment_files) {
        //     $requestForInformation->attachment_files->delete();
        // }

        // if ($request->input('file_upload', false)) {
        //     if (!$requestForInformation->file_upload || $request->input('file_upload') !== $requestForInformation->file_upload->file_name) {
        //         if ($requestForInformation->file_upload) {
        //             $requestForInformation->file_upload->delete();
        //         }

        //         $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($request->input('file_upload'))))->toMediaCollection('file_upload');
        //     }
        // } elseif ($requestForInformation->file_upload) {
        //     $requestForInformation->file_upload->delete();
        // }

        // return (new RequestForInformationResource($requestForInformation))
        //     ->response()
        //     ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $requestForInformation->delete();

        // return response(null, Response::HTTP_NO_CONTENT);
    }
}
