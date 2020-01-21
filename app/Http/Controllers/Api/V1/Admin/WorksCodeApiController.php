<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorksCodeRequest;
use App\Http\Requests\UpdateWorksCodeRequest;
use App\Http\Resources\Admin\WorksCodeResource;
use App\WorksCode;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorksCodeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('works_code_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorksCodeResource(WorksCode::all());
    }

    public function store(StoreWorksCodeRequest $request)
    {
        $worksCode = WorksCode::create($request->all());

        return (new WorksCodeResource($worksCode))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WorksCode $worksCode)
    {
        abort_if(Gate::denies('works_code_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorksCodeResource($worksCode);
    }

    public function update(UpdateWorksCodeRequest $request, WorksCode $worksCode)
    {
        $worksCode->update($request->all());

        return (new WorksCodeResource($worksCode))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WorksCode $worksCode)
    {
        abort_if(Gate::denies('works_code_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $worksCode->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
