<?php 

MJ_cmgt_header();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'setup';
?>
<div id="cmgt_imgSpinner1">
</div>
<div class="cmgt_ajax-ani"></div>
<div class="cmgt_ajax-img"><img src="<?php echo CMS_PLUGIN_URL.'/assets/images/loading.gif';?>" height="50px" width="50px"></div>
<div class="page-inner" ><!-- PAGE INNER DIV START-->
<?php 
if(isset($_REQUEST['varify_key']))
	{
		$verify_result = MJ_cmgt_submit_setupform($_POST);
		if($verify_result['cmgt_verify'] != '0')
		{
			echo '<div id="message" class="updated notice notice-success is-dismissible"><p>'.$verify_result['message'].'</p>
			<button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
			
		}
	}
?>
	<script type="text/javascript">
		$(document).ready(function() 
		{
			$('#verification_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		});
	</script>
	<?php 
	if(isset($_SESSION['cmgt_verify']) && $_SESSION['cmgt_verify'] == '3')
	{
		?>
		<div id="message" class="updated notice notice-success">
		<?php _e('There seems to be some problem please try after sometime or contact us on sales@dasinfomeida.com','church_mgt');?>
		</div>
	<?php 
	}
	elseif(isset($_SESSION['cmgt_verify']) && $_SESSION['cmgt_verify'] == '1')
	{
	?>
		<div id="message" class="updated notice notice-success">
		<?php _e('Please provide correct Envato purchase key.','church_mgt');?>
		</div>
	<?php 
	}
	else
	{
	?>
		<div id="message" class="updated notice notice-success" style="display:none;"></div>
	<?php 
	}?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->  
		<div class=""><!-- ROW DIV START--> 
			<div class="col-md-12"><!-- COL 12 DIV START-->  
				<div class="panel-white"><!-- PANEL WHITE DIV START-->  
					<div class=""><!-- PANEL BODY DIV START-->
						<form name="verification_form" action="" method="post" class="form-horizontal" id="verification_form">
							<div class="form-body user_form"> 
								<div class="row">
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="server_name" class="form-control validate[required]" type="text" value="<?php echo $_SERVER['SERVER_NAME'];?>" name="domain_name" readonly>
												<label for="domain_key"><?php esc_html_e('Domain','church_mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="licence_key" class="form-control validate[required]" type="text"  value="" name="licence_key">
												<label for="licence_key"><?php esc_html_e('Envato License key','church_mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="enter_email" class="form-control validate[required,custom[email]]" type="text"  value="" name="enter_email">
												<label for="enter_email"><?php esc_html_e('Email','church_mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-body user_form"> 
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<input type="submit" value="<?php _e('Submit','church_mgt');?>" name="varify_key" id="varify_key" class="btn save_btn"/>
									</div>
								</div>
							</div>
						</form>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->