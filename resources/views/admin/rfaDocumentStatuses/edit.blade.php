@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.rfaDocumentStatus.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.rfa-document-statuses.update", [$rfaDocumentStatus->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('status_name') ? 'has-error' : '' }}">
                            <label for="status_name">{{ trans('cruds.rfaDocumentStatus.fields.status_name') }}</label>
                            <input class="form-control" type="text" name="status_name" id="status_name" value="{{ old('status_name', $rfaDocumentStatus->status_name) }}">
                            @if($errors->has('status_name'))
                                <span class="help-block" role="alert">{{ $errors->first('status_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfaDocumentStatus.fields.status_name_helper') }}</span>
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