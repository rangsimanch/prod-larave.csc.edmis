<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWbsLevelOneRequest;
use App\Http\Requests\StoreWbsLevelOneRequest;
use App\Http\Requests\UpdateWbsLevelOneRequest;
use App\WbsLevelFive;
use App\WbsLevelOne;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WbsLevelOneController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('wbs_level_one_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = WbsLevelOne::with(['wbs_lv_1'])->select(sprintf('%s.*', (new WbsLevelOne)->table));
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

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : "";
            });
            $table->addColumn('wbs_lv_1_name', function ($row) {
                return $row->wbs_lv_1 ? $row->wbs_lv_1->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'wbs_lv_1']);

            return $table->make(true);
        }

        $wbs_level_fives = WbsLevelFive::get();

        return view('admin.wbsLevelOnes.index', compact('wbs_level_fives'));
    }

    public function create()
    {
        abort_if(Gate::denies('wbs_level_one_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbs_lv_1s = WbsLevelFive::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.wbsLevelOnes.create', compact('wbs_lv_1s'));
    }

    public function store(StoreWbsLevelOneRequest $request)
    {
        $wbsLevelOne = WbsLevelOne::create($request->all());

        return redirect()->route('admin.wbs-level-ones.index');
    }

    public function edit(WbsLevelOne $wbsLevelOne)
    {
        abort_if(Gate::denies('wbs_level_one_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbs_lv_1s = WbsLevelFive::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbsLevelOne->load('wbs_lv_1');

        return view('admin.wbsLevelOnes.edit', compact('wbs_lv_1s', 'wbsLevelOne'));
    }

    public function update(UpdateWbsLevelOneRequest $request, WbsLevelOne $wbsLevelOne)
    {
        $wbsLevelOne->update($request->all());

        return redirect()->route('admin.wbs-level-ones.index');
    }

    public function show(WbsLevelOne $wbsLevelOne)
    {
        abort_if(Gate::denies('wbs_level_one_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelOne->load('wbs_lv_1');

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
