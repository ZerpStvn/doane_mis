<?php ?>
<?php 	
if($active_tab == 'view-sermon')
	{
		$gift_id=0;
		if(isset($_REQUEST['sermon_id']))		
			$sermon_id= sanitize_text_field($_REQUEST['sermon_id']);
		$result = $obj_sermon->MJ_cmgt_get_single_sermon($sermon_id);?>
	<div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="gift_form" action="" method="post" class="form-horizontal" id="gift_form"><!-- GIFT FORM START-->
			<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
			<?php 
			if($result->sermon_type=='video')
			{?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">	
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Sermon Title','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->sermon_title);?>
							</label>
						</div>
						
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Sermon Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->sermon_type);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Status','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php 	
									if($result->status=='publish')
									{ 
										echo esc_html__("Publish",'church_mgt');
									}
									else
									{ 
										echo esc_html__("Draft",'church_mgt'); 
									}?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->description	);?>
							</label>
						</div>
						<div class="col-xs-12 col-sm-12">
							<video width="100%" height="auto"style="outline: none;" controls>
								<source src="<?php echo esc_attr($result->sermon_content);?>" type="video/mp4">
								Your browser does not support the video tag.
							</video>	
						</div>
					</div>
				</div>
			<?php 
			} 
			if($result->sermon_type=='audio')
			{?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">	
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Sermon Title','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->sermon_title);?>
							</label>
						</div>
							
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Sermon Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->sermon_type);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Status','church_mgt');?>
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php 	
									if($result->status=='publish')
									{ 
										echo esc_html__("Publish",'church_mgt');
									}
									else
									{ 
										echo esc_html__("Draft",'church_mgt'); 
									}?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?>: 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php
									if(!empty($result->description)){
										echo esc_attr($result->description);
									}
									else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								?>
							</label>
						</div>
						<div class="sermon-audio col-xs-12 col-sm-12">
							<audio id="audio-player" width="100%" height="600px" style="outline: none;"src="<?php if($result->sermon_content != ''){ echo esc_attr($result->sermon_content);}?>" type="audio/mp3" controls="controls"></audio>
						</div>
					</div>
				</div>
				<?php 
			} 
			if($result->sermon_type=='image')
			{?>
			<div class="form-group cmgt_view_sermon row mb-3">
				<div class="col-sm-6 col-xs-6 mb-2">
					<label class="popup_label_heading"  for="venue_name">
						<?php _e('Sermon Title','church_mgt');?>
					</label>
					<br>
					<label class="popup_label_value" for="venue_name">
						<?php echo esc_attr($result->sermon_title);?>
					</label>
				</div>
				
				<div class="col-sm-6 col-xs-6 mb-2">
					<label class="popup_label_heading"  for="venue_name">
						<?php _e('Sermon Type','church_mgt');?>
					</label>
					<br>
					<label class="popup_label_value" for="venue_name">
						<?php echo esc_attr($result->sermon_type);?>
					</label>
				</div>
				<div class="col-sm-6 col-xs-6 mb-2">
					<label class="popup_label_heading"  for="venue_name">
						<?php _e('Status','church_mgt');?>
					</label>
					<br>
					<label class="popup_label_value" for="venue_name">
						<?php 	
							if($result->status=='publish')
							{ 
								echo esc_html__("Publish",'church_mgt');
							}
							else
							{ 
								echo esc_html__("Draft",'church_mgt'); 
							}?>
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
							}
							else
							{
								echo esc_html( __( 'N/A', 'church_mgt' ) );
							}
						?>
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="mb-3 row">	
					<div class="col-xs-12 col-sm-12 ">
					   <image  class="sermon_image" width="680px" height="460px" src="<?php echo esc_attr($result->sermon_content);?>">
					</div>
				</div>
			</div>
			<?php 
			}
			if($result->sermon_type=='pdf')
			{?>
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">	
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Sermon Title','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->sermon_title);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Sermon Type','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr($result->sermon_type);?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Status','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php 	
									if($result->status=='publish')
									{ 
										echo esc_html__("Publish",'church_mgt');
									}
									else
									{ 
										echo esc_html__("Draft",'church_mgt'); 
									}?>
							</label>
						</div>
						<div class="col-sm-6 col-xs-6 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Description','church_mgt');?>: 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php
									if(!empty($result->description)){
										echo esc_attr($result->description);
									}
									else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								?>
							</label>
						</div>
					  <div class="col-xs-12 col-sm-12">
						<?php 
						if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) 
						{ ?>
						   <iframe src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?php echo $result->sermon_content;?>" target="_blank" frameborder="0" width="100%" height="600px"></iframe>
						    
						<?php 
						}
                       else 
						{ ?>
                           <iframe src="<?php echo esc_attr($result->sermon_content);?>"  target="_blank" frameborder="0" width="100%" height="600px"></iframe>
                        <?php
                        };
						 ?>
						</div>
									

					</div>
				</div>
				<?php 
			}?>
        </form><!-- GIFT FORM END-->
    </div> <!-- PANEL BODY DIV END--> 
     <?php 
	}
	 ?>