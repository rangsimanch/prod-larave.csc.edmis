@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.recordsOfVisitor.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.records-of-visitors.update", [$recordsOfVisitor->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('date_of_visit') ? 'has-error' : '' }}">
                            <label for="date_of_visit">{{ trans('cruds.recordsOfVisitor.fields.date_of_visit') }}</label>
                            <input class="form-control date" type="text" name="date_of_visit" id="date_of_visit" value="{{ old('date_of_visit', $recordsOfVisitor->date_of_visit) }}">
                            @if($errors->has('date_of_visit'))
                                <span class="help-block" role="alert">{{ $errors->first('date_of_visit') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recordsOfVisitor.fields.date_of_visit_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('name_of_visitor') ? 'has-error' : '' }}">
                            <label for="name_of_visitor">{{ trans('cruds.recordsOfVisitor.fields.name_of_visitor') }}</label>
                            <input class="form-control" type="text" name="name_of_visitor" id="name_of_visitor" value="{{ old('name_of_visitor', $recordsOfVisitor->name_of_visitor) }}">
                            @if($errors->has('name_of_visitor'))
                                <span class="help-block" role="alert">{{ $errors->first('name_of_visitor') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recordsOfVisitor.fields.name_of_visitor_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
                            <label for="details">{{ trans('cruds.recordsOfVisitor.fields.details') }}</label>
                            <textarea class="form-control" name="details" id="details">{{ old('details', $recordsOfVisitor->details) }}</textarea>
                            @if($errors->has('details'))
                                <span class="help-block" role="alert">{{ $errors->first('details') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recordsOfVisitor.fields.details_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.recordsOfVisitor.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recordsOfVisitor.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.records-of-visitors.storeMedia') }}',
    maxFilesize: 2000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2000
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
@if(isset($recordsOfVisitor) && $recordsOfVisitor->file_upload)
          var files =
            {!! json_encode($recordsOfVisitor->file_upload) !!}
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