@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.provisionalSum.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.provisional-sums.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('bill_no') ? 'has-error' : '' }}">
                            <label for="bill_no">{{ trans('cruds.provisionalSum.fields.bill_no') }}</label>
                            <input class="form-control" type="text" name="bill_no" id="bill_no" value="{{ old('bill_no', '') }}">
                            @if($errors->has('bill_no'))
                                <span class="help-block" role="alert">{{ $errors->first('bill_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.provisionalSum.fields.bill_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('item_no') ? 'has-error' : '' }}">
                            <label for="item_no">{{ trans('cruds.provisionalSum.fields.item_no') }}</label>
                            <input class="form-control" type="text" name="item_no" id="item_no" value="{{ old('item_no', '') }}">
                            @if($errors->has('item_no'))
                                <span class="help-block" role="alert">{{ $errors->first('item_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.provisionalSum.fields.item_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.provisionalSum.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.provisionalSum.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('name_of_ps') ? 'has-error' : '' }}">
                            <label for="name_of_ps">{{ trans('cruds.provisionalSum.fields.name_of_ps') }}</label>
                            <input class="form-control" type="text" name="name_of_ps" id="name_of_ps" value="{{ old('name_of_ps', '') }}">
                            @if($errors->has('name_of_ps'))
                                <span class="help-block" role="alert">{{ $errors->first('name_of_ps') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.provisionalSum.fields.name_of_ps_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.provisionalSum.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.provisionalSum.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.provisional-sums.storeMedia') }}',
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
@if(isset($provisionalSum) && $provisionalSum->file_upload)
          var files =
            {!! json_encode($provisionalSum->file_upload) !!}
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