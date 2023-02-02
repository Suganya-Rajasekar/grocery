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
        <th>Event Name</th>
        <th>Event datetime</th>
        <th>Ticket Name</th>
        <th>Quantity</th>
        <th>Revenew</th>  
    </tr>
    </thead>
    <tbody>

  @if(count($resultData)>0)

        @foreach($resultData as $key=>$val)
        <tr>
          <td>{!!isset($val->vendor->name) ? $val->vendor->name : '';!!}</td>
          <td>{{ date('d-m-Y',strtotime($val->restaurant->event_time[0]))." ".$val->restaurant->event_time[1] }}</td>
          <td >{{ $val->name }}</td>
          <td>{!!isset($val->order_quantity) ? $val->order_quantity : ''!!}</td>
          <td>{!!number_format($val->order_price,2,'.','')!!}</td>
        </tr>
        @endforeach
    @endif
    </tbody>
   </table>
 </body>