<!DOCTYPE html>
<html>
	<style type="text/css">
		.p-0{
			padding: 0px;
		}
		.px-0{
			padding-left: 0px!important;
			padding-right: 0px!important;	
		}
	</style>
	<body>
		<div class="">
			<div class="">
				<div class="invoice-asw">
					<div class="row justify-content-between">
						<div class="col-md-6 col-lg-6 ">
							<img  src="{{ asset('assets/front/img/logo.svg') }}" alt="" style="width: 80px;">
						</div>
						<div class="col-lg-6 col-md-6 text-right">
							<div class="invoice-details top-1">
								<span class="">Invoice #{!!$resultData->Orderdetail[0]->m_id!!}</span>
							</div>
						</div>
					</div>

					<div class="top-2" >
						<div class="row">
							<div class="col-md-6 col-lg-6">
								<span class="">Invoice To:</span>
								<ul class="p-0">
									<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['name'] : ''!!}</li>
									@if(\Auth::user()->role==1)
									<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['email'] : ''!!}</li>
									@endif
									<li class="address">{!!isset($resultData->getUserAddress) ? $resultData->getUserAddress->display_address : ''!!}</li>
									@if(\Auth::user()->role==1)
									<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['mobile'] : ''!!}</li>
									@endif
								</ul>
							</div>
						</div>
					</div>

					<div class="top-3 mt-n5">
						<div class="">
							<h4 class="">Event Booking List</h4>
						</div>

						<div class="invoice table-responsive">
							<table class="table p-0">
								<tbody>
									@if($resultData)
										@if(count($resultData->Orderdetail)>0)
											<?php $sum_tot = 0;$taxes = 0;$package_charges = 0; ?>
											@foreach($resultData->Orderdetail as $key=>$value)
												<?php $taxes += $value->tax_amount;
												$package_charges += $value->package_charge;
												?>
													<tr class="" style="background-color:#90A4AE;border-top:2px; color: white;">
														<th class="text-left">
															<div class="">
																<b>Event Booking id #</b>
																<b>{!! ($value->s_id)!!} 
																</b>
															</div>
														</th>
														<th class="text-left">
														</th>
														<th class="text-left" colspan="2">
															<div class="">
																<b>Event name : </b>
																{!!$value->restaurant->name!!}
															</div>
														</th>
													</tr>	
													<tr style="border-bottom: 1px solid grey;">
														<th scope="col" align="left">Description</th>
														<th scope="col" align="left">Rate</th>
														<th scope="col" align="left">Quantity</th>
														<th scope="col" align="center">Total</th>
													</tr>
												<?php
												$dataJson	= /*json_decode(*/$value->food_items/*,true)*/;
												?>
												@if(count($dataJson)>0)
													<?php $tot = 0; ?>
													@foreach($dataJson as $fKey=>$fVal)
														<?php
														$tot += $fVal['quantity']*$fVal['fPrice'];?>
														<tr class="">

															<td scope="col" align="left" style="text-align:left;" class="px-0">{!!$fVal['name']!!}{!!($fVal['unit'])!='' ? '-'.unitName($fVal['unit']) : ''!!}</td>
															<td class="col-md-2 text-left px-0" align="left" style="text-align:left;" >Rs {!!number_format($fVal['fPrice'],2,'.','')!!}</td>
															<td class="col-md-2 text-left" align="left" style="text-align:left;" >{!!$fVal['quantity']!!}</td>
															<td class="col-md-2 text-right text-nowrap" style="text-align:right;" align="right">Rs {!!number_format(($fVal['quantity']*$fVal['fPrice']),2,'.','')!!}</td>
														</tr>
													@endforeach
												@endif
												<?php
												 
												 $sum_tot +=$tot; ?>
												<tr style="border-top: 1px #c1c1c1 solid;">
													<th scope="col" colspan="3" class="text-right">Total Ticket Cost :</th>
													<td scope="col" class="text-right">Rs {!!number_format($value->total_food_amount,2,'.','')!!}</td>
												</tr>
												@if($taxes > 0)
												<tr>
													<th align="right" colspan="3">Tax ( {!!$value->tax!!} %) :</th>
													<td align="right">Rs  {!!number_format($value->tax_amount,2,'.','')!!}</td>
												</tr>
												@endif
												<tr style="border-top:1px solid grey;">
												<td colspan="2"></td>
												<th scope="col" align="right">Total Amount:</th>
												<td scope="col" align="right">Rs  {!!number_format($value->grand_total,2,'.','')!!}</td>
												</tr>
											@endforeach
										@endif
									@endif
									<tr style="background-color: #00BCD4;color: white;">
										<td scope="col" colspan="4"><b>Summary of payment statement</b></td>
									</tr>
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Total Tickets Cost :</b></td>
										<td scope="col" class="text-right">Rs {!!number_format($resultData->total_food_amount,2,'.','')!!}</td>
									</tr>
									@if($taxes > 0)
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Taxes :</b></td>
										<td scope="col" class="text-right">Rs  {!!number_format($taxes,2,'.','')!!}</td>

									</tr>
									@endif
									@if($resultData->Orderdetail[0]->offer_value > 0)
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Offer discount :</b></td>
										<td scope="col" class="text-right" style="color:red;">Rs  -{!!number_format($resultData->Orderdetail[0]->offer_value,2,'.','')!!}</td>

									</tr>
									@endif
									<tr class="bg" style="border-top: 1px #c1c1c1 solid;">
										<td scope="col" colspan="3" class="text-right px-0"><b>Grand Total :</b></td>
										<td scope="col" class="text-right">Rs  {!!number_format($resultData->grand_total,2,'.','')!!}</td>

									</tr>
								</tbody>
							</table>
						</div>
					</div>
					{{-- <?php exit();?> --}}
				</div>
			</div>
		</div>

		<!-- /basic responsive configuration -->
			
		<style type="text/css">

			.top-1 span, .top-2 span, .top-3 h4{
				font-size: 20px;
				font-weight: bold;
				color: #373b5a;
			}
			.top-1 li span, .top-2 li span{
				font-size: 16px;
				font-weight: 400;
			}
			.top-2 li.address{
				width: 300px;
			}
			table tbody td {
				padding: 8px 20px !important;
				font-size: 14px;
			}
			.bg{
				background-color: #e0f7f4;
			}
			body {
	            margin: 0px;
	        }
	        *{
	            font-family: Verdana, Arial, sans-serif;
	        }
	        ul,li {
	            text-decoration: none;
	            list-style-type: none;
	            margin: 0px;
	        }
	        table {
	            font-size: 14px;
	            border-collapse: collapse; 
	            width: 100%;
	        	padding:10px;
	        }
	        .table tr td, .table thead th, .table th{
	            font-size: 14px;
	            border: 0px solid transparent;
	            border: 0px solid transparent;
	        }
	        .table tr td{
	            padding-left: 0px; 
	            padding-right: 0px;
	        }
	        td{
	        	border:1px solid #ccc;
	        }
	        .text-right{
	        	text-align: right;
	        }
	        .text-left{
	        	text-align: left;
	        }
		</style>

		<footer>
			<p style="text-align:center;"><BR>Emperica Tech Solutions Pvt Ltd</p>
		</footer>
	</body>
</html>
{{-- <?php dd($resultData);?> --}}





