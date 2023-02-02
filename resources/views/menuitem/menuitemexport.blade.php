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
        <th @if($slug == 'pdf')width="1"@endif>name</th>
        @if($slug != 'pdf')
        <th>description</th>
        @endif
        <th @if($slug == 'pdf')width="1"@endif>price</th>
        @if($slug != 'pdf')
        <th>preparation_time</th>
        @endif
        <th @if($slug == 'pdf')width="1"@endif>main_category</th>
        <th @if($slug == 'pdf')width="1"@endif>stock_status</th>
        <th @if($slug == 'pdf')width="1"@endif>quantity</th>
        <th @if($slug == 'pdf')width="1"@endif>addons</th>
        <th @if($slug == 'pdf')width="1"@endif>variants</th>
        @if($slug != 'pdf')
        <th>discount</th>
        <th>status</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @if( count ($resultData)>0)
      @foreach($resultData as $key =>$val)
      <tr> 
        <td>{{ $val->name }}</td>
        @if($slug != 'pdf')
        <td>{{ $val->description }}</td>
        @endif
        <td align="left">{{ $val->price }}</td>
        @if($slug != 'pdf')
        <td>{{ $val->preparation_time }}</td>
        @endif
        <td>{{ !is_null($val->categories) ? $val->categories->name : '' }}</td>
        <td>{{ $val->stock_status }}</td>
        <td align="left">{{ $val->quantity }}</td>
        <td>{{ $val->addons_export }}</td>
        <td>{{ $val->unit_export }}</td>
        @if($slug != 'pdf')
        <td>{{ $val->discount }}</td>
        <td>{{ $val->status }}</td>
        @endif
       </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</body>
</html>