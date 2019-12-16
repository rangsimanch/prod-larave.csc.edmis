@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.rfaDocumentStatus.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.rfa-document-statuses.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('status_name') ? 'has-error' : '' }}">
                <label for="status_name">{{ trans('cruds.rfaDocumentStatus.fields.status_name') }}</label>
                <input type="text" id="status_name" name="status_name" class="form-control" value="{{ old('status_name', isset($rfaDocumentStatus) ? $rfaDocumentStatus->status_name : '') }}">
                @if($errors->has('status_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('status_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.rfaDocumentStatus.fields.status_name_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection