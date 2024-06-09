<?php ?>
<?php 	
	if($active_tab == 'view-song')
	{
        $song_id=0;
			if(isset($_REQUEST['song_id']))		
				$song_id= sanitize_text_field($_REQUEST['song_id']);
				$result = $obj_song->MJ_cmgt_get_single_song($song_id);
?>
		<div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="gift_form" action="" method="post" class="form-horizontal" id="gift_form"><!-- GIFT FORM START-->
				<div class="form-group cmgt_view_sermon">
					<div class="mb-3 row">
						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Song Name','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo esc_attr (ucfirst($result->song_name));?>
							</label>
						</div>

						<div class="col-sm-6 col-xs-6 width_50 mb-2">
							<label class="popup_label_heading"  for="venue_name">
								<?php _e('Song Category','church_mgt');?> 
							</label>
							<br>
							<label class="popup_label_value" for="venue_name">
								<?php echo ucfirst(get_the_title(esc_attr($result->song_cat_id)));?>
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
									echo esc_attr(ucfirst($result->description));
								}else{
									echo esc_html( __( 'N/A', 'church_mgt' ) );
								}
								?>
							</label>
						</div>
					</div>
					<div class="sermon-audio col-xs-12 col-sm-12 col-md-12 ">
						<audio id="audio-player" width="100%" height="600px" style="outline: none;"src="<?php if($result->song != ''){ echo esc_attr($result->song);}?>" type="audio/mp3" controls="controls"></audio>
					</div>
				</div>
			</form><!-- GIFT FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>