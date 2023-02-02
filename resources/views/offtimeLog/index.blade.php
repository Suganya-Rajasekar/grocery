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
		<form class="form-inline" method="GET">
				
				<div class="form-group">
					<select name="chef_id" class="select-search form-control">
						<option value="" selected >All chef</option>
						@if(count($chefs)>0)
						@foreach($chefs as $l_value)
						<option value="{!! $l_value->id !!}" @if(\Request::query('chef_id') != '' && ($l_value->id == \Request::query('chef_id')))  selected @endif>{!! $l_value->name !!}</option>
						@endforeach
						@endif
					</select>
				</div>
				
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
			</form>
		</div>
		<br><br>
	</div>

	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
		
		<thead>
			<tr>
				<th>#</th>
				<th>Vendor Id</th>
				<th>Type</th>
				<th>Off from</th>
				<th>Off to</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>

			@if(count($offtimelog)>0)
			@foreach($offtimelog as $key=>$value)
			@if($value->vendor_id!=0)
			@php
            $user = App\Models\User::where('id', $value->vendor_id)->first();
            @endphp
            @else 
            @php
            $user = '';
            @endphp
            @endif
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				<td>@if($user) {!!$user->name!!} @endif</td>
				<td>{!! $value->type!!}</td>
				<td>{!!$value->off_from!!}</td>
				<td>{!!$value->off_to!!}</td>
				<td class="text-center">
				<form action="{{ url(getRoleName().'/offtimelog/'.$value->id.'/delete') }}" method="POST">
				{{ csrf_field() }}				
				{{ method_field('DELETE') }}
				<button class="btn btn-danger" type="submit"><i class="fa fa-trash-o"></i></button>
				</form>
				</td>
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
