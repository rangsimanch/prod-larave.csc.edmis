@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.task.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.tasks.update", [$task->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                         <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label  class="required" for="construction_contract_id">{{ trans('cruds.task.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('construction_contract_id') ? old('construction_contract_id') : $task->construction_contract->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.construction_contract_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.task.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $task->name) }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.task.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description', $task->description) }}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
                            <label for="tags">{{ trans('cruds.task.fields.tag') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="tags[]" id="tags" multiple>
                                @foreach($tags as $id => $tag)
                                    <option value="{{ $id }}" {{ (in_array($id, old('tags', [])) || $task->tags->contains($id)) ? 'selected' : '' }}>{{ $tag }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tags'))
                                <span class="help-block" role="alert">{{ $errors->first('tags') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.tag_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">{{ trans('cruds.task.fields.location') }}</label>
                            <input class="form-control" type="text" name="location" id="location" value="{{ old('location', $task->location) }}">
                            @if($errors->has('location'))
                                <span class="help-block" role="alert">{{ $errors->first('location') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('due_date') ? 'has-error' : '' }}">
                            <label for="due_date">{{ trans('cruds.task.fields.due_date') }}</label>
                            <input class="form-control date" type="text" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date) }}">
                            @if($errors->has('due_date'))
                                <span class="help-block" role="alert">{{ $errors->first('due_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.due_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label for="status_id">{{ trans('cruds.task.fields.status') }}</label>
                            <select class="form-control select2" name="status_id" id="status_id">
                                @foreach($statuses as $id => $status)
                                    <option value="{{ $id }}" {{ ($task->status ? $task->status->id : old('status_id')) == $id ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('attachment') ? 'has-error' : '' }}">
                            <label for="attachment">{{ trans('cruds.task.fields.attachment') }}</label>
                            <div class="needsclick dropzone" id="attachment-dropzone">
                            </div>
                            @if($errors->has('attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('pdf_attachment') ? 'has-error' : '' }}">
                            <label for="pdf_attachment">{{ trans('cruds.task.fields.pdf_attachment') }}</label>
                            <div class="needsclick dropzone" id="pdf_attachment-dropzone">
                            </div>
                            @if($errors->has('pdf_attachment'))
                                <span class="help-block" role="alert">{{ $errors->first('pdf_attachment') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.pdf_attachment_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('weather') ? 'has-error' : '' }}">
                            <label for="weather">{{ trans('cruds.task.fields.weather') }}</label>
                            <input class="form-control" type="text" name="weather" id="weather" value="{{ old('weather', $task->weather) }}">
                            @if($errors->has('weather'))
                                <span class="help-block" role="alert">{{ $errors->first('weather') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.weather_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('temperature') ? 'has-error' : '' }}">
                            <label for="temperature">{{ trans('cruds.task.fields.temperature') }}</label>
                            <input class="form-control" type="number" name="temperature" id="temperature" value="{{ old('temperature', $task->temperature) }}" step="0.01">
                            @if($errors->has('temperature'))
                                <span class="help-block" role="alert">{{ $errors->first('temperature') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.temperature_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('wind') ? 'has-error' : '' }}">
                            <label for="wind">{{ trans('cruds.task.fields.wind') }}</label>
                            <input class="form-control" type="number" name="wind" id="wind" value="{{ old('wind', $task->wind) }}" step="0.01">
                            @if($errors->has('wind'))
                                <span class="help-block" role="alert">{{ $errors->first('wind') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.task.fields.wind_helper') }}</span>
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
    url: '{{ route('admin.tasks.storeMedia') }}',
    maxFilesize: 800, // MB
    addRemoveLinks: true,
    acceptedFiles: '.jpeg,.jpg',
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 800
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
@if(isset($task) && $task->attachment)
          var files =
            {!! json_encode($task->attachment) !!}
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

var APIkey = '16f8160661de97305b36536356f49640';
var zipCode = "30130";
var req = new XMLHttpRequest();
req.open("GET", "https://api.openweathermap.org/data/2.5/weather?zip=30000,th&appid=" + APIkey, false);
req.send(null);
var weatherObj = JSON.parse(req.responseText);
var Weather = weatherObj.weather[0].main;
var TempK = weatherObj.main["temp"];
var TempC = TempK - 273.15;
var Wind = weatherObj.wind["speed"];

document.getElementById('weather').value = Weather;
document.getElementById('temperature').value = TempC.toFixed(2);
document.getElementById('wind').value = Wind;
</script>
<script>
    var uploadedPdfAttachmentMap = {}
Dropzone.options.pdfAttachmentDropzone = {
    url: '{{ route('admin.tasks.storeMedia') }}',
    maxFilesize: 50, // MB
    addRemoveLinks: true,
    acceptedFiles: '.pdf',
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="pdf_attachment[]" value="' + response.name + '">')
      uploadedPdfAttachmentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedPdfAttachmentMap[file.name]
      }
      $('form').find('input[name="pdf_attachment[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($task) && $task->pdf_attachment)
          var files =
            {!! json_encode($task->pdf_attachment) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="pdf_attachment[]" value="' + file.file_name + '">')
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