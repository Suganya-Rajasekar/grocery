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
			<li class="active">Customer Complaints</li>
		</ul>
	</div>
	
</div>
<!-- /page header -->
<div class="pg-content">
	<div class="h-100 d-flex align-items-center justify-content-center text-center">
		<div class="complaints">
			<img src="{{ asset('assets/front/img/no data.png') }}">
			<p>No complaints reported keep it up!</p>
		</div>
	</div>
</div>
@endsection
@section('content')
@include('flash::message')

@endsection
