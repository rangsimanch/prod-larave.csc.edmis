@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.ncr.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.ncrs.store") }}" enctype="multipart/form-data">
                        @csrf

                        <legend> Section 2 : Response & Corrective Action by Contractor </legend>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.ncr.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $entry)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('corresponding_ncn') ? 'has-error' : '' }}">
                            <label for="corresponding_ncn_id">{{ trans('cruds.ncr.fields.corresponding_ncn') }}</label>
                            <select class="form-control select2" name="corresponding_ncn_id" id="corresponding_ncn_id">
                                @foreach($corresponding_ncns as $id => $entry)
                                    <option value="{{ $id }}" {{ old('corresponding_ncn_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('corresponding_ncn'))
                                <span class="help-block" role="alert">{{ $errors->first('corresponding_ncn') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.corresponding_ncn_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label for="document_number">{{ trans('cruds.ncr.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', '') }}">
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.document_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('acceptance_date') ? 'has-error' : '' }}">
                            <label for="acceptance_date">{{ trans('cruds.ncr.fields.acceptance_date') }}</label>
                            <input class="form-control date" type="text" name="acceptance_date" id="acceptance_date" value="{{ old('acceptance_date') }}">
                            @if($errors->has('acceptance_date'))
                                <span class="help-block" role="alert">{{ $errors->first('acceptance_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.acceptance_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('root_case') ? 'has-error' : '' }}">
                            <label for="root_case">{{ trans('cruds.ncr.fields.root_case') }}</label>
                            <textarea class="form-control ckeditor" name="root_case" id="root_case">{!! old('root_case') !!}</textarea>
                            @if($errors->has('root_case'))
                                <span class="help-block" role="alert">{{ $errors->first('root_case') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.root_case_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('rootcase_image') ? 'has-error' : '' }}">
                            <label for="rootcase_image">{{ trans('cruds.ncr.fields.rootcase_image') }}</label>
                            <div class="needsclick dropzone" id="rootcase_image-dropzone">
                            </div>
                            @if($errors->has('rootcase_image'))
                                <span class="help-block" role="alert">{{ $errors->first('rootcase_image') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.rootcase_image_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('containment_action') ? 'has-error' : '' }}">
                            <label for="containment_action">{{ trans('cruds.ncr.fields.containment_action') }}</label>
                            <textarea class="form-control ckeditor" name="containment_action" id="containment_action">{!! old('containment_action') !!}</textarea>
                            @if($errors->has('containment_action'))
                                <span class="help-block" role="alert">{{ $errors->first('containment_action') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.containment_action_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('containment_image') ? 'has-error' : '' }}">
                            <label for="containment_image">{{ trans('cruds.ncr.fields.containment_image') }}</label>
                            <div class="needsclick dropzone" id="containment_image-dropzone">
                            </div>
                            @if($errors->has('containment_image'))
                                <span class="help-block" role="alert">{{ $errors->first('containment_image') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.containment_image_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('corrective') ? 'has-error' : '' }}">
                            <label for="corrective">{{ trans('cruds.ncr.fields.corrective') }}</label>
                            <textarea class="form-control ckeditor" name="corrective" id="corrective">{!! old('corrective') !!}</textarea>
                            @if($errors->has('corrective'))
                                <span class="help-block" role="alert">{{ $errors->first('corrective') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.corrective_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('corrective_image') ? 'has-error' : '' }}">
                            <label for="corrective_image">{{ trans('cruds.ncr.fields.corrective_image') }}</label>
                            <div class="needsclick dropzone" id="corrective_image-dropzone">
                            </div>
                            @if($errors->has('corrective_image'))
                                <span class="help-block" role="alert">{{ $errors->first('corrective_image') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.corrective_image_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment_description') ? 'has-error' : '' }}">
                            <label for="attachment_description">{{ trans('cruds.ncr.fields.attachment_description') }}</label>
                            <textarea class="form-control ckeditor" name="attachment_description" id="attachment_description">{!! old('attachment_description') !!}</textarea>
                            @if($errors->has('attachment_description'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment_description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.attachment_description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('pages_of_attachment') ? 'has-error' : '' }}">
                            <label for="pages_of_attachment">{{ trans('cruds.ncr.fields.pages_of_attachment') }}</label>
                            <input class="form-control" type="number" name="pages_of_attachment" id="pages_of_attachment" value="{{ old('pages_of_attachment', '') }}" step="1">
                            @if($errors->has('pages_of_attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('pages_of_attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.pages_of_attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_attachment') ? 'has-error' : '' }}">
                            <label for="file_attachment">{{ trans('cruds.ncr.fields.file_attachment') }}</label>
                            <div class="needsclick dropzone" id="file_attachment-dropzone">
                            </div>
                            @if($errors->has('file_attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('file_attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.file_attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('prepared_by') ? 'has-error' : '' }}">
                            <label for="prepared_by_id">{{ trans('cruds.ncr.fields.prepared_by') }}</label>
                            <select class="form-control select2" name="prepared_by_id" id="prepared_by_id">
                                @foreach($prepared_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ old('prepared_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('prepared_by'))
                                <span class="help-block" role="alert">{{ $errors->first('prepared_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.prepared_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('contractor_manager') ? 'has-error' : '' }}">
                            <label for="contractor_manager_id">{{ trans('cruds.ncr.fields.contractor_manager') }}</label>
                            <select class="form-control select2" name="contractor_manager_id" id="contractor_manager_id">
                                @foreach($contractor_managers as $id => $entry)
                                    <option value="{{ $id }}" {{ old('contractor_manager_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('contractor_manager'))
                                <span class="help-block" role="alert">{{ $errors->first('contractor_manager') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.contractor_manager_helper') }}</span>
                        </div>
                        
                        <br> </br>
                        <legend> Section 3 : Disposition on Corrective Action Result </legend>
                        <div class="form-group {{ $errors->has('documents_status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.ncr.fields.documents_status') }}</label>
                            <select class="form-control" name="documents_status" id="documents_status">
                                <option value disabled {{ old('documents_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Ncr::DOCUMENTS_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('documents_status', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('documents_status'))
                                <span class="help-block" role="alert">{{ $errors->first('documents_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.documents_status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('issue_by') ? 'has-error' : '' }}">
                            <label for="issue_by_id">{{ trans('cruds.ncr.fields.issue_by') }}</label>
                            <select class="form-control select2" name="issue_by_id" id="issue_by_id">
                                @foreach($issue_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ old('issue_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('issue_by'))
                                <span class="help-block" role="alert">{{ $errors->first('issue_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.issue_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_specialist') ? 'has-error' : '' }}">
                            <label for="construction_specialist_id">{{ trans('cruds.ncr.fields.construction_specialist') }}</label>
                            <select class="form-control select2" name="construction_specialist_id" id="construction_specialist_id">
                                @foreach($construction_specialists as $id => $entry)
                                    <option value="{{ $id }}" {{ old('construction_specialist_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_specialist'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_specialist') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.construction_specialist_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('related_specialist') ? 'has-error' : '' }}">
                            <label for="related_specialist_id">{{ trans('cruds.ncr.fields.related_specialist') }}</label>
                            <select class="form-control select2" name="related_specialist_id" id="related_specialist_id">
                                @foreach($related_specialists as $id => $entry)
                                    <option value="{{ $id }}" {{ old('related_specialist_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('related_specialist'))
                                <span class="help-block" role="alert">{{ $errors->first('related_specialist') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.related_specialist_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('leader') ? 'has-error' : '' }}">
                            <label for="leader_id">{{ trans('cruds.ncr.fields.leader') }}</label>
                            <select class="form-control select2" name="leader_id" id="leader_id">
                                @foreach($leaders as $id => $entry)
                                    <option value="{{ $id }}" {{ old('leader_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('leader'))
                                <span class="help-block" role="alert">{{ $errors->first('leader') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.leader_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.ncrs.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $ncr->id ?? 0 }}');
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
    var uploadedRootcaseImageMap = {}
Dropzone.options.rootcaseImageDropzone = {
    url: '{{ route('admin.ncrs.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="rootcase_image[]" value="' + response.name + '">')
      uploadedRootcaseImageMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedRootcaseImageMap[file.name]
      }
      $('form').find('input[name="rootcase_image[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ncr) && $ncr->rootcase_image)
      var files = {!! json_encode($ncr->rootcase_image) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="rootcase_image[]" value="' + file.file_name + '">')
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
    var uploadedContainmentImageMap = {}
Dropzone.options.containmentImageDropzone = {
    url: '{{ route('admin.ncrs.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="containment_image[]" value="' + response.name + '">')
      uploadedContainmentImageMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedContainmentImageMap[file.name]
      }
      $('form').find('input[name="containment_image[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ncr) && $ncr->containment_image)
      var files = {!! json_encode($ncr->containment_image) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="containment_image[]" value="' + file.file_name + '">')
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
    var uploadedCorrectiveImageMap = {}
Dropzone.options.correctiveImageDropzone = {
    url: '{{ route('admin.ncrs.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="corrective_image[]" value="' + response.name + '">')
      uploadedCorrectiveImageMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedCorrectiveImageMap[file.name]
      }
      $('form').find('input[name="corrective_image[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ncr) && $ncr->corrective_image)
      var files = {!! json_encode($ncr->corrective_image) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="corrective_image[]" value="' + file.file_name + '">')
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
    url: '{{ route('admin.ncrs.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
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
@if(isset($ncr) && $ncr->file_attachment)
          var files =
            {!! json_encode($ncr->file_attachment) !!}
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