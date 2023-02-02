<!DOCTYPE html>
<html style="width: 270px; background: white;margin: auto;">
<head>
	<title>Emperica</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<style type="text/css">
		table{
			width: 100%;overflow: hidden;
		}
		th{
			font-size: 22px;
		}
		td{
			font-size: 14px;
		}
	</style>
</head>

<body style="margin: auto;">
	<table>
		<tr>
			<th  colspan="3">Knosh</th>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px dashed black;">ORDER ID :</td>
			<td  style="border-top: 1px dashed black;">{{$resultData->s_id}}</td>
		</tr>
		<tr>
			<td colspan="2">DELIVERY DATE :</td>
			<td>{!! date('d M Y',strtotime($resultData->date)) !!}</td>
		</tr>
		<tr>
			<td colspan="2">DELIVERY TIME :</td>
			<td>{!! $resultData->timeslotmanagement->time_slot_chef !!}</td>
		</tr>
	</table>
	<table>
		<tr>
			<td style="border-top: 1px dashed black;" style="border-top: 1px dashed black;" style="border-top: 1px dashed black;" style="border-top: 1px dashed black;">SR.</td>
			<td colspan="4" style="border-top: 1px dashed black;" style="border-top: 1px dashed black;" style="border-top: 1px dashed black;">Item Name</td>
			<td style="border-top: 1px dashed black;">Qnty</td>
		</tr>
		<?php
			$dataJson	= $resultData->food_items;
			?>
			@if(count($dataJson)>0)
			<?php $tot = 0;?>
			@foreach($dataJson as $fKey=>$fVal)
		<tr>
			<td>{!! $fKey+1 !!}.</td>
			<td colspan="4">{!!$fVal['name']!!} {!!($fVal['unit'])!='' ? '- '.unitName($fVal['unit']) : ''!!}</td>
			<td>{!!sprintf('%02d',$fVal['quantity'])!!}</td>
		</tr>
		
	</table>
	<table>
		<tr>
			<td style="border-top: 1px dashed black;border-bottom: 1px dashed black;">NO OF ITEMS</td>
			<td style="border-top: 1px dashed black;border-bottom: 1px dashed black;">{!!sprintf('%02d',$fVal['quantity'])!!}</td>
		</tr>
	</table>
	@endforeach
	@endif
	<table>
		<tr>
			<td colspan="5" align="center">THANK YOU</td>
		</tr>
		<tr>	
			<td colspan="5" align="center">Emperica Tech Solutions Pvt Ltd</td>
		</tr>	
	</table>
</body>
</html>