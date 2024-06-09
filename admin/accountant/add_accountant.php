<?php $role='accountant';?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#staff_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		jQuery('#birth_date').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate : 0,
		autoclose: true,
		changeMonth: true,
	    changeYear: true,
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
	$('#username').keypress(function( e ) 
	{
       if(e.which === 32) 
        return false;
    });
});
</script>
<?php 	
if($active_tab == 'add_accountant')
{
	//--------- EDIT ACCOUNTANT ---------//
    $accountant_id=0;
	$edit=0;
	if(isset($_REQUEST['accountant_id']))
	$accountant_id= sanitize_text_field($_REQUEST['accountant_id']);
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$user_info = get_userdata($accountant_id);	
	}
?>	
    <div class="panel-body"><!-- PANLE BODY DIV START-->
        <form name="staff_form" action="" method="post" class="form-horizontal" id="staff_form"><!-- Accountant FORM START-->	
			 <?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
			<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
			<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
			<input type="hidden" name="user_id" value="<?php echo esc_attr($accountant_id);?>"  />

			<div class="form-body user_form"> <!--Card Body div-->   
			    <div class="row cmgt-addform-detail">
					<p><?php _e('Personal Information ','church_mgt');?></p>
				</div>
				<div class="row"><!--Row Div--> 
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text" maxlength="50" id="first_name"  class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" name="first_name" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>">
								<label for="first_name"><?php _e('First Name','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 margin_bottom_10">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text" maxlength="50" id="middle_name"  class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" name="middle_name" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>">
								<label for="middle_name"><?php _e('Middle Name','church_mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text" maxlength="50" id="last_name"  class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" name="last_name" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>">
								<label for="last_name"><?php _e('Last Name','church_mgt');?><span class="require-field">*</span></label>
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
											<label class="custom-control-label margin_right_20px" for="male"><?php _e('Male','church_mgt');?></label>
											<input type="radio" value="female" name="gender" class="custom-control-input tog space_radio" <?php  checked( 'female', $genderval);  ?> id="female">
											<label class="custom-control-label" for="female"><?php _e('Female','church_mgt');?></label>
										</div>
									</div>
								</div>		
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text" id="birth_date" class="validate[required] birth_date form-control" name="birth_date" value="<?php if($edit){ echo esc_attr($user_info->birth_date);}elseif(isset($_POST['birth_date'])){ echo esc_attr($_POST['birth_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
								<label for="birth_date"><?php _e('Date of Birth','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
				</div>
					<div class="row cmgt-addform-detail">
						<p><?php _e('Address Information','church_mgt');?></p>
					</div>
				<div class="row"><!--Row Div--> 
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="address" class="form-control validate[required,custom[address_description_validation] text-input" maxlength="150" type="text"  name="address" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
								<label for="address"><?php _e('Address','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->city_name);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
								<label for="city_name"><?php _e('City','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
				</div>
				<div class="row cmgt-addform-detail">
					<p><?php _e('Contact Information','church_mgt');?></p>
				</div>
				<div class="row"><!--Row Div--> 
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-5 col-lg-4">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="country_code" maxlength="5" name="phonecode" type="text" class="pl-4 mobile form-control validate[required] onlynumber_and_plussign" value="<?php if($edit) { if(!empty($user_info->phonecode)){ echo esc_attr($user_info->phonecode); }else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }}else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); } ?>">
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
										<input type="text" id="mobile" class="form-control validate[required,custom[phone]]" name="mobile" minlength="6" maxlength="15" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->mobile);}elseif(isset($_POST['mobile'])) echo esc_attr($_POST['mobile']);?>">
										<label for="mobile"><?php _e('Mobile Number','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
						</div>
					</div> 
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="phone" class="form-control validate[,custom[phone]]" type="text" minlength="6" maxlength="15"  name="phone" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->phone);}elseif(isset($_POST['phone'])) echo esc_attr($_POST['phone']);?>">
								<label for="phone"><?php _e('Phone','church_mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="fax_number" class="form-control text-input validate[custom[phone_number]]"  maxlength="30" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" type="text"  name="fax_number" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->fax_number);}elseif(isset($_POST['fax_number'])) echo esc_attr($_POST['fax_number']);?>">
								<label for="fax_number"><?php _e('Fax','church_mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="skyp_id" class="form-control text-input validate[custom[username_validation]]" maxlength="50" type="text"  name="skyp_id" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->skyp_id);}elseif(isset($_POST['skyp_id'])) echo esc_attr($_POST['skyp_id']);?>">
								<label for="skyp_id"><?php _e('Skyp Id','church_mgt');?></label>
							</div>
						</div>
					</div>
				</div>
				<div class="row cmgt-addform-detail">
					<p><?php _e('Login Information','church_mgt');?></p>
				</div>
				<div class="row"><!--Row Div--> 
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="email" class="form-control validate[required,custom[email]] text-input"  maxlength="100" type="text"  name="email" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->user_email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
								<label for="email"><?php _e('Email','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">	
								<input id="username" class="form-control validate[required,custom[username_validation]]"  maxlength="50" type="text"  name="username" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->user_login);}elseif(isset($_POST['username'])) echo esc_attr($_POST['username']);?>" <?php if($edit) echo "readonly";?>>
								<label for="username"><?php _e('User Name','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="password" class="form-control <?php if(!$edit) echo 'validate[required,minSize[8]]';?>"  maxlength="12" type="password"  name="password">
									<label for="password"><?php _e('Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
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
								<label for="photo" class="custom-control-label custom-top-label ml-2"><?php esc_html_e('Upload Profile Image','church_mgt');?></label>
								<button id="upload_user_avatar_button" class="browse btn btn-success for_btn_grp1 community_button_disabled upload-profile-image-patient" data-toggle="modal" data-target="#image_upload" type="button"><?php esc_html_e('Choose image','church_mgt');?></button>
								<input type="text" id="cmgt_user_avatar_url" name="cmgt_user_avatar" value="<?php if($edit)echo esc_url( $user_info->cmgt_user_avatar );elseif(isset($_POST['cmgt_user_avatar'])) echo $_POST['cmgt_user_avatar']; ?>">
							</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<div id="upload_user_avatar_preview" >
									<?php 
									if($edit)
									{
										if($user_info->cmgt_user_avatar == "")
										{?>
											<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_accountant_logo' )); ?>">
											<?php
										}
										else 
										{
											?>
											<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->cmgt_user_avatar ); ?>" />
										<?php 
										}
									}
									else 
									{
									?>
										<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_accountant_logo' )); ?>">
										<?php 
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<?php wp_nonce_field( 'save_staff_nonce' ); ?>
						<input type="submit" value="<?php if($edit){ _e('Save','church_mgt'); }else{ _e('Add Accountant','church_mgt');}?>" name="save_staff" class="btn btn-success col-md-12 save_btn"/>
					</div>
				</div>
			</div>
		</form><!-- Accountant FORM END-->
    </div><!-- PANEL BODY DIV END-->
  <?php 
}
?>