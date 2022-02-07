<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtPdDocumentRequest;
use App\Http\Requests\StoreSrtPdDocumentRequest;
use App\Http\Requests\UpdateSrtPdDocumentRequest;
use App\SrtInputDocument;
use App\SrtPdDocument;
use App\SrtPeDocument;
use App\SrtHeadOfficeDocument;
use App\Team;
use App\User;
use DB;
use DateTime;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use File;

class SrtPdDocumentsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_pd_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtPdDocument::with(['refer_documents', 'operators', 'team'])->select(sprintf('%s.*', (new SrtPdDocument)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'srt_pd_document_show';
                $editGate      = 'srt_pd_document_edit';
                $deleteGate    = 'srt_pd_document_delete';
                $crudRoutePart = 'srt-pd-documents';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            // $table->editColumn('refer_documents.file_upload_2', function ($row) {
            //     if (!$row->refer_documents->file_upload_2) {
            //         return '';
            //     }

            //     $links = [];

            //     $refer_doc = $row->refer_documents ? $row->refer_documents->document_number : '';

            //     foreach ($row->refer_documents->file_upload_2 as $media) {
            //         $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . $refer_doc . '</a>';
            //     }

            //     return implode(', ', $links);
            // });


            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            
            // $table->addColumn('refer_documents_document_number', function ($row) {
            //     return $row->refer_documents ? $row->refer_documents->document_number : '';
            // });

            $table->editColumn('refer_documents.subject', function ($row) {
                return $row->refer_documents ? (is_string($row->refer_documents) ? $row->refer_documents : $row->refer_documents->subject) : '';
            });

            $table->editColumn('special_command', function ($row) {
                return $row->special_command ? SrtPdDocument::SPECIAL_COMMAND_SELECT[$row->special_command] : '';
            });

            $table->editColumn('operator', function ($row) {
                $labels = [];

                foreach ($row->operators as $operator) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $operator->name);
                }

                return implode(' ', $labels);
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


                // if (!$row->refer_documents->file_upload_3) {
                //     return '';
                // }
                // foreach ($row->refer_documents->file_upload_3 as $media) {
                //     $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                // }

                return implode(', ', $links);
            });

            $table->editColumn('to_text', function ($row) {
                return $row->to_text ? $row->to_text : "";
            });

            $table->rawColumns(['actions', 'placeholder', 'refer_documents', 'operator', 'file_upload']);

            return $table->make(true);
        }

        $srt_input_documents = SrtInputDocument::get();
        $users               = User::get();
        $teams               = Team::get();

        return view('admin.srtPdDocuments.index', compact('srt_input_documents', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_pd_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refer_documents = SrtInputDocument::all()->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $operators = User::all()->pluck('name', 'id');

        return view('admin.srtPdDocuments.create', compact('refer_documents', 'operators'));
    }

    public function store(StoreSrtPdDocumentRequest $request)
    {
        $srtPdDocument = SrtPdDocument::create($request->all());
        $srtPdDocument->operators()->sync($request->input('operators', []));

        foreach ($request->input('file_upload', []) as $file) {
            $srtPdDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $srtPdDocument->id]);
        }

        return redirect()->route('admin.srt-pd-documents.index');
    }

    public function edit(SrtPdDocument $srtPdDocument)
    {
        abort_if(Gate::denies('srt_pd_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refer_documents = SrtInputDocument::all()->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $operators = User::all()->pluck('name', 'id');

        $srtPdDocument->load('refer_documents', 'operators', 'team');

        return view('admin.srtPdDocuments.edit', compact('refer_documents', 'operators', 'srtPdDocument'));
    }

    public function update(UpdateSrtPdDocumentRequest $request, SrtPdDocument $srtPdDocument)
    {
        $data = $request->all();
        
        //Close Documents
        if($data['save_for'] == "Closed"){
            $document_status_id = 2;
            $close_by_id = auth()->id();
            $today = new DateTime();
            $close_date = $today->format("Y-m-d");
            DB::table('srt_input_documents')
            ->where('id',$request->refer_documents_id)
            ->update(
                ['docuement_status_id' => $document_status_id,
                'close_by_id' => $close_by_id,
                'close_date' => $close_date]
            );
        }

        // Insert to PD
        else{
            $SrtDocumentID = $request->refer_documents_id;
            $count_check_doc = SrtPeDocument::where('refer_documents_id',$SrtDocumentID)->count();
            $Contract = SrtInputDocument::where('id',$SrtDocumentID)->value('construction_contract_id');
            if($count_check_doc == 0){
                $dataPE = array(
                    'refer_documents_id' => $SrtDocumentID,
                    'save_for'          => "Process",
                    'construction_contract_id' => $Contract
                );
                $InsertDataPE[] = $dataPE; 
                $srtPEDocument = SrtPeDocument::insert($InsertDataPE);
            }

        }

        $srtPdDocument->update($request->all());
        $srtPdDocument->operators()->sync($request->input('operators', []));

        if (count($srtPdDocument->file_upload) > 0) {
            foreach ($srtPdDocument->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        //Default
        $media = $srtPdDocument->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $srtPdDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        //Create SrtInputDocument Object
        // $srtInputDocument = SrtInputDocument::find($request->refer_documents_id);
        // //Link Media to file_upload_2
        // $media = $srtInputDocument->file_upload_3->pluck('file_name')->toArray();
        // //Merger PDF

        // if(count($request->input('file_upload', [])) > 0){
        //     // Complete Files
        //     if($data['save_for'] == "Closed"){
        //         $CompleteMerger = PDFMerger::init();
        //         $fileUpload_1 = $srtInputDocument->getMedia('file_upload')->first();
        //         $fileUpload_2 = $srtInputDocument->getMedia('file_upload_2')->first();
        //         $CompleteMerger->addPDF($fileUpload_1->getPath());
        //         $CompleteMerger->addPDF($fileUpload_2->getPath());
        //         foreach ($request->input('file_upload', []) as $file) {
        //             if (count($media) === 0 || !in_array($file, $media)) {
        //                 $CompleteMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');  
        //             }
        //         }
        //         $CompleteMerger->merge();
        //         $CompleteMerger->save(storage_path('tmp/uploads/mergerPdf_Completed.pdf'), "file");
        //         $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_Completed.pdf'))->toMediaCollection('complete_file');
        //     }

        //     $pdfMerger = PDFMerger::init();
        //     //Merge PDF in Dropzone
        //     foreach ($request->input('file_upload', []) as $file) {
        //         if (count($media) === 0 || !in_array($file, $media)) {
        //             $pdfMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');  
        //         }
        //     }
        //     $pdfMerger->merge();
        //     //Save to tmp
        //     $pdfMerger->save(storage_path('tmp/uploads/mergerPdf_03.pdf'), "file");
        //     //Delete Dropzone file on tmp 
        //     foreach ($request->input('file_upload', []) as $file) {
        //         if (count($media) === 0 || !in_array($file, $media)) {
        //             File::delete(storage_path('tmp/uploads/' . $file));
        //         }
        //     }
        //     //Add media to SrtInputDocument Collection (file_upload_2)
        //     $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_03.pdf'))->toMediaCollection('file_upload_3');
        // }else{
        //     $CompleteMerger = PDFMerger::init();
        //     $fileUpload_1 = $srtInputDocument->getMedia('file_upload')->first();
        //     $fileUpload_2 = $srtInputDocument->getMedia('file_upload_2')->first();
        //     $CompleteMerger->addPDF($fileUpload_1->getPath());
        //     $CompleteMerger->addPDF($fileUpload_2->getPath());
        //     $CompleteMerger->merge();
        //     $CompleteMerger->save(storage_path('tmp/uploads/mergerPdf_Completed.pdf'), "file");
        //     $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_Completed.pdf'))->toMediaCollection('complete_file');
        // }
        // //Delete Function on SrtInputDocument Collection (file_upload_2)
        // if (count($srtInputDocument->file_upload_3) > 0) {
        //     foreach ($srtInputDocument->file_upload_3 as $media) {
        //         if (!in_array($media->file_name, $request->input('file_upload', []))) {
        //             $media->delete();
        //         }
        //     }
        // }

        return redirect()->route('admin.srt-pd-documents.index');
    }

    public function show(SrtPdDocument $srtPdDocument)
    {
        abort_if(Gate::denies('srt_pd_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtPdDocument->load('refer_documents', 'operators', 'team');

        return view('admin.srtPdDocuments.show', compact('srtPdDocument'));
    }

    public function destroy(SrtPdDocument $srtPdDocument)
    {
        abort_if(Gate::denies('srt_pd_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtPdDocument->delete();

        return back();
    }

    public function massDestroy(MassDestroySrtPdDocumentRequest $request)
    {
        SrtPdDocument::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('srt_pd_document_create') && Gate::denies('srt_pd_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SrtPdDocument();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
