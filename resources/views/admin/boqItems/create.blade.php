@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.boqItem.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.boq-items.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('boq') ? 'has-error' : '' }}">
                            <label for="boq_id">{{ trans('cruds.boqItem.fields.boq') }}</label>
                            <select class="form-control select2" name="boq_id" id="boq_id">
                                @foreach($boqs as $id => $boq)
                                    <option value="{{ $id }}" {{ old('boq_id') == $id ? 'selected' : '' }}>{{ $boq }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('boq'))
                                <span class="help-block" role="alert">{{ $errors->first('boq') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.boq_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                            <label for="code">{{ trans('cruds.boqItem.fields.code') }}</label>
                            <input class="form-control" type="text" name="code" id="code" value="{{ old('code', '') }}">
                            @if($errors->has('code'))
                                <span class="help-block" role="alert">{{ $errors->first('code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('cruds.boqItem.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('unit') ? 'has-error' : '' }}">
                            <label for="unit">{{ trans('cruds.boqItem.fields.unit') }}</label>
                            <input class="form-control" type="text" name="unit" id="unit" value="{{ old('unit', '') }}">
                            @if($errors->has('unit'))
                                <span class="help-block" role="alert">{{ $errors->first('unit') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.unit_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
                            <label for="quantity">{{ trans('cruds.boqItem.fields.quantity') }}</label>
                            <input class="form-control" type="number" name="quantity" id="quantity" value="{{ old('quantity', '') }}" step="0.01">
                            @if($errors->has('quantity'))
                                <span class="help-block" role="alert">{{ $errors->first('quantity') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.quantity_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('unit_rate') ? 'has-error' : '' }}">
                            <label for="unit_rate">{{ trans('cruds.boqItem.fields.unit_rate') }}</label>
                            <input class="form-control" type="number" name="unit_rate" id="unit_rate" value="{{ old('unit_rate', '') }}" step="0.01">
                            @if($errors->has('unit_rate'))
                                <span class="help-block" role="alert">{{ $errors->first('unit_rate') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.unit_rate_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                            <label for="amount">{{ trans('cruds.boqItem.fields.amount') }}</label>
                            <input class="form-control" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01">
                            @if($errors->has('amount'))
                                <span class="help-block" role="alert">{{ $errors->first('amount') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.amount_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('factor_f') ? 'has-error' : '' }}">
                            <label for="factor_f">{{ trans('cruds.boqItem.fields.factor_f') }}</label>
                            <input class="form-control" type="number" name="factor_f" id="factor_f" value="{{ old('factor_f', '') }}" step="0.01">
                            @if($errors->has('factor_f'))
                                <span class="help-block" role="alert">{{ $errors->first('factor_f') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.factor_f_helper') }}</span>
                        </div>
                        <!-- <div class="form-group {{ $errors->has('unit_rate_x_ff') ? 'has-error' : '' }}">
                            <label for="unit_rate_x_ff">{{ trans('cruds.boqItem.fields.unit_rate_x_ff') }}</label>
                            <input class="form-control" type="number" name="unit_rate_x_ff" id="unit_rate_x_ff" value="{{ old('unit_rate_x_ff', '') }}" step="0.01">
                            @if($errors->has('unit_rate_x_ff'))
                                <span class="help-block" role="alert">{{ $errors->first('unit_rate_x_ff') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.unit_rate_x_ff_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('total_amount') ? 'has-error' : '' }}">
                            <label for="total_amount">{{ trans('cruds.boqItem.fields.total_amount') }}</label>
                            <input class="form-control" type="number" name="total_amount" id="total_amount" value="{{ old('total_amount', '') }}" step="0.01">
                            @if($errors->has('total_amount'))
                                <span class="help-block" role="alert">{{ $errors->first('total_amount') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.total_amount_helper') }}</span>
                        </div> -->
                        <div class="form-group {{ $errors->has('remark') ? 'has-error' : '' }}">
                            <label for="remark">{{ trans('cruds.boqItem.fields.remark') }}</label>
                            <textarea class="form-control" name="remark" id="remark">{{ old('remark') }}</textarea>
                            @if($errors->has('remark'))
                                <span class="help-block" role="alert">{{ $errors->first('remark') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boqItem.fields.remark_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection