<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySrtDocumentStatusRequest;
use App\Http\Requests\StoreSrtDocumentStatusRequest;
use App\Http\Requests\UpdateSrtDocumentStatusRequest;
use App\SrtDocumentStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SrtDocumentStatusController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_document_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtDocumentStatus::query()->select(sprintf('%s.*', (new SrtDocumentStatus)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'srt_document_status_show';
                $editGate      = 'srt_document_status_edit';
                $deleteGate    = 'srt_document_status_delete';
                $crudRoutePart = 'srt-document-statuses';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.srtDocumentStatuses.index');
    }

    public function create()
    {
        abort_if(Gate::denies('srt_document_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.srtDocumentStatuses.create');
    }

    public function store(StoreSrtDocumentStatusRequest $request)
    {
        $srtDocumentStatus = SrtDocumentStatus::create($request->all());

        return redirect()->route('admin.srt-document-statuses.index');
    }

    public function edit(SrtDocumentStatus $srtDocumentStatus)
    {
        abort_if(Gate::denies('srt_document_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.srtDocumentStatuses.edit', compact('srtDocumentStatus'));
    }

    public function update(UpdateSrtDocumentStatusRequest $request, SrtDocumentStatus $srtDocumentStatus)
    {
        $srtDocumentStatus->update($request->all());

        return redirect()->route('admin.srt-document-statuses.index');
    }

    public function show(SrtDocumentStatus $srtDocumentStatus)
    {
        abort_if(Gate::denies('srt_document_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.srtDocumentStatuses.show', compact('srtDocumentStatus'));
    }

    public function destroy(SrtDocumentStatus $srtDocumentStatus)
    {
        abort_if(Gate::denies('srt_document_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtDocumentStatus->delete();

        return back();
    }

    public function massDestroy(MassDestroySrtDocumentStatusRequest $request)
    {
        SrtDocumentStatus::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
