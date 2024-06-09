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
$obj_ministry=new Cmgtministry;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'ministrylist');
if(isset($_POST))
{ 
//--------- GROUP ADD MEMBER ---------//
	if(isset($_POST['id']))
	{
		$result=$obj_group->MJ_cmgt_add_group_members($_POST['id'],$_POST['group_id']);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&&page=ministry&tab=ministrylist&message=4');
		}
	}
}
if(isset($_POST['save_group']))
	{
		if(isset($_FILES['ministry_image']) && !empty($_FILES['ministry_image']) && $_FILES['ministry_image']['size'] !=0)
		{
			if($_FILES['ministry_image']['size'] > 0)
				$member_image=MJ_cmgt_load_documets($_FILES['ministry_image'],'ministry_image','pimg');
				$upload_dir_url=MJ_cmgt_upload_url_path('church_assets'); 
				$member_image_url = $upload_dir_url.''.$member_image;
		}
		else
		{
			if(isset($_REQUEST['hidden_upload_user_avatar_image']))
				$member_image= sanitize_text_field($_REQUEST['hidden_upload_user_avatar_image']);
				$member_image_url=$member_image;
		}
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			//--------- EDIT  MEMBER ---------//
			$result=$obj_group->gmgt_add_group(sanitize_text_field($_POST));
			$returnans=$obj_group->MJ_cmgt_update_groupimage( $_REQUEST['group_id'],$member_image_url);
			if($returnans)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=ministry&tab=ministrylist&message=2');
			}
			elseif($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=ministry&tab=ministrylist&message=2');
			}
		}
		else
		{
			$result=$obj_group->gmgt_add_group($_POST,$member_image_url);
			
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=ministry&tab=ministrylist&message=1');
			}
		
		}
	}
	//---------  DELETE MEMBER ---------//
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$obj_group->MJ_cmgt_delete_group($_REQUEST['group_id']);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&&page=ministry&tab=ministrylist&message=3');
		}
	}
if(isset($_REQUEST['message']))
{
	$message = sanitize_text_field($_REQUEST['message']);
	if($message == 1)
	{?>
		<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				esc_html_e('Record inserted successfully','church_mgt');
			?></p>
		</div>
		<?php 
	}
	elseif($message == 2)
	{?>
		<div id="message" class="updated below-h2 "><p><?php
			esc_html_e("Record updated successfully.",'church_mgt');
			?></p>
		</div>
	<?php 
	}
	elseif($message == 3) 
	{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			esc_html_e('Record deleted successfully','church_mgt');
		?></div></p><?php
	}
	elseif($message == 4) 
	{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			esc_html_e('Group Members Added successfully','church_mgt');
		?></div></p><?php
	}
}
?>
	<script type="text/javascript">
		$(document).ready(function() 
		{
			jQuery('#ministry_list').DataTable({
				// "responsive":true,
				language:<?php echo MJ_cmgt_datatable_multi_language();?>,
				"order": [[ 0, "asc" ]],
				"sSearch": "<i class='fa fa-search'></i>",
				"dom": 'lifrtp',
				"aoColumns":[
								{"bSortable": false},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": false}]
			});
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			$('#group_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		} );
	</script>
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
<div class="panel-white"><!--PANEL WHITE DIV START-->
	<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT DIV STRAT-->
		<?php if($active_tab == 'ministrylist')
		{ 
			$ministrydata=$obj_ministry->MJ_cmgt_get_all_ministry();
			if(!empty($ministrydata))
			{
				?>	
				<div class="padding_left_15px"><!--PANEL BODY DIV START-->
					<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
						<table id="ministry_list" class="display" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
								<tr>
									<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Ministry Name', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Total Member', 'church_mgt' ) ;?></th>
									<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Ministry Name', 'church_mgt' ) ;?></th>	
									<th><?php  _e( 'Total Member', 'church_mgt' ) ;?></th>
									<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
								</tr>
							</tfoot>
							<tbody>
								<?php 
								if(!empty($ministrydata))
								{
									foreach ($ministrydata as $retrieved_data)
									{
									?>
									<tr>
										<td class="user_image width_5px"><?php 
											if($retrieved_data->ministry_image == '')
											{
												echo '<img src='.esc_url(get_option( 'cmgt_ministry_logo' )).' height="50px" width="50px" class="img-circle" />';
											}
											else
												echo '<img src='.esc_url($retrieved_data->ministry_image).' height="50px" width="50px" class="img-circle"/>';
										?></td>	
										<td class="name"><a class="color_black view_group_member" group_type="ministry" id="<?php echo esc_attr($retrieved_data->id);?>" href="#"><?php echo esc_attr($retrieved_data->ministry_name);?> </a></td>
										<td class="allmembers"><?php echo $obj_ministry->MJ_cmgt_count_ministry_members($retrieved_data->id);?> </td>
										<td class="action cmgt_pr_0px"> 
											<div class="cmgt-user-dropdown mt-2">
												<ul class="">
													<!-- BEGIN USER LOGIN DROPDOWN -->
													<li class="">
														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
															<img class="more_img_mr" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>">
														</a>						
														<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
															<li><a href="#" class="dropdown-item view_group_member" group_type="ministry" id="<?php echo esc_attr($retrieved_data->id);?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
														</ul>

													</li>
													<!-- END USER LOGIN DROPDOWN -->
												</ul>
											</div>
										</td>
									</tr>
								<?php } 
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
						<a href="<?php echo home_url().'?church-dashboard=user&page=ministry&tab=addministry';?>">
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