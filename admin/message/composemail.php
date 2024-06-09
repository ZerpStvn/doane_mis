<?php
//Compose mail
?>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#message_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	} );
</script>
	<div class="cmgt_mailbox-content mailbox-content"><!--MAILBOX CONTENT DIV STRAT-->
        <?php
		if(isset($message))
			echo '<div id="message" class="updated below-h2"><p>'.$message.'</p></div>';
		?>
        <form name="message_form" action="" method="post" class="form-horizontal" id="message_form"><!--COMPOSE MAIL FORM STRAT-->
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
								<input type="submit" value="<?php  _e('Send Message','church_mgt');?>" name="save_message" class="btn btn-success col-md-12 save_btn"/>
							</div>
						</div>	
					</div>
				</div>
		</form><!--COMPOSE MAIL FORM END-->
	</div><!--MAILBOX CONTENT DIV END-->
<?php
?>