function user_social_login(){$.ajax({type:"POST",dataType:"json",url:base_url+"checksocial",data:userData,success:function(a){!0==a.result&&(toast("Logged In","Success!","success"),setTimeout(function(){window.location.replace(redirect)},2e3))},error:function(a,b){toast(a,"Oops!","error")}})}function onSuccess(a){assign(a.getBasicProfile(),"gmail"),user_social_login(),gapi.auth2.getAuthInstance().signOut().then(function(){})}function assign(a,b){userData.name=a.getName(),userData.imageURL=a.getImageUrl(),userData.email=a.getEmail(),userData.social_id=a.getId(),userData.from=b}function onFailure(a){}function renderButton(){gapi.signin2.render("my-signin3",{scope:"profile email",width:240,height:50,longtitle:!0,theme:"dark",onsuccess:onSuccess,onfailure:onFailure})}function deleteCoupon(a){return confirm("Are you sure you want to delete the coupon?")&&$.ajax({type:"POST",dataType:"json",url:base_url+"vendor/deleteCoupon",data:{id:a,"_token":csrf_token},success:function(a){toast("Coupon Deleted","Success!","info"),setTimeout(function(){location.reload()},2e3)},error:function(a,b){toast(a,"Oops!","error")}}),!1}function deleteSubsc(a){return confirm("Are you sure you want to delete the subscription?")&&$.ajax({type:"POST",dataType:"json",url:base_url+"deleteSubsc",data:{id:a,"_token":csrf_token},success:function(a){toast("Subscription Deleted","Success!","info"),setTimeout(function(){location.reload()},2e3)},error:function(a,b){toast(a,"Oops!","error")}}),!1}"serviceWorker"in navigator&&window.addEventListener("load",function(){navigator.serviceWorker.register("/serviceWorker.js").then(a=>console.log("service worker registered")).catch(a=>console.log("service worker not registered",a))}),$("#password-user").validate({onkeyup:!1,onclick:!1,onfocusout:!1,rules:{confirm_password:{required:!0,minlength:6,equalTo:"#password"},password:{required:!0,minlength:6},old:{required:!0}},messages:{password:{required:"Please provide a new password",minlength:"Your password must be at least 6 characters long"},old:{required:"Please provide your current password"},confirm_password:{required:"Please provide the confirm password",minlength:"Your password must be at least 6 characters long",equalTo:"Password Mismatch"}},submitHandler:function(b){var a=new FormData($("#password-user")[0]);$.ajax({type:"POST",dataType:"json",url:base_url+"password-user",data:a,processData:!1,contentType:!1,success:function(a){!0==a.result&&(toast("Password Updated Successfully","Success!","info"),setTimeout(function(){$("#password-user").find("input").val(""),window.location.replace(base_url+"dashboard")},2e3))},error:function(a,b){$("#password-user").find("input").val(""),toast(a,"Oops!","error")}})}}),$(".couponDetail").click(function(){var a=$(this).attr("data-id");$.ajax({type:"POST",dataType:"json",url:base_url+"vendors/Coupondetail/"+a,success:function(a){$("#couponDetail .modal-body").html(a.html),$("#couponDetail").modal("show")},error:function(a,b){toast(a,"Oops!","error")}})}),$(".global").change(function(){var a=$(this).attr("group-id");$(this).is(":checked")?$("."+a).prop("checked",!0):$("."+a).prop("checked",!1)}),$(".edit,.remove,.create").change(function(){var a=$(this).attr("group-id");a=a.split("_")[1],$(this).is(":checked")?($(".grp_"+a+".edit").is(":checked")&&$(".grp_"+a+".remove").is(":checked")&&$(".grp_"+a+".create").is(":checked")&&$(".grp_"+a+".view").is(":checked")&&$(".global_"+a).prop("checked",!0),$(".view_"+a).prop("checked",!0)):$(".global_"+a).prop("checked",!1)}),$(".custom_container").on("click",function(){$(".radio_btn").removeClass("active"),$(this).parent(".radio_btn").addClass("active")}),$("#user_subscription_form").validate({rules:{company_name:{required:!0},website:{required:!0,url:!0},plan_name:{required:!0},price:{required:!0},currency:{required:!0},last_paid_date:{required:!0},plan_expiry_date:{required:!0},payment_type:{required:!0},interval_days:{required:!0}},messages:{company_name:{required:"Enter Company Name"},website:{required:"Enter Website URL",url:"Enter a valid URL"},plan_name:"Enter a Plan Name",price:"Enter a Price",currency:"Select a Currency",last_paid_date:"Select a Date",plan_expiry_date:"Select a Date",interval_days:"Enter Interval Days",payment_type:"Please select a Payment Type"},ignore:':hidden:not("select")',submitHandler:function(b){var a=$("#user_subscription_form").serialize();$.ajax({type:"POST",dataType:"json",url:base_url+"subscriptions/save",data:{form_data:a},success:function(a){jQuery("html,body").animate({scrollTop:0},1e3),$("#user_subscription_form").find("input").val(""),toast("Subscription Saved","Success!","info"),setTimeout(function(){window.location.replace(base_url+"subscriptions")},2e3)},error:function(a,b){toast(a,"Oops!","error")}})}}),$("#vendor_coupon_form").validate({rules:{coupon_code:{required:!0,remote:{url:base_url+"vendor/checkCoupon",type:"post",data:{coupon_code:function(){return $("#coupon_code").val()},id:function(){return $("#id").val()},"_token":csrf_token}}},add_discount_percent:{required:!0,range:[1,100],greaterThan:"#discount_percent"},discount_percent:{required:!0,range:[1,99]},count:{required:!0},last_name:{required:!0},valid_from:{required:!0},valid_to:{required:!0},terms:{required:!0},plans:{required:!0},users:{required:!0}},messages:{coupon_code:{required:"Enter Coupon Code",minlength:"Your password must be at least 2 characters long",remote:"Coupon Code already Used"},add_discount_percent:{required:"Enter Add.Discount Percent",range:"Enter between 1 to 100",greaterThan:"Must be greater than discount percent"},discount_percent:{required:"Enter Discount Percent",range:"Enter between 1 to 99"},count:"Enter a count",valid_from:"Select a From Date",valid_to:"Select a To Date",valid_to:"Select a To Date",terms:"Enter Terms and Conditions",plans:"Please select atleast 1 plan",users:"Please select atleast 1 user"},ignore:':hidden:not("select")',submitHandler:function(b){var a=new FormData($("#vendor_coupon_form")[0]);$.ajaxSetup({headers:{"X-CSRF-TOKEN":csrf_token}}),$.ajax({type:"POST",dataType:"json",url:base_url+"vendor/saveCoupon",processData:!1,contentType:!1,cache:!1,data:a,success:function(a){jQuery("html,body").animate({scrollTop:0},1e3),$("#vendor_coupon_form").find("input").val(""),toast("Coupon Saved","Success!","info"),setTimeout(function(){window.location.replace(base_url+"vendor/coupons")},2e3)},error:function(a,b){toast(a,"Oops!","error")}})}}),$(".cancel_btn").click(function(){$("#vendor_coupon_form").serialize()!=form_original_data?$("#changedModal").modal("show"):window.location.replace(base_url+"vendor/coupons")}),$(".subsc_cancel_btn").click(function(){$("#user_subscription_form").serialize()!=subsc_form_original_data?$("#changedModal").modal("show"):window.location.replace(base_url+"subscriptions")}),$("#vendor_dashboard_form").validate({rules:{email:{required:!0,email:!0,remote:{url:base_url+"vendor/checkEmail",global:!1,type:"post",data:{email:function(){return $("#email").val()},"_token":csrf_token,dashboard:1}}},business_name:{required:!0},phone_number:{required:!0,minlength:10,number:!0},address:{required:!0},last_name:{required:!0},website:{required:function(a){checkURL()},url:!0},confirm_password:{required:!0,minlength:6,equalTo:"#password"},password:{required:!0,minlength:6}},messages:{email:{required:"Enter your Email",email:"Invalid Email Address",remote:"Email address already taken"},business_name:"Enter your Business Name",website:{required:"Enter your Webste URL",url:"Enter a Valid URL"},address:"Enter your Address",name:"Enter your Contact Name",password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long"},confirm_password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long",equalTo:"Password Mismatch"}},submitHandler:function(b){var a=$("#vendor_dashboard_form").serialize();$.ajax({type:"POST",dataType:"json",url:base_url+"registerVendor",data:{form_data:a,"_token":csrf_token},success:function(a){toast("Updated Successfully","Success!","info")},error:function(a,b){toast(a,"Oops!","error")}})}}),$("#vendor_register_form").validate({rules:{email:{required:!0,email:!0,remote:{url:base_url+"vendor/checkEmail",global:!1,type:"post",data:{email:function(){return $("#email").val()},"_token":csrf_token}}},business_name:{required:!0},address:{required:!0},last_name:{required:!0},website:{required:function(a){checkURL()},url:!0},confirm_password:{required:!0,minlength:6,equalTo:"#password"},password:{required:!0,minlength:6}},messages:{email:{required:"Enter your Email",email:"Invalid Email Address",remote:"Email address already taken"},business_name:"Enter your Business Name",website:"Enter your Webste URL",address:"Enter your Address",name:"Enter your Contact Name",password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long"},confirm_password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long",equalTo:"Password Mismatch"}},submitHandler:function(b){var a=$("#vendor_register_form").serialize();$.ajax({type:"POST",dataType:"json",url:base_url+"registerVendor",data:{form_data:a,"_token":csrf_token},success:function(a){jQuery("html,body").animate({scrollTop:0},1e3),$("#vendor_register_form").find("input").val(""),toast("Registerd Successfully. Our Team will verify the information and contact you as soon as possible","Success!","info")},error:function(a,b){toast(a,"Oops!","error")}})}}),$("#vendor_login_form").validate({rules:{email:{required:!0,email:!0,remote:{url:base_url+"vendor/checkEmail",global:!1,type:"post",dataType:"json",data:{email:function(){return $("#email").val()},"_token":csrf_token,login:1}}},password:{required:!0,minlength:6}},messages:{email:{required:"This field is required",email:"Invalid Email Address",remote:"Email address not found"},password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long"}},submitHandler:function(b){var a=$("#vendor_login_form").serialize();$.ajax({type:"POST",dataType:"json",url:base_url+"vendor/loginUser",data:{form_data:a,"_token":csrf_token},success:function(a){toast("Logged In","Success!","success"),setTimeout(function(){window.location.replace("dashboard")},2e3)},error:function(a,b){toast(a,"Oops!","error")}})}}),$("#vendor_forgot_form").validate({onkeyup:!1,onclick:!1,onfocusout:!1,rules:{email:{required:!0,email:!0,remote:{url:base_url+"vendor/checkEmail",global:!1,type:"post",dataType:"json",data:{email:function(){return $("#email").val()},"_token":csrf_token,login:1,forgot:1}}}},messages:{email:{required:"This field is required",email:"Invalid Email Address",remote:"Email address not found"}},submitHandler:function(a){a.submit()}}),$("#register_form").validate({rules:{email:{required:!0,email:!0,remote:{url:base_url+"checkEmail",global:!1,type:"post",data:{email:function(){return $("#email").val()},"_token":csrf_token}}},first_name:{required:!0},last_name:{required:!0},confirm_password:{required:!0,minlength:6,equalTo:"#password"},password:{required:!0,minlength:6}},messages:{email:{required:"Enter your Email Address",email:"Invalid Email Address",remote:"Email address already taken"},first_name:"Enter your First Name",last_name:"Enter your Last Name",password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long"},confirm_password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long",equalTo:"Password Mismatch"}},submitHandler:function(b){var a=$("#register_form").serialize();$.ajax({type:"POST",dataType:"json",url:base_url+"registerUser",data:{form_data:a,"_token":csrf_token},success:function(a){jQuery("html,body").animate({scrollTop:0},1e3),$("#register_form").find("input").val(""),toast("Registerd Successfully!","Success!","info"),setTimeout(function(){window.location.replace("dashboard")},2e3)}})}}),$("#login_form").validate({rules:{email:{required:!0,email:!0,remote:{url:base_url+"checkEmail",type:"post",global:!1,dataType:"json",data:{email:function(){return $("#email").val()},"_token":csrf_token,login:1}}},password:{required:!0,minlength:6}},messages:{email:{required:"This field is required",email:"Invalid Email Address",remote:"Email address not found"},password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long"}},submitHandler:function(b){var a=$("#login_form").serialize();$.ajax({type:"POST",dataType:"json",url:base_url+"loginUser",data:{form_data:a,"_token":csrf_token},success:function(a){toast("Logged In","Success!","success"),setTimeout(function(){""==redirect?window.location.replace("dashboard"):window.location.replace(redirect)},2e3)},error:function(a,b){toast(a,"Oops!","error")}})}}),$("#forgot_form").validate({onkeyup:!1,onclick:!1,onfocusout:!1,rules:{email:{required:!0,email:!0,remote:{url:base_url+"checkEmail",global:!1,type:"post",dataType:"json",data:{email:function(){return $("#email").val()},"_token":csrf_token,login:1}}}},messages:{email:{required:"This field is required",email:"Invalid Email Address",remote:"Email address not found"}},submitHandler:function(a){a.submit()}}),$("#user_dashboard_form").validate({rules:{email:{required:!0,email:!0,remote:{url:base_url+"checkEmail",global:!1,type:"post",data:{email:function(){return $("#email").val()},"_token":csrf_token,dashboard:1}}},first_name:{required:!0},last_name:{required:!0}},messages:{email:{required:"Enter your Email Address",email:"Invalid Email Address",remote:"Email address already taken"},first_name:"Enter your First Name",last_name:"Enter your Last Name",password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long"},confirm_password:{required:"Please provide a password",minlength:"Your password must be at least 6 characters long",equalTo:"Password Mismatch"}},submitHandler:function(b){var a=$("#user_dashboard_form").serialize();$.ajax({type:"POST",dataType:"json",url:base_url+"registerUser",data:{form_data:a,"_token":csrf_token},success:function(a){toast("Updated Successfully!","Success!","info")}})}});var debounce=function(a,b,c){var d;return function(){var e=this,f=arguments,g=function(){d=null,c||a.apply(e,f)},h=c&&!d;clearTimeout(d),d=setTimeout(g,b),h&&a.apply(e,f)}}