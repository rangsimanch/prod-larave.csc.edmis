@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.closeOutMain.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.close-out-mains.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.closeOutMain.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $entry)
                                    <option value="{{ $id }}" {{ old('construction_contract_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.construction_contract_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('chapter_no') ? 'has-error' : '' }}">
                            <label class="required" for="chapter_no">{{ trans('cruds.closeOutMain.fields.chapter_no') }}</label>
                            <input class="form-control" type="text" name="chapter_no" id="chapter_no" value="{{ old('chapter_no', '') }}" required>
                            @if($errors->has('chapter_no'))
                                <span class="help-block" role="alert">{{ $errors->first('chapter_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.chapter_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="required" for="title">{{ trans('cruds.closeOutMain.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutMain.fields.title_helper') }}</span>
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
@endsection