@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.dailyRequest.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.daily-requests.update", [$dailyRequest->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('input_date') ? 'has-error' : '' }}">
                            <label for="input_date">{{ trans('cruds.dailyRequest.fields.input_date') }}</label>
                            <input class="form-control date" type="text" name="input_date" id="input_date" value="{{ old('input_date', $dailyRequest->input_date) }}">
                            @if($errors->has('input_date'))
                                <span class="help-block" role="alert">{{ $errors->first('input_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyRequest.fields.input_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('documents') ? 'has-error' : '' }}">
                            <label for="documents">{{ trans('cruds.dailyRequest.fields.documents') }}</label>
                            <div class="needsclick dropzone" id="documents-dropzone">
                            </div>
                            @if($errors->has('documents'))
                                <span class="help-block" role="alert">{{ $errors->first('documents') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyRequest.fields.documents_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('acknowledge') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="acknowledge" value="0">
                                <input type="checkbox" name="acknowledge" id="acknowledge" value="1" {{ $dailyRequest->acknowledge || old('acknowledge', 0) === 1 ? 'checked' : '' }}>
                                <label for="acknowledge" style="font-weight: 400">{{ trans('cruds.dailyRequest.fields.acknowledge') }}</label>
                            </div>
                            @if($errors->has('acknowledge'))
                                <span class="help-block" role="alert">{{ $errors->first('acknowledge') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyRequest.fields.acknowledge_helper') }}</span>
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
    var uploadedDocumentsMap = {}
Dropzone.options.documentsDropzone = {
    url: '{{ route('admin.daily-requests.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="documents[]" value="' + response.name + '">')
      uploadedDocumentsMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDocumentsMap[file.name]
      }
      $('form').find('input[name="documents[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($dailyRequest) && $dailyRequest->documents)
          var files =
            {!! json_encode($dailyRequest->documents) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="documents[]" value="' + file.file_name + '">')
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