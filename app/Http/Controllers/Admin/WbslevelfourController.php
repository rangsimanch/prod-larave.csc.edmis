<?php

namespace App\Http\Controllers\Admin;

use App\BoQ;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWbslevelfourRequest;
use App\Http\Requests\StoreWbslevelfourRequest;
use App\Http\Requests\UpdateWbslevelfourRequest;
use App\Wbslevelfour;
use App\WbsLevelThree;
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

        $boqs = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_threes = WbsLevelThree::all()->pluck('wbs_level_3_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.wbslevelfours.create', compact('boqs', 'wbs_level_threes'));
    }

    public function store(StoreWbslevelfourRequest $request)
    {
        $wbslevelfour = Wbslevelfour::create($request->all());

        return redirect()->route('admin.wbslevelfours.index');
    }

    public function edit(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boqs = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_threes = WbsLevelThree::all()->pluck('wbs_level_3_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbslevelfour->load('boq', 'wbs_level_three');

        return view('admin.wbslevelfours.edit', compact('boqs', 'wbs_level_threes', 'wbslevelfour'));
    }

    public function update(UpdateWbslevelfourRequest $request, Wbslevelfour $wbslevelfour)
    {
        $wbslevelfour->update($request->all());

        return redirect()->route('admin.wbslevelfours.index');
    }

    public function show(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbslevelfour->load('boq', 'wbs_level_three', 'wbsLevel4Rfas');

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
