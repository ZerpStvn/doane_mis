<?php ?>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#checkin_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$(".display-members").select2();
			
		$("#checkin_date").datepicker({
       	dateFormat: "yy-mm-dd",
		   autoclose: true,
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#checkout_date").datepicker("option", "minDate", dt);
        }
	    });
	    $("#checkout_date").datepicker({
	      dateFormat: "yy-mm-dd",
		  autoclose: true,
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() - 0);
	            $("#checkin_date").datepicker("option", "maxDate", dt);
	        }
	    });	
		 //not aloow - value//
		$('#family_member').keydown(function( e ) {
			if(e.which === 189 || e.which == 109) 
			 return false;
		}); 
	});
</script>
<?php 	
if($active_tab == 'checkin')
	{
        $room_id=0;
		if(isset($_REQUEST['room_id']))
			$room_id= sanitize_text_field($_REQUEST['room_id']);	
		$edit=0;
		?>
        <div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="checkin_form" action="" method="post" class="form-horizontal" id="checkin_form"><!-- Check-in FORM START-->
				<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="room_id" value="<?php echo esc_attr($room_id);?>"  />
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Check-In Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="room_title" readonly class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if(isset($room_id)){ ?>value="<?php echo MJ_get_room_name(esc_attr($room_id)); }?>" name="room_title">
									<label class="" for="room_title"><?php _e('Room Title','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>	
						</div>
						<div class="col-md-6 input cmgt_display margin_bottom_0_res">
							<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>

							<select id="member_list" class="form-control validate[required] line_height_30px member-select2" name="member_id">
								<option value=""><?php _e('Select Member','church_mgt');?></option>
									<?php $get_members = array('role' => 'member');
										$membersdata=get_users($get_members);
									if(!empty($membersdata))
									{
										foreach ($membersdata as $member){?>
											<option value="<?php echo esc_attr($member->ID);?>" <?php //selected($member_id,$member->ID);?>><?php echo esc_attr($member->display_name)." - ".esc_attr($member->member_id); ?> </option>
										<?php }
									}?>
							</select>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="family_member" class="form-control validate[required,custom[onlyNumber]] text-input" maxlength="4" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->family_members);}elseif(isset($_POST['family_member'])) echo esc_attr($_POST['family_member']);?>" name="family_member">
									<label class="ml-2" for="family_member"><?php _e('Number Of Family Member','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>	
						</div>

						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="checkin_date" class="form-control validate[required]" type="text" name="checkin_date" value="<?php if($edit){ echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->checkin_date)));}elseif(isset($_POST['checkin_date'])){ echo esc_attr($_POST['checkin_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
									<label class="" for="checkin_date"><?php _e('Check-in Date','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="checkout_date" class="form-control validate[required]" type="text" name="checkout_date"  
									value="<?php if($edit){ echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->checkout_date)));}elseif(isset($_POST['checkout_date'])){ echo esc_attr($_POST['checkout_date']);}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
									<label class="" for="checkin_date"><?php _e('Expected Check-out Date','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<?php wp_nonce_field( 'save_checkin_nonce' ); ?>
						<div class="col-md-6 mt-2">
							<input type="submit" value="<?php if($edit){ _e('Save Reservation','church_mgt'); }else{ _e('Add Reservation','church_mgt');}?>" name="save_checkin" class="btn btn-success col-md-12 save_btn"/>
						</div>
					</div>
				</div>
			</form><!-- Check-in FORM END-->
        </div><!-- PANEL BODY DIV END-->
<?php } ?>