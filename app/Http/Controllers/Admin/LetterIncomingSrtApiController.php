<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterIncomingSrtRequest;
use App\Http\Requests\UpdateLetterIncomingSrtRequest;
use App\Http\Resources\Admin\LetterIncomingSrtResource;
use App\LetterIncomingSrt;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterIncomingSrtApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_incoming_srt_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterIncomingSrtResource(LetterIncomingSrt::with(['letter', 'team'])->get());
    }

    public function store(StoreLetterIncomingSrtRequest $request)
    {
        $letterIncomingSrt = LetterIncomingSrt::create($request->all());

        return (new LetterIncomingSrtResource($letterIncomingSrt))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LetterIncomingSrt $letterIncomingSrt)
    {
        abort_if(Gate::denies('letter_incoming_srt_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterIncomingSrtResource($letterIncomingSrt->load(['letter', 'team']));
    }

    public function update(UpdateLetterIncomingSrtRequest $request, LetterIncomingSrt $letterIncomingSrt)
    {
        $letterIncomingSrt->update($request->all());

        return (new LetterIncomingSrtResource($letterIncomingSrt))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LetterIncomingSrt $letterIncomingSrt)
    {
        abort_if(Gate::denies('letter_incoming_srt_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterIncomingSrt->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
