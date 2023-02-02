@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title d-flex align-items-center justify-content-between">
			<h5><span class="text-semibold">Orders</span> - All orders</h5>
			<div class="filter-orders-asw-menu d-lg-none">
				<i class="fa fa-filter"></i>
			</div>
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
$dwnload= [/*'pdf'=>'PDF',*/'xls'=>'EXCEL','csv'=>'CSV'];
$urltype = \Request::segment(3);
$commission_count = \Request::query('commission_amount') ? count(\Request::query('commission_amount')) : 0;
$customer_count   = \Request::query('customer_order_count') ? count(\Request::query('customer_order_count')) : 0;
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
<div class="panel-body">
	<div class="filter-orders-asw-backdrop d-none"></div>
	<div class="filter-orders-asw">
		<div class="text-right py-3 filter-orders-asw-close d-lg-none">
			<i class="fa fa-times"></i>
		</div>
		<form class="form-inline orders-filter-asw" method="GET">
			<div class="form-group mb-2">
				<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					@php
					$customerOrdreCount1  = $customerOrdreCount2 = $commissionamount1 = $commissionamount2 = '';
					if( !empty(\Request::query('customer_order_count')) && (!empty(\Request::query('customer_order_count')[0]) && !empty(\Request::query('customer_order_count')[1]))) {
						$customerOrdreCount1 = (\Request::query('customer_order_count') !== null) ? \Request::query('customer_order_count')[0] : '';
						$customerOrdreCount2 = (\Request::query('customer_order_count') !== null) ? \Request::query('customer_order_count')[1] : '';
					}
					$commissionamount1 = '';
					$commissionamount2 = '';
					if( !empty(\Request::query('commission_amount')) && (!empty(\Request::query('commission_amount')[0]) && !empty(\Request::query('commission_amount')[1]))){
						$commissionamount1 = (\Request::query('commission_amount') !== null) ? urlencode(\Request::query('commission_amount')[0]) : '';
						$commissionamount2 = (\Request::query('commission_amount') !== null) ? \Request::query('commission_amount')[1] : '';
					}
					@endphp
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/orderexport/'.$dn.'?user_id='.\Request::query('user_id').'&commission_amount1='.$commissionamount1.'&commission_amount2='.$commissionamount2.'&customer_order_count1='.$customerOrdreCount1.'&customer_order_count2='.$customerOrdreCount2.'&payment_type='.\Request::query('payment_type').'&payment_status='.\Request::query('payment_status').'&search='.\Request::query('search').'&status='.\Request::query('status').'&urltype='.$urltype) !!}">{!! $dv !!}</a>
						@endforeach
					</div>
				</div>

			</div>
			{{-- <div class="form-group mb-2">
				<a class="btn bg-teal-400  btn-secondary" href="{!! url(getRoleName().'/ordercsvexport/csv?vendor_id='.\Request::query('user_id').'&urltype=all') !!}">CSV</a>
			</div> --}}
			<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search order id" value="{!! \Request::query('search') !!}">
			</div>
			<div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Commission Amount</label>
				<select name="commission_amount[]"  class="select2 form-control" >
				@if(count($aConditions)>0)
					@foreach($aConditions as $key => $com_amount)
					@if($commission_count < 2 && $key == "0")
					<option value="" selected ></option>
					@endif
					<option value="{!! $com_amount !!}" @if($commission_count >= 2 && ($com_amount == \Request::query('commission_amount')[0])) selected @endif>{!! $com_amount !!}       </option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="commission_amount[]" value="@if(isset(\Request::query('commission_amount')[1])){!! \Request::query('commission_amount')[1] !!}@endif">
			</div>
			<div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Count Order</label>
				<select name="customer_order_count[]" class="select2 form-control">
					@if(count($aConditions)>0)
					@foreach($aConditions as $key => $corder_count)
					@if($customer_count < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($customer_count >= 2 && ($corder_count == \Request::query('customer_order_count')[0])) selected @endif>{!! $corder_count !!}</option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="customer_order_count[]" value="@if(isset(Request::query('customer_order_count')[1])){!! \Request::query('customer_order_count')[1] !!} @endif">
			</div>
			<div class="form-group mb-2">
				<select name="user_id" class="select-search form-control">
					<option value="" selected >All Customers</option>
					@if(count($customerData)>0)
						@foreach($customerData as $key => $customername)
						<option value="{!! $customername->id !!}" @if(\Request::query('user_id') != '' && ($customername->id == \Request::query('user_id')))  selected @endif>{!! $customername->name !!}</option>
						@endforeach
						@endif
				</select>
			</div>
			<div class="form-group mb-10">
				<select name="payment_type" class="select-search form-control">
					<option value="">Payment Type</option>
					<option value="cod" @if(\Request::query('payment_type') == 'cod') selected @endif >Cod</option> 
					<option value="online" @if(\Request::query('payment_type') == 'online') selected @endif>Online</option>
				</select>
			</div>
			<div class="form-group mb-10">
				<select name="payment_status" class="select-search form-control">
					<option value="">Payment Status</option>
					<option value="paid" @if(\Request::query('payment_status') == 'paid') selected @endif>Paid</option>
					<option value="pending" @if(\Request::query('payment_status') == 'pending') selected @endif >Unpaid</option> 
				</select>
			</div>
			<div class="form-group mb-2" style="width: 200px;">
				<select name="timeslot" class="select-search">
					<option value="">Select timeslot</option>
					@foreach($timeslots as $key => $value)
					<option value="{{ $value->id }}" @if(\Request::query('timeslot') == $value->id) selected @endif>{{ $value->timeslot }}</option>
					@endforeach							
				</select>
			</div>	
			{{-- <div class="form-group mb-10">
				<select name="status" class="select-search form-control">
					<option value="">Status</option>
					<option value="pending" @if(\Request::query('status') == 'pending') selected @endif>Pending</option>
					<option value="accepted" @if(\Request::query('status') == 'accepted') selected @endif>Accepted</option>
					<option value="food_ready" @if(\Request::query('status') == 'food_ready') selected @endif>Food ready</option>
					<option value="on_d_way" @if(\Request::query('status') == 'on_d_way') selected @endif>On the way</option> 
					<option value="completed" @if(\Request::query('status') == 'completed') selected @endif>Delivered</option> 
				</select>
			</div> --}}
			 <div class="form-group mb-2">
			 	    <input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
			<button type="submit" class="btn btn-success ml-lg-2 mb-lg-2">Filter</button>
			@if( \Request::query('payment_type') != ''  || \Request::query('payment_status') != '' || \Request::query('user_id') != '' || \Request::query('m_id') != '' || \Request::query('commission_amount') != '' || \Request::query('customer_order_count') != '' || \Request::query('status') != '' || \Request::query('search') || Request::query('date') || Request::query('timeslot'))
			@if($type == 'all')
			<a href="{!! url('admin/order/all') !!}" class="btn btn-danger font-monserret ml-lg-2 mb-lg-2 mt-lg-0 mt-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif
			@if($type == 'competed')
			<a href="{!! url('admin/order/competed') !!}" class="btn btn-danger font-monserret ml-lg-2 mb-lg-2 mt-lg-0 mt-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif
			@if($type == 'rejected')
			<a href="{!! url('admin/order/rejected') !!}" class="btn btn-danger font-monserret ml-lg-2 mb-lg-2 mt-lg-0 mt-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif	
			@if($type == 'accepted')
			<a href="{!! url('admin/order/accepted') !!}" class="btn btn-danger font-monserret ml-lg-2 mb-lg-2 mt-lg-0 mt-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif	
			@if($type == 'pending')
			<a href="{!! url('admin/order/pending') !!}" class="btn btn-danger font-monserret ml-lg-2 mb-lg-2 mt-lg-0 mt-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif
			@if($type == 'today')
			<a href="{!! url('admin/order/today') !!}" class="btn btn-danger font-monserret ml-lg-2 mb-lg-2 mt-lg-0 mt-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif
			@if($type == 'cancelled')
			<a href="{!! url('admin/order/cancelled') !!}" class="btn btn-danger font-monserret ml-lg-2 mb-lg-2 mt-lg-0 mt-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif
			@if($type == 'nextweek' || $type == 'tomorrow')
			<a href="{!! url('admin/order/'.$type) !!}" class="btn btn-danger font-monserret ml-lg-2 mb-lg-2 mt-lg-0 mt-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif
			@endif
		</form>
	</div>
<!-- @include('filter') -->
{{-- </div> --}}
<div class="table-responsive">
	<table class="table datatable-responsive table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th>Order ID</th>
				<th>Total Sub Orders</th>
				<th>Customer</th>
				<th>Device</th>
				<th>Ordered Chefs</th>
				<th>Delivery Timeslots</th>
				<th>Customer order count</th>
				<th>Total Amount</th>
				<th>Address</th>
				<th>Ordered Timing</th>
				{{-- <th>Chef Earnings</th>
				<th>Commission Amount</th>
				<th>Payment Type</th>
				<th>Payment Status</th> --}}
				{{-- <th>Status</th> --}}
				<th>Action</th>
			</tr>
		</thead>
		<tbody>

			@if(count($resultData)>0)
			@foreach($resultData as $key=>$value)
			<tr>
				<td>{!!($key+1)+$page!!}</td>
				<td>{!!$value->Orderdetail[0]->m_id ?? ''!!}</td>
				<td>{!!$value->order_count!!}</td>
				<td>{!!isset($value->getUserDetails) ? $value->getUserDetails['name'] : ''!!} </td>
				<td>{{ isset($val->getUserDetails) ? $value->getUserDetails->device : '' }}</td>
				<td>{{ $value->chefnames }}</td>
				<td>@if($type != 'today'){{ $value->delivery_timeslots }} @else {{ $value->time_slot }} @endif</td>
				<td><span class="label bg-blue-400 text-right order_count">{!!$value->customer_order_count!!}</span></td>
				<td>{!!number_format($value->grand_total,2,'.','')!!}</td>
				<td class="address"><p>{!!isset($value->getUserAddress) ? $value->getUserAddress->display_address : ''!!}</p></td>
				<td>{{ date('Y-m-d h:i A',strtotime($value->created_at)) }}</td>
				{{-- <td>{!!number_format($value->vendor_price,2,'.','')!!}</td>
				<td>{!!number_format($value->commission_amount,2,'.','')!!}</td>
				
				
				<td>{!!$value->payment_type!!}</td>
				<td>{!!$value->payment_status!!}</td> 
				{!!$value->Orderdetail[0]->status!!}--}}
				{{-- <td>@if(isset($value->Orderdetail[0]->order_status)) {!!$value->Orderdetail[0]->order_status!!} @endif</td> --}}
				<td><a href="{!!url(getRoleName().'/order/'.$value->id.'/view')!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-eye"></i></b></button></a>
				{{-- @if($value->Orderdetail[0]->status == 'pending')
					<div class="btn-group">
						<a href="javascript::void();" style="text-decoration: none; color: #fff;" class="label bg-teal dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Order status</a>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="javascript::void();" data-orderid={!! $value->Orderdetail[0]->id !!} style="text-decoration: none;" class="orderstatus order_accept"><span class="status-mark bg-success position-left"></span> Accept </a></li>
							<li><a href="javascript::void();" data-orderid={!! $value->Orderdetail[0]->id !!} style="text-decoration: none;" class="orderreject order_reject" data-toggle="modal" data-target="#myModal"><span class="status-mark bg-danger position-left"></span> Reject </a></li>
						</ul>
					</div>
					@endif --}}
				</td>
				
			</tr>
			
			@endforeach
			@endif
		</tbody>
	</table>
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
            	<input type="hidden" name="status" id="status" value="rejected_admin"/>
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

</div>
	@include('footer')
</div>
<!-- /basic responsive configuration -->

@endsection
@section('script')
<script type="text/javascript">
	"use strict";
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	$(document).on('click','.orderstatus',function (argument) {
		var curcls	= $(this).attr('class').split(' ')[1];
		if (curcls == 'order_reject') { var status = 'rejected_admin';} else { var status = 'accepted_admin'; }
		var orderid	= $(this).attr('data-orderid');
		$.ajax({
			type : 'PUT',
			url : base_url+'/admin/orderstatuschange',
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
        url : base_url+'/admin/orderstatuschange',
        data : $("#rejectform").serialize(),
        success : function(res){
            $('.modal').modal('hide');
            var msg = JSON.parse(JSON.stringify(res)); 
            $(".error-message-area").css("display","block"); 
            toast(msg.message, 'Error!', 'error');
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
<script type="text/javascript">
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
	
	
</script>
@endsection
