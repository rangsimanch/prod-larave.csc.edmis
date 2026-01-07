@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.closeOutDrive.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.close-out-drives.update", [$closeOutDrive->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('filename') ? 'has-error' : '' }}">
                            <label class="required" for="filename">{{ trans('cruds.closeOutDrive.fields.filename') }}</label>
                            <input class="form-control" type="text" name="filename" id="filename" value="{{ old('filename', $closeOutDrive->filename) }}" required>
                            @if($errors->has('filename'))
                                <span class="help-block" role="alert">{{ $errors->first('filename') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutDrive.fields.filename_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                            <label class="required" for="url">{{ trans('cruds.closeOutDrive.fields.url') }}</label>
                            <input class="form-control" type="text" name="url" id="url" value="{{ old('url', $closeOutDrive->url) }}" required>
                            @if($errors->has('url'))
                                <span class="help-block" role="alert">{{ $errors->first('url') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutDrive.fields.url_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.closeOutDrive.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('construction_contract_id') ? old('construction_contract_id') : $closeOutDrive->construction_contract->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutDrive.fields.construction_contract_helper') }}</span>
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