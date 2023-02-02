@extends('mail.index')
@section('content')
<tr>
	<td class="bg_dark email-section" style="text-align:center;padding: 2.5em;background: rgb(255, 255, 255);">
	    <div class="heading-section heading-section-white">
	        <p style="text-align: left;">Hi {!! $username !!},
	        @if($request_from == 'register')
	        <p style="text-align: center;">Thanks for signing up {{$username}}</p>
	        @endif
	        <p>To start exploring the {{CNF_APPNAME}} app please confirm your email address </p>
	        <a href="{{$link}}"><input type="button" value="Verify Now"></a>
	    </div>
	</td>
</tr> 
@stop