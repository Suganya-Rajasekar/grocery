@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Customer</span> - All Customer</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>All Customer</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages=[];
$access = getUserModuleAccess(\Request::segment(2));
$cpage	= request()->has('page') ? request()->get('page') : '';
$from	= request()->has('from') ? request()->get('from') : '';
$url	= '?from='.$from.'&page='.$cpage;
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">

	<div class="panel-body">
		<div class="pull-left">
		</div>
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
					<input type="text" class="form-control daterange-basic" id="date" name="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name or customerid or email or mobileno" value="{!! \Request::query('search') !!}">
				</div>
				
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('search') != '' || \Request::query('date') != ''  )
					<a href="{!! url('admin/chat/users') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>
		
		</div>
		<br><br>
	</div>
	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
					<th>Customer ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Mobile</th>
					<th>Avatar</th>
					<th>Email verified</th>
					@if($access->edit)
					<th class="text-center">Actions</th>
					@endif
				</tr>
			</thead>
			<tbody>
				@if(count($customer)>0)
				@foreach($customer as $key=>$value)
				<tr>
					<td>{!!$value->user_code!!}</td>
					<td>{!!$value->name!!}</td>
					<td>{!!$value->email!!}</td>
					<td>{!!$value->mobile!!}</td>
					<td><img src="@if($value->avatar != ''){{url('/storage/app/public/avatar/'.$value->avatar)}}@else{{url('/storage/app/public/avatar.png')}}@endif" style="width: 40px;height: 40px;max-width: none;" class="img-circle" alt=""></td>

					<td>
						
						@if($value->email_verified_at!='')
						<span class="label label-success">Verified</span>
						@else
						<span class="label label-danger ws-nowrap">Not-Verified</span>
						@endif
					</td>
					<td class="text-center">				
						<a href="{!!url('admin/chat/'.$value->id)!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-comment"></i></b></button></a>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$customer->count()+$page}} of {{ $customer->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$customer->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->

@endsection
<style type="text/css">
	.img-circle {
		width: 40px;height: 40px;max-width: none;border-radius: 50%;
	}
</style>
@section('script')
<script type="text/javascript">
	"use strict";	
	
	<?php
	if(\Request::query('date') != ""){
		$dt  = explode(" - ",\Request::query('date'));
		$dt1 = $dt[0];
		$dt2 = $dt[1];
	}else{
		$dt1 = date('Y-m-d', strtotime('-1 month'));
		$dt2 = date('Y-m-d');
	} 	
	?>
	var startDate	= "{!! $dt1 !!}";
	var endDate		= "{!! $dt2 !!}";
	$('.daterange-basic').daterangepicker({
		applyClass	: 'bg-slate-600',
		cancelClass	: 'btn-default',
		startDate	: startDate,
		endDate		: endDate,
		locale		: {
			format	: 'YYYY-MM-DD'
		},
		"maxDate"	: new Date(),
	});
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#l_id").val($(this).attr('data-id'));
		$("#l_name").val($(this).attr('data-name'));
		$('#l_status option[value="'+$(this).attr('data-name')+'"]').attr("selected", "selected");
		$("#modal_location").modal('show');
	})
	// Success notification
	$('#pnotify-success').on('click', function () {
		new PNotify({
			title: 'Success notice',
			text: 'Check me out! I\'m a notice.',
			addclass: 'bg-success'
		});
	});
</script>
@endsection
