@extends('main.app')
@section('css')
<style>
    .tab-content {
        height: auto;
        /*overflow-y: scroll;*/
        scrollbar-width: none;
    }
    #wishlist ul {
        margin: 0px;
        padding: 0px;
        overflow: hidden;
    }
    a:not([href]) {
        color: #f65a60;
        text-decoration: none;
    }
    #wishlist li {
        list-style: none;
        overflow: hidden;
    }
    h4 {
        /* color: #212121 !important; */
        /* font-size: 25px !important; */
        /* margin-bottom: 20px !important; */
        /*font-weight: 600 !important;*/
    }

    .back-btn{
       background: #f65a60;
       border-radius: 50%;

       /* padding-left: 50px; */
       /* margin-left: 11px; */
       float: right;
       margin: auto;
       bottom: -19px;

   }
   label.error {
      color: red;
  }
  #Referral {
    box-shadow: 3px 3px black;
    padding: 10px;
    background: #f55a60;
    color: white;
    font-weight: bold;
}
</style>
@endsection
@section('content')
<input type="hidden" value="1" id="bPage">
<section class="profile-asw">
    <div class="error-message-area">
        <div class="error-content">
            <h4 class="error-msg"></h4>
        </div>
    </div>
    <div class="main-area" style="padding-top: 8px;">
        <div class="container-fluid">
            <div class="row mt-30">
                <div class="profile-backdrop d-none"></div>
                <div class="col-lg-3">
                    <div class="settings-main-menu">
                        @include('frontend.userprofile.tabs')
                    </div>
                </div>                                          
                <div class="col-lg-9 right-cont px-0">
                    <div class="tab-content" >
                        @if($module == 'profile')
                        @include('frontend.userprofile.profile')
                        @elseif($module == 'changePassword')
                        @include('frontend.userprofile.changePassword')
                        @elseif($module == 'myOrders')
                        @include('frontend.userprofile.myOrders')
                        @elseif($module == 'bookmark')
                        @include('frontend.userprofile.bookmark')
                        @elseif($module == 'favourites')
                        @include('frontend.userprofile.favorite')
                        @elseif($module == 'wishlist')
                        @include('frontend.userprofile.wishlist')
                        @elseif($module == 'address')
                        @include('frontend.userprofile.address')
                        @elseif($module == "events")
                        @include('frontend.userprofile.orders.eventorder')
                        @elseif($module == "home_events")
                        @include('frontend.userprofile.orders.home_event_order')
                        @elseif($module == "referral")
                        @include('frontend.userprofile.referral')
                        @elseif($module == "wallet")
                        @include('frontend.userprofile.wallet')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
    var logeyecount     = 0;
    var logconfeye      = 0;
    var logconfneweye   = 0;
    $(document).on('click','.login-eye i',function() {
        logeyecount++;
        if((logeyecount % 2) == 0) {
         $('input[name="current_password"]').attr('type', 'password');
         $('.login-eye i').addClass("fa-eye-slash");
         $('.login-eye i').removeClass("fa-eye");
     } else {
         $('input[name="current_password"]').attr('type', 'text');
         $('.login-eye i').removeClass("fa-eye-slash");
         $('.login-eye i').addClass("fa-eye");
     }
 });
    $(document).on('click','.login-new-eye i',function() {
        // alert();
        logconfeye++;
        if((logconfeye % 2) == 0){
            $('input[name="password"]').attr('type', 'password');
            $('.login-new-eye i').addClass("fa-eye-slash");
            $('.login-new-eye i').removeClass("fa-eye");
        } else {
            $('input[name="password"]').attr('type', 'text');
            $('.login-new-eye i').removeClass("fa-eye-slash");
            $('.login-new-eye i').addClass("fa-eye");
        }
    });
    $(document).on('click','.login-con-eye i',function(){
        logconfneweye++;
        if((logconfneweye % 2) == 0) {
            $('input[name="confirm_password"]').attr('type', 'password');
            $('.login-con-eye i').addClass("fa-eye-slash");
            $('.login-con-eye i').removeClass("fa-eye");
        } else {
            $('input[name="confirm_password"]').attr('type', 'text');
            $('.login-con-eye i').removeClass("fa-eye-slash");
            $('.login-con-eye i').addClass("fa-eye");
        }
    });

    $(document).on('click','.profileModule',function(){
        var module = $(this).attr('name');
        var offsetValue = $("#bPage").val();
        offsetValue = parseInt(offsetValue )+1;
        $("#bPage").val(offsetValue);
        var isPastOrder = $(this).attr('id');

        var page = {bPage:  offsetValue};
        if (module == 'favourites')
            var page = {fPage:  offsetValue};
        else if (module == 'myOrders' && isPastOrder == 'progress_orders')
            var page = {Page:  offsetValue};
        else if (module == 'myOrders' && isPastOrder == 'past_orders')
            var page = {Page:  offsetValue, isPastOrder: 1};
        else if (module == 'wishlist')
            var page = {pageNumber:  offsetValue};
        else if (module == 'home_events')
            var page = {Page : offsetValue}
        $.ajax({
            url: base_url + 'user/dashboard/'+module,
            type: "get",
            dataType: "json",
            async: true,
            data:  page,
            success: function(data) {
                if(data.action == 'past')
                {
                    if(data.recordCount == 0)
                    {
                        $(".past > .profileModule").css("display","none");
                    }
                    else
                    {
                        $(".past > .paginate").replaceWith(data.app);          
                    }
                }
                else if(data.action == 'progress')
                {
                    if(data.recordCount == 0)
                    {
                        $("#progress_orders").css("display","none");
                    }
                    else 
                    {
                        $(".process > .paginate").replaceWith(data.app); 
                    }
                }
                else if(data.action == null && data.recordCount == 0)
                {
                    $(".profileModule").css("display","none");
                }   
                else 
                {
                    $(".paginate").replaceWith(data.app); 
                    $('.owl-bookmark,  .owl-fav').owlCarousel({
                        loop:false,
                        margin:10,
                        nav:false,
                        dots:false,
                        responsive:{
                            0:{
                                items:1
                            },
                            600:{
                                items:2
                            },
                            850:{
                                items:3
                            },
                            1400:{
                                items:4
                            }
                        }
                    });
                }
            }
        });
    });

    $(document).ready(function () {
        $("#mobile").keypress(function (e) {
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

        $('#user_settings_form').validate({
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
            },
            mobile: {
                required: true,
                number: true,
                minlength : 10,
            },
        },
        messages: {
            name: {
              required:  'Enter your Full Name',      
          },
          email: {
            required: "Enter your Email",
            email: "Invalid Email Address",
        },
        mobile: {
            required: "Enter your Mobile number",
            number: "Invalid Mobile Number",
            minlength : "Enter valid Mobile Number",
        },
    },
});
    });


    function cancelOrder(id) {
        bootbox.confirm({
            message: "Are you sure? Want to cancel your order?",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i> Yes',
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result) {
                    $('#reasonmodal').modal('show');
                    $('#id').val(id);
                }
            }
        });    
        return false;
    }
    $(document).on('click','.reject',function(){
        var reason = $('#reason').val();
        var id     = $('#id').val();
        $('#reasonmodal').modal('hide');
        $.ajax({
            type : 'PUT',
            url : base_url+'cancel_order', 
            data : {order_id:id,reason:reason},
            success:function(data){
                var msg = JSON.parse(JSON.stringify(data)); 
            //$(".error-message-area").css("display","block");
            //$(".error-content").css("background","#9cda9c");
            //$(".error-msg").html("<b style='color:black'>"+msg.message+"</b>"); 
            toast(msg.message, 'Success!', 'success');
            setTimeout(function(){location.reload()}, 1000);
        },
        error : function(err){ 
            var msg = err.responseJSON.error; 
            toast(msg, 'Error!', 'error');
            setTimeout(function(){location.reload()}, 1000);
        }
    });
    });
//open popup order review 
function openorder_review(id){
    $.ajax({
        type : 'GET',
        url : base_url+'order_review_detail',
        data : {order_id:id},
        success:function(data){
            $("#reviews").modal('show');
            $('.contentbox').html(data.html);
        },
        error : function(err){ 

            $("#reviews").modal('hide');
            var msg = err.responseJSON.message; 
            $(".error-content").css("background","#d4d4d4");
            $(".error-message-area").find('.error-msg').text(msg);
            $(".error-message-area").show();
        }
    });
}

//send review data
$(document).on('submit','.reviewform',function(e){
    e.preventDefault();
    var url = base_url+"order_review_send";
    $.ajax({
        type : 'POST',
        url : url,
        data : $(".reviewform").serialize(),
        success : function(res){
            $('.modal').modal('hide');
            var msg = JSON.parse(JSON.stringify(res)); 
            $(".error-message-area").css("display","block");
            $(".error-content").css("background","#d4d4d4");
            $(".error-msg").html("<p style='color:red' class='mb-0'>"+msg.message+"</p>"); 
            setTimeout(function(){location.reload()}, 1000);
        },
        error : function(err){
            $('.modal').modal('hide');
            var msg = err.responseJSON.message; 
            $(".error-content").css("background","#d4d4d4");
            $(".error-message-area").find('.error-msg').text(msg);
            $(".error-message-area").show();
        }
    });
});
$(document).on('click','.review_remove',function(){
    var id = $(this).attr('data-reviewid');
    var url = base_url+"order_review_send";
    $.ajax({
        type : 'DELETE',
        url  : url,
        data : {review_id:id,action:'remove'},
        success : function(res){
            var msg = JSON.parse(JSON.stringify(res)); 
            $(".error-message-area").css("display","block");
            $(".error-content").css("background","#d4d4d4");
            $(".error-msg").html("<p style='color:red' class='mb-0'>"+msg.message+"</p>"); 
            setTimeout(function(){location.reload()}, 1000);
        },
        error : function(err){
            var msg = err.responseJSON.message; 
            $(".error-content").css("background","#d4d4d4");
            $(".error-message-area").find('.error-msg').text(msg);
            $(".error-message-area").show();
        }
    });
});
$(document).on('click','#Referral',function(){
    var ref_code = $(this).data('refcode');
    navigator.clipboard.writeText(ref_code);
});
</script>
@endsection


