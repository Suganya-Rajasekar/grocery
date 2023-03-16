$('.pickatime').pickatime();
$(".styled").uniform({
	radioClass: 'choice',
	checkClass: 'choice'
});
$('.daterange-time').daterangepicker({
	timePicker: true,
	applyClass: 'bg-teal-400',
	cancelClass: 'btn-danger',
	locale: {
		format: 'YYYY-MM-DD h:mm a'
	}
});
/* Update store mode */
$(document).on('change','.typebtn',function() {
	var url	= base_url+"/admin/vendor/stores/mode";
	var mode= ($(this).prop('checked') == true) ? 'open' : 'close';
	var c_id= $('#c_id').val();
	var s_id= $('#s_id').val();
	if (c_id != 0 && s_id != 0) {
		$.ajax({
			type	: 'PUT',
			url		: url,
			data	: {v_id:c_id,s_id: s_id,mode: mode},
			success : function(res){
				toast(res.message,'Success!',res.status);
			},
			error : function(err){
			}
		});
	}
});
/* Status change select option */
$(document).on('change', '#status', function() {
	if ( this.value == 'cancelled') {
		$("#reason").show();
		$('#inputreason').attr('required',true);
	} else {
		$("#reason").hide();
		$('#inputreason').removeAttr('required',true);
	}
});

/* Map functionality Begin */
function initialize(){
	var map;
	var marker;
	var lat     = $('#lat').val();
	var lang    = $('#lang').val();
	var myLatlng= new google.maps.LatLng(lat,lang);
	var geocoder= new google.maps.Geocoder();
	const input = document.getElementById("txtPlaces");
	// var infowindow = new google.maps.InfoWindow();
	var mapOptions  = {
		zoom: 15,
		center: new google.maps.LatLng(lat,lang),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map     = new google.maps.Map(document.getElementById("myMap"), mapOptions);
	marker  = new google.maps.Marker({
		map: map,
		position: new google.maps.LatLng(lat,lang),
		draggable: true
	});

	const options = {
		// componentRestrictions: { country: "us" },
		fields: ["formatted_address", "geometry", "name"],
		origin: map.getCenter(),
		strictBounds: false,
		types: ["establishment"],
	};
	// map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
	const autocomplete  = new google.maps.places.Autocomplete(input, options);
	autocomplete.bindTo("bounds", map);
	const infowindow    = new google.maps.InfoWindow();
	const infowindowContent = document.getElementById("infowindow-content");
	infowindow.setContent(infowindowContent);
	autocomplete.addListener("place_changed", () => {
		infowindow.close();
		marker.setVisible(false);
		const place = autocomplete.getPlace();
		var latitude  = place.geometry.location.lat();
		var longitude = place.geometry.location.lng();
		$('#lat').val(latitude);
		$('#lang').val(longitude);

		if (!place.geometry || !place.geometry.location) {
			window.toast("No details available for input: '" + place.name + "'",'Error!', 'error');
			return;
		}

		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(17);
		}
		marker.setPosition(place.geometry.location);
		marker.setVisible(true);
		infowindowContent.children["place-name"].textContent = place.name;
		infowindowContent.children["place-address"].textContent =
		place.formatted_address;
		infowindow.open(map, marker);
	});

	geocoder.geocode({'latLng': myLatlng }, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				$('#txtPlaces').val(results[0].formatted_address);
				$('#lat').val(marker.getPosition().lat());
				$('#lang').val(marker.getPosition().lng());
				var components = results[0].address_components;
			}
		}
	});
	google.maps.event.addListener(marker, 'dragend', function() {
		geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					$('#txtPlaces').val(results[0].formatted_address);
					$('#lat').val(marker.getPosition().lat());
					$('#lang').val(marker.getPosition().lng());
					var components = results[0].address_components;
				}
			}
		});
	});
	google.maps.event.addListener(map, 'click', function (event) {
		$('#mlatitude').val(event.latLng.lat());
		$('#mlongitude').val(event.latLng.lng());
		placeMarker(event.latLng);
		geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					$('#txtPlaces').val(results[0].formatted_address);
					$('#lat').val(marker.getPosition().lat());
					$('#lang').val(marker.getPosition().lng());
					var components = results[0].address_components;
				}
			}
		});
	});
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
/* Map functionality End */

$(function () {
	$('body').on('click', '.list-group .list-group-item', function () {
		$(this).toggleClass('active');
	});
	$('.list-arrows button').click(function () {
		var $button = $(this), actives = '';
		if ($button.hasClass('move-left')) {
			actives = $('.list-right ul li.active');
			actives.clone().appendTo('.list-left ul');
			actives.remove();
		} else if ($button.hasClass('move-right')) {
			actives = $('.list-left ul li.active');
			actives.clone().appendTo('.list-right ul');
			// console.log(actives.attr('id'));
			actives.remove();
		}
		$('.list-right ul li').each(function(i) {
			input = jQuery(`<input type="hidden" name="categories[]" value="`+$(this).attr('id').split('_')[1]+`">`);
			$('.inputDiv').append(input);
		});
	});
	$('.dual-list .selector').click(function () {
		var $checkBox = $(this);
		if (!$checkBox.hasClass('selected')) {
			$checkBox.addClass('selected').closest('.well').find('ul li:not(.active)').addClass('active');
			$checkBox.children('i').removeClass('glyphicon-unchecked').addClass('glyphicon-check');
		} else {
			$checkBox.removeClass('selected').closest('.well').find('ul li.active').removeClass('active');
			$checkBox.children('i').removeClass('glyphicon-check').addClass('glyphicon-unchecked');
		}
	});
	$('[name="SearchDualList"]').keyup(function (e) {
		var code = e.keyCode || e.which;
		if (code == '9') return;
		if (code == '27') $(this).val(null);
		var $rows = $(this).closest('.dual-list').find('.list-group li');
		var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
		$rows.show().filter(function () {
			var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
			return !~text.indexOf(val);
		}).hide();
	});
});