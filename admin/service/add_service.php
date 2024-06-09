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
$(document).ready(function()
{
	$('#activity_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('.group_id').multiselect(
	{
		nonSelectedText :'<?php _e('Select Group','church_mgt');?>',
		selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: '<?php _e('Search for group...','church_mgt');?>'
	});
	
	$(".start_date").datepicker({
       	dateFormat: "yy-mm-dd",
		minDate:0,
		autoclose: true,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".end_date").datepicker("option", "minDate", dt);
        }
	    });
    $(".end_date").datepicker({
      dateFormat: "yy-mm-dd",
	  autoclose: true,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 0);
            $(".start_date").datepicker("option", "maxDate", dt);
        }
    });	
	jQuery('.other_service_date').datepicker({
			dateFormat: "yy-mm-dd",
			minDate:'today',
			changeMonth: true,
			autoclose: true,
	        changeYear: true,
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},    
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "/" + year);
	        }                    
		});
	jQuery('.other_service_date').datepicker({
			dateFormat: "yy-mm-dd",
			minDate:'today',
			changeMonth: true,
			autoclose: true,
	        changeYear: true,
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},    
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "/" + year);
	        }                    
		}); 
	//start time//
	$('.start_time').timepicki({
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false});
		
		$('.end_time').timepicki({
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false});
	
	$('.other_start_time').timepicki(
	{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false}
		);
		
	$('.other_end_time').timepicki(
	{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false}
		);
});
</script>
    <?php 	
	if($active_tab == 'addservice')
	{
		$service_id=0;
		if(isset($_REQUEST['service_id']))
			$service_id= sanitize_text_field($_REQUEST['service_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_service->MJ_cmgt_get_single_services($service_id);
			}
			?>
			
		<div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="activity_form" action="" method="post" class="form-horizontal" id="activity_form"><!-- 		SERVICES FORM START-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="service_id" value="<?php echo esc_attr($service_id);?>"  />

				
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Service Information','church_mgt');?></p>
					</div>
					<div class="row"><!--Row Div--> 


						<div class="col-md-6 cmgt_display">
							<div class="form-group input row margin_buttom_0">
								<div class="col-md-8">
									<label class="ml-1 custom-top-label top" for="service_type"><?php esc_html_e('Service Type','church_mgt');?></label>
									<select class="form-control line_height_30px" name="service_type_id" id="service_type">
										<option value=""><?php _e('Select Service Type','church_mgt');?></option>
										<?php 
										
										if(isset($_REQUEST['service_type_id']))
											$category =$_REQUEST['service_type_id'];  
										elseif($edit)
											$category =$result->service_type_id;
										else 
											$category = "";
										
										$activity_category=MJ_cmgt_get_all_category('service_type');
										if(!empty($activity_category))
										{
											foreach ($activity_category as $retrive_data)
											{
												echo '<option value="'.esc_attr($retrive_data->ID).'" '.selected($category,$retrive_data->ID).'>'.esc_attr($retrive_data->post_title).'</option>';
											}
										}?>
									</select>
								</div>
								<div class="col-md-4">
									<button class="btn btn-success width_100 btn_height" id="addremove" model="service_type"><?php _e('Add Or Remove','church_mgt');?></button>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required]" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo esc_attr($result->service_title);}elseif(isset($_POST['service_title'])) echo esc_attr($_POST['service_title']);?>" name="service_title">
									<label class="" for="service_title"><?php _e('Service Title','church_mgt');?><span class="require-field">*</span></label>
								</div>	
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input class="form-control validate[required] start_date" type="text"  name="start_date" value="<?php if($edit){ echo esc_attr(date("Y-m-d",strtotime($result->start_date)));}elseif(isset($_POST['start_date'])){ echo esc_attr($_POST['start_date']);}else{ echo esc_attr(date("Y-m-d")); }?>" autocomplete="off" readonly>
									<label for="activity_date"><?php esc_html_e('Service Start Date','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input class="form-control validate[required] end_date " type="text"   name="end_date" value="<?php if($edit){ echo esc_attr(date("Y-m-d",strtotime($result->end_date)));}elseif(isset($_POST['end_date'])) { echo esc_attr($_POST['end_date']);}else{ echo esc_attr(date("Y-m-d")); }?>" autocomplete="off" readonly>
									<label for="activity_date"><?php esc_html_e('Service End Date','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control ">
									<input  class="form-control placeholder_color timepicker start_time validate[required]" type="text"  placeholder="<?php echo _e('Service Start Time*' , 'church_mgt'); ?>"  name="start_time" value="<?php if($edit){ echo esc_attr($result->start_time);}elseif(isset($_POST['start_time'])) echo esc_attr($_POST['start_time']);?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control placeholder_color timepicker end_time validate[required]" type="text" placeholder="<?php echo _e('Service End Time*' , 'church_mgt'); ?>" name="end_time" value="<?php if($edit){ echo esc_attr($result->end_time);}elseif(isset($_POST['end_time'])) echo esc_attr($_POST['end_time']);?>">
								</div>
							</div>
						</div> 	
					</div>

					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Other Informaiton','church_mgt');?></p>
					</div>
					<div class="row"><!--Row Div--> 
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control text-input validate[custom[popup_category_validation]]" maxlength="50" type="text"  <?php if($edit){ ?>value="<?php echo esc_attr($result->other_title);}elseif(isset($_POST['other_title'])) echo esc_attr($_POST['other_title']);?>" name="other_title">
									<label class="" for="service_title"><?php _e('Other Title','church_mgt');?></label>
								</div>	
							</div>
						</div>
						<div class="col-md-6 input cmgt_display">
							<?php $other_type='';
							if($edit){ $other_type=$result->other_service_type;}elseif(isset($_POST['other_service_type'])){ $other_type=$_POST['other_service_type'];}?>
							<select name="other_service_type" class="form-control line_height_30px" id="service_type" >
								<option value="rehearsal" <?php selected($other_type,'rehearsal');?>><?php _e('Rehearsal','church_mgt');?></option>
								<option value="other" <?php selected($other_type,'other');?>><?php _e('Other','church_mgt');?></option>
							</select>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control other_service_date" type="text"  name="other_service_date" value="<?php if($edit){ echo esc_attr(date("Y-m-d",strtotime($result->other_service_date)));}elseif(isset($_POST['other_service_date'])){ echo esc_attr($_POST['other_service_date']);}else{ echo esc_attr(date("Y-m-d"));}?>" autocomplete="off" readonly>

									<label for="activity_date"><?php esc_html_e('Other Service Date','church_mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-sm-3 otehrservice">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control placeholder_color timepicker other_start_time" type="text"  name="other_start_time" placeholder="<?php _e('Start Time','church_mgt');?>" value="<?php if($edit){ echo esc_attr($result->other_start_time);}elseif(isset($_POST['other_start_time'])) echo esc_attr($_POST['other_start_time']);?>">
								</div>
							</div>
						</div>
						<div class="col-sm-3 otehrservice">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input class="form-control placeholder_color timepicker other_end_time" type="text" placeholder="<?php _e('End Time','church_mgt');?>"  name="other_end_time" value="<?php if($edit){ echo esc_attr($result->other_end_time);}elseif(isset($_POST['other_end_time'])) echo esc_attr($_POST['other_end_time']);?>">
								</div>
							</div>
						</div>
						<div class="col-md-6 mt-2">
							<?php wp_nonce_field( 'save_service_nonce' ); ?>
							<div class="offset-sm-0">
								<input id="save_family_member" type="submit" value="<?php if($edit){ _e('Save Service','church_mgt'); }else{ _e('Add Service','church_mgt');}?>" name="save_service" class="btn btn-success  col-md-12 save_btn"/>
							</div>
						</div>	
					</div>
				</div>
			</form><!-- SERVICES FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>