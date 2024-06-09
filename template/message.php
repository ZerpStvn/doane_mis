<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
MJ_cmgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=MJ_cmgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_cmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
$obj_church = new Church_management(get_current_user_id());
$obj_message = new Cmgt_message();
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'inbox');
	//---------- SENT MASSAGE ---------------//
	// if(isset($result))
	// {
	// 	wp_redirect ( admin_url() . 'admin.php?page=cmgt-message&tab=inbox&message=1');
	// }
	if(isset($_REQUEST['message']))
	{
		$message = sanitize_text_field($_REQUEST['message']);
		if($message == 1)
		{?>
			<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
				<?php
				esc_html_e("Message sent successfully",'church_mgt');
				?>
				<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<?php 
		}
		elseif($message == 2)
		{?>
			<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
				<?php
				esc_html_e("Message deleted successfully",'church_mgt');
				?>
				<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
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

	<div id="main-wrapper"><!-- Main-wrapper -->
			<div class="col-md-12 row">
				<ul class="nav massage_menu_design nav-tabs panel_tabs margin_left_1per flex-nowrap overflow-auto" role="tablist">
					<li class="<?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>active<?php }?>">
						<a href="?church-dashboard=user&&page=message&tab=inbox" class="padding_left_0 tab <?php echo $active_tab == 'giftlist' ? 'active' : ''; ?>">
							<?php esc_html_e('Inbox', 'church_mgt'); ?>
							<span class="badge badge-success pull-right rtl_msg_count"><?php echo MJ_cmgt_count_unread_message_admin(get_current_user_id()); ?> </span>
						</a> 
					</li> 
					<?php if($obj_church->role == 'member' || $obj_church->role == 'accountant'){?>
					<li class="<?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>active<?php }?>">
						<a href="?church-dashboard=user&&page=message&tab=sentbox" class="padding_left_0 tab <?php echo $active_tab == 'giftlist' ? 'active' : ''; ?>">
						<?php esc_html_e('Sent', 'church_mgt'); ?></a> 
					</li> 
					<?php }?> 
				</ul>
			 <?php  
				if($active_tab == 'sentbox')
					require_once CMS_PLUGIN_DIR. '/template/message/sendbox.php';
				if($active_tab == 'inbox')
					require_once CMS_PLUGIN_DIR. '/template/message/inbox.php';
				if($active_tab == 'compose')
					require_once CMS_PLUGIN_DIR. '/template/message/composemail.php';
				if($active_tab == 'view_message')
					require_once CMS_PLUGIN_DIR. '/template/message/view_message.php';
				
				?>
			</div>
	</div><!-- Main-wrapper -->
<?php ?>