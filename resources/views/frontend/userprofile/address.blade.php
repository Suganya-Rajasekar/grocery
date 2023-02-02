<div class="setting-main-area tab-pane  fade  verification_area @if(last(request()->segments()) == 'address') active show @endif  " id="address">
    <!-- <form id="addressForm" method="post"> -->
        <div class="col-md-12 pb-5" style="">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="d-inline mb-0 font-opensans font-weight-bold">Manage Address</h4>
                <div class="d-flex justify-content-end align-items-center">
                     {{--<button class="btn btn-default wish-btn" type="submit">Save</button>--}}
                     <button class="btn btn-default wish-btn address-asw-edit fn_map_modal" type="submit">Add new address</button>
                    <div class="d-lg-none float-right profile-asw-menu d-inline"><i class="fa fa-bars"></i></div>
                </div>

            </div>
        </div>
        <div class="settings-content-area">
            <div class="row">
                @foreach($address as $k => $addr_v)
                <div class="col-md-6 mt-3">
                    <div class="d-flex py-3 address-box">
                        <i class="fas @if($addr_v->address_type == 'home'){!! 'fa-home' !!}@elseif($addr_v->address_type == 'office'){!! 'fa-briefcase' !!}@else{!! 'fa-map-marker-alt' !!}@endif px-sm-3 px-2"></i>
                        <div class="">
                            <h6 class="font-weight-bold">{!! ucfirst($addr_v->address_type) !!}</h6>
                            {{-- <p>{!! $addr_v->address !!}</p> --}}
                            <p>@if($addr_v->landmark){!! '<b>Landmark : </b>'.$addr_v->landmark.',<br>' !!}@endif {{-- @if($addr_v->building){!! $addr_v->building.',' !!}@endif @if($addr_v->area){!! $addr_v->area.',' !!}@endif @if($addr_v->city){!! $addr_v->city.',' !!}@endif @if($addr_v->state){!! $addr_v->state !!}@endif @if($addr_v->pin_code){!! '- '.$addr_v->pin_code !!}@endif --}}</p>
                            <p>{!! $addr_v->display_address !!}</p>
                            <div class="d-flex">
                                <input type="hidden" name="h_address_type" id="h_address_type_{{ $addr_v->id }}" value="{!! $addr_v->address_type !!}">
                                <input type="hidden" name="h_landmark" id="h_landmark_{{ $addr_v->id }}" value="{!! $addr_v->landmark !!}">
                                <input type="hidden" name="h_building" id="h_building_{{ $addr_v->id }}" value="{!! $addr_v->building !!}">
                                <input type="hidden" name="h_area" id="h_area_{{ $addr_v->id }}" value="{!! $addr_v->area !!}">
                                <input type="hidden" name="h_city" id="h_city_{{ $addr_v->id }}" value="{!! $addr_v->city !!}">
                                <input type="hidden" name="h_state" id="h_state_{{ $addr_v->id }}" value="{!! $addr_v->state !!}">
                                <input type="hidden" name="h_pin_code" id="h_pin_code_{{ $addr_v->id }}" value="{!! $addr_v->pin_code !!}">
                                <input type="hidden" name="h_address" id="h_address_{{ $addr_v->id }}" value="{!! $addr_v->address !!}">
                                <input type="hidden" name="h_lat" id="h_lat_{{ $addr_v->id }}" value="{!! $addr_v->lat !!}">
                                <input type="hidden" name="h_lang" id="h_lang_{{ $addr_v->id }}" value="{!! $addr_v->lang !!}">
                                <a href="javascript::void();" class="py-2 pr-5 address-asw-edit fn_map_modal" data-id="{{ $addr_v->id }}">Edit</a>
                                <a href="javascript:;" class="py-2 pr-5 delete_address" data-id="{{ $addr_v->id }}">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="checkout-asw">
            <div class="address-asw-backdrop d-none"></div>
            @include("frontend.addresspopup")
        </div>
    <!-- </form> -->
</div>
@section('script')
<script type="text/javascript">
    $(".address-asw-edit").click(function(){
        $(".address-asw-checkout").addClass("address-active-asw");
        $(".address-asw-backdrop").removeClass("d-none");
        $id = $(this).data('id');
        $('#location').val($('#h_address_'+$id).val());
        $('#pin_code').val($('#h_pin_code_'+$id).val());
        $('#building').val($('#h_building_'+$id).val());
        $('#area').val($('#h_area_'+$id).val());
        $('#landmark').val($('#h_landmark_'+$id).val());
        $('#city').val($('#h_city_'+$id).val());
        $('#state').val($('#h_state_'+$id).val());
        $('#a_lat').val($('#h_lat_'+$id).val());
        $('#a_lang').val($('#h_lang_'+$id).val());
        $('#a_addr').val($('#h_address_'+$id).val());
        $('#address_id').val($id);
        $add_val=$('#h_address_type_'+$id).val();
        //$add_val=$("input[name='address_type']:checked").val();
        if($add_val=='home'){
            $("#flexRadioDefault1").prop("checked", true);
        } else if($add_val=='office'){
            $("#flexRadioDefault2").prop("checked", true);
        } else if($add_val=='other'){
            $("#flexRadioDefault3").prop("checked", true);
        } else{
            $("#flexRadioDefault1").prop("checked", true);
        }

    })
    $(".close-asw span").click(function(){
        $(".address-asw-checkout").removeClass("address-active-asw");
        $(".address-asw-backdrop").addClass("d-none");
    })
    $(".address-asw-backdrop").click(function(){
        $(".address-asw-checkout").removeClass("address-active-asw");
        $(".address-asw-backdrop").addClass("d-none");
    })
</script>
<script type="text/javascript">
    $(".fn_map_modal").click(function(){
        getLocation_pos();
        setTimeout(function(){ resizingMap() }, 1000);
        /*$("input:radio[name='address_type']").each(function(e){
            this.checked = false;
        })*/
        $("#map_modal").addClass("left-active");
        $('.overlay').show();
    });

    function getLocation_pos(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(geoSuccess_place, showError_place);
        } else { 
            alert("Geolocation is not supported by this browser.");
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
        $('.error-toast').html(innerHTML).fadeIn();
        setTimeout(function () {
            $('.error-toast').fadeOut('slow');
        }, 4000);
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
                    if($("#a_addr").val()=='') {
                    $('#a_addr').val(results[0].formatted_address);
                    $('#a_lat').val(lat);
                    $('#a_lang').val(lng);
                    }
                    initialize();
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

    function initialize(){
        var map;
        var marker;
        var myLatlng = new google.maps.LatLng($('#a_lat').val(),$('#a_lang').val());
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();
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
            center: new google.maps.LatLng($('#a_lat').val(),$('#a_lang').val()),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: styles,
            gestureHandling: 'greedy'
        };
        var mapBox = document.getElementById("myaddrMap");
        map = new google.maps.Map(mapBox, mapOptions);
        mapBox.style.display="block";
        marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng($('#a_lat').val(),$('#a_lang').val()),
            draggable: true 
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
    }

    google.maps.event.addDomListener(window, "resize", resizingMap());

    var options = {
        componentRestrictions: {country: 'ca'},
        types: ['(regions)'],
    };
    var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'));
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place   = autocomplete.getPlace();
        var latitude  = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();
        $('#a_lat').val(latitude);
        $('#a_lang').val(longitude);
        $('#a_addr').val(place.formatted_address);
        console.log(place);
        initialize()
        address_check();
    });

    function resizeMap() {
        if(typeof map =="undefined") return;
        setTimeout( function(){resizingMap();} , 400);
    }

    function resizingMap() {
        if(typeof map =="undefined") return;
        var center = new google.maps.LatLng($('#lat').val(),$('#lang').val());
        google.maps.event.trigger(map, "resize");
        map.setCenter(center); 
    }

    function placeMarker(location) {
        if (marker == undefined){
            marker = new google.maps.Marker({
                position: location,
                map: map, 
                animation: google.maps.Animation.DROP,
            });
        } else {
            marker.setPosition(location);
        }
        map.setCenter(location);
    }

    function address_check(){
       var addr = $('#a_addr').val();
        var from = $('#addr').val();
        var lat = $('#a_lat').val();
        var lang = $('#a_lang').val();
        $(".go_to_step").removeAttr("disabled");
        $("#location").val(addr);
    }

    //save add new address 
   $(document).on('submit','#address_form',function(e){
        e.preventDefault();
        var add_id = $('#address_id').val();
        if(add_id!=''){ $type = 'PATCH'; } else { $type = 'POST'; }
        var url    = base_url+"useraddress";
        $.ajax({
            type : $type,
            url : url,
            data : $("#address_form").serialize(),
            success : function(res){
                var msg = JSON.parse(JSON.stringify(res)); 
                // $(".error-message-area").css("display","block");
                // $(".error-content").css("background","#9cda9c");
                // $(".error-msg").html("<b style='color:black'>"+msg.message+"</b>"); 
                toast(msg.message, 'Success!', 'success');
                setTimeout(function(){location.reload()}, 1000);
            },
            error : function(err){
                var msg = err.responseJSON.message; 
                $(".error-content").css("background","#ED4956");
                $(".error-message-area").find('.error-msg').text(msg);
                $(".error-message-area").show();
            }
        });
    });

   $(document).on('click','.delete_address',function(e){

    var address_id = $(this).data("id");
    var url = base_url+"deleteuseraddress";
    $.ajax(
    {
        url: url,
        type: 'POST',
        data: { "address_id": address_id,_method:"DELETE" },
        success : function(res){
            var msg = JSON.parse(JSON.stringify(res)); 
            // $(".error-message-area").css("display","block");
            // $(".error-content").css("background","#9cda9c");
            // $(".error-msg").html("<b style='color:black'>"+msg.message+"</b>"); 
            toast(msg.message, 'Success!', 'success');
            setTimeout(function(){location.reload()}, 1000);
        },
        error : function(err){
            var msg = err.responseJSON.message; 
            $(".error-content").css("background","#ED4956");
            $(".error-message-area").find('.error-msg').text(msg);
            $(".error-message-area").show();
        }
    });

});

   $(document).ready(function(){
    jQuery.validator.addMethod("valid_name", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z0-9.,'\s]*$/);
    }, 'Please enter a valid name.');

    $('#address_form').validate({
        onclick: false,
        rules: {
            building: {
                required: true,
                valid_name: true,
            },
            area: {
                required: true,
                valid_name: true,
            },
            landmark: {
                valid_name: true,
            },
            city: {
                required: true,
                valid_name: true,
            },
            state: {
                required: true,
                valid_name: true,
            },
        },
        messages: {
            building: {
                required:  'Enter your Building Name',
                valid_name: 'Enter valid Building address',      
            },
            area: {
                required: "Enter your Area Name",
                valid_name: 'Enter valid Area Address',
            },
            landmark: {
                valid_name: "Enter valid Landmark Name",
            },
            city: {
                required: "Enter your Town/city Name",
                valid_name: "Enter valid Town/City Name",
            },
            state : {
                required: "Enter your State Name",
                valid_name: "Enter valid State Name",
            },
        },
    });
});
   
</script>

@endsection