@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.dailyConstructionActivity.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.daily-construction-activities.update", [$dailyConstructionActivity->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('work_title') ? 'has-error' : '' }}">
                            <label for="work_title">{{ trans('cruds.dailyConstructionActivity.fields.work_title') }}</label>
                            <input class="form-control" type="text" name="work_title" id="work_title" value="{{ old('work_title', $dailyConstructionActivity->work_title) }}">
                            @if($errors->has('work_title'))
                                <span class="help-block" role="alert">{{ $errors->first('work_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyConstructionActivity.fields.work_title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('operation_date') ? 'has-error' : '' }}">
                            <label for="operation_date">{{ trans('cruds.dailyConstructionActivity.fields.operation_date') }}</label>
                            <input class="form-control date" type="text" name="operation_date" id="operation_date" value="{{ old('operation_date', $dailyConstructionActivity->operation_date) }}">
                            @if($errors->has('operation_date'))
                                <span class="help-block" role="alert">{{ $errors->first('operation_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyConstructionActivity.fields.operation_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.dailyConstructionActivity.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ ($dailyConstructionActivity->construction_contract ? $dailyConstructionActivity->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyConstructionActivity.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('image_upload') ? 'has-error' : '' }}">
                            <label for="image_upload">{{ trans('cruds.dailyConstructionActivity.fields.image_upload') }}</label>
                            <div class="needsclick dropzone" id="image_upload-dropzone">
                            </div>
                            @if($errors->has('image_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('image_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.dailyConstructionActivity.fields.image_upload_helper') }}</span>
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
    var uploadedImageUploadMap = {}
Dropzone.options.imageUploadDropzone = {
    url: '{{ route('admin.daily-construction-activities.storeMedia') }}',
    maxFilesize: 2000, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2000,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="image_upload[]" value="' + response.name + '">')
      uploadedImageUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedImageUploadMap[file.name]
      }
      $('form').find('input[name="image_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($dailyConstructionActivity) && $dailyConstructionActivity->image_upload)
      var files =
        {!! json_encode($dailyConstructionActivity->image_upload) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="image_upload[]" value="' + file.file_name + '">')
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