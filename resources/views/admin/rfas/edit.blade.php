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
                    <form method="POST" action="{{ route("admin.rfas.update", [$rfa->id]) }}"  class="swa-confirm" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        
                    <!-- IF STATUS NEW -->
                       @if($rfa->document_status_id == 1)
                       <legend>Incoming Distribution</legend>
                        <div class="form-group {{ $errors->has('action_by') ? 'has-error' : '' }}">
                            <label for="action_by_id">{{ trans('cruds.rfa.fields.action_by') }}</label>
                            <select class="form-control select2" name="action_by_id" id="action_by_id">
                                @foreach($action_bies as $id => $action_by)
                                    <option value="{{ $id }}" {{ (old('action_by_id') ? old('action_by_id') : $rfa->action_by->id ?? '') == $id ? 'selected' : '' }}>{{ $action_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('action_by'))
                                <span class="help-block" role="alert">{{ $errors->first('action_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.action_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('comment_by') ? 'has-error' : '' }}">
                            <label for="comment_by_id">{{ trans('cruds.rfa.fields.comment_by') }}</label>
                            <select class="form-control select2" name="comment_by_id" id="comment_by_id">
                                @foreach($comment_bies as $id => $comment_by)
                                    <option value="{{ $id }}" {{ (old('comment_by_id') ? old('comment_by_id') : $rfa->comment_by->id ?? '') == $id ? 'selected' : '' }}>{{ $comment_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('comment_by'))
                                <span class="help-block" role="alert">{{ $errors->first('comment_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.comment_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('information_by') ? 'has-error' : '' }}">
                            <label for="information_by_id">{{ trans('cruds.rfa.fields.information_by') }}</label>
                            <select class="form-control select2" name="information_by_id" id="information_by_id">
                                @foreach($information_bies as $id => $information_by)
                                    <option value="{{ $id }}" {{ (old('information_by_id') ? old('information_by_id') : $rfa->information_by->id ?? '') == $id ? 'selected' : '' }}>{{ $information_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('information_by'))
                                <span class="help-block" role="alert">{{ $errors->first('information_by') }}</span>
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

                        <div class="form-group {{ $errors->has('doc_count') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.rfa.fields.doc_count') }}</label>
                            <select class="form-control doc_counter" id="doc_count" name="doc_count">
                            <option value disabled {{ old('doc_count', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Rfa::DOC_COUNT_RADIO as $key => $label)
                            <option value="{{ $key }}" {{ old('doc_count', $rfa->doc_count) === (string) $key ? 'selected' : '' }}>{{ $label . ' Days' }}</option>
                            @endforeach
                            </select>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.doc_count_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('target_date') ? 'has-error' : '' }}">
                            <label for="target_date">{{ trans('cruds.rfa.fields.target_date') }}</label>
                            <input readonly class="form-control date" type="text" name="target_date" id="target_date" value="{{ old('target_date' , $rfa->target_date) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.target_date_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('note_2') ? 'has-error' : '' }}">
                            <label for="note_2">{{ trans('cruds.rfa.fields.note_2') }}</label>
                            <textarea class="form-control" name="note_2" id="note_2">{{ old('note_2', $rfa->note_2) }}</textarea>
                            @if($errors->has('note_2'))
                                <span class="help-block" role="alert">{{ $errors->first('note_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_2_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('work_file_upload') ? 'has-error' : '' }}">
                            <label for="work_file_upload">{{ trans('cruds.rfa.fields.work_file_upload') }}</label>
                            <div class="needsclick dropzone" id="work_file_upload-dropzone">
                            </div>
                            @if($errors->has('work_file_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('work_file_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.work_file_upload_helper') }}</span>
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
                        @endif

                        <!-- IF STATUS WORKING -->
                        @if($rfa->document_status_id == 2)
                        <legend> CSC Outgoing (Specialist/Engineer) </legend>
                        
                        <div class="form-group {{ $errors->has('action_by') ? 'has-error' : '' }}">
                            <label for="action_by_id">{{ trans('cruds.rfa.fields.action_by') }}</label>
                            <select class="form-control select2" name="action_by_id" id="action_by_id" disabled>
                                @foreach($action_bies as $id => $action_by)
                                    <option value="{{ $id }}" {{ (old('action_by_id') ? old('action_by_id') : $rfa->action_by->id ?? '') == $id ? 'selected' : '' }}>{{ $action_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('action_by'))
                                <span class="help-block" role="alert">{{ $errors->first('action_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.action_by_helper') }}</span>
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

                        <div class="form-group {{ $errors->has('commercial_file_upload') ? 'has-error' : '' }}">
                            <label for="commercial_file_upload">{{ trans('cruds.rfa.fields.commercial_file_upload') }}</label>
                            <div class="needsclick dropzone" id="commercial_file_upload-dropzone"></div>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.commercial_file_upload_helper') }}</span>
                        </div>
                        @endif

                          <!-- IF STATUS REVIEWED -->
                        @if($rfa->document_status_id == 3 || $rfa->document_status_id == 4)
                        <div class="form-group {{ $errors->has('reviewed_by') ? 'has-error' : '' }}">
                            <label for="reviewed_by_id">{{ trans('cruds.rfa.fields.reviewed_by') }}</label>
                            <select class="form-control select2" name="reviewed_by_id" id="reviewed_by_id">
                                @foreach($reviewed_bies as $id => $reviewed_by)
                                    <option value="{{ $id }}" {{ ($rfa->reviewed_by ? $rfa->reviewed_by->id : old('reviewed_by_id')) == $id ? 'selected' : '' }}>{{ $reviewed_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('reviewed_by'))
                                <span class="help-block" role="alert">{{ $errors->first('reviewed_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.reviewed_by_helper') }}</span>
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

                        <div class="form-group {{ $errors->has('note_4') ? 'has-error' : '' }}">
                            <label for="note_4">{{ trans('cruds.rfa.fields.note_4') }}</label>
                            <textarea class="form-control" name="note_4" id="note_4">{{ old('note_4', $rfa->note_4) }}</textarea>
                            @if($errors->has('note_4'))
                                <span class="help-block" role="alert">{{ $errors->first('note_4') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_4_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('commercial_file_upload') ? 'has-error' : '' }}">
                            <label for="commercial_file_upload">{{ trans('cruds.rfa.fields.commercial_file_upload') }}</label>
                            <div class="needsclick dropzone" id="commercial_file_upload-dropzone"></div>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.commercial_file_upload_helper') }}</span>
                        </div>

                        @endif


                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.rfas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>


                            <button class="btn btn-success" type="submit" id="save_form">
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
    maxFilesize: 1000, // MB
    addRemoveLinks: true,
    acceptedFiles: '.pdf',
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1000
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
<script>
    var uploadedCommercialFileUploadMap = {}
Dropzone.options.commercialFileUploadDropzone = {
    url: '{{ route('admin.rfas.storeMedia') }}',
    maxFilesize: 1000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1000
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="commercial_file_upload[]" value="' + response.name + '">')
      uploadedCommercialFileUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedCommercialFileUploadMap[file.name]
      }
      $('form').find('input[name="commercial_file_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($rfa) && $rfa->commercial_file_upload)
          var files =
            {!! json_encode($rfa->commercial_file_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="commercial_file_upload[]" value="' + file.file_name + '">')
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
    var uploadedDocumentFileUploadMap = {}
Dropzone.options.documentFileUploadDropzone = {
    url: '{{ route('admin.rfas.storeMedia') }}',
    maxFilesize: 1000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1000
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="document_file_upload[]" value="' + response.name + '">')
      uploadedDocumentFileUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDocumentFileUploadMap[file.name]
      }
      $('form').find('input[name="document_file_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($rfa) && $rfa->document_file_upload)
          var files =
            {!! json_encode($rfa->document_file_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="document_file_upload[]" value="' + file.file_name + '">')
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
            var dd = ("0" + (target_date.getDate() + 2)).slice(-2);
            var mm = ("0" + (target_date.getMonth() + 1)).slice(-2)
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
    
$("#ApproveAll").click(function () {
    var ident = '1';
    $(".review_status").val(ident).prop("selected", true).change();
    $("#AsNoteAll").prop("checked", false);
    $("#ResubmitAll").prop("checked", false);
    $("#RejectAll").prop("checked", false);
});

$("#AsNoteAll").click(function () {
    var ident = '2';
    $(".review_status").val(ident).prop("selected", true).change();
    $("#ApproveAll").prop("checked", false);
    $("#ResubmitAll").prop("checked", false);
    $("#RejectAll").prop("checked", false);
});

$("#ResubmitAll").click(function () {
    var ident = '3';
    $(".review_status").val(ident).prop("selected", true).change();
    $("#AsNoteAll").prop("checked", false);
    $("#ApproveAll").prop("checked", false);
    $("#RejectAll").prop("checked", false);
});

$("#RejectAll").click(function () {
    var ident = '4';
    $(".review_status").val(ident).prop("selected", true).change();
    $("#AsNoteAll").prop("checked", false);
    $("#ResubmitAll").prop("checked", false);
    $("#ApproveAll").prop("checked", false);
});

const getTwoDigits = (value) => value < 10 ? `0${value}` : value;

const formatDate = (date) => {
  const day = getTwoDigits(date.getDate());
  const month = getTwoDigits(date.getMonth() + 1); // add 1 since getMonth returns 0-11 for the months
  const year = date.getFullYear();

  return `${year}-${month}-${day}`;
}

const formatTime = (date) => {
  const hours = getTwoDigits(date.getHours());
  const mins = getTwoDigits(date.getMinutes());

  return `${hours}:${mins}`;
}

const date = new Date();
$(".date_returned").val(formatDate(date));

function check_stamp() {
        
          var str = "\"CONFIRM\"";
            swal({
                title: "{{ trans('global.change_box') }}",
                text: "{{ trans('global.please_enter') }}" + str + "{{ trans('global.to_confirm') }}",
                  type: "input",
                  showCancelButton: true,
                  closeOnConfirm: false,
                  inputPlaceholder: "CONFIRM"
              },
                  function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    if (inputValue == "CONFIRM") {
                        if(document.getElementById("cec_stamp_2").checked == false){
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.stamp_to_form') }}",
                                type : "success",
                            });
                        }
                        else{
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.unstamp_to_form') }}",
                                type : "success",
                            });
                        }
                    }
                    else{
                        swal.showInputError("{{ trans('global.invalid_box') }}");
                        if(document.getElementById("cec_stamp_2").checked == false){
                            document.getElementById("cec_stamp_2").checked = true;
                            document.getElementById("cec_stamp_1").checked = false;
                        }
                    else{
                        document.getElementById("cec_stamp_1").checked = true;
                        document.getElementById("cec_stamp_2").checked = false;
                    }
                  }
                    return false
                });
        }


        function check_sign() {
            var str = "\"CONFIRM\"";
            swal({
                title: "{{ trans('global.change_box') }}",
                text: "{{ trans('global.please_enter') }}" + str + "{{ trans('global.to_confirm') }}",
                  type: "input",
                  showCancelButton: true,
                  closeOnConfirm: false,
                  inputPlaceholder: "CONFIRM"
              },
                  function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    if (inputValue == "CONFIRM") {
                        if(document.getElementById("cec_sign_2").checked == false){
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.sign_to_form') }}",
                                type : "success",
                            });
                        }
                        else{
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.unsign_to_form') }}",
                                type : "success",
                            });
                        }
                    }
                    else{
                        swal.showInputError("{{ trans('global.invalid_box') }}");
                        
                        if(document.getElementById("cec_sign_2").checked == false){
                                document.getElementById("cec_sign_2").checked = true;
                                document.getElementById("cec_sign_1").checked = false;
                            }
                        else{
                                document.getElementById("cec_sign_1").checked = true;
                                document.getElementById("cec_sign_2").checked = false;
                            }
                      }
                      return false
                    });
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

<script>
    var uploadedWorkFileUploadMap = {}
Dropzone.options.workFileUploadDropzone = {
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
      $('form').append('<input type="hidden" name="work_file_upload[]" value="' + response.name + '">')
      uploadedWorkFileUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedWorkFileUploadMap[file.name]
      }
      $('form').find('input[name="work_file_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($rfa) && $rfa->work_file_upload)
          var files =
            {!! json_encode($rfa->work_file_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="work_file_upload[]" value="' + file.file_name + '">')
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