<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Announcement;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Http\Resources\Admin\AnnouncementResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('announcement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AnnouncementResource(Announcement::with(['announce_contracts', 'team'])->get());
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $announcement = Announcement::create($request->all());
        $announcement->announce_contracts()->sync($request->input('announce_contracts', []));
        if ($request->input('attachments', false)) {
            $announcement->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachments'))))->toMediaCollection('attachments');
        }

        return (new AnnouncementResource($announcement))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Announcement $announcement)
    {
        abort_if(Gate::denies('announcement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AnnouncementResource($announcement->load(['announce_contracts', 'team']));
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update($request->all());
        $announcement->announce_contracts()->sync($request->input('announce_contracts', []));
        if ($request->input('attachments', false)) {
            if (!$announcement->attachments || $request->input('attachments') !== $announcement->attachments->file_name) {
                if ($announcement->attachments) {
                    $announcement->attachments->delete();
                }
                $announcement->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachments'))))->toMediaCollection('attachments');
            }
        } elseif ($announcement->attachments) {
            $announcement->attachments->delete();
        }

        return (new AnnouncementResource($announcement))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Announcement $announcement)
    {
        abort_if(Gate::denies('announcement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $announcement->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
