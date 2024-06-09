<?php ?>
<?php 	
if($active_tab == 'view-sermon')
	{
        $gift_id=0;
			if(isset($_REQUEST['sermon_id']))		
				$sermon_id=$_REQUEST['sermon_id'];
				$result = $obj_sermon->MJ_cmgt_get_single_sermon($sermon_id);?>
		<div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="gift_form" action="" method="post" class="form-horizontal" id="gift_form"><!-- GIFT FORM START-->
				<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
				<?php 
				if($result->sermon_type=='video')
				{?>
					<div class="form-group">
						<label class="col-sm-6 col-sm-5 control-label" for="sermon_title"><h2><?php echo esc_attr($result->sermon_title);?></h2></label>
						<div class="">
							<video width="100%" height="600px" controls>
								<source src="<?php echo esc_url($result->sermon_content);?>" type="video/mp4">
								Your browser does not support the video tag.
							</video>	
						</div>
					</div>
				<?php 
				} 
				if($result->sermon_type=='audio')
				{?>
				<div class="form-group">
					<label class="col-sm-6 col-sm-5 control-label" for="sermon_title"><h2><?php echo esc_attr($result->sermon_title);?></h2></label>
					<div class="sermon-audio">
						<audio id="audio-player" width="100%" height="600px" src="<?php if($result->sermon_content != ''){ echo esc_url($result->sermon_content);}?>" type="audio/mp3" controls="controls"></audio>
					</div>
				</div>
				<?php 
				} 
				if($result->sermon_type=='image')
				{?>
				<div class="form-group">
					<label class="col-sm-6 col-sm-5 control-label" for="sermon_title"><h2><?php echo esc_attr($result->sermon_title);?></h2></label>
					<div class="">
						<image width="100%" height="600px" src="<?php echo $result->sermon_content;?>">
						
					</div>
				</div>
				<?php 
				}
				if($result->sermon_type=='pdf')
				{?>
				<div class="form-group">
					<label class="col-sm-6 col-sm-5 control-label" for="sermon_title"><h2><?php echo esc_attr($result->sermon_title);?></h2></label>
					<div class="">
						<iframe src="<?php echo esc_url($result->sermon_content);?>" frameborder="0" width="100%" height="600px"></iframe>
					</div>
				</div>
				<?php 
				}?>
			</form><!-- GIFT FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>