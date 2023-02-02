<style>

	ul.mobileappinfo {
		list-style-type: none;
		position: sticky;
		top: 0;
		z-index: 99999;
		padding:0;
	}

	ul.mobileappinfo li {
		border: 1px solid #ddd;
		margin-top: -1px; /* Prevent double borders */
		background-color: #f6f6f6;
		padding: 12px;
		text-decoration: none;
		font-size: 18px;
		color: black;
		display:flex;
		flex-wrap: wrap;
		align-items:center;
		position: relative;
	}
	ul.mobileappinfo li span{
		font-size:14px;
	}
	ul.mobileappinfo li:hover {
		background-color: #eee;
	}
	.mobileappinfo img{
		width: 40px;
		height: auto;
	}

/*	.mobileappinfo .close {
		cursor: pointer;
		position: absolute;
		top: 50%;
		left: 0%;
		transform: translate(0%, -50%);
		padding: 2px 5px;
		transform: translate(0%, -50%);
		border: 1px solid #7a7575;
		border-radius: 50%;
		background: white;
		color: grey;
	}*/

	.mobileappinfo .dismiss {
		color: #f55a60;
		border: unset;
		padding: 7px 4px;
		font-size: 16px;
		border-radius: 7px;
		margin-left: 210px;
		opacity: unset;
		font-weight: normal;
		text-shadow: none;
	}

	.mobileappinfo .close:hover {background: #bbb;}

	.downloadbtn {
		background: #f55a60;
		border: unset;
		color: #fff!important;
		padding: 7px 4px;
		font-size: 16px;
		border-radius: 7px;
	}
	@media screen and (min-width:768px) {
		.mobileappinfo{
			display:none
		}
	
}
</style>

<?php $device = user_agent(); 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<ul class="container-fluid mobileappinfo">
		<li>
			<a class="col-md-2 col-sm-2 col-2 " href="{!!url('/')!!}"><img src="{!!asset('assets/front/img/toplogo.png')!!}" alt="logo-image"></a>
			<span class="col-md-6 col-sm-6 col-6" data-device="{{ $device }}">Download the app and get 30% off on your first order.
			Use Code APP30</span>
			<input type="hidden" id="device" value="{{ $device }}">
			@if($device == "Ios")
			<a href="https://apps.apple.com/in/app/knosh-food-order-and-delivery/id1601196646" class="col-md-4 col-sm-4 col-4 downloadbtn text-center" id="ios_href">Download Now</a>
			@else
			<a href="https://play.google.com/store/apps/details?id=com.emperica.app" class="col-md-4 col-sm-4 col-4 downloadbtn text-center" id="android_href">Download Now</a>
			@endif
			<small class="dismiss close">Dismiss</small>
		</li>
	</ul>
</body>
</html>
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var device = document.getElementById("device").value;
		if (device == 'Ios') {
			var href = document.getElementById("ios_href").getAttribute("href");
		} else {
			var href = document.getElementById("android_href").getAttribute("href");
		}
		// window.location.href = href; 
	});
	var closebtns = document.getElementsByClassName("close");
	var i;

	for (i = 0; i < closebtns.length; i++) {
		closebtns[i].addEventListener("click", function() {
			this.parentElement.style.display = 'none';
		});
	}
</script>