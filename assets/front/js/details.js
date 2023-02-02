//get datepicker value
function loadDatepickerDish() {
	var date = new Date();
	date.setDate(date.getDate() + 1);
	var enddate = new Date();
	enddate.setDate(enddate.getDate() + 10);

	var disabledates = $('.datepickerdish').attr('data-offdates');
	
	$(".datepickerdish").datepicker({
		format: 'yy-mm-dd',
		startDate: date,
		endDate: enddate,
		autoclose: true,
		todayHighlight: true,
		changeYear: false,
		changeMonth: false,
		maxViewMode: 0,
		beforeShowDay: noMondays,
		datesDisabled: disabledates,
	}).on('changeDate', function(date) {
		var date = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
		var id = $(this).attr('data-id');
		$('#future_date_' + id).val(date);
		$('.datebtn' + id).removeAttr("disabled");
		timeslot(id, date);
	});
	$('[data-toggle="tooltip"]').tooltip();
	// outside click can't close modal popup
	$(".modal").modal({
		show: false,
		backdrop: 'static'
	});
}
function noMondays(date){
	var enableddays = $('.datepickerdish').attr('data-offdays');;
	 var days= ['sunday','monday' ,'tuesday','wednesday' ,'thursday' ,'friday' ,'saturday'];
	  if (enableddays.includes(days[date.getDay()])) { 
			return false;
		}
	  else {
			return true;
	  }
}
$(function() {
	loadDatepickerDish();
});
$(document).on('click', '.ordertoday', function() {
	var fullDate = new Date();
	var date = fullDate.getFullYear() + "-" + ('0' + (fullDate.getMonth() + 1)).slice(-2) + "-" + ('0' + fullDate.getDate()).slice(-2);
	var id = $(this).attr('data-id');
	timeslot(id, date);
})
//append time slot
function timeslot(id, date) {
	$.ajax({
		type: 'POST',
		url: base_url + 'timeslot',
		data: { id: id, date: date },
		success: function(data) {
			$('.appendid' + id).html(data);
		},
		error: function(err) {}
	});
}

//get addon value
$(document).on("click", '.addon', function() {
	var id = $(this).attr('data-id');
	var ids = ".addon" + id;

	var checkval = $(this).attr('data-val');
	var ischecked = $(this).is(':checked');
	var itecount = $('#afid_' + id).text();
	if (ischecked) {
		var pri = $('#total_price' + id).text();
		//var totalin = parseInt(pri) + parseInt(itecount*checkval);
		//$('#unitprice'+id).text(totalin);
		var totalin = parseInt(pri) + parseInt(checkval);
		$('#total_price' + id).text(totalin);
	} else {
		var pri = $('#total_price' + id).text();
		//var totalin = parseInt(pri) - parseInt(itecount*checkval);
		//$('#unitprice'+id).text(totalin);
		var totalin = parseInt(pri) - parseInt(checkval);
		$('#total_price' + id).text(totalin);
	}


	var re = $(ids + ":checked").map(function() {
		return this.value;
	}).get().join(",");
	$('#addon_item_' + id).val(re);
});
// get unit value
$(document).on("click", '.unit', function() {
	var id = $(this).attr('data-id');
	var ids = ".unit" + id;
	var radioValue = $(ids + ":checked").val();
	var discountval = $(ids + ":checked").attr('data-discount');
	var itcount = $('#afid_' + id).text();

	var totalPrice = 0;
	$(".addon" + id + ":checked").each(function() {
		totalPrice += parseInt($(this).attr('data-val'));
	});
	if (radioValue) {
		$('#unit_item_' + id).val(radioValue);
		var radval = $(this).attr('data-val');
		$('#unitprice' + id).text(itcount * radval);
		//$('#total_price'+id).text((itcount*radval)+(totalPrice*itcount));
		$('#total_price' + id).text((itcount * radval) + (totalPrice));
		if(discountval != 0) {
			// console.log(discountval);
			$('#discount_price' + id).text(parseFloat(itcount * discountval).toFixed(2));
			$('#total_price' + id).text(parseFloat((itcount * discountval) + (totalPrice)).toFixed(2));
		}
	}
});
//get timeslot value
$(document).on("click", '.timeslot', function() {
	var id = $(this).attr('data-id');
	var ids = ".timeslot" + id;
	var radioValue = $(ids + ":checked").val();

	if (radioValue) {
		$('#time_slot_' + id).val(radioValue);
	}
});
// unit default value set modal close 
$(document).on("click", '.close', function() {
	var unit = $(this).attr('data-unit');
	var discount = $(this).attr('data-disprice');
	var timee = $(this).attr('data-myval');
	var id = $(this).attr('data-id');
	var food_type = $(this).attr('data-type');  
	$('#unit_item_' + id).val(unit);
	$('#time_slot_' + id).val(timee);
	$('#quantity_' + id).val(1);
	$('.afid_'+id).attr('data-pqcount',$('.datatime'+id).attr('data-pqcount'));
	(food_type == 'home_event_menu') ? $('.item-count').text(10) : $('.item-count').text(1);
	if(food_type == 'home_event_menu') {
		// $('.meal_count').hide();
		$('#Non-Vegetarian_count').closest('.meal_list').find('.meal_count').hide();
	}
	$('#unitprice' + id).text($('.ids' + id).attr('data-pric'));
	$('#discount_price' + id).text(discount);
	$('#total_price' + id).text($('.ids' + id).attr('data-pric'));
	if(discount != 0) {
		$('#total_price' + id).text(discount);
	}
	$('.popup_img').hide();
	$('.resrt').trigger('reset');
	$('#datepicker' + id).datepicker('update', '');
});

$(document).on("click", '.add_dec_option', function() {
	var fid = $(this).attr('data-fid');
	var food_pquantity   = $(this).closest('div').find('.afid_'+fid).attr('data-purchasequantity');
	var remain_count     = $(this).closest('div').find('.afid_'+fid).attr('data-pqcount');
	const static_pqcount = $(this).closest('div').find('.afid_'+fid).attr('data-staticpqcount');
	var curclass  = $(this).attr('class').split(' ')[1];
	var div = $(this).closest("div");
	var span = div.find('.item-count');
	var item = div.attr("id").split("_")[1];
	var spancnt = parseInt(span.text());
	var discount = span.attr('data-disprice');
	var food_type = $(this).attr('data-foodtype');
	var min_order_qty = $(this).attr('data-min-qty');
	if(food_pquantity == 0 || curclass == 'remove_item' || (remain_count != 0 && discount != 0)) {
			spancnt = (curclass == 'add_item') ? ((spancnt < 50) ? spancnt += parseInt(1) : spancnt) : ((spancnt > 1) ? spancnt -= parseInt(1) : spancnt);
			if(food_type == 'home_event_menu') {
				if(curclass == "remove_item") {
					spancnt = (spancnt <= min_order_qty) ? min_order_qty : spancnt; 
				}
				$('.meal-text-box').attr('data-min-ord-count',spancnt);
				if($('.meal'+fid+':checked').length == 1) {
					$('.meal'+fid).each(function(){
						if($(this).is(':checked')) {
							$(this).closest('.meal_list').find('.meal-text-box').val(spancnt);;
						}
					});
				}
				if($('.meal'+fid+':checked').length == 2) {
					$('.meal'+fid).each(function(){
						$(this).closest('.meal_list').find('.meal-text-box').val('');;
					});
				}
			}
			span.text(spancnt);
			if(curclass == 'add_item' && remain_count != 9999) { 
				if(remain_count > spancnt) {
					var remain_count  = parseInt(remain_count)-parseInt(spancnt);
					$(this).closest('div').find('.afid_'+fid).attr('data-pqcount',remain_count)
					$('.pquantitycount_'+fid).val(remain_count);
				} else {
					$(this).closest('div').find('.afid_'+fid).attr('data-pqcount',0) 
					$('.pquantitycount_'+fid).val(0);
				}
			} else if(food_pquantity != 0 && curclass == 'remove_item' && remain_count != 9999) {
				var remain_count  = (spancnt == 1) ? static_pqcount : parseInt(static_pqcount)-parseInt(spancnt);
				$(this).closest('div').find('.afid_'+fid).attr('data-pqcount',remain_count);
				$('.pquantitycount_'+fid).val(remain_count);            
			}
	} else if(remain_count == 0 && discount != 0) {
		toast('You can add only '+food_pquantity+' into your cart', 'Oops!..', 'error');
	}
	$('#quantity_' + item).val(spancnt);
	var price = ($('.unit' + item).length) ? $(".unit" + item + ":checked").attr('data-val') : 1;
	price = (price == 1) ? span.attr('data-acprice') : price;
	var totalPrice = 0;
	$(".addon" + item + ":checked").each(function() {
		totalPrice += parseInt($(this).attr('data-val'));
	});
	$(this).parents().next().find('#unitprice' + item).text(price * spancnt);
	if(food_type == 'home_event_menu') {
	   var checked_theme_price = $('#checked_theme_amt').val();
	   if(checked_theme_price) {
			totalPrice += parseInt(checked_theme_price);
			$(this).parents().next().find('#unitprice' + item).text(parseInt(price * spancnt) + parseInt(checked_theme_price));
	   } 
	}
		// console.log($('.unit' + item + ":checked").attr('data-discount')); 
	//$(this).parents().find('#total_price'+item).text( parseInt(price*spancnt) + parseInt(totalPrice*spancnt));
	$(this).parents().find('#total_price' + item).text(parseInt(price * spancnt) + parseInt(totalPrice));
	if(discount != 0) {
		$(this).parents().next().find('#discount_price' + item).text(span.attr('data-disprice') * spancnt);
		$(this).parents().find('#total_price' + item).text(parseInt(discount * spancnt) + parseInt(totalPrice));

	} 
	if($('.unit' + item).length && $('.unit' + item + ":checked").attr('data-discount') != 0) {
		var price = $('.unit' + item + ":checked").attr('data-discount');
		$(this).parents().next().find('#discount_price' + item).text(parseFloat(price * spancnt).toFixed(2));
		$(this).parents().find('#total_price' + item).text(parseFloat((price * spancnt) + (totalPrice)).toFixed(2));
	}
});

$(document).on('click', '.ucart', function(e) {
	e.preventDefault();
	$this = $(this);
	var curclass = $this.attr('class').split(' ')[1];
	var curdiv = $this.closest("div").find(".cartQty");
	var Qty = curdiv.attr('data-qty');
	if (curclass == 'add_item') {
		Qty = parseInt(Qty) + 1;
	} else {
		Qty = parseInt(Qty) - 1;
	}
	if (Qty >= 0) {
		curdiv.text(Qty).trigger('changed');
		curdiv.attr('data-qty', Qty);
	}
});
$(document).on('changed', '.cartQty', debounce(function() {
	var url = baseurl + "/updatecart";
	var Qty = $(this).attr('data-qty');
	var static_qty = $(this).attr('data-staticqty');
	var ucart_id = $(this).closest('.itembuttn').find('.ucart').attr('data-uid');
	$.ajax({
		url: url,
		data: { quantity: Qty, ucart_id: ucart_id },
		dataType: 'json',
		type: 'PATCH',
		success: function(res) {
			/*  curdiv.attr('data-qty',Qty);
			curdiv.text(Qty);*/
			//$('.subTotal').text(res.price);
			if (res.cart != '') {
				$('.detailData').html(res.cart);
			} else {
				toast('Your cart was emptied.', 'Oops!..', 'success');
				setTimeout(function() { location.reload() }, 1000);
			}
			// if (Qty == 0) { $this.closest('.foodLi').remove(); }
			// $(".error-message-area").css("display","block");
			// $(".error-content").css("background","#d4d4d4");
			// $(".error-msg").html("<p style='color:red' class='mb-0'>"+res.message+"</p>");
			// setTimeout(function(){$(".error-message-area").css("display","none");}, 1000);
			// if(res.count == 0) setTimeout(function(){location.reload()}, 1000);
		},
		error: function(err) {
			var msg = err.responseJSON.message;
			$('#qty_'+ucart_id).attr('data-qty',static_qty);
			$('#qty_'+ucart_id).text(static_qty);
			$(".error-message-area").find('.error-msg').text(msg);
			$(".error-message-area").show();
		},
		complete: function(res) {
			// window.location.reload();
		}
	});
}, 500));

//remove the cart items
$(document).on('click', '.delcart', function(e) {
	e.preventDefault();
	var secondclss = $(this).attr('class').split(' ')[1];
	$('.cart_overlay').show();
	var url = baseurl + "/deletecart";
	var func = $(this).attr('data-function');
	if (func === 'removecart') {
		var res_id = $(this).attr('data-res_id');
		var date = $(this).attr('data-date');
		var time_slot = $(this).attr('data-time_slot');
		var dataS = { '_token': $('meta[name="csrf-token"]').attr('content'), function: func, res_id: res_id, date: date, time_slot: time_slot, page: 'detail' };
	} else if (func === 'removedish') {
		var food_id = $(this).attr('data-food_id');
		var date = $(this).attr('data-date');
		var time_slot = $(this).attr('data-time_slot');
		var dataS = { '_token': $('meta[name="csrf-token"]').attr('content'), function: func, food_id: food_id, date: date, time_slot: time_slot, page: 'detail' };
	} else if (func === 'removeaddon') {
		var addon_id = $(this).attr('data-addon_id');
		var dataS = { '_token': $('meta[name="csrf-token"]').attr('content'), function: func, addon_id: addon_id, page: 'detail' };
	}
	$.ajax({
		url: url,
		data: dataS,
		type: 'DELETE',
		dataType: 'json',
		success: function(res) {
			if (res.cart != '') {
				$('.detailData').html(res.cart);
			} else {
				toast('Your cart was emptied.', 'Oops!..', 'success');
				setTimeout(function() { location.reload() }, 1000);
			}
		},
		error: function(err) {
			var msg = err.responseJSON.message;
			toast(msg, 'Oops!..Something went wrong!', 'error');
		},
		complete: function(res) {
			if (func == 'removecart' && secondclss == 'un_available') {
				window.location.reload();
			} else {
				$('.cart_overlay').hide();
			}
		}
	});
});

//pass items ajax function 
function lastresult(id,is_samedatetime = 'no',current_modal = '') {
	var addon           = $('#addon_item_' + id).val();
	var date            = $('#future_date_' + id).val();
	var timeslot        = $('#time_slot_' + id).val();
	//var quantity      = $('#afid_'+id).text();
	var quantity        = $('#quantity_' + id).val();
	var unit            = $('#unit_item_' + id).val();
	var from            = $('#'+current_modal).attr('data-from');
	var fdiscount_price = $('.discount_'+id).val();
	var fpurchaseqcount = $('.pquantitycount_'+id).val();
	var limitstr        = "<a href=\"javascript:;\" class=\"add-asw btn font-montserrat btn-theme btn-small bordered-small-button\" onclick=\"purchase_quantity()\">Add</a>";
	var theme_arr       = [];
	var prefer_arr      = [];
	var meal_arr        = {};
	if(current_modal == 'home_event') {
		$('.theme'+id).each(function(){
			if($(this).is(':checked')) {
				theme_arr.push($(this).val());
			}
		});
		$('.preference'+id).each(function(){
			if($(this).is(':checked')) {
				prefer_arr.push($(this).val());  
			}
		});
		$('.meal'+id).each(function(){
			// if($(this).is(':checked')) {
				var meal  = $(this).val();
				var count = $(this).closest('.meal_list').find('#'+meal+'_count').val();
				console.log(count);
				if(count >= 0) {
                    meal = (meal == "Non-Vegetarian") ? 'Non_Vegetarian' : meal; 
                    meal_arr[meal] = count; 
					// var mdata =  {};
					// meal = (meal == "Non-Vegetarian") ? 'Non_Vegetarian' : meal;
					// mdata[meal] = count;
					// meal_arr.push(mdata);
				}
			// }
		});
	}
	var themes = (theme_arr.length > 0) ? theme_arr.join() : '';
	var preferences = (prefer_arr.length > 0) ? prefer_arr.join() : '';
	var meals  = (Object.keys(meal_arr).length > 100) ? JSON.stringify(meal_arr) : JSON.stringify(meal_arr);
	if(current_modal != "samedatetime" && current_modal !='samedatetime_all' && current_modal != 'no_samedatetime' && current_modal != 'event' && current_modal != 'home_event') {
		$("#"+current_modal).modal('hide');
		$('#sametimeslotModal'+id).modal('show');
	} else if(is_samedatetime == 'yes' && current_modal == 'samedatetime_all') {
		var date		= $('#com_date').val();
		var timeslot	= $('#com_timeslot').val();  
	} 
	if(current_modal == 'samedatetime' || current_modal =='samedatetime_all' || current_modal == 'no_samedatetime' || current_modal == 'event' || current_modal == 'home_event') {
	$.ajax({
		type: 'POST',
		url: base_url + 'sendfooditems',
		data: { id: id, addon: addon, date: date, timeslot: timeslot, quantity: quantity, unit: unit, is_samedatetime : is_samedatetime, themes : themes, preferences : preferences, meals : meals},
		success: function(data) {
			if(fdiscount_price > 0 && fpurchaseqcount == 0) {
				$('.itemadd_'+id).hide();
				$('.limitexceed_'+id).html(limitstr);
				$('.limitexceed_'+id).show();
			}
			var msg = data.carts.original;
			$('.modal').modal('hide');
			if(typeof msg.status !== 'undefined' && msg.status == 422) {
				toast(msg.message, 'Oops!..Something went wrong!', 'error');
			} 
			if(typeof msg.foodtype !== 'undefined'){
				$('#unavailable_message').text(msg.message);
				$('#unavailable_cart').modal('show');
			}
			// var msg = JSON.parse(JSON.stringify(data.carts));
			// setTimeout(function() { location.reload() }, 1000);
			if(typeof msg.foodtype === 'undefined' && msg.status != 422){
				// toast(msg.message, 'Success!', 'success');
				toast('', msg.message, 'success');
			}
			$('.detailData').html(data.detailcart);
			/*if(data.cartcount.is_samedatetime == 'no') {
				$('.firstitem').hide();
				$('.seconditem').show();
			} else*/ if(data.cartcount.is_samedatetime == 'yes') {
				$('.firstitem').hide();
				$('.order_time').hide();
				$('.addtocart').show();
				$('#com_date').val(data.cartcount.date);
				$('#com_timeslot').val(data.cartcount.time_slot);
				// $('.seconditem').hide();
				$('.after_confirm_samedatetime').show();
			}
		},
		error: function(err) {
			$('.modal').modal('hide');
			var msg = err.responseJSON.message;
			toast(msg, 'Oops!..Something went wrong!', 'error');
		}
	});
	}
}

//menu info ajax
function menuinfo(id) {
	$.ajax({
		type: 'POST',
		url: base_url + 'menufood',
		data: { id: id },
		success: function(data) {
			$('#commentbox' + id).html(data.html);
		},
		error: function(err) {

			$("#profilemodal" + id).modal('hide');
			var msg = err.responseJSON.message;
			$(".error-content").css("background", "#d4d4d4");
			$(".error-message-area").find('.error-msg').text(msg);
			$(".error-message-area").show();
		}
	});
}

//comment send data 
$(document).on('submit', '#commentform', function(e) {
	e.preventDefault();
	var url = base_url + "commentsend";
	$.ajax({
		type: 'POST',
		url: url,
		data: $("#commentform").serialize(),
		success: function(res) {
			$('.modal').modal('hide');
			var msg = JSON.parse(JSON.stringify(res));
			$(".error-message-area").css("display", "block");
			$(".error-content").css("background", "#d4d4d4");
			$(".error-msg").html("<p style='color:red' class='mb-0'>" + msg.message + "</p>");
			setTimeout(function() { location.reload() }, 1000);
		},
		error: function(err) {
			$('.modal').modal('hide');
			var msg = err.responseJSON.message;
			$(".error-content").css("background", "#d4d4d4");
			$(".error-message-area").find('.error-msg').text(msg);
			$(".error-message-area").show();
		}
	});
});

$(document).on('click', '#replypost', function(e) {
	var url = base_url + "commentsend";
	var food_id = $(this).closest('.reply_div').find('#food_id').val();
	var c_id = $(this).closest('.reply_div').find('#c_id').val();
	var comment = $(this).closest('.reply_div').find('#comment').val();
	var authuser = $(this).closest('.reply_div').find('#auth').val();
	if (authuser) {
		$.ajax({
			type: 'PATCH',
			url: url,
			data: { food_id: food_id, c_id: c_id, comment: comment },
			success: function(res) {
				$('.modal').modal('hide');
				var msg = JSON.parse(JSON.stringify(res));
				$(".error-message-area").css("display", "block");
				$(".error-content").css("background", "#d4d4d4");
				$(".error-msg").html("<p style='color:red' class='mb-0'>" + msg.message + "</p>");
				setTimeout(function() { location.reload() }, 1000);
			},
			error: function(err) {
				$('.modal').modal('hide');
				var msg = err.responseJSON.message;
				$(".error-content").css("background", "#d4d4d4");
				$(".error-message-area").find('.error-msg').text(msg);
				$(".error-message-area").show();
			}
		});
	} else {
		self.location = baseurl + "/login";
	}

});

function purchase_quantity()
{
	toast('You have ordered max quantity for this order ! Pl order this dish from our regular Chefs menu', 'Oops!..', 'error');
}

$(document).on('click','.themes',function(){
	var current_id = $(this).val();
	var menu_id = $(this).attr('data-id');
	var old_theme_amount = $(this).attr('data-checked-amount');
	var current_theme_amount  = $(this).attr('data-val');
	var checked_theme_amount  = parseInt(old_theme_amount) + parseInt(current_theme_amount); 
	if($(this).is(':unchecked')) {
		var checked_theme_amount  = parseInt(old_theme_amount) - parseInt(current_theme_amount); 
	} 
	var total_amt = $('#total_price'+menu_id).text() - parseInt(old_theme_amount);
	var ischecked = $('.theme'+menu_id+':checked');
	var unitprice = $('#unitprice'+menu_id).text() - old_theme_amount;
	if(ischecked.length > 1) {
		$('.theme'+menu_id+':checked').each(function(){
			if($(this).val() != current_id) {
				$(this).prop('checked',false);
				checked_theme_amount  = (parseInt(old_theme_amount) - parseInt($(this).attr('data-val'))) + parseInt(current_theme_amount);
			}
		});
	} 
	$('.theme'+menu_id).each(function(){
		$(this).attr('data-checked-amount',checked_theme_amount);
	});
	$('#checked_theme_amt').val(checked_theme_amount);
	$('#total_price'+menu_id).text(parseInt(total_amt) + parseInt(checked_theme_amount));
	$('#unitprice'+menu_id).text(parseInt(unitprice) + parseInt(checked_theme_amount));
});

$(document).on('click','.meal',function(){
	var meal_type = $(this).val();
	var menuid = $(this).attr('data-menuid');
	if($(this).is(':checked')) {
		$(this).closest('.meal_list').find('.meal_count').show();
		$('.meal-text-box').val('');
	} else if($(this).is(':unchecked')) { 
		if($('.meal'+menuid+':checked').length == 0) {
			$(this).prop('checked',true);
		} else {
			var min_order_count = $(this).closest('.meal_list').find('.meal-text-box').attr('data-min-ord-count');
			$(this).closest('.meal_list').find('.meal_count').hide();
			$('.meal'+menuid).each(function(){
				if($(this).is(':checked')) {
					$(this).closest('.meal_list').find('.meal-text-box').val(min_order_count);;
				}
			});
		}
	}
});

$(document).on('click','.homeevent_add',function(){
	$('#Vegetarian_count').show();
	// $('.meal-text-box').val('');
	// $('.meal').prop('checked',false);
	$('.preferences').prop('checked',false);
	$('.addon').prop('checked',false);
});

$(document).on('keyup','.meal-text-box',debounce(function(){
	var id = $(this).attr('data-id');
	var meal = $(this).attr('data-meal');
	var itemcount = $('#afid_'+id).text();
	var min_order_count = $(this).attr('data-min-ord-count');
	var veg_count = 0;
	var nonveg_count = 0;

	if(meal == 'Vegetarian') {
		veg_count = $(this).val();
		if($(this).closest('.ord-mod').find('#Non-Vegetarian_count').val() > 0) {
			nonveg_count = $(this).closest('.ord-mod').find('#Non-Vegetarian_count').val();
		}
	} else if(meal == 'Non-Vegetarian') {
		var nonveg_count = $(this).val();
		if($(this).closest('.ord-mod').find('#Vegetarian_count').val() > 0) {
			veg_count = $(this).closest('.ord-mod').find('#Vegetarian_count').val();
		}
	}
	var people_count = parseInt(veg_count) + parseInt(nonveg_count);
	if(min_order_count < people_count) {
		toast('Please enter the people count within menu quantity or increase menu quantity.','Oops!..','error');
		$(this).val('');
		(meal == 'Vegetarian') ? $(this).closest('.ord-mod').find('#Non-Vegetarian_count').val('') : $(this).closest('.ord-mod').find('#Vegetarian_count').val('');
	} else if(min_order_count > people_count) {
		var remain_count = min_order_count - people_count; 
		(meal == 'Vegetarian') ? $(this).closest('.ord-mod').find('#Non-Vegetarian_count').val(remain_count) : $(this).closest('.ord-mod').find('#Vegetarian_count').val(remain_count);       
		(meal == 'Vegetarian') ?  $(this).closest('.ord-mod').find('.mealcount_Non-Vegetarian').show() : $(this).closest('.ord-mod').find('.mealcount_Vegetarian').show(); 
		$('.meal'+id).prop('checked',true);
	}   

	if($(this).closest('.ord-mod').find('.meal'+id+':checked').length == 2) {
		if(meal == 'Vegetarian') {
			var non_veg_count = min_order_count - veg_count;
			(non_veg_count >= 0 && veg_count != '') ? $(this).closest('.ord-mod').find('#Non-Vegetarian_count').val(non_veg_count) : $(this).closest('.ord-mod').find('#Non-Vegetarian_count').val(''); 
		} else if(meal == 'Non-Vegetarian') {
			var veg = min_order_count - nonveg_count;
			(nonveg_count != '') ? $(this).closest('.ord-mod').find('#Vegetarian_count').val(veg) : $(this).closest('.ord-mod').find('#Vegetarian_count').val(''); 
		}   
	}
},250));

$(document).on('click','.theme_img_cls',function(){
	var menu_id  = $(this).attr('data-mid');
	var theme_id = $(this).attr('data-theme-id');
	var element  = $(this).closest('.themefor'+menu_id).find('.image'+theme_id);
	if(element.is(':hidden')) {
		$(this).closest('.ord-mod').find('.popup_img').hide();
		element.show();
		$('.modal-content').addClass('back_gray');
	} else {
		element.hide();
	}
});

$(document).on('click','.theme_img_close',function(){
	var menu_id  = $(this).attr('data-mid');
	var theme_id = $(this).attr('data-theme-id');
	var element  = $(this).closest('.themefor'+menu_id).find('.image'+theme_id);
	if(element.is(':visible')) {
		element.hide();
		$('.modal-content').removeClass('back_gray');   
	}
});