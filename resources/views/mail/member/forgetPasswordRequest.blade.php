@extends('mail.index')
@section('content')
<tr>
	<td class="bg_dark email-section" style="text-align:center;padding: 2.5em;background: rgb(255, 255, 255);">
	    <div class="heading-section heading-section-white">
	        <p style="text-align: left;">Hi {!! $user->name !!},
	        <p style="text-align: center;">You are receiving this email because we received a password reset request for your account.</p>
	        @if(isset($from) && $from == 'App')
	        	<p>Confirmation Code: <strong>{{$user->reminder}}</strong></p>
	        @endif
	        <p style="text-align: center;">If you did not request a password reset, no further action is required.</p>
	    </div>
	</td>
</tr>
@stop