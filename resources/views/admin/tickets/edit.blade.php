@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.ticket.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.tickets.update", [$ticket->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label class="required" for="subject">{{ trans('cruds.ticket.fields.subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="subject" value="{{ old('subject', $ticket->subject) }}" required>
                            @if($errors->has('subject'))
                                <span class="help-block" role="alert">{{ $errors->first('subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ticket.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('request_type') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.ticket.fields.request_type') }}</label>
                            <select class="form-control" name="request_type" id="request_type" required>
                                <option value disabled {{ old('request_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Ticket::REQUEST_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('request_type', $ticket->request_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('request_type'))
                                <span class="help-block" role="alert">{{ $errors->first('request_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ticket.fields.request_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('module') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.ticket.fields.module') }}</label>
                            <select class="form-control" name="module" id="module" required>
                                <option value disabled {{ old('module', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Ticket::MODULE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('module', $ticket->module) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('module'))
                                <span class="help-block" role="alert">{{ $errors->first('module') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ticket.fields.module_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('detail') ? 'has-error' : '' }}">
                            <label class="required" for="detail">{{ trans('cruds.ticket.fields.detail') }}</label>
                            <textarea class="form-control" name="detail" id="detail" required>{{ old('detail', $ticket->detail) }}</textarea>
                            @if($errors->has('detail'))
                                <span class="help-block" role="alert">{{ $errors->first('detail') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ticket.fields.detail_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment') ? 'has-error' : '' }}">
                            <label for="attachment">{{ trans('cruds.ticket.fields.attachment') }}</label>
                            <div class="needsclick dropzone" id="attachment-dropzone">
                            </div>
                            @if($errors->has('attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ticket.fields.attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.ticket.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Ticket::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $ticket->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ticket.fields.status_helper') }}</span>
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
    var uploadedAttachmentMap = {}
Dropzone.options.attachmentDropzone = {
    url: '{{ route('admin.tickets.storeMedia') }}',
    maxFilesize: 150, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 150
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attachment[]" value="' + response.name + '">')
      uploadedAttachmentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttachmentMap[file.name]
      }
      $('form').find('input[name="attachment[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ticket) && $ticket->attachment)
          var files =
            {!! json_encode($ticket->attachment) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attachment[]" value="' + file.file_name + '">')
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