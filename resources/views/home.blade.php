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

                    You are logged in!
<<<<<<< HEAD
<<<<<<< HEAD
                   {{ session('construction_contract_id') }}

=======
>>>>>>> parent of 2c893c1... Constraction Contract Select
=======
>>>>>>> parent of 2c893c1... Constraction Contract Select
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection