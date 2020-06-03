<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterOutgoingCecRequest;
use App\Http\Requests\UpdateLetterOutgoingCecRequest;
use App\Http\Resources\Admin\LetterOutgoingCecResource;
use App\LetterOutgoingCec;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterOutgoingCecApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_outgoing_cec_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterOutgoingCecResource(LetterOutgoingCec::with(['letter', 'team'])->get());
    }

    public function store(StoreLetterOutgoingCecRequest $request)
    {
        $letterOutgoingCec = LetterOutgoingCec::create($request->all());

        return (new LetterOutgoingCecResource($letterOutgoingCec))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LetterOutgoingCec $letterOutgoingCec)
    {
        abort_if(Gate::denies('letter_outgoing_cec_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterOutgoingCecResource($letterOutgoingCec->load(['letter', 'team']));
    }

    public function update(UpdateLetterOutgoingCecRequest $request, LetterOutgoingCec $letterOutgoingCec)
    {
        $letterOutgoingCec->update($request->all());

        return (new LetterOutgoingCecResource($letterOutgoingCec))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LetterOutgoingCec $letterOutgoingCec)
    {
        abort_if(Gate::denies('letter_outgoing_cec_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterOutgoingCec->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
