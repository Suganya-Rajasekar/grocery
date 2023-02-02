@extends('main.app')

@section('content')
<div class="main-content mt-50">
    <div class="container-fluid">
        <div class="row mt-150 mb-50">
            <div class="col-md-6 login-img">
                <img src="assets/front/img/login-img.svg">
            </div>
            <div class="col-lg-6">
                <div class="login-card">
                    <div class="login-header ndiv">
                        <h5>Create your Account</h5>
                        <p>Already have an account?  <a href="http://localhost/emperica/user/login">Sign in</a></p>
                    </div>  
                    <div class="login-body ndiv">
                        <div class="login-form">
                            <form method="POST" action="http://localhost/emperica/user/register" id="reg-form">
                                <input type="hidden" name="_token" value="0HzGBWThqR7l2Ar52HHVhnslecsNyXLQnaBlnj3Q">                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" placeholder="Enter your full name" class="form-control" value="">
                                </div>
                                <input type="hidden" name="role_id" value="2">
                                <input type="hidden" name="device" value="web">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" placeholder="Enter your email" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <div class="input-group ">
                                        <div class="country-code">
                                            <div class="flag-img">  <img src="https://cdn.pixabay.com/photo/2016/08/24/17/07/india-1617463__340.png" alt=""></div>
                                            <span>+91</span> <span class="rotate-icon">&gt;</span>
                                        </div>
                                        
                                        <input type="number" class="form-control" placeholder="Enter your number" id="mobileval" name="mobile" value="">
                                    </div>
                                </div>
                                <div class="form-group emp-reg-eye">
                                    <label>Password</label>
                                    <div class="input-group ">
                                        <input type="password" id="psw-type" class="form-control" placeholder="Enter your password" name="password">
                                        <div class="input-group-append" id="eye1">
                                            <span class="fa fa-eye input-group-text" id="login-psw"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group emp-reg-eye">
                                    <label>Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" id="psw-type1" class="form-control" placeholder="Retype your password" name="cpassword">
                                        <div class="input-group-append" id="eye2" onclick="pswvis1()">
                                            <span class="fa fa-eye input-group-text" id="login-psw1"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="login-button">
                                    <button type="submit" class="btn reg-btn">Register</button>
                                </div>
                                <div class="social-login">
                                    <h6>Or register with social account</h6>
                                    <div class="social-links">
                                        <a class="facebook" href="http://localhost/emperica/login/facebook"><i style="float: left;"><img src="{!! asset('assets/img/fb-icon.png') !!}"></i> Facebook</a>
                                        <div class="g-signin2" data-onsuccess="onSignIn"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="login-card odiv" style="display: none;">
                        <div class="login-header">
                            <h5>Verify Mobile Number</h5>
                            <p>Check Your SMS, we've sent you the <br> pin to <span class="mnumber">080 788 5078</span></p>
                        </div>
                    </div>
                    <div class="login-body odiv" style="display: none;">
                        <div class="otp">
                            <div class="min-h-screen flex flex-col justify-center text-center">
                                <form action="http://localhost/emperica/login" method="POST" id="votp-form">
                                    <input type="hidden" name="_token" value="0HzGBWThqR7l2Ar52HHVhnslecsNyXLQnaBlnj3Q">                                    <input type="hidden" name="role_id" value="2">
                                    <input type="hidden" name="device" value="web">
                                    <input type="hidden" name="mobile" id="omobile" value="">
                                    <div class="text-left" id="OTPInput">
                                    <input class="w-12 h-12 bg-gray-100 border-gray-100 outline-none focus:bg-gray-200 m-2 text-center rounded focus:border-blue-400 focus:shadow-outline" style="color: transparent; text-shadow: gray 0px 0px 0px;" id="otp-field0" maxlength="1"><input class="w-12 h-12 bg-gray-100 border-gray-100 outline-none focus:bg-gray-200 m-2 text-center rounded focus:border-blue-400 focus:shadow-outline" style="color: transparent; text-shadow: gray 0px 0px 0px;" id="otp-field1" maxlength="1"><input class="w-12 h-12 bg-gray-100 border-gray-100 outline-none focus:bg-gray-200 m-2 text-center rounded focus:border-blue-400 focus:shadow-outline" style="color: transparent; text-shadow: gray 0px 0px 0px;" id="otp-field2" maxlength="1"><input class="w-12 h-12 bg-gray-100 border-gray-100 outline-none focus:bg-gray-200 m-2 text-center rounded focus:border-blue-400 focus:shadow-outline" style="color: transparent; text-shadow: gray 0px 0px 0px;" id="otp-field3" maxlength="1"></div>
                                    <input id="otp" name="otp" value="" hidden="">
                                    <div class="col-md-12 text-left">
                                        <div class="verfy-btn my-4">
                                            <input type="submit" value="Verify" class="btn btn-theme">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="resendcode">
                            <p>Didn't recieve SMS <a href="javascript:void(0);" class="resend_code"><span class="theme-color">Resend code</span></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

