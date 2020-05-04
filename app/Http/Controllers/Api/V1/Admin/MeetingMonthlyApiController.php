<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMeetingMonthlyRequest;
use App\Http\Requests\UpdateMeetingMonthlyRequest;
use App\Http\Resources\Admin\MeetingMonthlyResource;
use App\MeetingMonthly;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingMonthlyApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('meeting_monthly_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MeetingMonthlyResource(MeetingMonthly::with(['construction_contract', 'team'])->get());

    }

    public function store(StoreMeetingMonthlyRequest $request)
    {
        $meetingMonthly = MeetingMonthly::create($request->all());

        if ($request->input('file_upload', false)) {
            $meetingMonthly->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new MeetingMonthlyResource($meetingMonthly))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(MeetingMonthly $meetingMonthly)
    {
        abort_if(Gate::denies('meeting_monthly_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MeetingMonthlyResource($meetingMonthly->load(['construction_contract', 'team']));

    }

    public function update(UpdateMeetingMonthlyRequest $request, MeetingMonthly $meetingMonthly)
    {
        $meetingMonthly->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$meetingMonthly->file_upload || $request->input('file_upload') !== $meetingMonthly->file_upload->file_name) {
                $meetingMonthly->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }

        } elseif ($meetingMonthly->file_upload) {
            $meetingMonthly->file_upload->delete();
        }

        return (new MeetingMonthlyResource($meetingMonthly))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);

    }

    public function destroy(MeetingMonthly $meetingMonthly)
    {
        abort_if(Gate::denies('meeting_monthly_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingMonthly->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

}
