<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#sermon_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$("#save_sermon").click(function()
	{
		var ext = $('#cmgt_church_background_image').val().split('.').pop().toLowerCase();
		var sermon_type=$('#sermon_type').val();
		if(sermon_type=='video')
		{
			if($.inArray(ext, ['mkv','flv','mp4','3gp','vob','wmv']) == -1) {
				alert("<?php _e('Only video formats files are allowed!','church_mgt');?>");
				$('#cmgt_church_background_image').val('');
				return false;
				
			}
		}
		if(sermon_type=='image')
		{
			if($.inArray(ext, ['gif','jpg','png','tif','psd','bmp','pspimage']) == -1) {
				alert("<?php _e('Only image files are allowed!','church_mgt');?>");
				$('#cmgt_sermon').val('');
				return false;
			}
		}
		if(sermon_type=='audio')
		{
			if($.inArray(ext, ['mp3','wma','wav','ogg']) == -1) {
				alert("<?php _e('Only audio formats files are allowed!','church_mgt');?>");
				$('#cmgt_sermon').val('');
				return false;
			}
		}
		if(sermon_type=='pdf')
		{
			if($.inArray(ext, ['pdf']) == -1) {
				alert("<?php _e('Only PDF files are allowed!','church_mgt');?>");
				$('#cmgt_sermon').val('');
				return false;
			}
		}
	});
} );
</script>
    <?php 	
	if($active_tab == 'addsermon')
	{
		$sermon_id=0;
		if(isset($_REQUEST['sermon_id']))
			$sermon_id= sanitize_text_field($_REQUEST['sermon_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_sermon->MJ_cmgt_get_single_sermon($sermon_id);
			}?>
	
		<div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="sermon_form" action="" method="post" class="form-horizontal" id="sermon_form"><!-- SERMON FORM START-->
				<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="sermon_id" value="<?php echo esc_attr($sermon_id);?>"  />
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e(' Sermon Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6 margin_bottom_0px">
							<div class="form-group input">
							<div class="col-md-12 form-control margin_bottom_0px">
									<input id="sermon_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->sermon_title);}elseif(isset($_POST['sermon_title'])) echo esc_attr($_POST['sermon_title']);?>" name="sermon_title">
									<label class="" for="sermon_title"><?php _e('Sermon Title','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input  class="form-control validate[custom[address_description_validation]]" maxlength="250" id="description" type="text" <?php if($edit){ ?> value="<?php echo esc_attr($result->description);}?>" name="description">
									<label class="" for="description"><?php _e('Description','church_mgt');?></label>
								</div>	
							</div>
						</div>
						<div class="col-md-6 input cmgt_display margin_bottom_0px">
							<label class="ml-1 custom-top-label top" for="sermon_type"><?php _e('Sermon Content Type','church_mgt');?><span class="require-field">*</span></label>

							<?php if($edit) 
								$sermon_type= sanitize_text_field($result->sermon_type);
								elseif(isset($_POST['sermon_type']))
									$sermon_type= sanitize_text_field($_POST['sermon_type']);
								else
									$sermon_type='';
							?>
							<select name="sermon_type" id="sermon_type" class="form-control line_height_30px  validate[required]" >
								<option value="" ><?php _e('Select Sermon Type','church_mgt');?></option>
								<option value="video" <?php selected($sermon_type,'video');?>><?php _e('Video','church_mgt');?></option>
								<option value="image" <?php selected($sermon_type,'image');?>><?php _e('Image','church_mgt');?></option>
								<option value="audio" <?php selected($sermon_type,'audio');?>><?php _e('Audio','church_mgt');?></option>
								<option value="pdf" <?php selected($sermon_type,'pdf');?>><?php _e('PDF','church_mgt');?></option>
							</select>
						</div>
						<div class="col-md-6 input margin_bottom_0px">
							<div class="form-group input margin_top_0">
								<div class="col-md-12 form-control margin_bottom_0px">	
									<label class="custom-control-label custom-top-label ml-2" for="sermon_content"><?php _e('Sermon Content','church_mgt');?><span class="require-field">*</span></label>
									<div class="row">
										<div class="col col-sm-8">
											<input type="text" class="form-control validate[required]" id="cmgt_church_background_image" name="cmgt_sermon" value="<?php if($edit){ echo esc_attr($result->sermon_content);}elseif(isset($_POST['cmgt_sermon'])) echo esc_attr($_POST['cmgt_sermon']);?>" readonly/>
										</div>	
										<div class="col col-sm-4">
											<input id="upload_image_button" type="button" class="btn btn-success upload_user_cover_button mr-2" style="float: right;" value="<?php _e( 'Upload Sermon', 'church_mgt' ); ?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 input cmgt_display margin_bottom_0px">
							<label class="ml-1 custom-top-label top" for="sermon_type"><?php _e('Status','church_mgt');?><span class="require-field">*</span></label>
							<?php if($edit) 
								$status= sanitize_text_field($result->status);
								elseif(isset($_POST['status']))
								$status= sanitize_text_field($_POST['status']);
								else
								$status='';
							?>
							<select name="status"class="form-control validate[required]">
								<option value="publish" <?php selected($status,'publish');?>><?php _e('Publish','church_mgt');?></option>
								<option value="draft" <?php selected($status,'draft');?>><?php _e('Draft','church_mgt');?></option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mt-2">
							<?php wp_nonce_field( 'save_sermon_nonce' ); ?>
							<div class="offset-sm-0">
								<input id="save_sermon" type="submit" value="<?php if($edit){ _e('Save Sermon','church_mgt'); }else{ _e('Add Sermon','church_mgt');}?>" name="save_sermon" class="btn btn-success col-md-12 save_btn"/>
							</div>
						</div>	
					</div>
				</div>
			</form><!-- SERMON FORM END-->
		</div> <!-- PANEL BODY DIV END--> 
     <?php 
	}
	 ?>