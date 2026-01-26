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
                            <legend>
                            <label class="col-md-4 control-label">Construction Contracts</label>
                            </legend>
                            <div class="col-md-6">
                                {!! Form::select('construction_contract_id', $construction_contracts, old('construction_contract_id'), ['class' => 'form-control select2']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4 text-right">
                                {!! Form::submit( trans('global.next'), ['class' => 'btn btn-success m-r-10']) !!}
                                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-default">
                                        {{ trans('global.logout') }}
                                    </button>
                                </form>
                           </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection