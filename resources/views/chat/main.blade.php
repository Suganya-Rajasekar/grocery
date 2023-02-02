<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Scripts -->
<script src="{{ asset('public/js/app.js') }}" defer></script>

<script src="{{ asset('public/js/manifest.js') }}" defer></script>

{{-- <script src="{{ asset('public/js/jquery.js') }}" defer></script> --}}
<!-- Styles -->
{{-- <link href="{{ asset('public/css/app.css') }}" rel="stylesheet"> --}}

<div id="chat-bot-main">
	<client-chat-component :is-auth="{{ json_encode(auth()->check()) }}"
    :user="{{ auth()->check() ? auth()->user() : 'null' }}" :channel="{{ auth()->check() ? json_encode(auth()->user()->socket_subscribe_name) : 'null' }}"></client-chat-component>
</div>