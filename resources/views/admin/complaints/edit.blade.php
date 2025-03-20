@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.complaint.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.complaints.update", [$complaint->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                    <legend><a onclick="HideSection(1)" id="element1"><i class="bi bi-eye"></i></a><b>  Section 1 : Open Complaints</b></legend>
                        <div id="section1">
                        <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label class="required" for="document_number">{{ trans('cruds.complaint.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', $complaint->document_number) }}" required>
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.document_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complaint_recipient') ? 'has-error' : '' }}">
                            <label class="required" for="complaint_recipient_id">{{ trans('cruds.complaint.fields.complaint_recipient') }}</label>
                            <select class="form-control select2" name="complaint_recipient_id" id="complaint_recipient_id" required>
                                @foreach($complaint_recipients as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('complaint_recipient_id') ? old('complaint_recipient_id') : $complaint->complaint_recipient->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('complaint_recipient'))
                                <span class="help-block" role="alert">{{ $errors->first('complaint_recipient') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complaint_recipient_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('received_date') ? 'has-error' : '' }}">
                            <label class="required" for="received_date">{{ trans('cruds.complaint.fields.received_date') }}</label>
                            <input class="form-control date" type="text" name="received_date" id="received_date" value="{{ old('received_date', $complaint->received_date) }}" required>
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
                                    <option value="{{ $key }}" {{ old('source_code', $complaint->source_code) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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
                            <input class="form-control" type="text" name="complainant" id="complainant" value="{{ old('complainant', $complaint->complainant) }}">
                            @if($errors->has('complainant'))
                                <span class="help-block" role="alert">{{ $errors->first('complainant') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complainant_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complainant_tel') ? 'has-error' : '' }}">
                            <label for="complainant_tel">{{ trans('cruds.complaint.fields.complainant_tel') }}</label>
                            <input class="form-control" type="text" name="complainant_tel" id="complainant_tel" value="{{ old('complainant_tel', $complaint->complainant_tel) }}">
                            @if($errors->has('complainant_tel'))
                                <span class="help-block" role="alert">{{ $errors->first('complainant_tel') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complainant_tel_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complainant_detail') ? 'has-error' : '' }}">
                            <label for="complainant_detail">{{ trans('cruds.complaint.fields.complainant_detail') }}</label>
                            <input class="form-control" type="text" name="complainant_detail" id="complainant_detail" value="{{ old('complainant_detail', $complaint->complainant_detail) }}">
                            @if($errors->has('complainant_detail'))
                                <span class="help-block" role="alert">{{ $errors->first('complainant_detail') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.complainant_detail_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complaint_description') ? 'has-error' : '' }}">
                            <label for="complaint_description">{{ trans('cruds.complaint.fields.complaint_description') }}</label>
                            <textarea class="form-control" name="complaint_description" id="complaint_description">{{ old('complaint_description', $complaint->complaint_description) }}</textarea>
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
                                    <option value="{{ $key }}" {{ old('type_code', $complaint->type_code) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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
                                    <option value="{{ $key }}" {{ old('impact_code', $complaint->impact_code) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('impact_code'))
                                <span class="help-block" role="alert">{{ $errors->first('impact_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.impact_code_helper') }}</span>
                        </div>
                        </div>

                        <legend><a onclick="HideSection(2)" id="element2"><i class="bi bi-eye"></i></a><b>  Section 2 : Close Complaints </b></legend>
                        <div id="section2">

                        <div class="form-group {{ $errors->has('operator') ? 'has-error' : '' }}">
                            <label for="operator_id">{{ trans('cruds.complaint.fields.operator') }}</label>
                            <select class="form-control select2" name="operator_id" id="operator_id">
                                @foreach($operators as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('operator_id') ? old('operator_id') : $complaint->operator->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('operator'))
                                <span class="help-block" role="alert">{{ $errors->first('operator') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.operator_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('action_detail') ? 'has-error' : '' }}">
                            <label for="action_detail">{{ trans('cruds.complaint.fields.action_detail') }}</label>
                            <textarea class="form-control" name="action_detail" id="action_detail">{{ old('action_detail', $complaint->action_detail) }}</textarea>
                            @if($errors->has('action_detail'))
                                <span class="help-block" role="alert">{{ $errors->first('action_detail') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.action_detail_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('progress_file') ? 'has-error' : '' }}">
                            <label for="progress_file">{{ trans('cruds.complaint.fields.progress_file') }}</label>
                            <div class="needsclick dropzone" id="progress_file-dropzone">
                            </div>
                            @if($errors->has('progress_file'))
                                <span class="help-block" role="alert">{{ $errors->first('progress_file') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.progress_file_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('action_date') ? 'has-error' : '' }}">
                            <label for="action_date">{{ trans('cruds.complaint.fields.action_date') }}</label>
                            <input class="form-control date" type="text" name="action_date" id="action_date" value="{{ old('action_date', $complaint->action_date) }}">
                            @if($errors->has('action_date'))
                                <span class="help-block" role="alert">{{ $errors->first('action_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.action_date_helper') }}</span>
                        </div>
                    </div>
                     <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.complaint.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Complaint::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $complaint->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.status_helper') }}</span>
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
<script>
    var uploadedProgressFileMap = {}
Dropzone.options.progressFileDropzone = {
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
      $('form').append('<input type="hidden" name="progress_file[]" value="' + response.name + '">')
      uploadedProgressFileMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedProgressFileMap[file.name]
      }
      $('form').find('input[name="progress_file[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($complaint) && $complaint->progress_file)
          var files =
            {!! json_encode($complaint->progress_file) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="progress_file[]" value="' + file.file_name + '">')
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
    const select2 = document.getElementsByClassName("select2");
for (let i = 0; i < select2.length; i++) {
    select2[i].style.cssText = 'width:100%!important;';
}
HideSection(1)
function HideSection(idElement) {
    var element = document.getElementById('element' + idElement);
    if (idElement === 1 || idElement === 2 || idElement === 3 || idElement === 4) {
        if (element.innerHTML === '<i class="bi bi-eye"></i>')
            element.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
        else {
            element.innerHTML = '<i class="bi bi-eye"></i>';
        }
        if(idElement === 1){
        	var section = document.getElementById("section1");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
        if(idElement === 2){
        	var section = document.getElementById("section2");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
        if(idElement === 3){
        	var section = document.getElementById("section3");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
        if(idElement === 4){
        	var section = document.getElementById("section4");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
    }
}
</script>
@endsection