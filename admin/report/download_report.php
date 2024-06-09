<?php 
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
			<div class="form-body user_form margin_top_15"> <!--Card Body div-->   
				<div class="row"><!--Row Div--> 
					<div class="col-md-4 cmgt_display">
						<label class="ml-1 custom-top-label top r_type" for="exam_id"><?php esc_html_e('Select Report Type','church_mgt');?></label>
						<select id="report_type" class="form-control r_type"  name="report_type">
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
			// var_dump($sdate);
			// var_dump($edate);
			// die;
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
					  <div class="clear col-md-12 padding_left_10px"><?php esc_html_e("Your file is ready. You can download it from",'church_mgt');?> <a href='<?php echo$filename;?>'><?php _e('Download!','church_mgt');?></a> <?php
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
						  <div class="clear col-md-12 padding_left_10px"><?php esc_html_e("Your file is ready. You can download it from",'church_mgt');?> <a href='<?php echo$filename;?>'><?php _e('Download!','church_mgt');?></a><?php
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
        } ?>