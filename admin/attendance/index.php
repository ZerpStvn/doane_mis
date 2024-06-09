<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED); ?>
<?php
MJ_cmgt_header();
$curr_user_id = get_current_user_id();
$obj_activity = new Cmgtactivity;
$obj_attend = new Cmgtattendence;
$active_tab = sanitize_text_field(isset($_GET['tab']) ? $_GET['tab'] : 'attendance');
$activity_id = 0;
$obj_group = new Cmgtgroup;
?>
<script type="text/javascript">
	$(document).ready(function () {
		//------------ CLOSE MESSAGE ---------//
		$('.notice-dismiss').click(function () {
			$('#message').hide();
		});
	}); 
</script>
<script type="text/javascript">
	$(document).ready(function () {

		$('#admin_attedence').validationEngine({ promptPosition: "bottomLeft", maxErrorsPerField: 1 });
		$('#curr_date').datepicker({
			dateFormat: "yy-mm-dd",
			//minDate:"today",
			maxDate: 0,
			changeMonth: true,
			changeYear: true,
			autoclose: true,
			yearRange: '-65:+25',
			beforeShow: function (textbox, instance) {
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			},
			onChangeMonthYear: function (year, month, inst) {
				jQuery(this).val(month + "/" + year);
			}
		});
		$('#selecctall').click(function (event) {  //on click 
			if (this.checked) { // check select status
				$('.checkbox1').each(function () { //loop through each checkbox
					this.checked = true;  //select all checkboxes with class "checkbox1"               
				});
			} else {
				$('.checkbox1').each(function () { //loop through each checkbox
					this.checked = false; //deselect all checkboxes with class "checkbox1"                       
				});
			}
		});
		$('.checkbox1').on('change', function () {
			if (false == $(this).prop("checked")) {
				$("#selecctall").prop('checked', false);
			}
			if ($('.checkbox1:checked').length == $('.checkbox1').length) {
				$("#selecctall").prop('checked', true);
			}
		});

	});
</script>
<!-- user redirect url enter -->
<?php
$user_access = MJ_cmgt_add_check_access_for_view('attendance');
if ($user_access == 'administrator') {
	$user_access_add = 1;
	$user_access_edit = 1;
	$user_access_delete = 1;
	$user_access_view = 1;
} else {
	$user_access_view = $user_access['view'];
	$user_access_add = $user_access['add'];

	if (isset($_REQUEST['page'])) {
		if ($user_access_view == '0') {
			mj_cmgt_access_right_page_not_access_message_admin_side();
			die;
		}
		if (!empty($_REQUEST['action'])) {
			if ($user_access['page_link'] == "attendance" && ($_REQUEST['action'] == "add")) {
				if ($user_access_edit == '0') {
					mj_cmgt_access_right_page_not_access_message_admin_side();
					die;
				}
			}
		}
	}
}
?>
<!-- user redirect url enter code end -->
<div class="page-inner"><!-- PAGE INNNER DIV START-->
	<?php
	if (isset($_POST['MJ_cmgt_save_attendence'])) {
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce($nonce, 'save_attendence_admin_nonce')) {
			//---------- SAVE ATTENDENCE ----------//
			if (!empty($_POST['attendance'])) {
				$attend_by = get_current_user_id();

				$result = $obj_attend->MJ_cmgt_save_attendence(MJ_cmgt_get_format_for_db($_POST['curr_date']), $_POST['activity_id'], $_POST['attendance'], $attend_by, $_POST['status'], $_POST['role_name']);
				if ($result) { ?>
					<div id="message" class="updated below-h2 notice is-dismissible ">
						<p>
							<?php _e('Attendance saved successfully!', 'church_mgt'); ?>
						</p>
					</div>
					<?php
				}
			}
		}
	}
	?>
	<div class="panel-body cmgt_main_attedence" id="cmgt_padding_10px"><!-- PANEL BODY DIV START-->

		<?php
		$active_tab_1 = sanitize_text_field(isset($_GET['tab_1']) ? $_GET['tab_1'] : 'activity_attendance_list');
		if ($active_tab == 'attendance') { ?>

			<h2 class="nav-tab-wrapper"><!-- NAV TAB WRAPPER MENU START-->

				<ul class="nav border_bottom_for_view_page nav-tabs panel_tabs margin_left_1per cmgt-view-page-tab flex-nowrap overflow-auto" id="activity_attendence_ul">
					<a href="?page=cmgt-attendance&tab_1=activity_attendance_list"
						class="nav-tab <?php echo esc_html($active_tab_1) == 'activity_attendance_list' ? 'nav-tab-active' : '' ?>">
						<?php echo esc_html__('Activity Attendance List', 'church_mgt'); ?>
					</a>

					<a href="?page=cmgt-attendance&tab_1=activity_attendance"
						class="nav-tab <?php echo esc_html($active_tab_1) == 'activity_attendance' ? 'nav-tab-active' : '' ?>">
						<?php echo esc_html__('Activity Attendance', 'church_mgt'); ?>
					</a>
				</ul>
			</h2>
			<?php
			if ($_REQUEST['tab_1'] == 'activity_attendance_list') {
				?>
				<form method="post" name="admin_attedence" id="admin_attedence" class="margin_top_20px">
					<input type="hidden" name="class_id" value="<?php if (isset($class_id))
						echo $class_id; ?>" />

					<div class="form-body user_form"> <!--Card Body div-->
						<div class="row">
							<div class="col-md-3 mb-3 input">
								<label class="ml-1 custom-top-label top" for="class_id">
									<?php esc_attr_e('Date Type', 'church_mgt'); ?><span class="require-field">*</span>
								</label>
								<select class="line_height_30px form-control date_type validate[required]" name="date_type"
									autocomplete="off">
									<option value="">
										<?php _e('Select', 'church_mgt'); ?>
									</option>
									<option value="today">
										<?php _e('Today', 'church_mgt'); ?>
									</option>
									<option value="this_week">
										<?php _e('This Week', 'church_mgt'); ?>
									</option>
									<option value="last_week">
										<?php _e('Last Week', 'church_mgt'); ?>
									</option>
									<option value="this_month">
										<?php _e('This Month', 'church_mgt'); ?>
									</option>
									<option value="last_month">
										<?php _e('Last Month', 'church_mgt'); ?>
									</option>
									<option value="last_3_month">
										<?php _e('Last 3 Months', 'church_mgt'); ?>
									</option>
									<option value="last_6_month">
										<?php _e('Last 6 Months', 'church_mgt'); ?>
									</option>
									<option value="last_12_month">
										<?php _e('Last 12 Months', 'church_mgt'); ?>
									</option>
									<option value="this_year">
										<?php _e('This Year', 'church_mgt'); ?>
									</option>
									<option value="last_year">
										<?php _e('Last Year', 'church_mgt'); ?>
									</option>
									<option value="period">
										<?php _e('Period', 'church_mgt'); ?>
									</option>
								</select>
							</div>
							<div id="date_type_div" class="date_type_div_none row col-md-6 mb-2"></div>
							<div class="col-md-3 cmgt_display cmgt_select_error margin_top_10 rtl_from_select_input_btn">
								<label class="ml-1 custom-top-label mt-2" for="reservation_date">
									<?php _e('Select Activity', 'church_mgt'); ?><span class="require-field">*</span>
								</label>

								<?php $activity_id = 0;
								if (isset($_POST['activity_id'])) {
									$activity_id = sanitize_text_field($_POST['activity_id']);
								} ?>
								<select name="activity_id" id="activity_id"
									class="activity_id_onchange form-control validate[required]  ">
									<option value=" ">
										<?php _e('Select Activity Name', 'church_mgt'); ?>
									</option>
									<?php
									$activitydata = $obj_activity->MJ_cmgt_get_all_activities();
									if (!empty($activitydata)) {
										foreach ($activitydata as $activity) { ?>
											<option value="<?php echo esc_attr($activity->activity_id); ?>" <?php selected($activity->activity_id, $activity_id) ?>>
												<?php echo esc_attr($activity->activity_title); ?>
											</option>
										<?php }
									} ?>
								</select>
							</div>
							<div class="col-md-3 cmgt_display rtl_from_select_input_btn">
								<label class="ml-2 custom-top-label mt-2" for="reservation_date">
									<?php _e('Select Group/Members', 'church_mgt'); ?> <span class="require-field">*</span>
								</label>
								<?php $group_id = 0;
								if (isset($_POST['group_id'])) {
									$group_id = $_POST['group_id'];
								} ?>
								<select name="group_id" id="group_id"
									class="load_group_by_activity_id form-control validate[required]">
									<option value=" ">
										<?php _e('Select Group Name', 'church_mgt'); ?>
									</option>
								</select>

							</div>

							<div class="col-md-3">
								<div class="offset-sm-0">
									<input type="submit" value="<?php _e('View  Attendance', 'church_mgt'); ?>"
										name="view_attendance" class="btn btn-success  col-md-12 save_btn" />
								</div>
							</div>
						</div>
					</div>
				</form>
				<?php
				if (isset($_REQUEST['view_attendance'])) {
					$date_type = $_POST['date_type'];
					if ($date_type == "period") {
						$start_date = $_REQUEST['start_date'];
						$end_date = $_REQUEST['end_date'];
					} else {
						$result = mj_cmgt_all_date_type_value($date_type);
						$response = json_decode($result);
						$start_date = $response[0];
						$end_date = $response[1];
					}
					$activity_id = $_REQUEST['activity_id'];
					$group = $_REQUEST['group_id'];
				} else {
					$start_date = date('Y-m-d');
					$end_date = date('Y-m-d');
					$activity_id = $_REQUEST['activity_id'];
					$group = $_REQUEST['group_id'];
				}

				global $wpdb;
				$table_name = $wpdb->prefix . 'cmgt_attendence';
				if ($group == 'member') {
					$attendance_data = $wpdb->get_results("select *from $table_name where attendence_date BETWEEN '$start_date' AND '$end_date' AND activity_id = '$activity_id' AND role_name = '$group' ");
				} else {
					$group_id = 0;
					$group_id = $_REQUEST['group_id'];
					$grp_membs = $obj_group->MJ_cmgt_get_group_members($group_id);
					if (!empty($grp_membs)) {
						foreach ($grp_membs as $member) {
							$attendance_data = $wpdb->get_results("select *from $table_name where attendence_date BETWEEN '$start_date' AND '$end_date' AND activity_id = '$activity_id' AND user_id = '$member->member_id' ");
						}
					}
				}

				if (!empty($attendance_data)) {
					?>
					<script type="text/javascript">
						$(document).ready(function () {
							var table = jQuery('#tblattendance').DataTable({
								//"responsive": true,
								"dom": 'lifrtp',
								language: <?php echo MJ_cmgt_datatable_multi_language(); ?>,
								"order": [[2, "asc"]],
								"sSearch": "<i class='fa fa-search'></i>",
								"aoColumns": [
									{ "bSortable": false },
									{ "bSortable": true },
									{ "bSortable": true },
									{ "bSortable": true },
									{ "bSortable": true },]
							});
							$('.btn-place').html(table.buttons().container());
							$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt'); ?>");
							$('.select_all').on('click', function (e) {
								if ($(this).is(':checked', true)) {
									$(".sub_chk").prop('checked', true);
									$(".select_all").prop('checked', true);
								}
								else {
									$(".sub_chk").prop('checked', false);
									$(".select_all").prop('checked', false);
								}
							});

							$('.sub_chk').on('change', function () {
								if (false == $(this).prop("checked")) {
									$(".select_all").prop('checked', false);
								}
								if ($('.sub_chk:checked').length == $('.sub_chk').length) {
									$(".select_all").prop('checked', true);
								}
							});
						});
					</script>
					<div class="btn-place"></div>
					<form name="wcwm_report" action="" method="post">
						<div class="panel-body"><!--PANEL BODY DIV START-->
							<div class="cmgt_payment_table_responsive table-responsive"><!--TABLE RESPONSIVE DIV START-->
								<table id="tblattendance" class="display" cellspacing="0" width="100%">
									<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
										<tr>
											<th>
												<?php _e('Image', 'church_mgt'); ?>
											</th>
											<th>
												<?php _e('Member Name', 'church_mgt'); ?>
											</th>
											<th>
												<?php _e('Activity Name', 'church_mgt'); ?>
											</th>
											<th>
												<?php _e('Date', 'church_mgt'); ?>
											</th>
											<th>
												<?php _e('Attendance Status', 'church_mgt'); ?>
											</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>
												<?php _e('Image', 'church_mgt'); ?>
											</th>
											<th>
												<?php _e('Member Name', 'church_mgt'); ?>
											</th>
											<th>
												<?php _e('Activity Name', 'church_mgt'); ?>
											</th>
											<th>
												<?php _e('Date', 'church_mgt'); ?>
											</th>
											<th>
												<?php _e('Attendance Status', 'church_mgt'); ?>
											</th>
										</tr>
									</tfoot>
									<tbody>
										<?php
										$i = 0;
										foreach ($attendance_data as $retrieved_data) {
											$all_entry = json_decode($retrieved_data->entry);
											$total_amount = 0;
											if ($i == 0) {
												$color_class = 'cmgt_list_page_image_color0';
											} elseif ($i == 1) {
												$color_class = 'cmgt_list_page_image_color1';

											} elseif ($i == 2) {
												$color_class = 'cmgt_list_page_image_color2';

											} elseif ($i == 3) {
												$color_class = 'cmgt_list_page_image_color3';

											} elseif ($i == 4) {
												$color_class = 'cmgt_list_page_image_color4';

											} elseif ($i == 5) {
												$color_class = 'cmgt_list_page_image_color5';

											} elseif ($i == 6) {
												$color_class = 'cmgt_list_page_image_color6';

											} elseif ($i == 7) {
												$color_class = 'cmgt_list_page_image_color7';

											} elseif ($i == 8) {
												$color_class = 'cmgt_list_page_image_color8';

											} elseif ($i == 9) {
												$color_class = 'cmgt_list_page_image_color9';
											}
											foreach ($all_entry as $entry) {
												$total_amount += $entry->amount;
											}
											?>
											<tr>
												<td class="user_image cmgt-checkbox_width_50px padding_left_0">
													<?php $uid = $retrieved_data->user_id;
													$userimage = get_user_meta($uid, 'cmgt_user_avatar', true);
													if (empty($userimage)) {
														echo '<img src=' . get_option('cmgt_member_thumb') . ' height="50px" width="50px" class="img-circle" />';
													} else
														echo '<img src=' . $userimage . ' height="50px" width="50px" class="img-circle"/>';
													?>
												</td>
												<td class="member_name ">
													<?php
													$user = get_userdata($retrieved_data->user_id);
													$display_label = $user->display_name;
													echo $display_label;
													?>
												</td>
												<td class="name ">
													<?php echo MJ_cmgt_get_activity_name($retrieved_data->activity_id); ?>
												</td>
												<td class="">
													<?php echo date(MJ_cmgt_date_formate(), strtotime(esc_attr($retrieved_data->attendence_date))); ?>
												</td>


												<td class="payment_status ">
													<?php echo _e($retrieved_data->status, 'church_mgt'); ?>
												</td>
											</tr>
											<?php
											$i++;
										}
										?>
									</tbody>
								</table>
							</div><!--TABLE RESPONSIVE DIV END-->
						</div> <!--PANEL BODY DIV END-->
					</form>
					<?php
				} else {
					?>
					<div class="calendar-event-new">
						<img class="no_data_img" src="<?php echo get_option('cmgt_Dashboard_defualt_img') ?>">
					</div>
					<?php
				}

			}
			if ($_REQUEST['tab_1'] == 'activity_attendance') {
				?>
				<form method="post" name="admin_attedence" id="admin_attedence">
					<input type="hidden" name="class_id" value="<?php if (isset($class_id))
						echo $class_id; ?>" />

					<div class="form-body user_form"> <!--Card Body div-->
						<div class="row cmgt-addform-detail">
							<p>
								<?php esc_html_e('Attendance', 'church_mgt'); ?>
							</p>
						</div>
						<div class="row">
							<div class="col-md-6 margin_bottom_20">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="curr_date" class="form-control validate[required]" type="text"
											name="curr_date"
											value="<?php if (isset($_POST['curr_date'])) {
												echo esc_attr(sanitize_text_field($_POST['curr_date']));
											} else {
												echo date('Y-m-d');
											} ?>"
											autocomplete="off" readonly>


										<label class="" for="curr_date">
											<?php _e('Date', 'church_mgt'); ?>
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-6 cmgt_display cmgt_select_error margin_top_10 rtl_from_select_input_btn">
								<label class="ml-1 custom-top-label mt-2" for="reservation_date">
									<?php _e('Select Activity', 'church_mgt'); ?><span class="require-field">*</span>
								</label>

								<?php $activity_id = 0;
								if (isset($_POST['activity_id'])) {
									$activity_id = sanitize_text_field($_POST['activity_id']);
								} ?>
								<select name="activity_id" id="activity_id"
									class="activity_id_onchange form-control validate[required]  ">
									<option value=" ">
										<?php _e('Select Activity Name', 'church_mgt'); ?>
									</option>
									<?php
									$activitydata = $obj_activity->MJ_cmgt_get_all_activities();
									if (!empty($activitydata)) {
										foreach ($activitydata as $activity) { ?>
											<option value="<?php echo esc_attr($activity->activity_id); ?>" <?php selected($activity->activity_id, $activity_id) ?>>
												<?php echo esc_attr($activity->activity_title); ?>
											</option>
										<?php }
									} ?>
								</select>

							</div>
							<div class="col-md-6 cmgt_display rtl_from_select_input_btn">
								<label class="ml-2 custom-top-label mt-2" for="reservation_date">
									<?php _e('Select Group/Members', 'church_mgt'); ?> <span class="require-field">*</span>
								</label>

								<?php $group_id = 0;
								if (isset($_POST['group_id'])) {
									$group_id = $_POST['group_id'];
								} ?>
								<select name="group_id" id="group_id"
									class="load_group_by_activity_id form-control validate[required]">
									<option value=" ">
										<?php _e('Select Group Name', 'church_mgt'); ?>
									</option>
								</select>

							</div>
							<div class="col-md-6">
								<div class="offset-sm-0">
									<input type="submit" value="<?php _e('Take/View  Attendance', 'church_mgt'); ?>"
										name="attendance" class="btn btn-success  col-md-12 save_btn" />
								</div>
							</div>
						</div>
					</div>
				</form>
				<div class="clearfix"> </div>
				<!-- Save Attdance Code -->
				<?php
				if (isset($_REQUEST['attendance']) || isset($_REQUEST['MJ_cmgt_save_attendence'])) {
					if (isset($_REQUEST['activity_id']) && $_REQUEST['activity_id'] != " ") {
						$activity_id = $_REQUEST['activity_id'];
					} else {
						$activity_id = 0;
					}

					if ($activity_id == 0) { ?>
						<div class="panel-heading">
							<h4 class="panel-title">
								<?php _e('Please Select Activity', 'church_mgt'); ?>
							</h4>
						</div>
						<?php
					} elseif (isset($_REQUEST['group_id']) && $_REQUEST['group_id'] != " ") {
						$all_members = array();
						if ($_REQUEST['group_id'] == 'member') {

							$get_members = array('role' => 'member');
							$Allmembersdata = get_users($get_members);
							foreach ($Allmembersdata as $member) {
								array_push($all_members, $member->ID);
							}
						} else {
							$group_id = 0;
							$group_id = $_REQUEST['group_id'];
							$grp_membs = $obj_group->MJ_cmgt_get_group_members($group_id);

							if (!empty($grp_membs)) {
								foreach ($grp_membs as $member) {
									array_push($all_members, $member->member_id);
								}

								// $membersdata=array();
								// $membersdata=MJ_cmgt_get_members_ofactivity($activity_id);
								// if(!empty($membersdata))
								// {
								// $all_members=array_merge($all_members,$grp_membs);
								// }
							} else { ?>
								<div class="panel-heading mt-4">
									<h4 class="panel-title">
										<?php _e('This group have no members.', 'church_mgt'); ?>
									</h4>
								</div>
							<?php
							}
						}
						$membersdata = array_unique($all_members);
					} else {
						$membersdata = MJ_cmgt_get_members_ofactivity($activity_id);
					}
					if (!empty($membersdata)) {
						?>
						<div class="mt-2" id="cmgt_padding_10px"> <!-- PANEL BODY DIV START-->
							<form method="post" class="cmgt_form_horizontal form-horizontal">
								<input type="hidden" name="activity_id" value="<?php echo $activity_id; ?>" />
								<input type="hidden" name="role_name" value="<?php echo 'member'; ?>" />
								<input type="hidden" name="curr_date"
									value="<?php if (isset($_POST['curr_date']))
										echo $_POST['curr_date'];
									else
										echo date(MJ_cmgt_date_formate()); ?>" />
								<div class="panel-heading mt-4">
									<h4 class="panel-title">
										<?php _e('Activity', 'church_mgt') ?> :
										<?php echo MJ_cmgt_get_activity_name($activity_id); ?> ,
										<?php _e('Date', 'church_mgt') ?> :
										<?php echo date(MJ_cmgt_date_formate(), strtotime($_POST['curr_date'])); ?>
									</h4>
								</div>
								<?php if (MJ_cmgt_get_format_for_db($_REQUEST['curr_date']) == date("Y-m-d") || get_option('cmgt_take_past_attendance') == 'yes') {
									?>
									<div class="form-group col-xs-12 col-md-12 col-sm-12">
										<label class="radio-inline">
											<input type="radio" name="status" value="Present" checked="checked" /> <span class="rediospan">
												<?php _e('Present', 'church_mgt'); ?>
											</span>
										</label>
										<label class="radio-inline">
											<input type="radio" name="status" value="Absent" /> <span class="rediospan">
												<?php _e('Absent', 'church_mgt'); ?>
											</span><br />
										</label>
									</div>
									<?php
								} ?>
								<div class="col-xs-6 col-md-6 col-sm-6 cmgt_table_responsive table-responsive">
									<table class="table">
										<tr>
											<?php if (MJ_cmgt_get_format_for_db($_REQUEST['curr_date']) == date("Y-m-d") || get_option('cmgt_take_past_attendance') == 'yes') {
												if ($user_access_add == 1) {
													?>
													<th width="46px" class="cmgt_pl_0px">
														<input type="checkbox" name="attendance[]" id="selecctall" />
													</th>
													<?php
												}
											}
											?>
											<th>
												<?php _e('Photo', 'church_mgt'); ?>
											</th>
											<th width="250px">
												<?php _e('Member Name', 'church_mgt'); ?>
											</th>
											<?php if (MJ_cmgt_get_format_for_db($_REQUEST['curr_date']) == date("Y-m-d") || get_option('cmgt_take_past_attendance') == 'yes') { ?>
												<th>
													<?php _e('Status', 'church_mgt'); ?>
												</th>
												<?php
											} else {
												?>
												<th width="70px">
													<?php _e('Status', 'church_mgt'); ?>
												</th>
											<?php
											}
											?>
										</tr>
										<?php
										$date = MJ_cmgt_get_format_for_db($_POST['curr_date']);
										if (!empty($membersdata)) {
											foreach ($membersdata as $user_id) {
												$user = get_userdata($user_id);
												if (empty($user)) {
												} else {
													$date = MJ_cmgt_get_format_for_db($_POST['curr_date']);
													$check_result = $obj_attend->MJ_cmgt_heck_attendence($user->ID, $activity_id, $date);
													echo '<tr>';
													if (MJ_cmgt_get_format_for_db($_REQUEST['curr_date']) == date("Y-m-d") || get_option('cmgt_take_past_attendance') == 'yes') {
														if ($user_access_add == 1) {
															?>
															<td class="checkbox_field cmgt_pl_0px">
																<span>
																	<input type="checkbox" class="checkbox1" name="attendance[]"
																		value="<?php echo $user->ID; ?>" <?php if (!empty($check_result)) {
																			   echo "checked=\'checked\'";
																		   } ?> />
																</span>
															</td>
														<?php
														}
													}
													?>
													<td class="user_image cmgt-checkbox_width_50px padding_left_0">
														<?php $uid = $user->ID;
														$userimage = get_user_meta($uid, 'cmgt_user_avatar', true);
														if (empty($userimage)) {
															echo '<img src=' . get_option('cmgt_member_thumb') . ' height="50px" width="50px" class="img-circle" />';
														} else
															echo '<img src=' . $userimage . ' height="50px" width="50px" class="img-circle"/>';
														?>
													</td>
													<?php
													echo '<td><span class="color_black">' . $user->first_name . ' ' . $user->last_name . ' (' . $user->member_id . ')</span></td>';
													if (MJ_cmgt_get_format_for_db($_REQUEST['curr_date']) == date("Y-m-d") || get_option('cmgt_take_past_attendance') == 'yes') {
														if (!empty($check_result)) {
															if ($check_result->status == "Present") {
																$status = esc_html__("Present", "church_mgt");
															} elseif ($check_result->status == "Absent") {
																$status = esc_html__("Absent", "church_mgt");
															}
															echo '<td><span>' . $status . '</span></td>';
														} else {
															echo '<td>&nbsp;</td>';
														}
													} else {
														?>
														<td>
															<?php if (!empty($check_result))
																_e('Present', 'church_mgt');
															else
																_e('Absent', 'church_mgt'); ?>
														</td>
													<?php
													}
													echo '</tr>';
												}
											}
										}
										?>
									</table>
								</div>
								<?php wp_nonce_field('save_attendence_admin_nonce'); ?>
								<div class="col-md-6 pt-3 rtl_save_att_btn_float">
									<div class="offset-sm-0">
										<?php if (MJ_cmgt_get_format_for_db($_REQUEST['curr_date']) == date("Y-m-d") || get_option('cmgt_take_past_attendance') == 'yes') {
											if ($user_access_add == 1) {
												?>
												<input type="submit" value="<?php _e('Save Attendance', 'church_mgt'); ?>"
													name="MJ_cmgt_save_attendence"
													class="btn btn-success col-md-12 save_btn cmgt_font_size_16px" />
											<?php
											}
										} ?>
									</div>
								</div>
							</form>
						</div><!-- PANEL BODY DIV END-->
					<?php
					}
				}
			}
		}
		if ($active_tab == 'ministry_attendence') {
			require_once CMS_PLUGIN_DIR . '/admin/attendance/ministry-attendence.php';
		}
		?>
	</div><!-- PANEL BODY DIV END-->
</div><!-- PNEL WHITE DIV END-->