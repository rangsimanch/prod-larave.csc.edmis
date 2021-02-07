<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWbsLevelFiveRequest;
use App\Http\Requests\StoreWbsLevelFiveRequest;
use App\Http\Requests\UpdateWbsLevelFiveRequest;
use App\WbsLevelFive;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WbsLevelFiveController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('wbs_level_five_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = WbsLevelFive::query()->select(sprintf('%s.*', (new WbsLevelFive)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'wbs_level_five_show';
                $editGate      = 'wbs_level_five_edit';
                $deleteGate    = 'wbs_level_five_delete';
                $crudRoutePart = 'wbs-level-fives';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.wbsLevelFives.index');
    }

    public function create()
    {
        abort_if(Gate::denies('wbs_level_five_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelFives.create');
    }

    public function store(StoreWbsLevelFiveRequest $request)
    {
        $wbsLevelFive = WbsLevelFive::create($request->all());

        return redirect()->route('admin.wbs-level-fives.index');
    }

    public function edit(WbsLevelFive $wbsLevelFive)
    {
        abort_if(Gate::denies('wbs_level_five_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelFives.edit', compact('wbsLevelFive'));
    }

    public function update(UpdateWbsLevelFiveRequest $request, WbsLevelFive $wbsLevelFive)
    {
        $wbsLevelFive->update($request->all());

        return redirect()->route('admin.wbs-level-fives.index');
    }

    public function show(WbsLevelFive $wbsLevelFive)
    {
        abort_if(Gate::denies('wbs_level_five_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelFives.show', compact('wbsLevelFive'));
    }

    public function destroy(WbsLevelFive $wbsLevelFive)
    {
        abort_if(Gate::denies('wbs_level_five_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelFive->delete();

        return back();
    }

    public function massDestroy(MassDestroyWbsLevelFiveRequest $request)
    {
        WbsLevelFive::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
