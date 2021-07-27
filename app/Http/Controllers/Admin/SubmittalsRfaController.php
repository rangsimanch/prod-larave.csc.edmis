<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubmittalsRfaRequest;
use App\Http\Requests\StoreSubmittalsRfaRequest;
use App\Http\Requests\UpdateSubmittalsRfaRequest;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Rfa;
use App\RfaCommentStatus;
use App\SubmittalsRfa;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubmittalsRfaController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('submittals_rfa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $submittalsRfas = SubmittalsRfa::all();

        return view('admin.submittalsRfas.index', compact('submittalsRfas'));
    }

    public function create()
    {
        abort_if(Gate::denies('submittals_rfa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $on_rfas = Rfa::all()->pluck('rfa_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.submittalsRfas.create', compact('review_statuses', 'on_rfas'));
    }

    public function store(StoreSubmittalsRfaRequest $request)
    {
        $submittalsRfa = SubmittalsRfa::create($request->all());

        return redirect()->route('admin.submittals-rfas.index');
    }

    public function edit(SubmittalsRfa $submittalsRfa)
    {
        abort_if(Gate::denies('submittals_rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $on_rfas = Rfa::all()->pluck('rfa_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $submittalsRfa->load('review_status', 'on_rfa');

        return view('admin.submittalsRfas.edit', compact('review_statuses', 'on_rfas', 'submittalsRfa'));
    }

    public function update(UpdateSubmittalsRfaRequest $request, SubmittalsRfa $submittalsRfa)
    {
        $submittalsRfa->update($request->all());

        return redirect()->route('admin.submittals-rfas.index');
    }

    public function show(SubmittalsRfa $submittalsRfa)
    {
        abort_if(Gate::denies('submittals_rfa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $submittalsRfa->load('review_status', 'on_rfa');

        return view('admin.submittalsRfas.show', compact('submittalsRfa'));
    }

    public function destroy(SubmittalsRfa $submittalsRfa)
    {
        abort_if(Gate::denies('submittals_rfa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $submittalsRfa->delete();

        return back();
    }

    public function massDestroy(MassDestroySubmittalsRfaRequest $request)
    {
        SubmittalsRfa::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
