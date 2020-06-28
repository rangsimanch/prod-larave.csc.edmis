<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWbsLevelTwoRequest;
use App\Http\Requests\StoreWbsLevelTwoRequest;
use App\Http\Requests\UpdateWbsLevelTwoRequest;
use App\WbsLevelTwo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WbsLevelTwoController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('wbs_level_two_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = WbsLevelTwo::query()->select(sprintf('%s.*', (new WbsLevelTwo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'wbs_level_two_show';
                $editGate      = 'wbs_level_two_edit';
                $deleteGate    = 'wbs_level_two_delete';
                $crudRoutePart = 'wbs-level-twos';

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

        return view('admin.wbsLevelTwos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('wbs_level_two_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelTwos.create');
    }

    public function store(StoreWbsLevelTwoRequest $request)
    {
        $wbsLevelTwo = WbsLevelTwo::create($request->all());

        return redirect()->route('admin.wbs-level-twos.index');
    }

    public function edit(WbsLevelTwo $wbsLevelTwo)
    {
        abort_if(Gate::denies('wbs_level_two_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelTwos.edit', compact('wbsLevelTwo'));
    }

    public function update(UpdateWbsLevelTwoRequest $request, WbsLevelTwo $wbsLevelTwo)
    {
        $wbsLevelTwo->update($request->all());

        return redirect()->route('admin.wbs-level-twos.index');
    }

    public function show(WbsLevelTwo $wbsLevelTwo)
    {
        abort_if(Gate::denies('wbs_level_two_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelTwos.show', compact('wbsLevelTwo'));
    }

    public function destroy(WbsLevelTwo $wbsLevelTwo)
    {
        abort_if(Gate::denies('wbs_level_two_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelTwo->delete();

        return back();
    }

    public function massDestroy(MassDestroyWbsLevelTwoRequest $request)
    {
        WbsLevelTwo::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
