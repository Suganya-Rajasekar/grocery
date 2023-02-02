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
        <th>Code</th>
        <th>Promo offer</th>
        <th>Validity_Start_Date</th>
        <th>Validity_End_Date</th>
        <th>Type</th>
        <th>Status</th>
        
    </tr>
    </thead>
    <tbody>
    @if( count ($resultData)>0)

        @foreach($resultData as $key =>$val)
        <tr>
          <td >{{ $val->name }}</td>
          <td>{{$val->promo_code}}</td>
          <td >{{ $val->offer }}</td>
          <td >{{ $val->start_date }}</td>
          <td >{{ $val->end_date }}</td>
          <td>{{ $val->promo_type }}</td>
           <td >{{ $val->status }}</td>
   
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>