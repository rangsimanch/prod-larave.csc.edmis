@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.documentsAndAssignment.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.documents-and-assignments.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('file_name') ? 'has-error' : '' }}">
                            <label for="file_name">{{ trans('cruds.documentsAndAssignment.fields.file_name') }}</label>
                            <input class="form-control" type="text" name="file_name" id="file_name" value="{{ old('file_name', '') }}">
                            @if($errors->has('file_name'))
                                <span class="help-block" role="alert">{{ $errors->first('file_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.documentsAndAssignment.fields.file_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('original_no') ? 'has-error' : '' }}">
                            <label for="original_no">{{ trans('cruds.documentsAndAssignment.fields.original_no') }}</label>
                            <input class="form-control" type="text" name="original_no" id="original_no" value="{{ old('original_no', '') }}">
                            @if($errors->has('original_no'))
                                <span class="help-block" role="alert">{{ $errors->first('original_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.documentsAndAssignment.fields.original_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('receipt_no') ? 'has-error' : '' }}">
                            <label for="receipt_no">{{ trans('cruds.documentsAndAssignment.fields.receipt_no') }}</label>
                            <input class="form-control" type="text" name="receipt_no" id="receipt_no" value="{{ old('receipt_no', '') }}">
                            @if($errors->has('receipt_no'))
                                <span class="help-block" role="alert">{{ $errors->first('receipt_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.documentsAndAssignment.fields.receipt_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('date_of_receipt') ? 'has-error' : '' }}">
                            <label for="date_of_receipt">{{ trans('cruds.documentsAndAssignment.fields.date_of_receipt') }}</label>
                            <input class="form-control date" type="text" name="date_of_receipt" id="date_of_receipt" value="{{ old('date_of_receipt') }}">
                            @if($errors->has('date_of_receipt'))
                                <span class="help-block" role="alert">{{ $errors->first('date_of_receipt') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.documentsAndAssignment.fields.date_of_receipt_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.documentsAndAssignment.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.documentsAndAssignment.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.documents-and-assignments.storeMedia') }}',
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
@if(isset($documentsAndAssignment) && $documentsAndAssignment->file_upload)
          var files =
            {!! json_encode($documentsAndAssignment->file_upload) !!}
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