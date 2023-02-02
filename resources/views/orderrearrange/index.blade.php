@extends('layouts.backend.app')
@section('page_header')
<?php

	$chef	= getUserData(\Request::segment(3));
    $cpage  = request()->has('page') ? request()->get('page') : '';
    $ipage  = request()->has('innerpage') ? request()->get('innerpage') : '';
    $from   = request()->has('from') ? request()->get('from') : '';
    $url    = '?from='.$from.'&page='.$cpage;
    $url2   = $url.'&innerpage='.$ipage;
    $i		= ($innerpage > 0 && $innerpage != 1) ? ($innerpage - 1) * ($pCount + 1) : 1 ;
?>
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h5><span class="text-semibold">@if(getRoleName() == 'admin'){!! $chef->name !!}@else{!! 'Menus' !!}@endif</span> - Menu items list</h5>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            @if(getRoleName() == 'admin')
            <li><a href="{!! url('admin/chef'.$url) !!}">All chefs</a></li>
            @endif
            <li class="active">Manage menus</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
//$pages=[];
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
@endphp
<!-- Basic responsive configuration -->

	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
			     <th>S.No</th>
				<th>Category</th>
				</tr>
			</thead>
			<tbody>
				@if(count($menuitem) > 0)
				@foreach($menuitem as $key=>$value)
				<tr>
					<td>{!! $i++ !!}</td>
					<td>{!! (isset($value->categories->name)) ? $value->categories->name : '' !!}</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="8" class="text-center"><b>No menu items</b></td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
	<button type="submit"  class="btn btn-success ml-2 mb-2">save</button>
</div>
<!-- /basic responsive configuration -->
@endsection
<style type="text/css">
	.img-circle {
		width: 40px;height: 40px;max-width: none;border-radius: 50%;
	}
</style>
@section('script')
<script type="text/javascript">
	"use strict";
</script>
@endsection
