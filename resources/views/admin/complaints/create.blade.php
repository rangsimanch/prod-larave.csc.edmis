@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.complaint.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.complaints.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.complaint.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label class="required" for="document_number">{{ trans('cruds.complaint.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', '') }}" required>
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.document_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complaint_recipient') ? 'has-error' : '' }}">
                            <label class="required" for="complaint_recipient_id">{{ trans('cruds.complaint.fields.complaint_recipient') }}</label>
                            <select class="form-control select2" name="complaint_recipient_id" id="complaint_recipient_id" required>
                                @foreach($complaint_recipients as $id => $complaint_recipient)
                                    <option value="{{ $id }}" {{ old('complaint_recipient_id') == $id ? 'selected' : '' }}>{{ $complaint_recipient }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('complaint_recipient'))
                                <span class="help-block" role="alert">{{ $errors->first('complaint_recipient') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complaint_recipient_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('received_date') ? 'has-error' : '' }}">
                            <label class="required" for="received_date">{{ trans('cruds.complaint.fields.received_date') }}</label>
                            <input class="form-control date" type="text" name="received_date" id="received_date" value="{{ old('received_date') }}" required>
                            @if($errors->has('received_date'))
                                <span class="help-block" role="alert">{{ $errors->first('received_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.received_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('source_code') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.complaint.fields.source_code') }}</label>
                            <select class="form-control" name="source_code" id="source_code" required>
                                <option value disabled {{ old('source_code', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Complaint::SOURCE_CODE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('source_code', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('source_code'))
                                <span class="help-block" role="alert">{{ $errors->first('source_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.source_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_attachment_create') ? 'has-error' : '' }}">
                            <label for="file_attachment_create">{{ trans('cruds.complaint.fields.file_attachment_create') }}</label>
                            <div class="needsclick dropzone" id="file_attachment_create-dropzone">
                            </div>
                            @if($errors->has('file_attachment_create'))
                                <span class="help-block" role="alert">{{ $errors->first('file_attachment_create') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.file_attachment_create_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complainant') ? 'has-error' : '' }}">
                            <label for="complainant">{{ trans('cruds.complaint.fields.complainant') }}</label>
                            <input class="form-control" type="text" name="complainant" id="complainant" value="{{ old('complainant', '') }}">
                            @if($errors->has('complainant'))
                                <span class="help-block" role="alert">{{ $errors->first('complainant') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complainant_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complainant_tel') ? 'has-error' : '' }}">
                            <label for="complainant_tel">{{ trans('cruds.complaint.fields.complainant_tel') }}</label>
                            <input class="form-control" type="text" name="complainant_tel" id="complainant_tel" value="{{ old('complainant_tel', '') }}">
                            @if($errors->has('complainant_tel'))
                                <span class="help-block" role="alert">{{ $errors->first('complainant_tel') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complainant_tel_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complainant_detail') ? 'has-error' : '' }}">
                            <label for="complainant_detail">{{ trans('cruds.complaint.fields.complainant_detail') }}</label>
                            <input class="form-control" type="text" name="complainant_detail" id="complainant_detail" value="{{ old('complainant_detail', '') }}">
                            @if($errors->has('complainant_detail'))
                                <span class="help-block" role="alert">{{ $errors->first('complainant_detail') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complainant_detail_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complaint_description') ? 'has-error' : '' }}">
                            <label for="complaint_description">{{ trans('cruds.complaint.fields.complaint_description') }}</label>
                            <textarea class="form-control" name="complaint_description" id="complaint_description">{{ old('complaint_description') }}</textarea>
                            @if($errors->has('complaint_description'))
                                <span class="help-block" role="alert">{{ $errors->first('complaint_description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complaint_description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('type_code') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.complaint.fields.type_code') }}</label>
                            <select class="form-control" name="type_code" id="type_code" required>
                                <option value disabled {{ old('type_code', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Complaint::TYPE_CODE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type_code', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type_code'))
                                <span class="help-block" role="alert">{{ $errors->first('type_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.type_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('impact_code') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.complaint.fields.impact_code') }}</label>
                            <select class="form-control" name="impact_code" id="impact_code" required>
                                <option value disabled {{ old('impact_code', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Complaint::IMPACT_CODE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('impact_code', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('impact_code'))
                                <span class="help-block" role="alert">{{ $errors->first('impact_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.impact_code_helper') }}</span>
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
    var uploadedFileAttachmentCreateMap = {}
Dropzone.options.fileAttachmentCreateDropzone = {
    url: '{{ route('admin.complaints.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="file_attachment_create[]" value="' + response.name + '">')
      uploadedFileAttachmentCreateMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFileAttachmentCreateMap[file.name]
      }
      $('form').find('input[name="file_attachment_create[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($complaint) && $complaint->file_attachment_create)
          var files =
            {!! json_encode($complaint->file_attachment_create) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="file_attachment_create[]" value="' + file.file_name + '">')
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