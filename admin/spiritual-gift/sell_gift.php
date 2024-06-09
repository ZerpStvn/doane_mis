<?php ?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#sell_gift_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$(".display-members").select2();
	 
	jQuery('#sell_date').datepicker({
		dateFormat: "yy-mm-dd",
		minDate:'today',
		changeMonth: true,
        changeYear: true,
        yearRange:'-65:+25',
		beforeShow: function (textbox, instance) 
		{
			instance.dpDiv.css({
				marginTop: (-textbox.offsetHeight) + 'px'                   
			});
		},    
        onChangeMonthYear: function(year, month, inst) {
            jQuery(this).val(month + "/" + year);
        }                    
	}); 
	//---member validation-----//
	$("#sell_gift").click(function() 
	{
		var ext = $('#member_list').val();
		if(ext =='' || ext == null)
		{
			alert("<?php _e('Please fill out all the required fields','church_mgt');?>");
			return false;	
		} 
	});
} );
</script>
    <?php 	
	if($active_tab == 'sellgift')
	{
		$sell_id=0;
		if(isset($_REQUEST['sell_id']))
			$sell_id= sanitize_text_field($_REQUEST['sell_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_gift->MJ_cmgt_get_single_sell_gift($sell_id);
			}?>
		
		<div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="sell_gift_form" action="" method="post" class="form-horizontal" id="sell_gift_form"><!-- SELL GIFT FORM START-->
				<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="sell_id" value="<?php echo esc_attr($sell_id);?>"  />
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Sell Gift Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6 input cmgt_display margin_bottom_0_res">
							<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>								
							<select class="form-control line_height_30px" name="member_id" id="member_list">		
								<option value=""><?php _e('Select Member','church_mgt');?></option>
								<?php
									if($edit)
									$member_id=$result->member_id;
								elseif(isset($_POST['member_id'])) 
									$member_id=$_POST['member_id'];
								else
									$member_id=0;
								
								$get_members = array('role' => 'member');
									$membersdata=get_users($get_members);
								if(!empty($membersdata))
								{
									foreach ($membersdata as $member){
										if(empty($member->cmgt_hash)){
											?>
										<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
									<?php }
									}
								}?>
							</select>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="sell_date" class="form-control" type="text" name="sell_date" 
									value="<?php if($edit){ echo date("Y-m-d",strtotime(esc_attr($result->sell_date)));}elseif(isset($_POST['sell_date'])){ echo esc_attr($_POST['sell_date']);}else{ echo date("Y-m-d"); }?>" autocomplete="off" readonly>
									<label class="" for="sell_date"><?php _e('Date','church_mgt');?></label>
								</div>	
							</div>
						</div>
						<div class="col-md-6 input cmgt_display margin_bottom_0_res">
							<label class="ml-1 custom-top-label top" for="gift_id"><?php _e('Gift','church_mgt');?><span class="require-field">*</span></label>
							<?php if($edit){ $gift_id=$result->gift_id; }elseif(isset($_POST['gift_id'])){$gift_id=$_POST['gift_id'];}else{$gift_id='';}?>
							<select id="gift_id" class="form-control validate[required]" name="gift_id">
								<option value=""><?php _e('Select Gift','church_mgt');?></option>
									<?php 
										if($edit)
											$gift_id= sanitize_text_field($result->gift_id);
										elseif(isset($_POST['gift_id'])) 
											$gift_id= sanitize_text_field($_POST['gift_id']);
										else
											$gift_id=0;
											$giftdata=$obj_gift->MJ_cmgt_get_all_gifts();
										if(!empty($giftdata))
										{
											foreach ($giftdata as $gift)
											{?>
												<option value="<?php echo $gift->id;?>" <?php selected($gift_id,$gift->id);  ?>><?php echo $gift->gift_name; ?> </option>
												<?php
											} 
										} ?>
							</select>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="gift_price" class="form-control validate[required,custom[amount]] text-input"  maxlength="8" type="text" value="<?php if($edit){ echo esc_attr($result->gift_price);}elseif(isset($_POST['gift_price'])) echo esc_attr($_POST['gift_price']);?>" name="gift_price">
									<label class="" for="gift_price"><?php _e('Gift Price','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mt-2">
							<div class="offset-sm-0">
								<input id="sell_gift" type="submit" value="<?php if($edit){ _e('Save Sell Gift','church_mgt'); }else{ _e('Add Sell Gift','church_mgt');}?>" name="sell_gift" class="btn btn-success col-md-12 save_btn"/>
							</div>
						</div>
					</div>
				</div>
			</form><!-- SELL GIFT FORM END-->
       </div><!-- PANEL BODY DIV END-->
<?php 
	}
?>