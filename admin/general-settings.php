<?php 	
if(isset($_POST['save_setting']))
{
	$optionval=MJ_cmgt_option();
	foreach($optionval as $key=>$val)
	{
		if(isset($_POST[$key]))
		{
			$result=update_option( $key, $_POST[$key] );
			
		}
	}
	if(isset($_REQUEST['cmgt_system_color_code']))
	{
		update_option( 'cmgt_system_color_code', $_REQUEST['cmgt_system_color_code']);
	}
	if(isset($_REQUEST['cmgt_paymaster_pack']))
		update_option( 'cmgt_paymaster_pack', 'yes' );
	else
		update_option( 'cmgt_paymaster_pack', 'no' );
	
	if(isset($_REQUEST['cmgt_enable_sandbox']))
		update_option( 'cmgt_enable_sandbox', 'yes' );
	else 
		update_option( 'cmgt_enable_sandbox', 'no' );
	
	if(isset($_REQUEST['cmgt_enable_notifications']))
			update_option( 'cmgt_enable_notifications', 'yes' );
		else 
			update_option( 'cmgt_enable_notifications', 'no' );

	if(isset($_REQUEST['cmgt_take_past_attendance']))
		update_option( 'cmgt_take_past_attendance', 'yes' );
	else 
		update_option( 'cmgt_take_past_attendance', 'no' );

	if(isset($_REQUEST['cmgt_header_enable']))
			update_option( 'cmgt_header_enable', 'yes' );
		else 
			update_option( 'cmgt_header_enable', 'no' );

	if(isset($_REQUEST['cmgt_family_without_email_pass']))
			update_option( 'cmgt_family_without_email_pass', 'yes' );
		else 
			update_option( 'cmgt_family_without_email_pass', 'no' );

	if(isset($_REQUEST['cmgt_family_can_login']))
			update_option( 'cmgt_family_can_login', 'yes' );
		else 
			update_option( 'cmgt_family_can_login', 'no' );
			
		
	if(isset($_REQUEST['cmgt_enable_change_profile_picture']))
			update_option( 'cmgt_enable_change_profile_picture', 'yes' );
		else 
			update_option( 'cmgt_enable_change_profile_picture', 'no' );
	
	if(isset($result))
	{?>
		<div id="message" class="updated below-h2 notice is-dismissible ">
			<p><?php esc_html_e('Record updated successfully','church_mgt');?></p>
		</div>
		<?php 
	}
}
?>
<script type="text/javascript">
$(document).ready(function()
{
	//------------ CLOSE MESSAGE ---------//
	$('.notice-dismiss').click(function() {
		$('#message').hide();
	}); 
}); 
</script>
<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('generalsetting');
	if($user_access == 'administrator')
	{
		$user_access_add=1;
		$user_access_edit=1;
		$user_access_delete=1;
		$user_access_view=1;
	}
	else
	{
		$user_access_view = $user_access['view'];
		$user_access_edit = $user_access['edit'];
	}

	if (isset($_REQUEST['page'])) 
	{
		if ($user_access_view == '0') 
		{
			mj_cmgt_access_right_page_not_access_message_admin_side();
			die;
		}
		if(!empty($_REQUEST['action']))
		{
			if ($user_access['page_link'] == "member" && ($_REQUEST['action']=="edit"))
			{
				if ($user_access_edit == '0') 
				{
					mj_cmgt_access_right_page_not_access_message_admin_side();
					die;
				}
			}
		}
	}
?>
<!-- user redirect url enter code end -->
<script type="text/javascript">
  $(document).ready(function() 
  {
	$('#setting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
  } );
</script>
	<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->
		
		<div id="main-wrapper"><!-- MAIN WRAPPER DIV START--> 
			<div class="panel panel-white general-panel-white"><!-- PANEL WHITE DIV START-->  
					
						 <!-- <h2>	
						<?php  echo esc_html( __( 'General Settings', 'church_mgt')); ?>
						</h2> -->
						<div class="panel-body padding_0"><!-- PANEL BODY DIV START-->
							<form name="setting_form" action="" method="post" class="form-horizontal" id="setting_form">
								<div class="form-body user_form">
									<div class="row cmgt-addform-detail">
										<p><?php esc_html_e('GENERAL SETTINGS','church_mgt');?></p>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group input">
												<div class="col-md-12 form-control">
													<input id="cmgt_system_name" class="form-control validate[required]" type="text" value="<?php echo get_option( 'cmgt_system_name' );?>"  name="cmgt_system_name">
													<label class="" for="cmgt_system_name"><?php esc_html_e('Church Name','church_mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group input">
											<div class="col-md-12 form-control">
													<input id="cmgt_staring_year" class="form-control" type="text" value="<?php echo get_option( 'cmgt_staring_year' );?>"  name="cmgt_staring_year">
													<label class="" for="cmgt_staring_year"><?php esc_html_e('Starting Year','church_mgt');?></label>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group input">
											<div class="col-md-12 form-control">
													<input id="cmgt_church_address" class="form-control validate[required]" type="text" value="<?php echo get_option( 'cmgt_church_address' );?>"  name="cmgt_church_address">
													<label class="" for="gmgt_gym_address"><?php esc_html_e('Church Address','church_mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group input">
												<div class="col-md-12 form-control">
													<input id="cmgt_contact_number" class="form-control validate[required]" type="text" value="<?php echo get_option( 'cmgt_contact_number' );?>"  name="cmgt_contact_number">
													<label class="" for="gmgt_contact_number"><?php esc_html_e('Official Phone Number','church_mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>

										<div class="col-md-6 input cmgt_display">
											<label class="ml-1 custom-top-label top" for="cmgt_contry"><?php esc_html_e('Country','church_mgt');?></label>

											<?php 
												$xml=simplexml_load_file(plugins_url( 'countrylist.xml', __FILE__ )) or die("Error: Cannot create object");
												$url = plugins_url( 'countrylist.xml', __FILE__ );
											
											?>
											<select name="cmgt_contry" class="form-control" id="cmgt_contry">
												<option value=""><?php esc_html_e('Select Country','cmgt_contry');?></option>
												<?php
													foreach($xml as $country)
													{  
													?>
														<option value="<?php echo $country->name;?>" <?php selected(get_option( 'cmgt_contry' ), $country->name);  ?>><?php echo $country->name;?></option>
												<?php }?>
											</select> 
										</div>

										<div class="col-md-6 margin_bottom_0px">
											<div class="form-group input">
											<div class="col-md-12 form-control">
													<input id="cmgt_email" class="form-control validate[required,custom[email]] text-input" type="text" value="<?php echo get_option( 'cmgt_email' );?>"  name="cmgt_email">
													<label class="" for="gmgt_email"><?php esc_html_e('Email','church_mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>

										<div class="col-md-6 input">
											<div class="form-group input">
												<div class="col-md-12 form-control">	
													<label class="custom-control-label custom-top-label ml-2" for="cmgt_logo"><?php esc_html_e('Church Header Logo','church_mgt');?><span class="require-field">*</span></label>

													<div class="row cmgt_gen_upload_btn">
														<div class="col-sm-7 col-md-7 col-lg-7 col-xl-8 width_60">
															<input type="text" id="cmgt_user_avatar_url" name="cmgt_system_logo" class="form-control validate[required]" readonly value="<?php  echo get_option( 'cmgt_system_logo' ); ?>" />
														</div>	
														<div class="col-sm-5 col-md-5 col-lg-5 col-xl-4 width_20">
															<input id="upload_user_avatar_button" type="button" class="btn btn-success upload_user_cover_button" value="<?php esc_html_e( 'Upload image', 'church_mgt' ); ?>" />
														</div>
													</div>
												</div>
												<div id="upload_system_logo_preview" class="gnrl_setting_image_background mt-3">
													<img class="image_preview_css"  src="<?php  echo get_option( 'cmgt_system_logo' ); ?>" />
												</div>
											</div>
											<p><?php esc_html_e('Note: logo Size must be 190 X 56 PX And Color Should Be White.','church_mgt');?></p>
										</div>

										<div class="col-md-6 input">
											<div class="form-group input">

												<div class="col-md-12 form-control upload-profile-image-patient">

													<label class="label_margin_left_7px custom-control-label custom-top-label ml-2" for="hmgt_cover_image"><?php esc_html_e('Other Logo','church_mgt');?>(<?php esc_html_e('Invoice, Mail','church_mgt'); ?>)</label>

													<div class="col-sm-12 display_flex">	

														<input type="text" id="cmgt_church_background_image" name="cmgt_church_other_data_logo" class="image_path_dots form-control"  readonly value="<?php  echo get_option( 'cmgt_church_other_data_logo' ); ?>" />	

														<input id="upload_image_button" type="button" class="button upload_user_cover_button upload_image_btn" value="<?php esc_html_e( 'Upload Cover Image', 'church_mgt' ); ?>" />

													</div>

												</div>

												<div class="clearfix"></div>

												<div id="upload_church_cover_preview" class="min_height_100 margin_top_5 mt-3">

													<img class="other_data_logo" src="<?php  echo get_option( 'cmgt_church_other_data_logo' ); ?>" />

												</div>

											</div>

										</div>
										<div class="col-md-6 input">
											<div class="form-group input">
												<div class="col-md-12 form-control upload-profile-image-patient">
													<label class="label_margin_left_7px custom-control-label custom-top-label ml-2" for="hmgt_cover_image"><?php esc_html_e('APP Icon','church_mgt');?></label>
													<div class="col-sm-12 display_flex">	
														<input type="text" id="cmgt_church_app_icon_image" name="cmgt_church_app_icon" class="image_path_dots form-control"  readonly value="<?php  echo get_option( 'cmgt_church_app_icon' ); ?>" />	
														<input id="upload_image_button" type="button" class="button upload_user_app_icon_button upload_image_btn" value="<?php esc_html_e( 'Upload APP Icon', 'church_mgt' ); ?>" />
													</div>
												</div>

												<div class="clearfix"></div>

												<div id="upload_church_app_icon_preview" class="min_height_100 margin_top_5 mt-3">
													<img class="other_data_logo" src="<?php  echo get_option( 'cmgt_church_app_icon' ); ?>" />
												</div>
											</div>
										</div>
										<!-- <div class="col-md-6 mb-3">
											<div class="form-group">
												<div class="col-md-12 form-control input_height_48px">
													<div class="row padding_radio">
														<div class="input-group">
															<label class="custom-top-label margin_left_0" for="cmgt_enable_change_profile_picture"><?php esc_html_e("User Can Change Profile Picture","church_mgt");?></label>

															<div class="checkbox checkbox_lebal_padding_8px">
																<label class="control-label form-label">
																	<input type="checkbox" name="cmgt_enable_change_profile_picture"  value="yes" <?php echo checked(get_option('cmgt_enable_change_profile_picture'),'yes');?>/>
																	<label class="px-2" ><?php esc_html_e('Enable','church_mgt');?></label>
																</label>

															</div>
														</div>												
													</div>
												</div>
											</div>
										</div> -->
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 input">
											<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
												<div class="form-group input">
													<div class="col-md-12 form-control color_picker_div_height">
													<label class="ml-1 custom-top-label top" for="cmgt_datepicker_format"><?php esc_attr_e('System Color','school-mgt');?></label>
														<input id="cmgt_notification_fcm_key" class="form-control text-input color_picker_input" type="color" value="<?php echo get_option( 'cmgt_system_color_code' );?>"  name="cmgt_system_color_code">
														<label class="color_picker_label" for="cmgt_notification_fcm_key"><?php esc_attr_e('System Color Code : ','school-mgt');?><?php echo get_option( 'cmgt_system_color_code' );?></label>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6 input cmgt_display margin_bottom_0px">
											<label class="ml-1 custom-top-label top" for="hmgt_currency_code"><?php esc_html_e('Date Format','church_mgt');?><span class="require-field">*</span></label>
												<select name="cmgt_datepicker_format" class="form-control validate[required] text-input">
													<option value=""> <?php esc_html_e('Select Date Format','church_mgt');?></option>
													<option value="Y-m-d" <?php echo selected(get_option( 'cmgt_datepicker_format' ),'Y-m-d');?>>
													<?php esc_html_e('2022-12-12','church_mgt');?></option>
													<option value="m/d/Y" <?php echo selected(get_option( 'cmgt_datepicker_format' ),'m/d/Y');?>>
													<?php esc_html_e('12/31/2022','church_mgt');?></option>
													<option value="d/m/Y" <?php echo selected(get_option( 'cmgt_datepicker_format' ),'d/m/Y');?>>
													<?php esc_html_e('31/12/2022','church_mgt');?></option>  
													<option value="F j, Y" <?php echo selected(get_option( 'cmgt_datepicker_format' ),'F j, Y');?>>
													<?php esc_html_e('December 12, 2022','church_mgt');?></option>
												</select>
										</div>
										<div class="col-md-6 mb-3 margin_bottom_0px">
											<div class="form-group">
												<div class="col-md-12 form-control input_height_48px">
													<div class="row padding_radio">
														<div class="input-group">
															<label class="custom-top-label margin_left_0 past_atten_label" for="cmgt_take_past_attendance"><?php esc_html_e('Want to take past date attendance?','church_mgt');?></label>

															<div class="checkbox checkbox_lebal_padding_8px">

																<label class="control-label form-label">
																	<input type="checkbox" name="cmgt_take_past_attendance"  value="1" <?php echo checked(get_option('cmgt_take_past_attendance'),'yes');?>/>
																	<label class="px-2 mb-2" ><?php esc_html_e('Enable','church_mgt');?></label>
																</label>

															</div>
														</div>												
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-3 margin_bottom_0px">
											<div class="form-group">
												<div class="col-md-12 form-control input_height_48px">
													<div class="row padding_radio">
														<div class="input-group">
															<label class="custom-top-label margin_left_0" for="cmgt_enable_notifications"><?php esc_html_e('Enable Notifications','church_mgt');?></label>

															<div class="checkbox checkbox_lebal_padding_8px">

																<label class="control-label form-label">
																	<input type="checkbox" name="cmgt_enable_notifications"  value="1" <?php echo checked(get_option('cmgt_enable_notifications'),'yes');?>/>
																	<label class="px-2 mb-2" ><?php esc_html_e('Enable','church_mgt');?></label>
																</label>

															</div>
														</div>												
													</div>
												</div>
											</div>
										</div>


										<?php if(is_plugin_active('paymaster/paymaster.php')) 
										{ ?> 
											<div class="col-md-6 mb-3">
												<div class="form-group">
													<div class="col-md-12 form-control input_height_48px">
														<div class="row padding_radio">
															<div class="input-group">
																<label class="custom-top-label margin_left_0 past_atten_label" for="gmgt_paymaster_pack"><?php esc_html_e('Use Paymaster Payment Gateways','church_mgt');?></label>

																<div class="checkbox checkbox_lebal_padding_8px">
																	<label class="control-label form-label">
																		<input type="checkbox" value="yes" <?php echo checked(get_option('cmgt_paymaster_pack'),'yes');?> name="cmgt_paymaster_pack">
																		<label class="px-2 mb-2" ><?php esc_html_e('Enable','church_mgt') ?></label>
																	</label>
																</div>
															</div>												
														</div>
													</div>
												</div>
											</div>
											<?php 
										} ?>
									</div>
									
									<div class="row cmgt-addform-detail">
										<p><?php esc_html_e('PAYPAL PAYMENT SETTING','church_mgt');?></p>
									</div>
									<div class="row">
										<div class="col-md-6 mb-3">
											<div class="form-group">
												<div class="col-md-12 form-control input_height_48px margin_bottom_0px">
													<div class="row padding_radio">
														<div class="input-group">
															<label class="custom-top-label margin_left_0" for="cmgt_enable_sandbox"><?php esc_html_e('Enable Sandbox','church_mgt');?></label>
															<div class="checkbox checkbox_lebal_padding_8px">
																<label class="control-label form-label">
																	<input type="checkbox" name="cmgt_enable_sandbox"  value="1" <?php echo checked(get_option('cmgt_enable_sandbox'),'yes');?>/>
																	<label class="px-2 mb-2" ><?php esc_html_e('Enable','church_mgt');?></label>
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
													<input id="cmgt_paypal_email" class="form-control validate[required,custom[email]] text-input" type="text" value="<?php echo get_option( 'cmgt_paypal_email' );?>"  name="cmgt_paypal_email">
													<label class="" for="cmgt_paypal_email"><?php esc_html_e('Paypal Email Id','church_mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>

										<div class="col-md-6 input cmgt_display">
											<label class="ml-1 custom-top-label top" for="cmgt_currency_code"><?php esc_html_e('Select Currency','church_mgt');?><span class="require-field">*</span></label>
											<select name="cmgt_currency_code" class="form-control validate[required] text-input">
												<option value=""> <?php esc_html_e('Select Currency','church_mgt');?></option>
												<option value="AUD" <?php echo selected(get_option( 'cmgt_currency_code' ),'AUD');?>>
												<?php esc_html_e('Australian Dollar','church_mgt');?></option>
												<option value="BRL" <?php echo selected(get_option( 'cmgt_currency_code' ),'BRL');?>>
												<?php esc_html_e('Brazilian Real','church_mgt');?> </option>
												<option value="CAD" <?php echo selected(get_option( 'cmgt_currency_code' ),'CAD');?>>
												<?php esc_html_e('Canadian Dollar','church_mgt');?></option>
												<option value="CZK" <?php echo selected(get_option( 'cmgt_currency_code' ),'CZK');?>>
												<?php esc_html_e('Czech Koruna','church_mgt');?></option>
												<option value="DKK" <?php echo selected(get_option( 'cmgt_currency_code' ),'DKK');?>>
												<?php esc_html_e('Danish Krone','church_mgt');?></option>
												<option value="EUR" <?php echo selected(get_option( 'cmgt_currency_code' ),'EUR');?>>
												<?php esc_html_e('Euro','church_mgt');?></option>
												<option value="HKD" <?php echo selected(get_option( 'cmgt_currency_code' ),'HKD');?>>
												<?php esc_html_e('Hong Kong Dollar','church_mgt');?></option>
												<option value="HUF" <?php echo selected(get_option( 'cmgt_currency_code' ),'HUF');?>>
												<?php esc_html_e('Hungarian Forint','church_mgt');?> </option>
												<option value="ILS" <?php echo selected(get_option( 'cmgt_currency_code' ),'ILS');?>>
												<?php esc_html_e('Israeli New Sheqel','church_mgt');?></option>
												<option value="INR" <?php echo selected(get_option( 'cmgt_currency_code' ),'INR');?>>
												<?php esc_html_e('Indian Rupee','church_mgt');?></option>
												<option value="JPY" <?php echo selected(get_option( 'cmgt_currency_code' ),'JPY');?>>
												<?php esc_html_e('Japanese Yen','church_mgt');?></option>
												<option value="MYR" <?php echo selected(get_option( 'cmgt_currency_code' ),'MYR');?>>
												<?php esc_html_e('Malaysian Ringgit','church_mgt');?></option>
												<option value="MXN" <?php echo selected(get_option( 'cmgt_currency_code' ),'MXN');?>>
												<?php esc_html_e('Mexican Peso','church_mgt');?></option>
												<option value="NOK" <?php echo selected(get_option( 'cmgt_currency_code' ),'NOK');?>>
												<?php esc_html_e('Norwegian Krone','church_mgt');?></option>
												<option value="NZD" <?php echo selected(get_option( 'cmgt_currency_code' ),'NZD');?>>
												<?php esc_html_e('New Zealand Dollar','church_mgt');?></option>
												<option value="PHP" <?php echo selected(get_option( 'cmgt_currency_code' ),'PHP');?>>
												<?php esc_html_e('Philippine Peso','church_mgt');?></option>
												<option value="PLN" <?php echo selected(get_option( 'cmgt_currency_code' ),'PLN');?>>
												<?php esc_html_e('Polish Zloty','church_mgt');?></option>
												<option value="GBP" <?php echo selected(get_option( 'cmgt_currency_code' ),'GBP');?>>
												<?php esc_html_e('Pound Sterling','church_mgt');?></option>
												
												<option value="SGD" <?php echo selected(get_option( 'cmgt_currency_code' ),'SGD');?>>
												<?php esc_html_e('Singapore Dollar','church_mgt');?></option>
												
													<option value="ZAR" <?php echo selected(get_option( 'cmgt_currency_code' ),'ZAR');?>>
												<?php esc_html_e('South African Rand','church_mgt');?></option>
												
												<option value="SEK" <?php echo selected(get_option( 'cmgt_currency_code' ),'SEK');?>>
												<?php esc_html_e('Swedish Krona','church_mgt');?></option>
												<option value="CHF" <?php echo selected(get_option( 'cmgt_currency_code' ),'CHF');?>>
												<?php esc_html_e('Swiss Franc','church_mgt');?></option>
												<option value="TWD" <?php echo selected(get_option( 'cmgt_currency_code' ),'TWD');?>>
												<?php esc_html_e('Taiwan New Dollar','church_mgt');?></option>
												<option value="THB" <?php echo selected(get_option( 'cmgt_currency_code' ),'THB');?>>
												<?php esc_html_e('Thai Baht','church_mgt');?></option>
												<option value="TRY" <?php echo selected(get_option( 'cmgt_currency_code' ),'TRY');?>>
												<?php esc_html_e('Turkish Lira','church_mgt');?></option>
												<option value="USD" <?php echo selected(get_option( 'cmgt_currency_code' ),'USD');?>>
												<?php esc_html_e('U.S. Dollar','church_mgt');?></option>
											</select>
										</div>
									</div>
									<div class="header">
										<h3 class="first_hed"><?php esc_html_e('Invoice Prefix Setting','church_mgt');?></h3>
									</div>
									<div class="form-body user_form"> <!--Card Body div-->                       
										<div class="row"><!--Row Div--> 
											<div class="col-md-6">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="cmgt_payment_prefix" class="form-control text-input validate[required]" maxlength="500" type="text"  value="<?php echo get_option( 'cmgt_payment_prefix' );?>" name="cmgt_payment_prefix">
														<label for="userinput1"><?php esc_html_e('Invoice Prefix','church_mgt');?><span class="require-red">*</span></label>
													</div>
												</div>
											</div>
										</div>
									</div>
                                    <!-- 
									<div class="header">	
										<h3 class="first_hed"><?php esc_html_e('Footer setting','church_mgt');?></h3>
									</div>

									<div class="form-body user_form">  
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="cmgt_footer_description" class="form-control text-input validate[required]" type="text" minlength="6" maxlength="100" value="<?php echo get_option( 'cmgt_footer_description' );?>"  name="cmgt_footer_description">
														<label class="" for="cmgt_footer_description"><?php esc_html_e('Footer Description','church_mgt');?><span class="require-red">*</span></label>
													</div>
												</div>
											</div>
										</div>
									</div>
                                    -->
									<div class="header">	
										<h3 class="first_hed"><?php esc_html_e('Datatable Header Settings','church_mgt');?></h3>
									</div>

									<div class="form-body user_form"> <!-- user_form Strat-->   
										<div class="row"><!--Row Div Strat--> 
											<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
												<div class="form-group">
													<div class="col-md-12 form-control input_height_48px margin_bottom_0px">
														<div class="row padding_radio">
															<div class="input-group">
																<label class="custom-top-label margin_left_0" for=""><?php esc_html_e("Header","church_mgt");?></label>
																<div class="checkbox checkbox_lebal_padding_8px">
																	<label class="control-label form-label">
																		<input type="checkbox" name="cmgt_header_enable" value="1" <?php echo checked(get_option('cmgt_header_enable'),'yes');?>/>
																		<label class="mb-2"><?php esc_html_e('Enable','church_mgt');?></label>
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>	
											</div>	
										</div>
									</div>
									<div class="header">	
										<h3 class="first_hed"><?php esc_html_e('Family Member Settings','church_mgt');?></h3>
									</div>

									<div class="form-body user_form"> <!-- user_form Strat-->   
										<div class="row"><!--Row Div Strat--> 
											<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
												<div class="form-group">
													<div class="col-md-12 form-control input_height_48px margin_bottom_0px">
														<div class="row padding_radio">
															<div class="input-group">
																<label class="custom-top-label margin_left_0 one-line-css" for=""><?php esc_html_e("Want to add without Email ID & password?","church_mgt");?></label>
																<div class="checkbox checkbox_lebal_padding_8px">
																	<label class="control-label form-label">
																		<input type="checkbox" name="cmgt_family_without_email_pass" value="1" <?php echo checked(get_option('cmgt_family_without_email_pass'),'yes');?>/>
																		<label class="mb-2"><?php esc_html_e('Enable','church_mgt');?></label>
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>	
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
												<div class="form-group">
													<div class="col-md-12 form-control input_height_48px margin_bottom_0px">
														<div class="row padding_radio">
															<div class="input-group">
																<label class="custom-top-label margin_left_0" for=""><?php esc_html_e("Family member can login?","church_mgt");?></label>
																<div class="checkbox checkbox_lebal_padding_8px">
																	<label class="control-label form-label">
																		<input type="checkbox" name="cmgt_family_can_login" value="1" <?php echo checked(get_option('cmgt_family_can_login'),'yes');?>/>
																		<label class="mb-2"><?php esc_html_e('Enable','church_mgt');?></label>
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>	
											</div>	
										</div>
									</div>

									<div class="row">
									<?php
											if($user_access_edit == 1)
												{
											?>
										<div class="col-md-6 mt-2">
											<input type="submit" value="<?php esc_html_e('Save', 'church_mgt' ); ?>" name="save_setting" class="btn btn-success col-md-12 save_btn"/>
										</div>
										<?php
												}
											?>
									</div>
								</div>
								
							</form>
						</div><!-- PANEL BODY DIV END-->
					
			</div><!-- PANEL WHITE DIV END-->
		</div><!-- MAIN WRAPPER DIV END-->
	</div><!-- PAGE INNER DIV END-->
<?php
?> 