@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.nonConformanceReport.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.non-conformance-reports.update", [$nonConformanceReport->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('ncn_ref') ? 'has-error' : '' }}">
                            <label for="ncn_ref_id">{{ trans('cruds.nonConformanceReport.fields.ncn_ref') }}</label>
                            <select class="form-control select2" name="ncn_ref_id" id="ncn_ref_id">
                                @foreach($ncn_refs as $id => $ncn_ref)
                                    <option value="{{ $id }}" {{ ($nonConformanceReport->ncn_ref ? $nonConformanceReport->ncn_ref->id : old('ncn_ref_id')) == $id ? 'selected' : '' }}>{{ $ncn_ref }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('ncn_ref'))
                                <span class="help-block" role="alert">{{ $errors->first('ncn_ref') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.ncn_ref_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('corresponding_to') ? 'has-error' : '' }}">
                            <label for="corresponding_to">{{ trans('cruds.nonConformanceReport.fields.corresponding_to') }}</label>
                            <input class="form-control" type="text" name="corresponding_to" id="corresponding_to" value="{{ old('corresponding_to', $nonConformanceReport->corresponding_to) }}">
                            @if($errors->has('corresponding_to'))
                                <span class="help-block" role="alert">{{ $errors->first('corresponding_to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.corresponding_to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('response_date') ? 'has-error' : '' }}">
                            <label for="response_date">{{ trans('cruds.nonConformanceReport.fields.response_date') }}</label>
                            <input class="form-control date" type="text" name="response_date" id="response_date" value="{{ old('response_date', $nonConformanceReport->response_date) }}">
                            @if($errors->has('response_date'))
                                <span class="help-block" role="alert">{{ $errors->first('response_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.response_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('root_cause') ? 'has-error' : '' }}">
                            <label for="root_cause">{{ trans('cruds.nonConformanceReport.fields.root_cause') }}</label>
                            <textarea class="form-control" name="root_cause" id="root_cause">{{ old('root_cause', $nonConformanceReport->root_cause) }}</textarea>
                            @if($errors->has('root_cause'))
                                <span class="help-block" role="alert">{{ $errors->first('root_cause') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.root_cause_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('corrective_action') ? 'has-error' : '' }}">
                            <label for="corrective_action">{{ trans('cruds.nonConformanceReport.fields.corrective_action') }}</label>
                            <textarea class="form-control" name="corrective_action" id="corrective_action">{{ old('corrective_action', $nonConformanceReport->corrective_action) }}</textarea>
                            @if($errors->has('corrective_action'))
                                <span class="help-block" role="alert">{{ $errors->first('corrective_action') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.corrective_action_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('preventive_action') ? 'has-error' : '' }}">
                            <label for="preventive_action">{{ trans('cruds.nonConformanceReport.fields.preventive_action') }}</label>
                            <textarea class="form-control" name="preventive_action" id="preventive_action">{{ old('preventive_action', $nonConformanceReport->preventive_action) }}</textarea>
                            @if($errors->has('preventive_action'))
                                <span class="help-block" role="alert">{{ $errors->first('preventive_action') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.preventive_action_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('ref_no') ? 'has-error' : '' }}">
                            <label for="ref_no">{{ trans('cruds.nonConformanceReport.fields.ref_no') }}</label>
                            <input class="form-control" type="text" name="ref_no" id="ref_no" value="{{ old('ref_no', $nonConformanceReport->ref_no) }}">
                            @if($errors->has('ref_no'))
                                <span class="help-block" role="alert">{{ $errors->first('ref_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.ref_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment') ? 'has-error' : '' }}">
                            <label for="attachment">{{ trans('cruds.nonConformanceReport.fields.attachment') }}</label>
                            <div class="needsclick dropzone" id="attachment-dropzone">
                            </div>
                            @if($errors->has('attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('prepared_by') ? 'has-error' : '' }}">
                            <label for="prepared_by_id">{{ trans('cruds.nonConformanceReport.fields.prepared_by') }}</label>
                            <select class="form-control select2" name="prepared_by_id" id="prepared_by_id">
                                @foreach($prepared_bies as $id => $prepared_by)
                                    <option value="{{ $id }}" {{ ($nonConformanceReport->prepared_by ? $nonConformanceReport->prepared_by->id : old('prepared_by_id')) == $id ? 'selected' : '' }}>{{ $prepared_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('prepared_by'))
                                <span class="help-block" role="alert">{{ $errors->first('prepared_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.prepared_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('contractors_project') ? 'has-error' : '' }}">
                            <label for="contractors_project_id">{{ trans('cruds.nonConformanceReport.fields.contractors_project') }}</label>
                            <select class="form-control select2" name="contractors_project_id" id="contractors_project_id">
                                @foreach($contractors_projects as $id => $contractors_project)
                                    <option value="{{ $id }}" {{ ($nonConformanceReport->contractors_project ? $nonConformanceReport->contractors_project->id : old('contractors_project_id')) == $id ? 'selected' : '' }}>{{ $contractors_project }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('contractors_project'))
                                <span class="help-block" role="alert">{{ $errors->first('contractors_project') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.contractors_project_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('csc_consideration_status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.nonConformanceReport.fields.csc_consideration_status') }}</label>
                            <select class="form-control" name="csc_consideration_status" id="csc_consideration_status">
                                <option value disabled {{ old('csc_consideration_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\NonConformanceReport::CSC_CONSIDERATION_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('csc_consideration_status', $nonConformanceReport->csc_consideration_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('csc_consideration_status'))
                                <span class="help-block" role="alert">{{ $errors->first('csc_consideration_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.csc_consideration_status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('approved_by') ? 'has-error' : '' }}">
                            <label for="approved_by_id">{{ trans('cruds.nonConformanceReport.fields.approved_by') }}</label>
                            <select class="form-control select2" name="approved_by_id" id="approved_by_id">
                                @foreach($approved_bies as $id => $approved_by)
                                    <option value="{{ $id }}" {{ ($nonConformanceReport->approved_by ? $nonConformanceReport->approved_by->id : old('approved_by_id')) == $id ? 'selected' : '' }}>{{ $approved_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('approved_by'))
                                <span class="help-block" role="alert">{{ $errors->first('approved_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.approved_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('csc_disposition_status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.nonConformanceReport.fields.csc_disposition_status') }}</label>
                            <select class="form-control" name="csc_disposition_status" id="csc_disposition_status">
                                <option value disabled {{ old('csc_disposition_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\NonConformanceReport::CSC_DISPOSITION_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('csc_disposition_status', $nonConformanceReport->csc_disposition_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('csc_disposition_status'))
                                <span class="help-block" role="alert">{{ $errors->first('csc_disposition_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.csc_disposition_status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.nonConformanceReport.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceReport.fields.file_upload_helper') }}</span>
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
    var uploadedAttachmentMap = {}
Dropzone.options.attachmentDropzone = {
    url: '{{ route('admin.non-conformance-reports.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attachment[]" value="' + response.name + '">')
      uploadedAttachmentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttachmentMap[file.name]
      }
      $('form').find('input[name="attachment[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($nonConformanceReport) && $nonConformanceReport->attachment)
          var files =
            {!! json_encode($nonConformanceReport->attachment) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attachment[]" value="' + file.file_name + '">')
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
    var uploadedFileUploadMap = {}
Dropzone.options.fileUploadDropzone = {
    url: '{{ route('admin.non-conformance-reports.storeMedia') }}',
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
@if(isset($nonConformanceReport) && $nonConformanceReport->file_upload)
          var files =
            {!! json_encode($nonConformanceReport->file_upload) !!}
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