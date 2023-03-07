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
        <th>Email id</th>
        <th>Mobile No</th>
        <th>Location</th>
        <th>Status</th>
        <th>Registered Date
    </tr>
    </thead>
    <tbody>

  @if(count($resultData)>0)
        @foreach($resultData as $key=>$val)
        {{-- <?php echo "<pre>"; print_r($val->locations[0]->name);exit();?> --}}
        <tr>
          <td >{{ $val->name }}</td>
          <td>{{ $val->email }}</td>
          <td>{{ $val->mobile }}</td>
          <td>
             @if(isset($val->locations[0])){!! $val->locations[0]->name !!}@endif
            </td>
           <td >{{ $val->status }}</td>
           <td >{{ $val->created_at }}</td>
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>