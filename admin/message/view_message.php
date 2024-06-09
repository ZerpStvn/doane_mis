<script>
	jQuery(document).ready(function() 
	{
	  jQuery("span.timeago").timeago();
	});
</script>
<?php 
//$obj_message = new Cmgt_message();
if($_REQUEST['from']=='sendbox')
{
	//DELETE SENDBOX DATA
	$message = get_post($_REQUEST['id']);
	MJ_cmgt_change_read_status_reply($_REQUEST['id']);
	$author = $message->post_author;	
	$box='sendbox';
	if(isset($_REQUEST['delete']))
	{
		echo sanitize_text_field($_REQUEST['delete']);
		$obj_message->MJ_cmgt_delete_message($_REQUEST['id']);
		wp_delete_post($_REQUEST['id']);
		wp_safe_redirect(admin_url()."admin.php?page=cmgt-message&tab=sentbox&message=2" );
		exit();
	}
}

if($_REQUEST['from']=='inbox')
{
	//Delete INBOX DATA
	$message = $obj_message->MJ_cmgt_get_message_by_id($_REQUEST['id']);
	$message1 = get_post($message->post_id);
	$author = $message1->post_author;
	MJ_cmgt_change_read_status($_REQUEST['id']);
	MJ_cmgt_change_read_status_reply($message->post_id);
	$box='inbox';
	if(isset($_REQUEST['delete']))
	{
		wp_delete_post($message->post_id);
		$obj_message->MJ_cmgt_delete_message($message->post_id);
		wp_safe_redirect(admin_url()."admin.php?page=cmgt-message&tab=inbox&message=2" );
		exit();
	}
}
//SAVE Reply MESSAGE DATA
if(isset($_POST['replay_message']))
{
	$message_id= sanitize_text_field($_REQUEST['id']);
	$message_from= sanitize_text_field($_REQUEST['from']);
	$result=$obj_message->MJ_cmgt_send_replay_message($_POST);
	if($result)
		wp_safe_redirect(admin_url()."admin.php?page=cmgt-message&tab=view_message&from=".$message_from."&id=$message_id&message=1" );
}
//Delete REPLY MESSAGE DATA
if(isset($_REQUEST['action'])&& isset($_REQUEST['action'])=='delete-reply')
{
	$message_id= sanitize_text_field($_REQUEST['id']);
	$message_from= sanitize_text_field($_REQUEST['from']);
	if(!empty($_REQUEST['reply_id']))
	{
		$result=$obj_message->MJ_cmgt_delete_reply($_REQUEST['reply_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-message&tab=view_message&action=delete-reply&from='.$message_from.'&id='.$message_id.'&message=2');
		}
	}
}
?>
<div class="mailbox-content"><!--MAILBOX CONTENT DIV STRAT-->
 	<div class="message-header">
		<h3><span><?php _e('Subject','church_mgt')?> :</span>  <?php if($box=='sendbox'){ echo $message->post_title; } else{ echo $message->msg_subject; } ?></h3>
        <p class="message-date"><?php  if($box=='sendbox') echo  mysql2date('d/m/y', $message->date ); else echo  mysql2date('d/m/y', $message->msg_date ) ;?></p>
	</div>
	<div class="message-sender">                                
    	<p><?php if($box=='sendbox')
		{
			$message_for=get_post_meta($_REQUEST['id'],'message_for',true);
			echo __('From','church_mgt')." : ".MJ_cmgt_church_get_display_name($message->post_author)."<span class='word_break'>&lt;".MJ_cmgt_get_emailid_byuser_id($message->post_author)."&gt;</span><br>";
			if($message_for == 'user'){
			echo  __('To','church_mgt')." : ".MJ_cmgt_church_get_display_name(get_post_meta($_REQUEST['id'],'message_for_userid',true))."<span class='word_break'>&lt;".MJ_cmgt_get_emailid_byuser_id(get_post_meta($_REQUEST['id'],'message_for_userid',true))."&gt;</span><br>";}
			else{
			echo __('To','church_mgt')." ".__('Group','church_mgt');}?>
		<?php 
		} 
		else
		{ 
			echo __('From','church_mgt')." : ".MJ_cmgt_church_get_display_name($message->sender)."<span class='word_break'>&lt;".MJ_cmgt_get_emailid_byuser_id($message->sender)."&gt;</span><br> ".__('To','church_mgt')." : ".MJ_cmgt_church_get_display_name($message->receiver);  ?> <span>&lt;<?php echo MJ_cmgt_get_emailid_byuser_id($message->receiver);?>&gt;</span>
			<?php 
		}?>
		</p>
    </div>
    <div class="message-content">
    	<p><?php $receiver_id=0;
		if($box=='sendbox')
		{ 
			echo $message->post_content; 
			$receiver_id=(get_post_meta($_REQUEST['id'],'message_for_userid',true));
		} 
		else
		{ 
			echo $message->message_body;
			$receiver_id=$message->sender;
		}?></p>
		<div class="message-options pull-right">
		<?php
			if ($user_access_delete == 1) 
			{ 
		?>
			<a class="btn delete_btn_css" href="?page=cmgt-message&tab=view_message&id=<?php echo $_REQUEST['id'];?>&from=<?php echo $box;?>&delete=1" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash m-r-xs"></i><?php _e('Delete','church_mgt')?></a> 
			<?php
			}
			?>
		</div>
	</div>
   <?php
    if(isset($_REQUEST['from']) && $_REQUEST['from']=='inbox')
   	{
   		$allreply_data=$obj_message->MJ_cmgt_get_all_replies($message->post_id);
  	}
	else
	{
		$allreply_data=$obj_message->MJ_cmgt_get_all_replies($_REQUEST['id']);
	}
	if(!empty($allreply_data))
	{
		foreach($allreply_data as $reply)
		{
			$receiver_name=MJ_cmgt_get_receiver_name_array($reply->message_id,$reply->sender_id,$reply->created_date,$reply->message_comment);
			?>
			<div class="message-content">
				<p><?php echo $reply->message_comment;?><br><h5><?php
					_e('Reply By : ','church_mgt'); 
						echo MJ_cmgt_church_get_display_name($reply->sender_id); 
						_e(' || ','church_mgt'); 	
						_e('Reply To : ','church_mgt'); 
						echo $receiver_name; 
						_e(' || ','church_mgt'); 
					?>	
				<span class="timeago" title="<?php echo MJ_cmgt_convert_time($reply->created_date);?>"></span>
				<?php 
				if($reply->sender_id == get_current_user_id())
				{
					 ?>
					<span class="comment-delete">
					<a href="admin.php?page=cmgt-message&tab=view_message&action=delete-reply&from=<?php echo $_REQUEST['from'];?>&id=<?php echo $_REQUEST['id'];?>&reply_id=<?php echo $reply->id;?>"><?php _e('Delete','church_mgt');?></a></span> 
				 	<?php 
				} ?>
				
				</h5> 
				</p>
			</div>
		<?php 
		}
	}
   ?>
   <script type="text/javascript">
	$(document).ready(function() 
	{
		$('#message-replay').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	} );
</script>
	<script type="text/javascript">
	$(document).ready(function() 
	{			
		  $('#selected_users').multiselect({ 
			 nonSelectedText :'<?php _e("Select users to reply","church_mgt");?>',
			 selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
			 allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
			 includeSelectAllOption: true,
			 enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			filterPlaceholder: '<?php _e('Search for Users...','church_mgt');?>',
			 templates: {
				button: '<button type="button" class="multiselect btn btn-default dropdown-toggle" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
			buttonContainer: '<div class="dropdown" />'	
		 });
		 $("body").on("click","#check_reply_user",function()
		 {
			var checked = $(".dropdown-menu input:checked").length;

			if(!checked)
			{
				alert("<?php esc_html_e('Please select atleast one users to reply','church_mgt');?>");
				return false;
			}		
		}); 
		$("body").on("click","#replay_message_btn",function()
		 {
			$(".replay_message_div").show();	
			$(".replay_message_btn").hide();	
		});   
	});
	</script>
<style type="text/css">
	.message-content {
    overflow: hidden;
    border-bottom: 1px solid #f1f1f1;
    padding: 0 0 5px;
    margin-bottom: 10px;
    min-height: 90px;
}
</style>
	<form name="message-replay" method="post" id="message-replay"><!--MESSAGE REPLY FORM START-->
		<input type="hidden" name="message_id" value="<?php if($_REQUEST['from']=='sendbox') echo $_REQUEST['id']; else echo $message->post_id;?>">
		<input type="hidden" name="user_id" value="<?php echo get_current_user_id();?>">
		<!-- <input type="hidden" name="receiver_id" value="<?php echo $receiver_id;?>"> -->
		<?php
		global $wpdb;
		$tbl_name = $wpdb->prefix .'cmgt_message';
		$current_user_id=get_current_user_id();
		if((string)$current_user_id == $author)
		{		
			if($_REQUEST['from']=='sendbox')
			{
				$msg_id=$_REQUEST['id']; 
				$msg_id_integer=(int)$msg_id;
				$reply_to_users =$wpdb->get_results("SELECT *  FROM $tbl_name where post_id = $msg_id_integer");			
			}
			else
			{
				$msg_id=$message->post_id;			
				$msg_id_integer=(int)$msg_id;
				$reply_to_users =$wpdb->get_results("SELECT *  FROM $tbl_name where post_id = $msg_id_integer");			
			}		
		}
		else
		{
			$reply_to_users=array();
			$reply_to_users[]=(object)array('receiver'=>$author);
		}
		?>
		<div class="message-options pull-right">
		<?php
			if ($user_access_edit == 1) 
			{ 
		?>
			<button type="button" name="replay_message_btn" class="btn delete_btn_css replay_message_btn cmgt_replay_message_btn" id="replay_message_btn"><i class="fa fa-reply m-r-xs"></i><?php esc_html_e('Reply','church_mgt')?></button>
			<?php
			}
			?>
	 	</div>

		<div class="message-content float_left_width_100 replay_message_div padding_top_25px">
			<div class="form-body user_form mt-2">
				<div class="row">
					<div class="col-md-6 rtl_margin_top_15px">
						<div class="form-group">
							<div class="mb-3 row">
								<div class="col-md-12" style="">			
									<select name="receiver_id[]" class="form-control cmgt_msg_reply_select" id="selected_users" multiple="true">
										<?php						
										foreach($reply_to_users as $reply_to_user)
										{  	
											$user_data=get_userdata($reply_to_user->receiver);
											if(!empty($user_data))
											{								
												if($reply_to_user->receiver != get_current_user_id())
												{
													?>
													<option  value="<?php echo $reply_to_user->receiver;?>" ><?php echo MJ_cmgt_church_get_display_name($reply_to_user->receiver); ?></option>
													<?php
												}
											}							
										} 
										?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 cmgt_form_description form-control">
									<textarea name="replay_message_body" maxlength="150" id="replay_message_body" class="validate[required] form-control text-input"></textarea>
									<label class="" for="photo"><?php _e('Message Comment','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>	
					<div class="col-md-6 mt-2 message-options pull-right reply-message-btn">
						<div class="offset-sm-0">
							<button type="submit" name="replay_message" class="btn delete_btn_css save_btn" id="check_reply_user"><?php _e('Send','church_mgt')?></button>
						</div>
					</div> 
				</div>
			</div>

		
			<!-- <div class="col-sm-12">
				<div class="form-group">
					<div class="mb-3 row">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label form-label" ><?php _e('Select user to reply','church_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" style="margin-bottom:20px;">			
							<select name="receiver_id[]" class="form-control" id="selected_users" multiple="true">
								<?php						
								foreach($reply_to_users as $reply_to_user)
								{  	
									$user_data=get_userdata($reply_to_user->receiver);
									if(!empty($user_data))
									{								
										if($reply_to_user->receiver != get_current_user_id())
										{
											?>
											<option  value="<?php echo $reply_to_user->receiver;?>" ><?php echo MJ_cmgt_church_get_display_name($reply_to_user->receiver); ?></option>
											<?php
										}
									}							
								} 
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<div class="mb-3 row">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label form-label" for="photo"><?php _e('Message Comment','church_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" style="margin-bottom: 10px;">
							<textarea name="replay_message_body" maxlength="150" id="replay_message_body" class="validate[required] form-control text-input"></textarea>
						</div>
					</div>
				</div>
			</div>	 
			
			<div class="message-options pull-right reply-message-btn">
					<button type="submit" name="replay_message" class="btn delete_btn_css" id="check_reply_user"><?php _e('Send','church_mgt')?></button>
			</div> -->
			
		</div>
	</form><!--MESSAGE REPLY FORM END-->
 </div><!--MAILBOX CONTENT DIV END--
<?php ?>