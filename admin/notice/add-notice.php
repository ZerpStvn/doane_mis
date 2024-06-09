<?php
$obj_notice=new Cmgtnotice;
?>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#notice_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		
		$(".notice_Start_date").datepicker({
       	dateFormat: "yy-mm-dd",
		minDate:0,
		autoclose: true,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".notice_end_date").datepicker("option", "minDate", dt);
        }
	    });
	    $(".notice_end_date").datepicker({
	      dateFormat: "yy-mm-dd",
		  autoclose: true,
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() - 0);
	            $(".notice_Start_date").datepicker("option", "maxDate", dt);
	        }
	    });	
	});
</script>
<?php  $edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$noticedata = $obj_notice->MJ_cmgt_get_single_notice($_REQUEST['id']);	
	}
	?>
<div class="panel-body"> <!--PANEL BODY DIV START-->
    <form name="class_form" action="" method="post" class="form-horizontal" id="notice_form"><!--NOTICE FORM START-->
        <?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
		<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
		<div class="form-body user_form">
			<div class="row cmgt-addform-detail">
				<p><?php esc_html_e('Notice Information','church_mgt');?></p>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="notice_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo esc_attr($noticedata->notice_title);}?>" name="notice_title">
							<label class="" for="notice_title"><?php _e('Notice Title','church_mgt');?><span class="require-field">*</span></label>
							<input type="hidden" name="id"   value="<?php if($edit){ echo esc_attr($noticedata->id);}?>"/> 
							<input type="hidden" name="status"    value="<?php if($edit){ echo esc_attr($noticedata->status);} else{ print 1; }?>"/> 
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 cmgt_form_description form-control">
							<textarea name="notice_content" class="form-control validate[custom[address_description_validation]]"  maxlength="250" id="notice_content"><?php if($edit){ echo esc_attr($noticedata->notice_content);}?></textarea>
							<label class="" for="notice_content"><?php _e('Notice Comment','church_mgt');?></label>
						</div>
					</div>	
				</div>
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input class="form-control validate[required] notice_Start_date" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime(esc_attr($noticedata->start_date))); }else{ echo date('Y-m-d'); } ?>" name="start_date" autocomplete="off" readonly>
							<label class="" for="notice_content"><?php _e('Notice Start Date','church_mgt');?><span class="require-field">*</span></label>
						</div>	
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input  class="form-control validate[required] notice_end_date" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime(esc_attr($noticedata->end_date))); }else{ echo date('Y-m-d'); }?>" name="end_date" autocomplete="off" readonly>
							<label class="" for="notice_content"><?php _e('Notice End Date','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 mt-2">
					<?php wp_nonce_field( 'save_notice_nonce' ); ?>
					<div class="offset-sm-0">
						<input type="submit" value="<?php if($edit){ _e('Save Notice','church_mgt'); }else{ _e('Add Notice','church_mgt');}?>" name="save_notice" class="btn btn-success col-md-12 save_btn" />
					</div>
				</div>	
			</div>
			
		</div>
    </form><!--NOTICE FORM END-->
</div><!--PANEL BODY DIV START-->
<?php
?>