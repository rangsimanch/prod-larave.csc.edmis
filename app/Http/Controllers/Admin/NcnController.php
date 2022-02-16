<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Department;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNcnRequest;
use App\Http\Requests\StoreNcnRequest;
use App\Http\Requests\UpdateNcnRequest;
use App\Ncn;
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
        $legth_of_doc = (int)Ncn::where('construction_contract_id' ,'=' ,$data['construction_contract_id'])->count(); 
        if($legth_of_doc != 0){
            $prev_doc_code = Ncn::where('construction_contract_id' ,'=' ,$data['construction_contract_id'])->orderBy('id','desc')->limit(1)->value('document_number');
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

        foreach ($request->input('file_attachment', []) as $file) {
            $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
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
        foreach ($request->input('file_attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
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
                'default_font' => 'sarabun_new'
            ]);
          } catch (\Mpdf\MpdfException $e) {
              print "Creating an mPDF object failed with" . $e->getMessage();
          }
        // Check Report
        $dept_code = $ncn->dept_code->code ?? '';
        if($dept_code == "G"){
            $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/NCN_Thai_Form.pdf'));
            $textbox1 = "CSC's Issuer";
            $textbox2 = "CSC COS";
            $textbox3 = "CSC Project / Team Leader";
        }
        else{
            $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/NCN_Chinese_Form.pdf'));
            $textbox1 = "CSC's Issuer";
            $textbox2 = "CSC's COS";
            $textbox3 = "The Director of S&Q Dept. of CSC";
        }
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        // Setting Data
        $project_name = "(Section 1 Bangkok - Nakhon Ratchasima)";
        $contract_name = 'Contract ' . $ncn->construction_contract->code . ' : ' . $ncn->construction_contract->name;
        $document_number ="Ref No." .  $ncn->document_number;
        $subject = "Subject : " . $ncn->title ?? '';
        $description = $ncn->description ?? '';
        $issue_date = $ncn->issue_date ?? '';
        $dept_name = $ncn->dept_code->name ?? '';
        $issuer = $ncn->issue_by->name ?? '';
        $attachment_description = $ncn->attachment_description ?? '';
        $pages_of_attachment = $ncn->pages_of_attachment ?? '';
        $acceptance_date = $ncn->acceptance_date ?? '';
        $leader = $ncn->leader->name ?? '';
        $cos = $ncn->construction_specialist->name ?? '';
        $related_specialist = $ncn->related_specialist->name ?? '';

        $html = "<div style=\"font-size: 13px; text-align: center; font-weight: bold; color:#1F4E78; padding-top:25px;\">" . $project_name  . " " . $contract_name  ."</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; color:#FFFFFF; position:absolute;top:115px;left:532px;\">" . $document_number  . "</div>";
        $html .= "<div style=\"padding-right:120px; font-size: 18px; font-weight: bold; position:absolute;top:175px;left:105px;\">" . $subject  . "</div>";
        $html .= "<div style=\"padding-right:120px; font-size: 18px; position:absolute;top:180px;left:105px\">" . $description  . "</div>";
        $html .= "<div style=\"padding-right:120px;font-size: 14px; position:absolute;top:731px;left:105px\">" . $attachment_description  . "</div>";
        $html .= "<div style=\"padding-right:120px;font-size: 14px; position:absolute;top:780px;left:260px\">" . $pages_of_attachment  .  " จำนวน-แผ่น" . "</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:805px;left:153px\">" . $textbox1  . "</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:805px;left:330px\">" . $textbox2  . "</div>";
        $html .= "<div style=\"font-size: 14px; font-weight: bold; position:absolute;top:805px;left:533px\">" . $textbox3  . "</div>";
        $html .= "<div style=\"font-size: 12px; font-weight: bold; position:absolute;top:848px;left:110px\">" . $issuer  . "</div>";
        $html .= "<div style=\"font-size: 12px; font-weight: bold; position:absolute;top:848px;left:325px\">" . $cos  . "</div>";
        $html .= "<div style=\"font-size: 12px; font-weight: bold; position:absolute;top:848px;left:540\">" . $leader  . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:893px;left:125px\">" . $issue_date  . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:893px;left:340px\">" . $issue_date  . "</div>";
        $html .= "<div style=\"font-size: 12px; position:absolute;top:893px;left:555px\">" . $issue_date  . "</div>";
        $mpdf->WriteHTML($html);


        //Attachment
        // Image Attacment
        $count_image = count($ncn->description_image);
        if($count_image > 0){
            $mpdf->SetDocTemplate(public_path('pdf-asset/SWN_Template_Attachment.pdf'),true);  
            $footer_text = "<div style=\"text-align: right; font-size:18px; font-weight: bold;\">" . $document_number . "</div>";
            $mpdf->AddPage('P','','','','','','',50,55);
            $mpdf->SetHTMLFooter($footer_text);

            $html = "";   
            for($index = 0; $index < $count_image; $index++){
                try{
                    $allowed = array('gif', 'png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG');
                    if(in_array(pathinfo(public_path($ncn->description_image[$index]->getUrl()),PATHINFO_EXTENSION),$allowed)){
                                            
                        $img = (string) Image::make($ncn->description_image[$index]->getPath())->orientate()->resize(null, 180, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode('data-url');

                        $html .= "<img style=\"padding-left:90px;\" width=\"". "30%" ."\" height=\"". "30%" ."\" src=\"" 
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

        foreach($ncn->file_attachment as $attacment){ 
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
            $filename =  "NCN-" . str_replace(".","",$subject) . ".pdf";;
        }
        else{
            $filename =  "NCN_Report.pdf";
        }
        return $mpdf->Output($filename , 'I');
    }
}
