@extends('main.app')

@section('content')
<style type="text/css">
.login-img {
    padding: 0px;
}
.resendcode p {
    font-size: 20px;
}




.navbar-nav li a.signuptest {
    background: none;
    color: black;
    border-radius: 3px;
}


/*#partitioned {
  padding-left: 15px;
  letter-spacing: 42px;
  border: 0;
  background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
  background-position: bottom;
  background-size: 50px 1px;
  background-repeat: repeat-x;
  background-position-x: 35px;
  width: 220px;
  min-width: 220px;
}*/
#partitioned {
    letter-spacing: 61px;
    border: 0;
    background-image: linear-gradient(to left, #e3e3e3 70%, rgba(255, 255, 255, 0) 0%);
    background-position: bottom;
        background-position-x: center;
    background-size: 68px 49px;
    background-repeat: repeat-x;
    background-position-x: 52px;
    width: 270px;
    padding: 9px 23px;
}

#divInner{
  left: 0;
  position: sticky;
}

#divOuter{
  width: 256px; 
  overflow: hidden;
}

/*otp*/
.border-gray-100 {
    --border-opacity: 1;
    border-color: #f7fafc;
    border-color: rgba(247,250,252,var(--border-opacity));
}
.bg-gray-100 {
    --bg-opacity: 1;
    background-color: #f7fafc;
    background-color: #8080803d;
}
#OTPInput input{
    border:0;
}
.rounded {
    border-radius: .25rem !important;
}
.w-12 {
    width: 3rem;
}
.h-12 {
    height: 3rem;
}
.focus\:shadow-outline:focus {
    box-shadow: 0 0 0 3px rgba(66,153,225,.5);
}
/*.focus\:border-blue-400:focus {
    --border-opacity: 1;
    border-color: #63b3ed;
    border-color: rgba(99,179,237,var(--border-opacity));
}
.focus\:bg-gray-200:focus {
    --bg-opacity: 1;
    background-color: #edf2f7;
    background-color: rgba(237,242,247,var(--bg-opacity));
}*/
button:focus, input:focus, input:focus, textarea, textarea:focus {
    outline: 0;
}
</style>
<div class="main-content mt-50">
    <div class="container-fluid">
        <div class="row mt-150 mb-50">
            <div class="col-md-6 login-img">
                @if(request()->segment(1)=='login')
                <div class="position-relative">
                    <img src="{{ asset('assets/front/img/login-img.png') }}">
                    {{-- <div class="inner-login">
                        <img src="{{ asset('assets/front/img/login-img-inner.png') }}">
                    </div> --}}
                </div>
                @elseif(request()->segment(1)=='chef')
                <img src="{{ asset('assets/front/img/chef-login.jpg') }}">
                @endif
            </div>
            <div class="col-md-6 login-img otp-img" style="display: none;">
                <img src="{{ asset('assets/front/img/OTP.svg') }}"> 
            </div>
            <div class="col-md-6">
                <div class="login-card">
                    @if(session('error_message') != '')
                        <div class="alert alert-danger">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i> {!! session('error_message') !!}
                        </div>
                    @elseif(session('flag')!= '' AND session('flag') == '1')
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i> Your email address has been verified successfully! and Your request is pending for approval.
                        </div>
                    @elseif(session('flag')!= '' AND session('flag') == '2')
                        <div class="alert alert-danger">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i> Your request is pending for approval.
                        </div>
                    @elseif(session('flag')!= '' AND session('flag') == '3')
                        <div class="alert alert-danger">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i> <span>Your email is not verified yet.</span>
                        </div>
                    @endif
                    <div class="login-header ndiv">
                        <h5 class="font-opensans">Sign-In</h5>
                        <p class="font-montserrat">Create your account  <a href="@if(request()->segment(1)=='login') {{ url('/register') }} @else {{ url('chef/register') }} @endif">Sign up</a></p>
                    </div>  
                    <div class="login-body ndiv">
                        <div class="login-form">
                            <form action="{{ route('login') }}" method="POST" id="lin-form">
                                @csrf
                                <input type="hidden" name="role_id" value="2">
                                <input type="hidden" name="device" value="web">
                                <div class="form-group einput">
                                    <label class="font-montserrat">{{ __('Email') }}</label>    
                                    <input type="email" id="emailval" name="email" class="font-montserrat form-control @error('email') is-invalid @enderror"  value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <input type="hidden" id="guestlogin" name="guestlogin" value="">
                                <div class="form-group minput" style="display: none;">
                                    <label class="font-montserrat">{{ __('Mobile') }}</label>
                                    <div class="input-group mobile_login">

                                        <div class="country-code">
                                            <div class="flag-img">  <img src="https://cdn.pixabay.com/photo/2016/08/24/17/07/india-1617463__340.png" alt=""></div>
                                            <span class="font-montserrat">+91</span> <!-- <span class="rotate-icon">></span> -->
                                        </div>
                                        <input type="hidden" class="font-montserrat" name="location_id" value="{!! CNF_LOCATION_ID !!}" readonly>
                                        <input type="tel" id="mobileval" name="mobile" class="font-montserrat form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" maxlength="10" placeholder="Enter your mobile" autocomplete="mobile" autofocus>
                                        @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="referal_div">
                                        <label class="font-montserrat mt-4">Referal Code</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Enter referal code" name="referal_code" id="referral">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4 emp-reg-eye einput">
                                    <label class="font-montserrat">{{ __('Password') }}</label>
                                    <div class=" login-psw-asw">
                                        <input type="password" id="psw-type" class="font-montserrat form-control @error('password') is-invalid @enderror" placeholder="Enter your password" name="password" required autocomplete="current-password">
                                        <div class="login-eye" id="eye1">
                                            <i class="fas fa-eye input-group-text relative-fa text-muted" id="login-psw"></i>
                                        </div>                                      
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="remember-section d-flex einput">
                                    <div class="forgotten">
                                        {{-- route('password.request') --}}
                                        @if(Route::has('password.request'))
                                        <a href="javascript:;" class="font-montserrat" id="forgot_pass">{{ __('Forgot password?') }}</a>
                                        @endif
                                    </div>
                                </div>
                                <div id="legalTextRow" class="font-montserrat form-group">
                                    By continuing, you agree to Knosh <a href="{{ url('/terms-and-conditions') }}">Conditions of Use</a> and <a href="{{ url('/privacy-policy') }}">Privacy Notice</a>.
                                    </div>
                                <div class="login-button">
                                    <button type="submit" class="font-montserrat btn lbtn">{{ __('Login') }}</button>
                                </div>
                                <div class="login-button guestlogin">
                                    <button type="button" class="font-montserrat btn melogin" data-cur="email">{{ __('or login with mobile number') }}</button>
                                </div>
                                @if(request()->segment(1)=='login')
                                <div class="login-button guestlogin">
                                    <button type="button" class="font-montserrat btn glogin">{{ __('Guest login') }}</button>
                                </div>
                                <div class="social-login">
                                    <h6 class="font-montserrat">{{ __('Or Login with Social account') }}</h6>
                                    <div class="social-links">
                                        @if(env('FACEBOOK_CLIENT_ID') != null)
                                            <fb:login-button onlogin="checkLoginState();"></fb:login-button>
                                            <div id="status"></div>
                                        @endif

                                        @if(env('GOOGLE_CLIENT_ID') != null)
                                            <div class="g-signin2" data-onsuccess="onSignIn"></div>
                                        @endif
                                    </div>
                                    <div class="social-links-asw">
                                        <div class="d-flex">
                                            <div class="fb font-montserrat"><a href="{{ url('/auth/facebook') }}" target="_blank" title="facebook"><i class="fa fa-facebook"></i> Facebook</a></div>
                                            <div class="gl font-montserrat"><a href="{{ url('/auth/google') }}" target="_blank" title="google"><i class="fa fa-google"></i> Google</a></div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="login-card odiv" style="display: none;">
                        <div class="login-header">
                            <h5>Verify details</h5>
                            <p>Check Your SMS, we've sent you the <br> pin to <span class="mnumber">080 788 5078</span></p>
                        </div>
                    </div>
                    <div class="login-body odiv" style="display: none;">
                        <div class="otp">
                            <div class="min-h-screen flex flex-col justify-center text-center">
                                <form action="{{ route('login') }}" method="POST" id="votp-form">
                                    @csrf
                                    <input type="hidden" name="role_id" value="2">
                                    <input type="hidden" name="device" value="web">
                                    <input type="hidden" name="mobile" id="omobile" value="">
                                    <input type="hidden" id="oguestlogin" name="guestlogin" value="">
                                    <div class="text-left" id="OTPInput">
                                    </div>
                                    <input hidden id="otp" name="otp" value="">
                                    <div class="col-md-12 text-left">
                                        <div class="verfy-btn my-4">
                                            <input type="submit" value="Verify" class="btn btn-theme">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="resendcode">
                            <p>OTP not received ? <a href="javascript:void(0);" class="resend_code"><span class="theme-color">Resend code</span></a></p>
                        </div>
                    </div>

                    <div class="login-header forgetdiv" style="display: none;">
                        <h5 class="font-opensans">{{ __('Forgot your password') }}</h5>
                        <p class="font-montserrat">Please enter your email address below to receive<br> your password reset instruction</p>
                    </div>  
                    <div class="login-body forgetdiv" style="display: none;">
                        <div class="login-form">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif
                            <form method="POST" action="" id="fpass-form">
                                @csrf
                                <div class="form-field mb-30">
                                    <label for="email" class="font-montserrat">Email</label>
                                    <input type="hidden" name="device" value="web">
                                    <input id="forgetemail" type="email" placeholder="Enter your email" class="form-control font-montserrat @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus/>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-field forgot-psw">
                                    <button type="submit" class="btn act-btn fpass-btn" value="">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="login-card rpassdiv" style="display: none;">
                    <div class="login-header">                        
                        <span class="backtof fas fa-arrow-left" style="cursor: pointer;"></span>
                        <h5>{{ __('Reset your password') }}</h5>
                        <p>Check your Inbox<br> We have sent you a verification code to your "<span class="remailtext"></span>".</p>
                    </div>  
                    <div class="login-body">
                        <div class="verify-email-address">
                            <div class="">
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <div class="card  login-form">
                                            <div class="card-body">
                                                <div class="sign-in-section">
                                                    @if (session('status'))
                                                    <div class="alert alert-success" role="alert">
                                                        {{ session('status') }}
                                                    </div>
                                                    @endif
                                                    <form method="POST" action="" id="reset-form">
                                                        @csrf
                                                        <div class="form-field mb-30">
                                                            <label>{{ __('Code') }}</label>
                                                            <input type="hidden" name="device" value="web">
                                                            <input type="hidden" id="remail" name="email">
                                                            <div class="text-left" id="OTPInputreset"></div>
                                                            <input hidden id="otpreset" name="code" value="">
                                                        </div>
                                                    
                                                        <div class="form-group emp-reg-eye">
                                                            <label>{{ __('Password') }}</label>
                                                            <div class="input-group login-psw-asw">
                                                                <input type="password" id="psw-typereset" class="form-control" placeholder="Enter your password" name="password" required>
                                                                <div class="login-eye" id="eyereset1">
                                                                    <i class="fas fa-eye input-group-text relative-fa text-muted" id="login-pswreset"></i>
                                                                </div> 
                                                               
                                                            </div>
                                                        </div>
                                                        <div class="form-group emp-reg-eye">
                                                            <label>{{ __('Confirm Password') }}</label>
                                                            <div class="input-group login-confpsw-asw">
                                                                <input type="password" id="psw-typereset1" class="form-control" placeholder="Retype your password" name="cpassword" required>
                                                                <div class="login-conf-eye" id="eyereset2">
                                                                    <i class="fas fa-eye input-group-text relative-fa text-muted" id="login-pswreset1"></i>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                        <div class="form-field forgot-psw">
                                                            <button type="submit" class="btn act-btn" value="">Reset Password</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="resendcode">
                            <p>Didn't recieve Email <a href="javascript:void(0);" class="resend_code"><span class="theme-color">Resend code</span></a></p>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
<script type="text/javascript">const surl = "{!! route('login') !!}";</script>
<script src="{{ asset('assets/js/login.js') }}"></script>  
<script type="text/javascript">
    $(document).on('submit','#lin-form',function(e) {
        e.preventDefault();
        calllogin();
    });

    $(document).on('click','.resend_code',function(){
        calllogin(true);
    });

    function calllogin(resend=false){

        var url = baseurl+"/login";
        $(".reg-btn").prop('disabled',true);
        var mlogin = false;
        if($(".einput").is(":visible") && !resend){
            var datas = $("#lin-form").find(":input[name!=mobile]").serialize();
        } else {
            var mlogin = true;
            var datas = $("#lin-form").find(":input[name!=email]").serialize();
        }
        $.ajax({
            url : url,
            data : datas,
            dataType : 'json',
            type : 'post',
            success : function(res){
                if(mlogin){
                    var mnumber = $("#mobileval").val();
                    $(".reg-btn").prop('disabled',false);
                    $(".error-message-area").hide();
                    $(".ndiv").hide();
                    $(".mnumber").text(mnumber);
                    $("#omobile").val(mnumber);
                    $("#oguestlogin").val($("#guestlogin").val());
                    $(".odiv").show();
                    $(".login-img").hide();
                    $(".otp-img").show();
                } else {
                    window.location.href = "{!! route('login') !!}";
                }
            },
            error : function(err){
                $(".reg-btn").prop('disabled',false);
                var msg = err.responseJSON.message;
                $(".error-message-area").find('.error-msg').text(msg);
                //$(".error-message-area").show();
                 toast(msg, 'Error!', 'error'); 
                if(resend){
                    $(".ndiv").show();
                    $(".login-img").show();
                    $(".odiv").hide();
                    $(".otp-img").hide();

                }
            }
        });
    }

    $(document).on('click','.melogin, .glogin',function(){
        $('.error').html('');
        if($(this).hasClass('melogin')){
            $("#guestlogin").val('');
            var cur = $(this).attr('data-cur');
            if(cur == 'email'){
                var changecur = 'mobile';
                var type = 'mlogin';
            } else {
                var changecur = 'email';
                var type = 'elogin';
            }
            $(".melogin").attr('data-cur',changecur);
            $('.glogin').show();
        } else {

            var changecur = '';
            var type = 'glogin';
            $("#guestlogin").val('yes');
            $('.glogin').hide();

        }
        
        if(type == 'mlogin' || type == 'glogin'){
            $(".remember-section").removeClass('d-flex');
            $(".einput").hide();
            $(".minput").show();
            $(".einput").find('input').prop('required',false).val('');
            $(".minput").find('input').prop('required',true);
            $('.referal_div').show();
            $('#referral').prop('required',false);

            if(type == 'mlogin'){
                $(".melogin").text('or login with email');
                $('.referal_div').hide();
            }
            $(".lbtn").text('Send OTP');
        } else {
            $(".remember-section").addClass('d-flex');
            $(".minput").hide();
            $(".einput").show();
            $("#mobileval").val('');
            $(".einput").find('input').prop('required',true);
            $(".minput").find('input').prop('required',false);
            $(".melogin").text('or login with mobile number');
            $(".lbtn").text('Login');

        }
    });


    $(document).on('submit','#votp-form',function(e){
        e.preventDefault();
        const inputs = document.querySelectorAll('#OTPInput > *[id]');
        let compiledOtp = '';
        for (let i = 0; i < inputs.length; i++) {
            compiledOtp += inputs[i].value;
        }
        $('#otp').val(compiledOtp);
        var url = baseurl+"/verifyotp";
        $.ajax({
            url : url,
            data : $("#votp-form").serialize(),
            dataType : 'json',
            type : 'post',
            success : function(res){
                window.location.href = "{!! route('login') !!}";
            },
            error : function(err){
                $(".reg-btn").prop('disabled',false);
                var msg = err.responseJSON.message;
                $(".error-message-area").find('.error-msg').text(msg);
                //$(".error-message-area").show();
                toast(msg, 'Error!', 'error'); 
            }
        });
    });
    $(document).on('click','#forgot_pass',function(){
            $(".ndiv").hide();
            $(".forgetdiv").show();     
    });
    $(document).on('submit','#fpass-form',function(e) {
        e.preventDefault();
        callfpass();
    });

    $(document).on('click','.resend_code_reset',function(){
        callfpass(true);
    });

     function callfpass(resend=false){
        var url = baseurl+"/api/forgetpasswordrequest";
        $(".act-btn").prop('disabled',true);
        $.ajax({
            url : url,
            data : $("#fpass-form").serialize(),
            dataType : 'json',
            type : 'post',
            success : function(res){
               $(".act-btn").prop('disabled',false);
               var emailVal = $("#forgetemail").val();
               $(".remailtext").text(emailVal);
               $("#remail").val(emailVal);
               var msg = JSON.parse(JSON.stringify(res)); 
               // $(".error-message-area").css("display","block");
               // $(".error-content").css("background","#9cda9c");
               // $(".error-msg").html("<b style='color:black'>"+msg.message+"</b>");
               toast(msg.message, 'Success!', 'success');
               $(".ndiv").hide();
               $(".forgetdiv").hide();
               $(".rpassdiv").show();
            },
            error : function(err){
                $(".act-btn").prop('disabled',false);
                var msg = err.responseJSON.message;
                $(".error-message-area").find('.error-msg').text(msg);
                //$(".error-message-area").show();
                toast(msg, 'Error!', 'error');
            }
        });
    }
$(document).on('submit','#reset-form',function(e) {
        e.preventDefault();
        const inputs = document.querySelectorAll('#OTPInputreset > *[id]');
        let compiledOtp = '';
        for (let i = 0; i < inputs.length; i++) {
            compiledOtp += inputs[i].value;
        }
        $('#otpreset').val(compiledOtp);
        var url = baseurl+"/api/resetpassword";
        $(".act-btn").prop('disabled',true);
        $.ajax({
            url : url,
            data : $("#reset-form").serialize(),
            dataType : 'json',
            type : 'post',
            success : function(res){
                window.location.href = baseurl+"/"+res.rurl;
            },
            error : function(err){
                $(".act-btn").prop('disabled',false);
                var msg = err.responseJSON.message;
                $(".error-message-area").find('.error-msg').text(msg);
                $(".error-message-area").show();
            }
        });
    });

 $(document).on('click','.backtof',function(){
        $(".rpassdiv").hide();
        $(".forgetdiv").show();
    });
</script>
<script>
    const $otp_length = 4;

    const element = document.getElementById('OTPInput');
    for (let i = 0; i < $otp_length; i++) {
        let inputField = document.createElement('input'); // Creates a new input element
        inputField.className = "w-12 h-12 bg-gray-100 border-gray-100 outline-none focus:bg-gray-200 m-2 text-center rounded focus:border-blue-400 focus:shadow-outline";
        inputField.style.cssText = "color: transparent; text-shadow: 0 0 0 gray;"; 
        inputField.id = 'otp-field' + i; 
        inputField.maxLength = 1; 
        element.appendChild(inputField); 
    }

    const inputs = document.querySelectorAll('#OTPInput > *[id]');
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('keydown', function (event) {
            if (event.key === "Backspace") {
                inputs[i].value = '';
                if (i !== 0) {
                    inputs[i - 1].focus();
                }
            } else if (event.key === "ArrowLeft" && i !== 0) {
                inputs[i - 1].focus();
            } else if (event.key === "ArrowRight" && i !== inputs.length - 1) {
                inputs[i + 1].focus();
            }
        });
        inputs[i].addEventListener('input', function () {
            inputs[i].value = inputs[i].value.toUpperCase();
            if (i === inputs.length - 1 && inputs[i].value !== '') {
                return true;
            } else if (inputs[i].value !== '') {
                inputs[i + 1].focus();
            }
        });

    }

  // reset password otp
     const elementreset = document.getElementById('OTPInputreset');
    for (let i = 0; i < $otp_length; i++) {
        let inputField = document.createElement('input'); // Creates a new input element
        inputField.className = "w-12 h-12 bg-gray-100 border-gray-100 outline-none focus:bg-gray-200 m-2 text-center rounded focus:border-blue-400 focus:shadow-outline";
        inputField.style.cssText = "color: transparent; text-shadow: 0 0 0 gray;"; 
        inputField.id = 'otp-field' + i; 
        inputField.maxLength = 1; 
        elementreset.appendChild(inputField); 
    }

    const inputsreset = document.querySelectorAll('#OTPInputreset > *[id]');
    for (let i = 0; i < inputsreset.length; i++) {
        inputsreset[i].addEventListener('keydown', function (event) {
            if (event.key === "Backspace") {
                inputsreset[i].value = '';
                if (i !== 0) {
                    inputsreset[i - 1].focus();
                }
            } else if (event.key === "ArrowLeft" && i !== 0) {
                inputsreset[i - 1].focus();
            } else if (event.key === "ArrowRight" && i !== inputsreset.length - 1) {
                inputsreset[i + 1].focus();
            }
        });
        inputsreset[i].addEventListener('input', function () {
            inputsreset[i].value = inputsreset[i].value.toUpperCase();
            if (i === inputsreset.length - 1 && inputsreset[i].value !== '') {
                return true;
            } else if (inputsreset[i].value !== '') {
                inputsreset[i + 1].focus();
            }
        });

    }

    $(document).ready(function () {
      $("#mobileval").keypress(function (e) {
         var length = $(this).val().length;
       if(length > 9) {
            return false;
       } else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
       } else if((length == 0) && (e.which == 48)) {
            return false;
       }
      });

      $('#lin-form').validate({
        onkeyup: false,
        onclick: false,
        onfocusout: false,
        ignore: ":hidden",
        rules: {
        email: {
            required: true,
            email: true
        },
        password: {
            required: true,
            minlength: 6,
        },
        mobile: {
            required: true,
            maxlength: 10,

        },
    }
    });
    });
</script>
@endsection