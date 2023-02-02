@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Offtime Log</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Offtime Log</li>
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
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	
	<div class="panel-body">
		<div class="pull-left">
		
		</div>
		<div class="pull-right">
		
		</div>
		<br><br>
	</div>

	<table class="table datatable-responsive">
		
		<thead>
			<tr>
				<th>#</th>
				<th>Off from</th>
				<th>Off to</th>
				<!-- <th class="text-center">Actions</th> -->
			</tr>
		</thead>
		<tbody>

			@if(count($offtimelog)>0)
			@foreach($offtimelog as $key=>$value)
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				<td>{!!$value->off_from!!}</td>
				<td>{!!$value->off_to!!}</td>
				{{--
				<td class="text-center">				
					<a href="{!!url(getRoleName().'/offer/'.$value->id.'/edit'.$url)!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
				</td>
				--}}
			</tr>
			@endforeach
			@endif

		</tbody>

	</table>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$offtimelog->count()+$page}} of {{ $offtimelog->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$offtimelog->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->

@endsection
@section('script')
<script type="text/javascript">
	"use strict";	
	
	
</script>
@endsection
