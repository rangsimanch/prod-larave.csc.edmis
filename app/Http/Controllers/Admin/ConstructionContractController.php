<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyConstructionContractRequest;
use App\Http\Requests\StoreConstructionContractRequest;
use App\Http\Requests\UpdateConstructionContractRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConstructionContractController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('construction_contract_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constructionContracts = ConstructionContract::all();

        return view('admin.constructionContracts.index', compact('constructionContracts'));
    }

    public function create()
    {
        abort_if(Gate::denies('construction_contract_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.constructionContracts.create');
    }

    public function store(StoreConstructionContractRequest $request)
    {
        $constructionContract = ConstructionContract::create($request->all());

        return redirect()->route('admin.construction-contracts.index');
    }

    public function edit(ConstructionContract $constructionContract)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.constructionContracts.edit', compact('constructionContract'));
    }

    public function update(UpdateConstructionContractRequest $request, ConstructionContract $constructionContract)
    {
        $constructionContract->update($request->all());

        return redirect()->route('admin.construction-contracts.index');
    }

    public function show(ConstructionContract $constructionContract)
    {
        abort_if(Gate::denies('construction_contract_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constructionContract->load('constructionContractRfas', 'constructionContractUsers', 'constructionContractTasks', 'constructionContractFileManagers');

        return view('admin.constructionContracts.show', compact('constructionContract'));
    }

    public function destroy(ConstructionContract $constructionContract)
    {
        abort_if(Gate::denies('construction_contract_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constructionContract->delete();

        return back();
    }

    public function massDestroy(MassDestroyConstructionContractRequest $request)
    {
        ConstructionContract::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
