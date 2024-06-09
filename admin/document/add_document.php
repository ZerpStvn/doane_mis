<?php ?>
<script>
	$(document).ready(function ()
	{
		$('#document_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#document_admin').change(function () 
		{
			var val = $(this).val().toLowerCase();
			var regex = new RegExp("(.*?)\.(docx|doc|pdf|xml|bmp|ppt|xls|png|jpg)$");
			 if(!(regex.test(val)))
			{
				$(this).val('');
				alert("<?php _e('Only Document , PDF , PPT and XML File Allowed.','church_mgt');?>");
			} 
		}); 
	});
</script> 
<?php 	
$obj_document=new cmgt_document;
if($active_tab == 'add_document')
	{
		$edit=0;
		//-------- EDIT DOCUMENTS -----------//
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_document->MJ_cmgt_get_single_document($_REQUEST['document_id']);
		}
		?>
        <div class="panel-body"><!-- PANEL BODY DIV START-->
            <form name="document_form" action="" method="post" class="form-horizontal" id="document_form" enctype="multipart/form-data"><!-- DOCUMENTS FORM START-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="document_id" value="<?php if(isset($_REQUEST['document_id'])) echo $_REQUEST['document_id'];?>" />

				<div class="form-body user_form">
					<div class="row cmgt-addform-detail">
						<p><?php esc_html_e('Document Information','church_mgt');?></p>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="Document_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text"  <?php if($edit){  ?>value="<?php echo esc_attr($result->document_name);}elseif(isset($_POST['document_name'])) echo esc_attr($_POST['document_name']);?>" name="document_name">
									<label class="" for="Document_name"><?php _e('Document Name','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input cmgt_document_list">
								<div class="col-md-12 form-control">
									<label class="custom-control-label custom-top-label ml-2 margin_left_30px" for="document"><?php _e('Document','church_mgt');?> <span class="require-field">*</span></label>
									<div class="col-sm-12">
										<input type="hidden" name="edit_document" value="<?php if($edit) echo esc_attr($result->document); else echo "";?>">
										<div class="row">
											<?php 				
											if($edit)
											{ 
												?>
												<div class="col-sm-8">
													<input type="file" class="form-control file"  name="document" id="document_admin">
												</div>
												<?php 
												if(trim($result->document) != "")
												{
													?>
													<div class="col-sm-4">
														<?php
														echo '<a target="blank" href="'.$result->document.'" class="btn btn-default cmgt_view_document_color"><i class="fa fa-eye"></i> '.esc_html__("View","church_mgt").' </a>';
														?>
													</div>
													<?php
												}
												else
												{
													echo "No any Document";
												}
											}
											else
											{
												?>
												<div class="col-sm-12">
													<input type="file" class="form-control file validate[required]" name="document" id="document_admin">
												</div>
												<?php 
											}
												?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 cmgt_form_description form-control">
									<textarea name="document_description" class="form-control validate[required]" maxlength="250" id="document_description"><?php if($edit)echo esc_attr($result->description); elseif(isset($_POST['diagno_description'])) echo esc_attr($_REQUEST['diagno_description']); else echo "";?></textarea>
									<label class="" for="description"><?php _e('Description','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>	
						</div>
					</div>
					<div class="row">
						<?php wp_nonce_field( 'save_document_nonce' ); ?>
						<div class="col-md-6 mt-2">
							<input type="submit" value="<?php if($edit){ _e('Save Document','church_mgt'); }else{ _e('Add Document','church_mgt');}?>" name="save_document" class="btn btn-success col-md-12 save_btn"/>
						</div>
					</div>
				</div>
		    </form><!-- DOCUMENTS FORM END-->
        </div><!-- PANEL BODY DIV START-->
     <?php 
	}
	 ?>