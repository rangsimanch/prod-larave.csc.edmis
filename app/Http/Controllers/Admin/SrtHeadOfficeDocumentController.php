<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtHeadOfficeDocumentRequest;
use App\Http\Requests\StoreSrtHeadOfficeDocumentRequest;
use App\Http\Requests\UpdateSrtHeadOfficeDocumentRequest;
use App\SrtHeadOfficeDocument;
use App\SrtInputDocument;
use App\SrtPdDocument;
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



class SrtHeadOfficeDocumentController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_head_office_document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtHeadOfficeDocument::with(['refer_documents', 'operators', 'team'])->select(sprintf('%s.*', (new SrtHeadOfficeDocument)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                // if(is_null($row->special_command)){
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
                // }
            });

                    // $table->editColumn('refer_documents.file_upload', function ($row) {
                    //     if (!$row->refer_documents->file_upload) {
                    //         return '';
                    //     }

                    //     $links = [];

                    //     $refer_doc = $row->refer_documents ? $row->refer_documents->document_number : '';

                    //     foreach ($row->refer_documents->file_upload as $media) {
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

            $table->editColumn('refer_documents.subject', function ($row) {
                return $row->refer_documents ? (is_string($row->refer_documents) ? $row->refer_documents : $row->refer_documents->subject) : '';
            });

            $table->editColumn('special_command', function ($row) {
                return $row->special_command ? SrtHeadOfficeDocument::SPECIAL_COMMAND_SELECT[$row->special_command] : '';
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
           

            $table->editColumn('to_text', function ($row) {
                return $row->to_text ? $row->to_text : "";
            });

            $table->editColumn('save_for', function ($row) {
                return $row->save_for ? $row->save_for : "";
            });

            $table->rawColumns(['actions', 'placeholder', 'refer_documents', 'operator', 'refer_documents.file_upload', 'save_for']);

            return $table->make(true);
        }

        $srt_input_documents = SrtInputDocument::get();
        $users               = User::get();
        $teams               = Team::get();

        return view('admin.srtHeadOfficeDocuments.index', compact('srt_input_documents', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_head_office_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refer_documents = SrtInputDocument::all()->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $operators = User::all()->where('team_id','1')->pluck('name', 'id');

        return view('admin.srtHeadOfficeDocuments.create', compact('refer_documents', 'operators'));
    }

    public function store(StoreSrtHeadOfficeDocumentRequest $request)
    {
        $srtHeadOfficeDocument = SrtHeadOfficeDocument::create($request->all());
        $srtHeadOfficeDocument->operators()->sync($request->input('operators', []));

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

        $operators = User::all()->where('team_id','1')->pluck('name', 'id');

        $srtHeadOfficeDocument->load('refer_documents', 'operators', 'team');

        return view('admin.srtHeadOfficeDocuments.edit', compact('refer_documents', 'operators', 'srtHeadOfficeDocument'));
    }

    public function update(UpdateSrtHeadOfficeDocumentRequest $request, SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        $data = $request->all();
        
        //Close Documents
        if($data['save_for'] == 'Closed'){
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
            $count_check_doc = SrtPdDocument::where('refer_documents_id',$SrtDocumentID)->count();
            $Contract = SrtInputDocument::where('id',$SrtDocumentID)->value('construction_contract_id');
            if($count_check_doc == 0){
                
                $dataPD = array(
                    'refer_documents_id' => $SrtDocumentID,
                    'save_for'          => 'Process',
                    'construction_contract_id' => $Contract
                );
                $InsertDataPD[] = $dataPD; 
                $srtPDDocument = SrtPdDocument::insert($InsertDataPD);
            }

        }


        $srtHeadOfficeDocument->update($request->all());
        $srtHeadOfficeDocument->operators()->sync($request->input('operators', []));

        if (count($srtHeadOfficeDocument->file_upload) > 0) {
            foreach ($srtHeadOfficeDocument->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        //Create SrtInputDocument Object
        $srtInputDocument = SrtInputDocument::find($request->refer_documents_id);
        //Link Media to file_upload_2
        $media = $srtInputDocument->file_upload_2->pluck('file_name')->toArray();

        // Complete Files
        if($data['save_for'] == "Closed"){
            $CompleteMerger = PDFMerger::init();
            $fileUpload_1 = $srtInputDocument->getMedia('file_upload');
            $CompleteMerger->addPDF(public_path($fileUpload_1[0]->getUrl()));
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
            if(count($request->input('file_upload', [])) > 0){
                $pdfMerger->merge();
                //Save to tmp
                $pdfMerger->save(storage_path('tmp/uploads/mergerPdf_02.pdf'), "file");
                //Delete Dropzone file on tmp 
                foreach ($request->input('file_upload', []) as $file) {
                    if (count($media) === 0 || !in_array($file, $media)) {
                        File::delete(storage_path('tmp/uploads/' . $file));
                    }
                }
                //Add media to SrtInputDocument Collection (file_upload_2)
                $srtInputDocument->addMedia(storage_path('tmp/uploads/mergerPdf_02.pdf'))->toMediaCollection('file_upload_2');
            }

            //Delete Function on SrtInputDocument Collection (file_upload_2)
            if (count($srtInputDocument->file_upload_2) > 0) {
                foreach ($srtInputDocument->file_upload_2 as $media) {
                    if (!in_array($media->file_name, $request->input('file_upload', []))) {
                        $media->delete();
                    }
                }
            }
        

        return redirect()->route('admin.srt-head-office-documents.index');
    }

    public function show(SrtHeadOfficeDocument $srtHeadOfficeDocument)
    {
        abort_if(Gate::denies('srt_head_office_document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtHeadOfficeDocument->load('refer_documents', 'operators', 'team');

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
