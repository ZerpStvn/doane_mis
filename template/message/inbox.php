<?php 
$message = $obj_message->MJ_cmgt_count_inbox_item(get_current_user_id());
$max = 10;
if(isset($_GET['pg']))
{
	$p = $_GET['pg'];
}else
{
	$p = 1;
}
$limit = ($p - 1) * $max;
$prev = $p - 1;
$next = $p + 1;
$limits = (int)($p - 1) * $max;
$totlal_message =count($message);
$totlal_message = ceil($totlal_message / $max);
$lpm1 = $totlal_message - 1;
$offest_value = ($p-1) * $max;
echo $obj_message->MJ_cmgt_pagination($totlal_message,$p,$prev,$next,'church-dashboard=user&&page=message&tab=inbox');
?>
<div class="mailbox-content">
    <!--MAILBOX CONTENT DIV STRAT-->

	<script type="text/javascript">
		$(document).ready(function() {
		jQuery('#inbox_list').DataTable({
			//"responsive": true,
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
	$message = $obj_message->MJ_cmgt_get_inbox_message(get_current_user_id(),$limit,$max);
	if(!empty($message))
	{
		?>
		<form name="wcwm_report" action="" method="post">
			<div class="padding_frontendlist_body"><!-- START PANEL BODY DIV-->
				<div class="table-responsive"> <!-- TABLE RESPONSIVE DIV START-->
					<table id="inbox_list" class="display" cellspacing="0" width="100%"><!--SENDBOX TABLE STRAT-->

						<!--INBOX TABLE STRAT-->
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
							$i=0;
							foreach($message as $msg)
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
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Send_icon.png"?>" alt="" class="massage_image center">
										</p>
									</td>
									<td class="min_width_110_px"><?php echo MJ_cmgt_church_get_display_name($msg->sender);?> </td>
									<td class="min_width_150_px">
										<a class="msg_color_black" href="?church-dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"> <?php 
										$subject_char=strlen($msg->msg_subject);
										if($subject_char <= 25)
										{
											echo esc_attr($msg->msg_subject);
										}
										else
										{
											$char_limit = 25;
											$subject_body= substr(strip_tags($msg->msg_subject), 0, $char_limit)."...";
											echo esc_attr($subject_body);
										}
										?><?php if(MJ_cmgt_count_reply_item($msg->post_id)>=1){?><span class="badge badge-success pull-right"><?php echo MJ_cmgt_count_reply_item($msg->post_id);?></span><?php } ?>
										</a>
									</td>
									<td class="max_width_400_px">
										<?php 
											$body_char=strlen($msg->message_body);
											if($body_char <= 55)
											{
												echo $msg->message_body;
											}
											else
											{
												$char_limit = 55;
												$msg_body= substr(strip_tags($msg->message_body), 0, $char_limit)."...";
												echo $msg_body;
											}
										?> 
									</td>
									<td>
										<!-- <?php  echo  mysql2date('d M', $msg->msg_date );?>  -->
										<?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($msg->msg_date)));?>
										
									</td>
								</tr>
								<?php 
								$i++;
							}
							?>

						</tbody>
					</table><!--SENDBOX TABLE END-->
				</div><!--TABLE RESPONSIVE DIV END-->
			</div><!-- PANEL BODY DIV END-->
		</form>
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
		?>
</div>
<!--MAILBOX CONTENT DIV END-->
<?php ?>