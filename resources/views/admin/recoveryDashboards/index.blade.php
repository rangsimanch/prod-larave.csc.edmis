@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.recoveryDashboard.title') }}
                </div>
                <div class="panel-body">
                    <p>
                        <h2>PDF Summary Uploaded Count : 
                            {{ $count_pdf_format }} / {{ $pdf_sum }}
                            <br>
                        </h2>
                        <h3>↳ Model RFA : 
                            {{ $count_rfa_format }} / {{ $rfa_sum }}
                            <br>
                        </h3>
                        <h3>↳ Model SRT : 
                            {{ $count_srt_format }} / {{ $srt_sum }}
                            <br>
                        </h3>
                        <h3>↳ Other : 
                            {{ $count_other_format }} / {{ $other_sum }}
                            <br>
                        </h3>
                    </p>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection