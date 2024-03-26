<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRfaRequest;
use App\Http\Requests\UpdateRfaRequest;
use App\Http\Resources\Admin\RfaResource;
use App\Rfa;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfaApiController extends Controller
{
    use MediaUploadingTrait;

     public function index()
    {
        abort_if(Gate::denies('rfa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rfas =  Rfa::with(['document_status', 'boq', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'create_by_user', 'distribute_by', 'reviewed_by', 'wbs_level_one', 'team'])->where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
        return RfaResource::collection($rfas)->response()->setData(
            $rfas->map(function ($rfa) {
                $file_upload_link = [];
                foreach ($rfa->file_upload_1 as $media) {
                    $file_upload_link[] = $media->getUrl();
                }

                $file_complete_link = [];
                foreach ($rfa->commercial_file_upload as $media) {
                    $file_complete_link[] = $media->getUrl();
                }

                return [
                    'id' => $rfa->id,
                    'construction_contract' => $rfa->construction_contract->code,
                    'status' => $rfa->document_status->status_name,
                    'boq' => $rfa->boq ? $rfa->boq->name : '',
                    'work_type' => $rfa->worktype ? $rfa->worktype : '',
                    'title_eng' => $rfa->title_eng ? $rfa->title_eng : '',
                    'title_th' => $rfa->title ? $rfa->title : '',
                    'document_number' => $rfa->document_number ? $rfa->document_number : '',
                    'created_at' => $rfa->created_at,
                    'document_type' => $rfa->type ? $rfa->type->type_code : '',
                    'wbs4_code' => $rfa->wbs_level_3 ? $rfa->wbs_level_3->wbs_level_3_code : '',
                    'wbs4_name' => $rfa->wbs_level_3 ? $rfa->wbs_level_3->wbs_level_3_name : '',
                    'action_by' => $rfa->action_by ? $rfa->action_by->name : '',
                    'approve_status' => $rfa->comment_status ? $rfa->comment_status->name : '',
                    'file_upload_link' => implode(', ', $file_upload_link),
                    'file_complete_link' => implode(', ', $file_complete_link),
                    // add any other fields you want to include in the response
                ];
            })
        );
        // return new AddLetterResource(AddLetter::with([ 'sender', 'receiver', 'construction_contract', 'team'])->get());
    }

    public function store(StoreRfaRequest $request)
    {
        $rfa = Rfa::create($request->all());

        if ($request->input('file_upload_1', false)) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_1')))->toMediaCollection('file_upload_1');
        }

        if ($request->input('commercial_file_upload', false)) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('commercial_file_upload')))->toMediaCollection('commercial_file_upload');
        }

        if ($request->input('document_file_upload', false)) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('document_file_upload')))->toMediaCollection('document_file_upload');
        }

        if ($request->input('submittals_file', false)) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('submittals_file')))->toMediaCollection('submittals_file');
        }

        return (new RfaResource($rfa))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $rfas =  Rfa::with(['document_status', 'boq', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'create_by_user', 'distribute_by', 'reviewed_by', 'wbs_level_one', 'team'])->where('id', '=', $rfa->id)->get();
        return RfaResource::collection($rfas)->response()->setData(
            $rfas->map(function ($rfa) {
                $file_upload_link = [];
                foreach ($rfa->file_upload_1 as $media) {
                    $file_upload_link[] = $media->getUrl();
                }

                $file_complete_link = [];
                foreach ($rfa->commercial_file_upload as $media) {
                    $file_complete_link[] = $media->getUrl();
                }

                return [
                    'id' => $rfa->id,
                    'construction_contract' => $rfa->construction_contract->code,
                    'status' => $rfa->document_status->status_name,
                    'boq' => $rfa->boq ? $rfa->boq->name : '',
                    'work_type' => $rfa->worktype ? $rfa->worktype : '',
                    'title_eng' => $rfa->title_eng ? $rfa->title_eng : '',
                    'title_th' => $rfa->title ? $rfa->title : '',
                    'document_number' => $rfa->document_number ? $rfa->document_number : '',
                    'created_at' => $rfa->created_at,
                    'document_type' => $rfa->type ? $rfa->type->type_code : '',
                    'wbs4_code' => $rfa->wbs_level_3 ? $rfa->wbs_level_3->wbs_level_3_code : '',
                    'wbs4_name' => $rfa->wbs_level_3 ? $rfa->wbs_level_3->wbs_level_3_name : '',
                    'action_by' => $rfa->action_by ? $rfa->action_by->name : '',
                    'approve_status' => $rfa->comment_status ? $rfa->comment_status->name : '',
                    'file_upload_link' => implode(', ', $file_upload_link),
                    'file_complete_link' => implode(', ', $file_complete_link),
                    // add any other fields you want to include in the response
                ];
            })
        );
        // return new RfaResource($rfa->load(['type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'distribute_by', 'reviewed_by', 'team']));
    }

    public function update(UpdateRfaRequest $request, Rfa $rfa)
    {
        $rfa->update($request->all());

        if ($request->input('file_upload_1', false)) {
            if (!$rfa->file_upload_1 || $request->input('file_upload_1') !== $rfa->file_upload_1->file_name) {
                $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_1')))->toMediaCollection('file_upload_1');
            }
        } elseif ($rfa->file_upload_1) {
            $rfa->file_upload_1->delete();
        }

        if ($request->input('commercial_file_upload', false)) {
            if (!$rfa->commercial_file_upload || $request->input('commercial_file_upload') !== $rfa->commercial_file_upload->file_name) {
                $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('commercial_file_upload')))->toMediaCollection('commercial_file_upload');
            }
        } elseif ($rfa->commercial_file_upload) {
            $rfa->commercial_file_upload->delete();
        }

        if ($request->input('document_file_upload', false)) {
            if (!$rfa->document_file_upload || $request->input('document_file_upload') !== $rfa->document_file_upload->file_name) {
                $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('document_file_upload')))->toMediaCollection('document_file_upload');
            }
        } elseif ($rfa->document_file_upload) {
            $rfa->document_file_upload->delete();
        }

        if ($request->input('submittals_file', false)) {
            if (!$rfa->submittals_file || $request->input('submittals_file') !== $rfa->submittals_file->file_name) {
                $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('submittals_file')))->toMediaCollection('submittals_file');
            }
        } elseif ($rfa->submittals_file) {
            $rfa->submittals_file->delete();
        }

        return (new RfaResource($rfa))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfa->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
