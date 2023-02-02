@extends('layouts.app')

@section('content')
@if($errors->any())
    <?php //echo '<pre>';print_r($errors->all());?>
@endif
<div class="container theme-orange">
    <div class="auth animated slideInRight">
        <div class="pvr_card">
            <div class="body">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="header">
                            <div class="logo m-t-15">
                                <img class="w-in-22" src="https://via.placeholder.com/80x80" alt="{!! CNF_APPNAME !!}">
                            </div>
                            <h1 class="text-white">{!! CNF_APPNAME !!}</h1>
                        </div>
                    </div>
                    <form class="col-lg-12 has-feedback" action="{{ route('password.update') }}" id="sign_in" method="POST">
                @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                        <h5 class="title">{{ __('Reset Password') }}</h5>
                        <div class="form-group-pvr form-float">
                            <div class="form-line-pvr">
                                <input  id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email">
                                <label class="form-label">{{ __('E-Mail Address') }}</label>
                            </div>
                            @error('email')
                                <span class="invalid" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group-pvr form-float {{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="form-line-pvr">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <label class="form-label">{{ __('Password') }}</label>
                            </div>
                             @error('password')
                                <span class="invalid" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group-pvr form-float {{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="form-line-pvr">
                                <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                                <label class="form-label">{{ __('Confirm Password') }}</label>
                            </div>
                             @error('password')
                                <span class="invalid" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    <div class="col-lg-12 m-t-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="remember" value="">
                                <span class="form-check-sign"></span>
                                Remember me
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-12 m-t-10">
                        <button type="submit" class="btn btn-purple waves-effect">{{ __('Reset Password') }}</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<div class="auth_bg"></div>
<script src="{!! asset('assets/plugins/jquery/jquery-3.2.1.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/plugins/bootstrap/js/bootstrap.bundle.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/plugins/jquery.backstretch/jquery.backstretch.js') !!}" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL JS -->
<script src="{!! asset('assets/js/pvr_lite_login_v1.js') !!}" type="text/javascript"></script>