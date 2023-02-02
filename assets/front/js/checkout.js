$(function(){    
    var options = {
        componentRestrictions: {country: 'ca'},
        types: ['(regions)'],
    };
    var map;
    var marker='';
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow();
    function generateCoords(){
        return new google.maps.LatLng($('#a_lat').val(),$('#a_lang').val());
    }
    function initialize(){
       marker = '';
       map = '';
       var myLatlng = generateCoords();
       var styles = [
       {
        featureType: "poi",
        elementType: "business",
        stylers: [
        { visibility: "off" }
        ]
    }
    ];
    var mapOptions = {
        zoom: 15,
        disableDefaultUI: true,
        streetViewControl: false,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: styles,
        gestureHandling: 'greedy'
    };
    map = new google.maps.Map(document.getElementById("myaddrMap"), mapOptions);
    placeMarker(myLatlng);
    google.maps.event.addListener(map, 'click', function (event) {
       placeMarker(event.latLng);
       geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
               if (results[0]) {
                   $('#a_addr').val(results[0].formatted_address);
                   $('#a_lat').val(marker.getPosition().lat());
                   $('#a_lang').val(marker.getPosition().lng());
                   infowindow.setContent(results[0].formatted_address);
                   infowindow.open(map, marker);
                   address_check();
               }
           }
       });
   });
    google.maps.event.addDomListener(window, "resize", resizingMap());
}
function resizeMap() {
    if(typeof map =="undefined") return;
    setTimeout( function(){
        resizingMap();
    } , 400);
}
function resizingMap() {
   if(typeof map =="undefined") return;
   var center = new google.maps.LatLng($('#lat').val(),$('#lang').val());
   google.maps.event.trigger(map, "resize");
   map.setCenter(center); 
}
function placeMarker(location) {
    var myLatlng = location;
    if (marker == undefined || marker == ''){
        marker = new google.maps.Marker({
            position: myLatlng,
            map: map, 
            animation: google.maps.Animation.DROP,
            draggable: true,
        });
        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#a_addr').val(results[0].formatted_address);
                    $('#a_lat').val(marker.getPosition().lat());
                    $('#a_lang').val(marker.getPosition().lng());
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                    address_check();
                }
            }
        });
        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('#a_addr').val(results[0].formatted_address);
                        $('#a_lat').val(marker.getPosition().lat());
                        $('#a_lang').val(marker.getPosition().lng());
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);
                        address_check();
                    }
                }
            });
        });
    } else {
        marker.setPosition(myLatlng);
    }
    map.setCenter(myLatlng);
}
function address_check(){
    var addr = $('#a_addr').val();
    var from = $('#addr').val();
    var lat = $('#a_lat').val();
    var lang = $('#a_lang').val();
    $(".go_to_step").removeAttr("disabled");
    $("#location").val(addr);
}
function geoSuccess_place(position) {
    var geocoder = new google.maps.Geocoder();
    var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    if (geocoder) {
        geocoder.geocode({ 'latLng': latLng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var components = results[0].address_components;
                $('#a_addr').val(results[0].formatted_address);
                $('#a_lat').val(lat);
                $('#a_lang').val(lng);
                initialize();
                    // placeMarker();
                    address_check();
                }
                else {
                    $('#a_lat').val('');
                    $('#a_lang').val('');
                    $("#a_addr").val(''); 
                }
            });
    } 
}
function showError_place(error){
    var innerHTML = '';
    switch(error.code) {
        case error.PERMISSION_DENIED:
        innerHTML = "You have blocked browser from tracking your location. To use this, change your location settings in browser."
        break;
        case error.POSITION_UNAVAILABLE:
        innerHTML = "Location information is unavailable."
        break;
        case error.TIMEOUT:
        innerHTML = "The request to get user location timed out."
        break;
        case error.UNKNOWN_ERROR:
        innerHTML = "An unknown error occurred."
        break;
    }
        // $('#location').val(innerHTML);
        $('#location').val('Madurai, Tamil Nadu, India');
        $('#a_addr').val('Madurai, Tamil Nadu, India');
        $('#a_lat').val(9.939093);
        $('#a_lang').val(78.121719);
        infowindow.setContent('Madurai, Tamil Nadu, India');
        infowindow.open(map, marker);
        placeMarker();
        address_check();
    }
    function getLocation_pos(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(geoSuccess_place, showError_place);
        } else { 
            alert("Geolocation is not supported by this browser.");
        }
    }
    var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'));
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place   = autocomplete.getPlace();
        var latitude  = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();
        $('#a_lat').val(latitude);
        $('#a_lang').val(longitude);
        $('#a_addr').val(place.formatted_address);
        // placeMarker(generateCoords());
        initialize();
        address_check();
    }); 
    $(document).on("click", ".fn_map_modal", function(){
        getLocation_pos();
        setTimeout(function(){ resizingMap() }, 1000);
        $("#building").val('');
        $("#landmark").val('');
        $("input:radio[name='address_type']").each(function(e){
            this.checked = false;
        })
        $("#map_modal").addClass("left-active");
        $('.overlay').show();
    });
    // initialize();
});

$(document).ready(function(){
    $(".address-asw").click(function(){
        $(".address-asw-checkout").addClass("address-active-asw");
        $(".address-asw-backdrop").removeClass("d-none");
    });

    $(".close-asw span").click(function(){
        $(".address-asw-checkout").removeClass("address-active-asw");
        $(".address-asw-backdrop").addClass("d-none");
    });

    $(".address-asw-backdrop").click(function(){
        $(".address-asw-checkout").removeClass("address-active-asw");
        $(".address-asw-backdrop").addClass("d-none");
    });

});





function choose_address(id) {
    $.ajax({
        type : 'PUT',
        url : base_url+'sendaddress',
        data : {id:id},
        success:function(data){
            $('.modal').modal('hide');
            var msg = JSON.parse(JSON.stringify(data)); 
            // $(".error-message-area").css("display","block");
            //$(".error-content").css("background","#9cda9c");
            //$(".error-msg").html("<b style='color:black'>"+msg.message+"</b>"); 
            toast('Cart details updated successfully', 'Success!', 'success');
            setTimeout(function(){location.reload()}, 1000);
        },
        error : function(err){ 
            $('.modal').modal('hide');
            var msg = err.responseJSON.message; 
            toast(msg, 'Oops!', 'error');
        }
    });
}

$(document).on('click',".change_address",function(){
    $('.allAddress').removeClass('d-none');
    $('.selectedAddress').addClass('d-none');
});

$(document).on('click',".cod_submit",function(){
    var type = "cod";
    $('.ajaxLoading').show();
    $.ajax({
        url     : base_url+"placeorder",
        type    : 'POST',
        data    : {type : type},
        //dataType: 'json',
        success : function(res){
            $('.ajaxLoading').hide();
            var msg = JSON.parse(JSON.stringify(res)); 
            toast(msg.message, 'Success!', 'success');
            setTimeout(function(){  window.location.href = base_url+'thankyou'; }, 1000);
        },
        error : function(err){ 
            $('.ajaxLoading').hide();
            $('.modal').modal('hide');
            var msg = err.responseJSON.message;             
            toast(msg, 'Oops!..', 'error');
        }
    });
});

//save add new address 
$(document).on('submit','#address_form',function(e){
    e.preventDefault();
    var url = base_url+"useraddress";
    $.ajax({
        type : 'POST',
        url : url,
        data : $("#address_form").serialize(),
        success : function(res){
            var msg = JSON.parse(JSON.stringify(res)); 
            //$(".error-message-area").css("display","block");
            //$(".error-content").css("background","#9cda9c");
            //$(".error-msg").html("<b style='color:black'>"+msg.message+"</b>"); 
            toast('Address data added successfully', 'Success!', 'success');
            setTimeout(function(){location.reload()}, 1000);
        },
        error : function(err){
            var msg = err.responseJSON.message; 
            //$(".error-content").css("background","#ED4956");
            //$(".error-message-area").find('.error-msg').text(msg);
           //$(".error-message-area").show();
           toast('The address type field is required', 'Error!', 'error');
       }
   });
});

$(document).on('click','.ucart',function (e) {
    e.preventDefault();
    //$('.cart_overlay').show();
    var curclass = $(this).attr('class').split(' ')[1];
    var curdiv   = $(this).closest("div").find(".cartQty");
    var Qty      = curdiv.attr('data-qty');
    if (curclass == 'add_item') {
        Qty = parseInt(Qty) + 1;
    } else {
        Qty = parseInt(Qty) - 1;
    }
    if(Qty >= 0) {
        curdiv.attr('data-qty',Qty);
        curdiv.text(Qty).trigger('changed');
    }
});
$(document).on('changed','.cartQty',debounce(function () {
    var url        = baseurl+"/updatecart";
    var Qty        = $(this).attr('data-qty');
    var static_qty = $(this).attr('data-staticqty');
    var ucart_id   = $(this).closest("div").find('.ucart').attr('data-uid');
    $.ajax({
        url     : url,
        data    : {quantity : Qty, ucart_id : ucart_id, page : 'checkout'},
        dataType: 'json',
        type    : 'PATCH',
        success : function(res) {
            if (res.cart != '') {
                $('.cartData').html(res.cart);
            } else {
                toast('Your cart was emptied.', 'Oops!..', 'success');
                setTimeout(function(){location.reload()}, 1000);
            }
        },
        error   : function(err) {
            var msg = err.responseJSON.message;
            $('#qty_'+ucart_id).attr('data-qty',static_qty);
            $('#qty_'+ucart_id).text(static_qty);
            toast(msg, 'Oops!..Something went wrong!', 'error');
        },
        complete: function (res) {
            // window.location.reload();
            // $('.cart_overlay').hide();
        }
    });
},500));

$(document).on('click','.delcart',function (e) {
    e.preventDefault();
    var secondclss = $(this).attr('class').split(' ')[1];
    var url      = baseurl+"/deletecart";
    var func     = $(this).attr('data-function');
    if (func === 'removecart') {
        var res_id      = $(this).attr('data-res_id');
        var date        = $(this).attr('data-date');
        var time_slot   = $(this).attr('data-time_slot');
        var msg = "chef";
        var dataS       = {_method:"DELETE",'_token':$('meta[name="csrf-token"]').attr('content'),function: func, res_id : res_id, date : date, time_slot : time_slot, page : 'checkout'};
    } else if (func === 'removedish') {
        var food_id     = $(this).attr('data-food_id');
        var date        = $(this).attr('data-date');
        var time_slot   = $(this).attr('data-time_slot');
        var msg = "dish";
        var dataS       = {_method:"DELETE",'_token':$('meta[name="csrf-token"]').attr('content'),function: func, food_id : food_id, date : date, time_slot : time_slot, page : 'checkout'};
    } else if (func === 'removeaddon') {
        var addon_id    = $(this).attr('data-addon_id');
        var msg = "addon";
        var dataS       = {_method:"DELETE",'_token':$('meta[name="csrf-token"]').attr('content'),function: func, addon_id : addon_id, page : 'checkout'};
    }
    bootbox.confirm({
        message: "Are you sure? Want to remove "+msg+" from your cart?",
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
                $('.cart_overlay').show();    
                $.ajax({
                    url     : url,
                    data    : dataS,
                    type    : 'POST',
                    dataType: 'json',
                    success : function(res){
                        if (res.cart != '') {
                            $('.cartData').html(res.cart);
                        } else {
                            toast('Your cart was emptied.', 'Oops!..', 'success');
                            setTimeout(function(){location.reload()}, 1000);
                        }
                    },
                    error : function(err){
                        var msg = err.responseJSON.message;
                        toast(msg, 'Oops!..Something went wrong!', 'error');
                    },
                    complete : function (res) {
                        if (func == 'removecart' && secondclss == 'un_available') {
                            window.location.reload();
                        } else {
                            $('.cart_overlay').hide();
                        }
                    }
                });
            }
        }
    });
});

//Apply coupon
$(document).on('click',".send_coupon",function(){
    var promo_id    = $(this).attr('data-id');
    var promo_code  = $(this).closest('.couponcode').find('input[name=coupon_code]').val();
    var action      = $(this).attr('data-action');
    var data        = {promo_id : promo_id,action : action};
    if(promo_code) {
        var data    = {promo_code : promo_code}; 
    }
    $('.ajaxLoading').show();
    $.ajax({
        url     : base_url+"apply_coupon",
        type    : 'PUT',
        data    : data,
        //dataType: 'json',
        success : function(res){
            $('.ajaxLoading').hide();
            var msg = JSON.parse(JSON.stringify(res)); 
            // $(".error-message-area").css("display","block");
            // $(".error-content").css("background","#9cda9c");
            // $(".error-msg").html("<b style='color:black'>"+msg.message+"</b>");
            toast(msg.message, 'Success!', 'success');
            setTimeout(function(){location.reload()}, 1000);
        },
        error : function(err){ 
            $('.modal').modal('hide');
            var msg = err.responseJSON.message; 
            toast(msg, 'Error!', 'error');
            if(err.status == 401){
                $('.account').addClass('account-active');
                $('.apply-coupon-btn').removeClass('active');
                $('.coupon-backdrop').addClass('d-none');
                $('.coupon-nav').removeClass('active');
                $('body').css("overflow","unset");

            }
            $('.ajaxLoading').hide();
            // $(".error-content").css("background","#ED4956");
            // $(".error-message-area").find('.error-msg').text(msg);
            // $(".error-message-area").show();
            // setTimeout(function(){location.reload()}, 1000);
        }
    });
});

$(document).on('keyup','.coupon_code',function(){
    if($(this).val().length > 1) {
        $('.coupon-submit').removeAttr('disabled');
    } else if($(this).val().length == 0) {
        $('.coupon-submit').attr('disabled');
    }
});

$('#address_type_others').click(function(){
    $('.address_types').attr("style", "display: none !important");
    $('#address_type_text').show();
    $('#cancel-btn').show();
});

$('#cancel-btn').click(function(){
   $(this).hide(); 
   $('#address_type_text').attr("style", "display: none !important");
   $('#flexRadioDefault1').prop('checked',false);
   $('#flexRadioDefault2').prop('checked',false);
   $('#flexRadioDefault3').prop('checked',false);
   $('#address_type_text').val("");
   $('.address_types').show();
});

$(document).on('click',".wallet-form-check",function(){
    $('#w-checkbox').hide();
    $('.w-apply').hide();
    if($(this).prop('checked') == true) {
        $('#w-checkbox').show();
        $('.w-apply').show();
        if(('#use-wa-text').text() != 'Edit Wallet') {
            $('#w-checkbox').val('');
        }
    }
});

$(document).on('click','.w-apply',function(){
    var wallet_amt = $('#w-checkbox').val();
        $.ajax({
            url  : baseurl+'/apply_wallet',
            type : "PATCH",
            dataType: 'json',
            data : {used_wallet_amt : wallet_amt},
            success:function(res){
                $('.cartData').html(res.cart);
                if(res.used_wallet_amount != 0) {
                    $('#w-checkbox').val(res.used_wallet_amount);
                    $('#use-wa-text').text('Edit Wallet');
                }
            },
            error:function(err) {
                var msg = err.responseJSON.message;
                toast(msg, 'Oops!..Something went wrong!', 'error');
            }
        });   
});