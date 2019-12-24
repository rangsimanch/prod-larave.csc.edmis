@extends('layouts.auth')
@include('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Select</div>
                <div class="panel-body">
                    {!! Form::open(['method' => 'POST', 'url' => route('admin.construction_contracts-select.select'), 'class' => 'form-horizontal']) !!}
                        <div class="form-group">
                            <label class="col-md-4 control-label">Construction Contracts</label>

                            <div class="col-md-6">
                                {!! Form::select('construction_contract_id', $construction_contracts, old('construction_contract_id'), ['class' => 'form-control select2']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                            {!! Form::submit( trans('global.save'), ['class' => 'btn btn-primary']) !!}
                           </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection