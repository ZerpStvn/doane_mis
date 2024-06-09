<?php ?>
<style>
	.open>.dropdown-menu
	{
	   top: auto;
	   bottom: 100%;
	   width: 259px;
	   height: 200px;
	   overflow: auto;
	   padding: 0px;
	} 
</style>
<script type="text/javascript">
$(document).ready(function()
 {
	$('#vanue_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
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
	
	$('#capacity').keydown(function( e ) {
		if(e.which == 189 || e.which == 109)
        return false;
    });
} );
</script>
     <?php 	
	if($active_tab == 'addvenue')
	{
		$venue_id=0;
		if(isset($_REQUEST['venue_id']))
			$venue_id= sanitize_text_field($_REQUEST['venue_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result =  $obj_venue->MJ_cmgt_get_single_venue($venue_id);
				
			}?>
		
		<!-- POP up code -->
		<div class="popup-bg" style="z-index:100000 !important;">
			<div class="overlay-content" id="overlay-content">
				<div class="modal-content">
					<div class="category_list">
					</div>
				</div>
			</div> 
		</div>
		<!-- End POP-UP Code -->
       <div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="vanue_form" action="" method="post" class="form-horizontal" id="vanue_form"><!-- VENUE FORM START-->
				 <?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="venue_id" value="<?php echo $venue_id;?>"  />
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Venue Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="venue_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->venue_title);}elseif(isset($_POST['venue_title'])) echo esc_attr($_POST['venue_title']);?>" name="venue_title">
									<label class="" for="activity_title"><?php _e('Venue Title','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group input">
								<div class="form-group">
								<div class="col-md-12 form-control">
										<input id="capacity" class="form-control validate[required,custom[onlyNumber]] text-input" type="text" maxlength="3" <?php if($edit){ ?> value="<?php echo esc_attr($result->capacity);}elseif(isset($_POST['capacity'])) echo esc_attr($_POST['capacity']);?>" name="capacity">
										<label class="" for="capacity"><?php _e('Capacity Seats','church_mgt');?><span class="require-field">*</span></label>
									</div>	
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="form-group">
								<div class="col-md-12 form-control">
										<input id="request_days" class="form-control validate[required,custom[onlyNumber]] text-input" maxlength="2" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->request_before_days);}elseif(isset($_POST['request_days'])) echo esc_attr($_POST['request_days']);?>" name="request_days">
										<label class="" for="request_days"><?php _e('Request Before Days','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>	
						</div>
						<div class="col-md-6 cmgt_display">
							<div class="form-group input row margin_buttom_0">
								<div class="col-md-8 input">
									<select class="form-control equipment_list equipment_category" multiple="multiple" name="equipment_id[]" id="equipment_category">
										<?php $equipment_array = array();
										
										if($edit)
										{  
											$equipment_array =(explode(",",$result->equipments));
										}
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
								<div class="col-sm-4 otehrservice1 rtl_margin_top_15px">
									<button type="button" id="addremove" class="otehrservice1 btn btn-success  width_100 btn_height" model="equipment_category"> <?php _e('Add Or Remove','church_mgt');?></button>

									<!-- <button class="otehrservice1 btn btn-primary" id="addremove" model="equipment_category"><?php _e('Add Or Remove','church_mgt');?></button> -->
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12 form-control input_height_48px">
									<div class="row padding_radio">
										<div class="input-group">
											<label for="multiple_reservation" class="control-label form-label"></label>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="checkbox col-xs-12 d-flex rtl_add_vanue" id="cmgt_mul_reservation">
													<input class="mt-1 cmgt_mul_checkbox" type="checkbox"  value="yes" <?php if($edit){ echo $check_val=$result->multiple_booking;}else{ $check_val="no"; } if($check_val=='yes'){?> checked <?php } ?> name="multiple_sreservation">
													<p class="otehrservice otehrs pl-2"><?php _e('Allow multiple reservation in the same period','church_mgt');?></p>
												</div>
											</div>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
					<div class="row">
						<?php wp_nonce_field( 'save_venue_nonce' ); ?>
						<div class="col-md-6 mt-2">
							<input type="submit" value="<?php if($edit){ _e('Save Venue','church_mgt'); }else{ _e('Add Venue','church_mgt');}?>" name="save_venue" class="btn btn-success col-md-12 save_btn"/>
						</div>
					</div>
				</div>
			</form><!-- VENUE FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>