<?php

namespace App\Http\Controllers\Admin;

use App\BoQ;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWbsLevelThreeRequest;
use App\Http\Requests\StoreWbsLevelThreeRequest;
use App\Http\Requests\UpdateWbsLevelThreeRequest;
use App\WbsLevelThree;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WbsLevelThreeController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('wbs_level_three_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = WbsLevelThree::with(['wbs_level_2'])->select(sprintf('%s.*', (new WbsLevelThree)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'wbs_level_three_show';
                $editGate      = 'wbs_level_three_edit';
                $deleteGate    = 'wbs_level_three_delete';
                $crudRoutePart = 'wbs-level-threes';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('wbs_level_3_name', function ($row) {
                return $row->wbs_level_3_name ? $row->wbs_level_3_name : "";
            });
            $table->editColumn('wbs_level_3_code', function ($row) {
                return $row->wbs_level_3_code ? $row->wbs_level_3_code : "";
            });
            $table->addColumn('wbs_level_2_name', function ($row) {
                return $row->wbs_level_2 ? $row->wbs_level_2->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'wbs_level_2']);

            return $table->make(true);
        }

        $bo_qs = BoQ::get();

        return view('admin.wbsLevelThrees.index', compact('bo_qs'));
    }

    public function create()
    {
        abort_if(Gate::denies('wbs_level_three_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbs_level_2s = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.wbsLevelThrees.create', compact('wbs_level_2s'));
    }

    public function store(StoreWbsLevelThreeRequest $request)
    {
        $wbsLevelThree = WbsLevelThree::create($request->all());

        return redirect()->route('admin.wbs-level-threes.index');
    }

    public function edit(WbsLevelThree $wbsLevelThree)
    {
        abort_if(Gate::denies('wbs_level_three_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbs_level_2s = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbsLevelThree->load('wbs_level_2');

        return view('admin.wbsLevelThrees.edit', compact('wbs_level_2s', 'wbsLevelThree'));
    }

    public function update(UpdateWbsLevelThreeRequest $request, WbsLevelThree $wbsLevelThree)
    {
        $wbsLevelThree->update($request->all());

        return redirect()->route('admin.wbs-level-threes.index');
    }

    public function show(WbsLevelThree $wbsLevelThree)
    {
        abort_if(Gate::denies('wbs_level_three_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelThree->load('wbs_level_2');

        return view('admin.wbsLevelThrees.show', compact('wbsLevelThree'));
    }

    public function destroy(WbsLevelThree $wbsLevelThree)
    {
        abort_if(Gate::denies('wbs_level_three_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelThree->delete();

        return back();
    }

    public function massDestroy(MassDestroyWbsLevelThreeRequest $request)
    {
        WbsLevelThree::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
