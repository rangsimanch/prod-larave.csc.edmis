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
                        <div class="form-group {{ $errors->has('document_status') ? 'has-error' : '' }}" hidden>
                            <label for="document_status_id">{{ trans('cruds.rfa.fields.document_status') }}</label>
                            <select class="form-control select2" name="document_status_id" id="document_status_id">
                                @foreach($document_statuses as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('document_status_id') ? old('document_status_id') : $rfa->document_status->id ?? '') == $id ? 'selected' : '' }}>{{ $id }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('document_status'))
                                <span class="help-block" role="alert">{{ $errors->first('document_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_status_helper') }}</span>
                        </div>

                        <legend><a onclick="HideSection(1)" id="element1"><i class="bi bi-eye"></i></a><b>  Section I : Contractor RFA Submittal</b></legend>
                        <div id="section1">

                         <div class="form-group {{ $errors->has('title_eng') ? 'has-error' : '' }}">
                            <label for="title_eng">{{ trans('cruds.rfa.fields.title_eng') }}</label>
                            <input class="form-control" type="text" name="title_eng" id="title_eng" value="{{ old('title_eng', $rfa->title_eng) }}">
                            @if($errors->has('title_eng'))
                                <span class="help-block" role="alert">{{ $errors->first('title_eng') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_eng_helper') }}</span>
                        </div>

                         <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title">{{ trans('cruds.rfa.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $rfa->title) }}">
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_helper') }}</span>
                        </div>

                         <div class="form-group {{ $errors->has('title_cn') ? 'has-error' : '' }}">
                            <label for="title_cn">{{ trans('cruds.rfa.fields.title_cn') }}</label>
                            <input class="form-control" type="text" name="title_cn" id="title_cn" value="{{ old('title_cn', $rfa->title_cn) }}">
                            @if($errors->has('title_cn'))
                                <span class="help-block" role="alert">{{ $errors->first('title_cn') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_cn_helper') }}</span>
                        </div>
                       
                     
                        {{-- <div class="form-group {{ $errors->has('wbs_level_3') ? 'has-error' : '' }}">
                            <label for="wbs_level_3_id">{{ trans('cruds.rfa.fields.wbs_level_3') }}</label>
                            <select class="form-control select2 wbslv3" name="wbs_level_3_id" id="wbs_level_3_id">
                                @foreach($wbs_level_3s as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('wbs_level_3_id') ? old('wbs_level_3_id') : $rfa->wbs_level_3->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_3'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.wbs_level_3_helper') }}</span>
                        </div>
                      
                        <div class="form-group {{ $errors->has('wbs_level_4') ? 'has-error' : '' }}">
                            <label for="wbs_level_4_id">{{ trans('cruds.rfa.fields.wbs_level_4') }}</label>
                            <select class="form-control select2 wbslv4" name="wbs_level_4_id" id="wbs_level_4_id">
                                @foreach($wbs_level_4s as $id => $entry)
                                    <option value="{{ $wbs_level_4s }}" {{ (old('wbs_level_4_id') ? old('wbs_level_4_id') : $rfa->wbs_level_4->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_4'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_4') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.wbs_level_4_helper') }}</span>
                        </div> --}}
                    
                    <div class="form-group {{ $errors->has('assign') ? 'has-error' : '' }}">
                        <label for="assign_id">{{ trans('cruds.rfa.fields.assign') }}</label>
                        <select class="form-control select2" name="assign_id" id="assign_id">
                            @foreach($assigns as $id => $entry)
                                <option value="{{ $id }}" {{ (old('assign_id') ? old('assign_id') : $rfa->assign->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('assign'))
                            <span class="help-block" role="alert">{{ $errors->first('assign') }}</span>
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

                    <div class="form-group {{ $errors->has('attach_file_name') ? 'has-error' : '' }}">
                            <label for="attach_file_name">{{ trans('cruds.rfa.fields.attach_file_name') }}</label>
                            <input class="form-control" type="text" name="attach_file_name" id="attach_file_name" value="{{ old('attach_file_name', $rfa->attach_file_name) }}">
                            @if($errors->has('attach_file_name'))
                                <span class="help-block" role="alert">{{ $errors->first('attach_file_name') }}</span>
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
                        <input class="form-control" type="text" name="qty_page" id="qty_page" value="{{ old('qty_page', $rfa->qty_page) }}">
                        @if($errors->has('qty_page'))
                            <span class="help-block" role="alert">{{ $errors->first('qty_page') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.rfa.fields.qty_page_helper') }}</span>
                    </div>

                    <div class="form-group {{ $errors->has('spec_ref_no') ? 'has-error' : '' }}">
                        <label for="spec_ref_no">{{ trans('cruds.rfa.fields.spec_ref_no') }}</label>
                        <input class="form-control" type="text" name="spec_ref_no" id="spec_ref_no" value="{{ old('spec_ref_no', $rfa->spec_ref_no) }}">
                        @if($errors->has('spec_ref_no'))
                            <span class="help-block" role="alert">{{ $errors->first('spec_ref_no') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.rfa.fields.spec_ref_no_helper') }}</span>
                    </div>

                     <div class="form-group {{ $errors->has('clause') ? 'has-error' : '' }}">
                            <label for="clause">{{ trans('cruds.rfa.fields.clause') }}</label>
                            <input class="form-control" type="text" name="clause" id="clause" value="{{ old('clause', $rfa->clause) }}">
                            @if($errors->has('clause'))
                                <span class="help-block" role="alert">{{ $errors->first('clause') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.clause_helper') }}</span>
                    </div>

                      <div class="form-group {{ $errors->has('contract_drawing_no') ? 'has-error' : '' }}">
                            <label for="contract_drawing_no">{{ trans('cruds.rfa.fields.contract_drawing_no') }}</label>
                            <input class="form-control" type="text" name="contract_drawing_no" id="contract_drawing_no" value="{{ old('contract_drawing_no', $rfa->contract_drawing_no) }}">
                            @if($errors->has('contract_drawing_no'))
                                <span class="help-block" role="alert">{{ $errors->first('contract_drawing_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.contract_drawing_no_helper') }}</span>
                     </div>
                </div>
                        
                    <!-- IF STATUS NEW -->
                       {{-- @if($rfa->document_status_id == 1) --}}
                       <legend><a onclick="HideSection(2)" id="element2"><i class="bi bi-eye"></i></a><b>  Section II : Incoming Distribution</b></legend>
                       <div id="section2">
                       {{-- <legend>Incoming Distribution</legend> --}}
                       <div class="form-group {{ $errors->has('assign') ? 'has-error' : '' }}">
                            <label class="required" for="assign_id">{{ trans('cruds.rfa.fields.assign') }}</label>
                            <select class="form-control select2" name="assign_id" id="assign_id">
                                @foreach($assigns as $id => $assign)
                                    <option value="{{ $id }}" {{ (old('assign_id') ? old('assign_id') : $rfa->assign->id ?? '') == $id ? 'selected' : '' }}>{{ $assign }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('assign_id'))
                                <span class="help-block" role="alert">{{ $errors->first('assign_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.assign_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('action_by') ? 'has-error' : '' }}">
                            <label class="required" for="action_by_id">{{ trans('cruds.rfa.fields.action_by') }}</label>
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
                            <input class="form-control date r_date" type="text" name="receive_date" id="receive_date" value="{{ old('receive_date', $rfa->receive_date) }}">
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
                        {{-- @endif --}}
                    </div>

                        <!-- IF STATUS WORKING -->
                        {{-- @if($rfa->document_status_id == 2) --}}
                        <legend><a onclick="HideSection(3)" id="element3"><i class="bi bi-eye"></i></a><b>  SECTION III : CSC Outgoing (Specialist/Engineer)</b></legend>
                        <div id="section3">
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

                        <div class="form-group {{ $errors->has('note_3') ? 'has-error' : '' }}">
                            <label for="note_3">{{ trans('cruds.rfa.fields.note_3') }}</label>
                            <textarea class="form-control" name="note_3" id="note_3">{{ old('note_3', $rfa->note_3) }}</textarea>
                            @if($errors->has('note_3'))
                                <span class="help-block" role="alert">{{ $errors->first('note_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_3_helper') }}</span>
                        </div>
                        {{-- @endif --}}



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

                        {{-- @endif --}}
                        </div>


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
            var dd = ("0" + (target_date.getDate()).slice(-2));
            var mm = ("0" + (target_date.getMonth() + 1)).slice(-2);
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

const select2 = document.getElementsByClassName("select2");
    for (let i = 0; i < select2.length; i++) {
        select2[i].style.cssText = 'width:100%!important;';
    }

var documents_status = document.getElementById("document_status_id").value
if(documents_status == "1"){
	var section1 = document.getElementById("section1");
    var element1 = document.getElementById('element1');
    section1.style.display = "none";
    element1.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
    
    var section3 = document.getElementById("section3");
    var element3 = document.getElementById('element3');
    section3.style.display = "none";
    element3.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';


    //$("#action_by_id").attr('required', '');    //turns required on

}

if(documents_status == "2"){
	var section1 = document.getElementById("section1");
    var element1 = document.getElementById('element1');
    section1.style.display = "none";
    element1.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
    
    var section2 = document.getElementById("section2");
    var element2 = document.getElementById('element2');
    section2.style.display = "none";
    element2.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';

    // $("#responsible_id").attr('required', '');    //turns required on
    // $("#review_status").attr('required', '');    //turns required on
    //$("#comment_status_id").attr('required', '');    //turns required on


}

if(documents_status == "3" || documents_status == "4"){
	var section1 = document.getElementById("section1");
    var element1 = document.getElementById('element1');
    section1.style.display = "none";
    element1.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
    
    var section2 = document.getElementById("section2");
    var element2 = document.getElementById('element2');
    section2.style.display = "none";
    element2.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';

    // var section3 = document.getElementById("section3");
    // var element3 = document.getElementById('element3');
    // section3.style.display = "none";
    // element3.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';

    // $("#responsible_id").attr('required', '');    //turns required on
    // $("#review_status").attr('required', '');    //turns required on
    // $("#auditing_status").attr('required', '');    //turns required on
    // $("#comment_status_id").attr('required', '');    //turns required on

}

function HideSection(idElement) {
    var element = document.getElementById('element' + idElement);
    if (idElement === 1 || idElement === 2 || idElement === 3 || idElement === 4) {
        if (element.innerHTML === '<i class="bi bi-eye"></i>') 					
            element.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
        else {
            element.innerHTML = '<i class="bi bi-eye"></i>';
        }
        if(idElement === 1){
        	var section = document.getElementById("section1");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
        if(idElement === 2){
        	var section = document.getElementById("section2");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
        if(idElement === 3){
        	var section = document.getElementById("section3");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
        if(idElement === 4){
        	var section = document.getElementById("section4");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
    }
}
</script>

@endsection