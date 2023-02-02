@extends('main.app')

@section('content')
<div class="main-content mt-50">
    <div class="container-fluid">
        <div class="row mt-150 mb-50">
            <div class="col-md-6 login-img">
                <img src="assets/front/img/login-img.svg">
            </div>
            <div class="col-md-6">
                <div class="login-card">
                    <div class="login-header ndiv">
                        <h5>Welcome Back,</h5>
                    </div>
                    <div class="login-body ndiv">
                        <div class="login-form">
                            <form action="http://localhost/emperica/login" method="POST" id="lin-form">
                                <input type="hidden" name="_token" value="0HzGBWThqR7l2Ar52HHVhnslecsNyXLQnaBlnj3Q">
                                <input type="hidden" name="role_id" value="2">
                                <input type="hidden" name="device" value="web">
                                <div class="form-group einput">
                                    <label>Email</label>
                                    <input type="email" id="emailval" name="email" class="form-control " value="" placeholder="Enter your email" required="" autocomplete="email" autofocus="">
                                </div>
                                <input type="hidden" id="guestlogin" name="guestlogin" value="">
                                <div class="form-group minput" style="display: none;">
                                    <label>Mobile</label>
                                    <div class="input-group">
                                        <input type="text" name="location_id" value="91" readonly="">
                                        <input type="number" id="mobileval" name="mobile" class="form-control " value="" placeholder="Enter your mobile" autocomplete="mobile" autofocus="">
                                    </div>
                                </div>
                                <div class="form-group mb-4 emp-reg-eye einput">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <input type="password" id="psw-type" class="form-control " placeholder="Enter your password" name="password" required="" autocomplete="current-password">
                                        <div class="input-group-append" id="eye1" onclick="pswvis()">
                                            <i class="fas fa-eye-slash input-group-text relative-fa text-muted" id="login-psw"></i>
                                        </div>                                      
                                    </div>
                                </div>
                                <div class="remember-section d-flex einput">
                                    <div class="forgotten">
                                        <a href="http://localhost/emperica/password/reset">Forgot password?</a>
                                    </div>
                                </div>
                                <div class="login-button">
                                    <button type="submit" class="btn lbtn">Login</button>
                                </div>
                                <div class="login-button guestlogin">
                                    <button type="button" class="btn melogin" data-cur="email">or login with mobile number</button>
                                </div>
                                <div class="login-button guestlogin">
                                    <button type="button" class="btn glogin">Guest Login</button>
                                </div>
                                <div class="social-login">
                                    <h6>Or login with social account</h6>
                                    <div class="social-links">
                                        <fb:login-button onlogin="checkLoginState();"></fb:login-button>
                                            <div id="status"></div>
                                            <div class="g-signin2" data-onsuccess="onSignIn" data-gapiscan="true" data-onload="true"><div style="height:36px;width:120px;" class="abcRioButton abcRioButtonLightBlue"><div class="abcRioButtonContentWrapper"><div class="abcRioButtonIcon" style="padding:8px"><div style="width:18px;height:18px;" class="abcRioButtonSvgImageWithFallback abcRioButtonIconImage abcRioButtonIconImage18"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg"><g><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></g></svg></div></div><span style="font-size:13px;line-height:34px;" class="abcRioButtonContents"><span id="not_signed_insse5msiu9j4p">Sign in</span><span id="connectedsse5msiu9j4p" style="display:none">Signed in</span></span></div></div></div></div>
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
                                    <input type="hidden" id="oguestlogin" name="guestlogin" value="">
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

