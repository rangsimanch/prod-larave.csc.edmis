<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobtitleRequest;
use App\Http\Requests\UpdateJobtitleRequest;
use App\Http\Resources\Admin\JobtitleResource;
use App\Jobtitle;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobtitleApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('jobtitle_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JobtitleResource(Jobtitle::with(['departments'])->get());
    }

    public function store(StoreJobtitleRequest $request)
    {
        $jobtitle = Jobtitle::create($request->all());
        $jobtitle->departments()->sync($request->input('departments', []));

        return (new JobtitleResource($jobtitle))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Jobtitle $jobtitle)
    {
        abort_if(Gate::denies('jobtitle_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JobtitleResource($jobtitle->load(['departments']));
    }

    public function update(UpdateJobtitleRequest $request, Jobtitle $jobtitle)
    {
        $jobtitle->update($request->all());
        $jobtitle->departments()->sync($request->input('departments', []));

        return (new JobtitleResource($jobtitle))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Jobtitle $jobtitle)
    {
        abort_if(Gate::denies('jobtitle_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jobtitle->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
