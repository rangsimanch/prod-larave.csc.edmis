@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.rfatype.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.rfatypes.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('type_name') ? 'has-error' : '' }}">
                <label for="type_name">{{ trans('cruds.rfatype.fields.type_name') }}</label>
                <input type="text" id="type_name" name="type_name" class="form-control" value="{{ old('type_name', isset($rfatype) ? $rfatype->type_name : '') }}">
                @if($errors->has('type_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('type_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.rfatype.fields.type_name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('type_code') ? 'has-error' : '' }}">
                <label for="type_code">{{ trans('cruds.rfatype.fields.type_code') }}</label>
                <input type="text" id="type_code" name="type_code" class="form-control" value="{{ old('type_code', isset($rfatype) ? $rfatype->type_code : '') }}">
                @if($errors->has('type_code'))
                    <em class="invalid-feedback">
                        {{ $errors->first('type_code') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.rfatype.fields.type_code_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection