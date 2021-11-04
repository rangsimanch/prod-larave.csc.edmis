@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.addLetter.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.add-letters.update", [$addLetter->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('received_date') ? 'has-error' : '' }}">
                            <label for="received_date">{{ trans('cruds.addLetter.fields.received_date') }}</label>
                            <input class="form-control date" type="text" name="received_date" id="received_date" value="{{ old('received_date', $addLetter->received_date) }}">
                            @if($errors->has('received_date'))
                                <span class="help-block" role="alert">{{ $errors->first('received_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.received_date_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('mask_as_received') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="mask_as_received" value="0">
                                <input type="checkbox" name="mask_as_received" id="mask_as_received" value="1" {{ $addLetter->mask_as_received || old('mask_as_received', 0) === 1 ? 'checked' : '' }}>
                                <label for="mask_as_received" style="font-weight: 400">{{ trans('cruds.addLetter.fields.mask_as_received') }}</label>
                            </div>
                            @if($errors->has('mask_as_received'))
                                <span class="help-block" role="alert">{{ $errors->first('mask_as_received') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.mask_as_received_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                            <label for="note">{{ trans('cruds.addLetter.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note', $addLetter->note) }}</textarea>
                            @if($errors->has('note'))
                                <span class="help-block" role="alert">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.note_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('letter_upload') ? 'has-error' : '' }}">
                            <label class="required" for="letter_upload">{{ trans('cruds.addLetter.fields.letter_upload') }}</label>
                            <div class="needsclick dropzone" id="letter_upload-dropzone">
                            </div>
                            @if($errors->has('letter_upload'))
                                <span class="help-block" role="alert">{{ $errors->first('letter_upload') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.letter_upload_helper') }}</span>
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
    var uploadedLetterUploadMap = {}
Dropzone.options.letterUploadDropzone = {
    url: '{{ route('admin.add-letters.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="letter_upload[]" value="' + response.name + '">')
      uploadedLetterUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedLetterUploadMap[file.name]
      }
      $('form').find('input[name="letter_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($addLetter) && $addLetter->letter_upload)
          var files =
            {!! json_encode($addLetter->letter_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="letter_upload[]" value="' + file.file_name + '">')
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