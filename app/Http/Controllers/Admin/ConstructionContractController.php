<?php

namespace App\Http\Controllers\Admin;


use App\User;
use App\Rfa;
use App\Task;
use App\FileManager;
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
    /**
     * Display a listing of Team.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('construction_contract_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constructionContracts = ConstructionContract::all();

        return view('admin.constructionContracts.index', compact('constructionContracts'));
    }
    
    /**
     * Show the form for creating new Team.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('construction_contract_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.constructionContracts.create');
    }

     /**
     * Store a newly created Team in storage.
     *
     * @param  \App\Http\Requests\StoreConstructionContractRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConstructionContractRequest $request)
    {
        $constructionContract = ConstructionContract::create($request->all());

        return redirect()->route('admin.construction-contracts.index');
    }

    /**
     * Show the form for editing Team.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $constructionContract = ConstructionContract::findOrFail($id);

        return view('admin.constructionContracts.edit', compact('constructionContract'));
    }

    /**
     * Update Team in storage.
     *
     * @param  \App\Http\Requests\StoreConstructionContractRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConstructionContractRequest $request, $id)
    {   
        $constructionContract = ConstructionContract::findOrFail($id);
        $constructionContract->update($request->all());
        return redirect()->route('admin.construction-contracts.index');
    }

    /**
     * Display Team.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('construction_contract_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constructionContract = ConstructionContract::findOrFail($id);
        $task = Task::where('construction_contract', $id)->get();
        $filemanager = FileManager::where('construction_contract', $id)->get();
        $rfa = Rfa::where('construction_contract', $id)->get();
        $users = User::whereHas('construction_contract', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();
       
        // $constructionContract->load('constructionContractRfas', 'constructionContractUsers', 'constructionContractTasks', 'constructionContractFileManagers');

        return view('admin.constructionContracts.show', compact('constructionContract', 'task', 'filemanager', 'rfa', 'users'));
    }


    /**
     * Remove Team from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('construction_contract_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constructionContracts = ConstructionContract::findOrFail($id);
        $constructionContracts->delete();

        return back();
    }

    public function massDestroy(MassDestroyConstructionContractRequest $request)
    {
        ConstructionContract::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
