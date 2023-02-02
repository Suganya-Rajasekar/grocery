@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Reports</span> - Item wise report</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Item wise report</li>
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
$dwnload= ['pdf'=>'PDF'/*,'xls'=>'EXCEL'*/,'csv'=>'CSV'];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-body">
	
		<div class="pull-left">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2 ml-4">
					<div class="dropdown">	
						<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							@foreach($dwnload as $dn => $dv)
							<a class="dropdown-item" href="{!! url(getRoleName().'/itemexport/'.$dn.'?filter='.\Request::query('filter').'&chef='.\Request::query('chef')) !!}">{!! $dv !!}</a>
							@endforeach
						</div>
					</div>
				</div>
				@if(\Request::segment(1) == 'admin')
				<div class="form-group mb-2">
					<select class="form-control select-search" name="chef">
						<option value="" selected>Chefs</option>
						@foreach($chefs as $k => $value)
						<option value="{{ $value->id }}" @if(\Request::query('chef') != '' && \Request::query('chef') == $value->id) selected @endif>{{ $value->name }}</option>
						@endforeach
					</select>	
				</div>
				@endif
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="filter" placeholder="Search foodname " value="{!! \Request::query('filter') !!}">
				</div>
				<div class="form-group mb-2">
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('filter') != '' || \Request::query('chef') != '')
					<a href="{!! url(getRoleName().'/earning_report/item') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
				</div>
			</form>
		</div>
	</div>
	<div class="table-responsive-xl">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="ws-nowrap">#</th>
					@if(\Request::segment(1) == 'admin')
					<th class="ws-nowrap">Chef Name</th>
					@endif
					<th class="ws-nowrap">Food Name</th>
					<th class="ws-nowrap">Quantity</th>
					<th class="ws-nowrap">Revenue</th>
				</tr>
			</thead>
			<tbody>
				@if(count($resultData)>0)
				@foreach($resultData as $key => $value)
				<tr>
					<td>{!!($key+1)+$page!!}</td>
					@if(\Request::segment(1) == 'admin')
					<td>{!!isset($value->vendor->name) ? $value->vendor->name : '';!!}</td>
					@endif
					<td>{!!$value->name!!}</td>
					<td>{!!$value->order_quantity!!}</td>
					<td>Rs. {!!number_format($value->order_price,2,'.','')!!}</td>
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
<?php if($date!=""){$dt=explode(" - ",$date); $dt1=$dt[0];$dt2=$dt[1];}else{$dt1=date('m/d/Y', strtotime('-1 year'));$dt2=date('m/d/Y');} 	?> 
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
		var url = base_url+'/admin/earning_report/downloadfilemis'; 		
		url += '?date='+date+'&filter='+filter; 
		window.location.href = url;
		return false;   
	});
</script>
@endsection