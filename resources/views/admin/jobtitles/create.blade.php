@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.jobtitle.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.jobtitles.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.jobtitle.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.jobtitle.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('departments') ? 'has-error' : '' }}">
                            <label class="required" for="departments">{{ trans('cruds.jobtitle.fields.department') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="departments[]" id="departments" multiple required>
                                @foreach($departments as $id => $department)
                                    <option value="{{ $id }}" {{ in_array($id, old('departments', [])) ? 'selected' : '' }}>{{ $department }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('departments'))
                                <span class="help-block" role="alert">{{ $errors->first('departments') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.jobtitle.fields.department_helper') }}</span>
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