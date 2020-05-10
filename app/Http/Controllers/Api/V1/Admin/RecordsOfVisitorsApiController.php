<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRecordsOfVisitorRequest;
use App\Http\Requests\UpdateRecordsOfVisitorRequest;
use App\Http\Resources\Admin\RecordsOfVisitorResource;
use App\RecordsOfVisitor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordsOfVisitorsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('records_of_visitor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RecordsOfVisitorResource(RecordsOfVisitor::all());
    }

    public function store(StoreRecordsOfVisitorRequest $request)
    {
        $recordsOfVisitor = RecordsOfVisitor::create($request->all());

        if ($request->input('file_upload', false)) {
            $recordsOfVisitor->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new RecordsOfVisitorResource($recordsOfVisitor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RecordsOfVisitor $recordsOfVisitor)
    {
        abort_if(Gate::denies('records_of_visitor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RecordsOfVisitorResource($recordsOfVisitor);
    }

    public function update(UpdateRecordsOfVisitorRequest $request, RecordsOfVisitor $recordsOfVisitor)
    {
        $recordsOfVisitor->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$recordsOfVisitor->file_upload || $request->input('file_upload') !== $recordsOfVisitor->file_upload->file_name) {
                $recordsOfVisitor->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($recordsOfVisitor->file_upload) {
            $recordsOfVisitor->file_upload->delete();
        }

        return (new RecordsOfVisitorResource($recordsOfVisitor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RecordsOfVisitor $recordsOfVisitor)
    {
        abort_if(Gate::denies('records_of_visitor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recordsOfVisitor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
