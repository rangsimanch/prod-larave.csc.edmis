@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.documentTag.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.document-tags.update", [$documentTag->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('tag_name') ? 'has-error' : '' }}">
                            <label for="tag_name">{{ trans('cruds.documentTag.fields.tag_name') }}</label>
                            <input class="form-control" type="text" name="tag_name" id="tag_name" value="{{ old('tag_name', $documentTag->tag_name) }}">
                            @if($errors->has('tag_name'))
                                <span class="help-block" role="alert">{{ $errors->first('tag_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.documentTag.fields.tag_name_helper') }}</span>
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