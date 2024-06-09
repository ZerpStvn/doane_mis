<?php $curr_user_id=get_current_user_id();
$obj_church = new Church_management(get_current_user_id());
$obj_group=new Cmgtgroup;
$apikey = get_option('cmgt_mailchimp_api');
$api = new MCAPI();
$result=$api->MCAPI($apikey);
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'mailchimp_setting');
//SAVE MAILCHIMP API DATA
if(isset($_REQUEST['save_setting']))
{
	$nonce = sanitize_text_field($_POST['_wpnonce']);
	if (wp_verify_nonce( $nonce, 'save_setting_nonce' ) )
	{
		update_option( 'cmgt_mailchimp_api', sanitize_text_field($_REQUEST['cmgt_mailchimp_api']));
		$message = __("Setting saved successfully.","church_mgt");
	}
}
//ADD Synchronize EMAIL DATA
if(isset($_REQUEST['sychroniz_email']))
{
	$retval = $api->lists();
	$subcsriber_emil = array();
	if(isset($_REQUEST['syncmail']))
	{
		$syncmail = sanitize_text_field($_REQUEST['syncmail']);
		foreach ($syncmail as $id)
		{
			$args =MJ_cmgt_get_group_users($id);
			$usersdata = MJ_cmgt_get_group_users($id);
			if(!empty($usersdata))
			{
				foreach ($usersdata as $retrieved_data)
				{
					$firstname=get_user_meta($retrieved_data->member_id,'first_name',true);
					$lastname=get_user_meta($retrieved_data->member_id,'last_name',true);
					$user_mail=MJ_cmgt_get_emailid_byuser_id($retrieved_data->member_id);
					if(trim($user_mail) !='')
						$subcsriber_emil[] = array('fname'=>$firstname,'lname'=>$lastname,'email'=>$user_mail);
				}
			}
		}
	}
	if(!empty($subcsriber_emil))
	{
		foreach ($subcsriber_emil as $value)
		{
			$merge_vars = array('FNAME'=>$value['fname'], 'LNAME'=>$value['lname']);
			$subscribe = $api->listSubscribe($_REQUEST['list_id'], $value['email'], $merge_vars );
		}
	}
	$message = __("Synchronize Mail Successfully","church_mgt");
}
//SEND CHAMPING MAIL
if(isset($_REQUEST['send_campign']))
{
	$retval = $api->campaigns();
	$retval1 = $api->lists();
	$emails = array();
	$listId = sanitize_text_field($_REQUEST['list_id']);
	$campaignId = sanitize_text_field($_REQUEST['camp_id']);
	$listmember = $api->listMembers($listId, 'subscribed', null, 0, 5000 );
	foreach($listmember['data'] as $member)
	{
		$emails[] = $member['email'];
	}
	$retval2 = $api->campaignSendTest($campaignId, $emails);
	if ($api->errorCode)
	{
		$message = __("Campaign Tests Not Sent!","church_mgt");
	}
	else
	{
		$message = __("Campaign Tests Sent!","church_mgt");
	}
}
if(isset($message))
{
	?>
	<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
		<?php
		echo $message;
		?>
		<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	<?php 
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
	<div class="panel-white "><!-- PANEL BODY DIV START -->
		<div class="attance_frontend_mt">
			<ul class="nav massage_menu_design nav-tabs panel_tabs margin_left_1per flex-nowrap overflow-auto" role="tablist">
				<li class="<?php if($active_tab=='mailchimp_setting'){?>active<?php }?>">
					<a href="?church-dashboard=user&&page=news_letter&tab=mailchimp_setting" class="padding_left_0 tab <?php echo $active_tab == 'mailchimp_setting' ? 'active' : ''; ?>">
						<?php esc_html_e('Setting', 'church_mgt'); ?>
					</a> 
				</li> 
				<li class="<?php if($active_tab=='sync'){?>active<?php }?>">
					<a href="?church-dashboard=user&&page=news_letter&tab=sync" class="padding_left_0 tab <?php echo $active_tab == 'sync' ? 'active' : ''; ?>">
						<?php esc_html_e('Sync Mail', 'church_mgt'); ?>
					</a> 
				</li> 
				<li class="<?php if($active_tab=='campaign'){?>active<?php }?>">
					<a href="?church-dashboard=user&&page=news_letter&tab=campaign" class="padding_left_0 tab <?php echo $active_tab == 'campaign' ? 'active' : ''; ?>">
						<?php esc_html_e('Campaign', 'church_mgt'); ?>
					</a> 
				</li> 
			</ul>

			<div class="tab-content attance_mt_0 padding_frontendlist_body"><!-- TAB CONTENT DIV START -->
				<?php if($active_tab == 'mailchimp_setting')
				{ ?>
					<script type="text/javascript">
						$(document).ready(function()
						{
							$('#newsletterform').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
						}); 
					</script>
					<div class="padding_left_15px padding_top_25px"><!-- PANEL BODY DIV START -->
						<form name="newsletterform" method="post" id="newsletterform" class="form-horizontal"><!-- MAILCHIMP SETTINGS FORM START -->
							<div class="form-body user_form">
								<div class="row">	
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="cmgt_mailchimp_api" class="form-control validate[required]" type="text" value="<?php echo get_option( 'cmgt_mailchimp_api' );?>"  name="cmgt_mailchimp_api">
												<label class="" for="amount"><?php _e('Mail chimp API key','church_mgt');?><span class="require-field">*</span></label></label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<?php wp_nonce_field( 'save_setting_nonce' ); ?>
									<!-- <div class="col-md-6 mt-2">
										<input type="submit" value="<?php esc_html_e('Save', 'church_mgt' ); ?>" name="save_setting" class="btn btn-success save_btn"/>
									</div> -->
								</div>
							</div>
						
						</form><!-- MAILCHIMP SETTINGS FORM END -->
					</div><!-- PANEL BODY DIV END -->
					<?php 
				}
				if($active_tab == 'sync')
				{
						$retval = $api->lists();?>
					<script type="text/javascript">
						$(document).ready(function()
						{
							$('#setting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
						}); 
					</script>
					<div class="padding_left_15px padding_top_25px"><!-- PANEL BODY DIV START -->
						<form name="template_form" action="" method="post" class="form-horizontal" id="setting_form"><!--SYNCRONIZE USER FORM START -->
							<div class="form-group user_form">
								<div class="row">	
									<div class="col-md-6 margin_top_10">
										<div class="form-group" id="frontend_syn_mb_15">
											<div class="col-md-12 form-control">
												<div class="row padding_radio">
													<div class="">
														<label class="custom-top-label margin_left_0" for="enable_quote_tab"><?php _e('Group List','church_mgt');?></label>
														<div class="checkbox checkbox_lebal_padding_8px cmgt_checkbox_befor_color" id="cmgt_sync">
															<?php 	$groupdata=$obj_group->MJ_cmgt_get_all_groups();
															if(!empty($groupdata))
															{
																foreach ($groupdata as $retrieved_data){?>
																		
																				<label class="newaletter_checkbox_value">
																					<input class="mt-2 me-1" type="checkbox" name="syncmail[]"  value="<?php echo esc_attr($retrieved_data->id);?>"/><?php echo esc_attr($retrieved_data->group_name);?>
																			</label>
																<?php }
															}
															else
															{
																esc_html_e('No Groups','church_mgt');
															}?>

														</div>

													</div>												
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-6 cmgt_display input">
										<div class="form-group input row margin_buttom_0">
											<div class="col-md-12">
												<label class="ml-1 custom-top-label top" for="day"><?php _e('Mailing list','church_mgt');?><span class="require-field">*</span></label>	
												<select name="list_id" id="list_id"  class="form-control validate[required]">
													<option value=""><?php esc_html_e('Select list','church_mgt');?></option>
													<?php 
													foreach ($retval['data'] as $list){
														
														echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
													}
													?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mt-2">
									<!-- <input type="submit" value="<?php esc_html_e('Sync Mail', 'church_mgt' ); ?>" name="sychroniz_email" class="btn btn-success save_btn"/> -->
								</div>
							</div>
						</form><!--SYNCRONIZE USER FORM END -->
					</div><!-- PANEL BODY DIV END -->
				<?php 
				}
				if($active_tab == 'campaign')
				{
					$retval = $api->campaigns();
					$retval1 = $api->lists();?>
					<script type="text/javascript">
						$(document).ready(function()
						{
							$('#setting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
						}); 
					</script>
					<div class="padding_left_15px padding_top_25px"><!-- PANEL BODY DIV STRAT -->
						<form name="student_form" action="" method="post" class="form-horizontal" id="setting_form"><!-- MAILCHIMP FORM START-->
						<div class="form-group user_form">
								<div class="row">	
									<div class="col-md-6 cmgt_display input">
										<div class="form-group input row margin_buttom_0">
											<div class="col-md-12">
												<label class="ml-1 custom-top-label top" for="day"><?php _e('MailChimp list','church_mgt');?><span class="require-field">*</span></label>	
												<select name="list_id" id="quote_form"  class="form-control validate[required]">
													<option value=""><?php esc_html_e('Select list','church_mgt');?></option>
													<?php 
													foreach ($retval1['data'] as $list){
														
														echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6 cmgt_display input">
										<div class="form-group input row margin_buttom_0">
											<div class="col-md-12">
												<label class="ml-1 custom-top-label top" for="day"><?php _e('Campaign list','church_mgt');?></label>	
												<select name="camp_id" id="quote_form"  class="form-control">
													<option value=""><?php esc_html_e('Select Campaign','church_mgt');?></option>
													<?php 
													foreach ($retval['data'] as $c){
														
														echo '<option value="'.$c['id'].'">'.$c['title'].'</option>';
													}
													?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mt-2">
									<!-- <input type="submit" value="<?php esc_html_e('Send Campaign', 'church_mgt' ); ?>" name="send_campign" class="btn btn-success save_btn"/> -->
								</div>
							</div>
						</form><!-- MAILCHIMP FORM END-->
					</div><!-- PANEL BODY DIV END -->
			<?php 
				}?>
			</div><!-- TAB CONTENT DIV END -->
		</div>
	</div><!-- PANEL BODY DIV END -->
<?php ?>