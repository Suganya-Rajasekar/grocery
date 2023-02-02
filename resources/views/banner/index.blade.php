@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Banners</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Banners</li>
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
				<a href="{!!url(getRoleName().'/banner/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-image"></i></b> {{ ('Add New') }}</button></a>
			</div>
			@endif
		</div>
		<div class="pull-right">
			<form class="form-inline" method="GET">
				{{-- <div class="form-group mb-2">
					<div class="dropdown">	
						<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							@foreach($dwnload as $dn => $dv)
							<a class="dropdown-item" href="{!! url(getRoleName().'/bannerexport/'.$dn.'?date='.\Request::query('date').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
							@endforeach
						</div>
					</div>
				</div> --}}
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="active" @if(\Request::query('status') == 'active') selected @endif >Active</option> 
						<option value="inactive" @if(\Request::query('status') == 'inactive') selected @endif>In-Active</option>
						
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('date') != '' || \Request::query('status') != '' )
				<a href="{!! url('admin/banner') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
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
				<!-- <th>Banner ID</th> -->
				<th>Banner Image</th>
				<th>Date</th>
				<th>Status</th>
				@if(getRoleName()=='admin' && ( $access->edit || $access->remove ))
				<th class="text-center">Actions</th>
				@endif
			</tr>
		</thead>
		<tbody>

			@if(count($banner)>0)
			@foreach($banner as $key=>$value)
			<tr>
				<td>{!!$key+1!!}</td>
				<!-- <td>{!!$value->id!!}</td> -->
				<td><img src="{!!$value->image_src!!}" style="height: 40px;max-width: none;" class="img-circle" alt=""></td>
				<td class="date-banner"><p>{!!date('d/m/Y',strtotime($value->start_date)).' - '.date('d/m/Y',strtotime($value->end_date))!!}</p></td>
				<td>
					@if($value->status=='active')
					<span class="label label-success">Active</span>
					@else
					<span class="label label-warning ws-nowrap">In-Active</span>					
					@endif
				</td>
				@if( $access->edit || $access->remove )
				<td>	
					<div class="text-center d-flex">
						@if( $access->edit )			
						<a href="{!!url(getRoleName().'/banner/'.$value->id.'/edit'.$url)!!}"><button type="button" class="btn btn-success mr-2 btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
						@endif
						@if( $access->remove )
						<form action="{!!url(getRoleName().'/banner/'.$value->id)!!}" method="post" enctype="Multipart/form-data">
						<input name="_method" type="hidden" value="DELETE">
						{{ csrf_field() }}
						<button type="submit" class="btn btn-danger btn-xs"  data-id="{!!$value->id!!}" ><b><i class="fa fa-trash"></i></b></button>
						</form>
						@endif
					</div>
				</td>
				@endif
			</tr>
			@endforeach
			@endif

		</tbody>

	</table>
	</div>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$banner->count()+$page}} of {{ $banner->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$banner->appends(Request::except('page'))->render()}}
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
	
	
</script>
@endsection
