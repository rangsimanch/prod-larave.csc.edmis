@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.srtInputDocument.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.srt-input-documents.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('document_type') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.srtInputDocument.fields.document_type') }}</label>
                            <select class="form-control" name="document_type" id="document_type">
                                <option value disabled {{ old('document_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtInputDocument::DOCUMENT_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('document_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('document_type'))
                                <span class="help-block" role="alert">{{ $errors->first('document_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.document_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label class="required" for="document_number">{{ trans('cruds.srtInputDocument.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', '') }}" required>
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.document_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('incoming_date') ? 'has-error' : '' }}">
                            <label class="required" for="incoming_date">{{ trans('cruds.srtInputDocument.fields.incoming_date') }}</label>
                            <input class="form-control date" type="text" name="incoming_date" id="incoming_date" value="{{ old('incoming_date') }}" required>
                            @if($errors->has('incoming_date'))
                                <span class="help-block" role="alert">{{ $errors->first('incoming_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.incoming_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('refer_to') ? 'has-error' : '' }}">
                            <label for="refer_to">{{ trans('cruds.srtInputDocument.fields.refer_to') }}</label>
                            <input class="form-control" type="text" name="refer_to" id="refer_to" value="{{ old('refer_to', '') }}">
                            @if($errors->has('refer_to'))
                                <span class="help-block" role="alert">{{ $errors->first('refer_to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.refer_to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachments') ? 'has-error' : '' }}">
                            <label for="attachments">{{ trans('cruds.srtInputDocument.fields.attachments') }}</label>
                            <input class="form-control" type="text" name="attachments" id="attachments" value="{{ old('attachments', '') }}">
                            @if($errors->has('attachments'))
                                <span class="help-block" role="alert">{{ $errors->first('attachments') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.attachments_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('from') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.srtInputDocument.fields.from') }}</label>
                            <select class="form-control" name="from" id="from" required>
                                <option value disabled {{ old('from', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtInputDocument::FROM_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('from', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('from'))
                                <span class="help-block" role="alert">{{ $errors->first('from') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.from_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('to') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.srtInputDocument.fields.to') }}</label>
                            <select class="form-control" name="to" id="to" required>
                                <option value disabled {{ old('to', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtInputDocument::TO_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('to', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('to'))
                                <span class="help-block" role="alert">{{ $errors->first('to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.srtInputDocument.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('speed_class') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.srtInputDocument.fields.speed_class') }}</label>
                            <select class="form-control" name="speed_class" id="speed_class">
                                <option value disabled {{ old('speed_class', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtInputDocument::SPEED_CLASS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('speed_class', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('speed_class'))
                                <span class="help-block" role="alert">{{ $errors->first('speed_class') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.speed_class_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('objective') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.srtInputDocument.fields.objective') }}</label>
                            <select class="form-control" name="objective" id="objective">
                                <option value disabled {{ old('objective', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtInputDocument::OBJECTIVE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('objective', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('objective'))
                                <span class="help-block" role="alert">{{ $errors->first('objective') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.objective_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('signer') ? 'has-error' : '' }}">
                            <label for="signer">{{ trans('cruds.srtInputDocument.fields.signer') }}</label>
                            <input class="form-control" type="text" name="signer" id="signer" value="{{ old('signer', '') }}">
                            @if($errors->has('signer'))
                                <span class="help-block" role="alert">{{ $errors->first('signer') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.signer_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_storage') ? 'has-error' : '' }}">
                            <label for="document_storage">{{ trans('cruds.srtInputDocument.fields.document_storage') }}</label>
                            <input class="form-control" type="text" name="document_storage" id="document_storage" value="{{ old('document_storage', '') }}">
                            @if($errors->has('document_storage'))
                                <span class="help-block" role="alert">{{ $errors->first('document_storage') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.document_storage_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                            <label for="note">{{ trans('cruds.srtInputDocument.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note') }}</textarea>
                            @if($errors->has('note'))
                                <span class="help-block" role="alert">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.note_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.srtInputDocument.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtInputDocument.fields.file_upload_helper') }}</span>
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

@section('scripts')
<script>
    var uploadedFileUploadMap = {}
Dropzone.options.fileUploadDropzone = {
    url: '{{ route('admin.srt-input-documents.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="file_upload[]" value="' + response.name + '">')
      uploadedFileUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFileUploadMap[file.name]
      }
      $('form').find('input[name="file_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($srtInputDocument) && $srtInputDocument->file_upload)
          var files =
            {!! json_encode($srtInputDocument->file_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="file_upload[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection