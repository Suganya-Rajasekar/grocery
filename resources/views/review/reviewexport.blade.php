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
      <th>Order Id</th>
      <th>Vendor Name</th>
      <th>User Name</th>
      <th>Review</th>
      <th>Rating</th>
      <th>Status</th>
      <th>Date</th>
        
    </tr>
    </thead>
    <tbody>
    @if( count ($resultData)>0)

        @foreach($resultData as $key =>$val)
        <tr>
          <td>@if(!empty($val->order)) {!!$val->order->s_id!!} @endif</td>
         @if(getRoleName()=='admin')
                <td>{!!isset($val->getVendorDetails) ? $val->getVendorDetails['name'] : ''!!}</td>
                @endif
         
         <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['name'] : ''!!}</td>
          <td>{{ $val->reviews }}</td>
          <td>{{ $val->rating }}</td>
           <td>{{ $val->status }}</td>
           <td>{{ $val->created_at }}</td>
   
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>
 </html>