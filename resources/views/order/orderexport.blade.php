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
            <th>Customer</th>
            <th>Device</th>
            <th>Ordered Chefs</th>
            <th>Customer order count</th>
            <th>Total Sub Orders</th>
            <th>Chef Earnings</th>
            <th>Commission Amount</th>
            <th>Total Amount</th>
            <th>Address</th>
            <th>Payment Type</th>
            <th>Payment Status</th>
            {{-- <th>Status</th> --}}
            <th>Ordered Timing</th>
       </tr>
    </thead>
    <tbody>
        @if(count($resultData)>0)
        @foreach($resultData as $key=>$val)
            <tr>
                <td>{!!$val->Orderdetail[0]['m_id'] ?? ''!!}</td>
                <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['name'] : ''!!} </td>
                <td>{{ isset($val->getUserDetails) ? $val->getUserDetails->device : '' }}</td>
                <td>{{ $val->chefnames }}</td>
                <td>{!!$val->customer_order_count!!}</td>
                <td>{!!$val->order_count!!}</td>
                <td>{!!number_format($val->vendor_price,2,'.','')!!}</td>
                <td>{!!number_format($val->commission_amount,2,'.','')!!}</td>
                <td>{!!number_format($val->grand_total,2,'.','')!!}</td>
                <td>{!!isset($val->getUserAddress) ? $val->getUserAddress['address'] : ''!!}</td>
                <td>{!!$val->payment_type!!}</td>
                <td>{!!$val->payment_status!!}</td>
                {{-- <td>{!!$val->Orderdetail[0]->status ?? ''!!}</td> --}}
                <td>{{ date('Y-m-d h:i A',strtotime($val->created_at)) }}</td>
            </tr>
        @endforeach
        @endif
    </tbody>
</table>
 </body>
 </html>