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
$obj_transaction=new Cmgttransaction;
$obj_payment=new Cmgtpayment;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'transactionlist');

if(isset($_POST['save_transaction']))
{
	$nonce = sanitize_text_field($_POST['_wpnonce']);
	if (wp_verify_nonce( $nonce, 'save_transaction_nonce' ) )
	{
		//---------- EDIT TRANSACTION DATA -----------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_transaction->MJ_cmgt_add_transaction($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=payment&tab=transactionlist&message=2');
			}
		}
		else
		{
			//---------- ADD TRANSACTION DATA -----------//
			$result=$obj_transaction->MJ_cmgt_add_transaction($_POST);
			
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=payment&tab=transactionlist&message=1');
			}
		}
	}
}
	//---------- DELETE TRANSACTION DATA -----------//
if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['income_id']))
		{
			$result=$obj_payment->MJ_cmgt_delete_income($_REQUEST['income_id']);
			if($result)
			{
				wp_redirect ( home_url() . '?church-dashboard=user&&page=payment&tab=incomelist&message=3');
			}
		}
		if(isset($_REQUEST['expense_id']))
		{
			$result=$obj_payment->MJ_cmgt_delete_income($_REQUEST['expense_id']);
			if($result)
			{
				wp_redirect ( home_url() . '?church-dashboard=user&&page=payment&tab=expenselist&message=3');
			}
		}
		if(isset($_REQUEST['transaction_id']))
		{
			$result=$obj_transaction->MJ_cmgt_delete_transaction($_REQUEST['transaction_id']);
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=payment&tab=transactionlist&message=3');
			}
		}
	}
//--------save income-------------
if(isset($_POST['save_income']))
{
	if($_REQUEST['action']=='edit')
	{
			
		$result=$obj_payment->MJ_cmgt_add_income($_POST);
		if($result)
		{
			wp_redirect ( home_url() . '?church-dashboard=user&&page=payment&tab=incomelist&message=2');
		}
	}
	else
	{
		$result=$obj_payment->MJ_cmgt_add_income($_POST);
		if($result)
		{
			wp_redirect ( home_url() . '?church-dashboard=user&&page=payment&tab=incomelist&message=1');
		}
	}
}		
//--------save Expense-------------
if(isset($_POST['save_expense']))
{
		
	if($_REQUEST['action']=='edit')
	{
			
		$result=$obj_payment->MJ_cmgt_add_expense($_POST);
		if($result)
		{
			wp_redirect ( home_url() . '?church-dashboard=user&&page=payment&tab=expenselist&message=2');
		}
	}
	else
	{
		$result=$obj_payment->MJ_cmgt_add_expense($_POST);
		if($result)
		{
			wp_redirect ( home_url() . '?church-dashboard=user&&page=payment&tab=expenselist&message=1');
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
			<button type="button" class="close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
			<!--<button type="button" class="close float-end btn-close p-3" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>-->
			<button type="button" class="close btn-close p-3 float-end" data-bs-dismiss="alert" aria-label="Close"></button>
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
			jQuery('#transaction_list').DataTable({
				"order": [[ 0, "asc" ]],
				"sSearch": "<i class='fa fa-search'></i>",
				"dom": 'lifrtp',
				language:<?php echo MJ_cmgt_datatable_multi_language();?>,
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
					
			//-----Add Transaction--------		
			$('#transaction_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			$(".display-members").select2();
			
			jQuery('#transaction_date').datepicker({
				dateFormat: "yy-mm-dd",
				//minDate:'today',
				changeMonth: true,
		        changeYear: true,
		        yearRange:'-100:+25',
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
		
			 //-----------income list------
			 jQuery('#tblincome').DataTable({
				 "order": [[ 3, "Desc" ]],
				 "sSearch": "<i class='fa fa-search'></i>",
				 "dom": 'lifrtp',
				 language:<?php echo MJ_cmgt_datatable_multi_language();?>,
				 "aoColumns":[
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true}, 
							  {"bSortable": false}
						   ]
				});
			$('#income_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			jQuery('#invoice_date').datepicker({
				dateFormat: "yy-mm-dd",
				//minDate:'today',
				changeMonth: true,
		        changeYear: true,
		        yearRange:'-100:+25',
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
				$(".display-members").select2();	
			
			//------expense list---------------	
			jQuery('#tblexpence').DataTable({
				 "order": [[ 2, "Desc" ]],
				 "sSearch": "<i class='fa fa-search'></i>",
				 "dom": 'lifrtp',
				 language:<?php echo MJ_cmgt_datatable_multi_language();?>,
				 "aoColumns":[
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": false}
						   ]
				});
			$('#expense_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			jQuery('#invoice_date').datepicker({
				dateFormat: "yy-mm-dd",
				//minDate:'today',
				changeMonth: true,
		        changeYear: true,
		        yearRange:'-100:+25',
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
		});

	</script>

<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content payment_invoice_popup">
		<div class="modal-content">
			<div class="invoice_data">
				<div class="category_list">
				</div>
			</div>	
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
	<div class="panel-white padding_frontendlist_body"><!--PANEL WHITE DIV START-->
		<!-- <ul class="nav massage_menu_design nav-tabs panel_tabs margin_left_1per" role="tablist">
			<li class="<?php if($active_tab=='transactionlist'){?>active<?php }?>">
				<a href="?church-dashboard=user&&page=payment&tab=transactionlist" class="padding_left_0 tab <?php echo $active_tab == 'transactionlist' ? 'active' : ''; ?>">
					<?php esc_html_e('Transaction List', 'church_mgt'); ?>
				</a> 
			</li> 
			<?php
			if($user_access['add'] == '1') 
			{
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['transaction_id']))
				{ 
					?>
					<li class="<?php if($active_tab=='addtransaction'){?>active<?php }?>">
						<a href="?church-dashboard=user&page=payment&tab=addtransaction&action=edit&transaction_id=<?php echo $_REQUEST['transaction_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addtransaction' ? 'active' : ''; ?>">
						<?php esc_html_e('Edit Transaction', 'church_mgt'); ?></a> 
					</li> 
					<?php
				} 
				else
				{
					?>
					<li class="<?php if($active_tab=='addtransaction'){?>active<?php }?>">
						<a href="?church-dashboard=user&&page=payment&tab=addtransaction" class="padding_left_0 tab <?php echo $active_tab == 'addtransaction' ? 'active' : ''; ?>">
						<?php esc_html_e('Add Transaction', 'church_mgt'); ?></a> 
					</li> 
					<?php
				}
			}
			?> 
			<li class="<?php if($active_tab=='incomelist'){?>active<?php }?>">
				<a href="?church-dashboard=user&&page=payment&tab=incomelist" class="padding_left_0 tab <?php echo $active_tab == 'incomelist' ? 'active' : ''; ?>">
				<?php esc_html_e('Income List', 'church_mgt'); ?></a> 
			</li> 
			<?php
			if($user_access['add'] == '1') 
			{
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['income_id']))
				{ 
					?>
					<li class="<?php if($active_tab=='addincome'){?>active<?php }?>">
						<a href="?church-dashboard=user&&page=payment&tab=addincome&action=edit&income_id=<?php echo sanitize_text_field($_REQUEST['income_id']);?>" class="padding_left_0 tab <?php echo $active_tab == 'addincome' ? 'active' : ''; ?>">
						<?php esc_html_e('Edit Income', 'church_mgt'); ?></a> 
					</li> 
					<?php
				} 
				else
				{
					?>
					<li class="<?php if($active_tab=='addincome'){?>active<?php }?>">
						<a href="?church-dashboard=user&&page=payment&tab=addincome" class="padding_left_0 tab <?php echo $active_tab == 'addincome' ? 'active' : ''; ?>">
						<?php esc_html_e('Add Income', 'church_mgt'); ?></a> 
					</li> 
					<?php
				}
			}
			
			if($obj_church->role != 'member')
			{
				?>
					<li class="<?php if($active_tab=='expenselist'){?>active<?php }?>">
						<a href="?church-dashboard=user&&page=payment&tab=expenselist" class="padding_left_0 tab <?php echo $active_tab == 'expenselist' ? 'active' : ''; ?>">
						<?php _e('Expense List', 'church_mgt'); ?></a> 
					</li> 
				<?php
			}
			if($user_access['add'] == '1') 
			{
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['expense_id']))
				{ 
					?>
					<li class="<?php if($active_tab=='addexpense'){?>active<?php }?>">
						<a href="?church-dashboard=user&&page=payment&tab=addexpense&action=edit&expense_id=<?php echo sanitize_text_field($_REQUEST['expense_id']);?>" class="padding_left_0 tab <?php echo $active_tab == 'addexpense' ? 'active' : ''; ?>">
						<?php esc_html_e('Edit Expense', 'church_mgt'); ?></a> 
					</li> 
					<?php
				} 
				else
				{
					?>
					<li class="<?php if($active_tab=='addexpense'){?>active<?php }?>">
						<a href="?church-dashboard=user&&page=payment&tab=addexpense" class="padding_left_0 tab <?php echo $active_tab == 'addexpense' ? 'active' : ''; ?>">
						<?php esc_html_e('Add Expense', 'church_mgt'); ?></a> 
					</li> 
					<?php
				}
			}
			?>
		</ul> -->
		<div class="tab-content "><!--TAB CONTENT DIV STRAT-->
			<?php 
			if($active_tab == 'transactionlist')
			{ 
				$own_data=$user_access['own_data'];
				if($obj_church->role == 'member')
				{
					if($own_data == '1')
					{ 
						$transactiondata=$obj_transaction->MJ_cmgt_get_all_transaction_own_member($curr_user_id);
					}
					else
					{
						$transactiondata=$obj_transaction->MJ_cmgt_get_all_transaction();
					}
				}
				else
				{
					$transactiondata=$obj_transaction->MJ_cmgt_get_all_transaction();
				}
				if(!empty($transactiondata))
				{
					?>	
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="transaction_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Invoice Number', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Donation Type', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Transaction Date', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Method', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Invoice Number', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Donation Type', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Transaction Date', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Method', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php
									if(!empty($transactiondata))
									{
										$i=0;
										foreach ($transactiondata as $retrieved_data)
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
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Payments-white.png"?>" alt="" class="massage_image center">
													</p>
												</td>
												<td class="name width_20_per"><a href="?church-dashboard=user&&page=payment&tab=view_invoice&idtest=<?php echo esc_attr($retrieved_data->id);?>&invoice_type=transaction" class="color_black" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="transaction" id="<?php echo $retrieved_data->id ?>" href="#"><?php $user=get_userdata($retrieved_data->member_id);
												echo esc_attr($user->display_name);
												?></a> 
												</td>
												<td class="width_15_per">
													<?php 
														$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($retrieved_data->id)); 
														echo get_option( 'cmgt_payment_prefix' ).''.$invoice_number;?> 
												</td>
												<td class=" width_25_per"><?php echo get_the_title(esc_attr($retrieved_data->donetion_type));?> </td>

												<td class="stat_date width_15_per"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->transaction_date)));?> </td>

												<td class="total_amount width_15_per"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->amount);?> </td>

												<td class="method width_15_per"><?php 
													if($retrieved_data->pay_method == "cash")
													{
														$pay_method=esc_html__('Cash','church_mgt');
													}
													elseif($retrieved_data->pay_method == "check")
													{
														$pay_method=esc_html__('Check','church_mgt');
													}
													elseif($retrieved_data->pay_method == "bank_transfer")
													{
														$pay_method=esc_html__('Bank Transfer','church_mgt');
													}
													else{

														$pay_method = $retrieved_data->pay_method;

													}
													echo esc_attr($pay_method);?> 
												</td>
												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown mt-2">
														<ul class="">
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">

																	<li><a href="?church-dashboard=user&&page=payment&tab=view_invoice&idtest=<?php echo esc_attr($retrieved_data->id);?>&invoice_type=transaction" class="dropdown-item "><i class="fa fa-eye"></i><?php _e('View Invoice', 'church_mgt' ) ;?></a></li>
																		<?php 
																	if($user_access['edit'] == '1') 
																	{
																		?>
																		<li><a href="?church-dashboard=user&&page=payment&tab=addtransaction&action=edit&transaction_id=<?php echo esc_attr($retrieved_data->id); ?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i> <?php _e('Edit', 'church_mgt' ) ;?></a></li>
																		<?php
																	}
																	if($user_access['delete'] == '1') 
																	{
																		?>
																		<div class="cmgt-dropdown-deletelist">
																			<li>
																				<a href="?church-dashboard=user&&page=payment&tab=transactionlist&action=delete&transaction_id=<?php echo esc_attr($retrieved_data->id); ?>" class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');">
																					<i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?>
																				</a>
																			</li>
																		</div>
																		<?php 
																	} ?>
																</ul>
															</li>
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
							<a href="<?php echo home_url().'?church-dashboard=user&&page=payment&tab=addtransaction';?>">
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
			if($active_tab == 'addtransaction')
			{
				?>
				<script type="text/javascript">
					$(document).ready(function() 
					{
						$("#save_transaction").click(function() 
						{
							var ext = $('#member_list').val();
							if(ext =='' || ext == null)
							{
								alert("<?php _e('Please fill out all the required fields','church_mgt');?>");
								return false;	
							} 
						});
						
					});
				</script>
				<?php
				$transaction_id=0;
				if(isset($_REQUEST['transaction_id']))
					$transaction_id= sanitize_text_field($_REQUEST['transaction_id']);
					$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						$edit=1;
						$result = $obj_transaction->MJ_cmgt_get_single_transaction($transaction_id);
						
					}?>
				<div class="invoice_padding"><!--PANEL BODY DIV START-->
					<form name="transaction_form" action="" method="post" class="form-horizontal" id="transaction_form"><!--DONATION FORM START-->
						<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
						<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
						<input type="hidden" name="transaction_id" value="<?php echo esc_attr($transaction_id);?>" />
						<div class="form-body user_form">
							<div class="row">
								<div class="col-md-6 cmgt_display ">
									<div class="form-group row margin_buttom_0">
										<div class="col-md-12">
											<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?></label>	

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
													foreach ($membersdata as $member)
													{
														if(empty($member->cmgt_hash)){
														?>
														<option value="<?php echo esc_attr($member->ID);?>" <?php selected($member_id,$member->ID);?>><?php echo esc_attr($member->display_name)." - ".esc_attr($member->member_id); ?> </option>
														<?php
														}
													}
												}?>
											</select>
										</div>
									
									</div>
								</div>

								<div class="col-md-6 cmgt_display input">
									<div class="form-group input row margin_buttom_0">
										<div class="col-md-8">
											<label class="ml-1 custom-top-label top" for="Donations"><?php _e('Donation Type','church_mgt');?><span class="require-field">*</span></label>
											<select class="form-control line_height_30px validate[required]" name="donetion_type" id="donation_category">
												<option value=""><?php _e('Select Donations Type','church_mgt');?></option>
												<?php 
												if($edit)
													$category = sanitize_text_field($result->donetion_type);
												elseif(isset($_REQUEST['donetion_type']))
													$category = sanitize_text_field($_REQUEST['donetion_type']);  
												else 
													$category = "";
												
												$donation_category=MJ_cmgt_get_all_category('donation_category');
												if(!empty($donation_category))
												{
													foreach ($donation_category as $retrive_data)
													{
														echo '<option value="'.esc_attr($retrive_data->ID).'" '.selected($category,$retrive_data->ID).'>'.esc_attr($retrive_data->post_title).'</option>';
													}
												}?>
											</select>
										</div>
										<div class="col-md-4">
											<button class="btn btn-success width_100 save_btn rtl_margin_top_0px" id="addremove" model="donation_category"><?php _e('Add Or Remove','church_mgt');?></button>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="transaction_date" class="form-control validate[required]" type="text" name="transaction_date" 
											value="<?php if($edit){ echo date("Y-m-d",strtotime($result->transaction_date));}elseif(isset($_POST['transaction_date'])){ echo esc_attr($_POST['transaction_date']);}else{ echo date('Y-m-d');}?>" autocomplete="off" readonly>
											<label class="" for="checkin_date"><?php _e('Transaction Date','church_mgt');?><span class="require-field">*</span></label>
										</div>	
									</div>	
								</div>
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="amount" class="form-control validate[required,min[0],maxSize[8]] text-input" step="0.01" maxlength="8" type="text"  name="amount" 
											<?php if($edit){ ?>value="<?php echo esc_attr($result->amount);}elseif(isset($_POST['amount'])) echo esc_attr($_POST['amount']);?>">
											<label class="" for="amount"><?php _e('Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-6  cmgt_display">
									<label class="ml-1 custom-top-label top" for="reservation_date"><?php _e('Method','church_mgt');?><span class="require-field">*</span></label>
										<?php if($edit)
											$period= sanitize_text_field($result->pay_method);
											elseif(isset($_POST['pay_method'])) 
											$period= sanitize_text_field($_POST['pay_method']);
											else
											$period=''; 
										?>
									<select class="form-control" name="pay_method" id="pay_method">
										<option value="cash" <?php selected($period,'cash');?>><?php _e('Cash','church_mgt');?></option>
										<option value="check" <?php selected($period,'check');?>><?php _e('Check','church_mgt');?></option>
										<option value="bank_transfer" <?php selected($period,'bank_transfer');?>><?php _e('Bank Transfer','church_mgt');?></option>
									</select>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
										<div class="col-md-12 cmgt_form_description form-control">
											<textarea name="description" class="form-control textarea_height validate[custom[address_description_validation]]" maxlength="150" id="description"><?php if($edit){ echo esc_textarea($result->description);}?></textarea>
											<label class="" for="description"><?php _e('Comment','church_mgt');?></label>
										</div>
									</div>	
								</div>
							</div>
							<div class="row">
								<?php wp_nonce_field( 'save_transaction_nonce' ); ?>
								<div class="col-md-6 mt-2">
									<input id="save_transaction" type="submit" value="<?php if($edit){ esc_html_e('Save Transaction','church_mgt'); }else{ esc_html_e('Add Transaction','church_mgt');}?>" name="save_transaction" class="btn btn-success save_btn"/>
								</div>
							</div>
						</div>
					</form><!--DONATION FORM END-->
				</div><!--PANEL BODY DIV END-->
				<?php 
			}
		 ?>
	
		<!------INCOME PART START-------->	
			<?php 
			if($active_tab == 'incomelist')
			{ 
				$own_data=$user_access['own_data'];
				if($obj_church->role == 'member')
				{
					if($own_data == '1')
					{ 
						$paymentdata=$obj_payment->MJ_cmgt_get_all_income_data_own_member($curr_user_id);
					}
					else
					{
						$paymentdata=$obj_payment->MJ_cmgt_get_all_income_data();
					}
				}
				else
				{
					$paymentdata=$obj_payment->MJ_cmgt_get_all_income_data();
				}
				if(!empty($paymentdata))
				{
					?>	
					<div class="padding_left_15px "><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="tblincome" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Income Title', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Invoice Number', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Payment Status', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Amount', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Date', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Income Title', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Invoice Number', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Payment Status', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Amount', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Date', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									$i=0;
									foreach ($paymentdata as $retrieved_data)
									{ 
										$all_entry=json_decode($retrieved_data->entry);
										$total_amount=0;
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
											foreach($all_entry as $entry)
											{
												$total_amount+=$entry->amount;
											}  
											?>
											<tr>
												<td class="user_image width_50px profile_image_prescription padding_left_0">
													<p class="padding_15px prescription_tag <?php echo $color_class; ?>">	
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Income-white.png"?>" alt="" class="massage_image center">
													</p>
												</td>

												<td class="name width_20_per"><a class="color_black show-invoice-popup" idtest="<?php echo esc_attr($retrieved_data->invoice_id); ?>" invoice_type="income" href="#"><?php echo esc_attr(ucfirst($retrieved_data->invoice_label));?></a> </td>
												<td class="width_15_per">
													<?php 
														$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($retrieved_data->invoice_id)); 
														echo get_option( 'cmgt_payment_prefix' ).''.$invoice_number;?> 
												</td>
												<td class="member_name width_20_per">
													<?php 
														$user=get_userdata($retrieved_data->supplier_name);
														$memberid=get_user_meta($retrieved_data->supplier_name,'member_id',true);
														$display_label=$user->display_name;
														if($memberid)
															$display_label.=" (".$memberid.")";
														echo $display_label;
													?> 
												</td>

												<td class="payment_status width_15_per"><span class="<?php if($retrieved_data->payment_status == "Unpaid"){ ?>red_color<?php }elseif($retrieved_data->payment_status == "Paid"){ ?>green_color<?php }else{ echo"blue_color";} ?>"><?php echo _e($retrieved_data->payment_status,'church_mgt');?></span> </td>

												<td class="income_amount width_15_per"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($total_amount);?> </td>

												<td class="date width_15_per"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->invoice_date)));?> </td>
												
												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																	
																	<li><a  href="?church-dashboard=user&&page=payment&tab=view_invoice&idtest=<?php echo esc_attr($retrieved_data->invoice_id);?>&invoice_type=income" class="dropdown-item"><i class="fa fa-eye"></i><?php _e('View income', 'church_mgt');?></a></li>
																	<?php 
																	if($user_access['add'] == '1') 
																	{
																		?>
																		<li><a href="?church-dashboard=user&&page=payment&tab=addincome&action=edit&income_id=<?php echo esc_attr($retrieved_data->invoice_id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																		<?php
																	}
																	if($user_access['delete'] == '1') {
																	?>
																	<div class="cmgt-dropdown-deletelist">
																		<li><a href="?church-dashboard=user&&page=payment&tab=incomelist&action=delete&income_id=<?php echo esc_attr($retrieved_data->invoice_id);?>" class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?> </a></li>
																	</div>
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
								?>

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
							<a href="<?php echo home_url().'?church-dashboard=user&&page=payment&tab=addincome';?>">
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
			if($active_tab == 'addincome')
			{
				?>
				<script type="text/javascript">
					$(document).ready(function() 
					{
						//---member validation-----//
						$("#save_income").click(function() 
						{
							var ext = $('#member_list').val();
							if(ext =='' || ext == null)
							{
								alert("<?php _e('Please fill out all the required fields','church_mgt');?>");
								return false;	
							} 
						});
					});
				</script>
				<?php
					$income_id=0;
					if(isset($_REQUEST['income_id']))
						$income_id= sanitize_text_field($_REQUEST['income_id']);
						$edit=0;
						if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
						{
							$edit=1;
							$result = $obj_payment->MJ_cmgt_get_income_data($income_id);
						}?>
						<div class="invoice_padding"><!--PANEL BODY DIV START-->
							<form name="income_form" action="" method="post" class="form-horizontal" id="income_form"><!--INCOME FORM START-->
								<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
								<input type="hidden" name="action" value="<?php echo $action;?>">
								<input type="hidden" name="income_id" value="<?php echo $income_id;?>">
								<input type="hidden" name="invoice_type" value="income">
								<div class="form-body user_form">
									<div class="row">
										<div class="col-md-6 cmgt_display input">
											<div class="form-group  row margin_buttom_0">
												<div class="col-md-12">
													<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?></label>	

													<?php if($edit){ $member_id=$result->supplier_name; }elseif(isset($_POST['member_id'])){$member_id=$_POST['member_id'];}else{$member_id='';}?>
													<select id="member_list" class="form-control line_height_30px" name="supplier_name">
														<option value=""><?php _e('Select Member','church_mgt');?></option>
														<?php $get_members = array('role' => 'member');
														$membersdata=get_users($get_members);
														if(!empty($membersdata))
														{
															foreach ($membersdata as $member)
															{
																if(empty($member->cmgt_hash)){
																?>
																<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
																<?php
															} }
														}?>
													</select>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group input">
											<div class="col-md-12 form-control">
													<input id="invoice_label" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->invoice_label);}elseif(isset($_POST['invoice_label'])) echo esc_attr($_POST['payment_title']);?>" name="invoice_label">
													<label class="" for="invoice_label"><?php _e('Income label','church_mgt');?><span class="require-field">*</span></label>
												</div>
											</div>	
										</div>

										<div class="col-md-6 input cmgt_display">
											<label class="ml-1 custom-top-label top status"for="payment_status"><?php _e('Status','church_mgt');?><span class="require-field">*</span></label>
											<select name="payment_status" id="payment_status" class="form-control validate[required]">
												<option value="Paid"
													<?php if($edit)selected('Paid',$result->payment_status);?> ><?php _e('Paid','church_mgt');?></option>
												<option value="Part Paid"
													<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php _e('Part Paid','church_mgt');?></option>
													<option value="Unpaid"
													<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php _e('Unpaid','church_mgt');?></option>
											</select>
										</div>
										<div class="col-md-6">
											<div class="form-group input">
											<div class="col-md-12 form-control">
													<input id="invoice_date" class="form-control " type="text"  value="<?php if($edit){ echo date("Y-m-d",strtotime($result->invoice_date));}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date("Y-m-d");}?>" name="invoice_date" autocomplete="off" readonly>
													<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="invoice_date"><?php _e('Date','church_mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>
										<hr class="margin_top_10">
											<?php 
											if($edit)
											{
												$all_entry=json_decode($result->entry);
											}
											else
											{
												if(isset($_POST['income_entry'])){
													
													$all_data=$obj_payment->MJ_cmgt_get_entry_records($_POST);
													$all_entry=json_decode($all_data);
												}
											}
											if(!empty($all_entry))
											{
												foreach($all_entry as $entry)
												{
													?>
													<div id="income_entry" class="col-md-12">
														<div class="row form-group input">

															<div class="col-md-3 recently_appoinment_card">
																<div class="form-group input">
																	<div class="col-md-12 form-control">
																		<input id="income_amount" class="form-control validate[required,custom[amount]] text-input" maxlength="10" type="text" value="<?php echo $entry->amount;?>" name="income_amount[]">
																		<label class="" for="income_entry"><?php _e('Income Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
																	</div>
																</div>
															</div>
															<div class="col-md-3 recently_appoinment_card">
																<div class="form-group input">
																	<div class="col-md-12 form-control">
																		<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" type="text" maxlength="50" value="<?php echo esc_attr($entry->entry);?>" name="income_entry[]">
																		<label class="" for="income_entry"><?php _e('Income Label','church_mgt');?></label>
																	</div>
																</div>
															</div>
															<div class="col-md-1 frontend_income_deopdown_div rtl_margin_top_15px">
																<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Delete.png"?>" alt="" onclick="deleteParentElement(this)" class="massage_image center entypo-trash">
															</div>
														</div>	
													</div>
													<?php 
												}
											}
											else
											{	
												?>
												<div id="income_entry" class="col-md-12">
													<div class="row form-group input">
														<div class="col-md-3 recently_appoinment_card">
															<div class="form-group input">
																<div class="col-md-12 form-control">
																	<input id="income_amount" class="form-control validate[required,custom[amount]] text-input" maxlength="10" type="text" name="income_amount[]">
																	<label class="" for="income_entry"><?php _e('Income Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
																</div>
															</div>
														</div>

														<div class="col-md-3 recently_appoinment_card">
															<div class="form-group input">
																<div class="col-md-12 form-control">
																	<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="income_entry[]" >
																	<label class="" for="income_entry"><?php _e('Income Label','church_mgt');?></label>
																</div>	
															</div>
														</div>	

														<div class="col-md-1 frontend_income_deopdown_div rtl_margin_top_15px">
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="daye_name_onclickr" id="add_new_entry">
														</div>

													</div>	
												</div>
												<?php 
											} ?>
										<hr class="margin_top_10">
									</div>
									<div class="row">
										<div class="col-md-6 mt-2">
											<?php wp_nonce_field( 'save_income_nonce' ); ?>
											<div class="offset-sm-0">
												<input id="save_income" type="submit" value="<?php if($edit){ esc_html_e('Save Income','church_mgt'); }else{ esc_html_e('Create Income Entry','church_mgt');}?>" name="save_income" class="btn btn-success save_btn"/>
											</div>
										</div>	
									</div>
								</div>
							</form><!--INCOME FORM END-->
						</div><!--PANEL BODY DIV END-->
						<script>
							// CREATING BLANK INCOME ENTRY
							function add_entry()
							{
								$("#income_entry").append('<div id="income_entry" class="col-md-12"><div class="row form-group input"><div class="col-md-3 recently_appoinment_card"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_amount" class="form-control validate[required,custom[amount]] text-input" maxlength="10" type="text" name="income_amount[]"><label class="" for="income_entry"><?php _e('Income Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label></div></div></div><div class="col-md-3 recently_appoinment_card"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="income_entry[]" ><label class="" for="income_entry"><?php _e('Income Label','church_mgt');?></label></div></div></div><div class="col-md-1 frontend_income_deopdown_div rtl_margin_top_15px"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Delete.png"?>" alt="" onclick="deleteParentElement(this)" class="massage_image entypo-trash"></div></div></div>'); 
							}
							
							// REMOVING INVOICE ENTRY
							function deleteParentElement(n)
							{
								n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
							}
						</script> 
				<?php 
			}
		 ?>	
			<!------EXPENCE PART START-------->	
			<?php 
			if($active_tab == 'expenselist')
			{ 
				$payment_expense_data=$obj_payment->MJ_cmgt_get_all_expense_data();
				if(!empty($payment_expense_data))
				{
					?>	
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="tblexpence" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Supplier Name', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Amount', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Payment Status', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Date', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Supplier Name', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Amount', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Payment Status', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Date', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									$i=0;
									foreach ($payment_expense_data as $retrieved_data)
									{ 
										$all_entry=json_decode($retrieved_data->entry);
										$total_amount=0;
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

										foreach($all_entry as $entry)
										{
											$total_amount+=$entry->amount;
										}	?>
										<tr>
											<td class="user_image width_50px profile_image_prescription padding_left_0">
												<p class="padding_15px prescription_tag <?php echo $color_class; ?>">	
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Expenses-white.png"?>" alt="" class="massage_image center">
												</p>
											</td>

											<td class="party_name width_25_per"><a class="color_black show-invoice-popup"  idtest="<?php echo esc_attr($retrieved_data->invoice_id); ?>" invoice_type="expense" href="#"><?php echo esc_attr($retrieved_data->supplier_name);?></a> </td>

											<td class="income_amount width_20_per"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($total_amount);?> </td>

											<td class="payment_status width_20_per <?php if($retrieved_data->payment_status == "Unpaid"){ ?>red_color<?php }elseif($retrieved_data->payment_status == "Paid"){ ?>green_color<?php }else{ echo"blue_color";} ?>"><?php echo esc_attr($retrieved_data->payment_status);?> </td>

											<td class="date width_20_per"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->invoice_date)));?> </td>

											<td class="action cmgt_pr_0px">
												<div class="cmgt-user-dropdown mt-2">
													<ul class="">
														<!-- BEGIN USER LOGIN DROPDOWN -->
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
															</a>
															<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">

																<li><a  href="?church-dashboard=user&&page=payment&tab=view_invoice&idtest=<?php echo esc_attr($retrieved_data->invoice_id);?>&invoice_type=expense" class="dropdown-item" >
																<i class="fa fa-eye"></i> <?php _e('View Expense', 'church_mgt');?></a></li>
																<?php
																if($user_access['add'] == '1') 
																{
									  								?>
																	<li><a href="?church-dashboard=user&&page=payment&tab=addexpense&action=edit&expense_id=<?php echo esc_attr($retrieved_data->invoice_id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																	<?php
																}
																if($user_access['delete'] == '1') {
																	?>
																	<div class="cmgt-dropdown-deletelist">
																		<li><a href="?church-dashboard=user&&page=payment&tab=expenselist&action=delete&expense_id=<?php echo esc_attr($retrieved_data->invoice_id);?>" class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?> </a></li>
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
									} ?>
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
							<a href="<?php echo home_url().'?church-dashboard=user&&page=payment&tab=addexpense';?>">
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
			if($active_tab == 'addexpense')
			{
					$expense_id=0;
					if(isset($_REQUEST['expense_id']))
						$expense_id= sanitize_text_field($_REQUEST['expense_id']);
						$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						$edit=1;
						$result = $obj_payment->MJ_cmgt_get_income_data($expense_id);
					}?>
				<div class="><!--PANEL BODY DIV START-->
					<form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form"><!--EXPENSE  FORM START-->
						<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
						<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
						<input type="hidden" name="expense_id" value="<?php echo esc_attr($expense_id);?>">
						<input type="hidden" name="invoice_type" value="expense">
						<div class="form-body user_form">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="supplier_name" class="form-control validate[required,cusom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->supplier_name);}elseif(isset($_POST['supplier_name'])) echo esc_attr($_POST['supplier_name']);?>" name="supplier_name">
											<label class="" for="patient"><?php _e('Supplier Name','church_mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-6 input cmgt_display">
									<label class="ml-1 custom-top-label top status" for="payment_status"><?php _e('Status','church_mgt');?><span class="require-field">*</span></label>
									<select name="payment_status" id="payment_status" class="form-control validate[required]">
										<option value="Paid"
											<?php if($edit)selected('Paid',$result->payment_status);?> ><?php _e('Paid','church_mgt');?></option>
										<option value="Part Paid"
											<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php _e('Part Paid','church_mgt');?></option>
										<option value="Unpaid"
											<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php _e('Unpaid','church_mgt');?></option>
									</select>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="invoice_date" class="form-control validate[required]" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime($result->invoice_date));}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date("Y-m-d");}?>" name="invoice_date" autocomplete="off" readonly>
											<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="invoice_date"><?php _e('Date','church_mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>

								<hr class="margin_top_10">
								<?php 
								if($edit)
								{
									$all_entry=json_decode($result->entry);
								}
								else
								{
									if(isset($_POST['income_entry']))
									{
										$all_data=$obj_payment->MJ_cmgt_get_entry_records($_POST);
										$all_entry=json_decode($all_data);
									}
								}
								if(!empty($all_entry))
								{
									foreach($all_entry as $entry)
									{
										?>
										<div id="expense_entry" class="col-md-12">
											<div class="row form-group input">

												<div class="col-md-3 recently_appoinment_card">
													<div class="form-group input">
														<div class="col-md-12 form-control">
															<input id="income_amount" class="form-control validate[required,custom[amount]] text-input" maxlength="10" type="text" value="<?php echo esc_attr($entry->amount);?>" name="income_amount[]" >
															<label class="" for="income_entry"><?php _e('Expense Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
														</div>
													</div>
												</div>

												<div class="col-md-3 recently_appoinment_card">
													<div class="form-group input">
														<div class="col-md-12 form-control">
															<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php echo esc_attr($entry->entry);?>" name="income_entry[]">
															<label class="" for="income_entry"><?php _e('Expense Label','church_mgt');?></label>
														</div>
													</div>
												</div>	

												<div class="col-md-1 frontend_income_deopdown_div rtl_margin_top_15px">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Delete.png"?>" alt="" onclick="deleteParentElement(this)" class="massage_image center entypo-trash">
												</div>

											</div>	
										</div>
										<?php 
									}
								}
								else
								{
									?>
									<div id="expense_entry" class="col-md-12">
										<div class="row form-group input">
											<div class="col-md-3 recently_appoinment_card">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="income_amount" class="form-control " type="text" maxlength="10" name="income_amount[]">
														<label class="" for="income_entry"><?php _e('Expense Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
													</div>
												</div>
											</div>
											<div class="col-md-3 recently_appoinment_card">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" type="text" maxlength="50"  name="income_entry[]">
														<label class="" for="income_entry"><?php _e('Expense Label','church_mgt');?><span class="require-field">*</span></label>
													</div>
												</div>
											</div>

											<div class="col-md-1 frontend_income_deopdown_div rtl_margin_top_15px">
												<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="daye_name_onclickr" id="add_new_entry">
											</div>
										</div>	
									</div>
									<?php 
								} ?>
									
								<hr class="margin_top_10">
							</div>

							<div class="row">
								<div class="col-md-6 mt-2">
									<?php wp_nonce_field( 'save_expense_nonce' ); ?>
									<div class="offset-sm-0">
										<input type="submit" value="<?php if($edit){ esc_html_e('Save Expense','church_mgt'); }else{ esc_html_e('Create Expense Entry','church_mgt');}?>" name="save_expense" class="btn btn-success save_btn"/>
									</div>
								</div>	
							</div>
						</div>
					</form><!--EXPENSE  FORM END-->
				</div><!--PANEL BODY DIV END-->
				<script>
					// CREATING BLANK INVOICE ENTRY
					function add_entry()
					{
					$('#expense_entry').append('<div id="expense_entry" class="col-md-12"><div class="row form-group input"><div class="col-md-3 recently_appoinment_card"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_amount" class="form-control validate[required,custom[amount]] text-input" type="text" maxlength="10" name="income_amount[]"><label class="" for="income_entry"><?php _e('Expense Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label></div></div></div><div class="col-md-3 recently_appoinment_card"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" type="text" maxlength="50" name="income_entry[]"><label class="" for="income_entry"><?php _e('Expense Label','church_mgt');?><span class="require-field">*</span></label></div></div></div><div class="col-md-1 frontend_income_deopdown_div rtl_margin_top_15px"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Delete.png"?>" alt="" onclick="deleteParentElement(this)" class="massage_image  entypo-trash"></div></div></div>');
					}
					// REMOVING INVOICE ENTRY
					function deleteParentElement(n){
						n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
					}
				</script> 
	
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