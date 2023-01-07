<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySubmittalsRfaRequest;
use App\Http\Requests\StoreSubmittalsRfaRequest;
use App\Http\Requests\UpdateSubmittalsRfaRequest;
use App\Rfa;
use App\RfaCommentStatus;
use App\SubmittalsRfa;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubmittalsRfaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('submittals_rfa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SubmittalsRfa::with(['review_status', 'on_rfa'])->select(sprintf('%s.*', (new SubmittalsRfa())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'submittals_rfa_show';
                $editGate = 'submittals_rfa_edit';
                $deleteGate = 'submittals_rfa_delete';
                $crudRoutePart = 'submittals-rfas';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('item_no', function ($row) {
                return $row->item_no ? $row->item_no : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('qty_sets', function ($row) {
                return $row->qty_sets ? $row->qty_sets : '';
            });
            $table->addColumn('review_status_name', function ($row) {
                return $row->review_status ? $row->review_status->name : '';
            });

            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });
            $table->addColumn('on_rfa_rfa_code', function ($row) {
                return $row->on_rfa ? $row->on_rfa->rfa_code : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'review_status', 'on_rfa']);

            return $table->make(true);
        }

        $rfa_comment_statuses = RfaCommentStatus::get();
        $rfas                 = Rfa::get();

        return view('admin.submittalsRfas.index', compact('rfa_comment_statuses', 'rfas'));
    }

    public function create()
    {
        abort_if(Gate::denies('submittals_rfa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review_statuses = RfaCommentStatus::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $on_rfas = Rfa::pluck('rfa_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.submittalsRfas.create', compact('on_rfas', 'review_statuses'));
    }

    public function store(StoreSubmittalsRfaRequest $request)
    {
        $submittalsRfa = SubmittalsRfa::create($request->all());

        return redirect()->route('admin.submittals-rfas.index');
    }

    public function edit(SubmittalsRfa $submittalsRfa)
    {
        abort_if(Gate::denies('submittals_rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review_statuses = RfaCommentStatus::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $on_rfas = Rfa::pluck('rfa_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $submittalsRfa->load('review_status', 'on_rfa');

        return view('admin.submittalsRfas.edit', compact('on_rfas', 'review_statuses', 'submittalsRfa'));
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
