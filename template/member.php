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
$obj_member=new Cmgtmember;
$role="member";
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'memberlist');
//-------- SAVE MEMBER DATA -------------//
if(isset($_POST['save_member']))		
{
	
	if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
	{
		
		if($_FILES['upload_user_avatar_image']['size'] > 0)
					$member_image=MJ_cmgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
					$upload_dir_url=MJ_cmgt_upload_url_path('church_assets'); 
					$member_image_url = $upload_dir_url.''.$member_image;
	}
	else{
		
		if(isset($_REQUEST['hidden_upload_user_avatar_image']))
						$member_image= sanitize_text_field($_REQUEST['hidden_upload_user_avatar_image']);
					$member_image_url=$member_image;
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
			
		$result=$obj_member->gmgt_add_user($_POST);	
			$returnans=update_user_meta( $result,'gmgt_user_avatar',$member_image_url);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&&page=member&tab=memberlist&message=2');
		}
	}
	else
	{
		if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {

			$result=$obj_member->gmgt_add_user(sanitize_text_field($_POST));
				$returnans=update_user_meta( $result,'gmgt_user_avatar',$member_image_url);
			if($result>0)
			{
				wp_redirect ( home_url() . '?church-dashboard=user&&page=member&tab=memberlist&message=2');
			}
		}
		else
		{?>
			<div id="message" class="updated below-h2">
				<p><?php esc_html_e('Username Or Emailid All Ready Exist.','church_mgt');?></p> 
			</div>
					
	<?php }
	}
}
//-------- DELETE MEMBER DATA ------------//
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	$result=$obj_member->MJ_cmgt_delete_usedata($_REQUEST['member_id']);
	if($result)
	{
		wp_redirect ( home_url().'?church-dashboard=user&&page=member&tab=memberlist&message=3');
	}
}
if(isset($_REQUEST['message']))
{
	$message =sanitize_text_field($_REQUEST['message']);
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
}
?>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('.date_field').datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			onChangeMonthYear: function(year, month, inst) {
				$(this).val(month + "/" + year);
			}
						
		}); 
		
		jQuery('#members_list').DataTable({
			//  "responsive": true,
			language:<?php echo MJ_cmgt_datatable_multi_language();?>,
			"sSearch": "<i class='fa fa-search'></i>",
			"dom": 'lifrtp',
			"order": [[ 1, "asc" ]],
			"aoColumns":[
							{"bSortable": false},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": false}
							
						]
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
<?php 
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'memberlist');
?>
<div class="panel-white panel-white"><!--PANEL WHITE DIV START-->
	<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT DIV STRAT-->
		<?php 
		if($active_tab == 'memberlist')
		{
			$current_user_id=get_current_user_id();
			if($obj_church->role == 'member')
			{
				$membersdata[]=get_user_by( 'id', $current_user_id );
				
			}
			elseif($obj_church->role == 'family_member')
			{
				$parent_member_id = get_user_meta( $current_user_id, 'member_id', true ); 
				
				$membersdata[]=get_user_by( 'id', $parent_member_id );
			}
			else
			{
				$get_members = array('role' => 'member');
				$membersdata=get_users($get_members);
			}
			if(!empty($membersdata))
			{
				?>
				<div class="tab-pane <?php if($active_tab == 'memberlist') echo "active";?>" >
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="members_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Member Name & Email', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Member Id', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Volunteer Member', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Mobile No.', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Member Name & Email', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Member Id', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Volunteer Member', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Mobile No.', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									if(!empty($membersdata))
									{
										foreach ($membersdata as $retrieved_data)
										{
											?>
											<tr>
												<td class="user_image width_5px"><?php $uid=$retrieved_data->ID;
													$userimage=get_user_meta($uid, 'cmgt_user_avatar', true);
													if(empty($userimage))
													{
														echo '<img src='.esc_url(get_option( 'cmgt_member_thumb' )).' height="50px" width="50px" class="img-circle" />';
													}
													else
													{
														echo '<img src='.esc_url($userimage).' height="50px" width="50px" class="img-circle"/>';
													}
													?>
												</td>
												<td class="name">
													<a class="color_black" href="?church-dashboard=user&&page=member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>"><?php echo esc_html($retrieved_data->display_name);?></a>
													<br>
													<label class="email_color"><?php echo esc_html($retrieved_data->user_email);?></label>	
												</td>
												<td class="memberid"><?php echo esc_attr($retrieved_data->member_id);?> </td>
												<td class="volunteer"><?php if($retrieved_data->volunteer=='yes') echo __('Yes','church_mgt'); else echo __('No','church_mgt'); ?> </td>

												<td class="mobile"><?php echo esc_attr($retrieved_data->phonecode).' '.esc_attr($retrieved_data->mobile);?></td>
										
												<td class="action cmgt_pr_0px"> 
													
													<?php 
														$role=$obj_church->role;  
														$family_id=get_current_user_id(); 
														$membersdata=get_userdata($family_id);
														$member_id=$membersdata->member_id;
													if( get_user_meta($retrieved_data->ID, 'cmgt_hash', true))
													{ ?>
													<a  href="?page=cmgt-member&action=active_member&member_id=<?php echo $retrieved_data->ID?>" class="btn btn-default" > <?php _e('Active', 'church_mgt');?></a>
													<?php 
													} ?>
													<div class="cmgt-user-dropdown mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																<?php if($obj_church->role == 'family_member' && $retrieved_data->ID==$member_id){ ?>
																	<li><a href="?church-dashboard=user&&page=member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="dropdown-item "><i class="fa fa-eye"></i> <?php esc_html_e('View', 'church_mgt' ) ;?></a></li>
																<?php } 
																else 
																{ 
																	if($obj_church->role == 'member' && $retrieved_data->ID==$curr_user_id)
																	{?>
																		<li><a href="?church-dashboard=user&&page=member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="dropdown-item "><i class="fa fa-eye"></i> <?php esc_html_e('View', 'church_mgt' ) ;?></a></li>
																		<?php
																	}
																}
																if($obj_church->role == 'accountant')
																{?>
																	<li><a href="?church-dashboard=user&&page=member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="dropdown-item "><i class="fa fa-eye"></i> <?php esc_html_e('View', 'church_mgt' ) ;?></a></li>
																	<li><a mem_id="<?php echo $retrieved_data->ID; ?>" class="dropdown-item view_gift_list cursor_poi_css"><i class="fa fa-gift"></i> <?php esc_html_e('Gifts', 'church_mgt' ) ;?></a></li>
																<?php } ?>
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
				</div>
				<!--Member Step one information-->
				<?php 
			}
			else
			{
				if($user_access['add']=='1')
				{
					?>
					<div class="no_data_list_div"> 
						<a href="<?php echo home_url().'?church-dashboard=user&page=member&tab=addmember';?>">
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
			<?php ?>	
		<!--Member Step two information-->
		<?php if($active_tab == 'viewmember')
		{?>
			<div class="tab-pane <?php if($active_tab == 'viewmember') echo "active";?>" >
				<?php require_once CMS_PLUGIN_DIR. '/template/view_member.php';?>
			</div>
		<?php
		}?>
		<?php if($active_tab == 'view_invoice')
		{?>
			<div class="tab-pane <?php if($active_tab == 'view_invoice') echo "active";?>" >
				<?php require_once CMS_PLUGIN_DIR. '/template/view_invoice.php';?>
			</div>
		<?php
		}?>
	</div><!--TAB CONTENT DIV END-->
</div><!--PANEL WHITE DIV END-->