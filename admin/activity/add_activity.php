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
	
	$('.equipment_category').multiselect(
	{
		
		nonSelectedText :'<?php _e('Select Equipment','church_mgt');?>',
		selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
		allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: '<?php _e('Search for Equipment...','church_mgt');?>',
		templates: {
            button: '<button type="button" class="multiselect btn btn-default dropdown-toggle" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
        },
		buttonContainer: '<div class="dropdown" />'
	});
	
	$('.group_id').multiselect(
	{
		nonSelectedText :'<?php _e('Select Group','church_mgt');?>',
		selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
		allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: '<?php _e('Search for group...','church_mgt');?>',
		templates: {
            button: '<button type="button" class="multiselect btn btn-default dropdown-toggles" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
        },
		buttonContainer: '<div class="dropdown" />'
	});
	
	$('#capacity').keydown(function( e ) {
		if(e.which == 189 || e.which == 109)
        return false;
    });
	 $("#activity_date").datepicker({
       	dateFormat: "yy-mm-dd",
		minDate:0,
		autoclose: true,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#activity_end_date").datepicker("option", "minDate", dt);
        }
	    });
    $("#activity_end_date").datepicker({
      dateFormat: "yy-mm-dd",
	  autoclose: true,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 0);
            $("#activity_date").datepicker("option", "maxDate", dt);
        }
    });	  
	
    jQuery('#activity_yearly_repeat').datepicker({
			dateFormat: "yy-mm-dd",
			autoclose: true,
			minDate:'today',
			changeMonth: true,
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
	$("#record_start_time,#record_end_time").timepicki(
	{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false
	});
	$('#group_id').multiselect();
	
	$("#full_day").on('change',function()
	{
    if(this.checked)
	{
		$('#start_time').val('');
		$('#start_time').prop('disabled',true);
		$('#end_time').val('');
		$('#end_time').prop('disabled',true);
		$("#start_time").removeClass("validate[required]");
		$("#end_time").removeClass("validate[required]");
    }
	else
	{
		$('#start_time').prop('disabled',false);
		$('#end_time').prop('disabled',false);
		$('#start_time').timepicki(
		{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false
		});
		
		$('#end_time').timepicki(
		{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:1,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: false
		}); 
	
	}
});
	if($('#action').val()=='edit')
	{
	   if($("#full_day").is(':checked')) 
	    {

			$('#start_time').prop('readonly',true);
			$('#end_time').prop('readonly',true);
			$("#start_time").removeClass("timepicker");
			$("#start_time").removeClass("validate[required]");
			$("#end_time").removeClass("timepicker");
			$("#end_time").removeClass("validate[required]");
		}
		else
		{
			$('#start_time').timepicki(
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:1,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: false
			});
			$('#end_time').timepicki(
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:1,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: false
			});
			$('#start_time').prop('readonly',false);
			$('#end_time').prop('readonly',false);
		}
		
		var occurence=$("#reccurence").val();
		$("#occurence_type").text(occurence);
		if(occurence=='weekly')
		{
			$("#reccurence-div").show();
			$('#weekly_div').show();
			$('#monthly_div').hide();
			$('#yearly_div').hide();
		}
		if(occurence=='monthly')
		{
			$("#reccurence-div").show();
			$('#monthly_div').show();
			$('#weekly_div').hide();
			$('#yearly_div').hide();
		}
		if(occurence=='yearly')
		{
			$("#reccurence-div").show();
			$('#monthly_div').hide();
			$('#weekly_div').hide();
			$('#yearly_div').show();
		}	
		if(occurence=='daily')
		{
			$("#reccurence-div").show();
			$('#monthly_div').hide();
			$('#weekly_div').hide();
			$('#yearly_div').hide();
		}
		if(occurence=='none')
		{
			$("#reccurence-div").show();
			$('#monthly_div').hide();
			$('#weekly_div').hide();
			$('#yearly_div').hide();
		}
	}
	if($('#action').val()=='insert')
	{
	    $("#reccurence-div").hide();
        $('#start_time').timepicki(
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:1,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: false
			});
		$('#end_time').timepicki(
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:1,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: false
			});  
	}
	
	$("#reccurence").on('change',function() {
		
		var occurence=$(this).val();
		$("#occurence_type").text(occurence);
		if(occurence=='weekly')
		{
			$("#reccurence-div").show();
			$('#weekly_div').show();
			$('#monthly_div').hide();
			$('#yearly_div').hide();
			
		}
		if(occurence=='monthly')
		{
			$("#reccurence-div").show();
			$('#monthly_div').show();
			$('#weekly_div').hide();
			$('#yearly_div').hide();
		}
		if(occurence=='yearly')
		{
			$("#reccurence-div").show();
			$('#monthly_div').hide();
			$('#weekly_div').hide();
			$('#yearly_div').show();
		}	
		if(occurence=='daily')
		{
			$("#reccurence-div").show();
			$('#monthly_div').hide();
			$('#weekly_div').hide();
			$('#yearly_div').hide();
		}
		if(occurence=='none')
		{
			$("#reccurence-div").show();
			$('#monthly_div').hide();
			$('#weekly_div').hide();
			$('#yearly_div').hide();
		}
	});
	
	
	 //add vanue ajax
	    $('#vanue_form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#vanue_form').validationEngine('validate');
		if (valid == true) 
		{
			$('.modal').modal('hide');
		    $.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			success: function(data)
			{
				 if(data!=""){ 
					$('#venue_id').append(data);
					$('#vanue_form').trigger("reset");
					$('.modal').modal('hide'); 
				} 
			},
			error: function(data){
			}
		   })
		} 
	});
	//add group poupup ajax
	
	$('#group_form').on('submit', function(e) 
	{
		e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#group_form').validationEngine('validate');
		var group = $('.group_id').multiselect(); 
		if (valid == true)
			
	    {
			$.ajax({
					type:"POST",
					url: $(this).attr('action'),
					data:form,
					success: function(data)
					{
						$('.group_id').append(data);
						group.multiselect('rebuild');
						$('#group_form').trigger("reset");
						$('.modal').modal('hide');
							
					},
					error: function(data){
						
					}
				})
		}
	});
	
});
</script>
<?php 	
if($active_tab == 'addactivity')
{
	//----- EDIT ACTIVITY -------//
    $activity_id=0;
	if(isset($_REQUEST['activity_id']))
		$activity_id=sanitize_text_field($_REQUEST['activity_id']);
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_activity->MJ_cmgt_get_single_activity($activity_id);	
		}
?>
		
    <div class="panel-body"><!-- PANEL BODY DIV START-->
		<form name="activity_form" action="" method="post" class="form-horizontal" id="activity_form"><!-- ACTIVITY FORM START-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
			<input type="hidden" name="activity_id" value="<?php echo esc_attr($activity_id);?>"  />
			<div class="form-body user_form">
				<div class="row cmgt-addform-detail">
					<p><?php esc_html_e('Activity Information','church_mgt');?></p>
				</div>
				<div class="row">
					<div class="col-md-6 cmgt_display">
						<div class="form-group input row margin_buttom_0">
							<div class="col-md-8">
								<label class="ml-1 custom-top-label top" for="activity_category"><?php esc_html_e('Activity Category','church_mgt');?><span class="require-field">*</span></label>
									<select class="form-control line_height_30px validate[required]" name="activity_cat_id" id="activity_category" >
									<option value=""><?php _e('Select Activity Category','church_mgt');?></option>
									<?php 
									if($edit)
										$category =$result->activity_cat_id;
									elseif(isset($_REQUEST['activity_cat_id']))
										$category =$_REQUEST['activity_cat_id'];  
									else 
										$category = "";
									
									$activity_category=MJ_cmgt_get_all_category('activity_category');
									if(!empty($activity_category))
									{
										foreach ($activity_category as $retrive_data)
										{
											echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
										}
									}?>
									</select>
							</div>
							<div class="col-sm-4">
								<button type="button" id="addremove" model="activity_category" class="btn btn-success width_100 btn_height"> <?php _e('Add Or Remove','church_mgt');?></button>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="activity_title" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->activity_title);}elseif(isset($_POST['activity_title'])) echo esc_attr($_POST['activity_title']);?>" 
								name="activity_title">
								<label class="" for="activity_title"><?php _e('Activity Title','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="speaker" class="form-control text-input validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50" <?php if($edit){ ?>value="<?php echo esc_attr($result->speaker_name);}elseif(isset($_POST['speaker'])) echo esc_attr($_POST['speaker']);?>" name="speaker">
								<label class="" for="speaker"><?php _e('Guest Speaker','church_mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 cmgt_display">
						<div class="form-group input row margin_buttom_0">
							<div class="col-md-8">
								<label class="ml-1 custom-top-label top" for="venue"><?php _e('Venue','church_mgt');?><span class="require-field">*</span></label>
								<select class="form-control validate[required]" name="venue_id" id="venue_id">
									<option value=""><?php _e('Select Venue','church_mgt');?></option>
									<?php 
									if($edit)
										$venue =$result->venue_id;
									elseif(isset($_REQUEST['venue_id']))
										$venue =$_REQUEST['venue_id'];  
									else 
										$venue = "";
									$venuedata=$obj_venue->MJ_cmgt_get_all_venue();
									if(!empty($venuedata))
									{
										foreach ($venuedata as $retrive_data)
										{
											echo '<option value="'.$retrive_data->id.'" '.selected($venue,$retrive_data->id).'>'.$retrive_data->venue_title.'</option>';
										}
									}?>
								</select>
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-success button_top width_100 btn_height" id="cmgt_addremove" data-bs-toggle="modal" data-bs-target="#myModal_Add_vanue"> <?php _e('Add Venue','church_mgt');?></button>
							</div>
						</div>
					</div>
					<div class="col-md-6 margin_bottom_15">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="activity_date" class="form-control validate[required]" type="text"   name="activity_date"  
								value="<?php if($edit){ echo esc_attr($result->activity_date);}elseif(isset($_POST['activity_date'])){ echo esc_attr($_POST['activity_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
								<label for="activity_date"><?php _e('Activity Start Date','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
						<div class="col-md-12 form-control">
								<input id="activity_end_date" class="form-control validate[required]" type="text"  name="activity_end_date"   
								value="<?php if($edit){ echo esc_attr($result->activity_end_date);}elseif(isset($_POST['activity_end_date'])){ echo esc_attr($_POST['activity_end_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
								<label for="activity_date"><?php _e('Activity End Date','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-md-12 form-control input_height_48px">
								<div class="row padding_radio">
									<div class="input-group">
										<label class="custom-top-label margin_left_0" for="all_day"><?php esc_html_e('All Day','church_mgt');?></label>	
										<div class="checkbox checkbox_lebal_padding_8px rtl_add_active">
											<label>
												<input class="form-control cmgt_volunteer_bg mt-1" id="full_day" type="checkbox" name="full_day" value="yes" <?php if($edit){ if($result->activity_start_time=='Full Day'){?> checked <?php } } ?>><label class="mt-1 px-2 cmgt_input_checkbox_label" for="Volunteer"><?php esc_html_e('All Day','church_mgt');?></label>	
											</label>
										</div>
									</div>												
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
						<div class="col-md-12 form-control">
								<input id="start_time" class="form-control placeholder_color validate[required]  timepicker" type="text" placeholder="<?php _e('Activity Start Time*','church_mgt');?>" name="start_time" value="<?php if($edit){ echo esc_attr($result->activity_start_time);}elseif(isset($_POST['start_time'])) echo esc_attr($_POST['start_time']);?>">
							</div>
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group input">
						<div class="col-md-12 form-control">
								<input id="end_time" class="form-control placeholder_color validate[required]  timepicker " type="text" placeholder="<?php _e('Activity End Time*','church_mgt');?>" name="end_time" value="<?php if($edit){ echo esc_attr($result->activity_end_time);}elseif(isset($_POST['end_time'])) echo esc_attr($_POST['end_time']);?>">
							</div>
						</div>	
					</div>
					
					<?php 
					if($edit)
					{
						$reccurence=json_decode($result->recurrence_content,true);
						
						if($reccurence['selected']=='monthly')
						{
							$monthly_data=$reccurence['monthly'];
							$repeat_time=$monthly_data['repeat_time'];
						}
						if($reccurence['selected']=='weekly')
						{
						
							$weekly_data=$reccurence['weekly'];
							$repeat_time=$weekly_data['repeat_time'];
						}
						if($reccurence['selected']=='yearly')
						{
							$yearly_data=$reccurence['yearly'];
							$repeat_time=$yearly_data['repeat_time'];
						}
						if($reccurence['selected']=='daily')
						{
							$daily_data=$reccurence['daily'];
							$repeat_time=$daily_data['repeat_time'];
						}
						if($reccurence['selected']=='none')
						{
							if(!empty($reccurence['repeat_time']))
							{
								$repeat_time=$reccurence['repeat_time'];
							}
						}
					}
					?>
					<div class="col-md-6 input cmgt_display">
						<label class="ml-1 custom-top-label top" for="reservation_date"><?php _e('Recurrence','church_mgt');?><span class="require-field">*</span></label>
						<?php if($edit)
							$reccurence=$reccurence['selected'];
							elseif(isset($_POST['reccurence'])) 
							$reccurence=$_POST['reccurence'];
							else
								$reccurence='none'; ?>
						<select class="form-control" name="reccurence" id="reccurence">
							
							<option value="none" <?php selected($reccurence,'none');?>><?php _e('None','church_mgt');?></option>
							<option value="daily" <?php selected($reccurence,'daily');?>><?php _e('Daily','church_mgt');?></option>
							<option value="weekly" <?php selected($reccurence,'weekly');?>><?php _e('Weekly','church_mgt');?></option>
							<option value="monthly" <?php selected($reccurence,'monthly');?>><?php _e('Monthly','church_mgt');?></option>
							<option value="yearly" <?php selected($reccurence,'yearly');?>><?php _e('Yearly','church_mgt');?></option>
						</select>
					</div>
				</div> 
				<div class="form-group">
					<div class="row">
						<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label form-label" for=""><?php ?></label>
						<div class="col-sm-6" id="reccurence-div">
							 
							<div class="col-sm-12" id="weekly_div">
								<input class="form-control" type="checkbox" name="weekly[mon]" <?php
								if($edit)
								{ 
									if(is_array(isset($weekly_data['weekly']) && $weekly_data['weekly']) && in_array('Monday',$weekly_data['weekly'])){?>  checked<?php }}  ?> value="Monday"><label class="day_name"><?php _e('&nbsp;Mon','church_mgt');?></label>
								<input class="form-control" type="checkbox" name="weekly[tue]" <?php if($edit) { if(is_array(isset($weekly_data['weekly']) && $weekly_data['weekly']) && in_array('Tuesday',$weekly_data['weekly'])){?> checked <?php } }?> value="Tuesday"><label class="day_name"><?php _e('&nbsp;Tue','church_mgt');?></label>
								<input class="form-control" type="checkbox" name="weekly[wed]" <?php if($edit) { if(is_array(isset($weekly_data['weekly']) && $weekly_data['weekly']) && in_array('Wednesday',$weekly_data['weekly'])){?> checked <?php } }?> value="Wednesday"><label class="day_name"><?php _e('&nbsp;Wed','church_mgt');?></label>
								<input class="form-control" type="checkbox" name="weekly[thu]" <?php if($edit) { if(is_array(isset($weekly_data['weekly']) && $weekly_data['weekly']) && in_array('Thursday',$weekly_data['weekly'])){?> checked <?php } } ?> value="Thursday"><label class="day_name"><?php _e('&nbsp;Thu','church_mgt');?></label>
								<input class="form-control" type="checkbox" name="weekly[fri]" <?php if($edit) { if(is_array(isset($weekly_data['weekly']) && $weekly_data['weekly']) && in_array('Friday',$weekly_data['weekly'])){?> checked <?php } }?> value="Friday"><label class="day_name"><?php _e('&nbsp;Fri','church_mgt');?></label>
								<input class="form-control" type="checkbox" name="weekly[sat]" <?php if($edit) { if(is_array(isset($weekly_data['weekly']) && $weekly_data['weekly']) && in_array('Saturday',$weekly_data['weekly'])){?> checked <?php } } ?> value="Saturday"><label class="day_name"><?php _e('&nbsp;Sat','church_mgt');?></label>
								<input class="form-control" type="checkbox" name="weekly[sun]" <?php if($edit){ if(is_array(isset($weekly_data['weekly']) && $weekly_data['weekly']) && in_array('Sunday',$weekly_data['weekly'])){?> checked <?php } } ?> value="Sunday"><label class="day_name"><?php _e('&nbsp;Sun','church_mgt');?></label>
							</div>
							
							
							<div class="col-sm-12 monthly_div" id="monthly_div">
								<label class="col-sm-3 control-label" for=""><?php _e('Repeat Date','church_mgt');?></label>
								<div class="col-sm-6 date_text margin_bottom_15">
								<input type="number" minlength="2" min="1" max="31" value="<?php if($edit && $reccurence=='monthly') echo $monthly_data['month_date'];?>"  name="month_date" class="form-control"></div>
							</div>
							<div class="col-sm-12 monthly_div" id="yearly_div">
								<label class="col-sm-3 control-label" for=""><?php _e('Repeat Date','church_mgt');?></label>
								<div class="col-sm-5 date_text">
									<input id="activity_yearly_repeat" class="form-control" type="text"  name="yearly_date" 
									value="<?php if($edit && $reccurence=='yearly'){ echo date("Y-m-d",strtotime($yearly_data['yearly_date']));}elseif(isset($_POST['yearly_date'])) echo $_POST['yearly_date'];?>" autocomplete="off" readonly>
								</div>
							</div>
						</div>
					</div>	
				</div>
				
				<div class="row cmgt-addform-detail">
					<p><?php esc_html_e('Other Information','church_mgt');?></p>
				</div>
				<div class="row"><!--Row Div--> 
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="record_start_time" class="form-control placeholder_color timepicker" type="text" placeholder="<?php _e('Record Start Time','church_mgt');?>" name="record_start_time" value="<?php if($edit){ echo $result->record_start_time;}elseif(isset($_POST['record_start_time'])) echo $_POST['record_start_time'];?>">
							</div>
						</div>
					</div>	
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="record_end_time" class="form-control placeholder_color timepicker" type="text" placeholder="<?php _e('Record End Time','church_mgt');?>"  name="record_end_time" value="<?php if($edit){ echo $result->record_end_time;}elseif(isset($_POST['record_end_time'])) echo $_POST['record_end_time'];?>">
							</div>
						</div>	
					</div>
					<div class="col-md-6 cmgt_display">
						<div class="form-group input row margin_buttom_0">
							<div class="col-md-8 input">
									<?php $groups_array = array();
									if($edit){  
									$groups_array =(explode(",",$result->groups));
									}
									?>
									<select  name="group_id[]" multiple="multiple" class="form-control group_id" style="overflow-y: scroll;height: 60px;">
									<?php $groupdata=$obj_group->MJ_cmgt_get_all_groups();
									if(!empty($groupdata))
									{
										foreach ($groupdata as $group){?>
											<option value="<?php echo $group->id;?>" <?php if(in_array($group->id,$groups_array)) echo 'selected';  ?>><?php echo $group->group_name; ?> </option>
								<?php } } ?>
								</select>
							</div>
							<!--ADD Group POPUP BUTTON -->
							<div class="col-sm-4 otehrservice1 rtl_add_remove_btn">
								<button type="button" class="btn btn-success width_100 btn_height" id="cmgt_addremove" data-bs-toggle="modal" data-bs-target="#myModal_Add_group"> <?php _e('Add Group','church_mgt');?></button>
							</div>
						</div>	
					</div>
				</div>
				<div class="row">
					<?php wp_nonce_field( 'save_actvity_nonce' ); ?>
					<div class="col-md-6 mt-2">
						<input type="submit" value="<?php if($edit){ _e('Save Activity','church_mgt'); }else{ _e('Add Activity','church_mgt');}?>" name="save_actvity" class="btn btn-success col-md-12 save_btn"/>
					</div>
				</div>
			</div>
		</form><!--Activity FORM END-->
    </div><!-- PANEL BODY DIV END-->
	<?php 
}
?>

<!-----   Add vanue in Member popupform --->

<div class="modal fade cmgt_main_modal" id="myModal_Add_vanue" tabindex="-1" aria-labelledby="myModal_Add_vanue" aria-hidden="true" role="dialog"><!-- MAIN MODAL DIV START-->
	<div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START-->
        <div class="modal-content"><!-- MODAL CONTENT DIV START-->
			<div class="modal-header">
			  <h3 class="modal-title"><?php _e('Add Venue','church_mgt');?>
			  	<a href="#" class="btn float-end mt-2 badge badge-danger rtl_float_left" data-bs-dismiss="modal">X</a></h3>
			</div>
			<div class="modal-body"><!-- MODAL BODY DIV START-->
				<form name="vanue_form"action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="" id="vanue_form">
					<input type="hidden" name="action" value="MJ_cmgt_add_vanue_popup">

					<div class="form-body user_form">
						<div class="row cmgt-addform-detail">
							<p><?php esc_html_e('Venue Information','church_mgt');?></p>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="venue_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if(isset($_POST['venue_title'])){ echo esc_attr($_POST['venue_title']); }?>" name="venue_title">
										<label class="" for="activity_title"><?php _e('Venue Title','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group input">
									<div class="form-group">
									<div class="col-md-12 form-control">
											<input id="capacity" class="form-control validate[required,custom[onlyNumber]] text-input" type="text" maxlength="3" value="<?php if(isset($_POST['capacity'])){ echo esc_attr($_POST['capacity']);}?>" name="capacity">
											<label class="" for="capacity"><?php _e('Capacity Seats','church_mgt');?><span class="require-field">*</span></label>
										</div>	
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group input">
									<div class="form-group">
									<div class="col-md-12 form-control">
											<input id="request_days" class="form-control validate[required,custom[onlyNumber]] text-input" maxlength="2" type="text" value="<?php if(isset($_POST['request_days'])){ echo esc_attr($_POST['request_days']); }?>" name="request_days">
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
											
											// if($edit)
											// {  
											// 	$equipment_array =(explode(",",$result->equipments));
											// }
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
									<div class="col-sm-4 otehrservice1 rtl_add_remove_btn">
										<button type="button" id="addremove" class="otehrservice1 btn btn-success utton_top width_100 btn_height" model="equipment_category"> <?php _e('Add','church_mgt');?></button>
									</div>
								</div>	
							</div>
							<div class="col-md-6">
								<div class="form-group rtl_vaneu_pop">
									<div class="mb-3 row">
										<label for="multiple_reservation" class="control-label form-label"></label>
										<div class="col-sm-12 col-xs-12 ">
											<div class="checkbox col-xs-12 d-flex">
											<input class="mt-1" type="checkbox"  value="yes" name="multiple_sreservation">
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
  
 <!-----   Add group in Member popupform --->
<div class="modal fade cmgt_main_modal" id="myModal_Add_group" tabindex="-1" aria-labelledby="myModal_Add_group" aria-hidden="true" role="dialog"><!-- MAIN MODAL DIV START-->
	<div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START-->
        <div class="modal-content"><!-- MODAL CONTENT DIV START-->
			<div class="modal-header">
			  <h3 class="modal-title"><?php _e('Add Group','church_mgt');?> 
			  	<a href="#" class="float-end mt-2 badge badge-danger rtl_float_left" data-bs-dismiss="modal">X</a></h3>
			</div>
			<div class="modal-body"><!-- MODAL BODY DIV START-->
				<form name="group_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="cmgt_form_horizontal form-horizontal" id="group_form" enctype="multipart/form-data"><!-- GROUP FORM START-->
					<input type="hidden" name="action" value="MJ_cmgt_add_group_popup">

					<div class="form-body user_form"> <!--Card Body div-->  
						<div class="row cmgt-addform-detail">
							<p><?php esc_html_e('Group Information','church_mgt');?></p>
						</div> 
						<div class="row"><!--Row Div--> 
							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input type="text" maxlength="50" id="group_name"  class="form-control validate[required,custom[popup_category_validation]]" name="group_name" >
										<label for="group_name"><?php _e('Group Name','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div> 
							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control upload-profile-image-patient">	
										<label for="gmgt_membershipimage" class="custom-control-label custom-top-label ml-2"><?php _e('Group Image','church_mgt');?></label>
										<button id="upload_user_avatar_button" class="browse btn btn-success for_btn_grp1 community_button_disabled upload_user_cover_button upload-profile-image-patient" data-toggle="modal" data-target="#image_upload" type="button"><?php _e('Choose Image','church_mgt');?></button>
										<input type="hidden" id="cmgt_user_avatar_url" name="cmgt_groupimage" onchange="fileCheck(this);" value="<?php if($edit){ echo esc_attr($result->cmgt_groupimage);}elseif(isset($_POST['cmgt_groupimage'])) echo esc_attr($_POST['cmgt_groupimage']);?>">
									</div>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<div id="upload_user_avatar_preview">
											<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_group_logo' )); ?>">	
										</div>
									</div>
								</div>
							</div>   
						</div>
						<div class="row">
							<div class="col-md-6">
								<?php wp_nonce_field('save_group_nonce' ); ?>
								<div class="offset-sm-0">
									<input type="submit" id="submit"  value="<?php _e('Save','church_mgt');?>" name="save_group" class="btn btn-success col-md-12 save_btn"/>
								</div>
							</div>
						</div>	
					</div>  
				</form><!-- GROUP FORM END-->
			</div><!-- PANEL BODY DIV END-->
        </div><!-- MODAL CONTENT DIV END-->
    </div><!-- MODAL DIALOG DIV END-->
</div><!-- MAIN MODAL DIV END-->