@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.jobtitle.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.jobtitles.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.jobtitle.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($jobtitle) ? $jobtitle->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.jobtitle.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('departments') ? 'has-error' : '' }}">
                <label for="department">{{ trans('cruds.jobtitle.fields.department') }}*
                    <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span></label>
                <select name="departments[]" id="departments" class="form-control select2" multiple="multiple" required>
                    @foreach($departments as $id => $department)
                        <option value="{{ $id }}" {{ (in_array($id, old('departments', [])) || isset($jobtitle) && $jobtitle->departments->contains($id)) ? 'selected' : '' }}>{{ $department }}</option>
                    @endforeach
                </select>
                @if($errors->has('departments'))
                    <em class="invalid-feedback">
                        {{ $errors->first('departments') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.jobtitle.fields.department_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection