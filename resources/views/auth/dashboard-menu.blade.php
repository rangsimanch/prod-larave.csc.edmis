@extends('layouts.app')
@section('content')
<div class="login-box">
    <div class="login-box-body" style="-webkit-box-shadow: 0px 0px 32px 0px rgba(0,0,0,0.75);
    -moz-box-shadow: 0px 0px 32px 0px rgba(0,0,0,0.75);
    box-shadow: 0px 0px 32px 0px rgba(0,0,0,0.75);">
       <div class="login-logo">
        <p>
        <img src="https://www.img.in.th/images/e0ce57eef9c5fc9cce3324c7431b49b3.png" border="0" style="width:80%; hight:80%;" />
        </p>
        
            <legend>
            <p>
                <b>
                    Dashboard Menu
                </b>
            </p>
            </legend>
        </div>
        <div style="text-align: center; margin-top:10px;">
            <a style="width: 250px" class="btn btn-danger" href="{{ route('dashboard-menu') }}"> Project Performance </a>
        </div>
        <div style="text-align: center; margin-top:10px">
            <a style="width: 250px" class="btn btn-success" target="_blank" href="https://datastudio.google.com/u/0/reporting/365be5f2-cc0c-4d47-89c4-709e81d4464d/page/p_rrt7bo76tc"> Request For Approval (RFA) </a>
        </div>
        
        <div style="text-align: center; margin-top:10px">
            <a style="width: 250px" class="btn btn-success" target="_blank" href="https://datastudio.google.com/u/0/reporting/365be5f2-cc0c-4d47-89c4-709e81d4464d/page/p_u90xeo76tc"> Request For Inspection (RFN) </a>
        </div>
        
        <div style="text-align: center; margin-top:10px">
            <a style="width: 250px" class="btn btn-success" target="_blank" href="https://datastudio.google.com/u/0/reporting/365be5f2-cc0c-4d47-89c4-709e81d4464d/page/p_0xod6n76tc"> Request For Information (RFI) </a>
        </div>
        <div style="text-align: center; margin-top:10px">
            <a style="width: 250px" class="btn btn-warning" target="_blank" href="https://datastudio.google.com/u/0/reporting/365be5f2-cc0c-4d47-89c4-709e81d4464d/page/p_ijykwo76tc"> Site Warning Notice (SWN) </a>
        </div>

        <div style="text-align: center; margin-top:10px">
            <a style="width: 250px" class="btn btn-warning" target="_blank" href="https://datastudio.google.com/u/0/reporting/365be5f2-cc0c-4d47-89c4-709e81d4464d/page/p_nq8uth66tc"> Non–Conformance Notice (NCN) </a>
        </div>

        <div style="text-align: center; margin-top:10px">
            <a style="width: 250px" class="btn btn-warning" target="_blank" href="https://datastudio.google.com/u/0/reporting/365be5f2-cc0c-4d47-89c4-709e81d4464d/page/p_xbdfho76tc"> Non–Conformance Report (NCR) </a>
        </div>

        <div style="text-align: center; margin-top:10px">
            <a style="width: 250px" class="btn btn-info" target="_blank" href="https://datastudio.google.com/u/0/reporting/365be5f2-cc0c-4d47-89c4-709e81d4464d/page/p_zmi9j397tc"> Daily Request </a>
        </div>

        <div style="text-align: center; margin-top:10px">
            <a style="width: 250px" class="btn btn-info" target="_blank" href="https://datastudio.google.com/u/0/reporting/365be5f2-cc0c-4d47-89c4-709e81d4464d/page/p_a46k5497tc"> Daily Report </a>
        </div>

        <div style="text-align: center; margin-top:10px; margin-bottom:10px">
            <a style="width: 250px" class="btn btn-primary" href="{{ route('dashboard-menu') }}"> Letter </a>
        </div>
        <legend>
        </legend>
        <a href="{{ route('login') }}"> Back to Login </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
@endsection