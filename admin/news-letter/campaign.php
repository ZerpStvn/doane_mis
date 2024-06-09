<?php 
$retval = $api->campaigns();
$api->useSecure(true);
$retval1 = $api->lists();
?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#setting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
}); 
</script>
<div class="panel-body"><!--PANEL BODY DIV STRAT-->
	<form name="student_form" action="" method="post" class="" id="setting_form"><!--Campaign FORM STRAT-->
	<div class="form-body user_form mt-4">
		<div class="row">
			<div class="col-md-6 cmgt_display">
				<label class="ml-1 mt-2 custom-top-label" for="quote_form"><?php _e('MailChimp list','church_mgt');?><span class="require-field">*</span></label>

				<select name="list_id" id="quote_form"  class="form-control validate[required]">
					<option value=""><?php _e('Select list','church_mgt');?></option>
					<?php 
					foreach ($retval1['data'] as $list)
					{
						echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
					}
					?>
				</select>
			</div>

			<div class="col-md-6 cmgt_display">
				<label class="ml-1 mt-2 custom-top-label" for="quote_form"><?php _e('Campaign list','church_mgt');?></label>

				<select name="camp_id" id="quote_form"  class="form-control">
					<option value=""><?php _e('Select Campaign','church_mgt');?></option>
					<?php 
					foreach ($retval['data'] as $c)
					{
						echo '<option value="'.$c['id'].'">'.$c['title'].'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mt-2">
				<div class="offset-sm-0">
				<?php
					if($user_access_add == 1)
					{
				?>
					<input type="submit" value="<?php _e('Send Campaign', 'church_mgt' ); ?>" name="send_campign" class="btn btn-success col-md-12 save_btn"/>
					<?php
						}
					?>
				</div>
			</div>	
		</div>
	</div>
	
	</form><!--Campaign FORM END-->
</div><!--PANEL BODYDIV END-->

