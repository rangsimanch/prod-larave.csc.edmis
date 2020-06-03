@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.addDrawing.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.add-drawings.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('drawing_title') ? 'has-error' : '' }}">
                            <label for="drawing_title">{{ trans('cruds.addDrawing.fields.drawing_title') }}</label>
                            <input class="form-control" type="text" name="drawing_title" id="drawing_title" value="{{ old('drawing_title', '') }}">
                            @if($errors->has('drawing_title'))
                                <span class="help-block" role="alert">{{ $errors->first('drawing_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addDrawing.fields.drawing_title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('activity_type') ? 'has-error' : '' }}">
                            <label for="activity_type_id">{{ trans('cruds.addDrawing.fields.activity_type') }}</label>
                            <select class="form-control select2" name="activity_type_id" id="activity_type_id">
                                @foreach($activity_types as $id => $activity_type)
                                    <option value="{{ $id }}" {{ old('activity_type_id') == $id ? 'selected' : '' }}>{{ $activity_type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('activity_type'))
                                <span class="help-block" role="alert">{{ $errors->first('activity_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addDrawing.fields.activity_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('work_type') ? 'has-error' : '' }}">
                            <label for="work_type_id">{{ trans('cruds.addDrawing.fields.work_type') }}</label>
                            <select class="form-control select2" name="work_type_id" id="work_type_id">
                                @foreach($work_types as $id => $work_type)
                                    <option value="{{ $id }}" {{ old('work_type_id') == $id ? 'selected' : '' }}>{{ $work_type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('work_type'))
                                <span class="help-block" role="alert">{{ $errors->first('work_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addDrawing.fields.work_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('coding_of_drawing') ? 'has-error' : '' }}">
                            <label for="coding_of_drawing">{{ trans('cruds.addDrawing.fields.coding_of_drawing') }}</label>
                            <input class="form-control" type="text" name="coding_of_drawing" id="coding_of_drawing" value="{{ old('coding_of_drawing', '') }}">
                            @if($errors->has('coding_of_drawing'))
                                <span class="help-block" role="alert">{{ $errors->first('coding_of_drawing') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addDrawing.fields.coding_of_drawing_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">{{ trans('cruds.addDrawing.fields.location') }}</label>
                            <input class="form-control" type="text" name="location" id="location" value="{{ old('location', '') }}">
                            @if($errors->has('location'))
                                <span class="help-block" role="alert">{{ $errors->first('location') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addDrawing.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.addDrawing.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addDrawing.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.add-drawings.storeMedia') }}',
    maxFilesize: 1024, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1024
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
@if(isset($addDrawing) && $addDrawing->file_upload)
          var files =
            {!! json_encode($addDrawing->file_upload) !!}
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