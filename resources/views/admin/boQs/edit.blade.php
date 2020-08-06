@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.boQ.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.bo-qs.update", [$boQ->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('cruds.boQ.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $boQ->name) }}">
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boQ.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                            <label for="code">{{ trans('cruds.boQ.fields.code') }}</label>
                            <input class="form-control" type="text" name="code" id="code" value="{{ old('code', $boQ->code) }}">
                            @if($errors->has('code'))
                                <span class="help-block" role="alert">{{ $errors->first('code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boQ.fields.code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_lv_1') ? 'has-error' : '' }}">
                            <label for="wbs_lv_1_id">{{ trans('cruds.boQ.fields.wbs_lv_1') }}</label>
                            <select class="form-control select2" name="wbs_lv_1_id" id="wbs_lv_1_id">
                                @foreach($wbs_lv_1s as $id => $wbs_lv_1)
                                    <option value="{{ $id }}" {{ ($boQ->wbs_lv_1 ? $boQ->wbs_lv_1->id : old('wbs_lv_1_id')) == $id ? 'selected' : '' }}>{{ $wbs_lv_1 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_lv_1'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_lv_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.boQ.fields.wbs_lv_1_helper') }}</span>
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