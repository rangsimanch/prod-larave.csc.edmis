<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWbslevelfourRequest;
use App\Http\Requests\StoreWbslevelfourRequest;
use App\Http\Requests\UpdateWbslevelfourRequest;
use App\Wbslevelfour;
use App\WbsLevelThree;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WbslevelfourController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('wbslevelfour_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Wbslevelfour::with(['wbs_level_three'])->select(sprintf('%s.*', (new Wbslevelfour)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'wbslevelfour_show';
                $editGate      = 'wbslevelfour_edit';
                $deleteGate    = 'wbslevelfour_delete';
                $crudRoutePart = 'wbslevelfours';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('wbs_level_4_name', function ($row) {
                return $row->wbs_level_4_name ? $row->wbs_level_4_name : "";
            });
            $table->editColumn('wbs_level_4_code', function ($row) {
                return $row->wbs_level_4_code ? $row->wbs_level_4_code : "";
            });
            $table->addColumn('wbs_level_three_wbs_level_3_name', function ($row) {
                return $row->wbs_level_three ? $row->wbs_level_three->wbs_level_3_name : '';
            });

            $table->editColumn('wbs_level_three.wbs_level_3_name', function ($row) {
                return $row->wbs_level_three ? (is_string($row->wbs_level_three) ? $row->wbs_level_three : $row->wbs_level_three->wbs_level_3_name) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'wbs_level_three']);

            return $table->make(true);
        }

        $wbs_level_threes = WbsLevelThree::get();

        return view('admin.wbslevelfours.index', compact('wbs_level_threes'));
    }

    public function create()
    {
        abort_if(Gate::denies('wbslevelfour_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbs_level_threes = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.wbslevelfours.create', compact('wbs_level_threes'));
    }

    public function store(StoreWbslevelfourRequest $request)
    {
        $wbslevelfour = Wbslevelfour::create($request->all());

        return redirect()->route('admin.wbslevelfours.index');
    }

    public function edit(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbs_level_threes = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbslevelfour->load('wbs_level_three');

        return view('admin.wbslevelfours.edit', compact('wbs_level_threes', 'wbslevelfour'));
    }

    public function update(UpdateWbslevelfourRequest $request, Wbslevelfour $wbslevelfour)
    {
        $wbslevelfour->update($request->all());

        return redirect()->route('admin.wbslevelfours.index');
    }

    public function show(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbslevelfour->load('wbs_level_three');

        return view('admin.wbslevelfours.show', compact('wbslevelfour'));
    }

    public function destroy(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbslevelfour->delete();

        return back();
    }

    public function massDestroy(MassDestroyWbslevelfourRequest $request)
    {
        Wbslevelfour::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
