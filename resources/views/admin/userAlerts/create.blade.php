@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.userAlert.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.user-alerts.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('alert_text') ? 'has-error' : '' }}">
                <label for="alert_text">{{ trans('cruds.userAlert.fields.alert_text') }}*</label>
                <input type="text" id="alert_text" name="alert_text" class="form-control" value="{{ old('alert_text', isset($userAlert) ? $userAlert->alert_text : '') }}" required>
                @if($errors->has('alert_text'))
                    <em class="invalid-feedback">
                        {{ $errors->first('alert_text') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.userAlert.fields.alert_text_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('alert_link') ? 'has-error' : '' }}">
                <label for="alert_link">{{ trans('cruds.userAlert.fields.alert_link') }}</label>
                <input type="text" id="alert_link" name="alert_link" class="form-control" value="{{ old('alert_link', isset($userAlert) ? $userAlert->alert_link : '') }}">
                @if($errors->has('alert_link'))
                    <em class="invalid-feedback">
                        {{ $errors->first('alert_link') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.userAlert.fields.alert_link_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('users') ? 'has-error' : '' }}">
                <label for="user">{{ trans('cruds.userAlert.fields.user') }}
                    <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span></label>
                <select name="users[]" id="users" class="form-control select2" multiple="multiple">
                    @foreach($users as $id => $user)
                        <option value="{{ $id }}" {{ (in_array($id, old('users', [])) || isset($userAlert) && $userAlert->users->contains($id)) ? 'selected' : '' }}>{{ $user }}</option>
                    @endforeach
                </select>
                @if($errors->has('users'))
                    <em class="invalid-feedback">
                        {{ $errors->first('users') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.userAlert.fields.user_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection