<?php ?>
<style>
	.open>.dropdown-menu {
		top: auto;
	   bottom: 100%;
	   width: 259px;
	   height: 200px;
	   overflow: auto;
	   padding: 0px;
	} 
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('#reservation_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	
	$('.equipment_list').multiselect(
	{
		nonSelectedText :'<?php _e('Select Equipment','church_mgt');?>',
		selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
		allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: '<?php _e('Search for equipment...','church_mgt');?>',
		templates: {
            button: '<button type="button" class="multiselect btn btn-default dropdown-toggle" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
        },
		buttonContainer: '<div class="dropdown" />'
	});
	
	$(".reservation_date").datepicker({
       	dateFormat: "yy-mm-dd",
		autoclose: true,
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".reservation_end_date").datepicker("option", "minDate", dt);
        }
	    });
	    $(".reservation_end_date").datepicker({
	      dateFormat: "yy-mm-dd",
		  autoclose: true,
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() - 0);
	            $(".reservation_date").datepicker("option", "maxDate", dt);
	        }
	    });	
    $('.capacity').keydown(function( e ) {
		if(e.which == 189 || e.which == 109)
         return false;
    });
 
	$('#reservation_start_time').timepicki(
	{
	    show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false
	});
		$('#reservation_end_time').timepicki(
	{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false}
		);
	$('#group_id').multiselect();
	
	//add vanue ajax
	    $('#vanue_form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this).serialize(); 
		
		var valid = $('#vanue_form').validationEngine('validate');
		var vanue = $('.equipment_list').multiselect(); 
		if (valid == true) {
			$('.modal').modal('hide');
		$.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			success: function(data)
			{
				 if(data!=""){ 
				   
					$('#vanue_form').trigger("reset");
					$('#vanue_id').append(data);
					vanue.multiselect('rebuild');
					$('.modal').modal('hide'); 
				} 
			},
			error: function(data){
			}
		})
		} 
	});
	//not aloow - value
	$('#participant').keydown(function( e ) {
		if(e.which === 189 || e.which == 109) 
         return false;
    });
	$(".check_memeber").click(function()
	{	
		var max_value=$('#capacity').val() ;
		var participant_value=$('#participant').val() ;
		
		if(participant_value > max_value)
		{
			//alert("Participant value must be less than or equals to the capacity");
			alert(language_translate.max_limit_member_alert);
			return false;
		}			
	}); 
} );
</script>
     <?php 	
	if($active_tab == 'add_reservation')
	{
		$reservation_id=0;
		if(isset($_REQUEST['reservation_id']))
			$reservation_id=$_REQUEST['reservation_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result =$obj_reservation->MJ_cmgt_get_single_reservation($reservation_id);
			
			
			}
			?>
				<!-- POP up code -->
		<div class="popup-bg" style="z-index:100000 !important;">
			<div class="overlay-content">
				<div class="modal-content">
					<div class="category_list">
					</div>
				</div>
			</div> 
		</div>
		<!-- End POP-UP Code -->
		<div class="panel-body"><!-- PANEL BODY DIV start-->
			<form name="reservation_form" action="" method="post" class="form-horizontal" id="reservation_form"><!-- RESERVATION FORM START-->
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="reservation_id" value="<?php echo esc_attr($reservation_id);?>"  />
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Reservation Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="usage_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->usage_title);}elseif(isset($_POST['usage_title'])) echo esc_attr($_POST['usage_title']);?>" name="usage_title">
									<label class="" for="usage_title"><?php _e('Usage Title','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>

						<div class="col-md-6 cmgt_display">
							<div class="form-group input row margin_buttom_0">
								<div class="col-md-8">
									<label class="ml-1 custom-top-label top" for="venue"><?php _e('Venue','church_mgt');?></label>
									<select class="form-control" name="vanue_id" id="vanue_id">
										<option value=""><?php _e('Select Venue','church_mgt');?></option>
										<?php 
										if(isset($_REQUEST['vanue_id']))
											$venue =sanitize_text_field($_REQUEST['vanue_id']);  
										elseif($edit)
											$venue = sanitize_text_field($result->vanue_id);
										else 
											$venue = "";
										$venuedata=$obj_venue->MJ_cmgt_get_all_venue();
										if(!empty($venuedata))
										{
											foreach ($venuedata as $retrive_data)
											{
												echo '<option value="'.esc_attr($retrive_data->id).'" '.selected($venue,$retrive_data->id).'>'.esc_attr($retrive_data->venue_title).'</option>';
											}
										}?>
									</select>
								</div>
								<div class="col-sm-4">
									<button type="button" id="cmgt_addremove" class="btn btn-success button_top width_100 btn_height" data-bs-toggle="modal" data-bs-target="#myModal_Add_vanue"> <?php _e('Add Venue','church_mgt');?></button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input class="form-control validate[required] reservation_date" type="text" name="reservation_date"  
									value="<?php if($edit){ echo esc_attr($result->reserve_date);}elseif(isset($_POST['reservation_date'])){ echo esc_attr($_POST['reservation_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
									<label for="reservation_date"><?php _e('Reserve Start Date','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="reservation_start_time" class="form-control validate[required]  timepicker placeholder_color" type="text" placeholder="<?php _e('Reservation Start Time*','church_mgt');?>"  name="start_time" 
									value="<?php if($edit){ echo esc_attr($result->reservation_start_time);}elseif(isset($_POST['start_time'])) echo esc_attr($_POST['start_time']);?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input  class="form-control validate[required] reservation_end_date" type="text"  name="reservation_end_date"   
									value="<?php if($edit){ echo esc_attr($result->reservation_end_date);}elseif(isset($_POST['reservation_end_date'])){ echo esc_attr($_POST['reservation_end_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
									<label for="reservation_date"><?php _e('Reserve End Date*','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="reservation_end_time" class="form-control validate[required]  timepicker placeholder_color" type="text" placeholder="<?php _e('Reservation End Time*','church_mgt');?>"  name="end_time" value="<?php if($edit){ echo esc_attr($result->reservation_end_time);}elseif(isset($_POST['end_time'])) echo esc_attr($_POST['end_time']);?>">
								</div>
							</div>
						</div> 
						<div class="col-md-4 rtl_margin_top_15px">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="participant" type="text" class="form-control participant_class validate[required,custom[onlyNumber],min[0]]" max="<?php if($edit){ echo esc_attr($result->participant_max_limit); }?>" <?php if($edit){ ?>value="<?php echo esc_attr($result->participant);}elseif(isset($_POST['participant'])) echo esc_attr($_POST['participant']);?>" name="participant">
									<label class="" for="users"><?php _e('Number of Participant','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-2 rtl_margin_top_15px">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="capacity" class="form-control" type="text" value="<?php if($edit){ echo esc_attr($result->participant_max_limit);}elseif(isset($_POST['capacity'])) echo esc_attr($_POST['capacity']);?>"  readonly  name="capacity">
									<label class="" for="users"><?php _e('Max-Limit','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 cmgt_display">
							<div class="form-group input row margin_buttom_0">
								<div class="col-md-12 input margin_bottom_0px">
									<label class="ml-1 custom-top-label top" for="applicant"><?php _e('Applicant','church_mgt');?><span class="require-field">*</span></label>
								
									<select class="form-control validate[required]" name="applicant_id" id="vanue_id">
										<option value=""><?php _e('Select Applicant','church_mgt');?></option>
										<?php 
										if(isset($_REQUEST['applicant_id']))
											$applicant = sanitize_text_field($_REQUEST['applicant_id']);  
										elseif($edit)
											$applicant = sanitize_text_field($result->applicant_id);
										else 
											$applicant = "";
										$get_members = array('role' => 'member');
										$membersdata=get_users($get_members);
										if(!empty($membersdata))
										{
											foreach ($membersdata as $retrieved_data){
												echo '<option value="'.esc_attr($retrieved_data->ID).'" '.selected($applicant,$retrieved_data->ID).'>'.esc_attr($retrieved_data->display_name).'</option>';
											}
										}
										?>
									</select>
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 cmgt_form_description form-control">
									<textarea name="description" class="form-control validate[custom[address_description_validation]]" maxlength="250" id="description"><?php if($edit){ echo esc_attr($result->description);}?></textarea>

									<label class="" for="description"><?php _e('Description','church_mgt');?></label>
								</div>
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mt-2">
							<?php wp_nonce_field( 'save_reservation_nonce' ); ?>
							<div class="offset-sm-0">
								<input type="submit" value="<?php if($edit){ _e('Save Reservation','church_mgt'); }else{ _e('Add Reservation','church_mgt');}?>" name="save_reservation" class="btn btn-success check_memeber col-md-12 save_btn"/>
							</div>
						</div>	
					</div>
				</div>

			</form><!-- RESERVATION FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>
	 <!-----   Add vanue in Member popupform --->
	<div class="modal fade cmgt_main_modal" id="myModal_Add_vanue" role="dialog" tabindex="-1" aria-labelledby="myModal_Add_vanue" aria-hidden="true"><!-- MAIN MODAL DIV START-->
		<div class="modal-dialog modal-lg" style="box-shadow: 0 0 5px rgb(0 0 0 / 100%);"><!-- MODAL DIALOG DIV START-->
		
			<div class="modal-content"><!-- MODAL CONTENT DIV START-->
			
				<div class="modal-header">
				  	<h3 class="modal-title"><?php _e('Add Venue','church_mgt');?>
				  		<a href="#" class="btn float-end mt-2 badge badge-danger rtl_float_left" data-bs-dismiss="modal">X</a>
					</h3>
				</div>
				<div class="modal-body"><!-- MODAL BODY DIV START-->
					<form name="vanue_form"action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="cmgt_form_horizontal" id="vanue_form">
						<input type="hidden" name="action" value="MJ_cmgt_add_vanue_popup">
						<div class="form-body user_form">
							<div class="row cmgt-addform-detail">
								<p><?php esc_html_e('Venue Information','church_mgt');?></p>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="venue_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if(isset($_POST['venue_title'])) echo esc_attr($_POST['venue_title']);?>" name="venue_title">
											<label class="" for="activity_title"><?php _e('Venue Title','church_mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group input">
										<div class="form-group">
										<div class="col-md-12 form-control">
												<input id="capacity" class="form-control validate[required,custom[onlyNumber]] text-input" type="text" maxlength="3" <?php if(isset($_POST['capacity'])) echo esc_attr($_POST['capacity']);?>" name="capacity">
												<label class="" for="capacity"><?php _e('Capacity Seats','church_mgt');?><span class="require-field">*</span></label>
											</div>	
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
										<div class="form-group">
										<div class="col-md-12 form-control">
												<input id="request_days" class="form-control validate[required,custom[onlyNumber]] text-input" maxlength="2" type="text" <?php if(isset($_POST['request_days'])) echo esc_attr($_POST['request_days']);?> name="request_days">
												<label class="" for="request_days"><?php _e('Request Before Days','church_mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>	
								</div>
								<div class="col-md-6 cmgt_display ">
									<div class="form-group input row margin_buttom_0">
										<div class="col-md-8 input">
											<select class="form-control equipment_list equipment_category" multiple="multiple" name="equipment_id[]" id="equipment_category">
												<?php $equipment_array = array();
												
												$equipments=MJ_cmgt_get_all_category('equipment_category');
												if(!empty($equipments))
												{
													foreach ($equipments as $retrive_data)
													{ ?>
														<option value="<?php echo esc_attr($retrive_data->ID);?>" <?php if(in_array($retrive_data->ID,$equipment_array)) echo 'selected';  ?>><span style="margin-left:50px;"><?php echo esc_attr($retrive_data->post_title); ?></span></option>
													<?php }
												}?>
											</select>
										</div>
										<!--ADD Group POPUP BUTTON -->
										<div class="col-sm-4 otehrservice1 rtl_from_select_input_btn">
											<button type="button" id="addremove" class="otehrservice1 btn btn-success utton_top width_100 btn_height cmgt_add_font_13px" model="equipment_category"> <?php _e('Add Or Remove','church_mgt');?></button>
										</div>
									</div>	
								</div>
								<div class="col-md-6">
									<div class="form-group rtl_vaneu_pop">
										<div class="mb-3 row">
											<label for="multiple_reservation" class="control-label form-label"></label>
											<div class="col-sm-12 col-xs-12 ">
												<div class="checkbox col-xs-12 d-flex">
												<input class="mt-1" type="checkbox"  value="yes" <?php  $check_val="no";  if($check_val=='yes'){?> checked <?php } ?> name="multiple_sreservation">
													<p class="otehrservice otehrs rtl_lh_12px margin_left_10px"><?php _e('Allow multiple reservation in the same period','church_mgt');?></p></div>
											</div>
										</div>	
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mt-2">
									<input type="submit" value="<?php _e('Save Venue','church_mgt');?>" name="save_venue" class="btn btn-success col-md-12 save_btn"/>
								</div>
							</div>
						</div>
					</form>
				</div><!-- MODAL BODY DIV END-->
			</div><!-- MODAL CONTENT DIV END-->
		</div><!-- MODAL DIALOG DIV END-->
	</div><!-- MAIN MODAL DIV END-->