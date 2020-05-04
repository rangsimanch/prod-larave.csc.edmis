<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMeetingOtherRequest;
use App\Http\Requests\UpdateMeetingOtherRequest;
use App\Http\Resources\Admin\MeetingOtherResource;
use App\MeetingOther;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingOtherApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('meeting_other_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MeetingOtherResource(MeetingOther::with(['construction_contract', 'team'])->get());

    }

    public function store(StoreMeetingOtherRequest $request)
    {
        $meetingOther = MeetingOther::create($request->all());

        if ($request->input('file_upload', false)) {
            $meetingOther->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new MeetingOtherResource($meetingOther))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(MeetingOther $meetingOther)
    {
        abort_if(Gate::denies('meeting_other_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MeetingOtherResource($meetingOther->load(['construction_contract', 'team']));

    }

    public function update(UpdateMeetingOtherRequest $request, MeetingOther $meetingOther)
    {
        $meetingOther->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$meetingOther->file_upload || $request->input('file_upload') !== $meetingOther->file_upload->file_name) {
                $meetingOther->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }

        } elseif ($meetingOther->file_upload) {
            $meetingOther->file_upload->delete();
        }

        return (new MeetingOtherResource($meetingOther))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);

    }

    public function destroy(MeetingOther $meetingOther)
    {
        abort_if(Gate::denies('meeting_other_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingOther->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

}
