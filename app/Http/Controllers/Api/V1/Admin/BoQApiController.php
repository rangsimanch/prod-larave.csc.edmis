<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\BoQ;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoQRequest;
use App\Http\Requests\UpdateBoQRequest;
use App\Http\Resources\Admin\BoQResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BoQApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('bo_q_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BoQResource(BoQ::all());
    }

    public function store(StoreBoQRequest $request)
    {
        $boQ = BoQ::create($request->all());

        return (new BoQResource($boQ))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BoQ $boQ)
    {
        abort_if(Gate::denies('bo_q_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BoQResource($boQ);
    }

    public function update(UpdateBoQRequest $request, BoQ $boQ)
    {
        $boQ->update($request->all());

        return (new BoQResource($boQ))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(BoQ $boQ)
    {
        abort_if(Gate::denies('bo_q_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boQ->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
