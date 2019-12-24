@extends('layouts.app')
@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('admin.home') }}">
           <b>EDMIS</b>
        </a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">
            {{ trans('global.register') }}
        </p>
        <form method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}
            <div>
               <div class="form-group {{ $errors->has('img_user') ? 'has-error' : '' }}">
                            <label for="img_user">{{ trans('cruds.user.fields.img_user') }}</label>
                            <div class="needsclick dropzone" id="img_user-dropzone">
                            </div>
                            @if($errors->has('img_user'))
                                <span class="help-block" role="alert">{{ $errors->first('img_user') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.img_user_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
                            <label class="required" for="dob">{{ trans('cruds.user.fields.dob') }}</label>
                            <input class="form-control date" type="text" name="dob" id="dob" value="{{ old('dob') }}" required>
                            @if($errors->has('dob'))
                                <span class="help-block" role="alert">{{ $errors->first('dob') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.dob_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.user.fields.gender') }}</label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\User::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('gender', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gender'))
                                <span class="help-block" role="alert">{{ $errors->first('gender') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.gender_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('workphone') ? 'has-error' : '' }}">
                            <label for="workphone">{{ trans('cruds.user.fields.workphone') }}</label>
                            <input class="form-control" type="text" name="workphone" id="workphone" value="{{ old('workphone', '') }}">
                            @if($errors->has('workphone'))
                                <span class="help-block" role="alert">{{ $errors->first('workphone') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.workphone_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('team') ? 'has-error' : '' }}">
                            <label class="required" for="team_id">{{ trans('cruds.user.fields.team') }}</label>
                            <select class="form-control select2" name="team_id" id="team_id" required>
                                @foreach($teams as $id => $team)
                                    <option value="{{ $id }}" {{ old('team_id') == $id ? 'selected' : '' }}>{{ $team->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('team_id'))
                                <span class="help-block" role="alert">{{ $errors->first('team_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.team_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('jobtitle') ? 'has-error' : '' }}">
                            <label for="jobtitle_id">{{ trans('cruds.user.fields.jobtitle') }}</label>
                            <select class="form-control select2" name="jobtitle_id" id="jobtitle_id">
                                @foreach($jobtitles as $id => $jobtitle)
                                    <option value="{{ $id }}" {{ old('jobtitle_id') == $id ? 'selected' : '' }}>{{ $jobtitle->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jobtitle_id'))
                                <span class="help-block" role="alert">{{ $errors->first('jobtitle_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.jobtitle_helper') }}</span>
                        </div>
                       
                     
                        <div class="form-group {{ $errors->has('signature') ? 'has-error' : '' }}">
                            <label for="signature">{{ trans('cruds.user.fields.signature') }}</label>
                            <div class="needsclick dropzone" id="signature-dropzone">
                            </div>
                            @if($errors->has('signature'))
                                <span class="help-block" role="alert">{{ $errors->first('signature') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.signature_helper') }}</span>
                        </div>
                
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                            <input class="form-control" type="text" name="email" id="email" value="{{ old('email') }}" required placeholder="{{ trans('global.login_email') }}">
                            @if($errors->has('email'))
                                <span class="help-block" role="alert">{{ $errors->first('email') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                        </div>


                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="required" for="password">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="{{ trans('global.login_password') }}">
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
                
                <div class="form-group">
                <label class="required" for="password_confirmation">Password Confirmation</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="{{ trans('global.login_password_confirmation') }}">
                </div>
                <div class="row">
                    <div class="col-xs-8">

                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            {{ trans('global.register') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
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
@endsection