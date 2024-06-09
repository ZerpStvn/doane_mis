<?php //======Front end template=========
require_once(ABSPATH.'wp-admin/includes/user.php' );
$user = wp_get_current_user ();
$obj_dashboard= new Cmgtdashboard;
$user_id=get_current_user_id(); 
$obj_church = new Church_management(get_current_user_id());
$cal_array= array();

//CHECK USER LOGIN IF LOGIN SO REDIRECT IN PAGE //
if (! is_user_logged_in ()) 
{
	$page_id = get_option ( 'cmgt_login_page' );

	wp_redirect ( home_url () . "?page_id=" . $page_id );
} 

//CHECK USER LOGIN IF LOGIN SO REDIRECT ADMIN SIDE //
if (is_super_admin ()) 
{
	wp_redirect ( admin_url () . 'admin.php?page=cmgt-church_system' );
}
	
$obj_reservation=new Cmgtreservation;
$obj_activity=new Cmgtactivity;
$obj_service=new Cmgtservice;
//-------- GET ALL RESERVATION DATA START----------//
$reservationdata=$obj_reservation->MJ_cmgt_get_all_reservation();
if(!empty($reservationdata))
{
	foreach ( $reservationdata as $retrieved_data ) 
	{
		 
		$cal_array [] = array (
				'title' => $retrieved_data->usage_title,
				'start' =>mysql2date('Y-m-d', $retrieved_data->reserve_date) ,
				'end' => mysql2date('Y-m-d', $retrieved_data->reservation_end_date),
				'backgroundColor' => '#BD5CA8'
		);
	}
}
//-------- GET ALL RESERVATION DATA END----------//
//-------- GET ALL ACTIVITY DATA START----------//
$activitydata=$obj_activity->MJ_cmgt_get_all_activities();
if(!empty($activitydata))
{
	foreach ( $activitydata as $retrieved_data ) 
	{
		// $cal_array [] = array (
		// 		'title' => $retrieved_data->activity_title,
		// 		'start' =>mysql2date('Y-m-d', $retrieved_data->activity_date) ,
		// 		'end' => mysql2date('Y-m-d', $retrieved_data->activity_end_date),
		// 		'backgroundColor' => '#3c8dbc'		
		// );
		$recurrence_content = json_decode($retrieved_data->recurrence_content);
		
		if($recurrence_content->selected == "daily")
		{
			$startDate = $retrieved_data->activity_date;
			$endDate = $retrieved_data->activity_end_date;

			$cal_array [] = array (
				'title' => $retrieved_data->activity_title,
				'start' =>mysql2date('Y-m-d', $startDate),
				'end' => mysql2date('Y-m-d', $endDate),
				'backgroundColor' => '#3c8dbc'
				
			);
		}
		if($recurrence_content->selected == "weekly")
		{
			$startDate = $retrieved_data->activity_date;
			$endDate = $retrieved_data->activity_end_date;

			$weekly_day = array();
			if(!empty($recurrence_content->weekly->weekly))
			foreach($recurrence_content->weekly->weekly as $value)
			{
				$day_number = MJ_cmgt_get_day_number($value);
				$weekly_day[] = getDateForSpecificDayBetweenDates($startDate,$endDate ,$day_number);
			}
			$result = call_user_func_array("array_merge", $weekly_day);
			foreach($result as $dates)
			{
				$cal_array [] = array (
					'title' => $retrieved_data->activity_title,
					'start' =>mysql2date('Y-m-d', $dates) ,
					'end' => mysql2date('Y-m-d', $dates),
					'backgroundColor' => '#3c8dbc'
					
				);
			}
		}
		if($recurrence_content->selected == "monthly")
		{
			$day = $recurrence_content->monthly->month_date;
			
			$startDate = $retrieved_data->activity_date;
			$endDate = $retrieved_data->activity_end_date;
			
			$get_between_date_array = get_between_date_array($startDate,$endDate,$day);

			foreach($get_between_date_array as $dates)
			{
				$cal_array [] = array (
					'title' => $retrieved_data->activity_title,
					'start' =>mysql2date('Y-m-d', $dates) ,
					'end' => mysql2date('Y-m-d', $dates),
					'backgroundColor' => '#3c8dbc'
					
				);
			}
		}
		if($recurrence_content->selected == "yearly")
		{
		
			$date = $recurrence_content->yearly->yearly_date;

			$startDate = $retrieved_data->activity_date;
			$endDate = $retrieved_data->activity_end_date;
			$get_yearly_between_date_array = get_yearly_between_date_array($startDate,$endDate,$date);
			
			foreach($get_yearly_between_date_array as $dates)
			{
				$cal_array [] = array (
					'title' => $retrieved_data->activity_title,
					'start' =>mysql2date('Y-m-d', $dates) ,
					'end' => mysql2date('Y-m-d', $dates),
					'backgroundColor' => '#3c8dbc'
					
				);
			}
		}
    }
	
}
//-------- GET ALL ACTIVITY DATA END----------//
//-------- GET ALL SERVICE DATA START----------//
$servicedata=$obj_service->MJ_cmgt_get_all_services();
if(!empty($servicedata))
{
	foreach ( $servicedata as $retrieved_data ) 
	{
		$cal_array [] = array (
				'title' => $retrieved_data->service_title,
				'start' =>mysql2date('Y-m-d', $retrieved_data->start_date) ,
				'end' => mysql2date('Y-m-d', $retrieved_data->end_date),
				'backgroundColor' => '#F25656'
		);
    }
}
//-------- GET ALL SERVICE DATA END----------//
//-------- GET ALL BIRTHDATE DATA START----------//
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
			'backgroundColor' => '#00a65a');
		} 
	}
}
//-------- GET ALL BIRTHDATE DATA END----------//
$site_title=my_custom_title();

?>
<!DOCTYPE html>
<html lang="en"><!-- HTML START -->
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $site_title['title'];?></title>
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dataTables.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dashboard.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dataTables.editor.min.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dataTables.responsive.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dataTables.tableTools.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/jquery-ui.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/font-awesome.min.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/popup.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/style.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/new-design.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/popping_font.css'; ?>">
		
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/cmgt-responsive.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/custom.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/fullcalendar.css'; ?>">
		<link rel="shortcut icon" href="<?php echo CMS_PLUGIN_URL.'/assets/images/favicon.ico'; ?>"/>
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/bootstrap.min.css'; ?>">	
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/datepicker.css'; ?>">  
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/time.css'; ?>">  
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/bootstrap-multiselect.min.css'; ?>">	
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/white.css'; ?>">	
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/gymmgt.min.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/dynamic_css.php'; ?>">

		<!-- Responsive css  -->
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/cmgt_responsive_ashish.css'; ?>">
		<!-- Responsive css end -->

		<!-- <link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/custom-admin.css'; ?>"> -->
		<?php
		if ( is_rtl() ) {
				// Load RTL CSS.
				?>
				<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/new_design_rtl.css'; ?>">
				<?php
			}
		?>
		<?php  if(is_rtl())
		{
			?>
			<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/bootstrap-rtl.min.css'; ?>">
			<?php  
		} ?>
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine.jquery.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.css'; ?>">
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/gym-responsive.css'; ?>">
		<script type="text/javascript" src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery-1.11.1.min-old.js'; ?>"></script>
		<!-- Material Design  -->
		<link rel="stylesheet"	href="<?php echo CMS_PLUGIN_URL.'/assets/default/material/bootstrap-inputs.css'; ?>">
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/default/material/material.min.js'; ?>"></script>
		<!-- Material Design End  -->
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery.min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery-ui.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/moment.min.js'; ?>"></script>

		<?php  if(!isset($_REQUEST['page']))
		{?>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/fullcalendar.min.js'; ?>"></script>
		<?php
		} ?>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery.timeago.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery.dataTables.min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/dataTables.responsive.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/dataTables.tableTools.min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/dataTables.editor.min.js'; ?>"></script>
		<!--<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap.min.js'; ?>"></script>-->
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap-multiselect.min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/popper.min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap.bundle.min.js'; ?>"></script>
		<!-- <script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script> -->
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/time.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/responsive-tabs.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-en.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/cmgt-dataTables-buttons-min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/cmgt-buttons-print-min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/cmgt-pdfmake-min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/cmgt-buttons.html5.min.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/cmgt-vfs_fonts.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/time.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/responsive-tabs.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-en.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>

		<?php	
		if(isset($_REQUEST['church-dashboard']) && $_REQUEST['church-dashboard'] == 'user')
		{
			?>
			<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/popup.js'; ?>"></script>
			<script id="cmgt-popup-front">
			var cmgt = {"ajax":"<?php echo admin_url('admin-ajax.php'); ?>"};
			</script>
			<?php	
		}
		?>

		<?php
			$lancode=get_locale();
			$code=substr($lancode,0,2);	
		?>
		<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
		<?php  if(!isset($_REQUEST['page']))
		{?>
			<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/calendar-lang/'.$code.'.js'; ?>"></script>
			<?php
		} ?>
	</head>
	<script>
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		});
	</script>
	<?php  
	if(!isset($_REQUEST['page']))
	{?>
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
						//left: 'prev,next today',
						left: 'prev,today,next',
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
	<?php
	} ?>

	<!--BODY START-->
	<body class="">
		<!--task-event POP up code -->
		<div class="popup-bg">
			<div class="modal-dialog">
			  	<div class="modal-content" style="margin-top: 25%;">
					<div class="task_event_list"></div>
			    </div>
			</div>     
		</div>
		<!-- End task-event POP-UP Code -->

			<?php
			if ( is_rtl() )
			{
				$rtl_left_icon_class = "fa-chevron-left";
			}
			else
			{
				$rtl_left_icon_class = "fa-chevron-right";
			}
			?>

		<!-- header part start -->
		<div class="row cmgt-header" style="margin: 0;">
			<div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 padding_0">
				<a href="<?php echo home_url().'?church-dashboard=user';?>" class='cmgt-logo'>
					<!-- <img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/WP-Church-Management-Logo.png"?>"> -->

					<img class="cmgt_header_logo" src="<?php  echo get_option( 'cmgt_system_logo' ); ?>" />
				</a>
				<!--  toggle button && desgin start-->
				<button type="button" id="sidebarCollapse" class="navbar-btn">
					<span></span>
					<span></span>
					<span></span>
				</button>	
				<!--  toggle button && desgin end-->
			</div>
			<div class="col-sm-12 col-md-12 col-lg-10 col-xl-10 cmgt-right-heder">
				<div class="row">
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<!-- <div class="row">
							<div class="col-sm-8 col-md-8 col-lg-9 col-xl-9">-->
								<div class="cmgt_title_add_btn"> 
									<h3 class="cmgt-addform-header-title">
										<?php 
											$page_name = "";
											$active_tab = "";
											$action_name = "";
											if(!empty($_REQUEST ['page']))
											{
												$page_name = $_REQUEST ['page'];
											}
											if(!empty($_REQUEST['tab']))
											{
												$active_tab = $_REQUEST["tab"];
											}
											if(!empty($_REQUEST['action']))
											{
												$action_name =$_REQUEST['action'];
											}

											if($page_name == "")
											{
												echo esc_html_e( 'Dashboard', 'church_mgt' );
											}
											elseif($page_name == "member")
											{
												if($active_tab == 'addmember' || $action_name=='view')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=member&tab=memberlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Member', 'church_mgt' );
													}
													elseif($action_name == 'view'){
														echo esc_html_e( 'View Member', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Member', 'church_mgt' );
													}
												
												}
												elseif($active_tab == 'view_invoice'){
													if($_REQUEST['invoice_type'] == 'transaction'){
														?>
														<a href='<?php echo home_url().'?church-dashboard=user&&page=member&tab=viewmember&tab1=invoice&action=view&member_id='.$_REQUEST['member_id'];?>'>
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
														</a>
														<?php
													}
													elseif($_REQUEST['invoice_type'] == 'pledges'){
														?>
														<a href='<?php echo home_url().'?church-dashboard=user&&page=member&tab=viewmember&tab1=peldgeslist&action=view&member_id='.$_REQUEST['member_id'];?>'>
															<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
														</a>
														<?php
													}
													echo esc_html_e( 'View Invoice', 'church_mgt' );
												}
												else
												{
													echo esc_html_e( 'Member', 'church_mgt' );
												}
											}
											elseif($page_name == "accountant")
											{
												if($active_tab == 'add_accountant' || $action_name=='view')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=accountant&tab=accountantlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Accountant', 'church_mgt' );
													}
													elseif($action_name == 'view'){
														echo esc_html_e( 'View Accountant', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Accountant', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Accountant', 'church_mgt' );
												}
											}
											elseif($page_name == "familymember")
											{
												if($active_tab == 'addfamily' || $action_name=='view')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=familymember';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Family Member', 'church_mgt' );
													}
													elseif($action_name == 'view'){
														echo esc_html_e( 'View Family Member', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Family Member', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Family Member', 'church_mgt' );
												}
											}
											elseif($page_name == "songs")
											{
												if($active_tab == 'addsong' || $action_name=='view-song'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=songs&tab=songlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Song', 'church_mgt' );
													}
													elseif($action_name == 'view-song'){
														echo esc_html_e( 'view Song', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Song', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Songs', 'church_mgt' );
												}
											}
											elseif($page_name == "group")
											{
												if($active_tab == 'addgroup' || $action_name=='view')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=group&tab=grouplist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Group', 'church_mgt' );
													}
													elseif($action_name == 'view'){
														echo esc_html_e( 'View Group', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Group', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Group', 'church_mgt' );
												}
											}
											elseif($page_name == "ministry")
											{
												if($active_tab == 'addministry' || $action_name=='view')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=ministry&tab=ministrylist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Ministry', 'church_mgt' );
													}
													elseif($action_name == 'view'){
														echo esc_html_e( 'View Ministry', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Ministry', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Ministry', 'church_mgt' );
												}
											}
											elseif($page_name == "services")
											{
												if($active_tab == 'addservice' || $action_name=='view')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=services&tab=serviceslist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit')
													{
														echo esc_html_e( 'Edit Service', 'church_mgt' );
													}
													elseif($action_name == 'view'){
														echo esc_html_e( 'View Service', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Service', 'church_mgt' );
													}
												}
												else
												{
													echo esc_html_e( 'Services', 'church_mgt' );
												}
											}
											elseif($page_name == "pastoral")
											{
												if($active_tab == 'add_pastoral' || $action_name=='view')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=pastoral&tab=pastoral_list';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Pastoral', 'church_mgt' );
													}
													elseif($action_name == 'view'){
														echo esc_html_e( 'View Pastoral', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Pastoral', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Pastoral', 'church_mgt' );
												}
											}
											elseif($page_name == "attendance")
											{
												if($page_name == "attendance" && $active_tab =="ministry_attendence")
												{
													echo esc_html_e( 'Ministry Attendance', 'church_mgt' );
												}else{
													echo esc_html_e( 'Attendance', 'church_mgt' );
												}
												//echo esc_html_e( 'Attendance', 'church_mgt' );
											}
											elseif($page_name == "activity")
											{
												if($active_tab == 'addactivity'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=activity&tab=activitylist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Activity', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Activity', 'church_mgt' );
													}
													
												}
												else{
													echo esc_html_e( 'Activity', 'church_mgt' );
												}
											}
											elseif($page_name == "venue")
											{
												if($active_tab == 'addvenue'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=venue&tab=venuelist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Venue', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Venue', 'church_mgt' );
													}
												}elseif($active_tab == 'add_reservation')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=venue&tab=reservation_list';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Reservation', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Reservation', 'church_mgt' );
													}
												}
												else{
													if($page_name == "venue" AND $active_tab == 'reservation_list'){
														echo esc_html_e( 'Reservation', 'church_mgt' );
													}else{
														echo esc_html_e( 'Venues', 'church_mgt' );
													}
												}
											}
											elseif($page_name == "check-in")
											{
												if($active_tab == 'addroom')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=check-in&tab=roomlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Room', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Room', 'church_mgt' );
													}
												}elseif($active_tab == 'checkin' && $action_name == 'booking')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=check-in&tab=roomlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													echo esc_html_e( 'Add Check-In', 'church_mgt' );
												}
												else{
													echo esc_html_e( 'Check-In', 'church_mgt' );
												}
											}
											elseif($page_name == "document")
											{
												if($active_tab == 'add_document'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=document&tab=documentlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Document', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Document', 'church_mgt' );
													}
													
												}
												else{
													echo esc_html_e( 'Document', 'church_mgt' );
												}
											}
											elseif($page_name == "sermon-list")
											{
												if($active_tab == 'addsermon')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=sermon-list&tab=sermonlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Sermon List', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Sermon List', 'church_mgt' );
													}
												}
												elseif($active_tab == 'view-sermon')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=sermon-list&tab=sermonlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													echo esc_html_e( 'view Sermon', 'church_mgt' );
												}
												else
												{
													echo esc_html_e( 'Sermon List', 'church_mgt' );
												}
											}
											elseif($page_name == "donate")
											{
												if($active_tab == 'addtransaction' || $active_tab == 'view_invoice')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=donate';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($active_tab == 'addtransaction' && $action_name == 'edit')
													{
														echo esc_html_e( 'Edit Donation ', 'church_mgt' );
													}
													elseif($active_tab == 'view_invoice')
													{
														echo esc_html_e( 'View Invoice', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Donation', 'church_mgt' );
													}
												}
												
												elseif($active_tab != 'addtransaction')
												{
													echo esc_html_e( 'Donate', 'church_mgt' );
												}
											}
											elseif($page_name == "spiritual-gift")
											{
												if($active_tab == 'addgift')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=spiritual-gift&tab=giftlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($active_tab == 'addgift' && $action_name == 'edit')
													{
														echo esc_html_e( 'Edit Spiritual Gift', 'church_mgt' );
													}else{
														echo esc_html_e( 'Add Spiritual Gift', 'church_mgt' );
													}
													
												}
												elseif($action_name == 'view-gift')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=spiritual-gift&tab=giftlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													echo esc_html_e( 'View Spiritual Gift', 'church_mgt' );
												}

												elseif($active_tab == 'sellgift')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=spiritual-gift&tab=sellgiftlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($active_tab == 'sellgift' && $action_name == 'edit')
													{
														echo esc_html_e( 'Edit Sell Gift ', 'church_mgt' );
													}else{
														echo esc_html_e( 'Add Sell Gift', 'church_mgt' );
													}
													
												}
												elseif($active_tab == 'sellgiftlist')
												{
													echo esc_html_e( 'Sell Gift List', 'church_mgt' );
												}
												else
												{
													echo esc_html_e( 'Spiritual Gifts', 'church_mgt' );
												}
												
											}
											elseif($page_name == "pledges")
											{
												if($active_tab == 'addpledges' || $active_tab == 'view_invoice'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=pledges&tab=pledgeslist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit'){
														echo esc_html_e( 'Edit Pledges', 'church_mgt' );
													}
													elseif($active_tab == 'view_invoice'){
														echo esc_html_e( 'View Invoice', 'church_mgt' );
													}
													else{
														echo esc_html_e( 'Add Pledges', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Pledges', 'church_mgt' );
												}
											}
											elseif($page_name == "payment")
											{
												if($active_tab == 'addtransaction')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=transactionlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($active_tab == 'addtransaction' && $action_name == 'edit')
													{
														echo esc_html_e( 'Edit Transaction', 'church_mgt' );
													}else{
														echo esc_html_e( 'Add Transaction', 'church_mgt' );
													}
												}
												elseif($active_tab == 'view_invoice'  && $_REQUEST['invoice_type'] == 'transaction'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=transactionlist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													echo esc_html_e( 'View Invoice', 'church_mgt' );	
												}
												elseif($active_tab == 'addincome')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=incomelist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($active_tab == 'addincome' && $action_name == 'edit')
													{
														echo esc_html_e( 'Edit Income', 'church_mgt' );
													}else{
														echo esc_html_e( 'Add Income', 'church_mgt' );
													}
													
												}
												elseif($active_tab == 'view_invoice'  && $_REQUEST['invoice_type'] == 'income'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=incomelist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													echo esc_html_e( 'View Invoice', 'church_mgt' );	
												}
												elseif($active_tab == 'addexpense')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=expenselist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($active_tab == 'addexpense' && $action_name == 'edit')
													{
														echo esc_html_e( 'Edit Expense', 'church_mgt' );
													}else{
														echo esc_html_e( 'Add Expense', 'church_mgt' );
													}
												}
												elseif($active_tab == 'view_invoice'  && $_REQUEST['invoice_type'] == 'expense'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=expenselist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													echo esc_html_e( 'View Invoice', 'church_mgt' );	
												}
												elseif($active_tab == 'transactionlist')
												{
													echo esc_html_e( 'Transaction List', 'church_mgt' );
												}
												elseif($active_tab == 'incomelist')
												{
													echo esc_html_e('Income List', 'church_mgt' );
												}
												elseif($active_tab == 'expenselist')
												{
													echo esc_html_e('Expense List', 'church_mgt' );
												}
												else
												{
													echo esc_html_e( 'Payment', 'church_mgt' );
												}

											}
											elseif($page_name == "notice")
											{
												if($active_tab == 'addnotice')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=notice&tab=noticelist';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit')
													{
														echo esc_html_e( 'Edit Notice', 'church_mgt' );
													}else{
														echo esc_html_e( 'Add Notice', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Notice', 'church_mgt' );
												}
											}
											elseif($page_name == "reservation")
											{
												if($active_tab == 'add_reservation')
												{
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=reservation&tab=reservation_list';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													if($action_name == 'edit')
													{
														echo esc_html_e( 'Edit Reservation', 'church_mgt' );
													}else{
														echo esc_html_e( 'Add Reservation', 'church_mgt' );
													}
												}
												else{
													echo esc_html_e( 'Reservations', 'church_mgt' );
												}
											}
											elseif($page_name == "message")
											{
												if($active_tab == 'compose'){
													?>
													<a href='<?php echo home_url().'?church-dashboard=user&page=message&tab=inbox';?>'>
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Back Arrow.png"?>">
													</a>
													<?php
													echo esc_html_e( 'Compose', 'church_mgt' );
												}
												else{
													echo esc_html_e( 'Messages', 'church_mgt' );
												}
											}
											elseif($page_name == "report")
											{
												if($active_tab == 'attendance_report'){
													echo esc_html_e( 'Attendance Reports', 'church_mgt' );
												}
												elseif($active_tab == 'payment_report' || $active_tab == 'payment_report' || $active_tab == 'income_report' || $active_tab == 'expense_report'){
													echo esc_html_e( 'Finance/Payment Reports', 'church_mgt' );
												}
												elseif($active_tab == 'activity_report'){
													echo esc_html_e( 'Activity Reports', 'church_mgt' );
												}
												elseif($active_tab == 'export-report'){
													echo esc_html_e( 'Download Reports', 'church_mgt' );
												}
												else{
													echo esc_html_e( 'Reports', 'church_mgt' );
												}
											}
											elseif($page_name == "account")
											{
												echo esc_html_e( 'Account', 'church_mgt');
											}
											elseif($page_name == "news_letter")
											{
												echo esc_html_e( 'Newsletter', 'church_mgt' );
											}
											elseif($page_name == "general-setting")
											{
												echo esc_html_e( 'General Setting', 'church_mgt' );
											}
											elseif($page_name == "mail_template")
											{
												echo esc_html_e( 'Mail Template', 'church_mgt' );
											}
											elseif($page_name == "access_right")
											{
												echo esc_html_e( 'Access Right', 'church_mgt' );
											}
											else
											{
												echo $page_name;
											}
										?>
									</h3>
									<?php
									$user_access=MJ_cmgt_get_userrole_wise_access_right_array();
									if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] != 'cmgt-church_system') 
									{
										// $page_name =$_REQUEST ['page'];    
										// $active_tab = $_REQUEST['tab'];
										if($page_name == "venue" && $active_tab != 'addvenue' && $action_name != 'view' && $active_tab !== 'add_reservation')
										{
											if($user_access['add'] == '1')
											{
												if(($page_name == "venue" AND $active_tab == 'reservation_list'))
												{
													?>
													<a href="?church-dashboard=user&page=venue&tab=add_reservation">
														<img class="cmgt-header-add-btn cmgt-addform-header-title" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
													</a>
													<?php
												}else
												{
													?>
													<a href="?church-dashboard=user&page=venue&tab=addvenue">
														<img class="cmgt-addform-header-title cmgt-header-add-btn" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
													</a>
													<?php
												}
											}
											
										}
										
										elseif($page_name == "familymember" && $active_tab != 'addfamily' && $action_name != 'view')
										{
											if($user_access['add'] == '1')
											{
												?>
												<a href="?church-dashboard=user&page=familymember&tab=addfamily">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
												</a>
												<?php
											}
										}

										elseif($page_name == "pledges" && $active_tab != 'addpledges' && $action_name != 'view' && $active_tab != 'view_invoice')
										{
											if($user_access['add'] == '1')
											{
												?>
												<a href="?church-dashboard=user&page=pledges&tab=addpledges">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
												</a>
												<?php
											}
										}
										elseif($page_name == "notice" && $active_tab != 'addnotice')
										{
											if($user_access['add'] == '1')
											{
												?>
												<a href="?church-dashboard=user&page=notice&tab=addnotice">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
												</a>
												<?php
											}
										}
										elseif($page_name == "reservation" && $active_tab != 'add_reservation')
										{
											if($user_access['add'] == '1')
											{
												?>
												<a href="?church-dashboard=user&page=reservation&tab=add_reservation">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
												</a>
												<?php
											}
										}
										elseif($page_name == "donate" && $active_tab != 'addtransaction')
										{
											if($user_access['add'] == '1')
											{
												?>
												<a href="?church-dashboard=user&page=donate&tab=addtransaction">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
												</a>
												<?php
											}
										}
										elseif($page_name == "songs" && $active_tab != 'addsong' && $action_name != 'view-song')
										{
											if($user_access['add'] == '1')
											{
											?>
											<a href="?church-dashboard=user&page=songs&tab=addsong">
												<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
											</a>
											<?php
											}
										}
										elseif($page_name == "message" && $active_tab != 'compose')
										{
											if($user_access['add'] == '1')
											{
												?>
												<a href="?church-dashboard=user&page=message&tab=compose">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
												</a>
												<?php
											}
										}
										elseif($page_name == "pastoral" && $active_tab != 'add_pastoral')
										{
											if($user_access['add'] == '1')
											{
												?>
												<a href="?church-dashboard=user&page=pastoral&tab=add_pastoral">
													<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
												</a>
												<?php
											}
										}
										elseif($page_name == "payment")
										{
											if($user_access['add'] == '1')
											{
												if($active_tab == 'transactionlist')
												{
													?>
													<a href="?church-dashboard=user&page=payment&tab=addtransaction">
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
													</a>
													<?php
												}
												if($active_tab == 'incomelist')
												{
													?>
													<a href="?church-dashboard=user&page=payment&tab=addincome">
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
													</a>
													<?php
												}
												if($active_tab == 'expenselist')
												{
													?>
													<a href="?church-dashboard=user&page=payment&tab=addexpense">
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>">
													</a>
													<?php
												}
											}
										}
									}
									?>
								</div>
							<!-- </div>
						</div> -->
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="cmgt-setting-notification">
							<a href='<?php echo home_url().'?church-dashboard=user&page=message&tab=inbox';?>' class="cmgt-setting-notification-bg">
								<img src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/Bell-Notification.png"?>" class="cmgt-right-heder-list-link">
								<spna class="between_border123 cmgt-right-heder-list-link"> </span>
							</a>
							<div class="cmgt-user-dropdown">
								<ul class="">
									<!-- BEGIN USER LOGIN DROPDOWN -->
									<li class="">
										<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<?php
												//$userimage = get_user_meta( $user->ID,'cmgt_user_avatar',true );

												$role_name = MJ_cmgt_get_user_role(get_current_user_id());
												$user_info = get_userdata(get_current_user_id());
												$userimage=$user_info->cmgt_user_avatar;
												?>
												<img src=" 
												<?php 
												if(!empty($userimage)) 
												{
													echo $userimage;
												}
												else
												{
													if($role_name == "member")
													{
														echo get_option( 'cmgt_member_thumb' ); 
													}
													elseif($role_name == "family_member")
													{
														echo get_option( 'cmgt_family_logo' ); 
													}
													else
													{
														echo get_option( 'cmgt_accountant_logo' ); 
													}
												} ?>" class="cmgt-dropdown-userimg" height="52px" width="52px" style="border-radius: 15px;">
										</a>
										<?php 

										if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'site_change')
										{
											$user_id=$_REQUEST['user_id'];
											clean_user_cache($user_id);
											wp_clear_auth_cookie();
											wp_set_current_user( $user_id );
											wp_set_auth_cookie( $user_id , true);
											$current_user_info=get_userdata($user_id);
											$redirect_url = home_url(). '?church-dashboard=user';
											wp_redirect($redirect_url);
										}
										$user = wp_get_current_user ();
										$role=implode(" ",$user->roles);
										$user_data =get_userdata( sanitize_text_field($user->ID));
										?>
										<ul class="dropdown-menu extended logout heder-dropdown-menu rtl_ml_0" aria-labelledby="dropdownMenuLink">
											<li><a class="dropdown-item cmgt-back-wp" href="<?php echo home_url().'?church-dashboard=user&page=account';?>"><i class="fa fa-user"></i>
											<?php echo $user_data->display_name; ?></a></li>
											<?php
											if(get_option('cmgt_family_can_login') == 'yes')
											{
												if($role == 'member')
												{
													$user_id = get_current_user_id();
													$user_meta =get_user_meta($user_id, 'family_id', true);
													
													foreach($user_meta as $parentsdata)
													{
														$parent=get_userdata($parentsdata);
														?>
														<li><a class="dropdown-item " href="<?php echo home_url().'?church-dashboard=user&action=site_change&user_id='.$parent->ID;?>"><i class="fa fa-users"></i><?php echo $parent->first_name." ".$parent->last_name." (".$parent->relation.")";?></a></li>
														<?php
													}
												}
												if($role == 'family_member')
												{
													$user_id = get_current_user_id();
													$user_meta =get_user_meta($user_id, 'member_id', true);
													$member=get_userdata($user_meta);
													?>
													<li><a class="dropdown-item " href="<?php echo home_url().'?church-dashboard=user&action=site_change&user_id='.$member->ID;?>"><i class="fa fa-users"></i><?php echo $member->first_name." ".$member->last_name;?></a></li>
													<?php
												}
											}
											?>
											<li><a class="dropdown-item" href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-sign-out m-r-xs"></i><?php esc_html_e( 'Log Out', 'church_mgt' ); ?></a></li>
										</ul>
									</li>
									<!-- END USER LOGIN DROPDOWN -->
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--MAIN PAGE PART START-->	
		<?php $user_access=MJ_cmgt_get_userrole_wise_access_right_array(); 
		?>	
		<div class="row main_page"  style="margin: 0;">
			<!--side menubar start  -->
			<div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 padding_0" id="main_sidebar-bgcolor">
				<div class="main_sidebar">
					<nav id="sidebar">
						<ul class='cmgt-frontend-navigation navbar-collapse' id="navbarNav">
							<li class="card-icon">
								<a href="<?php echo home_url().'?church-dashboard=user';?>" class="<?php if (isset ( $_REQUEST ['church-dashboard'] ) && $_REQUEST ['church-dashboard'] == "user" && isset($_REQUEST ['page']) == "") { echo "active"; } ?>">
									<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Dashboard.png"?>">
									<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Dashboard-white.png"?>">
									<span><?php esc_html_e( 'Dashboard', 'church_mgt' ); ?></span>
								</a>
							</li>
							<?php
							$access=MJ_cmgt_page_access_rolewise_accessright_dashboard('member');
							$access1=MJ_cmgt_page_access_rolewise_accessright_dashboard('familymember');
							$access2=MJ_cmgt_page_access_rolewise_accessright_dashboard('accountant');
							$access3=MJ_cmgt_page_access_rolewise_accessright_dashboard('group');
							$access4=MJ_cmgt_page_access_rolewise_accessright_dashboard('ministry');
							if ($access || $access1 || $access2 || $access3 || $access4) 
							{ 
								?>
								<li class="has-submenu nav-item card-icon">
									<a href='#' class="nav-link">
										<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Users.png"?>">
										<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Users-white.png"?>">
										<span><?php esc_html_e( 'Users', 'church_mgt' ); ?></span>
										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>
										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>
									</a>
									<ul class='submenu dropdown-menu'>
										<?php
										$page= 'member';
										$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
										if($access)
										{
											?>
											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=member';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "member") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Members', 'church_mgt' ); ?></span>
												</a>
											</li>
											<?php
										}
										?>
										<?php
										$page= 'familymember';
										$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
										if($access)
										{
												?>
											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=familymember';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "familymember") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Family Members', 'church_mgt' ); ?></span>
												</a>
											</li>
											<?php
										}
										$page= 'accountant';
										$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
										if($access)
										{
											?>
											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=accountant';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "accountant") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Accountant', 'church_mgt' ); ?></span>
												</a>
											</li>
											<?php
										}
										?>
										<?php
										$page= 'group';
										$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
										if($access)
										{
											?>
											<li class="">
												<a href='<?php echo home_url().'?church-dashboard=user&page=group';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "group") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Groups', 'church_mgt' ); ?></span>
												</a>
											</li>
											<?php
										}
										?>
										<?php
										$page= 'ministry';
										$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
										if($access)
										{
											?>
											<li class="">
												<a href="<?php echo home_url().'?church-dashboard=user&page=ministry';?>" class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "ministry") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Ministry', 'church_mgt' ); ?></span>
												</a>
											</li>
											<?php
										}
										?>
									</ul> 
								</li>
								<?php
							} ?>
							<?php
							$page_1= 'pastoral';
							$access_1=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_1);
							$page_2= 'services';
							$access_2=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_2);
							if($access_1 || $access_2){
							?>
							<li class="has-submenu nav-item card-icon">
								<a href='#' class="nav-link">
								<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/services.png"?>">
								<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/services-white.png"?>">
								<span><?php esc_html_e( 'Services', 'church_mgt' ); ?></span>
								<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>
								<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>
								</a>
								<ul class='submenu dropdown-menu'>
									<?php
									$page= 'services';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
									 	?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=services';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "service") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Services', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
									<?php
									$page= 'pastoral';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=pastoral';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "pastoral") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Pastoral', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
								</ul> 
							</li>
							<?php
							}
							$page= 'attendance';
							$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{
								?>	
								<li class="card-icon">
									<a href='<?php echo home_url().'?church-dashboard=user&page=attendance&tab=attendance_list';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "attendance") { echo "active"; } ?>">
									<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Attendance.png"?>">
									<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Attendance-white.png"?>">
									<span><?php esc_html_e( 'Attendance', 'church_mgt' ); ?></span>
									</a>
								</li>
								<?php
							}
							$page_1= 'activity';
							$access_1=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_1);
							$page_2= 'venue';
							$access_2=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_2);
							$page_3= 'reservation';
							$access_3=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_3);
							$page_4= 'check-in';
							$access_4=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_4);
							if($access_1 || $access_2 || $access_3 || $access_4)
							{
							?>
							<li class="has-submenu nav-item card-icon">
								<a href='#' class="nav-link">
								<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Activity.png"?>">
								<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Activity-white.png"?>">
								<span><?php esc_html_e( 'Activity', 'church_mgt' ); ?></span>
								<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>
								<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>
								</a>
								<ul class='submenu dropdown-menu'>
									<?php
									$page= 'activity';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=activity';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "activity") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Activity', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
									<?php
									$page= 'venue';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=venue';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "venue") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Venues', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
									<?php
									$page= 'reservation';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=reservation&tab=reservation_list';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "venue") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Reservations', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
									<?php
									$page= 'check-in';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=check-in';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "checkin") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Check-In', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
								</ul> 
							</li>
							<?php
							}
							$page= 'document';
							$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{
								?>
								<li class="card-icon">
									<a href='<?php echo home_url().'?church-dashboard=user&page=document';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "document") { echo "active"; } ?>">
										<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Documents.png"?>">
										<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Documents-white.png"?>">
										<span><?php esc_html_e( 'Document', 'church_mgt' ); ?></span>
									</a>
								</li> 
								<?php
							}
							?>
							<?php
							$page_1= 'sermon-list';
							$access_1=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_1);
							$page_2= 'spiritual-gift';
							$access_2=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_2);
							$page_3= 'pledges';
							$access_3=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_3);
							$page_4= 'songs';
							$access_4=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_4);
							if($access_1 || $access_2 || $access_3 || $access_4){
							?>
							<li class="has-submenu nav-item card-icon">
								<a href='#' class="nav-link">
								<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Worship.png"?>">
								<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Worship-white.png"?>">
								<span><?php esc_html_e( 'Worship', 'church_mgt' ); ?></span>
								<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>
								<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>
								</a>
								<ul class='submenu dropdown-menu'>
									<?php
									$page= 'sermon-list';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=sermon-list';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "sermon") { echo "active"; } ?>" >
											<span><?php esc_html_e( 'Sermon List', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
									<?php
									$page= 'spiritual-gift';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=spiritual-gift';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "gifts") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Spiritual Gifts', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
									<?php
									$page= 'pledges';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=pledges';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "pledges") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Pledges', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
									<?php
									$page= 'songs';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class="">
											<a href='<?php echo home_url().'?church-dashboard=user&page=songs';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "song") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Songs', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
								</ul> 
							</li>
							<?php
							}
							
							$page= 'donate';
							$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{
							?>
								<li class="card-icon">
									<a href='<?php echo home_url().'?church-dashboard=user&page=donate';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "donate") { echo "active"; } ?>">
									<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Payments.png"?>">
									<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Payments-white.png"?>">
									<span><?php esc_html_e( 'Donate', 'church_mgt' ); ?></span>
									</a>
								</li>
								<?php
							}
							?>
							<?php
							$page= 'payment';
							$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{
							?>
								<li class="has-submenu nav-item card-icon">
									<a href='#' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "payment") { echo "active"; } ?>">
										<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Payments.png"?>">
										<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Payments-white.png"?>">
										<span><?php esc_html_e( 'Payment', 'church_mgt' ); ?></span>
										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>
										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>
									</a>
									<ul class='submenu dropdown-menu'>
										<?php
										$page= 'payment';
										$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
										if($access)
										{
											?>
											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=transactionlist';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "payment") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Transaction', 'church_mgt' ); ?></span>
												</a>
											</li>
											<?php
										}
										?>
										<?php
										$page= 'payment';
										//var_dump($page);
										$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
										//var_dump($access);
										if($access)
										{
										?>
											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=incomelist';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "payment") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Income', 'church_mgt' ); ?></span>
												</a>
											</li>
											<?php
										}
										if($access)
										{
											if($obj_church->role != 'member')
											{
												?>
												<li class=''>
													<a href='<?php echo home_url().'?church-dashboard=user&page=payment&tab=expenselist';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "payment") { echo "active"; } ?>">
													<span><?php esc_html_e( 'Expense', 'church_mgt' ); ?></span>
													</a>
												</li>
												<?php
											}
										}
										?>
									</ul> 
									</li>
							<?php
							}
							$page= 'report';
							$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{ 
							?>
								<li class="has-submenu nav-item card-icon">
									<a href='#' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">
										<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Reports.png"?>">
										<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Reports-white.png"?>">
										<span><?php esc_html_e('Reports', 'church_mgt' ); ?></span>
										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>
										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>
									</a>
									<ul class='submenu dropdown-menu'>
										<?php
										$page= 'report';
										$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
										if($access)
										{
											?>
											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=report&tab=attendance_report&tab_1=activity_attendance';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Attendance Reports', 'church_mgt' ); ?></span>
												</a>
											</li>

											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=report&tab=payment_data';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Finance/Payment Reports', 'church_mgt' ); ?></span>
												</a>
											</li>

											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=report&tab=activity_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Activity Reports', 'church_mgt' ); ?></span>
												</a>
											</li>

											<li class=''>
												<a href='<?php echo home_url().'?church-dashboard=user&page=report&tab=export-report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">
												<span><?php esc_html_e( 'Download Reports', 'church_mgt' ); ?></span>
												</a>
											</li>
											<?php
										}
										?>
									</ul> 
								</li>
								<?php
							}		
							$page_1= 'message';
							$access_1=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_1);
							$page_2= 'notice';
							$access_2=MJ_cmgt_page_access_rolewise_accessright_dashboard($page_2);
							if($access_1 || $access_2){
							?>
							<li class="has-submenu nav-item card-icon">
								<a href='#' class="nav-link">
									<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Notificationa.png"?>">
									<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Notificationa-white.png"?>">
									 <span><?php esc_html_e( 'Notifications', 'church_mgt' ); ?></span>
									 <i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>
									 <i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>
								</a>
								<ul class='submenu dropdown-menu'>
									<?php
									$page= 'notice';
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									if($access)
									{
										?>
										<li class=''>
											<a href='<?php echo home_url().'?church-dashboard=user&page=notice';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "notice") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Notice', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
									<?php
									$page= 'message';
									//var_dump($page);
									$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
									//var_dump($access);
									if($access)
									{
									?>
										<li class=''>
											<a href='<?php echo home_url().'?church-dashboard=user&page=message';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "message") { echo "active"; } ?>">
											<span><?php esc_html_e( 'Messages', 'church_mgt' ); ?></span>
											</a>
										</li>
										<?php
									}
									?>
								</ul> 
							</li>
							<?php
							}
							$page= 'news_letter';
							$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{
							?>
								<li class="card-icon">
									<a href='<?php echo home_url().'?church-dashboard=user&page=news_letter';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "news_letter") { echo "active"; } ?>">
									<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/icon/newsletter.png"?>">
									<img class="icon newsletter_white_size" src="<?php echo CMS_PLUGIN_URL."/assets/images/icon/newsletter-white.png"?>">
									<span><?php esc_html_e( 'News Letter', 'church_mgt' ); ?></span>
									</a>
								</li>
							<?php
							}
							$page= 'account';
							$access=MJ_cmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{
								?>
								<li class="card-icon">
									<a href='<?php echo home_url().'?church-dashboard=user&page=account';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "account") { echo "active"; } ?>">
									<img class="icon img-top" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Reports.png"?>">
									<img class="icon " src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Reports-white.png"?>">
									<span><?php esc_html_e('Account', 'church_mgt' ); ?></span>
									</a>
								</li>
								<?php
							}
							?>
						</ul>
					</nav>	
				</div>
			</div>
			<!--side menubar end  -->
			<div class="col-sm-12 col-md-12 col-lg-10 col-xl-10 frontend_dashboard_margin padding_left_0">
				<!-- PAGE INNER DIV START-->
				<div class=" page-inner page_inner_1">
					<!-- MAIN WRAPPER DIV START-->  
					<div id="main-wrapper">
						<!-- Start Row2 -->
						<?php
						$user = wp_get_current_user ();
						$user_data =get_userdata( sanitize_text_field($user->ID));
					
						if (isset( $_REQUEST ['page'] )) 
						{
							$page_name = $_REQUEST ['page']; 
							
							require_once CMS_PLUGIN_DIR . '/template/'.$page_name.'.php';
						}
						?>
						<?php
						if(isset( $_REQUEST ['church-dashboard'] ) && $_REQUEST ['church-dashboard']  == 'user' && isset($_REQUEST ['page']) == '')
						{
						?>
						
						<div class="row" >
							<div class="col-lg-4 col-sm-12 col-md-12 col-xl-4 col-sm-4 line_chart_col line_chart_new">
								<div class="row m-0">
									<!-- Member card start -->
									<div class="col-lg-6 col-md-6 col-xl-6 col-sm-6 cmgt-card cmgt_card_1 no_1">
										<div class="cmgt-card-member-bg center" id="card-member-bg">
											<a href='<?php echo home_url().'?church-dashboard=user&page=member';?>'>
											<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Card/Member.png"?>">
											</a>
										</div>
										<div class="cmgt-card-number">
											<h3>
												<!-- <?php 
													echo count(get_users(array('role'=>'member')));
												?> -->

												<?php 
													//$result=get_option('cmgt_access_right_member');
													// var_dump($result);
													// die;
													$current_user_id=get_current_user_id();
													if($obj_church->role == 'member' || $obj_church->role == 'family_member')
													{
														echo esc_html_e('1','church_mgt');
													}
													// elseif($obj_church->role == 'family_member')
													// {
													// 	echo count(get_user_meta( $current_user_id, 'member_id', true )); 
													// }
													else
													{
														echo count(get_users(array('role'=>'member')));
													}
												?>
											</h3>
										</div>
										<div class="cmgt-card-title">
											<span><?php esc_html_e('Member','church_mgt');?></span>
										</div>
									</div>
									<!-- Member card end -->
									<!-- Accountant card start -->
									<div class="col-lg-6 col-md-6 col-xl-6 col-sm-6 cmgt-card cmgt_card_2 no_2">
										<div class="cmgt-card-member-bg center" id="card-accountant-bg">
											<a href='<?php echo home_url().'?church-dashboard=user&page=accountant';?>'>
												<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Card/Account.png"?>">
											</a>
										</div>
										<div class="cmgt-card-number">
											<h3><?php echo count(get_users(array('role'=>'accountant')));?></h3>
										</div>
										<div class="cmgt-card-title">
											<span><?php esc_html_e('Accountant','church_mgt');?></span>
										</div>
									</div>
									<!-- Accountant card end -->
									<!-- Notice card start -->
									<div class="col-lg-6 col-md-6 col-xl-6 col-sm-6 cmgt-card cmgt_card_1 no_3">
										<div class="cmgt-card-member-bg center" id="card-notice-bg">
											<a href='<?php echo home_url().'?church-dashboard=user&page=notice';?>'>
												<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Card/Notice.png"?>">
											</a>
										</div>
										<div class="cmgt-card-number">
											<h3><?php echo MJ_cmgt_count_notice();?></h3>
										</div>
										<div class="cmgt-card-title">
											<span><?php esc_html_e('Notice','church_mgt');?></span>
										</div>
									</div>
									<!-- Notice card end -->
									<!-- Message card start -->
									<div class="col-lg-6 col-md-6 col-xl-6 col-sm-6 cmgt-card cmgt_card_2 no_4">
										<div class="cmgt-card-member-bg center" id="card-message-bg">
											<a href='<?php echo home_url().'?church-dashboard=user&page=message';?>'>
												<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Card/Message.png"?>">
											</a>
										</div>
										<div class="cmgt-card-number">
											<h3><?php echo count(MJ_cmgt_count_inbox_item(get_current_user_id()));?></h3>
										</div>
										<div class="cmgt-card-title">
											<span><?php esc_html_e('Message','church_mgt');?></span>
										</div>
									</div>
									<!--Message card end -->
								</div>
							</div>
							<div class="col-lg-4 col-sm-6 col-md-6 col-xs-4 col-sm-4 line_chart_col">
								<div class="cmgt-line-chat">

									<?php 
									$members = count(mj_cmgt_get_all_members());
									$volunteer_member = count(mj_cmgt_get_all_volunteer_member());
									$family_member = count(mj_cmgt_get_all_family_member());
									$accountant = count(mj_cmgt_get_all_accountant());
									$management = count(mj_cmgt_get_all_management());
									?>
									<div class="row" id="cmgt-line-chat-p">
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<h3><?php esc_html_e('Users','church_mgt');?></h3>
										</div>
									</div>
									<script src="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.js"></script>
									<link rel="stylesheet" href="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.css">
									<div class="cmgt-member-chart">
										<div class="outer">
											<canvas id="chartJSContainer_users" width="300" height="250"></canvas>
											<p class="percent">
											 	<?php echo $members + $volunteer_member + $family_member + $accountant + $management;?> 
											</p>
											<p class="percent1">
											<?php _e('Users','church_mgt');?>
											</p>
										</div>
										<script>
												var options1 = {
													type: 'doughnut',
													data: {
														labels: ["<?php esc_html_e('Member', 'church_mgt');?>", "<?php esc_html_e('Volunteer Member', 'church_mgt');?>","<?php esc_html_e('Family Member', 'church_mgt');?>", "<?php esc_html_e('Accountant', 'church_mgt');?>", "<?php esc_html_e('Management', 'church_mgt');?>"],
														datasets: [
														{
																	label: '# of Votes',
																	data: [<?php echo $members;?>,<?php echo $volunteer_member;?>,<?php echo $family_member;?>,<?php echo $accountant;?>,<?php echo $management;?>],
																	backgroundColor: [
																		'#7DC5BF',
																		'#D1AFD8',
																		'#8A7B31',
																		'#E25C8A',
																		'#A6B3BA'
																	],
																	borderColor: [
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)'
																	],
																	borderWidth: 1,
																}
															]
														},
														
													options: {
																rotation: 1 * Math.PI,
																// circumference: 1 * Math.PI,
																legend: {
																	display: false
																},
																tooltip: {
																	enabled: false
																},
																cutoutPercentage: 85
													}
													}

													var ctx1 = document.getElementById('chartJSContainer_users').getContext('2d');
													new Chart(ctx1, options1);

													var options2 = {
													type: 'doughnut',
													data: {
													labels: ["", "Purple", ""],
																datasets: [
																{
																		data: [88.5, 1],
																		backgroundColor: [
																			"rgba(0,0,0,0)",
																			"rgba(255,255,255,1)",
																			"rgba(0,0,0,0)",
																		],
																		borderColor: [
																		'rgba(0, 0, 0 ,0)',
																		'rgba(46, 204, 113, 1)',
																		'rgba(0, 0, 0 ,0)'
																	],
																	borderWidth: 5
																	
																	}]
													},
													options: {
														cutoutPercentage: 95,
														rotation: 1 * Math.PI,
														circumference: 1 * Math.PI,
																legend: {
																	display: false
																},
																tooltips: {
																	enabled: false
																}
													}
													}

													var ctx2 = document.getElementById('secondContainer').getContext('2d');
													new Chart(ctx2, options2);
										</script>
									</div>
									
									<div class="row cmgt-line-chat-bottom margin_top_25px">
										<div class="col col-md-4 col-lg-4 col-xl-4" id="cmgt-line-chat-right-border">
											<p class="line-chart-checkcolor-Member center"></p>
											<p><?php esc_html_e('Members','church_mgt');?></p>
										</div>
										<div class="col col-md-4 col-lg-4 col-xl-4" id="cmgt-line-chat-right-border">
											<p class="line-chart-checkcolor-Accountant center"></p>
											<p><?php esc_html_e('Accountant','church_mgt');?></p>
										</div>
										<div class="col col-md-4 col-lg-4 col-xl-4 padding_left_2px">
											<p class="line-chart-checkcolor-Management center"></p>
											<p><?php esc_html_e('Management','church_mgt');?></p>
										</div>
										<div class="col col-md-6 col-lg-6 col-xl-6" id="cmgt-line-chat-right-border">
											<p class="line-chart-checkcolor-FamilyUser center"></p>
											<p><?php esc_html_e('Family Member','church_mgt');?></p>
										</div>
										<div class="col col-md-6 col-lg-6 col-xl-6">
											<p class="line-chart-checkcolor-FamilyMember center"></p>
											<p><?php esc_html_e('Volunteer Member','church_mgt');?></p>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="col-lg-4 col-sm-6 col-md-6 col-xs-4 col-sm-4 line_chart_col">
								<div class="cmgt-line-chat">

									<?php 
									$regular_member = count(mj_cmgt_get_all_regular_member());
									$volunteer_member = count(mj_cmgt_get_all_volunteer_member());
									$family_member = count(mj_cmgt_get_all_family_member());
									?>

									<div class="row" id="cmgt-line-chat-p">
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<h3><?php esc_html_e('Members','church_mgt');?></h3>
										</div>
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<a href="<?php echo admin_url().'admin.php?page=cmgt-member&tab=memberlist'; ?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
										</div>
									</div>
									<script src="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.js"></script>
									<link rel="stylesheet" href="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.css">
									<div class="cmgt-member-chart">
										<div class="outer">
											<canvas id="chartJSContainer" width="300" height="250"></canvas>
											<p class="percent">
											 	<?php echo $regular_member + $volunteer_member + $family_member;?> 
											</p>
											<p class="percent1">
											<?php _e('Member','church_mgt');?>
											</p>
										</div>
										<script>
												var options1 = {
													type: 'doughnut',
													data: {
														labels: ["<?php esc_html_e('Regular Member', 'church_mgt');?>", "<?php esc_html_e('Volunteer Member', 'church_mgt');?>", "<?php esc_html_e('Family Member', 'church_mgt');?>"],
														datasets: [
														{
																	label: '# of Votes',
																	data: [<?php echo $regular_member;?>,<?php echo $volunteer_member;?>,<?php echo $family_member;?>],
																	backgroundColor: [
																		'#FFB400',
																		'#44CB7F',
																		'#D1AFD8'
																	],
																	borderColor: [
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)'
																	],
																	borderWidth: 1,
																}
															]
														},
														
													options: {
																rotation: 1 * Math.PI,
																// circumference: 1 * Math.PI,
																legend: {
																	display: false
																},
																tooltip: {
																	enabled: false
																},
																cutoutPercentage: 85
													}
													}

													var ctx1 = document.getElementById('chartJSContainer').getContext('2d');
													new Chart(ctx1, options1);

													var options2 = {
													type: 'doughnut',
													data: {
													labels: ["", "Purple", ""],
																datasets: [
																{
																		data: [88.5, 1],
																		backgroundColor: [
																			"rgba(0,0,0,0)",
																			"rgba(255,255,255,1)",
																			"rgba(0,0,0,0)",
																		],
																		borderColor: [
																		'rgba(0, 0, 0 ,0)',
																		'rgba(46, 204, 113, 1)',
																		'rgba(0, 0, 0 ,0)'
																	],
																	borderWidth: 5
																	
																	}]
													},
													options: {
														cutoutPercentage: 95,
														rotation: 1 * Math.PI,
														circumference: 1 * Math.PI,
																legend: {
																	display: false
																},
																tooltips: {
																	enabled: false
																}
													}
													}

													var ctx2 = document.getElementById('secondContainer').getContext('2d');
													new Chart(ctx2, options2);
										</script>
									</div>
									<div class="row margin_top_25px">
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-RegularMember center"></p>
										</div>
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-VolunteerMember center"></p>
										</div>
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-FamilyMember center"></p>
										</div>
									</div>
									
									<div class="row cmgt-line-chat-bottom">
										<div class="col col-md-4 col-lg-4 col-xl-4" id="cmgt-line-chat-right-border">
											<span><?php echo $regular_member;?></span>
											<p><?php esc_html_e('Regular Member','church_mgt');?></p>
										</div>
										<div class="col col-md-4 col-lg-4 col-xl-4" id="cmgt-line-chat-right-border">
											<span><?php echo $volunteer_member;?></span>
											<p><?php esc_html_e('Volunteer Member','church_mgt');?></p>
										</div>
										<div class="col col-md-4 col-lg-4 col-xl-4">
											<span><?php echo $family_member;?></span>
											<p><?php esc_html_e('Family Member','church_mgt');?></p>
										</div>
									</div>
								</div>
							</div> -->
							<!-- <div class="col-lg-4 col-sm-6 col-md-6 col-xs-4 col-sm-4 line_chart_col">
								<div class="cmgt-line-chat">

									<?php 
									$regular_member = count(mj_cmgt_get_all_regular_member());
									$volunteer_member = count(mj_cmgt_get_all_volunteer_member());
									$family_member = count(mj_cmgt_get_all_family_member());
									?>

									<div class="row" id="cmgt-line-chat-p">
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<h3><?php esc_html_e('Transaction','church_mgt');?></h3>
										</div>
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<a href="<?php echo admin_url().'admin.php?page=cmgt-member&tab=memberlist'; ?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
										</div>
									</div>
									<script src="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.js"></script>
									<link rel="stylesheet" href="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.css">
									<div class="cmgt-member-chart">
										<div class="outer">
											<canvas id="chartJSContainer_transaction" width="300" height="250"></canvas>
											<p class="percent">
											 	<?php echo $regular_member + $volunteer_member + $family_member;?> 
											</p>
											<p class="percent1">
											<?php _e('Member','church_mgt');?>
											</p>
										</div>
										<script>
												var options1 = {
													type: 'doughnut',
													data: {
														labels: ["<?php esc_html_e('Regular Member', 'church_mgt');?>", "<?php esc_html_e('Volunteer Member', 'church_mgt');?>", "<?php esc_html_e('Family Member', 'church_mgt');?>"],
														datasets: [
														{
																	label: '# of Votes',
																	data: [<?php echo $regular_member;?>,<?php echo $volunteer_member;?>,<?php echo $family_member;?>],
																	backgroundColor: [
																		'#FFB400',
																		'#44CB7F',
																		'#D1AFD8'
																	],
																	borderColor: [
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)'
																	],
																	borderWidth: 1,
																}
															]
														},
														
													options: {
																rotation: 1 * Math.PI,
																// circumference: 1 * Math.PI,
																legend: {
																	display: false
																},
																tooltip: {
																	enabled: false
																},
																cutoutPercentage: 85
													}
													}

													var ctx1 = document.getElementById('chartJSContainer_transaction').getContext('2d');
													new Chart(ctx1, options1);

													var options2 = {
													type: 'doughnut',
													data: {
													labels: ["", "Purple", ""],
																datasets: [
																{
																		data: [88.5, 1],
																		backgroundColor: [
																			"rgba(0,0,0,0)",
																			"rgba(255,255,255,1)",
																			"rgba(0,0,0,0)",
																		],
																		borderColor: [
																		'rgba(0, 0, 0 ,0)',
																		'rgba(46, 204, 113, 1)',
																		'rgba(0, 0, 0 ,0)'
																	],
																	borderWidth: 5
																	
																	}]
													},
													options: {
														cutoutPercentage: 95,
														rotation: 1 * Math.PI,
														circumference: 1 * Math.PI,
																legend: {
																	display: false
																},
																tooltips: {
																	enabled: false
																}
													}
													}

													var ctx2 = document.getElementById('secondContainer').getContext('2d');
													new Chart(ctx2, options2);
										</script>
									</div>
									<div class="row margin_top_25px">
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-RegularMember center"></p>
										</div>
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-VolunteerMember center"></p>
										</div>
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-FamilyMember center"></p>
										</div>
									</div>
									
									<div class="row cmgt-line-chat-bottom">
										<div class="col col-md-4 col-lg-4 col-xl-4" id="cmgt-line-chat-right-border">
											<span><?php echo $regular_member;?></span>
											<p><?php esc_html_e('Regular Member','church_mgt');?></p>
										</div>
										<div class="col col-md-4 col-lg-4 col-xl-4" id="cmgt-line-chat-right-border">
											<span><?php echo $volunteer_member;?></span>
											<p><?php esc_html_e('Volunteer Member','church_mgt');?></p>
										</div>
										<div class="col col-md-4 col-lg-4 col-xl-4">
											<span><?php echo $family_member;?></span>
											<p><?php esc_html_e('Family Member','church_mgt');?></p>
										</div>
									</div>
								</div>
							</div> -->
							<div class="col-lg-4 col-sm-6 col-md-6 col-xs-4 col-sm-4 line_chart_col">
								<div class="cmgt-line-chat">

									<?php 
									$income = mj_cmgt_get_all_income();
									$expense = mj_cmgt_get_all_expense();
									$net_profit = mj_cmgt_get_netprofit();
									?>

									<div class="row" id="cmgt-line-chat-p">
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<h3><?php esc_html_e('Payment','church_mgt');?></h3>
										</div>
									</div>
									<script src="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.js"></script>
									<link rel="stylesheet" href="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.css">
									<div class="cmgt-member-chart">
										<div class="outer">
											<canvas id="chartJSContainer_incomeexpense" width="300" height="250"></canvas>
											<p class="percent">
											 	<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).' '.$net_profit;?> 
											</p>
											<p class="percent1">
											<?php _e('Payment','church_mgt');?>
											</p>
										</div>
										<script>
												var options1 = {
													type: 'doughnut',
													data: {
														labels: ["<?php esc_html_e('Regular Member', 'church_mgt');?>", "<?php esc_html_e('Volunteer Member', 'church_mgt');?>", "<?php esc_html_e('Family Member', 'church_mgt');?>"],
														datasets: [
														{
																	label: '# of Votes',
																	data: [<?php echo $income;?>,<?php echo $expense;?>,<?php echo $net_profit;?>],
																	backgroundColor: [
																		'#AED6F1',
																		'#F2B5AF',
																		'#90BD5D'
																	],
																	borderColor: [
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)'
																	],
																	borderWidth: 1,
																}
															]
														},
														
													options: {
																rotation: 1 * Math.PI,
																// circumference: 1 * Math.PI,
																legend: {
																	display: false
																},
																tooltip: {
																	enabled: false
																},
																cutoutPercentage: 85
													}
													}

													var ctx1 = document.getElementById('chartJSContainer_incomeexpense').getContext('2d');
													new Chart(ctx1, options1);

													var options2 = {
													type: 'doughnut',
													data: {
													labels: ["", "Purple", ""],
																datasets: [
																{
																		data: [88.5, 1],
																		backgroundColor: [
																			"rgba(0,0,0,0)",
																			"rgba(255,255,255,1)",
																			"rgba(0,0,0,0)",
																		],
																		borderColor: [
																		'rgba(0, 0, 0 ,0)',
																		'rgba(46, 204, 113, 1)',
																		'rgba(0, 0, 0 ,0)'
																	],
																	borderWidth: 5
																	
																	}]
													},
													options: {
														cutoutPercentage: 95,
														rotation: 1 * Math.PI,
														circumference: 1 * Math.PI,
																legend: {
																	display: false
																},
																tooltips: {
																	enabled: false
																}
													}
													}

													var ctx2 = document.getElementById('secondContainer').getContext('2d');
													new Chart(ctx2, options2);
										</script>
									</div>
									<div class="row margin_top_25px">
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-income center"></p>
										</div>
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-expense center"></p>
										</div>
										<div class="col line-chart-checkcolor-center">
											<p class="line-chart-checkcolor-NetProfit center"></p>
										</div>
									</div>
									
									<div class="row cmgt-line-chat-bottom payment_dashboard">
										<div class="col col-md-4 col-lg-4 col-xl-4" id="cmgt-line-chat-right-border">
											<span><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).' '.$income;?></span>
											<p><?php esc_html_e('Income','church_mgt');?></p>
										</div>
										<div class="col col-md-4 col-lg-4 col-xl-4" id="cmgt-line-chat-right-border">
											<span><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).' '.$expense;?></span>
											<p><?php esc_html_e('Expense','church_mgt');?></p>
										</div>
										<div class="col col-md-4 col-lg-4 col-xl-4">
											<span><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).' '.$net_profit;?></span>
											<p><?php esc_html_e('Net Profit','church_mgt');?></p>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="col-lg-4 col-sm-6 col-md-6 col-xs-4 col-sm-4 line_chart_col">
								<div class="cmgt-donation-table panel">
									<div class="panel-heading activities">
										<h3 class="panel-title"><?php esc_html_e('Donation List','church_mgt');?></h3>
										<ul class="nav navbar-right panel_toolbox">
											<?php
											$page_name = "donate";
											$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
											if($user_access['view'] == "1")
											{
												?>
												<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=donate&tab=transactionlist'; ?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
												</li> 
												<?php
											}
											?>                 
										</ul>
									</div>
									<?php 
										$page_name = "donate";
										$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
										$obj_payment=new Cmgttransaction;
										if($obj_church->role == 'member')
										{
											$paymentdata=$obj_payment->MJ_cmgt_get_my_donationlist_dashboard_by_role($user_data->ID);
										}
										else
										{
											$paymentdata=$obj_payment->MJ_cmgt_get_my_donationlist_dashboard();
										}
										
										if(!empty($paymentdata))
										{
											?>									
											<div class="cmgt-donation-list">
												<?php
												$i=0;
												foreach ($paymentdata as $retrieved_data)
												{
													if($i == 0)
													{
														$color_class='cmgt_donation_record_time_color0';
													}
													elseif($i == 1)
													{
														$color_class='cmgt_donation_record_time_color1';

													}
													elseif($i == 2)
													{
														$color_class='cmgt_donation_record_time_color2';

													}
													elseif($i == 3)
													{
														$color_class='cmgt_donation_record_time_color3';

													}
													elseif($i == 4)
													{
														$color_class='cmgt_donation_record_time_color4';

													}
														?>
													<div class="row cmgt-donation-record" id="<?php echo $retrieved_data->id;?>">
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4 col-xl-6 cmgt-donation-record-time <?php echo $color_class; ?>">
															<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo $retrieved_data->amount; ?>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-8 col-xl-6 cmgt_donation_name_date">
															<div class="cmgt-donation-record-donatname unit remainder_title_pr_cursor show_task_event" id="<?php echo $retrieved_data->id;?>" model="Donation Details">
																<?php $user=get_userdata($retrieved_data->member_id);
																echo $user->display_name;
																?>
															</div>
															<div class="cmgt-donation-record-donat-date">
																<?php
																$user=get_userdata($retrieved_data->member_id);
																 echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->transaction_date));?>
															</div>
														</div>
													</div>
													<?php
													$i++;
												}
												?>
											</div>
											<?php	
										} 
										else 
										{ 
											?>
											<div class="calendar-event-new1 no_data_donation no_data_img_center"> 
												<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
												<?php
												if($user_access['add'] == '1')
												{
													?>
													<div class="col-md-12 cmgt_dashboard_btn">
														<a href="<?php echo home_url().'?church-dashboard=user&page=donate&tab=addtransaction';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add donation','church_mgt');?></a>
													</div>
													<?php
												}	
													?>
											</div>			
											<?php
										} 
									?>
								</div>
							</div> -->
							<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
							<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 responsive_div_dasboard">
								<div class="panel panel-white cmgt-line-chatapp">
									<div class="panel-heading">
										<h3 class="panel-title float_left"><?php esc_html_e('Transaction/Donation','church_mgt');?></h3>
									</div>

									<?php
									$transaction_donation_array = mj_cmgt_get_transaction_donation_graphdata();	
										
									?>
									<script type="text/javascript">
										google.charts.load('current', {'packages':['bar']});
										google.charts.setOnLoadCallback(drawChart);

										function drawChart() {
											var data = google.visualization.arrayToDataTable(<?php echo $transaction_donation_array; ?>);

											var options = {
											
											width: 1040,
											height: 400,
											vAxis: {
												title: '<?php esc_html_e('No. of Amount','church_mgt');?>'
												},
											series: {
													0: {
														color: '#90BD5D'
													}
												}
											};

											var chart = new google.charts.Bar(document.getElementById('chart_div_transaction'));

											chart.draw(data, google.charts.Bar.convertOptions(options));
										}
									</script>
									<div id="chart_div_transaction" style="width:100%; padding:20px;"></div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 responsive_div_dasboard">
								<div class="panel panel-white cmgt-line-chatapp">
									<div class="panel-heading">
										<h3 class="panel-title float_left"><?php esc_html_e('Income, Expense & Net Profit','church_mgt');?></h3>
									</div>

									<?php
									$income_expence_array = mj_cmgt_get_income_netprofit_graphdata();				
									?>
									<script type="text/javascript">
										google.charts.load('current', {'packages':['bar']});
										google.charts.setOnLoadCallback(drawChart);

										function drawChart() {
											var data = google.visualization.arrayToDataTable(<?php echo $income_expence_array; ?>);

											var options = {
											
											width: 1040,
											height: 400,
											vAxis: {
												title: '<?php esc_html_e('No. of Amount','church_mgt');?>'
												},
											series: {
													0: {
														color: '#AED6F1'
													},
													1: {
														color: '#FFB74D'
													},
													2: {
														color: '#4DB6AC'
													}
												}
											};

											var chart = new google.charts.Bar(document.getElementById('chart_div_inc_expe_pro'));

											chart.draw(data, google.charts.Bar.convertOptions(options));
										}
									</script>
									<div id="chart_div_inc_expe_pro" style="width:100%; padding:20px;"></div>
								</div>
							</div>
							<?php
							$page_name = "group";
							$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
							?>
							<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
								<div class="cmgt-group-ministry">
									<div class="cmgt-group-list panel">
										<div class="panel-heading activities">
											<h3 class="panel-title"><?php esc_html_e('Group List','church_mgt');?></h3>
											
											<ul class="nav navbar-right panel_toolbox">
												<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=group&tab=grouplist'; ?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
												</li>                  
											</ul>
											
										</div>
										<?php
										// $obj_group=new Cmgtdashboard;
										// $groupdata=$obj_group->MJ_cmgt_get_grouplist();
										$obj_group=new Cmgtgroup;
										$groupdata=$obj_group->MJ_cmgt_get_all_groups_dashboard();
										if(!empty($groupdata))
										{
											foreach ($groupdata as $retrieved_data)
                                            {
                                                $group_count=$obj_group->MJ_cmgt_count_group_members($retrieved_data->id);
                                            ?>
												<div class="row cmgt-group-list-record">
													<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 cmgt-group-list-record-col-img">
														<?php 
														if($retrieved_data->cmgt_groupimage == '')
														{ 
															echo '<img src='.get_option( 'cmgt_group_logo' ).' height="52px" width="52px" class="cmgt-grouplist-img"/>';	
														}
														else
														{
															echo '<img src='.$retrieved_data->cmgt_groupimage.' height="52px" width="52px" class="cmgt-grouplist-img"/>';
														}
														?>
														<div class="cmgt-group-list-group-name remainder_title_pr Bold viewdetail show_task_event padding_right_20" id="<?php echo $retrieved_data->id;?>" model="Group Details">
															<span><?php echo $retrieved_data->group_name;?></span>
														</div>
													</div>
													<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 mt-2 cmgt-group-list-record-col-count">
														<div class="cmgt-group-list-total-group">
															<?php if(!empty($group_count) ) 
																{ ?>
																	<span class=""><?php echo $group_count;?></span>
																<?php 
																}
																else
																{
																?>  
																	<span class=""><?php echo "0";?></span>
																<?php
																}
																?>
														</div>
													</div>
												</div>
												<?php
											}
											
										} 
										else 
										{
											?>
											<div class="calendar-event-new1 no_data_img_center"> 
												<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
												<?php
												if($user_access['add'] == '1')
												{
													?>
													<div class="col-md-12 cmgt_dashboard_btn">
														<a href="<?php echo home_url().'?church-dashboard=user&page=group&tab=addgroup';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Group','church_mgt');?></a>
													</div>	
													<?php
												}
												?>
											</div>			
											<?php
										 } ?>
									</div>
									<?php
									$page_name = "ministry";
									$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
									?>
									<div class="cmgt-ministry-list panel">
										<div class="panel-heading activities">
											<h3 class="panel-title"><?php esc_html_e('Ministry List','church_mgt');?></h3>
											<ul class="nav navbar-right panel_toolbox">
												<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=ministry&tab=ministrylist'; ?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
												</li>                  
											</ul>
										</div>
										<?php 
										
										$obj_ministry=new Cmgtdashboard;
										$ministrydata=$obj_ministry->MJ_cmgt_get_all_ministry_dashboard();
										if(!empty($ministrydata))
										{
											foreach ($ministrydata as $retrieved_data)
											{
												$ministry_count=$obj_ministry->MJ_cmgt_count_ministry_members($retrieved_data->id);
											?>
												<div class="row cmgt-group-list-record">
													<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10  cmgt-group-list-record-col-img">
														<?php 
														
														if($retrieved_data->ministry_image == '')
														{ 
															echo '<img src='.get_option( 'cmgt_ministry_logo' ).' height="52px" width="52px" class="cmgt-grouplist-img"/>';
														}
														else
															echo '<img src='.$retrieved_data->ministry_image.' height="52px" width="52px" class="cmgt-grouplist-img"/>';
															?>
															
															<div class="cmgt-group-list-group-name remainder_title_pr Bold viewdetail show_task_event padding_right_20" id="<?php echo $retrieved_data->id;?>" model="ministry Details">
																<span><?php echo $retrieved_data->ministry_name;?></span>
															</div>
													</div>
													<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 mt-2 cmgt-group-list-record-col-count">
														<div class="cmgt-group-list-total-group">
															<?php if(!empty($ministry_count) ) 
																{ ?>
																	<span class=""><?php echo $ministry_count;?></span>
																<?php 
																}
																else
																{
																?>  
																	<span class=""><?php echo "0";?></span>
																<?php
																}
																?>
														</div>
													</div>
												</div>
												<?php
											}
										} 
										else 
										{
											?>
											<div class="calendar-event-new1 no_data_img_center"> 
												<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
												<?php
												if($user_access['add'] == '1')
												{
													?>
													<div class="col-md-12 cmgt_dashboard_btn">
														<a href="<?php echo home_url().'?church-dashboard=user&page=ministry&tab=addministry';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Ministry','church_mgt');?></a>
													</div>	
													<?php
												}
												?>
											</div>			
											<?php										
										} ?>
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
								<div class="cmgt-calendar panel">
									<div class="panel-heading activities">
										<h3 class="panel-title"><?php esc_html_e('Calendar','church_mgt');?></h3>
									</div>
									<div class="cmgt-cal-py">
										<!--set caldender-header event-List Start -->
										<div class="cmgt-card-head frontend">
											<ul class="cmgt-cards-indicators cmgt-right">
												<li><span class="cmgt-indic cmgt-blue-indic"></span> <?php esc_html_e( 'Activity', 'church_mgt' ); ?></li>
												<li><span class="cmgt-indic cmgt-red-indic"></span> <?php esc_html_e( 'Service', 'church_mgt' ); ?></li>
												<li><span class="cmgt-indic cmgt-green-indic"></span> <?php esc_html_e( 'Birth Date', 'church_mgt' );?></li>
												<li><span class="cmgt-indic cmgt-pink-indic"></span> <?php esc_html_e( 'Reservation', 'church_mgt' ); ?></li>
											</ul>
										</div>
										<!--set caldender-header event-List End -->
										<div id="calendar"></div>
									</div>
								</div>
							</div>
							<?php
							$page_name = "activity";
							$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
							if($user_access['view'] == "1")
							{
								?>
								<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
									<!-- Activity List Start -->
									<div class="panel dashboard_height panel-white cmgt-activity-table active">
										<div class="panel-heading activities">
											<h3 class="panel-title"><?php esc_html_e('Activities','church_mgt');?></h3>
											<ul class="nav navbar-right panel_toolbox">
												<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=activity';?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
												</li>                  
											</ul>
										</div>
										<?php 
											$page_name = "activity";
											$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
											$obj_activity=new Cmgtdashboard;
											$i=0;
											$activitydata=$obj_activity->MJ_cmgt_get_activity();
											if(!empty($activitydata))
											{ 
												
												foreach ($activitydata as $retrieved_data)
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
													?>
													<div class="calendar_event_p calendar-event view-complaint"> 
														<p class="cmgt_activity_list_img <?php echo $color_class;?>">
															<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Activity-white.png"?>">
														</p>
														
														<p class="cmgt_pastoral_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event margin_right_50"  id="<?php echo $retrieved_data->activity_id;?>" model="activity Details"><?php echo esc_attr(ucfirst(get_the_title($retrieved_data->activity_cat_id)));?>&nbsp;&nbsp;<span class="cmgt-notice-list-start_date"><?php echo _e($retrieved_data->activity_start_time,'church_mgt');?>&nbsp;|&nbsp;<?php echo _e($retrieved_data->activity_end_time,'church_mgt');?></span>	
														</p>
														<p class="cmgt_pastoral_remainder_title_pr cmgt_description_line"><span class="cmgt_activity_date" id="cmgt_start_date_end_date"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->activity_date)));?>&nbsp;|&nbsp;<?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->activity_end_date)));?></span></p>
													</div>
													<?php
												$i++;
												}  
											} 
											else
											{
												?>
												<div class="calendar-event-new1 no_data_img_center"> 
													<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
													<?php
													if($user_access['add'] == '1')
													{
														?>
														<div class="col-md-12 cmgt_dashboard_btn">
															<a href="<?php echo home_url().'?church-dashboard=user&page=activity&tab=addactivity';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('add activitie','church_mgt');?></a>
														</div>	
														<?php
													}
													?>
												</div>			
												<?php
											}
										?>
									</div><!-- Activity List End -->
								</div>
								<?php
							}
							$page_name = "services";
							$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
							if($user_access['view'] == "1")
							{
								?>
								<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
									<!-- Services List start -->
									<div class="panel panel-white dashboard_height services_btn cmgt-services-table">
										<div class="panel-heading service">
											<h3 class="panel-title"><?php esc_html_e('Services','church_mgt');?></h3>
											<ul class="nav navbar-right panel_toolbox">
												<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=services'; ?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
												</li>
											</ul>
										</div>
										<?php 
											$page_name = "services";
											$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
											
											$obj_Service=new Cmgtservice;
											$i=0;
											$Service_data=$obj_Service->MJ_cmgt_get_all_services_dashboard();
											if(!empty($Service_data))
											{ 
												foreach ($Service_data as $retrieved_data)
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
													?>
													<div class="calendar_event_p calendar-event view-complaint"> 
														<p class="cmgt_activity_list_img <?php echo $color_class;?>">
															<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/services-white.png"?>">
														</p>
														<p class="cmgt_pastoral_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event "  id="<?php echo $retrieved_data->id;?>" model="service Details"><?php echo $retrieved_data->service_title;?>&nbsp;&nbsp;<span class="cmgt-notice-list-start_date"><?php echo esc_attr($retrieved_data->start_time);?>&nbsp;|&nbsp;<?php echo esc_attr($retrieved_data->end_time);?></span>	
														</p>
														<p class="cmgt_pastoral_remainder_title_pr cmgt_description_line"><span class="cmgt_activity_date" id="cmgt_start_date_end_date"><?php $date= str_replace('00:00:00',"",$retrieved_data->start_date);echo date(MJ_cmgt_date_formate(),strtotime($date));?>&nbsp;|&nbsp;<?php $date1= str_replace('00:00:00',"",$retrieved_data->end_date);echo date(MJ_cmgt_date_formate(),strtotime($date1));?></span></p>
													</div>
													<?php
													$i++;
												}  
											} 
											else 
											{
												?>
												<div class="calendar-event-new1 no_data_img_center"> 
													<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
													<?php
													if($user_access['add'] == '1')
													{
														?>
														<div class="col-md-12 cmgt_dashboard_btn">
															<a href="<?php echo home_url().'?church-dashboard=user&page=services&tab=addservice';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Service','church_mgt');?></a>
														</div>	
														<?php
													}
													?>
												</div>			
												<?php
											} 
										?>

									</div><!-- Services List End -->
								</div>
								<?php 
							}
							if($obj_church->role != 'family_member')
							{
								$page_name = "reservation";
								$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
								if($user_access['view'] == "1")
								{

									?>	
									<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
										<!-- Reservation List start -->
										<div class="panel dashboard_height panel-white cmgt-reservation-table">
											<div class="panel-heading res_list">
												<h3 class="panel-title"><?php esc_html_e('Reservation List','church_mgt');?></h3>
												<?php
												if($user_access['add'] == "1")
												{ ?>
												<ul class="nav navbar-right panel_toolbox">
													<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=reservation&tab=reservation_list';?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
													</li>                  
												</ul>
												<?php } ?>
											</div>
											<div class="events">
												<?php 
												$page_name = "reservation";
												$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
												$obj_reservation=new Cmgtreservation;
												$i=0;
												if($obj_church->role == 'member')
												{
													$reservationdata=$obj_reservation->MJ_cmgt_get_member_reservation_dashboard($user_data->ID);
												}
												else
												{
													$reservationdata=$obj_reservation->MJ_cmgt_get_all_reservation_dashboard();
												}
												if(!empty($reservationdata))
												{
													foreach ($reservationdata as $retrieved_data)
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
														if(!empty ($retrieved_data->description))
														{
															?>
															<div class="calendar-event view-complaint"> 
																<p class="cmgt_activity_list_img <?php echo $color_class;?>">
																	<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Reservation-white.png"?>">
																</p>
																<p class="cmgt_reservation_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event mt-2 reservation padding_right_50" id="<?php echo $retrieved_data->id;?>" model="Reservation Details">
																<?php echo $retrieved_data->usage_title;?>							
																</p><p class="remainder_date_pr date_background"><span><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->reserve_date));?></span></p>
																<?php
																	$description = strlen($retrieved_data->description) > 40 ? 
																	substr($retrieved_data->description,0,40)."..." : $retrieved_data->description;
																?>
																<p class="cmgt_reservation_remainder_title_pr cmgt_reservation_description cmgt_description padding_right_50">
																<?php echo $description ?></p>

															</div>
															<?php
														}else
														{
															?>
															<div class="calendar-event view-complaint"> 
																<p class="cmgt_activity_list_img <?php echo $color_class;?>">
																	<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Reservation-white.png"?>">
																</p>
																<p class="cmgt_reservation_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event mt-2 reservation padding_right_50" id="<?php echo $retrieved_data->id;?>" model="Reservation Details">
																<?php echo $retrieved_data->usage_title;?>							
																</p><p class="remainder_date_pr date_background"><span><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->reserve_date));?></span></p>
															</div>
															<?php
														}	
														$i++;
													}	
												} 
												else 
												{ 
													?>
													<div class="calendar-event-new1 no_data_img_center"> 
														<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
														<?php
														if($user_access['add'] == '1')
														{
															?>
															<div class="col-md-12 cmgt_dashboard_btn">
																<a href="<?php echo home_url().'?church-dashboard=user&page=reservation&tab=add_reservation';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Reservation','church_mgt');?></a>
															</div>	
															<?php
														}
														?>
													</div>			
													<?php
												} ?>
											</div>
										</div><!-- Reservation List End -->
									</div>
									<?php 
								} 
								$page_name = "pledges";
								$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
								if($user_access['view'] == "1")
								{
									?>
									<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
										<!-- Peldges List Start -->
										<div class="cmgt-peldges-table panel cmgt_peldges_list">
											<div class="panel-heading activities">
												<h3 class="panel-title"><?php esc_html_e('Pledges List','church_mgt');?></h3>
												<?php
												if($user_access['add'] == "1")
												{ ?>
												<ul class="nav navbar-right panel_toolbox">
													<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=pledges&tab=pledgeslist';?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
													</li>                  
												</ul>
												<?php } ?>
											</div>
												<!-- <div class="panel-body"> -->
											<div class="events">
												<?php 
												$page_name = "pledges";
												$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);

												$obj_pledes=new Cmgtpledes;
												$i=0;
												if($obj_church->role == 'member')
												{
													$pledesdata=$obj_pledes->MJ_cmgt_get_All_member_pledges($user_data->ID);
												}
												else
												{
													$pledesdata=$obj_pledes->MJ_cmgt_get_my_pledgeslist_dashboard();
												}
												if(!empty($pledesdata))
												{
													foreach ($pledesdata as $retrieved_data)
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
														?>
														<div class="calendar-event view-complaint"> 
															<p class="cmgt_activity_list_img <?php echo $color_class;?>">
																<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pledges-white.png"?>">
															</p>
															<p class="cmgt_reservation_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event mt-2 reservation padding_right_50" id="<?php echo $retrieved_data->id;?>" model="Pledges Details">
																<?php $user=get_userdata($retrieved_data->member_id);echo $user->display_name; ?>
															</p>
															<p class="remainder_date_pr date_background"><span><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo $retrieved_data->amount; ?></span></p>
															<p class="cmgt_peldges_date padding_right_50">
																<?php $date= str_replace('00:00:00',"",$retrieved_data->start_date);echo date(MJ_cmgt_date_formate(),strtotime($date));?>&nbsp;|&nbsp;<?php $date= str_replace('00:00:00',"",$retrieved_data->end_date);echo date(MJ_cmgt_date_formate(),strtotime($date));?>
															</p>
														</div>
														<?php	
														$i++;
													}	
												} 
												else 
												{ 
													?>
													<div class="calendar-event-new1 no_data_img_center"> 
														<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
														<?php
														if($user_access['add'] == '1')
														{
															?>
															<div class="col-md-12 cmgt_dashboard_btn">
																<a href="<?php echo home_url().'?church-dashboard=user&page=pledges&tab=addpledges';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Pledges','church_mgt');?></a>
															</div>	
															<?php
														}
														?>
													</div>			
													<?php
												} ?>
											</div>
												<!-- </div> -->
										</div>
										<!-- Peldges List End -->
									</div>
									<?php 
								} 
								$page_name = "pastoral";
								$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
								if($user_access['view'] == "1")
								{
									?>
									<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
										<!-- Pastoral List Start -->
										<div class="panel panel-white dashboard_height" id="cmgt-Pastoral-table-height">
											<div class="panel-heading pastoral_list">
												<h3 class="panel-title"><?php esc_html_e('Pastoral List','church_mgt');?></h3>
												<ul class="nav navbar-right panel_toolbox">
													<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=pastoral&tab=pastoral_list';?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
													</li>
												</ul>
											</div>
											<div class="events">
												<?php 
												$page_name = "pastoral";
												$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);

												$obj_pastoral=new Cmgtpastoral;
												$i=0;
												if($obj_church->role == 'member')
												{
													$pastoraldata=$obj_pastoral->MJ_cmgt_get_pastoral_member_dashboard($user_data->ID);
												}
												else
												{
													$pastoraldata=$obj_pastoral->MJ_cmgt_get_all_pastoral_dashboard();
												}
												if(!empty($pastoraldata))
												{
													foreach ($pastoraldata as $retrieved_data)
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
														if(!empty($retrieved_data->description))
														{
															?>
															<div class="calendar_event_p calendar-event view-complaint"> 
																<p class="cmgt_activity_list_img <?php echo $color_class;?>">
																	<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pastoral-white.png"?>">
																</p>
																<p class="cmgt_pastoral_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event padding_right_50" id="<?php echo $retrieved_data->id;?>" model="Pastoral Details"><?php echo $retrieved_data->pastoral_title;?>&nbsp;<span class="cmgt-notice-list-start_date"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->pastoral_date));?>&nbsp;|&nbsp;<?php echo esc_attr($retrieved_data->pastoral_time);?></span>	
																</p>
																<?php
																		
																$description = strlen($retrieved_data->description) > 50 ? 
																substr($retrieved_data->description,0,50)."..." : $retrieved_data->description;
																?>
																<p class="cmgt_pastoral_remainder_title_pr cmgt_description_line cmgt_description"><?php echo $description;?></p>
															</div>
															<?php
														}else
														{
															?>
															<div class="calendar_event_p calendar-event view-complaint"> 
																<p class="cmgt_activity_list_img <?php echo $color_class;?>">
																	<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pastoral-white.png"?>">
																</p>
																<p class="remainder_title_pr Bold viewpriscription show_task_event mt-2" id="<?php echo $retrieved_data->id;?>" model="Pastoral Details"><?php echo $retrieved_data->pastoral_title;?>&nbsp;
																	<span class="cmgt-notice-list-start_date"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->pastoral_date));?>&nbsp;|&nbsp;<?php echo esc_attr($retrieved_data->pastoral_time);?></span>	
																</p>
															</div>
															<?php
														}
														$i++;
													}	
												} 
												else 
												{ 
													?>
													<div class="calendar-event-new1 no_data_img_center"> 
														<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
														<?php
														if($user_access['add'] == '1')
														{
															?>
															<div class="col-md-12 cmgt_dashboard_btn">
																<a href="<?php echo home_url().'?church-dashboard=user&page=pastoral&tab=add_pastoral';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Pastoral','church_mgt');?></a>
															</div>	
															<?php
														}
															?>
													</div>			
													<?php
												} ?>
											</div>
										</div><!-- Pastoral List End -->
									</div>
									<?php 
								} 
								$page_name = "spiritual-gift";
								$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
								if($user_access['view'] == "1")
								{
									?>
									
									<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
										<!-- Sell Gift List Start -->
										<div class="cmgt-SellGift-table panel cmgt_Sell_Gift_list">
											<div class="panel-heading activities">
												<h3 class="panel-title"><?php esc_html_e('Sell Gift List','church_mgt');?></h3>
												<ul class="nav navbar-right panel_toolbox">
													<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=spiritual-gift&tab=sellgiftlist';?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
													</li>                  
												</ul>
											</div>
											<div class="events">
												<?php 
												$page_name = "spiritual-gift";
												$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);

												$obj_sellgift=new Cmgtgift;
												$i=0;
												if($obj_church->role == 'member')
												{
													$sellgiftdata=$obj_sellgift->MJ_cmgt_get_member_sell_gifts_dashboard($user_data->ID);
												}
												else
												{
													$sellgiftdata=$obj_sellgift->MJ_cmgt_get_all_sell_gifts_dashboard();
												}
												if(!empty($sellgiftdata))
												{
													foreach ($sellgiftdata as $retrieved_data)
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
														?>
														<div class="calendar-event view-complaint"> 
															<p class="cmgt_activity_list_img <?php echo $color_class;?>">
																<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Sell-Gift-white.png"?>">
															</p>
															<p class="cmgt_reservation_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event mt-2 reservation padding_right_50" id="<?php echo $retrieved_data->id;?>" model="Sell Gift Details"><?php echo MJ_cmgt_church_get_display_name(esc_attr($retrieved_data->member_id));?>
															</p>
															<p class="remainder_date_pr">
																<span><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->sell_date));?></span>
															</p>
															<p class="cmgt_reservation_remainder_title_pr cmgt_Sell_Gift_date cmgt_description padding_right_50">
																<?php echo MJ_cmgt_church_get_gift_name(esc_attr($retrieved_data->gift_id));?>
															</p>
														</div>
														<?php
														$i++;
													}		
												} 
												else 
												{ 
													?>
													<div class="calendar-event-new1 no_data_img_center"> 
														<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
														<?php
														if($user_access['add'] == '1')
														{
															?>
															<div class="col-md-12 cmgt_dashboard_btn">
																<a href="<?php echo home_url().'?church-dashboard=user&page=spiritual-gift&tab=sellgift';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Sell Gift','church_mgt');?></a>
															</div>	
															<?php
														}
														?>
													</div>			
													<?php
												} ?>
											</div>
										</div>
										<!-- Sell Gift List End -->                                
									</div>
									<?php 
								} ?>
								<?php 
							} 
							
							if($obj_church->role != 'family_member')
							{
								$page_name = "notice";
								$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
								if($user_access['view'] == "1")
								{
								   ?>	
								   				
									<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
										<!-- Notice List start -->
										<div class="panel panel-white dashboard_height services_btn" id="cmgt-message-list">
											<div class="panel-heading service">
												<h3 class="panel-title"><?php esc_html_e('Notice','church_mgt');?></h3>
												<ul class="nav navbar-right panel_toolbox">
													<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&page=notice&tab=noticelist'; ?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
													</li>
												</ul>
											</div>
											<div class="cmgt-notice-list service_list">
												<?php 
												$page_name = "notice";
												$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);

												$obj_notice=new Cmgtnotice;
												$i= 0;
												$notice_data=$obj_notice->MJ_cmgt_get_all_notice_dashboard();
												if(!empty($notice_data))
												{
													foreach($notice_data as $retrieved_data)
													{
														if($i == 0)
														{
															$color_class1='cmgt_notice_color0';
														}
														elseif($i == 1)
														{
															$color_class1='cmgt_notice_color1';
							
														}
														elseif($i == 2)
														{
															$color_class1='cmgt_notice_color2';
							
														}
														elseif($i == 3)
														{
															$color_class1='cmgt_notice_color3';
							
														}
												
															if(!empty($retrieved_data->notice_content))
															{
																?>			
																<div class="row <?php echo $color_class1 ?>" id="<?php echo $retrieved_data->id;?>">
																	<div class="col col-sm-12 col-md-12 col-lg-12 col-xl-12 cmgt-notice-list-checkbox-list">
																		<div class="cmgt_text_overflow">
																			<!-- <?php
																				$notice_title = strlen($retrieved_data->notice_title) > 12 ? 
																				substr($retrieved_data->notice_title,0,12)."..." : $retrieved_data->notice_title;
																			?> -->
																			<span class="remainder_title_pr_cursor show_task_event cmgt-notice-list-title" id="<?php echo $retrieved_data->id;?>" model="Notice Details"><?php echo $retrieved_data->notice_title;?></span>
																			<span class="cmgt-notice-list-start_date"><?php $date= str_replace('00:00:00',"",$retrieved_data->start_date);echo date(MJ_cmgt_date_formate(),strtotime($date));?>&nbsp;|&nbsp;<?php $date1= str_replace('00:00:00',"",$retrieved_data->end_date);echo date(MJ_cmgt_date_formate(),strtotime($date1));?></span>
																		</div>
																		<div class="cmgt_text_overflow">
																			<!-- <?php
																				$notice_content = strlen($retrieved_data->notice_content) > 50 ? 
																				substr($retrieved_data->notice_content,0,50)."..." : $retrieved_data->notice_content;
																			?> -->
																			<span class="cmgt_notice_description"><?php echo $retrieved_data->notice_content; ?></span>
																		</div>
																	</div>
																</div> 
															<?php
															}else 
															{
																?>
																<div class="row <?php echo $color_class1 ?> " id="<?php echo $retrieved_data->id;?>">
																	<div class="col col-sm-12 col-md-12 col-lg-12 col-xl-12 cmgt-notice-list-checkbox-list">
																		<div class="cmgt_text_overflow">
																			<span class="remainder_title_pr_cursor show_task_event cmgt-notice-list-title" id="<?php echo $retrieved_data->id;?>" model="Notice Details"><?php echo $retrieved_data->notice_title;?></span>
																			<span class="cmgt-notice-list-start_date"><?php $date= str_replace('00:00:00',"",$retrieved_data->start_date);echo date(MJ_cmgt_date_formate(),strtotime($date));?>&nbsp;|&nbsp;<?php $date1= str_replace('00:00:00',"",$retrieved_data->end_date);echo date(MJ_cmgt_date_formate(),strtotime($date1));?></span>
																		</div>
																	</div>
																</div> 
																<?php 
															}
														$i++;
													}
												}
												else 
												{ 
													?>
													<div class="calendar-event-new1 no_data_img_center"> 
														<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
														<?php
														if($user_access['add'] == '1')
														{
															?>
															<div class="col-md-12 cmgt_dashboard_btn">
																<a href="<?php echo home_url().'?church-dashboard=user&page=notice&tab=addnotice';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Notice','church_mgt');?></a>
															</div>	
															<?php
														}
														?>
													</div>			
													<?php
												}
												?>
											</div>
										</div>
										<!-- Notice List End -->
									</div>
									<?php 
								} 
								$page_name = "message";
								$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);
								if($user_access['view'] == "1")
								{
									?>
									<div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
										<!-- Message List Start -->
										<div class="panel dashboard_height panel-white cmgt_message_list active" id="cmgt-message-list">
											<div class="panel-heading activities">
												<h3 class="panel-title"><?php esc_html_e('Message','church_mgt');?></h3>
												<ul class="nav navbar-right panel_toolbox">
													<li class="margin_dasboard"><a href="<?php echo home_url().'?church-dashboard=user&&page=message';?>"><img class="" src="<?php echo CMS_PLUGIN_URL."/assets/images/update-dashboard-icon/dots.png"?>"></a>
													</li>                  
												</ul>
											</div>
											<!-- <div class="panel-body"> -->
												<div class="events">
												<?php 
													$page_name = "message";
													$user_access=MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page_name);

													$obj_message=new Cmgt_message;
													$i=0;
													$messagedata=$obj_message->MJ_cmgt_get_my_messagelist_dashboard();
													if(!empty($messagedata))
													{

														foreach ($messagedata as $retrieved_data)
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
															?>
														<div class="calendar_event_p calendar-event view-complaint"> 
															<p class="cmgt_activity_list_img <?php echo $color_class;?>">
																<img class="center" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Message-white.png"?>">
															</p>
															<p class="cmgt_reservation_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event mt-2 reservation padding_right_50" id="<?php echo $retrieved_data->message_id;?>" model="Message Details">
															<?php echo MJ_cmgt_church_get_display_name($retrieved_data->sender);?>		
															</p>
															<p class="remainder_date_pr date_background"><span><?php echo date(MJ_cmgt_date_formate(),strtotime($retrieved_data->msg_date));?></span></p>
															<p class="cmgt_reservation_remainder_title_pr cmgt_msg_subject_remainder_title_pr cmgt_description padding_right_50"><?php echo $retrieved_data->msg_subject;?></p>
														</div>	
															<?php
													$i++;
													}	
												} 
												else 
												{ 
													?>
													<div class="calendar-event-new1 no_data_img_center"> 
														<img class="no_data_img1" src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/No-Data.png"?>" >
														<?php
														
														if($user_access['add'] == '1')
														{
															?>
															<div class="col-md-12 cmgt_dashboard_btn">
																<a href="<?php echo home_url().'?church-dashboard=user&page=message&tab=compose';?>" class="btn save_btn cmgt_no_data_btn_color line_height_31px"><?php esc_html_e('Add Message','church_mgt');?></a>
															</div>	
															<?php
														}
														?>
													</div>			
													<?php
												} ?>
												</div>
											<!-- </div> -->
										</div><!-- Message List End -->
									</div>
									<?php
								}
							}
							?>
						</div><!-- END Row2 -->
						<?php } ?>
					</div>
					<!-- MAIN WRAPPER DIV END-->
				</div>
				<!-- PAGE INNER DIV END-->
			</div>
			
		</div>
		<!-- FOOTER START-->
		<footer class='cmgt-footer'>
			<p>
				<?php echo get_option( 'cmgt_footer_description' ); ?>
			</p>
		</footer>
		<!-- FOOTER END-->

		</body>
	<!--BODY END-->
</html><!--HTML END-->

 