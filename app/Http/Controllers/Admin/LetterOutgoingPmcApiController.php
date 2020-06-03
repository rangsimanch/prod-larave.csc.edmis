<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterOutgoingPmcRequest;
use App\Http\Requests\UpdateLetterOutgoingPmcRequest;
use App\Http\Resources\Admin\LetterOutgoingPmcResource;
use App\LetterOutgoingPmc;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterOutgoingPmcApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_outgoing_pmc_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterOutgoingPmcResource(LetterOutgoingPmc::with(['letter', 'team'])->get());
    }

    public function store(StoreLetterOutgoingPmcRequest $request)
    {
        $letterOutgoingPmc = LetterOutgoingPmc::create($request->all());

        return (new LetterOutgoingPmcResource($letterOutgoingPmc))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LetterOutgoingPmc $letterOutgoingPmc)
    {
        abort_if(Gate::denies('letter_outgoing_pmc_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterOutgoingPmcResource($letterOutgoingPmc->load(['letter', 'team']));
    }

    public function update(UpdateLetterOutgoingPmcRequest $request, LetterOutgoingPmc $letterOutgoingPmc)
    {
        $letterOutgoingPmc->update($request->all());

        return (new LetterOutgoingPmcResource($letterOutgoingPmc))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LetterOutgoingPmc $letterOutgoingPmc)
    {
        abort_if(Gate::denies('letter_outgoing_pmc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterOutgoingPmc->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
