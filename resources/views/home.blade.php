@extends('layouts.admin')
@section('content')
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
                    <p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><strong><span style="font-size: 16px; line-height: 107%; font-family: RSU;">ประชาสัมพันธ์เพื่อโปรดทราบ</span></strong></p>
<p style="margin: 0in 0in 8pt; line-height: 107%; font-size: 15px; font-family: Calibri, sans-serif; text-align: justify;"><span style="font-size: 
      14px;"><span style="line-height: 107%; font-family: RSU;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ขณะนี้ระบบบริหารจัดการเอกสารโครงการ (EDMIS: Electronic Document Management Information System) กำลังดำเนินการ Upload ข้อมูลประกอบ RFA ฉบับต่าง ๆ และคาดว่าจะดำเนินการเสร็จภายในวันที่ 28 กุมภาพันธ์ 2563 <b>ซึ่งขณะนี้อัพโหลดได้แล้วเป็นจำนวน {{ DB::table('media')->where('model_type','App\Rfa')->count() }} ไฟล์</b>&nbsp;</span></span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size: 14px; line-height: 107%; font-family: RSU;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; จึงแจ้งมาเพื่อทราบ และจะได้ทำการแจ้งความคืบหน้าให้ทราบเป็นระยะต่อไป</span></p> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection