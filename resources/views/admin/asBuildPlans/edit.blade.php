@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.asBuildPlan.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.as-build-plans.update", [$asBuildPlan->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('shop_drawing_title') ? 'has-error' : '' }}">
                            <label for="shop_drawing_title">{{ trans('cruds.asBuildPlan.fields.shop_drawing_title') }}</label>
                            <input class="form-control" type="text" name="shop_drawing_title" id="shop_drawing_title" value="{{ old('shop_drawing_title', $asBuildPlan->shop_drawing_title) }}">
                            @if($errors->has('shop_drawing_title'))
                                <span class="help-block" role="alert">{{ $errors->first('shop_drawing_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.asBuildPlan.fields.shop_drawing_title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('drawing_references') ? 'has-error' : '' }}">
                            <label for="drawing_references">{{ trans('cruds.asBuildPlan.fields.drawing_reference') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="drawing_references[]" id="drawing_references" multiple>
                                @foreach($drawing_references as $id => $drawing_reference)
                                    <option value="{{ $id }}" {{ (in_array($id, old('drawing_references', [])) || $asBuildPlan->drawing_references->contains($id)) ? 'selected' : '' }}>{{ $drawing_reference }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('drawing_references'))
                                <span class="help-block" role="alert">{{ $errors->first('drawing_references') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.asBuildPlan.fields.drawing_reference_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('coding_of_shop_drawing') ? 'has-error' : '' }}">
                            <label for="coding_of_shop_drawing">{{ trans('cruds.asBuildPlan.fields.coding_of_shop_drawing') }}</label>
                            <input class="form-control" type="text" name="coding_of_shop_drawing" id="coding_of_shop_drawing" value="{{ old('coding_of_shop_drawing', $asBuildPlan->coding_of_shop_drawing) }}">
                            @if($errors->has('coding_of_shop_drawing'))
                                <span class="help-block" role="alert">{{ $errors->first('coding_of_shop_drawing') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.asBuildPlan.fields.coding_of_shop_drawing_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.asBuildPlan.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ ($asBuildPlan->construction_contract ? $asBuildPlan->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.asBuildPlan.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.asBuildPlan.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.asBuildPlan.fields.file_upload_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('special_file_upload') ? 'has-error' : '' }}">
                            <label for="special_file_upload">{{ trans('cruds.asBuildPlan.fields.special_file_upload') }}</label>
                            <div class="needsclick dropzone" id="special_file_upload-dropzone">
                            </div>
                            @if($errors->has('special_file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('special_file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.asBuildPlan.fields.special_file_upload_helper') }}</span>
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
    url: '{{ route('admin.as-build-plans.storeMedia') }}',
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
@if(isset($asBuildPlan) && $asBuildPlan->file_upload)
          var files =
            {!! json_encode($asBuildPlan->file_upload) !!}
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
<script>
    var uploadedSpecialFileUploadMap = {}
Dropzone.options.specialFileUploadDropzone = {
    url: '{{ route('admin.as-build-plans.storeMedia') }}',
    maxFilesize: 1024, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1024
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="special_file_upload[]" value="' + response.name + '">')
      uploadedSpecialFileUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedSpecialFileUploadMap[file.name]
      }
      $('form').find('input[name="special_file_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($asBuildPlan) && $asBuildPlan->special_file_upload)
          var files =
            {!! json_encode($asBuildPlan->special_file_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="special_file_upload[]" value="' + file.file_name + '">')
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