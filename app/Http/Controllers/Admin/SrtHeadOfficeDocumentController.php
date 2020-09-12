<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtHeadOfficeDocumentRequest;
use App\Http\Requests\StoreSrtHeadOfficeDocumentRequest;
use App\Http\Requests\UpdateSrtHeadOfficeDocumentRequest;
use App\SrtDocumentStatus;
use App\SrtHeadOfficeDocument;
use App\SrtInputDocument;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SrtHeadOfficeDocumentController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_head_office_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtHeadOfficeDocument::with(['refer_documents', 'special_command', 'team'])->select(sprintf('%s.*', (new SrtHeadOfficeDocument)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'srt_head_office_document_show';
                $editGate      = 'srt_head_office_document_edit';
                $deleteGate    = 'srt_head_office_document_delete';
                $crudRoutePart = 'srt-head-office-documents';

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
            $table->addColumn('refer_documents_document_number', function ($row) {
                return $row->refer_documents ? $row->refer_documents->document_number : '';
            });

            $table->addColumn('special_command_title', function ($row) {
                return $row->special_command ? $row->special_command->title : '';
            });

            $table->editColumn('practitioner', function ($row) {
                return $row->practitioner ? $row->practitioner : "";
            });
            $table->editColumn('practice_notes', function ($row) {
                return $row->practice_notes ? $row->practice_notes : "";
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : "";
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

            $table->rawColumns(['actions', 'placeholder', 'refer_documents', 'special_command', 'file_upload']);

            return $table->make(true);
        }

        $srt_input_documents   = SrtInputDocument::get();
        $srt_document_statuses = SrtDocumentStatus::get();
        $teams                 = Team::get();

        return view('admin.srtHeadOfficeDocuments.index', compact('srt_input_documents', 'srt_document_statuses', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_head_office_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refer_documents = SrtInputDocument::all()->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $special_commands = SrtDocumentStatus::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.srtHeadOfficeDocuments.create', compact('refer_documents', 'special_commands'));
    }

    public function store(StoreSrtHeadOfficeDocumentRequest $request)
    {
        $srtHeadOfficeDocument = SrtHeadOfficeDocument::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $srtHeadOfficeDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $srtHeadOfficeDocument->id]);
        }

        return redirect()->route('admin.srt-head-office-documents.index');
    }

    public function edit(SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        abort_if(Gate::denies('srt_head_office_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refer_documents = SrtInputDocument::all()->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $special_commands = SrtDocumentStatus::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $srtHeadOfficeDocument->load('refer_documents', 'special_command', 'team');

        return view('admin.srtHeadOfficeDocuments.edit', compact('refer_documents', 'special_commands', 'srtHeadOfficeDocument'));
    }

    public function update(UpdateSrtHeadOfficeDocumentRequest $request, SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        $srtHeadOfficeDocument->update($request->all());

        if (count($srtHeadOfficeDocument->file_upload) > 0) {
            foreach ($srtHeadOfficeDocument->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $srtHeadOfficeDocument->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $srtHeadOfficeDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.srt-head-office-documents.index');
    }

    public function show(SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        abort_if(Gate::denies('srt_head_office_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtHeadOfficeDocument->load('refer_documents', 'special_command', 'team');

        return view('admin.srtHeadOfficeDocuments.show', compact('srtHeadOfficeDocument'));
    }

    public function destroy(SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        abort_if(Gate::denies('srt_head_office_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtHeadOfficeDocument->delete();

        return back();
    }

    public function massDestroy(MassDestroySrtHeadOfficeDocumentRequest $request)
    {
        SrtHeadOfficeDocument::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('srt_head_office_document_create') && Gate::denies('srt_head_office_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SrtHeadOfficeDocument();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
