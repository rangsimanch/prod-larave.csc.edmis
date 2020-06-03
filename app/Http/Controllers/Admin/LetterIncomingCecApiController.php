<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterIncomingCecRequest;
use App\Http\Requests\UpdateLetterIncomingCecRequest;
use App\Http\Resources\Admin\LetterIncomingCecResource;
use App\LetterIncomingCec;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterIncomingCecApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_incoming_cec_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterIncomingCecResource(LetterIncomingCec::with(['letter', 'team'])->get());
    }

    public function store(StoreLetterIncomingCecRequest $request)
    {
        $letterIncomingCec = LetterIncomingCec::create($request->all());

        return (new LetterIncomingCecResource($letterIncomingCec))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LetterIncomingCec $letterIncomingCec)
    {
        abort_if(Gate::denies('letter_incoming_cec_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterIncomingCecResource($letterIncomingCec->load(['letter', 'team']));
    }

    public function update(UpdateLetterIncomingCecRequest $request, LetterIncomingCec $letterIncomingCec)
    {
        $letterIncomingCec->update($request->all());

        return (new LetterIncomingCecResource($letterIncomingCec))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LetterIncomingCec $letterIncomingCec)
    {
        abort_if(Gate::denies('letter_incoming_cec_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterIncomingCec->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
