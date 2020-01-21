<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorksCodeRequest;
use App\Http\Requests\StoreWorksCodeRequest;
use App\Http\Requests\UpdateWorksCodeRequest;
use App\WorksCode;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorksCodeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('works_code_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $worksCodes = WorksCode::all();

        return view('admin.worksCodes.index', compact('worksCodes'));
    }

    public function create()
    {
        abort_if(Gate::denies('works_code_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.worksCodes.create');
    }

    public function store(StoreWorksCodeRequest $request)
    {
        $worksCode = WorksCode::create($request->all());

        return redirect()->route('admin.works-codes.index');
    }

    public function edit(WorksCode $worksCode)
    {
        abort_if(Gate::denies('works_code_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.worksCodes.edit', compact('worksCode'));
    }

    public function update(UpdateWorksCodeRequest $request, WorksCode $worksCode)
    {
        $worksCode->update($request->all());

        return redirect()->route('admin.works-codes.index');
    }

    public function show(WorksCode $worksCode)
    {
        abort_if(Gate::denies('works_code_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.worksCodes.show', compact('worksCode'));
    }

    public function destroy(WorksCode $worksCode)
    {
        abort_if(Gate::denies('works_code_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $worksCode->delete();

        return back();
    }

    public function massDestroy(MassDestroyWorksCodeRequest $request)
    {
        WorksCode::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
