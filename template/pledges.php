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
$obj_pledge=new Cmgtpledes;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'pledgeslist');
$volunteer=MJ_cmgt_check_volunteer($curr_user_id);
	if(isset($_POST['save_pledge']))
	{
		$nonce = sanitize_text_field($_POST['_wpnonce']);
		if (wp_verify_nonce( $nonce, 'save_pledge_nonce' ) )
		{
		//------------- EDIT PLADGE ------------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_pledge->MJ_cmgt_add_pledges($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=pledges&tab=pledgeslist&message=2');
			}
				
		}
		else
		{
			//------------- ADD PLADGE ------------//
			   $result=$obj_pledge->MJ_cmgt_add_pledges($_POST);
				echo $result;
				if($result)
				{
					wp_redirect ( home_url().'?church-dashboard=user&&page=pledges&tab=pledgeslist&message=1');
				}
			}
		}
		}
	//------------- DELETE PLADGE ------------//
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_pledge->MJ_cmgt_delete_pledges(sanitize_text_field($_REQUEST['pledge_id']));
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&&page=pledges&tab=pledgeslist&message=3');
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
			<div id="message_template" class="alert_msg alert alert-success alert-dismissible fade in" role="alert">
				<?php
				esc_html_e('Record updated successfully','church_mgt');
				?>
				<button type="button" class="close float-end btn-close p-3" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
			</div>
		<?php }
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
			$('#pledge_add_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('#pledge_add_form').validationEngine({
				rules: {
					member_id: {
						required: true
					}
				},
				messages: {
					member_id: {
						required: 'Please select at least one thing.'
					}
				}
			});
			$(".display-members").select2();

			    jQuery('#start_date').datepicker({
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
		} );
	</script>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="invoice_data">
			</div>	
		</div>
	</div> 
</div>
<!-- End POP-UP Code -->
	<div class="panel-white"><!--PANEL WHITE DIV START-->
		<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT DIV STRAT-->
			<?php 
			if($active_tab == 'pledgeslist')
			{ 
				$own_data=$user_access['own_data'];
				if($obj_church->role == 'accountant')
				{
					if($own_data == '1')
					{ 
						$pledgedata=$obj_pledge->MJ_cmgt_get_my_pledgeslist_creted_by();
					}
					else
					{
						$pledgedata=$obj_pledge->MJ_cmgt_get_all_pledges();
					}
				}
				else
				{
					if($own_data == '1')
					{ 
						$pledgedata=$obj_pledge->MJ_cmgt_get_my_pledgeslist($curr_user_id);
					}
					else
					{
						$pledgedata=$obj_pledge->MJ_cmgt_get_all_pledges();
					}
				}	
				if(!empty($pledgedata))
				{
					?>	
					<script type="text/javascript">
						$(document).ready(function() 
						{
							jQuery('#pledges_list').DataTable({
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
											{"bSortable": true},
											{"bSortable": false}]
									});
							$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
						} );
					</script>
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="pledges_list" class="display" cellspacing="0" width="100%">	
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Pledge Invoice Number', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Start Date To End Date', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Frequency Number of Time', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Total Amount', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Pledge Invoice Number', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Start Date To End Date', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Frequency Number of Time', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Total Amount', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									if(!empty($pledgedata))
									{
										$i = 0;
										foreach ($pledgedata as $retrieved_data)
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
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pledges-white.png"?>" alt="" class="massage_image center">
													</p>
												</td>

												<td class="name width_15_per"><a class="color_black show-invoice-popup" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="pledges" href="#"><?php $user=get_userdata($retrieved_data->member_id); echo esc_attr($user->display_name); ?></a> </td>
												<td class="width_15_per">
													<?php 
														$invoice_number = esc_attr($obj_pledge->MJ_cmgt_generate_pledges_number($retrieved_data->id)); 
														echo get_option( 'cmgt_payment_prefix' ).''.$invoice_number;?> 
												</td>
												<td class="start_date width_22_per"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->start_date)));?> <?php _e('To','church_mgt');?> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->end_date)));?> </td>

												<td class="total_amount width_15_per"><lable><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->amount);?> </lable></td>

												<td class="Frequency_day width_20_per">
													<?php
														if($retrieved_data->period_id == "one_time")
														{
															?>
															<lable><?php esc_html_e( '1 Time', 'church_mgt' ) ;?><?php esc_html_e('(', 'church_mgt' );?><?php echo esc_attr($retrieved_data->times_number);?> <?php esc_html_e('-Time)', 'church_mgt' );?> </lable>
														<?php
														}else
														{
															?>
															<lable><?php echo esc_attr(ucfirst($retrieved_data->period_id));?> <?php echo esc_attr($retrieved_data->times_number);?> <?php esc_html_e('-Time)', 'church_mgt' );?> </lable>
															<?php
														}
														?>
													</td>
												<td class="total_amount width_15_per"><lable><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->total_amount);?> </lable></td>
												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																	<li><a href="?church-dashboard=user&&page=pledges&tab=view_invoice&idtest=<?php echo esc_attr($retrieved_data->id);?>&invoice_type=pledges" class="dropdown-item "><i class="fa fa-eye"></i><?php _e('View Invoice', 'church_mgt' ) ;?></a></li>
																	<?php  if($user_access['edit'] == '1'){ ?>
																		<li><a href="?church-dashboard=user&&page=pledges&tab=addpledges&action=edit&pledge_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
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
									}	?>
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
							<a href="<?php echo home_url().'?church-dashboard=user&page=pledges&tab=addpledges';?>">
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
			if($active_tab == 'addpledges')
			{
				$pledge_id=0;
				if(isset($_REQUEST['pledge_id']))
					$pledge_id= sanitize_text_field($_REQUEST['pledge_id']);
					$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						$edit=1;
						$result = $obj_pledge->MJ_cmgt_get_single_pledges($pledge_id);
						
					}?>
					<div class="padding_left_15px" id="frontend_pledge_form_mt"><!--PANEL BODY DIV START-->
						<form name="pledge_form" action="" method="post" class="form-horizontal" id="pledge_add_form"><!--PLADGE FORM START-->
							<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
							<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
							<input type="hidden" name="pledge_id" value="<?php echo esc_attr($pledge_id);?>" />
							<div class="form-body user_form">
								<div class="row">
									<div class="col-md-6 cmgt_display">
										<div class="form-group input row margin_buttom_0">
											<div class="col-md-12">
												<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>

												<select class="form-control validate[required] line_height_30px" name="member_id" id="member_list">
													<option value=""><?php _e('Select Member','church_mgt');?></option>

														<?php
														if($edit)
															$member_id= sanitize_text_field($result->member_id);
														elseif(isset($_POST['start_date'])) 
															$member_id= sanitize_text_field($_POST['start_date']);
														else
															$member_id=0;
														
														$get_members = array('role' => 'member');
															$membersdata=get_users($get_members);
														if(!empty($membersdata))
														{
															foreach ($membersdata as $member)
															{ 
																if(empty($member->cmgt_hash)){
																?>
																<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
																<?php 
																}
															}
														} ?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="start_date" class="form-control validate[required]" type="text" name="start_date" value="<?php if($edit){ echo date("Y-m-d",strtotime(esc_attr($result->start_date)));}elseif(isset($_POST['start_date'])){ echo esc_attr($_POST['start_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
												<label class="" for="checkin_date"><?php _e('Start Date','church_mgt');?><span
													class="require-field">*</span></label>
											</div>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="amount" class="form-control validate[required,custom[amount]] " type="text" maxlength="8" name="amount" <?php if($edit){  ?> value="<?php echo esc_attr($result->amount);}elseif(isset($_POST['amount'])) echo esc_attr($_POST['amount']);?>">
												<label class="" for="amount"><?php _e('Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-6 cmgt_display">
										<div class="form-group input row margin_buttom_0">
											<div class="col-sm-7 col-xs-12">
												<?php if($edit)
															$period= sanitize_text_field($result->period_id);
														elseif(isset($_POST['period_id'])) 
															$period= sanitize_text_field($_POST['period_id']);
															else
																$period='';?>
												<select class="form-control" name="period_id" id="period_id">
													<option value="select"><?php _e('Select Frequency','church_mgt');?></option>
													<option value="one_time" <?php selected($period,'one_time');?>><?php _e('1 Time','church_mgt');?>
													</option>
													<option value="weekly" <?php selected($period,'weekly');?>><?php _e('Weekly','church_mgt');?>
													</option>
													<option value="monthly" <?php selected($period,'monthly');?>><?php _e('Monthly','church_mgt');?>
													</option>
													<option value="yearly" <?php selected($period,'yearly');?>><?php _e('Yearly','church_mgt');?>
													</option>
												</select>
											</div>
											<div class="col-sm-5 col-xs-12 top1">
												<div class="form-group input rtl_margin_top_0px">
												<div class="col-md-12 form-control">
														<input id="times_number" class="form-control validate[required]" type="text" maxlength="4" name="times_number" value="<?php if($edit){ echo esc_attr($result->times_number);}elseif(isset($_POST['times_number'])){ echo esc_attr($_POST['times_number']); }else{ echo 1;} ?>">
														<label class="control-label" for="capacity"><?php _e('No of Times','church_mgt');?></label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>	
							<div class="form-body user_form">
								<div class="row" id="view_pledes_limit">
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="end_date" readonly class="form-control" type="text" name="end_date"
												value="<?php if($edit){ echo date("Y-m-d",strtotime($result->end_date));}elseif(isset($_POST['end_date'])){ echo esc_attr($_POST['end_date']);}else{ echo date('Y-m-d');}?>" autocomplete="off" readonly>
												<label class="" for="end_date"><?php _e('End Date','church_mgt');?></label>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input id="total_amount" readonly class="form-control" type="text" name="total_amount" <?php if($edit){ ?>value="<?php echo esc_attr($result->total_amount);}elseif(isset($_POST['total_amount'])) echo esc_attr($_POST['total_amount']);?>">
												<label class="" for="total_amount"><?php _e('Total Amount','church_mgt');?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mt-2">
									<?php wp_nonce_field( 'save_pledge_nonce' ); ?>
									<div class="offset-sm-0">
										<input type="submit" value="<?php if($edit){ esc_html_e('Save Pledge','church_mgt'); }else{ esc_html_e('Add Pledge','church_mgt');}?>" name="save_pledge" class="btn btn-success save_btn reduce_sp"/>
									</div>
								</div>	
							</div>
						
						</form><!--PLADGE FORM START-->
					</div><!--PANEL BODY DIV END-->
			<?php 
			} 
			if($active_tab == 'view_invoice')
			{
				$invoice_type=$_REQUEST['invoice_type'];
				$invoice_id=$_REQUEST['idtest'];
				MJ_cmgt_view_invoice_page($invoice_type,$invoice_id);
			}
			?>
		</div><!--TAB CONTENT DIV END-->
	</div><!--PANEL WHITE DIV END-->
<?php ?>