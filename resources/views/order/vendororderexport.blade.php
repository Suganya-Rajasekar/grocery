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
                <th>Commission Amount</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count($resultData)>0)
            @foreach($resultData as $key=>$value)
            <tr>
                <td>#{!!$value->s_id !!}</td>
                <td>{!! isset($value->getUserDetails) ? $value->getUserDetails['name'] : '' !!} </td>
                <td>{!! number_format($value->commission_amount,2,'.','') !!}</td>
                <td>{!! number_format($value->vendor_price,2,'.','') !!}</td>
                <td>{!!$value->status !!}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>