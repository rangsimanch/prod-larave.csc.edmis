<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DocumentsAndAssignment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocumentsAndAssignmentRequest;
use App\Http\Requests\UpdateDocumentsAndAssignmentRequest;
use App\Http\Resources\Admin\DocumentsAndAssignmentResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentsAndAssignmentsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('documents_and_assignment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocumentsAndAssignmentResource(DocumentsAndAssignment::all());
    }

    public function store(StoreDocumentsAndAssignmentRequest $request)
    {
        $documentsAndAssignment = DocumentsAndAssignment::create($request->all());

        if ($request->input('file_upload', false)) {
            $documentsAndAssignment->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new DocumentsAndAssignmentResource($documentsAndAssignment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocumentsAndAssignment $documentsAndAssignment)
    {
        abort_if(Gate::denies('documents_and_assignment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocumentsAndAssignmentResource($documentsAndAssignment);
    }

    public function update(UpdateDocumentsAndAssignmentRequest $request, DocumentsAndAssignment $documentsAndAssignment)
    {
        $documentsAndAssignment->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$documentsAndAssignment->file_upload || $request->input('file_upload') !== $documentsAndAssignment->file_upload->file_name) {
                $documentsAndAssignment->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($documentsAndAssignment->file_upload) {
            $documentsAndAssignment->file_upload->delete();
        }

        return (new DocumentsAndAssignmentResource($documentsAndAssignment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocumentsAndAssignment $documentsAndAssignment)
    {
        abort_if(Gate::denies('documents_and_assignment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentsAndAssignment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
