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
$obj_checkin=new Cmgtcehckin;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'roomlist');
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'checkout')
{
	//-------- CHECK OUT ------------//
	if(isset($_REQUEST['check_id']))
		$result=$obj_checkin->MJ_cmgt_room_checkout(sanitize_text_field($_REQUEST['check_id']));
	if($result)
	{
		wp_redirect ( home_url().'?church-dashboard=user&&page=check-in&tab=roomlist&message=4');
	}
}
if(isset($_POST['save_checkin']))
{
	$nonce = sanitize_text_field($_POST['_wpnonce']);
	if (wp_verify_nonce( $nonce, 'save_checkin_nonce' ) )
	{
		//--------ADD ROOM  CHECK IN ------------//
	$result=$obj_checkin->MJ_cmgt_add_room_checkin($_POST);
	if($result)
	{
		wp_redirect ( home_url().'?church-dashboard=user&&page=check-in&tab=roomlist&message=1');
	}
	}
}
if(isset($_REQUEST['message']))
{
	$message = sanitize_text_field($_REQUEST['message']);
	if($message == 1)
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e('Record inserted successfully','church_mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php 
	}
	elseif($message == 2)
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e('Record updated successfully','church_mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
			<?php 
	}
	elseif($message == 3) 
	{?>
		
	<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
		<?php
		esc_html_e('Record deleted successfully','church_mgt');
		?>
		<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
	</div><?php
	}
	elseif($message == 4) 
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e('Check-out successfully','church_mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php
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
<script type="text/javascript">
	$(document).ready(function() 
	{
		
		$('#checkin_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$(".display-members").select2();
		
		$("#checkin_date").datepicker({
		dateFormat: "yy-mm-dd",
		minDate:0,
		onSelect: function (selected) {
			var dt = new Date(selected);
			dt.setDate(dt.getDate() + 0);
			$("#checkout_date").datepicker("option", "minDate", dt);
		}
		});
		$("#checkout_date").datepicker({
			dateFormat: "yy-mm-dd",
			onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() - 0);
				$("#checkin_date").datepicker("option", "maxDate", dt);
			}
		});	
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
<div class="panel-white padding_frontendlist_body"><!--PANEL WHITE DIV START-->
	<div class=""><!--TAB CONTENT DIV STRAT-->
		<?php 
		if($active_tab == 'roomlist')
		{ 
			$own_data=$user_access['own_data'];
			$volunteer=MJ_cmgt_check_volunteer($curr_user_id);
			$roomdata=$obj_checkin->MJ_cmgt_get_all_room();
			if($obj_church->role == 'accountant')
			{
				$roomdata=$obj_checkin->MJ_cmgt_get_all_room();
			}
			else
			{
				if($own_data == '1')
				{ 
					$roomdata=$obj_checkin->MJ_cmgt_get_members_room($user_id);
				}
				else
				{
					$roomdata=$obj_checkin->MJ_cmgt_get_all_room();
				}
			}
				
			if(!empty($roomdata))
			{
				?>	
				<script type="text/javascript">
					$(document).ready(function() 
					{
						jQuery('#room_list').DataTable({
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
											{"bSortable": false} ]
								});
								
						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
					} );
				</script>
				<div class="padding_left_15px"><!--PANEL BODY DIV START-->
					<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
						<table id="room_list" class="display" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
								<tr>
									<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Room Title', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Status', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Capacity', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Demographics', 'church_mgt' ) ;?></th>
									<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Room Title', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Status', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Capacity', 'church_mgt' ) ;?></th>
									<th><?php  _e( 'Demographics', 'church_mgt' ) ;?></th>
									<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
								</tr>
							</tfoot>
							<tbody>
								<?php 
								$i = 0;
								if(!empty($roomdata))
								{
									foreach ($roomdata as $retrieved_data)
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
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Checkin.png"?>" alt="" class="massage_image center">
												</p>
											</td>

											<td class="name width_25_per"><a class="color_black view_checkins" href="#" id="<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_attr(ucfirst($retrieved_data->room_title));?></a> </td>

											<td class="width_15_per"><?php $room_status=$obj_checkin->MJ_cmgt_get_room_reservation($retrieved_data->id);
											if($room_status)
												echo '<span class="red_color">'.__("Occupied","church_mgt").'</span>';
											else
												echo '<span class="green_color">'.__("Available","church_mgt").'</span>';
											?> 
											</td>

											<td class="allmembers width_10_per"><?php echo esc_attr($retrieved_data->capacity);?> </td>
											<?php
												if(!empty($retrieved_data->demographics))
												{
													$demographics=$retrieved_data->demographics;
												}else{
													$demographics='N/A';
												}
											?>
											<td class="width_25_per font_style_capitalize"><?php echo _e(rtrim($demographics, ','),'church_mgt');?> </td>
		
											<td class="action cmgt_pr_0px">
												<div class="cmgt-user-dropdown mt-2">
													<ul class="">
														<!-- BEGIN USER LOGIN DROPDOWN -->
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
															</a>
															<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																<li class="check_in"><a href="#" class="dropdown-item  view_checkins" id="<?php echo esc_attr($retrieved_data->id);?>"><i class="fa fa-eye"></i><?php _e('View Check-in', 'church_mgt' ) ;?></a></li>
															</ul>
														</li><!-- END USER LOGIN DROPDOWN -->
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
						<a href="<?php echo home_url().'?church-dashboard=user&page=checkin&tab=addroom';?>">
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
		if($active_tab == 'checkin')
		{
			$room_id=0;
			if(isset($_REQUEST['room_id']))
				$room_id=$_REQUEST['room_id'];	
			$edit=0;
			?>
			<div class="panel-body"><!--PANEL BODY DIV START-->
				<form name="checkin_form" action="" method="post" class="form-horizontal" id="checkin_form"><!--CHECK-IN FORM START-->
					<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
					<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
					<input type="hidden" name="room_id" value="<?php echo esc_attr($room_id);?>"  />
					<div class="form-body user_form">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="room_title" readonly class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if(isset($room_id)){ ?>value="<?php echo MJ_get_room_name(esc_attr($room_id)); }?>" name="room_title">
										<label class="" for="room_title"><?php _e('Room Title','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>	
							</div>
							<div class="col-md-6 input cmgt_display">
								<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>

								<select id="member_list" class="form-control line_height_30px member-select2" name="member_id">
									<option value=""><?php _e('Select Member','church_mgt');?></option>
										<?php $get_members = array('role' => 'member');
											$membersdata=get_users($get_members);
										if(!empty($membersdata))
										{
											foreach ($membersdata as $member){?>
												<option value="<?php echo esc_attr($member->ID);?>" <?php //selected($member_id,$member->ID);?>><?php echo esc_attr($member->display_name)." - ".esc_attr($member->member_id); ?> </option>
											<?php }
										}?>
								</select>
							</div>
							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="family_member" class="form-control validate[required,custom[onlyNumber]] text-input" maxlength="4" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->family_members);}elseif(isset($_POST['family_member'])) echo esc_attr($_POST['family_member']);?>" name="family_member">
										<label class="ml-2" for="family_member"><?php _e('Number Of Family Member','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>	
							</div>

							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="checkin_date" class="form-control validate[required]" type="text" name="checkin_date" value="<?php if($edit){ echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->checkin_date)));}elseif(isset($_POST['checkin_date'])){ echo esc_attr($_POST['checkin_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
										<label class="" for="checkin_date"><?php _e('Check-in Date','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="checkout_date" class="form-control validate[required]" type="text" name="checkout_date"  
										value="<?php if($edit){ echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->checkout_date)));}elseif(isset($_POST['checkout_date'])){ echo esc_attr($_POST['checkout_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
										<label class="" for="checkin_date"><?php _e('Check-out Date','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<?php wp_nonce_field( 'save_checkin_nonce' ); ?>
						<div class="col-md-6 mt-2">
							<input type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Save Reservation','church_mgt');}?>" name="save_checkin" class="btn btn-success save_btn"/>
						</div>
					</div>
				
				</form><!--CHECK-IN FORM END-->
			</div><!--PANEL BODY DIV START-->
			<?php 
		}
	 	?>
	</div><!--TAB CONTENT DIV END-->
</div><!--PANEL WHITE DIV END-->
<?php ?>