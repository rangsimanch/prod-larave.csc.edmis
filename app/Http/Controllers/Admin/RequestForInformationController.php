<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRequestForInformationRequest;
use App\Http\Requests\StoreRequestForInformationRequest;
use App\Http\Requests\UpdateRequestForInformationRequest;
use App\RequestForInformation;
use App\Team;
use App\User;
use App\Rfatype;
use App\Wbslevelfour;
use App\WbsLevelThree;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RequestForInformationController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('request_for_information_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RequestForInformation::with(['construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'team'])->select(sprintf('%s.*', (new RequestForInformation)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'request_for_information_show';
                $editGate      = 'request_for_information_edit';
                $deleteGate    = 'request_for_information_delete';
                $crudRoutePart = 'request-for-informations';

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
                $cover_sheet[] = '<a class="btn btn-default" href="' . route('admin.request-for-informations.createReportRFI',$row->id) .'" target="_blank">
                Cover Sheet </a>';
                return implode(' ', $cover_sheet);
            });

            $table->editColumn('document_status', function ($row) {
                $status = $row->document_status ? RequestForInformation::DOCUMENT_STATUS_SELECT[$row->document_status] : '';
                 if ($status == 'New'){
                        return sprintf('<p style="color:#003399"><b>%s</b></p>',$status);
                    }
                    else if($status == 'Refer to SRT'){
                        return sprintf('<p style="color:#ff9900"><b>%s</b></p>',$status);
                    }
                    else if($status == 'Done (SRT Reviewed)'){
                        return sprintf('<p style="color:#009933"><b>%s</b></p>',$status);
                    }
                    else if($status == 'Done (CSC Reviewed)'){
                        return sprintf('<p style="color:#009933"><b>%s</b></p>',$status);
                    }
                    else{
                        return $row->document_status ? RequestForInformation::DOCUMENT_STATUS_SELECT[$row->document_status] : '';
                    }
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->editColumn('document_no', function ($row) {
                return $row->document_no ? $row->document_no : "";
            });
            $table->editColumn('originator_code', function ($row) {
                return $row->originator_code ? $row->originator_code : "";
            });

            $table->addColumn('to_code', function ($row) {
                return $row->to ? $row->to->code : '';
            });

            $table->addColumn('wbs_level_4_wbs_level_3_name', function ($row) {
                return $row->wbs_level_4 ? $row->wbs_level_4->wbs_level_3_name : '';
            });

            $table->addColumn('wbs_level_5_wbs_level_4_name', function ($row) {
                return $row->wbs_level_5 ? $row->wbs_level_5->wbs_level_4_name : '';
            });

            $table->editColumn('originator_name', function ($row) {
                return $row->originator_name ? $row->originator_name : "";
            });
            $table->editColumn('incoming_no', function ($row) {
                return $row->incoming_no ? $row->incoming_no : "";
            });

            $table->editColumn('attachment_files', function ($row) {
                if (!$row->attachment_files) {
                    return '';
                }

                $links = [];

                foreach ($row->attachment_files as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('request_by_name', function ($row) {
                return $row->request_by ? $row->request_by->name : '';
            });

            $table->editColumn('outgoing_no', function ($row) {
                return $row->outgoing_no ? $row->outgoing_no : "";
            });
            $table->addColumn('authorised_rep_name', function ($row) {
                return $row->authorised_rep ? $row->authorised_rep->name : '';
            });

            $table->addColumn('response_organization_code', function ($row) {
                return $row->response_organization ? $row->response_organization->code : '';
            });

            $table->editColumn('response_date', function ($row) {
                return $row->response_date ? $row->response_date : "";
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

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'attachment_files', 'request_by', 'authorised_rep', 'response_organization', 'file_upload', 'document_status','cover_sheet']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();
        $wbs_level_threes       = WbsLevelThree::get();
        $wbslevelfours          = Wbslevelfour::get();
        $users                  = User::get();

        return view('admin.requestForInformations.index', compact('construction_contracts', 'teams', 'wbs_level_threes', 'wbslevelfours', 'users'));
    }

    function selectWBS(Request $request){
        $id = $request->get('select');
        $result = array();
        $query = DB::table('wbs_level_threes')
        ->join('wbslevelfours','wbs_level_threes.id','=','wbslevelfours.wbs_level_three_id')
        ->select('wbslevelfours.wbs_level_4_name','wbslevelfours.id')
        ->where('wbs_level_threes.id',$id)
        ->groupBy('wbslevelfours.wbs_level_4_name','wbslevelfours.id')
        ->orderBy('wbs_level_4_name')
        ->get();
        $output = '<option value="">' . trans('global.pleaseSelect') . '</option>';
        foreach ($query as $row){
            $output .= '<option value="'. $row->id .'">'. $row->wbs_level_4_name .'</option>';
        }
        echo $output;
    }


    public function create()
    {
        abort_if(Gate::denies('request_for_information_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id');
        }
        else{
            $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $tos = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_4s = WbsLevelThree::all()->pluck('wbs_level_3_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $wbs_level_5s = Wbslevelfour::all()->pluck('wbs_level_4_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $document_types = Rfatype::all()->pluck('type_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $request_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('admin.requestForInformations.create', compact('construction_contracts', 'tos', 'wbs_level_4s', 'wbs_level_5s', 'request_bies', 'document_types'));
    }

    public function store(StoreRequestForInformationRequest $request)
    {

        $data = $request->all();

        $data['document_status'] = 1;
        $data['to_organization'] = 'Consortium of CRIC and CRDC';
        $data['attention_name'] = 'ผู้จัดการโครงการ/Project Manager (CSC)';

        $cur_date = date("ymd");
        $code_year = substr($cur_date,0,2);
        $code_mouth = substr($cur_date,2,2);
        $code_date = $code_year . "-" . $code_mouth;

        //WBS3,4 Code
        $wbs4code = WbsLevelThree::where('id','=',$request->wbs_level_4_id)->value('wbs_level_3_code');
        $wbs5code = Wbslevelfour::where('id','=',$request->wbs_level_5_id)->value('wbs_level_4_code');
            //Type Doc. Code
        $typecode = Rfatype::where('id','=',$request->document_type_id)->value('type_code');
            //ConstructionContart
        $const_code = ConstructionContract::where('id','=',$request->construction_contract_id)->value('code');

        $data['incoming_date'] = $request->date;
        $data['document_no'] = 'HSR1/'. $const_code . '/' . 'RFI' . '/' 
                                . $wbs4code . '/' . $wbs5code
                                . '/' . $typecode . '/' . $code_date . '/' . substr($data['originator_code'],-4);
        
        $data['incoming_no'] = 'IN-' . $data['originator_code'];

        $rfi_code = 'RFI/' . $const_code . '/' . $data['originator_code'];
        $data['originator_code'] = $rfi_code;

         //WBS3,4 Name
         $wbs4name = WbsLevelThree::where('id','=',$request->wbs_level_4_id)->value('wbs_level_3_name');
         $wbs5name = Wbslevelfour::where('id','=',$request->wbs_level_5_id)->value('wbs_level_4_name');
                         
        if($request->wbs_level_4_id != ''){
            $data['discipline'] = '1.' . $wbs4name;
            if($request->wbs_level_5_id != ''){
                $data['discipline'] = $data['discipline'] . ' 2.' . $wbs5name;
            }
        }

        $requestForInformation = RequestForInformation::create($data);

        foreach ($request->input('attachment_files', []) as $file) {
            $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment_files');
        }

        foreach ($request->input('file_upload', []) as $file) {
            $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $requestForInformation->id]);
        }

        return redirect()->route('admin.request-for-informations.index');
    }

    public function edit(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $authorised_reps = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $response_organizations = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $requestForInformation->load('construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'document_type', 'team');

        return view('admin.requestForInformations.edit', compact('authorised_reps', 'response_organizations', 'requestForInformation'));
    }

    public function update(UpdateRequestForInformationRequest $request, RequestForInformation $requestForInformation)
    {
        $data = $request->all();

        $data['incoming_no'] = 'IN-' . $requestForInformation->originator_code;
        $data['outgoing_no'] = 'OUT-' . $requestForInformation->originator_code;

            if($request->save_for == 'Save and Close'){
                $data['document_status'] = 2;
            }
            else{
                $data['document_status'] = 3;
            }
        
        $requestForInformation->update($data);
        
        $media = $requestForInformation->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $requestForInformation->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.request-for-informations.index');
    }

    public function show(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInformation->load('construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'document_type', 'team');

        return view('admin.requestForInformations.show', compact('requestForInformation'));
    }

    public function destroy(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInformation->delete();

        return back();
    }

    public function massDestroy(MassDestroyRequestForInformationRequest $request)
    {
        RequestForInformation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('request_for_information_create') && Gate::denies('request_for_information_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new RequestForInformation();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function createReportRFI(RequestForInformation $rfi)
    {
        $rfi->load('construction_contract', 'to', 'wbs_level_4', 'wbs_level_5', 'request_by', 'authorised_rep', 'response_organization', 'document_type', 'team');
       
        if($rfi->construction_contract->code == "C2-1"){
            $issue_by = 'Sitthichai Pimsawat';
            $issuer_jobtitle = 'ผู้จัดการโครงการ';
            $issue_position_lf = 255;
            $issue_position_lf_sub = 483;
            $constructor_name = 'Civil Construction Services & Products Company Limited';
            $constructor_code = 'CCSP';
            $logo_path =  public_path('png-asset/Stamp_CEC.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 115;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/Stamp_CEC.png');
            $signature_path =  public_path('png-asset/Signature_CEC.png');
            $signature_size_h = 40;
            $signature_size_w = 40;
            $signature_position_top = 410;
            $signature_position_left = 280;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;
        }

        if($rfi->construction_contract->code == "C3-2"){
            $issue_by = 'ธนนท์ ดอกลัดดา';
            $issuer_jobtitle = 'ผู้จัดการโครงการ';
            $issue_position_lf = 260;
            $issue_position_lf_sub = 485;
            $constructor_name = 'Nawarat Patanakarn Public Company Limited';
            $constructor_code = 'NWR';
            $logo_path =  public_path('png-asset/NWR_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 115;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/NWR_stamp.png');
            $signature_path =  public_path('png-asset/NWR_signature.png');
            $signature_size_h = 55;
            $signature_size_w = 55;
            $signature_position_top = 400;
            $signature_position_left = 280;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;

        }

        if($rfi->construction_contract->code == "C3-3"){
            $issue_by = 'กิตติพัฒน์ พฤกษ์ธนัทพงศ์';
            $issuer_jobtitle = 'รองผู้จัดการโครงการ';
            $issue_position_lf = 247;
            $issue_position_lf_sub = 480;
            $constructor_name = 'Thai Engineers & Industry Company Limited';
            $constructor_code = 'TEI';
            $logo_path = public_path('png-asset/TEI_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 115;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/TEI_stamp.png');
            $signature_path =  public_path('png-asset/TEI_signature.png');
            $signature_size_h = 60;
            $signature_size_w = 60;
            $signature_position_top = 400;
            $signature_position_left = 280;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;

        }

        if($rfi->construction_contract->code == "C3-4"){
            $issue_by = 'มฆา  อัศวราชันย์';
            $issuer_jobtitle = 'ผู้อำนวยการโครงการ';
            $issue_position_lf = 260;
            $issue_position_lf_sub = 489;
            $constructor_name = 'Italian-Thai Development PLC';
            $constructor_code = 'ITD';
            $logo_path = public_path('png-asset/ITD_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 115;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/ITD_stamp.png');
            $signature_path =  public_path('png-asset/ITD_signature.png');
            $signature_size_h = 60;
            $signature_size_w = 60;
            $signature_position_top = 394;
            $signature_position_left = 280;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;

        }

        if($rfi->construction_contract->code == "C3-5"){
            $issue_by = 'Narutchai Summawijitra';
            $issuer_jobtitle = 'ผู้จัดการโครงการ';
            $issue_position_lf = 245;
            $issue_position_lf_sub = 480;
            $constructor_name = 'SPTK Joint Venture Company Limited';
            $constructor_code = 'SPTK';
            $logo_path =  public_path('png-asset/SPTK_stamp.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 115;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/SPTK_stamp.png');
            $signature_path =  public_path('png-asset/SPTK_signature.png');
            $signature_size_h = 60;
            $signature_size_w = 60;
            $signature_position_top = 410;
            $signature_position_left = 275;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;

        }

        if($rfi->construction_contract->code == "C4-3"){
            $issue_by = 'Zhou Jia Yi';
            $issuer_jobtitle = 'Project manager';
            $issue_position_lf = 260;
            $issue_position_lf_sub = 489;
            $constructor_name = 'CAN Joint Venture';
            $constructor_code = 'CAN';
            $logo_path = public_path('png-asset/CAN_logo.png');
            $logo_h = 80;
            $logo_w = 80;
            $logo_top = 115;
            $logo_left = 670;
            $stamp_path =  public_path('png-asset/CAN_stamp.png');
            $signature_path = public_path('png-asset/CAN_signature.png');
            $signature_size_h = 60;
            $signature_size_w = 60;
            $signature_position_top = 394;
            $signature_position_left = 260;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;

        }

        if($rfi->construction_contract->code == "C4-4"){
            $issue_by = 'พิเชฐ  ภาพปัญญาพร';
            $issuer_jobtitle = 'ผู้อำนวยการโครงการ';
            $issue_position_lf = 260;
            $issue_position_lf_sub = 489;
            $constructor_name = 'Italian-Thai Development PCL.';
            $constructor_code = 'ITD';
            $logo_path = public_path('png-asset/ITD_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 115;
            $logo_left = 690;
            $stamp_path =  public_path('png-asset/ITD_stamp.png');
            $signature_path = public_path('png-asset/ITD2_signature.png');;
            $signature_size_h = 60;
            $signature_size_w = 60;
            $signature_position_top = 394;
            $signature_position_left = 280;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;

        }

        if($rfi->construction_contract->code == "C4-6"){
            $issue_by = 'นายเติมพงษ์ เหมาะสุวรรณ และนายปริญญา พรสวัสดิ์';
            $issuer_jobtitle = 'กรรมการผู้มีอำนาจ';
            $issue_position_lf = 190;
            $issue_position_lf_sub = 438;
            $constructor_name = 'Unique Engineering and Construction Public Company Limited';
            $constructor_code = 'UNIQUE';
            $logo_path = public_path('png-asset/UNIQUE_logo.png');
            $logo_h = 40;
            $logo_w = 40;
            $logo_top = 115;
            $logo_left = 690;
            $stamp_path =  '';
            $signature_path =  '';
            $signature_size_h = 50;
            $signature_size_w = 50;
            $signature_position_top = 400;
            $signature_position_left = 278;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;

        }

        if($rfi->construction_contract->code == "C4-7"){
            $issue_by = 'สุทิน สังข์หิรัญ';
            $issuer_jobtitle = 'ผู้จัดการโครงการ';
            $issue_position_lf = 265;
            $issue_position_lf_sub = 483;
            $constructor_name = 'Civil Enginneering Public Company Limited';
            $constructor_code = 'CIVIL';
            $logo_path = public_path('png-asset/CIVIL_logo.png');
            $logo_h = 80;
            $logo_w = 80;
            $logo_top = 115;
            $logo_left = 670;
            $stamp_path =  public_path('png-asset/CIVIL_stamp.png');
            $signature_path =  public_path('png-asset/CIVIL_signature.png');
            $signature_size_h = 50;
            $signature_size_w = 50;
            $signature_position_top = 400;
            $signature_position_left = 278;
            $contract_name = 'Contract ' . $rfi->construction_contract->code . ' : ' . $rfi->construction_contract->name;
        }

        $rfi_no = $rfi->originator_code;
        $submit_date = $rfi->incoming_date;
        $title = $rfi->title;
        $to_team = Team::where('id',$rfi->to_id)->value('code');
        $req_by =  User::where('id',$rfi->request_by_id)->value('name');
        $incoming_no = $rfi->incoming_no;
        $description = $rfi->description;

        try {
            $mpdf = new \Mpdf\Mpdf([
                'tempDir' =>  public_path('tmp'), 
                'default_font' => 'sarabun_new'
            ]);
          } catch (\Mpdf\MpdfException $e) {
              print "Creating an mPDF object failed with" . $e->getMessage();
          }
        $pagecount = $mpdf->SetSourceFile(public_path('pdf-asset/RFI_empty_form.pdf'));
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        $html = "<div style=\"font-size: 13px; font-weight: bold; position:absolute;top:90px;left:300px;\">" . $contract_name . "</div>";
        
        // Logo
        $html .= "<div style=\"font-size: 14px; position:absolute;top:". $logo_top ."px;left:". $logo_left ."px;\">
        <img src=\"". $logo_path ."\" width=\"". $logo_w ."px\" higth=\"". $logo_h ."px\"> </div>";

        $html .= "<div style=\"font-size: 13px; position:absolute;top:110px;left:615px;\">" . $constructor_code . '.' . "</div>";
        $html .= "<div style=\"font-size: 8px; position:absolute;top:155px;left:580px;\">" . $constructor_name  . '.' . "</div>";

        $html .= "<div style=\"font-size: 13px; position:absolute;top:210px;left:515px;\">" . $rfi_no  . '.' . "</div>";
        $html .= "<div style=\"font-size: 13px; position:absolute;top:210px;left:675px;\">" . $submit_date  . '.' . "</div>";

        $html .= "<div style=\"font-size: 13px; padding-right:340px; position:absolute;top:277px;left:110px; LINE-HEIGHT:18px;\">" . $title  . '.' . "</div>";
        $html .= "<div style=\"font-size: 13px; position:absolute;top:277px;left:520px;\">" . $to_team  . '.' . "</div>";
        $html .= "<div style=\"font-size: 13px; position:absolute;top:335px;left:180px;\">" . $req_by . '.' . "</div>";

        $html .= "<div style=\"font-size: 13px; position:absolute;top:373px;left:520px;\">" . $incoming_no . '.' . "</div>";
        $html .= "<div style=\"font-size: 13px; position:absolute;top:390px;left:520px;\">" . $submit_date . '.' . "</div>";

        $html .= "<div style=\"font-size: 13px; position:absolute;top:430px;left:60px;right:45px;text-align: justify;\">" . $description  . "</div>";

        $html .= "<div style=\"font-size: 11px; position:absolute;top:733px;left:270px;\">" . $issue_by  . "</div>";

        if($signature_path != ''){
            $html .= "<div style=\"font-size: 14px; position:absolute;top:720px;left:500px;\">
            <img src=\"". $signature_path ."\" width=\"". $signature_size_w ."px\" higth=\"". $signature_size_h ."px\"> </div>";
        }




        $mpdf->WriteHTML($html);
        return $mpdf->Output();
    }
}
