<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRfatypeRequest;
use App\Http\Requests\UpdateRfatypeRequest;
use App\Http\Resources\Admin\RfatypeResource;
use App\Rfatype;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfatypesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('rfatype_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RfatypeResource(Rfatype::all());
    }

    public function store(StoreRfatypeRequest $request)
    {
        $rfatype = Rfatype::create($request->all());

        return (new RfatypeResource($rfatype))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Rfatype $rfatype)
    {
        abort_if(Gate::denies('rfatype_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RfatypeResource($rfatype);
    }

    public function update(UpdateRfatypeRequest $request, Rfatype $rfatype)
    {
        $rfatype->update($request->all());

        return (new RfatypeResource($rfatype))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Rfatype $rfatype)
    {
        abort_if(Gate::denies('rfatype_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfatype->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
