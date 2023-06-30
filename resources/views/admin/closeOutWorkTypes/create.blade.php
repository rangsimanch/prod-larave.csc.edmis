@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.closeOutWorkType.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.close-out-work-types.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('work_type_name') ? 'has-error' : '' }}">
                            <label class="required" for="work_type_name">{{ trans('cruds.closeOutWorkType.fields.work_type_name') }}</label>
                            <input class="form-control" type="text" name="work_type_name" id="work_type_name" value="{{ old('work_type_name', '') }}" required>
                            @if($errors->has('work_type_name'))
                                <span class="help-block" role="alert">{{ $errors->first('work_type_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutWorkType.fields.work_type_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('work_type_code') ? 'has-error' : '' }}">
                            <label for="work_type_code">{{ trans('cruds.closeOutWorkType.fields.work_type_code') }}</label>
                            <input class="form-control" type="text" name="work_type_code" id="work_type_code" value="{{ old('work_type_code', '') }}">
                            @if($errors->has('work_type_code'))
                                <span class="help-block" role="alert">{{ $errors->first('work_type_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutWorkType.fields.work_type_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.closeOutWorkType.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $entry)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutWorkType.fields.construction_contract_helper') }}</span>
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