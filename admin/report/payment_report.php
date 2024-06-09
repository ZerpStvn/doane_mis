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

$currency=MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));

global $wpdb;
$table_name = $wpdb->prefix."cmgt_transaction";

$q="SELECT EXTRACT(MONTH FROM transaction_date) as date,sum(amount) as count FROM ".$table_name." WHERE YEAR(transaction_date) =".$year." group by month(transaction_date) ORDER BY transaction_date ASC";
$result=$wpdb->get_results($q);

$chart_array = array();
$chart_array[] = array(__('Month','church_mgt'),__('Payment','church_mgt'));
foreach($result as $r)
{
	
	$chart_array[]=array( $month[$r->date], (int)$r->count);
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
				 'format' => html_entity_decode($currency),
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'textStyle' => Array('color' => '#66707e','fontSize' => 12)
				),
 		'colors' => array('#22BAA0')
 		
			);
require_once CMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';

$GoogleCharts = new GoogleCharts;

$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );

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
	{ ?>
		<div class="calendar-event-new"> 
			<img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
		</div>	
		<?php 
	} ?>
	<script type="text/javascript">
		<?php 
		if(!empty($result))
		{
			echo $chart;
		}
		?>
	</script>
 

