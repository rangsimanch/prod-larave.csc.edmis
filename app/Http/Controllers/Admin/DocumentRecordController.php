<?php

namespace App\Http\Controllers\Admin;

use App\DocumentRecord;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocumentRecordRequest;
use App\Http\Requests\StoreDocumentRecordRequest;
use App\Http\Requests\UpdateDocumentRecordRequest;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocumentRecordController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('document_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocumentRecord::with(['team'])->select(sprintf('%s.*', (new DocumentRecord)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'document_record_show';
                $editGate      = 'document_record_edit';
                $deleteGate    = 'document_record_delete';
                $crudRoutePart = 'document-records';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
             $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });
            $table->editColumn('category', function ($row) {
                return $row->category ? DocumentRecord::CATEGORY_SELECT[$row->category] : '';
            });
            $table->editColumn('file_upload', function ($row) {
                if (! $row->file_upload) {
                    return '';
                }
                $links = [];
                foreach ($row->file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->editColumn('translated_by', function ($row) {
                return $row->translated_by ? $row->translated_by : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'file_upload']);

            return $table->make(true);
        }

        $teams = Team::get();

        return view('admin.documentRecords.index', compact('teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('document_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.documentRecords.create');
    }

    public function store(StoreDocumentRecordRequest $request)
    {
        $documentRecord = DocumentRecord::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $documentRecord->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $documentRecord->id]);
        }

        return redirect()->route('admin.document-records.index');
    }

    public function edit(DocumentRecord $documentRecord)
    {
        abort_if(Gate::denies('document_record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentRecord->load('team');

        return view('admin.documentRecords.edit', compact('documentRecord'));
    }

    public function update(UpdateDocumentRecordRequest $request, DocumentRecord $documentRecord)
    {
        $documentRecord->update($request->all());

        if (count($documentRecord->file_upload) > 0) {
            foreach ($documentRecord->file_upload as $media) {
                if (! in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $documentRecord->file_upload->pluck('file_name')->toArray();
        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $documentRecord->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.document-records.index');
    }

    public function show(DocumentRecord $documentRecord)
    {
        abort_if(Gate::denies('document_record_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentRecord->load('team');

        return view('admin.documentRecords.show', compact('documentRecord'));
    }

    public function destroy(DocumentRecord $documentRecord)
    {
        abort_if(Gate::denies('document_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentRecord->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocumentRecordRequest $request)
    {
        $documentRecords = DocumentRecord::find(request('ids'));

        foreach ($documentRecords as $documentRecord) {
            $documentRecord->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('document_record_create') && Gate::denies('document_record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocumentRecord();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
