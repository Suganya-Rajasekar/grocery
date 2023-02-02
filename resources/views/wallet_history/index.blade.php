@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Wallet History</span></h5>
		</div>
	</div>
	@php
	$customer_back = \Cache::has('customer_back_url') ? \Cache::get('customer_back_url') : ''; 
	$dwnload= [/*'pdf'=>'PDF','xls'=>'EXCEL',*/'csv'=>'CSV'];
	$dwnicon= ['pdf'=>'pdf','xls'=>'excel','csv'=>'csv'];
	@endphp 
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			@if(\Request::segment(2) == "customer")
				<li><a href="{!! $customer_back !!}"><i class="position-left"></i>Customer</a></li>
			@else 
				<li><a href="{!! url('admin/wallet_history') !!}"><i class="position-left"></i>Wallet History</a></li>
			@endif
			@if(\Request::segment(2) == "customer") <li>Wallet History</li> @endif
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
				<div class="form-group mb-10">
					<div class="btn-group ml-1 position-static">
						<button type="button" class="btn bg-transparent text-success border-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="icon-download"></i> Download</button>
						<div class="dropdown-menu dropdown-menu-right" style="">
							@foreach($dwnload as $dn => $dv)
							<a href="{!! url(getRoleName().'/wallethistory_export/'.$dn.'?id='.\Request::query('id').'&type='.\Request::query('type')) !!}" class="dropdown-item"><i class="fas fa-file-{!! $dwnicon[$dn] !!}"></i> {!! $dv !!}</a>
							@endforeach
						</div>
					</div>
				</div>
				<div class="form-group mb-10" style="width:250px">
					<select name="id" class="select-search form-control">
						<option value="" selected >Select user</option>
						@foreach($user as $key => $value)
						<option value="{{ $value->id }}" @if(\Request::query('id') == $value->id) selected @endif>{{ isset($value->name) ? $value->name : (isset($value->user_code) ? $value->user_code : '') }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group mb-10" style="width:250px">
					<select name="type" class="select-search form-control">
						<option value="" selected >Select type</option>
						<option value="credit" @if(\Request::query('type') == 'credit') selected @endif>Credit</option>
						<option value="debit" @if(\Request::query('type') == 'debit') selected @endif>Debit</option>
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if((\Request::query('type') != '' || \Request::query('id') != '') && (\Request::segment(2) == "wallet_history"))
					<a href="{!! url('admin/wallet_history') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@elseif(\Request::query('type') != '' && \Request::segment(2) == "customer") 
					<a href="{!! url('admin/customer/all/wallethistory/'.\Request()->id) !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
				
			</form>
		</div>
	</div>

	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
		
		<thead>
			<tr>
				<th>#</th>
				@if(\Request::segment(2) == "wallet_history")
				<th>User Name</th>
				@endif
				<th>Amount</th>
				<th>Type</th>
				<th>Notes</th>
				<th>Balance</th>
				<th>Last Order placed using wallet</th>
				<th>Last Order value</th>
				<th>Last Ordered Chefs</th>
			</tr>
		</thead>
		<tbody>
			@if(count($w_history)>0)
			@foreach($w_history as $key => $value)
			@php
			$symbol = ($value->type == 'credit') ? '+' : '-';
			@endphp
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				@if(\Request::segment(2) == "wallet_history")
				<td>{{ isset($value->user->name) ? $value->user->name : (isset($value->user->user_code) ? $value->user->user_code : '')}}</td>
				@endif
				<td @if($symbol == '+') class="text-success" @else class="text-danger" @endif>{!! $symbol !!}{!! $value->amount !!}</td>
				<td>{!! $value->type !!}</td>
				<td>{!! $value->notes !!}</td>
				<td>{!! $value->balance !!}</td>
				<td>{!! !is_null($value->order) ? date('d-m-Y',strtotime($value->order->created_at)) : '' !!}</td>
				<td>{!! !is_null($value->order) ? $value->order->grand_total : '' !!}</td>
				<td>{{ !is_null($value->order) ? $value->order->chefnames : ''}}</td>
			</tr>
			@endforeach
			@endif

		</tbody>

	</table>
</div>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$w_history->count()+$page}} of {{ $w_history->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$w_history->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->
@endsection
@section('script')
@endsection
