<?php

namespace App\Http\Controllers\Admin;

use App\AddDrawing;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyShopDrawingRequest;
use App\Http\Requests\StoreShopDrawingRequest;
use App\Http\Requests\UpdateShopDrawingRequest;
use App\ShopDrawing;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Team;


class ShopDrawingController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('shop_drawing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ShopDrawing::with(['drawing_references', 'construction_contract', 'team'])->select(sprintf('%s.*', (new ShopDrawing)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'shop_drawing_show';
                $editGate      = 'shop_drawing_edit';
                $deleteGate    = 'shop_drawing_delete';
                $crudRoutePart = 'shop-drawings';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('shop_drawing_title', function ($row) {
                return $row->shop_drawing_title ? $row->shop_drawing_title : "";
            });
            $table->editColumn('drawing_reference', function ($row) {
                $labels = [];

                foreach ($row->drawing_references as $drawing_reference) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $drawing_reference->coding_of_drawing);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('coding_of_shop_drawing', function ($row) {
                return $row->coding_of_shop_drawing ? $row->coding_of_shop_drawing : "";
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
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
            $table->editColumn('special_file_upload', function ($row) {
                if (!$row->special_file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->special_file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'drawing_reference', 'construction_contract', 'file_upload', 'special_file_upload']);

            return $table->make(true);
        }

        $add_drawings           = AddDrawing::get()->pluck('coding_of_drawing')->toArray();
        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('name')->toArray();

        return view('admin.shopDrawings.index', compact('add_drawings', 'construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('shop_drawing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $drawing_references = AddDrawing::all()->pluck('coding_of_drawing', 'id');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.shopDrawings.create', compact('drawing_references', 'construction_contracts'));
    }

    public function store(StoreShopDrawingRequest $request)
    {
        $shopDrawing = ShopDrawing::create($request->all());
        $shopDrawing->drawing_references()->sync($request->input('drawing_references', []));

        foreach ($request->input('file_upload', []) as $file) {
            $shopDrawing->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        foreach ($request->input('special_file_upload', []) as $file) {
            $shopDrawing->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('special_file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $shopDrawing->id]);
        }

        return redirect()->route('admin.shop-drawings.index');
    }

    public function edit(ShopDrawing $shopDrawing)
    {
        abort_if(Gate::denies('shop_drawing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $drawing_references = AddDrawing::all()->pluck('coding_of_drawing', 'id');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shopDrawing->load('drawing_references', 'construction_contract', 'team');

        return view('admin.shopDrawings.edit', compact('drawing_references', 'construction_contracts', 'shopDrawing'));
    }

    public function update(UpdateShopDrawingRequest $request, ShopDrawing $shopDrawing)
    {
        $shopDrawing->update($request->all());
        $shopDrawing->drawing_references()->sync($request->input('drawing_references', []));

        if (count($shopDrawing->file_upload) > 0) {
            foreach ($shopDrawing->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $shopDrawing->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $shopDrawing->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        if (count($shopDrawing->special_file_upload) > 0) {
            foreach ($shopDrawing->special_file_upload as $media) {
                if (!in_array($media->file_name, $request->input('special_file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $shopDrawing->special_file_upload->pluck('file_name')->toArray();

        foreach ($request->input('special_file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $shopDrawing->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('special_file_upload');
            }
        }

        return redirect()->route('admin.shop-drawings.index');
    }

    public function show(ShopDrawing $shopDrawing)
    {
        abort_if(Gate::denies('shop_drawing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopDrawing->load('drawing_references', 'construction_contract', 'team');

        return view('admin.shopDrawings.show', compact('shopDrawing'));
    }

    public function destroy(ShopDrawing $shopDrawing)
    {
        abort_if(Gate::denies('shop_drawing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopDrawing->delete();

        return back();
    }

    public function massDestroy(MassDestroyShopDrawingRequest $request)
    {
        ShopDrawing::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('shop_drawing_create') && Gate::denies('shop_drawing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ShopDrawing();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
