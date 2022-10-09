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
                        {{ $counting_file }}
                    </p>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection