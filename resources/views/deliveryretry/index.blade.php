@extends('layouts.backend.app')
@section('page_header')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title"> 
			<h5><span class="text-semibold">Delivery retry</span></h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li><a href="{{ url(getRoleName().'/delivery_retry') }}">{!! 'Delivery retry' !!}</a></li>
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
				<th>Order id</th>
				<th>Dealer</th>
				<th>Reason</th>
				<th>Date & Time</th>
			</thead>
			<tbody>
				@foreach($data as $retry)
				<tr>
				<td>{{ $retry->order_id }}</td>
				<td>{{ $retry->dealer }}</td>
				<td>{{ $retry->reason }}</td>
				<td> {{ date('Y-m-d h:i:s A',strtotime($retry->created_at)) }} </td>	
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