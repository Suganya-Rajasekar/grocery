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
        <th>Food Name</th>
        <th>Quantity</th>
        <th>Revenew</th>
        
    </tr>
    </thead>
    <tbody>

  @if(count($resultData)>0)

        @foreach($resultData as $key=>$val)
        <tr>
          <td >{{ $val->name }}</td>
       <td>{!!isset($val->order_quantity) ? $val->order_quantity : ''!!}</td>
         <td>{!!number_format($val->order_price,2,'.','')!!}</td>
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>