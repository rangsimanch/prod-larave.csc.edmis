<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtExternalDocumentRequest;
use App\Http\Requests\StoreSrtExternalDocumentRequest;
use App\Http\Requests\UpdateSrtExternalDocumentRequest;
use App\SrtDocumentStatus;
use App\SrtExternalDocument;
use App\Team;
use App\User;
use DateTime;
use File;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use LynX39\LaraPdfMerger\Facades\PdfMerger;

class SrtExternalDocumentController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_external_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtExternalDocument::with(['docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team'])->select(sprintf('%s.*', (new SrtExternalDocument)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'srt_external_document_show';
                $editGate      = 'srt_external_document_edit';
                $deleteGate    = 'srt_external_document_delete';
                $crudRoutePart = 'srt-external-documents';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            // $table->editColumn('id', function ($row) {
            //     return $row->id ? $row->id : "";
            // });
            $table->addColumn('docuement_status_title', function ($row) {
                return $row->docuement_status ? $row->docuement_status->title : '';
            });

            $table->addColumn('constuction_contract_code', function ($row) {
                return $row->constuction_contract ? $row->constuction_contract->code : '';
            });

            $table->editColumn('document_type', function ($row) {
                return $row->document_type ? SrtExternalDocument::DOCUMENT_TYPE_SELECT[$row->document_type] : '';
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : "";
            });
            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : "";
            });
            $table->editColumn('refer_to', function ($row) {
                return $row->refer_to ? $row->refer_to : "";
            });
            $table->editColumn('speed_class', function ($row) {
                return $row->speed_class ? SrtExternalDocument::SPEED_CLASS_SELECT[$row->speed_class] : '';
            });
            $table->editColumn('objective', function ($row) {
                return $row->objective ? SrtExternalDocument::OBJECTIVE_SELECT[$row->objective] : '';
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
                return $row->save_for ? SrtExternalDocument::SAVE_FOR_SELECT[$row->save_for] : '';
            });
            $table->addColumn('close_by_name', function ($row) {
                return $row->close_by ? $row->close_by->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'docuement_status', 'constuction_contract', 'file_upload', 'close_by']);

            return $table->make(true);
        }

        $srt_document_statuses  = SrtDocumentStatus::get();
        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();
        $users                  = User::get();
        $users                  = User::get();
        $teams                  = Team::get();

        return view('admin.srtExternalDocuments.index', compact('srt_document_statuses', 'construction_contracts', 'teams', 'users', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_external_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constuction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $froms = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tos = User::all()->pluck('name', 'id');

        $close_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.srtExternalDocuments.create', compact('constuction_contracts', 'froms', 'tos', 'close_bies'));
    }

    public function store(StoreSrtExternalDocumentRequest $request)
    {
        $data = $request->all();

        if($data['save_for'] == "Closed"){
            $data['docuement_status_id'] = 2;
            $data['close_by_id'] = auth()->id();
            $today = new DateTime();
            $data['close_date'] = $today->format("d/m/Y");
        }
        else{
            $data['docuement_status_id'] = 1;
        }

        // Document Number 
        $date_search = $data['incoming_date'];

            //Prefix
        $contracts_code = ConstructionContract::where('id',$data['constuction_contract_id'])->value('document_code');
        $doc_no_prefix = ConstructionContract::where('id',$data['constuction_contract_id'])->value('document_code') . '/';
            //Manual number
        if($data['document_number'] != null){
            $data['document_number'] = $doc_no_prefix . $data['document_number'];
        }
            //Auto number
        else{
            $date_replace = str_replace("/","",$date_search);
            $day_digit = substr($date_replace,0,2);
            $month_digit = substr($date_replace,2,2);
            $year_digit = substr($date_replace,4,4);
            $convert_year = intval($year_digit) + 543;
            $th_year_digit = substr($convert_year,2,2);
            $doc_date = $month_digit . $day_digit;

            $inDB = SrtExternalDocument::where('document_number','LIKE','%' . $contracts_code . "/" .  $doc_date .'%')->count(); 
            if($inDB > 0){

                $query= SrtExternalDocument::where('document_number','LIKE','%' . $contracts_code . "/" . $doc_date .'%')
                ->latest()->first(); 

                $str_last_number = $query->document_number;

                $last_postfix = substr($str_last_number,-10);
                $lastday_prefix = substr($last_postfix,0,-6);

                    // no. Document
                if($lastday_prefix == $doc_date){
                    $str_count = substr($last_postfix,5,-3);
                    $int_count = intval($str_count) + 1;
                    $count = $int_count;    
                }
                else{
                    $count = 1;
                }

                $data['document_number'] = $doc_no_prefix . $doc_date . '-' . substr("00{$count}",-2) . '/' . $th_year_digit;
            }
            else{
                    //no, Document 
                $count = 1;

                $data['document_number'] = $doc_no_prefix . $doc_date . '-' . substr("00{$count}",-2) . '/' . $th_year_digit;

            }
        }
        //End Document number

        $srtExternalDocument = SrtExternalDocument::create($data);
        $srtExternalDocument->tos()->sync($request->input('tos', []));

        $pdfMerger = PDFMerger::init();

        foreach ($request->input('file_upload', []) as $file) {
            $pdfMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');
        }
        $pdfMerger->merge();
        $pdfMerger->save(storage_path('tmp/uploads/mergerPdf_01.pdf'), "file");
        foreach ($request->input('file_upload', []) as $file) {
            File::delete(storage_path('tmp/uploads/' . $file));
        }
        $srtExternalDocument->addMedia(storage_path('tmp/uploads/mergerPdf_01.pdf'))->toMediaCollection('file_upload');

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $srtExternalDocument->id]);
        }

        return redirect()->route('admin.srt-external-documents.index');
    }

    public function edit(SrtExternalDocument $srtExternalDocument)
    {
        abort_if(Gate::denies('srt_external_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constuction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $froms = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tos = User::all()->pluck('name', 'id');

        $srtExternalDocument->load('docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team');

        return view('admin.srtExternalDocuments.edit', compact('constuction_contracts', 'froms', 'tos', 'close_bies', 'srtExternalDocument'));
    }

    public function update(UpdateSrtExternalDocumentRequest $request, SrtExternalDocument $srtExternalDocument)
    {
        $srtExternalDocument->update($request->all());
        $srtExternalDocument->tos()->sync($request->input('tos', []));

        if (count($srtExternalDocument->file_upload) > 0) {
            foreach ($srtExternalDocument->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $pdfMerger = PDFMerger::init();
        $media = $srtExternalDocument->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
                $pdfMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');
        }
        $pdfMerger->merge();
        $pdfMerger->save(storage_path('tmp/uploads/mergerPdf_01.pdf'), "file");
        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                File::delete(storage_path('tmp/uploads/' . $file));
            }
        }
        $srtExternalDocument->addMedia(storage_path('tmp/uploads/mergerPdf_01.pdf'))->toMediaCollection('file_upload');

        return redirect()->route('admin.srt-external-documents.index');
    }

    public function show(SrtExternalDocument $srtExternalDocument)
    {
        abort_if(Gate::denies('srt_external_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtExternalDocument->load('docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team');

        return view('admin.srtExternalDocuments.show', compact('srtExternalDocument'));
    }

    public function destroy(SrtExternalDocument $srtExternalDocument)
    {
        abort_if(Gate::denies('srt_external_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtExternalDocument->delete();

        return back();
    }

    public function massDestroy(MassDestroySrtExternalDocumentRequest $request)
    {
        SrtExternalDocument::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('srt_external_document_create') && Gate::denies('srt_external_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SrtExternalDocument();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
