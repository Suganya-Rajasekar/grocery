@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Reports</span> - Order wise earning</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Order wise earning</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages=[];
$access 	= getUserModuleAccess(\Request::segment(3));
$cpage		= request()->has('page') ? request()->get('page') : '';
$from			= request()->has('from') ? request()->get('from') : '';
$url			= '?from='.$from.'&page='.$cpage;
$dwnload	= [/*'pdf'=>'PDF','xls'=>'EXCEL',*/'csv'=>'CSV'];
$taxcount 				= \Request::query('tax') ? count(\Request::query('tax')) : 0;
$del_charge_count = \Request::query('del_charge') ? count(\Request::query('del_charge')) : 0;
// $revenue_count		=	\Request::query('revenue') ? count(\Request::query('revenue')) : 0;
// $package_count    = \Request::query('pack_charge') ? count(\Request::query('pack_charge')) : 0;
$offercode_count  = \Request::query('offer_code') ? count(\Request::query('offer_code')) : 0;
$offer_count			= \Request::query('offer') ? count(\Request::query('offer')) : 0;
$paid_count				= \Request::query('customer_paid') ? count(\Request::query('customer_paid')) : 0;
$commission_count	= \Request::query('commission') ? count(\Request::query('commission')) : 0;
$commission_amt_count = \Request::query('commission_amt') ? count(\Request::query('commission_amt')) : 0;

$csvtax						= \Request::query('tax') ? implode('|',\Request::query('tax')) : '';
$csvdel_charge		= \Request::query('del_charge') ? implode('|',\Request::query('del_charge')) : ''; 
$csvrevenue				= \Request::query('revenue') ? implode('|',\Request::query('revenue')) : ''; 
$csvoffer					= \Request::query('offer') ? implode('|',\Request::query('offer')) : ''; 
$csvcommision			= \Request::query('commission') ? implode('|',\Request::query('commission')) : '';
$offercode        = \Request::query('offer_code') ? implode('|',\Request::query('offer_code')) : '';
$customerpaid     = \Request::query('customer_paid') ? implode('|',\Request::query('customer_paid')) : '';
$csvcommision_amt = \Request::query('commission_amt') ? implode('|',\Request::query('commission_amt')) : '';
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	
	<div class="panel-body">
	
		<div class="pull-right">
		  {{-- @include('filter') --}}
		  <form class="form-inline" method="GET">
		  	<div class="form-group mb-2">
		  		<div class="dropdown">	
		  			<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
		  			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
		  				<?php 
		  				if($type == 'chef') { 
		  					$vendor_id =\Auth::user()->id;	
		  				} else {
		  					$vendor_id = \Request::query('vendor_id');
		  				}
		  			?>
		  			@foreach($dwnload as $dn => $dv)
		  			<a class="dropdown-item" href="{!! url(getRoleName().'/orderearexport/'.$dn.'?filter='.\Request::query('filter').'&vendor_id='.$vendor_id.'&date='.\Request::query('date').'&location_id='.\Request::query('location_id').'&tax='.$csvtax.'&del_charge='.$csvdel_charge.'&revenue='.$csvrevenue.'&offer='.$csvoffer.'&commission='.$csvcommision.'&offercode='.$offercode.'&customer_paid='.$customerpaid.'&commission_amt='.$csvcommision_amt.'&delivery_place='.\Request::query('delivery_place').'&gst_number='.\Request::query('gst_number')) !!}">{!! $dv !!}</a>
		  			@endforeach
		  		</div>
		  	</div>
				</div>

		  	<div class="form-group mb-2 ml-4">
		  	    	<input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
			  	<!-- <div class="form-group mb-10">
					<select name="location_id" class="select-search form-control">
						<option value="" selected >All Locations</option>
						@if(count($location)>0)
						@foreach($location as $l_value)
						<option value="{!! $l_value->id !!}" @if(\Request::query('location_id') != '' && ($l_value->id == \Request::query('location_id')))  selected @endif>{!! $l_value->name !!}</option>
						@endforeach
						@endif
					</select>
				</div> -->
				@if(($type == 'order') || ($type == 'revenueorder'))
				<div class="form-group mb-2 ml-4">
					<select name="vendor_id" class="select-search form-control">
						<option value="" selected >All Vendors</option>
						@if(count($chefs)>0)
						@foreach($chefs as $vname)
						<option value="{!! $vname->id !!}" @if(\Request::query('vendor_id') != '' && ($vname->id == \Request::query('vendor_id')))  selected @endif>{!! $vname->name !!}</option>
						@endforeach
						@endif
					</select>
				</div>
				@endif
			<div class="form-group mb-2 flex-nowrap two-input d-flex ml-4">
				<label>Tax Amount</label>
				<select name="tax[]" class="select2 form-control">
					@if(count($Conditions)>0)
					@foreach($Conditions as $key => $corder_count)
					@if($taxcount < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($taxcount >= 2 && ($corder_count == \Request::query('tax')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="tax[]" value="@if(empty(!\Request::query('tax') )){!! \Request::query('tax')[1] !!}@endif">
			</div>
			{{-- <div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Delivery charges</label>
				<select name="del_charge[]" class="select2 form-control">
					@if(count($Conditions)>0)
					@foreach($Conditions as $key => $corder_count)
					@if($del_charge_count < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($del_charge_count >= 2 && ($corder_count == \Request::query('del_charge')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="del_charge[]" value="@if(empty(!\Request::query('del_charge'))){!! \Request::query('del_charge')[1] !!}@endif">
			</div> --}}
{{-- 			<div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Packaging charge</label>
				<select name="pack_charge[]" class="select2 form-control">
					@if(count($Conditions)>0)
					@foreach($Conditions as $key => $corder_count)
					@if($package_count < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($package_count >= 2 && ($corder_count == \Request::query('pack_charge')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="pack_charge[]" value="@if(!empty(\Request::query('pack_charge'))){!! \Request::query('pack_charge')[1] !!}@endif">
			</div> --}}
			{{-- <div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Revenue</label>
				<select name="revenue[]" class="select2 form-control">
					@if(count($Conditions)>0)
					@foreach($Conditions as $key => $corder_count)
					@if($revenue_count < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($revenue_count >= 2 && ($corder_count == \Request::query('revenue')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="revenue[]" value="@if(empty(!\Request::query('revenue'))){!! \Request::query('revenue')[1] !!}@endif">
			</div> --}}
{{-- 			<div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Offer(%)</label>
				<select name="offer[]" class="select2 form-control">
					@if(count($Conditions)>0)
					@foreach($Conditions as $key => $corder_count)
					@if($offer_count < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($offer_count >= 2 && ($corder_count == \Request::query('offer')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="offer[]" value="@if(empty(!\Request::query('offer'))){!! \Request::query('offer')[1] !!}@endif">
			</div> --}}
			<div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Offer Code</label>
				<select name="offer_code[]" class="select2 form-control">
					@if(count($Conditions)>0)
					@foreach($Conditions as $key => $corder_count)
					@if($offer_count < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($offercode_count >= 2 && ($corder_count == \Request::query('offer_code')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
					@endforeach 
					@endif
				</select>
				<input type="text" class="form-control w-100" name="offer_code[]" value="@if(!empty(\Request::query('offer_code'))){!! \Request::query('offer_code')[1] !!}@endif">
			</div>
			<div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Customer paid</label>
				<select name="customer_paid[]" class="select2 form-control">
					@if(count($Conditions)>0)
					@foreach($Conditions as $key => $corder_count)
					@if($paid_count < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($paid_count >= 2 && ($corder_count == \Request::query('customer_paid')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
					@endforeach 
					@endif
				</select>
				<input type="text" class="form-control w-100" name="customer_paid[]" value="@if(!empty(\Request::query('customer_paid'))){!! \Request::query('customer_paid')[1] !!}@endif">
			</div>
			{{-- <div class="form-group mb-2 flex-nowrap two-input d-flex">
				<label>Commission(%)</label>
				<select name="commission[]" class="select2 form-control">
					@if(count($Conditions)>0)
					@foreach($Conditions as $key => $corder_count)
					@if($commission_count < 2 && $key == "0") 
					<option value="" selected></option>
					@endif
					<option value="{!! $corder_count !!}" @if($commission_count >= 2 && ($corder_count == \Request::query('commission')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
					@endforeach
					@endif
				</select>
				<input type="text" class="form-control w-100" name="commission[]" value="@if(empty(!\Request::query('commission'))){!! \Request::query('commission')[1] !!}@endif">
			</div> --}}
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
				@if(($type == 'order') || ($type == 'revenueorder'))
				<div class="form-group mb-2">
					<label>Gst number</label>
					<input type="text" class="form-control" name="gst_number" placeholder="Enter gst number" value="{!! \Request::query('gst_number') !!}">
				</div>
				<div class="form-group mb-2">
						<label>Delivery place</label>
						<input type="text" class="form-control" name="delivery_place" placeholder="Enter delivery place" value="{{ \Request::query('delivery_place') }}">
				</div>
				<div class="form-group mb-2 flex-nowrap two-input d-flex">
					<label>Commission amount</label>
					<select name="commission_amt[]" class="select2 form-control">
						@if(count($Conditions)>0)
						@foreach($Conditions as $key => $corder_count)
						@if($commission_amt_count < 2 && $key == "0") 
						<option value="" selected></option>
						@endif
						<option value="{!! $corder_count !!}" @if($commission_amt_count >= 2 && ($corder_count == \Request::query('commission_amt')[0])){!! 'selected' !!}@endif>{!! $corder_count !!}</option>
						@endforeach
						@endif
					</select>
					<input type="text" class="form-control w-100" name="commission_amt[]" value="@if(!empty(\Request::query('commission_amt'))){!! \Request::query('commission_amt')[1] !!}@endif">
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="filter" placeholder="Search Name or id or commission amount or vendor peice or grand taoal" value="{!! \Request::query('filter') !!}">
				</div>
				@elseif($type == 'chef')
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="filter" placeholder="Search Id or net payable" value="{!! \Request::query('filter') !!}">
				</div>
				@endif
				<div class="form-group mb-2">
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('vendor_id') != '' || \Request::query('date') != '' || \Request::query('filter') != '' || \Request::query('tax') != '' || \Request::query('del_charge') || \Request::query('revenue') || \Request::query('offer') != '' || \Request::query('commission') != '' || \Request::query('pack_charge') != '' || \Request::query('offer_code') != '' || \Request::query('customer_paid') != '' || \Request::query('gst_number') || \Request::query('delivery_place') || \Request::query('commission_amt'))
					@if(($type == 'order') || ($type == 'revenueorder'))
					<a href="{!! url('admin/earning_report/order') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
					@elseif($type == 'chef')
					<a href="{!! url('vendor/earning_report/chef') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
					@endif
				@endif
		  	
		  </form>
		</div>
	</div>

	<div class="table-responsive-xl">
		<table class="table table-bordered">
			
			<thead>
				<tr>
					<th class="ws-nowrap">#</th>
					<th class="ws-nowrap">Order ID</th>
					<th class="ws-nowrap">Order Date</th>
					@if(($type=='order') || ($type == 'revenueorder'))
					<th class="ws-nowrap">Chef </th>
					@if($type == 'order')
					<th class="ws-nowrap">Gst Number</th>
					<th class="ws-nowrap">Customer</th>
					@endif
					<th class="ws-nowrap">Delivery Place</th>
					<th class="ws-nowrap">Total Charge</th>
					<th class="ws-nowrap">Tax Amount</th>
					<th class="ws-nowrap">Delivery charges</th>
					{{-- <th>Offer %</th> --}}
					<th>Packaging Charges</th>
					@if($type=='revenueorder')
					<th>Wallet amount used</th>
					@endif
					<th>Offer</th>
					<th>Offer Code</th>
					<th class="ws-nowrap">Customer Paid</th>
					@if($type=='order')
					<th class="ws-nowrap">Commision Amount</th>
					@elseif($type=='revenueorder')
					<th class="ws-nowrap">Net Revenue<br><small>(Commision)</small></th>
					<th class="ws-nowrap">Gross Revenue</th>
					<th class="ws-nowrap">Gross Revenue less tax</th>
					<th class="ws-nowrap">Net Net revenue<br><small>
					(Commission + package + delivery)</small></th>
					@endif
					{{-- <th class="ws-nowrap">Commission %</th>
					<th class="ws-nowrap">Chef Earnings</th>
					<th class="ws-nowrap">Commision Amount</th> --}}
					@endif
					@if($type=='chef')
					<th class="ws-nowrap">Total food Amount</th>
					<th class="ws-nowrap">Commission Amount</th>
					<th class="ws-nowrap">Your Earnings</th>				
					@endif
				</tr>
			</thead>
			<tbody>
				<?php //print_r($resultData);exit(); ?>
				@if(count($resultData)>0)
				@foreach($resultData as $key=>$value)
				<tr>
					<td>{!!($key+1)+$page!!}</td>
					<td>{!!$value->s_id!!}</td>
					<td class="date-report"><p>{!!date('d M Y',strtotime($value->created_at))!!}</p></td>
					@if(($type=='order') || ($type == 'revenueorder'))
					<td>{!!isset($value->getVendorDetails) ? $value->getVendorDetails['name'] : ''!!}</td>
					@if($type=='order')
					<td>{{ (isset($value->getVendorDetails) && !is_null($value->getVendorDetails->getDocument)) ? $value->getVendorDetails->getDocument->gst_no : ''}}</td>
					<td>{!!isset($value->getUserDetails) ? $value->getUserDetails['name'] : ''!!}</td>
					@endif
					<td>{{ isset($value->order->getUserAddress) ? $value->order->getUserAddress->city : '' }}</td>
					<td>{!!number_format($value->total_food_amount,2,'.','')!!}</td>
					<td>{!!number_format($value->tax_amount,2,'.','')!!}</td>
					<td>Rs. {!!number_format($value->del_charge,2,'.','')!!}</td>
					{{-- <td>{!!$value->offer_percentage!!}</td> --}}
					<td>Rs. {!!number_format($value->package_charge,2,'.','')!!}</td>
					@if($type=='revenueorder')
					<td>Rs. {!! $value->order->used_wallet_amount !!}</td>
					@endif
					<td>Rs. {!!number_format($value->offer_value,2,'.','')!!}</td>
					<td>{{ (isset($value->order->promo) && !is_null($value->order->promo)) ? $value->order->promo->promo_code : '-' }}</td>
					<td>Rs. {!!number_format($value->grand_total,2,'.','')!!}</td>
					@if($type == 'order')
					<td>Rs. {!!number_format($value->commission_amount,2,'.','')!!}</td>
					@elseif($type == 'revenueorder')
					<td>Rs. {!!number_format($value->commission_amount,2,'.','')!!}</td>
					<td>Rs. {!!number_format($value->total_food_amount,2,'.','')!!}</td>
					<td>Rs. {!!number_format(($value->total_food_amount - $value->tax_amount),2,'.','')!!}</td>
					<td>Rs. {!!number_format(($value->tax_amount + $value->del_charge + $value->package_charge),2,'.','')!!}</td>
					@endif
					{{-- <td>{!!$value->commission!!}</td>
					<td>Rs. {!!number_format($value->vendor_price,2,'.','')!!}</td>
					<td>Rs. {!!number_format($value->commission_amount,2,'.','')!!}</td> --}}
					@endif 
					@if($type=='chef')
					<td>Rs. {!!number_format($value->total_food_amount,2,'.','')!!}</td> 
					<td>Rs. {!!number_format($value->commission_amount,2,'.','')!!}</td> 
					<td>Rs. {!!number_format($value->vendor_price,2,'.','')!!}</td>
					@endif
					
				</tr>
				@endforeach
				@endif
			</tbody>

		</table>
	</div>
	<div class="panel-body">
		@include('footer')	
		
	</div>
	
	
</div>
<!-- /basic responsive configuration -->

@endsection
@section('script')
{{-- <?php if($date!=""){$dt=explode(" - ",$date); $dt1=$dt[0];$dt2=$dt[1];}else{$dt1=date('m/d/Y', strtotime('-1 year'));$dt2=date('m/d/Y');} 	?> 
<script type="text/javascript">
	"use strict";
  $('.daterange-basic').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        "startDate": "{!!$dt1!!}",
        "endDate": "{!!$dt2!!}",    	    
        "maxDate": new Date(),
    });
  
$(document).on('click','.downloadfile', function(){
		var date = $('#date').val();
		var filter = $('#filter').val();
		var type = $('#type').val();
		var url = base_url+'/admin/earning_report/downloadfile'; 		
		url += '?date='+date+'&filter='+filter+'&type='+type; 
		window.location.href = url;
		return false;   
	});
</script> --}}
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
	
	
</script>
@endsection