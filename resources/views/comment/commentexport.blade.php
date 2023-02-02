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
        <th>User Name</th>
        <th>Food Name</th>
        <th>Comment</th>
        <th>Status</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>

  @if(count($resultData)>0)

        @foreach($resultData as $key=>$val)
        <tr>
          <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['name'] : ''!!}</td>
         <td>{!!isset($val->getFoodDetails) ? $val->getFoodDetails['name'] : ''!!}</td>
           <td >{{ $val->comment }}</td>
           <td >{{ $val->status }}</td>
           <td >{{ $val->created_at }}</td>
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>