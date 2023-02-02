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
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">

	<div class="panel-body">
		<div class="pull-left">
			@if($access->edit)
	<div class="panel-heading">
		<a href="{!!url(getRoleName().'/customer/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-users"></i></b> {{ ('Add New') }}</button></a>
	</div>
	@endif
		</div>	

	<div class="panel-body">

		<div class="pull-left">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
					<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/customerexport/'.$dn.'?&search='.\Request::query('search').'&device='.\Request::query('device')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
					</div>
				<div class="form-group mb-2">
					<input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name or customerid or email or mobileno" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-2">
					<select name="logintype" class="select-search form-control">
						<option value="" selected >Login type</option>
						<option value="google" @if(\Request::query('logintype') == 'google') selected @endif>Google</option>
						<option value="facebook" @if(\Request::query('logintype') == 'facebook') selected @endif>Facebook</option>
						<option value="apple" @if(\Request::query('logintype') == 'apple') selected @endif>Apple</option>
					</select>
				</div>
				<div class="form-group mb-2">
					<select name="device" class="select-search form-control">
						<option value="" selected >Device</option>
						<option value="ios" @if(\Request::query('device') == 'ios') selected @endif>Ios</option>
						<option value="android" @if(\Request::query('device') == 'android') selected @endif>Android</option>
						<option value="web" @if(\Request::query('device') == 'web') selected @endif>Web</option>
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('search') != '' || \Request::query('date') != '' || \Request::query('logintype') != '' || \Request::query('device') != '')
					<a href="{!! url('admin/customer/all') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>
		
		</div>
	</div>
		<br><br>
	</div>
	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
					{{-- <th>#</th> --}}
					<th>Customer ID</th>
					<th>Name</th>
					<th>Mobile</th>
					<th>Orders Count</th>
					<th>Wallet</th>
					<th>Last Ordered Date & time</th>
					<th>Registered Date</th>
					<th>Address</th>
					<th>Email</th>
					<th>Avatar</th>
					<th>Device</th>
					<th>Email verified</th>
					@if($access->edit)
					<th class="text-center">Actions</th>
					@endif
				</tr>
			</thead>
			<tbody>
				
				@if(count($customer)>0)
				@foreach($customer as $key=>$value)
				{{-- <?php dd($value->last_order_date->created_at);?> --}}
				<tr>
					{{-- <td>{!!$key+1!!}</td> --}}
					<td>{!!$value->user_code!!}</td>
					<td>{!!$value->name!!}</td>
					<td>{!!$value->mobile!!}</td>
					<td><span class="label bg-blue-400 text-right order_count">{!!$value->orders_count!!}</span></td>
					<td>
						<div class="text-center">
							<span style="color: #ef606f">{{ $value->wallet }}</span>
						</div>
						<div class="mt-4 d-flex">
							<div class="add-wallet">
								<button class="btn btn-primary" data-id="{{ $value->id }}" data-toggle="modal" data-target="#user_wallet_{{ $value->id }}" style="width:100px"><i class="fa fa-inr"></i> Add</button>
							</div>
							<div class="wallet-report ml-4">
								<?php \Cache::put('customer_back_url',\Request::fullUrl()); ?> 
								<a href="{{ url('admin/customer/all/wallethistory/'.$value->id) }}">
									<button class="btn btn-secondary" style="width:110px"><i class="fa fa-info-circle" style="font-size: 19px;"></i> Report</button>
								</a>
							<div>
						</div>
					</td>
					<td>{{ !empty($value->last_order_date) ? date('M-d-Y h:i:s A',strtotime($value->last_order_date->created_at)) : '' }}</td>
					<td>{{ date('M-d-Y',strtotime($value->created_at)) }}</td>
					<td>{{ (isset($value->Useraddress[0]) && !empty($value->Useraddress[0])) ? $value->Useraddress[0]->address : 'Nil'}}</td>
					<td>{!!$value->email!!}</td>
						
					<td><img src="@if($value->avatar != ''){{url('/storage/app/public/avatar/'.$value->avatar)}}@else{{url('/storage/app/public/avatar.png')}}@endif" style="width: 40px;height: 40px;max-width: none;" class="img-circle" alt=""></td>
					<td>{{ $value->device }}</td>
					<td>
						
						@if($value->email_verified_at!='')
						<span class="label label-success">Verified</span>
						@else
						<span class="label label-danger ws-nowrap">Not-Verified</span>
						@endif
					</td>
					@if($access->edit)
					<td class="text-center">				
						<a href="{!!url(getRoleName().'/customer/'.$value->id.'/edit'.$url)!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
					</td>
					@endif
				</tr>
				<!---- Wallet modal-->
				<div class="modal fade" id="user_wallet_{{ $value->id }}">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title w-popup-title">Wallet Amount Credit</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<form action="{{ url('/admin/wallet_amt_push') }}" method="POST">
								@csrf
								<div class="modal-body">
									<input type="hidden" name="id" value="{{ $value->id }}">
									<div class="form-group">
										<label>Type</label>
										<select name="type" class="select-search form-control wallet-type">
											<option value="credit">Credit</option>
											<option value="debit">Debit</option>
										</select> 
									</div>
									<div class="form-group">
										<label>Amount</label>
										<input type="text" class="form-control" name="wallet_amt">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary wallet_amt_push">Credit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!---- Wallet modal end-->
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

<!-- Location modal -->
<div id="modal_location" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Add/Edit Location</h5>
			</div>

			<form action="{!!url('location/store')!!}" method="POST" enctype="Multipart/form-data">
				{{ csrf_field() }}{{ method_field('PUT') }}
				<div class="modal-body">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label>Location name</label>
								<input type="text" name="l_name" id="l_name" placeholder="Location" class="form-control" required="">
							</div>

							<div class="col-sm-6">
								<label>Status</label>
								<select name="l_status" id="l_status" class="select-search" required="">
									<option value="">select any one</option>
									<option value="0">Active</option>
									<option value="1">In-Active</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="l_id" id="l_id">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Location modal -->
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
	/*var startDate	= "{!! $dt1 !!}";
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
	});*/
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
	$(document).on('change','.wallet-type',function(){
		var type = $(this).val()
		$('.wallet_amt_push').text('Credit');
		$('.w-popup-title').text('Wallet Amount Credit');
		if(type == 'debit') {
			$('.wallet_amt_push').text('Debit');
			$('.w-popup-title').text('Wallet Amount Debit');
		}
	});
</script>
@endsection
