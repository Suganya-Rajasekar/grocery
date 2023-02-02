@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Reports</span> - Customer report</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li class="active">Customer report</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages	= [];
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
@endphp
<div class="panel panel-flat">
	<div class="panel-body">
		<div class=" pull-right">
			{{-- @include('filter') --}}
			<form class="form-inline " method="GET">
				<div class="form-group mb-2">
					<div class="dropdown">	
						<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							@foreach($dwnload as $dn => $dv)
							<a class="dropdown-item" href="{!! url(getRoleName().'/customerearrexport/'.$dn.'?date='.\Request::query('date').'&onboard_date='.\Request::query('onboard_date').'&city='.\Request::query('city').'&customer_state='.\Request::query('customer_state').'&timeslot='.\Request::query('timeslot')) !!}">{!! $dv !!}</a>
							@endforeach
						</div>
					</div>
				</div>
				<div class="form-group mb-2">
					<label>Orderwise date :</label>
					<input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (isset($date) && $date!='') ? $date : '' !!}">
				</div>
				<div class="form-group mb-2">
					<label>Onboard date :</label>
					<input type="hidden" name="onboard_date" value="" id="onboard_date">
					<input type="text" class="form-control daterange-onboard" id="onboard_date" value="{!! (isset($onboard_date) && $onboard_date!='') ? $onboard_date : '' !!}">
				</div>
				<div class="form-group mb-2">
					<select name="customer_state" class="select-search">
						<option value="">Select customer</option>
						<option value="ordered" @if(\Request::query('customer_state') == 'ordered') selected @endif>Ordered</option>
						<option value="notordered" @if(\Request::query('customer_state') == 'notordered') selected @endif>Not Ordered</option>								
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
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="city" value="@if(\Request::query('city')){{\Request::query('city')}}@endif"  placeholder="Enter city for filter">
				</div>     
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('date') != '' ||\Request::query('onboard_date') != ''||\Request::query('customer_state') != ''||\Request::query('city') != '' || \Request::query('timeslot') != '') 
				<a href="{!! url('admin/earning_report/customer') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif

			</form> 
		</div>
	</div>
	<div class="table-responsive-xl">
		<table class="table table-bordered">
			<thead class="bg-slate-400">
				<tr>
					<th class="ws-nowrap">S.No</th>
					<th class="ws-nowrap">Name</th>
					@if($type == "customer")
					<th class="ws-nowrap">Email</th>
					<th class="ws-nowrap">Mobile</th>
					@endif
					<th class="ws-nowrap">City</th>
					<th class="ws-nowrap">Total orders</th>
					<th class="ws-nowrap">Amount spend</th>
					{{-- <th class="ws-nowrap">Cuisines</th> --}}
					{{-- <th class="ws-nowrap">City</th>--}}
					{{-- <th class="ws-nowrap">Mode of Payment</th> --}}
					{{-- <th class="ws-nowrap">Order sequence</th> --}}
					<th class="ws-nowrap">Date of onboarding</th>
				</tr>
			</thead>
			<tbody>
				<?php //echo "<pre>";print_r($resultData);exit; ?>
				@if(count($resultData) > 0)
				@foreach($resultData as $key => $value)
				<?php //echo "<pre>";print_r($value->orders);echo "</pre>";
				//$value->getUserDetails->orderscount?>
				<tr>
					<td>{!! ($key+1)+$page !!}</td>
					<td>{!! $value->name !!}</td>
					@if($type == "customer")
					<td>{!! $value->email !!}</td>
					<td>{!! (isset($value->mobile) && $value->mobile!==NULL) ? $value->mobile : '' !!}</td>
					@endif
					<td>{!! (isset($value->useraddress[0]->city) && $value->useraddress[0]->city != null) ? $value->useraddress[0]->city : '' !!}</td>
					{{-- <td>{!! (isset($value->usercity['city']) && $value->usercity['city']!==NULL) ? $value->usercity['city'] : '' !!}</td> --}}
					<td>Chef - @if($value->fooditemcount > 0) <a href="{{ url('admin/order/competed?user_id=').$value->id.'&type=menuitem' }}">{{ $value->fooditemcount }}</a>@else {!! $value->fooditemcount !!} @endif <br>
						Event - @if($value->eventcount > 0) <a href="{{ url('admin/order/competed?user_id=').$value->id.'&type=ticket' }}">{{ $value->eventcount }}</a>@else {!! $value->eventcount !!} @endif
					</td>
					<td>Chef - {!!number_format($value->fooditem_spend_amt,2,'.','')!!} <br>
						Event - {!!number_format($value->event_spend_amt,2,'.','')!!}
					</td>
					{{-- <td>@if($value->countorder > 0) <a href="{{ url('admin/order/competed?user_id=').$value->id }}">{{ $value->countorder }}</a>@else {!! $value->countorder !!} @endif</td> --}}
					{{-- <td>Cuisines</td> --}}
					{{-- <td>{!!number_format($value->spend_amt,2,'.','')!!}</td> --}}
					<td>{!! date('M d Y',strtotime($value->created_at)) !!}</td>
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
@endsection
@section('script')
<?php if($date!=""){$dt=explode(" - ",$date); $dt1=$dt[0];$dt2=$dt[1];}else{$dt1=date('m/d/Y', strtotime('-1 year'));$dt2=date('m/d/Y');} 	?> 
<?php if($onboard_date!=""){$dto=explode(" - ",$onboard_date); $dto1=$dto[0];$dto2=$dto[1];}else{$dto1=date('m/d/Y', strtotime('-1 year'));$dto2=date('m/d/Y');} 	?>
<script type="text/javascript">

	"use strict";
	$('.daterange-basic').daterangepicker({
		applyClass: 'bg-slate-600',
		cancelClass: 'btn-default',
		"startDate": "{!!$dt1!!}",
		"endDate": "{!!$dt2!!}",    	    
		"maxDate": new Date(),
	}, function (start_date,end_date) {
    $('#start_date').val(start_date.format('MM/DD/YYYY')+' - '+end_date.format('MM/DD/YYYY'));
});

	$('.daterange-onboard').daterangepicker({
		applyClass: 'bg-slate-600',
		cancelClass: 'btn-default',
		"startDate": "{!!$dto1!!}",
		"endDate": "{!!$dto2!!}",    	    
		"maxDate": new Date(),
	}, function (start_date,end_date) {
    $('#onboard_date').val(start_date.format('MM/DD/YYYY')+' - '+end_date.format('MM/DD/YYYY'));
});

	$(document).on('click','.downloadfile', function(){
		var date = $('#date').val();
		var filter = $('#filter').val();
		var url = base_url+'/admin/earning_report/downloadfilemis'; 		
		url += '?date='+date+'&filter='+filter; 
		window.location.href = url;
		return false;   
	});
</script>
@endsection