<?php ?>
<?php 	
	if($active_tab == 'view-gift')
	{
        $gift_id=0;
			if(isset($_REQUEST['gift_id']))		
			$gift_id=$_REQUEST['gift_id'];
			$result = $obj_gift->MJ_cmgt_get_single_gift($gift_id);
			 ?>
		<div class="panel-body"><!-- PENAl BODY DIV START-->
			<form name="gift_form" action="" method="post" class="" id="gift_form"><!-- GIFT FORM START-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<?php 
				if($result->gift_type=='video')
				{?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Name','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->gift_name);?>
							</label>
						</div>
						
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Price','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
							<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo _e($result->gift_type,'church_mgt');?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php
									if(!empty($result->description))
									{
										echo esc_attr($result->description);
									}else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								?>
							</label>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12">
						<video width="100%" style="outline: none;" controls>
							<source src="<?php echo $result->media_gift;?>" type="video/mp4">
							Your browser does not support the video tag.
						</video>	
					</div>
				</div>
				<?php
				} 
				if($result->gift_type=='image')
				{?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Name','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->gift_name);?>
							</label>
						</div>
						
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Price','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
							<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo _e($result->gift_type,'church_mgt');?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php
									if(!empty($result->description))
									{
										echo esc_attr($result->description);
									}else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								 ?>
							</label>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 ">
				   <image  class="sermon_image" width="680px" height="460px" style="outline: none;" src="<?php echo $result->media_gift;?>">
				</div>
				 
				<?php 
				}
				if($result->gift_type=='pdf')
				{?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Name','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->gift_name);?>
							</label>
						</div>
						
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Price','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
							<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo _e($result->gift_type,'church_mgt');?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php
									if(!empty($result->description))
									{
										echo esc_attr($result->description);
									}else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								 ?>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php
					if(isset($_REQUEST['web_type']) && $_REQUEST['web_type'] == "church_app")
					{
						?>
						<div class="form-body user_form margin_top_40px">
							<div class="row">
								<div class="col-md-1 pdf_btn_rs">
									<?php
									if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) 
									{ 
										?>
										<a href="https://drive.google.com/viewerng/viewer?embedded=true&url=<?php echo $result->media_gift;?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
										<?php
									}else{
										?>
										<a href="<?php echo esc_attr($result->media_gift);?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
										<?php
									}
									?>
								</div>
							</div>
						</div>
						<?php
					}
					else{
						?>
						<iframe src="<?php echo $result->media_gift;?>" frameborder="0" width="100%" height="600px"></iframe>
						<?php
					}  ?>
				</div>
				<?php 
				}
				if($result->gift_type=='audio')
				{?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Name','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->gift_name);?>
							</label>
						</div>
						
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Price','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
							<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo _e($result->gift_type,'church_mgt');?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php
									if(!empty($result->description))
									{
										echo esc_attr($result->description);
									}else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								 ?>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group col-sm-12 col-md-12 col-xs-12">
				   <audio id="audio-player" width="100%" height="600px" style="outline: none;" src="<?php if($result->media_gift != ''){ echo $result->media_gift;}?>" type="audio/mp3" controls="controls"></audio>
				</div>
				<?php 
				}
				if($result->gift_type=='service')
				{
					$ext = pathinfo($result->media_gift, PATHINFO_EXTENSION);
					?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Name','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->gift_name);?>
							</label>
						</div>
						
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Price','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
							<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo _e($result->gift_type,'church_mgt');?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6  width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php
									if(!empty($result->description))
									{
										echo esc_attr($result->description);
									}else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								 ?>
							</label>
						</div>
					</div>
				</div>
				<?php 
				if($ext=='mkv' || $ext=='mp4' || $ext=='flv' || $ext=='3gp' || $ext=='vob' || $ext=='wmv')
				{
				?>
				<div class=" col-sm-12 col-md-12 col-xs-12 ">
					<video width="100%" height="400px" style="outline: none;" controls>
						<source src="<?php echo $result->media_gift;?>" type="video/mp4">
						Your browser does not support the video tag.
					</video>	
				</div>
				<?php 
				}
				elseif($ext=='gif' || $ext=='jpg' || $ext=='png' || $ext=='tif' || $ext=='psd' || $ext=='bmp' || $ext=='pspimage' )
				{ 
				?>
				<div class="col-xs-12 col-sm-12 ">
				   <image  class="sermon_image" width="680px" height="460px" style="outline: none;" src="<?php echo $result->media_gift;?>">
				</div>
				<?php 
				}
				elseif($ext=='pdf')
				{ 
				?>
				<div class="form-group">
					<?php
					if(isset($_REQUEST['web_type']) && $_REQUEST['web_type'] == "church_app")
					{
						?>
						<div class="form-body user_form margin_top_40px">
							<div class="row">
								<div class="col-md-1 pdf_btn_rs">
									<?php
									if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) 
									{ 
										?>
										<a href="https://drive.google.com/viewerng/viewer?embedded=true&url=<?php echo $result->media_gift;?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
										<?php
									}else{
										?>
										<a href="<?php echo esc_attr($result->media_gift);?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
										<?php
									}
									?>
								</div>
							</div>
						</div>
						<?php
					}
					else{
						?>
						<iframe src="<?php echo $result->media_gift;?>" frameborder="0" width="100%" height="600px"></iframe>
						<?php
					}  ?>
				</div>
				<?php 
				}
				elseif($ext=='mp3' || $ext=='wma' || $ext=='wav' || $ext=='ogg' )
				{ 
				?>
				<div class="form-group col-sm-12 col-md-12 col-xs-12">
				   <audio id="audio-player" width="100%" height="600px" style="outline: none;" src="<?php if($result->media_gift != ''){ echo $result->media_gift;}?>" type="audio/mp3" controls="controls"></audio>
				</div>
				<?php
				}
				}
				if($result->gift_type=='product')
				{ 
				$ext = pathinfo($result->media_gift, PATHINFO_EXTENSION);
				?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Name','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->gift_name);?>
							</label>
						</div>
						
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Price','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
							<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Gift Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo _e($result->gift_type,'church_mgt');?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php
									if(!empty($result->description))
									{
										echo esc_attr($result->description);
									}else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								 ?>
							</label>
						</div>
					</div>
				</div>
				<?php 
				if($ext=='mkv' || $ext=='mp4' || $ext=='flv' || $ext=='3gp' || $ext=='vob' || $ext=='wmv')
				{
				?>
				<div class=" col-sm-12 col-md-12 col-xs-12 ">
					<video width="100%" height="400px" style="outline: none;" controls>
						<source src="<?php echo $result->media_gift;?>" type="video/mp4">
						Your browser does not support the video tag.
					</video>	
				</div>
				<?php 
				}
				elseif($ext=='gif' || $ext=='jpg' || $ext=='png' || $ext=='tif' || $ext=='psd' || $ext=='bmp' || $ext=='pspimage' )
				{ 
				?>
				<div class="col-xs-12 col-sm-12 ">
				   <image  class="sermon_image" width="680px" height="460px" style="outline: none;" src="<?php echo $result->media_gift;?>">
				</div>
				<?php 
				}
				elseif($ext=='pdf')
				{ 
				?>
				<div class="form-group">
					<?php
					if(isset($_REQUEST['web_type']) && $_REQUEST['web_type'] == "church_app")
					{
						?>
						<div class="form-body user_form margin_top_40px">
							<div class="row">
								<div class="col-md-1 pdf_btn_rs">
									<?php
									if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) 
									{ 
										?>
										<a href="https://drive.google.com/viewerng/viewer?embedded=true&url=<?php echo $result->media_gift;?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
										<?php
									}else{
										?>
										<a href="<?php echo esc_attr($result->media_gift);?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
										<?php
									}
									?>
								</div>
							</div>
						</div>
						<?php
					}
					else{
						?>
						<iframe src="<?php echo $result->media_gift;?>" frameborder="0" width="100%" height="600px"></iframe>
						<?php
					}  ?>
				</div>
				<?php 
				}
				elseif($ext=='mp3' || $ext=='wma' || $ext=='wav' || $ext=='ogg' )
				{ 
				?>
				<div class="form-group col-sm-12 col-md-12 col-xs-12">
				   <audio id="audio-player" width="100%" height="600px" style="outline: none;" src="<?php if($result->media_gift != ''){ echo $result->media_gift;}?>" type="audio/mp3" controls="controls"></audio>
				</div>
				<?php
				}
			}
				?>
			</form><!-- GIFT FORM END-->
        </div><!-- PENAl BODY DIV END-->
     <?php 
	}
	?>