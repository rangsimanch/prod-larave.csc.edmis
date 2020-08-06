<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWbsLevelThreeRequest;
use App\Http\Requests\UpdateWbsLevelThreeRequest;
use App\Http\Resources\Admin\WbsLevelThreeResource;
use App\WbsLevelThree;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WbsLevelThreeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wbs_level_three_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbsLevelThreeResource(WbsLevelThree::with(['wbs_level_2'])->get());
    }

    public function store(StoreWbsLevelThreeRequest $request)
    {
        $wbsLevelThree = WbsLevelThree::create($request->all());

        return (new WbsLevelThreeResource($wbsLevelThree))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WbsLevelThree $wbsLevelThree)
    {
        abort_if(Gate::denies('wbs_level_three_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbsLevelThreeResource($wbsLevelThree->load(['wbs_level_2']));
    }

    public function update(UpdateWbsLevelThreeRequest $request, WbsLevelThree $wbsLevelThree)
    {
        $wbsLevelThree->update($request->all());

        return (new WbsLevelThreeResource($wbsLevelThree))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WbsLevelThree $wbsLevelThree)
    {
        abort_if(Gate::denies('wbs_level_three_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelThree->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
