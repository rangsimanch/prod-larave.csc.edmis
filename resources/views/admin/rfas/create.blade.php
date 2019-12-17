@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.rfa.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.rfas.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">{{ trans('cruds.rfa.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}">
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="document_number">{{ trans('cruds.rfa.fields.document_number') }}</label>
                <input class="form-control {{ $errors->has('document_number') ? 'is-invalid' : '' }}" type="text" name="document_number" id="document_number" value="{{ old('document_number', '') }}">
                @if($errors->has('document_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('document_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.document_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rfa_code">{{ trans('cruds.rfa.fields.rfa_code') }}</label>
                <input class="form-control {{ $errors->has('rfa_code') ? 'is-invalid' : '' }}" type="text" name="rfa_code" id="rfa_code" value="{{ old('rfa_code', '') }}">
                @if($errors->has('rfa_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rfa_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.rfa_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="type_id">{{ trans('cruds.rfa.fields.type') }}</label>
                <select class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type_id" id="type_id">
                    @foreach($types as $id => $type)
                        <option value="{{ $id }}" {{ old('type_id') == $id ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                @if($errors->has('type_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="submit_date">{{ trans('cruds.rfa.fields.submit_date') }}</label>
                <input class="form-control date {{ $errors->has('submit_date') ? 'is-invalid' : '' }}" type="text" name="submit_date" id="submit_date" value="{{ old('submit_date') }}">
                @if($errors->has('submit_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('submit_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.submit_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="issueby_id">{{ trans('cruds.rfa.fields.issueby') }}</label>
                <select class="form-control select2 {{ $errors->has('issueby') ? 'is-invalid' : '' }}" name="issueby_id" id="issueby_id">
                    @foreach($issuebies as $id => $issueby)
                        <option value="{{ $id }}" {{ old('issueby_id') == $id ? 'selected' : '' }}>{{ $issueby }}</option>
                    @endforeach
                </select>
                @if($errors->has('issueby_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('issueby_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.issueby_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="assign_id">{{ trans('cruds.rfa.fields.assign') }}</label>
                <select class="form-control select2 {{ $errors->has('assign') ? 'is-invalid' : '' }}" name="assign_id" id="assign_id">
                    @foreach($assigns as $id => $assign)
                        <option value="{{ $id }}" {{ old('assign_id') == $id ? 'selected' : '' }}>{{ $assign }}</option>
                    @endforeach
                </select>
                @if($errors->has('assign_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('assign_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.assign_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note_1">{{ trans('cruds.rfa.fields.note_1') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('note_1') ? 'is-invalid' : '' }}" name="note_1" id="note_1">{!! old('note_1') !!}</textarea>
                @if($errors->has('note_1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note_1') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.note_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="file_upload_1">{{ trans('cruds.rfa.fields.file_upload_1') }}</label>
                <div class="needsclick dropzone {{ $errors->has('file_upload_1') ? 'is-invalid' : '' }}" id="file_upload_1-dropzone">
                </div>
                @if($errors->has('file_upload_1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('file_upload_1') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.file_upload_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="create_by_id">{{ trans('cruds.rfa.fields.create_by') }}</label>
                <select class="form-control select2 {{ $errors->has('create_by') ? 'is-invalid' : '' }}" name="create_by_id" id="create_by_id">
                    @foreach($create_bies as $id => $create_by)
                        <option value="{{ $id }}" {{ old('create_by_id') == $id ? 'selected' : '' }}>{{ $create_by }}</option>
                    @endforeach
                </select>
                @if($errors->has('create_by_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('create_by_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.create_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="action_by_id">{{ trans('cruds.rfa.fields.action_by') }}</label>
                <select class="form-control select2 {{ $errors->has('action_by') ? 'is-invalid' : '' }}" name="action_by_id" id="action_by_id">
                    @foreach($action_bies as $id => $action_by)
                        <option value="{{ $id }}" {{ old('action_by_id') == $id ? 'selected' : '' }}>{{ $action_by }}</option>
                    @endforeach
                </select>
                @if($errors->has('action_by_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('action_by_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.action_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comment_by_id">{{ trans('cruds.rfa.fields.comment_by') }}</label>
                <select class="form-control select2 {{ $errors->has('comment_by') ? 'is-invalid' : '' }}" name="comment_by_id" id="comment_by_id">
                    @foreach($comment_bies as $id => $comment_by)
                        <option value="{{ $id }}" {{ old('comment_by_id') == $id ? 'selected' : '' }}>{{ $comment_by }}</option>
                    @endforeach
                </select>
                @if($errors->has('comment_by_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comment_by_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.comment_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="information_by_id">{{ trans('cruds.rfa.fields.information_by') }}</label>
                <select class="form-control select2 {{ $errors->has('information_by') ? 'is-invalid' : '' }}" name="information_by_id" id="information_by_id">
                    @foreach($information_bies as $id => $information_by)
                        <option value="{{ $id }}" {{ old('information_by_id') == $id ? 'selected' : '' }}>{{ $information_by }}</option>
                    @endforeach
                </select>
                @if($errors->has('information_by_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('information_by_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.information_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="receive_date">{{ trans('cruds.rfa.fields.receive_date') }}</label>
                <input class="form-control date {{ $errors->has('receive_date') ? 'is-invalid' : '' }}" type="text" name="receive_date" id="receive_date" value="{{ old('receive_date') }}">
                @if($errors->has('receive_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receive_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.receive_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note_2">{{ trans('cruds.rfa.fields.note_2') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('note_2') ? 'is-invalid' : '' }}" name="note_2" id="note_2">{!! old('note_2') !!}</textarea>
                @if($errors->has('note_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.note_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comment_status_id">{{ trans('cruds.rfa.fields.comment_status') }}</label>
                <select class="form-control select2 {{ $errors->has('comment_status') ? 'is-invalid' : '' }}" name="comment_status_id" id="comment_status_id">
                    @foreach($comment_statuses as $id => $comment_status)
                        <option value="{{ $id }}" {{ old('comment_status_id') == $id ? 'selected' : '' }}>{{ $comment_status }}</option>
                    @endforeach
                </select>
                @if($errors->has('comment_status_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comment_status_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.comment_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note_3">{{ trans('cruds.rfa.fields.note_3') }}</label>
                <textarea class="form-control {{ $errors->has('note_3') ? 'is-invalid' : '' }}" name="note_3" id="note_3">{{ old('note_3') }}</textarea>
                @if($errors->has('note_3'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note_3') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.note_3_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="for_status_id">{{ trans('cruds.rfa.fields.for_status') }}</label>
                <select class="form-control select2 {{ $errors->has('for_status') ? 'is-invalid' : '' }}" name="for_status_id" id="for_status_id">
                    @foreach($for_statuses as $id => $for_status)
                        <option value="{{ $id }}" {{ old('for_status_id') == $id ? 'selected' : '' }}>{{ $for_status }}</option>
                    @endforeach
                </select>
                @if($errors->has('for_status_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('for_status_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.for_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="document_status_id">{{ trans('cruds.rfa.fields.document_status') }}</label>
                <select class="form-control select2 {{ $errors->has('document_status') ? 'is-invalid' : '' }}" name="document_status_id" id="document_status_id">
                    @foreach($document_statuses as $id => $document_status)
                        <option value="{{ $id }}" {{ old('document_status_id') == $id ? 'selected' : '' }}>{{ $document_status }}</option>
                    @endforeach
                </select>
                @if($errors->has('document_status_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('document_status_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.document_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="construction_contracts">{{ trans('cruds.rfa.fields.construction_contract') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('construction_contracts') ? 'is-invalid' : '' }}" name="construction_contracts[]" id="construction_contracts" multiple>
                    @foreach($construction_contracts as $id => $construction_contract)
                        <option value="{{ $id }}" {{ in_array($id, old('construction_contracts', [])) ? 'selected' : '' }}>{{ $construction_contract }}</option>
                    @endforeach
                </select>
                @if($errors->has('construction_contracts'))
                    <div class="invalid-feedback">
                        {{ $errors->first('construction_contracts') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rfa.fields.construction_contract_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    var uploadedFileUpload1Map = {}
Dropzone.options.fileUpload1Dropzone = {
    url: '{{ route('admin.rfas.storeMedia') }}',
    maxFilesize: 200, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 200
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="file_upload_1[]" value="' + response.name + '">')
      uploadedFileUpload1Map[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFileUpload1Map[file.name]
      }
      $('form').find('input[name="file_upload_1[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($rfa) && $rfa->file_upload_1)
          var files =
            {!! json_encode($rfa->file_upload_1) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="file_upload_1[]" value="' + file.file_name + '">')
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