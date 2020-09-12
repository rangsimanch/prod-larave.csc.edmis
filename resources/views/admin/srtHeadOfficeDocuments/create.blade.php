@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.srtHeadOfficeDocument.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.srt-head-office-documents.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('refer_documents') ? 'has-error' : '' }}">
                            <label class="required" for="refer_documents_id">{{ trans('cruds.srtHeadOfficeDocument.fields.refer_documents') }}</label>
                            <select class="form-control select2" name="refer_documents_id" id="refer_documents_id" required>
                                @foreach($refer_documents as $id => $refer_documents)
                                    <option value="{{ $id }}" {{ old('refer_documents_id') == $id ? 'selected' : '' }}>{{ $refer_documents }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('refer_documents'))
                                <span class="help-block" role="alert">{{ $errors->first('refer_documents') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtHeadOfficeDocument.fields.refer_documents_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('process_date') ? 'has-error' : '' }}">
                            <label for="process_date">{{ trans('cruds.srtHeadOfficeDocument.fields.process_date') }}</label>
                            <input class="form-control date" type="text" name="process_date" id="process_date" value="{{ old('process_date') }}">
                            @if($errors->has('process_date'))
                                <span class="help-block" role="alert">{{ $errors->first('process_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtHeadOfficeDocument.fields.process_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('special_command') ? 'has-error' : '' }}">
                            <label for="special_command_id">{{ trans('cruds.srtHeadOfficeDocument.fields.special_command') }}</label>
                            <select class="form-control select2" name="special_command_id" id="special_command_id">
                                @foreach($special_commands as $id => $special_command)
                                    <option value="{{ $id }}" {{ old('special_command_id') == $id ? 'selected' : '' }}>{{ $special_command }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('special_command'))
                                <span class="help-block" role="alert">{{ $errors->first('special_command') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtHeadOfficeDocument.fields.special_command_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('finished_date') ? 'has-error' : '' }}">
                            <label for="finished_date">{{ trans('cruds.srtHeadOfficeDocument.fields.finished_date') }}</label>
                            <input class="form-control date" type="text" name="finished_date" id="finished_date" value="{{ old('finished_date') }}">
                            @if($errors->has('finished_date'))
                                <span class="help-block" role="alert">{{ $errors->first('finished_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtHeadOfficeDocument.fields.finished_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('practitioner') ? 'has-error' : '' }}">
                            <label for="practitioner">{{ trans('cruds.srtHeadOfficeDocument.fields.practitioner') }}</label>
                            <input class="form-control" type="text" name="practitioner" id="practitioner" value="{{ old('practitioner', '') }}">
                            @if($errors->has('practitioner'))
                                <span class="help-block" role="alert">{{ $errors->first('practitioner') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtHeadOfficeDocument.fields.practitioner_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('practice_notes') ? 'has-error' : '' }}">
                            <label for="practice_notes">{{ trans('cruds.srtHeadOfficeDocument.fields.practice_notes') }}</label>
                            <textarea class="form-control" name="practice_notes" id="practice_notes">{{ old('practice_notes') }}</textarea>
                            @if($errors->has('practice_notes'))
                                <span class="help-block" role="alert">{{ $errors->first('practice_notes') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtHeadOfficeDocument.fields.practice_notes_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                            <label for="note">{{ trans('cruds.srtHeadOfficeDocument.fields.note') }}</label>
                            <input class="form-control" type="text" name="note" id="note" value="{{ old('note', '') }}">
                            @if($errors->has('note'))
                                <span class="help-block" role="alert">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtHeadOfficeDocument.fields.note_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.srtHeadOfficeDocument.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtHeadOfficeDocument.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.srt-head-office-documents.storeMedia') }}',
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
@if(isset($srtHeadOfficeDocument) && $srtHeadOfficeDocument->file_upload)
          var files =
            {!! json_encode($srtHeadOfficeDocument->file_upload) !!}
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