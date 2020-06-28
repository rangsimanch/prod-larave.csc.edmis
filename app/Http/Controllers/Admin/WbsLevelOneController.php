<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWbsLevelOneRequest;
use App\Http\Requests\StoreWbsLevelOneRequest;
use App\Http\Requests\UpdateWbsLevelOneRequest;
use App\WbsLevelOne;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WbsLevelOneController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('wbs_level_one_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = WbsLevelOne::query()->select(sprintf('%s.*', (new WbsLevelOne)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'wbs_level_one_show';
                $editGate      = 'wbs_level_one_edit';
                $deleteGate    = 'wbs_level_one_delete';
                $crudRoutePart = 'wbs-level-ones';

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

        return view('admin.wbsLevelOnes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('wbs_level_one_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelOnes.create');
    }

    public function store(StoreWbsLevelOneRequest $request)
    {
        $wbsLevelOne = WbsLevelOne::create($request->all());

        return redirect()->route('admin.wbs-level-ones.index');
    }

    public function edit(WbsLevelOne $wbsLevelOne)
    {
        abort_if(Gate::denies('wbs_level_one_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelOnes.edit', compact('wbsLevelOne'));
    }

    public function update(UpdateWbsLevelOneRequest $request, WbsLevelOne $wbsLevelOne)
    {
        $wbsLevelOne->update($request->all());

        return redirect()->route('admin.wbs-level-ones.index');
    }

    public function show(WbsLevelOne $wbsLevelOne)
    {
        abort_if(Gate::denies('wbs_level_one_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelOnes.show', compact('wbsLevelOne'));
    }

    public function destroy(WbsLevelOne $wbsLevelOne)
    {
        abort_if(Gate::denies('wbs_level_one_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelOne->delete();

        return back();
    }

    public function massDestroy(MassDestroyWbsLevelOneRequest $request)
    {
        WbsLevelOne::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
