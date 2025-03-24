@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.dailyRequest.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.daily-requests.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('input_date') ? 'has-error' : '' }}">
                            <label for="input_date">{{ trans('cruds.dailyRequest.fields.input_date') }}</label>
                            <input class="form-control date" type="text" name="input_date" id="input_date" value="{{ old('input_date') }}">
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
                        <div class="form-group {{ $errors->has('document_code') ? 'has-error' : '' }}">
                            <label for="document_code">{{ trans('cruds.dailyRequest.fields.document_code') }}</label>
                            <input class="form-control" type="text" name="document_code" id="document_code" value="{{ old('document_code', '') }}">
                            @if($errors->has('document_code'))
                                <span class="help-block" role="alert">{{ $errors->first('document_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyRequest.fields.document_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('constuction_contract') ? 'has-error' : '' }}">
                            <label for="constuction_contract_id">{{ trans('cruds.dailyRequest.fields.constuction_contract') }}</label>
                            <select class="form-control select2" name="constuction_contract_id" id="constuction_contract_id">
                                @foreach($constuction_contracts as $id => $constuction_contract)
                                    <option value="{{ $id }}" {{ old('constuction_contract_id') == $id ? 'selected' : '' }}>{{ $constuction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('constuction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('constuction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyRequest.fields.constuction_contract_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.daily-requests.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                            <button class="btn btn-success" type="submit">
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
    maxFilesize: 100, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 100
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