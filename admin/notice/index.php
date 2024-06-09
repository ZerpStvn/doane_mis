<?php 
MJ_cmgt_header();
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'noticelist');
$obj_notice=new Cmgtnotice;
?>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"> </div>		 
		</div>
    </div>     
</div>
<script type="text/javascript">
$(document).ready(function()
{
	//------------ CLOSE MESSAGE ---------//
	$('.notice-dismiss').click(function() {
		$('#message').hide();
	}); 
}); 
</script>
<!-- End POP-UP Code -->
<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('notice');
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
				if ('notice' == $user_access['page_link'] && $_REQUEST['action'] == 'edit') 
				{
					if ($user_access_edit == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('notice' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('notice' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
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
<div class="page-inner"><!--PAGE INNER DIV STRAT-->
	<?php 
		if(isset($_POST['save_notice']))
		{		
			$nonce = sanitize_text_field($_POST['_wpnonce']);
			if (wp_verify_nonce( $nonce, 'save_notice_nonce' ) )
			{
			//---------- EDIT NOTICE ----------------//
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{		
				$start_date=MJ_cmgt_get_format_for_db($_POST['start_date']);
				$end_date=MJ_cmgt_get_format_for_db($_POST['end_date']);
				if($start_date > $end_date)
				{
					$time_validation='1';
				}
				else
				{
					$time_validation='0';
					$result=$obj_notice->MJ_cmgt_add_notice($_POST);		
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=cmgt-notice&tab=noticelist&message=2');
					}
				}
			}
			else
			{		//---------- ADD NOTICE ----------------//		
				$result=$obj_notice->MJ_cmgt_add_notice($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-notice&tab=noticelist&message=1');
				}
			}
			if($time_validation=='1')
			{
			?>
				<div id="message" class="updated below-h2 ">
					<p>
						<?php 
							_e('End Date should be greater than Start Date.','church_mgt');
						?>
					</p>
				</div>
		   <?php 
			}
		 }
		}
		if(isset($_REQUEST['action']) && $_REQUEST['action']=="approve")
		{		
			$result=$obj_notice->MJ_cmgt_approve_notice($_REQUEST['id']);
			 $noticedata=$obj_notice->MJ_cmgt_get_single_notice($_REQUEST['id']);
			 $get_members = array('role' => 'member');
				$membersdata=get_users($get_members);
			if(!empty($membersdata))
			{
				foreach ($membersdata as $retrieved_data){
				$curent_user_data=get_userdata($noticedata->created_by);
				$curent_user_name=$curent_user_data->display_name;
				$to=$retrieved_data->user_email;
				$user_name=$retrieved_data->display_name;
				$subject =get_option('WPChurch_add_notice_subject');
				$page_link=home_url().'/?church-dashboard=user&page=notice&tab=noticelist';
				$churchname=get_option('cmgt_system_name');
				$message_content=get_option('WPChurch_add_notice_email_template');
				$subject_search=array('[GMGT_MEMBERNAME]','[CMGT_CHURCH_NAME]');
				$subject_replace=array($user_name,$churchname);
				$search=array('[GMGT_MEMBERNAME]','[GMGT_USERNAME]','[CMGT_NOTICE_TITLE]','[CMGT_NOTICE_START_DATE]','[CMGT_NOTICE_END_DATE]','[CMGT_NOTICE_CONTENT]','[CMGT_NOTICE_PAGE_LINK]','[CMGT_CHURCH_NAME]');
				$subject=str_replace($subject_search,$subject_replace,$subject);
				
				$notice_start_date=date(MJ_cmgt_date_formate(),strtotime($noticedata->start_date));
			    $notice_end_date=date(MJ_cmgt_date_formate(),strtotime($noticedata->end_date));
				$replace = array($user_name,$curent_user_name,$noticedata->notice_title,$notice_start_date,$notice_end_date,$noticedata->notice_content,$page_link,$churchname);
				$message_content = str_replace($search, $replace, $message_content);
				MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
				}
			}
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-notice&tab=noticelist&message=4');
			}
		}
		//---------- DELETE NOTICE ----------------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
			{
				$result=$obj_notice->MJ_cmgt_delete_notice($_REQUEST['id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-notice&tab=noticelist&message=3');
				}
			}
		if(isset($_REQUEST['delete_selected']))
			{		
				if(!empty($_REQUEST['selected_id']))
				{
					foreach($_REQUEST['selected_id'] as $_REQUEST['id'])
					{
						$result=$obj_notice->MJ_cmgt_delete_notice($_REQUEST['id']);
						wp_redirect ( admin_url().'admin.php?page=cmgt-notice&tab=noticelist&message=3');
					}
				}
				else
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-notice&tab=noticelist&message=5');
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
									_e('Notice Approved','church_mgt');
								?></div></p><?php
							}
							elseif($message == 5) 
							{?>
							<div id="message" class="updated below-h2 notice is-dismissible "><p>
								<?php 
									_e('Please select at least one record.','church_mgt');
								?>
							</div></p><?php	
							}
						}
					?>
					<div class="panel-body"><!--PANEL BODY DIV STRAT-->
						<!-- <h2 class="nav-tab-wrapper">
							<a href="?page=cmgt-notice&tab=noticelist" class="nav-tab <?php echo $active_tab == 'noticelist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Notice List', 'church_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=cmgt-notice&tab=addnotice&action=edit&id=<?php echo esc_attr($_REQUEST['id']);?>" class="nav-tab <?php echo $active_tab == 'addnotice' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Notice', 'church_mgt'); ?></a>  
							<?php 
							}
							else 
							{?>
							<a href="?page=cmgt-notice&tab=addnotice" class="nav-tab <?php echo $active_tab == 'addnotice' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Notice', 'church_mgt'); ?></a>
							<?php  }?>
						</h2> -->
							 <?php 
							//Report 1 
							if($active_tab == 'noticelist')
							{ 
								$noticedata=$obj_notice->MJ_cmgt_get_all_notice();			
								if(!empty($noticedata))
								{			
									?>	
									<script type="text/javascript">
										$(document).ready(function() {
										jQuery('#notice_list').DataTable({
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
									<form name="activity_form" action="" method="post">
										<div class="panel-body"><!--PANEL BODY DIV START-->
											<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
												<table id="notice_list" class="display" cellspacing="0" width="100%">
													<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
														<tr>
															<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
															<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
															<th><?php _e( 'Notice Title', 'church_mgt' ) ;?></th>
															<th><?php _e( 'Notice By', 'church_mgt' ) ;?></th>
															<th> <?php _e( 'Notice Start Date To End Date', 'church_mgt' ) ;?></th>
															<th><?php _e( 'Notice Comment', 'church_mgt' ) ;?></th>
															<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
														</tr>
													</thead>
													<tfoot>
														<tr>
															<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
															<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
															<th><?php _e( 'Notice Title', 'church_mgt' ) ;?></th>
															<th><?php _e( 'Notice By', 'church_mgt' ) ;?></th>
															<th> <?php _e( 'Notice Start Date To End Date', 'church_mgt' ) ;?></th>
															<th><?php _e( 'Notice Comment', 'church_mgt' ) ;?></th>
															<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
														</tr>
													</tfoot>	
													<tbody>
														<?php 
														$i=0;			
														if(!empty($noticedata))
														{			
															foreach ($noticedata as $retrieved_data)
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
																				<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/notice.png"?>" alt="" class="massage_image center">
																			</p>
																		</td>
																		
																		<td class="name width_25_per"><a class="color_black view_notice" id="<?php echo $retrieved_data->id ?>" href="#"><?php echo esc_attr($retrieved_data->notice_title);?></a> </td>
																		
																		<td class="notice_by width_15_per"><?php print  MJ_cmgt_church_get_display_name(esc_attr($retrieved_data->created_by));?> </td>

																		<td class="date width_22_per"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->start_date)));?> <?php _e('To','church_mgt');?> <?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->end_date)));?> </td>
																		<td class="notice_comment">
																			<?php
																			if(!empty($retrieved_data->notice_content)) 
																			{
																				$notice_comment = strlen($retrieved_data->notice_content) > 35 ? substr($retrieved_data->notice_content,0,35)."..." : $retrieved_data->notice_content;
																				echo $notice_comment;
																			}
																			else
																			{
																				echo 'N/A';
																			} 
																			?>
																			
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

																							<?php if( $retrieved_data->status == "0")
																							{ 	?>
																								<li><a href="?page=cmgt-notice&tab=noticelist&action=approve&id=<?php echo esc_attr($retrieved_data->id); ?>" class="dropdown-item"><i class="fa fa-thumbs-up" aria-hidden="true"></i><?php _e('Approve', 'church_mgt' ) ;?></a></li>
																								<?php 
																							}	?>
																							<li><a class="dropdown-item view_notice" id="<?php echo $retrieved_data->id ?>" href="#"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																							<?php
																						if ($user_access_edit == 1) 
																						{ 
																					?>
																							<li><a href="?page=cmgt-notice&tab=addnotice&action=edit&id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																							<?php
																						}
																						?>
																							<div class="cmgt-dropdown-deletelist">
																							<?php
																						if ($user_access_delete == 1) 
																						{ 
																					?>
																								<li><a href="?page=cmgt-notice&tab=noticelist&action=delete&id=<?php echo esc_attr($retrieved_data->id) ;?>" class="dropdown-item " 
																								onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e('Delete','church_mgt'); ?></a></li>
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
														<input type="checkbox" name="selected_id[]" class="select_all" value="<?php echo esc_attr($retrieved_data->id); ?>" style="margin-top: 0px;">
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
											</div><!--TABLE RESPONSIVE DIV END-->
										</div><!--PANEL BODY DIV END-->
									</form><!-- FORM END-->
							 		<?php 
								}
								else
								{
									?>
									<div class="no_data_list_div"> 
										<a href="<?php echo admin_url().'admin.php?page=cmgt-notice&tab=addnotice';?>">
											<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
										</a>
										<div class="col-md-12 dashboard_btn margin_top_20px">
											<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
										</div> 
									</div>		
									<?php
								}
							}
							
							if($active_tab == 'addnotice')
							{
								require_once CMS_PLUGIN_DIR.'/admin/notice/add-notice.php';
							}
							 ?>
					</div><!--PANEL BODY DIV END-->
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!--ROW DIV END-->
    </div><!--MAIN WRAPPER DIV END-->
</div><!--page inner DIV end-->