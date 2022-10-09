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
                        <h2>All File Summary Recovered:  
                            {{ $count_all_format }} / {{ $all_sum }}
                            <br>
                        </h2>
                        <h3>  PDF Summary Recovered : 
                            {{ $count_pdf_format }} / {{ $pdf_sum }}
                            <br>
                        </h3>
                        <h4>    ↳ Model RFA : 
                            {{ $count_rfa_format }} / {{ $rfa_sum }}
                            <br>
                        </h4>
                        <h4>    ↳ Model SRT : 
                            {{ $count_srt_format }} / {{ $srt_sum }}
                            <br>
                        </h4>
                        <h4>    ↳ Other(RFI, RFN, SWN, NCN, NCR, Letter, etc.) : 
                            {{ $count_other_format }} / {{ $other_sum }}
                            <br>
                        </h4>
                    </p>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection