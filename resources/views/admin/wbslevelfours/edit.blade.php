@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.wbslevelfour.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.wbslevelfours.update", [$wbslevelfour->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('wbs_level_4_name') ? 'has-error' : '' }}">
                            <label for="wbs_level_4_name">{{ trans('cruds.wbslevelfour.fields.wbs_level_4_name') }}</label>
                            <input class="form-control" type="text" name="wbs_level_4_name" id="wbs_level_4_name" value="{{ old('wbs_level_4_name', $wbslevelfour->wbs_level_4_name) }}">
                            @if($errors->has('wbs_level_4_name'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_4_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbslevelfour.fields.wbs_level_4_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_4_code') ? 'has-error' : '' }}">
                            <label for="wbs_level_4_code">{{ trans('cruds.wbslevelfour.fields.wbs_level_4_code') }}</label>
                            <input class="form-control" type="text" name="wbs_level_4_code" id="wbs_level_4_code" value="{{ old('wbs_level_4_code', $wbslevelfour->wbs_level_4_code) }}">
                            @if($errors->has('wbs_level_4_code'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_4_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbslevelfour.fields.wbs_level_4_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('boq') ? 'has-error' : '' }}">
                            <label for="boq_id">{{ trans('cruds.wbslevelfour.fields.boq') }}</label>
                            <select class="form-control select2" name="boq_id" id="boq_id">
                                @foreach($boqs as $id => $boq)
                                    <option value="{{ $id }}" {{ ($wbslevelfour->boq ? $wbslevelfour->boq->id : old('boq_id')) == $id ? 'selected' : '' }}>{{ $boq }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('boq_id'))
                                <span class="help-block" role="alert">{{ $errors->first('boq_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbslevelfour.fields.boq_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_three') ? 'has-error' : '' }}">
                            <label for="wbs_level_three_id">{{ trans('cruds.wbslevelfour.fields.wbs_level_three') }}</label>
                            <select class="form-control select2" name="wbs_level_three_id" id="wbs_level_three_id">
                                @foreach($wbs_level_threes as $id => $wbs_level_three)
                                    <option value="{{ $id }}" {{ ($wbslevelfour->wbs_level_three ? $wbslevelfour->wbs_level_three->id : old('wbs_level_three_id')) == $id ? 'selected' : '' }}>{{ $wbs_level_three }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_three_id'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_three_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbslevelfour.fields.wbs_level_three_helper') }}</span>
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