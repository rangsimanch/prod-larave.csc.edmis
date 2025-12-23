<?php

namespace App\Http\Controllers\Admin;

use App\CloseOutDrive;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCloseOutDriveRequest;
use App\Http\Requests\StoreCloseOutDriveRequest;
use App\Http\Requests\UpdateCloseOutDriveRequest;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CloseOutDriveController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('close_out_drive_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CloseOutDrive::with(['team'])->select(sprintf('%s.*', (new CloseOutDrive)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'close_out_drive_show';
                $editGate      = 'close_out_drive_edit';
                $deleteGate    = 'close_out_drive_delete';
                $crudRoutePart = 'close-out-drives';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('filename', function ($row) {
                return $row->filename ? $row->filename : '';
            });
            $table->editColumn('url', function ($row) {
                return $row->url ? $row->url : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $teams = Team::get();

        return view('admin.closeOutDrives.index', compact('teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('close_out_drive_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.closeOutDrives.create');
    }

    public function store(StoreCloseOutDriveRequest $request)
    {
        $closeOutDrive = CloseOutDrive::create($request->all());

        return redirect()->route('admin.close-out-drives.index');
    }

    public function edit(CloseOutDrive $closeOutDrive)
    {
        abort_if(Gate::denies('close_out_drive_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutDrive->load('team');

        return view('admin.closeOutDrives.edit', compact('closeOutDrive'));
    }

    public function update(UpdateCloseOutDriveRequest $request, CloseOutDrive $closeOutDrive)
    {
        $closeOutDrive->update($request->all());

        return redirect()->route('admin.close-out-drives.index');
    }

    public function show(CloseOutDrive $closeOutDrive)
    {
        abort_if(Gate::denies('close_out_drive_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutDrive->load('team', 'closeoutUrlCloseOutMains');

        return view('admin.closeOutDrives.show', compact('closeOutDrive'));
    }

    public function destroy(CloseOutDrive $closeOutDrive)
    {
        abort_if(Gate::denies('close_out_drive_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutDrive->delete();

        return back();
    }

    public function massDestroy(MassDestroyCloseOutDriveRequest $request)
    {
        $closeOutDrives = CloseOutDrive::find(request('ids'));

        foreach ($closeOutDrives as $closeOutDrive) {
            $closeOutDrive->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
