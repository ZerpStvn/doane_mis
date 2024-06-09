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
$curr_user_id=get_current_user_id();
$obj_church = new Church_management(get_current_user_id());
$obj_service=new Cmgtservice;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'servicelist');
?>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list">
			</div>	
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
	<div class="panel-white padding_frontendlist_body"><!--PANEL WHITE DIV START-->
		<div class=""><!--TAB CONTENT DIV STRAT-->
			<?php if($active_tab == 'servicelist')
			{ 
				$servicedata=$obj_service->MJ_cmgt_get_all_services();
				if(!empty($servicedata))
				{
					?>	
					<script type="text/javascript">
						$(document).ready(function()
						{
							jQuery('#service_list').DataTable({
								// "responsive":true,
								language:<?php echo MJ_cmgt_datatable_multi_language();?>,
								"order": [[ 0, "asc" ]],
								"sSearch": "<i class='fa fa-search'></i>",
								"dom": 'lifrtp',
								"aoColumns":[
											{"bSortable": false},
											{"bSortable": true},
											{"bSortable": true},
											{"bSortable": true},
											{"bSortable": true},
											{"bSortable": false}]
								});
								$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
						} );
					</script>
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="service_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Service Title', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Service Type', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Start Date To End Date', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Start Time To End Time', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Service Title', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Service Type', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Start Date To End Date', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Start Time To End Time', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									$i=0;
									if(!empty($servicedata))
									{
										foreach ($servicedata as $retrieved_data)
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
											<td class="user_image width_50px profile_image_prescription padding_left_0">
												<p class="padding_15px prescription_tag <?php echo $color_class; ?>">	
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/services-white.png"?>" alt="" class="massage_image center">
												</p>
											</td>
											<td class="name"><a class="color_black view_Service" id="<?php echo $retrieved_data->id ?>" href="#"><?php echo $retrieved_data->service_title;?></a> </td>

											<td class="service_type width_25_per"><?php echo esc_attr(get_the_title($retrieved_data->service_type_id));?> </td>

											<td class="start_date width_22_per"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->start_date)));?> <?php _e('To','church_mgt');?> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->end_date)));?> </td>

											<td class="width_15_per"><?php echo esc_attr($retrieved_data->start_time);?> <?php _e('To','church_mgt');?> <?php echo esc_attr($retrieved_data->end_time);?> </td>

											<td class="action cmgt_pr_0px"> 
												<div class="cmgt-user-dropdown mt-2">
													<ul class="">
														<!-- BEGIN USER LOGIN DROPDOWN -->
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
															</a>
															<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																<li><a href="" id="<?php echo $retrieved_data->id ?>"  class="dropdown-item view_Service"><i class="fa fa-eye"></i> <?php esc_html_e('View', 'church_mgt' ) ;?></a></li>
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
						</div><!--TABLE RESPONSIVE DIV END-->
					</div><!--PANEL BODY DIV END-->
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
			 ?>
		</div><!--TAB CONTENT DIV END-->
	</div><!--PANEL WHITE DIV END-->
<?php ?>