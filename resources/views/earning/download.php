<?php
$html='';
$html.= '<table border="1">';   
$html.= '<thead><tr>
<th>#</th>
<th>Order ID</th>
<th>Chef </th>';
if($type=='order'){
$html.= '<th>Customer</th>
<th>Chef Amount</th>
<th>Commission %</th>
<th>Your earnings</th>
<th>Total Amount</th>';
}
if($type=='chef'){				
$html.= '<th>Net payable</th>';
}
$html.= '<th>Order Date</th>
</tr></thead><tbody>';

if(count($resultData)>0){
	foreach($resultData as $key=>$value){
		$name=isset($value->getUserDetails) ? $value->getUserDetails["name"] : "";
		$vname=isset($value->getVendorDetails) ? $value->getVendorDetails["name"] : "";
		$html.= '<tr>
		<td>'.($key+1).'</td>
		<td>'.$value->s_id.'</td>
		<td>'.$vname.'</td>';
		if($type=='order'){
			$html.= '<td>'.$name.'</td>
			<td>Rs '.number_format($value->vendor_price,2,'.','').'</td>
			<td>'.$value->commission.'</td>
			<td>Rs'.number_format($value->commission_amount,2,'.','').'</td>
			<td>Rs '.number_format($value->grand_total,2,'.','').'</td>';
		}
		if($type=='chef'){				
			$html.= '<td>Rs'.number_format($value->vendor_price,2,'.','').'</td>';
		}
		$html.= '<td>'.date('d M Y',strtotime($value->created_at)).'</td>
		</tr>';  
	}
}

$html.= '</tbody></table><br><br>';

echo  $html;

?>