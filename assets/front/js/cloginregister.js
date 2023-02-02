$(document).ready(function() {
    $(".register").click(function(){
        $(".regcon").toggle();
        $(".logincon").hide();
        $("#logreg").html('');
        $('.account').removeClass('account-active');
    });
    $(".login").click(function(){
        $(".logincon").toggle();
        $(".regcon").hide();
        $("#logreg").html('');
        $('.account').removeClass('account-active');
    });
});

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
            $(".logincon, .checkout-otp-asw").css("display","block");
            $(".regcon").css("display","none");
            var mnumber = $("#mobileval").val();
            $(".mnumber").text(mnumber);
            $("#omobile").val(mnumber);
            $(".ndiv").hide();
            $(".odiv").show();
        },
        error : function(err){
            $(".reg-btn").prop('disabled',false);
            var msg = err.responseJSON.message;
            toast(msg, 'Oops!', 'error');
        }
    });
});

$("input[name='name']").on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});

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
    //console.log(datas);return false;
    $.ajax({
        url : url,
        data : datas,
        dataType : 'json',
        type : 'post',
        success : function(res){
            if(mlogin){
                var mnumber = $("#mobileval_login").val();
                //alert(mnumber);
                $(".reg-btn").prop('disabled',false);
                $(".error-message-area").hide();
                $(".ndiv").hide();
                $(".mnumber").text(mnumber);
                $("#omobile").val(mnumber);
                $("#oguestlogin").val($("#guestlogin").val());
                $(".odiv").show();
            } else {
                window.location.href = "{!! route('login') !!}";
            }
        },
        error : function(err){
            $(".reg-btn").prop('disabled',false);
            var msg = err.responseJSON.message;
            $(".error-message-area").find('.error-msg').text(msg);
            $(".error-message-area").show();
            if(resend){
                $(".ndiv").show();
                $(".odiv").hide();
            }
        }
    });
}

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
            location.reload(true);
            // window.location.href = "{!! route('login') !!}";
        },
        error : function(err){
            $(".reg-btn").prop('disabled',false);
            var msg = err.responseJSON.message;
            $(".error-message-area").find('.error-msg').text(msg);
            $(".error-message-area").show();
        }
    });
})

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