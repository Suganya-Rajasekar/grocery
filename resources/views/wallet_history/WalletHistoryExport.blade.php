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
        <th>#</th>
        <th>User Name</th>
        <th>Amount</th>
        <th>Type</th>
        <th>Notes</th>
        <th>Balance</th>
        <th>Last Order placed using wallet</th>
        <th>Last Order value</th>
        <th>Last Ordered Chefs</th>
      </tr>
    </thead>
    <tbody>

      @if(count($resultData)>0)

      @foreach($resultData as $key=>$value)
      <tr>
        <td>{!! ($key+1) !!}</td>
        <td>{{ isset($value->user->name) ? $value->user->name : (isset($value->user->user_code) ? $value->user->user_code : '')}}</td>
        <td>{!! $value->amount !!}</td>
        <td>{!! $value->type !!}</td>
        <td>{!! $value->notes !!}</td>
        <td>{!! $value->balance !!}</td>
        <td>{!! !is_null($value->order) ? date('d-m-Y',strtotime($value->order->created_at)) : '' !!}</td>
        <td>{!! !is_null($value->order) ? $value->order->grand_total : '' !!}</td>
        <td>{{ !is_null($value->order) ? $value->order->chefnames : ''}}</td>
      </tr>
      @endforeach
      @endif
    </tbody>
   </table>
 </body>