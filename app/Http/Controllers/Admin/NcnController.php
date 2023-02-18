<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Department;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNcnRequest;
use App\Http\Requests\StoreNcnRequest;
use App\Http\Requests\UpdateNcnRequest;
use App\Ncn;
use App\Ncr;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Image;


class NcnController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ncn_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ncn::with(['construction_contract', 'dept_code', 'issue_by', 'leader', 'construction_specialist', 'related_specialist', 'team'])->select(sprintf('%s.*', (new Ncn())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'ncn_show';
                $editGate = 'ncn_edit';
                $deleteGate = 'ncn_delete';
                $crudRoutePart = 'ncns';

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
                $rfi_id =  $row->id;
                $cover_sheet[] = '<a class="btn btn-default" href="' . route('admin.ncn.createReportNCN',$row->id) .'" target="_blank">
                NCN Report </a>';
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
            $table->editColumn('file_attachment', function ($row) {
                if (!$row->file_attachment) {
                    return '';
                }
                $links = [];
                foreach ($row->file_attachment as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->editColumn('documents_status', function ($row) {
                if (Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'New'){
                    return sprintf('<p style="color:#003399"><b>%s</b></p>',$row->documents_status ? Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Reply'){
                    return sprintf('<p style="color:#ff9900"><b>%s</b></p>',$row->documents_status ? Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Accepted and Closed case.'){
                    return sprintf('<p style="color:#28B463"><b>%s</b></p>',$row->documents_status ? Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Rejected and need further action.'){
                    return sprintf('<p style="color:#E74C3C"><b>%s</b></p>',$row->documents_status ? Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else{
                    return $row->documents_status ? Ncn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '';
                }
            });
            $table->addColumn('issue_by_name', function ($row) {
                return $row->issue_by ? $row->issue_by->name : '';
            });

            $table->addColumn('leader_name', function ($row) {
                return $row->leader ? $row->leader->name : '';
            });

            $table->addColumn('construction_specialist_name', function ($row) {
                return $row->construction_specialist ? $row->construction_specialist->name : '';
            });

            $table->addColumn('related_specialist_name', function ($row) {
                return $row->related_specialist ? $row->related_specialist->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'file_attachment', 'issue_by', 'leader', 'construction_specialist', 'related_specialist', 'cover_sheet', 'documents_status']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $departments            = Department::get();
        $users                  = User::get();
        $teams                  = Team::get();

        return view('admin.ncns.index', compact('construction_contracts', 'departments', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('ncn_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $dept_codes = Department::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issue_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaders = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $related_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ncns.create', compact('construction_contracts', 'construction_specialists', 'dept_codes', 'issue_bies', 'leaders', 'related_specialists'));
    }

    public function store(StoreNcnRequest $request)
    {
        $data = $request->all();
        $data['documents_status'] = '1';
        $contract_code = ConstructionContract::where('id', '=', $data['construction_contract_id'])->value('code');
        $dept_code = Department::where('id', '=', $data['dept_code_id'])->value('code');
        $submit_date = $data['issue_date'];
        $code_year = substr($submit_date,-4);
        // Year Select
        $prev_doc_code = Ncn::where('construction_contract_id' ,'=' ,$data['construction_contract_id'])->where(DB::raw('YEAR(issue_date)'), '=', $code_year)
        ->orderBy('id','desc')->limit(1)->value('document_number');
        $legth_of_doc = (int)substr(substr($prev_doc_code,-3),0,3); 
        if($legth_of_doc != 0){
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
        $data['document_number'] = $contract_code . '/CSC/NCN/' . $dept_code . ' No./' . $code_year . '-' . $doc_number;

        $ncn = Ncn::create($data);

       
        foreach ($request->input('description_image', []) as $file) {
            $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
        }


        $index = 0;
        $index_number = substr("00{$index}", -2);
        foreach ($request->input('file_attachment', []) as $file) {
            $index++;
            $index_number = substr("00{$index}", -2);
            $inputFile = storage_path('tmp/uploads/' . basename($file));
            $renameFile = storage_path('tmp/uploads/' . 'NCN' . $doc_number . '_' . $index_number . '.pdf');
            rename($inputFile, $renameFile);
            $outputFile = storage_path('tmp/uploads/' . 'Convert_' . 'NCN' . $doc_number . '_' . $index_number . '.pdf');

            // Set the Ghostscript command
            $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$outputFile $renameFile";

            // Run the Ghostscript command
            shell_exec($command);

            // Add the converted PDF file to the media collection
            $ncn->addMedia($outputFile)->toMediaCollection('file_attachment');
            // $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ncn->id]);
        }

        return redirect()->route('admin.ncns.index');
    }

    public function edit(Ncn $ncn)
    {
        abort_if(Gate::denies('ncn_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $dept_codes = Department::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issue_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaders = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $related_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ncn->load('construction_contract', 'dept_code', 'issue_by', 'leader', 'construction_specialist', 'related_specialist', 'team');

        return view('admin.ncns.edit', compact('construction_contracts', 'construction_specialists', 'dept_codes', 'issue_bies', 'leaders', 'ncn', 'related_specialists'));
    }

    public function update(UpdateNcnRequest $request, Ncn $ncn)
    {
        $ncn->update($request->all());

        if (count($ncn->description_image) > 0) {
            foreach ($ncn->description_image as $media) {
                if (!in_array($media->file_name, $request->input('description_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncn->description_image->pluck('file_name')->toArray();
        foreach ($request->input('description_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
            }
        }

        if (count($ncn->file_attachment) > 0) {
            foreach ($ncn->file_attachment as $media) {
                if (!in_array($media->file_name, $request->input('file_attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncn->file_attachment->pluck('file_name')->toArray();
        $index = 0;
        $index_number = substr("00{$index}", -2);
        foreach ($request->input('file_attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $index++;
                $index_number = substr("00{$index}", -2);
                $inputFile = storage_path('tmp/uploads/' . basename($file));
                $renameFile = storage_path('tmp/uploads/' . 'NCN' . $ncn->id . '_' . $index_number . '.pdf');
                rename($inputFile, $renameFile);
                $outputFile = storage_path('tmp/uploads/' . 'Convert_' . 'NCN' . $ncn->id . '_' . $index_number . '.pdf');

                // Set the Ghostscript command
                $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$outputFile $renameFile";

                // Run the Ghostscript command
                shell_exec($command);

                // Add the converted PDF file to the media collection
                $ncn->addMedia($outputFile)->toMediaCollection('file_attachment');
                // $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
            }
        }

        return redirect()->route('admin.ncns.index');
    }

    public function show(Ncn $ncn)
    {
        abort_if(Gate::denies('ncn_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ncn->load('construction_contract', 'dept_code', 'issue_by', 'leader', 'construction_specialist', 'related_specialist', 'team');

        return view('admin.ncns.show', compact('ncn'));
    }

    public function destroy(Ncn $ncn)
    {
        abort_if(Gate::denies('ncn_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ncn->delete();

        return back();
    }

    public function massDestroy(MassDestroyNcnRequest $request)
    {
        Ncn::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ncn_create') && Gate::denies('ncn_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Ncn();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function createReportNCN(ncn $ncn){
        try {
            $mpdf = new \Mpdf\Mpdf([
                'tempDir' =>  public_path('tmp'), 
                // 'default_font' => 'sarabun_new',
                'mode' => '+aCJK',
                "autoScriptToLang" => true,
                "autoLangToFont" => true,
            ]);
          } catch (\Mpdf\MpdfException $e) {
              print "Creating an mPDF object failed with" . $e->getMessage();
          }

        // Check Report
        $dept_code = $ncn->dept_code->code ?? '';
        $ncn_id = $ncn->id;

        if($ncn_id <= 93){
            if($dept_code == "G"){
                // $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/NCN_Thai_Form.pdf'));
                $mpdf->SetDocTemplate(public_path('pdf-asset/NCN_Thai_Form.pdf'),true);
                $textbox1 = "CSC's Issuer";
                $textbox2 = "CSC COS";
                $textbox3 = "CSC Project / Team Leader";
            }
            else{
                // $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/NCN_Chinese_Form.pdf'));
                $mpdf->SetDocTemplate(public_path('pdf-asset/NCN_Chinese_Form.pdf'),true);
                $textbox1 = "CSC's Issuer";
                $textbox2 = "CSC's COS";
                $textbox3 = "The Director of S&Q Dept. of CSC";
            }
            $mpdf->AddPage('P','','','','','','',60,130);
        
        }
        if($ncn_id > 93 && $ncn_id <= 180){
            $mpdf->SetDocTemplate(public_path('pdf-asset/NCN_New_Form.pdf'),true);
            $mpdf->AddPage('P','','','','','','',50,130);
        }
        
        if($ncn_id > 180){
            $mpdf->SetDocTemplate(public_path('pdf-asset/NCN_No_Deputy_Form.pdf'),true);
            $mpdf->AddPage('P','','','','','','',50,130);
        }
        // $tplId = $mpdf->ImportPage($pagecount);
        // $mpdf->UseTemplate($tplId);

        // Setting Data
        $project_name = "(Section 1 Bangkok - Nakhon Ratchasima)";
        $contract_code = $ncn->construction_contract->code ?? '';
        $contract_name = 'Contract ' . $ncn->construction_contract->code . ' : ' . $ncn->construction_contract->name;
        $contract_header = $project_name . " " . $contract_name;
        $document_number ="Ref No." .  $ncn->document_number;
        $subject = "Subject : " . $ncn->title ?? '';
        $description = $ncn->description ?? '';
        $issue_date = $ncn->issue_date ?? '';
        $dept_name = $ncn->dept_code->name ?? '';
        $issuer = $ncn->issue_by->name ?? '';
        $issuer_jobtitile = $ncn->issue_by->jobtitle->name ?? '';
        $attachment_description = $ncn->attachment_description ?? '';
        $pages_of_attachment = $ncn->pages_of_attachment ?? '';
        $acceptance_date = $ncn->acceptance_date ?? '';
        $leader = $ncn->leader->name ?? '';
        $leader_jobtitle = $ncn->leader->jobtitle->name ?? '';

       

        $cos = $ncn->construction_specialist->name ?? '';
        $cos_jobtitle = $ncn->construction_specialist->jobtitle->name ?? '';
        $cos_id = $ncn->construction_specialist->id ?? '';
        $related_specialist = $ncn->related_specialist->name ?? '';


        if($related_specialist == "Yan Lizhou"){
            $textbox3 = "Head of measurement Department";
        }

        if($ncn_id <= 93){
            // $header_len = strlen($contract_header);
            // $header_center = (690/2) - ($header_len * 2.5);
            // $html = "<div style=\"font-size: 9px; color:#1F4E78; position:fixed;top:55px;left:". $header_center . "\">" . $contract_header  ."</div>";
            $html = "<div style=\"font-size: 9px; font-weight: bold; color:#FFFFFF; position:absolute;top:117px;left:532px;\">" . $document_number  . "</div>";
            $html .= "<div style=\"padding-right:120px; font-size: 12px; font-weight: bold; position:absolute;top:175px;left:105px;\">" . $subject  . "</div>";
            $html .= "<div style=\"padding-right:120px;font-size: 10px; position:absolute;top:750px;left:105px\">" . $attachment_description  . "</div>";
            $html .= "<div style=\"padding-right:120px;font-size: 10px; position:absolute;top:782px;left:260px\">" . $pages_of_attachment  .  " จำนวน-แผ่น" . "</div>";
            $html .= "<div style=\"font-size: 10px; font-weight: bold; position:absolute;top:805px;left:153px\">" . $textbox1  . "</div>";
            $html .= "<div style=\"font-size: 10px; font-weight: bold; position:absolute;top:805px;left:375px\">" . $textbox2  . "</div>";
            $html .= "<div style=\"font-size: 10px; font-weight: bold; position:absolute;top:805px;left:528px\">" . $textbox3  . "</div>";
            
            if($issuer != ''){
                if(!is_null($ncn->issue_by->signature)){
                    $html .= "<div style=\"font-weight: bold; position:absolute;top:810;left:150px;\">
                    <img width=\"30%\" height=\"20%\" src=\"" . $ncn->issue_by->signature->getPath()
                    . "\"></div>";
                }
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:848px;left:110px\">" . $issuer  . "</div>";
            }
            if($cos != ''){
                if(!is_null($ncn->construction_specialist->signature)){
                    $html .= "<div style=\"font-weight: bold; position:absolute;top:810;left:365px;\">
                    <img width=\"35%\" height=\"25%\" src=\"" . $ncn->construction_specialist->signature->getPath()
                    . "\"></div>";
                }
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:848px;left:325px\">" . $cos  . "</div>";
            }
            if($leader != ''){
                if(!is_null($ncn->leader->signature)){
                    $html .= "<div style=\"font-weight: bold; position:absolute;top:810;left:585px;\">
                    <img width=\"45%\" height=\"35%\" src=\"" . $ncn->leader->signature->getPath()
                    . "\"></div>";
                }
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:848px;left:540\">" . $leader  . "</div>";
            }

            $html .= "<div style=\"font-size: 10px; position:absolute;top:893px;left:125px\">" . $issue_date  . "</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:893px;left:340px\">" . $issue_date  . "</div>";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:893px;left:555px\">" . $issue_date  . "</div>";

            $mpdf->SetHTMLHeader($html,'0',true);
            $html = "<div style=\" padding-left: 80px; padding-right:40px; padding-bottom:-15px; \">";
            $html .= "<div style=\"font-size: 12px; position:absolute;top:220px;left:105px;LINE-HEIGHT:20px;\">" . $description  . "</div>";
            $html .= "</div>";
        }
        else{
            // $header_len = strlen($contract_header);
            // $header_center = (690/2) - ($header_len * 2.5);
            // $html = "<div style=\"font-size: 9px; font-weight: bold; color:#1F4E78; position:fixed;top:45px;left:". $header_center . "\">" . $contract_header  ."</div>";
            $html = "<div style=\"font-size: 9px; font-weight: bold; color:#FFFFFF; position:absolute;top:98px;left:532px;\">" . $document_number  . "</div>";
            $html .= "<div style=\"padding-right:120px; font-size: 12px; font-weight: bold; position:absolute;top:150px;left:105px;\">" . $subject  . "</div>";
            $html .= "<div style=\"padding-right:120px;font-size: 10px; position:absolute;top:650px;left:105px\">" . $attachment_description  . "</div>";
            $html .= "<div style=\"padding-right:120px;font-size: 10px; position:absolute;top:691px;left:340px\">" . $pages_of_attachment  .  "</div>";
           
            if($issuer != ''){
                if(!is_null($ncn->issue_by->signature)){
                    $html .= "<div style=\"font-weight: bold; position:absolute;top:725;left:230px;\">
                    <img width=\"30%\" height=\"20%\" src=\"" . $ncn->issue_by->signature->getPath()
                    . "\"></div>";
                }
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:768px;left:185px\">" . $issuer  . "</div>";
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:785px;left:227px\">" . $issuer_jobtitile  . "</div>";
                $html .= "<div style=\"font-size: 10px; position:absolute;top:802px;left:227px\">" . $issue_date  . "</div>";
            }

            if($cos_id == 61){
                $cos_form_jobtitle = "Chief Engineer";
                $html .= "<div style=\"font-size: 9px;font-weight: bold;  position:absolute;top:860px;left:525\">" . $cos_form_jobtitle . "</div>";
            }
            else{
                $cos_form_jobtitle = "Deputy Chief Engineer";
                $html .= "<div style=\"font-size: 9px;font-weight: bold;  position:absolute;top:860px;left:505\">" . $cos_form_jobtitle . "</div>";
            }

            if($cos != ''){
                if(!is_null($ncn->construction_specialist->signature)){
                    $html .= "<div style=\"font-weight: bold; position:absolute;top:880;left:535px;\">
                    <img width=\"40%\" height=\"30%\" src=\"" . $ncn->construction_specialist->signature->getPath()
                    . "\"></div>";
                }
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:917px;left:480px\">" . $cos  . "</div>";
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:935px;left:525px\">" . $cos_jobtitle  . "</div>";
                $html .= "<div style=\"font-size: 10px; position:absolute;top:953px;left:525px\">" . $issue_date  . "</div>";
                
            }
            
            if($leader != ''){   
                if(!is_null($ncn->leader->signature)){
                    $html .= "<div style=\"font-weight: bold; position:absolute;top:725;left:535px;\">
                    <img width=\"45%\" height=\"35%\" src=\"" . $ncn->leader->signature->getPath()
                    . "\"></div>";
                }
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:771px;left:480\">" . $leader  . "</div>";
                $html .= "<div style=\"font-size: 10px;  position:absolute;top:790px;left:520px\">" . $leader_jobtitle . "</div>";
            }
            
            $html .= "<div style=\"font-size: 10px; position:absolute;top:808px;left:520px\">" . $issue_date  . "</div>";

            $mpdf->SetHTMLHeader($html,'0',true);
            $html = "<div style=\" padding-left: 80px; padding-right:40px; padding-bottom:-15px; \">";
            $html .= "<div style=\"font-size: 12px; position:absolute;top:220px;left:105px;LINE-HEIGHT:20px;\">" . $description  . "</div>";
            $html .= "</div>";

        }
        
        $mpdf->WriteHTML($html);
        $html = "";   
        $mpdf->SetHTMLHeader($html,'0',true);
        //Attachment
        // Image Attacment
        $count_image = count($ncn->description_image);
        if($count_image > 0){
            $mpdf->SetDocTemplate(public_path('pdf-asset/SWN_Template_Attachment.pdf'),true);  
            $footer_text = "<div style=\"text-align: right; font-size:18px; font-weight: bold;\">" . $document_number . "</div>";
            $mpdf->AddPage('P','','','','','','',50,55);
            $mpdf->SetHTMLFooter($footer_text);

            
            for($index = 0; $index < $count_image; $index++){
                try{
                    $allowed = array('gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG');
                    $url =  url($ncn->description_image[$index]->getUrl());
                    $handle = curl_init($url);
                    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
                    $response = curl_exec($handle);
                    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                    curl_close($handle);
                    if($httpCode != 404){
                        if(in_array(pathinfo(public_path($ncn->description_image[$index]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                                
                            $img = (string) Image::make($ncn->description_image[$index]->getPath())->orientate()->resize(null, 180, function ($constraint) {
                                $constraint->aspectRatio();
                            })
                            ->encode('data-url');

                            $html .= "<img style=\"padding-left:90px;\" width=\"". "30%" ."\" height=\"". "30%" ."\" src=\"" 
                                . $img
                                . "\">  ";
                        }
                    }
                }catch(Exception $e){
                    print "Creating an mPDF object failed with" . $e->getMessage();
                }
            }
            $mpdf->WriteHTML($html);
            $mpdf->SetDocTemplate("");  
        }
        $html="";
        $mpdf->SetHTMLHeader($html,'0',true);
        $mpdf->SetDocTemplate("");  

        foreach($ncn->file_attachment as $attacment){ 
            try{
                $url =  url($attacment->getUrl());
                $handle = curl_init($url);
                curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
                $response = curl_exec($handle);
                $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                curl_close($handle);
                if($httpCode != 404){
                    $pagecount = $mpdf->SetSourceFile($attacment->getPath());
                    for($page = 1; $page <= $pagecount; $page++){
                        $mpdf->AddPage();
                        $tplId = $mpdf->importPage($page);
                        $mpdf->UseTemplate($tplId);
                    }         
                }
            }catch(exeption $e){
                print "Creating an mPDF object failed with" . $e->getMessage();
            }
        }

        $ncrs = Ncr::where('corresponding_ncn_id',$ncn->id)->get();
        $mpdf->SetHTMLFooter($html,'0',true);
        foreach($ncrs as $ncr){
            foreach($ncr->file_attachment as $attachment){
                try{
                    $url = url($attachment->getUrl());
                    $handle = curl_init($url);
                    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
                    $response = curl_exec($handle);
                    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                    curl_close($handle);
                    if($httpCode != 404){
                        $pagecount = $mpdf->SetSourceFile($attachment->getPath());
                        for($page = 1; $page <= $pagecount; $page++){
                            $mpdf->AddPage();
                            $tplId = $mpdf->importPage($page);
                            $mpdf->UseTemplate($tplId);
                        }
                    }         
                }catch(exeption $e){
                    print "Creating an mPDF object failed with" . $e->getMessage();
                }
            }
        }


        if($ncn->dept_code->code ?? '' != ''){
            $filename =  "NCN-" . str_replace(".","",$subject) . ".pdf";;
        }
        else{
            $filename =  "NCN_Report.pdf";
        }
        return $mpdf->Output($filename , 'I');
    }
}
