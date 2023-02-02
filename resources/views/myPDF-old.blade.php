<!DOCTYPE html>
<html>
<head>
    <title>Emperica-Invoice</title>
</head>
<body>
    {{-- <h1>{{ $title }}</h1> --}}
    <div class="row">
		<div class="col-lg-12">
			<div class="panel-body no-padding-bottom">
				<div class="row " >
					<div class="col-sm-12">
						<h3 style="text-align:center;">KNOSH</h3>
					</div>
				</div>

				<div class="row " >
					<div class="col-sm-12">
						<div class="col-sm-6 content-group" style="border: 1px solid #ccc;float:left;width:50%;">
							<span class="text-muted">Invoice To:</span>
							<ul class="list-condensed list-unstyled" style="list-style-type:none;">
								<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['name'] : ''!!}</li>
								@if(\Auth::user()->role==1)
								<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['email'] : ''!!}</li>
								@endif
								<li>{!!isset($resultData->getUserAddress) ? $resultData->getUserAddress['address'] : ''!!}</li>
								@if(\Auth::user()->role==1)
								<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['mobile'] : ''!!}</li>
								@endif
							</ul>
						</div>
						<div class="col-sm-6 content-group" style="border: 1px solid #ccc;float:left;width:50%;">
							<div class="invoice-details">
								<span class="text-muted">Invoice #{!!$resultData->id!!}</span>
								<ul class="list-condensed list-unstyled" style="list-style-type:none;">
									<li>Payment Type: <span class="text-semibold">{!!$resultData->payment_type!!}</span></li>
									<li>Ordered Date: <span class="text-semibold">{!!date('M d, Y', strtotime($resultData->created_at))!!}</span></li>
									<li>Due date: <span class="text-semibold">{!!date('M d, Y', strtotime($resultData->Orderdetail->max('date')))!!}</span></li>
									<li>Number of orders: <span class="text-semibold">{!!$resultData->order_count!!}</span></li>
								</ul>
								<br/>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-flat">
					<div class="panel-heading">
						<h4 class="panel-title">Order List</h4>
					</div>

					<div class="table-responsive invoice">
						<table class="table text-nowrap">
							<thead>
								<tr style="background-color: #ccc;border-top:2px;">
									<th class="col-md-6">Description</th>
									<th class="col-md-2 text-right">Rate</th>
									<th class="col-md-2 text-right">Quantity</th>
									<th class="col-md-2 text-right">Total</th>
								</tr>
							</thead>
							<tbody>
								@if($resultData)
								@if(count($resultData->Orderdetail)>0)
								@foreach($resultData->Orderdetail as $key=>$value)
								<tr class="active border-double">
									<td colspan="1">Order #{!!($value->s_id)!!} [ <b>{!!$value->order_status!!}</b> ] </td>
									<td class="text-right">
										<b>Delivery Date/Time :</b> [ {!!  date('d,M Y',strtotime($value->date))!!} - {!! $value->timeslotmanagement->time_slot !!} ]
									</td><td></td>
									<td class="text-right">
										<b>Chef : </b><span class="label label-success">{!!$value->restaurant->name!!}</span>
									</td>
								</tr>
								<?php
								$dataJson	= /*json_decode(*/$value->food_items/*,true)*/;
								
								?>
								@if(count($dataJson)>0)
								@foreach($dataJson as $fKey=>$fVal)
								<tr class="pad">

									<td>{!!$fVal['name']!!} {!!($fVal['unit'])!='' ? '- '.$fVal['unit'] : ''!!}</td>
									<td class="col-md-2 text-right">Rs - {!!number_format($fVal['price'],2,'.','')!!}</td>
									<td class="col-md-2 text-right">{!!$fVal['quantity']!!}</td>
									<td class="col-md-2 text-right">Rs - {!!number_format(($fVal['quantity']*$fVal['price']),2,'.','')!!}</td>
								</tr>
								@if(count($fVal['addon'])>0)
								@foreach($fVal['addon'] as $aKey=>$aVal)
								<tr class="pad"> 								
									<td>{!!$aVal['name']!!}</td>
									<td class="col-md-2 text-right">Rs - {!!number_format($aVal['price'],2,'.','')!!}</td>
									<td class="col-md-2 text-right">{!!$aVal['quantity']!!}</td>
									<td class="col-md-2 text-right">Rs - {!!number_format(($aVal['quantity']*$aVal['price']),2,'.','')!!}</td>
								</tr>
								@endforeach
								@endif

								@endforeach
								@endif
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Total Food Cost :</th>
									<td class="text-right">Rs - {!!number_format($value->total_food_amount,2,'.','')!!}</td>
								</tr>
								{{-- <tr>
									<td></td>
									<td></td>
									<th class="text-right">Chef Cost :</th>
									<td class="text-right">Rs - {!!number_format($value->vendor_price,2,'.','')!!}</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Commission ( {!!$value->commission!!} %) :</th>
									<td class="text-right">Rs - {!!number_format($value->commission_amount,2,'.','')!!}</td>
								</tr>
								<tr>
									<td>Boy Name: </td>
									<td></td>
									<th class="text-right">Delivery charge ( {!!$value->del_km!!} KM) :</th>
									<td class="text-right">Rs - {!!number_format($value->del_charge,2,'.','')!!}</td>
								</tr> --}}
								<tr style="background-color:#ccc;border-top:2px;">
									<td></td>
									<td></td>
									<th class="text-right">Total Amount :</th>
									<td class="text-right">Rs - {!!number_format($value->grand_total,2,'.','')!!}</td>
								</tr>
								@endforeach
								@endif
								@endif
								<tr style="background-color: lightskyblue;color: black;">
									<td colspan="3"><b>Summary of payment statement</b></td>
									<td class="text-right">

									</td>
								</tr>
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Total Food Cost :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->total_food_amount,2,'.','')!!}</td>
								</tr>
								{{-- <tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Chef Cost :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->vendor_price,2,'.','')!!}</td>
								</tr>
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Commission :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->commission_amount,2,'.','')!!}</td>
								</tr> --}}
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Delivery charge :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->del_charge,2,'.','')!!}</td>

								</tr>
								<tr class="bg" style="border-top: 2px #000 solid;">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Grand Total :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->grand_total,2,'.','')!!}</td>

								</tr>
							</tbody>
						</table>
						
					</div>
				</div>

				
			</div>

		</div>
		<!-- /basic responsive configuration -->

		
		@section('style')
		<style type="text/css">
			table tbody .pad td {padding: 8px 20px !important;font-size: 15px}
			.bg{background-color: #e0f7f4;}
				 body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        ul,li {
            text-decoration: none;
        }
        table {
            font-size: x-small;
            border-collapse: collapse; width: 100%;
        	border:1px solid red; padding:10px;
        }
        table tr td {
            font-weight: bold;
            font-size: x-small;
        }
   

        td{
        	border:1px solid #ccc; padding:10px;
        }

        thead{
        	width:100%;position:fixed;
        	height:109px;
        }
        .text-right
        {
        	float: right;
        }
		</style>
		@endsection

<footer>
	<p style="text-align:center;"><BR>Emperica tech Solutions pvt ltd</p>
</footer>
</body>
</html>



