@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Invoice</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Payout Invoice</li>
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
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
					<th>S.no</th>
					<th>Invoice Number</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				
				@if(count($invoices)>0)
				@foreach($invoices as $key=>$value)
				<tr>
					<td>{!!$key+1!!}</td>
					<td>{!!$value->number!!}</td>
					<td>{!!$value->start_date!!}</td>
					<td>{!!$value->end_date!!}</td>
					<td class="text-center">				
						<a href="{!!Url('uploads/user_document/'.$value->chef.'/'.$value->invoice) !!}" type="button" class="btn btn-outline-primary btn-xs" download><b>Download</b></a>
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
		Displaying {{$invoices->count()+$page}} of {{ $invoices->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$invoices->appends(Request::except('page'))->render()}}
	</div>
</div>
@endsection