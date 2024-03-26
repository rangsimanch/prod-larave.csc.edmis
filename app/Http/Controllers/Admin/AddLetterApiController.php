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
        $addLetters = AddLetter::with([ 'sender', 'receiver', 'construction_contract', 'team'])->where('deleted_at', '=', null)->orderBy('id', 'desc')->get();

        return AddLetterResource::collection($addLetters)->response()->setData(
            $addLetters->map(function ($addLetter) {
                $link = [];
                foreach ($addLetter->letter_upload as $media) {
                    $link[] = $media->getUrl();
                }
                return [
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
                    'responsible' => $addLetter->responsible->name,
                    'link' => implode(', ', $links)
                    // add any other fields you want to include in the response
                ];
            })
        );
        // return new AddLetterResource(AddLetter::with([ 'sender', 'receiver', 'construction_contract', 'team'])->get());
    }

    public function store(StoreAddLetterRequest $request)
    {
        $addLetter = AddLetter::create($request->all());

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

        return new AddLetterResource($addLetter->load(['letter_type', 'sender', 'receiver', 'construction_contract', 'team']));
    }

    public function update(UpdateAddLetterRequest $request, AddLetter $addLetter)
    {
        $addLetter->update($request->all());

        if ($request->input('letter_upload', false)) {
            if (!$addLetter->letter_upload || $request->input('letter_upload') !== $addLetter->letter_upload->file_name) {
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
