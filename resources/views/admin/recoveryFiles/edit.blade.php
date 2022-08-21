@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.recoveryFile.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.recovery-files.update", [$recoveryFile->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('dir_name') ? 'has-error' : '' }}">
                            <label class="required" for="dir_name">{{ trans('cruds.recoveryFile.fields.dir_name') }}</label>
                            <input class="form-control" type="text" name="dir_name" id="dir_name" value="{{ old('dir_name', $recoveryFile->dir_name) }}" required>
                            @if($errors->has('dir_name'))
                                <span class="help-block" role="alert">{{ $errors->first('dir_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recoveryFile.fields.dir_name_helper') }}</span>
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