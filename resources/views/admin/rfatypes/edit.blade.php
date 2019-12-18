@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.rfatype.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.rfatypes.update", [$rfatype->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('type_name') ? 'has-error' : '' }}">
                            <label for="type_name">{{ trans('cruds.rfatype.fields.type_name') }}</label>
                            <input class="form-control" type="text" name="type_name" id="type_name" value="{{ old('type_name', $rfatype->type_name) }}">
                            @if($errors->has('type_name'))
                                <span class="help-block" role="alert">{{ $errors->first('type_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfatype.fields.type_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('type_code') ? 'has-error' : '' }}">
                            <label for="type_code">{{ trans('cruds.rfatype.fields.type_code') }}</label>
                            <input class="form-control" type="text" name="type_code" id="type_code" value="{{ old('type_code', $rfatype->type_code) }}">
                            @if($errors->has('type_code'))
                                <span class="help-block" role="alert">{{ $errors->first('type_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfatype.fields.type_code_helper') }}</span>
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