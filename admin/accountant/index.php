<?php 
MJ_cmgt_header();
$obj_user=new Cmgtmember;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'accountantlist');
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

<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('accountant');
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
				if ('accountant' == $user_access['page_link'] && $_REQUEST['action'] == 'edit') 
				{
					if ($user_access_edit == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('accountant' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('accountant' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
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

<div class="page-inner" style=""><!-- PAGE INNNER DIV START-->
	<?php 
	if(isset($_POST['save_staff']))
	{
		$nonce = sanitize_text_field($_POST['_wpnonce']);
		
		if (wp_verify_nonce( $nonce, 'save_staff_nonce' ) )
		{
		//---------- EDIT STAFF MEMBER ------------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
	        $txturl= sanitize_text_field($_POST['cmgt_user_avatar']);
			$ext=MJ_cmgt_check_valid_extension($txturl);
			if(!$ext == 0)
			{
				$result=$obj_user->MJ_cmgt_add_user($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php? page=cmgt-accountant&tab=accountantlist&message=2');
				}
			}
			else
				{
					wp_redirect ( admin_url() . 'admin.php? page=cmgt-accountant&tab=add_accountant&message=4');
				}		
		}
		else
		{
			//---------- ADD STAFF MEMBER ------------//
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) 
			{
			    $txturl=$_POST['cmgt_user_avatar'];
			    $ext=MJ_cmgt_check_valid_extension($txturl);
			    if(!$ext == 0)
				{
					
				    $result=$obj_user->MJ_cmgt_add_user($_POST);
					if($result)
					{
						wp_redirect ( admin_url() . 'admin.php? page=cmgt-accountant&tab=accountantlist&message=1');
					}
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php? page=cmgt-accountant&tab=add_accountant&message=4');
					
				}
			}
			else
			{?>
				<div id="message" class="updated below-h2 notice is-dismissible ">
				<p><p><?php _e('Username Or Emailid All Ready Exist.','church_mgt');?></p></p>
				</div>
	  <?php }
		}
		}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$obj_user->MJ_cmgt_delete_usedata($_REQUEST['accountant_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php? page=cmgt-accountant&tab=accountantlist&message=3');
		}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['selected_id']))
		{
			
			foreach($_REQUEST['selected_id'] as $_REQUEST['accountant_id'])
			{
				$result=$obj_user->MJ_cmgt_delete_usedata($_REQUEST['accountant_id']);
			}
		}
		else
		{
			wp_redirect ( admin_url() . 'admin.php? page=cmgt-accountant&tab=accountantlist&message=5');
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
										?></p></div>
										<?php 
									
								}
								elseif($message == 2)
								{?><div id="message" class="updated below-h2 notice is-dismissible "><p><?php
											_e("Record updated successfully",'church_mgt');
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
									_e('Only image formats are allow.','church_mgt');
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
						<div class="panel-body av_tab_responsive_4_tab"><!-- PANEL BODY DIV START-->
								<?php 
							//Report 1 
							if($active_tab == 'accountantlist')
							{ 
								$get_staff = array('role' => 'accountant');
								$staffdata=get_users($get_staff);
								if(!empty($staffdata))
								{
								?>	
									<script type="text/javascript">
										$(document).ready(function() {
										jQuery('#staff_list').DataTable({
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
									<form name="wcwm_report" action="" method="post"><!-- Accountant LIST FORM START-->
										<div class="panel-body"><!-- PANEL BODY DIV START-->
											<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
												<table id="staff_list" class="display" cellspacing="0" width="100%"><!-- TABLE Accountant START-->
													<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
														<tr>
															<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
															<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
															<th><?php _e( 'Accountant Name', 'church_mgt' ) ;?></th>
															<th> <?php _e( 'Accountant Email', 'church_mgt' ) ;?></th>
															<th> <?php _e( 'Gender', 'church_mgt' ) ;?></th>
															<th> <?php _e( 'Mobile No.', 'church_mgt' ) ;?></th>
															<th class="dob_text_transform"> <?php _e( 'Date of Birth', 'church_mgt' ) ;?></th>
															<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
														</tr>
													</thead>
													<tfoot>
														<tr>
															<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
															<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
															<th><?php _e( 'Accountant Name', 'church_mgt' ) ;?></th>
															<th> <?php _e( 'Accountant Email', 'church_mgt' ) ;?></th>
															<th> <?php _e( 'Gender', 'church_mgt' ) ;?></th>
															<th> <?php _e( 'Mobile No.', 'church_mgt' ) ;?></th>
															<th class="dob_text_transform"> <?php _e( 'Date of Birth', 'church_mgt' ) ;?></th>
															<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
														</tr>
													</tfoot>
													<tbody>
													<?php 
													if(!empty($staffdata))
													{
														foreach ($staffdata as $retrieved_data)
														{													?>
															<tr>
																<td class="cmgt-datatbl-checkbox cmgt-checkbox_width_10px">
																	<input data-mdb-row-index="0" class="sub_chk datatable-row-checkbox form-check-input" type="checkbox"  name="selected_id[]" value="<?php echo esc_attr($retrieved_data->ID); ?>">
																</td> 
																<td class="user_image cmgt-checkbox_width_50px padding_left_0"><?php $uid=$retrieved_data->ID;
																	$userimage=get_user_meta($uid, 'cmgt_user_avatar', true);
																	if(empty($userimage))
																	{
																		echo '<img src='.esc_url(get_option( 'cmgt_accountant_logo' )).' height="50px" width="50px" class="img-circle" />';
																	}
																	else
																	{
																		echo '<img src='.esc_url($userimage).' height="50px" width="50px" class="img-circle"/>';
																	}
																?>
																</td>
																<td class="name"><a class="color_black" href="?page=cmgt-accountant&tab=viewaccountant&action=view&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>"><?php echo esc_attr($retrieved_data->display_name);?></a></td>
																<td class="email"><?php echo esc_attr($retrieved_data->user_email);?> </td>
																<td class="gender width_10_per">
																	<?php echo _e(ucfirst($retrieved_data->gender) , 'church_mgt');?> 
																</td>
																<td class="mobile width_15_per">
																	<?php echo esc_attr($retrieved_data->phonecode).' '.esc_attr($retrieved_data->mobile);?> 
																</td>
																<td class="birth_date width_15_per"><label><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->birth_date)));?> </label></td>
									
																<td class="action cmgt_pr_0px"> 
																<div class="cmgt-user-dropdown">
																	<ul class="">
																		<!-- BEGIN USER LOGIN DROPDOWN -->
																		<li class="">
																			<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																				<img class="more_img_mr" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>">
																			</a>
																			<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																				<li><a class="dropdown-item" href="?page=cmgt-accountant&tab=viewaccountant&action=view&accountant_id=<?php echo $retrieved_data->ID;?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																				<?php
																						if ($user_access_edit == 1) 
																						{ 
																					?>
																				<li><a class="dropdown-item" href="?page=cmgt-accountant&tab=add_accountant&action=edit&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																				<?php
																						}
																					?>
																				<div class="cmgt-dropdown-deletelist">
																				<?php
																						if ($user_access_delete == 1) 
																						{ 
																					?>
																					<li><a class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"href="? page=cmgt-accountant&tab=accountantlist&action=delete&accountant_id=<?php echo $retrieved_data->ID;?>"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?></a></li>
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
													}?>
													</tbody>
												</table><!-- Accountant TABEL END-->
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
											</div><!-- TABLE RESPONSIVE DIV END-->
										</div><!-- PANEL BODY DIV END-->
									</form><!-- AccountantL LIST FORM END-->
									<?php 
								}
								else
								{
									?>
									<div class="no_data_list_div"> 
										<a href="<?php echo admin_url().'admin.php?page=cmgt-accountant&tab=add_accountant';?>">
											<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
										</a>
										<div class="col-md-12 dashboard_btn margin_top_20px">
											<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
										</div> 
									</div>		
									<?php
								}
							}
							if($active_tab == 'add_accountant')
							{
								require_once CMS_PLUGIN_DIR. '/admin/accountant/add_accountant.php';
							}
							if($active_tab == 'viewaccountant')
							{
								require_once CMS_PLUGIN_DIR.'/admin/accountant/view_accountant.php';
							}
							?>
						</div><!-- PANEL BODY DIV END-->
						</div><!-- PANEL WHITE DIV END-->
					</div><!-- COL 12 DIV END-->
				</div><!-- ROW DIV END-->
			</div><!-- MAIN WRAPPER DIV END-->
		</div><!-- PAGE INNER DIV END-->
		