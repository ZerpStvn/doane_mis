<?php ?>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('#group_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	});
</script>
    <?php 	
	if($active_tab == 'addministry')
	{
        	
		$ministry_id=0;
		if(isset($_REQUEST['ministry_id']))
		{
			$ministry_id=$_REQUEST['ministry_id'];
		}
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
		$edit=1;
		$result = $obj_ministry->MJ_cmgt_get_single_ministry($ministry_id);
			
		}
		?>
		<div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="group_form" action="" method="post" class="form-horizontal" id="group_form"><!--MINISTRY FORM STRAT-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="ministry_id" value="<?php echo esc_attr($ministry_id);?>"  />
				<div class="form-body user_form"> <!--Card Body div--> 
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Ministry Information','church_mgt');?></p>
					</div>                      
					<div class="row ">								
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input type="text" maxlength="50" id="ministry_name"  class="form-control validate[required,custom[popup_category_validation]]" name="ministry_name" <?php if($edit){ ?> value="<?php echo esc_attr($result->ministry_name);}elseif(isset($_POST['ministry_name']))echo esc_attr($_POST['ministry_name']);?>">
									<label for="ministry_name"><?php _e('Ministry Name','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control upload-profile-image-patient">	
									<label for="gmgt_membershipimage" class="custom-control-label custom-top-label ml-2"><?php _e('Ministry Image','church_mgt');?></label>
									<button id="upload_user_avatar_button" class="browse btn btn-success for_btn_grp1 community_button_disabled upload_user_cover_button upload-profile-image-patient" data-toggle="modal" data-target="#image_upload" type="button"><?php _e('Choose Image','church_mgt');?></button>
									<input type="text" id="cmgt_user_avatar_url" name="cmgt_ministryimage" value="<?php if($edit){ echo esc_attr($result->ministry_image);}elseif(isset($_POST['cmgt_ministryimage'])) echo esc_attr($_POST['cmgt_ministryimage']);?>" onchange="fileCheck(this);">
								</div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div id="upload_user_avatar_preview">
										<?php
										if($edit) 
										{
											if($result->ministry_image == "")
											{?>
												<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_ministry_logo' )); ?>">
												<?php 
											}
											else {
												?>
												<img class="image_preview_css" src="<?php if($edit) echo esc_url( $result->ministry_image ); ?>" />
												<?php 
											}
										}
										else
										{
											?>
												<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_ministry_logo' )); ?>">
											<?php 
										}?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<?php wp_nonce_field( 'save_ministry_nonce' ); ?>
							<div class="offset-sm-0">
								<input type="submit" value="<?php if($edit){ _e('Save','church_mgt'); }else{ _e('Add Ministry','church_mgt');}?>" name="save_ministry" class="btn btn-success  col-md-12 save_btn"/>
							</div>
						</div>
					</div>	
				</div>
			</form><!--MINISTRY FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>