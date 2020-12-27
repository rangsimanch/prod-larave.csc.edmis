<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\AddLetter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAddLetterRequest;
use App\Http\Requests\UpdateAddLetterRequest;
use App\Http\Resources\Admin\AddLetterResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddLetterApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('add_letter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddLetterResource(AddLetter::with(['sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'team'])->get());
    }

    public function store(StoreAddLetterRequest $request)
    {
        $addLetter = AddLetter::create($request->all());
        $addLetter->cc_tos()->sync($request->input('cc_tos', []));

        if ($request->input('letter_upload', false)) {
            $addLetter->addMedia(storage_path('tmp/uploads/' . $request->input('letter_upload')))->toMediaCollection('letter_upload');
        }

        return (new AddLetterResource($addLetter))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AddLetter $addLetter)
    {
        abort_if(Gate::denies('add_letter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddLetterResource($addLetter->load(['sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'team']));
    }

    public function update(UpdateAddLetterRequest $request, AddLetter $addLetter)
    {
        $addLetter->update($request->all());
        $addLetter->cc_tos()->sync($request->input('cc_tos', []));

        if ($request->input('letter_upload', false)) {
            if (!$addLetter->letter_upload || $request->input('letter_upload') !== $addLetter->letter_upload->file_name) {
                if ($addLetter->letter_upload) {
                    $addLetter->letter_upload->delete();
                }

                $addLetter->addMedia(storage_path('tmp/uploads/' . $request->input('letter_upload')))->toMediaCollection('letter_upload');
            }
        } elseif ($addLetter->letter_upload) {
            $addLetter->letter_upload->delete();
        }

        return (new AddLetterResource($addLetter))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AddLetter $addLetter)
    {
        abort_if(Gate::denies('add_letter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addLetter->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
