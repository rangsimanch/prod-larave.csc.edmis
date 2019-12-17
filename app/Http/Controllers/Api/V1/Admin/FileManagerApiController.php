<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\FileManager;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreFileManagerRequest;
use App\Http\Requests\UpdateFileManagerRequest;
use App\Http\Resources\Admin\FileManagerResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FileManagerApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('file_manager_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FileManagerResource(FileManager::with(['construction_contracts', 'team'])->get());
    }

    public function store(StoreFileManagerRequest $request)
    {
        $fileManager = FileManager::create($request->all());
        $fileManager->construction_contracts()->sync($request->input('construction_contracts', []));

        if ($request->input('file_upload', false)) {
            $fileManager->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new FileManagerResource($fileManager))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(FileManager $fileManager)
    {
        abort_if(Gate::denies('file_manager_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FileManagerResource($fileManager->load(['construction_contracts', 'team']));
    }

    public function update(UpdateFileManagerRequest $request, FileManager $fileManager)
    {
        $fileManager->update($request->all());
        $fileManager->construction_contracts()->sync($request->input('construction_contracts', []));

        if ($request->input('file_upload', false)) {
            if (!$fileManager->file_upload || $request->input('file_upload') !== $fileManager->file_upload->file_name) {
                $fileManager->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($fileManager->file_upload) {
            $fileManager->file_upload->delete();
        }

        return (new FileManagerResource($fileManager))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(FileManager $fileManager)
    {
        abort_if(Gate::denies('file_manager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fileManager->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
