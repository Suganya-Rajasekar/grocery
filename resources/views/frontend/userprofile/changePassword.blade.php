<div class="setting-main-area tab-pane fade  verification_area @if(last(request()->segments()) == 'changePassword') active show @endif " id="password">
    <div class="settings-content-area shadow-cont">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="d-inline font-opensans">Change Password</h4>
            <div class="d-lg-none float-right profile-asw-menu d-inline"><i class="fa fa-bars"></i></div>
        </div>
        <form action="" method="POST" id="user_pass_form" enctype="multipart/form-data">
            {{--  <input type="hidden" name="_token" value="0HzGBWThqR7l2Ar52HHVhnslecsNyXLQnaBlnj3Q">	 --}}
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <div class="row">
                <div class="col-lg-8 profile-form">
                    <div class="col-lg-12">
                        <div class="form-group mb-4">
                            <label for="current_password " class="font-montserrat">Current Password</label>
                            <input type="password" class="form-control font-montserrat" placeholder="Current Password" name="current_password" id="current_password" required="" value="{!! old('current_password') !!}">
                            <div class="fa-dash login-eye" id="eye1">
                                <i class="fas fa-eye-slash text-muted" id="login-psw"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-4">
                            <label for="new_password" class="font-montserrat">New Password</label>
                            <input type="password" class="form-control font-montserrat" placeholder="New Password" name="password" id="new_password" required="">
                            <div class="fa-dash login-new-eye" id="eye1">
                                <i class="fas fa-eye-slash text-muted" id="login-psw"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-4">
                            <label for="confirm_password" class="font-montserrat">Confirm Password</label>
                            <input type="password" class="form-control font-montserrat" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required="">
                            <div class="fa-dash login-con-eye" id="eye1">
                                <i class="fas fa-eye-slash text-muted" id="login-psw"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class=" ">
                            <button type="submit" class="btn font-montserrat btn-default" style="width: 100%">Update Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>