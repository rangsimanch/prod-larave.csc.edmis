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
                    <form  method="POST" action="{{ route("admin.rfas.store") }}" enctype="multipart/form-data" class="swa-confirm" >
                        @csrf
                        <legend> Contractor RFA Submittal </legend>

                          <div class="form-group {{ $errors->has('purpose_for') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.rfa.fields.purpose_for') }}</label>
                            <select class="form-control" name="purpose_for" id="purpose_for">
                                <option value disabled {{ old('purpose_for', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Rfa::PURPOSE_FOR_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('purpose_for', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('purpose_for'))
                                <span class="help-block" role="alert">{{ $errors->first('purpose_for') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.purpose_for_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('boq') ? 'has-error' : '' }}">
                            <label for="boq_id">{{ trans('cruds.rfa.fields.boq') }}</label>
                            <select class="form-control select2" name="boq_id" id="boq_id">
                                @foreach($boqs as $id => $boq)
                                    <option value="{{ $id }}" {{ old('boq_id') == $id ? 'selected' : '' }}>{{ $boq }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('boq'))
                                <span class="help-block" role="alert">{{ $errors->first('boq') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.boq_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('boq_sub') ? 'has-error' : '' }}">
                            <label for="boq_sub_id">{{ trans('cruds.rfa.fields.boq_sub') }}</label>
                            <select class="form-control select2" name="boq_sub_id" id="boq_sub_id">
                                @foreach($boq_subs as $id => $entry)
                                    <option value="{{ $id }}" {{ old('boq_sub_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('boq_sub'))
                                <span class="help-block" role="alert">{{ $errors->first('boq_sub') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.boq_sub_helper') }}</span>
                        </div>

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

                        <div class="form-group {{ $errors->has('origin_number') ? 'has-error' : '' }}">
                            <label for="origin_number">{{ trans('cruds.rfa.fields.origin_number') }}</label>
                            <input class="form-control" type="text" name="origin_number" id="origin_number" value="{{  old('origin_number', '') }}" placeholder="Example : RFA-0000, SHD-0000, DWG-0000">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.origin_number_helper') }}</span>
                        </div>

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

                    
                        <div class="form-group {{ $errors->has('note_1') ? 'has-error' : '' }}">
                            <label for="note_1">{{ trans('cruds.rfa.fields.note_1') }}</label>
                            <textarea class="form-control" name="note_1" id="note_1">{!! old('note_1') !!}</textarea>
                            @if($errors->has('note_1'))
                                <span class="help-block" role="alert">{{ $errors->first('note_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_1_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('attach_file_name') ? 'has-error' : '' }}">
                            <label for="attach_file_name">{{ trans('cruds.rfa.fields.attach_file_name') }}</label>
                            <textarea class="form-control" name="attach_file_name" id="attach_file_name">{{ old('attach_file_name', '') }}</textarea>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.attach_file_name_helper') }}</span>
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

                        <div class="form-group {{ $errors->has('qty_page') ? 'has-error' : '' }}">
                            <label for="qty_page">{{ trans('cruds.rfa.fields.qty_page') }}</label>
                            <input class="form-control" type="text" name="qty_page" id="qty_page" value="{{ old('qty_page', '') }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.qty_page_helper') }}</span>
                        </div>

                       

                        <div class="form-group {{ $errors->has('spec_ref_no') ? 'has-error' : '' }}">
                            <label for="spec_ref_no">{{ trans('cruds.rfa.fields.spec_ref_no') }}</label>
                            <input class="form-control" type="text" name="spec_ref_no" id="spec_ref_no" value="{{ old('spec_ref_no', '') }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.spec_ref_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('clause') ? 'has-error' : '' }}">
                            <label for="clause">{{ trans('cruds.rfa.fields.clause') }}</label>
                            <input class="form-control" type="text" name="clause" id="clause" value="{{ old('clause', '') }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.clause_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('contract_drawing_no') ? 'has-error' : '' }}">
                            <label for="contract_drawing_no">{{ trans('cruds.rfa.fields.contract_drawing_no') }}</label>
                            <input class="form-control" type="text" name="contract_drawing_no" id="contract_drawing_no" value="{{ old('contract_drawing_no', '') }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.contract_drawing_no_helper') }}</span>
                        </div>

                        <div class="table-responsive">
                                <span id="result"> </span>
                                <label> {{ trans('cruds.submittalsRfa.title')}} </label>
                                <table class="table table-bordered table-striped" id="submittal_table">
                                    <thead>
                                        <tr>
                                            <th width="25%"><center>{{ trans('cruds.submittalsRfa.fields.item_no') }}<center></th>
                                            <th width="25%"><center></center> {{ trans('cruds.submittalsRfa.fields.description') }}</center></th>
                                            <th width="25%"><center>{{ trans('cruds.submittalsRfa.fields.qty_sets') }}</center></th>
                                            <th width="30%"><center>{{ trans('global.action') }}</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td colspan="4" align="center">
                                            <a name="add" id="add" class="btn btn-success">&nbsp&nbsp&nbsp&nbsp{{ trans('global.add') }}&nbsp&nbsp&nbsp&nbsp</a>
                                        </td>
                                    </tbody>
                                </table>
                        </div>

                        <div class="form-group {{ $errors->has('submittals_file') ? 'has-error' : '' }}">
                            <label for="submittals_file">{{ trans('cruds.rfa.fields.submittals_file') }}</label>
                            <div class="needsclick dropzone" id="submittals_file-dropzone">
                            </div>
                            @if($errors->has('submittals_file'))
                                <span class="help-block" role="alert">{{ $errors->first('submittals_file') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.submittals_file_helper') }}</span>
                        </div>
        
                        
                        <div class="form-row">

                            <a class="btn btn-default" href="{{ route('admin.rfas.index') }}">
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
                xhr.open('POST', '/admin/rfas/ckmedia', true);
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
                data.append('crud_id', {{ $rfa->id ?? 0 }});
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
    var uploadedFileUpload1Map = {}
Dropzone.options.fileUpload1Dropzone = {
    url: '{{ route('admin.rfas.storeMedia') }}',
    maxFilesize: 700, // MB
    addRemoveLinks: true,
    acceptedFiles: '.pdf',
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 700
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


    $('.doc_counter').change(function(){
        if($('.r_date') != ''){
            var parts =  document.getElementById("receive_date").value.split('/');
            var start_date = new Date(parts[2], parts[1] - 1, parts[0]);
            var target_date = new Date(parts[2], parts[1] - 1, parts[0]);   
            var addDate = 0;
            if($(this).val() == '7'){
                var toggle_date = new Date(parts[2], parts[1] - 1, parts[0]);  
                for(var i = 0; i < 6; i++){
                    if(toggle_date.getDay() == '0'){
                        i--;
                        addDate ++;
                    }
                    else{
                        addDate++;
                    }
                    toggle_date.setDate(toggle_date.getDate()+1);
                    console.log(toggle_date.getDay() + ' addDate[' + addDate +']');
                }
                target_date.setDate(start_date.getDate() + addDate);
            }
            else{
                var toggle_date = new Date(parts[2], parts[1] - 1, parts[0]);  
                for(var i = 0; i < 13; i++){
                    if(toggle_date.getDay() == '0'){
                        i--;
                        addDate ++;
                    }
                    else{
                        addDate++;
                    }
                    toggle_date.setDate(toggle_date.getDate()+1);
                    console.log(toggle_date.getDay() + ' addDate[' + addDate +']');
                }
                target_date.setDate(start_date.getDate() + addDate);
            }
            var dd = target_date.getDate();
            var mm = target_date.getMonth() + 1;
            var y = target_date.getFullYear();

            var format_target_date = dd + '/' + mm + '/' + y;
            document.getElementById("target_date").value = format_target_date;
           
        }
    });

    $('.r_date').click(function(){
        document.getElementById("target_date").value = null;
        document.getElementById("target_date").setText = null;
        $('.doc_counter').prop('selectedIndex', 0);

//        console.log('click');
    });

    /// Dynamic Input
    $(document).ready(function(){
        var count = 0;

        dynamic_field(count);

        function dynamic_field(number){
            var html = '<tr>';

            if(number > 0){
                html += '<td><input type="text" name="item[]" class="form-control" /></td>';
                html += '<td><input type="text" name="des[]" class="form-control" /></td>';
                html += '<td><input type="number" name="qty[]" class="form-control" /></td>';   
                html += '<td align="center"><a name="remove" id="remove" class="btn btn-danger">{{ trans('global.remove') }}</a></td></tr>';
                $('tbody').append(html);
            }
        }

        $('#add').click(function(){
            count++;
            dynamic_field(count);
            console.log(count);
        });

        $(document).on('click', '#remove', function(){
            count--;
            $(this).closest("tr").remove();
        });
    });

     function check_stamp() {
        
        if(document.getElementById("cec_stamp_2").checked == false){
          var str = "\"CONFIRM\"";
            swal({
                title: "{{ trans('global.change_box') }}",
                text: "{{ trans('global.please_enter') }}" + str + "{{ trans('global.to_confirm') }}",
                  type: "input",
                  showCancelButton: false,
                  closeOnConfirm: false,
                  inputPlaceholder: "CONFIRM"
              },
                  function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    if (inputValue == "CONFIRM" || inputValue == "confirm" || inputValue == "Confirm") {
                        if(document.getElementById("cec_stamp_2").checked == false){
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.stamp_to_form') }}",
                                type : "success",
                            });
                        }
                        document.getElementById("cec_stamp_1").checked = true;
                    }
                    else{
                        swal.showInputError("{{ trans('global.invalid_box') }}");
                        document.getElementById("cec_stamp_1").checked = false;
                        document.getElementById("cec_stamp_2").checked = true;
                  }
                    return false
                });
        }
     }


        function check_sign() {
        if(document.getElementById("cec_sign_2").checked == false){
            var str = "\"CONFIRM\"";
            swal({
                title: "{{ trans('global.change_box') }}",
                text: "{{ trans('global.please_enter') }}" + str + "{{ trans('global.to_confirm') }}",
                  type: "input",
                  showCancelButton: false,
                  closeOnConfirm: false,
                  inputPlaceholder: "CONFIRM"
              },
                  function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    if (inputValue == "CONFIRM" || inputValue == "confirm" || inputValue == "Confirm") {
                        if(document.getElementById("cec_sign_2").checked == false){
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.sign_to_form') }}",
                                type : "success",
                            });
                        }
                        document.getElementById("cec_sign_1").checked = true;
                    }
                    else{
                        swal.showInputError("{{ trans('global.invalid_box') }}");
                        document.getElementById("cec_sign_1").checked = false;
                        document.getElementById("cec_sign_2").checked = true;
                      }
                      return false
                    });
            }
        }

        $("#save_form").on('click',function(e) {
    
                event.preventDefault();

                swal({
                  title: "{{ trans('global.are_you_sure') }}",
                  text: "{{ trans('global.verify_form') }}",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#4BB543",
                  confirmButtonText: "{{ trans('global.yes_add') }}",
                  cancelButtonText: "{{ trans('global.no_cancel') }}",
                  closeOnConfirm: false,
                  closeOnCancel: false
                },
            function(isConfirm){
              if (isConfirm) {
                   // $('.swa-confirm').attr('data-flag', '1');
                    $('.swa-confirm').submit();
                  } else {
                swal("{{ trans('global.cancelled') }}", "{{trans('global.add_fail')}}", "error");  
              }
            });
        });


</script>
<script>
    var uploadedSubmittalsFileMap = {}
Dropzone.options.submittalsFileDropzone = {
    url: '{{ route('admin.rfas.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="submittals_file[]" value="' + response.name + '">')
      uploadedSubmittalsFileMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedSubmittalsFileMap[file.name]
      }
      $('form').find('input[name="submittals_file[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($rfa) && $rfa->submittals_file)
          var files =
            {!! json_encode($rfa->submittals_file) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="submittals_file[]" value="' + file.file_name + '">')
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