@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.siteWarningNotice.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.site-warning-notices.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label for="subject">{{ trans('cruds.siteWarningNotice.fields.subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="subject" value="{{ old('subject', '') }}">
                            @if($errors->has('subject'))
                                <span class="help-block" role="alert">{{ $errors->first('subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">{{ trans('cruds.siteWarningNotice.fields.location') }}</label>
                            <input class="form-control" type="text" name="location" id="location" value="{{ old('location', '') }}">
                            @if($errors->has('location'))
                                <span class="help-block" role="alert">{{ $errors->first('location') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('reply_by_ncr') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.siteWarningNotice.fields.reply_by_ncr') }}</label>
                            <select class="form-control" name="reply_by_ncr" id="reply_by_ncr">
                                <option value disabled {{ old('reply_by_ncr', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SiteWarningNotice::REPLY_BY_NCR_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('reply_by_ncr', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('reply_by_ncr'))
                                <span class="help-block" role="alert">{{ $errors->first('reply_by_ncr') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.reply_by_ncr_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('swn_no') ? 'has-error' : '' }}">
                            <label for="swn_no">{{ trans('cruds.siteWarningNotice.fields.swn_no') }}</label>
                            <input class="form-control" type="text" name="swn_no" id="swn_no" value="{{ old('swn_no', '') }}">
                            @if($errors->has('swn_no'))
                                <span class="help-block" role="alert">{{ $errors->first('swn_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.swn_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('submit_date') ? 'has-error' : '' }}">
                            <label for="submit_date">{{ trans('cruds.siteWarningNotice.fields.submit_date') }}</label>
                            <input class="form-control date" type="text" name="submit_date" id="submit_date" value="{{ old('submit_date') }}">
                            @if($errors->has('submit_date'))
                                <span class="help-block" role="alert">{{ $errors->first('submit_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.submit_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('to_team') ? 'has-error' : '' }}">
                            <label for="to_team_id">{{ trans('cruds.siteWarningNotice.fields.to_team') }}</label>
                            <select class="form-control select2" name="to_team_id" id="to_team_id">
                                @foreach($to_teams as $id => $to_team)
                                    <option value="{{ $id }}" {{ old('to_team_id') == $id ? 'selected' : '' }}>{{ $to_team }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('to_team'))
                                <span class="help-block" role="alert">{{ $errors->first('to_team') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.to_team_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.siteWarningNotice.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.siteWarningNotice.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('issue_by') ? 'has-error' : '' }}">
                            <label for="issue_by_id">{{ trans('cruds.siteWarningNotice.fields.issue_by') }}</label>
                            <select class="form-control select2" name="issue_by_id" id="issue_by_id">
                                @foreach($issue_bies as $id => $issue_by)
                                    <option value="{{ $id }}" {{ old('issue_by_id') == $id ? 'selected' : '' }}>{{ $issue_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('issue_by'))
                                <span class="help-block" role="alert">{{ $errors->first('issue_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.issue_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('reviewed_by') ? 'has-error' : '' }}">
                            <label for="reviewed_by_id">{{ trans('cruds.siteWarningNotice.fields.reviewed_by') }}</label>
                            <select class="form-control select2" name="reviewed_by_id" id="reviewed_by_id">
                                @foreach($reviewed_bies as $id => $reviewed_by)
                                    <option value="{{ $id }}" {{ old('reviewed_by_id') == $id ? 'selected' : '' }}>{{ $reviewed_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('reviewed_by'))
                                <span class="help-block" role="alert">{{ $errors->first('reviewed_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.reviewed_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment') ? 'has-error' : '' }}">
                            <label for="attachment">{{ trans('cruds.siteWarningNotice.fields.attachment') }}</label>
                            <div class="needsclick dropzone" id="attachment-dropzone">
                            </div>
                            @if($errors->has('attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.attachment_helper') }}</span>
                        </div>
                        <!-- <div class="form-group {{ $errors->has('root_cause') ? 'has-error' : '' }}">
                            <label for="root_cause">{{ trans('cruds.siteWarningNotice.fields.root_cause') }}</label>
                            <textarea class="form-control" name="root_cause" id="root_cause">{{ old('root_cause') }}</textarea>
                            @if($errors->has('root_cause'))
                                <span class="help-block" role="alert">{{ $errors->first('root_cause') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.root_cause_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('containment_actions') ? 'has-error' : '' }}">
                            <label for="containment_actions">{{ trans('cruds.siteWarningNotice.fields.containment_actions') }}</label>
                            <textarea class="form-control ckeditor" name="containment_actions" id="containment_actions">{!! old('containment_actions') !!}</textarea>
                            @if($errors->has('containment_actions'))
                                <span class="help-block" role="alert">{{ $errors->first('containment_actions') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.containment_actions_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('containment_responsible') ? 'has-error' : '' }}">
                            <label for="containment_responsible_id">{{ trans('cruds.siteWarningNotice.fields.containment_responsible') }}</label>
                            <select class="form-control select2" name="containment_responsible_id" id="containment_responsible_id">
                                @foreach($containment_responsibles as $id => $containment_responsible)
                                    <option value="{{ $id }}" {{ old('containment_responsible_id') == $id ? 'selected' : '' }}>{{ $containment_responsible }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('containment_responsible'))
                                <span class="help-block" role="alert">{{ $errors->first('containment_responsible') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.containment_responsible_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('containment_completion_date') ? 'has-error' : '' }}">
                            <label for="containment_completion_date">{{ trans('cruds.siteWarningNotice.fields.containment_completion_date') }}</label>
                            <input class="form-control date" type="text" name="containment_completion_date" id="containment_completion_date" value="{{ old('containment_completion_date') }}">
                            @if($errors->has('containment_completion_date'))
                                <span class="help-block" role="alert">{{ $errors->first('containment_completion_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.containment_completion_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('corrective_and_preventive') ? 'has-error' : '' }}">
                            <label for="corrective_and_preventive">{{ trans('cruds.siteWarningNotice.fields.corrective_and_preventive') }}</label>
                            <textarea class="form-control ckeditor" name="corrective_and_preventive" id="corrective_and_preventive">{!! old('corrective_and_preventive') !!}</textarea>
                            @if($errors->has('corrective_and_preventive'))
                                <span class="help-block" role="alert">{{ $errors->first('corrective_and_preventive') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.corrective_and_preventive_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('corrective_responsible') ? 'has-error' : '' }}">
                            <label for="corrective_responsible_id">{{ trans('cruds.siteWarningNotice.fields.corrective_responsible') }}</label>
                            <select class="form-control select2" name="corrective_responsible_id" id="corrective_responsible_id">
                                @foreach($corrective_responsibles as $id => $corrective_responsible)
                                    <option value="{{ $id }}" {{ old('corrective_responsible_id') == $id ? 'selected' : '' }}>{{ $corrective_responsible }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('corrective_responsible'))
                                <span class="help-block" role="alert">{{ $errors->first('corrective_responsible') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.corrective_responsible_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('corrective_completion_date') ? 'has-error' : '' }}">
                            <label for="corrective_completion_date">{{ trans('cruds.siteWarningNotice.fields.corrective_completion_date') }}</label>
                            <input class="form-control date" type="text" name="corrective_completion_date" id="corrective_completion_date" value="{{ old('corrective_completion_date') }}">
                            @if($errors->has('corrective_completion_date'))
                                <span class="help-block" role="alert">{{ $errors->first('corrective_completion_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.corrective_completion_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('section_2_reviewed_by') ? 'has-error' : '' }}">
                            <label for="section_2_reviewed_by_id">{{ trans('cruds.siteWarningNotice.fields.section_2_reviewed_by') }}</label>
                            <select class="form-control select2" name="section_2_reviewed_by_id" id="section_2_reviewed_by_id">
                                @foreach($section_2_reviewed_bies as $id => $section_2_reviewed_by)
                                    <option value="{{ $id }}" {{ old('section_2_reviewed_by_id') == $id ? 'selected' : '' }}>{{ $section_2_reviewed_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('section_2_reviewed_by'))
                                <span class="help-block" role="alert">{{ $errors->first('section_2_reviewed_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.section_2_reviewed_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('section_2_approved_by') ? 'has-error' : '' }}">
                            <label for="section_2_approved_by_id">{{ trans('cruds.siteWarningNotice.fields.section_2_approved_by') }}</label>
                            <select class="form-control select2" name="section_2_approved_by_id" id="section_2_approved_by_id">
                                @foreach($section_2_approved_bies as $id => $section_2_approved_by)
                                    <option value="{{ $id }}" {{ old('section_2_approved_by_id') == $id ? 'selected' : '' }}>{{ $section_2_approved_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('section_2_approved_by'))
                                <span class="help-block" role="alert">{{ $errors->first('section_2_approved_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.section_2_approved_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('review_and_judgement_status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.siteWarningNotice.fields.review_and_judgement_status') }}</label>
                            <select class="form-control" name="review_and_judgement_status" id="review_and_judgement_status">
                                <option value disabled {{ old('review_and_judgement_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SiteWarningNotice::REVIEW_AND_JUDGEMENT_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('review_and_judgement_status', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select> -->
                            @if($errors->has('review_and_judgement_status'))
                                <span class="help-block" role="alert">{{ $errors->first('review_and_judgement_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.review_and_judgement_status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                            <label for="note">{{ trans('cruds.siteWarningNotice.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note') }}</textarea>
                            @if($errors->has('note'))
                                <span class="help-block" role="alert">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.note_helper') }}</span>
                        </div>
                        <!-- <div class="form-group {{ $errors->has('csc_issuer') ? 'has-error' : '' }}">
                            <label for="csc_issuer_id">{{ trans('cruds.siteWarningNotice.fields.csc_issuer') }}</label>
                            <select class="form-control select2" name="csc_issuer_id" id="csc_issuer_id">
                                @foreach($csc_issuers as $id => $csc_issuer)
                                    <option value="{{ $id }}" {{ old('csc_issuer_id') == $id ? 'selected' : '' }}>{{ $csc_issuer }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('csc_issuer'))
                                <span class="help-block" role="alert">{{ $errors->first('csc_issuer') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.csc_issuer_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('csc_qa') ? 'has-error' : '' }}">
                            <label for="csc_qa_id">{{ trans('cruds.siteWarningNotice.fields.csc_qa') }}</label>
                            <select class="form-control select2" name="csc_qa_id" id="csc_qa_id">
                                @foreach($csc_qas as $id => $csc_qa)
                                    <option value="{{ $id }}" {{ old('csc_qa_id') == $id ? 'selected' : '' }}>{{ $csc_qa }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('csc_qa'))
                                <span class="help-block" role="alert">{{ $errors->first('csc_qa') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.csc_qa_helper') }}</span>
                        </div> -->
                        <div class="form-group {{ $errors->has('disposition_status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.siteWarningNotice.fields.disposition_status') }}</label>
                            <select class="form-control" name="disposition_status" id="disposition_status">
                                <option value disabled {{ old('disposition_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SiteWarningNotice::DISPOSITION_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('disposition_status', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('disposition_status'))
                                <span class="help-block" role="alert">{{ $errors->first('disposition_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.disposition_status_helper') }}</span>
                        </div>
                        <!-- <div class="form-group {{ $errors->has('csc_pm') ? 'has-error' : '' }}">
                            <label for="csc_pm_id">{{ trans('cruds.siteWarningNotice.fields.csc_pm') }}</label>
                            <select class="form-control select2" name="csc_pm_id" id="csc_pm_id">
                                @foreach($csc_pms as $id => $csc_pm)
                                    <option value="{{ $id }}" {{ old('csc_pm_id') == $id ? 'selected' : '' }}>{{ $csc_pm }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('csc_pm'))
                                <span class="help-block" role="alert">{{ $errors->first('csc_pm') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.csc_pm_helper') }}</span>
                        </div> -->
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.siteWarningNotice.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.siteWarningNotice.fields.file_upload_helper') }}</span>
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
    url: '{{ route('admin.site-warning-notices.storeMedia') }}',
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
@if(isset($siteWarningNotice) && $siteWarningNotice->attachment)
          var files =
            {!! json_encode($siteWarningNotice->attachment) !!}
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
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/site-warning-notices/ckmedia', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', {{ $siteWarningNotice->id ?? 0 }});
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    var uploadedFileUploadMap = {}
Dropzone.options.fileUploadDropzone = {
    url: '{{ route('admin.site-warning-notices.storeMedia') }}',
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
@if(isset($siteWarningNotice) && $siteWarningNotice->file_upload)
          var files =
            {!! json_encode($siteWarningNotice->file_upload) !!}
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