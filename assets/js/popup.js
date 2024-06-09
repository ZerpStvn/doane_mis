jQuery(document).ready(function ($) {
	//----------- Sidebar dropdown in Responsive -----------//
	jQuery('#sidebarCollapse').on('click', function () {
		jQuery('#sidebar').toggleClass('active');
		jQuery(this).toggleClass('active');
	});
	jQuery('.has-submenu').on('click', function () {
		jQuery('.submenu', this).toggleClass('active');
		jQuery(this).toggleClass('active');
	});
	//----------- Sidebar dropdown in Responsive -----------//  
	//Category Add and Remove
	jQuery("body").on("click", "#addremove", function (event) {
		//$('.popup-bg').show().css({'height' : docHeight});
		jQuery("#myModal_Add_vanue").css({ "display": "none" });
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		var model = $(this).attr('model');

		var curr_data = {
			action: 'MJ_cmgt_add_or_remove_category',
			model: model,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});
	$("body").on("click", ".close-btn", function () {
		var model = $(this).attr('model');
		if (model == "equipment_category") {
			$("#myModal_Add_vanue").css({ "display": "block" });
			$(".category_list").empty();
			$('.popup-bg').hide(); // hide the overlay			
		}
		else {
			$(".category_list").empty();
			$('.popup-bg').hide(); // hide the overlay
		}
	});

	$("body").on("click", ".btn-delete-cat", function () {

		var cat_id = $(this).attr('id');
		var model = $(this).attr('model');
		if (confirm(language_translate.delete_record_alert)) {
			var curr_data = {
				action: 'MJ_cmgt_remove_category',
				model: model,
				cat_id: cat_id,
				dataType: 'json'
			};
			$.post(cmgt.ajax, curr_data, function (response) {
				$('#cat-' + cat_id).hide();
				// if(model == 'activity_category')
				// {
				$("." + model).find('option[value=' + cat_id + ']').remove();
				// window.location.reload(true);
				//return true;
				// }
				// else
				// {
				// 	$("."+model).find('option[value='+cat_id+']').remove();
				// 	//jQuery("#"+model).multiselect('rebuild');
				// 	// window.location.reload(true);
				// 	return true;
				// }

				// if(model == 'equipment_category')
				// {
				$("#" + model).find('option[value=' + cat_id + ']').remove();
				jQuery('.equipment_category').multiselect('rebuild');
				// }
				// else
				// {
				// 	$("#"+model).find('option[value='+cat_id+']').remove();
				// 	jQuery("#"+model).multiselect('rebuild');
				// }
				return true;
			});
		}
	});

	$("body").on("click", "#btn-add-cat", function () {
		var category_name = $('#category_name').val();
		var model = $(this).attr('model');
		var valid = jQuery('#category_form').validationEngine('validate');

		if (valid == true) {
			if (category_name != "") {
				var curr_data = {
					action: 'MJ_cmgt_add_category',
					model: model,
					category_name: category_name,
					dataType: 'json'
				};
				$.post(cmgt.ajax, curr_data, function (response) {
					var json_obj = $.parseJSON(response);//parse JSON			
					$('.category_listbox .table').append(json_obj[0]);
					$('#category_name').val("");
					if (model == 'equipment_category') {
						jQuery('.equipment_category').append(json_obj[1]);
						jQuery('.equipment_category').multiselect('rebuild');
					}
					else {
						jQuery('#' + model).append(json_obj[1]);
					}
					return false;
				});
			}
			else {
				alert(language_translate.please_enter_caegory_name_alert);
			}
		}
	});

	// Import data function popup ---------------


	// $("body").on("click",".importdata",function() 
	// {
	// 	var docHeight = $(document).height(); //grab the height of the page
	// 	var scrollTop = $(window).scrollTop();
	// 	var curr_data = {
	// 				action: 'MJ_cmgt_import_data',
	// 				dataType: 'json'
	// 				};					
	// 				$.post(cmgt.ajax, curr_data, function(response) {	
	// 				$('.popup-bg').show().css({'height' : docHeight});
	// 				$('.popup_dashboard').hide(); 
	// 					//$('.import_data').html(response);	
	// 					$('.category_list').html(response);	
	// 					$('.patient_data').html(response);	
	// 				});
	// });	

	//-----------load activity by category-------------
	$("#act_cat_id").change(function () {
		$('#activity_list').html('');
		var selection = $("#act_cat_id").val();
		var optionval = $(this);
		var curr_data = {
			action: 'gmgt_load_activity',
			activity_list: selection,
			dataType: 'json'
		};
		$.post(gmgt.ajax, curr_data, function (response) {
			//alert(response);
			$('#activity_list').append(response);
		});


	});
	//------------LOAD MAXIMUM NUMBER OF PARTICIPANT IN RESERVATION-----------//
	$("#vanue_id").change(function () {
		$(".participant_class").addClass("validate[required,custom[onlyNumber],max[0],min[0]]")
		$('#capacity').html('');
		var selection = $("#vanue_id").val();
		var optionval = $(this);
		var curr_data = {
			action: 'MJ_cmgt_load_capacity',
			vanue_id: selection,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {
			$('#capacity').val(response);
			var participant = response;
			$(".participant_class").removeClass("validate[required,custom[onlyNumber],max[0],min[0]]");
			$(".participant_class").addClass("validate[required,custom[onlyNumber],min[0],max[" + participant + "]]");
		});


	});
	//------------LOAD GIFT PRICE-----------//
	$("#gift_id").change(function () {
		$('#gift_price').html('');
		var selection = $("#gift_id").val();

		var curr_data = {
			action: 'MJ_cmgt_load_gift_price',
			gift_id: selection,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {
			$('#gift_price').val(response);
		});


	});

	$("body").on("click", "#varify_key", function (event) {
		$(".cmgt_ajax-img").show();
		$(".page-inner").css("opacity", "0.5");
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var res_json;
		var licence_key = $('#licence_key').val();
		var enter_email = $('#enter_email').val();
		//alert(model);
		//return false;
		var curr_data = {
			action: 'MJ_cmgt_verify_pkey',
			licence_key: licence_key,
			enter_email: enter_email,
			dataType: 'json'
		};

		$.post(cmgt.ajax, curr_data, function (response) {

			res_json = JSON.parse(response);

			$('#message').html(res_json.message);
			$("#message").css("display", "block");
			$(".cmgt_ajax-img").hide();
			$(".page-inner").css("opacity", "1");

			if (res_json.cmgt_verify == '0') {
				window.location.href = res_json.location_url;
			}
			return true;
		});
	});

	//-------VIEW VENUE -------------------
	jQuery("body").on("click", ".view_venue", function (event) {
		var venue_id = $(this).attr('id');
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(nutrition_id);
		var curr_data = {
			action: 'MJ_cmgt_venue_view',
			venue_id: venue_id,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('#venue_view').html(response);
			return true;
		});
	});
	//-------VIEW RESERVATION-hitesh -------------------
	jQuery("body").on("click", ".view_reservation", function (event) {
		var reservation_id = $(this).attr('id');
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(nutrition_id);
		var curr_data = {
			action: 'MJ_cmgt_reservation_view',
			reservation_id: reservation_id,
			dataType: 'json'
		};

		$.post(cmgt.ajax, curr_data, function (response) {

			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});
	//-------VIEW DOCUMENT-hitesh -------------------
	jQuery("body").on("click", ".view_document", function (event) {
		var document_id = $(this).attr('id');
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(nutrition_id);
		var curr_data = {
			action: 'MJ_cmgt_document_view',
			document_id: document_id,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {


			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});

	//-------VIEW PASTORAL -------------------
	jQuery("body").on("click", ".view_pastoral", function (event) {
		var pastoral_id = $(this).attr('id');
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(nutrition_id);

		var curr_data = {
			action: 'MJ_cmgt_pastoral_view',
			pastoral_id: pastoral_id,
			dataType: 'json'
		};

		$.post(cmgt.ajax, curr_data, function (response) {


			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});

	//-------VIEW SERVICE-hitesh -------------------
	jQuery("body").on("click", ".view_Service", function (event) {
		var service_id = $(this).attr('id');
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(nutrition_id);
		var curr_data = {
			action: 'MJ_cmgt_service_view',
			service_id: service_id,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {


			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});

	//-------VIEW NOTICE-hitesh -------------------
	jQuery("body").on("click", ".view_notice", function (event) {
		var notice_id = $(this).attr('id');
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(nutrition_id);
		var curr_data = {
			action: 'MJ_cmgt_notice_view',
			notice_id: notice_id,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {


			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});


	//-------VIEW Activity-hitesh -------------------
	jQuery("body").on("click", ".view_activity", function (event) {
		var activity_id = $(this).attr('id');
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(nutrition_id);
		var curr_data = {
			action: 'MJ_cmgt_activity_view',
			activity_id: activity_id,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {


			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});

	//-------VIEW ROOM CHECK-INS -------------------
	jQuery("body").on("click", ".view_checkins", function (event) {
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		var room_id = $(this).attr('id');

		var curr_data = {
			action: 'MJ_cmgt_room_checkin_view',
			room_id: room_id,
			dataType: 'json'
		};
		//alert('hello');
		$.post(cmgt.ajax, curr_data, function (response) {


			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});
	//-------Give Gift To Member -------------------
	jQuery("body").on("click", ".give_gift", function (event) {

		var gift_id = $(this).attr('id');

		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();

		var curr_data = {
			action: 'MJ_cmgt_give_gifts',
			gift_id: gift_id,
			dataType: 'json'
		};

		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});


	//-------Give Gift To Member -------------------
	jQuery("body").on("click", ".view_gift_list", function (event) {

		var member_id = $(this).attr('mem_id');
		//alert(member_id);
		//return false;
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		// alert(room_id);

		var curr_data = {
			action: 'MJ_cmgt_view_gifts_list',
			member_id: member_id,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			// alert(response);
			// 	console.log(response);
			// return false;
			return true;
		});
	});

	//-------Get pledges limit -------------------
	jQuery("body").on("blur", "#times_number", function (event) {
		var period = $('#period_id').val();
		var amount = $('#amount').val();
		var start_date = $('#start_date').val();
		var times_number = $(this).val();
		var curr_data = {
			action: 'MJ_cmgt_get_enddate_total_amount',
			period: period,
			start_date: start_date,
			amount: amount,
			times_number: times_number,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {
			$('#view_pledes_limit').html(response);
			return true;
		});
	});
	//-------Get pledges limit -------------------
	jQuery("body").on("change", "#period_id", function (event) {

		var period = $(this).val();
		var amount = $('#amount').val();
		var start_date = $('#start_date').val();
		var times_number = $('#times_number').val();
		var n = amount * 1;
		if (n >= 0) {
			//...Do stuff for +ve num
			var curr_data = {
				action: 'MJ_cmgt_get_enddate_total_amount',
				period: period,
				start_date: start_date,
				amount: amount,
				times_number: times_number,
				dataType: 'json'
			};
			$.post(cmgt.ajax, curr_data, function (response) {
				$('#view_pledes_limit').html(response);
				return true;
			});
		}
		else {
			alert("Please Enter Amount In Positive...");
			$("#period_id").val("select");
			return false;
			///...Do stuff -ve num
		}
	});

	//----------view Invoice popup--------------------
	$("body").on("click", ".show-invoice-popup", function (event) {

		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		var idtest = $(this).attr('idtest');
		var invoice_type = $(this).attr('invoice_type');
		var curr_data = {
			action: 'MJ_cmgt_invoice_view',
			idtest: idtest,
			invoice_type: invoice_type,
			dataType: 'json'
		};
		// alert(action);					
		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.invoice_data').html(response);
			return true;
		});

	});

	//----------view pledge popup--------------------
	$("body").on("click", ".show-view-popup", function (event) {

		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		var idtest = $(this).attr('idtest');
		var invoice_type = $(this).attr('invoice_type');
		var curr_data = {
			action: 'mj_cmgt_pledge_view',
			idtest: idtest,
			invoice_type: invoice_type,
			dataType: 'json'
		};
		//   alert(action);					
		$.post(cmgt.ajax, curr_data, function (response) {
			// console.log(response); 	
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.invoice_data').html(response);
			return true;
		});

	});
	//---------View Group Members----------------
	jQuery("body").on("click", ".view_group_member", function (event) {

		var group_id = $(this).attr('id');

		var group_type = $(this).attr('group_type');
		/*  alert(group_type);
		 die; */
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(group_id);
		// return false;
		var curr_data = {
			action: 'MJ_cmgt_group_member_view',
			group_id: group_id,
			group_type: group_type,
			dataType: 'json'
		};
		//alert('hello');
		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			return true;
		});
	});
	//---------View Group Members----------------
	jQuery("body").on("click", ".add_group_member", function (event) {
		//alert("hello");
		var group_id = $(this).attr('id');
		var group_type = $(this).attr('group_type');
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		var curr_data = {
			action: 'MJ_cmgt_group_member_add',
			group_id: group_id,
			group_type: group_type,
			dataType: 'json'
		};
		//console.log(group_type);
		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			//console.log(curr_data);
			return true;
		});
	});
	//----------DELETE GROUP MEMBER----------------		
	$("body").on("click", "#delete_groupmember", function () {

		var member_id = $(this).attr('mem_id');
		var group_id = $(this).attr('group_id');
		var group_type = $(this).attr('member_groputype');
		//if(confirm("Are you sure want to delete this record?"))
		if (confirm(language_translate.delete_record_alert)) {
			var curr_data = {
				action: 'MJ_cmgt_remove_group_member',
				member_id: member_id,
				group_id: group_id,
				group_type: group_type,
				dataType: 'json'
			};
			$.post(cmgt.ajax, curr_data, function (response) {
				$('#cat-' + member_id).remove();
				//window.location.reload(true);
				if (group_type == "ministry") {
					window.location.href = "admin.php?page=cmgt-ministry&tab=ministrylist&message=7";
					return true;
				} else {
					window.location.href = "admin.php?page=cmgt-group&tab=grouplist&message=7";
					return true;
				}
				// window.location.href = "admin.php?page=cmgt-group&tab=grouplist&message=7";
				// return true;				
			});
		}
	});

	// START POPUP FOR SEE  member family member 
	$("body").on("click", ".show-family", function (event) {
		var member_id = $(this).attr('idtest');

		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop(); //grab the px value from the top of the page to where you're scrolling
		//var id = $(this).data('idtest') ;
		//$('.popup-bg').show().css({'height' : docHeight}); //display your popup and set height to the page height
		//$('.overlay-content'+id).css({'top': scrollTop+20+'px'}); //set the content 20px from the window top

		var curr_data = {
			action: 'MJ_cmgt_view_family_member',
			member_id: member_id,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.category_list').html(response);
			//$('.show-family').html(response);	
		});
	});


	// hides the popup if user clicks anywhere outside the container

	// END POPUP

	$("body").on("click", "#profile_change", function () {

		//event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		//alert(evnet_id);
		var curr_data = {
			action: 'MJ_cmgt_change_profile_photo',
			dataType: 'json'
		};

		$.post(cmgt.ajax, curr_data, function (response) {
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.profile_picture').html(response);
		});
	});

	//Event And task display model
	$("body").on("click", ".show_task_event", function (event) {

		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
		var id = $(this).attr('id');
		var model = $(this).attr('model');

		//    alert(id);
		/* alert(model);
		return false; */
		var curr_data = {
			action: 'MJ_cmgt_show_event_task',
			id: id,
			model: model,
			dataType: 'json'
		};
		$.post(cmgt.ajax, curr_data, function (response) {
			/* console.log(response);
			return false; */
			$('.popup-bg').show().css({ 'height': docHeight });
			$('.task_event_list').html(response);
			return true;
		});
	});
	$("body").on("click", ".event_close-btn", function () {
		$('.popup-bg').hide(); // hide the overlay
	});
	$("#chk_sms_sent").change(function () {

		if ($(this).is(":checked")) {
			//alert("chekked");
			$('#hmsg_message_sent').addClass('hms_message_block');

		}
		else {
			$('#hmsg_message_sent').addClass('hmsg_message_none');
			$('#hmsg_message_sent').removeClass('hms_message_block');
		}
	});


	$('.onlynumber_and_plussign').on('keyup', function () {
		var inputVal = $(this).val();
		var phoneno = /^\+[0-9]*$/;

		if (inputVal.startsWith('+') && inputVal.match(phoneno)) {
			// console.log(inputVal);
		} else {
			alert('Please enter only + and 0-9');
			$(this).val('');
			return false;
		}
	});




	jQuery('.activity_id_onchange').on('change', function () {
		var activity_id = $(this).val();
		$('.load_group_by_activity_id').html("");
		var curr_data = {
			action: 'MJ_cmgt_load_group_by_activity_id',
			activity_id: activity_id,
			dataType: 'json'
		};


		$.post(cmgt.ajax, curr_data, function (response) {

			$('.load_group_by_activity_id').html(response);
			//$(response).appendTo('.load_group_by_activity_id');
			$('.load_group_by_activity_id option[value=""]').hide();

			return false;
		});
	});
	$("body").on("click", ".delete_all_button", function () {
		if ($('.sub_chk:checked').length == 0) {
			//alert("Please select at least one record");
			alert(language_translate.Please_select_at_least_one_record_alert);
			return false;
		}
		else {
			var proceed = confirm(language_translate.delete_record_alert);
			if (proceed) {
				return true;
			}
			else {
				return false;
			}
		}
	});
	jQuery("body").on("change", ".date_type", function (event) {
		if ($(this).find(":selected").val()) {
			date_type = $(this).val();
			// alert(date_type);
			if (date_type == "period") {
				$(".panel-body .date_type_div_none").css("display", "block");
				var curr_data = {
					action: 'mj_cmgt_admission_repot_load_date',
					date_type: date_type,
					dataType: 'json'
				};
				$.post(cmgt.ajax, curr_data, function (response) {
					$('#date_type_div').html(response);
				});
			}
			else {
				$(".panel-body .date_type_div_none").css("display", "none");
			}
		}
	});
});
$(document).ready(function () {
	$('[data-toggle="tooltip"]').tooltip();
})
//  sub menu small-screen click evevent call-start//
document.addEventListener("DOMContentLoaded", function () {
	// make it as accordion for smaller screens//
	document.querySelectorAll('#sidebar .nav-link').forEach(function (element) {
		element.addEventListener('click', function (e) {
			let nextEl = element.nextElementSibling;
			let parentEl = element.parentElement;
			let allSubmenus_array = parentEl.querySelectorAll('.submenu');

			if (nextEl && nextEl.classList.contains('submenu')) {
				e.preventDefault();
				if (nextEl.style.display == 'block') {
					nextEl.style.display = 'none';
				} else {
					nextEl.style.display = 'block';
				}
			}
		});
	})
});

$(document).ready(function () {
	var activeElement = document.querySelector('.flex-nowrap.overflow-auto .active');

	if (activeElement) {
		activeElement.scrollIntoView({
			behavior: 'smooth',
			block: 'end'
		});
	}

});