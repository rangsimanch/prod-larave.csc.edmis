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

        return new RfaResource(Rfa::with(['type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'distribute_by', 'reviewed_by', 'team'])->get());
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

        return new RfaResource($rfa->load(['type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'distribute_by', 'reviewed_by', 'team']));
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
