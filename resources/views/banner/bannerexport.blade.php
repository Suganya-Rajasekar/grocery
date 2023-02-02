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
        
        <th>Status</th>
        <th>Start Date</th>
         <th>End Date</th>
       
    </tr>
    </thead>
    <tbody>

  @if(count($resultData)>0)

        @foreach($resultData as $key=>$val)
        <tr>
           <td >{{ $val->status }}</td>
           <td>{{ $val->start_date }}</td>
           <td>{{ $val->end_date }}</td>
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>