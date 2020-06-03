<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterIncomingPmcRequest;
use App\Http\Requests\UpdateLetterIncomingPmcRequest;
use App\Http\Resources\Admin\LetterIncomingPmcResource;
use App\LetterIncomingPmc;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterIncomingPmcApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_incoming_pmc_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterIncomingPmcResource(LetterIncomingPmc::with(['letter', 'team'])->get());
    }

    public function store(StoreLetterIncomingPmcRequest $request)
    {
        $letterIncomingPmc = LetterIncomingPmc::create($request->all());

        return (new LetterIncomingPmcResource($letterIncomingPmc))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LetterIncomingPmc $letterIncomingPmc)
    {
        abort_if(Gate::denies('letter_incoming_pmc_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LetterIncomingPmcResource($letterIncomingPmc->load(['letter', 'team']));
    }

    public function update(UpdateLetterIncomingPmcRequest $request, LetterIncomingPmc $letterIncomingPmc)
    {
        $letterIncomingPmc->update($request->all());

        return (new LetterIncomingPmcResource($letterIncomingPmc))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LetterIncomingPmc $letterIncomingPmc)
    {
        abort_if(Gate::denies('letter_incoming_pmc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterIncomingPmc->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
