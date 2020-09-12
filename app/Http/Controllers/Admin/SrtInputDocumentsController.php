<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtInputDocumentRequest;
use App\Http\Requests\StoreSrtInputDocumentRequest;
use App\Http\Requests\UpdateSrtInputDocumentRequest;
use App\SrtDocumentStatus;
use App\SrtInputDocument;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SrtInputDocumentsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_input_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtInputDocument::with(['docuement_status', 'team'])->select(sprintf('%s.*', (new SrtInputDocument)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'srt_input_document_show';
                $editGate      = 'srt_input_document_edit';
                $deleteGate    = 'srt_input_document_delete';
                $crudRoutePart = 'srt-input-documents';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('document_type', function ($row) {
                return $row->document_type ? SrtInputDocument::DOCUMENT_TYPE_SELECT[$row->document_type] : '';
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : "";
            });

            $table->editColumn('refer_to', function ($row) {
                return $row->refer_to ? $row->refer_to : "";
            });
            $table->editColumn('attachments', function ($row) {
                return $row->attachments ? $row->attachments : "";
            });
            $table->editColumn('from', function ($row) {
                return $row->from ? SrtInputDocument::FROM_SELECT[$row->from] : '';
            });
            $table->editColumn('to', function ($row) {
                return $row->to ? SrtInputDocument::TO_SELECT[$row->to] : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : "";
            });
            $table->editColumn('speed_class', function ($row) {
                return $row->speed_class ? SrtInputDocument::SPEED_CLASS_SELECT[$row->speed_class] : '';
            });
            $table->editColumn('objective', function ($row) {
                return $row->objective ? SrtInputDocument::OBJECTIVE_SELECT[$row->objective] : '';
            });
            $table->editColumn('signer', function ($row) {
                return $row->signer ? $row->signer : "";
            });
            $table->editColumn('document_storage', function ($row) {
                return $row->document_storage ? $row->document_storage : "";
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : "";
            });
            $table->addColumn('docuement_status_title', function ($row) {
                return $row->docuement_status ? $row->docuement_status->title : '';
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

            $table->rawColumns(['actions', 'placeholder', 'docuement_status', 'file_upload']);

            return $table->make(true);
        }

        $srt_document_statuses = SrtDocumentStatus::get();
        $teams                 = Team::get();

        return view('admin.srtInputDocuments.index', compact('srt_document_statuses', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_input_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.srtInputDocuments.create');
    }

    public function store(StoreSrtInputDocumentRequest $request)
    {
        $srtInputDocument = SrtInputDocument::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $srtInputDocument->id]);
        }

        return redirect()->route('admin.srt-input-documents.index');
    }

    public function edit(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtInputDocument->load('docuement_status', 'team');

        return view('admin.srtInputDocuments.edit', compact('srtInputDocument'));
    }

    public function update(UpdateSrtInputDocumentRequest $request, SrtInputDocument $srtInputDocument)
    {
        $srtInputDocument->update($request->all());

        if (count($srtInputDocument->file_upload) > 0) {
            foreach ($srtInputDocument->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $srtInputDocument->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $srtInputDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.srt-input-documents.index');
    }

    public function show(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtInputDocument->load('docuement_status', 'team');

        return view('admin.srtInputDocuments.show', compact('srtInputDocument'));
    }

    public function destroy(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtInputDocument->delete();

        return back();
    }

    public function massDestroy(MassDestroySrtInputDocumentRequest $request)
    {
        SrtInputDocument::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('srt_input_document_create') && Gate::denies('srt_input_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SrtInputDocument();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
