@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.downloadSystemActivity.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.download-system-activities.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('activity_title') ? 'has-error' : '' }}">
                            <label for="activity_title">{{ trans('cruds.downloadSystemActivity.fields.activity_title') }}</label>
                            <input class="form-control" type="text" name="activity_title" id="activity_title" value="{{ old('activity_title', '') }}">
                            @if($errors->has('activity_title'))
                                <span class="help-block" role="alert">{{ $errors->first('activity_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.downloadSystemActivity.fields.activity_title_helper') }}</span>
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