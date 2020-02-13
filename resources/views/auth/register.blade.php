@extends('layouts.app-register')
@section('content')
<div class="login-box">
<link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">
    
    <div class="login-box-body">
    <div class="login-logo">
           <b>REGISTER</b>
    </div>
    
        <form method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}
            <div>
                    <legend> Personal </legend>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required placeholder="First name and Last name">
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                        </div>

                        <!-- <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
                        <div class="input-group">
                            <label class="required" for="dob">{{ trans('cruds.user.fields.dob') }}</label>
                            <input class="form-control date" type="text" name="dob" id="dob" value="{{ old('dob') }}" required>
                            @if($errors->has('dob'))
                                <span class="help-block" role="alert">{{ $errors->first('dob') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.dob_helper') }}</span>
                        </div>
                        </div> -->

                        <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.user.fields.gender') }}</label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\User::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('gender', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gender'))
                                <span class="help-block" role="alert">{{ $errors->first('gender') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.gender_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('workphone') ? 'has-error' : '' }}">
                            <label for="workphone">{{ trans('cruds.user.fields.workphone') }}</label>
                            <input class="form-control" type="text" name="workphone" id="workphone" value="{{ old('workphone', '') }}">
                            @if($errors->has('workphone'))
                                <span class="help-block" role="alert">{{ $errors->first('workphone') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.workphone_helper') }}</span>
                        </div>

                        <legend> Job Description </legend>
                        <div class="form-group {{ $errors->has('team') ? 'has-error' : '' }}">
                            <label class="required" for="team_id">{{ trans('cruds.user.fields.team') }}</label>
                            <select class="form-control select2" name="team_id" id="team_id" required>
                                @foreach($teams as $id => $team)
                                    <option value="{{ $team->id }}" >{{ $team->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('team_id'))
                                <span class="help-block" role="alert">{{ $errors->first('team_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.team_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('jobtitle') ? 'has-error' : '' }}">
                            <label for="jobtitle_id">{{ trans('cruds.user.fields.jobtitle') }}</label>
                            <select class="form-control select2" name="jobtitle_id" id="jobtitle_id">
                                @foreach($jobtitles as $id => $jobtitle)
                                    <option value="{{ $jobtitle->id }}" >{{ $jobtitle->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jobtitle_id'))
                                <span class="help-block" role="alert">{{ $errors->first('jobtitle_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.jobtitle_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('construction_contracts') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contracts">{{ trans('cruds.user.fields.construction_contract') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>

                            <select class="form-control select2" name="construction_contracts[]" id="construction_contracts" multiple>
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $construction_contract->id }}" {{ in_array($id, old('construction_contracts', [])) ? 'selected' : '' }}>{{ $construction_contract->code }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contracts'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contracts') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.construction_contract_helper') }}</span>
                        </div>


                        <legend> Account </legend>
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                            <input class="form-control" type="text" name="email" id="email" value="{{ old('email') }}" required placeholder="{{ trans('global.login_email') }}">
                            @if($errors->has('email'))
                                <span class="help-block" role="alert">{{ $errors->first('email') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                        </div>


                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="required" for="password">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="{{ trans('global.login_password') }} (Use at least 8 characters)">
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
                <div class="form-group">
                <label class="required" for="password_confirmation">Password Confirmation</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="{{ trans('global.login_password_confirmation') }}">
                </div>
                <div class="row">
                    <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <a href="{{ route('login') }}"> Back to Login </a>
                    </div>
                    </div>
                
                    <div class="col-xs-4">
                        <!-- <button type="submit" class="btn btn-primary btn-block btn-flat">
                            {{ trans('global.register') }}
                        </button> -->

                        <button type="button" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#exampleModal">
                            {{ trans('global.register') }}
                        </button>

                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Policy</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;text-align:center;"><strong><span style="font-size:24px;line-height:107%;font-family:Kanit;">สัญญาการใช้ระบบสารสนเทศเพื่อการจัดการ</span></strong></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;text-align:center;"><strong><span style="font-size:24px;line-height:107%;font-family:Kanit;">โครงการก่อสร้างรถไฟความเร็วสูงไทย-จีน</span></strong></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;text-align:center;"><strong><span style="font-size:24px;line-height:107%;font-family:Kanit;">ระยะที่ 1 (กรุงเทพ-นครราชสีมา)</span></strong></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;text-align:center;"><strong><span style="font-size:21px;line-height:107%;font-family:Kanit;">EDMIS (Electronic Document Management Information System)</span></strong></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:9px;line-height:107%;font-family:Kanit;">&nbsp;</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:19px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">สัญญานี้จัดทำขึ้นระหว่างบริษัทที่ปรึกษา&nbsp;</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">CSC (Construction Supervision Consultant) ซึ่งต่อไปนี้เรียกว่า&nbsp;“บริษัท&nbsp;CSC” กับอีกฝ่ายคือ&nbsp;Admin โปรแกรมและ&nbsp;User โปรแกรมซึ่งต่อไปนี้เรียกว่า&nbsp;“ผู้ใช้โปรแกรม” ซึ่งทั้งสองฝ่ายได้ตกลงที่จะทำสัญญาต่อกันเพื่อให้การใช้โปรแกรม&nbsp;EDMIS บริหารงานก่อสร้างรถไฟความเร็วสูงให้เกิดประสิทธิภาพสูงสุด และเพื่อรักษาผลประโยชน์ของทางการรถไฟแห่งประเทศไทย ด้านข้อมูลสารสนเทศของโครงการและระบบ&nbsp;Program จึงกำหนดหลักการใช้โปรแกรม ความรับผิดชอบ และข้อห้ามต่างๆ ดังนี้</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;1. บริษัท&nbsp;</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">CSC เป็น&nbsp;Admin หลักของระบบมีหน้าที่ดังนี้</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:.5in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;text-indent:.5in;"><span style="line-height:107%;font-family:Kanit;">1.1 การแก้ไขข้อมูล&nbsp;</span><span style="font-size:12px;line-height:107%;font-family:Kanit;">User&nbsp;</span><span style="line-height:107%;font-family:Kanit;">ได้แก่ รหัสผ่าน และสัญญา</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:.5in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;text-indent:.5in;"><span style="line-height:107%;font-family:Kanit;">1.2 แก้ไขสถานะการใช้งานโปรแกรม (ใช้ได้/ไม่ได้)</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:.5in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;text-indent:.5in;"><span style="line-height:107%;font-family:Kanit;">1.3 แก้ไขการเข้าถึงข้อมูลในส่วนต่างๆของโปรแกรม&nbsp;</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:.5in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;text-indent:.5in;"><span style="line-height:107%;font-family:Kanit;">1.5 แก้ไขบทบาทและสิทธิ์การเข้าถึงในการทำงานบนระบบ เช่น การสร้าง การลบ การแก้ไขข้อมูล&nbsp;</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="line-height:107%;font-family:Kanit;">ซึ่งมีผู้รับผิดชอบคือ&nbsp;</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;1. นายแอ๊ด เพชรฤทธิ์</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.&nbsp;</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">Mr.Wei Wuchang</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;3. นางสาวหทัยชนก วิริยมานุวงษ์</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2. ข้อกำหนดหน้าที่ความรับผิดชอบและข้อห้ามของผู้ใช้งาน</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.1 ต้องป้องกันดูแลรักษาข้อมูลบัญชีชื่อผู้ใช้งาน (</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">User Account) และรหัสผ่าน (Password) โดยมีบัญชีชื่อผู้ใช้งานของตนเองและห้ามใช้ร่วมกับผู้อื่นรวมทั้งห้ามเผยแพร่แจกจ่ายหรือให้ผู้อื่นล่วงรู้รหัสผ่าน</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.2 ต้องร่วมกันดูแลรักษาและรับผิดชอบต่อข้อมูลของหน่วยงาน การกระทำใดๆ ที่เกิดจากการใช้บัญชีผู้ใช้งานของตนเองที่มีกฎหมายกำหนดให้เป็นความผิดไม่ว่าการกระทำนั้นจะเกิดจากตนเองหรือไม่ก็ตามโดยความประมาท ให้ถือว่าเป็นความรับผิดชอบของเจ้าของบัญชีผู้ใช้งานจะต้องรับผิดชอบต่อความผิดที่เกิดขึ้น</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.3 ต้องตระหนักและระมัดระวังต่อการใช้งานข้อมูล&nbsp; &nbsp; &nbsp;&nbsp;</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.4 ต้องไม่เผยแพร่หรือทำลายข้อมูลที่เป็นความลับหรือมีระดับความสำคัญที่อยู่ในการครอบครอง/ดูแลของหน่วยงานโดยไม่ได้รับอนุญาตจากการรถไฟแห่งประเทศไทย</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.5 ต้องป้องกันดูแลรักษาไว้ซึ่งความลับความถูกต้องและความพร้อมใช้ของข้อมูลตลอดจนเอกสารสื่อบันทึกข้อมูลคอมพิวเตอร์หรือสารสนเทศต่างๆ ที่เสี่ยงต่อการเข้าถึงโดยผู้ไม่มีสิทธิ์</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.6 ห้ามใช้สินทรัพย์ของหน่วยงานเผยแพร่ข้อมูลข้อความรูปภาพหรือสิ่งอื่นใดที่มีลักษณะขัดต่อศีลธรรมความมั่นคงของประเทศกฎหมายหรือกระทบต่อภารกิจของหน่วยงาน</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.7 ห้ามใช้สินทรัพย์ของหน่วยงานเพื่อรบกวนก่อให้เกิดความเสียหายหรือใช้ในการโจรกรรมข้อมูลหรือสิ่งอื่นใดอันเป็นการขัดต่อกฎหมายและศีลธรรมหรือกระทบต่อภารกิจของหน่วยงาน</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.8 ไม่รบกวนทำลายหรือทำให้ระบบสารสนเทศของหน่วยงานต้องหยุดชะงัก</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.9 ห้ามกระทำการใดๆ อันมีลักษณะเป็นการลักลอบใช้งานระบบคอมพิวเตอร์หรือระบบสารสนเทศหรือดักรับรหัสผ่านของผู้อื่นไม่ว่าจะเป็นไปเพื่อประโยชน์ในการเข้าถึงข้อมูลหรือเพื่อการใช้ทรัพยากรหรือเพื่อการอื่นใดก็ตาม</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;2.10 ห้ามนำข้อมูล คัดลอก เลียนแบบ ดัดแปลงหรือตีพิมพ์ไปใช้ประโยชน์เชิงพาณิชย์หรือมีเจตนาเอื้อผลประโยชน์ทางธุรกิจใดๆ โดยเด็ดขาด</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:8px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">หากผู้ใช้งานโปรแกรม&nbsp;</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">EDMIS&nbsp;ทั้ง&nbsp;Admin และ&nbsp;User กระทำการใดๆที่นอกเหนือจากหน้าที่รับผิดชอบหรือปฏิบัติผิดต่อข้อกำหนดและแนวปฏิบัติในการรักษาความมั่นคงและปลอดภัยด้านสารสนเทศของโปรแกรม&nbsp;EDMIS และหลักปฏิบัติสากลที่เกี่ยวข้องกับการรักษาความลับของข้อมูลต่างๆที่อยู่ในความครอบครองหรือควบคุมดูแลของการรถไฟแห่งประเทศไทย หากพบการกระทำที่เป็นความผิดตามกฎหมาย ต้องรับผิดในทางแพ่งหรือต้องรับโทษทางอาญาตามบทบัญญัติของพระราชบัญญัติว่าด้วยการกระทำความผิดเกี่ยวกับคอมพิวเตอร์ปี พ.ศ.2561</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;3. กรณีบุคลากรลาออก ผู้รับผิดชอบด้านบุคคลแจ้งรายชื่อต่อ&nbsp;</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">Admin ของแต่ละหน่วย เพื่อคัดชื่อออกจากระบบ กรณีมีบุคคลเข้าเพิ่มให้ลงทะเบียนผ่านโปรแกรม&nbsp;EDMIS โดย&nbsp;Admin ของหน่วยงานเป็นผู้ตรวจสอบและรับสมัครบุคคลดังกล่าว และกรณี&nbsp;User ย้ายหน่วยงาน&nbsp;Admin ทั้งสองหน่วยงานต้องประสานงานกันเพื่อทราบและเพื่อการควบคุมระบบต่อไป</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;4. กระบวนการยืนยันตัวตนของบุคคลากรบนโปรแกรมโดยให้หน่วยงานที่ใช้โปรแกรมเป็นผู้ตรวจสอบและยืนยันตัวตนผู้ใช้โปรแกรมทุกไตรมาส โดยตรวจสอบข้อมูลในระบบ&nbsp;</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">Hard Copy</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;ทุกฝ่ายอ่านข้อความทั้งหมดเข้าใจแล้วจึงลงนาม (กด</span><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp;Accept) เพื่อเป็นการยอมรับข้อตกลงและเพื่อไว้เป็นหลักฐาน</span></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:8.0pt;margin-left:0in;line-height:107%;font-size:15px;font-family:&quot;Calibri&quot;,sans-serif;"><span style="font-size:16px;line-height:107%;font-family:Kanit;">&nbsp;</span></p>
                                   </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Decline</button>
                                        <button type="submit" class="btn btn-success">Accept</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                
            </div>
           
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.imgUserDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 100, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 100,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="img_user"]').remove()
      $('form').append('<input type="hidden" name="img_user" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="img_user"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->img_user)
      var file = {!! json_encode($user->img_user) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $user->img_user->getUrl('thumb') }}')
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="img_user" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script>
    Dropzone.options.signatureDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="signature"]').remove()
      $('form').append('<input type="hidden" name="signature" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="signature"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->signature)
      var file = {!! json_encode($user->signature) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $user->signature->getUrl('thumb') }}')
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="signature" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@endsection