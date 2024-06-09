<?php
	MJ_cmgt_header();
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
	$user_access = MJ_cmgt_add_check_access_for_view('family');
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
				if ('document' == $user_access['page_link'] && $_REQUEST['action'] == 'edit') 
				{
					if ($user_access_edit == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('document' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('document' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
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
	// This is Dashboard at admin side!!!!!!!!! 
	$role='family_member';
	 $active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'familylist'); 
	// This is Dashboard at admin side!!!!!!!!! 
	if(isset($_POST['save_family_member']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_family_member_nonce' ) )
		{	
			$firstname=sanitize_text_field($_POST['first_name']);
			$lastname=sanitize_text_field($_POST['last_name']);

			$cmgt_family_without_email_pass = get_option('cmgt_family_without_email_pass');

			if($cmgt_family_without_email_pass == 'yes')
			{
				$memberdata = get_userdata($_POST['member_id']);
				$member_email = explode('@',$memberdata->user_email);
				$family_email = $member_email[0].'+'.$_POST['first_name'].'@'.$member_email[1];
				$family_password = rand();
	
				$userdata = array(
				'user_login'=>$_POST['mobile_number'],			
				'user_nicename'=>NULL,
				'user_pass'=>$family_password,
				'user_email'=>$family_email,
				'user_url'=>NULL,
				'display_name'=>$firstname." ".$lastname,
				);
			}else{
				$userdata = array(
					'user_login'=>$_POST['username'],			
					'user_nicename'=>NULL,
					'user_email'=>$_POST['email'],
					'user_url'=>NULL,
					'display_name'=>$firstname." ".$lastname,
					);
				if($_POST['password'] != "")
				$userdata['user_pass']=$_POST['password'];
			}
			
			if(isset($_POST['cmgt_user_avatar']) && $_POST['cmgt_user_avatar'] != "")
			{
				$photo=$_POST['cmgt_user_avatar'];
			}
			else
			{
				$photo="";
			}
			
				$usermetadata=array(
					'middle_name'=>$_POST['middle_name'],
					'gender'=>$_POST['gender'],
					'birth_date'=>$_POST['birth_date'],
					'address'=>$_POST['address'],
					'city'=>$_POST['city_name'],
					'state'=>$_POST['state_name'],
					'zip_code'=>$_POST['zip_code'],
					'phone'=>$_POST['phone'],
					'mobile_number'=>$_POST['mobile_number'],
					'phonecode'=>$_POST['phonecode'],
					'relation'=>$_POST['relation'],
					'member_id'=>$_POST['member_id'],
					'cmgt_user_avatar'=>$photo,
				);
			if($_REQUEST['action']=='edit')
			{
				$txturl=$usermetadata['cmgt_user_avatar'];
				$ext=MJ_cmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{
					$userdata['ID']=$_REQUEST['family_id'];
					$result=MJ_cmgt_update_user($userdata,$usermetadata,$firstname,$lastname,$role);
					if($result)
			      	{
				     	wp_redirect ( admin_url() . 'admin.php? page=cmgt-family&tab=familylist&message=2');
			      	}
				}
				else
				{
					?>
						<div id="message" class="updated below-h2"><p>
							<?php 
								// _e('Only jpg jpeg png and gif files are allowed!.','church_mgt');
								wp_redirect ( admin_url() . 'admin.php?page=cmgt-family&tab=familylist&message=5');
							?> 
							
						</div></p> 
					<?php 
				}
				
			}
			else
			{
				if( !email_exists( $family_email )) 
				{
					
					$txturl=$usermetadata['cmgt_user_avatar'];
					$ext=MJ_cmgt_check_valid_extension($txturl);
					if(!$ext == 0)
			      	{	
						$result=MJ_cmgt_add_newuser($userdata,$usermetadata,$firstname,$lastname,$role);
				
				 		if($result)
						{
							wp_redirect ( admin_url() . 'admin.php? page=cmgt-family&tab=familylist&message=1');
						}
				  	}
				  	else
				  	{
					   	?> 
					   		<div id="message" class="updated below-h2 notice is-dismissible "><p>
								<?php _e('Only jpeg ,jpg ,png and gif files are allowed!.','church_mgt');?> 
							</div></p> 
				 		<?php  
					}
				}
				else
				{
						?>
						<div id="message" class="updated below-h2 notice is-dismissible ">
							<p><?php _e('Username Or Emailid All Ready Exist.','church_mgt');?></p>
						</div>
						<?php 
				}
			}
		}
	}
	?>

<?php 
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			$member=get_user_meta($_REQUEST['family_id'], 'member', true);
			
			if(!empty($member))
			{
				foreach($member as $memmbervalue)
				{
					$family=get_user_meta($memmbervalue, 'family_id', true);
					if(!empty($family))
					{
						if(($key = array_search($_REQUEST['family_id'], $family)) !== false) 
						{
						
							unset($family[$key]);
						
							update_user_meta( $memmbervalue,'family_id', $family );
						
						}
					}
				}
			}
			$result=MJ_cmgt_delete_usedata($_REQUEST['family_id']);	
		  	if($result)
			      {
				     wp_redirect ( admin_url() . 'admin.php? page=cmgt-family&tab=familylist&message=3');
					// wp_redirect ( admin_url() . 'admin.php? page=cmgt-accountant&tab=accountantlist&message=3');
			      }
		} 
		if(isset($_REQUEST['delete_selected']))
		{		
			if(!empty($_REQUEST['selected_id']))
			{
				foreach($_REQUEST['selected_id'] as $_REQUEST['family_id'])
				{
					$member=get_user_meta($_REQUEST['family_id'], 'member', true);
					if(!empty($member))
					{
						foreach($member as $memmbervalue)
						{
							$family=get_user_meta($memmbervalue, 'family_id', true);
							if(!empty($family))
							{
								if(($key = array_search($_REQUEST['family_id'], $family)) !== false) 
								{
								
									unset($family[$key]);
								
									update_user_meta( $memmbervalue,'family_id', $family );
								
								}
							}
						}
					}
					$result=MJ_cmgt_delete_usedata($_REQUEST['family_id']);	
					if($result)
					{
						wp_redirect ( admin_url() . 'admin.php? page=cmgt-family&tab=familylist&message=3');
					}
				}
				
				$member=get_user_meta($_REQUEST['family_id'], 'member', true);
			
				
			}
			else
			{
				wp_redirect ( admin_url() . 'admin.php? page=cmgt-family&tab=familylist&message=4');
			}
		}

		?>
		
<div class="page-inner"><!-- PAGE INNNER DIV START-->
	<div id=""><!-- MAIN WRAPPER DIV START--> 
		<div class="row"><!-- ROW DIV START--> 
			<div class="col-md-12"><!-- COL 12 DIV START-->  
				<div class="panel panel-white main_home_page_div"><!-- PANEL WHITE DIV START-->  
					<?php 
					if(isset($_REQUEST['message']))
					{
						$message =$_REQUEST['message'];
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
								_e('Please select at least one record.','church_mgt');
							?></div></p><?php
						}
						elseif($message == 5) 
						{?>
							<div id="message" class="updated below-h2 notice is-dismissible "><p>
							<?php 
								_e('Only jpeg ,jpg ,png and gif files are allowed!.','church_mgt');
							?></div></p><?php
						}
					} 
				?>
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<?php 
						if($active_tab == 'familylist')
						{ 
							$args = array('role'=>'family_member');
							$familydata=get_users($args);
							if($familydata)
							{
								?>	
								<script type="text/javascript">
									$(document).ready(function() {
									jQuery('#family_memmber_list').DataTable({
										// "responsive": true,
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
								<div class="panel-body"><!-- PANEL BODY DIV START-->
									<div class="table-responsive">
										<form name="frm-example" action="" method="post">
											<table id="family_memmber_list" class="display" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Family Member Name & Email', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Mobile Number', 'church_mgt' ) ;?></th>
														<th class="dob_text_transform"> <?php  _e( 'Date of Birth', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Gender', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Relation', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Family Member Name & Email', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Mobile Number', 'church_mgt' ) ;?></th>
														<th class="dob_text_transform"> <?php  _e( 'Date of Birth', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Gender', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Relation', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
													<?php 
													if($familydata)
													{
														foreach ($familydata as $retrieved_data)
														{	
															$user_meta =get_user_meta($retrieved_data->ID, 'member_id', true);
															$user_data=get_userdata($user_meta);
															?>	
															<tr>
																<td class="cmgt-checkbox_width_10px"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo esc_attr($retrieved_data->ID); ?>"></td>

																<td class="user_image cmgt-checkbox_width_50px padding_left_0">
																	<?php 
																	$uid=$retrieved_data->ID;
																	$umetadata=MJ_cmgt_get_user_image($uid);
																	if(empty($umetadata['meta_value']))
																		{
																			echo '<img src='.esc_url(get_option( 'cmgt_family_logo' )).' height="50px" width="50px" class="img-circle" />';
																		}
																	else
																		echo '<img src='.esc_url($umetadata['meta_value']).' height="50px" width="50px" class="img-circle"/>';
																		?>
																</td>  
																<td class="name"><a class="color_black" href="?page=cmgt-family&tab=viewfamily&action=view&family_id=<?php echo esc_attr($retrieved_data->ID);?>"><?php echo esc_attr($retrieved_data->display_name);?></a>
																<br>
																	<label class="email_color"><?php echo esc_attr($retrieved_data->user_email);?></label>
																</td>
																<td class="mobile width_15_per">
																	<?php echo esc_attr($retrieved_data->phonecode).' '.esc_attr($retrieved_data->mobile_number);?>
																	
																</td>

																<td class="birth_date width_15_per">
																	<label><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->birth_date)));?>
																		
																	</label>
																</td>

																<td class="gender">
																	<?php echo _e(ucfirst($retrieved_data->gender) , 'church_mgt');?>
																	
																</td>
																<td class="relation width_10_per">
																	<?php echo _e($retrieved_data->relation , 'church_mgt');?>
																	
																</td>
																<td class="member width_15_per">
																	<?php 
																	if(!empty($user_data->display_name))
																	{
																		echo esc_attr(ucfirst($user_data->display_name));
																	}
																	else
																	{
																		echo "N/A";
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
																					<li><a class="dropdown-item" href="?page=cmgt-family&tab=viewfamily&action=view&family_id=<?php echo $retrieved_data->ID;?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																					<?php
																						if ($user_access_edit == 1) 
																						{ 
																					?>
																					<li><a class="dropdown-item" href="?page=cmgt-family&tab=addfamily&action=edit&family_id=<?php echo $retrieved_data->ID;?>"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																					<?php
																					}
																					?>
																					<div class="cmgt-dropdown-deletelist">
																					<?php
																					if ($user_access_delete == 1) 
																					{ 
																					?>
																						<li><a class="dropdown-item" onclick="return  confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');" href="?page=cmgt-family&tab=familylist&action=delete&family_id=<?php echo esc_attr($retrieved_data->ID);?>"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?></a></li>
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
											</table>
											<div class="print-button pull-left cmgt_print_btn_p0">
												<button class="btn btn-success btn-niftyhms">
													<input type="checkbox" name="selected_id[]" class="select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">
													<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'church_mgt' ) ;?></label>
												</button>
												<?php
													if($user_access_delete == 1)
													{
												?>
												<button data-toggle="tooltip" data-toggle="tooltip" title="<?php esc_html_e('Delete All','church_mgt');?>" name="delete_selected" class="delete_all_button"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Delete.png" ?>" alt=""></button>
												<?php
													}
												?>
											</div>
										</form>
									</div>
								</div><!-- PANEL BODY DIV END-->
								<?php 
							}
							else
							{
								?>
								<div class="no_data_list_div"> 
									<a href="<?php echo admin_url().'admin.php?page=cmgt-family&tab=addfamily';?>">
										<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
									</div> 
								</div>		
								<?php
							}
						}
						if($active_tab == 'addfamily')
						{
							require_once CMS_PLUGIN_DIR. '/admin/family/add_family.php';
						}
						if($active_tab == 'viewfamily')
						{
							require_once CMS_PLUGIN_DIR.'/admin/family/view_family.php';
						}
							?>				
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->
<?php ?>