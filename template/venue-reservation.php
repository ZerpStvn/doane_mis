<?php $curr_user_id=get_current_user_id();
$obj_church = new Church_management(get_current_user_id());
$obj_venue=new Cmgtvenue;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'reservation_list');
?>
<script type="text/javascript">
	$(document).ready(function()
	{
		jQuery('#reservation_list').DataTable({
			// "responsive":true,
			language:<?php echo MJ_cmgt_datatable_multi_language();?>,
			"order": [[ 0, "asc" ]],
			"aoColumns":[
						  {"bSortable": false},
						  {"bSortable": true},
						  {"bSortable": true},
						  {"bSortable": true},
						  {"bSortable": false}]
				});
		
	} );
</script>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list">
			</div>	
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
	<div class="panel-body panel-white"><!--PANEL WHITE DIV START-->
		<ul class="nav nav-tabs panel_tabs" role="tablist"><!--NAV TABS MENU START-->
			<li class="<?php if($active_tab=='reservation_list'){?>active<?php }?>">
				<a href="?church-dashboard=user&&page=venue-reservation&tab=reservation_list" class="tab <?php echo $active_tab == 'reservation_list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Reservation List', 'church_mgt'); ?></a>
			  </a>
			</li>
		</ul><!--NAV TABS MENU END-->
		<div class="tab-content"><!--TAB CONTENT DIV STRAT-->
			<?php if($active_tab == 'reservation_list')
			{ ?>	
				<div class="panel-body"><!--PANEL BODY DIV START-->
					<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
						<table id="reservation_list" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Usage Title', 'church_mgt' ) ;?></th>
									<th><?php esc_html_e( 'Reserved Date', 'church_mgt' ) ;?></th>
									<th><?php esc_html_e( 'Reserved By', 'church_mgt' ) ;?></th>
									<th> <?php esc_html_e( 'Start Time', 'church_mgt' ) ;?></th>
									<th> <?php esc_html_e( 'End Time', 'church_mgt' ) ;?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?php esc_html_e( 'Usage Title', 'church_mgt' ) ;?></th>
									<th><?php esc_html_e( 'Reserved Date', 'church_mgt' ) ;?></th>
									<th><?php esc_html_e( 'Reserved By', 'church_mgt' ) ;?></th>
									<th> <?php esc_html_e( 'Start Time', 'church_mgt' ) ;?></th>
									<th> <?php esc_html_e( 'End Time', 'church_mgt' ) ;?></th>
								</tr>
							</tfoot>
							<tbody>
							 <?php 
								$reservationdata=$obj_reservation->MJ_cmgt_get_all_reservation();
								if(!empty($reservationdata))
								{
									foreach ($reservationdata as $retrieved_data)
									{
									?>
										<tr>
											<td class="title">
											<?php echo esc_attr($retrieved_data->usage_title);?>
											</td>
											<td class="reserv_date"><?php echo esc_attr($retrieved_data->reserve_date);?></td>
											<td class="reserv_date"><?php echo MJ_cmgt_church_get_display_name(esc_attr($retrieved_data->applicant_id));?></td>
											<td class="start time"><?php echo esc_attr($retrieved_data->reservation_start_time);?></td>
											<td class="end time"><?php echo esc_attr($retrieved_data->reservation_end_time);?></td>
									
										</tr>
								<?php } 
								}?>
							</tbody>
						</table>
					</div><!--TABLE RESPONSIVE DIV END-->
				</div><!--PANEL BODY DIV END-->
		<?php 
			}
			?>
		</div><!--TAB CONTENT DIV END-->
	</div><!--PANEL WHITE DIV END-->
<?php ?>