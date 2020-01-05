<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWbsLevelThreeRequest;
use App\Http\Requests\StoreWbsLevelThreeRequest;
use App\Http\Requests\UpdateWbsLevelThreeRequest;
use App\WbsLevelThree;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WbsLevelThreeController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('wbs_level_three_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelThrees = WbsLevelThree::all();

        return view('admin.wbsLevelThrees.index', compact('wbsLevelThrees'));
    }

    public function create()
    {
        abort_if(Gate::denies('wbs_level_three_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelThrees.create');
    }

    public function store(StoreWbsLevelThreeRequest $request)
    {
        $wbsLevelThree = WbsLevelThree::create($request->all());

        return redirect()->route('admin.wbs-level-threes.index');
    }

    public function edit(WbsLevelThree $wbsLevelThree)
    {
        abort_if(Gate::denies('wbs_level_three_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbsLevelThrees.edit', compact('wbsLevelThree'));
    }

    public function update(UpdateWbsLevelThreeRequest $request, WbsLevelThree $wbsLevelThree)
    {
        $wbsLevelThree->update($request->all());

        return redirect()->route('admin.wbs-level-threes.index');
    }

    public function show(WbsLevelThree $wbsLevelThree)
    {
        abort_if(Gate::denies('wbs_level_three_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelThree->load('wbsLevel3Rfas');

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
