<?php
// var_dump("hello");
// die;
require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
$obj_reservation=new Cmgtreservation;
$obj_dashboard= new Cmgtdashboard;
$obj_activity=new Cmgtactivity;
$obj_service=new Cmgtservice;
$obj_venue=new Cmgtvenue;
$obj_message=new Cmgt_message;
//-------- GET ALL RESERVATION DATA ----------//
$reservationdata=$obj_reservation->MJ_cmgt_get_all_reservation();
if(!empty($reservationdata)){
	foreach ( $reservationdata as $retrieved_data )  
	{
		$cal_array [] = array (
			'title' => $retrieved_data->usage_title,
			'start' =>mysql2date('Y-m-d', $retrieved_data->reserve_date) ,
			'end' => mysql2date('Y-m-d', $retrieved_data->reservation_end_date),
			'backgroundColor' => '#F25656'
		);
	}
}
if(isset($_GET['action']) && $_GET['action']=="birth_wish")
	{
		$user = get_userdata($_GET['mem_id']);
		$to=$user->user_email;
		$message = get_option('cmgt_birthday_mail_content');
		$subject = get_option('cmgt_birthday_mail_subject');
		$arr['{{member_name}}']=$user->display_name;	
		$arr['{{system_name}}']= get_option('cmgt_system_name');
		$replace_message =  MJ_cmgt_string_replacemnet($arr,$message);	
		if($replace_message)
		{
			$messagedata = array();			
			$messagedata['receiver'] = $_GET['mem_id'];
			$messagedata['subject'] = $subject;
			$messagedata['message_body'] = $replace_message;			
			$sussess = $obj_message->MJ_cmgt_add_message($messagedata);		
			if($sussess)
			{ 
				wp_mail($to,$subject,$replace_message);
				wp_redirect ( admin_url().'admin.php?page=cmgt-church_system&msg=1');
			}
		}
	} 
//-------- GET ALL ACTIVITY ----------//
$activitydata=$obj_activity->MJ_cmgt_get_all_activities();
if(!empty($activitydata)){
	foreach ( $activitydata as $retrieved_data ) 
	{
		$recurring_content=json_decode($retrieved_data->recurrence_content,true);
		$recuring_period='';
		if(!empty($recurring_content))
		{
			$recuring_period=$recurring_content['selected'];
		}	
		if($recuring_period=='yearly')
		{
			$yearly_data=$recurring_content['yearly'];
			$yearly_date=date('m/d',strtotime($yearly_data['yearly_date']));
			if($retrieved_data->activity_start_time!== 'Full Day' && $retrieved_data->activity_end_time!='Full Day'){
				$start_time= date("H:m:s", strtotime($retrieved_data->activity_start_time));
			}
			else
			{
				$start_time="";
			}
			while(strtotime($retrieved_data->activity_end_date) >= strtotime($retrieved_data->activity_date)) {
				if(date('m/d', strtotime($retrieved_data->activity_date))==$yearly_date)
				{
					if($start_time!=""){
					$start=date('Y/m/d', strtotime($retrieved_data->activity_date))." ".$start_time;
					$cal_array [] = array (
						'title' => $retrieved_data->activity_title,
						'start' =>mysql2date('Y-m-d H:m:s', $start) 
						);
					}
					else
					{
						$cal_array [] = array (
						'title' => $retrieved_data->activity_title,
						'start' =>mysql2date('Y-m-d', date('Y/m/d', strtotime($retrieved_data->activity_date))) 
						);
					}
				}
				$retrieved_data->activity_date =date('Y/m/d', strtotime($retrieved_data->activity_date. ' + 1 days'));
			}
		}
		if($recuring_period=='monthly')
		{
			$monthly_data=$recurring_content['monthly'];
			$monthly_date=$monthly_data['month_date'];
			if($retrieved_data->activity_start_time!== 'Full Day' && $retrieved_data->activity_end_time!='Full Day'){
				$start_time= date("H:m:s", strtotime($retrieved_data->activity_start_time));
			}
			else
			{
				$start_time="";
			}
			
			while(strtotime($retrieved_data->activity_end_date) >= strtotime($retrieved_data->activity_date)) 
			{
				if(date('d', strtotime($retrieved_data->activity_date))==$monthly_date)
				{
					if($start_time!=""){
					$start=date('m/d', strtotime($retrieved_data->activity_date))." ".$start_time;
					
					$cal_array [] = array (
						'id'=>$retrieved_data->activity_id,
						'title' => $retrieved_data->activity_title,
						'start' =>mysql2date('Y-m-d H:m:s',$start) 
						);
					}
					else
					{
						$cal_array [] = array (
						'id'=>$retrieved_data->activity_id,
						'title' => $retrieved_data->activity_title,
						'start' =>mysql2date('Y-m-d',date('m/d', strtotime($retrieved_data->activity_date))) 
						);
					}
				}
				$retrieved_data->activity_date =date('Y/m/d', strtotime($retrieved_data->activity_date. ' + 1 days'));
			}
		}
		if($recuring_period=='daily')
		{
			$daily_data=$recurring_content['daily'];
			if($retrieved_data->activity_start_time!== 'Full Day' && $retrieved_data->activity_end_time!='Full Day'){
			$start_time= date("H:m:s", strtotime($retrieved_data->activity_start_time));
			$end_time= date("H:m:s", strtotime($retrieved_data->activity_end_time));
			$start=date('Y/m/d', strtotime($retrieved_data->activity_date))." ".$start_time;
			$end=date('Y/m/d', strtotime($retrieved_data->activity_end_date))." ".$end_time;
			}	
			else
			{
				$start=date('Y/m/d', strtotime($retrieved_data->activity_date));
				$end=date('Y/m/d', strtotime($retrieved_data->activity_end_date));
			}
			$cal_array [] = array (
				'id'=>$retrieved_data->activity_id,
				'title' => $retrieved_data->activity_title,
				'start' =>mysql2date('Y-m-d g:i:A', $start), 
				'end' =>mysql2date('Y-m-d g:i:A', $end), 
			);
		}
		if($recuring_period=='weekly')
		{
			$weekly_data=$recurring_content['weekly'];
			$weekly_days=$weekly_data['weekly'];
			if($retrieved_data->activity_start_time!== 'Full Day' && $retrieved_data->activity_end_time!='Full Day'){
				$start_time= date("H:m:s", strtotime($retrieved_data->activity_start_time));
			}
			else
			{
				$start_time="";
			}
			while(strtotime($retrieved_data->activity_end_date) >= strtotime($retrieved_data->activity_date)) {
			$curr_day= date('l', strtotime($retrieved_data->activity_date));
			if(!empty($weekly_days))
			if(in_array($curr_day,$weekly_days))
			{
				
				if($start_time!=""){
				$start=date('Y/m/d', strtotime($retrieved_data->activity_date))." ".$start_time;
				$cal_array [] = array (
					'id'=>$retrieved_data->activity_id,
					'title' => $retrieved_data->activity_title,
					'start' =>mysql2date('Y-m-d H:m:s',$start) 
					);
				}
				else
				{
					$cal_array [] = array (
					'id'=>$retrieved_data->activity_id,
					'title' => $retrieved_data->activity_title,
					'start' =>mysql2date('Y-m-d', date('Y/m/d', strtotime($retrieved_data->activity_date))) 
					);
				}
			}
			$retrieved_data->activity_date =date('Y/m/d', strtotime($retrieved_data->activity_date. ' + 1 days'));
			}
		}
	}
}
//-------- GET ALL SERVICES -----------//
$servicedata=$obj_service->MJ_cmgt_get_all_services();
if(!empty($servicedata))
{
	foreach ( $servicedata as $retrieved_data ) 
	{		
		$cal_array [] = array (
			'title' => $retrieved_data->service_title,
			'start' =>mysql2date('Y-m-d', $retrieved_data->start_date) ,
			'end' => mysql2date('Y-m-d', $retrieved_data->end_date),
			'backgroundColor' => '#5FCE9B'				
		);
	}
}
$birthday_boys=get_users(array('role'=>'member'));
 if (! empty ( $birthday_boys )) 
{
	foreach ( $birthday_boys as $boys ) 
	{
		$startdate = date("Y",strtotime($boys->birth_date));
		$enddate = $startdate + 100;
		$years = range($startdate,$enddate,1);
		foreach($years as $year)
		{	
			$startdate1=date("m-d",strtotime($boys->birth_date));
			 $cal_array [] = array (
			'title' => $boys->first_name."'s Birthday",
			'start' =>"{$year}-{$startdate1}",
			'end' =>"{$year}-{$startdate1}",
			'backgroundColor' => '#F25656');
		} 
	}
}
?>

<!DOCTYPE html>
<html lang="en"><!-- HTML START -->
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dataTables.editor.min.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dataTables.tableTools.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/jquery-ui.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/fullcalendar.css'; ?>">
        <link rel="shortcut icon" href="<?php echo CMS_PLUGIN_URL.'/assets/images/favicon.ico'; ?>"/>
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/bootstrap.min.css'; ?>">	
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/datepicker.css'; ?>">  
       
           
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/gymmgt.min.css'; ?>">
        <?php  if(is_rtl())
        {
            ?>
            <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/bootstrap-rtl.min.css'; ?>">
            <?php  
        } ?>
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine.jquery.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/gym-responsive.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/gymmgt.min.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/newversion.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/white.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/font-awesome.min.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/time.css'; ?>">  
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/bootstrap-multiselect.min.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/bootstrap.min.css'; ?>">	
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/custom.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/custom-admin.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/popup.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/style.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/new-design.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dataTables.responsive.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dashboard.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dataTables.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/select.dataTables.min.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/accordian/jquery-ui.css'; ?>">
        <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/fullcalendar.min.css'; ?>">
        <!--- <script type="text/javascript" src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery-1.11.1.min.js'; ?>"></script> --->
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/accordian/jquery-ui.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/moment.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/fullcalendar.min.js'; ?>"></script>
        <?php
            $lancode=get_locale();
            $code=substr($lancode,0,2);
        ?>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/calendar-lang/'.$code.'.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery.dataTables.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/dataTables.tableTools.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/dataTables.editor.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/dataTables.responsive.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/popup.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/image-upload.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap-multiselect.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap.bundle.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/popper.min.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/time.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery.timeago.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
        <?php
            $lancode=get_locale();
            $code=substr($lancode,0,2);	
        ?>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery-ui.js'; ?>"></script>


        <!--<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap.min.js'; ?>"></script>-->
        <!-- <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script> -->
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/responsive-tabs.js'; ?>"></script>
        <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-en.js'; ?>"></script>
           
		<script>
			var calendar_laungage ="<?php echo MJ_cmgt_calander_laungage();?>";
			//$ = jQuery.noConflict();
			var $ = jQuery.noConflict();
			document.addEventListener('DOMContentLoaded', function() 
			{
				var calendarEl = document.getElementById('calendar');

				var calendar = new FullCalendar.Calendar(calendarEl, 
				{
					 dayMaxEventRows: true,	
					initialView: 'dayGridMonth',
					locale: calendar_laungage,
					headerToolbar: {
						left: 'prev,next today',
						center: 'title',
						right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
					},
						editable: false,
						slotEventOverlap: true,
						//timeFormat: 'h(:mm)a',
						eventTimeFormat: { // like '14:30:00'
							hour: 'numeric',
							minute: '2-digit',
							meridiem: 'short'
						},
						//eventLimit:1, // allow "more" link when too many events
						events: <?php echo json_encode($cal_array); ?>,
						forceEventDuration : true,
						
						//eventMouseEnter
						eventMouseover : function (event, jsEvent, view) {
				  
					//end date change with minus 1 day
					<?php $dformate=get_option('cmgt_datepicker_format'); ?>
					var dateformate_value='<?php echo $dformate;?>';	
					if(dateformate_value == 'yy-mm-dd')
					{	
						var dateformate='YYYY-MM-DD';
					}
					if(dateformate_value == 'yy/mm/dd')
					{	
						var dateformate='YYYY/MM/DD';	
					}
					if(dateformate_value == 'dd-mm-yy')
					{	
						var dateformate='DD-MM-YYYY';
					}
					if(dateformate_value == 'mm-dd-yy')
					{	
						var dateformate='MM-DD-YYYY';
					}
					if(dateformate_value == 'mm/dd/yy')
					{	
						var dateformate='MM/DD/YYYY';	
					}
						
						var newdate = event.event.end;
						
						var type = event.event._def.extendedProps.type;
						var date = new Date(newdate);
						var newdate1 = new Date(date);
					if(type == 'reservationdata')
					{
						newdate1.setDate(newdate1.getDate());
					}
					else
					{
						newdate1.setDate(newdate1.getDate() - 1);
					}
					var dateObj = new Date(newdate1);
					var momentObj = moment(dateObj);
					var momentString = momentObj.format(dateformate);
					var newstartdate = event.event.start;
					var date = new Date(newstartdate);
					var startdate = new Date(date);
					var dateObjstart = new Date(startdate);
					var momentObjstart = moment(dateObjstart);
					var momentStringstart = momentObjstart.format(dateformate);
					
					if(type == 'Birthday')
					{
						tooltip = "<div class='tooltiptopicevent dasboard_Birthday'>" + "<?php esc_html_e("Title Name","church_mgt"); ?>" + " : " + event.event.title + "</br>" + " <?php esc_html_e("Birthday Date","church_mgt"); ?> " + " : " + momentStringstart + " </div>";	
					}
					else
					{
						tooltip = "<div class='tooltiptopicevent dasboard_Birthday'>" + "<?php esc_html_e("Title Name","church_mgt"); ?>" + " : " + event.event.title + "</br>" + " <?php esc_html_e("Start Date","church_mgt"); ?> " + " : " + momentStringstart + "</br>" + "<?php esc_html_e("End Date","church_mgt"); ?>" + " : " + momentString + "</br>" +  " </div>";	
					}
						$("body").append(tooltip);
						$(this).mouseover(function (e) {
							$(this).css('z-index', 10000);
							$('.tooltiptopicevent').fadeIn('5000');
							$('.tooltiptopicevent').fadeTo('100', 1.9);
						}).mousemove(function (e) {
							$('.tooltiptopicevent').css('top', e.pageY + 10);
							$('.tooltiptopicevent').css('left', e.pageX + 20);
						});

					},
					eventMouseLeave: function (data, event, view)
					{
						"use strict"
						$(this).css('z-index', 8);
						$('.tooltiptopicevent').remove();
					},

				});

				calendar.render();
			});
		</script>
		<script>
			jQuery(document).ready(function (p) {
				p(".cmgt-navigation li:has(ul)").prepend('<span class="cmgt-droparrow"></span>'), p(".cmgt-droparrow").click(function () {
					p(this).siblings(".sub-menu").slideToggle("slow"), p(this).toggleClass("up")
				}), p(".sidebarScroll").slimScroll({
					height: "100%",
					size: "6px"
				}), p(".cmgt-dropdown-toggle").click(function () {
					p(this).toggleClass("cmgt-dropdown-active"), p(this).siblings(".cmgt-dropdown").slideToggle("slow"), p(this).parents(".cmgt-dropdownmain").toggleClass("cmgt-dropdown-open")
				}), p(document).mouseup(function (s) {
					var o = p(".cmgt-dropdown-open");
					o.is(s.target) || 0 !== o.has(s.target).length || (p(".cmgt-dropdown").fadeOut(), p(".cmgt-dropdown-toggle").removeClass("cmgt-dropdown-active"), p(".cmgt-dropdownmain").removeClass("cmgt-dropdown-open"))
				}), p(".cmgt-menuIcon").click(function () {
					p(this).toggleClass("cmgt-close"), p(".cmgt-sidebar").toggleClass("cmgt-slideMenu"), p("body").toggleClass("cmgt-bodyFix")
				}), p(".cmgt-overlay").click(function () {
					p(".cmgt-menuIcon").removeClass("cmgt-close"), p(".cmgt-sidebar").removeClass("cmgt-slideMenu"), p(".cmgt-bodyFix").removeClass("cmgt-bodyFix")
				}), p("#datetimepicker1").datepicker({
					autoclose: !0,
					todayHighlight: !0
				}).datepicker("update", new Date), p(".cmgt-popclick").off("click"), p(document).ready(function () {
					p("body").on("click", ".cmgt-popclick", function () {
						var s = p(this).attr("data-pop");
						p("#" + s).addClass("cmgt-popVisible"), p("body").addClass("cmgt-bodyFixed")
					})
				}), p(".cmgt-closePopup, .cmgt-popup-cancel, .cmgt-overlayer").click(function () {
					p("body").removeClass("cmgt-bodyFixed"), p("#SavingModal").css("display", "none"), p("#WarningModal").css("display", "none"), p("#SuccessModal").css("display", "none"), p("#DeleteModal").css("display", "none"), p(".cmgt-popupMain").removeClass("cmgt-popVisible")
				}), p("input[type=file]").change(function () {
					var s = this.value.split("\\").pop();
					p(this).closest(".cmgt-btn-file").next(".text").text(s)
				}), p(".cmgt-closeLoading, .cmgt-preLoading-onsubmit").click(function () {
					p(".cmgt-preLoading-onsubmit").css("display", "none")
				})
			});
			(function ($) {
				$(window).load(function () {
					$(".cmgt-preLoading").fadeOut()
				})
			})(jQuery);
		</script>
	</head>
    
    <body>
		<div class="popup-bg">
            <div class="modal-dialog">
                <div class="modal-content" style="border-top: 5px solid #22baa0;">
                    <div class="task_event_list"></div>
                </div>
            </div>     
        </div>
		<div class="row">
			<div class="col col-sm-2 col-md-2 col-lg-2 col-xl-2">
				
			</div>
			<div class="col col-sm-10 col-md-10 col-lg-10 col-xl-10">

			</div>
		</div>
        <header class='cmgt-header'>
				<a href='' class='cmgt-logo'>
					<span class='cmgt-logo-mini'>
						<img src="<?php echo get_option( 'cmgt_system_logo' ) ?>" class="img" width="45px" height="40px">
					</span>
					<span class='cmgt-schoolname'>Church-Management</span>
				</a>
                <!--<div class="cmgt-menuIcon"><span></span></div>-->
                <h3 class="cmgt-customeMsg">Live as if you were to die tomorrow. Learn as if you were to live forever.</h3>

                <div class="cmgt-righthead">
                    <div class="cmgt-head-action"></div>
                    <div class="cmgt-userMain cmgt-dropdownmain ">
                        <!--<div class="cmgt-profile-pic cmgt-dropdown-toggle">
                            <img src='http://192.168.1.28/wp_schoolpress/wp-content/plugins/wpschoolpress/img/avatar.png' class='cmgt-userPic' alt='User Image' />
                            <span class="cmgt-username">Admin</span>
                        </div>
                        <div class="cmgt-dropdown">
                            <ul>
                                <li class='cmgt-back-wp'><a href='#'>Back to wp-admin</a></li>
                            <li class='cmgt-back-wp-editprofile'><a href=''>Edit Profile</a></li>

                            <li class='cmgt-back-wp-changepassword'><a href='#'>Change Password</a></li>
                            <li><a href='#'>Sign Out</a></li>
                            
                                <button class="btn">Academic year<span class="badge">schoolyear</span></button>
                            
                            </ul>
                        </div> -->
                    </div>
                </div> 
        </header>
		<div class="row">
			<div class="col col-sm-2 col-md-2 col-lg-2 col-xl-2 cmgt-mainsidebar">
				<aside class='cmgt-sidebar'>
					<ul class='cmgt-navigation'>

						<li class="active">
							<a href='#'>
							<i class='fa fa-tachometer icon'></i>
							<span>Dashboard</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-file-text icon'></i>
							<span>Document</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-users icon'></i>
							<span>Group</span>
							</a>
						</li>
						<li class="">
							<a href=''>
							<i class='fa fa-male icon'></i>
							<span>Ministry</span>
							</a>
						</li>
						<li class="has-submenu">
							<a href='#'>
							<i class='fa fa-user icon'></i>
							<span>User</span><i class="fa fa-angle-down dropdown-icon icon" aria-hidden="true"></i>
							</a>
							<ul class='sub-menu'>
								<li class=''>
									<a href='#'>
									<span>Member</span>
									</a>
								</li>
								<li class=''>
									<a href='#'>
									<span>Family Member</span>
									</a>
								</li>
								<li class=''>
									<a href='#'>
									<span>Accountant</span>
									</a>
								</li>
							</ul> 
							
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-server icon'></i>
							<span>Services</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-star icon'></i>
							<span>Activity</span>
							</a>
						</li>
						<li class="#">
							<a href=''>
							<i class='fa fa-clock-o icon'></i>
							<span>Attendance</span>
							</a>
						</li>
						<li class="">
							<a href=''>
							<i class='fa fa-venus-mars icon'></i>
							<span>Venue</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-check-circle icon'></i>
							<span>Check-In</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-list icon'></i>
							<span>Sermon List</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-music icon'></i>
							<span>Songs</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-paper-plane icon'></i>
							<span>Pledges</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-gift icon'></i>
							<span>Spiritual Gift</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-shopping-cart icon'></i>
							<span>Payment</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-comments icon'></i>
							<span>Notice</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-envelope icon'></i>
							<span>Message</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-envelope icon'></i>
							<span>Pastoral</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-envelope icon'></i>
							<span>News Letter</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-flag icon'></i>
							<span>Report</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-key icon'></i>
							<span>Access Rights</span>
							</a>
						</li>
						<li class="">
							<a href='#'>
							<i class='fa fa-envelope icon'></i>
							<span>Mail Template</span>
							</a>
						</li><li class="">
							<a href='#'>
							<i class='fa fa-sliders icon'></i>
							<span>General Setting</span>
							</a>
						</li>
					</ul>
				</aside>
			</div>
			<!-- End task-event POP-UP Code -->

			<div class="col col-sm-10 col-md-10 col-lg-10 col-xl-10 mainpage-inner">
				<div class="page-inner dashboard_margin"><!-- PAGE INNER DIV START-->
					<div class="page-title">
						<h3><img src="<?php echo get_option( 'cmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'cmgt_system_name' );?>
						</h3>
					</div>
					<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->  
						<?php if(isset($_GET['msg']) && $_GET['msg']=="1")
						{ ?>
							<div id="message" class="updated below-h2 " style="margin-bottom:20px"><p><?php esc_html_e("Message successfully Sent.",'church_mgt');	?></p>
							</div>
						<?php 
							} ?>
						<div class="row"><!-- Start Row2 -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- Member card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-member';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body member">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Church-Member-white.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Church-Member.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo count(get_users(array('role'=>'member')))	;?></p>
												<span class="info-box-title"><?php echo esc_html( esc_html_e( 'Members', 'church_mgt' ) );?></span>
											</div>
										</div>
									</div>
								</a>
							</div><!-- Member card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- Accountant card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-accountant';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body accountant">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Accountant_new.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Accountant_new_black.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo count(get_users(array('role'=>'accountant')));?></p>
												<span class="info-box-title"><?php echo esc_html( esc_html_e( 'Accountant', 'church_mgt' ) );?></span>
											</div>
										</div>
									</div>
								</a>
							</div><!-- Accountant card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- group card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-group';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body group">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Group_new.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Group_new_black.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo MJ_cmgt_count_group();?></p>
												<span class="info-box-title"><?php echo esc_html( esc_html_e( 'Group', 'church_mgt' ) );?></span>
											</div>
										</div>
									</div>
								</a>
							</div><!-- group card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- ministry card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-ministry';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body ministry">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/church-ministry-white.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/church-ministry.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo MJ_cmgt_count_ministry();?></p>
												<span class="info-box-title"><?php echo esc_html( esc_html_e( 'Ministry', 'church_mgt' ) );?></span>
											</div>
										</div>
									</div>
								</a>
							</div><!-- ministry card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- Services card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-service';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body services">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/services-white.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/services.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo MJ_cmgt_count_services();?></p>
												<span class="info-box-title"><?php echo esc_html( esc_html_e( 'Services', 'church_mgt' ) );?></span>
											</div>
										</div>
									</div>
								</a>
							</div><!-- Services card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- message card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-message';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body message">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Message_new.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Message_new_black.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo count(MJ_cmgt_count_inbox_item(get_current_user_id()));?></p>
												<span class="info-box-title"><?php echo esc_html( esc_html_e( 'Message', 'church_mgt' ) );?></span>
											</div>
										</div>
									</div>
								</a>
							</div><!-- message card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- setting card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-general-setting';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body setting">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Settings.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Settings_black.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo "";?><span class="info-box-title"><?php echo esc_html( esc_html_e( 'Settings', 'church_mgt' ) );?></span>
												</p>
											</div>
											
										</div>
									</div>
								</a>
							</div><!-- setting card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- notice card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-notice';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body notice_event">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Notice.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Notice_black.png"?>" class="dashboard_background_second notice_black_img">
											<div class="info-box-stats">
												<p class="counter"><?php echo MJ_cmgt_count_notice();?></p>
												<span class="info-box-title"><?php echo esc_html( esc_html_e( 'Notice', 'church_mgt' ) );?></span>
											</div>
										</div>
									</div>
								</a>
							</div><!-- notice card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- attendance card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-attendance';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body attendance">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/TodayAttendance.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/TodayAttendance_black.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo MJ_cmgt_today_presents();?><span class="info-box-title"><?php echo esc_html( esc_html_e( 'Today Attendance', 'church_mgt' ) );?></span></p>
											</div>
										</div>
									</div>
								</a>
							</div><!-- attendance card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- reservation card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-venue&tab=reservation_list';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body nutrition">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/reservation.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/reservation_black.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo MJ_cmgt_count_reservation();?><span class="info-box-title"><?php echo esc_html( esc_html_e( 'Reservation', 'church_mgt' ) );?></span></p>
											</div>
										</div>
									</div>
								</a>
							</div><!-- reservation card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- pledges card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-pledges';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body pledges">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Pledges-white.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Pledges.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo MJ_cmgt_count_pledges();?><span class="info-box-title"><?php echo esc_html( esc_html_e( 'Pledges', 'church_mgt' ) );?></span></p>
											</div>
										</div>
									</div>
								</a>
							</div><!-- pledges card end -->
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6"><!-- song card start -->
								<a href="<?php echo admin_url().'admin.php?page=cmgt-song';?>">
									<div class="panel info-box panel-white">
										<div class="panel-body song">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Songs-white.png"?>" class="dashboard_background">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/dashboard/Songs.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo MJ_cmgt_count_song();?><span class="info-box-title"><?php echo esc_html( esc_html_e( 'Songs', 'church_mgt' ) );?></span></p>
											</div>
										</div>
									</div>
								</a>
							</div><!-- song card end -->
							<div class="col-md-6">
						<!-- Activity Attendance Report Start -->
						<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
							<div class="panel panel-body panel-white result_report">
								<div class="panel-heading activity_attendance_report">
									<h3 class="panel-title"><i class="fa fa-file-text" aria-hidden="true"></i><?php esc_html_e('Activity Attendance Report','church_mgt');?></h3>						
								</div>
									<?php 
									global $wpdb;
									$table_attendance = $wpdb->prefix .'cmgt_attendence';
									$table_activity = $wpdb->prefix .'cmgt_activity';
									$chart_array = array();
									$report_activity =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present,SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_activity as cl where `attendence_date` > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND at.activity_id = cl.activity_id AND at.role_name = 'member' GROUP BY at.activity_id") ;
									$chart_array[] = array(esc_html__('activity','church_mgt'),esc_html__('Present','church_mgt'),esc_html__('Absent','church_mgt'));
											if(!empty($report_activity))
												foreach($report_activity as $result)
												{
													$activity_id =MJ_cmgt_get_activity_name($result->activity_id);
													$chart_array[] = array("$activity_id",(int)$result->Present,(int)$result->Absent);
												}
												$options = Array(
																	'title' => esc_html__('Activity Attendance Report','church_mgt'),
																	'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																	'legend' =>Array('position' => 'right',
																	'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
																		
																	'hAxis' => Array(
																						'title' =>  esc_html__('Activity','church_mgt'),
																						'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																						'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																						'maxAlternation' => 2
																					),
																	'vAxis' => Array(
																						'title' =>  esc_html__('No of Member','church_mgt'),
																						'minValue' => 0,
																						'maxValue' => 4,
																						'format' => '#',
																						'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																						'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
																					),
																	'colors' => array('#22BAA0','#f25656')
																								);
							$GoogleCharts = new GoogleCharts;
							if(!empty($report_activity))
							{
								$chart = $GoogleCharts->load( 'column' , 'attendance_report_activity' )->get( $chart_array , $options );
							}
								if(isset($report_activity) && count($report_activity) >0)
								{
									
								?>
									<div id="attendance_report_activity" style="width: 100%; height: 500px;"></div>
							
								<!-- Javascript --> 
								<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
								<script type="text/javascript">
											<?php echo $chart;?>
									</script>
							<?php 
								}
							if(isset($report_activity) && empty($report_activity))
							{?>
								<div class="clear col-md-12 error_msg"><?php esc_html_e("No Data Available",'church_mgt');?></div>
							<?php }?>
						
							</div>
						</div>
						<!-- Activity Attendance Report end -->
						<!-- Ministry Attendance Report Start -->
						<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
							<div class="panel panel-body panel-white result_report">
								<div class="panel-heading ministry_attendance_report">
									<h3 class="panel-title"><i class="fa fa-file-text" aria-hidden="true"></i><?php esc_html_e('Ministry Attendance Report','church_mgt');?></h3>						
								</div>
									<?php 
									global $wpdb;
									$table_attendance = $wpdb->prefix .'cmgt_attendence';
									$table_ministry = $wpdb->prefix .'cmgt_ministry';
									$chart_array = array();
									$report_ministry =$wpdb->get_results("SELECT at.activity_id,SUM(case when `status` ='Present' then 1 else 0 end) as Present,SUM(case when `status` ='Absent' then 1 else 0 end) as Absent from $table_attendance as at,$table_ministry as cl where `attendence_date` > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND at.activity_id = cl.id AND at.role_name = 'ministry' GROUP BY at.activity_id");
									$chart_array[] = array(esc_html__('ministry','church_mgt'),esc_html__('Present','church_mgt'),esc_html__('Absent','church_mgt'));
											if(!empty($report_ministry))
												foreach($report_ministry as $result)
												{
													$activity_id =MJ_cmgt_get_ministry_name($result->activity_id);
													$chart_array[] = array("$activity_id",(int)$result->Present,(int)$result->Absent);
												}
												$options = Array(
																	'title' => esc_html__('Ministry Attendance Report','church_mgt'),
																	'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																	'legend' =>Array('position' => 'right',
																	'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
																		
																	'hAxis' => Array(
																						'title' =>  esc_html__('Ministry','church_mgt'),
																						'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																						'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																						'maxAlternation' => 2
																					),
																	'vAxis' => Array(
																						'title' =>  esc_html__('No of Member','church_mgt'),
																						'minValue' => 0,
																						'maxValue' => 4,
																						'format' => '#',
																						'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																						'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
																					),
																	'colors' => array('#22BAA0','#f25656')
																								);

							$GoogleCharts = new GoogleCharts;
								if(!empty($report_ministry))
								{
									$chart = $GoogleCharts->load( 'column' , 'attendance_report_ministry' )->get( $chart_array , $options );
								}
								if(isset($report_ministry) && count($report_ministry) >0)
								{
									
								?>
									<div id="attendance_report_ministry" style="width: 100%; height: 500px;"></div>
							
								<!-- Javascript --> 
								<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
								<script type="text/javascript">
											<?php echo $chart;?>
									</script>
							<?php 
								}
							if(isset($report_ministry) && empty($report_ministry))
							{?>
								<div class="clear col-md-12 error_msg"><?php esc_html_e("No Data Available",'church_mgt');?></div>
							<?php }?>
						
							</div>
						</div>
						<!-- Ministry Attendance Report End -->
						<!-- Payment Report Start -->
						<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
							<div class="panel panel-body panel-white result_report">
								<div class="panel-heading payment_report">
									<h3 class="panel-title"><i class="fa fa-file-text" aria-hidden="true"></i><?php esc_html_e('Payment Report','church_mgt');?></h3>						
								</div>
								<?php 
								$month =array('1'=>"January",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",'9'=>"September",'10'=>"Octomber",'11'=>"November",'12'=>"December",);
								$year =isset($_POST['year'])?$_POST['year']:date('Y');
								global $wpdb;
								$table_name = $wpdb->prefix."cmgt_transaction";
								$q="SELECT EXTRACT(MONTH FROM created_date) as date,sum(amount) as count FROM ".$table_name." WHERE YEAR(created_date) =".$year." group by month(created_date) ORDER BY created_date ASC";
								$result_payment_r_data=$wpdb->get_results($q);
								//var_dump($result_payment_r_data);
								$chart_array = array();
								$chart_array[] = array(esc_html__('Month','church_mgt'),esc_html__('Payment','church_mgt'));
								if(!empty($result_payment_r_data))
								{
									foreach($result_payment_r_data as $r)
									{
										$chart_array[]=array( $month[$r->date],(int)$r->count);
									}
								}
								
								$options = Array('title' => esc_html__('Payment Report By Month','church_mgt'),'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),'legend' =>Array('position' => 'right','textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),'hAxis' => Array('title' => esc_html__('Month','church_mgt'),'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),'textStyle' => Array('color' => '#66707e','fontSize' => 11),'maxAlternation' => 2),'vAxis' => Array('title' => esc_html__('Payment','church_mgt'),'minValue' => 0,'maxValue' => 5,'format' => '#','titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),'textStyle' => Array('color' => '#66707e','fontSize' => 12)),'colors' => array('#22BAA0'));
								$GoogleCharts = new GoogleCharts;
								?>
								<script type="text/javascript">
									$(document).ready(function() 
									{
										$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
										$('.edate').datepicker({dateFormat: "yy-mm-dd"}); 
									} );
								</script>
								<?php
								if(!empty($chart_array))
								{
									$chart = $GoogleCharts->load( 'column' , 'Payment_report' )->get( $chart_array , $options );
								}
								if(isset($result_payment_r_data) && count($result_payment_r_data) >0)
								{
								?>
									<div id="Payment_report" style="width: 100%; height: 500px;"></div>
							
								<!-- Javascript --> 
								<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
								<script type="text/javascript">
											<?php echo $chart;?>
									</script>
							<?php 
								}
								if(isset($result_payment_r_data) && empty($result_payment_r_data))
								{
								?>
									<div class="clear col-md-12 error_msg"><?php esc_html__("No Data Available",'church_mgt');?></div>
								<?php 
								}?>
							</div>
						</div>
						<!-- Payment Report End -->
						<!-- Activity Report Start -->
						<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
							<div class="panel panel-body panel-white result_report">
								<div class="panel-heading activity_report">
									<h3 class="panel-title"><i class="fa fa-file-text" aria-hidden="true"></i><?php esc_html_e('Activity Report','church_mgt');?></h3>						
								</div>
								<?php 
								$month =array('1'=>"January",'2'=>"February",'3'=>"March",'4'=>"April",
										'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",
										'9'=>"September",'10'=>"Octomber",'11'=>"November",'12'=>"December",);
								$year =isset($_POST['year'])?$_POST['year']:date('Y');
								global $wpdb;
								$table_name = $wpdb->prefix."cmgt_activity";
								$q="SELECT EXTRACT(MONTH FROM created_date) as date,count(*) as activity FROM ".$table_name." WHERE YEAR(created_date) =".$year." group by month(created_date) ORDER BY created_date ASC";
								$result_activity_data=$wpdb->get_results($q);
								$chart_array = array();
								$chart_array[] = array(esc_html__('Month','church_mgt'),esc_html__('Activity','church_mgt'));
								if(!empty($result_activity_data))
								{
									foreach($result_activity_data as $r)
									{
										$chart_array[]=array( $month[$r->date],(int)$r->activity);
									}
								}
								
								$options = Array(
													'title' => esc_html__('Activity Report By Month','church_mgt'),
													'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
													'legend' =>Array('position' => 'right',
														'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
													'hAxis' => Array(
													'title' => esc_html__('Month','church_mgt'),
													'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
													'textStyle' => Array('color' => '#66707e','fontSize' => 11),
													'maxAlternation' => 2 ),
													'vAxis' => Array(
													'title' => esc_html__('Activity','church_mgt'),
													'minValue' => 0,
													'maxValue' => 5,
													'format' => '#',
													'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
													'textStyle' => Array('color' => '#66707e','fontSize' => 12)),
													'colors' => array('#22BAA0'));
								$GoogleCharts = new GoogleCharts;
								?>
								<?php
								if(!empty($chart_array))
								{
									$chart = $GoogleCharts->load( 'column' , 'activity_report' )->get( $chart_array , $options );
								}
								if(isset($result_activity_data) && count($result_activity_data) >0)
								{
									
								?>
									<div id="activity_report" style="width: 100%; height: 500px;"></div>
							
									<!-- Javascript --> 
									<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
									<script type="text/javascript">
												<?php echo $chart;?>
										</script>
							<?php 
								}
								if(isset($result_activity_data) && empty($result_activity_data))
								{
								?>
									<div class="clear col-md-12 error_msg"><?php esc_html_e("No Data Available",'church_mgt');?></div>
								<?php 
								}
								?>
							</div>
						</div>
						<!-- Activity Report End -->
						</div>
						<div class="col-md-6 col-sm-12 col-xs-12 left_side_dashboard">
							<div class="panel panel-white group_btn dashboard_height"><!-- Group List Start -->
								<div class="panel-heading grp_list">
									<h3 class="panel-title"><i class="fa fa-users" aria-hidden="true"></i><?php esc_html_e('Group List','church_mgt');?></h3>
									<ul class="nav navbar-right panel_toolbox">
										<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=cmgt-group';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										</li>
									</ul>
								</div>
								<div class="panel-body grp">
									<div class="events">
									<?php
										$obj_group=new Cmgtdashboard;
										$groupdata=$obj_group->MJ_cmgt_get_grouplist();
										if(!empty($groupdata))
										{
												foreach ($groupdata as $retrieved_data)
												{
													$group_count=$obj_group->MJ_cmgt_count_group_members($retrieved_data->id);
												?>
										<div class="calendar-event view-complaint"> 
										<p class="remainder_title_pr Bold viewdetail show_task_event" id="<?php echo $retrieved_data->id;?>" model="Group Details"> <?php esc_html_e('Group Name : ','church_mgt');?>
										<?php echo $retrieved_data->group_name;?></p>							
										<?php if(!empty($group_count) ) { ?>
												<span class="btn btn-success btn-xs"><?php echo $group_count;?></span>
												<?php 
													}
													else{
														?>  <span class="btn btn-success btn-xs"><?php echo "0";?></span><?php
													}
													?>
										</div>	
									<?php
										}	
									} 
									else 
										esc_html_e("No Upcoming Group",'church_mgt');
									?>
									</div>
								</div>				
							</div><!-- Group List End -->
							<div class="panel dashboard_height panel-white activet"><!-- Activity List Start -->
								<div class="panel-heading activities">
									<h3 class="panel-title"><i class="fa fa-truck" aria-hidden="true"></i><?php esc_html_e('Activities','church_mgt');?></h3>
									<ul class="nav navbar-right panel_toolbox">
										<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=cmgt-activity';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										</li>                  
									</ul>
								</div>
								<div class="panel-body">
									<table class="table table-borderless activity_btn">
									<?php 
											$obj_activity=new Cmgtdashboard;
											$activitydata=$obj_activity->MJ_cmgt_get_activity();
											if(!empty($activitydata))
											{ ?>
												<thead>
													<tr>
													<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Activity Name','church_mgt');?></th>
													<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Activity Category','church_mgt');?></th>
													<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Guest Speaker','church_mgt');?></th>
													</tr>
												</thead>
												<tbody>
												<?php
														foreach ($activitydata as $retrieved_data)
														{
														?>	
													<tr>
														<td class="unit remainder_title_pr_cursor show_task_event " id="<?php echo $retrieved_data->activity_id;?>" model="activity Details"><?php echo $retrieved_data->activity_title;  ?></td>
													<td class="unit"><?php echo get_the_title($retrieved_data->activity_cat_id);?></td>
													<td class="unit">	<?php echo $retrieved_data->speaker_name;?></td>
													</tr>
														<?php
														}  ?>
												</tbody>
								<?php
										} 
										else 
											esc_html_e("No Upcoming Activity",'church_mgt');
											?>
									</table>
								</div>
							</div><!-- Activity List End -->
							<div class="panel panel-white ministry_btn dashboard_height"><!-- Ministry List Start -->
								<div class="panel-heading ministry_list">
									<h3 class="panel-title"><i class="fa fa-user" aria-hidden="true"></i><?php esc_html_e('Ministry List','church_mgt');?></h3>
									<ul class="nav navbar-right panel_toolbox">
										<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=cmgt-ministry';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										</li>
									</ul>
								</div>
								<div class="panel-body grp">
										<div class="events">
										<?php 
											$obj_ministry=new Cmgtdashboard;
											$ministrydata=$obj_ministry->MJ_cmgt_get_all_ministry_dashboard();
											if(!empty($ministrydata))
											{
													foreach ($ministrydata as $retrieved_data)
													{
														$ministry_count=$obj_ministry->MJ_cmgt_count_ministry_members($retrieved_data->id);
													?>
											<div class="calendar-event view-complaint"> 
											<p class="remainder_title_pr Bold viewdetail show_task_event" id="<?php echo $retrieved_data->id;?>" model="ministry Details"> <?php esc_html_e('Ministry Name : ','church_mgt');?>
											<?php echo $retrieved_data->ministry_name;?></p>							
											<?php if(!empty($ministry_count) ) { ?>
													<span class="btn btn-success btn-xs"><?php echo $ministry_count;?></span></td>
													<?php 
														}
														else{
															?>  <span class="btn btn-success btn-xs"><?php echo "0";?></span><?php
														}
														?>
											</div>	
										<?php
											}	
										} 
										else 
											esc_html_e("No Upcoming Ministry",'church_mgt');
										?>
									</div>
								</div>				
							</div><!-- Ministry List End -->
						<!-- Services List start -->
						<div class="panel panel-white dashboard_height services_btn">
							<div class="panel-heading service">
								<h3 class="panel-title"><i class="fa fa-book" aria-hidden="true"></i><?php esc_html_e('Services','church_mgt');?></h3>
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=cmgt-service'; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>
								</ul>
							</div>
							<div class="panel-body service_list">
								<table class="table table-borderless calendar-event">
									<thead>
										<tr>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Service Name','church_mgt');?></th>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Start Date','church_mgt');?></th>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('End Date','church_mgt');?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$obj_Service=new Cmgtservice;
										$Service_data=$obj_Service->MJ_cmgt_get_all_services_dashboard();
										if(!empty($Service_data))
										{
											foreach($Service_data as $retrieved_data)
											{
										?>
										<tr>
											<td class="unit remainder_title_pr_cursor show_task_event " id="<?php echo $retrieved_data->id;?>" model="service Details"><?php echo $retrieved_data->service_title;  ?></td>
										<td class="unit"><p class="remainder_date_pr"><?php $date= str_replace('00:00:00',"",$retrieved_data->start_date);
																echo date(MJ_cmgt_date_formate(),strtotime($date));
														?></p></td>
										<td class="unit"><p class="remainder_date_pr"><?php $date1= str_replace('00:00:00',"",$retrieved_data->end_date);
																echo date(MJ_cmgt_date_formate(),strtotime($date1));
														?></p></td>
										</tr>
										<?php 
											}
										}
										else { ?>
										
											<tr>
												<td colspan="4" ><?php  esc_html_e('No Data Available','church_mgt');?></td>
											</tr>
										<?php }
										?>
									</tbody>
									</table>
							</div>
						</div><!-- Services List End -->
						<!-- Reservation List start -->
						<div class="panel dashboard_height panel-white">
							<div class="panel-heading res_list">
								<h3 class="panel-title"><i class="fa fa-ticket" aria-hidden="true"></i><?php esc_html_e('Reservation List','church_mgt');?></h3>
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=cmgt-venue&tab=reservation_list';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>                  
								</ul>
							</div>
							<div class="panel-body">
								<div class="events">
								<?php 
									$obj_reservation=new Cmgtreservation;
									$reservationdata=$obj_reservation->MJ_cmgt_get_all_reservation_dashboard();
									if(!empty($reservationdata))
									{
										foreach ($reservationdata as $retrieved_data)
										{
											?>
									<div class="calendar-event view-complaint"> 
										<p class="remainder_title_pr Bold viewpriscription show_task_event" id="<?php echo $retrieved_data->id;?>" model="Reservation Details"><?php esc_html_e('Use title : ','church_mgt');?>
										<?php echo $retrieved_data->usage_title;?>							
										</p><p class="remainder_date_pr"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->reserve_date));?></p>
										<p class="">
										<?php esc_html_e('Description : ','church_mgt');?>
										<?php echo  ( $retrieved_data->description );?></p>
									</div>	
									<?php
										}	
									} 
									else 
										esc_html_e("No Upcoming Reservation",'church_mgt');
									?>
								</div>
							</div>
						</div><!-- Reservation List End -->
						<!-- Donation List Start -->
						<div class="panel panel-white dashboard_height invoice_btn">
							<div class="panel-heading invoice">
								<h3 class="panel-title"><i class="fa fa-list-alt" aria-hidden="true"></i><?php esc_html_e('Donation List','church_mgt');?></h3>
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?page=cmgt-payment&tab=transactionlist';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>
								</ul>
							</div>
							<div class="panel-body">
								<div class="events">
									<table class="table table-borderless">
									<?php 
									$obj_payment=new Cmgttransaction;
									$paymentdata=$obj_payment->MJ_cmgt_get_my_donationlist_dashboard();
									if(!empty($paymentdata))
									{
									?>
									<thead>
										<tr>
											<th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Member Name','church_mgt');?></th>
											<th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Total Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)</th>
											<th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Payment Type','church_mgt');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
									foreach ($paymentdata as $retrieved_data)
									{
										?>
										<tr>
											<td class="unit remainder_title_pr_cursor show_task_event " id="<?php echo $retrieved_data->id;?>" model="Donation Details">
											<?php $user=get_userdata($retrieved_data->member_id);
											echo $user->display_name; ?>
											</td>
											<td class="unit"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo $retrieved_data->amount; ?> </td>
											<td class="unit"><span><?php echo $retrieved_data->pay_method; ?></span></td>
										</tr>
									<?php
									}
									?>
									</tbody>
									<?php	
									} 
									else
										esc_html_e("No Upcoming Donations list",'church_mgt');
									?>
									</table>							
								</div>
							</div>
						</div><!-- Donation List End -->
						<!-- Pastoral List Start -->
						<div class="panel panel-white dashboard_height">
							<div class="panel-heading pastoral_list">
								<h3 class="panel-title"><i class="fa fa-list-alt" aria-hidden="true"></i><?php esc_html_e('Pastoral List','church_mgt');?></h3>
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=cmgt-pastoral&tab=pastoral_list';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>
								</ul>
							</div>
							<div class="panel-body">
								<div class="events">
									<table class="table table-borderless">
									<?php 
									$obj_pastoral=new Cmgtpastoral;
									$pastoraldata=$obj_pastoral->MJ_cmgt_get_all_pastoral_dashboard();
									if(!empty($pastoraldata))
									{
									?>
									<thead>
									<tr>
										<th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Pastoral Title','church_mgt');?></th>
										<th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;`"><?php esc_html_e('Pastoral Date','church_mgt');?></th>
									</tr>
									</thead>
									<tbody>
									<?php
									foreach ($pastoraldata as $retrieved_data)
									{
									?>
									<tr>
										<td class="unit remainder_title_pr_cursor show_task_event " id="<?php echo $retrieved_data->id;?>" model="Pastoral Details"><?php echo $retrieved_data->pastoral_title;?></td>
										<td class="unit"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->pastoral_date));?></td>
									</tr>
									<?php
									}
									?>
									</tbody>
									<?php		
									} 
									else
										esc_html_e("No Upcoming Invoice list",'church_mgt');
									?>
									</table>							
								</div>
							</div>
						</div><!-- Pastoral List End -->
						<!-- Sell Gift List Start -->
						<div class="panel panel-white dashboard_height">
							<div class="panel-heading sell_gift_list">
								<h3 class="panel-title"><i class="fa fa-user" aria-hidden="true"></i><?php esc_html_e('Sell Gift List','church_mgt');?></h3>
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=cmgt-gifts&tab=sellgiftlist';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>                  
								</ul>
							</div>
							<div class="panel-body">
								<table class="table table-borderless">
									<?php 
									$obj_sellgift=new Cmgtgift;
									$sellgiftdata=$obj_sellgift->MJ_cmgt_get_all_sell_gifts_dashboard();
									if(!empty($sellgiftdata))
									{
									?>
									<thead>
										<tr>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Gift Name','church_mgt');?></th>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Selling Date','church_mgt');?></th>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Price','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)</th>
										</tr>
									</thead>
									<tbody>
									<?php
										foreach ($sellgiftdata as $retrieved_data)
										{
										?>
										<tr>
											<td class="unit gift remainder_title_pr_cursor show_task_event" id="<?php echo $retrieved_data->id;?>" model="Sell Gift Details"><?php echo MJ_cmgt_church_get_gift_name($retrieved_data->gift_id);?>
											<td class="unit"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->sell_date));?></td>
											<td class="unit"><span class="btn btn-primary btn-xs"><?php echo $retrieved_data->gift_price; ?></span></td>
										</tr>
										<?php
										}
										?>
									</tbody>
									<?php		
									} 
									else 
										esc_html_e("No Upcoming Sell Gift",'church_mgt');
									?>
								</table>
							</div>
						</div><!-- Sell Gift List End -->
						<!-- Birthday List Start -->
						<div class="panel panel-white dashboard_height">
							<div class="panel-heading sell_gift_list">
								<h3 class="panel-title"><i class="fas fa-birthday-cake"></i> <?php esc_html_e('Birthday List','church_mgt');?></h3>
							</div>
							<div class="panel-body">
								<table class="table table-borderless">
									<?php 
									$curr_date = date("m/d");	
									$usersdata = get_users(array( 'meta_key'=> 'birth_day', 'meta_value' => $curr_date));
									if(!empty($usersdata))
									{
									?>
									<thead>
										<tr>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Member Name','church_mgt');?></th>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Birth Date','church_mgt');?></th>
										<th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php esc_html_e('Wish','church_mgt');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										foreach($usersdata as $user_key=>$user_val)
										{
											if(empty($user_val->cmgt_user_avatar))
											{
												$profile_img = get_option('cmgt_system_logo');
											}
											else
											{
												$profile_img = $user_val->cmgt_user_avatar;
											}
										?>
										<tr>								
											<td><img class="img img-rounded" src="<?php print $profile_img; ?>" style="height:30px; width:30px; float:left;"/> <?php print  " ".$user_val->display_name ?></td>
											<td><?php print get_user_meta($user_val->ID,'birth_date',true); ?></td>
											<td><a href="<?php echo admin_url().'admin.php?page=cmgt-church_system&action=birth_wish&mem_id='.$user_val->ID;?>" class="btn btn-success"><?php _e('Wish','church_mgt');?></a></td>
										</tr>	
										<?php
										}
										?>
									</tbody>
									<?php		
									} 
									else
									{
										esc_html_e("No Upcoming Birthday",'church_mgt');
									}
									?>
								</table>
							</div>
						</div><!-- Birthday List End -->
						<div class="panel panel-white cad">
							<div class="panel-body cal">
								<div id="calendar"></div>
							</div>
						</div>
					</div>




						</div><!-- END Row2 -->
						


					</div><!-- MAIN WRAPPER DIV END-->
				</div><!-- PAGE INNER DIV END-->
			</div>
		</div>
    </body>
</html>