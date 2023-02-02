<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="{!! asset('assets/plugins/bootstrap/css/bootstrap.css') !!}" rel="stylesheet"/>
    <link href="{!! asset('assets/plugins/bootstrap/css/bootstrap-grid.css') !!}" rel="stylesheet"/>
    <link href="{!! asset('assets/plugins/bootstrap/css/bootstrap-reboot.css') !!}" rel="stylesheet"/>
    <link href="{!! asset('assets/css/colors.css') !!}"  rel="stylesheet" id="themecolor"/>
    <link href="{!! asset('assets/css/style.css') !!}" rel="stylesheet"/>
</head>

<body class="theme-orange" style="overflow: auto;">
<div class="auth animated slideInRight">
    <div class="pvr_card">
        <div class="body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header">
                        <div class="logo m-t-15">
                            <img class="w-in-22" src="http://via.placeholder.com/80x80" alt="{!! CNF_APPNAME !!}">
                        </div>
                        <h1 class="text-white">{!! CNF_APPNAME !!} Admin</h1>
                    </div>
                </div>
                <form class="col-lg-12" id="sign_in" method="POST" action="{{ url('/password/email') }}">
            @csrf
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                    <h5 class="title">Enter Email to reset password</h5>
                    <div class="form-group-pvr form-float {{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="form-line-pvr">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" >
                            <label class="form-label">Email Id</label>
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                <div class="col-lg-12 m-t-10">
                    <button type="submit" class="btn btn-purple waves-effect">Send Password Reset Link</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="auth_bg"></div>
<script src="{!! asset('assets/plugins/jquery/jquery-3.2.1.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/plugins/bootstrap/js/bootstrap.bundle.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/plugins/jquery.backstretch/jquery.backstretch.js') !!}" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL JS -->
<script src="{!! asset('assets/js/pvr_lite_login_v1.js') !!}" type="text/javascript"></script>
</body>


</html>
