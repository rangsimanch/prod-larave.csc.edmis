<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtExternalAgencyDocumentRequest;
use App\Http\Requests\StoreSrtExternalAgencyDocumentRequest;
use App\Http\Requests\UpdateSrtExternalAgencyDocumentRequest;
use App\SrtExternalAgencyDocument;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SrtExternalAgencyDocumentsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_external_agency_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtExternalAgencyDocument::with(['construction_contracts', 'team'])->select(sprintf('%s.*', (new SrtExternalAgencyDocument())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'srt_external_agency_document_show';
                $editGate = 'srt_external_agency_document_edit';
                $deleteGate = 'srt_external_agency_document_delete';
                $crudRoutePart = 'srt-external-agency-documents';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('construction_contract', function ($row) {
                $labels = [];
                foreach ($row->construction_contracts as $construction_contract) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $construction_contract->code);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });
            $table->editColumn('document_type', function ($row) {
                return $row->document_type ? SrtExternalAgencyDocument::DOCUMENT_TYPE_SELECT[$row->document_type] : '';
            });
            $table->editColumn('originator_number', function ($row) {
                return $row->originator_number ? $row->originator_number : '';
            });

            $table->editColumn('refer_to', function ($row) {
                return $row->refer_to ? $row->refer_to : '';
            });
            $table->editColumn('from', function ($row) {
                return $row->from ? $row->from : '';
            });
            $table->editColumn('to', function ($row) {
                return $row->to ? $row->to : '';
            });
            $table->editColumn('speed_class', function ($row) {
                return $row->speed_class ? SrtExternalAgencyDocument::SPEED_CLASS_SELECT[$row->speed_class] : '';
            });
            $table->editColumn('objective', function ($row) {
                return $row->objective ? SrtExternalAgencyDocument::OBJECTIVE_SELECT[$row->objective] : '';
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
            $table->editColumn('save_for', function ($row) {
                return $row->save_for ? SrtExternalAgencyDocument::SAVE_FOR_SELECT[$row->save_for] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'file_upload']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();

        return view('admin.srtExternalAgencyDocuments.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_external_agency_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id');

        return view('admin.srtExternalAgencyDocuments.create', compact('construction_contracts'));
    }

    public function store(StoreSrtExternalAgencyDocumentRequest $request)
    {
        $srtExternalAgencyDocument = SrtExternalAgencyDocument::create($request->all());
        $srtExternalAgencyDocument->construction_contracts()->sync($request->input('construction_contracts', []));
        foreach ($request->input('file_upload', []) as $file) {
            $srtExternalAgencyDocument->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $srtExternalAgencyDocument->id]);
        }

        return redirect()->route('admin.srt-external-agency-documents.index');
    }

    public function edit(SrtExternalAgencyDocument $srtExternalAgencyDocument)
    {
        abort_if(Gate::denies('srt_external_agency_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id');

        $srtExternalAgencyDocument->load('construction_contracts', 'team');

        return view('admin.srtExternalAgencyDocuments.edit', compact('construction_contracts', 'srtExternalAgencyDocument'));
    }

    public function update(UpdateSrtExternalAgencyDocumentRequest $request, SrtExternalAgencyDocument $srtExternalAgencyDocument)
    {
        $srtExternalAgencyDocument->update($request->all());
        $srtExternalAgencyDocument->construction_contracts()->sync($request->input('construction_contracts', []));
        if (count($srtExternalAgencyDocument->file_upload) > 0) {
            foreach ($srtExternalAgencyDocument->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $srtExternalAgencyDocument->file_upload->pluck('file_name')->toArray();
        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $srtExternalAgencyDocument->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.srt-external-agency-documents.index');
    }

    public function show(SrtExternalAgencyDocument $srtExternalAgencyDocument)
    {
        abort_if(Gate::denies('srt_external_agency_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtExternalAgencyDocument->load('construction_contracts', 'team');

        return view('admin.srtExternalAgencyDocuments.show', compact('srtExternalAgencyDocument'));
    }

    public function destroy(SrtExternalAgencyDocument $srtExternalAgencyDocument)
    {
        abort_if(Gate::denies('srt_external_agency_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtExternalAgencyDocument->delete();

        return back();
    }

    public function massDestroy(MassDestroySrtExternalAgencyDocumentRequest $request)
    {
        SrtExternalAgencyDocument::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('srt_external_agency_document_create') && Gate::denies('srt_external_agency_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SrtExternalAgencyDocument();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
