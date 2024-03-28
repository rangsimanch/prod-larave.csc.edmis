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
        $addLetters = AddLetter::with([ 'sender', 'receiver', 'construction_contract', 'team'])->orderBy('id', 'desc')->limit(100)->get();
        // $addLetters = AddLetter::with([ 'sender', 'receiver', 'construction_contract', 'team'])->orderBy('id', 'desc')->get();

        return AddLetterResource::collection($addLetters)->response()->setData(
            $addLetters->map(function ($addLetter) {
                $link = [];
                foreach ($addLetter->letter_upload as $media) {
                    $link[] = $media->getUrl();
                }
                 return [
                    'id' => $addLetter->id,
                    'status' => $addLetter->status,
                    'repiled_ref' => $addLetter->repiled_ref,
                    'letter_type' => $addLetter->letter_type,
                    'objective' => $addLetter->objective,
                    'speed_class' => $addLetter->speed_class,
                    'title' => $addLetter->title,
                    'letter_no' => $addLetter->letter_no,
                    'sender' => $addLetter->sender->code,
                    'receiver' => $addLetter->receiver->code,
                    'sent_date' => $addLetter->sent_date,
                    'received_date' => $addLetter->received_date,
                    'construction_contract' => $addLetter->construction_contract->code,
                    'start_date' => $addLetter->start_date,
                    'complete_date' => $addLetter->complete_date,
                    'processing_time' => $addLetter->processing_time,
                    'responsible' => $addLetter->responsible ? $addLetter->responsible->name : '',
                    'link' => implode(', ', $link)
                    // add any other fields you want to include in the response
                ];
            })
        );
        // return new AddLetterResource(AddLetter::with([ 'sender', 'receiver', 'construction_contract', 'team'])->get());
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
