@extends('layouts.admin')
@section('content')
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