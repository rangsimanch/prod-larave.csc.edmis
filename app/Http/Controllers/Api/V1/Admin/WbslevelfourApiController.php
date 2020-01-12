<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWbslevelfourRequest;
use App\Http\Requests\UpdateWbslevelfourRequest;
use App\Http\Resources\Admin\WbslevelfourResource;
use App\Wbslevelfour;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WbslevelfourApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wbslevelfour_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbslevelfourResource(Wbslevelfour::with(['boq'])->get());
    }

    public function store(StoreWbslevelfourRequest $request)
    {
        $wbslevelfour = Wbslevelfour::create($request->all());

        return (new WbslevelfourResource($wbslevelfour))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbslevelfourResource($wbslevelfour->load(['boq']));
    }

    public function update(UpdateWbslevelfourRequest $request, Wbslevelfour $wbslevelfour)
    {
        $wbslevelfour->update($request->all());

        return (new WbslevelfourResource($wbslevelfour))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Wbslevelfour $wbslevelfour)
    {
        abort_if(Gate::denies('wbslevelfour_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbslevelfour->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
