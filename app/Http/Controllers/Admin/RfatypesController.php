<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRfatypeRequest;
use App\Http\Requests\StoreRfatypeRequest;
use App\Http\Requests\UpdateRfatypeRequest;
use App\Rfatype;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfatypesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('rfatype_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfatypes = Rfatype::all();

        return view('admin.rfatypes.index', compact('rfatypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('rfatype_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfatypes.create');
    }

    public function store(StoreRfatypeRequest $request)
    {
        $rfatype = Rfatype::create($request->all());

        return redirect()->route('admin.rfatypes.index');
    }

    public function edit(Rfatype $rfatype)
    {
        abort_if(Gate::denies('rfatype_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfatypes.edit', compact('rfatype'));
    }

    public function update(UpdateRfatypeRequest $request, Rfatype $rfatype)
    {
        $rfatype->update($request->all());

        return redirect()->route('admin.rfatypes.index');
    }

    public function show(Rfatype $rfatype)
    {
        abort_if(Gate::denies('rfatype_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rfatypes.show', compact('rfatype'));
    }

    public function destroy(Rfatype $rfatype)
    {
        abort_if(Gate::denies('rfatype_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfatype->delete();

        return back();
    }

    public function massDestroy(MassDestroyRfatypeRequest $request)
    {
        Rfatype::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
