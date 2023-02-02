@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Referral Users List</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Referral Users List</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-body">	
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="form-group mb-10" style="width:250px">
				<select name="user_id" class="select-search form-control">
					<option value="" selected >Select User</option>
					@if(count($users)>0)
					@foreach($referrer_user as $value)
					<option value="{!! $value->referrer_detail->id !!}" @if(\Request::query('user_id') != '' && ($value->referrer_detail->id == \Request::query('user_id'))) selected @endif>{!! $value->referrer_detail->name !!}</option>
					@endforeach
					@endif
				</select>
			</div>
				
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('user_id') != '')
				<a href="{!! url('admin/referral_users_list') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
				
			</form>
		</div>
	</div>

	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
		
		<thead>
			<tr>
				<th>#</th>
				<th>User Name</th>
				<th>Invited User Name</th>
				<th>Invited User Email</th>
				<th>Invited User Mobile Number</th>
				<th>Invited User Registered Date</th>
			</tr>
		</thead>
		<tbody>

			@if(count($users)>0)
			@foreach($users as $key => $value)
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				<td>{!! $value->referrer_detail->name !!}</td>
				<td>{!! $value->name !!} [{{ $value->user_code }}]</td>
				<td>{!! $value->email !!}</td>
				<td>{!! $value->mobile !!}</td>
				<td>{!! date('d-m-Y',strtotime($value->created_at)) !!}</td>
			</tr>
			@endforeach
			@endif

		</tbody>

	</table>
</div>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$users->count()+$page}} of {{ $users->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$users->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->
@endsection
@section('script')
@endsection
