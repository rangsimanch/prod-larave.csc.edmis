@extends('layouts.admin')
@section('content')

<link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Dashboard
                </div>

                <div class="panel-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   {{--  <p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><strong><span style="font-size: 23px; line-height: 107%; font-family:Kanit ;"><u>ประชาสัมพันธ์เพื่อโปรดทราบ</u></span></strong></p>
<p style="margin: 0in 0in 8pt; line-height: 107%; font-size: 15px; font-family: Kanit, sans-serif; text-align: justify;"><span style="font-size: 
      14px;"><span style="line-height: 107%; font-family: Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ขณะนี้ระบบบริหารจัดการเอกสารโครงการ (EDMIS: Electronic Document Management Information System) กำลังดำเนินการ Upload ข้อมูลประกอบ RFA ฉบับต่าง ๆ และคาดว่าจะดำเนินการเสร็จภายในวันที่ 28 กุมภาพันธ์ 2563 <b>ซึ่งขณะนี้อัพโหลดได้แล้วเป็นจำนวน {{ DB::table('media')->where('model_type','App\Rfa')->count() }} ไฟล์</b>&nbsp;</span></span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size: 14px; line-height: 107%; font-family: Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; จึงแจ้งมาเพื่อทราบ และจะได้ทำการแจ้งความคืบหน้าให้ทราบเป็นระยะต่อไป</span></p>  --}}
                
                <legend style="font-family: Kanit, sans-serif; text-align: justify;">
                    Manual Documents
                </legend>
                
                <p style="font-family: Kanit, sans-serif; text-align: justify;">
                <span class="glyphicon glyphicon-file"></span>
                <a href="{{ asset('/file-documents/rfa_manual-user.pptx') }}" target="_blank"> คู่มือการใช้งานระบบ RFA Document เบื้องต้น (User ทั่วไป) </a>
                </p>
                
                <p style="font-family: Kanit, sans-serif; text-align: justify;">
                <span class="glyphicon glyphicon-file"></span>
                 <a href="{{ asset('/file-documents/rfa_manual-cec.pptx') }}" target="_blank"> คู่มือการใช้งานและทำงานระบบ RFA Document ของทีม CEC </a>
                </p>

                <p style="font-family: Kanit, sans-serif; text-align: justify;">
                <span class="glyphicon glyphicon-file"></span>
                 <a href="{{ asset('/file-documents/rfa_manual-manager.pptx') }}" target="_blank"> คู่มือการใช้งานและทำงานระบบ RFA Document ของผู้จัดการ (CSC) </a>
                </p>

                <p style="font-family: Kanit, sans-serif; text-align: justify;">
                <span class="glyphicon glyphicon-file"></span>
                 <a href="{{ asset('/file-documents/rfa_manual-engineer.pptx') }}" target="_blank"> คู่มือการใช้งานและทำงานระบบ RFA Document ของ Engineer และ Specialist (CSC) </a>
                </p>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
session(['previous-url' => route('admin.home')]);
?>

@endsection
@section('scripts')
{{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
        $( document ).ready(function() {
        if("{{ count($alerts = \Auth::user()->userUserAlerts()->withPivot('read')->limit(10)->orderBy('created_at', 'ASC')->get()->reverse()) }}" != 0){
            var alert = "{{ $alerts = \Auth::user()->userUserAlerts()->orderBy('created_at', 'DESC')->first()}}";
            var alert_text = "{{ $alerts->alert_text }}";
            var parser = new DOMParser;
            var dom = parser.parseFromString(
                '<!doctype html><body>' + alert_text,
                'text/html');
            var alert_text = dom.body.textContent;
            swal("New Message!", alert_text, "warning");
        }
        else{
            console.log('TT')
        }

    });
    </script> --}}

@parent

@endsection