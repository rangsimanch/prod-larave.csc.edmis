<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWbsLevelFiveRequest;
use App\Http\Requests\UpdateWbsLevelFiveRequest;
use App\Http\Resources\Admin\WbsLevelFiveResource;
use App\WbsLevelFive;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WbsLevelFiveApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wbs_level_five_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbsLevelFiveResource(WbsLevelFive::all());
    }

    public function store(StoreWbsLevelFiveRequest $request)
    {
        $wbsLevelFive = WbsLevelFive::create($request->all());

        return (new WbsLevelFiveResource($wbsLevelFive))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WbsLevelFive $wbsLevelFive)
    {
        abort_if(Gate::denies('wbs_level_five_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WbsLevelFiveResource($wbsLevelFive);
    }

    public function update(UpdateWbsLevelFiveRequest $request, WbsLevelFive $wbsLevelFive)
    {
        $wbsLevelFive->update($request->all());

        return (new WbsLevelFiveResource($wbsLevelFive))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WbsLevelFive $wbsLevelFive)
    {
        abort_if(Gate::denies('wbs_level_five_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wbsLevelFive->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
