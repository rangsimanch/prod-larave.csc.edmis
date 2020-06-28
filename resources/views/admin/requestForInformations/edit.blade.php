@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.requestForInformation.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.request-for-informations.update", [$requestForInformation->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('to_organization') ? 'has-error' : '' }}">
                            <label for="to_organization">{{ trans('cruds.requestForInformation.fields.to_organization') }}</label>
                            <input class="form-control" type="text" name="to_organization" id="to_organization" value="{{ old('to_organization', $requestForInformation->to_organization) }}">
                            @if($errors->has('to_organization'))
                                <span class="help-block" role="alert">{{ $errors->first('to_organization') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.to_organization_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attention_name') ? 'has-error' : '' }}">
                            <label for="attention_name">{{ trans('cruds.requestForInformation.fields.attention_name') }}</label>
                            <input class="form-control" type="text" name="attention_name" id="attention_name" value="{{ old('attention_name', $requestForInformation->attention_name) }}">
                            @if($errors->has('attention_name'))
                                <span class="help-block" role="alert">{{ $errors->first('attention_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.attention_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_no') ? 'has-error' : '' }}">
                            <label for="document_no">{{ trans('cruds.requestForInformation.fields.document_no') }}</label>
                            <input class="form-control" type="text" name="document_no" id="document_no" value="{{ old('document_no', $requestForInformation->document_no) }}">
                            @if($errors->has('document_no'))
                                <span class="help-block" role="alert">{{ $errors->first('document_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.document_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.requestForInformation.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ ($requestForInformation->construction_contract ? $requestForInformation->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label for="date">{{ trans('cruds.requestForInformation.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date', $requestForInformation->date) }}">
                            @if($errors->has('date'))
                                <span class="help-block" role="alert">{{ $errors->first('date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="required" for="title">{{ trans('cruds.requestForInformation.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $requestForInformation->title) }}" required>
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('to') ? 'has-error' : '' }}">
                            <label for="to_id">{{ trans('cruds.requestForInformation.fields.to') }}</label>
                            <select class="form-control select2" name="to_id" id="to_id">
                                @foreach($tos as $id => $to)
                                    <option value="{{ $id }}" {{ ($requestForInformation->to ? $requestForInformation->to->id : old('to_id')) == $id ? 'selected' : '' }}>{{ $to }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('to'))
                                <span class="help-block" role="alert">{{ $errors->first('to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('discipline') ? 'has-error' : '' }}">
                            <label for="discipline">{{ trans('cruds.requestForInformation.fields.discipline') }}</label>
                            <input class="form-control" type="text" name="discipline" id="discipline" value="{{ old('discipline', $requestForInformation->discipline) }}">
                            @if($errors->has('discipline'))
                                <span class="help-block" role="alert">{{ $errors->first('discipline') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.discipline_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('originator_name') ? 'has-error' : '' }}">
                            <label for="originator_name">{{ trans('cruds.requestForInformation.fields.originator_name') }}</label>
                            <input class="form-control" type="text" name="originator_name" id="originator_name" value="{{ old('originator_name', $requestForInformation->originator_name) }}">
                            @if($errors->has('originator_name'))
                                <span class="help-block" role="alert">{{ $errors->first('originator_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.originator_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('cc_to') ? 'has-error' : '' }}">
                            <label for="cc_to">{{ trans('cruds.requestForInformation.fields.cc_to') }}</label>
                            <input class="form-control" type="text" name="cc_to" id="cc_to" value="{{ old('cc_to', $requestForInformation->cc_to) }}">
                            @if($errors->has('cc_to'))
                                <span class="help-block" role="alert">{{ $errors->first('cc_to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.cc_to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('incoming_no') ? 'has-error' : '' }}">
                            <label for="incoming_no">{{ trans('cruds.requestForInformation.fields.incoming_no') }}</label>
                            <input class="form-control" type="text" name="incoming_no" id="incoming_no" value="{{ old('incoming_no', $requestForInformation->incoming_no) }}">
                            @if($errors->has('incoming_no'))
                                <span class="help-block" role="alert">{{ $errors->first('incoming_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.incoming_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('incoming_date') ? 'has-error' : '' }}">
                            <label for="incoming_date">{{ trans('cruds.requestForInformation.fields.incoming_date') }}</label>
                            <input class="form-control date" type="text" name="incoming_date" id="incoming_date" value="{{ old('incoming_date', $requestForInformation->incoming_date) }}">
                            @if($errors->has('incoming_date'))
                                <span class="help-block" role="alert">{{ $errors->first('incoming_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.incoming_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.requestForInformation.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description', $requestForInformation->description) }}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment_file_description') ? 'has-error' : '' }}">
                            <label for="attachment_file_description">{{ trans('cruds.requestForInformation.fields.attachment_file_description') }}</label>
                            <textarea class="form-control" name="attachment_file_description" id="attachment_file_description">{{ old('attachment_file_description', $requestForInformation->attachment_file_description) }}</textarea>
                            @if($errors->has('attachment_file_description'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment_file_description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.attachment_file_description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment_files') ? 'has-error' : '' }}">
                            <label for="attachment_files">{{ trans('cruds.requestForInformation.fields.attachment_files') }}</label>
                            <div class="needsclick dropzone" id="attachment_files-dropzone">
                            </div>
                            @if($errors->has('attachment_files'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment_files') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.attachment_files_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('request_by') ? 'has-error' : '' }}">
                            <label for="request_by_id">{{ trans('cruds.requestForInformation.fields.request_by') }}</label>
                            <select class="form-control select2" name="request_by_id" id="request_by_id">
                                @foreach($request_bies as $id => $request_by)
                                    <option value="{{ $id }}" {{ ($requestForInformation->request_by ? $requestForInformation->request_by->id : old('request_by_id')) == $id ? 'selected' : '' }}>{{ $request_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('request_by'))
                                <span class="help-block" role="alert">{{ $errors->first('request_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.request_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('outgoing_no') ? 'has-error' : '' }}">
                            <label for="outgoing_no">{{ trans('cruds.requestForInformation.fields.outgoing_no') }}</label>
                            <input class="form-control" type="text" name="outgoing_no" id="outgoing_no" value="{{ old('outgoing_no', $requestForInformation->outgoing_no) }}">
                            @if($errors->has('outgoing_no'))
                                <span class="help-block" role="alert">{{ $errors->first('outgoing_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.outgoing_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('outgoing_date') ? 'has-error' : '' }}">
                            <label for="outgoing_date">{{ trans('cruds.requestForInformation.fields.outgoing_date') }}</label>
                            <input class="form-control date" type="text" name="outgoing_date" id="outgoing_date" value="{{ old('outgoing_date', $requestForInformation->outgoing_date) }}">
                            @if($errors->has('outgoing_date'))
                                <span class="help-block" role="alert">{{ $errors->first('outgoing_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.outgoing_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('response') ? 'has-error' : '' }}">
                            <label for="response">{{ trans('cruds.requestForInformation.fields.response') }}</label>
                            <textarea class="form-control" name="response" id="response">{{ old('response', $requestForInformation->response) }}</textarea>
                            @if($errors->has('response'))
                                <span class="help-block" role="alert">{{ $errors->first('response') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.response_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('authorised_rep') ? 'has-error' : '' }}">
                            <label for="authorised_rep_id">{{ trans('cruds.requestForInformation.fields.authorised_rep') }}</label>
                            <select class="form-control select2" name="authorised_rep_id" id="authorised_rep_id">
                                @foreach($authorised_reps as $id => $authorised_rep)
                                    <option value="{{ $id }}" {{ ($requestForInformation->authorised_rep ? $requestForInformation->authorised_rep->id : old('authorised_rep_id')) == $id ? 'selected' : '' }}>{{ $authorised_rep }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('authorised_rep'))
                                <span class="help-block" role="alert">{{ $errors->first('authorised_rep') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.authorised_rep_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('response_organization') ? 'has-error' : '' }}">
                            <label for="response_organization_id">{{ trans('cruds.requestForInformation.fields.response_organization') }}</label>
                            <select class="form-control select2" name="response_organization_id" id="response_organization_id">
                                @foreach($response_organizations as $id => $response_organization)
                                    <option value="{{ $id }}" {{ ($requestForInformation->response_organization ? $requestForInformation->response_organization->id : old('response_organization_id')) == $id ? 'selected' : '' }}>{{ $response_organization }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('response_organization'))
                                <span class="help-block" role="alert">{{ $errors->first('response_organization') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.response_organization_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('response_date') ? 'has-error' : '' }}">
                            <label for="response_date">{{ trans('cruds.requestForInformation.fields.response_date') }}</label>
                            <input class="form-control" type="text" name="response_date" id="response_date" value="{{ old('response_date', $requestForInformation->response_date) }}">
                            @if($errors->has('response_date'))
                                <span class="help-block" role="alert">{{ $errors->first('response_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.response_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('record') ? 'has-error' : '' }}">
                            <label for="record">{{ trans('cruds.requestForInformation.fields.record') }}</label>
                            <textarea class="form-control" name="record" id="record">{{ old('record', $requestForInformation->record) }}</textarea>
                            @if($errors->has('record'))
                                <span class="help-block" role="alert">{{ $errors->first('record') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.record_helper') }}</span>
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
    var uploadedAttachmentFilesMap = {}
Dropzone.options.attachmentFilesDropzone = {
    url: '{{ route('admin.request-for-informations.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attachment_files[]" value="' + response.name + '">')
      uploadedAttachmentFilesMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttachmentFilesMap[file.name]
      }
      $('form').find('input[name="attachment_files[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($requestForInformation) && $requestForInformation->attachment_files)
          var files =
            {!! json_encode($requestForInformation->attachment_files) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attachment_files[]" value="' + file.file_name + '">')
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