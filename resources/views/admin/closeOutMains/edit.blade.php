@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.closeOutMain.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.close-out-mains.update", [$closeOutMain->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.closeOutMain.fields.status') }}</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\CloseOutMain::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $closeOutMain->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.closeOutMain.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('construction_contract_id') ? old('construction_contract_id') : $closeOutMain->construction_contract->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('closeout_subject') ? 'has-error' : '' }}">
                            <label class="required" for="closeout_subject_id">{{ trans('cruds.closeOutMain.fields.closeout_subject') }}</label>
                            <select class="form-control select2" name="closeout_subject_id" id="closeout_subject_id" required>
                                @foreach($closeout_subjects as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('closeout_subject_id') ? old('closeout_subject_id') : $closeOutMain->closeout_subject->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('closeout_subject'))
                                <span class="help-block" role="alert">{{ $errors->first('closeout_subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.closeout_subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('detail') ? 'has-error' : '' }}">
                            <label class="required" for="detail">{{ trans('cruds.closeOutMain.fields.detail') }}</label>
                            <input class="form-control" type="text" name="detail" id="detail" value="{{ old('detail', $closeOutMain->detail) }}" required>
                            @if($errors->has('detail'))
                                <span class="help-block" role="alert">{{ $errors->first('detail') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.detail_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.closeOutMain.fields.description') }}</label>
                            <textarea class="form-control ckeditor" name="description" id="description">{!! old('description', $closeOutMain->description) !!}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
                            <label for="quantity">{{ trans('cruds.closeOutMain.fields.quantity') }}</label>
                            <input class="form-control" type="text" name="quantity" id="quantity" value="{{ old('quantity', $closeOutMain->quantity) }}">
                            @if($errors->has('quantity'))
                                <span class="help-block" role="alert">{{ $errors->first('quantity') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.quantity_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('ref_documents') ? 'has-error' : '' }}">
                            <label for="ref_documents">{{ trans('cruds.closeOutMain.fields.ref_documents') }}</label>
                            <input class="form-control" type="text" name="ref_documents" id="ref_documents" value="{{ old('ref_documents', $closeOutMain->ref_documents) }}">
                            @if($errors->has('ref_documents'))
                                <span class="help-block" role="alert">{{ $errors->first('ref_documents') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.ref_documents_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('final_file') ? 'has-error' : '' }}">
                            <label class="required" for="final_file">{{ trans('cruds.closeOutMain.fields.final_file') }}</label>
                            <div class="needsclick dropzone" id="final_file-dropzone">
                            </div>
                            @if($errors->has('final_file'))
                                <span class="help-block" role="alert">{{ $errors->first('final_file') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.final_file_helper') }}</span>
                        </div>
                        @php
                            $existingCloseoutFilenames = $closeOutMain->closeout_urls->pluck('filename')->toArray();
                            $existingCloseoutUrls = $closeOutMain->closeout_urls->pluck('url')->toArray();
                            $existingCloseoutIds = $closeOutMain->closeout_urls->pluck('id')->toArray();

                            $oldCloseoutFilenames = old('closeout_url_filename', $existingCloseoutFilenames);
                            $oldCloseoutUrls = old('closeout_url_url', $existingCloseoutUrls);
                            $oldCloseoutIds = old('closeout_url_ids', $existingCloseoutIds);
                        @endphp
                        <div class="form-group {{ $errors->has('closeout_url_filename') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.closeOutMain.fields.closeout_url') }}</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="closeout-url-table">
                                    <thead>
                                        <tr>
                                            <th width="35%">{{ trans('cruds.closeOutMain.fields.closeout_url') }} - {{ trans('cruds.closeOutMain.fields.ref_documents') }}</th>
                                            <th width="50%">URL</th>
                                            <th width="15%" class="text-center">{{ trans('global.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($oldCloseoutFilenames))
                                            @foreach($oldCloseoutFilenames as $idx => $filename)
                                                <tr class="closeout-url-row">
                                                    <td>
                                                        <input type="text" class="form-control" name="closeout_url_filename[]" value="{{ $filename }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="closeout_url_url[]" value="{{ $oldCloseoutUrls[$idx] ?? '' }}">
                                                        <input type="hidden" name="closeout_url_ids[]" value="{{ $oldCloseoutIds[$idx] ?? '' }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm remove-closeout-url">{{ trans('global.remove') }}</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="closeout-url-row">
                                                <td>
                                                    <input type="text" class="form-control" name="closeout_url_filename[]" value="">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="closeout_url_url[]" value="">
                                                    <input type="hidden" name="closeout_url_ids[]" value="">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-closeout-url">{{ trans('global.remove') }}</button>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <button type="button" class="btn btn-success btn-sm" id="closeout-url-add">{{ trans('global.add') }}</button>
                                    <button type="button" class="btn btn-primary btn-sm" id="closeout-url-paste">Paste Data from Excel</button>
                                </div>
                            </div>
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.closeout_url_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('ref_rfa_text') ? 'has-error' : '' }}">
                            <label for="ref_rfa_text">{{ trans('cruds.closeOutMain.fields.ref_rfa_text') }}</label>
                            <input class="form-control" type="text" name="ref_rfa_text" id="ref_rfa_text" value="{{ old('ref_rfa_text', $closeOutMain->ref_rfa_text) }}">
                            @if($errors->has('ref_rfa_text'))
                                <span class="help-block" role="alert">{{ $errors->first('ref_rfa_text') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.ref_rfa_text_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('remark') ? 'has-error' : '' }}">
                            <label for="remark">{{ trans('cruds.closeOutMain.fields.remark') }}</label>
                            <textarea class="form-control ckeditor" name="remark" id="remark">{!! old('remark', $closeOutMain->remark) !!}</textarea>
                            @if($errors->has('remark'))
                                <span class="help-block" role="alert">{{ $errors->first('remark') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.remark_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.close-out-mains.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $closeOutMain->id ?? 0 }}');
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
    var uploadedFinalFileMap = {}
Dropzone.options.finalFileDropzone = {
    url: '{{ route('admin.close-out-mains.storeMedia') }}',
    maxFilesize: 1000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1000
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="final_file[]" value="' + response.name + '">')
      uploadedFinalFileMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFinalFileMap[file.name]
      }
      $('form').find('input[name="final_file[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($closeOutMain) && $closeOutMain->final_file)
          var files =
            {!! json_encode($closeOutMain->final_file) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="final_file[]" value="' + file.file_name + '">')
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
    $(function () {
        function addCloseoutUrlRow(filename = '', url = '', id = '') {
            const row = `
                <tr class="closeout-url-row">
                    <td>
                        <input type="text" class="form-control" name="closeout_url_filename[]" value="${filename}">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="closeout_url_url[]" value="${url}">
                        <input type="hidden" name="closeout_url_ids[]" value="${id}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-closeout-url">{{ trans('global.remove') }}</button>
                    </td>
                </tr>
            `;
            $('#closeout-url-table tbody').append(row);
        }

        $('#closeout-url-add').on('click', function () {
            addCloseoutUrlRow();
        });

        $(document).on('click', '.remove-closeout-url', function () {
            $(this).closest('tr').remove();
        });

        $('#closeout-url-paste').on('click', function () {
            navigator.clipboard.readText().then(function (text) {
                const rows = text.split('\n').filter(row => row.trim().length);
                rows.forEach(function (row) {
                    const columns = row.split('\t');
                    if (columns.length >= 2) {
                        addCloseoutUrlRow(columns[0], columns[1]);
                    }
                });
            }).catch(function () {
                alert('Unable to read clipboard. Please allow clipboard permissions or paste manually.');
            });
        });
    });
</script>
@endsection