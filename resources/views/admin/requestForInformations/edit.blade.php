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
                       
                        <div class="form-group {{ $errors->has('outgoing_date') ? 'has-error' : '' }}">
                            <label for="outgoing_date">{{ trans('cruds.requestForInformation.fields.outgoing_date') }}</label>
                            <input class="form-control date" type="text" name="outgoing_date" id="outgoing_date" value="{{ old('outgoing_date', $requestForInformation->outgoing_date) }}">
                            @if($errors->has('outgoing_date'))
                                <span class="help-block" role="alert">{{ $errors->first('outgoing_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.outgoing_date_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('authorised_rep') ? 'has-error' : '' }}">
                            <label for="authorised_rep_id">{{ trans('cruds.requestForInformation.fields.authorised_rep') }}</label>
                            <select class="form-control select2" name="authorised_rep_id" id="authorised_rep_id">
                                @foreach($authorised_reps as $id => $authorised_rep)
                                    <option value="{{ $id }}" {{ (old('authorised_rep_id') ? old('authorised_rep_id') : $requestForInformation->authorised_rep->id ?? '') == $id ? 'selected' : '' }}>{{ $authorised_rep }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('authorised_rep'))
                                <span class="help-block" role="alert">{{ $errors->first('authorised_rep') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.authorised_rep_helper') }}</span>
                        </div>

                        @if($show_signatory_fields)
                        <div class="form-group {{ $errors->has('signatory_id') || $errors->has('signatory_2_id') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.rfa.fields.authorized_signatory') }}</label>
                            <style>
                                #signatory_table .select2-container { width: 100% !important; }
                            </style>
                            <table class="table table-bordered table-striped" id="signatory_table">
                                <thead>
                                    <tr>
                                        <th width="45%">{{ trans('cruds.rfa.fields.authorized_signatory') }}</th>
                                        <th width="45%">{{ trans('cruds.rfa.fields.signatory_jobtitle') }}</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="signatory_row_1">
                                        <td>
                                            <select class="form-control select2" name="signatory_id" id="signatory_id">
                                                <option value="" {{ old('signatory_id', $rfi_signatory_1->authorized_signatory_id ?? '') == '' ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                                @foreach($signatories as $id => $entry)
                                                    <option value="{{ $id }}" {{ old('signatory_id', $rfi_signatory_1->authorized_signatory_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="signatory_jobtitle_id" id="signatory_jobtitle_id">
                                                <option value="" {{ old('signatory_jobtitle_id', $rfi_signatory_1->authorized_signatory_jobtitle_id ?? '') == '' ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                                @foreach($signatory_jobtitles as $id => $entry)
                                                    <option value="{{ $id }}" {{ old('signatory_jobtitle_id', $rfi_signatory_1->authorized_signatory_jobtitle_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr id="signatory_row_2" style="{{ $rfi_signatory_2 ? '' : 'display:none;' }}">
                                        <td>
                                            <select class="form-control select2" name="signatory_2_id" id="signatory_2_id" style="width:100%;">
                                                <option value="" {{ old('signatory_2_id', $rfi_signatory_2->authorized_signatory_id ?? '') == '' ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                                @foreach($signatories as $id => $entry)
                                                    <option value="{{ $id }}" {{ old('signatory_2_id', $rfi_signatory_2->authorized_signatory_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="signatory_jobtitle_2_id" id="signatory_jobtitle_2_id" style="width:100%;">
                                                <option value="" {{ old('signatory_jobtitle_2_id', $rfi_signatory_2->authorized_signatory_jobtitle_id ?? '') == '' ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                                @foreach($signatory_jobtitles_2 as $id => $entry)
                                                    <option value="{{ $id }}" {{ old('signatory_jobtitle_2_id', $rfi_signatory_2->authorized_signatory_jobtitle_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <a class="btn btn-xs btn-danger" id="btn-remove-signatory-2">- {{ trans('cruds.rfa.fields.remove_signatory') }}</a>
                                        </td>
                                    </tr>
                                    <tr id="signatory_add_row" style="{{ $rfi_signatory_2 ? 'display:none;' : '' }}">
                                        <td colspan="3" align="center">
                                            <a class="btn btn-success" id="btn-add-signatory">+ {{ trans('cruds.rfa.fields.add_signatory') }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($errors->has('signatory_id'))
                                <span class="help-block" role="alert">{{ $errors->first('signatory_id') }}</span>
                            @endif
                            @if($errors->has('signatory_2_id'))
                                <span class="help-block" role="alert">{{ $errors->first('signatory_2_id') }}</span>
                            @endif
                            @if($errors->has('signatory_jobtitle_id'))
                                <span class="help-block" role="alert">{{ $errors->first('signatory_jobtitle_id') }}</span>
                            @endif
                            @if($errors->has('signatory_jobtitle_2_id'))
                                <span class="help-block" role="alert">{{ $errors->first('signatory_jobtitle_2_id') }}</span>
                            @endif
                        </div>
                        @endif
                        <div class="form-group {{ $errors->has('response_organization') ? 'has-error' : '' }}">
                            <label for="response_organization_id">{{ trans('cruds.requestForInformation.fields.response_organization') }}</label>
                            <select class="form-control select2" name="response_organization_id" id="response_organization_id">
                                @foreach($response_organizations as $id => $response_organization)
                                    <option value="{{ $id }}" {{ (old('response_organization_id') ? old('response_organization_id') : $requestForInformation->response_organization->id ?? '') == $id ? 'selected' : '' }}>{{ $response_organization }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('response_organization'))
                                <span class="help-block" role="alert">{{ $errors->first('response_organization') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.response_organization_helper') }}</span>
                        </div>
                       
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label for="file_upload">{{ trans('cruds.requestForInformation.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.file_upload_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('save_for') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.requestForInformation.fields.save_for') }}</label>
                            <select class="form-control" name="save_for" id="save_for">
                                <option value disabled {{ old('save_for', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\RequestForInformation::SAVE_FOR_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('save_for', $requestForInformation->save_for) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('save_for'))
                                <span class="help-block" role="alert">{{ $errors->first('save_for') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInformation.fields.save_for_helper') }}</span>
                        </div>
                        <div class="form-row">
                            <a class="btn btn-default" href="{{ route('admin.request-for-informations.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                            <button class="btn btn-success" type="submit" name="save_form" id="save_form" data-flag="0">
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
    //Authorized Signatory dependent dropdowns
    function rfaLoadJobtitles(signatoryId, targetId){
        var $target = $('#' + targetId);
        $target.html('');
        if(!signatoryId){
            return;
        }
        $.ajax({
            url:"{{ route('admin.rfas.fetchJobtitles') }}",
            method:"POST",
            data:{select:signatoryId, _token:"{{ csrf_token() }}"},
            success:function(result){
                $target.html(result);
            }
        });
    }

    function rfaDisableDuplicateSignatories(){
        var v1 = $('#signatory_id').val();
        var v2 = $('#signatory_2_id').val();
        $('#signatory_2_id option').prop('disabled', false);
        $('#signatory_id option').prop('disabled', false);
        if(v1){
            $('#signatory_2_id option[value="' + v1 + '"]').prop('disabled', true);
            if(v1 === v2){
                $('#signatory_2_id').val('').trigger('change');
                $('#signatory_jobtitle_2_id').html('');
                v2 = '';
            }
        }
        if(v2){
            $('#signatory_id option[value="' + v2 + '"]').prop('disabled', true);
        }
    }
    rfaDisableDuplicateSignatories();

    if($('#signatory_id').length && $('#signatory_id').val() && $('#signatory_jobtitle_id option').length === 0){
        rfaLoadJobtitles($('#signatory_id').val(), 'signatory_jobtitle_id');
    }
    if($('#signatory_2_id').length && $('#signatory_2_id').val() && $('#signatory_jobtitle_2_id option').length === 0){
        rfaLoadJobtitles($('#signatory_2_id').val(), 'signatory_jobtitle_2_id');
    }

    $('#signatory_id').change(function(){
        rfaLoadJobtitles($(this).val(), 'signatory_jobtitle_id');
        rfaDisableDuplicateSignatories();
    });
    $('#signatory_2_id').change(function(){
        rfaLoadJobtitles($(this).val(), 'signatory_jobtitle_2_id');
        rfaDisableDuplicateSignatories();
    });

    $('#btn-add-signatory').click(function(){
        $('#signatory_row_2').show();
        $('#signatory_add_row').hide();
        $('#signatory_2_id, #signatory_jobtitle_2_id').next('.select2-container').css('width', '100%');
    });
    $('#btn-remove-signatory-2').click(function(){
        $('#signatory_2_id').val('');
        $('#signatory_jobtitle_2_id').html('');
        $('#signatory_row_2').hide();
        $('#signatory_add_row').show();
    });

    var uploadedFileUploadMap = {}
Dropzone.options.fileUploadDropzone = {
    chunking: true,
    chunkSize: 50 * 1024 * 1024, // 50 MB per request
    retryChunks: true,
    retryChunksLimit: 3,
    sending: function (file, xhr, formData) {
        if (!file.upload.chunked) return;
        var chunk = file.upload.chunks.filter(function (item) {
            return item && item.xhr === xhr;
        })[0];
        var params = Dropzone.prototype.defaultOptions.params.call(this, [file], xhr, chunk);
        Object.keys(params).forEach(function (key) {
            formData.append(key, params[key]);
        });
    },
    url: '{{ route('admin.request-for-informations.storeMedia') }}',
    maxFilesize: 1000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
        if (file.upload && file.upload.chunked) {
            response = JSON.parse(file.xhr.responseText);
        }
        if (!response || !response.name) return;
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
@if(isset($requestForInformation) && $requestForInformation->file_upload)
          var files =
            {!! json_encode($requestForInformation->file_upload) !!}
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