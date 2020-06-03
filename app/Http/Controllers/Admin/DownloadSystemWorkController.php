<?php

namespace App\Http\Controllers\Admin;

use App\DownloadSystemWork;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDownloadSystemWorkRequest;
use App\Http\Requests\StoreDownloadSystemWorkRequest;
use App\Http\Requests\UpdateDownloadSystemWorkRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DownloadSystemWorkController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('download_system_work_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DownloadSystemWork::query()->select(sprintf('%s.*', (new DownloadSystemWork)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'download_system_work_show';
                $editGate      = 'download_system_work_edit';
                $deleteGate    = 'download_system_work_delete';
                $crudRoutePart = 'download-system-works';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('work_title', function ($row) {
                return $row->work_title ? $row->work_title : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.downloadSystemWorks.index');
    }

    public function create()
    {
        abort_if(Gate::denies('download_system_work_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.downloadSystemWorks.create');
    }

    public function store(StoreDownloadSystemWorkRequest $request)
    {
        $downloadSystemWork = DownloadSystemWork::create($request->all());

        return redirect()->route('admin.download-system-works.index');
    }

    public function edit(DownloadSystemWork $downloadSystemWork)
    {
        abort_if(Gate::denies('download_system_work_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.downloadSystemWorks.edit', compact('downloadSystemWork'));
    }

    public function update(UpdateDownloadSystemWorkRequest $request, DownloadSystemWork $downloadSystemWork)
    {
        $downloadSystemWork->update($request->all());

        return redirect()->route('admin.download-system-works.index');
    }

    public function show(DownloadSystemWork $downloadSystemWork)
    {
        abort_if(Gate::denies('download_system_work_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.downloadSystemWorks.show', compact('downloadSystemWork'));
    }

    public function destroy(DownloadSystemWork $downloadSystemWork)
    {
        abort_if(Gate::denies('download_system_work_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $downloadSystemWork->delete();

        return back();
    }

    public function massDestroy(MassDestroyDownloadSystemWorkRequest $request)
    {
        DownloadSystemWork::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
