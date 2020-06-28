<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWbsLevelTwoRequest;
use App\Http\Requests\UpdateWbsLevelTwoRequest;
use App\Http\Resources\Admin\WbsLevelTwoResource;
use App\WbsLevelTwo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WbsLevelTwoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wbs_level_two_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbsLevelTwoResource(WbsLevelTwo::all());
    }

    public function store(StoreWbsLevelTwoRequest $request)
    {
        $wbsLevelTwo = WbsLevelTwo::create($request->all());

        return (new WbsLevelTwoResource($wbsLevelTwo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WbsLevelTwo $wbsLevelTwo)
    {
        abort_if(Gate::denies('wbs_level_two_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbsLevelTwoResource($wbsLevelTwo);
    }

    public function update(UpdateWbsLevelTwoRequest $request, WbsLevelTwo $wbsLevelTwo)
    {
        $wbsLevelTwo->update($request->all());

        return (new WbsLevelTwoResource($wbsLevelTwo))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WbsLevelTwo $wbsLevelTwo)
    {
        abort_if(Gate::denies('wbs_level_two_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelTwo->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
