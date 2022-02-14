@extends('layouts.admin')
@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@500&display=swap" rel="stylesheet">

<div style="background-color: #e9d8a6; padding-top: 10px;">
    <legend>
        <marquee direction = "left" style="font-size: 18px; font-family: 'Sarabun', sans-serif ;color: black;" onmouseover="this.stop();" onmouseout="this.start();">
            {{!! str_replace('{', '', strip_tags($announce)) !!}}
        </marquee>
    </legend>
</div>
<div class="content">
    <div class="row">
       
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Dashboard
                </div>
                
                <div class="container px-4 mx-auto">

                    <div class="p-6 m-20 bg-white rounded shadow">
                        {!! $chart->container() !!}
                    </div>

                </div>

                <script src="{{ LarapexChart::cdn() }}"></script>

                {{ $chart->script() }}  
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
@endsection