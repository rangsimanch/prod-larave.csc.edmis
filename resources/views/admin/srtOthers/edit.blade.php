@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.srtOther.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.srt-others.update", [$srtOther->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        
                        <div class="form-group {{ $errors->has('document_type') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.srtOther.fields.document_type') }}</label>
                            <select class="form-control" name="document_type" id="document_type">
                                <option value disabled {{ old('document_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtOther::DOCUMENT_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('document_type', $srtOther->document_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('document_type'))
                                <span class="help-block" role="alert">{{ $errors->first('document_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.document_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label for="document_number">{{ trans('cruds.srtOther.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', $srtOther->document_number) }}">
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.document_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label for="subject">{{ trans('cruds.srtOther.fields.subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="subject" value="{{ old('subject', $srtOther->subject) }}">
                            @if($errors->has('subject'))
                                <span class="help-block" role="alert">{{ $errors->first('subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('incoming_date') ? 'has-error' : '' }}">
                            <label for="incoming_date">{{ trans('cruds.srtOther.fields.incoming_date') }}</label>
                            <input class="form-control date" type="text" name="incoming_date" id="incoming_date" value="{{ old('incoming_date', $srtOther->incoming_date) }}">
                            @if($errors->has('incoming_date'))
                                <span class="help-block" role="alert">{{ $errors->first('incoming_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.incoming_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('refer_to') ? 'has-error' : '' }}">
                            <label for="refer_to">{{ trans('cruds.srtOther.fields.refer_to') }}</label>
                            <input class="form-control" type="text" name="refer_to" id="refer_to" value="{{ old('refer_to', $srtOther->refer_to) }}">
                            @if($errors->has('refer_to'))
                                <span class="help-block" role="alert">{{ $errors->first('refer_to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.refer_to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('from') ? 'has-error' : '' }}">
                            <label for="from_id">{{ trans('cruds.srtOther.fields.from') }}</label>
                            <select class="form-control select2" name="from_id" id="from_id">
                                @foreach($froms as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('from_id') ? old('from_id') : $srtOther->from->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('from'))
                                <span class="help-block" role="alert">{{ $errors->first('from') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.from_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('tos') ? 'has-error' : '' }}">
                            <label for="tos">{{ trans('cruds.srtOther.fields.to') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="tos[]" id="tos" multiple>
                                @foreach($tos as $id => $to)
                                    <option value="{{ $id }}" {{ (in_array($id, old('tos', [])) || $srtOther->tos->contains($id)) ? 'selected' : '' }}>{{ $to }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tos'))
                                <span class="help-block" role="alert">{{ $errors->first('tos') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachments') ? 'has-error' : '' }}">
                            <label for="attachments">{{ trans('cruds.srtOther.fields.attachments') }}</label>
                            <input class="form-control" type="text" name="attachments" id="attachments" value="{{ old('attachments', $srtOther->attachments) }}">
                            @if($errors->has('attachments'))
                                <span class="help-block" role="alert">{{ $errors->first('attachments') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.attachments_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.srtOther.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description', $srtOther->description) }}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('speed_class') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.srtOther.fields.speed_class') }}</label>
                            <select class="form-control" name="speed_class" id="speed_class">
                                <option value disabled {{ old('speed_class', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtOther::SPEED_CLASS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('speed_class', $srtOther->speed_class) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('speed_class'))
                                <span class="help-block" role="alert">{{ $errors->first('speed_class') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.speed_class_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('objective') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.srtOther.fields.objective') }}</label>
                            <select class="form-control" name="objective" id="objective">
                                <option value disabled {{ old('objective', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtOther::OBJECTIVE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('objective', $srtOther->objective) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('objective'))
                                <span class="help-block" role="alert">{{ $errors->first('objective') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.objective_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('signatory') ? 'has-error' : '' }}">
                            <label for="signatory">{{ trans('cruds.srtOther.fields.signatory') }}</label>
                            <input class="form-control" type="text" name="signatory" id="signatory" value="{{ old('signatory', $srtOther->signatory) }}">
                            @if($errors->has('signatory'))
                                <span class="help-block" role="alert">{{ $errors->first('signatory') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.signatory_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_storage') ? 'has-error' : '' }}">
                            <label for="document_storage">{{ trans('cruds.srtOther.fields.document_storage') }}</label>
                            <input class="form-control" type="text" name="document_storage" id="document_storage" value="{{ old('document_storage', $srtOther->document_storage) }}">
                            @if($errors->has('document_storage'))
                                <span class="help-block" role="alert">{{ $errors->first('document_storage') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.document_storage_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                            <label for="note">{{ trans('cruds.srtOther.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note', $srtOther->note) }}</textarea>
                            @if($errors->has('note'))
                                <span class="help-block" role="alert">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.note_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('close_date') ? 'has-error' : '' }}">
                            <label for="close_date">{{ trans('cruds.srtOther.fields.close_date') }}</label>
                            <input class="form-control date" type="text" name="close_date" id="close_date" value="{{ old('close_date', $srtOther->close_date) }}">
                            @if($errors->has('close_date'))
                                <span class="help-block" role="alert">{{ $errors->first('close_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.close_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload') ? 'has-error' : '' }}">
                            <label class="required" for="file_upload">{{ trans('cruds.srtOther.fields.file_upload') }}</label>
                            <div class="needsclick dropzone" id="file_upload-dropzone">
                            </div>
                            @if($errors->has('file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.file_upload_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('save_for') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.srtOther.fields.save_for') }}</label>
                            <select class="form-control" name="save_for" id="save_for" required>
                                <option value disabled {{ old('save_for', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SrtOther::SAVE_FOR_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('save_for', $srtOther->save_for) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('save_for'))
                                <span class="help-block" role="alert">{{ $errors->first('save_for') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.save_for_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('close_by') ? 'has-error' : '' }}">
                            <label for="close_by_id">{{ trans('cruds.srtOther.fields.close_by') }}</label>
                            <select class="form-control select2" name="close_by_id" id="close_by_id">
                                @foreach($close_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('close_by_id') ? old('close_by_id') : $srtOther->close_by->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('close_by'))
                                <span class="help-block" role="alert">{{ $errors->first('close_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.close_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('to_text') ? 'has-error' : '' }}">
                            <label for="to_text">{{ trans('cruds.srtOther.fields.to_text') }}</label>
                            <input class="form-control" type="text" name="to_text" id="to_text" value="{{ old('to_text', $srtOther->to_text) }}">
                            @if($errors->has('to_text'))
                                <span class="help-block" role="alert">{{ $errors->first('to_text') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.srtOther.fields.to_text_helper') }}</span>
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
    url: '{{ route('admin.srt-others.storeMedia') }}',
    maxFilesize: 50, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 50
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
@if(isset($srtOther) && $srtOther->file_upload)
          var files =
            {!! json_encode($srtOther->file_upload) !!}
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