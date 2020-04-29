<?php

namespace App\Http\Controllers\Admin;

use App\DepartmentDocument;
use App\DocumentTag;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDepartmentDocumentRequest;
use App\Http\Requests\StoreDepartmentDocumentRequest;
use App\Http\Requests\UpdateDepartmentDocumentRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DepartmentDocumentsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('department_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DepartmentDocument::with(['tags', 'team'])->select(sprintf('%s.*', (new DepartmentDocument)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'department_document_show';
                $editGate      = 'department_document_edit';
                $deleteGate    = 'department_document_delete';
                $crudRoutePart = 'department-documents';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('document_name', function ($row) {
                return $row->document_name ? $row->document_name : "";
            });
            $table->editColumn('download', function ($row) {
                if (!$row->download) {
                    return '';
                }

                $links = [];

                foreach ($row->download as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('example_file', function ($row) {
                if (!$row->example_file) {
                    return '';
                }

                $links = [];

                foreach ($row->example_file as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('tag', function ($row) {
                $labels = [];

                foreach ($row->tags as $tag) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $tag->tag_name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'download', 'example_file', 'tag']);

            return $table->make(true);
        }

        return view('admin.departmentDocuments.index');
    }

    public function create()
    {
        abort_if(Gate::denies('department_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = DocumentTag::all()->pluck('tag_name', 'id');

        return view('admin.departmentDocuments.create', compact('tags'));
    }

    public function store(StoreDepartmentDocumentRequest $request)
    {
        $departmentDocument = DepartmentDocument::create($request->all());
        $departmentDocument->tags()->sync($request->input('tags', []));

        foreach ($request->input('download', []) as $file) {
            $departmentDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('download');
        }

        foreach ($request->input('example_file', []) as $file) {
            $departmentDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('example_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $departmentDocument->id]);
        }

        return redirect()->route('admin.department-documents.index');

    }

    public function edit(DepartmentDocument $departmentDocument)
    {
        abort_if(Gate::denies('department_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = DocumentTag::all()->pluck('tag_name', 'id');

        $departmentDocument->load('tags', 'team');

        return view('admin.departmentDocuments.edit', compact('tags', 'departmentDocument'));
    }

    public function update(UpdateDepartmentDocumentRequest $request, DepartmentDocument $departmentDocument)
    {
        $departmentDocument->update($request->all());
        $departmentDocument->tags()->sync($request->input('tags', []));

        if (count($departmentDocument->download) > 0) {
            foreach ($departmentDocument->download as $media) {
                if (!in_array($media->file_name, $request->input('download', []))) {
                    $media->delete();
                }

            }

        }

        $media = $departmentDocument->download->pluck('file_name')->toArray();

        foreach ($request->input('download', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $departmentDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('download');
            }

        }

        if (count($departmentDocument->example_file) > 0) {
            foreach ($departmentDocument->example_file as $media) {
                if (!in_array($media->file_name, $request->input('example_file', []))) {
                    $media->delete();
                }

            }

        }

        $media = $departmentDocument->example_file->pluck('file_name')->toArray();

        foreach ($request->input('example_file', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $departmentDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('example_file');
            }

        }

        return redirect()->route('admin.department-documents.index');

    }

    public function show(DepartmentDocument $departmentDocument)
    {
        abort_if(Gate::denies('department_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departmentDocument->load('tags', 'team');

        return view('admin.departmentDocuments.show', compact('departmentDocument'));
    }

    public function destroy(DepartmentDocument $departmentDocument)
    {
        abort_if(Gate::denies('department_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departmentDocument->delete();

        return back();

    }

    public function massDestroy(MassDestroyDepartmentDocumentRequest $request)
    {
        DepartmentDocument::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('department_document_create') && Gate::denies('department_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DepartmentDocument();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);

    }

}
