<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\DailyRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDailyRequestRequest;
use App\Http\Requests\StoreDailyRequestRequest;
use App\Http\Requests\UpdateDailyRequestRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DailyRequestController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('daily_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(strcmp(Auth::id(),'1') == 0){
            $dailyRequests = DailyRequest::all();
            return view('admin.dailyRequests.index', compact('dailyRequests'));
        }
        else{
            $dailyRequests = DailyRequest::all()->where('acknowledge',1);
            return view('admin.dailyRequests.index', compact('dailyRequests'));
        }
    }

    public function create()
    {
        abort_if(Gate::denies('daily_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dailyRequests.create');
    }

    public function store(StoreDailyRequestRequest $request)
    {
        $dailyRequest = DailyRequest::create($request->all());

        foreach ($request->input('documents', []) as $file) {
            $dailyRequest->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('documents');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $dailyRequest->id]);
        }

        return redirect()->route('admin.daily-requests.index');

    }

    public function edit(DailyRequest $dailyRequest)
    {
        abort_if(Gate::denies('daily_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyRequest->load('receive_by');

        return view('admin.dailyRequests.edit', compact('dailyRequest'));
    }

    public function update(UpdateDailyRequestRequest $request, DailyRequest $dailyRequest)
    {
        $dailyRequest->update($request->all());

        if (count($dailyRequest->documents) > 0) {
            foreach ($dailyRequest->documents as $media) {
                if (!in_array($media->file_name, $request->input('documents', []))) {
                    $media->delete();
                }

            }

        }

        $media = $dailyRequest->documents->pluck('file_name')->toArray();

        foreach ($request->input('documents', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $dailyRequest->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('documents');
            }

        }

        return redirect()->route('admin.daily-requests.index');

    }

    public function show(DailyRequest $dailyRequest)
    {
        abort_if(Gate::denies('daily_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyRequest->load('receive_by');

        return view('admin.dailyRequests.show', compact('dailyRequest'));
    }

    public function destroy(DailyRequest $dailyRequest)
    {
        abort_if(Gate::denies('daily_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyRequest->delete();

        return back();

    }

    public function massDestroy(MassDestroyDailyRequestRequest $request)
    {
        DailyRequest::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('daily_request_create') && Gate::denies('daily_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DailyRequest();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media', 'public');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);

    }

}
