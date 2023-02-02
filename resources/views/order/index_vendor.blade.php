@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Orders</span> - All orders</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			@if($type != 'all')
			<li><a href="{!! url(getRoleName().'/order/all') !!}">All orders</a></li>
			@endif
			<li class="active"><span style="text-transform: capitalize">@if($type == 'competed'){!! 'Completed' !!}@else{!! $type !!}@endif</span> orders</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages=[];
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
$urltype = \Request::segment(3);
@endphp
<!-- Basic responsive configuration -->
<input type="hidden" value="{{ url('public/new_order_notification.mp3') }}" id="audio">
<button type="button" class="play" onclick="callfunc()" style="display:none;">play</button>
<div class="panel panel-flat">
	 <div class="panel-body pull-right">
		{{-- @include('filter')--}}
		<form class="form-inline" method="GET">
			<div class="form-group mb-2">
				<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					@php
					$commissionamount1 = (\Request::query('commission_amount') !== null) ? urlencode(\Request::query('commission_amount')[0]) : '';
					$commissionamount2 = (\Request::query('commission_amount') !== null) ? \Request::query('commission_amount')[1] : '';
					@endphp
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/vendororderexport/'.$dn.'?user_id='.\Request::query('user_id').'&commission_amount1='.$commissionamount1.'&commission_amount2='.$commissionamount2.'&search='.\Request::query('search').'&totamt='.\Request::query('totamt').'&status='.\Request::query('status').'&urltype='.$urltype) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
					</div>
					
			</div>
			<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search order id" value="{!! \Request::query('search') !!}">
			</div>
			<div class="form-group mb-2">
				<input type="text" class="form-control" name="totamt" placeholder="Search total amount" value="{!! \Request::query('totamt') !!}">
			</div>
			<div class="form-group mb-2 flex-nowrap two-input d-flex">
				<select name="commission_amount[]"  class="select2 form-control w-100" >
				@if(count($aConditions)>0)
					@foreach($aConditions as $key => $com_amount)
					@if(\Request::query('commission_amount') == '' && $key == "0") 
					<option value="" selected >Commission Amount</option>
					@endif
					<option value="{!! $com_amount !!}" @if(\Request::query('commission_amount') != '' && ($com_amount == \Request::query('commission_amount')[0])) selected @endif>{!! $com_amount !!}</option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="commission_amount[]" value="@if(\Request::query('commission_amount') != '') {!! \Request::query('commission_amount')[1] !!} @endif">
			</div>
			<div class="form-group mb-10">
				<select name="user_id" class="select-search form-control">
					<option value="" selected >All Customers</option>
					@if(count($customerData)>0)
					@foreach($customerData as $cname)
					<option value="{!! $cname->id !!}" @if(\Request::query('user_id') != '' && ($cname->id == \Request::query('user_id')))  selected @endif>{!! $cname->name !!}</option>
					@endforeach
					@endif
				</select>
			</div> 
			@if (Request::is('vendor/order/all') || Request::is('vendor/order/today'))
			<div class="form-group mb-10">
				<select name="status" class="select-search form-control">
					<option value="">All Status</option>
					<option value="pending" @if(\Request::query('status') == 'pending') selected @endif>Pending</option>
					<option value="accepted" @if(\Request::query('status') == 'accepted') selected @endif>Accepted</option>
					<option value="completed" @if(\Request::query('status') == 'completed') selected @endif>Completed</option> 
				</select>
			</div>
			@endif
			<div class="form-group mb-2">
				<input type="hidden" name="date" value="" id="start_date">
				<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
			</div>
			<div class="form-group mb-10">
				<select name="time" class="select-search form-control">
					<option value="" selected >Time</option>
					@if(count($time)>0)
					@foreach($time as $value)
					<option value="{!! $value->id !!}" @if(\Request::query('time') != '' && ($value->id == \Request::query('time')))  selected @endif>{!! $value->name !!}</option>
					@endforeach
					@endif
				</select>
			</div> 
			<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
			@if(\Request::query('user_id') != '' || \Request::query('search') != '' || \Request::query('totamt') != '' || \Request::query('commission_amount') != '' || \Request::query('status') != '' || \Request::query('date') != '' || \Request::query('time') != '')
			<a href="{!! url('vendor/order/all') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif
		</form>
		</div> 
	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
		<thead>
			<tr>
				<th width="150">Order ID</th>
				<th>Customer</th>
				<th>Commission Amount</th>
				<th>Total Amount</th>
				<th>Date</th>
				<th>Time</th>
				{{-- <th>Address</th> --}}
				<th>Status</th>
				<th width="200">Action</th>
			</tr>
		</thead>
		<tbody>
			@if(count($resultData)>0)
			@foreach($resultData as $key=>$value)
			<tr>
				<td>#{!!$value->s_id!!}</td>
				<td>{!!isset($value->order->getUserDetails) ? $value->order->getUserDetails['name'] : ''!!}</td>
				<td class="align-right">{!!number_format($value->commission_amount,2,'.','')!!}</td>
				<td class="align-right">{!!number_format($value->vendor_price,2,'.','')!!}</td>
				<td class="align-right">{!! $value->date !!}</td>
				<td class="align-right">{{ $value->time_chef }}</td>
				{{-- <td>{!!isset($value->order->getUserAddress) ? $value->order->getUserAddress['address'] : ''!!}</td> --}}
				<td>{!!$value->status!!}</td>
				<td>
					<a href="{!!url(getRoleName().'/order/'.$value->id.'/view')!!}" class="label label-primary mr-2" data-popup="tooltip" title="View order" data-original-title="View"><i class="icon-eye"></i></a>
					<div class="btn-group">
							@if($value->status == 'pending')
							<a href="javascript::void();" style="text-decoration: none; color: #fff;" class="label bg-teal dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Order status</a>
							<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="javascript::void();" data-orderid={!! $value->id !!} style="text-decoration: none;" class="orderstatus order_accept"><span class="status-mark bg-success position-left"></span> Accept </a></li>
							<li><a href="javascript::void();" data-orderid={!! $value->id !!} style="text-decoration: none;" class="orderreject order_reject" data-toggle="modal" data-target="#myModal"><span class="status-mark bg-danger position-left"></span> Reject </a></li>
							</ul>
							@elseif($value->status == 'accepted_res' || $value->status == 'accepted_admin')
							<a href="javascript::void();" style="text-decoration: none; color: #fff;" class="label bg-teal dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Order status</a>
							<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderstatus order_food_ready"><span class="status-mark bg-success position-left"></span> Food ready </a></li>
							<li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderreject order_reject" data-toggle="modal" data-target="#myModal"><span class="status-mark bg-danger position-left"></span> Reject </a></li>
							</ul>
							@else
							{{-- <li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderreject order_reject" data-toggle="modal" data-target="#myModal"><span class="status-mark bg-danger position-left"></span> Reject </a></li> --}}
							@endif
						
					</div>
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
</div>
		<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<h4 class="modal-title" id="myModalLabel">Reject Reason</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form id="rejectform">
                            <!-- @csrf -->
            <div class="modal-body">
            	<input type="hidden" name="order_id" id="order_id" value=""/>
            	<input type="hidden" name="status" id="status" value="rejected_res"/>
                <textarea name="reason" required="" rows="5" cols="45"></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
	<div class="panel-body">
		@include('footer')
	</div>
</div>
<!-- /basic responsive configuration -->
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.min.js"></script>
<script type="text/javascript">
	"use strict";
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	var socket = io('https://knosh.in:8891');
	socket.on('Alert',function(message){
		$('.play').trigger('click')
	});
	$('.play').click(function(){
		callfunc();
	});

	function callfunc(){
		var audiourl = $('#audio').val();
		var audio  = new Audio();
		audio.src = audiourl;
		audio.load();
		audio.play();
	}

	$(document).on('click','.orderstatus',function (argument) {
		var curcls	= $(this).attr('class').split(' ')[1];
		if (curcls == 'order_reject') { var status = 'rejected_res';} else if(curcls == 'order_food_ready') { var status = 'food_ready'; } else { var status = 'accepted_res'; }
		var orderid	= $(this).attr('data-orderid');
		$.ajax({
			type : 'PUT',
			url : base_url+'/vendor/orderstatuschange',
			data : {status : status, order_id : orderid},
			success:function(data){
				$('.modal').modal('hide');
				var msg = JSON.parse(JSON.stringify(data)); 
				$(".error-message-area").css("display","block");
				toast(msg.message, 'Success!', 'success'); 
				setTimeout(function(){location.reload()}, 1000);
			},
			error : function(err){ 
				$('.modal').modal('hide');
				var msg = err.responseJSON.message; 
				$(".error-message-area").find('.error-msg').text(msg);
				toast(msg.message, 'Error!', 'error');

			}
		});
	});

	$(document).on("click", ".orderreject", function () {
		var orderid	= $(this).attr('data-orderid');
		$(".modal-body #order_id").val(orderid);
	});
//save reason
$(document).on('submit','#rejectform',function(e){
	e.preventDefault();
	$.ajax({
		type : 'PUT',
		url : base_url+'/vendor/orderstatuschange',
		data : $("#rejectform").serialize(),
		success : function(res){ 
			$('.modal').modal('hide');
			var msg = JSON.parse(JSON.stringify(res)); 
			$(".error-message-area").css("display","block");
			toast(msg.message, 'Success!', 'success'); 
			setTimeout(function(){location.reload()}, 1000);
		},
		error : function(err){
			$('.modal').modal('hide');
			var msg = err.responseJSON.message; 
			$(".error-message-area").find('.error-msg').text(msg);
			toast(msg.message, 'Error!', 'error');

		}
	});
});
</script>
@endsection
