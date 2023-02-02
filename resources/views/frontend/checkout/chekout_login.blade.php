<div class="login-body p-0 ndiv checkout-login-asw">
    <div class="login-form">
        <form action="{{ route('login') }}" method="POST" id="lin-form">
            @csrf
            <p class="font-montserrat">Login to your account (or) <span class="text-theme cursor-pointer d-inline-block font-montserrat register">Create an account </span></p>
            <input type="hidden" name="role_id" value="2">
            <input type="hidden" name="device" value="web">
            <div class="form-group einput" style="display: none;">
                <label>{{ __('Email') }}</label>
                <input type="email" id="emailval" name="email" class="form-control @error('email') is-invalid @enderror"  value="" placeholder="Enter your email" autocomplete="email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <input type="hidden" id="guestlogin" name="guestlogin" value="">
            <div class="form-group minput" >
                <label class="font-montserrat">{{ __('Mobile') }}</label>
                <div class="position-relative">
                    <div class="align-items-center position-absolute d-flex"style="height: 60px;">
                         <img src="https://cdn.pixabay.com/photo/2016/08/24/17/07/india-1617463__340.png" alt="" width="35px" height="25px" class="ml-1">
                        <span class="country_code_asw font-montserrat" name="location_id" value="">
                           +{!! CNF_LOCATION_ID !!}
                        </span>
                    </div>
                    <input type="number" id="mobileval_login" name="mobile" class="form-control font-montserrat @error('mobile') is-invalid @enderror" name="mobile" value="" required placeholder="Enter your mobile" autocomplete="mobile" autofocus>
                    @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="submit-btn">
                <button type="submit" class="btn lbtn btn-theme my-3 font-montserrat">{{ __('Login') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="login-body checkout-otp-asw odiv p-0"  style="display: none;">
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
                        <input type="submit" value="Verify" class="btn btn-theme my-3">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="resendcode">
        <p>OTP not received ? <a href="javascript:void(0);" class="resend_code"><span class="text-theme">Resend code</span></a></p>
    </div>
</div>