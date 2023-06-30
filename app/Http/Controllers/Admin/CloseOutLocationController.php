<?php

namespace App\Http\Controllers\Admin;

use App\CloseOutLocation;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCloseOutLocationRequest;
use App\Http\Requests\StoreCloseOutLocationRequest;
use App\Http\Requests\UpdateCloseOutLocationRequest;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CloseOutLocationController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('close_out_location_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CloseOutLocation::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new CloseOutLocation)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'close_out_location_show';
                $editGate      = 'close_out_location_edit';
                $deleteGate    = 'close_out_location_delete';
                $crudRoutePart = 'close-out-locations';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('location_name', function ($row) {
                return $row->location_name ? $row->location_name : '';
            });
            $table->editColumn('location_code', function ($row) {
                return $row->location_code ? $row->location_code : '';
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();

        return view('admin.closeOutLocations.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('close_out_location_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.closeOutLocations.create', compact('construction_contracts'));
    }

    public function store(StoreCloseOutLocationRequest $request)
    {
        $closeOutLocation = CloseOutLocation::create($request->all());

        return redirect()->route('admin.close-out-locations.index');
    }

    public function edit(CloseOutLocation $closeOutLocation)
    {
        abort_if(Gate::denies('close_out_location_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $closeOutLocation->load('construction_contract', 'team');

        return view('admin.closeOutLocations.edit', compact('closeOutLocation', 'construction_contracts'));
    }

    public function update(UpdateCloseOutLocationRequest $request, CloseOutLocation $closeOutLocation)
    {
        $closeOutLocation->update($request->all());

        return redirect()->route('admin.close-out-locations.index');
    }

    public function show(CloseOutLocation $closeOutLocation)
    {
        abort_if(Gate::denies('close_out_location_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutLocation->load('construction_contract', 'team');

        return view('admin.closeOutLocations.show', compact('closeOutLocation'));
    }

    public function destroy(CloseOutLocation $closeOutLocation)
    {
        abort_if(Gate::denies('close_out_location_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutLocation->delete();

        return back();
    }

    public function massDestroy(MassDestroyCloseOutLocationRequest $request)
    {
        $closeOutLocations = CloseOutLocation::find(request('ids'));

        foreach ($closeOutLocations as $closeOutLocation) {
            $closeOutLocation->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
