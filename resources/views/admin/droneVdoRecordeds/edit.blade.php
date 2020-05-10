@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.droneVdoRecorded.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.drone-vdo-recordeds.update", [$droneVdoRecorded->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('work_tiltle') ? 'has-error' : '' }}">
                            <label for="work_tiltle">{{ trans('cruds.droneVdoRecorded.fields.work_tiltle') }}</label>
                            <input class="form-control" type="text" name="work_tiltle" id="work_tiltle" value="{{ old('work_tiltle', $droneVdoRecorded->work_tiltle) }}">
                            @if($errors->has('work_tiltle'))
                                <span class="help-block" role="alert">{{ $errors->first('work_tiltle') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.droneVdoRecorded.fields.work_tiltle_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
                            <label for="details">{{ trans('cruds.droneVdoRecorded.fields.details') }}</label>
                            <textarea class="form-control" name="details" id="details">{{ old('details', $droneVdoRecorded->details) }}</textarea>
                            @if($errors->has('details'))
                                <span class="help-block" role="alert">{{ $errors->first('details') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.droneVdoRecorded.fields.details_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('operation_date') ? 'has-error' : '' }}">
                            <label for="operation_date">{{ trans('cruds.droneVdoRecorded.fields.operation_date') }}</label>
                            <input class="form-control date" type="text" name="operation_date" id="operation_date" value="{{ old('operation_date', $droneVdoRecorded->operation_date) }}">
                            @if($errors->has('operation_date'))
                                <span class="help-block" role="alert">{{ $errors->first('operation_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.droneVdoRecorded.fields.operation_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.droneVdoRecorded.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ ($droneVdoRecorded->construction_contract ? $droneVdoRecorded->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.droneVdoRecorded.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.droneVdoRecorded.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.droneVdoRecorded.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.drone-vdo-recordeds.storeMedia') }}',
    maxFilesize: 5000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5000
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
@if(isset($droneVdoRecorded) && $droneVdoRecorded->file_upload)
          var files =
            {!! json_encode($droneVdoRecorded->file_upload) !!}
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