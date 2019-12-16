<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRfaDocumentStatusRequest;
use App\Http\Requests\StoreRfaDocumentStatusRequest;
use App\Http\Requests\UpdateRfaDocumentStatusRequest;
use App\RfaDocumentStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfaDocumentStatusController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('rfa_document_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfaDocumentStatuses = RfaDocumentStatus::all();

        return view('admin.rfaDocumentStatuses.index', compact('rfaDocumentStatuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('rfa_document_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfaDocumentStatuses.create');
    }

    public function store(StoreRfaDocumentStatusRequest $request)
    {
        $rfaDocumentStatus = RfaDocumentStatus::create($request->all());

        return redirect()->route('admin.rfa-document-statuses.index');
    }

    public function edit(RfaDocumentStatus $rfaDocumentStatus)
    {
        abort_if(Gate::denies('rfa_document_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfaDocumentStatuses.edit', compact('rfaDocumentStatus'));
    }

    public function update(UpdateRfaDocumentStatusRequest $request, RfaDocumentStatus $rfaDocumentStatus)
    {
        $rfaDocumentStatus->update($request->all());

        return redirect()->route('admin.rfa-document-statuses.index');
    }

    public function show(RfaDocumentStatus $rfaDocumentStatus)
    {
        abort_if(Gate::denies('rfa_document_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfaDocumentStatuses.show', compact('rfaDocumentStatus'));
    }

    public function destroy(RfaDocumentStatus $rfaDocumentStatus)
    {
        abort_if(Gate::denies('rfa_document_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfaDocumentStatus->delete();

        return back();
    }

    public function massDestroy(MassDestroyRfaDocumentStatusRequest $request)
    {
        RfaDocumentStatus::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
