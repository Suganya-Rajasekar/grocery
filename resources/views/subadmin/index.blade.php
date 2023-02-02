@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Sub Admin</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Sub Admin</li>
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
		<a href="{!!url(getRoleName().'/subadmin/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-user"></i></b> {{ ('Add New') }}</button></a>
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
						<a class="dropdown-item" href="{!! url(getRoleName().'/subadminexport/'.$dn.'?search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
					</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name or email or mobile" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="pending" @if(\Request::query('status') == 'pending') selected @endif >Pending</option> 
						<option value="approved" @if(\Request::query('status') == 'approved') selected @endif>Approved</option>
						<option value="suspended" @if(\Request::query('status') == 'suspended') selected @endif>Suspended</option>
						<option value="cancelled" @if(\Request::query('status') == 'cancelled') selected @endif>Cancelled</option>
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('search') != '' || \Request::query('status') != '' )
				<a href="{!! url('admin/subadmin') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
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
				{{-- <th>Sub Admin ID</th> --}}
				<th>Avatar</th>
				<th>Name</th>
				<th>Email</th>
				<th>Mobile</th>
				<th>Status</th>
				{{-- <th>Email verified</th> --}}
				@if( $access->edit)
				<th class="text-center">Actions</th>
				@endif
			</tr>
		</thead>
		<tbody>

			@if(count($subadmin)>0)
			@foreach($subadmin as $key=>$value)
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				{{-- <td>{!!$value->id!!}</td> --}}
				<td>
					<a href="{!! url(getRoleName().'/chef/'.$value->id.'/edit') !!}"><img src="@if($value->avatar != ''){{url($value->avatar)}}@else{{url('/storage/app/public/avatar.png')}}@endif" class="img-circle" alt="">
					</a>
				</td>
				<td>{!!$value->name!!}</td>
				<td>{!!$value->email!!}</td>
				<td>{!!$value->mobile!!}</td>
				<td>@if($value->status == 'approved') Approved @elseif($value->status == 'cancelled') Cancelled @elseif($value->status == 'suspended') Suspended @elseif($value->status == 'pending') Pending @endif</td>
				{{-- <td>
					@if($value->email_verified_at!='')
					<span class="label label-success">Verified</span>
					@else
					<span class="label label-danger">Not-Verified</span>
					@endif
				</td> --}}
				@if( $access->edit)
				<td class="text-center">				
					<a href="{!!url(getRoleName().'/subadmin/'.$value->id.'/edit'.$url)!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
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
		Displaying {{$subadmin->count()+$page}} of {{ $subadmin->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$subadmin->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->

<!-- Location modal -->
<div id="modal_location" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Add/Edit Location</h5>
			</div>

			<form action="{!!url('location/store')!!}" method="POST" enctype="Multipart/form-data">
				{{ csrf_field() }}{{ method_field('PUT') }}
				<div class="modal-body">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label>Location name</label>
								<input type="text" name="l_name" id="l_name" placeholder="Location" class="form-control" required="">
							</div>

							<div class="col-sm-6">
								<label>Status</label>
								<select name="l_status" id="l_status" class="select-search" required="">
									<option value="">select any one</option>
									<option value="0">Active</option>
									<option value="1">In-Active</option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<input type="hidden" name="l_id" id="l_id">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Location modal -->
@endsection
<style type="text/css">
	.img-circle {
		width: 40px;height: 40px;max-width: none;border-radius: 50%;
	}
</style>
@section('script')
<script type="text/javascript">
	"use strict";	
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#l_id").val($(this).attr('data-id'));
		$("#l_name").val($(this).attr('data-name'));
		$('#l_status option[value="'+$(this).attr('data-name')+'"]').attr("selected", "selected");
		$("#modal_location").modal('show');
	})
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
