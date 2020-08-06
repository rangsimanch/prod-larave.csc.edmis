@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.requestForInspection.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.request-for-inspections.store") }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group {{ $errors->has('wbs_level_1') ? 'has-error' : '' }}">
                            <label for="wbs_level_1_id">{{ trans('cruds.requestForInspection.fields.wbs_level_1') }}</label>
                            <select class="form-control select2 wbslv1" name="wbs_level_1_id" id="wbs_level_1_id" require>
                                @foreach($wbs_level_1s as $id => $wbs_level_1)
                                    <option value="{{ $id }}" {{ old('wbs_level_1_id') == $id ? 'selected' : '' }}>{{ $wbs_level_1 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_1'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.wbs_level_1_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('bill') ? 'has-error' : '' }}">
                            <label for="bill_id">{{ trans('cruds.requestForInspection.fields.bill') }}</label>
                            <select class="form-control select2 wbslv2" name="bill_id" id="bill_id" require>
                                <option value=""> {{ trans('global.pleaseSelect') }} </option>
                            </select>
                            @if($errors->has('bill'))
                                <span class="help-block" role="alert">{{ $errors->first('bill') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.bill_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('wbs_level_3') ? 'has-error' : '' }}">
                            <label for="wbs_level_3_id" class="required">{{ trans('cruds.rfa.fields.wbs_level_3') }}</label>
                            <select class="form-control select2 wbslv3" name="wbs_level_3_id" id="wbs_level_3_id" required>
                                <option value=""> {{ trans('global.pleaseSelect') }} </option>
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


                        <div class="form-group {{ $errors->has('items') ? 'has-error' : '' }}">
                            <label for="items">{{ trans('cruds.requestForInspection.fields.item') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 itemBoq" name="items[]" id="items" multiple>
                                <option value=""> {{ trans('global.pleaseSelect') }} </option>
                            </select>
                            @if($errors->has('items'))
                                <span class="help-block" role="alert">{{ $errors->first('items') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.item_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('type_of_work') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.requestForInspection.fields.type_of_work') }}</label>
                            <select class="form-control" name="type_of_work" id="type_of_work">
                                <option value disabled {{ old('type_of_work', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\RequestForInspection::TYPE_OF_WORK_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type_of_work', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type_of_work'))
                                <span class="help-block" role="alert">{{ $errors->first('type_of_work') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.type_of_work_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label for="subject">{{ trans('cruds.requestForInspection.fields.subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="subject" value="{{ old('subject', '') }}">
                            @if($errors->has('subject'))
                                <span class="help-block" role="alert">{{ $errors->first('subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('ref_no') ? 'has-error' : '' }}">
                            <label for="ref_no">{{ trans('cruds.requestForInspection.fields.ref_no') }}</label>
                            <input class="form-control" type="text" name="ref_no" id="ref_no" value="{{ old('ref_no', '') }}" placeholder="WBS1/WBS2/WBS3/ORDER No.">
                            @if($errors->has('ref_no'))
                                <span class="help-block" role="alert">{{ $errors->first('ref_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.ref_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">{{ trans('cruds.requestForInspection.fields.location') }}</label>
                            <input class="form-control" type="text" name="location" id="location" value="{{ old('location', '') }}">
                            @if($errors->has('location'))
                                <span class="help-block" role="alert">{{ $errors->first('location') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('requested_by') ? 'has-error' : '' }}">
                            <label for="requested_by_id">{{ trans('cruds.requestForInspection.fields.requested_by') }}</label>
                            <select class="form-control select2" name="requested_by_id" id="requested_by_id">
                                @foreach($requested_bies as $id => $requested_by)
                                    <option value="{{ $id }}" {{ old('requested_by_id') == $id ? 'selected' : '' }}>{{ $requested_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('requested_by'))
                                <span class="help-block" role="alert">{{ $errors->first('requested_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.requested_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('submittal_date') ? 'has-error' : '' }}">
                            <label for="submittal_date">{{ trans('cruds.requestForInspection.fields.submittal_date') }}</label>
                            <input class="form-control date" type="text" name="submittal_date" id="submittal_date" value="{{ old('submittal_date') }}">
                            @if($errors->has('submittal_date'))
                                <span class="help-block" role="alert">{{ $errors->first('submittal_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.submittal_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('contact_person') ? 'has-error' : '' }}">
                            <label for="contact_person_id">{{ trans('cruds.requestForInspection.fields.contact_person') }}</label>
                            <select class="form-control select2" name="contact_person_id" id="contact_person_id">
                                @foreach($contact_people as $id => $contact_person)
                                    <option value="{{ $id }}" {{ old('contact_person_id') == $id ? 'selected' : '' }}>{{ $contact_person }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('contact_person'))
                                <span class="help-block" role="alert">{{ $errors->first('contact_person') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.contact_person_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('replied_date') ? 'has-error' : '' }}">
                            <label for="replied_date">{{ trans('cruds.requestForInspection.fields.replied_date') }}</label>
                            <input class="form-control date" type="text" name="replied_date" id="replied_date" value="{{ old('replied_date') }}">
                            @if($errors->has('replied_date'))
                                <span class="help-block" role="alert">{{ $errors->first('replied_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.replied_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('ipa') ? 'has-error' : '' }}">
                            <label for="ipa">{{ trans('cruds.requestForInspection.fields.ipa') }}</label>
                            <input class="form-control" type="text" name="ipa" id="ipa" value="{{ old('ipa', '') }}">
                            @if($errors->has('ipa'))
                                <span class="help-block" role="alert">{{ $errors->first('ipa') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.ipa_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
                            <label for="comment">{{ trans('cruds.requestForInspection.fields.comment') }}</label>
                            <textarea class="form-control" name="comment" id="comment">{{ old('comment') }}</textarea>
                            @if($errors->has('comment'))
                                <span class="help-block" role="alert">{{ $errors->first('comment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.comment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label for="construction_contract_id">{{ trans('cruds.requestForInspection.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id">
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('files_upload') ? 'has-error' : '' }}">
                            <label for="files_upload">{{ trans('cruds.requestForInspection.fields.files_upload') }}</label>
                            <div class="needsclick dropzone" id="files_upload-dropzone">
                            </div>
                            @if($errors->has('files_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('files_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.files_upload_helper') }}</span>
                        </div>

                        <legend> Loop Options </legend>
                        <!-- <div class="form-group {{ $errors->has('start_loop') ? 'has-error' : '' }}">
                            <label for="start_loop">{{ trans('cruds.requestForInspection.fields.start_loop') }}</label>
                            <input class="form-control" type="number" name="start_loop" id="start_loop" value="{{ old('start_loop', '') }}" step="1">
                            @if($errors->has('start_loop'))
                                <span class="help-block" role="alert">{{ $errors->first('start_loop') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.start_loop_helper') }}</span>
                        </div> -->
                        <div class="form-group {{ $errors->has('end_loop') ? 'has-error' : '' }}">
                            <label for="end_loop">{{ trans('cruds.requestForInspection.fields.end_loop') }}</label>
                            <input class="form-control" type="number" name="end_loop" id="end_loop" value="{{ old('end_loop', '') }}" step="1">
                            @if($errors->has('end_loop'))
                                <span class="help-block" role="alert">{{ $errors->first('end_loop') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.end_loop_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('loop_file_upload') ? 'has-error' : '' }}">
                            <label for="loop_file_upload">{{ trans('cruds.requestForInspection.fields.loop_file_upload') }}</label>
                            <div class="needsclick dropzone" id="loop_file_upload-dropzone">
                            </div>
                            @if($errors->has('loop_file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('loop_file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.loop_file_upload_helper') }}</span>
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
    var uploadedFilesUploadMap = {}
Dropzone.options.filesUploadDropzone = {
    url: '{{ route('admin.request-for-inspections.storeMedia') }}',
    maxFilesize: 100, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 100
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="files_upload[]" value="' + response.name + '">')
      uploadedFilesUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFilesUploadMap[file.name]
      }
      $('form').find('input[name="files_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($requestForInspection) && $requestForInspection->files_upload)
          var files =
            {!! json_encode($requestForInspection->files_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="files_upload[]" value="' + file.file_name + '">')
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
    var uploadedLoopFileUploadMap = {}
Dropzone.options.loopFileUploadDropzone = {
    url: '{{ route('admin.request-for-inspections.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="loop_file_upload[]" value="' + response.name + '">')
      uploadedLoopFileUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedLoopFileUploadMap[file.name]
      }
      $('form').find('input[name="loop_file_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($requestForInspection) && $requestForInspection->loop_file_upload)
          var files =
            {!! json_encode($requestForInspection->loop_file_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="loop_file_upload[]" value="' + file.file_name + '">')
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

$('.wbslv1').change(function(){
        if($(this).val() != ''){
            var select = $(this).val();
            console.log(select);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('admin.rfns.WbsTwo') }}",
                method:"POST",
                data:{select:select , _token:_token},
                success:function(result){
                    //Action

                    $('.wbslv2').html(result);
                    console.log(result);
                }
            })
        }
});


$('.wbslv2').change(function(){
        if($(this).val() != ''){
            var select = $(this).val();
            console.log(select);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('admin.rfns.WbsThree') }}",
                method:"POST",
                data:{select:select , _token:_token},
                success:function(result){
                    //Action

                    $('.wbslv3').html(result);
                    console.log(result);
                }
            })
        }
});

$('.wbslv3').change(function(){
        if($(this).val() != ''){
            var select = $(this).val();
            console.log(select);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('admin.rfns.fetch') }}",
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

$('.wbslv2').change(function(){
        if($(this).val() != ''){
            var select = $(this).val();
            console.log(select);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('admin.rfns.itemBoQ') }}",
                method:"POST",
                data:{select:select , _token:_token},
                success:function(result){
                    //Action

                    $('.itemBoq').html(result);
                    console.log(result);
                }
            })
        }
});



</script>
@endsection