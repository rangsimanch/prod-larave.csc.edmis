@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.requestForInformation.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.request-for-informations.update", [$requestForInformation->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                       
                        <div class="form-group {{ $errors->has('outgoing_date') ? 'has-error' : '' }}">
                            <label for="outgoing_date">{{ trans('cruds.requestForInformation.fields.outgoing_date') }}</label>
                            <input class="form-control date" type="text" name="outgoing_date" id="outgoing_date" value="{{ old('outgoing_date', $requestForInformation->outgoing_date) }}">
                            @if($errors->has('outgoing_date'))
                                <span class="help-block" role="alert">{{ $errors->first('outgoing_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.outgoing_date_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('authorised_rep') ? 'has-error' : '' }}">
                            <label for="authorised_rep_id">{{ trans('cruds.requestForInformation.fields.authorised_rep') }}</label>
                            <select class="form-control select2" name="authorised_rep_id" id="authorised_rep_id">
                                @foreach($authorised_reps as $id => $authorised_rep)
                                    <option value="{{ $id }}" {{ (old('authorised_rep_id') ? old('authorised_rep_id') : $requestForInformation->authorised_rep->id ?? '') == $id ? 'selected' : '' }}>{{ $authorised_rep }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('authorised_rep'))
                                <span class="help-block" role="alert">{{ $errors->first('authorised_rep') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.authorised_rep_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('response_organization') ? 'has-error' : '' }}">
                            <label for="response_organization_id">{{ trans('cruds.requestForInformation.fields.response_organization') }}</label>
                            <select class="form-control select2" name="response_organization_id" id="response_organization_id">
                                @foreach($response_organizations as $id => $response_organization)
                                    <option value="{{ $id }}" {{ (old('response_organization_id') ? old('response_organization_id') : $requestForInformation->response_organization->id ?? '') == $id ? 'selected' : '' }}>{{ $response_organization }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('response_organization'))
                                <span class="help-block" role="alert">{{ $errors->first('response_organization') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.response_organization_helper') }}</span>
                        </div>
                       
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.requestForInformation.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.file_upload_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('save_for') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.requestForInformation.fields.save_for') }}</label>
                            <select class="form-control" name="save_for" id="save_for">
                                <option value disabled {{ old('save_for', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\RequestForInformation::SAVE_FOR_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('save_for', $requestForInformation->save_for) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('save_for'))
                                <span class="help-block" role="alert">{{ $errors->first('save_for') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.save_for_helper') }}</span>
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
@if(isset($requestForInformation) && $requestForInformation->file_upload)
          var files =
            {!! json_encode($requestForInformation->file_upload) !!}
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