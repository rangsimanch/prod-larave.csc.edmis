@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.manual.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.manuals.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('document_name') ? 'has-error' : '' }}">
                            <label for="document_name">{{ trans('cruds.manual.fields.document_name') }}</label>
                            <input class="form-control" type="text" name="document_name" id="document_name" value="{{ old('document_name', '') }}">
                            @if($errors->has('document_name'))
                                <span class="help-block" role="alert">{{ $errors->first('document_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.manual.fields.document_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.manual.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.manual.fields.file_upload_helper') }}</span>
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
    chunking: true,
    chunkSize: 50 * 1024 * 1024, // 50 MB per request
    retryChunks: true,
    retryChunksLimit: 3,
    sending: function (file, xhr, formData) {
        if (!file.upload.chunked) return;
        var chunk = file.upload.chunks.filter(function (item) {
            return item && item.xhr === xhr;
        })[0];
        var params = Dropzone.prototype.defaultOptions.params.call(this, [file], xhr, chunk);
        Object.keys(params).forEach(function (key) {
            formData.append(key, params[key]);
        });
    },
    url: '{{ route('admin.manuals.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
        if (file.upload && file.upload.chunked) {
            response = JSON.parse(file.xhr.responseText);
        }
        if (!response || !response.name) return;
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
@if(isset($manual) && $manual->file_upload)
          var files =
            {!! json_encode($manual->file_upload) !!}
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