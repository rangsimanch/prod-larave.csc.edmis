<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreShopDrawingRequest;
use App\Http\Requests\UpdateShopDrawingRequest;
use App\Http\Resources\Admin\ShopDrawingResource;
use App\ShopDrawing;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopDrawingApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('shop_drawing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ShopDrawingResource(ShopDrawing::with(['drawing_references', 'construction_contract', 'team'])->get());
    }

    public function store(StoreShopDrawingRequest $request)
    {
        $shopDrawing = ShopDrawing::create($request->all());
        $shopDrawing->drawing_references()->sync($request->input('drawing_references', []));

        if ($request->input('file_upload', false)) {
            $shopDrawing->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        if ($request->input('special_file_upload', false)) {
            $shopDrawing->addMedia(storage_path('tmp/uploads/' . $request->input('special_file_upload')))->toMediaCollection('special_file_upload');
        }

        return (new ShopDrawingResource($shopDrawing))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ShopDrawing $shopDrawing)
    {
        abort_if(Gate::denies('shop_drawing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ShopDrawingResource($shopDrawing->load(['drawing_references', 'construction_contract', 'team']));
    }

    public function update(UpdateShopDrawingRequest $request, ShopDrawing $shopDrawing)
    {
        $shopDrawing->update($request->all());
        $shopDrawing->drawing_references()->sync($request->input('drawing_references', []));

        if ($request->input('file_upload', false)) {
            if (!$shopDrawing->file_upload || $request->input('file_upload') !== $shopDrawing->file_upload->file_name) {
                $shopDrawing->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($shopDrawing->file_upload) {
            $shopDrawing->file_upload->delete();
        }

        if ($request->input('special_file_upload', false)) {
            if (!$shopDrawing->special_file_upload || $request->input('special_file_upload') !== $shopDrawing->special_file_upload->file_name) {
                $shopDrawing->addMedia(storage_path('tmp/uploads/' . $request->input('special_file_upload')))->toMediaCollection('special_file_upload');
            }
        } elseif ($shopDrawing->special_file_upload) {
            $shopDrawing->special_file_upload->delete();
        }

        return (new ShopDrawingResource($shopDrawing))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ShopDrawing $shopDrawing)
    {
        abort_if(Gate::denies('shop_drawing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopDrawing->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
