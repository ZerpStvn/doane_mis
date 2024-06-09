<?php 
$role='member';
?>
<style>
.open>.dropdown-menu {
   top: auto;
   bottom: 100%;
   width: 259px;
   height: 200px;
   overflow: auto;
   padding: 0px;
} 
.multiselect-container input[type="text"]::placeholder {
  color: black;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
        $('.multiselect-native-select .btn-group > button').attr('data-bs-target','dropdown');
});
$(document).ready(function()
{
    $('#member_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
   jQuery('.birth_date').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate : 0,
		changeMonth: true,
	    changeYear: true,
		autoclose: true,
	    yearRange:'-100:+25',
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
	$('.ministry_id').multiselect(
	{
		nonSelectedText :'<?php esc_html_e('Select Ministry','church_mgt');?>',
		selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
		allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: '<?php esc_html_e('Search for ministry...','church_mgt');?>',
		templates: {
				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
			buttonContainer: '<div class="dropdown" />'
	});
	
	$('.group_id').multiselect(
	{
	nonSelectedText :'<?php esc_html_e('Select Group','church_mgt');?>',
	selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
	allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
	includeSelectAllOption: true,
	enableFiltering: true,
	enableCaseInsensitiveFiltering: true,
    filterPlaceholder: '<?php esc_html_e('Search for group...','church_mgt');?>',
	templates: {
            button: '<button type="button" class="multiselect btn btn-default dropdown-toggle" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
        },
		buttonContainer: '<div class="dropdown" />'
	});
    var date = new Date();
	date.setDate(date.getDate()-0);
	jQuery('.date').datepicker({
			dateFormat: "yy-mm-dd",
			changeMonth: true,
	        changeYear: true,
			autoclose: true,
	        yearRange:'-100:+25',
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
   //add group poupup ajax//
	$('#group_form').on('submit', function(e)
	{
		e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#group_form').validationEngine('validate');
		var group = $('.group_id').multiselect(); 
		if (valid == true) {
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
	//add ministary_popup ajax//
	$('#ministary_form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#ministary_form').validationEngine('validate');
		var ministry_id = $('.ministry_id').multiselect(); 
		if (valid == true) {
		$.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			success: function(data)
			{
				$('.ministry_id').append(data);
				ministry_id.multiselect('rebuild');
				$('#ministary_form').trigger("reset");
				$('.modal').modal('hide');
					
			},
			error: function(data){
				
			}
		})
		}
	});
	//username not  allow space validation
		$('.username').keypress(function( e ) 
		{
		   if(e.which === 32) 
			return false;
		});
	});	
</script>
    <?php 	 
	if($active_tab == 'addmember')
	{
		$member_id=0;
		if(isset($_REQUEST['member_id']))
		{
			$member_id=$_REQUEST['member_id'];
		}
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$user_info = get_userdata($member_id);
		}
		else
		{
		//Count Member Id //
		$lastmember_id=MJ_cmgt_get_lastmember_id($role);
		$nodate=substr($lastmember_id,0,-4);
		$memberno=substr($nodate,1);
		//$memberno+=1;
		$add="1";
		$test=(int)$memberno+(int)$add;
		$newmember='M'.$test.date("my");
		} 
		?>
		<div class="panel-body"><!-- Panel Body Div   -->
			<form name="member_form" action="" method="post" class="cmgt_add_mt_35" id="member_form" autocomplete="off"> <!-- Member Form -->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
				<input type="hidden" name="user_id" value="<?php echo esc_attr($member_id);?>"  />

				<div class="form-body user_form"> <!--Card Body div-->   
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Personal Information','church_mgt');?></p>
					</div>
					<div class="row"><!--Row Div--> 
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required]" type="text" value="<?php if($edit){ echo esc_attr($user_info->member_id);}else echo esc_attr($newmember);?>"readonly  name="member_id">
									<label for="member_id"><?php esc_html_e('Member Id','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text" maxlength="50" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
									<label for="first_name"><?php esc_html_e('First Name','church_mgt');?><span class="require-field">*</span></label>
								</div>	
							</div>
						</div>
						<div class="col-md-6 margin_bottom_15">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
									<label for="middle_name"><?php esc_html_e('Middle Name','church_mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text" maxlength="50"  <?php if($edit){ ?>value="<?php echo esc_attr($user_info->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>" name="last_name">
									<label for="last_name"><?php esc_html_e('Last Name','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 rtl_margin_top_15px">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0" for="gender"><?php esc_html_e('Gender','church_mgt');?></label>
											<div class="d-inline-block">
												<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
												<input type="radio" value="male" name="gender" class="custom-control-input tog space_radio" <?php  checked( 'male', $genderval);  ?> id="male">
												<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male','church_mgt');?></label>
												<input type="radio" value="female" name="gender" class="custom-control-input tog space_radio" <?php  checked( 'female', $genderval);  ?> id="female">
												<label class="custom-control-label" for="female"><?php esc_html_e('Female','church_mgt');?></label>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="birth_date" class="form-control validate[required] birth_date" type="text" name="birth_date"  
									value="<?php if($edit){ echo esc_attr($user_info->birth_date);}elseif(isset($_POST['birth_date'])){ echo esc_attr($_POST['birth_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
									<label for="birth_date"><?php esc_html_e('Date of Birth','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 rtl_margin_top_15px">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0" for="marital-status"><?php esc_html_e('Marital Status','church_mgt');?></label>
											<div class="d-inline-block">
												<?php $marital_val = "unmarried"; if($edit){ $marital_val=$user_info->marital_status; }elseif(isset($_POST['marital_status'])) {$marital_val=$_POST['marital_status'];}?>
												<input type="radio" value="unmarried" name="marital_status" class="custom-control-input tog space_radio" <?php  checked( 'unmarried', $marital_val);  ?>>
												<label class="custom-control-label margin_right_20px"><?php esc_html_e('Unmarried','church_mgt');?></label>

												<input type="radio" value="married" name="marital_status" class="custom-control-input tog space_radio" <?php  checked( 'married', $marital_val);  ?>>
												<label class="custom-control-label margin_right_20px"><?php esc_html_e('Married','church_mgt');?></label>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
						<div class="col-md-6 input medicine_select_btn">
							<?php 
								$joinminisrty_list = $obj_member->MJ_cmgt_get_all_joinministry($member_id);
								$ministry_array = $obj_member->MJ_cmgt_convert_grouparray($joinminisrty_list);
							?>
							
							<select  class="form-control ministry_id" id="ministry_id" name="ministry_id[]" multiple>					
								<?php 
									$ministrydata=$obj_ministry->MJ_cmgt_get_all_ministry();
									if(!empty($ministrydata))
									{
										foreach ($ministrydata as $ministry)
										{
											?>
										<option value="<?php echo $ministry->id;?>" <?php if(in_array($ministry->id,$ministry_array)) echo 'selected';  ?>><?php echo $ministry->ministry_name; ?></option>
										<?php 
										} 
									} ?>
							</select>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input class="form-control validate[custom[onlyLetterAccentSp]]" type="text" maxlength="50"  <?php if($edit){ ?>value="<?php echo esc_attr($user_info->occupation);}elseif(isset($_POST['occupation'])) echo esc_attr($_POST['occupation']);?>" name="occupation">
									<label for="occupation"><?php esc_html_e('Occupation','church_mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 input medicine_select_btn">
							<?php 
							$joingroup_list = $obj_member->MJ_cmgt_get_all_joingroup($member_id);
							$groups_array = $obj_member->MJ_cmgt_convert_grouparray($joingroup_list);
							?>
							<select  class="form-control group_id" id="group_id" name="group_id[]" multiple="multiple">					
									<?php $groupdata=$obj_group->MJ_cmgt_get_all_groups();
									if(!empty($groupdata))
									{
										foreach ($groupdata as $group)
										{?>
											<option value="<?php echo $group->id;?>" <?php if(in_array($group->id,$groups_array)) echo 'selected';  ?>><?php echo $group->group_name; ?></option>
											<?php   
										} 
									} 
									?>
							</select>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[custom[address_description_validation]]" type="text" maxlength="50"   <?php if($edit){ ?>value="<?php echo esc_attr($user_info->education);}elseif(isset($_POST['education'])) echo esc_attr($_POST['education']);?>" name="education">
									<label for="education"><?php esc_html_e('Education','church_mgt');?></label>
								</div>
							</div>
						</div>
					</div>

					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Contact Information','church_mgt');?></p>
					</div>

					<div class="row"><!--Row Div--> 
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required,cusom[address_description_validation]]" maxlength="150" type="text"  name="address" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
									<label for="address"><?php esc_html_e('Address','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->city_name);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
									<label for="city_name"><?php esc_html_e('City','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-5 col-lg-4">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input type="text" value="<?php if($edit) { if(!empty($user_info->phonecode)){ echo esc_attr($user_info->phonecode); }else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }}else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); } ?>"  class="form-control pl-4 mobile validate[required] onlynumber_and_plussign" maxlength="5" name="phonecode">
											<label for="country_code" class="pl-2 cmgt_country_code"><?php esc_html_e('Country Code','church_mgt');?><span class="required red">*</span></label>
											<div class="pos_mobile  form-control-position nf_left_icon">
												<i class="ft-plus"></i>
											</div>
										</div>											
									</div>
								</div>
								<div class="col-md-7 col-lg-8">
									<div class="form-group input">
										<div class="col-md-12 form-control cmgt_mobile_error">
											<input  class="form-control validate[required,custom[phone]] text-input" type="text" minlength="6" maxlength="15"  name="mobile" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->mobile);}elseif(isset($_POST['mobile'])) echo esc_attr($_POST['mobile']);?>">
											<label for="mobile"><?php esc_html_e('Mobile Number','church_mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[custom[phone]] text-input"  minlength="6" maxlength="15" type="text"  name="phone" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->phone);}elseif(isset($_POST['phone'])) echo esc_attr($_POST['phone']);?>">
									<label for="phone"><?php esc_html_e('Phone','church_mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[custom[phone_number]]" maxlength="30" type="text" name="fax_number" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->fax_number);}elseif(isset($_POST['fax_number'])) echo esc_attr($_POST['fax_number']);?>">
									<label for="fax_number"><?php esc_html_e('Fax','church_mgt');?></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[custom[username_validation]]" maxlength="50" type="text"  name="skyp_id" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->skyp_id);}elseif(isset($_POST['skyp_id'])) echo esc_attr($_POST['skyp_id']);?>">
									<label for="skype_id"><?php esc_html_e('Skype Id','church_mgt');?></label>
								</div>
							</div>	
						</div>
					</div>
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Religion Information','church_mgt');?></p>
					</div>
					<div class="row"><!--Row Div--> 
						<div class="col-md-6 margin_bottom_15">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required] date" type="text" name="begin_date" 
									<?php if($edit){ ?>value="<?php echo esc_attr(date("Y-m-d",strtotime($user_info->begin_date)));}elseif(isset($_POST['begin_date'])) echo esc_attr($_POST['begin_date']);?>" autocomplete="off" readonly>
									<label for="begin_date"><?php esc_html_e('Join Church Date','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required] date" type="text" name="baptist_date" <?php if($edit){ ?>value="<?php echo esc_attr(date("Y-m-d",strtotime($user_info->baptist_date)));}elseif(isset($_POST['baptist_date'])) echo esc_attr($_POST['baptist_date']);?>" autocomplete="off" readonly>
									<label for="baptist_date"><?php esc_html_e('Baptist Date','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>	
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12 form-control input_height_48px">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0 " for="Volunteer"><?php esc_html_e('Volunteer','church_mgt');?></label>		
											<div class="checkbox checkbox_lebal_padding_8px rtl_add_member">
												<label>
													<input id="cmgt_volunteer_bg" class="form-control cmgt_volunteer_bg mt-1 rtl_checkbox_mt_5px" type="checkbox" name="volunteer" value="yes" <?php if($edit){ if($user_info->volunteer=='yes'){?> checked <?php } } ?>><label class="mt-1 px-2 cmgt_input_checkbox_label" for="Volunteer"><?php esc_html_e('Volunteer','church_mgt');?></label>	
													<!-- <?php 
													if($volunteer=='yes')
													{
														 echo "Yes";
													}else{
														echo "No";
													} ?> -->
												</label>
											</div>
										</div>												
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cmgt-addform-detail mt-3">
						<p><?php esc_html_e('Login Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required,custom[email]]" maxlength="100" type="text" name="email" 
									<?php if($edit){ ?>value="<?php echo esc_attr($user_info->user_email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
									<label for="email"><?php esc_html_e('Email','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input class="form-control validate[required,custom[username_validation]] username" type="text" maxlength="50"  name="username" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->user_login);}elseif(isset($_POST['username'])) echo esc_attr($_POST['username']);?>" <?php if($edit) echo "readonly";?>>
									<label for="username"><?php esc_html_e('User Name','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control <?php if(!$edit) echo 'validate[required,minSize[8]]';?>"  maxlength="12" type="password"  name="password">
									<label for="password"><?php esc_html_e('Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
								</div>
							</div>	
						</div>
					</div>

					<div class="row cmgt-addform-detail">
						<p><?php _e('Profile Image','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control upload-profile-image-patient">	
									<label for="gmgt_membershipimage" class="custom-control-label custom-top-label ml-2"><?php _e('Upload Profile Image','church_mgt');?></label>
									<div class="col-sm-12 display_flex">
										<input type="text" id="cmgt_user_avatar_url" class="form-control" name="cmgt_user_avatar"  
										value="<?php if($edit)echo esc_url( $user_info->cmgt_user_avatar );elseif(isset($_POST['cmgt_user_avatar'])) echo $_POST['cmgt_user_avatar']; ?>" readonly />
										<input id="upload_user_avatar_button" type="button" class="btn btn-success" style="float: right;" value="<?php esc_html_e( 'Choose image', 'church_mgt' ); ?>" />
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div id="upload_user_avatar_preview">
										<?php
										if($edit) 
										{
											if($user_info->cmgt_user_avatar == "")
											{ ?>
												<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_member_thumb' )); ?>">
												<?php 
											}
											else 
											{
												?>
												<img class="image_preview_css" src="<?php if($edit) echo esc_url( $user_info->cmgt_user_avatar ); ?>" />
												<?php 
											}
										}
										else
										{
											?>
												<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_member_thumb' )); ?>">
											<?php 
										}   ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<?php wp_nonce_field( 'save_member_nonce' ); ?>
						<input type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Add Member','church_mgt');}?>" name="save_member" class="btn btn-success col-md-12 save_btn button_space_r"/>
					</div>
				</div>
			</form> <!-- Member Form END -->
		</div><!-- PANEL BODY DIV END-->	
			 <?php 
	}
	?>
			<!-----   Add Ministary in Member popupform --->
		<div class="modal fade" id="myModal_Add_Ministary" tabindex="-1" aria-labelledby="myModal_Add_Ministary" aria-hidden="true" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content" style="top:30px">
					<div class="modal-header">
						<h3 class="modal-title"><?php esc_html_e('Add Ministry','church_mgt');?></h3>
						<button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body"><!-- PANEL BODY DIV START-->
						<form name="ministary_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="ministary_form">
							<input type="hidden" name="action" value="MJ_cmgt_add_ministry_popup">
							<div class="form-group">
								<div class="mb-3 row">
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="ministry_name"><?php esc_html_e('Ministry Name','church_mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<input id="ministry_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->ministry_name);}elseif(isset($_POST['ministry_name'])) echo esc_attr($_POST['ministry_name']);?>" name="ministry_name">
									</div>
								</div>	
							</div>
							<div class="form-group">
								<div class="mb-3 row">
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="gmgt_membershipimage"><?php esc_html_e('Ministry Image','church_mgt');?></label>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<input type="text" id="" name="cmgt_ministryimage"  class="cmgt_user_avatar_url"
										value="<?php if(isset($_POST['cmgt_ministryimage'])) echo $_POST['cmgt_ministryimage'];?>" />	
										<input id="" type="button" class="button upload_user_avatar_button button_top" value="<?php esc_html_e( 'Upload Cover Image', 'church_mgt' ); ?>" />
										<span class="description"><?php esc_html_e('Upload Ministry Image', 'church_mgt' ); ?></span>
										<div id="" class="upload_user_avatar_preview" style="min-height: 100px;">
											<img style="max-width:25%; margin-top:10px" 
											src="<?php if(isset($_POST['cmgt_ministryimage'])) echo $_POST['cmgt_ministryimage']; else echo get_option( 'cmgt_system_logo' );?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="offset-sm-2 col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<input type="submit" value="<?php esc_html_e('Save','church_mgt'); ?>" name="save_ministry" class="btn btn-success"/>
							</div>
						</form>
					</div><!-- PANEL BODY DIV END-->
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php esc_html_e('Close','church_mgt'); ?></button>
					</div>
				</div>
			</div>
		</div>
				<!-----   Add group in Member popupform --->
		<div class="modal fade" id="myModal_Add_group" tabindex="-1" aria-labelledby="myModal_Add_group" aria-hidden="true" role="dialog"><!-- MAIN MODAL DIV START-->
			<div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START-->
				<div class="modal-content"><!-- MODAL CONTENT DIV START-->
					<div class="modal-header">
						<h3 class="modal-title"><?php esc_html_e('Add Group','church_mgt');?></h3>
						<button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body"><!-- MODAL BODY DIV START-->
						<form name="group_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="group_form" enctype="multipart/form-data"><!-- GROUP FORM START-->
							<input type="hidden" name="action" value="MJ_cmgt_add_group_popup">
							<div class="form-group">
								<div class="mb-3 row">
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="group_name"><?php esc_html_e('Group Name','church_mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<input id="group_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="" name="group_name">
									</div>
								</div>	
							</div>
							<div class="form-group">
								<div class="mb-3 row">
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="gmgt_membershipimage"><?php esc_html_e('Group Image','church_mgt');?></label>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<input type="text" id="" name="cmgt_groupimage"  class="cmgt_user_avatar_url"
										value="<?php if(isset($_POST['cmgt_groupimage'])) echo esc_attr($_POST['cmgt_groupimage']);?>" />	
										<input id="" type="button" class="button upload_user_avatar_button" value="<?php esc_html_e( 'Upload Cover Image', 'church_mgt' ); ?>" />
										<span class="description"><?php esc_html_e('Upload Group Image', 'church_mgt' ); ?></span>
										<div id="" class="upload_user_avatar_preview" style="min-height: 100px;">
											<img style="max-width:25%; margin-top:10px;" 
											src="<?php if(isset($_POST['cmgt_groupimage'])) echo esc_attr($_POST['cmgt_groupimage']); else echo get_option('cmgt_system_logo');?>" />
										</div>
									</div>
								</div>	
							</div>
							<div class="offset-sm-2 col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<input type="submit" value="<?php esc_html_e('Save','church_mgt');?>" name="save_group" class="btn btn-success"/>
							</div>
						</form><!-- GROUP FORM END-->
					</div><!-- PANEL BODY DIV END-->
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php esc_html_e('Close','church_mgt');?></button>
					</div>
				 </div><!-- MODAL CONTENT DIV END-->
			</div><!-- MODAL DIALOG DIV END-->
		</div><!-- MAIN MODAL DIV END-->
		
		