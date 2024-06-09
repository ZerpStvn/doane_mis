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
$obj_notice=new Cmgtnotice;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'noticelist');
?>
<?php
//SAVE NOTICE DATA
	if(isset($_POST['save_notice']))
		{		
			$nonce = sanitize_text_field($_POST['_wpnonce']);
			if (wp_verify_nonce( $nonce, 'save_notice_nonce' ) )
			{
			//---------- EDIT NOTICE ----------------//
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{		
				$start_date=MJ_cmgt_get_format_for_db($_POST['start_date']);
				$end_date=MJ_cmgt_get_format_for_db($_POST['end_date']);
				if($start_date > $end_date)
				{
					$time_validation='1';
				}
				else
				{
					$time_validation='0';
					$result=$obj_notice->MJ_cmgt_add_notice($_POST);		
					if($result)
					{
						
					   wp_redirect ( home_url().'?church-dashboard=user&&page=notice&tab=noticelist&message=2');
					}
				}
			}
			else
			{		//---------- ADD NOTICE ----------------//		
				$result=$obj_notice->MJ_cmgt_add_notice($_POST);
				if($result)
				{
						
					   wp_redirect ( home_url().'?church-dashboard=user&&page=notice&tab=noticelist&message=1');
				}
			}
			if($time_validation=='1')
			{
			?>
				<div id="message" class="updated below-h2 ">
					<p>
						<?php 
							_e('End Date should be greater than Start Date.','church_mgt');
						?>
					</p>
				</div>
		   <?php 
			}
		 }
		}

if(isset($_REQUEST['message'])){
    $message = sanitize_text_field($_REQUEST['message']);
	if($message == 1)
	
	{ ?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e("Record inserted successfully.",'church_mgt');
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
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"> </div>		 
		</div>
    </div>     
</div>
<!-- End POP-UP Code -->
	<div class="panel-white"><!--PANEL BODY DIV START -->
		<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT START -->
			<?php 
			if($active_tab == 'noticelist')
			{ 
				$own_data=$user_access['own_data'];
				if($own_data == '1')
				{ 
					$noticedata=$obj_notice->MJ_cmgt_get_all_notice_creted_by();
				}
				else
				{
					$noticedata=$obj_notice->MJ_cmgt_get_all_notice();
				}
				$i=0;
				if(!empty($noticedata))
				{
					?>	
					<script type="text/javascript">
						$(document).ready(function() 
						{
								jQuery('#noticelist').DataTable({
								// "responsive": true,
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
											{"bSortable": false}
										]
								});
								$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
						} );
					</script>
					<div class="padding_left_15px"><!-- PANEL BODY DIV START -->
						<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
							<table id="noticelist" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Notice Title', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Notice By', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Notice Start Date To End Date', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Notice Comment', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Notice Title', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Notice By', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Notice Start Date To End Date', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Notice Comment', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>	
								<tbody>
								<?php 
									$i=0;
									if(!empty($noticedata))
									{
										foreach ($noticedata as $retrieved_data)
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
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/notice.png"?>" alt="" class="massage_image center">
														</p>
													</td>
													
													<td class="name width_25_per"><a class="color_black view_notice" id="<?php echo $retrieved_data->id ?>" href="#"><?php echo esc_attr($retrieved_data->notice_title);?></a> </td>

													<td class="notice_by width_15_per"><?php print  MJ_cmgt_church_get_display_name(esc_attr($retrieved_data->created_by));?> </td>

													<td class="date width_22_per"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->start_date)));?> <?php _e('To','church_mgt');?> <?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->end_date)));?> </td>

													<?php
														if(!empty($retrieved_data->notice_content)) 
														{
															$notice_comment = strlen($retrieved_data->notice_content) > 35 ? substr($retrieved_data->notice_content,0,35)."..." : $retrieved_data->notice_content;
															?>
																<td class="speaker"><?php echo $notice_comment;?> </td>

															<?php
														}
														else
														{
															?>
																<td class="notice_comment"><?php echo esc_html( __( 'N/A', 'church_mgt' ) );?> </td>
															<?php
														} 
													?>
													<td class="action cmgt_pr_0px">
														<div class="cmgt-user-dropdown mt-2">
															<ul class="">
																<!-- BEGIN USER LOGIN DROPDOWN -->
																<li class="">
																	<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																		<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																	</a>
																	<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																		<li><a href="" id="<?php echo $retrieved_data->id ?>" class="dropdown-item view_notice"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' );?></a></li>
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
						</div><!--TABLE RESPONSIVE DIV END -->
					</div><!-- PANEL BODY DIV END -->
					<?php 
				}
				else
				{
					if($user_access['add']=='1')
					{
						?>
						<div class="no_data_list_div"> 
							<a href="<?php echo home_url().'?church-dashboard=user&page=notice&tab=addnotice';?>">
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
			if($active_tab=="addnotice")
			{ ?>

				<script type="text/javascript">
					$(document).ready(function() 
					{
						$('#notice_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
						$("#notice_Start_date").datepicker({
				       	dateFormat: "yy-mm-dd",
						minDate:0,
				        onSelect: function (selected) {
				            var dt = new Date(selected);
				            dt.setDate(dt.getDate() + 0);
				            $("#notice_end_date").datepicker("option", "minDate", dt);
				        }
					    });
					    $("#notice_end_date").datepicker({
					      dateFormat: "yy-mm-dd",
					        onSelect: function (selected) {
					            var dt = new Date(selected);
					            dt.setDate(dt.getDate() - 0);
					            $("#notice_Start_date").datepicker("option", "maxDate", dt);
					        }
					    });	
					});
				</script>	
				<?php 
				$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						$edit=1;
						$noticedata = $obj_notice->MJ_cmgt_get_single_notice(sanitize_text_field($_REQUEST['id']));	
					}
				?>
				<div class="padding_left_15px">
				<form name="class_form" action="" method="post" class="form-horizontal" id="notice_form">
					<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
					<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
					<div class="form-body user_form">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="notice_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($noticedata->notice_title);}?>" name="notice_title">
										<label class="" for="notice_title"><?php _e('Notice Title','church_mgt');?><span class="require-field">*</span></label>
										<input type="hidden" name="id"   value="<?php if($edit){ echo esc_attr($noticedata->id);}?>"/> 
										<input type="hidden" name="status"    value="<?php if($edit){ echo esc_attr($noticedata->status);} else{ print 1; }?>"/> 
									</div>
								</div>
							</div>
							<div class="col-md-6 note_text_notice">
								<div class="form-group input">
									<div class="col-md-12 note_border margin_bottom_15px_res">
										<div class="form-field">
											<textarea name="notice_content" class="form-control textarea_height validate[custom[address_description_validation]]"  maxlength="150" id="notice_content"><?php if($edit){ echo esc_attr($noticedata->notice_content);}?></textarea>
											<span class="txt-title-label"></span>
											<label class="text-area address"><?php esc_html_e('Notice Comment','church_mgt');?></label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="notice_Start_date" class="form-control validate[required] notice_Start_date" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime(esc_attr($noticedata->start_date))); }else{ echo date('Y-m-d'); } ?>" name="start_date" autocomplete="off" readonly>
										<label class="" for="notice_content"><?php _e('Notice Start Date','church_mgt');?><span class="require-field">*</span></label>
									</div>	
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group input">
								<div class="col-md-12 form-control">
										<input id="notice_end_date" class="form-control validate[required] notice_end_date" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime(esc_attr($noticedata->end_date))); }else{ echo date('Y-m-d'); }?>" name="end_date" autocomplete="off" readonly>
										<label class="" for="notice_content"><?php _e('Notice End Date','church_mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mt-2">
								<?php wp_nonce_field( 'save_notice_nonce' ); ?>
								<div class="offset-sm-0">
									<input type="submit" value="<?php if($edit){ esc_html_e('Save Notice','church_mgt'); }else{ esc_html_e('Add Notice','church_mgt');}?>" name="save_notice" class="btn btn-success save_btn" />
								</div>
							</div>	
						</div>
				</form>
				</div>
	<?php 	} ?>
	</div><!-- TAB CONTENT DIV END -->
</div><!-- PANEL BODY DIV END -->
<?php ?>