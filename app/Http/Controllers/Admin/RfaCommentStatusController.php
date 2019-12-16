<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRfaCommentStatusRequest;
use App\Http\Requests\StoreRfaCommentStatusRequest;
use App\Http\Requests\UpdateRfaCommentStatusRequest;
use App\RfaCommentStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfaCommentStatusController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('rfa_comment_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfaCommentStatuses = RfaCommentStatus::all();

        return view('admin.rfaCommentStatuses.index', compact('rfaCommentStatuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('rfa_comment_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfaCommentStatuses.create');
    }

    public function store(StoreRfaCommentStatusRequest $request)
    {
        $rfaCommentStatus = RfaCommentStatus::create($request->all());

        return redirect()->route('admin.rfa-comment-statuses.index');
    }

    public function edit(RfaCommentStatus $rfaCommentStatus)
    {
        abort_if(Gate::denies('rfa_comment_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfaCommentStatuses.edit', compact('rfaCommentStatus'));
    }

    public function update(UpdateRfaCommentStatusRequest $request, RfaCommentStatus $rfaCommentStatus)
    {
        $rfaCommentStatus->update($request->all());

        return redirect()->route('admin.rfa-comment-statuses.index');
    }

    public function show(RfaCommentStatus $rfaCommentStatus)
    {
        abort_if(Gate::denies('rfa_comment_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfaCommentStatuses.show', compact('rfaCommentStatus'));
    }

    public function destroy(RfaCommentStatus $rfaCommentStatus)
    {
        abort_if(Gate::denies('rfa_comment_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfaCommentStatus->delete();

        return back();
    }

    public function massDestroy(MassDestroyRfaCommentStatusRequest $request)
    {
        RfaCommentStatus::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
