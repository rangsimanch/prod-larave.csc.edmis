<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyVocabularyHubRequest;
use App\Http\Requests\StoreVocabularyHubRequest;
use App\Http\Requests\UpdateVocabularyHubRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VocabularyHubController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('vocabulary_hub_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vocabularyHubs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('vocabulary_hub_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vocabularyHubs.create');
    }

    public function store(StoreVocabularyHubRequest $request)
    {
        $vocabularyHub = VocabularyHub::create($request->all());

        return redirect()->route('admin.vocabulary-hubs.index');
    }

    public function edit(VocabularyHub $vocabularyHub)
    {
        abort_if(Gate::denies('vocabulary_hub_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vocabularyHubs.edit', compact('vocabularyHub'));
    }

    public function update(UpdateVocabularyHubRequest $request, VocabularyHub $vocabularyHub)
    {
        $vocabularyHub->update($request->all());

        return redirect()->route('admin.vocabulary-hubs.index');
    }

    public function show(VocabularyHub $vocabularyHub)
    {
        abort_if(Gate::denies('vocabulary_hub_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vocabularyHubs.show', compact('vocabularyHub'));
    }

    public function destroy(VocabularyHub $vocabularyHub)
    {
        abort_if(Gate::denies('vocabulary_hub_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vocabularyHub->delete();

        return back();
    }

    public function massDestroy(MassDestroyVocabularyHubRequest $request)
    {
        $vocabularyHubs = VocabularyHub::find(request('ids'));

        foreach ($vocabularyHubs as $vocabularyHub) {
            $vocabularyHub->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
