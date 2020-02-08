<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmittalsRfaRequest;
use App\Http\Requests\UpdateSubmittalsRfaRequest;
use App\Http\Resources\Admin\SubmittalsRfaResource;
use App\SubmittalsRfa;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubmittalsRfaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('submittals_rfa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubmittalsRfaResource(SubmittalsRfa::with(['review_status', 'on_rfa'])->get());
    }

    public function store(StoreSubmittalsRfaRequest $request)
    {
        $submittalsRfa = SubmittalsRfa::create($request->all());

        return (new SubmittalsRfaResource($submittalsRfa))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SubmittalsRfa $submittalsRfa)
    {
        abort_if(Gate::denies('submittals_rfa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubmittalsRfaResource($submittalsRfa->load(['review_status', 'on_rfa']));
    }

    public function update(UpdateSubmittalsRfaRequest $request, SubmittalsRfa $submittalsRfa)
    {
        $submittalsRfa->update($request->all());

        return (new SubmittalsRfaResource($submittalsRfa))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SubmittalsRfa $submittalsRfa)
    {
        abort_if(Gate::denies('submittals_rfa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $submittalsRfa->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
