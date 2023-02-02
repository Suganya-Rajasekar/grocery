@extends('layouts.backend.app')
@section('content')
@include('flash::message')
@php 
$pages=[];
@endphp
<!-- Basic responsive configuration -->
<div class="col-sm-12">
	<div class="panel panel-flat col-sm-5">
		<div class="panel-heading">
			<h5 class="panel-title">Wallet Details</h5>		
		</div>
		<div class="panel-body">
			<table class="table" style="border: 2px solid #cccc">
				<thead>
					<tr><th>Total no of orders</th><th>:</th><td>{!!$resultData['no_of_orders']!!}</td></tr>
					<tr><th>Total Amount</th><th>:</th><td>Rs {!!number_format($resultData['total_amount'],2,'.','')!!}</td></tr>
					<tr><th>Admin Transferred amount</th><th>:</th><td>Rs {!!number_format($resultData['transferred_amount'],2,'.','')!!}</td></tr>
					<tr><th>Already requested amount</th><th>:</th><td>Rs {!!number_format($resultData['requested_amount'],2,'.','')!!}</td></tr>
					<tr><th>Remaining amount in your wallet</th><th>:</th><td>Rs {!!number_format($resultData['wallet_amount'],2,'.','')!!}</td></tr>
				</thead>
			</table>
		</div>	
	</div>
	<div class="col-sm-1"></div>
	<div class="panel panel-flat col-sm-5">
		<div class="panel-heading">
			<h5 class="panel-title">Amount Request Form</h5>
		</div>
		<div class="panel-body">
			 <form action="{!!url(getRoleName().'/payout/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name="type" id="type" value="request">
        <fieldset class="content-group">
          <legend class="text-bold">Basic details</legend>    
           <div class="form-group">
            <label class="control-label col-lg-2">Amount</label>
            <div class="col-lg-5">
            <input type="text" class="form-control" name="amount" placeholder="Enter request amount" > 
            </div>
          </div>  
        </fieldset>

        <div class="text-right">
          <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
      </form>
		</div>	
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
