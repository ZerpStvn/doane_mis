<?php 
MJ_cmgt_header();
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'pastoral_list');
$obj_pastoral=new Cmgtpastoral;
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
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="cmgt_overlay_popup_top overlay-content">
		<div class="modal-content">
			<div class="category_list pastoral_page">
			</div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('pastoral');
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
				if ('pastoral' == $user_access['page_link'] && $_REQUEST['action'] == 'edit') 
				{
					if ($user_access_edit == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('pastoral' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('pastoral' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
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
<?php 
	if(isset($_POST['save_pastoral']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_pastoral_nonce' ) )
		{
			//-------- EDIT POSTORAL ------//
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_pastoral->MJ_cmgt_add_pastoral($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-pastoral&tab=pastoral_list&message=2');
				}
			}
			else
			{
				//-------- ADD POSTORAL ------//
				$result=$obj_pastoral->MJ_cmgt_add_pastoral($_POST);
				echo $result;
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-pastoral&tab=pastoral_list&message=1');
				}
			}
		}
	}
	  //-------- DELETE POSTORAL ------//
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_pastoral->MJ_cmgt_delete_pastoral($_REQUEST['pastoral_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-pastoral&tab=pastoral_list&message=3');
		}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $_REQUEST['pastoral_id'])
			{
				$result=$obj_pastoral->MJ_cmgt_delete_pastoral($_REQUEST['pastoral_id']);
				wp_redirect ( admin_url().'admin.php?page=cmgt-pastoral&tab=pastoral_list&message=3');
			}
		}
		else
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-pastoral&tab=pastoral_list&message=4');
		}
	}
	?>
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
								?>
							</div></p><?php	
							}
						}
					?>
					<div class="panel-body"> <!-- PANEL BODY DIV START-->
						<?php 
						if($active_tab == 'pastoral_list')
						{ 
							$pastoraldata=$obj_pastoral->MJ_cmgt_get_all_pastoral();
							if(!empty($pastoraldata))
							{
								?>	
								<script type="text/javascript">
									$(document).ready(function() 
									{
										jQuery('#pastoral_list').DataTable({
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
									<div class="panel-body"> <!-- PANEL BODY DIV START-->
										<div class="table-responsive">
											<table id="pastoral_list" class="display" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Pastoral Title', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Pastoral Date', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Pastoral Time', 'church_mgt' ) ;?></th>
														<th class=""><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Pastoral Title', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Pastoral Date', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Pastoral Time', 'church_mgt' ) ;?></th>
														<th class=""><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
													<?php 
													$i=0;
													if(!empty($pastoraldata))
													{
														foreach ($pastoraldata as $retrieved_data)
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
																		<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pledges-white.png"?>" alt="" class="massage_image center">
																	</p>
																</td>
																<td class="pastoraltitle width_20_per"><a class="color_black view_pastoral"  id="<?php echo $retrieved_data->id?>" href="#"><?php echo esc_attr($retrieved_data->pastoral_title);?></a></td>

																<td class="Member width_20_per"><?php $user=get_userdata($retrieved_data->member_id); echo esc_attr($user->display_name);?> </td>

																<td class="pastoral width_15_per"><?php  echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->pastoral_date));?> </td>

																<td class="pastoral-time width_10_per">
																	<?php
																	if(empty($retrieved_data->pastoral_time))
																	{
																		echo 'N/A';
																	}
																	else
																	{
																		 echo esc_attr($retrieved_data->pastoral_time);
																	}
																	?>
																	
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
																					<li><a class="dropdown-item view_pastoral" id="<?php echo $retrieved_data->id ?>"  href="#"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																					<?php
																						if ($user_access_edit == 1) 
																						{ 
																					?>
																					<li><a class="dropdown-item" href="?page=cmgt-pastoral&tab=add_pastoral&action=edit&pastoral_id=<?php echo $retrieved_data->id?>"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																					<?php
																						}
																					?>
																					<div class="cmgt-dropdown-deletelist">
																					<?php
																						if ($user_access_delete == 1) 
																						{ 
																					?>
																						<li><a class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');" href="?page=cmgt-pastoral&tab=pastoral_list&action=delete&pastoral_id=<?php echo $retrieved_data->id;?>"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?></a></li>
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
													}?>
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
									</div>
								</form>
								 <?php 
							}
							else
							{
								?>
								<div class="no_data_list_div"> 
									<a href="<?php echo admin_url().'admin.php?page=cmgt-pastoral&tab=add_pastoral';?>">
										<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
									</div> 
								</div>		
								<?php
							}
						}
						if($active_tab == 'add_pastoral')
						{
							require_once CMS_PLUGIN_DIR. '/admin/pastoral/add_pastoral.php';
						}
						
						if($active_tab == 'view-pastoral')
						{
							require_once CMS_PLUGIN_DIR. '/admin/pastoral/view_sermon.php';
						}
						 ?>
 					</div> <!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->
<?php ?>