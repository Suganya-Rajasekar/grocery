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
        <th>S.no</th>
        <th>User Code</th>
        <th>Name</th>
        <th>Email id</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Device</th>
        <th>Customer Order Count</th>
        <th>Last Ordered Date time</th>
        <th>Registered Date</th>
    </tr>
    </thead>
    <tbody>
    @if( count ($resultData)>0)
        @foreach($resultData as $key =>$val)
        <tr>
            <td align="left">{{ $key+1 }}</td>
            <td>{{ $val->user_code }}</td>
            <td>{{ $val->name }}</td>
            <td>{{ $val->email}}</td>
            <td>{{ $val->mobile }}</td>
            <td>{{ (isset($val->Useraddress[0]) && !empty($val->Useraddress[0])) ? $val->Useraddress[0]->address : 'Nil'}}</td>
            <td>{{ $val->device }}</td>
            <td>{{ $val->orders_count }}</td>
            <td>{{ !empty($val->last_order_date) ? date('M-d-Y h:i:s A',strtotime($val->last_order_date->created_at)) : '' }}</td>
            <td>{{ date('M-d-Y',strtotime($val->created_at)) }}</td>  
        </tr>
        @endforeach
    @endif
    </tbody>
   </table>
 </body>