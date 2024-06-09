<?php

	// if(isset($_REQUEST['attendance_report']))
    // {
    //     $date_type = $_POST['date_type'];
    //     if($date_type=="period")
    //     {
    //         $start_date = $_REQUEST['start_date'];
    //         $end_date = $_REQUEST['end_date'];
    //     }
    //     else
    //     {
    //         $result =  mj_cmgt_all_date_type_value($date_type);
    
    //         $response =  json_decode($result);
    //         $start_date = $response[0];
    //         $end_date = $response[1];
    //     }
    // }
    // else
    // {
    //     $start_date = date('Y-m-d');
    //     $end_date= date('Y-m-d');
    // }
	// global $wpdb;
	// $table_attendance = $wpdb->prefix ."cmgt_attendence";
	// $table_activity = $wpdb->prefix ."cmgt_activity";
	// // $sdate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['date_type']));
	// // $edate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['edate']));
	// $report_2 =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
	// SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` BETWEEN '$start_date' AND '$end_date' AND at.activity_id = cl.activity_id AND at.role_name = 'member' GROUP BY at.activity_id") ;
	// $chart_array[] = array(__('Class','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
	// if(!empty($report_2))
	// foreach($report_2 as $result)
	// 	{
	// 		$activity =MJ_cmgt_get_activity_name($result->activity_id);
	// 		$chart_array[] = array("$activity",(int)$result->Present,(int)$result->Absent);
	// 	}

	// $options = Array(
	// 		'title' => __('Member Attendance Report','church_mgt'),
	// 		'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
	// 		'legend' =>Array('position' => 'right',
	// 				'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
				
	// 		'hAxis' => Array(
	// 				'title' =>  __('Activity','church_mgt'),
	// 				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
	// 				'textStyle' => Array('color' => '#66707e','fontSize' => 10),
	// 				'maxAlternation' => 2
	// 		),
	// 		'vAxis' => Array(
	// 				'title' =>  __('No of Member','church_mgt'),
	// 				'minValue' => 0,
	// 				'maxValue' => 5,
	// 				'format' => '#',
	// 				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
	// 				'textStyle' => Array('color' => '#66707e','fontSize' => 12)
	// 		),
	// 		'colors' => array('#22BAA0','#f25656')
	// );

	$active_tab_1 = sanitize_text_field(isset($_GET['tab_1'])?$_GET['tab_1']:'activity_attendance');
	?> 
	<ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per margin_top_20px flex-nowrap overflow-auto" role="tablist">

		<li class="<?php if($active_tab_1 =='activity_attendance'){?>active<?php }?>"> 
			<a href="?page=cmgt-report&tab=attendance_report&tab_1=activity_attendance" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'activity_attendance' ? 'nav-tab-active' : ''?>">
			<?php echo esc_html__('Activity Attendance', 'church_mgt'); ?></a>
		</li> 

		<li class="<?php if($active_tab_1 =='ministry_attendance'){?>active<?php }?>"> 
			<a href="?page=cmgt-report&tab=attendance_report&tab_1=ministry_attendance" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'ministry_attendance' ? 'nav-tab-active' : ''?>">
			<?php echo esc_html__('Ministry Attendance', 'church_mgt'); ?></a>
		</li> 
		
	</ul> 
	<?php
	if($_REQUEST['tab_1'] == 'activity_attendance')
	{
		require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';

		$GoogleCharts = new GoogleCharts;
		if(isset($_REQUEST['attendance_report'])  && $active_tab == 'attendance_report')
		{
			if(isset($_REQUEST['attendance_report'])  && $active_tab == 'attendance_report')
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
			$table_attendance = $wpdb->prefix ."cmgt_attendence";
			$table_activity = $wpdb->prefix ."cmgt_activity";
			// $sdate = MJ_cmgt_get_format_for_db(date('Y-m-d',strtotime('first day of this month')));
			// $edate = MJ_cmgt_get_format_for_db(date('Y-m-d',strtotime('last day of this month')));
			$report_2 =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
			SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` BETWEEN '$start_date' AND '$end_date' AND at.activity_id = cl.activity_id AND at.role_name = 'member' GROUP BY at.activity_id") ;
			$chart_array[] = array(__('Class','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
			if(!empty($report_2))
			{
				foreach($report_2 as $result)
					{
						$activity =MJ_cmgt_get_activity_name($result->activity_id);
						$chart_array[] = array("$activity",(int)$result->Present,(int)$result->Absent);
					}
			
				$options = Array(
						'title' => __('Member Attendance Report','church_mgt'),
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'legend' =>Array('position' => 'right',
								'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
							
						'hAxis' => Array(
								'title' =>  __('Activity','church_mgt'),
								'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
								'textStyle' => Array('color' => '#66707e','fontSize' => 10),
								'maxAlternation' => 2
						),
						'vAxis' => Array(
								'title' =>  __('No of Member','church_mgt'),
								'minValue' => 0,
								'maxValue' => 5,
								'format' => '#',
								'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
								'textStyle' => Array('color' => '#66707e','fontSize' => 12)
						),
						'colors' => array('#22BAA0','#f25656')
				);
			}
		
		}
		?>
		<script type="text/javascript">
			$(document).ready(function() 
			{ 
				$(".sdate").datepicker({
				   dateFormat: "yy-mm-dd",
				maxDate : 0,
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
			<form method="post" id="income_payment">  
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
							<input type="submit" name="attendance_report" Value="<?php esc_attr_e('Go','church_mgt');?>"  class="btn btn-info save_btn"/>
						</div>
					</div>
				</div>
			</form> 
		</div>
		
		
		<?php if(isset($report_2) && count($report_2) >0)
		{
			$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
			?>
			<div id="chart_div" style="width: 100%; height: 500px;"></div>
			<!-- Javascript --> 
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php echo $chart;?>
			</script>
		<?php 
		}else{
			?>
			<div class="calendar-event-new"> 
				<img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
			</div>	
			<?php 
		}
	}else{
		require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';

		$GoogleCharts = new GoogleCharts;
		if(isset($_REQUEST['attendance_report'])  && $active_tab == 'attendance_report')
		{
			if(isset($_REQUEST['attendance_report'])  && $active_tab == 'attendance_report')
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
			$table_attendance = $wpdb->prefix ."cmgt_attendence";
			$table_activity = $wpdb->prefix ."cmgt_activity";
			$sdate = MJ_cmgt_get_format_for_db(date('Y-m-d',strtotime('first day of this month')));
			$edate = MJ_cmgt_get_format_for_db(date('Y-m-d',strtotime('last day of this month')));
			$report_2 =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
			SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.activity_id = cl.activity_id AND at.role_name = 'member' GROUP BY at.activity_id") ;
			$chart_array[] = array(__('Class','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
			// var_dump($start_date);
			// var_dump($end_date);
			// var_dump($report_2);
			// die;
			if(!empty($report_2))
			foreach($report_2 as $result)
				{
					$activity =MJ_cmgt_get_activity_name($result->activity_id);
					$chart_array[] = array("$activity",(int)$result->Present,(int)$result->Absent);
				}
		
			$options = Array(
					'title' => __('Ministry Attendance Report','church_mgt'),
					'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'legend' =>Array('position' => 'right',
							'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
						
					'hAxis' => Array(
							'title' =>  __('Activity','church_mgt'),
							'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
							'textStyle' => Array('color' => '#66707e','fontSize' => 10),
							'maxAlternation' => 2
					),
					'vAxis' => Array(
							'title' =>  __('No of Ministry','church_mgt'),
							'minValue' => 0,
							'maxValue' => 5,
							'format' => '#',
							'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
							'textStyle' => Array('color' => '#66707e','fontSize' => 12)
					),
					'colors' => array('#22BAA0','#f25656')
			);
		
		}
		?>
		<script type="text/javascript">
			$(document).ready(function() 
			{ 
				$(".sdate").datepicker({
				   dateFormat: "yy-mm-dd",
				maxDate : 0,
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
					<form method="post" id="income_payment">  
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
									<input type="submit" name="attendance_report" Value="<?php esc_attr_e('Go','church_mgt');?>"  class="btn btn-info save_btn"/>
								</div>
							</div>
						</div>
					</form> 
				</div>
				
				
				<?php if(isset($report_2) && count($report_2) >0)
				{
					$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
					?>
					<div id="chart_div" style="width: 100%; height: 500px;"></div>
				  <!-- Javascript --> 
				  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
				  <script type="text/javascript">
						<?php echo $chart;?>
				  </script>
			  <?php 
				}else{
					?>
				<div class="calendar-event-new"> 
					<img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
				</div>	
			  <?php 
				}
	} ?>
