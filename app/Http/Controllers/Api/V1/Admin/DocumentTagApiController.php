<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DocumentTag;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentTagRequest;
use App\Http\Requests\UpdateDocumentTagRequest;
use App\Http\Resources\Admin\DocumentTagResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentTagApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('document_tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocumentTagResource(DocumentTag::all());

    }

    public function store(StoreDocumentTagRequest $request)
    {
        $documentTag = DocumentTag::create($request->all());

        return (new DocumentTagResource($documentTag))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(DocumentTag $documentTag)
    {
        abort_if(Gate::denies('document_tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocumentTagResource($documentTag);

    }

    public function update(UpdateDocumentTagRequest $request, DocumentTag $documentTag)
    {
        $documentTag->update($request->all());

        return (new DocumentTagResource($documentTag))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);

    }

    public function destroy(DocumentTag $documentTag)
    {
        abort_if(Gate::denies('document_tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentTag->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }
}
