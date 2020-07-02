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
                        <div class="form-group {{ $errors->has('bill') ? 'has-error' : '' }}">
                            <label for="bill_id">{{ trans('cruds.requestForInspection.fields.bill') }}</label>
                            <select class="form-control select2" name="bill_id" id="bill_id">
                                @foreach($bills as $id => $bill)
                                    <option value="{{ $id }}" {{ old('bill_id') == $id ? 'selected' : '' }}>{{ $bill }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('bill'))
                                <span class="help-block" role="alert">{{ $errors->first('bill') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.bill_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('item') ? 'has-error' : '' }}">
                            <label for="item_id">{{ trans('cruds.requestForInspection.fields.item') }}</label>
                            <select class="form-control select2" name="item_id" id="item_id">
                                @foreach($items as $id => $item)
                                    <option value="{{ $id }}" {{ old('item_id') == $id ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('item'))
                                <span class="help-block" role="alert">{{ $errors->first('item') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.item_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_1') ? 'has-error' : '' }}">
                            <label for="wbs_level_1_id">{{ trans('cruds.requestForInspection.fields.wbs_level_1') }}</label>
                            <select class="form-control select2" name="wbs_level_1_id" id="wbs_level_1_id">
                                @foreach($wbs_level_1s as $id => $wbs_level_1)
                                    <option value="{{ $id }}" {{ old('wbs_level_1_id') == $id ? 'selected' : '' }}>{{ $wbs_level_1 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_1'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.wbs_level_1_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_2') ? 'has-error' : '' }}">
                            <label for="wbs_level_2_id">{{ trans('cruds.requestForInspection.fields.wbs_level_2') }}</label>
                            <select class="form-control select2" name="wbs_level_2_id" id="wbs_level_2_id">
                                @foreach($wbs_level_2s as $id => $wbs_level_2)
                                    <option value="{{ $id }}" {{ old('wbs_level_2_id') == $id ? 'selected' : '' }}>{{ $wbs_level_2 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_2'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.wbs_level_2_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_3') ? 'has-error' : '' }}">
                            <label for="wbs_level_3_id">{{ trans('cruds.requestForInspection.fields.wbs_level_3') }}</label>
                            <select class="form-control select2" name="wbs_level_3_id" id="wbs_level_3_id">
                                @foreach($wbs_level_3s as $id => $wbs_level_3)
                                    <option value="{{ $id }}" {{ old('wbs_level_3_id') == $id ? 'selected' : '' }}>{{ $wbs_level_3 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_3'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.wbs_level_3_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wbs_level_4') ? 'has-error' : '' }}">
                            <label for="wbs_level_4_id">{{ trans('cruds.requestForInspection.fields.wbs_level_4') }}</label>
                            <select class="form-control select2" name="wbs_level_4_id" id="wbs_level_4_id">
                                <option value="">{{ trans('global.pleaseSelect') }}</option> 
                            </select>
                            @if($errors->has('wbs_level_4'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_4') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.wbs_level_4_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label for="subject">{{ trans('cruds.requestForInspection.fields.subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="subject" value="{{ old('subject', '') }}">
                            @if($errors->has('subject'))
                                <span class="help-block" role="alert">{{ $errors->first('subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('item_no') ? 'has-error' : '' }}">
                            <label for="item_no">{{ trans('cruds.requestForInspection.fields.item_no') }}</label>
                            <input class="form-control" type="text" name="item_no" id="item_no" value="{{ old('item_no', '') }}">
                            @if($errors->has('item_no'))
                                <span class="help-block" role="alert">{{ $errors->first('item_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.item_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('ref_no') ? 'has-error' : '' }}">
                            <label for="ref_no">{{ trans('cruds.requestForInspection.fields.ref_no') }}</label>
                            <input class="form-control" type="text" name="ref_no" id="ref_no" value="{{ old('ref_no', '') }}">
                            @if($errors->has('ref_no'))
                                <span class="help-block" role="alert">{{ $errors->first('ref_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.ref_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('inspection_date_time') ? 'has-error' : '' }}">
                            <label for="inspection_date_time">{{ trans('cruds.requestForInspection.fields.inspection_date_time') }}</label>
                            <input class="form-control datetime" type="text" name="inspection_date_time" id="inspection_date_time" value="{{ old('inspection_date_time') }}">
                            @if($errors->has('inspection_date_time'))
                                <span class="help-block" role="alert">{{ $errors->first('inspection_date_time') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.inspection_date_time_helper') }}</span>
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
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">{{ trans('cruds.requestForInspection.fields.location') }}</label>
                            <input class="form-control" type="text" name="location" id="location" value="{{ old('location', '') }}">
                            @if($errors->has('location'))
                                <span class="help-block" role="alert">{{ $errors->first('location') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('details_of_inspection') ? 'has-error' : '' }}">
                            <label for="details_of_inspection">{{ trans('cruds.requestForInspection.fields.details_of_inspection') }}</label>
                            <textarea class="form-control" name="details_of_inspection" id="details_of_inspection">{{ old('details_of_inspection') }}</textarea>
                            @if($errors->has('details_of_inspection'))
                                <span class="help-block" role="alert">{{ $errors->first('details_of_inspection') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.details_of_inspection_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('ref_specification') ? 'has-error' : '' }}">
                            <label for="ref_specification">{{ trans('cruds.requestForInspection.fields.ref_specification') }}</label>
                            <input class="form-control" type="text" name="ref_specification" id="ref_specification" value="{{ old('ref_specification', '') }}">
                            @if($errors->has('ref_specification'))
                                <span class="help-block" role="alert">{{ $errors->first('ref_specification') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.ref_specification_helper') }}</span>
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
                        <div class="form-group {{ $errors->has('result_of_inspection') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.requestForInspection.fields.result_of_inspection') }}</label>
                            <select class="form-control" name="result_of_inspection" id="result_of_inspection">
                                <option value disabled {{ old('result_of_inspection', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\RequestForInspection::RESULT_OF_INSPECTION_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('result_of_inspection', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('result_of_inspection'))
                                <span class="help-block" role="alert">{{ $errors->first('result_of_inspection') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.result_of_inspection_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
                            <label for="comment">{{ trans('cruds.requestForInspection.fields.comment') }}</label>
                            <textarea class="form-control" name="comment" id="comment">{{ old('comment') }}</textarea>
                            @if($errors->has('comment'))
                                <span class="help-block" role="alert">{{ $errors->first('comment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.comment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                            <label for="amount">{{ trans('cruds.requestForInspection.fields.amount') }}</label>
                            <input class="form-control" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01">
                            @if($errors->has('amount'))
                                <span class="help-block" role="alert">{{ $errors->first('amount') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.requestForInspection.fields.amount_helper') }}</span>
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