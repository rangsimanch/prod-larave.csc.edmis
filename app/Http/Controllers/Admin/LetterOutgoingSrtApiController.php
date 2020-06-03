<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterOutgoingSrtRequest;
use App\Http\Requests\UpdateLetterOutgoingSrtRequest;
use App\Http\Resources\Admin\LetterOutgoingSrtResource;
use App\LetterOutgoingSrt;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterOutgoingSrtApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_outgoing_srt_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterOutgoingSrtResource(LetterOutgoingSrt::with(['letter', 'team'])->get());
    }

    public function store(StoreLetterOutgoingSrtRequest $request)
    {
        $letterOutgoingSrt = LetterOutgoingSrt::create($request->all());

        return (new LetterOutgoingSrtResource($letterOutgoingSrt))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LetterOutgoingSrt $letterOutgoingSrt)
    {
        abort_if(Gate::denies('letter_outgoing_srt_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterOutgoingSrtResource($letterOutgoingSrt->load(['letter', 'team']));
    }

    public function update(UpdateLetterOutgoingSrtRequest $request, LetterOutgoingSrt $letterOutgoingSrt)
    {
        $letterOutgoingSrt->update($request->all());

        return (new LetterOutgoingSrtResource($letterOutgoingSrt))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LetterOutgoingSrt $letterOutgoingSrt)
    {
        abort_if(Gate::denies('letter_outgoing_srt_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterOutgoingSrt->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
