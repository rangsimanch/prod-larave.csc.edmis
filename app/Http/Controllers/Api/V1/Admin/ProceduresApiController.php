<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProcedureRequest;
use App\Http\Requests\UpdateProcedureRequest;
use App\Http\Resources\Admin\ProcedureResource;
use App\Procedure;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProceduresApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('procedure_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProcedureResource(Procedure::all());
    }

    public function store(StoreProcedureRequest $request)
    {
        $procedure = Procedure::create($request->all());

        if ($request->input('file_upload', false)) {
            $procedure->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new ProcedureResource($procedure))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Procedure $procedure)
    {
        abort_if(Gate::denies('procedure_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProcedureResource($procedure);
    }

    public function update(UpdateProcedureRequest $request, Procedure $procedure)
    {
        $procedure->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$procedure->file_upload || $request->input('file_upload') !== $procedure->file_upload->file_name) {
                $procedure->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($procedure->file_upload) {
            $procedure->file_upload->delete();
        }

        return (new ProcedureResource($procedure))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Procedure $procedure)
    {
        abort_if(Gate::denies('procedure_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $procedure->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
