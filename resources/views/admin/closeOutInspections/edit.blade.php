@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.closeOutInspection.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.close-out-inspections.update", [$closeOutInspection->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <legend><a onclick="HideSection(1)" id="element1"><i class="bi bi-eye-slash-fill"></i></a> Section 1 : Requests Inspection Scope of Work </legend>
                        <div id="section1">

                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label class="required" for="subject">{{ trans('cruds.closeOutInspection.fields.subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="subject" value="{{ old('subject', $closeOutInspection->subject) }}" required>
                            @if($errors->has('subject'))
                                <span class="help-block" role="alert">{{ $errors->first('subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label for="document_number">{{ trans('cruds.closeOutInspection.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', $closeOutInspection->document_number) }}">
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.document_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('submit_date') ? 'has-error' : '' }}">
                            <label class="required" for="submit_date">{{ trans('cruds.closeOutInspection.fields.submit_date') }}</label>
                            <input class="form-control date" type="text" name="submit_date" id="submit_date" value="{{ old('submit_date', $closeOutInspection->submit_date) }}" required>
                            @if($errors->has('submit_date'))
                                <span class="help-block" role="alert">{{ $errors->first('submit_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.submit_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('boqs') ? 'has-error' : '' }}">
                            <label class="required" for="boqs">{{ trans('cruds.closeOutInspection.fields.boq') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="boqs[]" id="boqs" multiple required>
                                @foreach($boqs as $id => $boq)
                                    <option value="{{ $id }}" {{ (in_array($id, old('boqs', [])) || $closeOutInspection->boqs->contains($id)) ? 'selected' : '' }}>{{ $boq }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('boqs'))
                                <span class="help-block" role="alert">{{ $errors->first('boqs') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.boq_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('locations') ? 'has-error' : '' }}">
                            <label for="locations">{{ trans('cruds.closeOutInspection.fields.location') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="locations[]" id="locations" multiple>
                                @foreach($locations as $id => $location)
                                    <option value="{{ $id }}" {{ (in_array($id, old('locations', [])) || $closeOutInspection->locations->contains($id)) ? 'selected' : '' }}>{{ $location }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('locations'))
                                <span class="help-block" role="alert">{{ $errors->first('locations') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('sub_location') ? 'has-error' : '' }}">
                            <label for="sub_location">{{ trans('cruds.closeOutInspection.fields.sub_location') }}</label>
                            <input data-role="tagsinput" class="form-control" name="sub_location" id="sub_location" value="{{ old('sub_location', $closeOutInspection->sub_location) }}">
                            @if($errors->has('sub_location'))
                                <span class="help-block" role="alert">{{ $errors->first('sub_location') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.sub_location_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('work_types') ? 'has-error' : '' }}">
                            <label for="work_types">{{ trans('cruds.closeOutInspection.fields.work_type') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="work_types[]" id="work_types" multiple>
                                @foreach($work_types as $id => $work_type)
                                    <option value="{{ $id }}" {{ (in_array($id, old('work_types', [])) || $closeOutInspection->work_types->contains($id)) ? 'selected' : '' }}>{{ $work_type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('work_types'))
                                <span class="help-block" role="alert">{{ $errors->first('work_types') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.work_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('sub_worktype') ? 'has-error' : '' }}">
                            <label for="sub_worktype">{{ trans('cruds.closeOutInspection.fields.sub_worktype') }}</label>
                            <input data-role="tagsinput" class="form-control" type="text" name="sub_worktype" id="sub_worktype" value="{{ old('sub_worktype', $closeOutInspection->sub_worktype) }}">
                            @if($errors->has('sub_worktype'))
                                <span class="help-block" role="alert">{{ $errors->first('sub_worktype') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.sub_worktype_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('files_report_before') ? 'has-error' : '' }}">
                            <label class="required" for="files_report_before">{{ trans('cruds.closeOutInspection.fields.files_report_before') }}</label>
                            <div class="needsclick dropzone" id="files_report_before-dropzone">
                            </div>
                            @if($errors->has('files_report_before'))
                                <span class="help-block" role="alert">{{ $errors->first('files_report_before') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.files_report_before_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('respond_date') ? 'has-error' : '' }}">
                            <label for="respond_date">{{ trans('cruds.closeOutInspection.fields.respond_date') }}</label>
                            <input class="form-control date" type="text" name="respond_date" id="respond_date" value="{{ old('respond_date', $closeOutInspection->respond_date) }}">
                            @if($errors->has('respond_date'))
                                <span class="help-block" role="alert">{{ $errors->first('respond_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.respond_date_helper') }}</span>
                        </div>
                    </div>


                        <legend><a onclick="HideSection(2)" id="element2"><i class="bi bi-eye"></i></a> Section 2 : Approve Inspection Scope of Work </legend>
                        <div id="section2">

                        <div class="form-group {{ $errors->has('files_report_after') ? 'has-error' : '' }}">
                            <label for="files_report_after">{{ trans('cruds.closeOutInspection.fields.files_report_after') }}</label>
                            <div class="needsclick dropzone" id="files_report_after-dropzone">
                            </div>
                            @if($errors->has('files_report_after'))
                                <span class="help-block" role="alert">{{ $errors->first('files_report_after') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.files_report_after_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('review_status') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.closeOutInspection.fields.review_status') }}</label>
                            <select class="form-control" name="review_status" id="review_status">
                                <option value disabled {{ old('review_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\CloseOutInspection::REVIEW_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('review_status', $closeOutInspection->review_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('review_status'))
                                <span class="help-block" role="alert">{{ $errors->first('review_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.review_status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('review_date') ? 'has-error' : '' }}">
                            <label for="review_date">{{ trans('cruds.closeOutInspection.fields.review_date') }}</label>
                            <input class="form-control date" type="text" name="review_date" id="review_date" value="{{ old('review_date', $closeOutInspection->review_date) }}">
                            @if($errors->has('review_date'))
                                <span class="help-block" role="alert">{{ $errors->first('review_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.review_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('reviewer') ? 'has-error' : '' }}">
                            <label for="reviewer_id">{{ trans('cruds.closeOutInspection.fields.reviewer') }}</label>
                            <select class="form-control select2" name="reviewer_id" id="reviewer_id">
                                @foreach($reviewers as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('reviewer_id') ? old('reviewer_id') : $closeOutInspection->reviewer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('reviewer'))
                                <span class="help-block" role="alert">{{ $errors->first('reviewer') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.reviewer_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.closeOutInspection.fields.document_status') }}</label>
                            <select class="form-control" name="document_status" id="document_status">
                                @foreach(App\CloseOutInspection::DOCUMENT_STATUS_SELECT as $key => $label)
                                    @if($key != 1)
                                    <option value="{{ $key }}" {{ old('document_status', $closeOutInspection->document_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if($errors->has('document_status'))
                                <span class="help-block" role="alert">{{ $errors->first('document_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.closeOutInspection.fields.document_status_helper') }}</span>
                        </div>
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
    var uploadedFilesReportBeforeMap = {}
Dropzone.options.filesReportBeforeDropzone = {
    url: '{{ route('admin.close-out-inspections.storeMedia') }}',
    maxFilesize: 1000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1000
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="files_report_before[]" value="' + response.name + '">')
      uploadedFilesReportBeforeMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFilesReportBeforeMap[file.name]
      }
      $('form').find('input[name="files_report_before[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($closeOutInspection) && $closeOutInspection->files_report_before)
          var files =
            {!! json_encode($closeOutInspection->files_report_before) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="files_report_before[]" value="' + file.file_name + '">')
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
    var uploadedFilesReportAfterMap = {}
Dropzone.options.filesReportAfterDropzone = {
    url: '{{ route('admin.close-out-inspections.storeMedia') }}',
    maxFilesize: 1000, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1000
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="files_report_after[]" value="' + response.name + '">')
      uploadedFilesReportAfterMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFilesReportAfterMap[file.name]
      }
      $('form').find('input[name="files_report_after[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($closeOutInspection) && $closeOutInspection->files_report_after)
          var files =
            {!! json_encode($closeOutInspection->files_report_after) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="files_report_after[]" value="' + file.file_name + '">')
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
section1.style.display = "none";
const select2 = document.getElementsByClassName("select2");
    for (let i = 0; i < select2.length; i++) {
        select2[i].style.cssText = 'width:100%!important;';
    }

</script>
@endsection