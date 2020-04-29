@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.departmentDocument.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.department-documents.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('document_name') ? 'has-error' : '' }}">
                            <label for="document_name">{{ trans('cruds.departmentDocument.fields.document_name') }}</label>
                            <input class="form-control" type="text" name="document_name" id="document_name" value="{{ old('document_name', '') }}">
                            @if($errors->has('document_name'))
                                <span class="help-block" role="alert">{{ $errors->first('document_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.departmentDocument.fields.document_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
                            <label for="tags">{{ trans('cruds.departmentDocument.fields.tag') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="tags[]" id="tags" multiple>
                                @foreach($tags as $id => $tag)
                                    <option value="{{ $id }}" {{ in_array($id, old('tags', [])) ? 'selected' : '' }}>{{ $tag }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tags'))
                                <span class="help-block" role="alert">{{ $errors->first('tags') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.departmentDocument.fields.tag_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.departmentDocument.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.departmentDocument.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('download') ? 'has-error' : '' }}">
                            <label for="download">{{ trans('cruds.departmentDocument.fields.download') }}</label>
                            <div class="needsclick dropzone" id="download-dropzone">
                            </div>
                            @if($errors->has('download'))
                                <span class="help-block" role="alert">{{ $errors->first('download') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.departmentDocument.fields.download_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('example_file') ? 'has-error' : '' }}">
                            <label for="example_file">{{ trans('cruds.departmentDocument.fields.example_file') }}</label>
                            <div class="needsclick dropzone" id="example_file-dropzone">
                            </div>
                            @if($errors->has('example_file'))
                                <span class="help-block" role="alert">{{ $errors->first('example_file') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.departmentDocument.fields.example_file_helper') }}</span>
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
    var uploadedDownloadMap = {}
Dropzone.options.downloadDropzone = {
    url: '{{ route('admin.department-documents.storeMedia') }}',
    maxFilesize: 200, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 200
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="download[]" value="' + response.name + '">')
      uploadedDownloadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDownloadMap[file.name]
      }
      $('form').find('input[name="download[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($departmentDocument) && $departmentDocument->download)
          var files =
            {!! json_encode($departmentDocument->download) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="download[]" value="' + file.file_name + '">')
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
    var uploadedExampleFileMap = {}
Dropzone.options.exampleFileDropzone = {
    url: '{{ route('admin.department-documents.storeMedia') }}',
    maxFilesize: 200, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 200
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="example_file[]" value="' + response.name + '">')
      uploadedExampleFileMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedExampleFileMap[file.name]
      }
      $('form').find('input[name="example_file[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($departmentDocument) && $departmentDocument->example_file)
          var files =
            {!! json_encode($departmentDocument->example_file) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="example_file[]" value="' + file.file_name + '">')
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