<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWbsLevelOneRequest;
use App\Http\Requests\UpdateWbsLevelOneRequest;
use App\Http\Resources\Admin\WbsLevelOneResource;
use App\WbsLevelOne;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WbsLevelOneApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wbs_level_one_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbsLevelOneResource(WbsLevelOne::all());
    }

    public function store(StoreWbsLevelOneRequest $request)
    {
        $wbsLevelOne = WbsLevelOne::create($request->all());

        return (new WbsLevelOneResource($wbsLevelOne))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WbsLevelOne $wbsLevelOne)
    {
        abort_if(Gate::denies('wbs_level_one_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbsLevelOneResource($wbsLevelOne);
    }

    public function update(UpdateWbsLevelOneRequest $request, WbsLevelOne $wbsLevelOne)
    {
        $wbsLevelOne->update($request->all());

        return (new WbsLevelOneResource($wbsLevelOne))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WbsLevelOne $wbsLevelOne)
    {
        abort_if(Gate::denies('wbs_level_one_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelOne->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
