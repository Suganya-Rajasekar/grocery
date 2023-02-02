@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Pages</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Pages</li>
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
		<a href="{!!url(getRoleName().'/pages/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="icon-insert-template"></i></b> {{ ('Add New') }}</button></a>
	</div>
	@endif
		</div>
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
					<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/pagesexport/'.$dn.'?search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
					</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search title or slug" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="active" @if(\Request::query('status') == 'active') selected @endif >Active</option> 
						<option value="inactive" @if(\Request::query('status') == 'inactive') selected @endif>In-Active</option>
						
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('search') != '' || \Request::query('status') != '' )
				<a href="{!! url('admin/pages') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
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
				<th>title</th>
				<th>slug</th>
				<th>Status</th>
				@if( $access->edit || $access->remove )
				<th class="text-center">Actions</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@if(count($allpage)>0)
			@foreach($allpage as $key=>$value)
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				<td>{!!$value->title!!}</td>
				<td>{!!$value->slug!!}</td>
				<td>
					@if($value->status=='active')
					<span class="label label-success">Active</span>
					@else
					<span class="label label-warning">In-Active</span>					
					@endif
				</td>
				@if( $access->edit || $access->remove )
				<td class="text-center">				
					<div class="d-flex">
						@if( $access->edit )
						<a href="{!!url(getRoleName().'/pages/'.$value->id.'/edit'.$url)!!}"><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-edit"></i></b></button></a>
						@endif
						@if($access->remove )
						<form action="{!!url(getRoleName().'/pages/'.$value->id.'/delete/')!!}" method="post" enctype="Multipart/form-data">
						<input name="_method" type="hidden" value="PUT">
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
		Displaying {{$allpage->count()+$page}} of {{ $allpage->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$allpage->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->
@endsection
@section('script')
<script type="text/javascript">
	"use strict";
	 // Success notification
	 $('#pnotify-success').on('click', function () {
	 	new PNotify({
            title: 'Success notice',
            text: 'Check me out! I\'m a notice.',
            addclass: 'bg-success'
        });
	 });
</script>
@endsection
