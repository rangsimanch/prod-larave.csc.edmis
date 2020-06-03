<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreVariationOrderRequest;
use App\Http\Requests\UpdateVariationOrderRequest;
use App\Http\Resources\Admin\VariationOrderResource;
use App\VariationOrder;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VariationOrderApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('variation_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VariationOrderResource(VariationOrder::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreVariationOrderRequest $request)
    {
        $variationOrder = VariationOrder::create($request->all());

        if ($request->input('file_upload', false)) {
            $variationOrder->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new VariationOrderResource($variationOrder))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(VariationOrder $variationOrder)
    {
        abort_if(Gate::denies('variation_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VariationOrderResource($variationOrder->load(['construction_contract', 'team']));
    }

    public function update(UpdateVariationOrderRequest $request, VariationOrder $variationOrder)
    {
        $variationOrder->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$variationOrder->file_upload || $request->input('file_upload') !== $variationOrder->file_upload->file_name) {
                $variationOrder->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($variationOrder->file_upload) {
            $variationOrder->file_upload->delete();
        }

        return (new VariationOrderResource($variationOrder))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(VariationOrder $variationOrder)
    {
        abort_if(Gate::denies('variation_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variationOrder->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
