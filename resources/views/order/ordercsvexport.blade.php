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
            <th>Sno</th>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Chef Name</th>
            <th>Chef ID</th>
            <th>Modeof Payment</th>
            <th>Order status</th>
            <th>Gross revenue</th>
            <th>Commission Amount</th>
            <th>Commission %</th>
            <th>Commission value</th>
            <th>Tax for knosh</th>
            <th>TCS</th>
            <th>TDS</th>
            <th>Net receivable</th>
            <th>Transfer status</th>
            <th>Transfer Date</th>
            <th>Transfer ID</th>
       </tr>
    </thead>
    <tbody>
        <?php //echo "<pre>";print_r($resultData);exit;?>
        @if(count($resultData)>0)
        @foreach($resultData as $key=>$val)
        {{-- @php echo "<pre>"; print_r($val->Orderdetail->toArray()[0]['m_id']);exit; @endphp --}}
            <tr>
                <td>{!!($key+1)!!}</td>
                <td>{!!$val->Orderdetail[0]['m_id'] ?? ''!!}</td>
                <td>{!!$val->Orderdetail[0]->date?? ''!!}</td>
                <td>{!!isset($val->Orderdetail[0]->getVendorDetails) ? $val->Orderdetail[0]->getVendorDetails['name'] : ''!!} </td>
                <td>{!!isset($val->Orderdetail[0]->getVendorDetails) ? $val->Orderdetail[0]->getVendorDetails['id'] : ''!!} </td>
                <td>{!!$val->payment_type!!}</td>
                <td>{!!$val->Orderdetail[0]->status ?? ''!!}</td>
                <td>0</td>
                <td>{!!number_format($val->commission_amount,2,'.','')!!}</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>{!!number_format($val->vendor_price,2,'.','')!!}</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                {{-- <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['name'] : ''!!} </td> --}}
                {{-- <td>{!!$val->customer_order_count!!}</td>
                <td>{!!$val->order_count!!}</td>
                <td>{!!number_format($val->vendor_price,2,'.','')!!}</td>
                <td>{!!number_format($val->grand_total,2,'.','')!!}</td>
                <td>{!!isset($val->getUserAddress) ? $val->getUserAddress['address'] : ''!!}</td> --}}
                {{-- <td>{!!$val->payment_status!!}</td> --}}
            </tr>
        @endforeach
        @endif
    </tbody>
</table>
 </body>
 </html>