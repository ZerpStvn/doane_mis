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
		// die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				// die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				// die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				// die;
			}	
		} 
	}
}
$curr_user_id=get_current_user_id();
$obj_church = new Church_management(get_current_user_id());
$obj_member=new Cmgtmember;
$role="accountant";
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'accountantlist');
?>
<script type="text/javascript">
	$(document).ready(function()
	{
		jQuery('#staff_list').DataTable({
			//  "responsive": true,
			 language:<?php echo MJ_cmgt_datatable_multi_language();?>,
			 "order": [[ 1, "asc" ]],
			 "sSearch": "<i class='fa fa-search'></i>",
			 "dom": 'lifrtp',
			 "aoColumns":[
						  {"bSortable": false},
						  {"bSortable": true},
						  {"bSortable": true},
						  {"bSortable": true},
						  {"bSortable": true},
						  {"bSortable": true},
						   <?php if($obj_church->role == 'accountant')?>
						  {"bSortable": false}]
			});
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			$('#member_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('#memberform2_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('#group_id').multiselect();
	} );
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list">
			</div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->

<div class="panel-white panel-white"><!--PANEL WHITE DIV START-->
	<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT DIV START-->
		<?php 
		if($active_tab == 'accountantlist')
		{
			$current_user_id=get_current_user_id();
			if($obj_church->role == 'accountant')
			{
				$staffdata[]=get_user_by( 'id', $current_user_id );
				
			}
			else
			{
				$get_staff = array('role' => 'accountant');
				$staffdata=get_users($get_staff);
			}
			
			if(!empty($staffdata))
			{
				?>
				<div class="tab-pane <?php if($active_tab == 'accountantlist') echo "active";?>" >
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="staff_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
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
										<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Accountant Name', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Accountant Email', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Gender', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Mobile No.', 'church_mgt' ) ;?></th>
										<th class="dob_text_transform"> <?php _e( 'Date of Birth', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
								</tfoot>
								<tbody>
									<?php 
									if(!empty($staffdata))
									{
										foreach ($staffdata as $retrieved_data)
										{
											?>
											<tr>
												<td class="user_image width_5px"><?php $uid=$retrieved_data->ID;
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
												<td class="name"><a class="color_black" href="?church-dashboard=user&&page=accountant&tab=viewaccountant&action=view&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>"><?php echo esc_attr($retrieved_data->display_name);?></a></td>
												<td class="email"><?php echo esc_attr($retrieved_data->user_email);?> </td>
												<td class="gender width_10_per">
													<?php echo ucfirst(_e($retrieved_data->gender,"church_mgt"));?> 
												</td>
												<td class="mobile width_15_per">
													<?php echo esc_attr($retrieved_data->phonecode).' '.esc_attr($retrieved_data->mobile);?> 
												</td>
												<td class="birth_date width_15_per"><label><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->birth_date)));?> </label></td>
												<td class="action cmgt_pr_0px"> 
													<div class="cmgt-user-dropdown mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																	<li><a href="?church-dashboard=user&&page=accountant&tab=viewaccountant&action=view&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>" class="dropdown-item "><i class="fa fa-eye"></i> <?php esc_html_e('View', 'church_mgt' ) ;?></a></li>
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
						</div><!--TABLE RESPONSIVE DIV END-->
					</div><!--PANEL BODY DIV END-->
				</div><!--TAB CONTENT DIV END-->
				<!--Member Step one information-->
				<?php 
			}
			else
			{
				if($user_access['add']=='1')
				{
					?>
					<div class="no_data_list_div"> 
						<a href="<?php echo home_url().'#';?>">
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
		$accountant_id=0;
		if(isset($_REQUEST['accountant_id']))
		{
			$accountant_id=$_REQUEST['accountant_id'];
			$edit=0;					
			$edit=1;
			$user_info = get_userdata($accountant_id);
		}		
			?>
			<?php
			if(isset ( $_REQUEST ['tab'] ) && $_REQUEST['tab']=='viewaccountant')
			{
				$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
				?>
				<div class="padding_left_15px view_patient_main"><!-- START PANEL BODY DIV-->
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
														<label class="view_user_name_label"><?php echo esc_html(chunk_split(($user_info->first_name." ".$user_info->last_name),17));?> </label>
														<div class="view_user_edit_btn ">
																	
														</div>
													</div>
													<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
														<div class="view_user_phone float_left_width_100">
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/phone_figma.png" ?>" alt="">&nbsp;
															<lable>
																<?php echo esc_attr($user_info->phonecode).' '.get_user_meta($accountant_id, 'mobile', true);?>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
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
						<!-- <section id="body_area" class="">
							<div class="row">
								<div class="col-xl-12 col-md-12 col-sm-12 cmgt_padding_0px">
									<ul class="nav border_bottom_for_view_page nav-tabs panel_tabs margin_left_1per cmgt-view-page-tab" role="tablist">
										<li class="<?php if($active_tab1=='general'){?>active<?php }?>">			
											<a href="?church-dashboard=user&&page=accountant&tab=viewaccountant&tab1=general&action=view&accountant_id=<?php echo $_REQUEST['accountant_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'general' ? 'active' : ''; ?>">
											<?php esc_html_e('General', 'church_mgt'); ?></a> 
										</li>
									</ul>
								</div>
							</div>
						</section> 	 -->
							<section id="body_content_area" class="margin_top_7per">

								<div class="panel-body1"><!-- START PANEL BODY DIV-->
									<?php 
									if($active_tab1 == "general")
									{
										?>
										<div class="row">
											<div class="col-xl-4 col-md-4 col-sm-12 margin_bottom_10_res">
												<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'church_mgt'); ?> </label><br/>
												<label class="view_page_content_labels"><?php echo esc_html($user_info->user_email);?></label>
											</div>
											<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
												<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'church_mgt'); ?> </label><br/>
												<label class="view_page_content_labels"><?php echo esc_html(date(MJ_cmgt_date_formate(),strtotime($user_info->birth_date)));?></label>
											</div>
											<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
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
											<!-- <div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
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
																<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Phone','church_mgt'); ?> </label>: <label class=""><?php if(!empty($user_info->phone)){ echo esc_html($user_info->phone); }else{ echo "N/A"; } ?></label>
															</div>
															<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
																<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Fax','church_mgt'); ?> </label>: <label class=""><?php if(!empty($user_info->fax_number)){ echo esc_html($user_info->fax_number); }else{ echo "N/A"; } ?></label>
															</div>
															<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
																<label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Skyp Id','church_mgt'); ?> </label>: <label class=""><?php if(!empty($user_info->skyp_id)){ echo esc_html($user_info->skyp_id); }else{ echo "N/A"; } ?></label>
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
	<?php ?>
		<!--Member Step two information-->
		<?php if($active_tab == 'viewmember')
		{?>
			<div class="tab-pane <?php if($active_tab == 'viewmember') echo "active";?>" >
			<?php require_once CMS_PLUGIN_DIR. '/template/view_member.php';?>
		</div>
		<?php 
		}?>
	</div><!--TAB CONTENT DIV END-->
</div><!--PANEL WHITE DIV END-->
<?php ?>