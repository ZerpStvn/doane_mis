<?php
$active_tab_1 = sanitize_text_field(isset($_GET['tab_1'])?$_GET['tab_1']:'graph');
?>

<h2 class="nav-tab-wrapper"><!-- NAV TAB WRAPPER MENU START-->  

    <a href="?page=cmgt-report&tab=income_expense&tab_1=datatable" class="nav-tab <?php echo esc_html($active_tab_1) == 'datatable' ? 'nav-tab-active' : ''?>">
    <?php echo esc_html__('Datatable', 'church_mgt'); ?></a>

    <a href="?page=cmgt-report&tab=income_expense&tab_1=graph" class="nav-tab <?php echo esc_html($active_tab_1) == 'graph' ? 'nav-tab-active' : ''?>">
    <?php echo esc_html__('Graph', 'church_mgt'); ?></a>

</h2> <!-- NAV TAB WRAPPER MENU END--> 
<?php
$obj_payment=new Cmgtpayment;
if($active_tab_1 == 'graph')
{
    ?>
    <form method="post" id="attendance_list"  class="attendance_list">  

        <div class="form-body user_form margin_top_15px">

            <div class="row">

                <div class="col-md-3 mb-3 input">

                    <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Year','church_mgt');?><span class="require-field">*</span></label>

                    <select name="year" class="line_height_30px form-control validate[required]">

                        <!-- <option ><?php esc_attr_e('Selecte year','church_mgt');?></option> -->

                            <?php

                            $current_year = date('Y');

                            $min_year = $current_year - 10;

                            

                            for($i = $min_year; $i <= $current_year; $i++){

                                $year_array[$i] = $i;

                                if(isset($_REQUEST['year']))
                                {
                                    $selected = ($_REQUEST['year'] == $i ? ' selected' : '');
                                }else{
                                    $selected = ($current_year == $i ? ' selected' : '');
                                }

                                echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n";

                            }

                            ?>

                    </select>       

                </div>



                <div class="col-md-3 mb-2">

                    <input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','church_mgt');?>"  class="btn btn-info save_btn"/>

                </div>

            </div>

        </div>

    </form>
    <?php
    $invoice_data= $obj_payment->MJ_cmgt_get_all_invoice_data();
    
	foreach($invoice_data as $retrieved_data)
	{
		$datetime = DateTime::createFromFormat('Y-m-d',$retrieved_data->invoice_date);
		// $year_new = $datetime->format('Y');
       
		if(isset($_REQUEST['view_attendance']))
        {
            $year = $_REQUEST['year'];
        }
        else
        {
            $year =isset($year_new)?$year_new:date('Y');
        }
	}
	$current_year = Date("Y");
	$month =array('1'=>esc_html__('Jan','church_mgt'),'2'=>esc_html__('Feb','church_mgt'),'3'=>esc_html__('Mar','church_mgt'),'4'=>esc_html__('Apr','church_mgt'),'5'=>esc_html__('May','church_mgt'),'6'=>esc_html__('Jun','church_mgt'),'7'=>esc_html__('Jul','church_mgt'),'8'=>esc_html__('Aug','church_mgt'),'9'=>esc_html__('Sep','church_mgt'),'10'=>esc_html__('Oct','church_mgt'),'11'=>esc_html__('Nov','church_mgt'),'12'=>esc_html__('Dec','church_mgt'),);
	$result = array();
	$dataPoints_2 = array();
	//array_push($dataPoints_2, array('Month','Income','Expense'));
	array_push($dataPoints_2, array(esc_html__('Month','church_mgt'),esc_html__('Income','church_mgt'),esc_html__('Expense','church_mgt'),esc_html__('Net Profit','church_mgt')));
	$dataPoints_1 = array();
    $currency_symbol = MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
    $new_currency_symbol = html_entity_decode($currency_symbol);
	foreach($month as $key=>$value)
	{
		global $wpdb;
		$table_name = $wpdb->prefix."cmgt_income_expense";

        if(!empty($year)){
            $q = "SELECT * FROM $table_name WHERE YEAR(invoice_date) = $year AND MONTH(invoice_date) = $key AND invoice_type = 'income'";
            $result=$wpdb->get_results($q);
        }

        $q1 = "SELECT * FROM $table_name WHERE YEAR(invoice_date) = $current_year AND MONTH(invoice_date) = $key AND invoice_type = 'expense'";
		
        $result1=$wpdb->get_results($q1);
        
		$income_yearly_amount = 0;
        foreach($result as $income_entry)
		{
            $entry = json_decode($income_entry->entry);
		    $income_yearly_amount += $entry[0]->amount;
		}

		if($income_yearly_amount == 0)
		{
			$income_amount = 0;
		}
		else
		{
			$income_amount = $income_yearly_amount;
		}

        $expense_yearly_amount = 0;
		foreach($result1 as $expense_entry)
		{
            $entry = json_decode($expense_entry->entry);
            $expense_yearly_amount += $entry[0]->amount;
          
		}
        
		if($expense_yearly_amount == 0)
		{
			$expense_amount = 0;
		}
		else
		{
			$expense_amount = $expense_yearly_amount;
		}
		$net_profit_array = $income_amount - $expense_amount;
		
		array_push($dataPoints_2, array($value,$income_amount,$expense_amount,$net_profit_array));
    }

	$new_array = json_encode($dataPoints_2);

	if(!empty($new_array))
	{
		$new_currency_symbol = html_entity_decode($currency_symbol);
		?>
		
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['bar']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				var data = google.visualization.arrayToDataTable(<?php echo $new_array; ?>);

				var options = {
				
					bars: 'vertical', // Required for Material Bar Charts.
					colors: ['#104B73', '#FF9054', '#70ad46'],
					
				};
			
				var chart = new google.charts.Bar(document.getElementById('barchart_material'));

				chart.draw(data, google.charts.Bar.convertOptions(options));
			}
		</script>
		<div id="barchart_material" style="width:100%;height: 430px; padding:20px;"></div>
		<?php
	}
	else
	{
		?>
		<div class="calendar-event-new"> 
			<img class="no_data_img" src="<?php echo AMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
		</div>
		<?php	
	}
}
if($_REQUEST['tab_1'] == 'datatable')
{
	?>
    <script type="text/javascript">
        $(document).ready(function() 
        {   //MEMBER FORM VALIDATIONENGINE
            "use strict";
            <?php
            if (is_rtl())
            {
                ?>	
                $('#student_income_expence_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
                <?php
            }
            else
            {
                ?>
                $('#student_income_expence_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
                <?php
            }
            ?>
            } );

    </script>
	  <div class="panel-body clearfix margin_top_20px padding_top_15px_res">
        <div class="panel-body clearfix">
            <form method="post" id="student_income_expence_payment">  
                <div class="form-body user_form">
                    <div class="row">
                        <div class="col-md-3 mb-3 input">
                            <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date Type','church_mgt');?><span class="require-field">*</span></label>			
                            <select class="line_height_30px form-control date_type validate[required]" name="date_type" autocomplete="off">
                                <option value=""><?php esc_attr_e('Select','church_mgt');?></option>
                                <option value="today"><?php esc_attr_e('Today','church_mgt');?></option>
                                <option value="this_week"><?php esc_attr_e('This Week','church_mgt');?></option>
                                <option value="last_week"><?php esc_attr_e('Last Week','church_mgt');?></option>
                                <option value="this_month"><?php esc_attr_e('This Month','church_mgt');?></option>
                                <option value="last_month"><?php esc_attr_e('Last Month','church_mgt');?></option>
                                <option value="last_3_month"><?php esc_attr_e('Last 3 Months','church_mgt');?></option>
                                <option value="last_6_month"><?php esc_attr_e('Last 6 Months','church_mgt');?></option>
                                <option value="last_12_month"><?php esc_attr_e('Last 12 Months','church_mgt');?></option>
                                <option value="this_year"><?php esc_attr_e('This Year','church_mgt');?></option>
                                <option value="last_year"><?php esc_attr_e('Last Year','church_mgt');?></option>
                                <option value="period"><?php esc_attr_e('Period','church_mgt');?></option>
                            </select>
                        </div>
                        <div id="date_type_div" class="date_type_div_none row col-md-6 mb-2"></div>	
                        <div class="col-md-3 mb-2">
                            <input type="submit" name="income_expense_report" Value="<?php esc_attr_e('Go','church_mgt');?>"  class="btn btn-info save_btn"/>
                        </div>
                    </div>
                </div>
            </form> 
        </div>	
        <?php
        if(isset($_REQUEST['income_expense_report']))
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

            $income_data = mj_cmgt_get_total_income($start_date,$end_date);
            $expense_data = mj_cmgt_get_total_expense($start_date,$end_date);

            //----------- Expense Record Sum ------------//
            $expense_yearly_amount = 0;
            foreach($expense_data as $expense_entry)
            {
                $entry = json_decode($expense_entry->entry);
                $expense_yearly_amount += $entry[0]->amount;
            }
            if($expense_yearly_amount == 0)
            {
                $expense_amount = null;
            }
            else
            {
                $expense_amount = "$expense_yearly_amount";
            }
            //----------- Expense Record Sum ------------//

            //----------- Income Record Sum -------------//
            $income_yearly_amount = 0;
            foreach($income_data as $income_entry)
            {
                $entry = json_decode($income_entry->entry);
                $income_yearly_amount += $entry[0]->amount;
            }
    
            if($income_yearly_amount == 0)
            {
                $income_amount = null;
            }
            else
            {
                $income_amount = "$income_yearly_amount";
            }
            //----------- Income Record Sum -------------//

        }
        else
        {
            $start_date = date('Y-m-d');
            $end_date= date('Y-m-d');

            $income_data = mj_cmgt_get_total_income($start_date,$end_date);
            $expense_data = mj_cmgt_get_total_expense($start_date,$end_date);

           //----------- Expense Record Sum ------------//
          $expense_yearly_amount = 0;
            foreach($expense_data as $expense_entry)
            {
                $entry = json_decode($expense_entry->entry);
                $expense_yearly_amount += $entry[0]->amount;
            }
            if($expense_yearly_amount == 0)
            {
                $expense_amount = null;
            }
            else
            {
                $expense_amount = "$expense_yearly_amount";
            }
            //----------- Expense Record Sum ------------//

            //----------- Income Record Sum -------------//
            $income_yearly_amount = 0;
            foreach($income_data as $income_entry)
            {
                $entry = json_decode($income_entry->entry);
                $income_yearly_amount += $entry[0]->amount;
            }
    
            if($income_yearly_amount == 0)
            {
                $income_amount = null;
            }
            else
            {
                $income_amount = "$income_yearly_amount";
            }
            //----------- Income Record Sum -------------//
        }

        if(!empty($expense_amount) || !empty($income_amount))
        {
            ?>
            <script type="text/javascript">
			$(document).ready(function() {
			jQuery('#table_income_expense').DataTable({
				//"responsive": true,
				"dom": 'lifrtp',
				language:<?php echo MJ_cmgt_datatable_multi_language();?>,
				"order": [[ 2, "asc" ]],
				"sSearch": "<i class='fa fa-search'></i>",
				"aoColumns":[
								{"bSortable": false},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": true}]
				});
				$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
				$('.select_all').on('click', function(e)
					{
							if($(this).is(':checked',true))  
							{
								$(".sub_chk").prop('checked', true); 
								$(".select_all").prop('checked', true);								
							}  
							else  
							{  
								$(".sub_chk").prop('checked',false);  
								$(".select_all").prop('checked', false);
							} 
					});
				
					$('.sub_chk').on('change',function()
					{ 
						if(false == $(this).prop("checked"))
						{ 
							$(".select_all").prop('checked', false); 
						}
						if ($('.sub_chk:checked').length == $('.sub_chk').length )
						{
							$(".select_all").prop('checked', true);
						}
					});
			} );
		</script>
           
                <div class="btn-place"></div>
                <form id="frm-example1" name="frm-example1" method="post" class="table-responsive">
                    <table id="table_income_expense" class="display" cellspacing="0" width="100%">
                        <thead class="<?php echo MJ_cmgt_datatable_heder() ?>">
                            <tr>
                                <th><?php  esc_html_e( 'Image', 'church_mgt' ) ;?></th>
                                <th> <?php esc_html_e( 'Total Income', 'church_mgt' ) ;?></th>
                                <th> <?php esc_html_e( 'Total Expense', 'church_mgt' ) ;?></th>
                                <th> <?php esc_html_e( 'Net Profit', 'church_mgt' ) ;?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $net_profit = $income_amount - $expense_amount;
                            ?>
                            <tr>
                                <td class="user_image width_50px profile_image_prescription padding_left_0">
                                    <p class="padding_15px prescription_tag netprofit_color">
                                        <img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Income-white.png"?>" alt="" class="massage_image center">
                                    </p>
                                </td>
                                <td class="patient"><?php if(!empty($income_amount)){ echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).' '.$income_amount; }else{ echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ))." 0"; } ?> </td>
                                <td class="patient_name"><?php if(!empty($expense_amount)){ echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).' '.$expense_amount; }else{ echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ))." 0"; } ?></td>
                                <td class="income_amount" style="<?php if($net_profit < 0){ echo "color: red !important"; } ?>"><?php if(!empty($net_profit)){ echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).' '.$net_profit; }else{ echo MJ_cmgt_get_currency_symbol()." 0"; } ?> </td>
                            </tr>
                                
                        </tbody>        
                    </table>
                </form>
                
           
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
        ?>
    </div>
	<?php
}
?>
