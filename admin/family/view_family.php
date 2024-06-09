<?php 
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
											<label class="view_user_name_label"><?php echo esc_html($user_info->first_name." $user_info->middle_name ".$user_info->last_name);?> </label>
											<div class="view_user_edit_btn ">
												<a class="color_white margin_left_2px" href="?page=cmgt-family&tab=addfamily&action=edit&family_id=<?php echo $user_info->ID;?>">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/pencil_icon.png" ?>" alt="">
												</a>
											</div>
										</div>
										<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
											<div class="view_user_phone float_left_width_100">
												<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/phone_figma.png" ?>" alt="">&nbsp;
												<lable class="cmgt_phone_color">
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
									<label class="view_page_content_labels"><?php echo _e($user_info->relation , 'church_mgt');?></label>
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
													<label class="guardian_labels view_page_content_labels"> <?php _e('Mobile Number','church_mgt');?> </label>: <label class="">
														<?php echo get_user_meta($family_id, 'phonecode', true).' '.get_user_meta($family_id, 'mobile_number', true);?>
													</label>
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