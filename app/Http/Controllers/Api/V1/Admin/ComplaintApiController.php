<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Complaint;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Http\Resources\Admin\ComplaintResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComplaintApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('complaint_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ComplaintResource(Complaint::with(['construction_contract', 'complaint_recipient', 'operator', 'team'])->get());
    }

    public function store(StoreComplaintRequest $request)
    {
        $complaint = Complaint::create($request->all());

        if ($request->input('file_attachment_create', false)) {
            $complaint->addMedia(storage_path('tmp/uploads/' . $request->input('file_attachment_create')))->toMediaCollection('file_attachment_create');
        }

        if ($request->input('progress_file', false)) {
            $complaint->addMedia(storage_path('tmp/uploads/' . $request->input('progress_file')))->toMediaCollection('progress_file');
        }

        return (new ComplaintResource($complaint))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Complaint $complaint)
    {
        abort_if(Gate::denies('complaint_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ComplaintResource($complaint->load(['construction_contract', 'complaint_recipient', 'operator', 'team']));
    }

    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        $complaint->update($request->all());

        if ($request->input('file_attachment_create', false)) {
            if (!$complaint->file_attachment_create || $request->input('file_attachment_create') !== $complaint->file_attachment_create->file_name) {
                if ($complaint->file_attachment_create) {
                    $complaint->file_attachment_create->delete();
                }

                $complaint->addMedia(storage_path('tmp/uploads/' . $request->input('file_attachment_create')))->toMediaCollection('file_attachment_create');
            }
        } elseif ($complaint->file_attachment_create) {
            $complaint->file_attachment_create->delete();
        }

        if ($request->input('progress_file', false)) {
            if (!$complaint->progress_file || $request->input('progress_file') !== $complaint->progress_file->file_name) {
                if ($complaint->progress_file) {
                    $complaint->progress_file->delete();
                }

                $complaint->addMedia(storage_path('tmp/uploads/' . $request->input('progress_file')))->toMediaCollection('progress_file');
            }
        } elseif ($complaint->progress_file) {
            $complaint->progress_file->delete();
        }

        return (new ComplaintResource($complaint))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Complaint $complaint)
    {
        abort_if(Gate::denies('complaint_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $complaint->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
