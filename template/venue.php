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
$obj_venue=new Cmgtvenue;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'venuelist');
if(isset($_POST['save_venue']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_venue_nonce' ) )
	{
	//---------- EDIT VENUE ------------//
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
		$result=$obj_venue->MJ_cmgt_add_venue($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&page=venue&tab=venuelist&message=2');
		}
	}

	else
	{
		//---------- ADD VENUE ------------//
		$result=$obj_venue->MJ_cmgt_add_venue($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&page=venue&tab=venuelist&message=1');
		}
	}
	
}
}
		//--------DELETE VENUE ------------//
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['venue_id']))
		{
			$result=$obj_venue->MJ_cmgt_delete_venue(sanitize_text_field($_REQUEST['venue_id']));
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&page=venue&tab=venuelist&message=3');
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
			esc_html_e("Record updated successfully.",'church_mgt');
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
		$('#message_template').hide();
	}); 
}); 
</script>
<script type="text/javascript">
	$(document).ready(function() 
	{
		
		$('#vanue_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#equipment_category').multiselect({
		nonSelectedText :'<?php esc_html_e('Select Equipment','church_mgt');?>',
		selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
		allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: '<?php esc_html_e('Search for Equipment...','church_mgt');?>',
		templates: {
            button: '<button type="button" class="multiselect btn btn-default dropdown-toggle" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
        },
		buttonContainer: '<div class="dropdown" />'
		
		});
		
		$('#reservation_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		
		$(".reservation_date").datepicker({
       	dateFormat: "yy-mm-dd",
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".reservation_end_date").datepicker("option", "minDate", dt);
        }
	    });
	    $(".reservation_end_date").datepicker({
	      dateFormat: "yy-mm-dd",
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() - 0);
	            $(".reservation_date").datepicker("option", "maxDate", dt);
	        }
	    });	
	    
		$('#reservation_start_time').timepicki(
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
		$('#reservation_end_time').timepicki(
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
		 
		$('#group_id').multiselect();

	   $('#capacity').keydown(function( e ) 
		{
			if(e.which == 189 || e.which == 109)
			 return false;
		});

		//not aloow - value
		$('#participant').keydown(function( e ) 
		{
			if(e.which === 189 || e.which == 109) 
			 return false;
		});	
		$(".check_memeber").click(function()
		{	
			var max_value=$('#capacity').val() ;
			var participant_value=$('#participant').val() ;

			if(participant_value > max_value)
			{
				alert(language_translate.max_limit_member_alert);
				return false;
			}			
		}); 
	} );
</script>
		
	<div class="panel-white"><!--PANEL WHITE DIV START-->
		<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT DIV STRAT-->
		<?php 
		if($active_tab == 'venuelist')
		{ 
			$curr_user_id=get_current_user_id(); 
			$own_data=$user_access['own_data'];
			if($obj_church->role == 'accountant')
			{
				if($own_data == '1')
				{ 
					$venuedata=$obj_venue->MJ_cmgt_get_all_venue();
				}
				else
				{
				$venuedata=$obj_venue->MJ_cmgt_get_all_venue();
				}
			
			}
			else
			{
				$venuedata=$obj_venue->MJ_cmgt_get_all_venue();
			}	
			if(!empty($venuedata))
			{
				?>	
				<script type="text/javascript">
					$(document).ready(function() 
					{
						jQuery('#venue_list').DataTable(
						{
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
										{"bSortable": true},
										{"bSortable": false}]
						});
						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
					} );
				</script>
				<!-- POP up code -->
				<div class="popup-bg" style="z-index:100000 !important;">
					<div class="overlay-content">
						<div class="modal-content">
							<div id="venue_view">
							</div>
						</div>
					</div> 
				</div>
				<!-- End POP-UP Code -->
				<div class="padding_left_15px"><!--PANEL BODY DIV START-->
					<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
						<table id="venue_list" class="display" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
								<tr>
									<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
									<th><?php _e( 'Venue Title', 'church_mgt' ) ;?></th>
									<th><?php _e( 'Capacity Seats', 'church_mgt' ) ;?></th>
									<th><?php _e( 'Request Before Days', 'church_mgt' ) ;?></th>
									<th> <?php _e( 'Equipment', 'church_mgt' );?></th>
									<th> <?php _e( 'Multiple Reservation', 'church_mgt' );?></th>
									<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
									<th><?php _e( 'Venue Title', 'church_mgt' ) ;?></th>
									<th><?php _e( 'Capacity Seats', 'church_mgt' ) ;?></th>
									<th><?php _e( 'Request Before Days', 'church_mgt' ) ;?></th>
									<th> <?php _e( 'Equipment', 'church_mgt' );?></th>
									<th> <?php _e( 'Multiple Reservation', 'church_mgt' );?></th>
									<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
								</tr>
							<tbody>
								<?php
								$i=0;
								if(!empty($venuedata))
								{
									foreach ($venuedata as $retrieved_data)
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
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Venue-white.png"?>" alt="" class="massage_image center">
												</p>
											</td>
											
											<td class="name width_25_per"><a class="color_black view_venue" id="<?php echo esc_attr($retrieved_data->id);?>" href="#"><?php echo esc_attr(ucfirst($retrieved_data->venue_title));?></a> </td>

											<td class="capacity width_10_per"><?php echo esc_attr($retrieved_data->capacity);?><?php esc_html_e(' Seats','church_mgt');?> </td>
											<td class="req_before width_10_per"><?php echo esc_attr($retrieved_data->request_before_days);?><?php esc_html_e(' Days','church_mgt');?> </td>
											
											<?php 
											$quipment='';
											$equipment_array = array();
											$equipment_array =(explode(",",$retrieved_data->equipments));
												foreach ($equipment_array as $retrive_data)
												{ 
													if($retrive_data!='')
														$quipment.=get_the_title($retrive_data).',';
												}
													if(!empty($quipment))
													{ 
														$quipment_title= rtrim($quipment,',');
														?>
														<td class="equipments width_25_per"><?php echo esc_attr($quipment_title);?> </td>
														<?php
													}else
													{
														?>
														<td class="equipments width_25_per"><?php esc_html_e('N/A','church_mgt');?> </td>
														<?php
													}
											?>
											<td class="multiple_booking width_15_per"><?php if($retrieved_data->multiple_booking=='yes') echo __('Yes','church_mgt'); else echo __('No','church_mgt');?> </td>
											<td class="action cmgt_pr_0px">
												<div class="cmgt-user-dropdown mt-2">
													<ul class="">
														<!-- BEGIN USER LOGIN DROPDOWN -->
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
															</a>
															<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																<li><a class="dropdown-item view_venue" href="#" id="<?php echo esc_attr($retrieved_data->id);?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																<?php
																if($user_access['edit'] == '1')
																{
																	?>
																	<li><a href="?church-dashboard=user&&page=venue&tab=addvenue&action=edit&venue_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item "><i class="fa fa-edit"></i> <?php esc_html_e('Edit', 'church_mgt' ) ;?></a></li>
																	<?php
																}
																if($user_access['delete'] == '1')
																{
																	?>
																	<div class="cmgt-dropdown-deletelist">
																		<li><a href="?church-dashboard=user&page=venue&tab=venuelist&action=delete&venue_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item" onclick="return confirm('<?php esc_html_e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash"></i>
																		<?php esc_html_e( 'Delete', 'church_mgt' ) ;?> </a></li>
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
						<a href="<?php echo home_url().'?church-dashboard=user&page=venue&tab=addvenue';?>">
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
		if($active_tab == 'addvenue')
		{
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
			<?php 
        	$venue_id=0;
			if(isset($_REQUEST['venue_id']))
			$venue_id= sanitize_text_field($_REQUEST['venue_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result =  $obj_venue->MJ_cmgt_get_single_venue($venue_id);
			}?>
			<div class="padding_left_15px"><!--PANEL BODY DIV START-->
				<form name="vanue_form" action="" method="post" class="form-horizontal" id="vanue_form"><!--VANUE FORM START-->
					<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="venue_id" value="<?php echo $venue_id;?>"  />
					<div class="form-body user_form">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="venue_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->venue_title);}elseif(isset($_POST['venue_title'])) echo esc_attr($_POST['venue_title']);?>" name="venue_title">
										<label class="" for="activity_title"><?php _e('Venue Title','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group input">
									<div class="form-group">
									<div class="col-md-12 form-control">
											<input id="capacity" class="form-control validate[required,custom[onlyNumber]] text-input" type="text" maxlength="3" <?php if($edit){ ?> value="<?php echo esc_attr($result->capacity);}elseif(isset($_POST['capacity'])) echo esc_attr($_POST['capacity']);?>" name="capacity">
											<label class="" for="capacity"><?php _e('Capacity Seats','church_mgt');?><span class="require-field">*</span></label>
										</div>	
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group input">
									<div class="form-group">
									<div class="col-md-12 form-control">
											<input id="request_days" class="form-control validate[required,custom[onlyNumber]] text-input" maxlength="2" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->request_before_days);}elseif(isset($_POST['request_days'])) echo esc_attr($_POST['request_days']);?>" name="request_days">
											<label class="" for="request_days"><?php _e('Request Before Days','church_mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>	
							</div>
							<div class="col-md-6 cmgt_display">
								<div class="form-group input row margin_buttom_0">
									<div class="col-md-8 input">
										<select class="form-control equipment_list equipment_category" multiple="multiple" name="equipment_id[]" id="equipment_category">
											<?php $equipment_array = array();
											
											if($edit)
											{  
												$equipment_array =(explode(",",$result->equipments));
											}
											$equipments=MJ_cmgt_get_all_category('equipment_category');
											if(!empty($equipments))
											{
												foreach ($equipments as $retrive_data)
												{ ?>
													<option value="<?php echo esc_attr($retrive_data->ID);?>" <?php if(in_array($retrive_data->ID,$equipment_array)) echo 'selected';  ?>><span style="margin-left:50px;"><?php echo esc_attr($retrive_data->post_title); ?></span></option>
												<?php }
											}?>
										</select>
									</div>
									<!--ADD Group POPUP BUTTON -->
									<div class="col-sm-4 otehrservice1 rtl_add_remove_btn">
										<button type="button" id="addremove" class="otehrservice1 btn btn-success  width_100 btn_height cmgt_add_font_13px" model="equipment_category"> <?php _e('Add Or Remove','church_mgt');?></button>
									</div>
								</div>	
							</div>
							<!-- <div class="form-group">
								<div class="mb-3 row">
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<div class="checkbox">
										<input type="checkbox" value="yes" <?php if($edit){ echo $check_val=$result->multiple_booking;}else{ $check_val=""; } if($check_val=='yes'){?> checked <?php } ?> name="multiple_sreservation">
											<?php esc_html_e('Allow multiple reservation in the same period','church_mgt');?></div>
									</div>
								</div>	
							</div> -->

							<div class="col-md-6">
								<div class="form-group">
									<div class="col-md-12 form-control input_height_48px">
										<div class="row padding_radio">
											<div class="input-group">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="checkbox" id="font_size_11px">
													<input type="checkbox" value="yes" <?php if($edit){ echo $check_val=$result->multiple_booking;}else{ $check_val=""; } if($check_val=='yes'){?> checked <?php } ?> name="multiple_sreservation">
														<?php esc_html_e('Allow multiple reservation in the same period','church_mgt');?></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>



						</div>
					</div>
					<div class="row">
						<?php wp_nonce_field( 'save_venue_nonce' ); ?>
						<div class="col-md-6 mt-2">
							<input type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Add Venue','church_mgt');}?>" name="save_venue" class="btn btn-success save_btn reduce_sp"/>
						</div>
					</div>
			
				</form><!--VANUE FORM END-->
			</div><!--PANEL BODY DIV END-->
			<?php  
		} ?>
		</div><!--TAB CONTENT DIV END-->
	</div><!--PANEL WHITE DIV END-->
<?php ?>