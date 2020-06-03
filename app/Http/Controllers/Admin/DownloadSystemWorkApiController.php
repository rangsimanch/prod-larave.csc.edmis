<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DownloadSystemWork;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDownloadSystemWorkRequest;
use App\Http\Requests\UpdateDownloadSystemWorkRequest;
use App\Http\Resources\Admin\DownloadSystemWorkResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DownloadSystemWorkApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('download_system_work_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DownloadSystemWorkResource(DownloadSystemWork::all());
    }

    public function store(StoreDownloadSystemWorkRequest $request)
    {
        $downloadSystemWork = DownloadSystemWork::create($request->all());

        return (new DownloadSystemWorkResource($downloadSystemWork))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DownloadSystemWork $downloadSystemWork)
    {
        abort_if(Gate::denies('download_system_work_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DownloadSystemWorkResource($downloadSystemWork);
    }

    public function update(UpdateDownloadSystemWorkRequest $request, DownloadSystemWork $downloadSystemWork)
    {
        $downloadSystemWork->update($request->all());

        return (new DownloadSystemWorkResource($downloadSystemWork))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DownloadSystemWork $downloadSystemWork)
    {
        abort_if(Gate::denies('download_system_work_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $downloadSystemWork->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
