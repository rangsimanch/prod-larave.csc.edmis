<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMeetingWeeklyRequest;
use App\Http\Requests\UpdateMeetingWeeklyRequest;
use App\Http\Resources\Admin\MeetingWeeklyResource;
use App\MeetingWeekly;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingWeeklyApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('meeting_weekly_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MeetingWeeklyResource(MeetingWeekly::with(['construction_contract', 'team'])->get());

    }

    public function store(StoreMeetingWeeklyRequest $request)
    {
        $meetingWeekly = MeetingWeekly::create($request->all());

        if ($request->input('file_upload', false)) {
            $meetingWeekly->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new MeetingWeeklyResource($meetingWeekly))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(MeetingWeekly $meetingWeekly)
    {
        abort_if(Gate::denies('meeting_weekly_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MeetingWeeklyResource($meetingWeekly->load(['construction_contract', 'team']));

    }

    public function update(UpdateMeetingWeeklyRequest $request, MeetingWeekly $meetingWeekly)
    {
        $meetingWeekly->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$meetingWeekly->file_upload || $request->input('file_upload') !== $meetingWeekly->file_upload->file_name) {
                $meetingWeekly->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }

        } elseif ($meetingWeekly->file_upload) {
            $meetingWeekly->file_upload->delete();
        }

        return (new MeetingWeeklyResource($meetingWeekly))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);

    }

    public function destroy(MeetingWeekly $meetingWeekly)
    {
        abort_if(Gate::denies('meeting_weekly_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingWeekly->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

}
