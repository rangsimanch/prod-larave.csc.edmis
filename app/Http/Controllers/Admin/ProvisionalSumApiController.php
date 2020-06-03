<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProvisionalSumRequest;
use App\Http\Requests\UpdateProvisionalSumRequest;
use App\Http\Resources\Admin\ProvisionalSumResource;
use App\ProvisionalSum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProvisionalSumApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('provisional_sum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProvisionalSumResource(ProvisionalSum::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreProvisionalSumRequest $request)
    {
        $provisionalSum = ProvisionalSum::create($request->all());

        if ($request->input('file_upload', false)) {
            $provisionalSum->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new ProvisionalSumResource($provisionalSum))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ProvisionalSum $provisionalSum)
    {
        abort_if(Gate::denies('provisional_sum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProvisionalSumResource($provisionalSum->load(['construction_contract', 'team']));
    }

    public function update(UpdateProvisionalSumRequest $request, ProvisionalSum $provisionalSum)
    {
        $provisionalSum->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$provisionalSum->file_upload || $request->input('file_upload') !== $provisionalSum->file_upload->file_name) {
                $provisionalSum->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($provisionalSum->file_upload) {
            $provisionalSum->file_upload->delete();
        }

        return (new ProvisionalSumResource($provisionalSum))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ProvisionalSum $provisionalSum)
    {
        abort_if(Gate::denies('provisional_sum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provisionalSum->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
