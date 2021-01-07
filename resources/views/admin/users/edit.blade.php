@extends('layouts.admin')
@section('content')

<style>
            .jay-signature-pad {
                position: relative;
                display: -ms-flexbox;
                -ms-flex-direction: column;
                width: 100%;
                height: 100%;
                max-width: 800px;
                max-height: 315px;
                border: 1px solid #e8e8e8;
                background-color: #fff;
                box-shadow: 0 3px 20px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.08) inset;
                border-radius: 15px;
                padding: 20px;
            }
            .txt-center {
                text-align: -webkit-center;
            }
</style>

<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('img_user') ? 'has-error' : '' }}">
                            <label for="img_user">{{ trans('cruds.user.fields.img_user') }}</label>
                            <div class="needsclick dropzone" id="img_user-dropzone">
                            </div>
                            @if($errors->has('img_user'))
                                <span class="help-block" role="alert">{{ $errors->first('img_user') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.img_user_helper') }}</span>
                        </div>

                        @if(auth()->user()->roles->contains(1))
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                                @if($errors->has('name'))
                                    <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                            </div>

                        @else
                            <div hidden="true" class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                                    @if($errors->has('name'))
                                        <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                                </div>
                        @endif

                        
                        <div class="form-group {{ $errors->has('workphone') ? 'has-error' : '' }}">
                            <label for="workphone">{{ trans('cruds.user.fields.workphone') }}</label>
                            <input class="form-control" type="text" name="workphone" id="workphone" value="{{ old('workphone', $user->workphone) }}">
                            @if($errors->has('workphone'))
                                <span class="help-block" role="alert">{{ $errors->first('workphone') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.workphone_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
                            <label for="organization_id">{{ trans('cruds.user.fields.organization') }}</label>
                            <select class="form-control select2" name="organization_id" id="organization_id">
                                @foreach($organizations as $id => $organization)
                                    <option value="{{ $id }}" {{ (old('organization_id') ? old('organization_id') : $user->organization->id ?? '') == $id ? 'selected' : '' }}>{{ $organization }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('organization'))
                                <span class="help-block" role="alert">{{ $errors->first('organization') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.organization_helper') }}</span>
                        </div>

                        @if(auth()->user()->roles->contains(1))
                            <div class="form-group {{ $errors->has('team') ? 'has-error' : '' }}">
                                <label class="required" for="team_id">{{ trans('cruds.user.fields.team') }}</label>
                                <select class="form-control select2" name="team_id" id="team_id" required>
                                    @foreach($teams as $id => $team)
                                        <option value="{{ $id }}" {{ ($user->team ? $user->team->id : old('team_id')) == $id ? 'selected' : '' }}>{{ $team }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('team_id'))
                                    <span class="help-block" role="alert">{{ $errors->first('team_id') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.team_helper') }}</span>
                            </div>
                        @else
                            <div hidden="true" class="form-group {{ $errors->has('team') ? 'has-error' : '' }}">
                                <label class="required" for="team_id">{{ trans('cruds.user.fields.team') }}</label>
                                <select class="form-control select2" name="team_id" id="team_id" required>
                                    @foreach($teams as $id => $team)
                                        <option value="{{ $id }}" {{ ($user->team ? $user->team->id : old('team_id')) == $id ? 'selected' : '' }}>{{ $team }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('team_id'))
                                    <span class="help-block" role="alert">{{ $errors->first('team_id') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.team_helper') }}</span>
                            </div>
                        @endif

                        <div class="form-group {{ $errors->has('jobtitle') ? 'has-error' : '' }}">
                            <label for="jobtitle_id">{{ trans('cruds.user.fields.jobtitle') }}</label>
                            <select class="form-control select2" name="jobtitle_id" id="jobtitle_id">
                                @foreach($jobtitles as $id => $jobtitle)
                                    <option value="{{ $id }}" {{ ($user->jobtitle ? $user->jobtitle->id : old('jobtitle_id')) == $id ? 'selected' : '' }}>{{ $jobtitle }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jobtitle_id'))
                                <span class="help-block" role="alert">{{ $errors->first('jobtitle_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.jobtitle_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                            <input class="form-control" type="text" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                            @if($errors->has('email'))
                                <span class="help-block" role="alert">{{ $errors->first('email') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                            <input class="form-control" type="password" name="password" id="password">
                            @if($errors->has('password'))
                                <span class="help-block" role="alert">{{ $errors->first('password') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                        </div>

                        @if(auth()->user()->roles->contains(1))
                            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                                <div style="padding-bottom: 4px">
                                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                </div>
                                <select class="form-control select2" name="roles[]" id="roles" multiple required>
                                    @foreach($roles as $id => $roles)
                                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('roles'))
                                    <span class="help-block" role="alert">{{ $errors->first('roles') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
                            </div>
                        @else
                            <div hidden="true" class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                    <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                                    <div style="padding-bottom: 4px">
                                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                    </div>
                                    <select class="form-control select2" name="roles[]" id="roles" multiple required>
                                        @foreach($roles as $id => $roles)
                                            <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('roles'))
                                        <span class="help-block" role="alert">{{ $errors->first('roles') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
                            </div>
                        @endif

                        <div class="form-group {{ $errors->has('signature') ? 'has-error' : '' }}">
                            <label for="signature">{{ trans('cruds.user.fields.signature') }}</label>
                            <div class="needsclick dropzone" id="signature-dropzone">
                            </div>
                            @if($errors->has('signature'))
                                <span class="help-block" role="alert">{{ $errors->first('signature') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.signature_helper') }}</span>
                        </div>

                         <div class="form-group {{ $errors->has('stamp_signature') ? 'has-error' : '' }}">
                            <label for="stamp_signature">{{ trans('cruds.user.fields.stamp_signature') }}</label>
                            <div class="needsclick dropzone" id="stamp_signature-dropzone">
                            </div>
                            @if($errors->has('stamp_signature'))
                                <span class="help-block" role="alert">{{ $errors->first('stamp_signature') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.stamp_signature_helper') }}</span>
                        </div>

                        @if(auth()->user()->roles->contains(1))
                            <div class="form-group {{ $errors->has('approved') ? 'has-error' : '' }}">
                                <div>
                                    <input type="hidden" name="approved" value="0">
                                    <input type="checkbox" name="approved" id="approved" value="1" {{ $user->approved || old('approved', 0) === 1 ? 'checked' : '' }}>
                                    <label for="approved" style="font-weight: 400">{{ trans('cruds.user.fields.approved') }}</label>
                                </div>
                                @if($errors->has('approved'))
                                    <span class="help-block" role="alert">{{ $errors->first('approved') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.approved_helper') }}</span>
                            </div>
                            <div class="form-group {{ $errors->has('construction_contracts') ? 'has-error' : '' }}">
                                <label for="construction_contracts">{{ trans('cruds.user.fields.construction_contract') }}</label>
                                <div style="padding-bottom: 4px">
                                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                </div>
                                <select class="form-control select2" name="construction_contracts[]" id="construction_contracts" multiple>
                                    @foreach($construction_contracts as $id => $construction_contract)
                                        <option value="{{ $id }}" {{ (in_array($id, old('construction_contracts', [])) || $user->construction_contracts->contains($id)) ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('construction_contracts'))
                                    <span class="help-block" role="alert">{{ $errors->first('construction_contracts') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.construction_contract_helper') }}</span>
                            </div>
                            
                        @else
                            <div hidden="true" class="form-group {{ $errors->has('approved') ? 'has-error' : '' }}">
                                <div>
                                    <input type="hidden" name="approved" value="0">
                                    <input type="checkbox" name="approved" id="approved" value="1" {{ $user->approved || old('approved', 0) === 1 ? 'checked' : '' }}>
                                    <label for="approved" style="font-weight: 400">{{ trans('cruds.user.fields.approved') }}</label>
                                </div>
                                @if($errors->has('approved'))
                                    <span class="help-block" role="alert">{{ $errors->first('approved') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.approved_helper') }}</span>
                            </div>
                            <div hidden="true" class="form-group {{ $errors->has('construction_contracts') ? 'has-error' : '' }}">
                                <label for="construction_contracts">{{ trans('cruds.user.fields.construction_contract') }}</label>
                                <div style="padding-bottom: 4px">
                                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                </div>
                                <select class="form-control select2" name="construction_contracts[]" id="construction_contracts" multiple>
                                    @foreach($construction_contracts as $id => $construction_contract)
                                        <option value="{{ $id }}" {{ (in_array($id, old('construction_contracts', [])) || $user->construction_contracts->contains($id)) ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('construction_contracts'))
                                    <span class="help-block" role="alert">{{ $errors->first('construction_contracts') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.construction_contract_helper') }}</span>
                            </div>
                        @endif

                        <div class="form-group">
                            <a class="btn btn-default" href="{{ session('previous-url') }}">
                                {{ trans('global.back_to_home') }}
                            </a>


                            <button class="btn btn-success" type="submit">
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
    Dropzone.options.imgUserDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 100, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 100,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="img_user"]').remove()
      $('form').append('<input type="hidden" name="img_user" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="img_user"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->img_user)
      var file = {!! json_encode($user->img_user) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $user->img_user->getUrl('thumb') }}')
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="img_user" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
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
    Dropzone.options.signatureDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="signature"]').remove()
      $('form').append('<input type="hidden" name="signature" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="signature"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->signature)
      var file = {!! json_encode($user->signature) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $user->signature->getUrl('thumb') }}')
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="signature" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
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
    Dropzone.options.stampSignatureDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 10, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="stamp_signature"]').remove()
      $('form').append('<input type="hidden" name="stamp_signature" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="stamp_signature"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->stamp_signature)
      var file = {!! json_encode($user->stamp_signature) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $user->stamp_signature->getUrl('thumb') }}')
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="stamp_signature" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
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



<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
        <script>
            var wrapper = document.getElementById("signature-pad");
            var clearButton = wrapper.querySelector("[data-action=clear]");
            var changeColorButton = wrapper.querySelector("[data-action=change-color]");
            var savePNGButton = wrapper.querySelector("[data-action=save-png]");
            var saveJPGButton = wrapper.querySelector("[data-action=save-jpg]");
            var saveSVGButton = wrapper.querySelector("[data-action=save-svg]");
            var canvas = wrapper.querySelector("canvas");
            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)'
            });
            // Adjust canvas coordinate space taking into account pixel ratio,
            // to make it look crisp on mobile devices.
            // This also causes canvas to be cleared.
            function resizeCanvas() {
                // When zoomed out to less than 100%, for some very strange reason,
                // some browsers report devicePixelRatio as less than 1
                // and only part of the canvas is cleared then.
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                // This part causes the canvas to be cleared
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                // This library does not listen for canvas changes, so after the canvas is automatically
                // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
                // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
                // that the state of this library is consistent with visual state of the canvas, you
                // have to clear it manually.
                signaturePad.clear();
            }
            // On mobile devices it might make more sense to listen to orientation change,
            // rather than window resize events.
            window.onresize = resizeCanvas;
            resizeCanvas();
            function download(dataURL, filename) {
                var blob = dataURLToBlob(dataURL);
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement("a");
                a.style = "display: none";
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            }
            // One could simply use Canvas#toBlob method instead, but it's just to show
            // that it can be done using result of SignaturePad#toDataURL.
            function dataURLToBlob(dataURL) {
                var parts = dataURL.split(';base64,');
                var contentType = parts[0].split(":")[1];
                var raw = window.atob(parts[1]);
                var rawLength = raw.length;
                var uInt8Array = new Uint8Array(rawLength);
                for (var i = 0; i < rawLength; ++i) {
                    uInt8Array[i] = raw.charCodeAt(i);
                }
                return new Blob([uInt8Array], { type: contentType });
            }
            clearButton.addEventListener("click", function (event) {
                signaturePad.clear();
            });
            changeColorButton.addEventListener("click", function (event) {
                var r = Math.round(Math.random() * 255);
                var g = Math.round(Math.random() * 255);
                var b = Math.round(Math.random() * 255);
                var color = "rgb(" + r + "," + g + "," + b +")";
                signaturePad.penColor = color;
            });
            savePNGButton.addEventListener("click", function (event) {
                if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
                } else {
                var dataURL = signaturePad.toDataURL();
                download(dataURL, "signature.png");
                }
            });
            saveJPGButton.addEventListener("click", function (event) {
                if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
                } else {
                var dataURL = signaturePad.toDataURL("image/jpeg");
                download(dataURL, "signature.jpg");
                }
            });
            saveSVGButton.addEventListener("click", function (event) {
                if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
                } else {
                var dataURL = signaturePad.toDataURL('image/svg+xml');
                download(dataURL, "signature.svg");
                }
            });
        </script>
@endsection