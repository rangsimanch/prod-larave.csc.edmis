<?php

namespace App\Http\Controllers\Admin;

use App\BoQ;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBoQRequest;
use App\Http\Requests\StoreBoQRequest;
use App\Http\Requests\UpdateBoQRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BoQController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BoQ::query()->select(sprintf('%s.*', (new BoQ)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'bo_q_show';
                $editGate      = 'bo_q_edit';
                $deleteGate    = 'bo_q_delete';
                $crudRoutePart = 'bo-qs';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.boQs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('bo_q_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.boQs.create');
    }

    public function store(StoreBoQRequest $request)
    {
        $boQ = BoQ::create($request->all());

        return redirect()->route('admin.bo-qs.index');
    }

    public function edit(BoQ $boQ)
    {
        abort_if(Gate::denies('bo_q_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.boQs.edit', compact('boQ'));
    }

    public function update(UpdateBoQRequest $request, BoQ $boQ)
    {
        $boQ->update($request->all());

        return redirect()->route('admin.bo-qs.index');
    }

    public function show(BoQ $boQ)
    {
        abort_if(Gate::denies('bo_q_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boQ->load('boqWbslevelfours');

        return view('admin.boQs.show', compact('boQ'));
    }

    public function destroy(BoQ $boQ)
    {
        abort_if(Gate::denies('bo_q_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boQ->delete();

        return back();
    }

    public function massDestroy(MassDestroyBoQRequest $request)
    {
        BoQ::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
