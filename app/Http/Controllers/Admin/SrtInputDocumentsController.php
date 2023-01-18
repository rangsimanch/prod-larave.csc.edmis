<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtInputDocumentRequest;
use App\Http\Requests\StoreSrtInputDocumentRequest;
use App\Http\Requests\UpdateSrtInputDocumentRequest;
use App\SrtDocumentStatus;
use App\SrtInputDocument;
use App\Team;
use App\User;
use App\Organization;
use DateTime;
use File;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\SrtHeadOfficeDocument;
use DB;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Illuminate\Support\Facades\Auth;


class SrtInputDocumentsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_input_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtInputDocument::with(['docuement_status', 'constuction_contract','from_organization', 'from', 'tos', 'close_by', 'team'])->select(sprintf('%s.*', (new SrtInputDocument)->table));
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

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('docuement_status_title', function ($row) {
                return $row->docuement_status ? $row->docuement_status->title : '';
            });

            $table->addColumn('constuction_contract_code', function ($row) {
                return $row->constuction_contract ? $row->constuction_contract->code : '';
            });

            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : "";
            });
            $table->editColumn('document_type', function ($row) {
                return $row->document_type ? SrtInputDocument::DOCUMENT_TYPE_SELECT[$row->document_type] : '';
            });
            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : "";
            });
            $table->editColumn('refer_to', function ($row) {
                return $row->refer_to ? $row->refer_to : "";
            });
            $table->editColumn('speed_class', function ($row) {
                return $row->speed_class ? SrtInputDocument::SPEED_CLASS_SELECT[$row->speed_class] : '';
            });
            $table->editColumn('objective', function ($row) {
                return $row->objective ? SrtInputDocument::OBJECTIVE_SELECT[$row->objective] : '';
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
            $table->editColumn('file_upload_2', function ($row) {
                if (!$row->file_upload_2) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload_2 as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('file_upload_3', function ($row) {
                if (!$row->file_upload_3) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload_3 as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('file_upload_4', function ($row) {
                if (!$row->file_upload_4) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload_4 as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('complete_file', function ($row) {
                if (!$row->complete_file) {
                    return '';
                }

                $links = [];

                foreach ($row->complete_file as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('close_by_name', function ($row) {
                return $row->close_by ? $row->close_by->name : '';
            });

            $table->editColumn('to_text', function ($row) {
                return $row->to_text ? $row->to_text : "";
            });

            $table->rawColumns(['actions', 'placeholder','construction_contract', 'docuement_status', 'file_upload', 'file_upload_2', 'file_upload_3', 'file_upload_4','complete_file', 'close_by']);

            return $table->make(true);
        }

        $srt_document_statuses  = SrtDocumentStatus::get();
        $construction_contracts = ConstructionContract::get();
        $organizations          = Organization::get();
        $users                  = User::get();
        $users                  = User::get();
        $users                  = User::get();
        $teams                  = Team::get();

        return view('admin.srtInputDocuments.index', compact('srt_document_statuses', 'construction_contracts', 'organizations', 'users', 'users', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_input_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if(Auth::id() != 1){
            $constuction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $constuction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $froms = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tos = User::all()->where('team_id','1')->pluck('name', 'id');

        return view('admin.srtInputDocuments.create', compact('constuction_contracts','froms', 'tos'));
    }

    public function store(StoreSrtInputDocumentRequest $request)
    {
        $data = $request->all();

        $date_search = $data['incoming_date'];

        //Prefix
        $contracts_code = ConstructionContract::where('id',$data['construction_contract_id'])->value('document_code');
        $doc_no_prefix = ConstructionContract::where('id',$data['construction_contract_id'])->value('document_code') . '/';
        
        if($data['document_number'] != null){
            $data['document_number'] = $doc_no_prefix . $data['document_number'];
        }
        else{
            $date_replace = str_replace("/","",$date_search);
            $day_digit = substr($date_replace,0,2);
            $month_digit = substr($date_replace,2,2);
            $year_digit = substr($date_replace,4,4);
            $convert_year = intval($year_digit) + 543;
            $th_year_digit = substr($convert_year,2,2);

            $doc_date = $day_digit . $month_digit;

        
            $inDB = SrtInputDocument::where('document_number' , 'LIKE' , '%' . $contracts_code . "/" .  $doc_date .'%')
            ->whereRaw('RIGHT(document_number,2) LIKE ?' , [$th_year_digit])
            ->count(); 


            if($inDB > 0){

                $query = SrtInputDocument::where('document_number' , 'LIKE' , '%' . $contracts_code . "/" . $doc_date .'%')
                ->whereRaw('RIGHT(document_number,2) LIKE ?' , [$th_year_digit])
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



        if($data['save_for'] == "Closed"){
            $data['docuement_status_id'] = 2;
            $data['close_by_id'] = auth()->id();
            $today = new DateTime();
            $data['close_date'] = $today->format("d/m/Y");
        }
        else{
            $data['docuement_status_id'] = 1;
        }

        $srtInputDocument = SrtInputDocument::create($data);
        
        if($data['save_for'] == "Process"){
            $SrtDocumentID = DB::table('srt_input_documents')->latest()->first()->id;
            $Contract = $data['construction_contract_id'];
            $dataHeadOffice = array(
                'refer_documents_id' => $SrtDocumentID,
                'save_for'          => "Process",
                'construction_contract_id' => $Contract
            );
            $InsertDataHeadOffice[] = $dataHeadOffice; 
            $srtHeadOfficeDocument = SrtHeadOfficeDocument::insert($InsertDataHeadOffice);
        }

        $srtInputDocument->tos()->sync($request->input('tos', []));

        foreach ($request->input('file_upload', []) as $file) {
            $srtInputDocument->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
        }


        // $pdfMerger = PDFMerger::init();

        // foreach ($request->input('file_upload', []) as $file) {
        //     $pdfMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');
        // }
        // $pdfMerger->merge();
        // $pdfMerger->save(storage_path('tmp/uploads/mergerPdf_01.pdf'), "file");
        // foreach ($request->input('file_upload', []) as $file) {
        //     File::delete(storage_path('tmp/uploads/' . $file));
        // }
        // $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_01.pdf'))->toMediaCollection('file_upload');

        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $srtInputDocument->id]);
        }

        return redirect()->route('admin.srt-input-documents.index');
    }

    public function edit(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constuction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

 

        $froms = Team::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tos = User::all()->where('team_id','1')->pluck('name', 'id');
        

        $srtInputDocument->load('docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team');

        return view('admin.srtInputDocuments.edit', compact('constuction_contracts', 'froms', 'tos', 'srtInputDocument'));
    }

    public function update(UpdateSrtInputDocumentRequest $request, SrtInputDocument $srtInputDocument)
    {
        $data = $request->all();
        
        if($data['save_for'] == 'Closed'){
            $data['docuement_status_id'] = 2;
        }
        else{
            $data['docuement_status_id'] = 1;
        }

        $data['close_by_id'] = auth()->id();
        
        $today = new DateTime();
        $data['close_date'] = $today->format("d/m/Y");

        $srtInputDocument->update($data);
        $srtInputDocument->tos()->sync($request->input('tos', []));


        if (count($srtInputDocument->file_upload_2) > 0) {
            foreach ($srtInputDocument->file_upload_2 as $media) {
                if (!in_array($media->file_name, $request->input('file_upload_2', []))) {
                    $media->delete();
                }
            }
        }
        $media = $srtInputDocument->file_upload_2->pluck('file_name')->toArray();
        foreach ($request->input('file_upload_2', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $srtInputDocument->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload_2');
            }
        }

    

        // if (count($srtInputDocument->file_upload) > 0) {
        //     foreach ($srtInputDocument->file_upload as $media) {
        //         if (!in_array($media->file_name, $request->input('file_upload', []))) {
        //             $media->delete();
        //         }
        //     }
        // }

        
        // $media = $srtInputDocument->file_upload->pluck('file_name')->toArray();
        // if(count($request->input('file_upload', [])) > 1){
        //     // Complete Files
        //     if($data['save_for'] == "Closed"){
        //         $CompleteMerger = PDFMerger::init();
        //         $fileUpload_1 = $srtInputDocument->getMedia('file_upload')->first();
        //         $CompleteMerger->addPDF($fileUpload_1->getPath());
        //         foreach ($request->input('file_upload', []) as $file) {
        //             if (count($media) === 0 || !in_array($file, $media)) {
        //                 $CompleteMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');  
        //             }
        //         }
        //         $CompleteMerger->merge();
        //         $CompleteMerger->save(storage_path('tmp/uploads/mergerPdf_Completed.pdf'), "file");
        //         $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_Completed.pdf'))->toMediaCollection('complete_file');
        //     }
    
        //         //Merger PDF
        //         $pdfMerger = PDFMerger::init();
        //         //Merge PDF in Dropzone
        //         foreach ($request->input('file_upload', []) as $file) {
        //             if (count($media) === 0 || !in_array($file, $media)) {
        //                 $pdfMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');  
        //             }
        //         }
        //             $pdfMerger->merge();
        //             //Save to tmp
        //             $pdfMerger->save(storage_path('tmp/uploads/mergerPdf_02.pdf'), "file");
        //             //Delete Dropzone file on tmp 
        //             foreach ($request->input('file_upload', []) as $file) {
        //                 if (count($media) === 0 || !in_array($file, $media)) {
        //                     File::delete(storage_path('tmp/uploads/' . $file));
        //                 }
        //             }
        //             //Add media to SrtInputDocument Collection (file_upload_2)
        //             $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_02.pdf'))->toMediaCollection('file_upload_2');
        //         }
        //         else{
        //             if($data['save_for'] == "Closed"){
        //                 $CompleteMerger = PDFMerger::init();
        //                 // $fileUpload_1 = $srtInputDocument->getMedia('file_upload')->first();
        //                 // $CompleteMerger = $fileUpload_1->copy($srtInputDocument,'complete_file');
        //                 $media = $srtInputDocument->file_upload->pluck('file_name')->toArray();
        //                 foreach ($request->input('file_upload', []) as $file) {
        //                     if (count($media) === 0 || !in_array($file, $media)) {
        //                         $srtInputDocument->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('complete_file');
        //                     }
        //                 }
        //             }
        //         }
    
        //         //Delete Function on SrtInputDocument Collection (file_upload_2)
        //         if (count($srtInputDocument->file_upload) > 0) {
        //             foreach ($srtInputDocument->file_upload as $media) {
        //                 if (!in_array($media->file_name, $request->input('file_upload', []))) {
        //                     $media->delete();
        //                 }
        //             }
        //         }

        return redirect()->route('admin.srt-input-documents.index');
    }

    public function show(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtInputDocument->load('docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team');

        return view('admin.srtInputDocuments.show', compact('srtInputDocument'));
    }

    public function destroy(SrtInputDocument $srtInputDocument)
    {
        abort_if(Gate::denies('srt_input_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtInputDocument->delete();
        $srtHeadOfficeDocument = SrtHeadOfficeDocument::where('refer_documents_id',$srtInputDocument->id)->delete();

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
