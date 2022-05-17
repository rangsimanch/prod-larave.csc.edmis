@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.letterSubjectType.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.letter-subject-types.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('subject_name') ? 'has-error' : '' }}">
                            <label class="required" for="subject_name">{{ trans('cruds.letterSubjectType.fields.subject_name') }}</label>
                            <input class="form-control" type="text" name="subject_name" id="subject_name" value="{{ old('subject_name', '') }}" required>
                            @if($errors->has('subject_name'))
                                <span class="help-block" role="alert">{{ $errors->first('subject_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.letterSubjectType.fields.subject_name_helper') }}</span>
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