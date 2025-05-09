@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.addLetter.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.add-letters.store") }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.addLetter.fields.status') }}</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\AddLetter::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'New') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('repiled_ref') ? 'has-error' : '' }}">
                            <label for="repiled_ref">{{ trans('cruds.addLetter.fields.repiled_ref') }}</label>
                            <input class="form-control" type="text" name="repiled_ref" id="repiled_ref" value="{{ old('repiled_ref', '') }}" placeholder="Reference Letter No.">
                            @if($errors->has('repiled_ref'))
                                <span class="help-block" role="alert">{{ $errors->first('repiled_ref') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.repiled_ref_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('letter_type') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.addLetter.fields.letter_type') }}</label>
                            <select class="form-control" name="letter_type" id="letter_type" required>
                                @foreach(App\AddLetter::LETTER_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('letter_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('letter_type'))
                                <span class="help-block" role="alert">{{ $errors->first('letter_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.letter_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('objective') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.addLetter.fields.objective') }}</label>
                            <select class="form-control" name="objective" id="objective" required>
                                @foreach(App\AddLetter::OBJECTIVE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('objective', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('objective'))
                                <span class="help-block" role="alert">{{ $errors->first('objective') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.objective_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('topic_categories') ? 'has-error' : '' }}">
                            <label for="topic_categories">{{ trans('cruds.addLetter.fields.topic_category') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="topic_categories[]" id="topic_categories" multiple>
                                @foreach($topic_categories as $id => $topic_category)
                                    <option value="{{ $id }}" {{ in_array($id, old('topic_categories', [])) ? 'selected' : '' }}>{{ $topic_category }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('topic_categories'))
                                <span class="help-block" role="alert">{{ $errors->first('topic_categories') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.topic_category_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="required" for="title">{{ trans('cruds.addLetter.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('letter_no') ? 'has-error' : '' }}">
                            <label class="required" for="letter_no">{{ trans('cruds.addLetter.fields.letter_no') }}</label>
                            <input class="form-control" type="text" name="letter_no" id="letter_no" value="{{ old('letter_no', '') }}" required>
                            @if($errors->has('letter_no'))
                                <span class="help-block" role="alert">{{ $errors->first('letter_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.letter_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('speed_class') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.addLetter.fields.speed_class') }}</label>
                            <select class="form-control" name="speed_class" id="speed_class" required>
                                @foreach(App\AddLetter::SPEED_CLASS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('speed_class', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('speed_class'))
                                <span class="help-block" role="alert">{{ $errors->first('speed_class') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.speed_class_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('sender') ? 'has-error' : '' }}">
                            <label class="required" for="sender_id">{{ trans('cruds.addLetter.fields.sender') }}</label>
                            <select class="form-control select2" name="sender_id" id="sender_id" required>
                                @foreach($senders as $id => $sender)
                                    <option value="{{ $id }}" {{ old('sender_id') == $id ? 'selected' : '' }}>{{ $sender }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('sender'))
                                <span class="help-block" role="alert">{{ $errors->first('sender') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.sender_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('sent_date') ? 'has-error' : '' }}">
                            <label class="required" for="sent_date">{{ trans('cruds.addLetter.fields.sent_date') }}</label>
                            <input class="form-control date" type="text" name="sent_date" id="sent_date" value="{{ old('sent_date') }}" required>
                            @if($errors->has('sent_date'))
                                <span class="help-block" role="alert">{{ $errors->first('sent_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.sent_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('receiver') ? 'has-error' : '' }}">
                            <label class="required" for="receiver_id">{{ trans('cruds.addLetter.fields.receiver') }}</label>
                            <select class="form-control select2" name="receiver_id" id="receiver_id" required>
                                @foreach($receivers as $id => $receiver)
                                    <option value="{{ $id }}" {{ old('receiver_id') == $id ? 'selected' : '' }}>{{ $receiver }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('receiver'))
                                <span class="help-block" role="alert">{{ $errors->first('receiver') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.receiver_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('received_date') ? 'has-error' : '' }}">
                            <label class="required" for="received_date">{{ trans('cruds.addLetter.fields.received_date') }}</label>
                            <input class="form-control date" type="text" name="received_date" id="received_date" value="{{ old('received_date') }}" required>
                            @if($errors->has('received_date'))
                                <span class="help-block" role="alert">{{ $errors->first('received_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.received_date_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('cc_tos') ? 'has-error' : '' }}">
                            <label for="cc_tos">{{ trans('cruds.addLetter.fields.cc_to') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="cc_tos[]" id="cc_tos" multiple>
                                @foreach($cc_tos as $id => $cc_to)
                                    <option value="{{ $id }}" {{ in_array($id, old('cc_tos', [])) ? 'selected' : '' }}>{{ $cc_to }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cc_tos'))
                                <span class="help-block" role="alert">{{ $errors->first('cc_tos') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.cc_to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.addLetter.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('letter_upload') ? 'has-error' : '' }}">
                            <label class="required" for="letter_upload">{{ trans('cruds.addLetter.fields.letter_upload') }}</label>
                            <div class="needsclick dropzone" id="letter_upload-dropzone">
                            </div>
                            @if($errors->has('letter_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('letter_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.letter_upload_helper') }}</span>
                        </div>
                        <legend>
                            สำหรับเอกสารที่มีการติดตาม
                        </legend>
                        <div class="form-group {{ $errors->has('responsible') ? 'has-error' : '' }}">
                            <label for="responsible_id">{{ trans('cruds.addLetter.fields.responsible') }}</label>
                            <select class="form-control select2" name="responsible_id" id="responsible_id">
                                @foreach($responsibles as $id => $entry)
                                    <option value="{{ $id }}" {{ old('responsible_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('responsible'))
                                <span class="help-block" role="alert">{{ $errors->first('responsible') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.responsible_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('start_date') ? 'has-error' : '' }}">
                            <label for="start_date">{{ trans('cruds.addLetter.fields.start_date') }}</label>
                            <input class="form-control date" type="text" name="start_date" id="start_date" value="{{ old('start_date') }}">
                            @if($errors->has('start_date'))
                                <span class="help-block" role="alert">{{ $errors->first('start_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.start_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('complete_date') ? 'has-error' : '' }}">
                            <label for="complete_date">{{ trans('cruds.addLetter.fields.complete_date') }}</label>
                            <input class="form-control date" type="text" name="complete_date" id="complete_date" value="{{ old('complete_date') }}">
                            @if($errors->has('complete_date'))
                                <span class="help-block" role="alert">{{ $errors->first('complete_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.complete_date_helper') }}</span>
                        </div>

                        <div class="form-row">
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
    var uploadedLetterUploadMap = {}
Dropzone.options.letterUploadDropzone = {
    url: '{{ route('admin.add-letters.storeMedia') }}',
    maxFilesize: 2048, // MB
    addRemoveLinks: true,
    timeout: 180000,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5120
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="letter_upload[]" value="' + response.name + '">')
      uploadedLetterUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedLetterUploadMap[file.name]
      }
      $('form').find('input[name="letter_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($addLetter) && $addLetter->letter_upload)
          var files =
            {!! json_encode($addLetter->letter_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="letter_upload[]" value="' + file.file_name + '">')
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