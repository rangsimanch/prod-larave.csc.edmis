@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.designReview.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.design-reviews.update", [$designReview->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('sheet_title') ? 'has-error' : '' }}">
                            <label class="required" for="sheet_title">{{ trans('cruds.designReview.fields.sheet_title') }}</label>
                            <input class="form-control" type="text" name="sheet_title" id="sheet_title" value="{{ old('sheet_title', $designReview->sheet_title) }}" required>
                            @if($errors->has('sheet_title'))
                                <span class="help-block" role="alert">{{ $errors->first('sheet_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.designReview.fields.sheet_title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                            <label class="required" for="url">{{ trans('cruds.designReview.fields.url') }}</label>
                            <input class="form-control" type="text" name="url" id="url" value="{{ old('url', $designReview->url) }}" required>
                            @if($errors->has('url'))
                                <span class="help-block" role="alert">{{ $errors->first('url') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.designReview.fields.url_helper') }}</span>
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