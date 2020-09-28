@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.constructionContract.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.construction-contracts.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.constructionContract.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                            <label class="required" for="code">{{ trans('cruds.constructionContract.fields.code') }}</label>
                            <input class="form-control" type="text" name="code" id="code" value="{{ old('code', '') }}" required>
                            @if($errors->has('code'))
                                <span class="help-block" role="alert">{{ $errors->first('code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_code') ? 'has-error' : '' }}">
                            <label for="document_code">{{ trans('cruds.constructionContract.fields.document_code') }}</label>
                            <input class="form-control" type="text" name="document_code" id="document_code" value="{{ old('document_code', '') }}">
                            @if($errors->has('document_code'))
                                <span class="help-block" role="alert">{{ $errors->first('document_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.document_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('dk_start_1') ? 'has-error' : '' }}">
                            <label for="dk_start_1">{{ trans('cruds.constructionContract.fields.dk_start_1') }}</label>
                            <input class="form-control" type="text" name="dk_start_1" id="dk_start_1" value="{{ old('dk_start_1', '') }}">
                            @if($errors->has('dk_start_1'))
                                <span class="help-block" role="alert">{{ $errors->first('dk_start_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_start_1_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('dk_end_1') ? 'has-error' : '' }}">
                            <label for="dk_end_1">{{ trans('cruds.constructionContract.fields.dk_end_1') }}</label>
                            <input class="form-control" type="text" name="dk_end_1" id="dk_end_1" value="{{ old('dk_end_1', '') }}">
                            @if($errors->has('dk_end_1'))
                                <span class="help-block" role="alert">{{ $errors->first('dk_end_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_end_1_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('dk_start_2') ? 'has-error' : '' }}">
                            <label for="dk_start_2">{{ trans('cruds.constructionContract.fields.dk_start_2') }}</label>
                            <input class="form-control" type="text" name="dk_start_2" id="dk_start_2" value="{{ old('dk_start_2', '') }}">
                            @if($errors->has('dk_start_2'))
                                <span class="help-block" role="alert">{{ $errors->first('dk_start_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_start_2_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('dk_end_2') ? 'has-error' : '' }}">
                            <label for="dk_end_2">{{ trans('cruds.constructionContract.fields.dk_end_2') }}</label>
                            <input class="form-control" type="text" name="dk_end_2" id="dk_end_2" value="{{ old('dk_end_2', '') }}">
                            @if($errors->has('dk_end_2'))
                                <span class="help-block" role="alert">{{ $errors->first('dk_end_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_end_2_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('dk_start_3') ? 'has-error' : '' }}">
                            <label for="dk_start_3">{{ trans('cruds.constructionContract.fields.dk_start_3') }}</label>
                            <input class="form-control" type="text" name="dk_start_3" id="dk_start_3" value="{{ old('dk_start_3', '') }}">
                            @if($errors->has('dk_start_3'))
                                <span class="help-block" role="alert">{{ $errors->first('dk_start_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_start_3_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('dk_end_3') ? 'has-error' : '' }}">
                            <label for="dk_end_3">{{ trans('cruds.constructionContract.fields.dk_end_3') }}</label>
                            <input class="form-control" type="text" name="dk_end_3" id="dk_end_3" value="{{ old('dk_end_3', '') }}">
                            @if($errors->has('dk_end_3'))
                                <span class="help-block" role="alert">{{ $errors->first('dk_end_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_end_3_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('roadway_km') ? 'has-error' : '' }}">
                            <label for="roadway_km">{{ trans('cruds.constructionContract.fields.roadway_km') }}</label>
                            <input class="form-control" type="number" name="roadway_km" id="roadway_km" value="{{ old('roadway_km', '0') }}" step="0.01">
                            @if($errors->has('roadway_km'))
                                <span class="help-block" role="alert">{{ $errors->first('roadway_km') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.roadway_km_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('tollway_km') ? 'has-error' : '' }}">
                            <label for="tollway_km">{{ trans('cruds.constructionContract.fields.tollway_km') }}</label>
                            <input class="form-control" type="number" name="tollway_km" id="tollway_km" value="{{ old('tollway_km', '0') }}" step="0.01">
                            @if($errors->has('tollway_km'))
                                <span class="help-block" role="alert">{{ $errors->first('tollway_km') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.tollway_km_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('total_distance_km') ? 'has-error' : '' }}">
                            <label for="total_distance_km">{{ trans('cruds.constructionContract.fields.total_distance_km') }}</label>
                            <input class="form-control" type="text" name="total_distance_km" id="total_distance_km" value="{{ old('total_distance_km', '') }}">
                            @if($errors->has('total_distance_km'))
                                <span class="help-block" role="alert">{{ $errors->first('total_distance_km') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.total_distance_km_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }}">
                            <label for="budget">{{ trans('cruds.constructionContract.fields.budget') }}</label>
                            <input class="form-control" type="number" name="budget" id="budget" value="{{ old('budget', '0') }}" step="0.01">
                            @if($errors->has('budget'))
                                <span class="help-block" role="alert">{{ $errors->first('budget') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.budget_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('works_code') ? 'has-error' : '' }}">
                            <label for="works_code_id">{{ trans('cruds.constructionContract.fields.works_code') }}</label>
                            <select class="form-control select2" name="works_code_id" id="works_code_id">
                                @foreach($works_codes as $id => $works_code)
                                    <option value="{{ $id }}" {{ old('works_code_id') == $id ? 'selected' : '' }}>{{ $works_code }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('works_code'))
                                <span class="help-block" role="alert">{{ $errors->first('works_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.constructionContract.fields.works_code_helper') }}</span>
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