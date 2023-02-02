<div class="login-checkout-asw">
    <div class="login-form">
        <form method="POST" action="{{ route('register') }}" id="reg-form">
        @csrf
        <p class="font-montserrat">Create an account (or) <span class="text-theme cursor-pointer d-inline-block font-montserrat login">Login to your account</span></p>
        <div class="form-group">
            <input type="text" name="name" placeholder="Enter your full name" class="form-control font-montserrat" value="">
        </div>
        <input type="hidden" name="role_id" value="2">
        <input type="hidden" name="device" value="web">
        <div class="form-group">
            <input type="email" name="email" placeholder="Enter your email" class="form-control font-montserrat" value="">
        </div>
        <div class="form-group">
            <div class="input-group phone-no">
                <div class="country-code">
                    <div class="flag-img">  <img src="https://cdn.pixabay.com/photo/2016/08/24/17/07/india-1617463__340.png" alt=""></div>
                    <span class="font-montserrat">+91</span> <!-- <span class="rotate-icon">></span> -->
                </div>
                <input type="hidden" name="country" value="{!! CNF_LOCATION_ID !!}" readonly> 
                <input type="number" class="form-control font-montserrat" placeholder="Enter your number" id="mobileval" name="mobile" value="">
            </div>

        </div>
        <div class="form-group emp-reg-eye">
            <div class="input-group login-psw-asw">
                <input type="password" id="psw-type" class="form-control font-montserrat" placeholder="Enter your password" name="password">
                <div class="login-eye" id="eye1" >
                    <i class="fa fa-eye input-group-text relative-fa text-muted" id="login-psw"></i>
                </div>
            </div>
        </div>
        <div class="form-group emp-reg-eye">
            <div class="input-group login-confpsw-asw">
                <input type="password" id="psw-type1" class="form-control font-montserrat" placeholder="Retype your password" name="cpassword">
                <div class="login-conf-eye" id="eye2"  >
                    <i class="fa fa-eye input-group-text relative-fa text-muted" id="login-psw1"></i>
                </div>
            </div>
        </div>
        <p class="text-muted font-montserrat ind-30"><input type="checkbox" checked="" class="checkTerms" name="" value="1" required="">By creating an account, I accept the terms and conditions</p>
        <div class="submit-btn">
            <button type="submit" class="btn btn-theme my-3 reg-btn font-montserrat">{{ __('Register') }}</button>
        </div>
    </form>
    </div>
</div>
