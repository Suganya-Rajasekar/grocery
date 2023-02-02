@extends('layouts.backend.app')
@section('content')
@include('flash::message')
@php 
$pages=[];
$access = getUserModuleAccess('payout/'.\Request::segment(3));
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Manage Chef account details</h5>		
		
	</div>
	<div class="panel-body pull-right">
	</div>
	<div class="table-responsive-xl">
		<table class="table table-bordered">
			
			<thead>
				<tr>
					<th class="ws-nowrap">#</th>
					<th class="ws-nowrap">Chef name</th>
					<th class="ws-nowrap">Account Number</th>
					<th class="ws-nowrap">IFSC Code</th>
					@if($access->edit)
					<th class="ws-nowrap">Action</th>
					@endif
				</tr>
			</thead>
			<tbody>

				@if(count($resultData)>0)
				@foreach($resultData as $key=>$value)
				<tr>
					<td>{!!($key+1)+$page!!}</td>
					<td>{!!$value->name!!}</td>
					@if(empty($value->rzaccount) || empty($value->rzaccount->account_number)) 
					<td colspan="2" class="text-center"><span class="label label-danger">Not Created</span></td>
					@else
						<td >{!!$value->rzaccount->account_number ?? ''!!}</td>
						<td>{!!$value->rzaccount->ifsc_code ?? '' !!}</td>
					@endif
					@if($access->edit)
					<td>
						<a href="{!!url(getRoleName().'/chef/'.$value->id.'/edit_business')!!}" data-placement="left" data-toggle="tooltip" title="Click to update business profile"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
					</td>
					@endif
				</tr>
				@endforeach
				@endif

			</tbody>

		</table>
	</div>
	<div class="panel-body">
		@include('footer')	
		
	</div>
	
	
</div>
<!-- /basic responsive configuration -->
@endsection
@section('script')
<script type="text/javascript">
	"use strict";	
	$(document).on('click','.edit_popup',function(){
		$("#id").val($(this).attr('data-id'));
		$("#status").val($(this).attr('data-status')).trigger('change');
		$("#modal_status").modal('show');
	})
	
</script>
@endsection
