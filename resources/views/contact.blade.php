@extends('mail.index')
@section('content')
<div>
	<b class="font-opensans">User Name:</b>
	<p class="font-montserrat">{{ $name }}</p>
</div>
<div>
	<b class="font-opensans">phone:</b>
	<p class="font-montserrat">{{ $phone_number }}</p>
</div>
<div>
	<b class="font-opensans">Message:</b>
	<p class="font-montserrat" style="width: 700px;word-wrap: break-word;">{{ $notes }}</p>
</div>
@endsection