@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.complaint.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.complaints.update", [$complaint->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                       
                        <div class="form-group {{ $errors->has('operator') ? 'has-error' : '' }}">
                            <label for="operator_id">{{ trans('cruds.complaint.fields.operator') }}</label>
                            <select class="form-control select2" name="operator_id" id="operator_id">
                                @foreach($operators as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('operator_id') ? old('operator_id') : $complaint->operator->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('operator'))
                                <span class="help-block" role="alert">{{ $errors->first('operator') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.operator_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('action_detail') ? 'has-error' : '' }}">
                            <label for="action_detail">{{ trans('cruds.complaint.fields.action_detail') }}</label>
                            <textarea class="form-control" name="action_detail" id="action_detail">{{ old('action_detail', $complaint->action_detail) }}</textarea>
                            @if($errors->has('action_detail'))
                                <span class="help-block" role="alert">{{ $errors->first('action_detail') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.action_detail_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('progress_file') ? 'has-error' : '' }}">
                            <label for="progress_file">{{ trans('cruds.complaint.fields.progress_file') }}</label>
                            <div class="needsclick dropzone" id="progress_file-dropzone">
                            </div>
                            @if($errors->has('progress_file'))
                                <span class="help-block" role="alert">{{ $errors->first('progress_file') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.progress_file_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('action_date') ? 'has-error' : '' }}">
                            <label for="action_date">{{ trans('cruds.complaint.fields.action_date') }}</label>
                            <input class="form-control date" type="text" name="action_date" id="action_date" value="{{ old('action_date', $complaint->action_date) }}">
                            @if($errors->has('action_date'))
                                <span class="help-block" role="alert">{{ $errors->first('action_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.action_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.complaint.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Complaint::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $complaint->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.complaint.fields.status_helper') }}</span>
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
    var uploadedProgressFileMap = {}
Dropzone.options.progressFileDropzone = {
    url: '{{ route('admin.complaints.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="progress_file[]" value="' + response.name + '">')
      uploadedProgressFileMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedProgressFileMap[file.name]
      }
      $('form').find('input[name="progress_file[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($complaint) && $complaint->progress_file)
          var files =
            {!! json_encode($complaint->progress_file) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="progress_file[]" value="' + file.file_name + '">')
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