<div class="modal fade bd-example-modal-lg" id="couponDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body"> There are unsaved data in the screen.Do you still wish to go back? </div>
            <div class="modal-footer"> 
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="registration_success" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style='background-image: url("{!! asset("assets/front/img/welcome.jpg") !!}"); width: 100%;height: 450px;background-position: 100% 100%;background-size: cover;'>
            <div class="modal-body" style="padding: 50px"><h6 style="font-size: 69px;color: #fff;font-weight: bold;">Registered Successfully!</h6><p style="color: #fff;font-size: 25px;">A confirmation mail has been sent to the registered email ID.</p></div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="first_login" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style='width: 72%;margin: 0 auto;'>
            <div class="modal-header">
                
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>            
            <div class="modal-body" style="padding: 15px 50px;">
                <h6 style="font-size: 28px;font-weight: bold;">Welcome to {!! CNF_APPNAME !!}!</h6>
                <div style="font-weight: normal;font-size: 18px;">Do you wish to change your password?</div>
                <div class="btn  btn-secondary my-4 first_login_yes">Yes</div>
                <div class="modal_form mt-3">
                <form id="password-change" style="display: none;">
                    <label class=" mt-3">Password</label>
                    <div class="col-md-12">
                        <input type="password" name="password" id="password" required="" style="width:100%">
                    </div>
                    <label class=" mt-3">Confirm Password</label>
                    <div class="col-md-12">
                        <input type="password" name="confirm_password" id="confirm_password" style="width:100%" required="">
                    </div>
                     <div class="row  my-4">
                        <div class="col-md-12  text-center">
                             <input type="submit" class="btn btn-purple" value="Save">
                        </div>
                   
                </div>
                </form></div>
            </div>
           
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="deleteAccount" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body"> Are you sure you want to delete your Account? Please be sure that data related to your account will be erased within 24hrs of account deletion.
            </div>
            <div class="modal-footer"> 
                <form action="deleteAccount">
                    <input type="submit" class="btn btn-danger" value="Yes">
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
            </div>
        </div>
    </div>
</div>