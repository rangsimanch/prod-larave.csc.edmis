<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CloseOutDescription;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCloseOutDescriptionRequest;
use App\Http\Requests\UpdateCloseOutDescriptionRequest;
use App\Http\Resources\Admin\CloseOutDescriptionResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloseOutDescriptionApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('close_out_description_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CloseOutDescriptionResource(CloseOutDescription::all());
    }

    public function store(StoreCloseOutDescriptionRequest $request)
    {
        $closeOutDescription = CloseOutDescription::create($request->all());

        return (new CloseOutDescriptionResource($closeOutDescription))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateCloseOutDescriptionRequest $request, CloseOutDescription $closeOutDescription)
    {
        $closeOutDescription->update($request->all());

        return (new CloseOutDescriptionResource($closeOutDescription))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CloseOutDescription $closeOutDescription)
    {
        abort_if(Gate::denies('close_out_description_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutDescription->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
