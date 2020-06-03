<?php

namespace App\Http\Controllers\Admin;

use App\DownloadSystemActivity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDownloadSystemActivityRequest;
use App\Http\Requests\StoreDownloadSystemActivityRequest;
use App\Http\Requests\UpdateDownloadSystemActivityRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DownloadSystemActivityController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('download_system_activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DownloadSystemActivity::query()->select(sprintf('%s.*', (new DownloadSystemActivity)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'download_system_activity_show';
                $editGate      = 'download_system_activity_edit';
                $deleteGate    = 'download_system_activity_delete';
                $crudRoutePart = 'download-system-activities';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('activity_title', function ($row) {
                return $row->activity_title ? $row->activity_title : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.downloadSystemActivities.index');
    }

    public function create()
    {
        abort_if(Gate::denies('download_system_activity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.downloadSystemActivities.create');
    }

    public function store(StoreDownloadSystemActivityRequest $request)
    {
        $downloadSystemActivity = DownloadSystemActivity::create($request->all());

        return redirect()->route('admin.download-system-activities.index');
    }

    public function edit(DownloadSystemActivity $downloadSystemActivity)
    {
        abort_if(Gate::denies('download_system_activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.downloadSystemActivities.edit', compact('downloadSystemActivity'));
    }

    public function update(UpdateDownloadSystemActivityRequest $request, DownloadSystemActivity $downloadSystemActivity)
    {
        $downloadSystemActivity->update($request->all());

        return redirect()->route('admin.download-system-activities.index');
    }

    public function show(DownloadSystemActivity $downloadSystemActivity)
    {
        abort_if(Gate::denies('download_system_activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.downloadSystemActivities.show', compact('downloadSystemActivity'));
    }

    public function destroy(DownloadSystemActivity $downloadSystemActivity)
    {
        abort_if(Gate::denies('download_system_activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $downloadSystemActivity->delete();

        return back();
    }

    public function massDestroy(MassDestroyDownloadSystemActivityRequest $request)
    {
        DownloadSystemActivity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
