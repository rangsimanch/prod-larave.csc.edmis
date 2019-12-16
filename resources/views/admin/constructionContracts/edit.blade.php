@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.constructionContract.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.construction-contracts.update", [$constructionContract->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.constructionContract.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $constructionContract->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="code">{{ trans('cruds.constructionContract.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', $constructionContract->code) }}" required>
                @if($errors->has('code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dk_start_1">{{ trans('cruds.constructionContract.fields.dk_start_1') }}</label>
                <input class="form-control {{ $errors->has('dk_start_1') ? 'is-invalid' : '' }}" type="text" name="dk_start_1" id="dk_start_1" value="{{ old('dk_start_1', $constructionContract->dk_start_1) }}">
                @if($errors->has('dk_start_1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dk_start_1') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_start_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dk_end_1">{{ trans('cruds.constructionContract.fields.dk_end_1') }}</label>
                <input class="form-control {{ $errors->has('dk_end_1') ? 'is-invalid' : '' }}" type="text" name="dk_end_1" id="dk_end_1" value="{{ old('dk_end_1', $constructionContract->dk_end_1) }}">
                @if($errors->has('dk_end_1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dk_end_1') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_end_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dk_start_2">{{ trans('cruds.constructionContract.fields.dk_start_2') }}</label>
                <input class="form-control {{ $errors->has('dk_start_2') ? 'is-invalid' : '' }}" type="text" name="dk_start_2" id="dk_start_2" value="{{ old('dk_start_2', $constructionContract->dk_start_2) }}">
                @if($errors->has('dk_start_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dk_start_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_start_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dk_end_2">{{ trans('cruds.constructionContract.fields.dk_end_2') }}</label>
                <input class="form-control {{ $errors->has('dk_end_2') ? 'is-invalid' : '' }}" type="text" name="dk_end_2" id="dk_end_2" value="{{ old('dk_end_2', $constructionContract->dk_end_2) }}">
                @if($errors->has('dk_end_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dk_end_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_end_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dk_start_3">{{ trans('cruds.constructionContract.fields.dk_start_3') }}</label>
                <input class="form-control {{ $errors->has('dk_start_3') ? 'is-invalid' : '' }}" type="text" name="dk_start_3" id="dk_start_3" value="{{ old('dk_start_3', $constructionContract->dk_start_3) }}">
                @if($errors->has('dk_start_3'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dk_start_3') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_start_3_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dk_end_3">{{ trans('cruds.constructionContract.fields.dk_end_3') }}</label>
                <input class="form-control {{ $errors->has('dk_end_3') ? 'is-invalid' : '' }}" type="text" name="dk_end_3" id="dk_end_3" value="{{ old('dk_end_3', $constructionContract->dk_end_3) }}">
                @if($errors->has('dk_end_3'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dk_end_3') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.dk_end_3_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="roadway_km">{{ trans('cruds.constructionContract.fields.roadway_km') }}</label>
                <input class="form-control {{ $errors->has('roadway_km') ? 'is-invalid' : '' }}" type="number" name="roadway_km" id="roadway_km" value="{{ old('roadway_km', $constructionContract->roadway_km) }}" step="0.01">
                @if($errors->has('roadway_km'))
                    <div class="invalid-feedback">
                        {{ $errors->first('roadway_km') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.roadway_km_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tollway_km">{{ trans('cruds.constructionContract.fields.tollway_km') }}</label>
                <input class="form-control {{ $errors->has('tollway_km') ? 'is-invalid' : '' }}" type="number" name="tollway_km" id="tollway_km" value="{{ old('tollway_km', $constructionContract->tollway_km) }}" step="0.01">
                @if($errors->has('tollway_km'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tollway_km') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.tollway_km_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_distance_km">{{ trans('cruds.constructionContract.fields.total_distance_km') }}</label>
                <input class="form-control {{ $errors->has('total_distance_km') ? 'is-invalid' : '' }}" type="text" name="total_distance_km" id="total_distance_km" value="{{ old('total_distance_km', $constructionContract->total_distance_km) }}">
                @if($errors->has('total_distance_km'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_distance_km') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.total_distance_km_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="budget">{{ trans('cruds.constructionContract.fields.budget') }}</label>
                <input class="form-control {{ $errors->has('budget') ? 'is-invalid' : '' }}" type="number" name="budget" id="budget" value="{{ old('budget', $constructionContract->budget) }}" step="0.01">
                @if($errors->has('budget'))
                    <div class="invalid-feedback">
                        {{ $errors->first('budget') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.constructionContract.fields.budget_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection