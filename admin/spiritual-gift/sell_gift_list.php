<?php 
if($active_tab == 'sellgiftlist')
{ 
	$giftdata=$obj_gift->MJ_cmgt_get_all_sell_gifts();
	if(!empty($giftdata))
	{
		?>	
		<script type="text/javascript">
			$(document).ready(function() {
			jQuery('#sell_gift_list').DataTable({
				//"responsive": true,
				"dom": 'lifrtp',
				language:<?php echo MJ_cmgt_datatable_multi_language();?>,
				"order": [[ 2, "asc" ]],
				"sSearch": "<i class='fa fa-search'></i>",
				"aoColumns":[
								{"bSortable": false},
								{"bSortable": false},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": false}]
				});
				$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
				$('.select_all').on('click', function(e)
					{
							if($(this).is(':checked',true))  
							{
							$(".sub_chk").prop('checked', true); 
							$(".select_all").prop('checked', true);							
							}  
							else  
							{  
							$(".sub_chk").prop('checked',false);
							$(".select_all").prop('checked', false);							
							} 
					});
				
					$('.sub_chk').on('change',function()
					{ 
						if(false == $(this).prop("checked"))
						{ 
							$(".select_all").prop('checked', false); 
						}
						if ($('.sub_chk:checked').length == $('.sub_chk').length )
						{
							$(".select_all").prop('checked', true);
						}
					});
			} );
		</script>
		<!-- POP up code -->
		<div class="popup-bg" style="z-index:100000 !important;">
			<div class="overlay-content" id="cmgt_service_pop">
				<div class="modal-content">
					<div class="invoice_data">
					</div>
				</div>
			</div>  
		</div>
		<!-- End POP-UP Code -->
		<form name="wcwm_report" action="" method="post">
			<div class="panel-body"><!-- PANEL BODY DIV START-->
				<div class="cmgt_gift_table_responsive table-responsive"><!-- TABLE RESPONSIVE DIV STRAT-->
					<table id="sell_gift_list" class="display" cellspacing="0" width="100%">
						<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
							<tr>
								<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
								<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Gift Name', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Gift Invoice Number', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Date', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Gift Price', 'church_mgt' ) ;?></th>
								<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
								<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Gift Name', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Gift Invoice Number', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Date', 'church_mgt' ) ;?></th>
								<th><?php  _e( 'Gift Price', 'church_mgt' ) ;?></th>
								<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
							</tr>
						</tfoot>
						<tbody>
							<?php 
								$i=0;
								if(!empty($giftdata))
								{
									foreach ($giftdata as $retrieved_data)
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
											<td class="cmgt-checkbox_width_10px"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo esc_attr($retrieved_data->id); ?>"></td>
											<td class="user_image width_50px profile_image_prescription padding_left_0">
												<p class="padding_15px prescription_tag margin_bottom_0 <?php echo $color_class; ?>">	
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Sell-Gift-white.png"?>" alt="" class="massage_image center">
												</p>
											</td>
											<td class="giftname">
												<a class="color_black show-invoice-popup" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="sell_gift" href="#">
													<?php echo MJ_cmgt_church_get_display_name(esc_attr($retrieved_data->member_id));?>
												</a> 
											</td>
											<td class="gift">
												<?php echo MJ_cmgt_church_get_gift_name(esc_attr($retrieved_data->gift_id));?> 
											</td>
											<td class="gift">
												<?php 
													$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($retrieved_data->id));
													echo get_option( 'cmgt_payment_prefix' ).''.$invoice_number;?> 
											</td>
											<td class="sell_date">
												<?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->sell_date)));?> 
											</td>
											<td class="giftprice width_15_per">
												<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->gift_price);?> 
											</td>
											<td class="action cmgt_pr_0px">
												<div class="cmgt-user-dropdown mt-2">
													<ul class="">
														<!-- BEGIN USER LOGIN DROPDOWN -->
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
															</a>
															<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">

																<li>
																	<a href="?page=cmgt-gifts&tab=view_sellgift&idtest=<?php echo esc_attr($retrieved_data->id);?>&invoice_type=sell_gift" class="dropdown-item"><i class="fa fa-eye"></i><?php _e('View Invoice', 'church_mgt' ) ;?></a>
																</li>
																<?php
																	if ($user_access_edit == 1) 
																	{ 
																?>
																<li>
																	<a href="?page=cmgt-gifts&tab=sellgift&action=edit&sell_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a>
																</li>
																<?php
																	}
																?>
																<div class="cmgt-dropdown-deletelist">
																	<?php
																		if ($user_access_delete == 1) 
																		{ 
																	?>
																	<li>
																		<a href="?page=cmgt-gifts&tab=sellgiftlist&action=delete&sell_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item" 
																		onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?> </a>
																	</li>
																	<?php
																	}
																	?>
																</div>

															</ul>
														</li>
														<!-- END USER LOGIN DROPDOWN -->
													</ul>
												</div>
											</td>
										
										</tr>
										<?php 
										$i++;
									} 
								} ?>
						</tbody>
					</table>
					<div class="print-button pull-left cmgt_print_btn_p0">
						<button class="btn btn-success btn-niftyhms">
							<input type="checkbox" name="selected_id[]" class="select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">
							<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'church_mgt' ) ;?></label>
						</button>
						<?php
							if ($user_access_delete == 1) 
							{ 
						?>
						<button data-toggle="tooltip" title="<?php esc_html_e('Delete All','church_mgt');?>" name="delete_selected1" class="delete_all_button"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Delete.png" ?>" alt=""></button>
						<?php
							}
						?>
					</div>
				</div><!-- TABLE RESPONSIVE DIV END-->
			</div><!-- PANEL BODY DIV END-->
		</form>
		<?php 
	}
	else
	{
		?>
		<div class="no_data_list_div"> 
			<?php
				if(MJ_cmgt_add_check_access_for_view_add('spiritual-gift','add'))
				{ 
			?>
			<a href="<?php echo admin_url().'admin.php?page=cmgt-gifts&tab=sellgift';?>">
				<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
			</a>
			<?php
				}
			?>
			<div class="col-md-12 dashboard_btn margin_top_20px">
				<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
			</div> 
		</div>		
		<?php
	}
} ?>