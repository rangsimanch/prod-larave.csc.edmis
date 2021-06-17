@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.requestForInformation.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.request-for-informations.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.requestForInformation.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="required" for="title">{{ trans('cruds.requestForInformation.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.title_helper') }}</span>
                        </div>
                       
                        <div class="form-group {{ $errors->has('originator_code') ? 'has-error' : '' }}">
                            <label for="originator_code">{{ trans('cruds.requestForInformation.fields.originator_code') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon">RFI-</span>
                            <input class="form-control" type="text" name="originator_code" id="originator_code" value="{{ old('originator_code', '') }}" placeholder="Example : 0000, XXXX">
                            @if($errors->has('originator_code'))
                                <span class="help-block" role="alert">{{ $errors->first('originator_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.originator_code_helper') }}</span>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label for="date">{{ trans('cruds.requestForInformation.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date') }}">
                            @if($errors->has('date'))
                                <span class="help-block" role="alert">{{ $errors->first('date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('to') ? 'has-error' : '' }}">
                            <label for="to_id">{{ trans('cruds.requestForInformation.fields.to') }}</label>
                            <select class="form-control select2" name="to_id" id="to_id">
                                @foreach($tos as $id => $to)
                                    <option value="{{ $id }}" {{ old('to_id') == $id ? 'selected' : '' }}>{{ $to }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('to'))
                                <span class="help-block" role="alert">{{ $errors->first('to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.to_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('document_type') ? 'has-error' : '' }}">
                            <label for="document_type_id">{{ trans('cruds.requestForInformation.fields.document_type') }}</label>
                            <select class="form-control select2" name="document_type_id" id="document_type_id">
                                @foreach($document_types as $id => $document_type)
                                    <option value="{{ $id }}" {{ old('document_type_id') == $id ? 'selected' : '' }}>{{ $document_type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('document_type'))
                                <span class="help-block" role="alert">{{ $errors->first('document_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.document_type_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('wbs_level_4') ? 'has-error' : '' }}">
                            <label for="wbs_level_4_id">{{ trans('cruds.requestForInformation.fields.wbs_level_4') }}</label>
                            <select class="form-control select2 wbslv3" name="wbs_level_4_id" id="wbs_level_4_id">
                                @foreach($wbs_level_4s as $id => $wbs_level_4)
                                    <option value="{{ $id }}" {{ old('wbs_level_4_id') == $id ? 'selected' : '' }}>{{ $wbs_level_4 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_4'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_4') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.wbs_level_4_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_5') ? 'has-error' : '' }}">
                            <label for="wbs_level_5_id">{{ trans('cruds.requestForInformation.fields.wbs_level_5') }}</label>
                            <select class="form-control select2 wbslv4" name="wbs_level_5_id" id="wbs_level_5_id">
                                @foreach($wbs_level_5s as $id => $wbs_level_5)
                                    <option value="{{ $id }}" {{ old('wbs_level_5_id') == $id ? 'selected' : '' }}>{{ $wbs_level_5 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_5'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_5') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.wbs_level_5_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('request_by') ? 'has-error' : '' }}">
                            <label for="request_by_id">{{ trans('cruds.requestForInformation.fields.request_by') }}</label>
                            <select class="form-control select2" name="request_by_id" id="request_by_id">
                                @foreach($request_bies as $id => $request_by)
                                    <option value="{{ $id }}" {{ old('request_by_id') == $id ? 'selected' : '' }}>{{ $request_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('request_by'))
                                <span class="help-block" role="alert">{{ $errors->first('request_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.request_by_helper') }}</span>
                        </div>
                       
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.requestForInformation.fields.description') }}</label>
                            <textarea class="form-control ckeditor" name="description" id="description">{!! old('description') !!}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment_files') ? 'has-error' : '' }}">
                            <label for="attachment_files">{{ trans('cruds.requestForInformation.fields.attachment_files') }}</label>
                            <div class="needsclick dropzone" id="attachment_files-dropzone">
                            </div>
                            @if($errors->has('attachment_files'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment_files') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.attachment_files_helper') }}</span>
                        </div>

                        <div class="form-row">
                            <a class="btn btn-default" href="{{ route('admin.request-for-informations.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                            <button class="btn btn-success" type="submit" name="save_form" id="save_form" data-flag="0">
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
                xhr.open('POST', '/admin/request-for-informations/ckmedia', true);
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
                data.append('crud_id', '{{ $requestForInformation->id ?? 0 }}');
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
    var uploadedAttachmentFilesMap = {}
Dropzone.options.attachmentFilesDropzone = {
    url: '{{ route('admin.request-for-informations.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attachment_files[]" value="' + response.name + '">')
      uploadedAttachmentFilesMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttachmentFilesMap[file.name]
      }
      $('form').find('input[name="attachment_files[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($requestForInformation) && $requestForInformation->attachment_files)
          var files =
            {!! json_encode($requestForInformation->attachment_files) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attachment_files[]" value="' + file.file_name + '">')
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

    $('.wbslv3').change(function(){
        if($(this).val() != ''){
            var select = $(this).val();
            console.log(select);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('admin.rfas.fetch') }}",
                method:"POST",
                data:{select:select , _token:_token},
                success:function(result){
                    //Action

                    $('.wbslv4').html(result);
                    console.log(result);
                }
            })
        }
    });
</script>
@endsection