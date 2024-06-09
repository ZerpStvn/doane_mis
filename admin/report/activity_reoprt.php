<?php 
$month =array('1'=>__('January','church_mgt'),
			  '2'=>__('February','church_mgt'),
			  '3'=>__('March','church_mgt'),
			  '4'=>__('April','church_mgt'),
			  '5'=>__('May','church_mgt'),
			  '6'=>__('June','church_mgt'),
			  '7'=>__('July','church_mgt'),
			  '8'=>__('Auguest','church_mgt'),
		      '9'=>__('September','church_mgt'),
			  '10'=>__('Octomber','church_mgt'),
			  '11'=>__('November','church_mgt'),
			  '12'=>__('December','church_mgt'),);
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
	
