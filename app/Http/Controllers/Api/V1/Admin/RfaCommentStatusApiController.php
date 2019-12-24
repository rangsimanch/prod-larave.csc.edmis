<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRfaCommentStatusRequest;
use App\Http\Requests\UpdateRfaCommentStatusRequest;
use App\Http\Resources\Admin\RfaCommentStatusResource;
use App\RfaCommentStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfaCommentStatusApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('rfa_comment_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RfaCommentStatusResource(RfaCommentStatus::all());
    }

    public function store(StoreRfaCommentStatusRequest $request)
    {
        $rfaCommentStatus = RfaCommentStatus::create($request->all());

        return (new RfaCommentStatusResource($rfaCommentStatus))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RfaCommentStatus $rfaCommentStatus)
    {
        abort_if(Gate::denies('rfa_comment_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RfaCommentStatusResource($rfaCommentStatus);
    }

    public function update(UpdateRfaCommentStatusRequest $request, RfaCommentStatus $rfaCommentStatus)
    {
        $rfaCommentStatus->update($request->all());

        return (new RfaCommentStatusResource($rfaCommentStatus))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RfaCommentStatus $rfaCommentStatus)
    {
        abort_if(Gate::denies('rfa_comment_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfaCommentStatus->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
