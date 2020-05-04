<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyManualRequest;
use App\Http\Requests\StoreManualRequest;
use App\Http\Requests\UpdateManualRequest;
use App\Manual;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ManualsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('manual_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Manual::query()->select(sprintf('%s.*', (new Manual)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'manual_show';
                $editGate      = 'manual_edit';
                $deleteGate    = 'manual_delete';
                $crudRoutePart = 'manuals';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('document_name', function ($row) {
                return $row->document_name ? $row->document_name : "";
            });
            $table->editColumn('file_upload', function ($row) {
                if (!$row->file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'file_upload']);

            return $table->make(true);
        }

        return view('admin.manuals.index');
    }

    public function create()
    {
        abort_if(Gate::denies('manual_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.manuals.create');
    }

    public function store(StoreManualRequest $request)
    {
        $manual = Manual::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $manual->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $manual->id]);
        }

        return redirect()->route('admin.manuals.index');

    }

    public function edit(Manual $manual)
    {
        abort_if(Gate::denies('manual_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.manuals.edit', compact('manual'));
    }

    public function update(UpdateManualRequest $request, Manual $manual)
    {
        $manual->update($request->all());

        if (count($manual->file_upload) > 0) {
            foreach ($manual->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }

            }

        }

        $media = $manual->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $manual->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }

        }

        return redirect()->route('admin.manuals.index');

    }

    public function show(Manual $manual)
    {
        abort_if(Gate::denies('manual_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.manuals.show', compact('manual'));
    }

    public function destroy(Manual $manual)
    {
        abort_if(Gate::denies('manual_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manual->delete();

        return back();

    }

    public function massDestroy(MassDestroyManualRequest $request)
    {
        Manual::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('manual_create') && Gate::denies('manual_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Manual();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);

    }

}
