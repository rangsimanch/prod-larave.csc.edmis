<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreInterimPaymentRequest;
use App\Http\Requests\UpdateInterimPaymentRequest;
use App\Http\Resources\Admin\InterimPaymentResource;
use App\InterimPayment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InterimPaymentApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('interim_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InterimPaymentResource(InterimPayment::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreInterimPaymentRequest $request)
    {
        $interimPayment = InterimPayment::create($request->all());

        if ($request->input('file_upload', false)) {
            $interimPayment->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new InterimPaymentResource($interimPayment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(InterimPayment $interimPayment)
    {
        abort_if(Gate::denies('interim_payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InterimPaymentResource($interimPayment->load(['construction_contract', 'team']));
    }

    public function update(UpdateInterimPaymentRequest $request, InterimPayment $interimPayment)
    {
        $interimPayment->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$interimPayment->file_upload || $request->input('file_upload') !== $interimPayment->file_upload->file_name) {
                $interimPayment->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($interimPayment->file_upload) {
            $interimPayment->file_upload->delete();
        }

        return (new InterimPaymentResource($interimPayment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(InterimPayment $interimPayment)
    {
        abort_if(Gate::denies('interim_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interimPayment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
