<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterIncomingCscRequest;
use App\Http\Requests\UpdateLetterIncomingCscRequest;
use App\Http\Resources\Admin\LetterIncomingCscResource;
use App\LetterIncomingCsc;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterIncomingCscApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_incoming_csc_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterIncomingCscResource(LetterIncomingCsc::with(['letter', 'team'])->get());
    }

    public function store(StoreLetterIncomingCscRequest $request)
    {
        $letterIncomingCsc = LetterIncomingCsc::create($request->all());

        return (new LetterIncomingCscResource($letterIncomingCsc))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LetterIncomingCsc $letterIncomingCsc)
    {
        abort_if(Gate::denies('letter_incoming_csc_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterIncomingCscResource($letterIncomingCsc->load(['letter', 'team']));
    }

    public function update(UpdateLetterIncomingCscRequest $request, LetterIncomingCsc $letterIncomingCsc)
    {
        $letterIncomingCsc->update($request->all());

        return (new LetterIncomingCscResource($letterIncomingCsc))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LetterIncomingCsc $letterIncomingCsc)
    {
        abort_if(Gate::denies('letter_incoming_csc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterIncomingCsc->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
