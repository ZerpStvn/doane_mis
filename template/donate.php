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
require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
//---------- SAVE Transaction DATA-------------//
if(isset($_POST['save_transaction']))
{
	if($_POST['pay_method']=='Paypal')
	{				
		require_once CMS_PLUGIN_DIR. '/lib/paypal/paypal_process.php';	
	}
	elseif($_POST['pay_method'] == 'Stripe')
	{
		require_once PM_PLUGIN_DIR. '/lib/stripe/index.php';			
	}
	elseif($_POST['pay_method'] == 'Skrill')
	{		
		require_once PM_PLUGIN_DIR. '/lib/skrill/skrill.php';
	}
	elseif($_POST['pay_method'] == 'Instamojo')
	{
		require_once PM_PLUGIN_DIR. '/lib/instamojo/instamojo.php';
	}
	elseif($_POST['pay_method'] == 'PayUMony')
	{
		require_once PM_PLUGIN_DIR. '/lib/OpenPayU/index.php';			
	}
	elseif($_REQUEST['pay_method'] == '2CheckOut'){				
		require_once PM_PLUGIN_DIR. '/lib/2checkout/index.php';
	}
	elseif($_POST['pay_method'] == 'iDeal')
	{
		require_once PM_PLUGIN_DIR. '/lib/ideal/ideal.php';
	}
	elseif($_POST['pay_method'] == 'Paystack')
	{
		require_once PM_PLUGIN_DIR. '/lib/paystack/paystack.php';
	}
	elseif($_POST['pay_method'] == 'paytm')
	{
		require_once PM_PLUGIN_DIR. '/lib/PaytmKit/index.php';
	}
	elseif($_POST['pay_method'] == 'razorpay')
	{
		require_once CMS_PLUGIN_DIR. '/lib/razorpay/index.php';
	}
	else
	{
	
		$result=$obj_transaction->MJ_cmgt_add_transaction($_POST);
	
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&&page=donate&tab=transactionlist&message=1');
		}
	}
}

if(isset($_REQUEST['action'])&& $_REQUEST['action']=='success')
	{
	?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e('Payment successfully','church-mgt');
			?>
			<button type="button" class="	 btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php
	}
	
	if(isset($_REQUEST['payment_status']) && $_REQUEST['action'] == 'paypal_payment' && $_REQUEST['payment_status'] == 'Completed')

	{  

        $transaction = new Cmgttransaction();

		$donatedata['member_id']=sanitize_text_field($_REQUEST['custom']);

		$donatedata['amount']=sanitize_text_field($_REQUEST['mc_gross_1']);

		$donatedata['pay_method']='paypal';	

		$donatedata['transaction_date']=date('Y-m-d');

		$donatedata['transaction_id']=sanitize_text_field($_REQUEST["txn_id"]);

		$donatedata['description']="";

		$donatedata['donetion_type']=sanitize_text_field($_REQUEST['item_name1']);

		$donatedata['action']='insert';

		$result = $transaction->MJ_cmgt_add_transaction($donatedata);
		if($result)
		{
			wp_redirect ( home_url() . '?church-dashboard=user&page=donate&action=success');
			die;
		}
	}
	
if(isset($_REQUEST['action']) && $_REQUEST['action']=="ideal_payments" && $_REQUEST['page']=="donate" && isset(
		$_REQUEST['ideal_pay_id']) && isset($_REQUEST['ideal_amt']))
	{	
		$transaction = new Cmgttransaction();
		$data['member_id']=get_current_user_id();
		$data['amount']= sanitize_text_field($_REQUEST['ideal_amt']);
		$data['donetion_type']=sanitize_text_field($_REQUEST['donetion_type']);
		$data['pay_method']='ideal';
		$data['transaction_id']='';
		$data['created_date']=date("Y-m-d");
		$data['created_by']=get_current_user_id();
		$data['transaction_date']=date('Y-m-d');
		$data['description']="";
		$result = $transaction->MJ_cmgt_add_transaction($data);
		if($result){ 
			wp_redirect ( home_url() . '?church-dashboard=user&page=donate&action=success');
		}	
	}
if(isset($_REQUEST['fees_pay_id']) && (isset($_REQUEST['amount'])))
{
	$transaction = new Cmgttransaction();
	$data['member_id']=sanitize_text_field($_REQUEST['fees_pay_id']);
	$data['amount']=sanitize_text_field($_REQUEST['amount']);
	$data['donetion_type']=sanitize_text_field($_REQUEST['donetion_type']);
	$data['pay_method']='Skrill';
	$data['transaction_id']='';
	$data['created_date']=date("Y-m-d");
	$data['created_by']=get_current_user_id();;
	$data['transaction_date']=date('Y-m-d');
	$data['description']="";
	$result = $transaction->MJ_cmgt_add_transaction($data);
	if($result)
	{ 
		wp_redirect ( home_url() . '?church-dashboard=user&page=donate&action=success');
	}	
}
if(isset($_REQUEST['amount']) && (isset($_REQUEST['donet_pay_id'])))
{
	$transaction = new Cmgttransaction();
	$data['member_id']=sanitize_text_field($_REQUEST['donet_pay_id']);
	$data['amount']=sanitize_text_field($_REQUEST['amount']);
	$data['donetion_type']=sanitize_text_field($_REQUEST['donetion_type']);
	$data['pay_method']='Instamojo';	
	$data['transaction_id']=sanitize_text_field($_REQUEST['payment_request_id']);
	$data['created_date']=date("Y-m-d");
	$data['created_by']=get_current_user_id();
	$data['transaction_date']=date('Y-m-d');
	$data['description']="";
	$result = $transaction->MJ_cmgt_add_transaction($data);
	if($result)
	{ 
		wp_redirect ( home_url() . '?church-dashboard=user&page=donate&action=success');
	}	
}
//------------PAYSTACK SUCCESS ----------------------//
$reference='';
$reference = sanitize_text_field(isset($_GET['reference']) ? $_GET['reference'] : '');
if($reference)
{
      $paystack_secret_key=get_option('paystack_secret_key');
	  $curl = curl_init();
	  curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_HTTPHEADER => [
		"accept: application/json",
		"authorization: Bearer $paystack_secret_key",
		"cache-control: no-cache"
	  ],
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	if($err)
	{
		// there was an error contacting the Paystack API
	  die('Curl returned error: ' . $err);
	}
	$tranx = json_decode($response);
	if(!$tranx->status)
	{
	  // there was an error from the API
	  die('API returned error: ' . $tranx->message);
	}
	if('success' == $tranx->data->status)
	{
		$trasaction_id  = sanitize_text_field($tranx->data->reference);
		$transaction = new Cmgttransaction();
		$data['member_id']= sanitize_text_field($tranx->data->metadata->custom_fields->fees_pay_id);
		$data['amount']=sanitize_text_field($tranx->data->amount / 100);
		$data['donetion_type']=sanitize_text_field($tranx->data->metadata->custom_fields->donetion_type);
		$data['pay_method']='Paystack';	
		$data['transaction_id']=$trasaction_id;
		$data['created_date']=date("Y-m-d");
		$data['created_by']=get_current_user_id();
		$data['transaction_date']=date('Y-m-d');
		$data['description']="";
		$result = $transaction->MJ_cmgt_add_transaction($data);
		if($result)
		{ 
			wp_redirect ( home_url() . '?church-dashboard=user&page=donate&action=success');
		}	
	}
}
 
//Paytm Success//
if(isset($_REQUEST['STATUS']) && $_REQUEST['STATUS'] == 'TXN_SUCCESS')
{ 
	$custom_array = explode("_",sanitize_text_field($_REQUEST['ORDERID']));
	$trasaction_id  =  sanitize_text_field($_REQUEST["TXNID"]);
	$transaction = new Cmgttransaction();
	$data['member_id']=$custom_array[1];
	$data['amount']= sanitize_text_field($_REQUEST['TXNAMOUNT']);
	$data['donetion_type']= sanitize_text_field($_REQUEST['MERC_UNQ_REF']);
	$data['pay_method']='Paytm';	
	$data['transaction_id']=$trasaction_id;
	$data['created_date']=date("Y-m-d");
	$data['created_by']=get_current_user_id();
	$data['transaction_date']=date('Y-m-d');
	$data['description']="";
	 
	$result = $transaction->MJ_cmgt_add_transaction($data);
	if($result)
	{ 
		wp_redirect ( home_url() . '?church-dashboard=user&page=donate&action=success');
	}	
}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='cancel')
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible fade in" role="alert">
			<?php
			esc_html_e('Payment Cancel','school-mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
			<?php
	}	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['transaction_id'])){
			$result=$obj_transaction->MJ_cmgt_get_my_aadonation(sanitize_text_field($_REQUEST['transaction_id']));
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=donate&tab=transactionlist&message=3');
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

	$("#save_transaction").click(function() {
        var ext = $('#amount').val();
        if (ext == '' || ext == null) {
            alert("<?php _e('Please fill out all the required fields','church_mgt');?>");
            return false;
        }
    });
}); 
</script>
	<script type="text/javascript">
		$(document).ready(function()
		{
			jQuery('#transaction_list').DataTable({
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
			//-----Add Transaction--------	//	
			$('#transaction_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			$(".display-members").select2();
			jQuery('#transaction_date').datepicker({
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
			if($active_tab == 'transactionlist')
			{ 
				if($obj_church->role == 'accountant')
				{
					$transactiondata=$obj_transaction->MJ_cmgt_get_all_transaction();
				}
				else
				{
					$transactiondata=$obj_transaction->MJ_cmgt_get_my_donationlist($curr_user_id);
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
										<th><?php _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Donation Type', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Transaction Date', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Amount', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Method', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Donation Type', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Transaction Date', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Amount', 'church_mgt' ) ;?></th>
										<th> <?php _e( 'Method', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php  
									$i=0;
									if(!empty($transactiondata))
									{
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
												<td class="name width_20_per"><a class="color_black show-invoice-popup" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="transaction" id="<?php echo $retrieved_data->id ?>" href="#"><?php $user=get_userdata($retrieved_data->member_id);
												echo esc_attr($user->display_name);
												?></a> 
												</td>

												<td class=" width_25_per"><?php echo get_the_title(esc_attr($retrieved_data->donetion_type));?> </td>

												<td class="stat_date width_15_per"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->transaction_date)));?> </td>

												<td class="total_amount width_15_per"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->amount);?> </td>
												<td class="method width_15_per"><?php echo esc_attr($retrieved_data->pay_method);?> </td>

												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																	<li><a href="?church-dashboard=user&page=donate&tab=view_invoice&idtest=<?php echo esc_attr($retrieved_data->id);?>&invoice_type=transaction" class="dropdown-item"><i class="fa fa-eye"></i><?php _e('View Invoice', 'church_mgt' ) ;?></a></li>
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
							<a href="<?php echo home_url().'?church-dashboard=user&page=donate&tab=addtransaction';?>">
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
				
					$("#save_transaction").click(function() {
						var ext = $('#amount').val();
						if (ext == '' || ext == null) {
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
					}
					$user = wp_get_current_user ();
					$membersdata =get_userdata( sanitize_text_field($user->ID));
					$member_id = $membersdata->ID;
					?>

				<div class="padding_frontendlist_body"><!--PANEL BODY DIV START-->
					<form name="transaction_form" action="" method="post" class="form-horizontal padding_15" id="transaction_form"><!--DONETION FORM START-->
						<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
						<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
						<input type="hidden" name="transaction_id" value="<?php echo esc_attr($transaction_id);?>" />
						<input type="hidden" name="invoice_number" value="<?php echo esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($transaction_id)); ?>">
						<?php 
						if($obj_church->role == 'member')
						{
							?>
							<input type="hidden" name="member_id" value="<?php echo esc_attr($member_id); ?>">
							<?php
						} 
						?>

						<div class="form-body user_form">
							<div class="row">
								<div class="col-md-6 cmgt_display ">
									<div class="form-group input row margin_buttom_0">
										<div class="col-md-12">
											<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>	
											<?php
											if($obj_church->role == 'member')
											{
											?>
												<select class="form-control line_height_30px" name="member_id" id="member_list" disabled>
											<?php
											}
											else
											{
												?>
												<select class="form-control line_height_30px" name="member_id" id="member_list">
												<?php
											}
											?>
												<option value=""><?php _e('Select Member','church_mgt');?></option>
												<?php
												if($obj_church->role == 'member')
												{
													if($edit)
													{
														$member_id=sanitize_text_field($result->member_id);
													}
													else
													{
														$user = wp_get_current_user ();
														
														$membersdata =get_userdata( sanitize_text_field($user->ID));
														$member_id = $membersdata->ID;
													}
												}
												else
												{
													if($edit)
													{
														$member_id=sanitize_text_field($result->member_id);
													}
													elseif(isset($_POST['start_date'])) 
													{
														$member_id=sanitize_text_field($_POST['start_date']);
													}
													else
													{
														$member_id=0;
													}
												}
											
												if($obj_church->role == 'member')
												{
													$user = wp_get_current_user ();
													$membersdata =get_userdata( sanitize_text_field($user->ID));
												}
												else
												{
													$get_members = array('role' => 'member');
													$membersdata=get_users($get_members);
												}
											
												if(!empty($membersdata))
												{
													foreach ($membersdata as $member)
													{
														$u_id= $member->ID;
														$user_member_id =get_user_meta($u_id, 'member_id', true);
														?>
														<option value="<?php echo esc_attr($member->ID);?>" <?php selected($member_id,$member->ID);?>><?php echo esc_attr($member->display_name)." - ".esc_attr($user_member_id); ?> </option>
													<?php 
													}
												}?>
											</select>
										</div>
									</div>
								</div>

								<div class="col-md-6 cmgt_display ">
									<div class="form-group input row margin_buttom_0">
										<div class="col-md-12">
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
									<select class="form-control validate[required]" name="pay_method" id="pay_method">
										<option value=""><?php esc_html_e('Select Payment Method','church_mgt');?></option>
										<?php
											if(is_plugin_active('paymaster/paymaster.php') && get_option('cmgt_paymaster_pack')=="yes") 
											{ 
												include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
												
												$payment_method = get_option('pm_payment_method');
												print '<option value="'.$payment_method.'">'.$payment_method.'</option>';
											} 
											else
											{
												echo '<option value="Paypal">Paypal</option>';
											
											} 
										?>
									</select>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
										<div class="col-md-12 cmgt_form_description form-control">
											<textarea name="description" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="description"><?php if($edit){ echo esc_textarea($result->description);}?></textarea>
											<label class="" for="description"><?php _e('Comment','church_mgt');?></label>
										</div>
									</div>	
								</div>
							</div>
							<div class="row">
								<?php wp_nonce_field( 'save_transaction_nonce' ); ?>
								<div class="col-md-6 mt-2">
									<input type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Give Donation','church_mgt');}?>" name="save_transaction"  id="save_transaction" class="btn btn-success save_btn"/>
								</div>
							</div>
						</div>
					</form><!--DONETION FORM END-->
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