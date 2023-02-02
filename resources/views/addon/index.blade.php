@extends('layouts.backend.app')
@section('page_header')
<?php
	$chef	= getUserData(\Request::segment(3));
	$cpage	= request()->has('page') ? request()->get('page') : '';
	$ipage	= request()->has('innerpage') ? request()->get('innerpage') : '';
	$from	= request()->has('from') ? request()->get('from') : '';
	$url	= '?from='.$from.'&page='.$cpage;
	$url2	= $url.'&innerpage='.$ipage;
	$i		= ($innerpage > 0 && $innerpage != 1) ? ($innerpage - 1) * ($pCount + 1) : 1 ;
?>
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h5><span class="text-semibold">@if(getRoleName() == 'admin'){{-- $chef->name --}}@else{!! 'Addons' !!}@endif</span> - {!! ucfirst($type) !!}s list</h5>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            @if(getRoleName() == 'admin')
            <li><a href="{!! url('admin/chef'.$url) !!}">All chefs</a></li>
            @endif
            <li class="active">Manage {!! ucfirst($type) !!}s</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages=[];
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="pull-left">
		<div class="panel-heading">
			<a href="{!!url(getRoleName().'/chef/'.$v_id.'/'.$type.'/create'.$url)!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="icon-plus3"></i></b> {{ ('Add New') }}</button></a>
		</div>
	</div>
	<div class="pull-right">
		<form class="form-inline" method="GET">
				<input type="hidden" name="page" value="{{ $cpage }}">
				<div class="form-group mb-2">
					<div class="dropdown">	
						<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							@foreach($dwnload as $dn => $dv)
							<a class="dropdown-item" href="@if(getRoleName()=='admin'){!! url(getRoleName().'/chef/'.$v_id.'/addonexport/'.$dn.'?search='.\Request::query('search').'&status='.\Request::query('status')) !!}@elseif(getRoleName()=='vendor'){!! url(getRoleName().'/restaurants/'.$v_id.'/addonexport/'.$dn.'?search='.\Request::query('search').'&status='.\Request::query('status')) !!}@endif">{!! $dv !!}</a>
							@endforeach
						</div>
					</div>
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search Name or price" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="active" @if(\Request::query('status') == 'active') selected @endif>Active</option>
						<option value="inactive" @if(\Request::query('status') == 'inactive') selected @endif>Inactive</option>
						<option value="declined" @if(\Request::query('status') == 'declined') selected @endif>Declined</option>
						<option value="p_inactive" @if(\Request::query('status') == 'p_inactive') selected @endif>In-Active by partner</option> 
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('search') != '' || \Request::query('status') != '' )
				<a href="@if(\Auth::user()->role == 1 || \Auth::user()->role == 5) {!! url('admin/chef/'.$v_id.'/addon?from=web&page='.$cpage) !!} @else {!! url('vendor/common/addon') !!} @endif" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>

		</div>
	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
		<thead>
			<tr>
				<th>#</th>
				{{-- <th>{!!ucfirst($type)!!} ID</th> --}}
				<th>Name</th>
				@if($type=='addon') <th>Price</th> @endif
				<th>Status</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			@if(count($addon)>0)
			@foreach($addon as $key=>$value)
			<tr>
				<td>{!! $i++ !!}</td>
				{{-- <td>{!!$value->id!!}</td> --}}
				<td>{!!$value->name!!}</td>
				@if($type=='addon') <td>{!!$value->price!!}</td> @endif
				<td>
					@if($value->status=='active')
					<span class="label label-success">Active</span>
					@elseif($value->status=='inactive')
					<span class="label label-warning">In-Active</span>
					@elseif($value->status=='declined')
					<span class="label label-danger">In-Active</span>
					@else
					<span class="label label-info">In-Active by Partner</span>
					@endif
				</td>
				<td class="text-center">				
					<a href="{!!url(getRoleName().'/chef/'.$v_id.'/'.$type.'/edit/'.$value->id.$url2)!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="icon-pencil3"></i></b></button></a>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="5" class="text-center"><b>No addons</b></td>
			</tr>
			@endif
		</tbody>
	</table>
	</div>
</div>
@if(count($addon) > 0)
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$addon->count() + $innerpage}} of {{ $addon->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$addon->appends(Request::all())->render()}}
	</div>
</div>
@endif
<!-- /basic responsive configuration -->
@endsection
@section('script')
<script type="text/javascript">
	"use strict";
</script>
@endsection
