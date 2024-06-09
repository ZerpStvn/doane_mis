<?php
//SAVE MESSAGE DATA
if(isset($_POST['save_message']))
{

	$result = $obj_message->MJ_cmgt_add_message($_POST);

	if(isset($result))
	{
		wp_redirect ( home_url() . '?church-dashboard=user&&page=message&tab=inbox&message=1');
	}	
}
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
		$('#message_template').hide();
	}); 
}); 
</script>
<script type="text/javascript">
	$(document).ready(function() 
	{
		 $('#message_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	} );
</script>
	<div class="mailbox-content padding_top_25px"><!-- MAILBOX CONTENT DIV START -->
        <?php
		if(isset($message))
			echo '<div id="message" class="updated below-h2"><p>'.$message.'</p></div>';
		?>
        <form name="message_form" action="" method="post" class="form-horizontal" id="message_form"><!-- COMPOSE MAIL FORM START -->
			<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-6 cmgt_display">
						<label class="ml-1 mt-2 custom-top-label" for="to"><?php _e('Message To','church_mgt');?><span class="require-field">*</span></label>
						<select name="receiver" class="form-control validate[required] text-input" id="to">
							<option value="member"><?php _e('All Members','church_mgt');?></option>	
							<option value="accountant"><?php _e('All Accountants','church_mgt');?></option>	
							<?php MJ_cmgt_get_all_user_in_message();?>
						</select>
					</div>
					<div class="col-md-6">
						<div class="form-group input margin_top_0">
						<div class="col-md-12 form-control">
								<input id="subject" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" name="subject" >
								<label class="" for="subject"><?php _e('Subject','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 cmgt_form_description form-control">
								<!-- <textarea name="description" class="form-control validate[custom[address_description_validation]]"  maxlength="150" id="description"><?php if($edit){ echo esc_attr($result->description);}?></textarea> -->
								<textarea name="message_body" id="message_body" class="form-control validate[required,custom[address_description_validation]]" maxlength="150"></textarea>
								<label class="" for="subject"><?php _e('Message Comment','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>	
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mt-2">
						<?php wp_nonce_field( 'save_message_nonce' ); ?>
						<div class="offset-sm-0">
							<input type="submit" value="<?php  esc_html_e('Send Message','church_mgt');?>" name="save_message" class="btn btn-success save_btn"/>
						</div>
					</div>	
				</div>
			</div>
		</form><!-- COMPOSE MAIL FORM END -->
	</div><!-- MAILBOX CONTENT DIV END -->
<?php

?>