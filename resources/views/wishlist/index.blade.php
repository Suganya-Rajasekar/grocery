@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Customer</span> - Wishlist</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Wishlist</li>
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
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-body">
		
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
					<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/wishlistexport/'.$dn.'?user_id='.\Request::query('user_id').'&filter='.\Request::query('filter')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
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
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="filter" placeholder="Search title or description" value="{!! \Request::query('filter') !!}">
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('user_id') != '' || \Request::query('filter') != '')
				<a href="{!! url('admin/customer/wishlist') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>
		</div>
		
	</div>
	<div class="table-responsive-xl">
		<table class="table table-bordered">
		
		<thead>
			<tr>
				<th>#</th>
				<th>Customer</th>
				<th>Title</th>
				<th>Description</th>
				@if(getRoleName()=='admin' && $access->remove)
				<th>Action</th>
				@endif
			</tr>
		</thead>
		<tbody>

			@if(count($resultData)>0)
			@foreach($resultData as $key=>$value)
			<tr>
				<td>{!!($key+1)+$page!!}</td>
				<td>{!!isset($value->getUserDetails) ? $value->getUserDetails['name'] : ''!!}</td>
				<!-- <td>{!!$value->user_id!!}</td> -->
				<td>{!!$value->title!!}</td>
				<td>{!!$value->description!!}</td>
				@if(getRoleName()=='admin' && $access->remove)
				<td>
					<form action="{!!url(getRoleName().'/wishlist/'.$value->id.'/delete/')!!}" method="post" enctype="Multipart/form-data">
					<input name="_method" type="hidden" value="PUT">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-danger btn-xs"  data-id="{!!$value->id!!}" ><b><i class="fa fa-trash"></i></b></button>
					</form>
				</td>
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
<script type="text/javascript">
	"use strict";	
	
	
</script>
@endsection
