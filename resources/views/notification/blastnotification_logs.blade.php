@extends('layouts.backend.app')
<style>
.ellipsis{
	display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;"
}
</style>
@section('page_header')
<?php $page  = request()->has('page') ? request()->get('page') : 1; ?>
{{-- <?php
	$chef	= getUserData(\Request::segment(3));
    $ipage  = request()->has('innerpage') ? request()->get('innerpage') : '';
    $from   = request()->has('from') ? request()->get('from') : '';
    $url    = '?from='.$from.'&page='.$cpage;
    $url2   = $url.'&innerpage='.$ipage;
    $i		= ($innerpage > 0 && $innerpage != 1) ? ($innerpage - 1) * ($pCount + 1) : 1 ;
?> --}}
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            {{-- <h5><span class="text-semibold">@if(getRoleName() == 'admin'){!! $chef->name !!}@else{!! 'Menus' !!}@endif</span> - Menu items list</h5> --}}
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            @if(getRoleName() == 'admin')
            <li><a href="{!! url('admin/chef/blast_notification') !!}">Blast Notification</a></li>
            @endif
            <li class="active">Logs</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
					<th>subject</th>
					<th>message</th>
					<th>users</th>
					<th>chefs</th>
					<th>status</th>
					<th>created at</th>
				</tr>
			</thead>
			<tbody>
				@if(count($logs) > 0)
				@foreach($logs as $key=>$value)
				<tr>
					<td>{{ $value->subject }}</td>
					<td>{{ $value->message }}</td>
					<td><div class="ellipsis">{{ $value->user }}</div></td>
					<td><div class="ellipsis">{{ $value->chef }}</div></td>
					<td>{{ $value->status }}</td>
					<td style="white-space:nowrap;">{{ date('d-m-Y h:i:s A',strtotime($value->created_at)) }}</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
@if(count($logs) > 0)
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$logs->count() * $page}} of {{ $logs->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$logs->appends(Request::all())->render()}}
	</div>
</div>
@endif
@endsection
@section('script')
<script type="text/javascript">
	"use strict";
</script>
@endsection
