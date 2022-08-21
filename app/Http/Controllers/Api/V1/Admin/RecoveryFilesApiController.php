<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRecoveryFileRequest;
use App\Http\Requests\UpdateRecoveryFileRequest;
use App\Http\Resources\Admin\RecoveryFileResource;
use App\RecoveryFile;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecoveryFilesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('recovery_file_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RecoveryFileResource(RecoveryFile::with(['team'])->get());
    }

    public function store(StoreRecoveryFileRequest $request)
    {
        $recoveryFile = RecoveryFile::create($request->all());

        foreach ($request->input('recovery_file', []) as $file) {
            $recoveryFile->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('recovery_file');
        }

        return (new RecoveryFileResource($recoveryFile))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RecoveryFile $recoveryFile)
    {
        abort_if(Gate::denies('recovery_file_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RecoveryFileResource($recoveryFile->load(['team']));
    }

    public function update(UpdateRecoveryFileRequest $request, RecoveryFile $recoveryFile)
    {
        $recoveryFile->update($request->all());

        if (count($recoveryFile->recovery_file) > 0) {
            foreach ($recoveryFile->recovery_file as $media) {
                if (!in_array($media->file_name, $request->input('recovery_file', []))) {
                    $media->delete();
                }
            }
        }
        $media = $recoveryFile->recovery_file->pluck('file_name')->toArray();
        foreach ($request->input('recovery_file', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $recoveryFile->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('recovery_file');
            }
        }

        return (new RecoveryFileResource($recoveryFile))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RecoveryFile $recoveryFile)
    {
        abort_if(Gate::denies('recovery_file_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recoveryFile->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
