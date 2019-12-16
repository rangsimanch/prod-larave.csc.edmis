@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.fileManager.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.file-managers.update", [$fileManager->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="file_name">{{ trans('cruds.fileManager.fields.file_name') }}</label>
                <input class="form-control {{ $errors->has('file_name') ? 'is-invalid' : '' }}" type="text" name="file_name" id="file_name" value="{{ old('file_name', $fileManager->file_name) }}">
                @if($errors->has('file_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('file_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.fileManager.fields.file_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="code">{{ trans('cruds.fileManager.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', $fileManager->code) }}">
                @if($errors->has('code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.fileManager.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="file_upload">{{ trans('cruds.fileManager.fields.file_upload') }}</label>
                <div class="needsclick dropzone {{ $errors->has('file_upload') ? 'is-invalid' : '' }}" id="file_upload-dropzone">
                </div>
                @if($errors->has('file_upload'))
                    <div class="invalid-feedback">
                        {{ $errors->first('file_upload') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.fileManager.fields.file_upload_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="construction_contract_id">{{ trans('cruds.fileManager.fields.construction_contract') }}</label>
                <select class="form-control select2 {{ $errors->has('construction_contract') ? 'is-invalid' : '' }}" name="construction_contract_id" id="construction_contract_id">
                    @foreach($construction_contracts as $id => $construction_contract)
                        <option value="{{ $id }}" {{ ($fileManager->construction_contract ? $fileManager->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                    @endforeach
                </select>
                @if($errors->has('construction_contract_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('construction_contract_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.fileManager.fields.construction_contract_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    var uploadedFileUploadMap = {}
Dropzone.options.fileUploadDropzone = {
    url: '{{ route('admin.file-managers.storeMedia') }}',
    maxFilesize: 100, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 100
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
@if(isset($fileManager) && $fileManager->file_upload)
          var files =
            {!! json_encode($fileManager->file_upload) !!}
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