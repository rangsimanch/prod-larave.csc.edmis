@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.ncn.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.ncns.update", [$ncn->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.ncn.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('construction_contract_id') ? old('construction_contract_id') : $ncn->construction_contract->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="required" for="title">{{ trans('cruds.ncn.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $ncn->title) }}" required>
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('dept_code') ? 'has-error' : '' }}">
                            <label class="required" for="dept_code_id">{{ trans('cruds.ncn.fields.dept_code') }}</label>
                            <select class="form-control select2" name="dept_code_id" id="dept_code_id" required>
                                @foreach($dept_codes as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('dept_code_id') ? old('dept_code_id') : $ncn->dept_code->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('dept_code'))
                                <span class="help-block" role="alert">{{ $errors->first('dept_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.dept_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <label class="required" for="issue_date">{{ trans('cruds.ncn.fields.issue_date') }}</label>
                            <input class="form-control date" type="text" name="issue_date" id="issue_date" value="{{ old('issue_date', $ncn->issue_date) }}" required>
                            @if($errors->has('issue_date'))
                                <span class="help-block" role="alert">{{ $errors->first('issue_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.issue_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.ncn.fields.description') }}</label>
                            <textarea class="form-control ckeditor" name="description" id="description">{!! old('description', $ncn->description) !!}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description_image') ? 'has-error' : '' }}">
                            <label for="description_image">{{ trans('cruds.ncn.fields.description_image') }}</label>
                            <div class="needsclick dropzone" id="description_image-dropzone">
                            </div>
                            @if($errors->has('description_image'))
                                <span class="help-block" role="alert">{{ $errors->first('description_image') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.description_image_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment_description') ? 'has-error' : '' }}">
                            <label for="attachment_description">{{ trans('cruds.ncn.fields.attachment_description') }}</label>
                            <textarea class="form-control ckeditor" name="attachment_description" id="attachment_description">{!! old('attachment_description', $ncn->attachment_description) !!}</textarea>
                            @if($errors->has('attachment_description'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment_description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.attachment_description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('pages_of_attachment') ? 'has-error' : '' }}">
                            <label for="pages_of_attachment">{{ trans('cruds.ncn.fields.pages_of_attachment') }}</label>
                            <input class="form-control" type="number" name="pages_of_attachment" id="pages_of_attachment" value="{{ old('pages_of_attachment', $ncn->pages_of_attachment) }}" step="1">
                            @if($errors->has('pages_of_attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('pages_of_attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.pages_of_attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_attachment') ? 'has-error' : '' }}">
                            <label for="file_attachment">{{ trans('cruds.ncn.fields.file_attachment') }}</label>
                            <div class="needsclick dropzone" id="file_attachment-dropzone">
                            </div>
                            @if($errors->has('file_attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('file_attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.file_attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('acceptance_date') ? 'has-error' : '' }}">
                            <label for="acceptance_date">{{ trans('cruds.ncn.fields.acceptance_date') }}</label>
                            <input class="form-control date" type="text" name="acceptance_date" id="acceptance_date" value="{{ old('acceptance_date', $ncn->acceptance_date) }}">
                            @if($errors->has('acceptance_date'))
                                <span class="help-block" role="alert">{{ $errors->first('acceptance_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.acceptance_date_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('issue_by') ? 'has-error' : '' }}">
                            <label for="issue_by_id">{{ trans('cruds.ncn.fields.issue_by') }}</label>
                            <select class="form-control select2" name="issue_by_id" id="issue_by_id">
                                @foreach($issue_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('issue_by_id') ? old('issue_by_id') : $ncn->issue_by->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('issue_by'))
                                <span class="help-block" role="alert">{{ $errors->first('issue_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.issue_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('leader') ? 'has-error' : '' }}">
                            <label for="leader_id">{{ trans('cruds.ncn.fields.leader') }}</label>
                            <select class="form-control select2" name="leader_id" id="leader_id">
                                @foreach($leaders as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('leader_id') ? old('leader_id') : $ncn->leader->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('leader'))
                                <span class="help-block" role="alert">{{ $errors->first('leader') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.leader_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_specialist') ? 'has-error' : '' }}">
                            <label for="construction_specialist_id">{{ trans('cruds.ncn.fields.construction_specialist') }}</label>
                            <select class="form-control select2" name="construction_specialist_id" id="construction_specialist_id">
                                @foreach($construction_specialists as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('construction_specialist_id') ? old('construction_specialist_id') : $ncn->construction_specialist->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_specialist'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_specialist') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncn.fields.construction_specialist_helper') }}</span>
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
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.ncns.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $ncn->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    var uploadedDescriptionImageMap = {}
Dropzone.options.descriptionImageDropzone = {
    url: '{{ route('admin.ncns.storeMedia') }}',
    maxFilesize: 500, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="description_image[]" value="' + response.name + '">')
      uploadedDescriptionImageMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDescriptionImageMap[file.name]
      }
      $('form').find('input[name="description_image[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ncn) && $ncn->description_image)
      var files = {!! json_encode($ncn->description_image) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="description_image[]" value="' + file.file_name + '">')
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
<script>
    var uploadedFileAttachmentMap = {}
Dropzone.options.fileAttachmentDropzone = {
    url: '{{ route('admin.ncns.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    acceptedFiles: '.pdf',
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="file_attachment[]" value="' + response.name + '">')
      uploadedFileAttachmentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFileAttachmentMap[file.name]
      }
      $('form').find('input[name="file_attachment[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ncn) && $ncn->file_attachment)
          var files =
            {!! json_encode($ncn->file_attachment) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="file_attachment[]" value="' + file.file_name + '">')
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