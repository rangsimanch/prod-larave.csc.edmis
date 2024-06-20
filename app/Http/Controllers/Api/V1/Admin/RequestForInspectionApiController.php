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
use Carbon\Carbon;


class RequestForInspectionApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('request_for_inspection_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $rfns = RequestForInspection::with(['construction_contract', 'wbs_level_1', 'bill', 'wbs_level_3', 'item_1', 'item_2', 'item_3', 'requested_by', 'contact_person', 'team'])
        // ->orderBy('updated_at', 'desc')->limit(500)->get();


        $rfns = RequestForInspection::with(['construction_contract', 'wbs_level_1', 'bill', 'wbs_level_3', 'item_1', 'item_2', 'item_3', 'requested_by', 'contact_person', 'team'])
        ->where('id', '>=', 20572)
        ->orderBy('id', 'asc')->limit(10000)->get();


        return RequestForInspectionResource::collection($rfns)->response()->setData(
            $rfns->map(function ($rfn) {
                $file_upload_link = [];
                foreach ($rfn->files_upload as $media) {
                    $file_upload_link[] = $media->getUrl();
                }

        
                $updatedDate = '';
                try {
                    $date = Carbon::parse($rfn->updated_at);
                    $updatedDate = $date->format('d/m/Y');
                }catch(exeption $e){
                    $updatedDate = '';
                }

                return [
                    'id' => $rfn->id,
                    'construction_contract' => $rfn->construction_contract ? $rfn->construction_contract->code : '',
                    'boq' => $rfn->bill ? $rfn->bill->name : '',
                    'title' => $rfn->subject ? $rfn->subject : '',
                    'document_number' => $rfn->ref_no ? $rfn->ref_no : '',
                    'ipa' => $rfn->ipa ? $rfn->ipa : '',
                    'submit_date' => $rfn->submittal_date,
                    'file_upload_link' => implode(', ', $file_upload_link),
                    'updated_at' => $updatedDate,
                    // add any other fields you want to include in the response
                ];
            })
        );
    }

    public function store(StoreRequestForInspectionRequest $request)
    {
        // $requestForInspection = RequestForInspection::create($request->all());

        // if ($request->input('files_upload', false)) {
        //     $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('files_upload')))->toMediaCollection('files_upload');
        // }

        // if ($request->input('loop_file_upload', false)) {
        //     $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('loop_file_upload')))->toMediaCollection('loop_file_upload');
        // }

        // return (new RequestForInspectionResource($requestForInspection))
        //     ->response()
        //     ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RequestForInspectionResource($requestForInspection->load(['construction_contract', 'wbs_level_1', 'bill', 'wbs_level_3', 'item_1', 'item_2', 'item_3', 'requested_by', 'contact_person', 'team']));
    }

    public function update(UpdateRequestForInspectionRequest $request, RequestForInspection $requestForInspection)
    {
        // $requestForInspection->update($request->all());

        // if ($request->input('files_upload', false)) {
        //     if (!$requestForInspection->files_upload || $request->input('files_upload') !== $requestForInspection->files_upload->file_name) {
        //         if ($requestForInspection->files_upload) {
        //             $requestForInspection->files_upload->delete();
        //         }

        //         $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('files_upload')))->toMediaCollection('files_upload');
        //     }
        // } elseif ($requestForInspection->files_upload) {
        //     $requestForInspection->files_upload->delete();
        // }

        // if ($request->input('loop_file_upload', false)) {
        //     if (!$requestForInspection->loop_file_upload || $request->input('loop_file_upload') !== $requestForInspection->loop_file_upload->file_name) {
        //         if ($requestForInspection->loop_file_upload) {
        //             $requestForInspection->loop_file_upload->delete();
        //         }

        //         $requestForInspection->addMedia(storage_path('tmp/uploads/' . $request->input('loop_file_upload')))->toMediaCollection('loop_file_upload');
        //     }
        // } elseif ($requestForInspection->loop_file_upload) {
        //     $requestForInspection->loop_file_upload->delete();
        // }

        // return (new RequestForInspectionResource($requestForInspection))
        //     ->response()
        //     ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RequestForInspection $requestForInspection)
    {
        abort_if(Gate::denies('request_for_inspection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $requestForInspection->delete();

        // return response(null, Response::HTTP_NO_CONTENT);
    }
}
