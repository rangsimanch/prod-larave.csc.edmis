@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.variationOrder.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.variation-orders.update", [$variationOrder->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('type_of_vo') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.variationOrder.fields.type_of_vo') }}</label>
                            <select class="form-control" name="type_of_vo" id="type_of_vo">
                                <option value disabled {{ old('type_of_vo', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\VariationOrder::TYPE_OF_VO_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type_of_vo', $variationOrder->type_of_vo) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type_of_vo'))
                                <span class="help-block" role="alert">{{ $errors->first('type_of_vo') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.variationOrder.fields.type_of_vo_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.variationOrder.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ ($variationOrder->construction_contract ? $variationOrder->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.variationOrder.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('work_title') ? 'has-error' : '' }}">
                            <label for="work_title">{{ trans('cruds.variationOrder.fields.work_title') }}</label>
                            <input class="form-control" type="text" name="work_title" id="work_title" value="{{ old('work_title', $variationOrder->work_title) }}">
                            @if($errors->has('work_title'))
                                <span class="help-block" role="alert">{{ $errors->first('work_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.variationOrder.fields.work_title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('operation_date') ? 'has-error' : '' }}">
                            <label for="operation_date">{{ trans('cruds.variationOrder.fields.operation_date') }}</label>
                            <input class="form-control date" type="text" name="operation_date" id="operation_date" value="{{ old('operation_date', $variationOrder->operation_date) }}">
                            @if($errors->has('operation_date'))
                                <span class="help-block" role="alert">{{ $errors->first('operation_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.variationOrder.fields.operation_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.variationOrder.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.variationOrder.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.variation-orders.storeMedia') }}',
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
@if(isset($variationOrder) && $variationOrder->file_upload)
          var files =
            {!! json_encode($variationOrder->file_upload) !!}
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