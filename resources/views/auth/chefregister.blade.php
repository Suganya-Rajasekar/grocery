@extends('main.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="main-content mt-50">
    <div class="container-fluid">
        <div class="row mt-150 mb-50">
            <div class="col-xl-5 col-lg-4 login-img">
                {{-- <img src="{{ asset('assets/front/img/chef_login.png') }}"> --}}
                <img src="{{ asset('assets/front/img/chef-signup.jpg') }}">
            </div>
            <div class="col-xl-7 col-lg-8 login-img otp-img" style="display: none;">
                <img src="{{ asset('assets/front/img/OTP.svg') }}"> 
            </div>
            <div class="col-xl-7 col-lg-8">
                <div class="login-card">
                    <div class="login-header ndiv">
                        <h5 class="font-opensans">{{ __('Create your Account') }}</h5>
                        <p class="font-montserrat">Already have an account?  <a href="@if(\Request::segment(2) == 'register'&& \Request::segment(1)=='chef'){{ url('chef/login') }}@endif">Sign in</a></p>
                    </div>  
                    <div class="login-body ndiv">
                        <div class="cart_overlay"></div>
                        <div class="login-form">
                            @if(Session::has('errors'))
                            <div class="row">
                                <div class="col-12">
                                    <p class="alert alert-danger">{{ Session::get('errors') }}</p>
                                </div>
                            </div>
                            @endif
                            <form method="POST" action="" id="reg-form" enctype="Multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="font-montserrat">{{ __('Full Name') }} *</label>
                                            <input type="text" name="name" placeholder="Enter your full name" class="form-control font-montserrat fname" value="@if(old('firstname')){{old('firstname')}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="font-montserrat">{{ __('Email') }} *</label>
                                            <input type="email" name="email" id="email" placeholder="Enter your email" class="form-control font-montserrat" value="@if(old('email')){{old('email')}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-montserrat">{{ __('Business Profile Name') }} *</label>
                                    <input name="profile_name" id="profile_name" class="form-control font-montserrat" placeholder="Enter your business profile name" value="@if(old('profile_name')){{old('profile_name')}}@endif">
                                       
                                </div>
                                <input type="hidden" name="role_id" value="3">
                                <input type="hidden" name="role" value="3">
                                <input type="hidden" name="device" value="web">
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="font-montserrat">{{ __('Mobile Number') }} *</label>
                                            <div class="input-group phone-no">
                                                <div class="country-code">
                                                    <div class="flag-img">  <img src="https://cdn.pixabay.com/photo/2016/08/24/17/07/india-1617463__340.png" alt=""></div>
                                                    <span class="font-montserrat">+91</span> <!-- <span class="rotate-icon">></span> -->
                                                </div>
                                                <input type="hidden" class="font-montserrat" name="country" value="{!! CNF_LOCATION_ID !!}" readonly> 
                                                <input type="tel" class="form-control font-montserrat" placeholder="Enter your number" id="mobileval" name="mobile" value="@if(old('mobile')){{old('mobile')}}@endif" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group location-select">
                                            <label class="font-montserrat">Location *</label>
                                            <select name="area_code" id="area_code" class="form-control">
                                                <option value="" selected disabled>--select location--</option>
                                                @if($cuisine_location_list->locations)
                                                    @foreach($cuisine_location_list->locations as $loc_k => $loc_val)
                                                        <option value="{{$loc_val->id}}">{{$loc_val->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group emp-reg-eye">
                                            <label class="font-montserrat">{{ __('Password') }} *</label>
                                            <div class="input-group login-psw-asw">
                                                <input type="password" id="psw-type" class="form-control font-montserrat" placeholder="Enter your password" name="password">
                                                <div class="input-group-append login-eye" id="eye1" >
                                                    <i class="fas fa-eye input-group-text" id="login-psw"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group emp-reg-eye">
                                            <label class="font-montserrat">{{ __('Confirm Password') }} *</label>
                                            <div class="input-group login-confpsw-asw">
                                                <input type="password" id="psw-type1" class="font-montserrat form-control" placeholder="Retype your password" name="cpassword">
                                                <div class="input-group-append login-conf-eye" id="eye2"  >
                                                    <i class="fas fa-eye input-group-text" id="login-psw1"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group cuisine-select-asw">
                                    <label class="font-montserrat">Cuisine *</label>
                                    <?php //echo "<pre>";print_r($cuisine_location_list->cuisines);exit;?>
                                    <select name="cuisine_type[]" id="cuisine-type" class="form-control font-montserrat " multiple="">
                                        <option value="">--Select cuisine--</option>
                                        @if($cuisine_location_list->cuisines)
                                            @foreach($cuisine_location_list->cuisines as $cui_k => $cui_val)
                                                <option class="font-montserrat" value="{{$cui_val->id}}">{{$cui_val->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="row file-upload">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="font-montserrat">Aadhar Card *</label>
                                            <input type="file"  class="form-control font-montserrat elipsis-text" name="aadar_image" id="aadar_image" accept="image/jpeg,image/png,application/pdf">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="font-montserrat">FSSAI</label>
                                            <input type="file"  class="form-control font-montserrat elipsis-text" name="fssai_certificate" id="fssai_certificate" accept="image/jpeg,image/png,application/pdf">
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-4">
                                    <div class="form-group time-select">
                                        <label class="font-montserrat">How long have you been selling your dishes professionally?</label>
                                        <select name="time_to_sell" id="time-to-sell" class="form-control">
                                            <option disabled value>Select time</option>
                                            <option value="0_2_Years">0-2 Years</option>
                                            <option value="2_5_Years">2-5 Years</option>
                                            <option value="More_than_5_Years">More than 5 Years</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group social-link pt-4">
                                    <label class="font-montserrat">Please share your social media links</label>
                                    <div class="form-group d-flex align-items-center">
                                        <label class="mb-0 font-montserrat">Facebook</label>
                                        <input type="text" name="fa_link" placeholder="Enter your Facebook link" class="ml-3 form-control font-montserrat" value="">
                                    </div>
                                    <div class="form-group d-flex align-items-center">
                                        <label class="mb-0 font-montserrat">Instagram</label>
                                        <input type="text" name="in_link" placeholder="Enter your Instagram link" class="ml-3 form-control font-montserrat" value="">
                                    </div>
                                    <div class="form-group d-flex align-items-center">
                                        <label class="mb-0 font-montserrat">Youtube</label>
                                        <input type="text" name="yo_link" placeholder="Enter your Youtube link" class="ml-3 form-control font-montserrat" value="">
                                    </div>
                                </div>
                                <div class="login-button mt-4">
                                    <button type="submit" class="font-montserrat btn reg-btn" onclick="gtag_report_conversion()">{{ __('Register') }}</button>
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
                                <form action="{{ route('login') }}" method="POST" id="votp-form">
                                    @csrf
                                    <input type="hidden" name="role_id" value="2">
                                    <input type="hidden" name="device" value="web">
                                    <input type="hidden" name="mobile" id="omobile" value="">
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
                            <p>Didn't recieve SMS <a href="javascript:void(0);" class="resend_code"><span class="theme-color">Resend code</span></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
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
    button:focus, input:focus, input:focus, textarea, textarea:focus {
        outline: 0;
    }
</style>
@endsection
@section('script')
<script type="text/javascript">const surl = "{!! route('login') !!}";</script>
<script src="{{ asset('assets/js/login.js') }}"></script>
<script type="text/javascript">
    $("#cuisine-type").select2({width: '100%'});
    $(document).on('submit','#votp-form',function(e){
        e.preventDefault();
        const inputs = document.querySelectorAll('#OTPInput > *[id]');
        let compiledOtp = '';
        for (let i = 0; i < inputs.length; i++) {
            compiledOtp += inputs[i].value;
        }
        $('#otp').val(compiledOtp);
        var url = baseurl+"/api/verifyotp";
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
                $(".error-message-area").show();
            }
        });
    });

    const $otp_length   = 4;
    const element       = document.getElementById('OTPInput');
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

    //send chef register data
    $(document).on('submit','#reg-form',function(e){
        e.preventDefault();
        $('.reg-btn').hide();
        $('.cart_overlay').show();
        var url = base_url+"send_chefregister";
        $.ajax({
            type : 'POST',
            url : url,
            data : new FormData(this),
            processData: false,
            contentType: false,
            success : function(res){
                var msg = JSON.parse(JSON.stringify(res));
                //$(".error-content").css("background","#ED4956");
                $(".error-message-area").find('.error-msg').text(msg.message);
                //$(".error-message-area").show();
                toast(msg.message, 'Success!', 'success');
                setTimeout(function() {
                    window.location.replace(base_url+'become-a-chef');
                }, 2000);
            },
            error : function(err){
                $('.reg-btn').show();
                var msg = err.responseJSON.message; 
                $(".error-content").css("background","#ED4956");
                $(".error-message-area").find('.error-msg').text(msg);
                $(".error-message-area").show();
            },
            complete : function(res){
                $('.cart_overlay').hide();
            },
        });
    });

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

      jQuery.validator.addMethod("valid_name", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]*$/);
    }, 'Please enter a valid name.');

      $('#reg-form').validate({
        // onkeyup: false,
        onclick: false,
        // onfocusout: false,
        rules: {
            name: {
                required: true,
                valid_name: true,
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url     : base_url + "checkEmail",
                    global  : false,
                    type    : "post",
                    data    : {
                        email: function() { return $("#email").val(); },
                        "_token": csrf_token
                    }
                }
            },
            profile_name: {
                required : true,
                valid_name : true,
            },
            mobile: {
                required: true,
                number: true,
                remote: {
                    url     : base_url + "checkMobile",
                    global  : false,
                    type    : "post",
                    data    : {
                        mobile: function() { return $("#mobileval").val(); },
                        "_token": csrf_token
                    }
                }
            },
            password: {
                required: true,
                minlength: 6,
            },
            cpassword: {
                required: true,
                minlength: 6,
                equalTo: "#psw-type"
            },
            'cuisine_type[]': {
                required: true,
            },
            area_code: {
                required: true,
            },
            aadar_image: {
                required: true,
            },
            // fa_link: {
            //     required: function(el) {
            //         checkURL(el.value);
            //     },
            //     url: true
            // },
            // in_link: {
            //     required: function(el) {
            //         checkURL(el.value);
            //     },
            //     url: true
            // },
            // yo_link: {
            //     required: function(el) {
            //         checkURL(el.value);
            //     },
            //     url: true
            // },
        },
        messages: {
            email: {
                required: "Enter your Email",
                email: "Invalid Email Address",
                remote: "Email address already registered"
            },
            mobile: {
                required: "Enter your Mobile number",
                number: "Invalid Mobile Number",
                remote: "Mobile Number already registered"
            },
            profile_name: {
                required:  'Enter your Profile Name',  
            },
            fa_link: 'Enter your Webste URL',
            in_link: 'Enter your Webste URL',
            yo_link: 'Enter your Webste URL',
            /*business_name: 'Enter your Business Name',
            address: 'Enter your Address',*/
            name: {
              required:  'Enter your Full Name',      
          },
          password: {
            required: "Please provide a password",
            minlength: "Your password must be atleast 6 characters"
        },
        cpassword: {
            required: "Please provide a password",
            minlength: "Your password must be atleast 6 characters",
            equalTo: "Password Mismatch"
        },
    },
});
  });
</script>
@endsection

