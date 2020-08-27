<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\BoqItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoqItemRequest;
use App\Http\Requests\UpdateBoqItemRequest;
use App\Http\Resources\Admin\BoqItemResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BoqItemApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('boq_item_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BoqItemResource(BoqItem::with(['boq'])->get());
    }

    public function store(StoreBoqItemRequest $request)
    {
        $boqItem = BoqItem::create($request->all());

        return (new BoqItemResource($boqItem))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BoqItem $boqItem)
    {
        abort_if(Gate::denies('boq_item_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BoqItemResource($boqItem->load(['boq']));
    }

    public function update(UpdateBoqItemRequest $request, BoqItem $boqItem)
    {
        $boqItem->update($request->all());

        return (new BoqItemResource($boqItem))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(BoqItem $boqItem)
    {
        abort_if(Gate::denies('boq_item_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boqItem->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
