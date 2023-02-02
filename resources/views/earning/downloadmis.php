<?php
$html='';
$html.= '<table border="1">';   
$html.= '<thead><tr><th>#</th>
				<th>Order ID</th>
				<th>Payment type</th>
				<th>Food Amount</th>
				<th>Chef Amount</th>
				<th>Commission %</th>
				<th>Your earnings</th>
				<th>Delivery KM/Amount</th>
				<th>Offer %</th>
				<th>Offer Amount</th>
				<th>Total Amount</th>				
				<th>Order Date</th>
				<th>Customer</th>
				<th>Customer mail</th>
				<th>Customer mobile</th>
				<th>Chef</th>
				<th>Delivery address</th>
				<th>Delivery Boy</th></tr></thead><tbody>';

if(count($resultData)>0){
	foreach($resultData as $key=>$value){
		$name=isset($value->getUserDetails) ? $value->getUserDetails["name"] : "";
		$email=isset($value->getUserDetails) ? $value->getUserDetails["email"] : "";
		$mobile=isset($value->getUserDetails) ? $value->getUserDetails["mobile"] : "";
		$vName=isset($value->getVendorDetails) ? $value->getVendorDetails["name"] : "";
		$address=isset($value->order) ? $value->order->address : '';
		$bName=isset($value->boy_info) ? $value->boy_info->name : '';
		$html.= '<tr>
		<td>'.($key+1).'</td>
		<td>'.$value->s_id.'</td>
		<td>'.$value->order->payment_type.'</td>

				<td>Rs '.number_format($value->total_food_amount,2,'.','').'</td>
				<td>Rs'.number_format($value->vendor_price,2,'.','').'</td>
				<td>'.$value->commission.'</td>
				<td>Rs'.number_format($value->commission_amount,2,'.','').'</td>
				<td>'.$value->del_km.' / Rs '.number_format($value->del_charge,2,'.','').'</td>
				<td>'.$value->offer_percentage.'</td>
				<td>Rs '.number_format($value->offer_amount,2,'.','').'</td>
				<td>Rs '.number_format($value->grand_total,2,'.','').'</td>			
				<td>'.date('d M Y',strtotime($value->created_at)).'</td>
				<td>'.$name.'</td>
				<td>'.$email.'</td>
				<td>'.$mobile.'</td>
				<td>'.$vName.'</td>
				<td>'.$address.'</td>
				<td>'.$bName.'</td>
		</tr>';  
	}
}

$html.= '</tbody></table><br><br>';

echo  $html;

?>