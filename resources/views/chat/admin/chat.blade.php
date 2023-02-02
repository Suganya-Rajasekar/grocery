@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Chat</span></h5>
		</div>
	</div>

{{-- 	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Chat</li>
		</ul>
	</div> --}}
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<div class="panel panel-flat">
	<div class="panel-body">
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<input type="hidden" value="{{ url('public/msn_messenger_chat.mp3') }}" id="audio">
		<button type="button" class="play" onclick="callfunc()" style="display:none;">play</button>
		<!-- Scripts -->
		<script src="{{ asset('public/js/app.js') }}" defer></script>

		<script src="{{ asset('public/js/manifest.js') }}" defer></script>

		{{-- <script src="{{ asset('public/js/jquery.js') }}" defer></script> --}}
		<!-- Styles -->
		{{-- <link href="{{ asset('public/css/app.css') }}" rel="stylesheet"> --}}

		<div id="chat-bot-main">
			<admin-chat-component 
			:is-auth="{{ json_encode(auth()->check()) }}"
    		:user="{{ auth()->check() ? auth()->user() : 'null' }}"
    		></admin-chat-component>
		</div>
	</div>	
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.min.js"></script>
<script type="text/javascript">
	var socket = io('https://knosh.in:8891');
	socket.on('receivenotify',function(message){
		if(message.history == false){
			$('.play').trigger('click')
		}
	});
	$('.play').click(function(){
		callfunc();
	});
	function callfunc() {
		var audiourl = $('#audio').val();
		var audio  = new Audio();
		audio.src = audiourl;
		audio.load();
		audio.play();	
	}
</script>
<script type="text/javascript">
	$("#profile-img").click(function() {
		$("#status-options").toggleClass("active");
	});

	$(".expand-button").click(function() {
	  $("#profile").toggleClass("expanded");
		$("#contacts").toggleClass("expanded");
	});

	$("#status-options ul li").click(function() {
		$("#profile-img").removeClass();
		$("#status-online").removeClass("active");
		$("#status-away").removeClass("active");
		$("#status-busy").removeClass("active");
		$("#status-offline").removeClass("active");
		$(this).addClass("active");
		
		if($("#status-online").hasClass("active")) {
			$("#profile-img").addClass("online");
		} else if ($("#status-away").hasClass("active")) {
			$("#profile-img").addClass("away");
		} else if ($("#status-busy").hasClass("active")) {
			$("#profile-img").addClass("busy");
		} else if ($("#status-offline").hasClass("active")) {
			$("#profile-img").addClass("offline");
		} else {
			$("#profile-img").removeClass();
		};
		
		$("#status-options").removeClass("active");
	});

	function newMessage() {
		message = $(".message-input input").val();
		if($.trim(message) == '') {
			return false;
		}
		$('<li class="sent"><img src="http://emilcarlsson.se/assets/mikeross.png" alt="" /><p>' + message + '</p></li>').appendTo($('.messages ul'));
		$('.message-input input').val(null);
		$('.contact.active .preview').html('<span>You: </span>' + message);
		$(".messages").animate({ scrollTop: $(document).height() }, "fast");
	};

	$('.submit').click(function() {
	  newMessage();
	});

	$(window).on('keydown', function(e) {
	  if (e.which == 13) {
	    newMessage();
	    return false;
	  }
	});
</script>
@endsection
