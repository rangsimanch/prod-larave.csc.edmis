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

        return new RfaResource(Rfa::with(['type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team'])->get());
    }

    public function store(StoreRfaRequest $request)
    {
        $rfa = Rfa::create($request->all());

        if ($request->input('file_upload_1', false)) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload_1')))->toMediaCollection('file_upload_1');
        }

        return (new RfaResource($rfa))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RfaResource($rfa->load(['type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team']));
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