@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.wbslevelfour.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.wbslevelfours.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('wbs_level_4_name') ? 'has-error' : '' }}">
                            <label for="wbs_level_4_name">{{ trans('cruds.wbslevelfour.fields.wbs_level_4_name') }}</label>
                            <input class="form-control" type="text" name="wbs_level_4_name" id="wbs_level_4_name" value="{{ old('wbs_level_4_name', '') }}">
                            @if($errors->has('wbs_level_4_name'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_4_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbslevelfour.fields.wbs_level_4_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_4_code') ? 'has-error' : '' }}">
                            <label for="wbs_level_4_code">{{ trans('cruds.wbslevelfour.fields.wbs_level_4_code') }}</label>
                            <input class="form-control" type="text" name="wbs_level_4_code" id="wbs_level_4_code" value="{{ old('wbs_level_4_code', '') }}">
                            @if($errors->has('wbs_level_4_code'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_4_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbslevelfour.fields.wbs_level_4_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_three') ? 'has-error' : '' }}">
                            <label for="wbs_level_three_id">{{ trans('cruds.wbslevelfour.fields.wbs_level_three') }}</label>
                            <select class="form-control select2" name="wbs_level_three_id" id="wbs_level_three_id">
                                @foreach($wbs_level_threes as $id => $wbs_level_three)
                                    <option value="{{ $id }}" {{ old('wbs_level_three_id') == $id ? 'selected' : '' }}>{{ $wbs_level_three }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_three'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_three') }}</span>
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