$(window).on('load',function(){
	if ($(".pro_save").length > 0) {
		$(".pro_save").removeAttr('disabled');
	}
});
(function ($) {
	"use strict";
  //basicform submit
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$("#basicform").on('submit', function(e){
		var $this = $(this);
		if($this.hasClass('seller_infomation')){
			var preference = $(".del_preference:checked").length;
		    if(preference == 0){
		      	var item = 'Please select any one of delivery preference';
		      	$('.errorarea').show();
				Sweet('error',item);
				$("#errors").html("<li class='text-danger' style='color:white;'>"+item+"</li>")
		      	return false;
		    }
		}
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			success: async function(response){ 
				if($this.hasClass('banner-form')){
					Sweet('success',response);
					var bid = $("#banner_id").val();
					$("#banner_id").val('');
				    $('.banner_status_in option[value=""]').prop('selected',true);
				    $('.banner_auth_in option[value=""]').prop('selected',true);
				    $("#preview").attr('src',noimage);
				    $("#preview_input").val('');
				    if(bid != ''){
				    	$("#banner_"+bid).html(response.html);
				    } else {
				    	$(".banner_body").append(response.html);
				    }
				} else {
					var base_location = window.location.href;
					if( base_location.indexOf('store/addon-product/create') > 0){
						// for addon-product redirection
						var promise = await new Promise(function () {
							window.location.href = response[0] + '/edit';
						});
					} else if(base_location.indexOf('store/product/create') > 0) {
						// for product redirection
						var promise = await new Promise( function () {
							window.location.href = response[0] + '/edit';
						});
					} else if(base_location.indexOf('shop/category') > 0) {
						window.location.href = $("#cat_url").val();
					} else if(base_location.indexOf('/edit') > 0) {
						if(typeof response.redirect != 'undefined'){
							window.location.href = response.redirect;
						} else {
							var promise = await new Promise( function () {
								window.location.href = window.location.href; //response[0] + '/edit?auth_id=' + response[1];
							});
						}
					} else if($this.hasClass('coupon_form')){
					} else if(base_location.indexOf('admin/order') > 0 || base_location.indexOf('store/order') > 0){
						if(response.status == true) {
							Sweet('success',response.message);
		      				setTimeout(function(){ window.location.reload(); }, 2000);
						}else {
							Sweet('error',response.message);
						}
					} else if($this.hasClass('coupon_form')){
					} else if(base_location.indexOf('admin/order') > 0 || base_location.indexOf('store/order') > 0){
						if(response.status == true)
						{
							Sweet('success',response.message);
		      				setTimeout(function(){ window.location.reload(); }, 2000);
						}else
						{
							Sweet('error',response.message);
						}
					} else if(base_location.indexOf('rider/payouts') > 0 || base_location.indexOf('admin/payout/request') > 0 || base_location.indexOf('settings/payouts') > 0){
							$("#basicform").find('input').val('');
							Sweet('success',response);
		      				setTimeout(function(){ window.location.reload(); }, 500);
					} else {
						Sweet('success',response);
						// window.location.href = window.location.href; //response[0] + '/edit?auth_id=' + response[1];
					}
					// success(response)
				}
			},
			error: function(xhr, status, error) 
			{
				if(typeof xhr.responseJSON[0] != 'undefined'){
					Sweet('error', xhr.responseJSON[0]);
				} else if (typeof xhr.responseJSON.message != 'undefined') {
					Sweet('error', xhr.responseJSON.message);
				} else {
					Sweet('error', 'Something went wrong');
				}
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					if ($.isArray(item)) {
						$("#errors").html("<li class='text-danger'>"+item[0]+"</li>")
					} else if(typeof item != 'undefined'){
						$("#errors").html("<li class='text-danger'>"+item+"</li>");
					}
				});
				errosresponse(xhr, status, error);
			}
		})


	});

	//id basicform1 when submit 
	$("#basicform1").on('submit', function(e){
		e.preventDefault();

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			success: function(response){ 
				success(response);
				var base_location = window.location.href;
				if(base_location.indexOf('shop/category') > 0) {
					window.location.href = $("#cat_url").val();
				}
			},
			error: function(xhr, status, error) 
			{
				if(typeof xhr.responseJSON[0] != 'undefined'){
					Sweet('error', xhr.responseJSON[0]);
				} else if (typeof xhr.responseJSON.message != 'undefined') {
					Sweet('error', xhr.responseJSON.message);
				} else {
					Sweet('error', 'Something went wrong');
				}
				$('.errorarea').show();

				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					// Sweet('error',item);
					if (typeof item[0] != 'undefined') {
						console.log('id');
						console.log(item[0]);
						$("#errors").html("<li class='text-danger'>"+item[0]+"</li>")
					} else if(typeof item != 'undefined'){
						console.log('elseif');
						$("#errors").html("<li class='text-danger'>"+item+"</li>");
					}
				});
				errosresponse(xhr, status, error);
			}
		})
	});	
	
	$(".checkAll").on('click',function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
$('input[name="type"]').click(function(){
	$("#rider_name option:selected").prop("selected", false)
	// $("#rider_name option[selected]").removeAttr("selected");   
	$('#rider_amount').val('').attr('placeholder','');
})
	$('#order_status').on('change',function(e){
		if($(this).val() != ''){
			$('.submit-btn').attr('disabled',false);
		}else{
			$('.submit-btn').attr('disabled',true);
		}
	})
	$('#rider_name').on('change',function(e) {
		
		var formData = $(this).val();
		var type = $('input[name="type"]:checked').val();
		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: 'checkBoyBalance',
			data: {'rider':formData,'type':type},
			success: function(response){ 
				var max = response.balanceAmount;
				if(max>0 || (max<0 && type == 'receive'))
				{
					$('#rider_amount').attr('disabled',false).attr('max',Math.abs(max)).val((Math.abs(max)));
					$('#rider_submit').attr('disabled',false);
				}else
				{
					$('#rider_amount').val('').attr('placeholder','No Balance needs to be payed').attr('disabled',true);
					$('#rider_submit').attr('disabled',true);
				}
			},
			error: function(xhr, status, error) 
			{
				Sweet('error', xhr.responseJSON[0]);
			}
		});
	});
})(jQuery);	
