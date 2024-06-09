<?php ?>
<script type="text/javascript">
$(document).ready(function()
 {
	$('#transaction_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$(".display-members").select2();
	$('#transaction_date').datepicker({
		changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			autoclose: true,
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
     }); 
} );
</script>
     <?php 	
	if($active_tab == 'addtransaction')
	 {
		$group_id=0;
		if(isset($_REQUEST['transaction_id']))
			$transaction_id=$_REQUEST['transaction_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_transaction->MJ_cmgt_get_single_transaction($transaction_id);
				
			}?>
		<div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="transaction_form" action="" method="post" class="form-horizontal" id="transaction_form"><!-- TRANSACTION FORM START-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="transaction_id" value="<?php echo $transaction_id;?>" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="day"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>	
					<div class="col-sm-8">
						<?php ?>
						<select id="member_list" class="display-members member-select2" name="member_id">
						<option value=""><?php _e('Select Member','church_mgt');?></option>
							<?php
							if($edit)
								$member_id=$result->member_id;
							elseif(isset($_POST['start_date'])) 
								$member_id=$_POST['start_date'];
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
								<?php } }
							 }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="checkin_date"><?php _e('Transaction Date','church_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="transaction_date" class="form-control validate[required]" type="text"  name="transaction_date" 
						value="<?php if($edit){ echo $result->transaction_date;}elseif(isset($_POST['transaction_date'])) echo $_POST['transaction_date'];?>" autocomplete="off" readonly>
					</div>	
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="amount"><?php _e('Amount','church_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="amount" class="form-control validate[required]" type="text"  name="amount" 
						value="<?php if($edit){ echo $result->amount;}elseif(isset($_POST['amount'])) echo $_POST['amount'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 col-xs-12 control-label" for="reservation_date"><?php _e('Method','church_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3 col-xs-9">
					<?php if($edit)
							$period=$result->pay_method;
						  elseif(isset($_POST['pay_method'])) 
							$period=$_POST['pay_method'];?>
						<select class="form-control" name="pay_method" id="pay_method">
							<option value="cash" <?php selected($period,'cash');?>><?php _e('Cash','church_mgt');?></option>
							<option value="check" <?php selected($period,'check');?>><?php _e('Check','church_mgt');?></option>
							<option value="bank_transfer" <?php selected($period,'bank_transfer');?>><?php _e('Bank Transfer','church_mgt');?></option>
						</select>
					</div>
				</div>
				<?php wp_nonce_field( 'save_transaction_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Save Transaction','church_mgt'); }else{ _e('Add Transaction','church_mgt');}?>" name="save_transaction" class="btn btn-success"/>
				</div>
			</form><!-- TRANSACTION FORM END-->
        </div><!-- PANEL BODY DIV END-->
     <?php 
	}
	 ?>