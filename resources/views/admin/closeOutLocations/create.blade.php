@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.closeOutLocation.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.close-out-locations.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('location_name') ? 'has-error' : '' }}">
                            <label class="required" for="location_name">{{ trans('cruds.closeOutLocation.fields.location_name') }}</label>
                            <input class="form-control" type="text" name="location_name" id="location_name" value="{{ old('location_name', '') }}" required>
                            @if($errors->has('location_name'))
                                <span class="help-block" role="alert">{{ $errors->first('location_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutLocation.fields.location_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('location_code') ? 'has-error' : '' }}">
                            <label for="location_code">{{ trans('cruds.closeOutLocation.fields.location_code') }}</label>
                            <input class="form-control" type="text" name="location_code" id="location_code" value="{{ old('location_code', '') }}">
                            @if($errors->has('location_code'))
                                <span class="help-block" role="alert">{{ $errors->first('location_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutLocation.fields.location_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.closeOutLocation.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $entry)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutLocation.fields.construction_contract_helper') }}</span>
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