@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Reels</span></h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Reels</li>
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
/*$cpage	= request()->has('page') ? request()->get('page') : '';
$from	= request()->has('from') ? request()->get('from') : '';
$url	= '?from='.$from.'&page='.$cpage;
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];*/
\Cache::put('reels_backurl',\Request::fullUrl()); 
@endphp

<!-- Basic responsive configuration -->
<div class="panel panel-flat">
		<div class="panel-body">
		<div class="pull-left">
			@if($access->create)
			<div class="panel-heading">
				<a href="{!!url(getRoleName().'/reels/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-cube"></i></b> {{ ('Add New') }}</button></a>
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
						<a class="dropdown-item" href="{!! url(getRoleName().'/unitexport/'.$dn.'?search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
				</div> --}}
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value=""  >All Status</option>
						 <option value="active" @if(\Request::query('status') == 'active')  selected @endif >Active</option> 
						<option value="inactive" @if(\Request::query('status') == 'inactive') selected @endif>Inactive</option> 
					</select>
				</div>
				<div class="form-group mb-10">
					<input type="hidden" name="date" value="" id="start_date">
					<input type="text" name="date" class="form-control daterange-time" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}"> 
				</div>
				{{-- <div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name" value="{!! \Request::query('search') !!}">
				</div> --}}
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('status') != '' || \Request::query('search') != '' || \Request::query('date') != '')
				<a href="{!! url('admin/reels') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
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
					<th>Title</th>
					<th>Description</th>
					<th>Selected chef name</th>
					<th>Validity</th> 
					<th>Status</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				@if(count($reels)>0)
				@foreach($reels as $key=>$value)
				<tr>
					<td>{!! ($key+1)+$page !!}</td>
					<td>{!! $value->title !!}</td>
					<td>{!! $value->description !!}</td>
					<td>{!!isset($value->chef) ? $value->chef['name'] : ' - '!!}</td>
					<td>{!! date('d-m-Y h:i:s a',strtotime($value->validity_date_time)) !!}</td>
					<td>
						@if($value->status=='active')
						<span class="label label-success">Active</span>
						@elseif($value->status=='inactive')
						<span class="label label-warning">In-Active</span>
						@endif
					</td>
					<td>				
						<div class="text-center d-flex">
							<a class="mr-2" href="{!!url(getRoleName().'/reels/'.$value->id.'/edit')!!}">
								<button type="button" class=" btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
							<form action="{!!url(getRoleName().'/reels/'.$value->id)!!}" method="post" enctype="Multipart/form-data">
								<input name="_method" type="hidden" value="delete">
								<input name="id" type="hidden" value="{{ $value->id }}">
								{{ csrf_field() }}
								<button type="submit" class="btn btn-danger btn-xs"  data-id="{!!$value->id!!}" ><b><i class="fa fa-trash"></i></b></button>
							</form>
						</div>
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
		Displaying {{$reels->count()+$page}} of {{ $reels->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$reels->links()}}
	</div>
</div>
<!-- /basic responsive configuration -->
@endsection


@section('script')
<script type="text/javascript">
    // Display time picker
    $('.daterange-time').daterangepicker({
        timePicker: true,
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-danger',
        locale: {
            format: 'YYYY-MM-DD h:mm a'
        }
    });
</script>
@endsection