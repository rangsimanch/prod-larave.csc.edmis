@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.organization.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.organizations.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('title_th') ? 'has-error' : '' }}">
                            <label class="required" for="title_th">{{ trans('cruds.organization.fields.title_th') }}</label>
                            <input class="form-control" type="text" name="title_th" id="title_th" value="{{ old('title_th', '') }}" required>
                            @if($errors->has('title_th'))
                                <span class="help-block" role="alert">{{ $errors->first('title_th') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.organization.fields.title_th_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title_en') ? 'has-error' : '' }}">
                            <label for="title_en">{{ trans('cruds.organization.fields.title_en') }}</label>
                            <input class="form-control" type="text" name="title_en" id="title_en" value="{{ old('title_en', '') }}">
                            @if($errors->has('title_en'))
                                <span class="help-block" role="alert">{{ $errors->first('title_en') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.organization.fields.title_en_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                            <label for="code">{{ trans('cruds.organization.fields.code') }}</label>
                            <input class="form-control" type="text" name="code" id="code" value="{{ old('code', '') }}">
                            @if($errors->has('code'))
                                <span class="help-block" role="alert">{{ $errors->first('code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.organization.fields.code_helper') }}</span>
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