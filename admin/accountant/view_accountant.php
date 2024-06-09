<?php 
	$accountant_id=0;
	if(isset($_REQUEST['accountant_id']))
	{
		$accountant_id=$_REQUEST['accountant_id'];
		$edit=0;					
		$edit=1;
		$user_info = get_userdata($accountant_id);
        // var_dump($user_info);
        // die;
	}		
?>
<?php
if(isset ( $_REQUEST ['tab'] ) && $_REQUEST['tab']=='viewaccountant')
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
									<!-- <img class="user_view_profile_image" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/995913.png"?>"> -->
									<img class="user_view_profile_image" src="<?php echo get_option( 'cmgt_accountant_logo' )?>">
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
												<a class="color_white margin_left_2px" href="?page=cmgt-accountant&tab=add_accountant&action=edit&accountant_id=<?php echo $user_info->ID;?>">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/pencil_icon.png" ?>" alt="">
												</a>
											</div>
										</div>
										<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
											<div class="view_user_phone float_left_width_100">
												<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/phone_figma.png" ?>" alt="">&nbsp;
												<lable class="cmgt_phone_color">
													<?php echo esc_attr($user_info->phonecode).' '.get_user_meta($accountant_id, 'mobile', true); ?>
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row margin_right_10px">
									<div class="col-xl-12 col-md-12 col-sm-12">
										<div class="view_top2">
											<div class="view_user_doctor_label">
											<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;<lable><?php echo  $user_info->address.', '.get_user_meta($accountant_id, 'city_name', true) ?> </label>
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
							// $user_meta =get_user_meta($_REQUEST['family_id'], 'member_id', true); 
							// var_dump($user_meta);
							// die;
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
                                <!-- <div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('City', 'church_mgt'); ?> </label><br/>
									<label class="view_page_content_labels"><?php echo esc_html($user_info->city_name);?></label>
								</div> -->
							</div>
							<div class="row margin_top_20px">
							    <!-- <div class="col-xl-12 col-md-12 col-sm-12"> -->
									<div class="col-xl-6 col-md-6 col-sm-6 margin_top_20px">
										<div class="guardian_div">
											<label class="view_page_label_heading"> <?php esc_html_e('Address Information', 'church_mgt'); ?> </label>
											<div class="row">
												<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Address','church_mgt'); ?> </label>: <label class=""><?php echo get_user_meta($accountant_id, 'address', true);?></label>
												</div>
												<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php _e('City','church_mgt');?> </label>: <label class=""><?php echo get_user_meta($accountant_id, 'city_name', true);?></label>
												</div>
											</div>
										</div>	
									</div>
									<div class="col-xl-6 col-md-6 col-sm-6 margin_top_20px">
										<div class="guardian_div">
											<label class="view_page_label_heading"> <?php esc_html_e('Contact Information', 'church_mgt'); ?> </label>
											<div class="row">
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Phone','church_mgt'); ?> </label>: <label class="">
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
													<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Fax','church_mgt'); ?> </label>: <label class="">
														
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
													<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Skyp Id','church_mgt'); ?> </label>: <label class="">
														
														<?php 
														if(!empty($user_info->skyp_id)){
															echo esc_html($user_info->skyp_id);
														}else{
															echo esc_html( __( 'N/A', 'church_mgt' ) );
														}
														?>
													</label>
												</div>
												
											</div>
										</div>	
									</div>
								<!-- </div> -->
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