<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#save_song").click(function() 
	{
		
		var ext = $('#cmgt_church_background_image').val().split('.').pop().toLowerCase();
		if(ext =='' || ext == null)
		{
			alert("<?php _e('please fill in the required fields','church_mgt');?>");
			return false;	
		}else
		{
		if($.inArray(ext, ['mp3','wma','wav','ogg']) == -1) {
			alert("<?php _e('Only audio formats files are allowed!','church_mgt');?>");
			return false;
		}
		}
	});
	$('#gift_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
} );
</script>
     <?php 	
	if($active_tab == 'addsong')
	{
		
		$song_id=0;
		if(isset($_REQUEST['song_id']))
			$song_id= sanitize_text_field($_REQUEST['song_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
				
				$edit=1;
				$result = $obj_song->MJ_cmgt_get_single_song($song_id);
			}?>
		
		<div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="gift_form" action="" method="post" class="form-horizontal" id="gift_form"><!-- SONG FORM START-->
				<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="song_id" value="<?php echo esc_attr($song_id);?>"  />
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Song Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6 cmgt_display">
							<div class="form-group input row margin_buttom_0">
								<div class="col-md-8">
									<label class="ml-1 custom-top-label top" for="activity_category"><?php _e('Song Category','church_mgt');?><span class="require-field">*</span></label>

									<select class="form-control line_height_30px validate[required]" name="song_cat_id" id="song_category">

										<option value=""><?php _e('Select Song Category','church_mgt');?></option>
										<?php 
										
										if(isset($_REQUEST['song_cat_id']))
											$category =$_REQUEST['song_cat_id'];  
										elseif($edit)
											$category =$result->song_cat_id;
										else 
											$category = "";
										
										$activity_category=MJ_cmgt_get_all_category('song_category');
										if(!empty($activity_category))
										{
											foreach ($activity_category as $retrive_data)
											{
												echo '<option value="'.esc_attr($retrive_data->ID).'" '.selected($category,$retrive_data->ID).'>'.esc_attr($retrive_data->post_title).'</option>';
											}
										}?>
									</select>
								</div>
								<div class="col-md-4">
									<button class="btn btn-success width_100 btn_height" id="addremove" model="song_category"><?php _e('Add Or Remove','church_mgt');?></button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="song_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo esc_attr($result->song_name);}elseif(isset($_POST['song_name'])) echo esc_attr($_POST['song_name']);?>" name="song_name">
									<label class="" for="song_name"><?php _e('Song Name','church_mgt');?><span class="require-field">*</span></label>
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
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control margin_bottom_15">	
									<label class="custom-control-label custom-top-label ml-2" for="gmgt_membershipimage"><?php _e('Song','church_mgt');?><span class="require-field">*</span></label>

									<div class="row">
										<div class="col col-sm-8 ">
											<input type="text" class="validate[required]" id="cmgt_church_background_image" name="song" 
											value="<?php if($edit){ echo esc_attr($result->song);}elseif(isset($_POST['song'])) echo esc_attr($_POST['song']);?>" />
										</div>	
										<div class="col col-sm-4 ">
											<input id="upload_image_button" type="button" class="btn btn-success upload_user_cover_button mr-2" style="float: right;" value="<?php _e( 'Upload Song', 'church_mgt' ); ?>" />
										</div>
									</div>
								</div>
							</div>
							<div id="upload_gym_cover_preview" style="min-height: 50px; padding-bottom:10px; padding-top: 15px;">
								<audio controls="" id="audio-player" style="outline: none;" src="<?php if($edit && $result->song != ''){ echo esc_attr($result->song);}elseif(isset($_POST['song'])) echo esc_attr($_POST['song']);?>" type="audio/mpeg" style="margin-top:10px;"></audio>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<?php wp_nonce_field( 'save_song_nonce' ); ?>
							<div class="offset-sm-0">
								<input id="save_song" type="submit" value="<?php if($edit){ _e('Save','church_mgt'); }else{ _e('Add Song','church_mgt');}?>" name="save_song" class="btn btn-success col-md-12 save_btn"/>
							</div>
						</div>	
					</div>
				</div>




				<!-- <div class="form-group">
					<div class="mb-3 row">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="activity_category"><?php _e('Song Category','church_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8 col-xs-9">
							<select class="form-control validate[required]" name="song_cat_id" id="song_category">
							<option value=""><?php _e('Select Song Category','church_mgt');?></option>
							<?php 
							
							if(isset($_REQUEST['song_cat_id']))
								$category =$_REQUEST['song_cat_id'];  
							elseif($edit)
								$category =$result->song_cat_id;
							else 
								$category = "";
							
							$activity_category=MJ_cmgt_get_all_category('song_category');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									echo '<option value="'.esc_attr($retrive_data->ID).'" '.selected($category,$retrive_data->ID).'>'.esc_attr($retrive_data->post_title).'</option>';
								}
							}?>
							</select>
						</div>
						<div class="col-sm-2 col-xs-9 top1">
							<button class="btn btn-primary" id="addremove" model="song_category"><?php _e('Add Or Remove','church_mgt');?></button>
						</div>
					</div>	
				</div>
				<div class="form-group">
					<div class="mb-3 row">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="song_name"><?php _e('Song Name','church_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<input id="song_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->song_name);}elseif(isset($_POST['song_name'])) echo esc_attr($_POST['song_name']);?>" name="song_name">
						</div>
					</div>	
				</div>
				<div class="form-group">
					<div class="mb-3 row">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="description"><?php _e('Description','church_mgt');?></label>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
						<textarea name="description" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="description"><?php if($edit){ echo esc_attr($result->description);}?></textarea>
						</div>
					</div>	
				</div>
				<div class="form-group">
					<div class="mb-3 row">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="gmgt_membershipimage"><?php _e('Song','church_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<input type="text" class="validate[required]" id="cmgt_church_background_image" name="song" 
							value="<?php if($edit){ echo esc_attr($result->song);}elseif(isset($_POST['song'])) echo esc_attr($_POST['song']);?>" />	
							<input id="upload_image_button" type="button" class="button upload_user_cover_button margin_top_10_res" value="<?php _e( 'Upload Song', 'church_mgt' ); ?>" />

							
							<div id="upload_gym_cover_preview" style="min-height: 50px; padding-bottom:10px; padding-top: 15px;">
								<audio controls="" id="audio-player" style="outline: none;" src="<?php if($edit && $result->song != ''){ echo esc_attr($result->song);}elseif(isset($_POST['song'])) echo esc_attr($_POST['song']);?>" type="audio/mpeg" style="margin-top:10px;"></audio>
							</div>
						</div>
					</div>	
				</div>
				<?php wp_nonce_field( 'save_song_nonce' ); ?>
				<div class="offset-sm-2 col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<input id="save_song" type="submit" value="<?php if($edit){ _e('Save','church_mgt'); }else{ _e('Add Song','church_mgt');}?>" name="save_song" class="btn btn-success"/>
				</div> -->


			</form><!-- SONG FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>