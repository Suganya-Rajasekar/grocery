@extends('main.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="main-content mt-50">
	<div class="container-fluid">
		<div class="row mt-150 mb-50">
			<div class="col-md-6 login-img">
				<img src="https://image.freepik.com/free-vector/login-concept-illustration_114360-757.jpg">
				{{-- /*{{ asset('assets/front/img/signup-img.png') }}">*/ --}}
			</div>
			<div class="col-md-6 login-img otp-img" style="display: none;">
				<img src="{{ asset('assets/front/img/OTP.svg') }}"> 
			</div>
			<div class="col-md-6">
				<div class="login-card">
					<div class="login-header ndiv">
						<h5 class="font-montserrat">{{ __('Create your Account') }}</h5>
						<p class="font-montserrat">Already have an account?  <a href="{{ url('/login') }}">Sign in</a></p>
					</div>  
					<div class="login-body ndiv">
						<div class="login-form">
							@if(Session::has('errors'))
							<div class="row">
								<div class="col-12">
									<p class="alert alert-danger">{{ Session::get('errors') }}</p>
								</div>
							</div>
							@endif
							<form method="POST" action="{{ route('register') }}" id="reg-form">
								@csrf
								<div class="form-group">
									<label class="font-montserrat">{{ __('Full Name') }}</label>
									<input type="text" name="name" placeholder="Enter your full name" class="form-control font-montserrat" value="@if(old('firstname')){{old('firstname')}}@endif">
								</div>
								<input type="hidden" name="role_id" value="2">
								<input type="hidden" name="device" value="web">
								<div class="form-group">
									<label class="font-montserrat">{{ __('Email') }}</label>
									<input type="email" name="email" placeholder="Enter your email" class="form-control font-montserrat" value="@if(old('email')){{old('email')}}@endif">
								</div>
								<div class="form-group">
									<label class="font-montserrat">{{ __('Mobile Number') }}</label>
									<div class="input-group ">
										<div class="country-code">
											<div class="flag-img">  <img src="https://cdn.pixabay.com/photo/2016/08/24/17/07/india-1617463__340.png" alt=""></div>
											<span class="font-montserrat">+91</span> <!-- <span class="rotate-icon">></span> -->
										</div>
										<input type="hidden" class="font-montserrat" name="country" value="{!! CNF_LOCATION_ID !!}" readonly> 
										<input type="number" class="form-control font-montserrat" placeholder="Enter your number" id="mobileval" name="mobile" value="@if(old('mobile')){{old('mobile')}}@endif">
									</div>
								</div>
								<div class="form-group">
									<label class="font-montserrat">Referal Code</label>
									<div class="input-group ">
										<input type="text" class="form-control" placeholder="Enter referal code" name="referal_code">
									</div>
								</div>
								<div class="form-group emp-reg-eye">
									<label class="font-montserrat">{{ __('Password') }}</label>
									<div class=" login-psw-asw">
										<input type="password" id="psw-type" class="font-montserrat form-control" placeholder="Enter your password" name="password">
										<div class=" login-eye" id="eye1" >
											<i class="fas fa-eye input-group-text relative-fa " id="login-psw"></i>
										</div>
									</div>
								</div>
								<div class="form-group emp-reg-eye">
									<label class="font-montserrat">{{ __('Confirm Password') }}</label>
									<div class="font-montserrat login-confpsw-asw">
										<input type="password" id="psw-type1" class="font-montserrat form-control" placeholder="Retype your password" name="cpassword">
										<div class=" login-conf-eye" id="eye2"  >
											<i class="fas fa-eye input-group-text relative-fa" id="login-psw1"></i>
										</div>
									</div>
								</div>
								<div class="login-button">
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
	*otp*/
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
	$(document).on('submit','#reg-form',function(e) {
		e.preventDefault();
		var url = baseurl+"/api/register";
		$(".reg-btn").prop('disabled',true);
		$.ajax({
			url : url,
			data : $("#reg-form").serialize(),
			dataType : 'json',
			type : 'post',
			success : function(res){
				// window.location.href = "{!! route('login') !!}";
				var mnumber = $("#mobileval").val();
				$(".mnumber").text(mnumber);
				$("#omobile").val(mnumber);
				$(".ndiv").hide();
				$(".odiv").show();
				$(".login-img").hide();
				$(".otp-img").show();
			},
			error : function(err){
				$(".reg-btn").prop('disabled',false);
				var msg = err.responseJSON.message;
				$(".error-message-area").find('.error-msg').text(msg);
				// $(".error-message-area").show();
				toast(msg, 'Error!', 'error');
			}
		});
	});

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
				//$(".error-message-area").show();
				toast(msg, 'Error!', 'error');
			}
		});
	});
	
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

	$("input[name='name']").on('keypress', function (event) {
	var regex = new RegExp("^[a-zA-Z0-9]+$");
	var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	if (!regex.test(key)) {
	   event.preventDefault();
	   return false;
	}
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
		jQuery.validator.addMethod('lettersonly',function(value,element){
			return this.optional(element) || value == value.match(/^[a-zA-Z/s]*$/);
		},'Enter only Letters');
	   $('#reg-form').validate({
		rules: {
		name: {
			required: true,
			lettersonly: true,
		},
		email: {
			required: true,
			email: true
		},
		mobile: {
			required: true,
			number: true,
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
	}
	});
	});
</script>
@endsection
