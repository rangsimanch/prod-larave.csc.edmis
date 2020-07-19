@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.nonConformanceNotice.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.non-conformance-notices.update", [$nonConformanceNotice->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label for="subject">{{ trans('cruds.nonConformanceNotice.fields.subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="subject" value="{{ old('subject', $nonConformanceNotice->subject) }}">
                            @if($errors->has('subject'))
                                <span class="help-block" role="alert">{{ $errors->first('subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.nonConformanceNotice.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description', $nonConformanceNotice->description) }}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('ref_no') ? 'has-error' : '' }}">
                            <label for="ref_no">{{ trans('cruds.nonConformanceNotice.fields.ref_no') }}</label>
                            <input class="form-control" type="text" name="ref_no" id="ref_no" value="{{ old('ref_no', $nonConformanceNotice->ref_no) }}">
                            @if($errors->has('ref_no'))
                                <span class="help-block" role="alert">{{ $errors->first('ref_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.ref_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('submit_date') ? 'has-error' : '' }}">
                            <label for="submit_date">{{ trans('cruds.nonConformanceNotice.fields.submit_date') }}</label>
                            <input class="form-control date" type="text" name="submit_date" id="submit_date" value="{{ old('submit_date', $nonConformanceNotice->submit_date) }}">
                            @if($errors->has('submit_date'))
                                <span class="help-block" role="alert">{{ $errors->first('submit_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.submit_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment') ? 'has-error' : '' }}">
                            <label for="attachment">{{ trans('cruds.nonConformanceNotice.fields.attachment') }}</label>
                            <div class="needsclick dropzone" id="attachment-dropzone">
                            </div>
                            @if($errors->has('attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment_description') ? 'has-error' : '' }}">
                            <label for="attachment_description">{{ trans('cruds.nonConformanceNotice.fields.attachment_description') }}</label>
                            <textarea class="form-control" name="attachment_description" id="attachment_description">{{ old('attachment_description', $nonConformanceNotice->attachment_description) }}</textarea>
                            @if($errors->has('attachment_description'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment_description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.attachment_description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status_ncn') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.nonConformanceNotice.fields.status_ncn') }}</label>
                            <select class="form-control" name="status_ncn" id="status_ncn">
                                <option value disabled {{ old('status_ncn', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\NonConformanceNotice::STATUS_NCN_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status_ncn', $nonConformanceNotice->status_ncn) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status_ncn'))
                                <span class="help-block" role="alert">{{ $errors->first('status_ncn') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.status_ncn_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('csc_issuers') ? 'has-error' : '' }}">
                            <label for="csc_issuers_id">{{ trans('cruds.nonConformanceNotice.fields.csc_issuers') }}</label>
                            <select class="form-control select2" name="csc_issuers_id" id="csc_issuers_id">
                                @foreach($csc_issuers as $id => $csc_issuers)
                                    <option value="{{ $id }}" {{ ($nonConformanceNotice->csc_issuers ? $nonConformanceNotice->csc_issuers->id : old('csc_issuers_id')) == $id ? 'selected' : '' }}>{{ $csc_issuers }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('csc_issuers'))
                                <span class="help-block" role="alert">{{ $errors->first('csc_issuers') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.csc_issuers_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.nonConformanceNotice.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.file_upload_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('cc_srt') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="cc_srt" value="0">
                                <input type="checkbox" name="cc_srt" id="cc_srt" value="1" {{ $nonConformanceNotice->cc_srt || old('cc_srt', 0) === 1 ? 'checked' : '' }}>
                                <label for="cc_srt" style="font-weight: 400">{{ trans('cruds.nonConformanceNotice.fields.cc_srt') }}</label>
                            </div>
                            @if($errors->has('cc_srt'))
                                <span class="help-block" role="alert">{{ $errors->first('cc_srt') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.cc_srt_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('cc_pmc') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="cc_pmc" value="0">
                                <input type="checkbox" name="cc_pmc" id="cc_pmc" value="1" {{ $nonConformanceNotice->cc_pmc || old('cc_pmc', 0) === 1 ? 'checked' : '' }}>
                                <label for="cc_pmc" style="font-weight: 400">{{ trans('cruds.nonConformanceNotice.fields.cc_pmc') }}</label>
                            </div>
                            @if($errors->has('cc_pmc'))
                                <span class="help-block" role="alert">{{ $errors->first('cc_pmc') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.cc_pmc_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('cc_cec') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="cc_cec" value="0">
                                <input type="checkbox" name="cc_cec" id="cc_cec" value="1" {{ $nonConformanceNotice->cc_cec || old('cc_cec', 0) === 1 ? 'checked' : '' }}>
                                <label for="cc_cec" style="font-weight: 400">{{ trans('cruds.nonConformanceNotice.fields.cc_cec') }}</label>
                            </div>
                            @if($errors->has('cc_cec'))
                                <span class="help-block" role="alert">{{ $errors->first('cc_cec') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.cc_cec_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('cc_csc') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="cc_csc" value="0">
                                <input type="checkbox" name="cc_csc" id="cc_csc" value="1" {{ $nonConformanceNotice->cc_csc || old('cc_csc', 0) === 1 ? 'checked' : '' }}>
                                <label for="cc_csc" style="font-weight: 400">{{ trans('cruds.nonConformanceNotice.fields.cc_csc') }}</label>
                            </div>
                            @if($errors->has('cc_csc'))
                                <span class="help-block" role="alert">{{ $errors->first('cc_csc') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonConformanceNotice.fields.cc_csc_helper') }}</span>
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
    url: '{{ route('admin.non-conformance-notices.storeMedia') }}',
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
@if(isset($nonConformanceNotice) && $nonConformanceNotice->attachment)
          var files =
            {!! json_encode($nonConformanceNotice->attachment) !!}
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
    url: '{{ route('admin.non-conformance-notices.storeMedia') }}',
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
@if(isset($nonConformanceNotice) && $nonConformanceNotice->file_upload)
          var files =
            {!! json_encode($nonConformanceNotice->file_upload) !!}
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