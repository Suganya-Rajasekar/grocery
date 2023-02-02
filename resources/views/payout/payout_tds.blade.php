@extends('layouts.backend.app')
@section('content')
@include('flash::message')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Manage Chef account details</h5>			
	</div>
	<div class="panel-body pull-right">
	</div>
	@if(\Auth::user()->role == 1)
<a href="{!!url(getRoleName().'/tdsadd')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="icon-pie-chart3"></i></b> {{ ('Add New') }}</button></a>
@endif
	<div class="table-responsive-xl">
		<table class="table table-bordered">
			
			<thead>
				<tr>
					<th class="ws-nowrap">#</th>
					<th class="ws-nowrap">Chef name</th>
					<th class="ws-nowrap">FQ Quarter </th>
					<th class="ws-nowrap">Start date</th>
					<th class="ws-nowrap">End date</th>
					<th class="ws-nowrap">Certificate</th> 
				</tr>
			</thead>
			<tbody>

				@if(count($resultData)>0)
				@foreach($resultData as $key=>$value)
				<tr>
					<td>{!!($key+1)!!}</td>
					<td>{!! $value->chefDetails->name!!}</td>
					<td>{!!$value->fq_quarter!!}</td>
					<td>{!!$value->start_date!!}</td>
					<td>{!!$value->end_date!!}</td>
					<td>
						<a href="{!!Url('storage/tds_document/'.$value->certificate) !!}" type="button" download><b>Download</b></a>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>

		</table>
	</div>
</div>
<div class="panel-body">
		@include('footer')	
		
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
