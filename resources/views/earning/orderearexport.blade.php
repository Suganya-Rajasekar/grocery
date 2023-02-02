<!DOCTYPE html>
<html>
<head>
 <title></title>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
 <table>
  <thead>
   <tr>
     <th>Order ID</th>
     <th>Date</th>
     <th>Vendor</th>
     @if($type=='admin')
     <th>Gst Number</th>
     <th>Customer</th>
     <th>Delivery Place</th>
     <th>Total Charge</th>
     <th>Tax Amount</th>
     <th>Delivery Charges </th>
     <th>Packaging Charges</th>
     <th>Offer</th>
     <th>Offer Code</th>
     <th>Customer Paid</th>
     <th>Commission Amount</th>
     @endif
     @if($type=='vendor')
     <th>Total food Amount</th>
     <th>Commission Amount</th>
     <th>Your Earnings</th>
     @endif
   </tr>
 </thead>
 <tbody>
  @if(count($resultData)>0)
  @foreach($resultData as $key=>$val)
  <tr>
   <td>{{ $val->s_id }}</td>
   <td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
   <td>{!!isset($val->getVendorDetails) ? $val->getVendorDetails['name'] : ''!!}</td>
   @if($type=='admin')
   <td>{{ (isset($val->getVendorDetails) && !is_null($val->getVendorDetails->getDocument)) ? $val->getVendorDetails->getDocument->gst_no : ''}}</td>
   <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['name'] : ''!!}</td>
   <td>{{ isset($val->order->getUserAddress) ? $val->order->getUserAddress->city : '' }}</td>
   <td>{!!number_format($val->total_food_amount,2,'.','')!!}</td>
   <td>{!!number_format($val->tax_amount,2,'.','')!!}</td>
   <td>{!!number_format($val->del_charge,2,'.','')!!}</td>
   <td>{!!number_format($val->package_charge,2,'.','')!!}</td>
   <td>{!!number_format($val->offer_value,2,'.','')!!}</td>
   <td>{!!!is_null($val->order->promo) ? $val->order->promo->promo_code : '-'!!}</td>
   <td>{!!number_format($val->grand_total,2,'.','')!!}</td>
   <td>{!!number_format($val->commission_amount,2,'.','')!!}</td> 
   @endif
   @if($type=='vendor')
   <td>{!!number_format($val->total_food_amount,2,'.','')!!}</td> 
   <td>{!!number_format($val->commission_amount,2,'.','')!!}</td> 
   <td>{!!number_format($val->vendor_price,2,'.','')!!}</td>
   @endif
 </tr>
 @endforeach
 @endif
</tbody>
</table>
</body>
  </html>