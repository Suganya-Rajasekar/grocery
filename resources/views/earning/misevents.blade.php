@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Reports</span> - MIS - Events Report</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>MIS- Events Report</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages=[];
$access = getUserModuleAccess(\Request::segment(3));
$cpage	= request()->has('page') ? request()->get('page') : '';
$from	= request()->has('from') ? request()->get('from') : '';
$url	= '?from='.$from.'&page='.$cpage;
$dwnload= [/*'pdf'=>'PDF','xls'=>'EXCEL',*/'csv'=>'CSV'];
$taxcount 				= \Request::query('tax') ? count(\Request::query('tax')) : 0;
$offer_count			= \Request::query('offer') ? count(\Request::query('offer')) : 0;

$csvtax						= \Request::query('tax') ? implode('|',\Request::query('tax')) : '';
$csvoffer					= \Request::query('offer') ? implode('|',\Request::query('offer')) : ''; 
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="pull-right">
			{{-- @include('filter') --}}
			<form class="form-inline" method="GET">
				<div class="form-group mb-2 adm_knosh">
					<div class="dropdown">	
						<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							@foreach($dwnload as $dn => $dv)
							<a class="dropdown-item" href="{!! url(getRoleName().'/miseventexport/'.$dn.'?user_id='.\Request::query('user_id').'&filter='.\Request::query('filter').'&date='.\Request::query('date').'&chefid='.\Request::query('chefid').'&tax='.$csvtax.'&offer='.$csvoffer) !!}">{!! $dv !!}</a>
							@endforeach
						</div>
					</div>

				<div class="form-group mb-2 ml-4">
					<input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
				<!-- <div class="form-group mb-2">
					<select name="payment_type" class="select-search form-control">
						<option value="">Payment Type</option>
						<option value="cod" @if(\Request::query('payment_type') == 'cod') selected @endif >Cod</option> 
						<option value="online" @if(\Request::query('payment_type') == 'online') selected @endif>Online</option>
					</select>
				</div> -->
				<div class="form-group mb-2 flex-nowrap two-input d-flex ml-4">
					<label>Tax(%)</label>
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
				<div class="form-group mb-2 flex-nowrap two-input d-flex">
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
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="filter" placeholder="Search" value="{!! \Request::query('filter') !!}">
				</div>
				<div class="form-group mb-2">
					<select name="chefid" class="select-search">
						<option value="">Select Event to filter</option>
						@foreach($events as $key => $value)
						<option value="{{ $value->id }}" @if(\Request::query('chefid') != '' && \Request::query('chefid') == $value->id) selected @endif>{{ $value->name }}</option>
						@endforeach
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('filter') != '' || \Request::query('chefid') != '' || \Request::query('tax') != '' || \Request::query('del_charge') || \Request::query('revenue') || \Request::query('offer') != '' || \Request::query('commission') != '' || \Request::query('date') != '')
					<a href="{!! url('admin/earning_report/mis_events') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
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
					<th class="ws-nowrap">Payment type</th>
					<th class="ws-nowrap">Event</th>
					<th class="ws-nowrap">Order Value</th>
					<th class="ws-nowrap">Tax %</th>
					<th class="ws-nowrap">Tax Amount</th>
					<th>Offer </th>
					<th>Offer Value</th>
					<th class="ws-nowrap">Total Amount <div><small>(Order value + Tax Value - Offer Amount)</small></div></th>				
					<th class="ws-nowrap">Order Date</th>
					<th class="ws-nowrap">Customer</th>
					<th class="ws-nowrap">Customer mail</th>
					<th class="ws-nowrap">Customer mobile</th>
				</tr>
			</thead>
			<tbody>

				@if(count($resultData)>0)
				@foreach($resultData as $key=>$value)
				<tr>
					<td>{!!($key+1)+$page!!}</td>
					<td>{!!$value->s_id!!}</td>

					<td>{!!$value->order->payment_type!!}</td>

					<td>{!!isset($value->getVendorDetails) ? $value->getVendorDetails['name'] : ''!!}</td>		
					<td>Rs. {!!number_format($value->total_food_amount,2,'.','')!!}</td>
					<td>{!!$value->tax!!}</td>
					<td>{!!number_format($value->tax_amount,2,'.','')!!}</td>
					<td>@if($value->offer_type != 'none') {!! ($value->offer_type == 'percentage') ? $value->offer_percentage.' %' : 'Rs.'.$value->offer_amount !!} @endif</td>
					<td>Rs. {!!number_format($value->offer_value,2,'.','')!!}</td>
					<td>Rs. {!!number_format($value->grand_total,2,'.','')!!}</td>				
					<td class="date-misreport"><p>{!!date('d M Y',strtotime($value->created_at))!!}</p></td>
					<td>{!!isset($value->getUserDetails) ? $value->getUserDetails['name'] : ''!!}</td>
					<td>{!!isset($value->getUserDetails) ? $value->getUserDetails['email'] : ''!!}</td>
					<td>{!!isset($value->getUserDetails) ? $value->getUserDetails['mobile'] : ''!!}</td>
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
