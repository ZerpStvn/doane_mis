<?php 
	$curr_user_id=get_current_user_id();
	$obj_church = new Church_management(get_current_user_id());
	$user = wp_get_current_user ();
	$user_info=get_userdata($user->ID);
	$role_name = MJ_cmgt_get_user_role(get_current_user_id());
	$obj_member=new Cmgtmember;
	$obj_dashboard= new Cmgtdashboard;
	$user_data =get_userdata( sanitize_text_field($user->ID));
	require_once ABSPATH . 'wp-includes/class-phpass.php';
	$wp_hasher = new PasswordHash( 8, true );
	$user_access=MJ_cmgt_get_userrole_wise_access_right_array();
	//------- PASSWORD CHANGE ----------//
	if(isset($_POST['save_change']))
	{
		$nonce = sanitize_text_field($_POST['_wpnonce']);
		if (wp_verify_nonce( $nonce, 'save_change_nonce' ) )
		{
			$referrer = $_SERVER['HTTP_REFERER'];
			$success=0;
			if($wp_hasher->CheckPassword($_REQUEST['current_pass'],$user_data->user_pass))
			{
				if(isset($_REQUEST['new_pass'])==$_REQUEST['conform_pass'])
				{
						if($_REQUEST['new_pass'] == $_REQUEST['conform_pass'])
						{
							wp_set_password($_REQUEST['new_pass'], $user->ID);
							$success=1;
						}
						else{
							wp_safe_redirect(home_url()."?church-dashboard=user&&page=account&message=3" );
						}
				}
				else
				{
					if(empty($_REQUEST['conform_pass']))
					{
						wp_safe_redirect(home_url()."?church-dashboard=user&&page=account&message=5" );
					}
					else
					{
						wp_safe_redirect(home_url()."?church-dashboard=user&&page=account&message=4" );
						
					}
				}
			}
			else
			{
				wp_redirect($referrer.'&sucess=3');
			}
			if($success==1)
			{
				wp_cache_delete($user->ID,'users');
				wp_cache_delete($user_data->user_login,'userlogins');
				wp_logout();
				if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
					$referrer = $_SERVER['HTTP_REFERER'];
					wp_redirect($referrer.'&sucess=1');
				endif;
				ob_start();
			}
			else
			{
				wp_set_auth_cookie($user->ID, true);
			}
		}
	}
	//--------- PROFILE PHOTO---------//
	if(isset($_POST['save_profile_pic']))
	{
		$referrer = $_SERVER['HTTP_REFERER'];
		if($_FILES['profile']['size'] > 0)
		{
			$user_image=MJ_cmgt_load_documets($_FILES['profile'],'profile','pimg');
			$user_image_url=$user_image;
		}
		if(!empty($user_image_url))
		{
			$extension=MJ_cmgt_check_valid_extension($user_image_url);
			if(!$extension == 0)
			{
				$returnans=update_user_meta($user->ID,'cmgt_user_avatar',$user_image_url);
				if($returnans)
				{
					wp_redirect($referrer.'&message=6');
				}
			}
			else
			{
				?>
					<div class="col-md-12">
						<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
							<?php
								_e('Only jpeg ,jpg ,png and gif files are allowed!.','church_mgt');
							?>
							<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					</div>
				<?php
			//	wp_redirect($referrer.'&message=7');
			}
		}
	}

	if(isset($_REQUEST['message']))
	{
		$message =sanitize_text_field($_REQUEST['message']);
		if($message == 2)
		{ ?>
			<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
				<?php
				esc_html_e('Record updated successfully.','church_mgt');
				?>
				<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<?php 
			
		}
		elseif($message == 3)
		{ ?>
			<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
				<?php
				esc_html_e('New Password And Confirm Password Did Not Match.','church_mgt');
				?>
				<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<?php 
			
		}
		
		elseif($message == 4)
		{ ?>
			<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
				<?php
				esc_html_e('Please Enter New Password.','church_mgt');
				?>
				<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<?php 
			
		}
		elseif($message == 5)
		{ ?>
			<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
				<?php
					esc_html_e('Please Enter Confirm Password.','church_mgt');
				?>
				<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<?php 
			
		}
		
		elseif($message == 6)
		{ ?>
			<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
				<?php
				esc_html_e('Profile picture updated successfully.','church_mgt');
				?>
				<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<?php 
		}
	}
						
?>
<?php 
	$edit=1;
	$coverimage=get_option('cmgt_church_background_image' );
	if($coverimage!="")
	{   ?>
		<style>
			.profile-cover{
				background: url("<?php echo esc_url_raw(get_option( 'cmgt_church_background_image' ));?>") repeat scroll 0 0 / cover rgba(0, 0, 0, 0);
			}
		</style>
		<?php 
	}?>
	
	<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#account_settings_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#doctor_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			jQuery('#birth_date').datepicker({
			dateFormat: "yy-mm-dd",
			maxDate : 0,
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
	} );
	</script>
	<script type="text/javascript">
		$(document).ready(function() 
		{
			jQuery("body").on("click", ".save_profile_pic", function ()
			{ 
				"use strict";
				var value = $(".profile_file").val();
				if(!value)
				{
					alert("<?php echo esc_html__('Please Select atleast One Image.','church_mgt') ?>")
					return false;
				}
			});	
		});	
	</script>
<div class="view_page_main">

	<!-- POP up code -->
	<div class="popup-bg" style="z-index:100000 !important;">
		<div class="overlay-content">
			<div class="modal-content">
				<div class="profile_picture">
				</div>
			</div>
		</div> 
	</div>
	<!-- End POP-UP Code -->

	<!-- Detail Page Header Start -->
	<section id="user_information" class="">
		<div class="view_pateint_header_bg">
			<div class="row">
				<div class="col-xl-10 col-lg-9 col-md-9 col-sm-10">
					<div class="user_profile_header_left float_left_width_100">
						<?php 
						$role_name = MJ_cmgt_get_user_role(get_current_user_id());
						$user_info = get_userdata(get_current_user_id());
						$userimage=$user_info->cmgt_user_avatar;
						?>
						<img <?php if($user_access['edit']=='1'){ ?>id="profile_change"<?php } ?> class="cursor_pointer user_view_profile_image" 
							src="
								<?php 
								if(!empty($userimage)) 
								{
									echo $userimage;
								}
								else
								{
									if($role_name == "member")
									{
										echo get_option( 'cmgt_member_thumb' ); 
									}
									elseif($role_name == "family_member")
									{
										echo get_option( 'cmgt_family_logo' ); 
									}
									else
									{
										echo get_option( 'cmgt_accountant_logo' ); 
									}
								} ?>
						">
						<div class="row">
							<div class="float_left view_top1">
								<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
									<label class="view_user_name_label"><?php echo esc_attr($user->display_name); ?></label>
								</div>
								<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100 mt-2">
									<div class="view_user_phone float_left_width_100">
										<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/phone_figma.png" ?>">&nbsp;+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' ));?>&nbsp;&nbsp;<lable class="color_white_rs"><?php if($role_name == "family_member"){ echo $user->mobile_number; }else{ echo $user->mobile;}?></label>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-12 col-md-12 col-sm-12">
								<div class="view_top2">
									<div class="view_user_doctor_label">
									<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;<lable><?php echo  $user_info->address.', '.get_user_meta(get_current_user_id(), 'city_name', true) ?> </label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-2 col-lg-3 col-md-3 col-sm-2 group_thumbs">
					<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Group.png"?>">
				</div>
			</div>
		</div>
	</section>
	<!-- Detail Page Header End -->

	<section id="body_area" class="margin_top_50px account_top">
			<div class="header">
				<h3 class="first_hed"><?php _e('Account Settings ','church_mgt');?>	</h3>
			</div>
			<form class="account_settings_form" id="account_settings_form" action="#" method="post"><!-- ACCOUNT FORM START-->
				<div class="form-body user_form">
					<div class="row">
						<div class="form-group">
							<!-- <label  class="control-label form-label col-xs-2"></label> -->
							<div class="col-xs-10">	
								<p>
								<h4 class="bg-danger"><?php 
								if(isset($_REQUEST['sucess']))
								{ 
									if($_REQUEST['sucess']==1)
									{
										wp_safe_redirect(home_url()."?church-dashboard=user&&page=account&action=edit&message=2" );
									}
								}?></h4>
							</p>
						</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="name" class="form-control" type="text" maxlength="50" value="<?php echo esc_attr($user->display_name); ?>" readonly>
									<label for="first_name"><?php esc_html_e('Display Name','church_mgt');?><span class="require-field">*</span></label>
								</div>	
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="name" class="form-control" type="text" maxlength="50" value="<?php echo esc_attr($user->user_login); ?>" readonly>
									<label for="first_name"><?php esc_html_e('Username','church_mgt');?><span class="require-field">*</span></label>
								</div>	
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input  class="form-control validate[required,minSize[8]]"  maxlength="12" type="password" id="inputPassword" name="current_pass">
									<label for="password"><?php esc_html_e('Current Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input  class="form-control validate[required,minSize[8]]"  maxlength="12" type="password" id="inputPassword" name="new_pass">
									<label for="password"><?php esc_html_e('New Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input  class="form-control validate[required,minSize[8]]"  maxlength="12" type="password" id="inputPassword" name="conform_pass">
									<label for="password"><?php esc_html_e('Confirm Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
								</div>
							</div>	
						</div>
					</div>
				</div>
				<?php
				if($user_access['edit']=='1')
				{
					?>
					<div class="form-body user_form">
						<div class="row">
							<?php wp_nonce_field( 'save_change_nonce' ); ?>
							<div class="col-md-6">
								<div class="form-group">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 rtl_account_p_0">
										<button type="submit" class="btn btn-success save_btn" name="save_change"><?php _e('Saves','church_mgt');?></button>
									</div>
								</div>
							</div>
						</div>
					</div>	
					<?php
				}
				?>
			</form><!-- ACCOUNT FORM END-->
			
			<div class="header mt-2">
				<h3 class="first_hed"><?php _e('Other Information','church_mgt');?>	</h3>
			</div>
			<form class="" action="#" method="post" id="doctor_form">
				<input type="hidden" value="edit" name="action">
				<input type="hidden" value="<?php echo $obj_church->role;?>" name="role">
				<input type="hidden" value="<?php echo get_current_user_id();?>" name="user_id">
				<div class="form-body user_form">
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text" maxlength="50" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
									<label for="first_name"><?php esc_html_e('First Name','church_mgt');?><span class="require-field">*</span></label>
								</div>	
							</div>
						</div>
						<div class="col-md-6">
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
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="birth_date" class="form-control validate[required] birth_date" type="text" name="birth_date"  
									value="<?php if($edit){ echo esc_attr($user_info->birth_date);}elseif(isset($_POST['birth_date'])){ echo esc_attr($_POST['birth_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
									<label for="birth_date"><?php esc_html_e('Date of Birth','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="address" class="form-control validate[required,cusom[address_description_validation]]" maxlength="150" type="text"  name="address" value="<?php if($edit){ echo esc_attr($user_info->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
									<label for="address"><?php esc_html_e('Address','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<?php
						if(($obj_church->role=='family_member')==false)
						{
							?>
							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" value="<?php if($edit){ echo esc_attr($user_info->city_name);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
										<label for="city_name"><?php esc_html_e('City','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<?php
						}?>
						<?php
						if($obj_church->role=='family_member')
						{
							?>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-5 col-lg-4">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="country_code" maxlength="5" disabled name="phonecode" type="text" class="form-control pl-4 mobile validate[required] onlynumber_and_plussign" value="<?php if($edit) { if(!empty($user_info->phonecode)){ echo esc_attr($user_info->phonecode); }else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }}else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); } ?>">			
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
							<?php
						}
						else
						{
							?>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-5 col-lg-4">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="country_code" maxlength="5" disabled name="phonecode" type="text" class="pl-4 mobile form-control validate[required] onlynumber_and_plussign" value="<?php if($edit) { if(!empty($user_info->phonecode)){ echo esc_attr($user_info->phonecode); }else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }}else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); } ?>">
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
							<?php
						}
						?>

						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="phone" class="form-control validate[custom[phone]] text-input"  minlength="6" maxlength="15" type="text"  name="phone" value="<?php if($edit){ echo esc_attr($user_info->phone);}elseif(isset($_POST['phone'])) echo esc_attr($_POST['phone']);?>">
									<label for="phone"><?php esc_html_e('Phone','church_mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="email" class="form-control validate[required,custom[email]]" maxlength="100" type="text" name="email" 
										value="<?php if($edit){ echo esc_attr($user_info->user_email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
									<label for="email"><?php esc_html_e('Email','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="fax_number" class="form-control validate[custom[phone_number]]" maxlength="30" type="text" name="fax_number" value="<?php if($edit){ echo esc_attr($user_info->fax_number);}elseif(isset($_POST['fax_number'])) echo esc_attr($_POST['fax_number']);?>">
									<label for="fax_number"><?php esc_html_e('Fax','church_mgt');?></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="skyp_id" class="form-control validate[custom[username_validation]]" maxlength="50" type="text"  name="skyp_id" value="<?php if($edit){ echo esc_attr($user_info->skyp_id);}elseif(isset($_POST['skyp_id'])) echo esc_attr($_POST['skyp_id']);?>">
									<label for="skype_id"><?php esc_html_e('Skype Id','church_mgt');?></label>
								</div>
							</div>	
						</div>
					</div>
				</div>
				<?php
				if($user_access['edit']=='1')
				{
					?>
					<div class="form-body user_form">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 rtl_account_p_0">
										<input type="submit" value="<?php esc_html_e('Save','church_mgt'); ?>" name="profile_save_change" class="btn btn-success col-md-12 save_btn "/>
									</div>
								</div>
							</div>
						</div>
					</div>	
					<?php
				}
				?>
			</form>

	</section>	

</div>	





<?php 
	if(isset($_POST['profile_save_change']))
	{
		$result=$obj_member->MJ_cmgt_add_user($_POST);
		// var_dump($result);
		// die;
		if($result)
		{ 
			wp_safe_redirect(home_url()."?church-dashboard=user&&page=account&action=edit&message=2" );
		}
	}
?>