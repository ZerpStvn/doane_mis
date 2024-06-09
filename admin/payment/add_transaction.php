<?php
$obj_payment=new Cmgtpayment;
?>
<style>
	.open>.dropdown-menu
	{
	   top: auto;
	   bottom: 100%;
	   width: 259px;
	   height: 200px;
	   overflow: auto;
	   padding: 0px;
	} 
</style>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#transaction_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
    $('.ministry_id').multiselect(
	{
			nonSelectedText :'<?php _e('Select Ministry','church_mgt');?>',
			selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
			allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			filterPlaceholder: '<?php _e('Search for ministry...','church_mgt');?>',
			templates: {
            button: '<button type="button" class="multiselect btn btn-default dropdown-toggle" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
			buttonContainer: '<div class="dropdown" />'
	});
		
	$('.group_id').multiselect(
	{
			nonSelectedText :'<?php _e('Select Group','church_mgt');?>',
			selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
			allSelectedText :'<?php esc_html_e('All Selected','church_mgt'); ?>',
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			filterPlaceholder: '<?php _e('Search for group...','church_mgt');?>',
			templates: {
            button: '<button type="button" class="multiselect btn btn-default dropdown-toggle" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
	},
			buttonContainer: '<div class="dropdown" />'
	});
	
	$(".display-members").select2();
	
	/*var date = new Date();
    date.setDate(date.getDate()-0);
    $('#transaction_date').datepicker(
	{ 
     startDate: date,
     autoclose: true
	});*/
	jQuery('#transaction_date').datepicker({
		dateFormat: "yy-mm-dd",
		//minDate:'today',
		changeMonth: true,
        changeYear: true,
		autoclose: true,
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
	
	jQuery('.date').datepicker({
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
	//username not  allow space validation//
	$('.username').keypress(function( e ) {
       if(e.which === 32) 
        return false;
    });
	 
	jQuery('.birth_date').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate : 0,
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
	 
	$('#add_ministary_popup_active').hide();
	$('#add_group_popup_active').hide();
	$('#ministary_label').hide();
	$('#group_label').hide();
	
		
    $("body").on("click", '#add_ministary_popup', function()
	{	
		$('#add_ministary_popup_active').show();
		$('#add_member_popup_active').hide();
		$('#add_group_popup_active').hide();
		$('#add_ministary_tab').addClass("nav-tab-active");
		$('#add_member_tab').removeClass("nav-tab-active");
		$('#add_group_tab').removeClass("nav-tab-active");
		$('#ministary_label').show();
		$('#member_label').hide();  
		$('#group_label').hide();  
    });  
			 
			 
	$("body").on("click", '#myModal_Add_group_id', function()
	{	
	   $('#add_group_popup_active').show();
	   $('#add_member_popup_active').hide();
	   $('#add_ministary_popup_active').hide();
	   $('#add_group_tab').addClass("nav-tab-active");
	   $('#add_member_tab').removeClass("nav-tab-active");
	   $('#add_ministary_tab').removeClass("nav-tab-active");
	   $('#group_label').show();
	   $('#member_label').hide();  
	   $('#ministary_label').hide();  
	}); 	 
			 
	$('#add_ministary_tab').click(function()
	{
		$('#add_ministary_tab').addClass("nav-tab-active");
		$('#add_ministary_popup_active').show();
		$('#add_member_popup_active').hide();
		$('#add_group_popup_active').hide();
		$('#add_member_tab').removeClass("nav-tab-active");
		$('#add_group_tab').removeClass("nav-tab-active");
		$('#ministary_label').show();
		$('#member_label').hide(); 
		$('#group_label').hide(); 
    });
		  
	$('#add_member_tab').click(function()
	 {
		$('#add_ministary_tab').removeClass("nav-tab-active");
		$('#add_group_tab').removeClass("nav-tab-active");
		$('#add_ministary_popup_active').hide();
		$('#add_group_popup_active').hide();
		$('#add_member_tab').addClass("nav-tab-active");
		$('#add_member_popup_active').show();
		$('#ministary_label').hide();
		$('#group_label').hide();
		$('#member_label').show();
	});
	   
    $('#add_group_tab').click(function()
    {
		$('#add_group_tab').addClass("nav-tab-active");
		$('#add_group_popup_active').show();
		$('#add_ministary_popup_active').hide();
		$('#add_member_popup_active').hide();
		$('#add_member_tab').removeClass("nav-tab-active");
		$('#add_ministary_tab').removeClass("nav-tab-active");
		$('#group_label').show();
		$('#member_label').hide(); 
		$('#ministary_label').hide(); 
	});
	//add member poupup ajax
	$('#member_form').on('submit', function(e) {
	  e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#member_form').validationEngine('validate');
		if (valid == true) {
		$.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			success: function(data)
			 {
				var json_obj = $.parseJSON(data);
				$('#member_list').append(json_obj[0]);
				$('.upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'cmgt_system_logo' ); ?>">');
				$('.cmgt_user_avatar_url').val('');
				$('#member_form').trigger("reset");
				$('.modal').modal('hide');
			},
			error: function(data){
			}
		})
		}
	});
	
	
	//add ministary popup
	$('#ministary_form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#ministary_form').validationEngine('validate');
		var ministry_id = $('.ministry_id').multiselect(); 
		if (valid == true) {
		$.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			success: function(data)
			{
				$('.ministry_id').append(data);
				ministry_id.multiselect('rebuild');
				$('#ministary_form').trigger("reset");
				$('.upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'cmgt_system_logo' ); ?>">');
				$('.cmgt_user_avatar_url').val('');	
				$('#add_member_tab').addClass("nav-tab-active")
				$('#add_ministary_tab').removeClass("nav-tab-active");
				$('#add_ministary_popup_active').hide();
				$('#add_member_popup_active').show();
					
			},
			error: function(data){
				
			}
		})
		}
	});
	
	$('#group_form').on('submit', function(e) {
		
		e.preventDefault();
		var form = $(this).serialize(); 
		
		var valid = $('#group_form').validationEngine('validate');
		var group_id = $('.group_id').multiselect(); 
		if (valid == true) {
		$.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			success: function(data)
			{
				 if(data!=""){ 
				 
					$('#group_form').trigger("reset");
					$('.group_id').append(data);
					group_id.multiselect('rebuild');
					$('#add_group_popup_active').hide();
					$('#add_member_popup_active').show();
					$('#add_member_tab').addClass("nav-tab-active")
					$('#add_group_tab').removeClass("nav-tab-active");
                    $('.upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'cmgt_system_logo' ); ?>">');
					$('.cmgt_user_avatar_url').val('');	
				} 
			},
			error: function(data){
				
			}
		})
		}
	});
	
	//add group poupup ajax
	
	$('#group_form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#group_form').validationEngine('validate');
		var group = $('#group_id').multiselect(); 
		if (valid == true) {
		$.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			success: function(data)
			{
				$('#group_id').append(data);
				group.multiselect('rebuild');
				$('#group_form').trigger("reset");
				$('.upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'cmgt_system_logo' ); ?>">');
				$('.cmgt_user_avatar_url').val('');	
				$('#add_member_tab').addClass("nav-tab-active")
				$('#add_group_tab').removeClass("nav-tab-active");
				$('#add_group_popup_active').hide();
				$('#add_member_popup_active').show();
			},
			error: function(data){
			}
		})
		}
	});
	//---member validation-----//
	$("#save_transaction").click(function() 
	{
		var ext = $('#member_list').val();
		if(ext =='' || ext == null)
		{
			alert("<?php _e('Please fill out all the required fields','church_mgt');?>");
			return false;	
		} 
	});
	
});
</script>
<?php 	
	if($active_tab == 'addtransaction')
	{
		$transaction_id=0;
		if(isset($_REQUEST['transaction_id']))
			$transaction_id= sanitize_text_field($_REQUEST['transaction_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_transaction->MJ_cmgt_get_single_transaction($transaction_id);
			}
			if(MJ_cmgt_add_check_access_for_view_add('payment','add'))
				{ 
			?>
		<div class="panel-body"><!--PANEL BODY DIV START-->
			<form name="transaction_form" action="" method="post" class="" id="transaction_form"><!-- DONETION FORM START-->
				<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="transaction_id" value="<?php echo esc_attr($transaction_id);?>" />
				<input type="hidden" name="invoice_number" value="<?php echo esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($transaction_id)); ?>">
				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Transaction Information','church_mgt');?></p>
					</div>
					<div class="row">
						<div class="col-md-6 cmgt_display input">
							<div class="form-group input row margin_buttom_0">
								<div class="col-md-8">
									<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?></label>	

									<select class="form-control line_height_30px" name="member_id" id="member_list">
										<option value=""><?php _e('Select Member','church_mgt');?></option>
										<?php
										if($edit)
										{
											$member_id=$result->member_id;
										}
										elseif(isset($_REQUEST['member_id'])) 
										{
											$member_id=$_REQUEST['member_id'];
										}
										else
										{ 
											$member_id="";
										}
											
										
										$get_members = array('role' => 'member');
											$membersdata=get_users($get_members);
										if(!empty($membersdata))
										{
											foreach ($membersdata as $member)
											{
												if(empty($member->cmgt_hash)){
												?>
												<option value="<?php echo esc_attr($member->ID);?>" <?php selected($member_id,$member->ID);?>><?php echo esc_attr($member->display_name)." - ".esc_attr($member->member_id); ?> </option>
												<?php
												}
											}
										}?>
									</select>
								</div>
								<!--ADD Member POPUP BUTTON -->
								<div class="col-sm-4">
									<button type="button" class="btn btn-success width_100 btn_height" data-bs-toggle="modal" data-bs-target="#myModal_add_member"> <?php _e('Add Member','church_mgt');?></button>
								</div>
							</div>
						</div>

						<div class="col-md-6 cmgt_display input">
							<div class="form-group input row margin_buttom_0">
								<div class="col-md-8">
									<label class="ml-1 custom-top-label top" for="Donations"><?php _e('Donation Type','church_mgt');?><span class="require-field">*</span></label>
									<select class="form-control line_height_30px validate[required]" name="donetion_type" id="donation_category">
										<option value=""><?php _e('Select Donations Type','church_mgt');?></option>
										<?php 
										if($edit)
											$category = sanitize_text_field($result->donetion_type);
										elseif(isset($_REQUEST['donetion_type']))
											$category = sanitize_text_field($_REQUEST['donetion_type']);  
										else 
											$category = "";
										
										$donation_category=MJ_cmgt_get_all_category('donation_category');
										if(!empty($donation_category))
										{
											foreach ($donation_category as $retrive_data)
											{
												echo '<option value="'.esc_attr($retrive_data->ID).'" '.selected($category,$retrive_data->ID).'>'.esc_attr($retrive_data->post_title).'</option>';
											}
										}?>
									</select>
								</div>
								<div class="col-md-4">
									<button class="btn btn-success width_100 btn_height" id="addremove" model="donation_category"><?php _e('Add Or Remove','church_mgt');?></button>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="transaction_date" class="form-control validate[required]" type="text" name="transaction_date" 
									value="<?php if($edit){ echo date("Y-m-d",strtotime($result->transaction_date));}elseif(isset($_POST['transaction_date'])){ echo esc_attr($_POST['transaction_date']);}else{ echo date('Y-m-d');}?>" autocomplete="off" readonly>
									<label class="" for="checkin_date"><?php _e('Transaction Date','church_mgt');?><span class="require-field">*</span></label>
								</div>	
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group input">
							<div class="col-md-12 form-control">
									<input id="amount" class="form-control validate[required,min[0],maxSize[8]] text-input" step="0.01" maxlength="8" type="text"  name="amount" 
									<?php if($edit){ ?>value="<?php echo esc_attr($result->amount);}elseif(isset($_POST['amount'])) echo esc_attr($_POST['amount']);?>">
									<label class="" for="amount"><?php _e('Amount','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 input cmgt_display">
							<label class="ml-1 custom-top-label top" for="reservation_date"><?php _e('Method','church_mgt');?><span class="require-field">*</span></label>
								<?php if($edit)
									$period= sanitize_text_field($result->pay_method);
							 		 elseif(isset($_POST['pay_method'])) 
									$period= sanitize_text_field($_POST['pay_method']);
							 		else
								 	$period=''; 
								?>
							<select class="form-control" name="pay_method" id="pay_method">
								<option value="cash" <?php selected($period,'cash');?>><?php _e('Cash','church_mgt');?></option>
								<option value="check" <?php selected($period,'check');?>><?php _e('Check','church_mgt');?></option>
								<option value="bank_transfer" <?php selected($period,'bank_transfer');?>><?php _e('Bank Transfer','church_mgt');?></option>
							</select>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 cmgt_form_description form-control">
									<textarea name="description" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="description"><?php if($edit){ echo esc_textarea($result->description);}?></textarea>
									<label class="" for="description"><?php _e('Comment','church_mgt');?></label>
								</div>
							</div>	
						</div>
					</div>
					<div class="row">
						<?php wp_nonce_field( 'save_transaction_nonce' ); ?>
						<div class="col-md-6 mt-2">
							<input id="save_transaction" type="submit" value="<?php if($edit){ _e('Save Transaction','church_mgt'); }else{ _e('Add Transaction','church_mgt');}?>" name="save_transaction" class="btn btn-success col-md-12 save_btn"/>
						</div>
					</div>
				</div>
			</form><!-- DONETION FORM END-->
		</div><!--PANEL BODY DIV END-->
     <?php 
				}
	}
	 ?>
	 <!----------ADD  Member POPUP------------->
	<div class="modal fade cmgt_main_modal" id="myModal_add_member" role="dialog" tabindex="-1" aria-labelledby="myModal_add_member" aria-hidden="true"><!-- MAIN MODAL DIV START-->
		<div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START-->
			<div class="modal-content"><!-- MODAL CONTENT DIV START-->
				<div class="modal-header">
					<h3 id="member_label" class="modal-title"><?php _e('Add Member','church_mgt');?><a href="#" class="btn float-end mt-2 badge badge-danger rtl_float_left" data-bs-dismiss="modal">X</a></h3>
					<h3 id="ministary_label" class="modal-title"><?php _e('Add Ministry','church_mgt');?><a href="#" class="btn float-end mt-2 badge badge-danger rtl_float_left" data-bs-dismiss="modal">X</a></h3>
					<h3 id="group_label" class="modal-title"><?php _e('Add Group','church_mgt');?><a href="#" class="btn float-end mt-2 badge badge-danger rtl_float_left	" data-bs-dismiss="modal">X</a></h3>
				</div>
				<div class="modal-body"><!-- MODAL BODY DIV START-->
							<h2 class="nav-tab-wrapper" id="h2_tab_mt_0" style="position: relative; top: 1px;">
								<a class="nav-tab nav-tab-active" id="add_member_tab">
								<?php echo esc_html_e('Add Member', 'church_mgt'); ?></a>
								<a class="nav-tab" id="add_ministary_tab" >
								<?php echo esc_html_e('Add Ministry', 'church_mgt'); ?></a> 
								<a class="nav-tab" id="add_group_tab" >
								<?php echo esc_html_e('Add Group', 'church_mgt'); ?></a>		
							</h2>


							<div id="add_member_popup_active">
								<?php 
									$role='member';
									$lastmember_id=MJ_cmgt_get_lastmember_id($role);
									$nodate=substr($lastmember_id,0,-4);
									$memberno=substr($nodate,1);
									$memberno+=1;
									$newmember='M'.$memberno.date("my");
								?> 
								<div class="panel-body">	
									<form name="member_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="" id="member_form">
										<input type="hidden" name="role" value="member" />
										<input type="hidden" name="action" value="MJ_cmgt_add_member_popup">
										<div class="form-body user_form"> <!--Card Body div-->   
											<div class="row cmgt-addform-detail">
												<p><?php esc_html_e('Personal Information','church_mgt');?></p>
											</div>
											<div class="row"><!--Row Div--> 
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[required]" type="text" value="<?php echo $newmember;?>" readonly  name="member_id">
															<label for="member_id"><?php esc_html_e('Member Id','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text" maxlength="50"  name="first_name">
															<label for="first_name"><?php esc_html_e('First Name','church_mgt');?><span class="require-field">*</span></label>
														</div>	
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50"  name="middle_name">
															<label for="middle_name"><?php esc_html_e('Middle Name','church_mgt');?></label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text" maxlength="50"  name="last_name">
															<label for="last_name"><?php esc_html_e('Last Name','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-md-6 rtl_margin_top_15px">
													<div class="form-group">
														<div class="col-md-12 form-control">
															<div class="skin skin-flat row">
																<div class="input-group">
																	<label class="custom-control-label custom-top-label ml-2" for="gender"><?php esc_html_e('Gender','church_mgt');?><span class="require-field">*</span></label>
																	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
																		<?php $genderval = "male" ?>
																		<label class="radio-inline">
																		<input type="radio" value="male" class="tog" style="margin-top: 1px;" name="gender"  <?php  checked( 'male', $genderval);  ?>/><span class="rediospan" style="margin-left:5px;"><?php esc_html_e('Male','church_mgt');?></span>
																		</label>
																		<label class="radio-inline" style="margin-left: 10px;margin-bottom: 0;">
																		<input type="radio" value="female" class="tog" style="margin-top: 1px;" name="gender"  <?php  checked( 'female', $genderval);  ?>/><span class="rediospan" style="margin-left:5px;"> <?php esc_html_e('Female','church_mgt');?> </span>
																		</label>
																	</div>
																</div>
															</div>		
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input id="birth_date" class="form-control validate[required] birth_date" type="text" name="birth_date" autocomplete="off" readonly>
															<label for="birth_date"><?php esc_html_e('Date of Birth','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-md-6 rtl_margin_top_15px">
													<div class="form-group">
														<div class="col-md-12 form-control">
															<div class="skin skin-flat row">
																<div class="input-group">
																	<label class="control-label form-label custom-top-label ml-2" for="marital-status"><?php esc_html_e('Marital Status','church_mgt');?><span class="require-field">*</span></label>
																	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
																		<?php $marital_val = "unmarried"; ?>
																		<label class="radio-inline">
																		<input type="radio" value="unmarried" class="tog" style="margin-top: 1px;" name="marital_status"  <?php  checked( 'unmarried', $marital_val);  ?>/><span class="rediospan" style="margin-left:5px;"><?php esc_html_e('Unmarried','church_mgt');?></span>
																		</label>
																		<label class="radio-inline">
																		<input type="radio" value="married" class="tog" style="margin-top: 1px;" name="marital_status"  <?php  checked( 'married', $marital_val);  ?>/><span class="rediospan" style="margin-left:5px;"><?php esc_html_e('Married','church_mgt');?> </span>
																		</label>
																	</div>
																</div>
															</div>		
														</div>
													</div>
												</div>
												<div class="col-md-4 input medicine_select_btn">
												<?php 
													$obj_member=new Cmgtmember;
													$obj_ministry=new Cmgtministry;
													$member_id=0;
													$joinminisrty_list = $obj_member->MJ_cmgt_get_all_joinministry($member_id);
													$ministry_array = $obj_member->MJ_cmgt_convert_grouparray($joinminisrty_list);?>
													<select  class="form-control ministry_id" id="ministry_id" name="ministry_id[]" multiple="multiple">					
														<?php 
															$ministrydata=$obj_ministry->MJ_cmgt_get_all_ministry();
															if(!empty($ministrydata))
															{
																foreach ($ministrydata as $ministry)
																{?>
																<option value="<?php echo $ministry->id;?>" <?php if(in_array($ministry->id,$ministry_array)) echo 'selected';  ?>><?php echo $ministry->ministry_name; ?></option>
																<?php 
																} 
															} ?>
													</select>	
												</div>
												<div class="col-md-2 rtl_margin_top_15px margin_bottom_15px">
													<a href="#" class="btn btn-success cmgt_addfrom_btn" id="add_ministary_popup" data-toggle="modal" data-target="#myModal_Add_Ministary"> <?php _e('ADD','church_mgt');?></a>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input class="form-control validate[custom[onlyLetterAccentSp]]" type="text" maxlength="50"  value="<?php if(isset($_POST['occupation'])){ echo esc_attr($_POST['occupation']);}?>" name="occupation">
															<label for="occupation"><?php esc_html_e('Occupation','church_mgt');?></label>
														</div>
													</div>
												</div>
												<div class="col-md-4 input medicine_select_btn">
													<?php 
													$member_id=0;
													$obj_group=new Cmgtgroup;
													$obj_member=new Cmgtmember;
													$joingroup_list = $obj_member->MJ_cmgt_get_all_joingroup($member_id);
													$groups_array = $obj_member->MJ_cmgt_convert_grouparray($joingroup_list);
													
													?>
													<select  class="form-control group_id" id="group_id" name="group_id[]" multiple="multiple">					
															<?php $groupdata=$obj_group->MJ_cmgt_get_all_groups();
															if(!empty($groupdata))
															{
																foreach ($groupdata as $group)
																{?>
																	<option value="<?php echo $group->id;?>" <?php if(in_array($group->id,$groups_array)) echo 'selected';  ?>><?php echo $group->group_name; ?></option>
																	<?php   
																} 
															} 
															?>
													</select>	
												</div>
												<div class="col-md-2 rtl_margin_top_15px margin_bottom_15px">
													<a href="#" class="btn btn-success cmgt_addfrom_btn" id="myModal_Add_group_id" data-toggle="modal" data-target="#myModal_Add_group"> <?php _e('ADD','church_mgt');?></a>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[custom[address_description_validation]]" type="text" maxlength="50"  name="education">
															<label for="education"><?php esc_html_e('Education','church_mgt');?></label>
														</div>
													</div>
												</div>
											</div>

											<div class="row cmgt-addform-detail">
												<p><?php esc_html_e('Contact Information','church_mgt');?></p>
											</div>

											<div class="row"><!--Row Div--> 
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[required,cusom[address_description_validation]]" maxlength="150" type="text"  name="address">
															<label for="address"><?php esc_html_e('Address','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name">
															<label for="city_name"><?php esc_html_e('City','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-4 col-lg-6">
															<div class="form-group input">
																<div class="col-md-12 form-control">
																	<input type="text" disabled value="+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' ));?>" class="form-control pl-4 mobile validate[required] onlynumber_and_plussign" maxlength="5" name="phonecode">
																	<label for="country_code" class="pl-2"><?php esc_html_e('Country Code','church_mgt');?><span class="required red">*</span></label>
																	<div class="pos_mobile  form-control-position nf_left_icon">
																		<i class="ft-plus"></i>
																	</div>
																</div>											
															</div>
														</div>
														<div class="col-md-8 col-lg-6">
															<div class="form-group input">
																<div class="col-md-12 form-control">
																	<input  class="form-control validate[required,custom[phone]] text-input" type="text" minlength="6" maxlength="15"  name="mobile">
																	<label for="mobile"><?php esc_html_e('Mobile Number','church_mgt');?><span class="require-field">*</span></label>
																</div>
															</div>
														</div>
													</div>
												</div> 
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[custom[phone]] text-input"  minlength="6" maxlength="15" type="text" name="phone">
															<label for="phone"><?php esc_html_e('Phone','church_mgt');?></label>
														</div>
													</div>
												</div>
												
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[custom[phone_number]]" maxlength="30" type="text" name="fax_number">
															<label for="fax_number"><?php esc_html_e('Fax','church_mgt');?></label>
														</div>
													</div>	
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[custom[username_validation]]" maxlength="50" type="text"  name="skyp_id">
															<label for="skype_id"><?php esc_html_e('Skype Id','church_mgt');?></label>
														</div>
													</div>	
												</div>
											</div>
											<div class="row cmgt-addform-detail">
												<p><?php esc_html_e('Religion Information','church_mgt');?></p>
											</div>
											<div class="row"><!--Row Div--> 
												<div class="col-md-6 rtl_mb_15">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[required] date" type="text" name="begin_date" autocomplete="off" readonly>
															<label for="begin_date"><?php esc_html_e('Join Church Date','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>	
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control validate[required] date" type="text" name="baptist_date" autocomplete="off" readonly>
															<label for="baptist_date"><?php esc_html_e('Baptist Date','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>	
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<div class="col-md-12 form-control input_height_48px">
															<div class="row padding_radio">
																<div class="input-group">
																	<label class="custom-top-label margin_left_0" for="Volunteer"><?php esc_html_e('Volunteer','church_mgt');?></label>		
																	<div class="checkbox checkbox_lebal_padding_8px rtl_add_member">
																		<label>
																			<input class="form-control cmgt_volunteer_bg mt-2" type="checkbox" name="volunteer" value="yes"><label class="mt-2" for="Volunteer"><?php esc_html_e('Volunteer','church_mgt');?></label>	
																			<!-- <?php 
																			if($volunteer=='yes')
																			{
																				echo "Yes";
																			}else{
																				echo "No";
																			} ?> -->
																		</label>
																	</div>
																</div>												
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row cmgt-addform-detail mt-3">
												<p><?php esc_html_e('Login Information','church_mgt');?></p>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group input">
														<div class="col-md-12 form-control">
															<input  class="form-control validate[required,custom[email]]" maxlength="100" type="text" name="email" >
															<label for="email"><?php esc_html_e('Email','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input class="form-control validate[required,custom[username_validation]] username" type="text" maxlength="50"  name="username" <?php if($edit) echo "readonly";?>>
															<label for="username"><?php esc_html_e('User Name','church_mgt');?><span class="require-field">*</span></label>
														</div>
													</div>	
												</div>
												<div class="col-md-6">
													<div class="form-group input">
													<div class="col-md-12 form-control">
															<input  class="form-control <?php if(!$edit) echo 'validate[required,minSize[8]]';?>"  maxlength="12" type="password"  name="password">
															<label for="password"><?php esc_html_e('Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
														</div>
													</div>	
												</div>
											</div>
											<div class="row cmgt-addform-detail">
												<p><?php _e('Profile Image','church_mgt');?></p>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group input">
														<div class="col-md-12 form-control upload-profile-image-patient">	
															<label for="gmgt_membershipimage" class="custom-control-label custom-top-label ml-2"><?php _e('Upload Profile Image','church_mgt');?></label>
															<div class="col-sm-12">
																<input type="hidden" id="cmgt_user_avatar_url" class="form-control" name="cmgt_user_avatar"  
																readonly />
																<input id="upload_user_avatar_button" type="button" class="btn btn-success" style="float: right;" value="<?php esc_html_e( 'Upload image', 'church_mgt' ); ?>" />
															</div>
														</div>
														<div class="clearfix"></div>
														<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
															<div id="upload_user_avatar_preview">
																<img class="image_preview_css" alt="" src="<?php echo esc_url(get_option( 'cmgt_member_thumb' )); ?>">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<?php wp_nonce_field( 'save_member_nonce' ); ?>
												<div class="offset-sm-0">
													<input type="submit" value="<?php _e('Save Member','church_mgt');?>" name="save_member" class="btn btn-success col-md-12 save_btn"/>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
					<!-- start Ministary -->
				<div id="add_ministary_popup_active">
					<div class="panel-body">	
						<form name="ministary_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="" id="ministary_form">
							<input type="hidden" name="action" value="MJ_cmgt_add_ministry_popup">
							<div class="form-body user_form"> <!--Card Body div--> 
								<div class="row cmgt-addform-detail">
									<p><?php esc_html_e('Ministry Information','church_mgt');?></p>
								</div>                      
								<div class="row ">								
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input type="text" maxlength="50" id="ministry_name"  class="form-control validate[required,custom[popup_category_validation]]" name="ministry_name" value="<?php if(isset($_POST['ministry_name'])){ echo esc_attr($_POST['ministry_name']); }?>">
												<label for="ministry_name"><?php _e('Ministry Name','church_mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input">
											<div class="col-md-12 form-control upload-profile-image-patient">	
												<label for="gmgt_membershipimage" class="custom-control-label custom-top-label ml-2"><?php _e('Ministry Image','church_mgt');?></label>
												<button id="upload_user_avatar_button" class="browse btn btn-success for_btn_grp1 community_button_disabled upload_user_cover_button button_top upload-profile-image-patient" data-toggle="modal" data-target="#image_upload" type="button"><?php _e('Choose Image','church_mgt');?></button>
												<input type="hidden" id="cmgt_user_avatar_url" name="cmgt_ministryimage" value="<?php if($edit){ echo esc_attr($result->ministry_image);}elseif(isset($_POST['cmgt_ministryimage'])) echo esc_attr($_POST['cmgt_ministryimage']);?>" onchange="fileCheck(this);">
											</div>
											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
												<div id="upload_user_avatar_preview" style="min-height: 100px;">
													<img class="image_preview_css" style="max-width:100%;" 
														src="<?php if(isset($_POST['cmgt_ministryimage'])) echo $_POST['cmgt_ministryimage']; else echo get_option( 'cmgt_ministry_logo' );?>" />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<?php wp_nonce_field( 'save_ministry_nonce' ); ?>
										<div class="offset-sm-0">
											<input type="submit" value="<?php _e('Save','church_mgt'); ?>" name="save_ministry" class="btn btn-success  col-md-12 save_btn"/>
										</div>
									</div>
								</div>	
							</div>

						</form>
					</div>
				</div>
				<!-- end ministary -->
		 
				<!-- start Group -->
				<div id="add_group_popup_active">
					<div class="panel-body">	
						<form name="group_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="" id="group_form" enctype="multipart/form-data">
							<input type="hidden" name="action" value="MJ_cmgt_add_group_popup">
							<div class="form-body user_form"> <!--Card Body div-->  
								<div class="row cmgt-addform-detail">
									<p><?php esc_html_e('Group Information','church_mgt');?></p>
								</div>                
								<div class="row"><!--Row Div--> 
									<div class="col-md-6">
										<div class="form-group input">
										<div class="col-md-12 form-control">
												<input type="text" maxlength="50" id="group_name"  class="form-control validate[required,custom[popup_category_validation]]" name="group_name" value="<?php if(isset($_POST['group_name'])){ echo esc_attr($_POST['group_name']);}?>">
												<label for="group_name"><?php _e('Group Name','church_mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group input">
											<div class="col-md-12 form-control upload-profile-image-patient">	
												<label for="gmgt_membershipimage" class="custom-control-label custom-top-label ml-2"><?php _e('Group Image','church_mgt');?></label>
												<button id="upload_user_avatar_button" class="browse btn btn-success for_btn_grp1 community_button_disabled upload_user_cover_button upload-profile-image-patient" data-toggle="modal" data-target="#image_upload" type="button"><?php _e('Choose Image','church_mgt');?></button>
												<input type="hidden" id="cmgt_user_avatar_url" name="cmgt_groupimage" onchange="fileCheck(this);" value="<?php if($edit){ echo esc_attr($result->cmgt_groupimage);}elseif(isset($_POST['cmgt_groupimage'])) echo esc_attr($_POST['cmgt_groupimage']);?>">
											</div>
											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
												<div id="upload_user_avatar_preview" style="min-height: 100px;">
													<img class="image_preview_css mt-3" style="max-width:100%;" src="<?php if(isset($_POST['cmgt_groupimage'])) echo $_POST['cmgt_groupimage']; else echo get_option( 'cmgt_group_logo' );?>" />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<?php wp_nonce_field('save_group_nonce' ); ?>
										<div class="offset-sm-0">
											<input type="submit" id="submit"  value="<?php if($edit){ _e('Save Group','church_mgt'); }else{ _e('Save Group','church_mgt');}?>" name="save_group" class="btn btn-success col-md-12 save_btn"/>
										</div>
									</div>
								</div>	
							</div>
						</form>
					</div>
				</div>
				<!-- end  group popup -->
				</div><!-- MODAL BODY DIV END-->
			</div><!-- MODAL CONTENT DIV END-->
		</div><!-- MODAL DIALOG DIV END-->
	</div><!-- MAIN MODAL DIV END-->