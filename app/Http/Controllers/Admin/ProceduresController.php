<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProcedureRequest;
use App\Http\Requests\StoreProcedureRequest;
use App\Http\Requests\UpdateProcedureRequest;
use App\Procedure;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProceduresController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('procedure_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Procedure::query()->select(sprintf('%s.*', (new Procedure)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'procedure_show';
                $editGate      = 'procedure_edit';
                $deleteGate    = 'procedure_delete';
                $crudRoutePart = 'procedures';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
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

        return view('admin.procedures.index');
    }

    public function create()
    {
        abort_if(Gate::denies('procedure_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.procedures.create');
    }

    public function store(StoreProcedureRequest $request)
    {
        $procedure = Procedure::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $procedure->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $procedure->id]);
        }

        return redirect()->route('admin.procedures.index');
    }

    public function edit(Procedure $procedure)
    {
        abort_if(Gate::denies('procedure_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.procedures.edit', compact('procedure'));
    }

    public function update(UpdateProcedureRequest $request, Procedure $procedure)
    {
        $procedure->update($request->all());

        if (count($procedure->file_upload) > 0) {
            foreach ($procedure->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $procedure->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $procedure->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.procedures.index');
    }

    public function show(Procedure $procedure)
    {
        abort_if(Gate::denies('procedure_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.procedures.show', compact('procedure'));
    }

    public function destroy(Procedure $procedure)
    {
        abort_if(Gate::denies('procedure_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $procedure->delete();

        return back();
    }

    public function massDestroy(MassDestroyProcedureRequest $request)
    {
        Procedure::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('procedure_create') && Gate::denies('procedure_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Procedure();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
