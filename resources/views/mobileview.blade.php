<script src="{{ asset('admin/assets/js/core/libraries/jquery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var message 	= "<?php echo $menuinfo->message;?>";
		var vendor_id   = "<?php echo $menuinfo->vendor_id;?>"; 
		var device		= "<?php echo $device;?>"
		startMyApp(message,vendor_id,device);
	});
	function startMyApp(message = '',vendor_id = '',device)
		{
			var Apppath = "Knosh://chefmenu/"+vendor_id+"?message="+message;
			document.location = Apppath;
			setTimeout( function(){
				if( confirm( 'You do not seem to have Your App installed, do you want to go download it now?')){
					if(device == 'Ios') {
						document.location = 'https://apps.apple.com/in/app/knosh-food-order-and-delivery/id1601196646';
					}
					document.location = 'https://play.google.com/store/apps/details?id=com.emperica.app';
				}
			}, 5000);
		}
</script>
