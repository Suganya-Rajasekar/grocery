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
        <th>Customer Name</th>
        <th>Vendor </th>
    </tr>
    </thead>
    <tbody>

  @if(count($resultData)>0)

        @foreach($resultData as $key=>$val)
        <tr>
          <td>{!!isset($val->getUserDetails) ? $val->getUserDetails['name'] : ''!!}</td>
          <td>{!!isset($val->getVendorDetails) ? $val->getVendorDetails['name'] : ''!!}</td>
        </tr>
    @endforeach
    @endif
    </tbody>
   </table>
 </body>