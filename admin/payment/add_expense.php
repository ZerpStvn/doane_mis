<?php ?>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#expense_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		jQuery('#invoice_date').datepicker({
			dateFormat: "yy-mm-dd",
			//minDate:'today',
			changeMonth: true,
	        changeYear: true,
	        yearRange:'-100:+25',
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
		
	});
</script>
<?php 	
if($active_tab == 'addexpense')
{
	$expense_id=0;
	if(isset($_REQUEST['expense_id']))
		$expense_id= sanitize_text_field($_REQUEST['expense_id']);
		$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_payment->MJ_cmgt_get_income_data($expense_id);
	}
	if(MJ_cmgt_add_check_access_for_view_add('payment','add'))
				{ 
	?>
	<div class="panel-body"><!--PANEL BODY DIV STRAT-->
        <form name="expense_form" action="" method="post" class="" id="expense_form"><!--Expense FORM STRAT-->
			<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
			<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
			<input type="hidden" name="expense_id" value="<?php echo esc_attr($expense_id);?>">
			<input type="hidden" name="invoice_type" value="expense">

			<div class="form-body user_form">
				<div class="row cmgt-addform-detail">
					<p><?php esc_html_e('Expense Information','church_mgt');?></p>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="supplier_name" class="form-control validate[required,cusom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($result->supplier_name);}elseif(isset($_POST['supplier_name'])) echo esc_attr($_POST['supplier_name']);?>" name="supplier_name">
								<label class="" for="patient"><?php _e('Supplier Name','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 input cmgt_display">
						<label class="ml-1 custom-top-label top status" for="payment_status"><?php _e('Status','church_mgt');?><span class="require-field">*</span></label>
						<select name="payment_status" id="payment_status" class="form-control validate[required]">
							<option value="Paid"
								<?php if($edit)selected('Paid',$result->payment_status);?> ><?php _e('Paid','church_mgt');?></option>
							<option value="Part Paid"
								<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php _e('Part Paid','church_mgt');?></option>
							<option value="Unpaid"
								<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php _e('Unpaid','church_mgt');?></option>
						</select>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="invoice_date" class="form-control validate[required]" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime($result->invoice_date));}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date("Y-m-d");}?>" name="invoice_date" autocomplete="off" readonly>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="invoice_date"><?php _e('Date','church_mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>

					<hr class="margin_top_10">
					<?php 
					if($edit)
					{
						$all_entry=json_decode($result->entry);
					}
					else
					{
						if(isset($_POST['income_entry']))
						{
							$all_data=$obj_payment->MJ_cmgt_get_entry_records($_POST);
							$all_entry=json_decode($all_data);
						}
					}
					if(!empty($all_entry))
					{
						foreach($all_entry as $entry)
						{
							?>
							<div id="expense_entry" class="col-md-12">
								<div class="row form-group input">

									<div class="col-md-3 recently_appoinment_card">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="income_amount" class="form-control validate[required,custom[amount]] text-input" maxlength="10" type="text" value="<?php echo esc_attr($entry->amount);?>" name="income_amount[]" >
												<label class="" for="income_entry"><?php _e('Expense Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
											</div>
										</div>
									</div>

									<div class="col-md-3 recently_appoinment_card">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php echo esc_attr($entry->entry);?>" name="income_entry[]">
												<label class="" for="income_entry"><?php _e('Expense Label','church_mgt');?></label>
											</div>
										</div>
									</div>	
									<?php
									if(!$edit)
									{
										?>
										<div class="col-md-1 income_deopdown_div rtl_margin_top_15px">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Delete.png"?>" alt="" onclick="deleteParentElement(this)" class="massage_image center entypo-trash">
										</div>
										<?php
									}
									?>
								</div>	
							</div>
							<?php 
						}
					}
					else
					{
						?>
						<div id="expense_entry" class="col-md-12">
							<div class="row form-group input">
								<div class="col-md-3 recently_appoinment_card">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="income_amount" class="form-control " type="text" maxlength="10" name="income_amount[]">
											<label class="" for="income_entry"><?php _e('Expense Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-3 recently_appoinment_card">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" type="text" maxlength="50"  name="income_entry[]">
											<label class="" for="income_entry"><?php _e('Expense Label','church_mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>

								<div class="col-md-1 income_deopdown_div rtl_margin_top_15px">
									<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Add new Button.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="daye_name_onclickr" id="add_new_entry">
								</div>
							</div>	
						</div>
						<?php 
					} ?>
						
					<hr class="margin_top_10">
				</div>

				<div class="row">
					<div class="col-md-6 mt-2">
						<?php wp_nonce_field( 'save_expense_nonce' ); ?>
						<div class="offset-sm-0">
							<input type="submit" value="<?php if($edit){ _e('Save Expense','church_mgt'); }else{ _e('Create Expense Entry','church_mgt');}?>" name="save_expense" class="btn btn-success col-md-12 save_btn"/>
						</div>
					</div>	
				</div>
			</div>
		</form><!--Expense FORM END-->
    </div><!--PANEL BODY DIV END-->
	<?php
		}
	?>
    <script>
	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';

   	function add_entry()
   	{
	   $('#expense_entry').append('<div id="expense_entry" class="col-md-12"><div class="row form-group input"><div class="col-md-3 recently_appoinment_card"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_amount" class="form-control validate[required,custom[amount]] text-input" type="text" maxlength="10" name="income_amount[]"><label class="" for="income_entry"><?php _e('Expense Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label></div></div></div><div class="col-md-3 recently_appoinment_card"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" type="text" maxlength="50" name="income_entry[]"><label class="" for="income_entry"><?php _e('Expense Label','church_mgt');?><span class="require-field">*</span></label></div></div></div><div class="col-md-1 income_deopdown_div rtl_margin_top_15px"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Delete.png"?>" alt="" onclick="deleteParentElement(this)" class="massage_image  entypo-trash"></div></div></div>');
   	}
	
	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
</script> 
<?php 
} 
?>