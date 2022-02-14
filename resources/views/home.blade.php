@extends('layouts.admin')
@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@500&display=swap" rel="stylesheet">

@if($announce_text != "")
<div  class="row">
    <div class="col-md-3 text-center justify" style="background-color: #DD4B39; ;border-color: #DD4B39; border-style:solid; border-width:2px;">
        <b style="font-size: 18px; color: white"><i>
            <span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>&nbsp; ANNOUNCEMENTS :<i></b>
    </div>
    <div class="col-md-9" style="background-color: #ffe5d9;">
        <marquee direction = "left" style="font-size: 18px; font-family: 'Sarabun', sans-serif ;color: black;" onmouseover="this.stop();" onmouseout="this.start();">
            {!! strip_tags($announce_text, '<a>') !!}
        </marquee>
    </div>
    
</div>    
@endif
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