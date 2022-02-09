<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Department;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySwnRequest;
use App\Http\Requests\StoreSwnRequest;
use App\Http\Requests\UpdateSwnRequest;
use App\Swn;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Image;


class SwnController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('swn_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Swn::with(['construction_contract', 'dept_code', 'issue_by', 'responsible', 'related_specialist', 'construction_specialist', 'leader', 'team'])->select(sprintf('%s.*', (new Swn())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'swn_show';
                $editGate = 'swn_edit';
                $deleteGate = 'swn_delete';
                $crudRoutePart = 'swns';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('cover_sheet', function ($row) {
                $cover_sheet = [];
                $swn_id =  $row->id;
                $cover_sheet[] = '<a class="btn btn-default" href="' . route('admin.swn.createReportSWN',$row->id) .'" target="_blank">
                SWN Report </a>';
                return implode(' ', $cover_sheet);
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });

            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });
            $table->editColumn('document_attachment', function ($row) {
                if (!$row->document_attachment) {
                    return '';
                }
                $links = [];
                foreach ($row->document_attachment as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('issue_by_name', function ($row) {
                return $row->issue_by ? $row->issue_by->name : '';
            });

            $table->addColumn('responsible_name', function ($row) {
                return $row->responsible ? $row->responsible->name : '';
            });

            $table->addColumn('related_specialist_name', function ($row) {
                return $row->related_specialist ? $row->related_specialist->name : '';
            });

            $table->editColumn('review_status', function ($row) {
                return $row->review_status ? Swn::REVIEW_STATUS_SELECT[$row->review_status] : '';
            });
            $table->addColumn('construction_specialist_name', function ($row) {
                return $row->construction_specialist ? $row->construction_specialist->name : '';
            });

            $table->addColumn('leader_name', function ($row) {
                return $row->leader ? $row->leader->name : '';
            });

            $table->editColumn('auditing_status', function ($row) {
                return $row->auditing_status ? Swn::AUDITING_STATUS_SELECT[$row->auditing_status] : '';
            });
            $table->editColumn('documents_status', function ($row) {
                if (Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'New'){
                    return sprintf('<p style="color:#003399"><b>%s</b></p>',$row->documents_status ? Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Reply'){
                    return sprintf('<p style="color:#ff9900"><b>%s</b></p>',$row->documents_status ? Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Review'){
                    return sprintf('<p style="color:#6600cc"><b>%s</b></p>',$row->documents_status ? Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Done'){
                    return sprintf('<p style="color:#009933"><b>%s</b></p>',$row->documents_status ? Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else{
                    return $row->documents_status ? Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '';
                }
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'document_attachment', 'issue_by', 'responsible', 'related_specialist', 'construction_specialist', 'leader', 'documents_status', 'cover_sheet', 'reply_sheet']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $departments            = Department::get();
        $users                  = User::get();
        $teams                  = Team::get();

        return view('admin.swns.index', compact('construction_contracts', 'departments', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('swn_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //Contract Check
            //Check is Admin
            if(Auth::id() != 1){
                $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            else{
                $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
        $dept_codes = Department::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issue_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $responsibles = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $related_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaders = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.swns.create', compact('construction_contracts', 'construction_specialists', 'dept_codes', 'issue_bies', 'leaders', 'related_specialists', 'responsibles'));
    }

    public function store(StoreSwnRequest $request)
    {   
        $data = $request->all();

        $contract_code = ConstructionContract::where('id', '=', $data['construction_contract_id'])->value('code');
        $dept_code = Department::where('id', '=', $data['dept_code_id'])->value('code');
        $submit_date = $data['submit_date'];
        $code_year = substr($submit_date,-4);
        $legth_of_doc = (int)Swn::where('construction_contract_id' ,'=' ,$data['construction_contract_id'])->count(); 
        if($legth_of_doc != 0){
            $prev_doc_code = Swn::where('construction_contract_id' ,'=' ,$data['construction_contract_id'])->orderBy('id','desc')->limit(1)->value('document_number');
            $prev_year = substr(substr($prev_doc_code,-8),0,4);
            if(strcmp($prev_year,$code_year)){
                $legth_of_doc = 1;
            }
            else{
                $legth_of_doc = $legth_of_doc + 1;
            }
        }
        else{
            $legth_of_doc = $legth_of_doc + 1;
        }
        $doc_number = substr("000{$legth_of_doc}", -3);
        $data['document_number'] = $contract_code . '/CSC/SWN/' . $dept_code . ' No./' . $code_year . '-' . $doc_number;

        $swn = Swn::create($data);

        foreach ($request->input('document_attachment', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('document_attachment');
        }

        foreach ($request->input('description_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
        }

        foreach ($request->input('rootcase_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
        }

        foreach ($request->input('containment_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
        }

        foreach ($request->input('corrective_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $swn->id]);
        }

        return redirect()->route('admin.swns.index');
    }

    public function edit(Swn $swn)
    {
        abort_if(Gate::denies('swn_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //Contract Check
            //Check is Admin
            if(Auth::id() != 1){
                $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            else{
                $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }

        $dept_codes = Department::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issue_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $responsibles = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $related_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaders = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $swn->load('construction_contract', 'dept_code', 'issue_by', 'responsible', 'related_specialist', 'construction_specialist', 'leader', 'team');

        return view('admin.swns.edit', compact('construction_contracts', 'construction_specialists', 'dept_codes', 'issue_bies', 'leaders', 'related_specialists', 'responsibles', 'swn'));
    }

    public function update(UpdateSwnRequest $request, Swn $swn)
    {
        $data = $request->all();
        $state = $swn->documents_status;
        
        if($state == '3'){
            $data['documents_status'] = '4';
        }
        if($state == '2'){
            $data['documents_status'] = '3';
        }
        if($state == '1'){
            $data['documents_status'] = '2';
        }    
    
        $swn->update($data);

        if (count($swn->document_attachment) > 0) {
            foreach ($swn->document_attachment as $media) {
                if (!in_array($media->file_name, $request->input('document_attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->document_attachment->pluck('file_name')->toArray();
        foreach ($request->input('document_attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('document_attachment');
            }
        }

        if (count($swn->description_image) > 0) {
            foreach ($swn->description_image as $media) {
                if (!in_array($media->file_name, $request->input('description_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->description_image->pluck('file_name')->toArray();
        foreach ($request->input('description_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
            }
        }

        if (count($swn->rootcase_image) > 0) {
            foreach ($swn->rootcase_image as $media) {
                if (!in_array($media->file_name, $request->input('rootcase_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->rootcase_image->pluck('file_name')->toArray();
        foreach ($request->input('rootcase_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
            }
        }

        if (count($swn->containment_image) > 0) {
            foreach ($swn->containment_image as $media) {
                if (!in_array($media->file_name, $request->input('containment_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->containment_image->pluck('file_name')->toArray();
        foreach ($request->input('containment_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
            }
        }

        if (count($swn->corrective_image) > 0) {
            foreach ($swn->corrective_image as $media) {
                if (!in_array($media->file_name, $request->input('corrective_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->corrective_image->pluck('file_name')->toArray();
        foreach ($request->input('corrective_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
            }
        }

        return redirect()->route('admin.swns.index');
    }

    public function show(Swn $swn)
    {
        abort_if(Gate::denies('swn_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $swn->load('construction_contract', 'dept_code', 'issue_by', 'responsible', 'related_specialist', 'construction_specialist', 'leader', 'team');

        return view('admin.swns.show', compact('swn'));
    }

    public function destroy(Swn $swn)
    {
        abort_if(Gate::denies('swn_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $swn->delete();

        return back();
    }

    public function massDestroy(MassDestroySwnRequest $request)
    {
        Swn::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('swn_create') && Gate::denies('swn_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Swn();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
    
    public function createReportSWN(Swn $swn){
        try {
            $mpdf = new \Mpdf\Mpdf([
                'tempDir' =>  public_path('tmp'), 
                'default_font' => 'sarabun_new'
            ]);
          } catch (\Mpdf\MpdfException $e) {
              print "Creating an mPDF object failed with" . $e->getMessage();
          }
        $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/SWN_Template_Section_1.pdf'));
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);      
        // Setting Data
        $contract_name = 'Contract ' . $swn->construction_contract->code . ' : ' . $swn->construction_contract->name;
        $send_to = "Civil's Project Manager";
        $submit_date = $swn->submit_date ?? '';
        $review_date = $swn->review_date ?? '';
        $auditing_date = $swn->auditing_date ?? '';
        $document_number = 'Ref No.' . $swn->document_number;
        $subject = $swn->title ?? '';
        $location = $swn->location ?? '';
        $reply_ncr = $swn->reply_ncr ?? '';
        $ref_doc = $swn->ref_doc ?? '';
        $description = $swn->description ?? '';
        $issuer_name = $swn->issue_by->name ?? '';
        $issuer_position = $swn->issue_by->jobtitle->name ?? '';
        $qa_name = $swn->related_specialist->name ?? '';
        $cos_name = $swn->leader->name ?? '';
        $review_status = $swn->review_status ?? '';
        $auditing_status = $swn->auditing_status ?? '';

        $html = "<div style=\"font-size: 16px; font-weight: bold; color:#1F4E78; position:absolute;top:141px;left:140px;\">" . $contract_name  . "</div>";
        $html .= "<div style=\"font-size: 18px; position:absolute;top:190px;left:120px;\">" . $send_to  . "</div>";
        $html .= "<div style=\"font-size: 18px; position:absolute;top:190px;left:370px;\">" . $submit_date  . "</div>";
        $html .= "<div style=\"font-size: 16px; position:absolute;top:190px;left:520px;\">" . $document_number  . "</div>";
        $html .= "<div style=\"font-size: 18px; position:absolute;top:238px;left:200px;\">" . $subject  . "</div>";
        $html .= "<div style=\"font-size: 18px; position:absolute;top:261px;left:145px;\">" . $location  . "</div>";
        
        if($reply_ncr == "Yes"){
            $html .= "<div style=\"font-size: 18px;font-weight: bold; position:absolute;top:260px;left:572px;\">" . "X"  . "</div>";
        }
        if($reply_ncr == "No"){
            $html .= "<div style=\"font-size: 18px;font-weight: bold; position:absolute;top:260px;left:632px;\">" . "X"  . "</div>";
        }
        $html .= "<div style=\"font-size: 18px; font-weight: bold; position:absolute;top:287px;left:230px;\"> : </div>";
        $html .= "<div style=\"font-size: 16px; padding-right:90px; position:absolute;top:270px;left:235px;\">" . $ref_doc  . "</div>";
        $html .= "<div style=\"font-size: 18px; font-weight: bold; padding-right:80px; position:absolute;top:335px;left:100px;\">" . "Description(Content details):"  . "</div>";
        $html .= "<div style=\"font-size: 16px; padding-right:80px; position:absolute;top:338px;left:120px;\">" . $description  . "</div>";
       
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:606px;left:130px;\">( " . $issuer_name  . " )</div>";
        // $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:620px;left:135px;\">" . $issuer_position  . "</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:606px;left:340px;\">( " . $qa_name  . " )</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:606px;left:592px;\">( " . $cos_name  . " )</div>";
        
        $html .= "<div style=\"font-size: 14px; position:absolute;top:643px;left:137px;\">" . $submit_date  . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:643px;left:345px;\">" . $submit_date  . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:643px;left:599px;\">" . $submit_date  . "</div>";

        // Review Section
        if($review_status == "1"){
            $html .= "<div style=\"font-size: 18px; font-weight: bold; position:absolute;top:715px;left:104px;\">" . "X"  . "</div>";
        }
        if($review_status == "2"){
            $html .= "<div style=\"font-size: 18px; font-weight: bold; position:absolute;top:715px;left:214px;\">" . "X"  . "</div>";
        }
        if($review_status == "3"){
            $html .= "<div style=\"font-size: 18px; font-weight: bold; position:absolute;top:715px;left:334px;\">" . "X"  . "</div>";
        }

        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:810px;left:147px;\">( " . $issuer_name  . " )</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:810px;left:583px;\">( " . $qa_name  . " )</div>";

        $html .= "<div style=\"font-size: 14px; position:absolute;top:835px;left:160px;\">" . $review_date  . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:835px;left:605px;\">" . $review_date  . "</div>";

        if($auditing_status == "1"){
            $html .= "<div style=\"font-size: 18px; font-weight: bold; position:absolute;top:885px;left:104px;\">" . "X"  . "</div>";
        }
        if($auditing_status == "2"){
            $html .= "<div style=\"font-size: 18px; font-weight: bold; position:absolute;top:910px;left:104px;\">" . "X"  . "</div>";
        }

        // Auditing Section
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:960px;left:150px;\">( " . $issuer_name  . " )</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:960px;left:345px;\">( " . $qa_name  . " )</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:960px;left:583px;\">( " . $cos_name  . " )</div>";

        $html .= "<div style=\"font-size: 14px; position:absolute;top:985px;left:160px;\">" . $auditing_date  . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:985px;left:360px;\">" . $auditing_date  . "</div>";
        $html .= "<div style=\"font-size: 14px; position:absolute;top:985px;left:600px;\">" . $auditing_date  . "</div>";
        $mpdf->WriteHTML($html);
        

        // Image Attacment
        $count_image = count($swn->description_image);
        if($count_image > 0){
            $mpdf->SetDocTemplate(public_path('pdf-asset/SWN_Template_Attachment.pdf'),true);  
            $mpdf->AddPage('P','','','','',35,'',80,55);
             
            $html = "<div style=\"font-size: 16px; font-weight: bold; color:#1F4E78; position:absolute;top:139px;left:368px;\">" . $contract_name  . "</div>";
            $html .= "<div style=\"font-size: 18px; position:absolute;top:190px;left:120px;\">" . $send_to  . "</div>";
            $html .= "<div style=\"font-size: 18px; position:absolute;top:190px;left:370px;\">" . $submit_date  . "</div>";
            $html .= "<div style=\"font-size: 16px; position:absolute;top:190px;left:520px;\">" . $document_number  . "</div>";
            for($index = 0; $index < $count_image; $index++){
                try{
                    $allowed = array('gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG');
                    if(in_array(pathinfo(public_path($swn->description_image[$index]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                            
                        $img = (string) Image::make($swn->description_image[$index]->getPath())->orientate()->resize(null, 180, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode('data-url');

                        $html .= "<img width=\"". "30%" ."\" height=\"". "30%" ."\" src=\"" 
                            . $img
                            . "\">  ";
                    }
                }catch(Exception $e){
                    print "Creating an mPDF object failed with" . $e->getMessage();
                }
            }
            $mpdf->WriteHTML($html);
            $mpdf->SetDocTemplate("");  
        }

        foreach($swn->document_attachment as $attacment){ 
            try{
                $pagecount = $mpdf->SetSourceFile($attacment->getPath());
                for($page = 1; $page <= $pagecount; $page++){
                    $mpdf->AddPage();
                    $tplId = $mpdf->importPage($page);
                    $mpdf->UseTemplate($tplId);

                }         
            }catch(exeption $e){
                print "Creating an mPDF object failed with" . $e->getMessage();
            }
        }
        
        if($swn->dept_code->code ?? '' != ''){
            $filename =  "SWN-" . $swn->dept_code->code  . substr($document_number,-8);
        }
        else{
            $filename =  "SWN-" . $submit_date  . substr($document_number,-8);

        }
        // Output a PDF file directly to the browser
        return $mpdf->Output($filename,"I");
    }
}
