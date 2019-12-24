@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.rfa.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.rfas.update", [$rfa->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title">{{ trans('cruds.rfa.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $rfa->title) }}">
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label for="document_number">{{ trans('cruds.rfa.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', $rfa->document_number) }}">
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('rfa_code') ? 'has-error' : '' }}">
                            <label for="rfa_code">{{ trans('cruds.rfa.fields.rfa_code') }}</label>
                            <input class="form-control" type="text" name="rfa_code" id="rfa_code" value="{{ old('rfa_code', $rfa->rfa_code) }}">
                            @if($errors->has('rfa_code'))
                                <span class="help-block" role="alert">{{ $errors->first('rfa_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.rfa_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                            <label for="type_id">{{ trans('cruds.rfa.fields.type') }}</label>
                            <select class="form-control select2" name="type_id" id="type_id">
                                @foreach($types as $id => $type)
                                    <option value="{{ $id }}" {{ ($rfa->type ? $rfa->type->id : old('type_id')) == $id ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type_id'))
                                <span class="help-block" role="alert">{{ $errors->first('type_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.rfa.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ ($rfa->construction_contract ? $rfa->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract_id'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('submit_date') ? 'has-error' : '' }}">
                            <label for="submit_date">{{ trans('cruds.rfa.fields.submit_date') }}</label>
                            <input class="form-control date" type="text" name="submit_date" id="submit_date" value="{{ old('submit_date', $rfa->submit_date) }}">
                            @if($errors->has('submit_date'))
                                <span class="help-block" role="alert">{{ $errors->first('submit_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.submit_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('issueby') ? 'has-error' : '' }}">
                            <label for="issueby_id">{{ trans('cruds.rfa.fields.issueby') }}</label>
                            <select class="form-control select2" name="issueby_id" id="issueby_id">
                                @foreach($issuebies as $id => $issueby)
                                    <option value="{{ $id }}" {{ ($rfa->issueby ? $rfa->issueby->id : old('issueby_id')) == $id ? 'selected' : '' }}>{{ $issueby }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('issueby_id'))
                                <span class="help-block" role="alert">{{ $errors->first('issueby_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.issueby_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('assign') ? 'has-error' : '' }}">
                            <label for="assign_id">{{ trans('cruds.rfa.fields.assign') }}</label>
                            <select class="form-control select2" name="assign_id" id="assign_id">
                                @foreach($assigns as $id => $assign)
                                    <option value="{{ $id }}" {{ ($rfa->assign ? $rfa->assign->id : old('assign_id')) == $id ? 'selected' : '' }}>{{ $assign }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('assign_id'))
                                <span class="help-block" role="alert">{{ $errors->first('assign_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.assign_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note_1') ? 'has-error' : '' }}">
                            <label for="note_1">{{ trans('cruds.rfa.fields.note_1') }}</label>
                            <textarea class="form-control ckeditor" name="note_1" id="note_1">{!! old('note_1', $rfa->note_1) !!}</textarea>
                            @if($errors->has('note_1'))
                                <span class="help-block" role="alert">{{ $errors->first('note_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_1_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_upload_1') ? 'has-error' : '' }}">
                            <label for="file_upload_1">{{ trans('cruds.rfa.fields.file_upload_1') }}</label>
                            <div class="needsclick dropzone" id="file_upload_1-dropzone">
                            </div>
                            @if($errors->has('file_upload_1'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.file_upload_1_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('comment_by') ? 'has-error' : '' }}">
                            <label for="comment_by_id">{{ trans('cruds.rfa.fields.comment_by') }}</label>
                            <select class="form-control select2" name="comment_by_id" id="comment_by_id">
                                @foreach($comment_bies as $id => $comment_by)
                                    <option value="{{ $id }}" {{ ($rfa->comment_by ? $rfa->comment_by->id : old('comment_by_id')) == $id ? 'selected' : '' }}>{{ $comment_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('comment_by_id'))
                                <span class="help-block" role="alert">{{ $errors->first('comment_by_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.comment_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('information_by') ? 'has-error' : '' }}">
                            <label for="information_by_id">{{ trans('cruds.rfa.fields.information_by') }}</label>
                            <select class="form-control select2" name="information_by_id" id="information_by_id">
                                @foreach($information_bies as $id => $information_by)
                                    <option value="{{ $id }}" {{ ($rfa->information_by ? $rfa->information_by->id : old('information_by_id')) == $id ? 'selected' : '' }}>{{ $information_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('information_by_id'))
                                <span class="help-block" role="alert">{{ $errors->first('information_by_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.information_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('receive_date') ? 'has-error' : '' }}">
                            <label for="receive_date">{{ trans('cruds.rfa.fields.receive_date') }}</label>
                            <input class="form-control date" type="text" name="receive_date" id="receive_date" value="{{ old('receive_date', $rfa->receive_date) }}">
                            @if($errors->has('receive_date'))
                                <span class="help-block" role="alert">{{ $errors->first('receive_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.receive_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note_2') ? 'has-error' : '' }}">
                            <label for="note_2">{{ trans('cruds.rfa.fields.note_2') }}</label>
                            <textarea class="form-control" name="note_2" id="note_2">{{ old('note_2', $rfa->note_2) }}</textarea>
                            @if($errors->has('note_2'))
                                <span class="help-block" role="alert">{{ $errors->first('note_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_2_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('comment_status') ? 'has-error' : '' }}">
                            <label for="comment_status_id">{{ trans('cruds.rfa.fields.comment_status') }}</label>
                            <select class="form-control select2" name="comment_status_id" id="comment_status_id">
                                @foreach($comment_statuses as $id => $comment_status)
                                    <option value="{{ $id }}" {{ ($rfa->comment_status ? $rfa->comment_status->id : old('comment_status_id')) == $id ? 'selected' : '' }}>{{ $comment_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('comment_status_id'))
                                <span class="help-block" role="alert">{{ $errors->first('comment_status_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.comment_status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note_3') ? 'has-error' : '' }}">
                            <label for="note_3">{{ trans('cruds.rfa.fields.note_3') }}</label>
                            <textarea class="form-control" name="note_3" id="note_3">{{ old('note_3', $rfa->note_3) }}</textarea>
                            @if($errors->has('note_3'))
                                <span class="help-block" role="alert">{{ $errors->first('note_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_3_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('for_status') ? 'has-error' : '' }}">
                            <label for="for_status_id">{{ trans('cruds.rfa.fields.for_status') }}</label>
                            <select class="form-control select2" name="for_status_id" id="for_status_id">
                                @foreach($for_statuses as $id => $for_status)
                                    <option value="{{ $id }}" {{ ($rfa->for_status ? $rfa->for_status->id : old('for_status_id')) == $id ? 'selected' : '' }}>{{ $for_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('for_status_id'))
                                <span class="help-block" role="alert">{{ $errors->first('for_status_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.for_status_helper') }}</span>
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