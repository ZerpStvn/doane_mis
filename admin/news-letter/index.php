<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);?>
<?php 
MJ_cmgt_header();
$obj_group=new Cmgtgroup;
$apikey = get_option('cmgt_mailchimp_api');
$api = new MCAPI();
$result=$api->MCAPI($apikey);
$api->useSecure(true);
$active_tab = isset($_GET['tab'])?$_GET['tab']:'mailchimp_setting';
?>
<script type="text/javascript">
$(document).ready(function()
{
	//------------ CLOSE MESSAGE ---------//
	$('.notice-dismiss').click(function() {
		$('#message').hide();
	}); 
	$('#newsletterform').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
}); 
</script>
<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('news_letter');
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
		$user_access_add = $user_access['add'];

		if (isset($_REQUEST['page'])) 
		{
			if ($user_access_view == '0') 
			{
				mj_cmgt_access_right_page_not_access_message_admin_side();
				die;
			}
			if(!empty($_REQUEST['action']))
			{
				if ($user_access['page_link'] == "member" && ($_REQUEST['action']=="add"))
				{
					if ($user_access_add == '0') 
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
<div class="page-inner"><!-- PAGE INNER DIV START-->
	<?php 
	//SAVE MAILCHIMP API DATA
	if(isset($_REQUEST['save_setting']))
	{
		update_option( 'cmgt_mailchimp_api', $_REQUEST['cmgt_mailchimp_api']);
		$message = __("Setting saved successfully.","church_mgt");
	}
	//SYNCRONIZE USER EMAIL WITH MAILCHIMP
	if(isset($_REQUEST['sychroniz_email']))
	{
		$retval = $api->lists();
		$subcsriber_emil = array();
		if(isset($_REQUEST['syncmail']))
		{
			$syncmail = $_REQUEST['syncmail'];
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
	if(isset($_REQUEST['send_campign']))
	{
		$retval = $api->campaigns();
		$retval1 = $api->lists();
		$emails = array();
		$listId = $_REQUEST['list_id'];
		$campaignId =$_REQUEST['camp_id'];
		$listmember = $api->listMembers($listId, 'subscribed', null, 0, 5000 );
		foreach($listmember['data'] as $member){
			$emails[] = $member['email'];
		}
		$retval2 = $api->campaignSendTest($campaignId, $emails);
		 
		if ($api->errorCode){
			 
			$message = __("Campaign Tests Not Sent!","church_mgt");
		} else {
			$message = __("Campaign Tests Sent!","church_mgt");
		}
	}
		?>

	<div id=""><!-- MAIN WRAPPER DIV START--> 
		<div class="row"><!-- ROW DIV START--> 
			<div class="col-md-12"><!-- COL 12 DIV START-->  
				<div class="panel panel-white main_home_page_div"><!-- PANEL WHITE DIV START-->  
					<?php
					if(isset($message))
					{
						?>
						<div id="message" class="updated below-h2 notice is-dismissible "><p>
							<?php 
								echo $message;
							?></p>
						</div>
						<?php 
					}
					?>
					<div class="panel-body"><!--PANEL BODY DIV STRAT-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER MENU STRAT-->
						<ul class="nav border_bottom_for_view_page nav-tabs panel_tabs margin_left_1per cmgt-view-page-tab flex-nowrap overflow-auto" id="newsletter_ul">
							<a href="?page=cmgt-newsletter&tab=mailchimp_setting" class="nav-tab <?php echo $active_tab == 'mailchimp_setting' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Setting', 'church_mgt'); ?></a>
							
						  
							<a href="?page=cmgt-newsletter&tab=sync" class="nav-tab <?php echo $active_tab == 'sync' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Sync Mail', 'church_mgt'); ?></a>  
							
							<a href="?page=cmgt-newsletter&tab=campaign" class="nav-tab <?php echo $active_tab == 'campaign' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Campaign', 'church_mgt'); ?></a>
						</ul>
						</h2><!--NAV TAB WRAPPER MENU END-->
						 <?php 
						//Report 1 
						if($active_tab == 'mailchimp_setting')
						{ ?>
							<div class="panel-body"><!--PANEL BODY DIV STRAT-->
								<form name="newsletterform" method="post" id="newsletterform" class=""><!--NEWSSLETER FORM STRAT-->
								<div class="form-body user_form mt-4">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group input">
												<div class="col-md-12 form-control">
													<input id="cmgt_mailchimp_api" class="form-control validate[required]" type="text" value="<?php echo get_option( 'cmgt_mailchimp_api' );?>"  name="cmgt_mailchimp_api">
													<label class="" for="wpcrm_mailchimp_api"><?php _e('Mail chimp API key','church_mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 mt-2">
											<?php wp_nonce_field( 'save_pastoral_nonce' ); ?>
											<div class="offset-sm-0">
											<?php
													if($user_access_add == 1)
													{
														?>
												<input type="submit" value="<?php _e('Save', 'church_mgt' ); ?>" name="save_setting" class="btn btn-success col-md-12 save_btn"/>
												<?php
													}
													?>
											</div>
										</div>	
									</div>
								</div>
									
									
								</form><!--NEWSSLETER FORM END-->
							</div><!--PANEL BODY DIV END-->
						 <?php 
						}
							if($active_tab == 'sync')
							{
								require_once CMS_PLUGIN_DIR. '/admin/news-letter/sync.php';
							}
							if($active_tab == 'campaign')
							{
								require_once CMS_PLUGIN_DIR. '/admin/news-letter/campaign.php';
							}
							 ?>
					</div><!--PANEL BODY DIV END-->
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
        </div><!--ROW DIV END-->
    </div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNER DIV END-->
<?php //} ?>