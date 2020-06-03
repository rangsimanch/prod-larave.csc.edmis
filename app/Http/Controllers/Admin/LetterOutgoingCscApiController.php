<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterOutgoingCscRequest;
use App\Http\Requests\UpdateLetterOutgoingCscRequest;
use App\Http\Resources\Admin\LetterOutgoingCscResource;
use App\LetterOutgoingCsc;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterOutgoingCscApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_outgoing_csc_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterOutgoingCscResource(LetterOutgoingCsc::with(['letter', 'team'])->get());
    }

    public function store(StoreLetterOutgoingCscRequest $request)
    {
        $letterOutgoingCsc = LetterOutgoingCsc::create($request->all());

        return (new LetterOutgoingCscResource($letterOutgoingCsc))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LetterOutgoingCsc $letterOutgoingCsc)
    {
        abort_if(Gate::denies('letter_outgoing_csc_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterOutgoingCscResource($letterOutgoingCsc->load(['letter', 'team']));
    }

    public function update(UpdateLetterOutgoingCscRequest $request, LetterOutgoingCsc $letterOutgoingCsc)
    {
        $letterOutgoingCsc->update($request->all());

        return (new LetterOutgoingCscResource($letterOutgoingCsc))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LetterOutgoingCsc $letterOutgoingCsc)
    {
        abort_if(Gate::denies('letter_outgoing_csc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterOutgoingCsc->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
