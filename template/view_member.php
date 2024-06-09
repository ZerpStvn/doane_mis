<?php 
$curr_user_id=get_current_user_id();
$obj_church = new Church_management(get_current_user_id());
$obj_dashboard= new Cmgtdashboard;
$obj_pastoral=new Cmgtpastoral;
$obj_pledge=new Cmgtpledes;
$obj_gift=new Cmgtgift();
$obj_transaction=new Cmgttransaction;
$obj_attend=new Cmgtattendence;
$obj_activity=new Cmgtactivity;
$user_access=MJ_cmgt_get_userrole_wise_access_right_array();
	$member_id=0;
	if(isset($_REQUEST['member_id']))
	{
		$member_id=sanitize_text_field($_REQUEST['member_id']);
		$edit=0;					
		$edit=1;
		$user_info = get_userdata($member_id);
	}		
?>
<?php
if(isset ( $_REQUEST ['tab'] ) && $_REQUEST['tab']=='viewmember')
{
	$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
	
	?>
	<div class="padding_left_15px padding_frontendlist_body view_patient_main"><!-- START PANEL BODY DIV-->
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
									<img class="user_view_profile_image" src="<?php echo get_option( 'cmgt_member_thumb' )?>">
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
											<label class="view_user_name_label"><?php echo esc_html(chunk_split(($user_info->display_name),17));?> </label>
											<div class="view_user_edit_btn ">
																	
											</div>
										</div>
										<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
											<div class="view_user_phone float_left_width_100">
												<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/phone_figma.png" ?>" alt="">&nbsp;
												<lable class="cmgt_phone_color">
													<?php echo esc_attr($user_info->phonecode).' '.esc_html($user_info->mobile);?>
												</label>
											</div>
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-xl-12 col-md-12 col-sm-12">
										<div class="view_top2">
											<div class="view_user_doctor_label">
											<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;<lable><?php if($user_info->address != '') echo $user_info->address.",";if($user_info->city_name != '')echo $user_info->city_name."."; ?> </label>
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
			
			<section id="body_area" class="">
				<div class="row">
					<div class="col-xl-12 col-md-12 col-sm-12 cmgt_padding_0px">
						<ul class="nav border_bottom_for_view_page nav-tabs panel_tabs margin_left_1per cmgt-view-page-tab flex-nowrap overflow-auto" role="tablist">
							<li class="<?php if($active_tab1=='general'){?>active<?php }?>">			
								<a href="?church-dashboard=user&&page=member&tab=viewmember&tab1=general&action=view&member_id=<?php echo $_REQUEST['member_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'general' ? 'active' : ''; ?>">
								<?php esc_html_e('General', 'church_mgt'); ?></a> 
								
							</li>
							<?php
							$page_name = "familymember";
							$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
							$role = MJ_cmgt_get_user_role(get_current_user_id());
							if($user_access['view'] == "1" && $role!='accountant')
							{ 
							?>
							<li class="<?php if($active_tab1=='familylist'){?>active<?php }?>">
								<a href="?church-dashboard=user&&page=member&tab=viewmember&tab1=familylist&action=view&member_id=<?php echo $_REQUEST['member_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'familylist' ? 'active' : ''; ?>">
								<?php esc_html_e('Family Member', 'church_mgt'); ?></a> 
							</li> 
							<?php } ?>
							<?php
							$page_name = "pastoral";
							$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
							if($user_access['view'] == "1")
							{ 
							?>
							<li class="<?php if($active_tab1=='pastorallist'){?>active<?php }?>">
								<a href="?church-dashboard=user&&page=member&tab=viewmember&tab1=pastorallist&action=view&member_id=<?php echo $_REQUEST['member_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'pastorallist' ? 'active' : ''; ?>" >
									<?php esc_html_e('Pastoral List', 'church_mgt'); ?></a> 
								</a>  
							</li>
							<?php } ?>
							<?php
							$page_name = "attendance";
							$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
							if($user_access['view'] == "1")
							{ 
							?>
							<li class="<?php if($active_tab1=='attendancelist'){?>active<?php }?>">
								<a href="?church-dashboard=user&&page=member&tab=viewmember&tab1=attendancelist&action=view&member_id=<?php echo $_REQUEST['member_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'attendancelist' ? 'active' : ''; ?>">
								<?php esc_html_e('Attendance List', 'church_mgt'); ?></a> 
							</li>  
							<?php } ?>
							<?php
							$page_name = "pledges";
							$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
							if($user_access['view'] == "1")
							{ 
							?>
							<li class="<?php if($active_tab1=='peldgeslist'){?>active<?php }?>">
								<a href="?church-dashboard=user&&page=member&tab=viewmember&tab1=peldgeslist&action=view&member_id=<?php echo $_REQUEST['member_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'peldgeslist' ? 'active' : ''; ?>">
								<?php esc_html_e('Pledges List', 'church_mgt'); ?></a> 
							</li>  
							<?php } ?>
							
							<?php
							$page_name = "payment";
							$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
							if($user_access['view'] == "1")
							{ 
							?>
							<li class="<?php if($active_tab1=='invoice'){?>active<?php }?>">			
								<a href="?church-dashboard=user&&page=member&tab=viewmember&tab1=invoice&action=view&member_id=<?php echo $_REQUEST['member_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'invoice' ? 'active' : ''; ?>">
								<?php esc_html_e('Invoices', 'church_mgt'); ?></a> 
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</section> 
			
				<section id="body_content_area_frontend" class="">
					<div class="panel-body"><!-- START PANEL BODY DIV-->
						<?php 
				        if($active_tab1 == "general")
				        {
					         ?>
							<div class="row margin_top_15px">
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Member Id', 'church_mgt'); ?> </label><br/>
									<label class="view_page_content_labels"><?php if($edit){ echo esc_attr($user_info->member_id);}else echo esc_attr($newmember);?></label>
								</div>
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'church_mgt'); ?> </label><br/>
									<label class="view_page_content_labels"><?php echo esc_html($user_info->user_email);?></label>
								</div>
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'church_mgt'); ?> </label><br/>
									<label class="view_page_content_labels"><?php echo esc_html(date(MJ_cmgt_date_formate(),strtotime($user_info->birth_date)));?></label>
								</div>
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Baptist Date', 'church_mgt'); ?> </label><br/>
									<label class="patient_status_td view_page_content_labels"><?php echo esc_html(date(MJ_cmgt_date_formate(),strtotime($user_info->baptist_date)));?></label>
								</div>
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Join Date', 'church_mgt'); ?> </label><br/>
									<label class="patient_status_td view_page_content_labels"><?php echo esc_html(date(MJ_cmgt_date_formate(),strtotime($user_info->begin_date)));?></label>
								</div>
							</div>
							<div class="row margin_top_20px">
							    <div class="col-xl-8 col-md-8 col-sm-12">
									<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px">
										<div class="guardian_div">
											<label class="view_page_label_heading"> <?php esc_html_e('Member Information', 'church_mgt'); ?> </label>
											<div class="row">
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Name','church_mgt'); ?> </label>: <label class=""><?php echo $user_info->display_name; ?></label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels"> <?php _e('Gender','church_mgt');?> </label>: <label class=""><?php 
									                        if($user_info->gender == "male")
									                        {
										                        $gender=esc_html__('Male','church_mgt');
									                        }
									                        elseif($user_info->gender == "female")
									                        {
										                        $gender=esc_html__('Female','church_mgt');
									                        }
									                            echo $gender;?>
													</label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php _e('Marital Status','church_mgt');?></label>: <label class=""><?php
									                    if($user_info->marital_status == "unmarried")
									                    {
										                       $marital_status=esc_html__('Unmarried','church_mgt');
									                    }
									                    elseif($user_info->marital_status == "married")
									                    {
										                    $marital_status=esc_html__('Married','church_mgt');
									                    }
									                       echo ucfirst($marital_status); ?>
													</label>
												</div>

												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php _e('Occupation','church_mgt');?> </label>: <label class="">
														<?php 
														if(!empty($user_info->occupation)){
															echo esc_html($user_info->occupation);
															
														}else{
															echo esc_html( __( 'N/A', 'church_mgt' ) );
														}
														?>
													</label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php _e('Education','church_mgt');?> </label>: 
													<label class="">
														<?php 
														if(!empty($user_info->education)){
															echo esc_html($user_info->education);
															
														}else{
															echo esc_html( __( 'N/A', 'church_mgt' ) );
														}
														?>
													</label>
												</div>
											</div>
										</div>	
									</div>
									
									<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px">
										<div class="guardian_div">
											<label class="view_page_label_heading"> <?php esc_html_e('Contact Information', 'church_mgt'); ?> </label>
											<div class="row">
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels"> <?php _e('Phone No','church_mgt');?> </label>: 
													<label class="">
														<?php 
														if(!empty($user_info->phone)){
															echo esc_html($user_info->phone);
														}else{
															echo esc_html( __( 'N/A', 'church_mgt' ) );
														}
														?>
													</label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php _e('Fax','church_mgt');?></label>: 
													<label class="">
														<?php 
															if(!empty($user_info->fax_number)){
																echo esc_html($user_info->fax_number);
															}else{
																echo esc_html( __( 'N/A', 'church_mgt' ) );
															}
														?>
													</label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php _e('Skype Id','church_mgt');?> </label>: 
													<label class="">
														<?php 
															if(!empty($user_info->skyp_id)){
																echo esc_html($user_info->skyp_id);
															}else{
																echo esc_html( __( 'N/A', 'church_mgt' ) );
															}
														?>
													</label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> 
														<?php esc_html_e('City','church_mgt');?> </label>: <label class=""><?php echo esc_html(chunk_split(($user_info->city_name),17));?></label>
												</div>
												<div class="col-xl-8 col-md-8 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Address','church_mgt'); ?> </label>: <label class=""><?php echo $user_info->address; ?></label>
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
													<label class="card_heading_label"><?php _e('Group List','church_mgt');?></label>
												</div>
											</div>
											<?php								
											// $groupdata = $obj_dashboard->MJ_cmgt_get_my_grouplist($member_id);
											$obj_group=new Cmgtgroup;
											$groupdata=$obj_group->MJ_cmgt_get_all_groups_dashboard();
										
											if(!empty($groupdata))
											{
												$i= 1;
												
												foreach ($groupdata as $retrieved_data)
												{		
													$group_count=$obj_group->MJ_cmgt_count_group_members($retrieved_data->id);
													?>
													<div class="row cmgt_view_card_mb">
														<div class="col-sm-2 col-md-3 col-lg-4 col-xl-3 appoinment_card_image appoinment_card_image_width">
															<?php $groupimage=$retrieved_data->cmgt_groupimage;
																if(empty($groupimage))
																{
																	?>
																	<img src=<?php echo get_option( 'cmgt_group_logo' ); ?> height="52px" width="52px" id="grouplist_view_img"/>
																	<?php
																}
																else
																{
																	?>
																	<img src=<?php echo $groupimage; ?> height="52px" width="52px"  id="grouplist_view_img"/>
																	<?php
																}
															?>
														</div>
														<div class="col-sm-8 col-md-6 col-lg-5 col-xl-6 cmgt_padding_0px fullname_of_card_title_width">
															<p class="fullname_of_card_title"> <?php echo $retrieved_data->group_name;?></p>
														</div>
														<div class="col-sm-2 col-md-3 col-lg-3 col-xl-3 mt-2 cmgt-group-list-total-width">
															<div class="cmgt-group-list-total-group">
																<?php if(!empty($group_count) ) 
																	{ ?>
																		<span class=""><?php echo $group_count;?></span>
																	<?php 
																	}
																	else
																	{
																	?>  
																		<span class=""><?php echo "0";?></span>
																	<?php
																	}
																	?>
															</div>
														</div>
													</div>
													<?php
												}
											
											}
											else
											{
												?>
												<div class="row"> 
													<p class="remainder_title_pr empty_data_color Bold">  <?php esc_html_e('No Data Available','church_mgt');?>
													</p>
												</div>	
												<?php
											}	
											?>
										</div>
									</div>
									
									<div class="col-xl-12 col-md-12 col-sm-12 mb-3">
										<div class="view_card appoinment_card">
											<div class="row">
												<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2 card_heading">
													<label class="card_heading_label"><?php _e('Ministry List','church_mgt');?></label>
												</div>
											</div>
											<?php								
											$ministrydata = $obj_dashboard->MJ_cmgt_get_my_ministrylist($member_id);
												
											if(!empty($ministrydata))
											{
												$i= 1;
												
												foreach ($ministrydata as $retrieved_data)
												{
													
													$ministry_count=$obj_dashboard->MJ_cmgt_count_ministry_members($retrieved_data->id);

													?>	
													<div class="row cmgt_view_card_mb">
														<div class="col-sm-2 col-md-3 col-lg-4 col-xl-3 appoinment_card_image appoinment_card_image_width ">
															<?php 
																if($retrieved_data->ministry_image == '')
																{
																	echo '<img src='.get_option( 'cmgt_ministry_logo' ).' height="50px" width="50px" id="grouplist_view_img" />';
																}
																else
																	echo '<img src='.$retrieved_data->ministry_image.' height="50px" width="50px" id="grouplist_view_img"/>';
															?>
															
														</div>
														<div class="col-sm-8 col-md-6 col-lg-5 col-xl-6 cmgt_padding_0px fullname_of_card_title_width">
															<p class="fullname_of_card_title"> <?php echo $retrieved_data->ministry_name;?></p>
														</div>
														<div class="col-sm-2 col-md-3 col-lg-3 col-xl-3 mt-2 cmgt-group-list-total-width">
															<div class="cmgt-group-list-total-group">
																	<?php 
																	if(!empty($ministry_count) ) 
																	{ ?>
																		<span class=""><?php echo $ministry_count;?></span>
																	<?php 
																	}
																	else
																	{
																	?>  
																		<span class=""><?php echo "0";?></span>
																	<?php
																	}
																?>
															</div>
														</div>
													</div>
													<?php
												}
											}
											else
											{
												?>
												<div class="row"> 
													<p class="remainder_title_pr empty_data_color Bold">  <?php esc_html_e('No Data Available','church_mgt');?>
													</p>
												</div>	
												<?php
											}	
											?>
										</div>
									</div>
								</div>
							</div>
							<?php
							}
							?>
					
						<?php 
                        if ($active_tab1 == "invoice") 
						{
                            ?>
							<!-- POP up code -->
								<div class="popup-bg" style="z-index:100000 !important;">
									<div class="overlay-content payment_invoice_popup">
										<div class="modal-content">
											<div class="invoice_data">
												<div class="category_list">
												</div>
											</div>
										</div>
									</div> 
								</div>
							<!-- End POP-UP Code -->
							<script type="text/javascript">
								$(document).ready(function()
								{
									jQuery('#transaction_viewlist').DataTable({
										//"responsive":true,
										language:<?php echo MJ_cmgt_datatable_multi_language();?>,
										"order": [[ 0, "desc" ]],
										dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-6"i><"col-sm-5"p>>',
										"aoColumns":[
													  {"bSortable": false},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": false}]
										});
										$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
										$('.dataTables_filter').addClass('search_btn_view_page');
								} );
							</script>
							<?php
							$transactiondata=$obj_transaction->MJ_cmgt_get_all_transaction_own_member($member_id);
							if(!empty($transactiondata))
							{
								?>
								<div class="table-responsive cmgt_view_pt_0">
									<table id="transaction_viewlist" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
											<tr>
												<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Donation Type', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Transaction Date', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Payment Method', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Description', 'church_mgt' ) ;?></th>
												<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Donation Type', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Transaction Date', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Payment Method', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Description', 'church_mgt' ) ;?></th>
												<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
											<?php 
											$i=0;
											if(!empty($transactiondata))
											{
												foreach ($transactiondata as $retrieved_data)
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
														<td class="user_image width_50px profile_image_prescription"><p class="remainder_title_pr Bold viewpriscription prescription_tag <?php echo $color_class; ?>">	
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Donation-white.png"?>" alt="" class="massage_image center"></p>
														</td>
														<td class="name width_20_per">
															<label class="color_black show-invoice-popup" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="transaction">
															<?php echo get_the_title(esc_attr($retrieved_data->donetion_type));?> 
															</label>
														</td>
														<td class="stat_date width_15_per"><label><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->transaction_date)));?> </label></td>
														
														<td class="total_amount width_15_per"><label><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->amount);?> </label></td>
														<td class="method method_status_td width_15_per">
															<label class=""><?php echo MJ_cmgt_get_payment_method($retrieved_data->pay_method);?> </label>
														</td>
															<?php
																$description = strlen($retrieved_data->description) > 32 ? substr($retrieved_data->description,0,32)."..." : $retrieved_data->description;
																if(!empty($description)) 
																{
																	?>
																	<td class="description  width_30_per"><?php echo $description;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php echo $retrieved_data->description; ?>"></i></td>

																	<?php
																}
																else
																{
																	?>
																	<td class="description width_25_per"><?php echo esc_html( __( 'N/A', 'church_mgt' ) );?> </td>
																	<?php
																} 
															?>


														<td class="action"> 
															<div class="cmgt-user-dropdown mt-2">
																<ul class="">
																	<!-- BEGIN USER LOGIN DROPDOWN -->
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>">
																		</a>
																		<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																			<li><a class="dropdown-item " href="?church-dashboard=user&&page=member&tab=view_invoice&member_id=<?php echo $_REQUEST['member_id'];?>&idtest=<?php echo esc_attr($retrieved_data->id);?>&invoice_type=transaction"><i class="fa fa-eye"></i><?php _e('View Invoice', 'church_mgt' ) ;?></a></li>
																		</ul> 
																	</li>
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
								</div>
								<?php
							}
							else
							{
								$page= 'payment';
								$access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page);
								if($access['add']=='1')
								{
									?>
									<div class="no_data_list_div"> 
										<a href="<?php echo home_url().'?church-dashboard=user&&page=payment&tab=addtransaction';?>">
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
						?>
                        <?php 
						if($active_tab1 == "pastorallist") 
						{
							$pastoraldata=$obj_pastoral->MJ_cmgt_get_pastoral_member($member_id);
							if(!empty($pastoraldata))
							{
                           	 	?>
								<!-- POP up code -->
									<div class="popup-bg" style="z-index:100000 !important;">
										<div class="overlay-content">
											<div class="modal-content">
												<div class="category_list pastoral_page">
												</div>
											</div>
										</div> 
									</div>
								<!-- End POP-UP Code -->
								<script type="text/javascript">
									$(document).ready(function()
									{
										jQuery('#pastoral_list').DataTable({
											//"responsive":true,
											language:<?php echo MJ_cmgt_datatable_multi_language();?>,
											"order": [[ 0, "desc" ]],
											dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-6"i><"col-sm-5"p>>',
											"aoColumns":[
														{"bSortable": false},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": false}]
											});
											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
											$('.dataTables_filter').addClass('search_btn_view_page');
									} );
								</script>
								<div class="table-responsive cmgt_view_pt_0">
									<table id="pastoral_list" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
											<tr>
												<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Pastoral Title', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Pastoral Date', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Pastoral Time', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Description', 'church_mgt' ) ;?></th>
												<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Pastoral Title', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Pastoral Date', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Pastoral Time', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Description', 'church_mgt' ) ;?></th>
												<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
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
														<td class="user_image width_50px profile_image_prescription"><p class="remainder_title_pr Bold viewpriscription prescription_tag <?php echo $color_class; ?>">	
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pastoral-white.png"?>" alt="" class="massage_image center"></p>
														</td>
														<td class="pastoraltitle name width_25_per"><a class="color_black view_pastoral" id="<?php echo $retrieved_data->id?>" href=""><?php echo esc_attr($retrieved_data->pastoral_title);?> </a>
														</td>
														<td class="pastoral width_15_per"><label><?php  echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->pastoral_date));?> </label></td>
														<td class="pastoral-time width_15_per">
															<label><?php if(!empty($retrieved_data->pastoral_time)){ echo esc_attr($retrieved_data->pastoral_time); }else{ echo "N/A"; }  ?> </label></td>
														<?php
															
															$description = strlen($retrieved_data->description) > 40 ? substr($retrieved_data->description,0,40)."..." : $retrieved_data->description;
															if(!empty($description)) 
															{
																?>
																	<td class="description width_35_per"><label><?php echo $description;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php echo $retrieved_data->description; ?>"></i></label></td>

																<?php
															}
															else
															{
																?>
																	<td class="description width_35_per"><label><?php echo esc_html( __( 'N/A', 'church_mgt' ) );?> </label></td>

																<?php
															} 
														?>
														<td class="action"> 
															<div class="cmgt-user-dropdown mt-2">
																<ul class="">
																	<!-- BEGIN USER LOGIN DROPDOWN -->
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>">
																		</a>
																		<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																			<li><a class="dropdown-item view_pastoral" href="#" id="<?php echo $retrieved_data->id?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																		</ul> 
																	</li>
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
								</div>
								<?php
							}
							else
							{
								$page= 'pastoral';
								$access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page);
								if($access['add']=='1')
								{
									?>
									<div class="no_data_list_div"> 
										<a href="<?php echo home_url().'?church-dashboard=user&page=pastoral&tab=add_pastoral';?>">
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
						?>
						<?php 
                        if ($active_tab1 == "peldgeslist") 
						{
                            ?>
							<!-- POP up code -->
								<div class="popup-bg" style="z-index:100000 !important;">
									<div class="overlay-content">
										<div class="modal-content">
											<div class="invoice_data">
											</div>
										</div>
									</div> 
								</div>
							<!-- End POP-UP Code -->
							<script type="text/javascript">
								$(document).ready(function()
								{
									jQuery('#pledges_list').DataTable({
										//"responsive":true,
										language:<?php echo MJ_cmgt_datatable_multi_language();?>,
										"order": [[ 0, "desc" ]],
										dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-6"i><"col-sm-5"p>>',
										"aoColumns":[
													  {"bSortable": false},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": false}]
										});
										$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
										$('.dataTables_filter').addClass('search_btn_view_page');
								} );
							</script>
							<?php
							$pledgedata=$obj_pledge->MJ_cmgt_get_All_member_pledges($member_id);
							if(!empty($pledgedata))
							{
								?>
								<div class="table-responsive cmgt_view_pt_0"><!-- TABLE RESPONSIVE DIV START-->
									<table id="pledges_list" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
											<tr>
												<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Start Date', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'End Date', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Frequency Number of Time', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Total Amount', 'church_mgt' ) ;?></th>
												<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Start Date', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'End Date', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Frequency Number of Time', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Total Amount', 'church_mgt' ) ;?></th>
												<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
											<?php 
											$i=0;
											if(!empty($pledgedata))
											{
												foreach ($pledgedata as $retrieved_data)
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
														<td class="user_image width_50px profile_image_prescription"><p class="remainder_title_pr Bold viewpriscription prescription_tag <?php echo $color_class; ?>">	
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pledges-white.png"?>" alt="" class="massage_image center"></p>
														</td>
														<td class="stat_date width_20_per"><lable class="show-invoice-popup" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="pledges"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->start_date)));?> </lable></td>
														<td class="end_date width_20_per"><lable><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->end_date)));?> </lable></td>
														<td class="total_amount width_20_per"><lable><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->amount);?> </lable></td>
														<td class="Frequency_day width_20_per">
														<?php
															if($retrieved_data->period_id == "one_time")
															{
																?>
																<lable><?php esc_html_e( '1 Time', 'church_mgt' ) ;?><?php esc_html_e('(', 'church_mgt' );?><?php echo esc_attr($retrieved_data->times_number);?> <?php _e('-Time)','church_mgt');?> </lable>
															<?php
															}else
															{
																?>
																<lable><?php echo _e(ucfirst($retrieved_data->period_id),'church_mgt');?> (<?php echo esc_attr($retrieved_data->times_number);?> <?php _e('-Time)','church_mgt');?> </lable>
																<?php
															}
															?>
														</td>
														<td class="total_amount width_20_per"><lable><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->total_amount);?> </lable></td>
														<td class="action"> 
														<div class="cmgt-user-dropdown mt-2">
															<ul class="">
																<!-- BEGIN USER LOGIN DROPDOWN -->
																<li class="">
																	<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																		<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>">
																	</a>
																	
																	<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																		<li><a class="dropdown-item " href="?church-dashboard=user&&page=member&tab=view_invoice&member_id=<?php echo $_REQUEST['member_id'];?>&idtest=<?php echo esc_attr($retrieved_data->id);?>&invoice_type=pledges"><i class="fa fa-eye"></i><?php _e('View Invoice', 'church_mgt' ) ;?></a></li>
																	</ul> 
																</li>
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
								</div><!-- TABLE RESPONSIVE DIV END-->
								<?php
							}
							else
							{
								$page= 'pledges';
								$access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page);
								if($access['add']=='1')
								{
									?>
									<div class="no_data_list_div"> 
										<a href="<?php echo home_url().'?church-dashboard=user&page=pledges&tab=addpledges';?>">
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
						?>
						<?php 
                        if ($active_tab1 == "familylist") 
						{
                            ?>
							<script type="text/javascript">
								$(document).ready(function()
								{
									jQuery('#family_memmber_list').DataTable({
										//"responsive":true,
										language:<?php echo MJ_cmgt_datatable_multi_language();?>,
										"order": [[ 0, "desc" ]],
										dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-6"i><"col-sm-5"p>>',
										"aoColumns":[
														{"bSortable": false},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true}]
										});
										$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
										$('.dataTables_filter').addClass('search_btn_view_page');
								} );
							</script>
							<?php 
							$user_meta =get_user_meta($_REQUEST['member_id'], 'family_id', true);
							if(!empty($user_meta))
							{
								?>
								<div class="table-responsive cmgt_view_pt_0">
									<form name="frm-example" action="" method="post">
										<table id="family_memmber_list" class="display" cellspacing="0" width="100%">
											<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
												<tr>
													<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Family Member Name & Email', 'church_mgt' ) ;?></th>
													<th class="dob_text_transform"> <?php  _e( 'Date of Birth', 'church_mgt' ) ;?></th>
													<th> <?php  _e( 'Gender', 'church_mgt' ) ;?></th>
													<th> <?php  _e( 'Relation', 'church_mgt' ) ;?></th>
													<th> <?php  _e( 'City', 'church_mgt' ) ;?></th>
													<th> <?php  _e( 'Mobile Number', 'church_mgt' ) ;?></th>
												</tr>
											</thead>
											<tbody>
											<?php 
											if($user_meta)
											{
												foreach($user_meta as $parentsdata)
												{
													$parent=get_userdata($parentsdata);
													?>
													<tr>
														<td class="user_image width_5_per">
															<?php 
																if($parentsdata)
																{
																	$umetadata=MJ_cmgt_get_user_image($parentsdata);
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
															<a class="color_black" href="#"><?php echo $parent->first_name." ".$parent->last_name;?></a>
															<br>
															<!-- href="?page=cmgt-family&tab=addfamily&action=edit&family_id=<?php echo $user_info->ID;?>" -->
															<label class="email_color"><?php echo esc_html  ($parent->user_email);?></label>
														</td>
														
														<td class="birth_date width_15_per"><label><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($parent->birth_date)));?> </label></td>
														
														<td class="gender width_10_per">
															<?php echo _e($parent->gender,'church_mgt');?>
														</td>
														<td class="relation width_10_per">
															<?php echo _e($parent->relation,'church_mgt');?> 
														</td>
														<td class="city width_15_per">
															<?php echo $parent->city;?> 
														</td>
														<td class="mobile width_15_per">
															<?php echo '+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.esc_html  ($parent->mobile_number);?> 
														</td>
													</tr>
													<?php
												} 
											}?>
										</tbody>
										</table>
									</form>
								</div>
								<?php
							}
							else
							{
								$page= 'familymember';
								$access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page);
								if($access['add']=='1')
								{
									?>
									<div class="no_data_list_div"> 
										<a href="<?php echo home_url().'?church-dashboard=user&page=family&tab=addfamily';?>">
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
						?>
						<?php 
                        if($active_tab1 == "attendancelist") 
						{
                            ?>
							<script type="text/javascript">
								$(document).ready(function()
								{
									jQuery('#attendence_list').DataTable({
										//"responsive":true,
										language:<?php echo MJ_cmgt_datatable_multi_language();?>,
										"order": [[ 0, "desc" ]],
										dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-6"i><"col-sm-5"p>>',
										"aoColumns":[
													  {"bSortable": false},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": false}]
										});
										$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
										$('.dataTables_filter').addClass('search_btn_view_page');
								} );
							</script>
							<?php
							$attendencedata=$obj_attend->MJ_cmgt_get_All_member_attendence($member_id);
							if(!empty($attendencedata))
							{
								?>
								<input type="hidden" value="">
								<div class="table-responsive cmgt_view_pt_0"><!-- TABLE RESPONSIVE DIV START-->
									<table id="attendence_list" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
											<tr>
												<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Attendance Type', 'church_mgt' ) ;?></th>
												<th> <?php  _e( 'Activity Name', 'church_mgt' ) ;?></th>
												<th> <?php  _e( 'Attendance Date', 'church_mgt' ) ;?></th>
												<th> <?php  _e( 'Attendance Status', 'church_mgt' ) ;?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
												<th><?php  _e( 'Attendance Type', 'church_mgt' ) ;?></th>
												<th> <?php  _e( 'Activity Name', 'church_mgt' ) ;?></th>
												<th> <?php  _e( 'Attendance Date', 'church_mgt' ) ;?></th>
												<th> <?php  _e( 'Attendance Status', 'church_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
											<?php 
											$i=0;
											if(!empty($attendencedata))
											{
												foreach ($attendencedata as $retrieved_data)
												{
													//$ministry_id=$retrieved_data->activity_id;
													$id = $retrieved_data->activity_id;
													$obj_ministry=new Cmgtministry;
													$ministrydata=$obj_ministry->MJ_cmgt_get_single_ministry($id);

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
														<td class="user_image width_50px profile_image_prescription"><p class="remainder_title_pr Bold viewpriscription prescription_tag <?php echo $color_class; ?>">	
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/attendance.png"?>" alt="" class="massage_image center"></p>
														</td>
														<td class="role_name"><lable><?php echo esc_attr(ucfirst($retrieved_data->role_name));?> </lable></td>
														
														<?php
														if(($retrieved_data->role_name) == "ministry")
														{
															?>
																<td class="activity name "><lable><?php echo esc_attr($ministrydata->ministry_name);?> </lable></td>
															<?php
														}else
														{
															?>
																<td class="activity name "><lable><?php if(!empty(MJ_cmgt_get_activity_name($id))){ echo MJ_cmgt_get_activity_name($id);}else{ echo "N/A";}?> </lable></td>
															<?php
														}

														?>
														<td class="stat_date"><lable><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->attendence_date)));?> </lable></td>
														<td class="status"><lable class="<?php echo esc_attr($retrieved_data->status);?>"><?php echo _e($retrieved_data->status,'church_mgt');?> </lable></td>
													</tr>
													<?php 
													$i++;
												} 
											}?>
										</tbody>
									</table>
								</div><!-- TABLE RESPONSIVE DIV END-->
								<?php
							}
							else
							{
								$page= 'attendance';
								$access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page);
								if($access['add']=='1')
								{
									?>
									<div class="no_data_list_div"> 
										<a href="<?php echo home_url().'?church-dashboard=user&page=attendance';?>">
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
						?>
					</div>
				</section>
		</div>
	</div>		
	<?php
}
?>
