<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DownloadSystemActivity;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDownloadSystemActivityRequest;
use App\Http\Requests\UpdateDownloadSystemActivityRequest;
use App\Http\Resources\Admin\DownloadSystemActivityResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DownloadSystemActivityApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('download_system_activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DownloadSystemActivityResource(DownloadSystemActivity::all());
    }

    public function store(StoreDownloadSystemActivityRequest $request)
    {
        $downloadSystemActivity = DownloadSystemActivity::create($request->all());

        return (new DownloadSystemActivityResource($downloadSystemActivity))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DownloadSystemActivity $downloadSystemActivity)
    {
        abort_if(Gate::denies('download_system_activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DownloadSystemActivityResource($downloadSystemActivity);
    }

    public function update(UpdateDownloadSystemActivityRequest $request, DownloadSystemActivity $downloadSystemActivity)
    {
        $downloadSystemActivity->update($request->all());

        return (new DownloadSystemActivityResource($downloadSystemActivity))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DownloadSystemActivity $downloadSystemActivity)
    {
        abort_if(Gate::denies('download_system_activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $downloadSystemActivity->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
