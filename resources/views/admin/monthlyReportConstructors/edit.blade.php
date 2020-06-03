@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.monthlyReportConstructor.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.monthly-report-constructors.update", [$monthlyReportConstructor->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('for_month') ? 'has-error' : '' }}">
                            <label class="required" for="for_month">{{ trans('cruds.monthlyReportConstructor.fields.for_month') }}</label>
                            <input class="form-control date" type="text" name="for_month" id="for_month" value="{{ old('for_month', $monthlyReportConstructor->for_month) }}" required>
                            @if($errors->has('for_month'))
                                <span class="help-block" role="alert">{{ $errors->first('for_month') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.monthlyReportConstructor.fields.for_month_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_uplaod') ? 'has-error' : '' }}">
                            <label for="file_uplaod">{{ trans('cruds.monthlyReportConstructor.fields.file_uplaod') }}</label>
                            <div class="needsclick dropzone" id="file_uplaod-dropzone">
                            </div>
                            @if($errors->has('file_uplaod'))
                                <span class="help-block" role="alert">{{ $errors->first('file_uplaod') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.monthlyReportConstructor.fields.file_uplaod_helper') }}</span>
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
    var uploadedFileUplaodMap = {}
Dropzone.options.fileUplaodDropzone = {
    url: '{{ route('admin.monthly-report-constructors.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="file_uplaod[]" value="' + response.name + '">')
      uploadedFileUplaodMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFileUplaodMap[file.name]
      }
      $('form').find('input[name="file_uplaod[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($monthlyReportConstructor) && $monthlyReportConstructor->file_uplaod)
          var files =
            {!! json_encode($monthlyReportConstructor->file_uplaod) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="file_uplaod[]" value="' + file.file_name + '">')
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