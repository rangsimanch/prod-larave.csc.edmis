<?php

namespace App\Http\Controllers\Admin;

use App\CloseOutWorkType;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCloseOutWorkTypeRequest;
use App\Http\Requests\StoreCloseOutWorkTypeRequest;
use App\Http\Requests\UpdateCloseOutWorkTypeRequest;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CloseOutWorkTypeController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('close_out_work_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CloseOutWorkType::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new CloseOutWorkType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'close_out_work_type_show';
                $editGate      = 'close_out_work_type_edit';
                $deleteGate    = 'close_out_work_type_delete';
                $crudRoutePart = 'close-out-work-types';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('work_type_name', function ($row) {
                return $row->work_type_name ? $row->work_type_name : '';
            });
            $table->editColumn('work_type_code', function ($row) {
                return $row->work_type_code ? $row->work_type_code : '';
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();

        return view('admin.closeOutWorkTypes.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('close_out_work_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.closeOutWorkTypes.create', compact('construction_contracts'));
    }

    public function store(StoreCloseOutWorkTypeRequest $request)
    {
        $closeOutWorkType = CloseOutWorkType::create($request->all());

        return redirect()->route('admin.close-out-work-types.index');
    }

    public function edit(CloseOutWorkType $closeOutWorkType)
    {
        abort_if(Gate::denies('close_out_work_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $closeOutWorkType->load('construction_contract', 'team');

        return view('admin.closeOutWorkTypes.edit', compact('closeOutWorkType', 'construction_contracts'));
    }

    public function update(UpdateCloseOutWorkTypeRequest $request, CloseOutWorkType $closeOutWorkType)
    {
        $closeOutWorkType->update($request->all());

        return redirect()->route('admin.close-out-work-types.index');
    }

    public function show(CloseOutWorkType $closeOutWorkType)
    {
        abort_if(Gate::denies('close_out_work_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutWorkType->load('construction_contract', 'team');

        return view('admin.closeOutWorkTypes.show', compact('closeOutWorkType'));
    }

    public function destroy(CloseOutWorkType $closeOutWorkType)
    {
        abort_if(Gate::denies('close_out_work_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutWorkType->delete();

        return back();
    }

    public function massDestroy(MassDestroyCloseOutWorkTypeRequest $request)
    {
        $closeOutWorkTypes = CloseOutWorkType::find(request('ids'));

        foreach ($closeOutWorkTypes as $closeOutWorkType) {
            $closeOutWorkType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
