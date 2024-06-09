<?php 
MJ_cmgt_header();
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'giftlist');
$obj_gift=new Cmgtgift;
?>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content"id="">
		<div class="modal-content">
			<div class="category_list">
				<div class="invoice_data">
				</div>	 
			</div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<script type="text/javascript">
$(document).ready(function()
{
	//------------ CLOSE MESSAGE ---------//
	$('.notice-dismiss').click(function() {
		$('#message').hide();
	}); 
}); 
</script>
<div class="page-inner"><!-- PAGE INNER DIV START-->
	<?php 
	
	if(isset($_POST['save_gift']))
	{
		$nonce = sanitize_text_field($_POST['_wpnonce']);
		if (wp_verify_nonce( $nonce, 'save_gift_nonce' ) )
		{
		//--------- EDIT GIFT --------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_gift->MJ_cmgt_add_gift($_POST,$_POST['cmgt_gift']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=giftlist&message=2');
			}	
		}
		else
		{
			//--------- ADD GIFT --------//
			$result=$obj_gift->MJ_cmgt_add_gift($_POST,$_POST['cmgt_gift']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=giftlist&message=1');
			}
		}
	}
	}
	if(isset($_POST['sell_gift']))
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
				
			$result=$obj_gift->MJ_cmgt_sell_gift($_POST);
		
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=sellgiftlist&message=2');
			}
				
				
		}
		else
		{
				$result=$obj_gift->MJ_cmgt_sell_gift($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=sellgiftlist&message=1');
				}
		}
	}
	if(isset($_POST['give_gift']))
	{
		$result=$obj_gift->MJ_cmgt_give_gift($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=giftlist&message=4');
		}
	}
		//--------- DELETE GIFT --------//
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			if(isset($_REQUEST['gift_id']))
			{
				$result=$obj_gift->MJ_cmgt_delete_gift($_REQUEST['gift_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=giftlist&message=3');
				}
			}
			if(isset($_REQUEST['sell_id']))
			{
				$result=$obj_gift->MJ_cmgt_delete_sell_gift($_REQUEST['sell_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=sellgiftlist&message=3');
				}
			}
		}

		if(isset($_REQUEST['delete_selected']))
		{		
			if(!empty($_REQUEST['selected_id']))
			{
				
				foreach($_REQUEST['selected_id'] as $_REQUEST['gift_id'])
				{
					$result=$obj_gift->MJ_cmgt_delete_gift($_REQUEST['gift_id']);
					wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=giftlist&message=3');
				}
			}
			else
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=giftlist&message=5');
			}
		}
		if(isset($_REQUEST['delete_selected1']))
		{		
			if(!empty($_REQUEST['selected_id']))
			{
				
				foreach($_REQUEST['selected_id'] as $_REQUEST['sell_id'])
				{
					$result=$obj_gift->MJ_cmgt_delete_sell_gift($_REQUEST['sell_id']);
					wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=sellgiftlist&message=3');
				}
			}
			else
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-gifts&tab=sellgiftlist&message=5');
			}
		}
	?>
		<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('spiritual-gift');
	if($user_access == 'administrator')
	{
		$user_access_add=1;
		$user_access_edit=1;
		$user_access_delete=1;
		$user_access_view=1;
	}
	else
	{
		$user_access_view = $user_access['view'];
		$user_access_add = $user_access['add'];
		$user_access_edit = $user_access['edit'];
		$user_access_delete = $user_access['delete'];

		if (isset($_REQUEST['page'])) 
		{
			if ($user_access_view == '0') 
			{
				mj_cmgt_access_right_page_not_access_message_admin_side();
				die;
			}
			if(!empty($_REQUEST['action']))
			{
				if ('spiritual-gift' == $user_access['page_link'] && $_REQUEST['action'] == 'edit') 
				{
					if ($user_access_edit == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('spiritual-gift' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('spiritual-gift' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
				{	
					if ($user_access_delete == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
			}
		}
	}
?>
<!-- user redirect url enter code end -->
	<div id=""><!-- MAIN WRAPPER DIV START--> 
		<div class="row"><!-- ROW DIV START--> 
			<div class="col-md-12"><!-- COL 12 DIV START-->  
				<div class="panel panel-white main_home_page_div"><!-- PANEL WHITE DIV START-->  
					<?php
						if(isset($_REQUEST['message']))
						{
							$message = sanitize_text_field($_REQUEST['message']);
							if($message == 1)
							{?>
								<div id="message" class="updated below-h2 notice is-dismissible ">
								<p>
								<?php 
									_e('Record inserted successfully','church_mgt');
								?></p></div>
								<?php 
							}
							elseif($message == 2)
							{?><div id="message" class="updated below-h2 notice is-dismissible "><p><?php
								_e("Record updated successfully.",'church_mgt');
								?></p>
								</div>
							<?php 
							}
							elseif($message == 3) 
							{?>
							<div id="message" class="updated below-h2 notice is-dismissible "><p>
							<?php 
								_e('Record deleted successfully','church_mgt');
							?></div></p><?php
									
							}
							elseif($message == 4) 
							{?>
							<div id="message" class="updated below-h2 notice is-dismissible "><p>
							<?php 
								_e('Gift Assigned successfully','church_mgt');
							?></div></p><?php
							}
							elseif($message == 5) 
							{?>
								<div id="message" class="updated below-h2 notice is-dismissible "><p>
								<?php 
									_e('Please select at least one record.','church_mgt');
								?></div></p><?php	
							}
						}
					?>
					<div class="panel-body cmgt_gift_main" id="cmgt_tab_res_mt"><!-- PANEL BODY DIV START-->
						<ul class="nav margin_bottom_20px nav-tabs panel_tabs spiritual_gift_menu margin_left_1per cmgt-view-page-tab flex-nowrap overflow-auto" role="tablist"><!-- NAV TAB WRAPPER MENU START-->  
							<li class="<?php if($active_tab=='giftlist'){?>active<?php }?>">
								<a href="?page=cmgt-gifts&tab=giftlist" class="padding_left_0 tab <?php echo $active_tab == 'giftlist' ? 'nav-tab-active' : ''; ?>">
								<?php _e('Spiritual Gifts List', 'church_mgt'); ?>
								</a>
							</li>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['gift_id']))
							{?>
								<li class="<?php if($active_tab=='addgift'){?>active<?php }?>">
									<a href="?page=cmgt-gifts&tab=addgift&&action=edit&gift_id=<?php echo esc_attr($_REQUEST['gift_id']);?>" class="padding_left_0 tab <?php echo $active_tab == 'addgift' ? 'nav-tab-active' : ''; ?>">
									<?php _e('Edit Spiritual Gift', 'church_mgt'); ?></a>  
								</li>
							<?php 
							}
							else
							{?>
								<li class="<?php if($active_tab=='addgift'){?>active<?php }?>">
									<a href="?page=cmgt-gifts&tab=addgift" class="padding_left_0 tab <?php echo $active_tab == 'addgift' ? 'nav-tab-active' : ''; ?>">
									<?php _e('Add Spiritual Gift', 'church_mgt'); ?></a>
								</li>
								<?php 
							 }

							if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view-gift')
							{?>
								<li class="<?php if($active_tab=='view-gift'){?>active<?php }?>">	
									<a href="?page=cmgt-gifts&tab=view-gift&action=view-gift&gift_id=<?php echo esc_attr($_REQUEST['gift_id']);?>" class="padding_left_0 tab <?php echo $active_tab == 'view-gift' ? 'nav-tab-active' : ''; ?>">
									<?php _e('View Gifts', 'church_mgt'); ?></a>  
								</li>
							<?php 
							}
							?>

							<li class="<?php if($active_tab=='sellgiftlist'){?>active<?php }?>">
								<a href="?page=cmgt-gifts&tab=sellgiftlist" class="padding_left_0 tab <?php echo $active_tab == 'sellgiftlist' ? 'nav-tab-active' : ''; ?>">
								<?php _e('Sell Gifts List', 'church_mgt'); ?></a>
							</li>

							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['sell_id']))
							{?>
								<li class="<?php if($active_tab=='sellgift'){?>active<?php }?>">
									<a href="?page=cmgt-gifts&tab=sellgift&action=edit&sell_id=<?php echo esc_attr($_REQUEST['sell_id']);?>" class="padding_left_0 tab <?php echo $active_tab == 'sellgift' ? 'nav-tab-active' : ''; ?>">
									<?php _e('Edit Sell Gift', 'church_mgt'); ?></a>  
								</li>
							<?php 
							}
							else
							{?>
								<li class="<?php if($active_tab=='sellgift'){?>active<?php }?>">
									<a href="?page=cmgt-gifts&tab=sellgift" class="padding_left_0 tab <?php echo $active_tab == 'sellgift' ? 'nav-tab-active' : ''; ?>">
									<?php _e('Add Sell Gift', 'church_mgt'); ?></a>
							<?php  }
							if($active_tab == 'view_sellgift')
							{
								?>
								<li class="<?php if($active_tab=='view_sellgift'){?>active<?php }?>">
								<a href="?page=cmgt-gifts&tab=sellgift" class="padding_left_0 tab <?php echo $active_tab == 'view_sellgift' ? 'nav-tab-active' : ''; ?>">
								<?php _e('View Invoice', 'church_mgt'); ?></a>
								<?php
							}
							?>
						</ul>
						 <?php 
						if($active_tab == 'giftlist')
						{ 
							$giftdata=$obj_gift->MJ_cmgt_get_all_gifts();
							if(!empty($giftdata))
							{
								?>	
								<script type="text/javascript">
									$(document).ready(function() {
										jQuery('#group_list').DataTable({
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
								<form name="wcwm_report" action="" method="post">
									<div class="panel-body">
										<div class="cmgt_gift_table_responsive table-responsive">
											<table id="group_list" class="display" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Gift Name', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Gift Price', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Gift Type', 'church_mgt' ) ;?></th>
														<th class=""><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Gift Name', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Gift Price', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Gift Type', 'church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
													<?php 
													if(!empty($giftdata))
													{
														foreach ($giftdata as $retrieved_data)
														{
															?>
															<tr>
																<td class="cmgt-checkbox_width_10px"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo esc_attr($retrieved_data->id); ?>"></td>
																	
																<td class="user_image width_50px profile_image_prescription padding_left_0">
																	<?php
																	if($retrieved_data->gift_type == "image")
																	{
																		?>
																		<img style="" src="<?php echo esc_attr($retrieved_data->media_gift);?>" height="50px" width="50px" class="img-circle" />
																		<?php
																	}else{
																		echo '<img src='.esc_url(get_option( 'cmgt_gift_logo' )).' height="50px" width="50px" class="img-circle" />';
																	}
																	?>
																</td>

																<td class="giftname width_25_per">
																	<a class="color_black" href="?page=cmgt-gifts&tab=view-gift&action=view-gift&gift_id=<?php echo esc_attr($retrieved_data->id);?>">
																		<?php echo ucfirst($retrieved_data->gift_name);?>
																	</a> 
																</td>
																
																<td class="giftprice width_15_per">
																	<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php if($retrieved_data->gift_price!='') echo esc_attr($retrieved_data->gift_price); else echo "Free";?> 
																</td>
																<td class="giftprice width_10_per"><?php echo _e($retrieved_data->gift_type,'church_mgt');?> </td>
															
																	<td class="action cmgt_pr_0px">
																		<div class="cmgt-user-dropdown action_menu mt-2">
																			<ul class="">
																				<!-- BEGIN USER LOGIN DROPDOWN -->
																				<li class="">
																					<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																						<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																					</a>
																					<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">

																						<li>
																							<a href="?page=cmgt-gifts&tab=view-gift&action=view-gift&gift_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-eye"></i><?php _e('View','church_mgt');?></a>
																						</li>

																						<li>
																							<a href="#" class="dropdown-item give_gift" id="<?php echo esc_attr($retrieved_data->id);?>"><i class="fa fa-gift fa-lg" ></i><?php _e('Give Gift', 'church_mgt' );?></a>
																						</li>
																						<?php
																						if ($user_access_edit == 1) 
																						{ 
																					?>
																						<li>
																							<a href="?page=cmgt-gifts&tab=addgift&action=edit&gift_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a>
																						</li>
																						<?php
																						}
																					?>
																						<div class="cmgt-dropdown-deletelist">
																						<?php
																						if ($user_access_delete == 1) 
																						{ 
																					?>
																							<li><a href="?page=cmgt-gifts&tab=giftlist&action=delete&gift_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?> </a></li>
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
														} 
													}
													?>
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
												<button data-toggle="tooltip" title="<?php esc_html_e('Delete All','church_mgt');?>" name="delete_selected" class="delete_all_button"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Delete.png" ?>" alt=""></button>
												<?php
												}
												?>
											</div>
										</div>
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
									<a href="<?php echo admin_url().'admin.php?page=cmgt-gifts&tab=addgift';?>">
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
						}
						if($active_tab == 'view_sellgift')
						{	
							$invoice_type=$_REQUEST['invoice_type'];
							$invoice_id=$_REQUEST['idtest'];
							MJ_cmgt_view_invoice_page($invoice_type,$invoice_id);
						}
						if($active_tab == 'addgift')
						{
							require_once CMS_PLUGIN_DIR. '/admin/spiritual-gift/add_gift.php';
						}
						if($active_tab == 'view-gift')
						{
							require_once CMS_PLUGIN_DIR. '/admin/spiritual-gift/view_gift.php';
						}
						 if($active_tab == 'sellgiftlist')
						{
							require_once CMS_PLUGIN_DIR. '/admin/spiritual-gift/sell_gift_list.php';
						}
						 if($active_tab == 'sellgift')
						{
							require_once CMS_PLUGIN_DIR. '/admin/spiritual-gift/sell_gift.php';
						}
						 ?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->
<?php ?>