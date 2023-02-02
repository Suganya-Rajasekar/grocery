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
$dwnload= [/*'pdf'=>'PDF',*/'xls'=>'EXCEL','csv'=>'CSV'];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-body">
		@error('file')
		<div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
		@enderror
		@if(session()->has('error'))
		<div class="alert alert-danger">
			{{session()->get('error') }}                                                  
		</div>
		@endif
		@if(session()->has('message'))
		<div class="alert alert-success">
			{{ session()->get('message') }}
		</div>
		@endif	
	<div class="pull-left">
		<div class="panel-heading">
			<a href="{!!url(getRoleName().'/chef/'.$v_id.'/menu_item/create'.$url)!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-cutlery"></i></b> {{ 'Add New' }}</button></a>
		</div>
	</div>
	<div class="pull-right">
		<form class="form-inline" method="GET">
			<input type="hidden" name="page" value="{{ $cpage }}">
			<input type="hidden" name="from" value="{{ $from }}">
			<input type="hidden" name="v_id" value="{{ $v_id }}">
			<div class="form-group mb-2">
				<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="@if(getRoleName()=='admin'){!! url(getRoleName().'/chef/'.$v_id.'/menuitemexport/'.$dn.'?search='.\Request::query('search').'&status='.\Request::query('status').'&stock_status='.\Request::query('stock_status')) !!}@elseif(getRoleName()=='vendor'){!! url(getRoleName().'/restaurants/'.$v_id.'/menuitemexport/'.$dn.'?search='.\Request::query('search').'&status='.\Request::query('status').'&stock_status='.\Request::query('stock_status')) !!}@endif">{!! $dv !!}</a>
						@endforeach
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<input type="text" class="form-control" name="search" placeholder="Search Dishname or quantity or price" value="{!! \Request::query('search') !!}">
			</div>
			<div class="form-group mb-10">
				<select name="status" class="select-search form-control">
					<option value="">All Status</option>
					<option value="pending" @if(\Request::query('status') == 'pending') selected @endif>Pending</option>
					<option value="approved" @if(\Request::query('status') == 'approved') selected @endif>Approved</option>
					<option value="cancelled" @if(\Request::query('status') == 'cancelled') selected @endif>Cancelled</option>
					<option value="deleted" @if(\Request::query('status') == 'deleted') selected @endif>Deleted</option> 
				</select>
			</div>
			<div class="form-group mb-10" @if($menuCat->type == 'event') style="display:none;" @endif>
				<select name="stock_status" class="select-search form-control">
					<option value="">All Stock Status</option>
					<option value="instock" @if(\Request::query('stock_status') == 'instock') selected @endif>Instock</option>
					<option value="outofstock" @if(\Request::query('stock_status') == 'outofstock') selected @endif>Out Of Stock</option>
					
				</select>
			</div>
			<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
			@if( \Request::query('search') != '' || \Request::query('status') != '' || \Request::query('stock_status') != '')
			<a href="@if(getRoleName() == "vendor"){!! url('vendor/common/menu_item') !!}@else{!! url('admin/chef/'.$v_id.'/menu_item'.$url) !!} @endif" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
			@endif
		</form>
	</div>

	@if($menuCat->type != 'event')
	<div class="pull-left" style="display:inline-flex;">
			<a href="{{ URL::to('storage/app/public/MenuitemExport_sample.csv') }}" style="text-decoration: none;color: black;margin-top: 25px;"><button class="btn btn-primary mb-2 rounded-pill"><b><i class="icon-download"></i></b> sample csv</button></a>
			<form style="border: 2px solid #26a69a;margin: 5px 1px;padding: 5px; border-radius: 5px;margin-left: 25px;" action="{{ URL::to('importExcel') }}" accept=".csv" class="form-inline" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" name="vendor_id" value="{!! $v_id !!}"/>
			<input type="file" name="file" />
			<button class="btn btn-primary" type="submit">Import File</button>
			</form>
	</div>	
	@endif
	</div>
{{-- 	<form action="{{ URL::to('importExcel') }}" method="post" enctype="multipart/form-data">     
		@csrf
		<input type="hidden" name="vendor_id" value="{!! $v_id !!}" />
		<input type="file"  name="file" />  <input type="submit"  class="btn bg-teal-400" value="submit">
	</form>  --}}


	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>@if($menuCat->type == "event") Ticket image @else Dish image @endif</th>
					<th>@if($menuCat->type == "event") Ticket name @else Dish name @endif</th>
					@if($menuCat->home_event == "no")
					<th>Category</th>
					<th @if($menuCat->type == "event") style="display:none;" @endif>Stock status</th>
					<th>Quantity</th>
					<th @if($menuCat->type == "event") style="display:none;" @endif>Stock</th>
					@endif
					<th>Status</th>
					<th>Price</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
				@if(count($menuitem) > 0)
				@foreach($menuitem as $key=>$value)
				<tr>
					<td>{!! $i++ !!}</td>
					<td @if($menuCat->home_event == "no") class="text-center" @endif><img src="@if($value->image != ''){!! url($value->image) !!}@else{!! url('/storage/app/public/food.jpg') !!}@endif" class="img-circle" alt=""></td>
					<td>{!! $value->name !!}</td>
					@if($menuCat->home_event == "no")
					<td>{!! (isset($value->categories->name)) ? $value->categories->name : '' !!}</td>
					<td @if($menuCat->type == "event") style="display:none;" @endif><span class="label @if($value->stock_status == 'instock'){!! 'label-success' !!}@else{!! 'label-danger' !!}@endif">@if($value->stock_status == 'instock'){{ $value->stock_status }}@else{!! 'outofstock' !!}@endif</span></td>
					<td>@if($value->quantity == 0) - @else {{ $value->quantity }} @endif</td>
					<td @if($menuCat->type == "event") style="display:none;" @endif>@if($value->stock == 0) - @else {{ $value->stock_remain }} @endif</td>
					@endif
					{{-- <td><span class="label @if($value->quantity > 0){!! 'label-success' !!}@else{!! 'label-danger' !!}@endif">@if($value->quantity > 0){{ $value->stock_status }}@else{!! 'outofstock' !!}@endif</span></td>
					<td>{{ $value->quantity }}</td> --}}
					<td>
						@if($value->status=='pending')
						<span class="label label-warning">Pending</span>
						@elseif($value->status=='approved')
						<span class="label label-success">Approved</span>
						@elseif($value->status=='cancelled')
						<span class="label label-danger">Cancelled</span>
						@else
						<span class="label label-info">Deleted</span>
						@endif
					</td>
					<td>{!!number_format($value->price,2,'.','')!!}</td>
					<td class="text-center">				
						<a href="{!!url(getRoleName().'/chef/'.$v_id.'/menu_item/edit/'.$value->id.$url2)!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
					</td>
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
</div>
@if(count($menuitem) > 0)
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$menuitem->count() + $innerpage}} of {{ $menuitem->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$menuitem->appends(Request::all())->render()}}
	</div>
</div>
@endif
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
