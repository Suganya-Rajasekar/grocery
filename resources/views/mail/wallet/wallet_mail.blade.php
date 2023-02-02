<div style="width: 600px;margin: auto;">
	<div>Hi {!! $userData->name ?? '' !!}</div>
	<div style="max-width:550px;padding-top: 20px;text-indent: 20;border-top: 3px solid #f55a60;border-bottom: 3px solid #f55a60;margin: 20px 0 0 20px;">
		@if($type == 'Wallet amount debited')
			<div>Glad that you made use of the wallet amount!</div>
		@elseif($type == 'Wallet amount credited')
			<div>Congratulations!</div>
		@endif
		<div style="white-space: pre-wrap;margin-top: 20px;">{{ $msg }}</div>
		@if($type == 'Wallet amount debited')
			<div style="margin-top: 20px;">Watch out for more offers and more delicious dishes at KNOSH</div>
		@elseif($type == 'Wallet amount credited')
			<div style="margin-top: 20px;">Place your order and don't forget to use your wallet amount at the time of checkout.Valid for next 15 days</div>
		@endif
		<div class="regards" style="margin-top:20px;">
			<p>Let's Eat together ....Let's KNOSH together!</p>
			<div>
				<a href="https://knosh.in/downloadApp">https://knosh.in/downloadApp</a>
			</div>
			<div style="margin-top: 20px;">
				<a href="www.knosh.in">www.knosh.in</a>
			</div>
		</div>
		<div style="margin:20px 0 20px 0;color: #f55a60;">
			@include('mail.footer')		
		</div>
	</div>
</div>