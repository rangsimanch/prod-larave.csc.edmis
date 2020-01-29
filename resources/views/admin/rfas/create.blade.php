@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.rfa.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.rfas.store") }}" enctype="multipart/form-data">
                        @csrf

                        @can('rfa_panel_a')
                        <legend> CEC Outgoing </legend>
                        <div class="form-group {{ $errors->has('title_eng') ? 'has-error' : '' }}">
                            <label for="title_eng">{{ trans('cruds.rfa.fields.title_eng') }}</label>
                            <input class="form-control" type="text" name="title_eng" id="title_eng" value="{{ old('title_eng', '') }}">
                            @if($errors->has('title_eng'))
                                <span class="help-block" role="alert">{{ $errors->first('title_eng') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_eng_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title">{{ trans('cruds.rfa.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}">
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title_cn') ? 'has-error' : '' }}">
                            <label for="title_cn">{{ trans('cruds.rfa.fields.title_cn') }}</label>
                            <input class="form-control" type="text" name="title_cn" id="title_cn" value="{{ old('title_cn', '') }}">
                            @if($errors->has('title_cn'))
                                <span class="help-block" role="alert">{{ $errors->first('title_cn') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_cn_helper') }}</span>
                        </div>

                        <!-- <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label for="document_number">{{ trans('cruds.rfa.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', '') }}">
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('rfa_code') ? 'has-error' : '' }}">
                            <label for="rfa_code">{{ trans('cruds.rfa.fields.rfa_code') }}</label>
                            <input class="form-control" type="text" name="rfa_code" id="rfa_code" value="{{ old('rfa_code', '') }}">
                            @if($errors->has('rfa_code'))
                                <span class="help-block" role="alert">{{ $errors->first('rfa_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.rfa_code_helper') }}</span>
                        </div> -->
                        <!-- <div class="form-group {{ $errors->has('review_time') ? 'has-error' : '' }}">
                            <label for="review_time">{{ trans('cruds.rfa.fields.review_time') }}</label>
                            <input class="form-control" type="number" name="review_time" id="review_time" value="{{ old('review_time') }}" step="1">
                            @if($errors->has('review_time'))
                                <span class="help-block" role="alert">{{ $errors->first('review_time') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.review_time_helper') }}</span>
                        </div> -->

                        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                            <label class="required" for="type_id">{{ trans('cruds.rfa.fields.type') }}</label>
                            <select class="form-control select2" name="type_id" id="type_id" required>
                                @foreach($types as $id => $type)
                                    <option value="{{ $id }}" {{ old('type_id') == $id ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type_id'))
                                <span class="help-block" role="alert">{{ $errors->first('type_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.type_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('worktype') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.rfa.fields.worktype') }}</label>
                            <select class="form-control" name="worktype" id="worktype" required>
                                <option value disabled {{ old('worktype', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Rfa::WORKTYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('worktype', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('worktype'))
                                <span class="help-block" role="alert">{{ $errors->first('worktype') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.worktype_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.rfa.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract_id'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.construction_contract_helper') }}</span>
                        </div>
                       
                        <div class="form-group {{ $errors->has('wbs_level_3') ? 'has-error' : '' }}">
                            <label for="wbs_level_3_id" class="required">{{ trans('cruds.rfa.fields.wbs_level_3') }}</label>
                            <select class="form-control select2 wbslv3" name="wbs_level_3_id" id="wbs_level_3_id" required>
                                @foreach($wbs_level_3s as $id => $wbs_level_3)
                                    <option value="{{ $id }}" {{ old('wbs_level_3_id') == $id ? 'selected' : '' }}>{{ $wbs_level_3 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_3_id'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_3_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.wbs_level_3_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('wbs_level_4') ? 'has-error' : '' }}">
                            <label class="required" for="wbs_level_4_id">{{ trans('cruds.rfa.fields.wbs_level_4') }}</label>
                            <select class="form-control select2 wbslv4" name="wbs_level_4_id" id="wbs_level_4_id" required>
                                <option value=""> {{ trans('global.pleaseSelect') }} </option>
                            </select>
                            @if($errors->has('wbs_level_4_id'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_4_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.wbs_level_4_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('submit_date') ? 'has-error' : '' }}">
                            <label for="submit_date">{{ trans('cruds.rfa.fields.submit_date') }}</label>
                            <input class="form-control date" type="text" name="submit_date" id="submit_date" value="{{ old('submit_date') }}">
                            @if($errors->has('submit_date'))
                                <span class="help-block" role="alert">{{ $errors->first('submit_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.submit_date_helper') }}</span>
                        </div>


                        <div class="form-group {{ $errors->has('issueby') ? 'has-error' : '' }}">
                            <label for="issueby_id">{{ trans('cruds.rfa.fields.issueby') }}</label>
                            <select class="form-control select2" name="issueby_id" id="issueby_id">
                                @foreach($issuebies as $id => $issueby)
                                    <option value="{{ $id }}" {{ old('issueby_id') == $id ? 'selected' : '' }}>{{ $issueby }}</option>
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
                                    <option value="{{ $id }}" {{ old('assign_id') == $id ? 'selected' : '' }}>{{ $assign }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('assign_id'))
                                <span class="help-block" role="alert">{{ $errors->first('assign_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.assign_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note_1') ? 'has-error' : '' }}">
                            <label for="note_1">{{ trans('cruds.rfa.fields.note_1') }}</label>
                            <textarea class="form-control ckeditor" name="note_1" id="note_1">{!! old('note_1') !!}</textarea>
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
                        @endcan

                        @can('rfa_panel_b')
                        <legend> CSC Incoming </legend>

                        <div class="form-group {{ $errors->has('action_by') ? 'has-error' : '' }}">
                            <label for="action_by_id">{{ trans('cruds.rfa.fields.action_by') }}</label>
                            <select class="form-control select2" name="action_by_id" id="action_by_id">
                                @foreach($action_bies as $id => $action_by)
                                    <option value="{{ $id }}" {{ old('action_by_id') == $id ? 'selected' : '' }}>{{ $action_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.action_by_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('comment_by') ? 'has-error' : '' }}">
                            <label for="comment_by_id">{{ trans('cruds.rfa.fields.comment_by') }}</label>
                            <select class="form-control select2" name="comment_by_id" id="comment_by_id">
                                @foreach($comment_bies as $id => $comment_by)
                                    <option value="{{ $id }}" {{ old('comment_by_id') == $id ? 'selected' : '' }}>{{ $comment_by }}</option>
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
                                    <option value="{{ $id }}" {{ old('information_by_id') == $id ? 'selected' : '' }}>{{ $information_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('information_by_id'))
                                <span class="help-block" role="alert">{{ $errors->first('information_by_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.information_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('receive_date') ? 'has-error' : '' }}">
                            <label for="receive_date">{{ trans('cruds.rfa.fields.receive_date') }}</label>
                            <input class="form-control date" type="text" name="receive_date" id="receive_date" value="{{ old('receive_date') }}">
                            @if($errors->has('receive_date'))
                                <span class="help-block" role="alert">{{ $errors->first('receive_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.receive_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note_2') ? 'has-error' : '' }}">
                            <label for="note_2">{{ trans('cruds.rfa.fields.note_2') }}</label>
                            <textarea class="form-control" name="note_2" id="note_2">{{ old('note_2') }}</textarea>
                            @if($errors->has('note_2'))
                                <span class="help-block" role="alert">{{ $errors->first('note_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_2_helper') }}</span>
                        </div>
                        @endcan

                        @can('rfa_panel_c')
                        <legend> CSC Outgoing (Specialist/Engineer) </legend>
                        <div class="form-group {{ $errors->has('comment_status') ? 'has-error' : '' }}">
                            <label for="comment_status_id">{{ trans('cruds.rfa.fields.comment_status') }}</label>
                            <select class="form-control select2" name="comment_status_id" id="comment_status_id">
                                @foreach($comment_statuses as $id => $comment_status)
                                    <option value="{{ $id }}" {{ old('comment_status_id') == $id ? 'selected' : '' }}>{{ $comment_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('comment_status_id'))
                                <span class="help-block" role="alert">{{ $errors->first('comment_status_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.comment_status_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('note_3') ? 'has-error' : '' }}">
                            <label for="note_3">{{ trans('cruds.rfa.fields.note_3') }}</label>
                            <textarea class="form-control" name="note_3" id="note_3">{{ old('note_3') }}</textarea>
                            @if($errors->has('note_3'))
                                <span class="help-block" role="alert">{{ $errors->first('note_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_3_helper') }}</span>
                        </div>
                        @endcan
                        
                        @can('rfa_panel_d')
                        <legend> CSC Outgoing </legend>

                        <div class="form-group {{ $errors->has('for_status') ? 'has-error' : '' }}">
                            <label for="for_status_id">{{ trans('cruds.rfa.fields.for_status') }}</label>
                            <select class="form-control select2" name="for_status_id" id="for_status_id">
                                @foreach($for_statuses as $id => $for_status)
                                    <option value="{{ $id }}" {{ old('for_status_id') == $id ? 'selected' : '' }}>{{ $for_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('for_status_id'))
                                <span class="help-block" role="alert">{{ $errors->first('for_status_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.for_status_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('note_4') ? 'has-error' : '' }}">
                            <label for="note_4">{{ trans('cruds.rfa.fields.note_4') }}</label>
                            <textarea class="form-control" name="note_4" id="note_4">{{ old('note_4') }}</textarea>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_4_helper') }}</span>
                        </div>

                        @can('rfa_edit_all')
                        <div class="form-group {{ $errors->has('document_status') ? 'has-error' : '' }}">
                            <label for="document_status_id">{{ trans('cruds.rfa.fields.document_status') }}</label>
                            <select class="form-control select2" name="document_status_id" id="document_status_id">
                                @foreach($document_statuses as $id => $document_status)
                                    <option value="{{ $id }}" {{ old('document_status_id') == $id ? 'selected' : '' }}>{{ $document_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_status_helper') }}</span>
                        </div>
                        @endcan
                        
                        @endcan
                        
                        <div class="form-row">
                            <button class="btn btn-success" type="submit">
                                {{ trans('global.save') }}
                            </button>
                            
                            <a class="btn btn-default" href="{{ route('admin.rfas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
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

<script type="text/javascript">
    $('.wbslv3').change(function(){
        if($(this).val() != ''){
            var select = $(this).val();
            console.log(select);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('admin.rfas.fetch') }}",
                method:"POST",
                data:{select:select , _token:_token},
                success:function(result){
                    //Action

                    $('.wbslv4').html(result);
                    console.log(result);
                }
            })
        }
    });
</script>
@endsection