@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.downloadSystemWork.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.download-system-works.update", [$downloadSystemWork->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('work_title') ? 'has-error' : '' }}">
                            <label for="work_title">{{ trans('cruds.downloadSystemWork.fields.work_title') }}</label>
                            <input class="form-control" type="text" name="work_title" id="work_title" value="{{ old('work_title', $downloadSystemWork->work_title) }}">
                            @if($errors->has('work_title'))
                                <span class="help-block" role="alert">{{ $errors->first('work_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.downloadSystemWork.fields.work_title_helper') }}</span>
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