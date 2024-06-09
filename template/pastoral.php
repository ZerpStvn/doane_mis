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
$obj_pastoral=new Cmgtpastoral;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'pastoral_list');
$volunteer=MJ_cmgt_check_volunteer($curr_user_id);
//----------- SAVE POSTORAL DATA -----------//
if(isset($_POST['save_pastoral']))
{
	$nonce = sanitize_text_field($_POST['_wpnonce']);
	if (wp_verify_nonce( $nonce, 'save_pastoral_nonce' ) )
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_pastoral->MJ_cmgt_add_pastoral($_POST);
			if($result)	
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=pastoral&tab=pastoral_list&message=2');
			}
		}
		else
		{
			$result=$obj_pastoral->MJ_cmgt_add_pastoral($_POST);
			echo $result;
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=pastoral&tab=pastoral_list&message=1');
			}
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
					
		$('#pastoral_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$(".display-members").select2();
		
		$('#member_list').on('change', function() {  // when the value changes
			$(this).valid(); // trigger validation on this element
		});
			
		$('#pastoral_time').timepicki(
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:15,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: true
			}
		);
			
		jQuery('#pastoral_date').datepicker({
			dateFormat: "yy-mm-dd",
			minDate:'today',
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+25',
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


		//---member validation-----//
		$("#save_pastoral").click(function() 
		{
		
		var ext = $('#member_list').val();
		if(ext =='' || ext == null)
		{
			alert("<?php esc_html_e('Please fill out all the required fields','church_mgt');?>");
			return false;	
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
	<div class="panel-white"><!--PANEL WHITE DIV START-->
		<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT DIV STRAT-->
			<?php 
			if($active_tab == 'pastoral_list')
			{ 
				$curr_user_id=get_current_user_id();
				$own_data=$user_access['own_data'];
				if($obj_church->role == 'accountant')
				{
					if($own_data == '1')
					{ 
						$pastoraldata=$obj_pastoral->MJ_cmgt_get_all_pastoral_created_by();
					}
					else
					{
						$pastoraldata=$obj_pastoral->MJ_cmgt_get_all_pastoral();
					}
					
				}
				else
				{
					if($own_data == '1')
					{ 
						$pastoraldata=$obj_pastoral->MJ_cmgt_get_pastoral_member($curr_user_id);
					}
					else
					{
						$pastoraldata=$obj_pastoral->MJ_cmgt_get_all_pastoral();
					}
				}	
				if(!empty($pastoraldata))
				{
					?>	
					<script type="text/javascript">
							$(document).ready(function() 
							{
								jQuery('#pastoral_list').DataTable({
									// "responsive":true,
									"sSearch": "<i class='fa fa-search'></i>",
									"dom": 'lifrtp',
									language:<?php echo MJ_cmgt_datatable_multi_language();?>,
									"order": [[ 0, "asc" ]],
									"aoColumns":[
												{"bSortable": false},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": false}]
									});
									$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
							});
					</script>
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="pastoral_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Pastoral Title', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Pastoral Date', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Pastoral Time', 'church_mgt' ) ;?></th>
										<th class=""><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Pastoral Title', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Pastoral Date', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Pastoral Time', 'church_mgt' ) ;?></th>
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
												<td class="user_image width_50px profile_image_prescription padding_left_0">
													<p class="padding_15px prescription_tag margin_bottom_0 <?php echo $color_class; ?>">	
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pledges-white.png"?>" alt="" class="massage_image center">
													</p>
												</td>
												<?php if($obj_church->role == 'accountant' || $volunteer=='yes' && $retrieved_data->created_by==$curr_user_id){?>
													<td class="pastoraltitle width_20_per"><a class="color_black view_pastoral"  id="<?php echo $retrieved_data->id?>" href="#"><?php echo esc_attr($retrieved_data->pastoral_title);?></a> </td>
												<?php }
												else { ?>
													<td class="pastoraltitle width_20_per"><a class="color_black view_pastoral"  id="<?php echo $retrieved_data->id?>" href="#"><?php echo esc_attr($retrieved_data->pastoral_title);?></a> </td>
													<?php } ?>
												<td class="Member width_20_per"><?php $user=get_userdata($retrieved_data->member_id); echo esc_attr($user->display_name);?> </td>
												<td class="pastoral width_15_per"><?php  echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->pastoral_date));?> </td>
												<td class="pastoral-time width_10_per">
													<?php 
													if(!empty($retrieved_data->pastoral_time))
													{
														echo esc_attr($retrieved_data->pastoral_time);
													}
													else
													{
														echo 'N/A';
													}
													?>
													</td>
											
												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown action_menu mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																	<li><a href="#" class="dropdown-item view_pastoral" id="<?php echo esc_attr($retrieved_data->id);?>"><i class="fa fa-eye"></i> <?php esc_html_e('View', 'church_mgt' ) ;?></a></li>
																	<?php if($user_access['edit'] == '1'){?>
																	<li><a href="?church-dashboard=user&&page=pastoral&tab=add_pastoral&action=edit&pastoral_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i> <?php esc_html_e('Edit', 'church_mgt' ) ;?></a></li>
																	<?php } ?>
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
			if($active_tab == 'add_pastoral')
				{
					$pastoral_id=0;
					if(isset($_REQUEST['pastoral_id']))
						$pastoral_id= sanitize_text_field($_REQUEST['pastoral_id']);
						$edit=0;
						if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
						{
							$edit=1;
							$result = $obj_pastoral->MJ_cmgt_get_single_pastoral($pastoral_id);
							
						}?>
						<div class="padding_left_15px "><!--PANEL BODY DIV START-->
						<form name="pastoral_form" action="" method="post" class="form-horizontal" id="pastoral_form"><!--POSTORAL FORM START-->
							<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
							<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
							<input type="hidden" name="pastoral_id" value="<?php echo esc_attr($pastoral_id);?>"  />
							<div class="form-body user_form">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="pastoral_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->pastoral_title);}elseif(isset($_POST['pastoral_title'])) echo esc_attr($_POST['pastoral_title']);?>" name="pastoral_title">
												<label class="" for="pastoral_title"><?php _e('Pastoral Title','church_mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-6 cmgt_display">
										<div class="form-group input row margin_buttom_0">
											<div class="col-md-12">
												<label class="ml-1 custom-top-label top" for="day"><?php esc_html_e('Member','church_mgt');?><span class="require-field">*</span></label>
												<select class="form-control line_height_30px" name="member_id" id="member_list">
													<option value=""><?php _e('Select Member','church_mgt');?></option>
													<?php
														if($edit)
															$member_id=$result->member_id;
														elseif(isset($_POST['member_id'])) 
															$member_id=$_POST['member_id'];
														else
															$member_id=0;
														
														$get_members = array('role' => 'member');
															$membersdata=get_users($get_members);
														if(!empty($membersdata))
														{
															foreach ($membersdata as $member){	
																if(empty($member->cmgt_hash)){
																?>
																<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
																<?php }
															}
														}?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="pastoral_date" class="form-control" type="text" data-date-format="yyyy-mm-dd"  name="pastoral_date" 
												value="<?php if($edit){ echo esc_attr($result->pastoral_date);}elseif(isset($_POST['pastoral_date'])){ echo esc_attr($_POST['pastoral_date']);}else{ echo date('Y-m-d'); } ?>" autocomplete="off" readonly>
												<label class="" for="pastoral_date"><?php _e('Pastoral Date','church_mgt');?><span class="require-field">*</span></label>
											</div>	
										</div>
									</div>
									<!-- <div class="col-md-6">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="pastoral_time" class="form-control  timepicker" type="text"  name="pastoral_time" value="<?php if($edit){ echo esc_attr($result->pastoral_time);}elseif(isset($_POST['pastoral_time'])){ echo esc_attr($_POST['pastoral_time']);}else{ echo esc_html_e('Pastoral Time','church_mgt');}?>">
											</div>
										</div>
									</div>
									<div class="col-md-6 note_text_notice">
										<div class="form-group input">
											<div class="col-md-12 note_border margin_bottom_15px_res">
												<div class="form-field">
													<textarea name="description" class="form-control textarea_height validate[custom[address_description_validation]]" id="description"><?php if($edit){ echo esc_textarea($result->description);}?></textarea>
													<span class="txt-title-label"></span>
													<label class="text-area address"><?php esc_html_e('Description','church_mgt');?></label>
												</div>
											</div>
										</div>
									</div> -->

									<div class="col-md-6">
										<div class="form-group input">
											<div class="col-md-12 form-control ">
												<input id="pastoral_time" class="form-control placeholder_color timepicker" type="text" placeholder="<?php _e('Pastoral Time','church_mgt');?>" name="pastoral_time" value="<?php if($edit){ echo esc_attr($result->pastoral_time);}elseif(isset($_POST['pastoral_time'])) echo esc_attr($_POST['pastoral_time']);?>">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input">
											<div class="col-md-12 cmgt_form_description form-control">
												<textarea name="description" class="form-control validate[custom[address_description_validation]]"  maxlength="250" id="description"><?php if($edit){ echo esc_attr($result->description);}?></textarea>
												<label class="" for="description"><?php _e('Description','church_mgt');?></label>
											</div>
										</div>	
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mt-2">
									<?php wp_nonce_field( 'save_pastoral_nonce' ); ?>
									<div class="offset-sm-0">
										<input id="save_pastoral reduce_sp" type="submit" value="<?php if($edit){ esc_html_e('Save Pastoral','church_mgt'); }else{ esc_html_e('Add Pastoral','church_mgt');}?>" name="save_pastoral" class="btn btn-success save_btn"/>
									</div>
								</div>	
							</div>
						
						</form><!--POSTORAL FORM END-->
					</div><!--PANEL BODY DIV END-->
				 <?php 
				}
				 ?>
		</div><!--TAB CONTENT DIV END-->
	</div><!--PANEL WHITE DIV END-->
<?php ?>