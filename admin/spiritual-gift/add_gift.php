<?php ?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#gift_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	var gift_type=$('#gift_type').val();
		
	if(gift_type=='video' || gift_type=='audio' || gift_type=='pdf')
	{
		$("#upload_user_avatar_preview").hide();
		return true;
		
	}
	if(gift_type=='image' || gift_type=='product' ||  gift_type=='service')
	{
		$("#upload_user_avatar_preview").show();
		return true;
	}
	$("#gift_type").change(function() {

		var gift_type=$('#gift_type').val();
		
		if(gift_type=='video' || gift_type=='audio' || gift_type=='pdf')
		{
			$("#upload_user_avatar_preview").hide();
			return true;
			
		}
		if(gift_type=='image' || gift_type=='product' ||  gift_type=='service')
		{
			$("#upload_user_avatar_preview").show();
			return true;
		}
	});
	$("#save_gift").click(function() {
	 			
		var ext = $('#cmgt_user_avatar_url').val().split('.').pop().toLowerCase();
		var gift_type=$('#gift_type').val();
		
		if(gift_type=='video')
		{
			if($.inArray(ext, ['mkv','flv','mp4','3gp','vob','wmv']) == -1) {
				alert("<?php _e('Only video formats files are allowed!','church_mgt');?>");
				$('#cmgt_user_avatar_url').val('');
				
				return false;
			}
		}
		if(gift_type=='image')
		{
			if($.inArray(ext, ['gif','jpg','png','tif','psd','bmp','pspimage']) == -1) {
				alert("<?php _e('Only image files are allowed!','church_mgt');?>");
				$('#cmgt_user_avatar_url').val('');
				return false;
			}
		}
		if(gift_type=='audio')
		{
			if($.inArray(ext, ['mp3','wma','wav','ogg']) == -1) {
				alert("<?php _e('Only audio formats files are allowed!','church_mgt');?>");
				$('#cmgt_user_avatar_url').val('');
				return false;
			}
		}
		if(gift_type=='pdf')
		{
			if($.inArray(ext, ['pdf']) == -1) {
				alert("<?php _e('Only PDF files are allowed!','church_mgt');?>");
				$('#cmgt_user_avatar_url').val('');
				return false;
			}
		}
	});
} );
</script>
     <?php 	
	if($active_tab == 'addgift')
	{
        	
		$gift_id=0;
		if(isset($_REQUEST['gift_id']))
			$gift_id= sanitize_text_field($_REQUEST['gift_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_gift->MJ_cmgt_get_single_gift($gift_id);
			}
			?>
		<div class="panel-body"><!-- PANEL BODY DIV STRAT-->
			<form name="gift_form" action="" method="post" class="" id="gift_form"><!-- GIFT FORM START-->
				 <?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="gift_id" value="<?php echo esc_attr($gift_id);?>"  />
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e(' Spiritual Gift Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="gift_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo esc_attr($result->gift_name);}elseif(isset($_POST['gift_name'])) echo esc_attr($_POST['gift_name']);?>" name="gift_name">
									<label class="" for="gift_name"><?php _e('Gift Name','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="gift_price"  class="form-control validate[required,min[0],maxSize[8]] text-input" step="0.01" type="text" maxlength="8" name="gift_price" <?php if($edit){ ?>value="<?php echo esc_attr($result->gift_price);}elseif(isset($_POST['gift_price'])) echo esc_attr($_POST['gift_price']);?>">
									<label class="" for="gift_price"><?php _e('Gift Price','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 cmgt_form_description form-control">
									<textarea name="description" class="form-control validate[custom[address_description_validation]]" maxlength="250" id="description"><?php if($edit){ echo esc_attr($result->description);}?></textarea>
									<label class="" for="description"><?php _e('Description','church_mgt');?></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6 input cmgt_display">
							<label class="ml-1 custom-top-label top" for="gift_type"><?php _e('Gift Type','church_mgt');?><span class="require-field">*</span></label>
							<?php if($edit) 
								$gift_type= sanitize_text_field($result->gift_type);
								elseif(isset($_POST['gift_type']))
									$gift_type=sanitize_text_field($_POST['gift_type']);
								else
								$gift_type='';
							?>
							<select name="gift_type" id="gift_type" class="form-control validate[required] line_height_30px" >
								<option value="" ><?php _e('Select Gift Type','church_mgt');?></option>
								<option value="video" <?php selected($gift_type,'video');?>><?php _e('Video','church_mgt');?></option>
								<option value="image" <?php selected($gift_type,'image');?>><?php _e('Image','church_mgt');?></option>
								<option value="audio" <?php selected($gift_type,'audio');?>><?php _e('Audio','church_mgt');?></option>
								<option value="pdf" <?php selected($gift_type,'pdf');?>><?php _e('PDF','church_mgt');?></option>
								<option value="product" <?php selected($gift_type,'product');?>><?php _e('Product','church_mgt');?></option>
								<option value="service" <?php selected($gift_type,'service');?>><?php _e('Service','church_mgt');?></option>
							</select>
						</div>

						
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">	
									<label class="custom-control-label custom-top-label ml-2" for="gmgt_membershipimage"><?php _e('Gift','church_mgt');?><span class="require-field">*</span></label>
									<div class="row">
										<div class="col col-sm-8">
											<input type="text" class="form-control validate[required]" id="cmgt_user_avatar_url" name="cmgt_gift" value="<?php if($edit){ echo esc_attr($result->media_gift);}elseif(isset($_POST['cmgt_gift'])) echo esc_attr($_POST['cmgt_gift']);?>" readonly/>
										</div>	
										<div class="col col-sm-4">
											<input id="upload_user_avatar_button" type="button" class="btn btn-success upload_user_cover_button" style="float:right;" value="<?php _e( 'Upload Gift', 'church_mgt' ); ?>" />
										</div>
									</div>
								</div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div id="upload_user_avatar_preview" style="min-height: 100px;">
										<img class="image_preview_css" style="max-width:100%;" 
											src="<?php if($edit && $result->media_gift != ''){ echo esc_attr($result->media_gift);}elseif(isset($_POST['cmgt_gift'])) echo esc_attr($_POST['cmgt_gift']); else echo get_option( 'cmgt_gift_logo' );?>" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<?php wp_nonce_field( 'save_gift_nonce' ); ?>
							<div class="offset-sm-0">
								<input id="save_gift" type="submit" value="<?php if($edit){ _e('Save','church_mgt'); }else{ _e('Add Spiritual Gift','church_mgt');}?>" name="save_gift" class="btn btn-success col-md-12 save_btn"/>
							</div>
						</div>
					</div>
				</div>
			</form><!-- GIFT FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>