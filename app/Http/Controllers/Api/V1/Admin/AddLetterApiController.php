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

        return new AddLetterResource(AddLetter::with(['topic_categories', 'sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'responsible', 'team'])->get());
    }

    public function store(StoreAddLetterRequest $request)
    {
        $addLetter = AddLetter::create($request->all());
        $addLetter->topic_categories()->sync($request->input('topic_categories', []));
        $addLetter->cc_tos()->sync($request->input('cc_tos', []));
        foreach ($request->input('letter_upload', []) as $file) {
            $addLetter->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('letter_upload');
        }

        return (new AddLetterResource($addLetter))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AddLetter $addLetter)
    {
        abort_if(Gate::denies('add_letter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddLetterResource($addLetter->load(['topic_categories', 'sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'responsible', 'team']));
    }

    public function update(UpdateAddLetterRequest $request, AddLetter $addLetter)
    {
        $addLetter->update($request->all());
        $addLetter->topic_categories()->sync($request->input('topic_categories', []));
        $addLetter->cc_tos()->sync($request->input('cc_tos', []));
        if (count($addLetter->letter_upload) > 0) {
            foreach ($addLetter->letter_upload as $media) {
                if (!in_array($media->file_name, $request->input('letter_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $addLetter->letter_upload->pluck('file_name')->toArray();
        foreach ($request->input('letter_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $addLetter->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('letter_upload');
            }
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
