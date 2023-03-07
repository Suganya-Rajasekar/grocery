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
			<h5>
				<span class="text-semibold">
					@if($id == '') {!! 'All vendors' !!} @else @if(getRoleName() == 'admin'){!! $chefs->name.' ' !!}@else{!! 'Profile' !!}@endif @endif
				</span> - @if($id == ''){!! 'Add new' !!}@else{!! 'Edit Profile' !!}@endif
			</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			@if(getRoleName() == 'admin')
			<li><a href="@if($id == ''){!! url('admin/vendor'.$url) !!}@endif">{!! 'All vendors' !!}</a></li>
			@endif
			<li class="active" id="breadcrumb_activetext">@if($id == ''){!! 'Add new vendor' !!}@else{!! 'Edit vendor' !!}@endif</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content">
	<form action="{!!url(getRoleName().'/vendor/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data" id="chef_form">
		{{ csrf_field() }}{{ method_field('PUT') }}
		<input autocomplete="false" name="hidden" type="text" class="hidden">
		<input type="hidden" name="c_id" id="c_id" value="{!! isset($chefs->id) ? $chefs->id : '0' !!}">
		 {{-- <div class="type-switch-asw mr-2">
				<legend class="text-semibold">Type</legend>
				<label class="switch mb-0">
					<input type="checkbox" name="type" class="typebtn" @if(isset($chefs->type) && $chefs->type == 'event') checked @endif>
					<div class="slider round">
						<span class="event">EVENT</span>
						<span class="chef">CHEF</span>
					</div>
				</label>
		</div> --}}
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
								<input type="text" required class="form-control" name="name" placeholder="Enter name" id="name" value="{!! isset($chefs->name) ? $chefs->name : old('name') !!}">
							</div>
							<div class="form-group" id="mail_field">
								<label class="text-semibold">Email</label>
								<input type="email" required class="form-control" name="email" placeholder="Enter email" autocomplete="off" id="email" value="{!! isset($chefs->email) ? $chefs->email : old('email') !!}">
							</div>
							<div class="form-group" id="mobile_field">
								<input type="hidden"  class="form-control" name="location_id" placeholder="Enter phone code EX: 91"  id="location_id" value="91"> {{-- {!! isset($chefs->location_id) ? $chefs->location_id : old('location_id') !!} --}}
								<label class="text-semibold">Mobile</label>
								<input type="number" required class="form-control" name="mobile" placeholder="Enter Mobile number" maxlength="10" id="mobile" value="{!!isset($chefs->mobile) ? $chefs->mobile : old('mobile') !!}" >
							</div>
							<div class="form-group">
								<label class="text-semibold">Avatar</label>
								<div class="media no-margin-top">
									@if(isset($chefs->avatar))
									<div class="media-left">
										<a href="{!! url($chefs->avatar) !!}" download="{{ substr(strrchr($chefs->avatar,'/'),1) }}"><img src="{!! url($chefs->avatar) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
									</div>
									@endif
									<div class="media-left" style="display: none;">
										<img src="" id="item-img-output" style="width: 58px; height: 58px; border-radius: 2px;" alt="Cropped Image">
									</div>
									<div class="media-body text-nowrap">
										<input type="file" class="file-styled" name="avatar" id="imageid" accept="image/png, image/jpeg">
										<span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
									</div>
								</div>
							</div>
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
							{{-- <div class="form-group">
								<label class="text-semibold">Select City</label>
								<select name="location" data-placeholder="Select city" class="select">
									<option value="" selected disabled>Select city</option>
									@if(count($city)>0)
									@foreach($city as $key=>$value)
									@if(isset($restaurant->location))
									<option @if($value->id == $restaurant->location || old('location')) selected @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
									@else
									<option value="{!!$value->id!!}">{!!$value->name!!}</option>
									@endif
									@endforeach
									@endif
								</select>
							</div> --}}
							<div class="form-group" id="password_field">
								<label class="text-semibold">Password</label>
								<input type="password" @if(!isset($chefs->id)) required @endif class="form-control" name="password" placeholder="Enter password" id="password" value="">
							</div>
							<div class="form-group" id="retypepassword_field">
								<label class="text-semibold">Retype password </label>
								<input type="password" @if(!isset($chefs->id)) required @endif class="form-control" name="confirm_password" placeholder="Enter confirm password" id="confirm_password" value="">
							</div>
							@if(isset($chefs->id))
							<div class="form-group" style="color: red;">
								<label class="text-semibold">Note :</label>
								Leave blank if you don't want to change current password
							</div>
							@endif
							@if(\Auth::user()->role == 1 || \Auth::user()->role == 5)
							<div class="form-group">
								<label class="text-semibold">Status</label>
								<select name="status" required id="status" class="select-search">
									<option disabled hidden value="">select any one</option>
									<option @if(isset($chefs->status) && $chefs->status=='pending') selected="" @endif value="pending">Pending</option>
									<option @if(isset($chefs->status) && $chefs->status=='approved') selected="" @endif value="approved">Approved</option>
									<!-- <option @if(isset($chefs->status) && $chefs->status=='suspended') selected="" @endif value="suspended">Suspended</option> -->
									<option @if(isset($chefs->status) && $chefs->status=='cancelled') selected="" @endif value="cancelled">Rejected</option>
								</select>
							</div>
							<div class="form-group" @if((isset($chefs->status) && $chefs->status != 'cancelled' && $chefs->status != 'suspended') || (!isset($chefs->status))) style="display:none;" @endif id="reason">
								<input type="text" name="reason" id="inputreason" value="{!! (isset($chefs->status) && ($chefs->status == 'cancelled' || $chefs->status == 'suspended')) ? $chefs->reason : '' !!}" class="form-control" placeholder="Enter the reason for reject">
							</div>
							@endif
						</fieldset>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="text-right">
					<a href="{!! url('admin/vendor') !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
					<button type="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- /content area -->
@endsection
@section('script')

<script type="text/javascript">
	$(".styled").uniform({
		radioClass: 'choice'
	});
	$(document).on('change','input[name=homeevent]',function (e) {
		if (this.value == 'no') {
			$('#certifiedDiv').show();
			$('#celebritydiv').show();
			$('#promoteddiv').show();

		} else {
			$('#certifiedDiv').hide();
			$('#celebritydiv').hide();
			$('#promoteddiv').hide();
		}
	});
	$(document).on('change','input[name=celebrity]',function (e) {
		if (this.value == 'no') {
			$('#certifiedDiv').show();
		} else {
			$('#certifiedDiv').hide();
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
});
	 $(document).ready(function(){
		var v_id = $('#c_id').val();
		if($('.typebtn').prop('checked') == true) {
			// $('#mail_field').hide();
			$('#individual_email').hide();
			$('#mobile_field').hide();
			$('#aadhar_field').hide();
			$('#password_field').hide();
			$('#retypepassword_field').hide();
			$('#exp_field').hide();
			$('#fssai_field').hide();
			$('#cuisines').hide();
			$('#email').val('');
			$('#password').val('');
			$('#mobile').val('');
			$('#chef_form').attr('novalidate',true);
			$('#home_event_div').hide();
			$('<input>').attr({
				type: 'hidden',
				name: 'type',
				id  : 'types',
				value: 'on'
			}).appendTo('#chef_form');
		}
		if(v_id != 0){
			$('.typebtn').attr('disabled',true);
		}
	 });

	 $(document).on('change','.typebtn',function(){
		if($(this).prop('checked') == true) {
			// $('#mail_field').hide();
			$('#individual_email').hide();
			$('#mobile_field').hide();
			$('#aadhar_field').hide();
			$('#password_field').hide();
			$('#retypepassword_field').hide();
			$('#exp_field').hide();
			$('#fssai_field').hide();
			$('#cuisines').hide();
			$('#email').val('');
			$('#password').val('');
			$('#mobile').val('');
			$('#home_event_div').hide();
			$('#breadcrumb_activetext').text('Add new Event');
			$('<input>').attr({
				type: 'hidden',
				name: 'type',
				id : 'types',
				value: 'on'
			}).appendTo('#chef_form');
		} else if($(this).prop('checked') == false) {
			$('#mail_field').show();
			$('#individual_email').show();
			$('#mobile_field').show();
			$('#aadhar_field').show();
			$('#password_field').show();
			$('#retypepassword_field').show();
			$('#exp_field').show();
			$('#fssai_field').show();
			$('#breadcrumb_activetext').text('Add new Chef');
			$('#types').remove();
			$('#home_event_div').show(); 
		}
	 });
</script>
@endsection