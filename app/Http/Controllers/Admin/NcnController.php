<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Department;
use Carbon\Carbon;
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

        $is_central = !empty($data['is_central']) && in_array((string) $data['is_central'], ['1', 'true', true], true) ? 1 : 0;
        $data['is_central'] = $is_central;

        // Year Select
        $prev_doc_code = Ncn::where('construction_contract_id' ,'=' ,$data['construction_contract_id'])
            ->where('is_central', $is_central)
            ->where(DB::raw('YEAR(issue_date)'), '=', $code_year)
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
        $prefix = $is_central ? '(Z)' : '';
        $data['document_number'] = $prefix . $contract_code . '/CSC/NCN/' . $dept_code . ' No./' . $code_year . '-' . $doc_number;

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

            // Check if conversion succeeded, otherwise use original file
            if (file_exists($outputFile)) {
                $ncn->addMedia($outputFile)->toMediaCollection('file_attachment');
            } else {
                $ncn->addMedia($renameFile)->toMediaCollection('file_attachment');
            }
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

                // Check if conversion succeeded, otherwise use original file
                if (file_exists($outputFile)) {
                    $ncn->addMedia($outputFile)->toMediaCollection('file_attachment');
                } else {
                    $ncn->addMedia($renameFile)->toMediaCollection('file_attachment');
                }
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

        // New form layout for NCNs created between 26/07/2026 and 30/08/2026
        $startDate = Carbon::createFromFormat('d/m/Y', '26/07/2026')->startOfDay();
        $endDate = Carbon::createFromFormat('d/m/Y', '30/08/2026')->endOfDay();
        $ncnCreated = $ncn->created_at ? Carbon::instance($ncn->created_at) : null;
        if ($ncnCreated && $ncnCreated->greaterThanOrEqualTo($startDate) && $ncnCreated->lessThanOrEqualTo($endDate)) {
            return $this->createReportNCNNewForm($ncn);
        }

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
            $mpdf->AddPage('P','','','','','','',50,143);
        }
        // $tplId = $mpdf->ImportPage($pagecount);
        // $mpdf->UseTemplate($tplId);

        // Setting Data
        $project_name = "(Section 1 Bangkok - Nakhon Ratchasima)";
        $contract_code = $ncn->construction_contract->code ?? '';
        $contract_name = 'Contract ' . $ncn->construction_contract->code . ' : ' . $ncn->construction_contract->name;
        $contract_header = $project_name . " " . $contract_name;
        $document_number ="No." .  $ncn->document_number;
        $subject = $ncn->title ?? '';
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
            $html .= "<div style=\"padding-right:120px; font-size: 12px; font-weight: bold; position:absolute;top:175px;left:105px;\">" . $subject  .  "</div>>";
            $html .= "<div style=\"padding-right:120px;font-size: 10px; position:absolute;top:70px;left:105px\">" . $attachment_description  . "</div>";
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
            $html = "<div style=\" padding-left: 80px; padding-right:40px; padding-bottom:-10px; \">";
            $html .= "<div style=\"font-size: 10px; position:absolute;top:220px;left:105px;LINE-HEIGHT:20px;\">" . $description  . "</div>";
            $html .= "</div>";
        }
        else{
            // $header_len = strlen($contract_header);
            // $header_center = (690/2) - ($header_len * 2.5);
            // $html = "<div style=\"font-size: 9px; font-weight: bold; color:#1F4E78; position:fixed;top:45px;left:". $header_center . "\">" . $contract_header  ."</div>";
            $html = "<div style=\"font-size: 9px; font-weight: bold; color:#FFFFFF; position:absolute;top:98px;left:532px;\">" . $document_number  . "</div>";
            $html .= "<div style=\"padding-right:120px; font-size: 10px; font-weight: bold; position:absolute;top:150px;left:105px;\">" . $subject  . "</div>";
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
            $cos_form_jobtitle = "";
            $html .= "<div style=\"font-size: 9px;font-weight: bold;  position:absolute;top:860px;left:525\">" . $cos_form_jobtitle . "</div>";
            if($cos_id == 61){
                $cos_form_jobtitle = "Chief Engineer";
                $html .= "<div style=\"font-size: 9px;font-weight: bold;  position:absolute;top:860px;left:525\">" . $cos_form_jobtitle . "</div>";
            }
            if($cos_id == 38){
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
            $html = "<div style=\"padding-left: 80px; padding-right:40px; padding-bottom:-10px;  \">";
            $html .= "<div style=\"font-size: 10px;  position:absolute;top:220px;left:105px;LINE-HEIGHT:20px;\">" . $description  . "</div>";
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

            // Each image is written via a temp-file path (NOT a base64
            // data URL) so the HTML string stays tiny. Concatenating many
            // base64-encoded images into one WriteHTML() call exceeds
            // pcre.backtrack_limit (1,000,000) and triggers:
            //   "The HTML code size is larger than pcre.backtrack_limit"
            $allowed = ['gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG'];
            // Use storage/app/tmp (NOT public/tmp) so temp image files are
            // never web-accessible. Each call gets a unique subdir via
            // uniqid() so concurrent requests cannot collide on or delete
            // each other's temp files.
            $tmpDir = storage_path('app/tmp/ncn_' . ($ncn->id ?? 'x') . '_' . uniqid('', true));
            if (!is_dir($tmpDir)) {
                @mkdir($tmpDir, 0755, true);
            }
            // Pass 1: resize each image to a temp file and collect paths.
            // Temp files are kept until after the single WriteHTML() call
            // below so mPDF can read them by path during rendering.
            $imagePaths = [];
            for ($index = 0; $index < $count_image; $index++){
                try{
                    $path = $ncn->description_image[$index]->getPath();
                    if (!file_exists($path)) {
                        continue;
                    }
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowed)) {
                        continue;
                    }
                    // Resize via Intervention, save to a temp file so mPDF
                    // can read it by path. This keeps the <img> tag string
                    // tiny (~80 chars) instead of a ~300KB base64 blob.
                    $tmpFile = $tmpDir . '/img_' . $index . '.' . $ext;
                    Image::make($path)->orientate()->resize(null, 180, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($tmpFile, 80);
                    $imagePaths[] = $tmpFile;
                }catch(\Exception $e){
                    \Log::error('NCN image embed failed: ' . $e->getMessage());
                }
            }

            // Pass 2: build a 2-column table layout and write it in a single
            // WriteHTML() call. Using <table> gives reliable 2-per-row layout
            // in mPDF (float/flex support is limited). The HTML string stays
            // tiny because src is a file path, not a base64 blob — even 100
            // images = ~10KB, far under pcre.backtrack_limit.
            if (!empty($imagePaths)) {
                $html = '<table style="width:100%; border:none; border-collapse:collapse; padding:0 40px;">';
                $cols = 2;
                $total = count($imagePaths);
                for ($i = 0; $i < $total; $i += $cols) {
                    $html .= '<tr>';
                    for ($c = 0; $c < $cols; $c++) {
                        $idx = $i + $c;
                        $html .= '<td style="width:50%; text-align:center; padding:10px 5px; border:none;">';
                        if (isset($imagePaths[$idx])) {
                            $html .= '<img style="padding-top:10px;" width="30%" src="' . $imagePaths[$idx] . '">';
                        }
                        $html .= '</td>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</table>';
                $mpdf->WriteHTML($html);
            }

            // Pass 3: clean up temp files now that mPDF has embedded them.
            foreach ($imagePaths as $tmpFile) {
                if (file_exists($tmpFile)) {
                    @unlink($tmpFile);
                }
            }
            // Clean up the per-call temp subdir (empty after file deletion).
            @rmdir($tmpDir);

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
                        $tplId = $mpdf->importPage($page);
                        $size = $mpdf->getTemplateSize($tplId);
                        $mpdf->AddPage($size['orientation']);
                        $mpdf->UseTemplate($tplId, 0, 0, $size['width'], $size['height'], true);
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

    /**
     * New NCN report layout for NCNs created after 27/07/2026.
     * Uses NCN_form_1page.pdf when description fits on a single page,
     * and NCN_form_multipage.pdf for overflow pages, with the 1-page form
     * used again as the final page. Same approach as createReportSWNNewForm.
     */
    protected function createReportNCNNewForm(Ncn $ncn)
    {
        $mpdfConfig = [
            'tempDir'             => public_path('tmp'),
            'mode'                => '+aCJK',
            'autoScriptToLang'    => true,
            'autoLangToFont'      => true,
            'allow_charset_conversion' => true,
            'charset_in'          => 'UTF-8',
        ];

        $mpdf = new \Mpdf\Mpdf($mpdfConfig);

        // Setting Data (same fields as the legacy layout)
        $project_name          = '(Section 1 Bangkok - Nakhon Ratchasima)';
        $contract_code         = $ncn->construction_contract->code ?? '';
        $contract_name         = 'Contract ' . ($ncn->construction_contract->code ?? '') . ' : ' . ($ncn->construction_contract->name ?? '');
        $document_number       = 'No.' . $ncn->document_number;
        $subject               = ($ncn->title ?? '');
        $description           = $ncn->description ?? '';
        $issue_date            = $ncn->issue_date ?? '';
        $attachment_description = $ncn->attachment_description ?? '';
        $pages_of_attachment   = $ncn->pages_of_attachment ?? '';
        $issuer                = $ncn->issue_by->name ?? '';
        $issuer_jobtitle       = $ncn->issue_by->jobtitle->name ?? '';
        $leader                = $ncn->leader->name ?? '';
        $leader_jobtitle       = $ncn->leader->jobtitle->name ?? '';
        $cos                   = $ncn->construction_specialist->name ?? '';
        $cos_jobtitle          = $ncn->construction_specialist->jobtitle->name ?? '';
        $cos_id                = $ncn->construction_specialist->id ?? '';

        // Send-to (constructor name) per construction contract — mirrors the
        // SwnController createReportSWNNewForm contract mapping.
        $send_to = "Project Manager";
        if ($contract_code == "C4-3") {
            $send_to = 'CAN Joint Venture';
        } elseif ($contract_code == "C4-4") {
            $send_to = 'Italian-Thai Development PCL.';
        } elseif ($contract_code == "C4-2") {
            $send_to = 'Unique Engineering and Construction Public Company Limited';
        } elseif ($contract_code == "C4-6") {
            $send_to = 'Unique Engineering and Construction Public Company Limited';
        } elseif ($contract_code == "C4-7") {
            $send_to = 'Civil Enginneering Public Company Limited';
        } elseif ($contract_code == "C2-1") {
            $send_to = 'Civil Construction Services & Products Company Limited';
        } elseif ($contract_code == "C3-1") {
            $send_to = 'ITD-CREC No.10 Joint Venture';
        } elseif ($contract_code == "C3-2") {
            $send_to = 'Nawarat Patanakarn Public Company Limited';
        } elseif ($contract_code == "C3-3") {
            $send_to = 'Thai Engineers & Industry Company Limited';
        } elseif ($contract_code == "C3-4") {
            $send_to = 'Italian-Thai Development PCL.';
        } elseif ($contract_code == "C3-5") {
            $send_to = 'SPTK Joint Venture Company Limited';
        }

        // Header data block (absolute-positioned, same coordinates as SWN new form)
        $html  = "<div style=\"font-size: 10px; text-align: center; font-weight: bold; position:absolute;top:105px;left:95px;\">" . $contract_name . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:129px;left:145px;\">" . $send_to . "</div>";
        $html .= "<div style=\"font-size: 10px; position:absolute;top:105px;left:525px; font-weight: bold;\">" . $document_number . "</div>";
        $html .= "<div style=\"font-size: 10px; padding-right:70px; position:absolute;top:156px;left:165px;\">" . $subject . "</div>";
        $html .= "<div style=\"padding-right:120px;font-size: 10px; position:absolute;top:650px;left:105px\">" . $attachment_description . "</div>";
        $html .= "<div style=\"padding-right:120px;font-size: 10px; position:absolute;top:691px;left:340px\">" . $pages_of_attachment . "</div>";


        // Build description images HTML (multiple images supported). Images
        // go on a separate attachment page (same approach as SWN new form).
        $imagesHtml = '';
        $count_image = count($ncn->description_image);
        if ($count_image > 0) {
            for ($index = 0; $index < $count_image; $index++) {
                try {
                    $allowed = ['gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG'];
                    $path = $ncn->description_image[$index]->getPath();
                    if (file_exists($path)) {
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        if (in_array($ext, $allowed)) {
                            $img = (string) Image::make($path)->orientate()->resize(null, 180, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode('data-url');
                            $imagesHtml .= "<img style=\"padding-left:90px; padding-top:10px;\" width=\"30%\" height=\"30%\" src=\"" . $img . "\">  ";
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('NCN image embed failed: ' . $e->getMessage());
                }
            }
        }

        // Description block — text only. Images go on a separate attachment page.
        $des  = "<div style=\" padding-left: 80px; padding-right:40px; padding-bottom:-15px; \">";
        $des .= "<div style=\"font-size: 10px; padding-right:20px; position:absolute;top:330px;left:110px;LINE-HEIGHT:15px;\">" . $description . "</div>";
        $des .= "</div>";

        // Two-pass layout (same as createReportSWNNewForm):
        //  Pass 1 — try the 1-page form, measure overflow.
        //  Pass 2 — if overflow, rebuild with multipage form for non-last
        //           pages and 1page form for the last content page.
        $mpdf->SetDocTemplate(public_path('pdf-asset/NCN_form_1page.pdf'), true);
        $mpdf->AddPage('P', '', '', '', '', '', '', 70, 70);
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLHeader($html, '0', true);

        $pageBefore = $mpdf->page;
        $mpdf->WriteHTML($des);
        $pageAfter = $mpdf->page;
        $mpdf->SetHTMLHeader('', '0', true);

        if ($pageAfter > $pageBefore) {
            $totalPages = $pageAfter;

            $mpdf = new class($mpdfConfig) extends \Mpdf\Mpdf {
                public $switchAtPage = null;
                public $switchTemplate = null;
                public $switched = false;

                public function AddPage(
                    $orientation = '',
                    $condition = '',
                    $resetpagenum = '',
                    $pagenumstyle = '',
                    $suppress = '',
                    $mgl = '',
                    $mgr = '',
                    $mgt = '',
                    $mgb = '',
                    $mgh = '',
                    $mgf = '',
                    $ohname = '',
                    $ehname = '',
                    $ofname = '',
                    $efname = '',
                    $ohvalue = 0,
                    $ehvalue = 0,
                    $ofvalue = 0,
                    $efvalue = 0,
                    $pagesel = '',
                    $newformat = ''
                ) {
                    if (!$this->switched
                        && $this->switchAtPage !== null
                        && ($this->page + 1) >= $this->switchAtPage) {
                        $this->SetDocTemplate($this->switchTemplate, true);
                        $this->switched = true;
                    }
                    parent::AddPage(
                        $orientation,
                        $condition,
                        $resetpagenum,
                        $pagenumstyle,
                        $suppress,
                        $mgl,
                        $mgr,
                        $mgt,
                        $mgb,
                        $mgh,
                        $mgf,
                        $ohname,
                        $ehname,
                        $ofname,
                        $efname,
                        $ohvalue,
                        $ehvalue,
                        $ofvalue,
                        $efvalue,
                        $pagesel,
                        $newformat
                    );
                }
            };
            $mpdf->switchAtPage = $totalPages;
            $mpdf->switchTemplate = public_path('pdf-asset/NCN_form_1page.pdf');

            $mpdf->SetDocTemplate(public_path('pdf-asset/NCN_form_multipage.pdf'), true);
            $mpdf->AddPage('P', '', '', '', '', '', '', 70, 70);
            $mpdf->WriteHTML($html);
            $mpdf->SetHTMLHeader($html, '0', true);
            $mpdf->WriteHTML($des);
            $mpdf->SetHTMLHeader('', '0', true);
        }

        // Image Attachment — dedicated attachment page, images in normal flow.
        if ($imagesHtml !== '') {
            $mpdf->SetDocTemplate(public_path('pdf-asset/SWN_Template_Attachment.pdf'), true);
            $mpdf->AddPage('P', '', '', '', '', '', '', 50, 55);
            $mpdf->WriteHTML("<div style=\"position:absolute;bottom:20px;right:30px;font-size:10px;font-weight:bold;\">" . $document_number . "</div>");
            $mpdf->WriteHTML($imagesHtml);
        }

        // Attachments — merge attached PDFs (file_attachment collection).
        $mpdf->SetDocTemplate('');
        foreach ($ncn->file_attachment as $attachment) {
            try {
                $path = $attachment->getPath();
                if (file_exists($path)) {
                    $pagecount = $mpdf->SetSourceFile($path);
                    for ($page = 1; $page <= $pagecount; $page++) {
                        $tplId = $mpdf->importPage($page);
                        $size = $mpdf->getTemplateSize($tplId);
                        $mpdf->AddPage($size['orientation']);
                        $mpdf->UseTemplate($tplId, 0, 0, $size['width'], $size['height'], true);
                    }
                }
            } catch (\Exception $e) {
                print "Creating an mPDF object failed with" . $e->getMessage();
            }
        }

        // Linked NCRs — merge their file_attachment PDFs (same as legacy).
        $ncrs = Ncr::where('corresponding_ncn_id', $ncn->id)->get();
        foreach ($ncrs as $ncr) {
            foreach ($ncr->file_attachment as $attachment) {
                try {
                    $path = $attachment->getPath();
                    if (file_exists($path)) {
                        $pagecount = $mpdf->SetSourceFile($path);
                        for ($page = 1; $page <= $pagecount; $page++) {
                            $tplId = $mpdf->importPage($page);
                            $size = $mpdf->getTemplateSize($tplId);
                            $mpdf->AddPage($size['orientation']);
                            $mpdf->UseTemplate($tplId, 0, 0, $size['width'], $size['height'], true);
                        }
                    }
                } catch (\Exception $e) {
                    print "Creating an mPDF object failed with" . $e->getMessage();
                }
            }
        }

        $filename = "NCN-" . str_replace(".", "", $subject) . ".pdf";
        return $mpdf->Output($filename, 'I');
    }
}
