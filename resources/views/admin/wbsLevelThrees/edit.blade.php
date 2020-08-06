@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.wbsLevelThree.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.wbs-level-threes.update", [$wbsLevelThree->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('wbs_level_3_name') ? 'has-error' : '' }}">
                            <label for="wbs_level_3_name">{{ trans('cruds.wbsLevelThree.fields.wbs_level_3_name') }}</label>
                            <input class="form-control" type="text" name="wbs_level_3_name" id="wbs_level_3_name" value="{{ old('wbs_level_3_name', $wbsLevelThree->wbs_level_3_name) }}">
                            @if($errors->has('wbs_level_3_name'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_3_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbsLevelThree.fields.wbs_level_3_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_3_code') ? 'has-error' : '' }}">
                            <label for="wbs_level_3_code">{{ trans('cruds.wbsLevelThree.fields.wbs_level_3_code') }}</label>
                            <input class="form-control" type="text" name="wbs_level_3_code" id="wbs_level_3_code" value="{{ old('wbs_level_3_code', $wbsLevelThree->wbs_level_3_code) }}">
                            @if($errors->has('wbs_level_3_code'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_3_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbsLevelThree.fields.wbs_level_3_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_2') ? 'has-error' : '' }}">
                            <label for="wbs_level_2_id">{{ trans('cruds.wbsLevelThree.fields.wbs_level_2') }}</label>
                            <select class="form-control select2" name="wbs_level_2_id" id="wbs_level_2_id">
                                @foreach($wbs_level_2s as $id => $wbs_level_2)
                                    <option value="{{ $id }}" {{ ($wbsLevelThree->wbs_level_2 ? $wbsLevelThree->wbs_level_2->id : old('wbs_level_2_id')) == $id ? 'selected' : '' }}>{{ $wbs_level_2 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_2'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.wbsLevelThree.fields.wbs_level_2_helper') }}</span>
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