@extends('layouts.app')
@section('content')
<div class="login-box">



<div class="login-box-body" style="-webkit-box-shadow: 0px 0px 32px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 0px 32px 0px rgba(0,0,0,0.75);
box-shadow: 0px 0px 32px 0px rgba(0,0,0,0.75);">

        <div class="login-logo">
        
        <legend>
        <p>
        <img src="{{ url('png-asset/EDMIS_Logo.png') }}" border="0" style="width:100%; hight:100%;" />
        </p>
        <p>
            <b>
                Login
            </b>
        </p>
        </legend>
</div>
                    

        @if(session('message'))
            <p class="alert alert-info">
                {{ session('message') }}
            </p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input id="email" type="email" name="email" class="form-control" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">

                @if($errors->has('email'))
                    <p class="help-block">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label class="required" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" required placeholder="{{ trans('global.login_password') }}">

                @if($errors->has('password'))
                    <p class="help-block">
                        {{ $errors->first('password') }}
                    </p>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label><input type="checkbox" name="remember"> {{ trans('global.remember_me') }}</label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-success btn-block btn-flat">
                        {{ trans('global.login') }}
                    </button>
                </div>
            </div>
        </form>

        @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ trans('global.forgot_password') }}
            </a><br>
        @endif

        <a href="{{ route('register') }}">{{ trans('global.register') }}</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
@endsection