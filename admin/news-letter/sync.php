<?php 
$api->useSecure(true);
$retval = $api->lists();
?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#setting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
}); 
</script>
<div class="panel-body"><!--PANEL BODY DIV STRAT-->
	<form name="template_form" action="" method="post" class="" id="setting_form"><!--Mailing LIST SYNCRONIZE USER FORM STRAT-->
	<div class="form-body user_form mt-4">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-md-12 form-control">
						<div class="row padding_radio">
							<div class="">
								<label class="custom-top-label margin_left_0" for="enable_quote_tab"><?php _e('Group List','church_mgt');?></label>
								<div class="checkbox checkbox_lebal_padding_8px cmgt_checkbox_befor_color" id="cmgt_sync">
									<?php 
									$groupdata=$obj_group->MJ_cmgt_get_all_groups();
									if(!empty($groupdata))
									{
										foreach ($groupdata as $retrieved_data)
										{?>
											
											<input class="mt-2 form-control cmgt_volunteer_bg top1" type="checkbox" name="syncmail[]" value="<?php echo $retrieved_data->id?>"/><span class="demographics_text otehrservice"><?php echo $retrieved_data->group_name; ?></span>
											
										<?php
										}
									}
									else
									{
										_e('No Group','church_mgt');
									}?>

								</div>

							</div>												
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 cmgt_display">
				<label class="ml-1 mt-2 custom-top-label" for="list_id"><?php _e('Mailing list','church_mgt');?><span class="require-field">*</span></label>

				<select name="list_id" id="list_id"  class="form-control validate[required]">
					<option value=""><?php _e('Select list','church_mgt');?></option>
					<?php 
					foreach ($retval['data'] as $list){
						
						echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
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
					<input type="submit" value="<?php _e('Sync Mail', 'church_mgt' ); ?>" name="sychroniz_email" class="btn btn-success col-md-12 save_btn"/>
					<?php
				}
				?>
				</div>
			</div>	
		</div>

	</div>


	    <!-- <div class="form-group">
			<div class="mb-3 row">	
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="enable_quote_tab"><?php _e('Group List','church_mgt');?></label>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<div class="checkbox">
						<?php 
						$groupdata=$obj_group->MJ_cmgt_get_all_groups();
						if(!empty($groupdata))
						{
							foreach ($groupdata as $retrieved_data)
							{?>
								<label>
								<input class="mt-2" type="checkbox" name="syncmail[]"  value="<?php echo $retrieved_data->id?>"/><span class="otehrservice"><?php echo $retrieved_data->group_name;?></span>
								</label><br/>
							<?php
							}
						}
						else
						{
							_e('No Group','church_mgt');
						}?>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="mb-3 row">	
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="list_id"><?php _e('Mailing list','church_mgt');?></label>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<select name="list_id" id="list_id"  class="form-control">
						<option value=""><?php _e('Select list','church_mgt');?></option>
						<?php 
						foreach ($retval['data'] as $list){
							
							echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="offset-sm-2 col-lg-8 col-md-8 col-sm-8 col-xs-12">        	
        	<input type="submit" value="<?php _e('Sync Mail', 'church_mgt' ); ?>" name="sychroniz_email" class="btn btn-success"/>
        </div> -->


	</form><!--Mailing LIST SYNCRONIZE USER FORM END-->
</div><!--PANEL BODY DIV END-->