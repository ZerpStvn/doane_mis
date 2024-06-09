<?php 
$max = 10;
if(isset($_GET['pg'])){
	$p = $_GET['pg'];
}else{
	$p = 1;
}

$limit = ($p - 1) * $max;
$prev = $p - 1;
$next = $p + 1;
$limits = (int)($p - 1) * $max;
$totlal_message1 = $obj_message->MJ_cmgt_count_send_item(get_current_user_id());
$totlal_message = ceil($totlal_message1 / $max);
$lpm1 = $totlal_message - 1;               	
$offest_value = ($p-1) * $max;
echo $obj_message->MJ_cmgt_pagination($totlal_message,$p,$lpm1,$prev,$next);
?>
<div class="mailbox-content">
	<script type="text/javascript">
		$(document).ready(function() {
		jQuery('#patient_list').DataTable({
			"dom": 'lifrtp',
			language:<?php echo MJ_cmgt_datatable_multi_language();?>,
			"order": [[ 2, "asc" ]],
			"sSearch": "<i class='fa fa-search'></i>",
			"aoColumns":[
							{"bSortable": false},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true}]
			});
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			
		} );
	</script>
	<?php
	$offset = 0;
	if(isset($_REQUEST['pg']))
	$offset = $_REQUEST['pg'];
	$message = $obj_message->MJ_cmgt_get_send_message(get_current_user_id(),$max,$offset);
	if(!empty($message))
	{
		?>
		<form name="wcwm_report" action="" method="post">
			<div class="padding_frontendlist_body"><!-- START PANEL BODY DIV-->
				<div class="table-responsive"> <!-- TABLE RESPONSIVE DIV START-->
					<table id="patient_list" class="display" cellspacing="0" width="100%">
					<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
							<tr> 
								<th><?php esc_html_e('Image','church_mgt');?></th>			
								<th><?php esc_html_e('Message For','church_mgt');?></th>
								<th><?php esc_html_e('Subject','church_mgt');?></th>
								<th><?php esc_html_e('Message Comment','church_mgt');?></th>
								<th><?php esc_html_e('Date','church_mgt');?></th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$offset = 0;
						if(isset($_REQUEST['pg']))
							$offset = $_REQUEST['pg'];
						$message = $obj_message->MJ_cmgt_get_send_message(get_current_user_id(),$max,$offset);
						$i=0;
						
						foreach($message as $msg_post)
						{
							if($msg_post->post_author==get_current_user_id())
							{
								if($i == 0)
								{
									$color_class='cmgt_list_page_image_color0';
								}
								elseif($i == 1)
								{
									$color_class='cmgt_list_page_image_color1';
								}
								elseif($i == 2)
								{
									$color_class='cmgt_list_page_image_color2';
								}
								elseif($i == 3)
								{
									$color_class='cmgt_list_page_image_color3';
								}
								elseif($i == 4)
								{
									$color_class='cmgt_list_page_image_color4';
								}
								elseif($i == 5)
								{
									$color_class='cmgt_list_page_image_color5';
								}
								elseif($i == 6)
								{
									$color_class='cmgt_list_page_image_color6';
								}
								elseif($i == 7)
								{
									$color_class='cmgt_list_page_image_color7';
								}
								elseif($i == 8)
								{
									$color_class='cmgt_list_page_image_color8';
								}
								elseif($i == 9)
								{
									$color_class='cmgt_list_page_image_color9';
								}
								?>
							<tr>
								<td class="user_image width_50px profile_image_prescription padding_left_0">
									<p class="padding_15px prescription_tag margin_bottom_0 <?php echo $color_class; ?>">	
										<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/sendbox.png"?>" alt="" class="massage_image center">
									</p>
								</td>
								<td class="hidden-xs min_width_110_px">
									<span><?php 
								
									if(get_post_meta( $msg_post->ID, 'message_for',true) == 'user')
									{
										echo MJ_cmgt_church_get_display_name(get_post_meta( $msg_post->ID, 'message_for_userid',true));
									}
									else
									{
										echo MJ_cmgt_get_role_name_in_message(get_post_meta( $msg_post->ID, 'message_for',true));?></span>
									<?php
									}
									?> 
								</td>
								<td class="min_width_150_px">
									<a class="msg_color_black" href="?church-dashboard=user&page=message&tab=view_message&from=sendbox&id=<?php echo $msg_post->ID;?>">
										<?php 
										$subject_char=strlen($msg_post->post_title);
										if($subject_char <= 25)
										{
											echo $msg_post->post_title;
										}
										else
										{
											$char_limit = 25;
											$subject_body= substr(strip_tags($msg_post->post_title), 0, $char_limit)."...";
											echo $subject_body;
										}
										?>
										<?php 
										if(MJ_cmgt_count_reply_item($msg_post->ID)>=1)
										{
											?>
											<span class="badge badge-success pull-right">
												<?php echo MJ_cmgt_count_reply_item($msg_post->ID);?>
											</span>
											<?php
										} 
										?>
									</a> 
								</td>
								<td class="max_width_400_px">
									<?php 
										$body_char=strlen($msg_post->post_content);
										if($body_char <= 45)
										{
											echo $msg_post->post_content;
										}
										else
										{
											$char_limit = 45;
											$msg_body= substr(strip_tags($msg_post->post_content), 0, $char_limit)."...";
											echo $msg_body;
										}
									?> 
								</td>

								<td>
									
									<?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($msg_post->post_date)));?>
									
								</td>
							</tr>
							<?php
							$i++;
							} 
						}
						?>
						</tbody>
					</table>
				</div><!--TABLE RESPONSIVE DIV END-->
			</div><!-- PANEL BODY DIV END-->
		</form>
		<?php 
	}
	else
	{
		if($user_access['add']=='1')
		{
			?>
			<div class="no_data_list_div"> 
				<a href="<?php echo home_url().'?church-dashboard=user&page=message&tab=compose';?>">
					<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
				</a>
				<div class="col-md-12 dashboard_btn margin_top_20px">
					<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
				</div> 
			</div>		
			<?php
		}
		else
		{
			?>
			<div class="calendar-event-new"> 
				<img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
			</div>	
			<?php
		}
	}
	?>
</div>
<?php ?>