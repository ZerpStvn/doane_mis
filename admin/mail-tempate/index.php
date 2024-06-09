<?php
MJ_cmgt_header();
$changed = 0;
if(isset($_REQUEST['Member_Registration_Template']))
{
	update_option('WPChurch_Member_Registration',sanitize_text_field($_REQUEST['WPChurch_Member_Registration']));
	update_option('WPChurch_registration_email_template',sanitize_text_field($_REQUEST['WPChurch_registration_email_template']));
	$changed = 1;
}

if(isset($_REQUEST['Member_Approve_Save']))
{
	update_option('WPChurch_Member_Approve_Subject',sanitize_text_field($_REQUEST['WPChurch_Member_Approve_Subject']));
	update_option('WPChurch_Member_Approve_Template',sanitize_text_field($_REQUEST['WPChurch_Member_Approve_Template']));
	$changed = 1;
}

if(isset($_REQUEST['add_user_email_template_save']))
{
	update_option('WPChurch_add_user_subject',sanitize_text_field($_REQUEST['WPChurch_add_user_subject']));
	update_option('WPChurch_add_user_email_template',sanitize_text_field($_REQUEST['WPChurch_add_user_email_template']));	
	$changed = 1;
} 

if(isset($_REQUEST['Save_Member_Added_In_Group']))
{
	update_option('WPChurch_Member_Added_In_Group_subject',sanitize_text_field($_REQUEST['WPChurch_Member_Added_In_Group_subject']));
	update_option('WPChurch_Member_Added_In_Group_Template',sanitize_text_field($_REQUEST['WPChurch_Member_Added_In_Group_Template']));
	$changed = 1;
}

if(isset($_REQUEST['Save_Member_Added_In_Ministry']))
{
	update_option('WPChurch_Member_Added_In_Ministry_subject',sanitize_text_field($_REQUEST['WPChurch_Member_Added_In_Ministry_subject']));
	update_option('WPChurch_Member_Added_In_Ministry_Template',sanitize_text_field($_REQUEST['WPChurch_Member_Added_In_Ministry_Template']));
	$changed = 1;
}

if(isset($_REQUEST['add_notice_email_template_save']))
{
	update_option('WPChurch_add_notice_subject',sanitize_text_field($_REQUEST['WPChurch_add_notice_subject']));
	update_option('WPChurch_add_notice_email_template',sanitize_text_field($_REQUEST['WPChurch_add_notice_email_template']));
	$changed = 1;
}

if(isset($_REQUEST['Add_Service_Template_save']))
{
	update_option('WPChurch_servic_subject',sanitize_text_field($_REQUEST['WPChurch_servic_subject']));
	update_option('WPChurch_Add_Service_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Service_Template']));
	$changed = 1;
}

if(isset($_REQUEST['Add_Activity_Template_save']))
{
	update_option('WPChurch_Add_Activity_Subject',sanitize_text_field($_REQUEST['WPChurch_Add_Activity_Subject']));
	update_option('WPChurch_Add_Activity_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Activity_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Check_In_church_venue_save']))
{
	update_option('WPChurch_Check_In_church_venue_subject',sanitize_text_field($_REQUEST['WPChurch_Check_In_church_venue_subject']));
	update_option('WPChurch_Check_In_church_venue_Template',sanitize_text_field($_REQUEST['WPChurch_Check_In_church_venue_Template']));
	$changed = 1;
}

if(isset($_REQUEST['Check-Out_From_Church_Venue_save']))
{
	update_option('WPChurch_Ckeck_Out_From_Church_Venue_Subject',sanitize_text_field($_REQUEST['WPChurch_Ckeck_Out_From_Church_Venue_Subject']));
	update_option('WPChurch_Ckeck_Out_From_Church_Venue_Template',sanitize_text_field($_REQUEST['WPChurch_Ckeck_Out_From_Church_Venue_Template']));
	$changed = 1;
}

if(isset($_REQUEST['Add_Sermon_Template_save']))
{
	update_option('WPChurch_Add_Sermon_Subject',sanitize_text_field($_REQUEST['WPChurch_Add_Sermon_Subject']));
	update_option('WPChurch_Add_Sermon_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Sermon_Template']));
	$changed = 1;
}

if(isset($_REQUEST['Add_Song_Template_save']))
{
	update_option('WPChurch_Add_Song_Subject',sanitize_text_field($_REQUEST['WPChurch_Add_Song_Subject']));
	update_option('WPChurch_Add_Song_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Song_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Add_pledges_Template_save']))
{
	update_option('WPChurch_Add_Pledges_Subject',sanitize_text_field($_REQUEST['WPChurch_Add_Pledges_Subject']));
	update_option('WPChurch_Add_Pledges_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Pledges_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Sell_Spiritual_Gift_Save']))
{
	update_option('WPChurch_Sell_Spiritual_Gift_Subject',sanitize_text_field($_REQUEST['WPChurch_Sell_Spiritual_Gift_Subject']));
	update_option('WPChurch_Sell_Spiritual_Gift_Template',sanitize_text_field($_REQUEST['WPChurch_Sell_Spiritual_Gift_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Add_Transaction_Template_Save']))
{
	update_option('WPChurch_Add_Transaction_Subject',sanitize_text_field($_REQUEST['WPChurch_Add_Transaction_Subject']));
	update_option('WPChurch_Add_Transaction_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Transaction_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Payment_Received_against_Transaction_Invoice_Save']))
{
	update_option('WPChurch_Payment_Received_against_Transaction_Invoice_Subject',sanitize_text_field($_REQUEST['WPChurch_Payment_Received_against_Transaction_Invoice_Subject']));
	update_option('WPChurch_Payment_Received_against_Transaction_Invoice_Template',sanitize_text_field($_REQUEST['WPChurch_Payment_Received_against_Transaction_Invoice_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Add_Donation_Save']))
{
	update_option('WPChurch_Add_Donation_subject',sanitize_text_field($_REQUEST['WPChurch_Add_Donation_subject']));
	update_option('WPChurch_Add_Donation_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Donation_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Add_Donation_admin_Save']))
{
	update_option('WPChurch_Add_Donation_Admin_subject',sanitize_text_field($_REQUEST['WPChurch_Add_Donation_Admin_subject']));
	update_option('WPChurch_Add_Donation_Admin_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Donation_Admin_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Add_Income_save']))
{
	update_option('WPChurch_Add_Income_Subject',sanitize_text_field($_REQUEST['WPChurch_Add_Income_Subject']));
	update_option('WPChurch_Add_Income_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Income_Template']));
	$changed = 1;
}
if(isset($_REQUEST['Message_Received_Template_save']))
{
	update_option('WPChurch_Message_Received_subject',sanitize_text_field($_REQUEST['WPChurch_Message_Received_subject']));
	update_option('WPChurch_Message_Received_Template',sanitize_text_field($_REQUEST['WPChurch_Message_Received_Template']));	
	$changed = 1;
} 
if(isset($_REQUEST['Add_Pastoral_Save']))
{
	update_option('WPChurch_Add_Pastoral_subject',sanitize_text_field($_REQUEST['WPChurch_Add_Pastoral_subject']));
	update_option('WPChurch_Add_Pastoral_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Pastoral_Template']));	
	$changed = 1;
} 
if(isset($_REQUEST['Add_Notice_Save']))
{
	update_option('WPChurch_Add_Notice_Admin_subject',sanitize_text_field($_REQUEST['WPChurch_Add_Notice_Admin_subject']));
	update_option('WPChurch_Add_Notice_Template',sanitize_text_field($_REQUEST['WPChurch_Add_Notice_Template']));	
	$changed = 1;
}
if($changed)
{
	wp_redirect ( admin_url() . 'admin.php?page=cmgt-mail_template&message=1');
}
?>
<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('emailtemplate');
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
	}
?>
<!-- user redirect url enter code end -->
<div class="page-inner"><!-- PAGE INNNER DIV START-->
	<div id=""><!-- MAIN WRAPPER DIV START-->
		<div class="row"><!-- ROW DIV START-->
			<div class="col-md-12"><!-- COL 12 DIV START-->
				<div class="panel panel-white main_home_page_div"><!-- PANEL WHITE DIV START-->
					<div class="panel-body"><!-- PANEL BODY DIV START-->
					<?php
					if(isset($_REQUEST['message']))
					{
						$message =$_REQUEST['message'];
						if($message == 1)
						{?>
								<div id="message" class="updated below-h2 notice is-dismissible ">
								<p>
								<?php 
									_e('Record Updated successfully','church_mgt');
								?></p></div>
								<?php 
							
						}
					}
					?>
						<div class="panel-group accordion" id="accordionExample">
							<!-----------Registration Email Template---------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
									<h4 class="accordion-header panel-title" id="headingOne">
									  <button class="accordion-button accordion-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										<?php esc_html_e('Registration Email Template ','church_mgt'); ?>
									  </button>
									</h4>
								</div>
								<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
								  <div class="accordion-body panel-body">
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
										<div class="form-body user_form">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group input">
														<div class="col-md-12 form-control input_height_75px">
															<input class="form-control validate[required]" name="WPChurch_Member_Registration" id="WPChurch_Member_Registration" type="text"  value="<?php print get_option('WPChurch_Member_Registration'); ?>">
															<label class="" for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?><span class="require-field">*</span> </label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
														<div class="col-md-12 cmgt_form_description form-control">
															<textarea name="WPChurch_registration_email_template" class="form-control validate[required] mt-2" style="min-height:150px;" ><?php print get_option('WPChurch_registration_email_template'); ?></textarea>
															<label class="" for="first_name" class=""><?php esc_html_e('Registration Email Template','church_mgt'); ?><span class="require-field">*</span> </label>
														</div>
													</div>	
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
													<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
													<label><strong>[CMGT_CHURCH_NAME] </strong> <?php esc_html_e('Name Of Church ','church_mgt'); ?></label><br>
													<label><strong>[CMGT_LOGIN_LINK] </strong> <?php esc_html_e('Login Link','church_mgt'); ?></label><br>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
													<input value="<?php _e('Save','church_mgt');?>" name="Member_Registration_Template" class="btn btn-success col-md-12 save_btn" type="submit">
													<?php
													}
													?>
												</div>
											</div>
										</div>
									</form>
								  </div>
								</div>
							</div>
	  
					   <!-----------Member Aprove Email Template---------------->
							<div class="accordion-item panel panel-default">
								<div class="panel-heading">
									  <h4 class="accordion-header" id="headingtwentythree">
									 <!-- <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwentyThree">
									  <?php esc_html_e('Member Approved By Admin Template','church_mgt'); ?>
									  </a>-->
										  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyThree" aria-expanded="false" aria-controls="collapseTwentyThree">
											<?php esc_html_e('Member Approved By Admin Template','church_mgt'); ?>
										  </button>
									  </h4>
								</div>
								<div id="collapseTwentyThree" class="accordion-collapse collapse" aria-labelledby="headingtwentythree" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">
																<input class="form-control validate[required]" name="WPChurch_Member_Approve_Subject" id="WPChurch_Member_Approve_Subject" type="text" value="<?php print get_option('WPChurch_Member_Approve_Subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?><span class="require-field">*</span> </label>
															</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control cmgt_form_description">
																<textarea style="min-height:150px;" name="WPChurch_Member_Approve_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Member_Approve_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Member Approved By Admin Template','church_mgt'); ?><span class="require-field">*</span> </label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME] </strong> <?php esc_html_e('Name Of Church ','church_mgt'); ?></label><br>
														<label><strong>[CMGT_LOGIN_LINK] </strong> <?php esc_html_e('Login Link','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
														if($user_access_edit == 1)
														{
													?>
														<input value="<?php _e('Save','church_mgt');?>" name="Member_Approve_Save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
	  
					 <!-----------ADD OTHER USER IN SYSTEM TEMPLATE--------------------->
							<div class="accordion-item panel panel-default">
								<div class="panel-heading">
									  <h4 class="accordion-header panel-title" id="headingtwo">
										<!--<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsetwo">
										  <?php esc_html_e('Add Other User in system Template','church_mgt'); ?>
										</a>-->
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsetwo" aria-expanded="false" aria-controls="collapsetwo">
											<?php esc_html_e('Add Other User in system Template','church_mgt'); ?>
										  </button>
									  </h4>
								</div>
								<div id="collapsetwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">
																<input class="form-control validate[required]" name="WPChurch_add_user_subject" id="WPChurch_add_user_subject" type="text" value="<?php print get_option('WPChurch_add_user_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">
																<textarea style="min-height:150px;" name="WPChurch_add_user_email_template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_add_user_email_template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Other User in System Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBER_NAME]</strong> <?php esc_html_e('Name of User','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ROLE_NAME]</strong> <?php esc_html_e('User Role','church_mgt'); ?></label><br>
														<label><strong>[CMGT_USERNAME]</strong> <?php esc_html_e('Username','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PASSWORD]</strong> <?php esc_html_e('Password','church_mgt'); ?></label><br>
														<label><strong>[CMGT_LOGIN_LINK]</strong> <?php esc_html_e('Login Page Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="add_user_email_template_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
	  
						<!-----------ADD USER IN Group  TEMPLATE--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<!--<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
										  <?php esc_html_e('Member Added In Group Template','church_mgt'); ?>
										</a>-->
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
											 <?php esc_html_e('Member Added In Group Template','church_mgt'); ?>
										</button>
									  </h4>
								</div>
								<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">
																<input class="form-control validate[required]" name="WPChurch_Member_Added_In_Group_subject" id="WPChurch_Member_Added_In_Group_subject" type="text" value="<?php print get_option('WPChurch_Member_Added_In_Group_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">
																<textarea style="min-height:150px;" name="WPChurch_Member_Added_In_Group_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Member_Added_In_Group_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Member Added In Group Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] - </strong><?php esc_html_e('The Member Name','church_mgt');?></label><br>
														<label><strong>[CMGT_GROUPNAME] - </strong><?php esc_html_e('The Group Name','church_mgt');?></label><br>
														<label><strong>[CMGT_CHURCH_NAME] - </strong><?php esc_html_e('Church Name','church_mgt');?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Save_Member_Added_In_Group" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
	  
							<!-----------ADD USER IN Ministry TEMPLATE--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
								  <h4 class="panel-title accordion-header">
									<!--<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
									  <?php esc_html_e('Member Added In Ministry Template','church_mgt'); ?>
									</a>-->
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
											<?php esc_html_e('Member Added In Ministry Template','church_mgt'); ?>
									</button>
								  </h4>
								</div>
								<div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">
																<input class="form-control validate[required]" name="WPChurch_Member_Added_In_Ministry_subject" id="WPChurch_Member_Added_In_Ministry_subject" type="text" value="<?php print get_option('WPChurch_Member_Added_In_Ministry_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">
																<textarea style="min-height:150px;" name="WPChurch_Member_Added_In_Ministry_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Member_Added_In_Ministry_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Member Added In Ministry Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>	
														<label><strong>[CMGT_MEMBERNAME] - </strong><?php esc_html_e('The Member Name','church_mgt');?></label><br>
														<label><strong>[CMGT_MINISTRY] - </strong><?php esc_html_e('The Ministry Name','church_mgt');?></label><br>
														<label><strong>[CMGT_CHURCH_NAME] - </strong><?php esc_html_e('Church Name','church_mgt');?></label><br>
													</div>	
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Save_Member_Added_In_Ministry" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>

										</form>
									</div>
								</div>
							</div>
	  
						  <!-----------Add NOTICE TEMPLATE--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
											<?php esc_html_e('Add Notice Template','church_mgt'); ?>
										</button>
									  </h4>
								</div>
								<div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">

													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">
																<input class="form-control validate[required]" name="WPChurch_add_notice_subject" id="WPChurch_add_notice_subject" type="text" value="<?php print get_option('WPChurch_add_notice_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">
																<textarea style="min-height:150px;" name="WPChurch_add_notice_email_template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_add_notice_email_template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Notice Email Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[GMGT_USERNAME] </strong> <?php esc_html_e('Name of User','church_mgt'); ?></label><br>
														<label><strong>[CMGT_NOTICE_TITLE] </strong> <?php esc_html_e('Notice Title','church_mgt'); ?></label><br>
														<label><strong>[CMGT_NOTICE_START_DATE] </strong> <?php esc_html_e('Notice Start Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_NOTICE_END_DATE] </strong> <?php esc_html_e('Notice End Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_NOTICE_CONTENT] </strong> <?php esc_html_e('Notice Content','church_mgt'); ?></label><br>
														<label><strong>[CMGT_NOTICE_PAGE_LINK] </strong> <?php esc_html_e('Notice Page Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="add_notice_email_template_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
								  </div>
								</div>
							</div>
						  
						   <!-----------Add services template--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
											<?php esc_html_e('Add Service Template','church_mgt'); ?>
										</button>
									  </h4>
								</div>
								<div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">
																<input class="form-control validate[required]" name="WPChurch_servic_subject" id="Add_Service_Subject" type="text" value="<?php print get_option('WPChurch_servic_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">
																<textarea style="min-height:150px;" name="WPChurch_Add_Service_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Service_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Service Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SERVICE_TITLE] </strong> <?php esc_html_e('Service Title','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SERVICE_START_DATE] </strong> <?php esc_html_e('Service Start Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SERVICE_END_DATE] </strong> <?php esc_html_e('Service End Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SERVICE_START_TIME] </strong> <?php esc_html_e('Service Start Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SERVICE_END_TIME] </strong> <?php esc_html_e('Service End Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_OTHER_SERVICE_TITLE]</strong> <?php esc_html_e('Other Title','church_mgt'); ?></label><br>
														<label><strong>[CMGT_OTHER_SERVICE_TYPE]</strong> <?php esc_html_e('Other Service Type','church_mgt'); ?></label><br>
														<label><strong>[CMGT_OTHER_SERVICE_DATE]</strong> <?php esc_html_e('Other Service Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_OTHER_SERVICE_START_TIME]</strong> <?php esc_html_e('Other Service Start Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_OTHER_SERVICE_END_TIME]</strong> <?php esc_html_e('Other Service End Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('Service Page Link','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Service_Template_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						  
						  <!-----------Add Activity template--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
											<?php esc_html_e('Add Activity Template','church_mgt'); ?>
										</button>
									  </h4>
								</div>
								<div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">
																<input class="form-control validate[required]" name="WPChurch_Add_Activity_Subject" id="WPChurch_Add_Activity_Subject" type="text" value="<?php print get_option('WPChurch_Add_Activity_Subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">
																<textarea style="min-height:150px;" name="WPChurch_Add_Activity_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Activity_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Activity Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_GROUPNAME] </strong><?php esc_html_e('The Group Name','church_mgt');?></label><br>
														<label><strong>[CMGT_ACTIVITY_TITLE] </strong> <?php esc_html_e('Activity Title','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_CATEGORY] </strong> <?php esc_html_e('Activity Category','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_VENUE] </strong> <?php esc_html_e('Activity Venue','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_REOCCURNCE] </strong> <?php esc_html_e('Activity Reoccurence','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_START_DATE] </strong> <?php esc_html_e('Activity Start Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_END_DATE] </strong> <?php esc_html_e('Activity End Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_START_TIME] </strong> <?php esc_html_e('Activity Start Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_END_TIME] </strong> <?php esc_html_e('Activity End Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_RECORD_START_TIME] </strong> <?php esc_html_e('Activity Record Start Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ACTIVITY_RECORD_END_TIME] </strong> <?php esc_html_e('Activity Record End Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('Activity Page Link','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Activity_Template_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
	  
						  <!----------- Add  Check-In In church venue Template--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseeight" aria-expanded="false" aria-controls="collapseeight">
											<?php esc_html_e('Check-In church venue Template','church_mgt'); ?>
										</button>
									  </h4>
								</div>
							<div id="collapseeight" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								<div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">
																<input class="form-control validate[required]" name="WPChurch_Check_In_church_venue_subject" id="WPChurch_Check_In_church_venue_subject" type="text" value="<?php print get_option('WPChurch_Check_In_church_venue_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">
																<textarea style="min-height:150px;" name="WPChurch_Check_In_church_venue_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Check_In_church_venue_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Check-In church venue Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ROOM_TITLE] </strong> <?php esc_html_e('Room Title','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHEKED_INDATE] </strong> <?php esc_html_e('Cheked-in Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHEKED_OUTDATE] </strong> <?php esc_html_e('Cheked-out Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_NO_OF_FAMILY_MEMBER] </strong> <?php esc_html_e('No Of Family Members','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('check-in Page Link','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Check_In_church_venue_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						  
						  <!----------- Add Check Out In church venue Template--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
								  <h4 class="panel-title accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsenine" aria-expanded="false" aria-controls="collapsenine">
											<?php esc_html_e('Check-Out From Church Venue Template','church_mgt'); ?>
									</button>
								  </h4>
								</div>
								<div id="collapsenine" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Ckeck_Out_From_Church_Venue_Subject" id="WPChurch_Ckeck_Out_From_Church_Venue_Subject" type="text" value="<?php print get_option('WPChurch_Ckeck_Out_From_Church_Venue_Subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Ckeck_Out_From_Church_Venue_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Ckeck_Out_From_Church_Venue_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Check-Out From Church Venue Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_ROOM_TITLE] </strong> <?php esc_html_e('Room Title','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHEKED_INDATE] </strong> <?php esc_html_e('Cheked-in Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHEKED_OUTDATE] </strong> <?php esc_html_e('Cheked-out Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_NO_OF_FAMILY_MEMBER] </strong> <?php esc_html_e('No Of Family Members','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('check-out Page Link','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Check-Out_From_Church_Venue_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						  
						  
							<!----------- Add Sermon Template--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
								  <h4 class="panel-title accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
											<?php esc_html_e('Add Sermon Template','church_mgt'); ?>
									</button>
								  </h4>
								</div>
								<div id="collapseTen" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Add_Sermon_Subject" id="WPChurch_Add_Sermon_Subject" type="text" value="<?php print get_option('WPChurch_Add_Sermon_Subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Add_Sermon_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Sermon_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Sermon Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SERMONADDEDBY] </strong> <?php esc_html_e('Sermon Added By','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SERMON_TITLE] </strong> <?php esc_html_e('Sermon Title','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SERMON_DESCRIPTION] </strong> <?php esc_html_e('Sermon Description','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('Sermon Page Link','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Sermon_Template_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						  
							<!----------- Add Song Template--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
								  <h4 class="panel-title accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseElevan" aria-expanded="false" aria-controls="collapseElevan">
											<?php esc_html_e('Add Song Template','church_mgt'); ?>
									</button>
								  </h4>
								</div>
								<div id="collapseElevan" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									<div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Add_Song_Subject" id="WPChurch_Add_Song_Subject" type="text" value="<?php print get_option('WPChurch_Add_Song_Subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Add_Song_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Song_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Song Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SONGADDEDBY] </strong> <?php esc_html_e('Song Added By','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SONG_NAME] </strong> <?php esc_html_e('Song Name','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SONG_CATEGORY] </strong> <?php esc_html_e('Song Category','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SONG_DESCRIPTION]</strong> <?php esc_html_e('Song Description','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('Song Page Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Song_Template_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>

										</form>
									</div>
								</div>
							</div>
						  
						  
						   <!----------- Add Pledges Template--------------------->
							<div class="panel panel-default accordion-item">
								<div class="panel-heading">
								  <h4 class="panel-title accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
											<?php esc_html_e('Add Pledges Template','church_mgt'); ?>
									</button>
								  </h4>
								</div>
								<div id="collapseTwelve" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								  <div class="accordion-body panel-body">
									<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
										<div class="form-body user_form">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group input">
														<div class="col-md-12 form-control input_height_75px">	
															<input class="form-control validate[required]" name="WPChurch_Add_Pledges_Subject" id="WPChurch_Add_Pledges_Subject" type="text" value="<?php print get_option('WPChurch_Add_Pledges_Subject'); ?>">
															<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
														</div>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group input">
														<div class="col-md-12 form-control">	
															<textarea style="min-height:150px;" name="WPChurch_Add_Pledges_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Pledges_Template'); ?></textarea>
															<label for="first_name" class=""><?php esc_html_e('Add Pledges Template','church_mgt'); ?> <span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
													<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
													<label><strong>[CMGT_USER] </strong> <?php esc_html_e('Name of User','church_mgt'); ?></label><br>
													<label><strong>[CMGT_START_DATE] </strong> <?php esc_html_e('Pledges Start Date','church_mgt'); ?></label><br>
													<label><strong>[CMGT_END_DATE] </strong> <?php esc_html_e('Pledges End Date','church_mgt'); ?></label><br>
													<label><strong>[CMGT_PLEDGES_AMOUNT] </strong> <?php esc_html_e('Pledges Amount','church_mgt'); ?></label><br>
													<label><strong>[CMGT_PLEDGES_FREQUENCY] </strong> <?php esc_html_e('Pledges frequecy','church_mgt'); ?></label><br>
													<label><strong>[CMGT_PLEDGES_TOTAL_AMOUNT] </strong> <?php esc_html_e('Pledges Total Amount','church_mgt'); ?></label><br>
													<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('Pledges Page Link','church_mgt'); ?></label><br>
													<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 mt-3">
												<?php
													if($user_access_edit == 1)
													{
														?>
													<input value="<?php _e('Save','church_mgt');?>" name="Add_pledges_Template_save" class="btn btn-success col-md-12 save_btn" type="submit">
													<?php
													}
													?>
												</div>
											</div>
										</div>

									</form>
								  </div>
								</div>
							</div>
	  
							   <!----------- Sell Spiritual Gift Template--------------------->
								<div class="panel panel-default accordion-item">
								<div class="panel-heading">
								  <h4 class="panel-title accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsethartin" aria-expanded="false" aria-controls="collapsethartin">
											<?php esc_html_e('Sell Spiritual Gift Template','church_mgt'); ?>
									</button>
								  </h4>
								</div>
								<div id="collapsethartin" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								  <div class="accordion-body panel-body">
									<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
										<div class="form-body user_form">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group input">
														<div class="col-md-12 form-control input_height_75px">	
															<input class="form-control validate[required]" name="WPChurch_Sell_Spiritual_Gift_Subject" id="WPChurch_Sell_Spiritual_Gift_Subject" type="text" value="<?php print get_option('WPChurch_Sell_Spiritual_Gift_Subject'); ?>">
															<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
														<div class="col-md-12 form-control">	
															<textarea style="min-height:150px;" name="WPChurch_Sell_Spiritual_Gift_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Sell_Spiritual_Gift_Template'); ?></textarea>
															<label for="first_name" class=""><?php esc_html_e('Sell Spiritual Gift Template','church_mgt'); ?> <span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
													<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
													<label><strong>[CMGT_GIFT_NAME] </strong> <?php esc_html_e('Gift Name','church_mgt'); ?></label><br>
													<label><strong>[CMGT_GIFT_PRICE] </strong> <?php esc_html_e('Gift Price','church_mgt'); ?></label><br>
													<label><strong>[CMGT_GIFT_GOT_DATE] </strong> <?php esc_html_e('Gift Got Date','church_mgt'); ?></label><br>
													<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('Pledges Page Link','church_mgt'); ?></label><br>
													<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 mt-3">
												<?php
													if($user_access_edit == 1)
													{
														?>
													<input value="<?php _e('Save','church_mgt');?>" name="Sell_Spiritual_Gift_Save" class="btn btn-success col-md-12 save_btn" type="submit">
													<?php
													}
													?>
												</div>
											</div>
										</div>
									</form>
								  </div>
								</div>
							  </div>
							  
							  
							  <!----------- Add Transaction Template--------------------->
								<div class="panel panel-default accordion-item">
									<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefourtin" aria-expanded="false" aria-controls="collapsefourtin">
											<?php esc_html_e('Add Transaction Template','church_mgt'); ?>
										</button>
									  </h4>
									</div>
									<div id="collapsefourtin" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									  <div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Add_Transaction_Subject" id="WPChurch_Add_Transaction_Subject" type="text" value="<?php print get_option('WPChurch_Add_Transaction_Subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Add_Transaction_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Transaction_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Transaction Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PAYMENT_LINK]</strong> <?php esc_html_e('Payment Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Transaction_Template_Save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
															?>
														
													</div>
												</div>
											</div>
										</form>
									  </div>
									</div>
								</div>
							  
							  
							   <!----------- Payment Received against Transaction Invoice Template--------------------->
								<div class="panel panel-default accordion-item">
									<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiftin" aria-expanded="false" aria-controls="collapseFiftin">
											<?php esc_html_e('Payment Received Against Transaction Invoice Template','church_mgt'); ?>
										</button>
									  </h4>
									</div>
									<div id="collapseFiftin" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									  <div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Payment_Received_against_Transaction_Invoice_Subject" id="WPChurch_Payment_Received_against_Transaction_Invoice_Subject" type="text" value="<?php print get_option('WPChurch_Payment_Received_against_Transaction_Invoice_Subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Payment_Received_against_Transaction_Invoice_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Payment_Received_against_Transaction_Invoice_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Payment Received against Transaction Invoice Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Payment_Received_against_Transaction_Invoice_Save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>

										</form>
									  </div>
									</div>
								</div>
							  
							  
							  
							  
							  <!----------- Add Donation Send Mail Member Template--------------------->
								<div class="panel panel-default accordion-item">
									<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSixtine" aria-expanded="false" aria-controls="collapseSixtine">
											 <?php esc_html_e('Add Donation Template','church_mgt'); ?>
										</button>
									  </h4>
									</div>
									<div id="collapseSixtine" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									  <div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Add_Donation_subject" id="WPChurch_Add_Donation_subject" type="text" value="<?php print get_option('WPChurch_Add_Donation_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">		
																<textarea style="min-height:150px;" name="WPChurch_Add_Donation_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Donation_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Donation Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_DONATION_TYPE] </strong> <?php esc_html_e('Donation Type','church_mgt'); ?></label><br>
														<label><strong>[CMGT_DONATION_AMOUNT] </strong> <?php esc_html_e('Donation Amount','church_mgt'); ?></label><br>
														<label><strong>[CMGT_DONATION_DATE] </strong> <?php esc_html_e('Donation Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_DONATION_LINK]</strong> <?php esc_html_e('View Donation Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Donation_Save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>

										</form>
									  </div>
									</div>
								</div>
							  
							  <!----------- Add Donation Send Mail Admin Template--------------------->
								<div class="panel panel-default accordion-item">
									<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSevantin" aria-expanded="false" aria-controls="collapseSevantin">
											 <?php esc_html_e('Add Donation Template','church_mgt'); ?>
										</button>
									  </h4>
									</div>
									<div id="collapseSevantin" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									  <div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Add_Donation_Admin_subject" id="WPChurch_Add_Donation_Admin_subject" type="text" value="<?php print get_option('WPChurch_Add_Donation_Admin_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">		
																<textarea style="min-height:150px;" name="WPChurch_Add_Donation_Admin_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Donation_Admin_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Donation Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_ADMIN_NAME] </strong> <?php esc_html_e('Name of Admin','church_mgt'); ?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_DONATION_LINK]</strong> <?php esc_html_e('View Donation Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Donation_admin_Save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>

										</form>
									  </div>
									</div>
								</div>
							  
							  <!----------- Add Add Income Send MailTemplate--------------------->
								<div class="panel panel-default accordion-item">
									<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEightin" aria-expanded="false" aria-controls="collapseEightin">
											 <?php esc_html_e('Add Income Template','church_mgt'); ?>
										</button>
									  </h4>
									</div>
									<div id="collapseEightin" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									  <div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Add_Income_Subject" id="WPChurch_Add_Income_Subject" type="text" value="<?php print get_option('WPChurch_Add_Income_Subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Add_Income_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Income_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Income Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Member','church_mgt'); ?></label><br>
														<label><strong>[CMGT_USER_ROLE] </strong> <?php esc_html_e('Role Of User','church_mgt'); ?></label><br>
														<label><strong>[CMGT_INVOICE_LINK]</strong> <?php esc_html_e('Page Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Income_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
															?>
														
													</div>
												</div>
											</div>

										</form>
									  </div>
									</div>
								</div>
							  
							  <!-----------Message Received------------------>
								<div class="panel panel-default accordion-item">
									<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsenintitn" aria-expanded="false" aria-controls="collapsenintitn">
											<?php esc_html_e('Message Received Template','church_mgt'); ?>
										  </button>
									  </h4>
									</div>
									<div id="collapsenintitn" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									  <div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Message_Received_subject" id="WPChurch_Message_Received_subject" type="text" value="<?php print get_option('WPChurch_Message_Received_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Message_Received_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Message_Received_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Message Received Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_RECEIVER_NAME] </strong> <?php esc_html_e('Name of Receiver','church_mgt'); ?></label><br>
														<label><strong>[CMGT_SENDER_NAME] </strong> <?php esc_html_e('Name Of Sender','church_mgt'); ?></label><br>
														<label><strong>[CMGT_MESSAGE_CONTENT] </strong> <?php esc_html_e('Message Content','church_mgt'); ?></label><br>
														<label><strong>[CMGT_MESSAGE_LINK]</strong> <?php esc_html_e('Page Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Message_Received_Template_save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>

										</form>
									  </div>
									</div>
								</div>	  
							  
							  <!-----------Add Pastoral------------------>
								<div class="panel panel-default accordion-item">
									<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
											<?php esc_html_e('Add Pastoral Template','church_mgt'); ?>
										  </button>
									  </h4>
									</div>
									<div id="collapseTwenty" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									  <div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Add_Pastoral_subject" id="WPChurch_Add_Pastoral_subject" type="text" value="<?php print get_option('WPChurch_Add_Pastoral_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Add_Pastoral_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Pastoral_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Pastoral Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_USER] </strong> <?php esc_html_e('Name of User','church_mgt'); ?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Memeber','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PASTORAL_TITLE] </strong> <?php esc_html_e('Pastoral Title','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PASTORAL_DATE] </strong> <?php esc_html_e('Pastoral Date','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PASTORAL_TIME]</strong> <?php esc_html_e('Pastoral Time','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PASTORAL_DESCRIPTION]</strong> <?php esc_html_e('Pastoral Description','church_mgt'); ?></label><br>
														<label><strong>CMGT_PAGE_LINK</strong> <?php esc_html_e('Pastoral Page Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Pastoral_Save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									  </div>
									</div>
								</div>	 

							  <!-----------Add Notice Mail Template------------------>
								<div class="panel panel-default accordion-item">
									<div class="panel-heading">
									  <h4 class="panel-title accordion-header">
										 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyone" aria-expanded="false" aria-controls="collapseTwentyone">
											<?php esc_html_e('Add Notice Admin Template','church_mgt'); ?>
										</button>
									  </h4>
									</div>
									<div id="collapseTwentyone" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
									  <div class="accordion-body panel-body">
										<form id="WPChurch_registration_email_template" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-body user_form">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control input_height_75px">	
																<input class="form-control validate[required]" name="WPChurch_Add_Notice_Admin_subject" id="Add_Notice_subject" type="text" value="<?php print get_option('WPChurch_Add_Notice_Admin_subject'); ?>">
																<label for="first_name" class=""><?php esc_html_e('Email Subject','church_mgt');?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">	
																<textarea style="min-height:150px;" name="WPChurch_Add_Notice_Template" class="form-control validate[required] mt-2"><?php print get_option('WPChurch_Add_Notice_Template'); ?></textarea>
																<label for="first_name" class=""><?php esc_html_e('Add Notice Template','church_mgt'); ?> <span class="require-field">*</span></label>
															</div>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label><?php esc_html_e('You can use following variables in the email template:','church_mgt');?></label><br>
														<label><strong>[CMGT_ADMIN] </strong> <?php esc_html_e('Name of Admin','church_mgt'); ?></label><br>
														<label><strong>[CMGT_MEMBERNAME] </strong> <?php esc_html_e('Name of Memeber','church_mgt'); ?></label><br>
														<label><strong>[CMGT_PAGE_LINK]</strong> <?php esc_html_e('Notice Page Link','church_mgt'); ?></label><br>
														<label><strong>[CMGT_CHURCH_NAME]</strong> <?php esc_html_e('Name Of Church','church_mgt'); ?></label><br>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 mt-3">
													<?php
													if($user_access_edit == 1)
													{
														?>
														<input value="<?php _e('Save','church_mgt');?>" name="Add_Notice_Save" class="btn btn-success col-md-12 save_btn" type="submit">
														<?php
													}
													?>
													</div>
												</div>
											</div>
										</form>
									  </div>
									</div>
								</div>
						</div>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV START-->
</div><!-- PAGE INNNER DIV END-->