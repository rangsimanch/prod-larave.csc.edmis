@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.recoveryFile.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.recovery-files.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('dir_name') ? 'has-error' : '' }}">
                            <label class="required" for="dir_name">{{ trans('cruds.recoveryFile.fields.dir_name') }}</label>
                            <input class="form-control" type="text" name="dir_name" id="dir_name" value="{{ old('dir_name', '') }}" required>
                            @if($errors->has('dir_name'))
                                <span class="help-block" role="alert">{{ $errors->first('dir_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recoveryFile.fields.dir_name_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('recovery_file') ? 'has-error' : '' }}">
                            <label for="recovery_file">{{ trans('cruds.recoveryFile.fields.recovery_file') }}</label>
                            <div class="needsclick dropzone" id="recovery_file-dropzone">
                            </div>
                            @if($errors->has('recovery_file'))
                                <span class="help-block" role="alert">{{ $errors->first('recovery_file') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recoveryFile.fields.recovery_file_helper') }}</span>
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
    var uploadedRecoveryFileMap = {}
Dropzone.options.recoveryFileDropzone = {
    url: '{{ route('admin.recovery-files.storeMedia') }}',
    maxFilesize: 50000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10000
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="recovery_file[]" value="' + response.name + '">')
      uploadedRecoveryFileMap[file.name] = response.name
    },
    renameFile: function (file) {
        var name = '';
        var fileName = file.name;
        var lastDot = fileName.lastIndexOf('.');
        var strLength = fileName.length;
        var ext = fileName.substring(lastDot + 1);

        if(strLength > 50){
            newName = fileName.substring(0, 50);
            name = fileName + '.' + ext;
        }
        else{
            name = fileName;
        }
        return name;
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedRecoveryFileMap[file.name]
      }
      $('form').find('input[name="recovery_file[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($recoveryFile) && $recoveryFile->recovery_file)
          var files =
            {!! json_encode($recoveryFile->recovery_file) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="recovery_file[]" value="' + file.file_name + '">')
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