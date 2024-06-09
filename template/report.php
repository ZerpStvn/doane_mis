<?php 
MJ_cmgt_header();
$active_tab= sanitize_text_field(isset($_REQUEST['tab'])?$_REQUEST['tab']:'attendance_report');
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
	  maxDate : 0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 0);
            $(".sdate").datepicker("option", "maxDate", dt);
        }
    });	
 
} );
</script>
<div class="page-inner"><!-- PAGE INNER DIV START-->
	<div id=""><!-- MAIN WRAPPER DIV START-->  
		<div class="row report"><!-- ROW DIV START--> 
			<div class="col-md-12"><!-- COL 12 DIV START-->  
				<div class="panel panel-white main_home_page_div"><!-- PANEL WHITE DIV START-->  
					<div class="panel-body"><!-- PANEL BODY DIV START-->
                        <!-- <ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per" role="tablist"> -->
		                	<!-- <li class="<?php if($active_tab =='attendance_report'){?>active<?php }?>"> 
                                <a href="?church-dashboard=user&page=report&tab=attendance_report" class="padding_left_0 tab <?php echo $active_tab == 'attendance_report' ? 'nav-tab-active' : ''?>">
                                <?php echo esc_html__('Attendance Report', 'church_mgt'); ?></a>
                            </li> 
                            <li class="<?php if($active_tab =='payment_report'){?>active<?php }?>"> 
                                <a href="?church-dashboard=user&page=report&tab=payment_report" class="padding_left_0 tab <?php echo $active_tab == 'payment_report' ? 'nav-tab-active' : ''?>">
                                <?php echo esc_html__('Payment Report', 'church_mgt'); ?></a>
                             </li> 
                             <li class="<?php if($active_tab =='payment_data'){?>active<?php }?>"> 
                                <a href="?church-dashboard=user&page=report&tab=payment_data" class="padding_left_0 tab <?php echo $active_tab == 'payment_data' ? 'nav-tab-active' : ''?>">
                                <?php echo esc_html__('Payment Data', 'church_mgt'); ?></a>
                             </li> 
                             <li class="<?php if($active_tab =='income_report'){?>active<?php }?>"> 
                                <a href="?church-dashboard=user&page=report&tab=income_report&tab_1=datatable" class="padding_left_0 tab <?php echo $active_tab == 'income_report' ? 'nav-tab-active' : ''?>">
                                <?php echo esc_html__('Income Report', 'church_mgt'); ?></a>
                             </li> 
                             <li class="<?php if($active_tab =='expense_report'){?>active<?php }?>"> 
                                <a href="?church-dashboard=user&page=report&tab=expense_report&tab_1=datatable" class="padding_left_0 tab <?php echo $active_tab == 'expense_report' ? 'nav-tab-active' : ''?>">
                                <?php echo esc_html__('Expense Report', 'church_mgt'); ?></a>
                             </li> 
                             <li class="<?php if($active_tab =='activity_report'){?>active<?php }?>"> 
                                <a href="?church-dashboard=user&page=report&tab=activity_report" class="padding_left_0 tab <?php echo $active_tab == 'activity_report' ? 'nav-tab-active' : ''?>">
                                <?php echo esc_html__('Activity Report', 'church_mgt'); ?></a>
                            </li> 
                            <li class="<?php if($active_tab =='export-report'){?>active<?php }?>"> 
                                <a href="?church-dashboard=user&page=report&tab=export-report" class="padding_left_0 tab <?php echo $active_tab == 'export-report' ? 'nav-tab-active' : ''?>">
                                <?php echo esc_html__('Download Reports', 'church_mgt'); ?></a>
                            </li>  -->
                           
                        <!-- </ul> -->
                        <?php
						if($active_tab == 'payment_report' || $active_tab == 'payment_data' || $active_tab == 'income_report' || $active_tab == 'expense_report'  || $active_tab == 'income_expense')
						{ ?>
                            <ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per flex-nowrap overflow-auto" role="tablist">
                                <li class="<?php if($active_tab =='payment_data'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=payment_data" class="padding_left_0 tab <?php echo $active_tab == 'payment_data' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Transaction/Donation Report', 'church_mgt'); ?></a>
                                </li> 
                                <li class="<?php if($active_tab =='income_report'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=income_report&tab_1=datatable" class="padding_left_0 tab <?php echo $active_tab == 'income_report' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Income Report', 'church_mgt'); ?></a>
                                </li> 
                                <li class="<?php if($active_tab =='expense_report'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=expense_report&tab_1=datatable" class="padding_left_0 tab <?php echo $active_tab == 'expense_report' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Expense Report', 'church_mgt'); ?></a>
                                </li> 
                                <li class="<?php if($active_tab =='income_expense'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=income_expense&tab_1=datatable" class="padding_left_0 tab <?php echo $active_tab == 'income_expense' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Income & Expense Report', 'church_mgt'); ?></a>
                                </li> 
                            </ul>
                            <?php
                        }?>
                            <?php
                            if($active_tab =='payment_report' || $active_tab =='payment_data')
                            { ?>
                            <ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per margin_top_20px " role="tablist">
                                <li class="<?php if($active_tab =='payment_data'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=payment_data" class="padding_left_0 tab <?php echo $active_tab == 'payment_data' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Datatable', 'church_mgt'); ?></a>
                                </li> 

                                <li class="<?php if($active_tab =='payment_report'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=payment_report" class="padding_left_0 tab <?php echo $active_tab == 'payment_report' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Graph', 'church_mgt'); ?></a>
                                </li> 
                                </ul>
                                <?php
                            } ?>
                       
                         <!-- NAV TAB WRAPPER MENU END--> 
						<div class="clearfix"></div>
                        <?php
                        if($active_tab == 'attendance_report')
						{
                            $active_tab_1 = sanitize_text_field(isset($_GET['tab_1'])?$_GET['tab_1']:'activity_attendance');
                            ?> 
                            <ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per margin_top_20px flex-nowrap overflow-auto" role="tablist">

                                <li class="<?php if($active_tab_1 =='activity_attendance'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=attendance_report&tab_1=activity_attendance" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'activity_attendance' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Activity Attendance', 'church_mgt'); ?></a>
                                </li> 

                                <li class="<?php if($active_tab_1 =='ministry_attendance'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=attendance_report&tab_1=ministry_attendance" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'ministry_attendance' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Ministry Attendance', 'church_mgt'); ?></a>
                                </li> 
                            </ul> 
                            <?php
                            if($_REQUEST['tab_1'] == 'activity_attendance')
                            {
                                if(isset($_REQUEST['attendance_report'])  && $active_tab == 'attendance_report')
                                {
                                    $date_type = $_POST['date_type'];
                                    if($date_type=="period")
                                    {
                                        $sdate = $_REQUEST['start_date'];
                                        $edate = $_REQUEST['end_date'];
                                    }
                                    else
                                    {
                                        $result =  mj_cmgt_all_date_type_value($date_type);
                                
                                        $response =  json_decode($result);
                                        $sdate = $response[0];
                                        $edate = $response[1];
                                    }
                                }
                                else
                                {
                                    $sdate = date('Y-m-d');
                                    $edate= date('Y-m-d');
                                }
                                if(isset($_POST['attendance_report']))
                                {
                                    global $wpdb;
                                    $table_attendance = $wpdb->prefix ."cmgt_attendence";
                                    $table_activity = $wpdb->prefix ."cmgt_activity";
                                    // $sdate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['sdate']));
                                    // $edate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['edate']));
                                    $report_2 =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
                                    SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.activity_id = cl.activity_id AND at.role_name = 'member' GROUP BY at.activity_id") ;
                                    $chart_array[] = array(__('Class','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
                                    if(!empty($report_2))
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
                                    require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';

                                    $GoogleCharts = new GoogleCharts;

                                // if($active_tab == 'attendance_report' && empty($_POST['attendance_report']))
                                // {
                                //     global $wpdb;
                                //     $table_attendance = $wpdb->prefix ."cmgt_attendence";
                                //     $table_activity = $wpdb->prefix ."cmgt_activity";
                                //     $start_date = date('Y-m-d',strtotime('first day of this month'));
                                //     $end_date = date('Y-m-d',strtotime('last day of this month'));
                                //     $report_2 =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
                                //     SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` BETWEEN '$start_date' AND '$end_date' AND at.activity_id = cl.activity_id AND at.role_name = 'member' GROUP BY at.activity_id") ;
                                //     $chart_array[] = array(__('Class','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
                                //     if(!empty($report_2))
                                //     foreach($report_2 as $result)
                                //     {
                                //         $activity =MJ_cmgt_get_activity_name($result->activity_id);
                                //         $chart_array[] = array("$activity",(int)$result->Present,(int)$result->Absent);
                                //     }

                                //     $options = Array(
                                //             'title' => __('Member Attendance Report','church_mgt'),
                                //             'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                //             'legend' =>Array('position' => 'right',
                                //                     'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
                                                
                                //             'hAxis' => Array(
                                //                     'title' =>  __('Activity','church_mgt'),
                                //                     'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                //                     'textStyle' => Array('color' => '#66707e','fontSize' => 10),
                                //                     'maxAlternation' => 2
                                //             ),
                                //             'vAxis' => Array(
                                //                     'title' =>  __('No of Member','church_mgt'),
                                //                     'minValue' => 0,
                                //                     'maxValue' => 5,
                                //                     'format' => '#',
                                //                     'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                //                     'textStyle' => Array('color' => '#66707e','fontSize' => 12)
                                //             ),
                                //             'colors' => array('#22BAA0','#f25656')
                                //     );
                                //     require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
                                //     $GoogleCharts = new GoogleCharts;
                                // }

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
                                    }
                                if(empty($report_2)) 
                                {
                                    ?>
                                    <div class="calendar-event-new"> 
                                        <img class="no_data_img"  src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
                                    </div>
                                    <?php 
                                }
                            }else{
                                if(isset($_REQUEST['attendance_report'])  && $active_tab == 'attendance_report')
                                {
                                    $date_type = $_POST['date_type'];
                                    if($date_type=="period")
                                    {
                                        $sdate = $_REQUEST['start_date'];
                                        $edate = $_REQUEST['end_date'];
                                    }
                                    else
                                    {
                                        $result =  mj_cmgt_all_date_type_value($date_type);
                                
                                        $response =  json_decode($result);
                                        $sdate = $response[0];
                                        $edate = $response[1];
                                    }
                                }
                                else
                                {
                                    $sdate = date('Y-m-d');
                                    $edate= date('Y-m-d');
                                }
                                if(isset($_POST['attendance_report']))
                                {
                                    global $wpdb;
                                    $table_attendance = $wpdb->prefix ."cmgt_attendence";
                                    $table_activity = $wpdb->prefix ."cmgt_activity";
                                    // $sdate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['sdate']));
                                    // $edate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['edate']));
                                    $report_2 =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
                                    SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.activity_id = cl.activity_id AND at.role_name = 'member' GROUP BY at.activity_id") ;
                                    $chart_array[] = array(__('Class','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
                                    if(!empty($report_2))
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
                                    require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';

                                    $GoogleCharts = new GoogleCharts;

                                if($active_tab == 'attendance_report' && empty($_POST['attendance_report']))
                                {
                                    global $wpdb;
                                    $table_attendance = $wpdb->prefix ."cmgt_attendence";
                                    $table_activity = $wpdb->prefix ."cmgt_activity";
                                    $start_date = date('Y-m-d',strtotime('first day of this month'));
                                    $end_date = date('Y-m-d',strtotime('last day of this month'));
                                    $report_2 =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
                                    SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` BETWEEN '$start_date' AND '$end_date' AND at.activity_id = cl.activity_id AND at.role_name = 'ministry' GROUP BY at.activity_id") ;
                                    $chart_array[] = array(__('Class','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
                                    if(!empty($report_2))
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
                                    require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
                                    $GoogleCharts = new GoogleCharts;
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
                                    }
                                if(isset($report_2) && empty($report_2)) 
                                {
                                    ?>
                                    <div class="calendar-event-new"> 
                                        <img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
                                    </div>
                                    <?php 
                                }
                            }

                        }
                        elseif($active_tab == 'payment_report')
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
                            $month =array('1'=>esc_html__('January','church_mgt'),'2'=>esc_html__('February','church_mgt'),'3'=>esc_html__('March','church_mgt'),'4'=>esc_html__('April','church_mgt'),
                            '5'=>esc_html__('May','church_mgt'),'6'=>esc_html__('June','church_mgt'),'7'=>esc_html__('July','church_mgt'),'8'=>esc_html__('August','church_mgt'),
                            '9'=>esc_html__('September','church_mgt'),'10'=>esc_html__('Octomber','church_mgt'),'11'=>esc_html__('November','church_mgt'),'12'=>esc_html__('December','church_mgt'),);
                           
                            if(isset($_REQUEST['view_attendance']))
                            {
                                $year = $_REQUEST['year'];
                            }
                            else
                            {
                                $year = sanitize_text_field(isset($_POST['year'])?$_POST['year']:date('Y'));
                            }
                           

                            global $wpdb;
                            $table_name = $wpdb->prefix."cmgt_transaction";

                            $q="SELECT EXTRACT(MONTH FROM transaction_date) as date,sum(amount) as count FROM ".$table_name." WHERE YEAR(transaction_date) =".$year." group by month(transaction_date) ORDER BY transaction_date ASC";
                            $result=$wpdb->get_results($q);
                            $chart_array = array();
                            $chart_array[] = array(__('Month','church_mgt'),__('Payment','church_mgt'));
                            foreach($result as $r)
                            {
                                $chart_array[]=array( $month[$r->date],(int)$r->count);
                            }

                            $options = Array(
                                        'title' => __('Payment Report By Month','church_mgt'),
                                        'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                        'legend' =>Array('position' => 'right',
                                                    'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
                                        
                                        'hAxis' => Array(
                                            'title' => __('Month','church_mgt'),
                                            'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                            'textStyle' => Array('color' => '#66707e','fontSize' => 11),
                                            'maxAlternation' => 2
                                            
                                            ),
                                        'vAxis' => Array(
                                            'title' => __('Payment','church_mgt'),
                                            'minValue' => 0,
                                            'maxValue' => 5,
                                            'format' => '#',
                                            'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                            'textStyle' => Array('color' => '#66707e','fontSize' => 12)
                                            ),
                                    'colors' => array('#22BAA0')
                                    
                                        );
                            require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';

                            $GoogleCharts = new GoogleCharts;

                            $chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
                            ?>
                            <script type="text/javascript">
                            $(document).ready(function() 
                            {
                                $('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
                                $('.edate').datepicker({dateFormat: "yy-mm-dd"}); 
                            } );
                            </script>
                                <?php
                                if(!empty($result))
                                {
                                    
                                     ?>
                                    <div id="chart_div" style="width: 100%; height: 500px;"></div>
                                
                                     <!-- Javascript --> 
                                    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
                                    <script type="text/javascript">
                                        <?php echo $chart;?>
                                    </script>
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
                                <script type="text/javascript">
                                    <?php 
                                    if(!empty($result))
                                    {
                                        echo $chart;
                                    }
                                    else
                                    {
                                        echo "hello";
                                    }
                                    
                                    ?>
                                </script>
                                <?php
                        }
                        if($active_tab == 'payment_data')
						{
                            $obj_transaction=new Cmgttransaction;
                            ?>
                            <?php
                            // if(isset($_POST['attendance_report']))
                            // {
                            //     global $wpdb;
                            //     $table_name=$wpdb->prefix.'cmgt_transaction';
                            //     $sdate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['sdate']));
                            //     $edate = MJ_cmgt_get_format_for_db(sanitize_text_field($_POST['edate']));
                            //     $result = $wpdb->get_results("select *from $table_name where created_date BETWEEN '$sdate' AND '$edate'");  
                                
                            // }
                            // if($active_tab == 'payment_data' && empty($_POST['attendance_report']))
                            // {
                            //     global $wpdb;
                            //     $table_name=$wpdb->prefix.'cmgt_transaction';
                            //     $start_date = date('Y-m-d',strtotime('first day of this month'));
                            //     $end_date = date('Y-m-d',strtotime('last day of this month'));
                            //     $result = $wpdb->get_results("select *from $table_name where created_date BETWEEN '$start_date' AND '$end_date'");  
                                
                            // }
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
                            <?php
                            if(!empty($result))
                            {
                                ?>
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
                                <div class="calendar-event-new"> 
                                    <img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
                                </div>		
                                <?php
                            }
                        }
                        if($active_tab == 'activity_report')
                        {
                            $month =array('1'=>"January",'2'=>"February",'3'=>"March",'4'=>"April",
                            '5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",
                            '9'=>"September",'10'=>"Octomber",'11'=>"November",'12'=>"December",);
                            $year = sanitize_text_field(isset($_POST['year'])?$_POST['year']:date('Y'));
                            global $wpdb;
                            $table_name = $wpdb->prefix."cmgt_activity";
                            $q="SELECT EXTRACT(MONTH FROM created_date) as date,count(*) as activity FROM ".$table_name." WHERE YEAR(created_date) =".$year." group by month(created_date) ORDER BY created_date ASC";
                            $result=$wpdb->get_results($q);
                            $chart_array = array();
                            $chart_array[] = array(__('Month','church_mgt'),__('Activity','church_mgt'));
                            foreach($result as $r)
                            {
                                $chart_array[]=array( $month[$r->date],(int)$r->activity);
                            }
                            $options = Array(
                                        'title' => __('Activity Report By Month','church_mgt'),
                                        'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                        'legend' =>Array('position' => 'right',
                                                    'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
                                        'hAxis' => Array(
                                            'title' => __('Month','church_mgt'),
                                            'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                            'textStyle' => Array('color' => '#66707e','fontSize' => 11),
                                            'maxAlternation' => 2
                                            
                                            // 'annotations' =>Array('textStyle'=>Array('fontSize'=>5))
                                            ),
                                        'vAxis' => Array(
                                            'title' => __('Activity','church_mgt'),
                                            'minValue' => 0,
                                            'maxValue' => 5,
                                            'format' => '#',
                                            'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                                            'textStyle' => Array('color' => '#66707e','fontSize' => 12)
                                            ),
                                    'colors' => array('#22BAA0')
                                    
                                        );
                            require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
                            $GoogleCharts = new GoogleCharts;
                            $chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
                            ?>

                            <div id="chart_div" style="width: 100%; height: 500px;">
                                <?php
                                $obj_activity=new Cmgtactivity;
                                $activitydata=$obj_activity->MJ_cmgt_get_all_activities();
                                if(empty($activitydata))
                                {
                                    ?>
                                    <!-- <div class="no_data_list_div"> 
                                        <a href="<?php echo admin_url().'admin.php?page=cmgt-activity&tab=Activitylist';?>">
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
                            </div>
                            
                            <!-- Javascript --> 
                            <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
                            <script type="text/javascript">
                                    <?php if(!empty($result))
                                    echo $chart;?>
                            </script>
                            <?php
                        }
                        if($active_tab == 'export-report')
                        {

                            if(isset($_REQUEST['attendance_report']))
                            {
                                global $wpdb;
                                $table_attendance = $wpdb->prefix .'cmgt_attendence';
                                $table_activity = $wpdb->prefix .'cmgt_activity';
                                $sdate = MJ_cmgt_get_format_for_db($_REQUEST['sdate']);
                                $edate = MJ_cmgt_get_format_for_db($_REQUEST['edate']);
                                $report_2 =$wpdb->get_results("SELECT  at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present,SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.activity_id = cl.activity_id AND at.role_name = 'member' GROUP BY at.activity_id") ;
                                
                                $chart_array[] = array(__('Class','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
                                if(!empty($report_2))
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
                            require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';

                            $GoogleCharts = new GoogleCharts;
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
                                } );
                            </script>

                            <div class="panel-body"><!-- PANEL BODY DIV START-->
                                <form method="post"> 
                                    <div class="form-body user_form margin_top_15 margin_top_15_per_res"> <!--Card Body div-->   
                                        <div class="row"><!--Row Div--> 
                                            <div class="col-md-4 cmgt_display report_top_15">
                                                <label class="ml-1 custom-top-label top r_type" for="exam_id"><?php esc_html_e('Select Report Type','church_mgt');?></label>
                                                <select id="report_type" class="form-control"  name="report_type">
                                                    <?php if(isset($_REQUEST['report_type'])) $report_type=$_REQUEST['report_type']; else $report_type="";?>
                                                    <option <?php if($report_type=='attendance_report') echo "selected";?> value="attendance_report"><?php esc_html_e('Attendance Report','church_mgt');?></option>
                                                    <option <?php if($report_type=='payment_report') echo "selected";?> value="payment_report"><?php esc_html_e('Payment Report','church_mgt');?></option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group input">
                                                    <div class="col-md-12 form-control">
                                                        <input type="text"  class="form-control sdate validate[required]" name="sdate"   value="<?php if(isset($_REQUEST['sdate'])) echo esc_attr($_REQUEST['sdate']);else echo date("Y-m-d");?>" autocomplete="off" readonly>
                                                        <label class="" for="exam_id"><?php esc_html_e('Start Date','church_mgt');?><span class="require-field">*</span></label>
                                                    </div>	
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group input">
                                                    <div class="col-md-12 form-control">
                                                        <input type="text"  class="form-control edate validate[required]" name="edate"   value="<?php if(isset($_REQUEST['edate'])) echo esc_attr($_REQUEST['edate']);else echo date("Y-m-d");?>" autocomplete="off" readonly>
                                                        <label class="" for="exam_id"><?php esc_html_e('End Date','church_mgt');?><span class="require-field">*</span></label>
                                                    </div>	
                                                </div>
                                            </div>

                                            <div class="col-md-2">        	
                                                <input for="subject_id" type="submit" name="download_report" Value="<?php esc_html_e('Go','church_mgt');?>"  class="btn btn-success download_report_btn save_btn btn_height"/>
                                            </div> 


                                        </div>
                                    </div>
                                </form>
                            </div><!-- PANEL BODY DIV END-->
                            <?php 
                            if(isset($_POST['download_report']))
                            {
                                global $wpdb;
                                $sdate=date('Y-m-d',strtotime($_POST['sdate']));
                                $edate=date('Y-m-d',strtotime($_POST['edate']));
                                if($_POST['report_type']=='attendance_report')
                                {
                                    $table_name=$wpdb->prefix.'cmgt_attendence';
                                    $result = $wpdb->get_results("select *from $table_name where attendence_date BETWEEN '$sdate' AND '$edate'");  
                                    $num_rows = count($result);
                                    if($num_rows >= 1)
                                    {					
                                        $filename="AttendanceReportfile.csv";
                                        $fp = fopen($filename, "w");	   
                                        // Get The Field Name
                                        $output="";
                                        $output .= '"'.__('Id','church_mgt').'",';
                                        foreach($result[0] as $key=>$rec)
                                        {
                                            if($key=='user_id')
                                                $output .= '"'.__('Member Name','church_mgt').'",';
                                            if($key=='activity_id')
                                                $output .= '"'.__('Activity Title','church_mgt').'",';
                                            if($key=='attendence_date')
                                                $output .= '"'.__('Attendence Date','church_mgt').'",';
                                            if($key=='status')
                                                $output .= '"'.__('Status','church_mgt').'",';
                                        }
                                        $output .="\n";
                                        $i=1;
                                        foreach($result as $single_rec)
                                        {
                                            $membersdata=get_userdata($single_rec->user_id);
                                            if(!empty($membersdata->member_id))
                                            $output .='"'.$membersdata->member_id.'",';
                                            else
                                            $output .='';
                                            foreach($single_rec as $key=>$row){
                                                
                                                if($key=='user_id' ||  $key=='activity_id' || $key=='attendence_date' || $key=='status'){
                                                
                                                if($key=='user_id') 
                                                    $output .='"'.MJ_cmgt_church_get_display_name($row).'",';
                                                elseif($key=='activity_id') 
                                                    $output .='"'.MJ_cmgt_get_activity_name($row).'",';
                                                else
                                                    $output .='"'.$row.'",';
                                                }
                                            }
                                            $output .="\n";
                                            $i++;
                                        }
                                        // Download the file
                                        fputs($fp,$output);
                                        fclose($fp);
                                    ?>
                                        <div class="clear col-md-12"><?php esc_html_e("Your file is ready. You can download it from",'church_mgt');?> <a href='<?php echo$filename;?>'><?php esc_html_e('Download','church_mgt') ?></a> <?php
                                    }
                                    else
                                    { ?>
                                        <div class="calendar-event-new"> 
                                            <img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
                                        </div>
                                    <?php 
                                    }
                                            
                                }
                                if($_POST['report_type']=='payment_report')
                                {
                                    $table_name=$wpdb->prefix.'cmgt_transaction';
                                    $result = $wpdb->get_results("select *from $table_name where transaction_date BETWEEN '$sdate' AND '$edate'");  
                                    
                                    $num_rows = count($result);
                                    if($num_rows >= 1)
                                    {
                                        $filename="PaymentReportfile.csv";
                                        $fp = fopen($filename, "w");
                                        // Get The Field Name
                                        $output="";
                                        $output .= '"'.__('Id','church_mgt').'",';
                                        foreach($result[0] as $key=>$rec)
                                        {
                                            
                                            if($key=='member_id')
                                                $output .= '"'.__('Member Name','church_mgt').'",';
                                            if($key=='amount')
                                                $output .= '"'.__('Amount','church_mgt').'",';
                                            if($key=='transaction_date')
                                                $output .= '"'.__('Transaction Date','church_mgt').'",';
                                            if($key=='pay_method')
                                                $output .= '"'.__('Pay Method','church_mgt').'",';
                                        }
                                        $output .="\n";
                                        $i=1;
                                        foreach($result as $single_rec)
                                        {
                                            $output .='"'.$i.'",';
                                            foreach($single_rec as $key=>$row)
                                            {
                                                
                                                if($key=='member_id' ||  $key=='amount' || $key=='transaction_date' || $key=='pay_method'){
                                                
                                                if($key=='member_id') 
                                                    $output .='"'.MJ_cmgt_church_get_display_name($row).'",';
                                                else
                                                    $output .='"'.$row.'",';
                                                }
                                                
                                            }
                                            $output .="\n";
                                            $i++;
                                        }
                                        // Download the file
                                        fputs($fp,$output);
                                        fclose($fp);
                                        ?>
                                            <div class="clear col-md-12"><?php esc_html_e("Your file is ready. You can download it from",'church_mgt');?> <a href='<?php echo $filename;?>'><?php esc_html_e('Download','church_mgt') ?></a><?php
                                    }
                                    else
                                    { ?>
                                        <!-- <div class="clear col-md-12">
                                            <?php esc_html_e("There is not enough data to generate report.",'church_mgt');?>
                                        </div> -->
                                        <div class="calendar-event-new"> 
                                            <img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
                                        </div>
                                    <?php 
                                    }
                                }
                            } 
                        }
                        if($active_tab == 'income_report')
                        {
                            $active_tab_1 = sanitize_text_field(isset($_GET['tab_1'])?$_GET['tab_1']:'graph');
                            ?>
                             <ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per margin_top_20px" role="tablist">

                                <li class="<?php if($active_tab_1 =='datatable'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=income_report&tab_1=datatable" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'datatable' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Datatable', 'church_mgt'); ?></a>
                                </li> 

                                <li class="<?php if($active_tab_1 =='graph'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=income_report&tab_1=graph" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'graph' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Graph', 'church_mgt'); ?></a>
                                </li> 
                                
                            </ul> 
                            <?php
                            $obj_payment=new Cmgtpayment;
                            if($_REQUEST['tab_1'] == 'graph')
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
                                $invoice_data= $obj_payment->MJ_cmgt_get_all_income_data();
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
                                array_push($dataPoints_2, array(esc_html__('Month','church_mgt'),esc_html__('Income','church_mgt')));
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
                                   
                                    // die;
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
                                        $income_amount = $new_currency_symbol.' '.$income_yearly_amount;
                                    }
                            
                                    array_push($dataPoints_2, array($value,$income_amount));
                                    
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
                                                colors: ['#104B73', '#FF9054'],
                                                
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
                                    {
                                        "use strict";
                                        <?php
                                        if (is_rtl())
                                        {
                                            ?>	
                                            $('#income_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            $('#income_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
                                            <?php
                                        }
                                        ?>
                                    } );
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
                                                    <input type="submit" name="income_payment" Value="<?php esc_attr_e('Go','church_mgt');?>"  class="btn btn-info save_btn"/>
                                                </div>
                                            </div>
                                        </div>
                                    </form> 
                                </div>	
                                <?php
                                if(isset($_REQUEST['income_payment']))
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
                                $table_name=$wpdb->prefix.'cmgt_income_expense';
                                
                                $paymentdata = $wpdb->get_results("select *from $table_name where invoice_date BETWEEN '$start_date' AND '$end_date' AND invoice_type = 'income'");
                            
                                if(!empty($paymentdata))
                                {
                                    ?>
                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            var table = jQuery('#tblincome').DataTable({
                                            //"responsive": true,
                                            "dom": 'lifrtp',
                                            language:<?php echo MJ_cmgt_datatable_multi_language();?>,
                                            "order": [[ 2, "asc" ]],
                                            buttons:[
                                                {
                                                    extend: 'csv',
                                                    text:'CSV',
                                                    title: 'Income Report',
                                                    exportOptions: {
                                                        columns: [1, 2, 3,4,5,6], 
                                                    }
                                                },
                                                {
                                                    extend: 'print',
                                                    text:'Print',
                                                    title: 'Income Report',
                                                    exportOptions: {
                                                        columns: [1, 2, 3,4,5,6], 
                                                    }
                                                },
                                            ],
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
                                            $('.btn-place').html(table.buttons().container()); 
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
                                    <form name="wcwm_report" action="" method="post">
                                        <div class="panel-body"><!--PANEL BODY DIV START-->
                                            <div class="cmgt_payment_table_responsive table-responsive"><!--TABLE RESPONSIVE DIV START-->
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
                            
                                                                    <td class="payment_status width_15_per <?php if($retrieved_data->payment_status == "Unpaid"){ ?>red_color<?php }elseif($retrieved_data->payment_status == "Paid"){ ?>green_color<?php }else{ echo"blue_color";} ?>"><?php echo _e($retrieved_data->payment_status,'church_mgt');?> </td>
                            
                                                                    <td class="income_amount width_15_per"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($total_amount);?> </td>
                            
                                                                    <td class="date width_15_per"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->invoice_date)));?> </td>
                                                                    
                                                                </tr>
                                                            <?php
                                                            $i++;
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div><!--TABLE RESPONSIVE DIV END-->
                                        </div>	<!--PANEL BODY DIV END-->	
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
                               
                                <?php
                            }
                        }
                        if($active_tab == 'expense_report')
                        {
                            $active_tab_1 = sanitize_text_field(isset($_GET['tab_1'])?$_GET['tab_1']:'graph');
                            ?> 
                            <ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per margin_top_20px" role="tablist">

                                <li class="<?php if($active_tab_1 =='datatable'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=expense_report&tab_1=datatable" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'datatable' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Datatable', 'church_mgt'); ?></a>
                                </li> 

                                <li class="<?php if($active_tab_1 =='graph'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=expense_report&tab_1=graph" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'graph' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Graph', 'church_mgt'); ?></a>
                                </li> 
                                
                            </ul> 
                            <?php
                            $obj_payment=new Cmgtpayment;
                            if($_REQUEST['tab_1'] == 'graph')
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
                                $invoice_data= $obj_payment->MJ_cmgt_get_all_income_data();
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
                                array_push($dataPoints_2, array(esc_html__('Month','church_mgt'),esc_html__('Expense','church_mgt')));
                                $dataPoints_1 = array();
                                $currency_symbol = MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
                                $new_currency_symbol = html_entity_decode($currency_symbol);
                                // var_dump($currency_symbol);
                                // die;
                                foreach($month as $key=>$value)
                                {
                                    global $wpdb;
                                    $table_name = $wpdb->prefix."cmgt_income_expense";
                                    
                                    if(!empty($year)){
                                        $q = "SELECT * FROM $table_name WHERE YEAR(invoice_date) = $year AND MONTH(invoice_date) = $key AND invoice_type = 'expense'";
                                        $result=$wpdb->get_results($q);
                                    }
                                
                                    // die;
                                    $expense_yearly_amount = 0;
                                    foreach($result as $expense_entry)
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
                                        $expense_amount = $new_currency_symbol.' '.$expense_yearly_amount;
                                    }
                                    array_push($dataPoints_2, array($value,$expense_amount));		
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
                                                colors: ['#FF9054'],
                                                
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
                                    {
                                        "use strict";
                                        <?php
                                        if (is_rtl())
                                        {
                                            ?>	
                                            $('#expense_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            $('#expense_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
                                            <?php
                                        }
                                        ?>
                                    } );
                                </script>
                                <div class="panel-body clearfix margin_top_20px">
                                    <form method="post" id="expense_payment">  
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
                                                    <input type="submit" name="expense_payment" Value="<?php esc_attr_e('Go','church_mgt');?>"  class="btn btn-info save_btn"/>
                                                </div>
                                            </div>
                                        </div>
                                    </form> 
                                </div>	
                                <?php
                                if(isset($_REQUEST['expense_payment']))
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
                                $table_name=$wpdb->prefix.'cmgt_income_expense';

                                $payment_expense_data = $wpdb->get_results("select *from $table_name where invoice_date BETWEEN '$start_date' AND '$end_date' AND invoice_type = 'expense'");

                                if(!empty( $payment_expense_data))
                                {
                                    ?>
                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            var table = jQuery('#tblexpence').DataTable({
                                            //"responsive": true,
                                            "dom": 'lifrtp',
                                            language:<?php echo MJ_cmgt_datatable_multi_language();?>,
                                            "order": [[ 2, "asc" ]],
                                            buttons:[
                                                {
                                                    extend: 'csv',
                                                    text:'CSV',
                                                    title: 'Expense Report',
                                                    exportOptions: {
                                                        columns: [1, 2, 3,4], 
                                                    }
                                                },
                                                {
                                                    extend: 'print',
                                                    text:'Print',
                                                    title: 'Expense Report',
                                                    exportOptions: {
                                                        columns: [1, 2, 3,4], 
                                                    }
                                                },
                                            ],
                                            "sSearch": "<i class='fa fa-search'></i>",
                                            "aoColumns":[
                                                            {"bSortable": false},
                                                            {"bSortable": true},
                                                            {"bSortable": true},
                                                            {"bSortable": true},
                                                            {"bSortable": true}]
                                            });
                                            $('.btn-place').html(table.buttons().container()); 
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
                                    <form name="wcwm_report" action="" method="post">
                                        <div class="panel-body"><!--PANEL BODY DIV START-->
                                            <div class="cmgt_payment_table_responsive table-responsive">
                                                <table id="tblexpence" class="display" cellspacing="0" width="100%">
                                                    <thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
                                                        <tr>
                                                            <th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
                                                            <th> <?php _e( 'Supplier Name', 'church_mgt' ) ;?></th>
                                                            <th> <?php _e( 'Amount', 'church_mgt' ) ;?></th>
                                                            <th> <?php _e( 'Payment Status', 'church_mgt' ) ;?></th>
                                                            <th> <?php _e( 'Date', 'church_mgt' ) ;?></th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
                                                            <th> <?php _e( 'Supplier Name', 'church_mgt' ) ;?></th>
                                                            <th> <?php _e( 'Amount', 'church_mgt' ) ;?></th>
                                                            <th> <?php _e( 'Payment Status', 'church_mgt' ) ;?></th>
                                                            <th> <?php _e( 'Date', 'church_mgt' ) ;?></th>
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

                                                                    <td class="party_name width_25_per"><a class="color_black show-invoice-popup" idtest="<?php echo esc_attr($retrieved_data->invoice_id); ?>" invoice_type="expense" href="#"><?php echo esc_attr($retrieved_data->supplier_name);?></a> </td>

                                                                    <td class="income_amount width_20_per"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($total_amount);?> </td>

                                                                    <td class="payment_status width_20_per <?php if($retrieved_data->payment_status == "Unpaid"){ ?>red_color<?php }elseif($retrieved_data->payment_status == "Paid"){ ?>green_color<?php }else{ echo"blue_color";} ?>"><?php echo _e($retrieved_data->payment_status,"church_mgt");?> </td>

                                                                    <td class="date width_20_per"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->invoice_date)));?> </td>

                                                                </tr>
                                                                <?php 
                                                                $i++;
                                                            } 	
                                                            ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!--PANEL BODY DIV END-->
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

                                <?php
                            }   
                        }
                        if($active_tab == 'income_expense')
                        {
                            $active_tab_1 = sanitize_text_field(isset($_GET['tab_1'])?$_GET['tab_1']:'graph');
                            ?> 
                            <ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per margin_top_20px" role="tablist">

                                <li class="<?php if($active_tab_1 =='datatable'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=income_expense&tab_1=datatable" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'datatable' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Datatable', 'church_mgt'); ?></a>
                                </li> 

                                <li class="<?php if($active_tab_1 =='graph'){?>active<?php }?>"> 
                                    <a href="?church-dashboard=user&page=report&tab=income_expense&tab_1=graph" class="padding_left_0 tab <?php echo esc_html($active_tab_1) == 'graph' ? 'nav-tab-active' : ''?>">
                                    <?php echo esc_html__('Graph', 'church_mgt'); ?></a>
                                </li> 
                                
                            </ul> 
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
                            if($active_tab_1 == 'datatable')
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
                                            $(document).ready(function() 
                                            {
                                                "use strict";
                                                var table = jQuery('#table_income_expense').DataTable(
                                                {
                                                    "order": [[ 0, "Desc" ]],
                                                    dom: 'lifrtp',
                                                
                                                    "aoColumns":[
                                                        {"bSortable": false},
                                                        {"bSortable": true},
                                                        {"bSortable": true},
                                                        {"bSortable": true}
                                                    ],
                                                    language:<?php echo MJ_cmgt_datatable_multi_language();?>		   
                                                });
                                                $('.btn-place').html(table.buttons().container()); 
                                                $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt') ?>");
                                            } );
                                        </script>
                                    
                                            <div class="btn-place"></div>
                                            <form id="frm-example1" name="frm-example1" method="post">
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
                                                            <td class="patient_name"><?php if(!empty($expense_amount)){ echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).' '.$expense_amount; }else{ echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ))." 0"; } ?> </td>
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
                        }
                        ?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->
 
