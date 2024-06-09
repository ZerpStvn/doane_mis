<?php
	MJ_cmgt_header();
?>
<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
MJ_cmgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=MJ_cmgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_cmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
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
				     	wp_redirect ( home_url() . '?church-dashboard=user&page=familymember&message=2');
			      	}
				}
				else
				{
					?>
						<div id="message" class="updated below-h2"><p>
							<?php 
								wp_redirect ( home_url() . '?church-dashboard=user&page=familymember&message=5');
							?> 
							
						</div></p> 
					<?php 
				}
				
			}
			else
			{
				if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) 
				{
					
					$txturl=$usermetadata['cmgt_user_avatar'];
					$ext=MJ_cmgt_check_valid_extension($txturl);
					if(!$ext == 0)
			      	{	
						$result=MJ_cmgt_add_newuser($userdata,$usermetadata,$firstname,$lastname,$role);
				
				 		if($result)
						{
							wp_redirect ( home_url() . '?church-dashboard=user&page=familymember&message=1');
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
				     wp_redirect ( home_url() . '?church-dashboard=user&page=familymember&message=3');
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
					wp_redirect ( home_url() . '?church-dashboard=user&page=familymember&message=3');
					
					}
				}
				
				$member=get_user_meta($_REQUEST['family_id'], 'member', true);
			
				
			}
			else
			{
				wp_redirect ( home_url() . '?church-dashboard=user&page=familymember&message=4');
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
						{ ?>
							<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
								<?php _e('Record inserted successfully','church_mgt');?>
								<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<?php 
						}
						
						elseif($message == 2)
						{?>
							<div id="message_template"  class="alert_msg alert alert-success alert-dismissible" role="alert">
								<?php _e("Record updated successfully",'church_mgt'); ?>
								<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<?php 
							
						}
						elseif($message == 3) 
						{?>
							<div id="message_template"  class="alert_msg alert alert-success alert-dismissible" role="alert">
								<?php _e('Record deleted successfully','church_mgt'); ?>
								<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<?php	
						}
						elseif($message == 4) 
						{?>
							<div id="message_template"  class="alert_msg alert alert-success alert-dismissible" role="alert">
								<?php _e('Please select at least one record.','church_mgt');?>
								<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<?php
						}
						elseif($message == 5) 
						{?>
							<div id="message_template"  class="alert_msg alert alert-success alert-dismissible" role="alert">
								<?php _e('Only jpeg ,jpg ,png and gif files are allowed!.','church_mgt');?>
								<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<?php
						}
					} 
					?> 
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<?php 
						if($active_tab == 'familylist')
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
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": false},
												{"bSortable": false}]
									});
									$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
								} );
							</script>
							<?php 
							$current_user_id=get_current_user_id();
							$own_data=$user_access['own_data'];
							if($obj_church->role == 'member')
							{
								if($own_data == '1')
								{ 
									$familydata =get_user_meta($current_user_id, 'family_id', true);
								}
								else
								{
									$args = array('role'=>'family_member');
									$familydata=get_users($args);
								}
							}
							elseif($obj_church->role == 'family_member')
							{
								if($own_data == '1')
								{ 
									$member_id=get_user_meta($current_user_id, 'member_id', true);
									$familydata =get_user_meta($member_id, 'family_id', true);
								}
								else
								{
									$args = array('role'=>'family_member');
									$familydata=get_users($args);
								}
							}
					
							if($familydata)
							{	
								?>
								<div class="panel-body"><!-- PANEL BODY DIV START-->
									<div class="table-responsive">
										<form name="frm-example" action="" method="post">
											<table id="family_memmber_list" class="display" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
													<tr>
														<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Family Member Name & Email', 'church_mgt' ) ;?></th>
														<th class="dob_text_transform"> <?php  _e( 'Date of Birth', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Gender', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Relation', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
														<th> <?php  _e( 'Mobile Number', 'church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</thead>
												<tbody>
													<?php 
													if($familydata)
													{	
														if($own_data == '1')
														{ 
															foreach($familydata as $retrieved_data)
															{	
																$parent=get_userdata($retrieved_data);
															
																?>	
																<tr>
																	<td class="user_image width_5_per">
																		<?php 
																			if($retrieved_data)
																			{
																				$umetadata=MJ_cmgt_get_user_image($retrieved_data);
																			}
																			if(empty($umetadata['meta_value']))
																			{
																				echo '<img src='.get_option( 'cmgt_family_logo' ).' height="50px" width="50px" class="img-circle" />';
																			}
																			else
																			echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';
																		?>
																	</td>  
																	<td class="name width_20_per">
																		<a class="color_black" href="?church-dashboard=user&page=familymember&tab=viewfamily&action=view&family_id=<?php echo $parent->ID;?>"><?php echo $parent->first_name." ".$parent->last_name;?></a>
																		<br>
																		<label class="email_color"><?php echo esc_html  ($parent->user_email);?></label>
																	</td>
																	
																	<td class="birth_date width_15_per">
																		<label>
																			<?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($parent->birth_date)));?>
																			
																		</label>
																	</td>
																	
																	<td class="gender width_10_per">
																		<?php echo _e($parent->gender,'church_mgt');?>
																		
																	</td>
																	<td class="relation width_10_per">
																		<?php echo _e($parent->relation,'church_mgt');?>
																		
																	</td>
																	<td class="city width_15_per">
																		<?php 
																		$user_meta =get_user_meta($retrieved_data, 'member_id', true);
																		$user_data=get_userdata($user_meta);
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
																	<td class="mobile width_15_per">
																		<?php echo '+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.esc_html  ($parent->mobile_number);?>
																		
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
																						<!-- <li><a class="dropdown-item" href="?church-dashboard=user&page=familymember&tab=viewfamily&action=view&family_id=<?php echo $parent->ID;?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li> -->

																						

																						<li><a class="dropdown-item" href="?church-dashboard=user&page=familymember&tab=viewfamily&action=view&family_id=<?php echo $parent->ID;?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																						<?php
																					if($user_access['edit'] == '1')
																					{
																						?>
																						<li><a class="dropdown-item" href="?church-dashboard=user&page=familymember&tab=addfamily&action=edit&family_id=<?php echo $parent->ID;?>"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																						<?php
																					}
																					if($user_access['delete'] == '1')
																					{
																						?>
																						<div class="cmgt-dropdown-deletelist">
																							<li><a class="dropdown-item" onclick="return  confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');" href="?church-dashboard=user&page=familymember&action=delete&family_id=<?php echo esc_attr($parent->ID);?>"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?></a></li>
																						</div>
																						<?php
																					}
																					?>
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
														else
														{
															foreach ($familydata as $retrieved_data)
															{	
																$user_meta =get_user_meta($retrieved_data->ID, 'member_id', true);
																$user_data=get_userdata($user_meta);
																?>	
																<tr>
																	
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
																	<td class="name">
																		<a class="color_black" href="?church-dashboard=user&page=familymember&tab=viewfamily&action=view&family_id=<?php echo $retrieved_data->ID;?>">
																			<?php echo esc_attr($retrieved_data->display_name);?>
																		</a>
																		<br>
																		<label class="email_color"><?php echo esc_attr($retrieved_data->user_email);?></label>
																	</td>
																	<td class="mobile width_15_per">
																		<?php echo esc_attr($retrieved_data->phonecode).' '.esc_attr($retrieved_data->mobile_number);?>
																		
																	</td>

																	<td class="birth_date width_15_per">
																		<label>
																			<?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->birth_date)));?>
																				
																		</label>
																	</td>

																	<td class="gender">
																		<?php echo ucfirst($retrieved_data->gender);?>
																		
																	</td>
																	<td class="relation width_10_per">
																		<?php echo esc_attr($retrieved_data->relation);?>
																			
																	</td>
																	<td class="member width_15_per">
																		<?php echo esc_attr(ucfirst($user_data->display_name));?>
																			
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
																						<li><a class="dropdown-item" href="?church-dashboard=user&page=familymember&tab=viewfamily&action=view&family_id=<?php echo $retrieved_data->ID;?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
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
													}?>
												</tbody>
											</table>
										</form>
									</div>
								</div><!-- PANEL BODY DIV END-->
								<?php 
							}
							else
							{
								if($user_access['add']=='1')
								{
									?>
									<div class="no_data_list_div"> 
										<a href="<?php echo home_url().'?church-dashboard=user&page=familymember&tab=addfamily';?>">
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
						}
						$family_id=0;
						if(isset($_REQUEST['family_id']))
						{
							$family_id=$_REQUEST['family_id'];
							$edit=0;					
							$edit=1;
							$user_info = get_userdata($family_id);
						}		
						?>
						<?php
						if(isset ( $_REQUEST ['tab'] ) && $_REQUEST['tab']=='viewfamily')
						{
							$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
							?>
							<div class="panel-body view_patient_main"><!-- START PANEL BODY DIV-->
								<div class="content-body">
									<section id="user_information" class="">
										<div class="view_pateint_header_bg">
											<div class="row">
												<div class="col-xl-10 col-lg-9 col-md-9 col-sm-10">
													<div class="user_profile_header_left float_left_width_100">
														<?php 
														if($user_info->cmgt_user_avatar == "")
														{
															?>
															<img class="user_view_profile_image" src="<?php echo get_option( 'cmgt_family_logo' )?>">
														<?php 
														}
														else 
														{
														?>
															<img class="user_view_profile_image" src="<?php if($edit)echo esc_url( $user_info->cmgt_user_avatar ); ?>" />
														<?php 
														}
														?>
														<div class="row">
															<div class="float_left view_top1">
																<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
																	<label class="view_user_name_label"><?php echo esc_html(chunk_split(($user_info->first_name." ".$user_info->last_name),17));?> </label>
																	<div class="view_user_edit_btn ">
																		
																	</div>
																</div>
																<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
																	<div class="view_user_phone float_left_width_100">
																		<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/phone_figma.png" ?>" alt="">&nbsp;
																		<lable>
																			<?php echo get_user_meta($family_id, 'phonecode', true).' '.get_user_meta($family_id, 'mobile_number', true);?>
																		</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="row" id="cmgt_viewpage_addres_width">
															<div class="col-xl-12 col-md-12 col-sm-12">
																<div class="view_top2">
																	<div class="view_user_doctor_label">
																	<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;<lable ><?php echo  $user_info->address.', '.get_user_meta($family_id, 'city', true).', '.get_user_meta($family_id, 'state', true) .', '.get_user_meta($family_id, 'zip_code', true) ?> </label>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-xl-2 col-lg-3 col-md-3 col-sm-2 group_thumbs">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Group.png"?>">
												</div>
											</div>
										</div>
									</section>		
									<section id="body_content_area" class="margin_top_7per">
										<div class="panel-body"><!-- START PANEL BODY DIV-->
											<?php 
											if($active_tab1 == "general")
											{
												$user_meta =get_user_meta($_REQUEST['family_id'], 'member_id', true); 
													?>
												<div class="row">
													<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
														<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'church_mgt'); ?> </label><br/>
														<label class="view_page_content_labels"><?php echo esc_html($user_info->user_email);?></label>
													</div>
													<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
														<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'church_mgt'); ?> </label><br/>
														<label class="view_page_content_labels"><?php echo esc_html(date(MJ_cmgt_date_formate(),strtotime($user_info->birth_date)));?></label>
													</div>
													<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
														<label class="view_page_header_labels"> <?php esc_html_e('Gender', 'church_mgt'); ?> </label><br/>
														<label class="view_page_content_labels"><?php 
																	if($user_info->gender == "male")
																	{
																		$gender=esc_html__('Male','church_mgt');
																	}
																	elseif($user_info->gender == "female")
																	{
																		$gender=esc_html__('Female','church_mgt');
																	}
																	
																	echo $gender;?> </label>	
													</div>
													<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
														<label class="view_page_header_labels"> <?php esc_html_e('Relation', 'church_mgt'); ?> </label><br/>
														<label class="view_page_content_labels"><?php echo _e($user_info->relation,'church_mgt');?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
														<label class="view_page_header_labels"> <?php esc_html_e('Member Name', 'church_mgt'); ?> </label><br/>
														<label class="view_page_content_labels"><?php echo get_user_meta($user_meta, 'first_name', true);?> <?php echo get_user_meta($user_meta, 'last_name', true);?></label>
													</div>
												</div>
												<div class="row margin_top_20px">
													<div class="col-xl-8 col-md-8 col-sm-12">
														<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px">
															<div class="guardian_div">
																<label class="view_page_label_heading"> <?php esc_html_e('Address Information', 'church_mgt'); ?> </label>
																<div class="row">
																	<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
																		<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('City','church_mgt'); ?> </label>: <label class=""><?php echo get_user_meta($family_id, 'city', true);?></label>
																	</div>
																	<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
																		<label class="guardian_labels view_page_content_labels"> <?php _e('State','church_mgt');?> </label>: <label class="">
																			<?php 
																			if(!empty(get_user_meta($family_id, 'state', true))){
																				echo get_user_meta($family_id, 'state', true);
																			}else{
																				echo esc_html( __( 'N/A', 'church_mgt' ) );
																			}
																			?>
																		</label>
																	</div>
																	<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
																		<label class="guardian_labels view_page_content_labels"> <?php _e('Zip Code','church_mgt');?> </label>: <label class=""><?php echo get_user_meta($family_id, 'zip_code', true);?></label>
																	</div>
																</div>
															</div>	
														</div>
														<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px">
															<div class="guardian_div">
																<label class="view_page_label_heading"> <?php esc_html_e('Contact Information', 'church_mgt'); ?> </label>
																<div class="row">
																<div class="col-xl-6 col-md-6 col-sm-12 margin_top_15px">
																		<label class="guardian_labels view_page_content_labels"> <?php _e('Mobile Number','church_mgt');?> </label>: <label class=""><?php echo '+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta($family_id, 'mobile_number', true);?></label>
																	</div>
																	<div class="col-xl-6 col-md-6 col-sm-12 margin_top_15px">
																		<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Phone No','church_mgt'); ?> </label>: <label class="">
																			<?php 
																			if(!empty($user_info->phone)){
																				echo esc_html($user_info->phone);
																			}else{
																				echo esc_html( __( 'N/A', 'church_mgt' ) );
																			}
																			?>
																		</label>
																	</div>
																</div>
															</div>	
														</div>
													</div>
													
													<div class="col-xl-4 col-md-4 col-sm-12 margin_top_20px">
					
														<div class="col-xl-12 col-md-12 col-sm-12 mb-3">
															<div class="view_card appoinment_card">
																<div class="row">
																	<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2 card_heading">
																		<label class="card_heading_label"><?php _e('Member List','church_mgt');?></label>
																	</div>
																</div>
																<?php								
																$parent_member_id =get_user_meta($_REQUEST['family_id'], 'member_id', false);
																$parent_id =get_user_meta($_REQUEST['family_id'], 'member_id', true);
																$all_familty_member_id =get_user_meta($parent_id, 'family_id', true);
					
																$family_arr_data=(array_merge($parent_member_id,$all_familty_member_id));
																	foreach($family_arr_data as $familydata)
																	{
																		$family_id =$_REQUEST['family_id'];
																		$family=get_userdata($familydata);
																		if($family_id != $familydata)
																		{
																			?>
																			<div class="row cmgt_view_card_mb">
																				<div class="col-sm-2 col-md-4 col-lg-4 col-xl-3 appoinment_card_image cmgt_card_image_width">
																					<?php 
																						if($familydata)
																						{
																							$umetadata=MJ_cmgt_get_user_image($familydata);
																						}
																						if(empty($umetadata['meta_value']))
																						{
																							echo '<img src='.get_option( 'cmgt_family_logo' ).' height="52px" width="52px" id="grouplist_view_img" />';
																						}
																						else
																						echo '<img src='.$umetadata['meta_value'].' height="52px" width="52px" id="grouplist_view_img"/>';
																					?>
																				</div>
																				<div class="col-sm-10 col-md-8 col-lg-8 col-xl-9 cmgt_padding_0px cmgt_card_titel_width mt-1">
																					<p class="color_black"> <?php echo $family->display_name;?></p>
																					<p class="email_color cmgt_word_break"> <?php echo $family->user_email;?></p>
																				</div>
																			</div>
																			<?php
																		}
																	}
																?>
															</div>
														</div>
													</div>
												</div>
												<?php
											}
											?>
										</div>
									</section>
								</div>
							</div>
						
							<?php
						}
						?>		
						<?php
						if($active_tab == 'addfamily')
						{
							?>
								<?php
								$role='family_member';
								?>
								<script type="text/javascript">
								$(document).ready(function()
								{
									$('#family_member_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
									jQuery('.birth_date').datepicker({
										dateFormat: "yy-mm-dd",
										maxDate : 0,
										changeMonth: true,
										changeYear: true,
										autoclose: true,
										yearRange:'-100:+25',
										beforeShow: function (textbox, instance) 
										{
											instance.dpDiv.css({
												marginTop: (-textbox.offsetHeight) + 'px'                   
											});
										},    
										onChangeMonthYear: function(year, month, inst) {
											jQuery(this).val(month + "/" + year);
										}                    
									}); 
									$(".display-members").select2();
										//username not  allow space validation
									$('.username').keypress(function( e )
									{
									if(e.which == 32) 
										return false;
									});
								});
								</script>
								<?php 
								$edit=0;
								if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' )
								{
									$edit=1;	
									$user_info = get_userdata($_REQUEST['family_id']);

								}?>
									<div class="panel-body"><!-- PANEL BODY DIV START-->
										<form name="family_member_form" action="" method="post" class="form-horizontal" id="family_member_form" enctype="multipart/form-data"><!-- FAMILY MEMBER FORM START-->
											<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
											<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
											<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
											<?php $old_member_id=get_user_meta($user_info->ID,'member_id',true); ?>
											<input type="hidden" id="old_member_id" name="old_member_id" value="<?php echo $old_member_id; ?>">
											
											<div class="form-body user_form"> 
												<div class="row cmgt-addform-detail">
													<p><?php _e('Personal Information ','church_mgt');?></p>
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
														<div class="col-md-12 form-control">
																<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo esc_attr($user_info->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
																<label for="first_name"><?php _e('First Name','church_mgt');?><span class="require-field">*</span></label>
															</div>	
														</div>
													</div>
													<div class="col-md-6 margin_bottom_15">
														<div class="form-group input">
														<div class="col-md-12 form-control">
																<input  class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50"  <?php if($edit){ ?>value="<?php echo esc_attr($user_info->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
																<label for="middle_name"><?php _e('Middle Name','church_mgt');?></label>
															</div>	
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
														<div class="col-md-12 form-control">
																<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" type="text" maxlength="50"  <?php if($edit){ ?>value="<?php echo esc_attr($user_info->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>" name="last_name">
																<label for="last_name"><?php _e('Last Name','church_mgt');?><span class="require-field">*</span></label>
															</div>	
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<div class="col-md-12 form-control">
																<div class="skin skin-flat row">
																	<div class="input-group">
																		<label class="custom-control-label custom-top-label ml-2" for="gender"><?php _e('Gender','church_mgt');?><span class="require-field">*</span></label>
																		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
																			<?php $genderval = "male"; if($edit){ $genderval=esc_attr($user_info->gender); }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
																			<label class="radio-inline">
																			<input type="radio" value="male" class="tog" style="margin-top: 1px;" name="gender"  <?php  checked( 'male', $genderval);  ?>/><span class="rediospan" style="margin-left:5px;"><?php _e('Male','church_mgt');?></span> 
																			</label>
																			<label class="radio-inline">
																			<input type="radio" value="female" class="tog" style="margin-top: 1px;" name="gender"  <?php  checked( 'female', $genderval);  ?>/><span class="rediospan" style="margin-left:5px;"><?php _e('Female','church_mgt');?></span> 
																			</label>
																		</div>
																	</div>
																</div>		
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control">
																<input id="birth_date" class="form-control validate[required] birth_date" type="text" name="birth_date"  
																	value="<?php if($edit){ echo esc_attr($user_info->birth_date);}elseif(isset($_POST['birth_date'])){ echo esc_attr($_POST['birth_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
																<label for="birth_date"><?php _e('Date of Birth','church_mgt');?><span class="require-field">*</span></label>
															</div>	
														</div>
													</div>
													
													
													<div class="col-md-6 cmgt_display">
														<div class="form-group input row margin_buttom_0">
															<div class="col-md-12 input">
																<label class="ml-1 custom-top-label top" for="relation"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>
																<select id="member_list" class="form-control line_height_30px validate[required]" name="member_id">
																	<?php
																		if($edit)
																		{
																			$member_id=get_user_meta($user_info->ID,'member_id',true);
																		}
																		$user_id=get_current_user_id();										
																		if($obj_church->role == 'member')
																		{
																		   $user_data=get_userdata($user_id);
																		   echo '<option value="'.esc_attr($user_data->ID).'" '.selected($applicant,$user_data->ID).'>'.esc_attr($user_data->display_name).'</option>';
																		}
																		else
																		{ 
																			?>
																			<option value=""><?php _e('Select Member','church_mgt');?></option>
																			<?php
																			$get_members = array('role' => 'member');
																			$membersdata=get_users($get_members);
																			if(!empty($membersdata))
																			{
																				foreach ($membersdata as $retrieved_data)
																				{
																					echo '<option value="'.esc_attr($retrieved_data->ID).'" '.selected($applicant,$retrieved_data->ID).'>'.esc_attr($retrieved_data->display_name).'</option>';
																				}
																			}
																		}
																	  ?>
																</select>
															</div>
														</div>	
													</div>
																			
													
													
													<!----<div class="col-md-6 input cmgt_display">
														<label class="ml-1 custom-top-label top" for="relation"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>
														<select id="member_list" class="form-control line_height_30px validate[required]" name="member_id">
															<option value=""><?php _e('Select Member','church_mgt');?></option>
																<?php
																	if($edit)
																	{
																		$member_id=get_user_meta($user_info->ID,'member_id',true);
																	}
																	elseif(isset($_POST['member_id'])) 
																	{
																		$member_id= $_POST['member_id'];
																	}
																	else
																		$member_id=0;
																	
																	$get_members = array('role' => 'member');
																		$membersdata=get_users($get_members);
																	if(!empty($membersdata))
																	{
																		foreach ($membersdata as $member){?>
																			<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
																		<?php }
																	}?>
														</select> 
														
													</div>-->

													
													<div class="col-md-6 input cmgt_display">
														<label class="ml-1 custom-top-label top" for="relation"><?php _e('Relation','church_mgt');?><span class="require-field">*</span></label>
														<?php if($edit){ $relationval=$user_info->relation; }elseif(isset($_POST['relation'])){$relationval=$_POST['relation'];}else{$relationval='';}?>
														<select name="relation" class="form-control line_height_30px validate[required]" id="relation" >
															<option value=""><?php _e('Select Relation','church_mgt');?></option>
															<option value="<?php _e('Husband','school-mgt');?>" <?php selected( $relationval, 'Husband'); ?>><?php _e('Husband','church_mgt');?></option>
															<option value="<?php _e('Wife','school-mgt');?>" <?php selected( $relationval, 'Wife'); ?>><?php _e('Wife','church_mgt');?></option>
															<option value="<?php _e('Daughter','school-mgt');?>" <?php selected( $relationval, 'Daughter'); ?>><?php _e('Daughter','church_mgt');?></option>
															<option value="<?php _e('Father','school-mgt');?>" <?php selected( $relationval, 'Father'); ?>><?php _e('Father','church_mgt');?></option>
															<option value="<?php _e('Mother','school-mgt');?>" <?php selected( $relationval, 'Mother'); ?>><?php _e('Mother','church_mgt');?></option>
															<option value="<?php _e('Son','school-mgt');?>" <?php selected( $relationval, 'Son'); ?>><?php _e('Son','church_mgt');?></option>
															<option value="<?php _e('Brother','school-mgt');?>" <?php selected( $relationval, 'Brother'); ?>><?php _e('Brother','church_mgt');?></option>
															<option value="<?php _e('Sister','school-mgt');?>" <?php selected( $relationval, 'Sister'); ?>><?php _e('Sister','church_mgt');?></option>
														</select>
													</div>
												</div>
												<div class="row cmgt-addform-detail">
													<p><?php _e('Address Information','church_mgt');?></p>
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
														<div class="col-md-12 form-control">
																<input  class="form-control validate[required,cusom[address_description_validation]]" maxlength="150" type="text"  name="address" 
																<?php if($edit){ ?>value="<?php echo esc_attr($user_info->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
																<label for="address"><?php _e('Address','church_mgt');?><span class="require-field">*</span></label>
															</div>	
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
														<div class="col-md-12 form-control">
																<input  class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
																<?php if($edit){ ?>value="<?php echo esc_attr($user_info->city);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
																<label for="city_name"><?php _e('City','church_mgt');?><span class="require-field">*</span></label>
															</div>	
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
														<div class="col-md-12 form-control">
																<input  class="form-control" maxlength="50" type="text"  name="state_name" 
																<?php if($edit){ ?>value="<?php echo esc_attr($user_info->state);}elseif(isset($_POST['state_name'])) echo esc_attr($_POST['state_name']);?>">
																<label for="state_name"><?php _e('State','church_mgt');?></label>
															</div>	
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group input">
														<div class="col-md-12 form-control">
																<input  class="form-control  validate[required,custom[zipcode]]" maxlength="15" type="text"  name="zip_code" 
																<?php if($edit){ ?>value="<?php echo esc_attr($user_info->zip_code);}elseif(isset($_POST['zip_code'])) echo esc_attr($_POST['zip_code']);?>">
																<label for="zip_code"><?php _e('Zip Code','church_mgt');?><span class="require-field">*</span></label>
															</div>	
														</div>
													</div>
												</div>
												<div class="row cmgt-addform-detail">
													<p><?php _e('Contact Information','church_mgt');?></p>
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
														<div class="col-md-12 form-control">
																<input  class="form-control validate[,custom[phone]] text-input" type="text" minlength="6" maxlength="15"  name="phone" 
																<?php if($edit){ ?> value="<?php echo esc_attr($user_info->phone);}elseif(isset($_POST['phone'])) echo esc_attr($_POST['phone']);?>">
																<label for="phone"><?php _e('Phone','church_mgt');?><span class="require-field"></span></label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-5 col-lg-4">
																<div class="form-group input">
																	<div class="col-md-12 form-control">
																		<input id="country_code" maxlength="5" disabled name="phonecode" type="text" class="form-control pl-4 mobile validate[required] onlynumber_and_plussign" value="<?php if($edit) { if(!empty($user_info->phonecode)){ echo esc_attr($user_info->phonecode); }else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }}else{ ?>+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )); } ?>">
																		
																		<label for="country_code" class="pl-2 cmgt_country_code"><?php esc_html_e('Country Code','church_mgt');?><span class="required red">*</span></label>
																		<div class="pos_mobile  form-control-position nf_left_icon">
																			<i class="ft-plus"></i>
																		</div>
																	</div>											
																</div>
															</div>
															<div class="col-md-7 col-lg-8">
																<div class="form-group input">
																	<div class="col-md-12 form-control cmgt_mobile_error">
																		<input type="text" class="form-control validate[required,custom[onlyNumberSp]]" name="mobile_number" minlength="6" maxlength="15" <?php if($edit){ ?>value="<?php echo esc_attr($user_info->mobile_number);}elseif(isset($_POST['mobile_number'])) echo esc_attr($_POST['mobile_number']);?>">
																		<label for="mobile_number"><?php _e('Mobile Number','church_mgt');?><span class="require-field">*</span></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
												</div>
												<?php

												$cmgt_family_without_email_pass = get_option('cmgt_family_without_email_pass');

												if($cmgt_family_without_email_pass != 'yes')
												{ 
													?>
													<div class="row cmgt-addform-detail">
														<p><?php _e('Login Information','church_mgt');?></p>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group input">
															<div class="col-md-12 form-control">
																	<input  class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
																	<?php if($edit){ ?>value="<?php echo esc_attr($user_info->user_email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
																	<label for="email"><?php _e('Email','church_mgt');?><span class="require-field">*</span></label>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group input">
															<div class="col-md-12 form-control">
																	<input  class="form-control validate[required,custom[username_validation]] username" maxlength="50" type="text"  name="username" 
																	<?php if($edit){ ?>value="<?php echo esc_attr($user_info->user_login);}elseif(isset($_POST['username'])) echo esc_attr($_POST['username']);?>" <?php if($edit) echo "readonly";?>>
																	<label for="username"><?php _e('User Name','church_mgt');?><span class="require-field">*</span></label>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group input">
															<div class="col-md-12 form-control">
																	<input  class="form-control <?php if(!$edit) echo 'validate[required,minSize[8]]';?>" type="password"  name="password" >
																	<label for="password"><?php _e('Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
																</div>
															</div>
														</div>
													</div>
													<?php
												} ?>
												<div class="row cmgt-addform-detail">
													<p><?php _e('Profile Image','church_mgt');?></p>
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group input">
															<div class="col-md-12 form-control upload-profile-image-patient">	
																<label for="photo" class="custom-control-label custom-top-label ml-2"><?php esc_html_e('Upload Profile Image','church_mgt');?></label>
																<!-- <button id="upload_user_avatar_button" class="browse btn btn-success for_btn_grp1 community_button_disabled upload-profile-image-patient" data-toggle="modal" data-target="#image_upload" type="button"><?php esc_html_e('Choose image','church_mgt');?></button> -->
																<input type="file" id="cmgt_user_avatar_url" name="cmgt_user_avatar" value="<?php if($edit)echo esc_url( $user_info->cmgt_user_avatar );elseif(isset($_POST['cmgt_user_avatar'])) echo $_POST['cmgt_user_avatar']; ?>">
																<input type="hidden" name="hidden_cmgt_user_avatar" 
																value="<?php if($edit){ echo esc_html($user_data->cmgt_user_avatar);}elseif(isset($_POST['cmgt_user_avatar'])) echo $_POST['cmgt_user_avatar'];?>">
															</div>
															<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
																<div id="upload_user_avatar_preview" >
																	<?php 
																	if($edit)
																	{
																		if($user_info->cmgt_user_avatar == "")
																		{?>
																			<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_family_logo' )); ?>">
																			<?php
																		}
																		else 
																		{
																			?>
																			<img class="image_preview_css" style="max-width:100%;" src="<?php if($edit)echo esc_url( $user_info->cmgt_user_avatar ); ?>" />
																		<?php 
																		}
																	}
																	else 
																	{
																	?>
																		<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_family_logo' )); ?>">
																	<?php 
																	}
																	?>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
					
													<div class="col-md-6">
														<?php wp_nonce_field( 'save_family_member_nonce' ); ?>
														<div class="offset-sm-0">
															<input id="save_family_member" type="submit" value="<?php if($edit){ _e('Save Family','church_mgt'); }else{ _e('Add Family Member','church_mgt');}?>" name="save_family_member" class="btn btn-success  col-md-12 save_btn"/>
														</div>
													</div>

												</div>	
											</div>
										</form><!-- FAMILY MEMBER FORM END-->
									</div><!-- PANEL BODY DIV START-->
								<?php
								?>
							<?php
						}
						?>		
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->
<?php ?>