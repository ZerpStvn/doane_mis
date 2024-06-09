<?php 
MJ_cmgt_header();
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'sermonlist');
$obj_sermon=new Cmgtsermon;
?>
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
	if(isset($_POST['save_sermon']))
	{
		$nonce = sanitize_text_field($_POST['_wpnonce']);
		if (wp_verify_nonce( $nonce, 'save_sermon_nonce' ) )
		{
		//-------- EDIT SERMON --------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_sermon->MJ_cmgt_add_sermon($_POST,$_POST['cmgt_sermon']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-sermon&tab=sermonlist&message=2');
			}	
		}
		else
		{
			//-------- ADD SERMON --------//
			$result=$obj_sermon->MJ_cmgt_add_sermon($_POST,$_POST['cmgt_sermon']);
			echo $result;
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-sermon&tab=sermonlist&message=1');
			}
		}
	}
	}
		//-------- DELETE SERMON --------//
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
			
		$result=$obj_sermon->MJ_cmgt_delete_sermon($_REQUEST['sermon_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-sermon&tab=sermonlist&message=3');
		}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['selected_id']))
		{
			
			foreach($_REQUEST['selected_id'] as $_REQUEST['sermon_id'])
			{
				$result=$obj_sermon->MJ_cmgt_delete_sermon($_REQUEST['sermon_id']);
				wp_redirect ( admin_url().'admin.php?page=cmgt-sermon&tab=sermonlist&message=3');
			}
		}
		else
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-sermon&tab=sermonlist&message=4');
		}
	}
	?>
	<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('sermon-list');
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
				if ('sermon-list' == $user_access['page_link'] && $_REQUEST['action'] == 'edit') 
				{
					if ($user_access_edit == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('sermon-list' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('sermon-list' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
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
									?></p>
									</div>
								<?php 
							}
							elseif($message == 2)
							{?>
								<div id="message" class="updated below-h2 notice is-dismissible "><p><?php
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
									?>
								</div></p><?php
								
							}
							elseif($message == 4) 
							{?>
								<div id="message" class="updated below-h2 notice is-dismissible "><p>
								<?php 
									_e('Please select at least one record.','church_mgt');
								?></div></p><?php	
							}
						}
					?>
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<!-- <h2 class="nav-tab-wrapper"> 
							<a href="?page=cmgt-sermon&tab=sermonlist" class="nav-tab <?php echo $active_tab == 'sermonlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Sermon List', 'church_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
								<a href="?page=cmgt-sermon&tab=addsermon&&action=edit&sermon_id=<?php echo esc_attr($_REQUEST['sermon_id']);?>" class="nav-tab <?php echo $active_tab == 'addsermon' ? 'nav-tab-active' : ''; ?>">
								<?php _e('Edit Sermon', 'church_mgt'); ?></a>  
								<?php 
							}
							else
							{?>
								<a href="?page=cmgt-sermon&tab=addsermon" class="nav-tab <?php echo $active_tab == 'addsermon' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Sermon', 'church_mgt'); ?></a>
								
							<?php 
							}
							if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view-sermon')
							{?>
								<a href="?page=cmgt-sermon&tab=view-sermon&action=view-sermon&sermon_id=<?php echo esc_attr($_REQUEST['sermon_id']);?>" class="nav-tab <?php echo $active_tab == 'view-sermon' ? 'nav-tab-active' : ''; ?>">
								<?php _e('View Sermon', 'church_mgt'); ?></a>  
								<?php 
							}	?>
						</h2> -->
						 <?php 
						if($active_tab == 'sermonlist')
						{ 
							$sermondata=$obj_sermon->MJ_cmgt_get_all_sermons();
							if(!empty($sermondata))
							{
								?>
								<script type="text/javascript">
									$(document).ready(function() {
									jQuery('#sermon_list').DataTable({
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
									<div class="panel-body"><!-- PANEL BODY DIV START-->
										<div class="table-responsive">
											<table id="sermon_list" class="display" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Sermon Title', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Sermon Type', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Status', 'church_mgt' ) ;?></th>
														<th class=""><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Sermon Title', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Sermon Type', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Status', 'church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
													<?php 
														$i=0;
														if(!empty($sermondata))
														{
															foreach ($sermondata as $retrieved_data)
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
																		<p class="padding_15px prescription_tag <?php echo $color_class; ?>">	
																			<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Sermon-white.png"?>" alt="" class="massage_image center">
																		</p>
																	</td>

																	<td class="name width_25_per"><a class="color_black" href="?page=cmgt-sermon&tab=view-sermon&action=view-sermon&sermon_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_attr(ucfirst($retrieved_data->sermon_title));?></a></td>

																	<td class="media_type width_15_per"><?php echo MJ_cmgt_get_media_type(esc_attr($retrieved_data->sermon_type));?> </td>

																	<td class="Status width_15_per"><?php if($retrieved_data->status=='publish'){ 
																		echo '<span class="green_color">'.esc_html__("Publish",'church_mgt').'</span>';
																		}
																		else
																		{ 
																			echo '<span class="red_color">'.esc_html__("Draft",'church_mgt').'</span>'; 
																		}?> 
																	</td>
																	
																	<td class="action cmgt_pr_0px">
																		<div class="cmgt-user-dropdown action_menu mt-2">
																			<ul class="">
																				<!-- BEGIN USER LOGIN DROPDOWN -->
																				<li class="">
																					<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																						<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																					</a>
																					<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">

																						<li><a  href="?page=cmgt-sermon&tab=view-sermon&action=view-sermon&sermon_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-eye"></i><?php _e('View','church_mgt');?></a></li>
																						<?php
																						if ($user_access_edit == 1) 
																						{ 
																					?>
																						<li><a href="?page=cmgt-sermon&tab=addsermon&action=edit&sermon_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																						<?php
																						}
																					?>
																						<div class="cmgt-dropdown-deletelist">
																						<?php
																						if ($user_access_delete == 1) 
																						{ 
																					?>
																							<li><a href="?page=cmgt-sermon&tab=sermonlist&action=delete&sermon_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?> </a></li>
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
									<a href="<?php echo admin_url().'admin.php?page=cmgt-sermon&tab=addsermon';?>">
										<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
									</div> 
								</div>		
								<?php
							}
						}
						if($active_tab == 'addsermon')
						{
						require_once CMS_PLUGIN_DIR. '/admin/sermon/add_sermon.php';
						}
						if($active_tab == 'view-sermon')
						{
						require_once CMS_PLUGIN_DIR. '/admin/sermon/view_sermon.php';
						}
						 ?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->
<?php ?>