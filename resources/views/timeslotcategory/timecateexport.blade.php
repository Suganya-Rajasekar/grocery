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
        <th>Status</th>
        
    </tr>
    </thead>
    <tbody>
    @if( count ($resultData)>0)

        @foreach($resultData as $key =>$val)
        <tr>
          <td >{{ $val->name }}</td>
           <td >{{ $val->status }}</td>
   
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>