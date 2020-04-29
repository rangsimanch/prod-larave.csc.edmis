<?php

namespace App\Http\Controllers\Admin;

use App\DocumentTag;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDocumentTagRequest;
use App\Http\Requests\StoreDocumentTagRequest;
use App\Http\Requests\UpdateDocumentTagRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentTagController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('document_tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentTags = DocumentTag::all();

        return view('admin.documentTags.index', compact('documentTags'));
    }

    public function create()
    {
        abort_if(Gate::denies('document_tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.documentTags.create');
    }

    public function store(StoreDocumentTagRequest $request)
    {
        $documentTag = DocumentTag::create($request->all());

        return redirect()->route('admin.document-tags.index');

    }

    public function edit(DocumentTag $documentTag)
    {
        abort_if(Gate::denies('document_tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.documentTags.edit', compact('documentTag'));
    }

    public function update(UpdateDocumentTagRequest $request, DocumentTag $documentTag)
    {
        $documentTag->update($request->all());

        return redirect()->route('admin.document-tags.index');

    }

    public function show(DocumentTag $documentTag)
    {
        abort_if(Gate::denies('document_tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.documentTags.show', compact('documentTag'));
    }

    public function destroy(DocumentTag $documentTag)
    {
        abort_if(Gate::denies('document_tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentTag->delete();

        return back();

    }

    public function massDestroy(MassDestroyDocumentTagRequest $request)
    {
        DocumentTag::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }
}
