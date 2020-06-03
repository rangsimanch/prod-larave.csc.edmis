<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyVariationOrderRequest;
use App\Http\Requests\StoreVariationOrderRequest;
use App\Http\Requests\UpdateVariationOrderRequest;
use App\VariationOrder;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Team;


class VariationOrderController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('variation_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = VariationOrder::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new VariationOrder)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'variation_order_show';
                $editGate      = 'variation_order_edit';
                $deleteGate    = 'variation_order_delete';
                $crudRoutePart = 'variation-orders';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('type_of_vo', function ($row) {
                return $row->type_of_vo ? VariationOrder::TYPE_OF_VO_SELECT[$row->type_of_vo] : '';
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('work_title', function ($row) {
                return $row->work_title ? $row->work_title : "";
            });

            $table->editColumn('file_upload', function ($row) {
                if (!$row->file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'file_upload']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('name')->toArray();

        return view('admin.variationOrders.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('variation_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.variationOrders.create', compact('construction_contracts'));
    }

    public function store(StoreVariationOrderRequest $request)
    {
        $variationOrder = VariationOrder::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $variationOrder->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $variationOrder->id]);
        }

        return redirect()->route('admin.variation-orders.index');
    }

    public function edit(VariationOrder $variationOrder)
    {
        abort_if(Gate::denies('variation_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variationOrder->load('construction_contract', 'team');

        return view('admin.variationOrders.edit', compact('construction_contracts', 'variationOrder'));
    }

    public function update(UpdateVariationOrderRequest $request, VariationOrder $variationOrder)
    {
        $variationOrder->update($request->all());

        if (count($variationOrder->file_upload) > 0) {
            foreach ($variationOrder->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $variationOrder->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $variationOrder->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.variation-orders.index');
    }

    public function show(VariationOrder $variationOrder)
    {
        abort_if(Gate::denies('variation_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variationOrder->load('construction_contract', 'team');

        return view('admin.variationOrders.show', compact('variationOrder'));
    }

    public function destroy(VariationOrder $variationOrder)
    {
        abort_if(Gate::denies('variation_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variationOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyVariationOrderRequest $request)
    {
        VariationOrder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('variation_order_create') && Gate::denies('variation_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new VariationOrder();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
