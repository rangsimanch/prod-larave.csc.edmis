<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtPeDocumentRequest;
use App\Http\Requests\StoreSrtPeDocumentRequest;
use App\Http\Requests\UpdateSrtPeDocumentRequest;
use App\SrtInputDocument;
use App\SrtPeDocument;
use App\Team;
use App\User;
use Gate;
use DB;
use DateTime;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use File;

class SrtPeDocumentsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_pe_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtPeDocument::with(['refer_documents', 'operators', 'team'])->select(sprintf('%s.*', (new SrtPeDocument)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'srt_pe_document_show';
                $editGate      = 'srt_pe_document_edit';
                $deleteGate    = 'srt_pe_document_delete';
                $crudRoutePart = 'srt-pe-documents';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            // $table->editColumn('refer_documents.file_upload_3', function ($row) {
            //     if (!$row->refer_documents->file_upload_3) {
            //         return '';
            //     }

            //     $links = [];

            //     $refer_doc = $row->refer_documents ? $row->refer_documents->document_number : '';

            //     foreach ($row->refer_documents->file_upload_3 as $media) {
            //         $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . $refer_doc . '</a>';
            //     }

            //     return implode(', ', $links);
            // });

            $table->addColumn('refer_documents_document_number', function ($row) {
                return $row->refer_documents ? $row->refer_documents->document_number : '';
            });

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
                return $row->special_command ? SrtPeDocument::SPECIAL_COMMAND_SELECT[$row->special_command] : '';
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
                // if (!$row->file_upload) {
                //     return '';
                // }

                if (!$row->refer_documents->file_upload_4) {
                    return '';
                }

                $links = [];

                // foreach ($row->file_upload as $media) {
                //     $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                // }

                foreach ($row->refer_documents->file_upload_4 as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->editColumn('to_text', function ($row) {
                return $row->to_text ? $row->to_text : "";
            });

            $table->rawColumns(['actions', 'placeholder', 'refer_documents', 'operator', 'file_upload', 'refer_documents.file_upload_3']);

            return $table->make(true);
        }

        $srt_input_documents = SrtInputDocument::get();
        $users               = User::get();
        $teams               = Team::get();

        return view('admin.srtPeDocuments.index', compact('srt_input_documents', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_pe_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refer_documents = SrtInputDocument::all()->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $operators = User::all()->pluck('name', 'id');

        return view('admin.srtPeDocuments.create', compact('refer_documents', 'operators'));
    }

    public function store(StoreSrtPeDocumentRequest $request)
    {
        $srtPeDocument = SrtPeDocument::create($request->all());
        $srtPeDocument->operators()->sync($request->input('operators', []));

        foreach ($request->input('file_upload', []) as $file) {
            $srtPeDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $srtPeDocument->id]);
        }

        return redirect()->route('admin.srt-pe-documents.index');
    }

    public function edit(SrtPeDocument $srtPeDocument)
    {
        abort_if(Gate::denies('srt_pe_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refer_documents = SrtInputDocument::all()->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $operators = User::all()->pluck('name', 'id');

        $srtPeDocument->load('refer_documents', 'operators', 'team');

        return view('admin.srtPeDocuments.edit', compact('refer_documents', 'operators', 'srtPeDocument'));
    }

    public function update(UpdateSrtPeDocumentRequest $request, SrtPeDocument $srtPeDocument)
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
        
        $srtPeDocument->update($request->all());
        $srtPeDocument->operators()->sync($request->input('operators', []));

        if (count($srtPeDocument->file_upload) > 0) {
            foreach ($srtPeDocument->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        //Create SrtInputDocument Object
        $srtInputDocument = SrtInputDocument::find($request->refer_documents_id);
        //Link Media to file_upload_2
        $media = $srtInputDocument->file_upload_4->pluck('file_name')->toArray();
        if(count($request->input('file_upload', [])) > 0){
            // Complete Files
            if($data['save_for'] == "Closed"){
                $CompleteMerger = PDFMerger::init();
                $fileUpload_1 = $srtInputDocument->getMedia('file_upload')->first();
                $fileUpload_2 = $srtInputDocument->getMedia('file_upload_2')->first();
                $fileUpload_3 = $srtInputDocument->getMedia('file_upload_3')->first();
                $CompleteMerger->addPDF($fileUpload_1->getPath());
                $CompleteMerger->addPDF($fileUpload_2->getPath());
                $CompleteMerger->addPDF($fileUpload_3->getPath());
                foreach ($request->input('file_upload', []) as $file) {
                    if (count($media) === 0 || !in_array($file, $media)) {
                        $CompleteMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');  
                    }
                }
                $CompleteMerger->merge();
                $CompleteMerger->save(storage_path('tmp/uploads/mergerPdf_Completed.pdf'), "file");
                $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_Completed.pdf'))->toMediaCollection('complete_file');
            }


            //Merger PDF
            $pdfMerger = PDFMerger::init();
            //Merge PDF in Dropzone
            foreach ($request->input('file_upload', []) as $file) {
                if (count($media) === 0 || !in_array($file, $media)) {
                    $pdfMerger->addPDF(storage_path('tmp/uploads/' . $file), 'all');  
                }
            }
            $pdfMerger->merge();
            //Save to tmp
            $pdfMerger->save(storage_path('tmp/uploads/mergerPdf_04.pdf'), "file");
            
            
            //Delete Dropzone file on tmp 
            foreach ($request->input('file_upload', []) as $file) {
                if (count($media) === 0 || !in_array($file, $media)) {
                    File::delete(storage_path('tmp/uploads/' . $file));
                }
            }
            //Add media to SrtInputDocument Collection (file_upload_4)
            $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_04.pdf'))->toMediaCollection('file_upload_4');
        }else{
            $CompleteMerger = PDFMerger::init();
            $fileUpload_1 = $srtInputDocument->getMedia('file_upload')->first();
            $fileUpload_2 = $srtInputDocument->getMedia('file_upload_2')->first();
            $fileUpload_3 = $srtInputDocument->getMedia('file_upload_3')->first();
            $CompleteMerger->addPDF($fileUpload_1->getPath());
            $CompleteMerger->addPDF($fileUpload_2->getPath());
            $CompleteMerger->addPDF($fileUpload_3->getPath());
            $CompleteMerger->merge();
            $CompleteMerger->save(storage_path('tmp/uploads/mergerPdf_Completed.pdf'), "file");
            $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_Completed.pdf'))->toMediaCollection('complete_file');
        }

        //Delete Function on SrtInputDocument Collection (file_upload_4)
        if (count($srtInputDocument->file_upload_4) > 0) {
            foreach ($srtInputDocument->file_upload_4 as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        //Defualt
        // $media = $srtPeDocument->file_upload->pluck('file_name')->toArray();

        // foreach ($request->input('file_upload', []) as $file) {
        //     if (count($media) === 0 || !in_array($file, $media)) {
        //         $srtPeDocument->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        //     }
        // }

        return redirect()->route('admin.srt-pe-documents.index');
    }

    public function show(SrtPeDocument $srtPeDocument)
    {
        abort_if(Gate::denies('srt_pe_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtPeDocument->load('refer_documents', 'operators', 'team');

        return view('admin.srtPeDocuments.show', compact('srtPeDocument'));
    }

    public function destroy(SrtPeDocument $srtPeDocument)
    {
        abort_if(Gate::denies('srt_pe_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtPeDocument->delete();

        return back();
    }

    public function massDestroy(MassDestroySrtPeDocumentRequest $request)
    {
        SrtPeDocument::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('srt_pe_document_create') && Gate::denies('srt_pe_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SrtPeDocument();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
