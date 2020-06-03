@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.checkSheet.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.check-sheets.update", [$checkSheet->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('work_type') ? 'has-error' : '' }}">
                            <label for="work_type_id">{{ trans('cruds.checkSheet.fields.work_type') }}</label>
                            <select class="form-control select2" name="work_type_id" id="work_type_id">
                                @foreach($work_types as $id => $work_type)
                                    <option value="{{ $id }}" {{ ($checkSheet->work_type ? $checkSheet->work_type->id : old('work_type_id')) == $id ? 'selected' : '' }}>{{ $work_type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('work_type'))
                                <span class="help-block" role="alert">{{ $errors->first('work_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.work_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('work_title') ? 'has-error' : '' }}">
                            <label for="work_title">{{ trans('cruds.checkSheet.fields.work_title') }}</label>
                            <input class="form-control" type="text" name="work_title" id="work_title" value="{{ old('work_title', $checkSheet->work_title) }}">
                            @if($errors->has('work_title'))
                                <span class="help-block" role="alert">{{ $errors->first('work_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.work_title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">{{ trans('cruds.checkSheet.fields.location') }}</label>
                            <input class="form-control" type="text" name="location" id="location" value="{{ old('location', $checkSheet->location) }}">
                            @if($errors->has('location'))
                                <span class="help-block" role="alert">{{ $errors->first('location') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('date_of_work_done') ? 'has-error' : '' }}">
                            <label for="date_of_work_done">{{ trans('cruds.checkSheet.fields.date_of_work_done') }}</label>
                            <input class="form-control date" type="text" name="date_of_work_done" id="date_of_work_done" value="{{ old('date_of_work_done', $checkSheet->date_of_work_done) }}">
                            @if($errors->has('date_of_work_done'))
                                <span class="help-block" role="alert">{{ $errors->first('date_of_work_done') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.date_of_work_done_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('name_of_inspector') ? 'has-error' : '' }}">
                            <label for="name_of_inspector_id">{{ trans('cruds.checkSheet.fields.name_of_inspector') }}</label>
                            <select class="form-control select2" name="name_of_inspector_id" id="name_of_inspector_id">
                                @foreach($name_of_inspectors as $id => $name_of_inspector)
                                    <option value="{{ $id }}" {{ ($checkSheet->name_of_inspector ? $checkSheet->name_of_inspector->id : old('name_of_inspector_id')) == $id ? 'selected' : '' }}>{{ $name_of_inspector }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('name_of_inspector'))
                                <span class="help-block" role="alert">{{ $errors->first('name_of_inspector') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.name_of_inspector_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('thai_or_chinese_work') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.checkSheet.fields.thai_or_chinese_work') }}</label>
                            <select class="form-control" name="thai_or_chinese_work" id="thai_or_chinese_work">
                                <option value disabled {{ old('thai_or_chinese_work', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\CheckSheet::THAI_OR_CHINESE_WORK_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('thai_or_chinese_work', $checkSheet->thai_or_chinese_work) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('thai_or_chinese_work'))
                                <span class="help-block" role="alert">{{ $errors->first('thai_or_chinese_work') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.thai_or_chinese_work_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.checkSheet.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ ($checkSheet->construction_contract ? $checkSheet->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                            <label for="note">{{ trans('cruds.checkSheet.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note', $checkSheet->note) }}</textarea>
                            @if($errors->has('note'))
                                <span class="help-block" role="alert">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.note_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.checkSheet.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkSheet.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.check-sheets.storeMedia') }}',
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
@if(isset($checkSheet) && $checkSheet->file_upload)
          var files =
            {!! json_encode($checkSheet->file_upload) !!}
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