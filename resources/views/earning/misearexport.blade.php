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
        <th>Order id</th>
        <th>Payment Type</th>
        <th>Order Value</th>
        <th>Commission</th>
        <th>Revenew</th>
        <th>Chef Earning</th>
        <th>Tax %</th>
        <th>Tax Amount</th>
        <th>Deleivery Charges</th>
        <th>Offer</th>
        <th>Offer Value</th>
        <th>Package charge</th>
        <th>Total Amount</th>
        <th>Order Date</th>
        <th>Customer</th>
        <th>Mail</th>
        <th>Mobile</th>
        <th>Chef</th>      
    </tr>
    </thead>
    <tbody>
  @if(count($resultData)>0)

        @foreach($resultData as $key=>$val)
        <tr>
          <td >{{ $val->s_id }}</td>
          <td>{!!$val->order->payment_type!!}</td>
          <td>{!!number_format($val->total_food_amount,2,'.','')!!}</td>
          <td>{!!$val->commission!!}</td>
          <td>{!!number_format($val->commission_amount,2,'.','')!!}</td>
          <td>{!!number_format($val->vendor_price,2,'.','')!!}</td>
          <td>{!!$val->tax!!}</td>
          <td>{!!number_format($val->tax_amount,2,'.','')!!}</td>
          <td>{!!number_format($val->del_charge,2,'.','')!!}</td>
          <td>@if($val->offer_type != 'none') {!! ($val->offer_type == 'percentage') ? $val->offer_percentage.' %' : $val->offer_amount !!} @endif</td>
          <td>{!!number_format($val->offer_value,2,'.','')!!}</td>
          <td>{!!number_format($val->package_charge,2,'.','')!!}</td>
          <td >{{ $val->grand_total}}</td>
          <td class="date-misreport"><p>{!!date('d M Y',strtotime($val->created_at))!!}</p></td>
          <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['name'] : ''!!}</td>
          <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['email'] : ''!!}</td>
          <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['mobile'] : ''!!}</td>
          <td>{!!isset($val->getVendorDetails) ? $val->getVendorDetails['name'] : ''!!}</td>
   
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>
 </html>