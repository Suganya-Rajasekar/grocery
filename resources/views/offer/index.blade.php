@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Promo</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>All Promo</li>
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
		<a href="{!!url(getRoleName().'/offer/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-gift"></i></b> {{ ('Add New') }}</button></a>
	</div>
	@endif
		</div>
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/offerexport/'.$dn.'?promo_type='.\Request::query('promo_type').'&date='.\Request::query('date').'&search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
					</div>
				<div class="form-group mb-2">
					<input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
				<div class="form-group mb-10">
					<select name="promo_type" class="select-search form-control">
						<option value="">All Type</option>
						<option value="amount" @if(\Request::query('promo_type') == 'amount') selected @endif >Amount</option> 
						<option value="percentage" @if(\Request::query('promo_type') == 'percentage') selected @endif>Percentage</option>

					</select>
				</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="1" @if(\Request::query('status') == '1') selected @endif >Active</option> 
						<option value="0" @if(\Request::query('status') == '0') selected @endif>In-Active</option>
					</select>
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name or promo code or promo offer" value="{!! \Request::query('search') !!}">
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('date') != '' || \Request::query('promo_type') != '' || \Request::query('status') != '' || \Request::query('search') != '' )
				<a href="{!! url('admin/offer') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>

		</div>
		<br><br>
	</div>

	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
		
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Code</th>
				<th>Image</th>
				<th>validity</th>
				<th>Type</th>
				<th>Promo offer</th>
				<th>Status</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>

			@if(count($offer)>0)
			@foreach($offer as $key=>$value)
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				<td>{!!$value->name!!}</td>
				<td>{!!$value->promo_code!!}</td>
				<td><img src="{!!$value->image!!}" style="width: 40px;height: 40px;max-width: none;" class="img-circle" alt=""></td>
				<td>{!!date('d/m/Y',strtotime($value->start_date)).' - '.date('d/m/Y',strtotime($value->end_date))!!}</td>
				<td>{!!$value->promo_type!!}</td>
				<td>{!!$value->offer!!}</td>
				<td>
					@if($value->status=='1')
					<span class="label label-success">Active</span>
					@else
					<span class="label label-warning">In-Active</span>					
					@endif
				</td>
				<td class="text-center">				
					<a href="{!!url(getRoleName().'/offer/'.$value->id.'/edit'.$url)!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
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
		Displaying {{$offer->count()+$page}} of {{ $offer->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$offer->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->

@endsection
@section('script')
<script type="text/javascript">
	"use strict";	
	
	<?php 
	if(\Request::query('date') != ""){
		$dt  = explode(" - ",\Request::query('date'));
	 	$dt1 = $dt[0];
		$dt2 = $dt[1];
	}else{
		$dt1 = date('Y-m-d');
		$dt2 = date('Y-m-d');
	} 	
	?>
	/* $('.daterange-basic').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        locale		: {
			format	: 'YYYY-MM-DD'
		},
    });*/
	
</script>
@endsection
