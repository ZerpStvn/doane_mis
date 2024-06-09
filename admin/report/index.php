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
<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('report');
	if($user_access == 'administrator')
	{
		$user_access_add=1;
		$user_access_edit=1;
		$user_access_delete=1;
		$user_access_view=1;
	}
	else
	{
		$user_access_view = $user_access['view'];

		if (isset($_REQUEST['page'])) 
		{
			if ($user_access_view == '0') 
			{
				mj_cmgt_access_right_page_not_access_message_admin_side();
				die;
			}
		}
	}
?>
<!-- user redirect url enter code end -->
<div class="page-inner"><!-- PAGE INNER DIV START-->
	<div id=""><!-- MAIN WRAPPER DIV START-->  
		<div class="row"><!-- ROW DIV START--> 
			<div class="col-md-12"><!-- COL 12 DIV START-->  
				<div class="panel panel-white main_home_page_div chart_reports"><!-- PANEL WHITE DIV START-->  
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<!-- NAV TAB WRAPPER MENU START-->  
						<!-- <h2 class="nav-tab-wrapper">
							<a href="?page=cmgt-report&tab=attendance_report" class="nav-tab <?php echo $active_tab == 'attendance_report' ? 'nav-tab-active' : ''?>">
							<?php echo esc_html__('Attendance Report', 'church_mgt'); ?></a>
							
							<a href="?page=cmgt-report&tab=payment_report" class="nav-tab <?php echo $active_tab == 'payment_report' ? 'nav-tab-active' : ''?>">
							<?php echo esc_html__('Payment Report', 'church_mgt'); ?></a>
							
							<a href="?page=cmgt-report&tab=payment_data" class="nav-tab <?php echo $active_tab == 'payment_data' ? 'nav-tab-active' : ''?>">
							<?php echo esc_html__('Payment Data', 'church_mgt'); ?></a>

							<a href="?page=cmgt-report&tab=income_report&tab_1=datatable" class="nav-tab <?php echo $active_tab == 'income_report' ? 'nav-tab-active' : ''?>">
							<?php echo esc_html__('Income Report', 'church_mgt'); ?></a>

							<a href="?page=cmgt-report&tab=expense_report&tab_1=datatable" class="nav-tab <?php echo $active_tab == 'expense_report' ? 'nav-tab-active' : ''?>">
							<?php echo esc_html__('Expense Report', 'church_mgt'); ?></a>
							
							<a href="?page=cmgt-report&tab=activity_report" class="nav-tab <?php echo $active_tab == 'activity_report' ? 'nav-tab-active' : ''?>">
							<?php echo esc_html__('Activity Report', 'church_mgt'); ?></a>

							<a href="?page=cmgt-report&tab=export-report" class="nav-tab <?php echo $active_tab == 'export-report' ? 'nav-tab-active' : ''?>">
							<?php echo esc_html__('Download Reports', 'church_mgt'); ?></a>
						</h2> -->
						<?php
						if($active_tab == 'payment_report' || $active_tab == 'payment_data' || $active_tab == 'income_report' || $active_tab == 'expense_report'  || $active_tab == 'income_expense')
						{ ?>
							<ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per cmgt-view-page-tab nav-tab-wrapper flex-nowrap overflow-auto" role="tablist">
								<li class="<?php if($active_tab1=='payment_data'){?>active<?php }?>">			
									<a href="?page=cmgt-report&tab=payment_data" class="nav-tab <?php echo $active_tab == 'payment_data' ? 'nav-tab-active' : ''?>">
									<?php echo esc_html__('Transaction/Donation Report', 'church_mgt'); ?></a>
								</li>
								<li class="<?php if($active_tab1=='income_report'){?>active<?php }?>">
									<a href="?page=cmgt-report&tab=income_report&tab_1=datatable" class="nav-tab <?php echo $active_tab == 'income_report' ? 'nav-tab-active' : ''?>">
									<?php echo esc_html__('Income Report', 'church_mgt'); ?></a>
								</li>
								<li class="<?php if($active_tab1=='expense_report'){?>active<?php }?>">
									<a href="?page=cmgt-report&tab=expense_report&tab_1=datatable" class="nav-tab <?php echo $active_tab == 'expense_report' ? 'nav-tab-active' : ''?>">
									<?php echo esc_html__('Expense Report', 'church_mgt'); ?></a>
								</li>
								<li class="<?php if($active_tab1=='income_expense'){?>active<?php }?>">
									<a href="?page=cmgt-report&tab=income_expense&tab_1=datatable" class="nav-tab <?php echo $active_tab == 'income_expense' ? 'nav-tab-active' : ''?>">
									<?php echo esc_html__('Income & Expense Report', 'church_mgt'); ?></a>
								</li>
							</ul>
							<?php
						} 
						if($active_tab == 'payment_report' || $active_tab == 'payment_data')
						{ ?>
							<h2 class="nav-tab-wrapper"><!-- NAV TAB WRAPPER MENU START-->  
								<a href="?page=cmgt-report&tab=payment_data" class="nav-tab <?php echo esc_html($active_tab) == 'payment_data' ? 'nav-tab-active' : ''?>">
								<?php echo esc_html__('Datatable', 'church_mgt'); ?></a>
								<a href="?page=cmgt-report&tab=payment_report" class="nav-tab <?php echo esc_html($active_tab) == 'payment_report' ? 'nav-tab-active' : ''?>">
								<?php echo esc_html__('Graph', 'church_mgt'); ?></a>
							</h2> <!-- NAV TAB WRAPPER MENU END--> 
							<?php
						} ?>

						 <!-- NAV TAB WRAPPER MENU END--> 
						<div class="clearfix"></div>
						<?php 
							if($active_tab == 'attendance_report')
							{
								require_once CMS_PLUGIN_DIR. '/admin/report/attendance_report.php';
							}
							if($active_tab == 'payment_report')
							{
								require_once CMS_PLUGIN_DIR. '/admin/report/payment_report.php';
							}
							if($active_tab == 'payment_data')
							{
								require_once CMS_PLUGIN_DIR. '/admin/report/payment_data.php';
							}
							if($active_tab == 'income_report')
							{
								require_once CMS_PLUGIN_DIR. '/admin/report/income_report.php';
							}
							if($active_tab == 'expense_report')
							{
								require_once CMS_PLUGIN_DIR. '/admin/report/expense_report.php';
							}
							if($active_tab == 'activity_report')
							{
								require_once CMS_PLUGIN_DIR. '/admin/report/activity_reoprt.php';
							}
							if($active_tab == 'export-report')
							{
								require_once CMS_PLUGIN_DIR. '/admin/report/download_report.php';
							}
							if($active_tab == 'income_expense')
							{
								require_once CMS_PLUGIN_DIR. '/admin/report/income_expense_netprofit.php';
							}
						?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->
 
