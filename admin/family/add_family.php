<?php
$role='family_member';
?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#family_member_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
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
	$(".display-members").select2();
		 //username not  allow space validation
	$('.username').keypress(function( e )
	{
       if(e.which == 32) 
        return false;
    });
});
</script>
<?php 
$edit=0;
$user_info="";
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' )
{
	$edit=1;	
	$user_info = get_userdata($_REQUEST['family_id']);

}?>
    <div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="family_member_form" action="" method="post" class="form-horizontal" id="family_member_form"><!-- FAMILY MEMBER FORM START-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
			<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
			<?php 
			if(!empty($user_info)){
				$old_member_id=get_user_meta($user_info->ID,'member_id',true); 
			}
				//$old_member_id=get_user_meta($user_info->ID,'member_id',true); 
			?>
			<input type="hidden" id="old_member_id" name="old_member_id" value="<?php echo $old_member_id; ?>">
			
			<div class="form-body user_form"> 
			    <div class="row cmgt-addform-detail">
					<p><?php _e('Personal Information ','church_mgt');?></p>
				</div>
				<div class="row">
					<div class="col-md-6 margin_bottom_0">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
								<label for="first_name"><?php _e('First Name','church_mgt');?><span class="require-field">*</span></label>
							</div>	
						</div>
					</div>
					<div class="col-md-6 margin_bottom_15">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input  class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50"  <?php if($edit){ ?>value="<?php echo esc_attr($user_info->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
								<label for="middle_name"><?php _e('Middle Name','church_mgt');?></label>
							</div>	
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" type="text" maxlength="50"  <?php if($edit){ ?>value="<?php echo esc_attr($user_info->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>" name="last_name">
								<label for="last_name"><?php _e('Last Name','church_mgt');?><span class="require-field">*</span></label>
							</div>	
						</div>
					</div>
					<div class="col-md-6 rtl_margin_top_15px">
						<div class="form-group">
							<div class="col-md-12 form-control">
								<div class="skin skin-flat row">
									<div class="input-group">
										<label class="custom-control-label custom-top-label ml-2" for="gender"><?php _e('Gender','church_mgt');?><span class="require-field">*</span></label>
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
											<?php $genderval = "male"; if($edit){ $genderval=esc_attr($user_info->gender); }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
											<label class="radio-inline">
											<input type="radio" value="male" class="tog" name="gender"  <?php  checked( 'male', $genderval);  ?>/><span class="rediospan " style="margin-left:5px;"><?php _e('Male','church_mgt');?></span> 
											</label>
											<label class="radio-inline">
											<input type="radio" value="female" class="tog"  name="gender"  <?php  checked( 'female', $genderval);  ?>/><span class="rediospan " style="margin-left:5px;"><?php _e('Female','church_mgt');?></span> 
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
								<input id="birth_date" class="form-control validate[required] birth_date" type="text" name="birth_date"  
									value="<?php if($edit){ echo esc_attr($user_info->birth_date);}elseif(isset($_POST['birth_date'])){ echo esc_attr($_POST['birth_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
								<label for="birth_date"><?php _e('Date of Birth','church_mgt');?><span class="require-field">*</span></label>
							</div>	
						</div>
					</div>
					<div class="col-md-6 input cmgt_display margin_bottom_0px">
						<label class="ml-1 custom-top-label top" for="relation"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>
						<select id="member_list" class="form-control line_height_30px validate[required]" name="member_id">
							<option value=""><?php _e('Select Member','church_mgt');?></option>
								<?php
									if($edit)
									{
										$member_id=get_user_meta($user_info->ID,'member_id',true);
									}
									elseif(isset($_POST['member_id'])) 
									{
										$member_id= $_POST['member_id'];
									}
									else
										$member_id=0;
									
									$get_members = array('role' => 'member');
										$membersdata=get_users($get_members);
									if(!empty($membersdata))
									{
										foreach ($membersdata as $member){?>
											<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
										<?php }
									}?>
						</select>
					</div>

					
					<div class="col-md-6 input cmgt_display">
						<label class="ml-1 custom-top-label top" for="relation"><?php _e('Relation','church_mgt');?><span class="require-field">*</span></label>
						<?php if($edit){ $relationval=$user_info->relation; }elseif(isset($_POST['relation'])){$relationval=$_POST['relation'];}else{$relationval='';}?>
						<select name="relation" class="form-control line_height_30px validate[required]" id="relation" >
							<option value=""><?php _e('Select Relation','church_mgt');?></option>
							<option value="<?php _e('Husband','school-mgt');?>" <?php selected( $relationval, 'Husband'); ?>><?php _e('Husband','church_mgt');?></option>
							<option value="<?php _e('Wife','school-mgt');?>" <?php selected( $relationval, 'Wife'); ?>><?php _e('Wife','church_mgt');?></option>
							<option value="<?php _e('Daughter','school-mgt');?>" <?php selected( $relationval, 'Daughter'); ?>><?php _e('Daughter','church_mgt');?></option>
							<option value="<?php _e('Father','school-mgt');?>" <?php selected( $relationval, 'Father'); ?>><?php _e('Father','church_mgt');?></option>
							<option value="<?php _e('Mother','school-mgt');?>" <?php selected( $relationval, 'Mother'); ?>><?php _e('Mother','church_mgt');?></option>
							<option value="<?php _e('Son','school-mgt');?>" <?php selected( $relationval, 'Son'); ?>><?php _e('Son','church_mgt');?></option>
							<option value="<?php _e('Brother','school-mgt');?>" <?php selected( $relationval, 'Brother'); ?>><?php _e('Brother','church_mgt');?></option>
							<option value="<?php _e('Sister','school-mgt');?>" <?php selected( $relationval, 'Sister'); ?>><?php _e('Sister','church_mgt');?></option>
						</select>
					</div>
				</div>
				<div class="row cmgt-addform-detail">
					<p><?php _e('Address Information','church_mgt');?></p>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input  class="form-control validate[required,cusom[address_description_validation]]" maxlength="150" type="text"  name="address" 
								<?php if($edit){ ?>value="<?php echo esc_attr($user_info->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
								<label for="address"><?php _e('Address','church_mgt');?><span class="require-field">*</span></label>
							</div>	
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input  class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
								<?php if($edit){ ?>value="<?php echo esc_attr($user_info->city);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
								<label for="city_name"><?php _e('City','church_mgt');?><span class="require-field">*</span></label>
							</div>	
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input  class="form-control" maxlength="50" type="text"  name="state_name" 
								<?php if($edit){ ?>value="<?php echo esc_attr($user_info->state);}elseif(isset($_POST['state_name'])) echo esc_attr($_POST['state_name']);?>">
								<label for="state_name"><?php _e('State','church_mgt');?></label>
							</div>	
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input  class="form-control  validate[required,custom[zipcode]]" maxlength="15" type="text"  name="zip_code" 
								<?php if($edit){ ?>value="<?php echo esc_attr($user_info->zip_code);}elseif(isset($_POST['zip_code'])) echo esc_attr($_POST['zip_code']);?>">
								<label for="zip_code"><?php _e('Zip Code','church_mgt');?><span class="require-field">*</span></label>
							</div>	
						</div>
					</div>
				</div>
				<div class="row cmgt-addform-detail">
					<p><?php _e('Contact Information','church_mgt');?></p>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input  class="form-control validate[,custom[phone]] text-input" type="text" minlength="6" maxlength="15"  name="phone" 
								<?php if($edit){ ?> value="<?php echo esc_attr($user_info->phone);}elseif(isset($_POST['phone'])) echo esc_attr($_POST['phone']);?>">
								<label for="phone"><?php _e('Phone','church_mgt');?><span class="require-field"></span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-5 col-lg-4">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="country_code" maxlength="5" name="phonecode" disabled type="text" class="form-control pl-4 mobile validate[required] onlynumber_and_plussign" value="<?php if($edit) { if(!empty($user_info->phonecode)){ echo esc_attr($user_info->phonecode); }else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }}else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); } ?>">
										
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
										<input type="text" class="form-control validate[required,custom[onlyNumberSp]]" name="mobile_number" minlength="6" maxlength="15" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->mobile_number);}elseif(isset($_POST['mobile_number'])) echo esc_attr($_POST['mobile_number']);?>">
										<label for="mobile_number"><?php _e('Mobile Number','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				<?php

				$cmgt_family_without_email_pass = get_option('cmgt_family_without_email_pass');

				if($cmgt_family_without_email_pass != 'yes')
				{ 
					?>
					<div class="row cmgt-addform-detail">
						<p><?php _e('Login Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
									<?php if($edit){ ?>value="<?php echo esc_attr($user_info->user_email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
									<label for="email"><?php _e('Email','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control validate[required,custom[username_validation]] username" maxlength="50" type="text"  name="username" 
									<?php if($edit){ ?>value="<?php echo esc_attr($user_info->user_login);}elseif(isset($_POST['username'])) echo esc_attr($_POST['username']);?>" <?php if($edit) echo "readonly";?>>
									<label for="username"><?php _e('User Name','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input  class="form-control <?php if(!$edit) echo 'validate[required,minSize[8]]';?>" type="password"  name="password" >
									<label for="password"><?php _e('Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
								</div>
							</div>
						</div>
					</div>
					<?php
				} ?>
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
											<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_family_logo' )); ?>">
											<?php
										}
										else 
										{
											?>
											<img class="image_preview_css" style="max-width:100%;" src="<?php if($edit)echo esc_url( $user_info->cmgt_user_avatar ); ?>" />
										<?php 
										}
									}
									else 
									{
									?>
										<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_family_logo' )); ?>">
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
						<?php wp_nonce_field( 'save_family_member_nonce' ); ?>
						<input id="save_family_member" type="submit" value="<?php if($edit){ _e('Save Family','church_mgt'); }else{ _e('Add Family Member','church_mgt');}?>" name="save_family_member" class="btn btn-success  col-md-12 save_btn"/>
					</div>	
				</div>	
			</div>
        </form><!-- FAMILY MEMBER FORM END-->
	</div><!-- PANEL BODY DIV START-->
<?php
?>