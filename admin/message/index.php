<?php 
	MJ_cmgt_header();
	// This is Class at admin side!!!!!!!!! 
	$obj_message = new Cmgt_message();
	$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'inbox');
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
	$user_access = MJ_cmgt_add_check_access_for_view('message');
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
		$user_access_edit = $user_access['edit'];
		$user_access_delete = $user_access['delete'];

		if (isset($_REQUEST['page'])) 
		{
			if ($user_access_view == '0') 
			{
				mj_cmgt_access_right_page_not_access_message_admin_side();
				die;
			}
			if(!empty($_REQUEST['action']))
			{
				if ('message' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('message' == $user_access['page_link'] && $_REQUEST['action'] == 'edit') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('message' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
				{	
					if ($user_access_delete == '0') 
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
<div class="page-inner" ><!-- PAGE INNER DIV START-->
	<?php 
	if(isset($_POST['save_message']))
	{
		$nonce = sanitize_text_field($_POST['_wpnonce']);
		if (wp_verify_nonce( $nonce, 'save_message_nonce' ) )
		{
			$result = $obj_message->MJ_cmgt_add_message($_POST);
	    }
		if(isset($result))
		{
			wp_redirect ( admin_url() . 'admin.php?page=cmgt-message&tab=inbox&message=1');
		}
	}
	?>
		
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START--> 
	
		<!-- <div class="row mailbox-header"> 
			<div class="col-md-2">
                <a class="btn btn-success btn-block" href="?page=cmgt-message&tab=compose"><?php _e('Compose','church_mgt');?></a>
            </div>
            <div class="col-md-6">
                <h2>
                 <?php
					if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
					{
						echo esc_html( __( 'Inbox', 'church_mgt' ) );
					}
					else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
					{
						echo esc_html( __( 'Sent Item', 'church_mgt' ) );
					}
					else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
					{
						echo esc_html( __( 'Compose', 'church_mgt' ) );
					}
					?>
				</h2>
            </div>
        </div> -->
		<div class="mb-12 row">
			<?php
				if(isset($_REQUEST['message']))
				{
					$message = sanitize_text_field($_REQUEST['message']);
					if($message == 1)
					{?>
						<div id="message" class="updated below-h2 notice is-dismissible ">
							<p>
							<?php 
								_e('Message sent successfully','church_mgt');
							?></p>
						</div>
						<?php 
					}
					elseif($message == 2)
					{?>
						<div id="message" class="updated below-h2 notice is-dismissible "><p><?php
							_e("Message deleted successfully",'church_mgt');
							?></p>
						</div>
					<?php 
					}
				}	
			?>
			<!-- <div class="col-md-2"> -->
				<ul class="nav massage_menu_design nav-tabs panel_tabs margin_left_1per flex-nowrap overflow-auto">
					<li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
						<a href="?page=cmgt-message&tab=inbox" class="padding_left_0 tab cmgt_msg_txt_decoration"><label for="" class="notification_label"> <?php _e('Inbox','church_mgt');?>
						<span class="badge badge-success pull-right rtl_msg_count"><?php echo MJ_cmgt_count_unread_message_admin(get_current_user_id());?></span></label></a>
					</li>
					<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?page=cmgt-message&tab=sentbox" class="padding_left_0 tab cmgt_msg_txt_decoration"><?php _e('Sent','church_mgt');?></a></li>
				</ul>
			<!-- </div> -->
			<!-- <div class="col-md-12"> -->
			<?php  
				if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox')
				{
					require_once CMS_PLUGIN_DIR. '/admin/message/sendbox.php';
				}
				if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
				{
					require_once CMS_PLUGIN_DIR. '/admin/message/inbox.php';
				}
				if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'compose'))
				{
					require_once CMS_PLUGIN_DIR. '/admin/message/composemail.php';
				}
				if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_message'))
				{
					require_once CMS_PLUGIN_DIR. '/admin/message/view_message.php';
				}
				
				?>
			<!-- </div> -->
		</div>	
	</div><!-- Main-wrapper -->
</div><!-- Page-inner -->
<?php ?>