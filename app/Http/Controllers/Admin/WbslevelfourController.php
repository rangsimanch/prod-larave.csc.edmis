<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWbslevelfourRequest;
use App\Http\Requests\StoreWbslevelfourRequest;
use App\Http\Requests\UpdateWbslevelfourRequest;
use App\Wbslevelfour;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WbslevelfourController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wbslevelfour_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbslevelfours = Wbslevelfour::all();

        return view('admin.wbslevelfours.index', compact('wbslevelfours'));
    }

    public function create()
    {
        abort_if(Gate::denies('wbslevelfour_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbslevelfours.create');
    }

    public function store(StoreWbslevelfourRequest $request)
    {
        $wbslevelfour = Wbslevelfour::create($request->all());

        return redirect()->route('admin.wbslevelfours.index');
    }

    public function edit(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wbslevelfours.edit', compact('wbslevelfour'));
    }

    public function update(UpdateWbslevelfourRequest $request, Wbslevelfour $wbslevelfour)
    {
        $wbslevelfour->update($request->all());

        return redirect()->route('admin.wbslevelfours.index');
    }

    public function show(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbslevelfour->load('wbsLevel4Rfas');

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
