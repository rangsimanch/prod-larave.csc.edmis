<?php

namespace App\Http\Controllers\Admin;

use App\BoQ;
use App\BoqItem;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBoqItemRequest;
use App\Http\Requests\StoreBoqItemRequest;
use App\Http\Requests\UpdateBoqItemRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BoqItemController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('boq_item_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BoqItem::with(['boq'])->select(sprintf('%s.*', (new BoqItem)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'boq_item_show';
                $editGate      = 'boq_item_edit';
                $deleteGate    = 'boq_item_delete';
                $crudRoutePart = 'boq-items';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('boq_name', function ($row) {
                return $row->boq ? $row->boq->name : '';
            });

            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('unit', function ($row) {
                return $row->unit ? $row->unit : "";
            });
            $table->editColumn('quantity', function ($row) {
                return number_format($row->quantity,2) ? $row->quantity : "";
            });
            $table->editColumn('unit_rate', function ($row) {
                return number_format($row->unit_rate,2) ? $row->unit_rate : "";
            });
            $table->editColumn('amount', function ($row) {
                return number_format($row->amount,2) ? $row->amount : "";
            });
            $table->editColumn('factor_f', function ($row) {
                return number_format($row->factor_f,2) ? $row->factor_f : "";
            });
            $table->editColumn('unit_rate_x_ff', function ($row) {
                return number_format($row->unit_rate_x_ff,2) ? $row->unit_rate_x_ff : "";
            });
            $table->editColumn('total_amount', function ($row) {
                return number_format($row->total_amount,2) ? $row->total_amount : "";
            });
            $table->editColumn('remark', function ($row) {
                return $row->remark ? $row->remark : "";
            });

            $table->rawColumns(['actions', 'placeholder', 'boq']);

            return $table->make(true);
        }

        $bo_qs = BoQ::get();

        return view('admin.boqItems.index', compact('bo_qs'));
    }

    public function create()
    {
        abort_if(Gate::denies('boq_item_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boqs = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.boqItems.create', compact('boqs'));
    }

    public function store(StoreBoqItemRequest $request)
    {
        $boqItem = BoqItem::create($request->all());

        return redirect()->route('admin.boq-items.index');
    }

    public function edit(BoqItem $boqItem)
    {
        abort_if(Gate::denies('boq_item_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boqs = BoQ::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boqItem->load('boq');

        return view('admin.boqItems.edit', compact('boqs', 'boqItem'));
    }

    public function update(UpdateBoqItemRequest $request, BoqItem $boqItem)
    {
        $boqItem->update($request->all());

        return redirect()->route('admin.boq-items.index');
    }

    public function show(BoqItem $boqItem)
    {
        abort_if(Gate::denies('boq_item_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boqItem->load('boq');

        return view('admin.boqItems.show', compact('boqItem'));
    }

    public function destroy(BoqItem $boqItem)
    {
        abort_if(Gate::denies('boq_item_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boqItem->delete();

        return back();
    }

    public function massDestroy(MassDestroyBoqItemRequest $request)
    {
        BoqItem::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
