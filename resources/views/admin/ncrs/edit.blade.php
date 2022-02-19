@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.ncr.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.ncrs.update", [$ncr->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                       
                        <div class="form-group {{ $errors->has('acceptance_date') ? 'has-error' : '' }}">
                            <label for="acceptance_date">{{ trans('cruds.ncr.fields.acceptance_date') }}</label>
                            <input class="form-control date" type="text" name="acceptance_date" id="acceptance_date" value="{{ old('acceptance_date', $ncr->acceptance_date) }}">
                            @if($errors->has('acceptance_date'))
                                <span class="help-block" role="alert">{{ $errors->first('acceptance_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.acceptance_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('file_attachment') ? 'has-error' : '' }}">
                            <label for="file_attachment">{{ trans('cruds.ncr.fields.file_attachment') }}</label>
                            <div class="needsclick dropzone" id="file_attachment-dropzone">
                            </div>
                            @if($errors->has('file_attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('file_attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.file_attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('prepared_by') ? 'has-error' : '' }}">
                            <label for="prepared_by_id">{{ trans('cruds.ncr.fields.prepared_by') }}</label>
                            <select class="form-control select2" name="prepared_by_id" id="prepared_by_id">
                                @foreach($prepared_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('prepared_by_id') ? old('prepared_by_id') : $ncr->prepared_by->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('prepared_by'))
                                <span class="help-block" role="alert">{{ $errors->first('prepared_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.prepared_by_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('documents_status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.ncr.fields.documents_status') }}</label>
                            <select class="form-control" name="documents_status" id="documents_status">
                                <option value disabled {{ old('documents_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Ncr::DOCUMENTS_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('documents_status', $ncr->documents_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('documents_status'))
                                <span class="help-block" role="alert">{{ $errors->first('documents_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.documents_status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_specialist') ? 'has-error' : '' }}">
                            <label for="construction_specialist_id">{{ trans('cruds.ncr.fields.construction_specialist') }}</label>
                            <select class="form-control select2" name="construction_specialist_id" id="construction_specialist_id">
                                @foreach($construction_specialists as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('construction_specialist_id') ? old('construction_specialist_id') : $ncr->construction_specialist->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_specialist'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_specialist') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.construction_specialist_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('leader') ? 'has-error' : '' }}">
                            <label for="leader_id">{{ trans('cruds.ncr.fields.leader') }}</label>
                            <select class="form-control select2" name="leader_id" id="leader_id">
                                @foreach($leaders as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('leader_id') ? old('leader_id') : $ncr->leader->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('leader'))
                                <span class="help-block" role="alert">{{ $errors->first('leader') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ncr.fields.leader_helper') }}</span>
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
    var uploadedFileAttachmentMap = {}
Dropzone.options.fileAttachmentDropzone = {
    url: '{{ route('admin.ncrs.storeMedia') }}',
    maxFilesize: 5000, // MB
    addRemoveLinks: true,
    acceptedFiles: '.pdf',
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="file_attachment[]" value="' + response.name + '">')
      uploadedFileAttachmentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFileAttachmentMap[file.name]
      }
      $('form').find('input[name="file_attachment[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ncr) && $ncr->file_attachment)
          var files =
            {!! json_encode($ncr->file_attachment) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="file_attachment[]" value="' + file.file_name + '">')
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