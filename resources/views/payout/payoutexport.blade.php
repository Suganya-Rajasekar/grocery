<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<table class="payout_down">
    <thead>
    <tr>
        <th>S.No</th>
        <th>UTR Number</th>
        <th>Order Id</th>
        <th>Order Date</th>
        <th>Settlement Data</th>
        <th>Channel</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
       <?php $k=1; ?>
       @foreach($request['payout']->orders as $order)
        <tr>
         <td>{!! $k !!}</td>
         <td>{!! $request['payout']->utr !!}</td>
         <td>{!! $order->s_id !!}</td>
          <td>{!! date_format($order->created_at,'d-m-Y') !!}</td>
         <td>{!! $request['payout']->created_at !!}</td>
          <td>Online</td>
          <td>INR{!! $order->vendor_price !!}</td>
          <?php $k++; ?>
        </tr>
    @endforeach
    </tbody>
   </table>
 </body>