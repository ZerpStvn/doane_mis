<?php
$obj_transaction=new Cmgttransaction;
 ?>
<?php
// if(isset($_POST['attendance_report']))
// {
// 	global $wpdb;
// 	$table_name=$wpdb->prefix.'cmgt_transaction';
// 	$sdate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['sdate']));
// 	$edate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['edate']));
// 	$result = $wpdb->get_results("select *from $table_name where created_date BETWEEN '$sdate' AND '$edate'");  
	
// }
// if($active_tab == 'payment_data' && empty($_POST['attendance_report']))
// {
// 	global $wpdb;
// 	$table_name=$wpdb->prefix.'cmgt_transaction';
// 	$start_date = date('Y-m-d',strtotime('first day of this month'));
// 	$end_date = date('Y-m-d',strtotime('last day of this month'));
// 	$result = $wpdb->get_results("select *from $table_name where created_date BETWEEN '$start_date' AND '$end_date'");  
	
// }
?>
<script type="text/javascript">
	$(document).ready(function() 
	{ 
	   $(".sdate").datepicker({
       	dateFormat: "yy-mm-dd",
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".edate").datepicker("option", "minDate", dt);
        }
	    });
	    $(".edate").datepicker({
	      dateFormat: "yy-mm-dd",
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() - 0);
	            $(".sdate").datepicker("option", "maxDate", dt);
	        }
	    });	
		
	});
</script>

    	<div class="panel-body clearfix margin_top_20px">
			<form method="post" id="payment_data">  
				<div class="form-body user_form">
					<div class="row">
						<div class="col-md-3 mb-3 input">
							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date Type','church_mgt');?><span class="require-field">*</span></label>			
								<select class="line_height_30px form-control date_type validate[required]" name="date_type" autocomplete="off">
									<option value=""><?php  _e( 'Select', 'church_mgt' ) ;?></option>
									<option value="today"><?php  _e( 'Today', 'church_mgt' ) ;?></option>
									<option value="this_week"><?php  _e( 'This Week', 'church_mgt' ) ;?></option>
									<option value="last_week"><?php  _e( 'Last Week', 'church_mgt' ) ;?></option>
									<option value="this_month"><?php  _e( 'This Month', 'church_mgt' ) ;?></option>
									<option value="last_month"><?php  _e( 'Last Month', 'church_mgt' ) ;?></option>
									<option value="last_3_month"><?php  _e( 'Last 3 Months', 'church_mgt' ) ;?></option>
									<option value="last_6_month"><?php  _e( 'Last 6 Months', 'church_mgt' ) ;?></option>
									<option value="last_12_month"><?php  _e( 'Last 12 Months', 'church_mgt' ) ;?></option>
									<option value="this_year"><?php  _e( 'This Year', 'church_mgt' ) ;?></option>
									<option value="last_year"><?php  _e( 'Last Year', 'church_mgt' ) ;?></option>
									<option value="period"><?php  _e( 'Period', 'church_mgt' ) ;?></option>
								</select>
						</div>
						<div id="date_type_div" class="date_type_div_none row col-md-6 mb-2"></div>	
						<div class="col-md-3 mb-2">
							<input type="submit" name="payment_data" Value="<?php esc_attr_e('Go','church_mgt');?>"  class="btn btn-info save_btn"/>
						</div>
					</div>
				</div>
			</form> 
		</div>
		<?php
		if(isset($_REQUEST['payment_data']))
		{
			$date_type = $_POST['date_type'];
			if($date_type=="period")
			{
				$start_date = $_REQUEST['start_date'];
				$end_date = $_REQUEST['end_date'];
			}
			else
			{
				$result =  mj_cmgt_all_date_type_value($date_type);

				$response =  json_decode($result);
				$start_date = $response[0];
				$end_date = $response[1];
			}
		}
		else
		{
			$start_date = date('Y-m-d',strtotime('first day of this month'));
			$end_date = date('Y-m-d',strtotime('last day of this month'));
		}
		
		global $wpdb;
		$obj_payment=new Cmgtpayment;
		$table_name=$wpdb->prefix.'cmgt_transaction';
		$result = $wpdb->get_results("select *from $table_name where transaction_date BETWEEN '$start_date' AND '$end_date'"); 
		if(!empty($result))
		{
			?>
			<script type="text/javascript">
				$(document).ready(function() {
				jQuery('#transaction_list').DataTable({
					//"responsive": true,
					"dom": 'lifrtp',
					language:<?php echo MJ_cmgt_datatable_multi_language();?>,
					"order": [[ 2, "asc" ]],
					"sSearch": "<i class='fa fa-search'></i>",
					"aoColumns":[
									{"bSortable": false},
									{"bSortable": true},
									{"bSortable": true},
									{"bSortable": true},
									{"bSortable": true},
									{"bSortable": true},
									{"bSortable": true}]
					});
					$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
				} );
			</script>
		
			<form name="wcwm_report" action="" method="post">
				<div class="panel-body">
					<div class="table-responsive">
						<table id="transaction_list" class="display" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
								<tr>
									<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Member Name', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Invoice Number', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Donation Type', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Transaction Date', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Amount', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Method', 'church_mgt' ) ;?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Member Name', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Invoice Number', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Donation Type', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Transaction Date', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Amount', 'church_mgt' ) ;?></th>
									<th><?php  esc_html_e( 'Method', 'church_mgt' ) ;?></th>
								</tr>
							</tfoot>
							<tbody>
							<?php 
							$i=0;
							if(!empty($result))
							{
								foreach ($result as $retrieved_data)
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
										<td class="name"><?php $user=get_userdata($retrieved_data->member_id);
										echo esc_attr($user->display_name);
										?> </td>
										<td class="">
											<?php 
												$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($retrieved_data->id)); 
												echo get_option( 'cmgt_payment_prefix' ).''.$invoice_number;?> 
										</td>
										<td class=" "><?php echo get_the_title(esc_attr($retrieved_data->donetion_type));?> </td>
										<td class="stat_date"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->transaction_date)));?> </td>

										<td class="total_amount"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->amount);?> </td>

										<td class="method"><?php echo MJ_cmgt_get_payment_method(esc_attr($retrieved_data->pay_method));?> </td>
									</tr>
									<?php 
									$i++;
								} 
							}	
								?>
							</tbody>
						</table>
					</div>
				</div><!-- PANEL BODY DIV END-->
			</form>	
			<?php
		}
		else
		{
			?>
			<!-- <div class="no_data_list_div"> 
				<a href="<?php echo admin_url().'admin.php?page=cmgt-payment&tab=addtransaction';?>">
					<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
				</a>
				<div class="col-md-12 dashboard_btn margin_top_20px">
					<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
				</div> 
			</div>	 -->
			<div class="calendar-event-new"> 
				<img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
			</div>		
			<?php
		}
		?>
		