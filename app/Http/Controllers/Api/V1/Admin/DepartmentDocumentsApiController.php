<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DepartmentDocument;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDepartmentDocumentRequest;
use App\Http\Requests\UpdateDepartmentDocumentRequest;
use App\Http\Resources\Admin\DepartmentDocumentResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartmentDocumentsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('department_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DepartmentDocumentResource(DepartmentDocument::with(['tags', 'construction_contract', 'team'])->get());

    }

    public function store(StoreDepartmentDocumentRequest $request)
    {
        $departmentDocument = DepartmentDocument::create($request->all());
        $departmentDocument->tags()->sync($request->input('tags', []));

        if ($request->input('download', false)) {
            $departmentDocument->addMedia(storage_path('tmp/uploads/' . $request->input('download')))->toMediaCollection('download');
        }

        if ($request->input('example_file', false)) {
            $departmentDocument->addMedia(storage_path('tmp/uploads/' . $request->input('example_file')))->toMediaCollection('example_file');
        }

        return (new DepartmentDocumentResource($departmentDocument))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(DepartmentDocument $departmentDocument)
    {
        abort_if(Gate::denies('department_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DepartmentDocumentResource($departmentDocument->load(['tags', 'construction_contract', 'team']));

    }

    public function update(UpdateDepartmentDocumentRequest $request, DepartmentDocument $departmentDocument)
    {
        $departmentDocument->update($request->all());
        $departmentDocument->tags()->sync($request->input('tags', []));

        if ($request->input('download', false)) {
            if (!$departmentDocument->download || $request->input('download') !== $departmentDocument->download->file_name) {
                $departmentDocument->addMedia(storage_path('tmp/uploads/' . $request->input('download')))->toMediaCollection('download');
            }

        } elseif ($departmentDocument->download) {
            $departmentDocument->download->delete();
        }

        if ($request->input('example_file', false)) {
            if (!$departmentDocument->example_file || $request->input('example_file') !== $departmentDocument->example_file->file_name) {
                $departmentDocument->addMedia(storage_path('tmp/uploads/' . $request->input('example_file')))->toMediaCollection('example_file');
            }

        } elseif ($departmentDocument->example_file) {
            $departmentDocument->example_file->delete();
        }

        return (new DepartmentDocumentResource($departmentDocument))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);

    }

    public function destroy(DepartmentDocument $departmentDocument)
    {
        abort_if(Gate::denies('department_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departmentDocument->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

}
