<?php 
//Upload Student From CSV
?>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('#upload_header_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	});
</script>
    <div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="upload_header_form" action="" method="post" class="form-horizontal" id="upload_header_form" enctype="multipart/form-data">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<div class="form-group">
				<div class="mb-3 row">
					<label class="col-sm-2 control-label form-label" for="city_name"><?php _e('Select CSV file','church_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="csv_file" type="file" style="display: inline;" name="csv_file" class="form-control validate[required]">
					</div>
				</div>	
			</div>
			<div class="offset-sm-2 col-sm-8">
				<input id="upload_csv_headers" type="submit" value="<?php _e('Upload CSV File','church_mgt');?>" name="upload_csv_file" class="btn btn-success"/>
			</div>
		</form>
	</div><!-- PANEL BODY DIV END-->