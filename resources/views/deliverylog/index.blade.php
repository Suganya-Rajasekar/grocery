@extends('layouts.backend.app')
@section('page_header')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title"> 
			<h5><span class="text-semibold">Delivery log</span></h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			@if(request()->has('orderid'))
			<li><a href="{{ url(getRoleName().'/delivery_log') }}">{!! 'Delivery log' !!}</a></li>
			<li>{!! $data[0]->orderdetail->s_id !!}</li>
			@else
			<li>{!! 'Delivery log' !!}</li>
			@endif
		</ul>
	</div>
</div>
@endsection
@section('content')
<style>
	.pagination {
		margin-top: 50px;
		margin-bottom: -6px;
	}
</style>	
<div class="table-responsive">
	<table class="table datatable-responsive table-bordered">
		<thead>
			@if(empty(request()->has('orderid')))	
			<th>Order id</th>
			@endif
			@if(request()->has('orderid'))
			<th>S.No</th>
			@endif
			<th>Dealer</th>
			<th>Status</th>
			<th>Message</th>
			<th>Date and timing</th>
		</thead>
		<tbody>
			@foreach($data as $key => $log)
			<tr>
				@if(empty(request()->has('orderid')))	
				<td> {{ $log->order_id }} </td>
				@endif
				@if(request()->has('orderid'))
				<td>{{ $key+1 }}</td>
				@endif
				<td> {{ $log->dealer }} </td>
				<td> {{ $log->status }} </td>
				<td> {{ $log->message }} </td>
				<td> {{ date('Y-m-d h:i:s A',strtotime($log->created_at)) }} </td>	
			</tr>
			@endforeach	
		</tbody>	
	</table>
</div>

<div class="mt-4">
	<div class="pull-left font-montserrat">
		Displaying {{$data->count()+$page}} of {{ $data->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$data->appends(Request::except('page'))->render()}}
	</div>
</div>			
@endsection