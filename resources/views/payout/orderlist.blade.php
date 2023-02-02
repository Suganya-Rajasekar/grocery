<div class="col-sm-12">
	<div class="panel panel-flat">
		<div class="panel-body">
			@if(count($payout->orders) > 0)
			<table class="table" style="border: 2px solid #cccc">
				<thead>
					<tr>
	            <th>Order ID</th>
	            <th>Date</th>
	            <th>Channel</th>
	            <th>Amount</th>
	        </tr>
				</thead>
				<tbody>
					@foreach($payout->orders as $order)
					<tr>
						<td>{!! $order->s_id !!}</td>
						<td>{!! date_format($order->created_at,'d-m-Y') !!}</td>
						<td>Online</td>
						<td>INR{!! $order->vendor_price !!}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@else
				<button class="btn btn-default">No Orders</button>
			@endif
		</div>	
	</div>
</div>	