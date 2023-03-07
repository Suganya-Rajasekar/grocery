@extends('layouts.backend.app')
@section('page_header')
<?php
	$cpage  = request()->has('page') ? request()->get('page') : '';
	$from   = request()->has('from') ? request()->get('from') : '';
	$url    = '?from='.$from.'&page='.$cpage;
?>
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title d-flex align-items-center">
			<h5><span class="text-semibold">{!! $chef->name !!}</span> - @if($id == ''){!! 'Add new ' !!}@else {!! 'Edit Store' !!}@endif
			</h5>
			@if(getRoleName() == 'admin')
			<span class="type-switch-asw mx-2">
				{{-- <legend class="text-semibold">Type</legend> --}}
				<label class="switch mb-0">
					<input type="checkbox" name="type" class="typebtn" @if(isset($chef->type) && $chef->type == 'event') checked @endif>
					<div class="slider round">
						<span class="event">OPEN</span>
						<span class="chef">CLOSE</span>
					</div>
				</label>
			</span>
			@endif
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			@if(getRoleName() == 'admin')
			<li><a href="{!! url('admin/vendor'.$url) !!}">All vendors</a></li>
			@endif
			<li><a href="{!! \URL::to(getRoleName().'/vendor/'.$chef->id.'/store') !!}">All stores</a></li>
			<li class="active" id="breadcrumb_activetext">@if($id == ''){!! 'Add new store' !!}@else{!! 'Edit store' !!}@endif</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content">
	<div class="row">
		<div class="col-md-12">
			<form action="{!!url(getRoleName().'/availability')!!}" method="POST" class="form-horizontal operational_hour" enctype="Multipart/form-data" id="availform">
				{{ csrf_field() }}{{ method_field('POST') }}
				@if(getRoleName() == 'vendor')
				<div class="row">
					<div class="col-md-12">
						@endif
						<div class="panel panel-flat">
							<input type="hidden" name="id" value="{{ (isset($restaurant)) ? $restaurant->id : '' }}">
							<input type="hidden" name="v_id" id="v_id" value="{!! isset($chef->id) ? $chef->id : '' !!}">
							<input type="hidden"  class="form-control" name="area_code" id="area_code" value="" disabled="">
							<div class="panel-body business-info-asw">
								<fieldset>
									<legend class="text-semibold">
										<i class="fa fa-clock-o position-left"></i>
										Update your store Operational Hours
										<a class="control-arrow" data-toggle="collapse" data-target="#demo2">
											<i class="icon-circle-down2"></i>
										</a>
									</legend>
									<div class="collapse in" id="demo2">
										<div class="row">
											<div class="form-group col-md-12 mb-0">
												<label class="text-semibold">Timeslot - 1</label>
											</div>
											<div class="form-group col-md-3">
												<div class="input-group align-items-center">
													<span class="input-group-addon calendar-icon-asw">From :</span>
													<input type="text" name="opening_time" class="form-control pickatime" value="{!! isset($restaurant->opening_time) ? $restaurant->opening_time : old('opening_time') !!}"> 
												</div>
											</div>
											<div class="form-group col-md-3">
												<div class="input-group align-items-center">
													<span class="input-group-addon calendar-icon-asw">&nbsp;&nbsp; To :</span>
													<input type="text" name="closing_time" class="form-control pickatime" value="{!! isset($restaurant->closing_time) ? $restaurant->closing_time : old('closing_time') !!}"> 
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-12 mb-0">
												<label class="text-semibold">Timeslot - 2</label>
											</div>
											<div class="form-group col-md-3">
												<div class="input-group align-items-center">
													<span class="input-group-addon calendar-icon-asw">From :</span>
													<input type="text" name="opening_time2" class="form-control pickatime" value="{!! isset($restaurant->opening_time2) ? $restaurant->opening_time2 : old('opening_time2') !!}"> 
												</div>
											</div>
											<div class="form-group col-md-3">
												<div class="input-group align-items-center">
													<span class="input-group-addon calendar-icon-asw">&nbsp;&nbsp; To :</span>
													<input type="text" name="closing_time2" class="form-control pickatime" value="{!! isset($restaurant->closing_time2) ? $restaurant->closing_time2 : old('closing_time2') !!}"> 
												</div>
											</div>
											<div class="form-group col-md-6">
												<button type="submit" class="btn btn-primary font-monserret mr-2">Submit<i class="icon-arrow-right14 position-right"></i></button>
												<a href="{!! \Request::fullUrl() !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
											</div>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
						@if(getRoleName() == 'vendor')
					</div>
				</div>
				@endif
			</form>
			<form action="{!!url(getRoleName().'/working_days')!!}" method="POST" class="form-horizontal unavail_days" enctype="Multipart/form-data" id="availform">
				{{ csrf_field() }}{{ method_field('POST') }}
				@if(getRoleName() == 'vendor')
				<div class="row">
					<div class="col-md-12">
						@endif
						<div class="panel panel-flat">
							<input type="hidden" name="id" value="{{ (isset($restaurant)) ? $restaurant->id : '' }}">
							<input type="hidden" name="v_id" id="v_id" value="{!! isset($chef->id) ? $chef->id : '' !!}">
							<input type="hidden"  class="form-control" name="area_code" id="area_code" value="" disabled="">
							<div class="panel-body business-info-asw">
								<fieldset>
									<legend class="text-semibold">
										<i class="fa fa- clock-o position-left"></i>
										Schedule Off days in Advance
										<a class="control-arrow" data-toggle="collapse" data-target="#demo2">
											<i class="icon-circle-down2"></i>
										</a>
									</legend>
									<div class="collapse in" id="demo2">
										<div class="input-group align-items-center">
											<?php 
											$week   = array('sunday','monday' ,'tuesday','wednesday' ,'thursday' ,'friday' ,'saturday');
											$workdays   = (isset($restaurant)) ? json_decode($restaurant->working_days) : [];
											$dds =[];
											if($workdays){
												foreach($workdays as $kk=>$val){
													if($val==1){
														$dds[]= $kk;
													} 
												}
											}
											?>
											{{-- //foreach is more readable --}}
											@foreach ($week as $dayName)
											{{-- //Check if current week day is in real_days --}}
											<?php 
												$checked = '';
												if (in_array($dayName, $dds)) {
													$checked = ' checked="checked" ';
												}
											?>
											<div class='mr-3'>
												<label>
												<input type='checkbox' name='working_days[]' value="{!! $dayName !!}" {!! $checked !!}>
												{!! ucfirst($dayName) !!}<br>
												</label>
											</div>
											@endforeach
										</div>
										<div class="row">
											<div class="form-group col-md-6 mt-3">
												<button type="submit" class="btn btn-primary font-monserret mr-2">Submit<i class="icon-arrow-right14 position-right"></i></button>
												<a href="{!! \Request::fullUrl() !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
											</div>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
						@if(getRoleName() == 'vendor')
					</div>
				</div>
				@endif
			</form>
			<form action="{!!url(getRoleName().'/schedule')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data" id="scheduleform">
				{{ csrf_field() }}{{ method_field('POST') }}
				@if(getRoleName() == 'vendor') 
				<div class="row">
					<div class="col-md-12">
						@endif
						<div class="panel panel-flat">
							{{-- <div class="panel-heading">
								<h5 class="panel-title">Scheduling details</h5>
								<div class="heading-elements">
									<ul class="icons-list">
										<li><a data-action="collapse"></a></li>
									</ul>
								</div>
							</div> --}}
							<input type="hidden" name="id" value="{{ (isset($restaurant)) ? $restaurant->id : '' }}">
							<input type="hidden" name="v_id" id="v_id" value="{!! isset($chef->id) ? $chef->id : '' !!}">
							<input type="hidden"  class="form-control" name="area_code" id="area_code" value="" disabled="">
							<div class="panel-body business-info-asw">
								<fieldset>
									<legend class="text-semibold">
										<i class="fa fa- clock-o position-left"></i>
										Schedule Off time in Advance
										<a class="control-arrow" data-toggle="collapse" data-target="#demo1">
											<i class="icon-circle-down2"></i>
										</a>
									</legend>
									<div class="row collapse in show" id="demo1">
										<div class="form-group col-md-6">
											{{-- <label class="text-semibold">Off time in Advance :</label> --}}
											<div class="input-group align-items-center">
												<span class="input-group-addon calendar-icon-asw"><i class="icon-calendar22"></i></span>
												<input type="text" name="date_range" class="form-control daterange-time" value="@if((isset($restaurant)) && (!empty($restaurant->off_from) && !empty($restaurant->off_to))) {{ date('Y-m-d h:i A',strtotime($restaurant->off_from)).' - '.date('Y-m-d h:i A',strtotime($restaurant->off_to)) }} @else {!! date('Y-m-d').' - '.date('Y-m-d') !!} @endif"> 
											</div>
										</div>
										<div class="form-group col-md-6">
											<button type="submit" class="btn btn-primary font-monserret mr-2">Submit<i class="icon-arrow-right14 position-right"></i></button>
											<a href="{!! \Request::fullUrl() !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
											<a href="{!! url(getRoleName().'/offtimelog?chef_id='.$chef->id) !!}"  class="btn btn-info font-monserret ml-2"><i class="fa fa-toggle-off position-left"></i>Offtime Log</a>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
						@if(getRoleName() == 'vendor')
					</div>
				</div>
				@endif
			</form>
		</div>
	</div>
	<form action="{!!url(getRoleName().'/chef/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data" id="chef_form">
		{{ csrf_field() }}{{ method_field('PUT') }}
		<input autocomplete="false" name="hidden" type="text" class="hidden">
		<input type="hidden" name="c_id" id="c_id" value="{!! isset($chef->id) ? $chef->id : '0' !!}">
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Basic details</h5>
						<div class="heading-elements">
							<ul class="icons-list">
								<li><a data-action="collapse"></a></li>
								{{-- <li><a data-action="reload"></a></li> --}}
								{{-- <li><a data-action="close"></a></li> --}}
							</ul>
						</div>
					</div>
					<div class="panel-body">
						<fieldset>
							<div class="form-group">
								<label class="text-semibold">Name</label>
								<input type="text"  class="form-control" name="name" placeholder="Enter name" id="name" value="{!! isset($restaurant->name) ? $restaurant->name : old('name') !!}" required>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group" id="mobile_field">
										<label class="text-semibold">Mobile</label>
										<input type="number" class="form-control" name="phone" placeholder="Enter Mobile number" maxlength="10" id="phone" value="{!! isset($restaurant->phone) ? $restaurant->phone : old('phone') !!}" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group" id="mail_field">
										<label class="text-semibold">Email</label>
										<input type="email" class="form-control" name="email" placeholder="Enter email" autocomplete="off" id="email" value="{!! isset($restaurant->email) ? $restaurant->email : old('email') !!}" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="text-semibold">Select City</label>
								<select name="location" data-placeholder="Select city" class="select" required>
									<option value="" selected disabled>Select city</option>
									@if(count($city)>0)
									@foreach($city as $key => $value)
									@if(isset($restaurant->location))
									<option @if($value->id == $restaurant->location || old('location')) selected @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
									@else
									<option value="{!! $value->id !!}">{!! $value->name !!}</option>
									@endif
									@endforeach
									@endif
								</select>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="text-semibold">Tax</label>
										<input type="number" class="form-control" name="tax" placeholder="Enter tax percentage" id="tax" value="{!! isset($restaurant->tax) ? $restaurant->tax : old('tax') !!}">
									</div>
								</div>
								@if(\Auth::user()->role == 1 || \Auth::user()->role == 5)
								<div class="col-md-6">
									<div class="form-group">
										<label class="text-semibold">Commission</label>
										<input type="email" class="form-control" name="commission" placeholder="Enter commission" autocomplete="off" id="commission" value="{!! isset($restaurant->commission) ? $restaurant->commission : old('commission') !!}" required>
									</div>
								</div>
								@endif
							</div>
							{{-- <div class="form-group" id="cuisines">
								<label class="text-semibold">Cuisines</label>
								<select name="cuisine_type[]" class="select-search" multiple="multiple" data-placeholder="Select cuisines">
									<option disabled hidden value> Select cuisines </option>
									@if(count($cuisines)>0)
									@foreach($cuisines as $key=>$value)
									@if(isset($chef->cuisine_type))
									<option @if(in_array($value->id,explode(',',$chef->cuisine_type)))  selected="" @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
									@else
									<option value="{!!$value->id!!}">{!!$value->name!!}</option>
									@endif
									@endforeach
									@endif
								</select>
							</div> --}}
							<div class="form-group">
								<label class="text-semibold">Logo</label>
								<div class="media no-margin-top">
									@if(isset($restaurant->logo))
									<div class="media-left">
										<a href="{!! url($restaurant->logo) !!}" download="{{ substr(strrchr($restaurant->logo,'/'),1) }}"><img src="{!! url($restaurant->logo) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
									</div>
									@endif
									<div class="media-left" style="display: none;">
										<img src="" id="item-img-output" style="width: 58px; height: 58px; border-radius: 2px;" alt="Cropped Image">
									</div>
									<div class="media-body text-nowrap">
										<input type="file" class="file-styled" name="logo" id="imageid" accept="image/png, image/jpeg, image/jpg">
										<span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
									</div>
								</div>
							</div>
							@if(\Auth::user()->role == 1 || \Auth::user()->role == 5)
							{{-- <div class="col-md-12"> --}}
								{{-- <div class="panel-body"> --}}
									<div class="form-group">
										<label class="text-semibold">Status</label>
										<select required name="status" id="status" class="select-search">
											<option disabled hidden value="">select any one</option>
											<option @if(isset($chef->status) && $chef->status=='pending') selected="" @endif value="pending">Pending</option>
											<option @if(isset($chef->status) && $chef->status=='approved') selected="" @endif value="approved">Approved</option>
											<!-- <option @if(isset($chef->status) && $chef->status=='suspended') selected="" @endif value="suspended">Suspended</option> -->
											<option @if(isset($chef->status) && $chef->status=='cancelled') selected="" @endif value="cancelled">Rejected</option>
										</select>
									</div>
								{{-- </div> --}}
							{{-- </div> --}}
							<div @if((isset($chef->status) && $chef->status != 'cancelled' && $chef->status != 'suspended') || (!isset($chef->status))) style="display:none;" @endif id="reason">
								<input type="text" name="reason" id="inputreason" value="{!! (isset($chef->status) && ($chef->status == 'cancelled' || $chef->status == 'suspended')) ? $chef->reason : '' !!}" class="form-control" placeholder="Enter the reason for reject">
							</div>
							@endif
						</fieldset>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Other details</h5>
						<div class="heading-elements">
							<ul class="icons-list">
								<li><a data-action="collapse"></a></li>
								{{-- <li><a data-action="reload"></a></li> --}}
								{{-- <li><a data-action="close"></a></li> --}}
							</ul>
						</div>
					</div>
					<div class="panel-body">
						<fieldset>
							<div class="form-group">
								<label class="text-semibold">Complete Address</label>
								<input type="text"  class="form-control" name="adrs_line_1" placeholder="Enter complete address" id="adrs_line1" value="{!! isset($restaurant->adrs_line_1) ? $restaurant->adrs_line_1 : old('adrs_line_1') !!}" required="">
							</div>
							<div class="form-group" id="location">
								<label class="text-semibold">Location</label>
								<input type="text" id="txtPlaces" class="form-control" name="adrs_line_2" placeholder="Enter location" id="adrs_line1" value="{!! isset($restaurant->adrs_line_2) ? $restaurant->adrs_line_2 : old('adrs_line_2') !!}" required>
							</div>
							<div class="row" id="lat_lang">
								<div class="col-md-6">
									<div class="form-group">
										<label class="text-semibold">Latitude</label>
										<input type="text"  class="form-control" name="latitude" placeholder="Enter latitude" id="lat" value="{!! isset($restaurant->latitude) ? $restaurant->latitude : old('latitude') !!}" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="text-semibold">Longitude</label>
										<input type="text"  class="form-control" name="longitude" placeholder="Enter longitde" id="lang" value="{!! isset($restaurant->longitude) ? $restaurant->longitude : old('longitude') !!}" required>
									</div>
								</div>
							</div>
							<div id="myMap"></div>
						</fieldset>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="text-right">
					<a href="@if($id == ''){!! url('admin/chef') !!}@else{!! url('admin/chef/request') !!}@endif" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
					<button type="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- /content area -->
<style type="text/css">
	#myMap {max-width:100%;height: 200px;width: 920px;z-index:1;}
</style>
@endsection
@section('script')	
<script async defer src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAUqxCzqXHg1jeS_RUd4p4ukmVrcXckxYA&callback=initialize" type="text/javascript"></script>
<script type="text/javascript">
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
	$(".styled").uniform({
		radioClass: 'choice'
	});
	$('.pickatime').pickatime();
	$('.daterange-time').daterangepicker({
		timePicker: true,
		applyClass: 'bg-slate-600',
		cancelClass: 'btn-danger',
		locale: {
			format: 'YYYY-MM-DD h:mm a'
		}
	});
	$(document).on('change','.location',function(e){
		e.preventDefault();
		var location = $(this).val(); 
		var token = $("input[name='_token']").val();
		var url = base_url+"/admin/location_code";
		$.ajax({
			type : 'POST',
			url : url,
			data : {location:location,"_token": token},
			success : function(res){
				$('#code').val(res);
			},
			error : function(err){

			}
		});
	});
	$(document).on('change', '#status', function() {
		if ( this.value == 'cancelled') {
			$("#reason").show();
			$('#inputreason').attr('required',true);
		} else {
			$("#reason").hide();
			$('#inputreason').removeAttr('required',true);
		}
	});
	$(document).ready(function () {
		$("#phone").keypress(function (e) {
			var length = $(this).val().length;
			if(length > 9) {
				return false;
			} else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			} else if((length == 0) && (e.which == 48)) {
				return false;
			}
		});
	});
	$(document).on('change','.typebtn',function(){
	});
</script>
@endsection