<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreInterimPaymentMeetingRequest;
use App\Http\Requests\UpdateInterimPaymentMeetingRequest;
use App\Http\Resources\Admin\InterimPaymentMeetingResource;
use App\InterimPaymentMeeting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InterimPaymentMeetingApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('interim_payment_meeting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InterimPaymentMeetingResource(InterimPaymentMeeting::with(['construction_contract', 'team'])->get());
    }

    public function store(StoreInterimPaymentMeetingRequest $request)
    {
        $interimPaymentMeeting = InterimPaymentMeeting::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $interimPaymentMeeting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
        }

        return (new InterimPaymentMeetingResource($interimPaymentMeeting))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(InterimPaymentMeeting $interimPaymentMeeting)
    {
        abort_if(Gate::denies('interim_payment_meeting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InterimPaymentMeetingResource($interimPaymentMeeting->load(['construction_contract', 'team']));
    }

    public function update(UpdateInterimPaymentMeetingRequest $request, InterimPaymentMeeting $interimPaymentMeeting)
    {
        $interimPaymentMeeting->update($request->all());

        if (count($interimPaymentMeeting->file_upload) > 0) {
            foreach ($interimPaymentMeeting->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $interimPaymentMeeting->file_upload->pluck('file_name')->toArray();
        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $interimPaymentMeeting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
            }
        }

        return (new InterimPaymentMeetingResource($interimPaymentMeeting))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(InterimPaymentMeeting $interimPaymentMeeting)
    {
        abort_if(Gate::denies('interim_payment_meeting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interimPaymentMeeting->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
