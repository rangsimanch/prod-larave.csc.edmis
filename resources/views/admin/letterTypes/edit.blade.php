@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.letterType.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.letter-types.update", [$letterType->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('type_title') ? 'has-error' : '' }}">
                            <label for="type_title">{{ trans('cruds.letterType.fields.type_title') }}</label>
                            <input class="form-control" type="text" name="type_title" id="type_title" value="{{ old('type_title', $letterType->type_title) }}">
                            @if($errors->has('type_title'))
                                <span class="help-block" role="alert">{{ $errors->first('type_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.letterType.fields.type_title_helper') }}</span>
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