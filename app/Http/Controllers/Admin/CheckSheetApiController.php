<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CheckSheet;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCheckSheetRequest;
use App\Http\Requests\UpdateCheckSheetRequest;
use App\Http\Resources\Admin\CheckSheetResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSheetApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('check_sheet_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckSheetResource(CheckSheet::with(['work_type', 'name_of_inspector', 'construction_contract', 'team'])->get());
    }

    public function store(StoreCheckSheetRequest $request)
    {
        $checkSheet = CheckSheet::create($request->all());

        if ($request->input('file_upload', false)) {
            $checkSheet->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new CheckSheetResource($checkSheet))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CheckSheet $checkSheet)
    {
        abort_if(Gate::denies('check_sheet_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckSheetResource($checkSheet->load(['work_type', 'name_of_inspector', 'construction_contract', 'team']));
    }

    public function update(UpdateCheckSheetRequest $request, CheckSheet $checkSheet)
    {
        $checkSheet->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$checkSheet->file_upload || $request->input('file_upload') !== $checkSheet->file_upload->file_name) {
                $checkSheet->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($checkSheet->file_upload) {
            $checkSheet->file_upload->delete();
        }

        return (new CheckSheetResource($checkSheet))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CheckSheet $checkSheet)
    {
        abort_if(Gate::denies('check_sheet_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkSheet->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
