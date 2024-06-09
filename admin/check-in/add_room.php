<script type="text/javascript">
	$(document).ready(function() {
		$('#room_form').validationEngine({promptPosition: "bottomLeft",maxErrorsPerField: 1});
		//not aloow - value//
		$('#capacity').keydown(function(e) {
			if (e.which === 189)
				return false;
		});
	});
</script>
<?php
	if ($active_tab == 'addroom') {
		$room_id = 0;
		if (isset($_REQUEST['room_id'])) {
			$room_id = sanitize_text_field($_REQUEST['room_id']);
		}

		$edit = 0;
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit') {
			$edit = 1;
			$result = $obj_checkin->MJ_cmgt_get_single_room($room_id);
		}
?>

<div class="panel-body">
    <!-- PANEL BODY DIV START-->
    <form name="vanue_form" action="" method="post" class="form-horizontal" id="room_form">
        <!-- ROOM FORM START-->
        <?php $action = sanitize_text_field(isset($_REQUEST['action']) ? $_REQUEST['action'] : 'insert');?>
        <input type="hidden" name="action" value="<?php echo esc_attr($action); ?>">
        <input type="hidden" name="room_id" value="<?php echo esc_attr($room_id); ?>" />
		<div class="form-body user_form">
			<div class="row cmgt-addform-detail">
				<p><?php esc_html_e('Room Information','church_mgt');?></p>
			</div>
			<div class="row">
				<div class="col-md-6 margin_bottom_15">
					<div class="form-group input ">
					<div class="col-md-12 form-control">
							<input id="room_title"
								class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if ($edit) { ?>value="<?php echo esc_attr($result->room_title);} elseif (isset($_POST['room_title'])) { echo esc_attr($_POST['room_title']); } ?>" name="room_title">
							<label class="" for="room_title"><?php _e('Room Title', 'church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>	
				</div>
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="capacity" class="form-control validate[required,custom[onlyNumber]] text-input" maxlength="4"
								type="text" <?php if ($edit) { ?>value="<?php echo esc_attr($result->capacity);} elseif (isset($_POST['capacity'])) {
								echo esc_attr($_POST['capacity']);
							}
							?>" name="capacity">
							<label class="" for="capacity"><?php _e('Capacity', 'church_mgt');?><span
								class="require-field">*</span></label>
						</div>
					</div>	
				</div>
				<?php
					$demographics_array = array();
					if ($edit) 
					{
						$demographics_array = (explode(",", $result->demographics));
					}
				?>
				
				<div class="col-md-6">
					<div class="form-group">
						<div class="col-md-12 form-control input_height_48px">
							<div class="row padding_radio">
								<div class="input-group">
									<label class="custom-top-label margin_left_0" for="demographics"><?php _e('Demographics', 'church_mgt');?></label>
									<div class="checkbox checkbox_lebal_padding_8px cmgt_checkbox_befor_color rtl_checkbox_right rtl_add_room_ck_box">
										<input id="Adults" class="form-control cmgt_volunteer_bg top1 " <?php if (in_array("adults", $demographics_array)) {
											echo 'checked';
										}
										?> type="checkbox" name="demographics[]" value="adults">
													<span class="demographics_text"><?php _e('Adults', 'church_mgt');?></span>
													<input id="families" class="form-control cmgt_volunteer_bg top1" <?php if (in_array("families", $demographics_array)) {
											echo 'checked';
										}
										?> type="checkbox" name="demographics[]" value="families">
													<span class="demographics_text"><?php _e('Families', 'church_mgt');?></span> 
													<input id="youth" class="form-control cmgt_volunteer_bg top1" <?php if (in_array("youth", $demographics_array)) {
											echo 'checked';
										}
										?> type="checkbox" name="demographics[]" value="youth">
													<span class="demographics_text"><?php _e('Youth', 'church_mgt');?></span>
													<input id="children" class="form-control cmgt_volunteer_bg top1" <?php if (in_array("children", $demographics_array)) {
											echo 'checked';
										}
										?> type="checkbox" name="demographics[]" value="children">
										<span class="demographics_text"><?php _e('Children', 'church_mgt');?></span> 
									</div>

								</div>												
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="row">
				<?php wp_nonce_field('save_room_nonce');?>
				<div class="col-md-6 mt-2">
					<input type="submit" value="<?php if ($edit) {_e('Save Room', 'church_mgt');} else {_e('Add Room', 'church_mgt');}?>" name="save_room" class="btn btn-success col-md-12 save_btn" />
				</div>
			</div>
		</div>
    </form><!-- ROOM FORM END-->
</div><!-- PANEL BODY DIV END-->
<?php
}?>