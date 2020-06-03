<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CheckList;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCheckListRequest;
use App\Http\Requests\UpdateCheckListRequest;
use App\Http\Resources\Admin\CheckListResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckListApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('check_list_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckListResource(CheckList::with(['work_type', 'name_of_inspector', 'construction_contract', 'team'])->get());
    }

    public function store(StoreCheckListRequest $request)
    {
        $checkList = CheckList::create($request->all());

        if ($request->input('file_upload', false)) {
            $checkList->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new CheckListResource($checkList))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CheckList $checkList)
    {
        abort_if(Gate::denies('check_list_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckListResource($checkList->load(['work_type', 'name_of_inspector', 'construction_contract', 'team']));
    }

    public function update(UpdateCheckListRequest $request, CheckList $checkList)
    {
        $checkList->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$checkList->file_upload || $request->input('file_upload') !== $checkList->file_upload->file_name) {
                $checkList->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($checkList->file_upload) {
            $checkList->file_upload->delete();
        }

        return (new CheckListResource($checkList))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CheckList $checkList)
    {
        abort_if(Gate::denies('check_list_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkList->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
