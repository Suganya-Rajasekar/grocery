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
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>City</th>
        <th>Total Orders</th>
        <th>Amount Spend</th>
        <th>Date</th>
        
    </tr>
    </thead>
    <tbody>
<?php //echo "<pre>";print_r($resultData);exit; ?>
  @if(count($resultData)>0)
        @foreach($resultData as $key=>$val)
        <tr>
          <td >{{ $val->name }}</td>
          <td >{{ $val->email}}</td>
          <td>{!! (isset($val->mobile) && $val->mobile!==NULL) ? $val->mobile : '' !!}</td>
          <td>{!! (isset($val->useraddress[0]->city) && $val->useraddress[0]->city != null) ? $val->useraddress[0]->city : ''  !!}</td>
          <td>Chef - {{ $val->fooditemcount }} <br>
            Event - {{ $val->eventcount }}
          </td>
          <td>Chef - {!!number_format($val->fooditem_spend_amt,2,'.','')!!} <br>
            Event - {!!number_format($val->event_spend_amt,2,'.','')!!}
          </td>
          <td>{!! date('M d Y',strtotime($val->created_at)) !!}</td>
   
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>