<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
MJ_cmgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=MJ_cmgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_cmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
$curr_user_id=get_current_user_id();
$obj_church = new Church_management(get_current_user_id());
$obj_sermon=new Cmgtsermon;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'sermonlist');
$volunteer=MJ_cmgt_check_volunteer($curr_user_id);
//------------- SAVA SERMON DATA --------------//
if(isset($_POST['save_sermon']))
	{
		$nonce = sanitize_text_field($_POST['_wpnonce']);
		if (wp_verify_nonce( $nonce, 'save_sermon_nonce' ) )
		{
		if(isset($_FILES['upload_file']) && !empty($_FILES['upload_file']) && $_FILES['upload_file']['size'] !=0)
		{
			
			if($_FILES['upload_file']['size'] > 0)
				$file_name=MJ_cmgt_load_documets($_FILES['upload_file'],'upload_file','media');
				$upload_dir_url=MJ_cmgt_upload_url_path('church_assets'); 
				$file_url = $upload_dir_url.''.$file_name;
		}
		else
		{
			if(isset($_REQUEST['hidden_upload_file']))
				$file_name= sanitize_text_field($_REQUEST['hidden_upload_file']);
				$file_url=$file_name;
		}
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
				//------ EDIT SERMON ---------//
			$result=$obj_sermon->MJ_cmgt_add_sermon($_POST,$file_url);
		
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=sermon-list&tab=sermonlist&message=2');
			}
		}
		else
		{
		//------ ADD SERMON ---------//
			$result=$obj_sermon->MJ_cmgt_add_sermon($_POST,$file_url);
			echo $result;
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&&page=sermon-list&tab=sermonlist&message=1');
			}
		}
	}
	}
if(isset($_REQUEST['message']))
	{
	$message = sanitize_text_field($_REQUEST['message']);
	if($message == 1)
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e('Record inserted successfully','church_mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>

		<?php 
	}
	elseif($message == 2)
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e('Record updated successfully','church_mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
			<?php 
	}
}
?>
<script type="text/javascript">
$(document).ready(function()
{
	//------------ CLOSE MESSAGE ---------//
	$('.notice-dismiss').click(function() {
		$('#message').hide();
	}); 
}); 
</script>
<script type="text/javascript">
	$(document).ready(function()
	{
		
		$('#sermon_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#upload_file').change(function (e) 
		{
			var file =$('#upload_file').val();
			$('#cmgt_sermon').val(file);
			var ext = $('#cmgt_sermon').val().split('.').pop().toLowerCase();
			var sermon_type=$('#sermon_type').val();
			
			if(sermon_type=='video')
			{
				if($.inArray(ext, ['mkv','flv','mp4','3gp','vob','wmv']) == -1) {
					alert("<?php esc_html_e('Only video formats files are allowed!','church_mgt');?>");
					$('#cmgt_sermon').val('');
					$('#upload_file').val('');
					return false;
				}
			}
			if(sermon_type=='image')
			{
				if($.inArray(ext, ['gif','jpg','png','tif','psd','bmp','pspimage']) == -1) {
					alert("<?php esc_html_e('Only image files are allowed!','church_mgt');?>");
					$('#cmgt_sermon').val('');
					$('#upload_file').val('');
					return false;
				}
			}
			if(sermon_type=='audio')
			{
				if($.inArray(ext, ['mp3','wma','wav','ogg']) == -1) {
					alert("<?php esc_html_e('Only audio formats files are allowed!','church_mgt');?>");
					$('#cmgt_sermon').val('');
					$('#upload_file').val('');
					return false;
				}
			}
			if(sermon_type=='pdf')
			{
				if($.inArray(ext, ['pdf']) == -1) {
					alert("<?php esc_html_e('Only PDF files are allowed!','church_mgt');?>");
					$('#cmgt_sermon').val('');
					$('#upload_file').val('');
					return false;
				}
			}
		});
	});
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list">
			</div>	
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->

	<div class="padding_frontendlist_body panel-white"><!--PANEL WHITE DIV START-->
		<div class=""><!--TAB CONTENT DIV STRAT-->
			<?php 
			if($active_tab == 'sermonlist')
			{ 
				$sermondata=$obj_sermon->MJ_cmgt_get_all_sermons();
				if(!empty($sermondata))
				{
					?>	
					<script type="text/javascript">
						$(document).ready(function()
						{
							jQuery('#sermon_list').DataTable({
								// "responsive":true,
								language:<?php echo MJ_cmgt_datatable_multi_language();?>,
								"order": [[ 0, "asc" ]],
								"sSearch": "<i class='fa fa-search'></i>",
								"dom": 'lifrtp',
								"aoColumns":[
												{"bSortable": false},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": false}]
								});
							$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
						} );
					</script>
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="sermon_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Sermon Title', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Sermon Type', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Status', 'church_mgt' ) ;?></th>
										<th class=""><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Sermon Title', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Sermon Type', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Status', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									if(!empty($sermondata))
									{
										$i = 0;
										foreach ($sermondata as $retrieved_data)
										{
											if($i == 0)
											{
												$color_class='cmgt_list_page_image_color0';
											}
											elseif($i == 1)
											{
												$color_class='cmgt_list_page_image_color1';
											}
											elseif($i == 2)
											{
												$color_class='cmgt_list_page_image_color2';
											}
											elseif($i == 3)
											{
												$color_class='cmgt_list_page_image_color3';
											}
											elseif($i == 4)
											{
												$color_class='cmgt_list_page_image_color4';
											}
											elseif($i == 5)
											{
												$color_class='cmgt_list_page_image_color5';
											}
											elseif($i == 6)
											{
												$color_class='cmgt_list_page_image_color6';
											}
											elseif($i == 7)
											{
												$color_class='cmgt_list_page_image_color7';
											}
											elseif($i == 8)
											{
												$color_class='cmgt_list_page_image_color8';
											}
											elseif($i == 9)
											{
												$color_class='cmgt_list_page_image_color9';
											}
											?>
											<tr>
												
												<td class="user_image width_50px profile_image_prescription padding_left_0">
													<p class="padding_15px prescription_tag <?php echo $color_class; ?>">	
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Sermon-white.png"?>" alt="" class="massage_image center">
													</p>
												</td>

												<td class="name width_25_per"><a class="color_black" href="?church-dashboard=user&&page=sermon-list&tab=view-sermon&action=view-sermon&sermon_id=<?php echo $retrieved_data->id;?>"><?php echo esc_attr(ucfirst($retrieved_data->sermon_title));?></a> </td>

												<td class="media_type width_15_per"><?php echo MJ_cmgt_get_media_type(esc_attr($retrieved_data->sermon_type));?> </td>

												<td class="Status width_15_per"><?php if($retrieved_data->status=='publish'){ 
													echo '<span class="green_color">'.esc_html__("Publish",'church_mgt').'</span>';
													}
													else
													{ 
														echo '<span class="red_color">'.esc_html__("Draft",'church_mgt').'</span>'; 
													}?> 
												</td>
												
												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown action_menu mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																	<li><a  href="?church-dashboard=user&&page=sermon-list&tab=view-sermon&action=view-sermon&sermon_id=<?php echo $retrieved_data->id;?>" class="dropdown-item"><i class="fa fa-eye"></i><?php _e('View','church_mgt');?></a></li>
																	<?php  if($user_access['edit'] == '1'){ ?>
																	<li><a href="?church-dashboard=user&&page=sermon-list&tab=addsermon&action=edit&sermon_id=<?php echo $retrieved_data->id?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																	<?php } ?>
																</ul>
															</li>
															<!-- END USER LOGIN DROPDOWN -->
														</ul>
													</div>
												</td>
											</tr>
											<?php 
											$i++;
										} 
									
									}?>
								</tbody>
							</table>
						</div><!--TABLE RESPONSIVE DIV END-->
					</div><!--PANEL BODY DIV END-->
					<?php 
				}
				else
				{
					if($user_access['add']=='1')
					{
						?>
						<div class="no_data_list_div"> 
							<a href="<?php echo home_url().'?church-dashboard=user&page=sermon&tab=addsermon';?>">
								<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
							</a>
							<div class="col-md-12 dashboard_btn margin_top_20px">
								<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
							</div> 
						</div>		
						<?php
					}
					else
					{
						?>
						<div class="calendar-event-new"> 
							<img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
						</div>	
						<?php
					}
				}
			}
			if($active_tab == 'view-sermon')
			{
				$gift_id=0;
				if(isset($_REQUEST['sermon_id']))		
					$sermon_id= sanitize_text_field($_REQUEST['sermon_id']);
					$result = $obj_sermon->MJ_cmgt_get_single_sermon($sermon_id);
				?>
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<form name="gift_form" action="" method="post" class="" id="gift_form"><!-- GIFT FORM START-->
							<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
							<?php 
							if($result->sermon_type=='video')
							{?>
								<div class="form-group cmgt_view_sermon">
									<div class="mb-3 row">	
										<div class="col-sm-6 col-xs-6 mb-2">
											<label class="popup_label_heading"  for="venue_name">
												<?php _e('Sermon Title','church_mgt');?> 
											</label>
											<br>
											<label class="popup_label_value" for="venue_name">
												<?php echo esc_attr($result->sermon_title);?>
											</label>
										</div>
										
										<div class="col-sm-6 col-xs-6 mb-2">
											<label class="popup_label_heading"  for="venue_name">
												<?php _e('Sermon Type','church_mgt');?> 
											</label>
											<br>
											<label class="popup_label_value" for="venue_name">
												<?php echo _e($result->sermon_type,'church_mgt');?>
											</label>
										</div>
										<div class="col-sm-6 col-xs-6 mb-2">
											<label class="popup_label_heading"  for="venue_name">
												<?php _e('Status','church_mgt');?> 
											</label>
											<br>
											<label class="popup_label_value" for="venue_name">
												<?php 	
													if($result->status=='publish')
													{ 
														echo esc_html__("Publish",'church_mgt');
													}
													else
													{ 
														echo esc_html__("Draft",'church_mgt'); 
													}?>
											</label>
										</div>
										<div class="col-sm-6 col-xs-6 mb-2">
											<label class="popup_label_heading"  for="venue_name">
												<?php _e('Description','church_mgt');?> 
											</label>
											<br>
											<label class="popup_label_value" for="venue_name">
												<?php echo esc_attr($result->description	);?>
											</label>
										</div>
										<div class="col-xs-12 col-sm-12">
											<video width="100%" height="auto"style="outline: none;" controls>
												<source src="<?php echo esc_attr($result->sermon_content);?>" type="video/mp4">
												Your browser does not support the video tag.
											</video>	
										</div>
									</div>
								</div>
							<?php 
							} 
							if($result->sermon_type=='audio')
							{?>
							<div class="form-group cmgt_view_sermon">
								<div class="mb-3 row">	
									<div class="col-sm-6 col-xs-6 mb-2">
										<label class="popup_label_heading"  for="venue_name">
											<?php _e('Sermon Title','church_mgt');?> 
										</label>
										<br>
										<label class="popup_label_value" for="venue_name">
											<?php echo esc_attr($result->sermon_title);?>
										</label>
									</div>
										
									<div class="col-sm-6 col-xs-6 mb-2">
										<label class="popup_label_heading"  for="venue_name">
											<?php _e('Sermon Type','church_mgt');?> 
										</label>
										<br>
										<label class="popup_label_value" for="venue_name">
										<?php echo _e($result->sermon_type,'church_mgt');?>
										</label>
									</div>
									<div class="col-sm-6 col-xs-6 mb-2">
										<label class="popup_label_heading"  for="venue_name">
											<?php _e('Status','church_mgt');?>
										</label>
										<br>
										<label class="popup_label_value" for="venue_name">
											<?php 	
												if($result->status=='publish')
												{ 
													echo esc_html__("Publish",'church_mgt');
												}
												else
												{ 
													echo esc_html__("Draft",'church_mgt'); 
												}?>
										</label>
									</div>
									<div class="col-sm-6 col-xs-6 mb-2">
										<label class="popup_label_heading"  for="venue_name">
											<?php _e('Description','church_mgt');?>: 
										</label>
										<br>
										<label class="popup_label_value" for="venue_name">
											<?php
												if(!empty($result->description)){
													echo esc_attr($result->description);
												}
												else
												{
													echo esc_html( __( 'N/A', 'church_mgt' ) );
												}
											?>
										</label>
									</div>
									<div class="sermon-audio col-xs-12 col-sm-12">
										<audio id="audio-player" width="100%" height="600px" style="outline: none;"src="<?php if($result->sermon_content != ''){ echo esc_attr($result->sermon_content);}?>" type="audio/mp3" controls="controls"></audio>
									</div>
								</div>
							</div>

							<?php 
							} 
							if($result->sermon_type=='image')
							{
								?>
							<div class="form-group cmgt_view_sermon row mb-3">
								<div class="col-sm-6 col-xs-6 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Sermon Title','church_mgt');?>
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->sermon_title);?>
									</label>
								</div>
								
								<div class="col-sm-6 col-xs-6 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Sermon Type','church_mgt');?>
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
									<?php echo _e($result->sermon_type,'church_mgt');?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Status','church_mgt');?>
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php 	
											if($result->status=='publish')
											{ 
												echo esc_html__("Publish",'church_mgt');
											}
											else
											{ 
												echo esc_html__("Draft",'church_mgt'); 
											}?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Description','church_mgt');?>
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php
											if(!empty($result->description))
											{
												echo esc_attr($result->description);
											}
											else
											{
												echo esc_html( __( 'N/A', 'church_mgt' ) );
											}
										?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="mb-3 row">	
									<div class="col-xs-12 col-sm-12 ">
									<image  class="sermon_image" width="680px" height="460px" src="<?php echo esc_attr($result->sermon_content);?>">
									</div>
								</div>
							</div>
							<?php 
							}
							if($result->sermon_type=='pdf')
							{
								?>
								<div class="form-group cmgt_view_sermon">
									<div class="mb-3 row">	
										<div class="col-sm-6 col-xs-6 mb-2">
											<label class="popup_label_heading"  for="venue_name">
												<?php _e('Sermon Title','church_mgt');?> 
											</label>
											<br>
											<label class="popup_label_value" for="venue_name">
												<?php echo esc_attr($result->sermon_title);?>
											</label>
										</div>
										<div class="col-sm-6 col-xs-6 mb-2">
											<label class="popup_label_heading"  for="venue_name">
												<?php _e('Sermon Type','church_mgt');?> 
											</label>
											<br>
											<label class="popup_label_value" for="venue_name">
											<?php echo _e($result->sermon_type,'church_mgt');?>
											</label>
										</div>
										<div class="col-sm-6 col-xs-6 mb-2">
											<label class="popup_label_heading"  for="venue_name">
												<?php _e('Status','church_mgt');?> 
											</label>
											<br>
											<label class="popup_label_value" for="venue_name">
												<?php 	
													if($result->status=='publish')
													{ 
														echo '<span class="green_color">'.esc_html__("Publish",'church_mgt').'</span>';
													}
													else
													{ 
														echo '<span class="red_color">'.esc_html__("Draft",'church_mgt').'</span>'; 
													}?>
											</label>
										</div>
										<div class="col-sm-6 col-xs-6 mb-2">
											<label class="popup_label_heading"  for="venue_name">
												<?php _e('Description','church_mgt');?>: 
											</label>
											<br>
											<label class="popup_label_value" for="venue_name">
												<?php
													if(!empty($result->description)){
														echo esc_attr($result->description);
													}
													else
													{
														echo esc_html( __( 'N/A', 'church_mgt' ) );
													}
												?>
											</label>
										</div>
										<div class="col-xs-12 col-sm-12">
											<?php
											if(isset($_REQUEST['web_type']) && $_REQUEST['web_type'] == "church_app")
											{
												?>
												<div class="form-body user_form margin_top_40px">
													<div class="row">
														<div class="col-md-1 pdf_btn_rs">
															<?php
															if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) 
															{ 
																?>
																<a href="https://drive.google.com/viewerng/viewer?embedded=true&url=<?php echo $result->sermon_content;?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
																<?php
															}else{
																?>
																<a href="<?php echo esc_attr($result->sermon_content);?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
																<?php
															}
															?>
														</div>
													</div>
												</div>
												<?php
											}
											else{
												if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) 
												{ ?>
													<iframe src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?php echo $result->sermon_content;?>" frameborder="0" width="100%" height="600px"></iframe>
													<?php 
												}
												else 
												{ ?>
													<iframe src="<?php echo esc_attr($result->sermon_content);?>" frameborder="0" width="100%" height="600px"></iframe>
													<?php
												};
											}

											?>

										</div>
									</div>
								</div>
								<?php 
							}?>
						</form><!-- GIFT FORM END-->
					</div> <!-- PANEL BODY DIV END--> 
		<?php 
			}
			if($active_tab == 'addsermon')
			{
				$sermon_id=0;
				if(isset($_REQUEST['sermon_id']))
					$sermon_id= sanitize_text_field($_REQUEST['sermon_id']);
					$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						$edit=1;
						$result = $obj_sermon->MJ_cmgt_get_single_sermon($sermon_id);
					}?>
				<div class="panel-body"><!--PANEL BODY DIV START-->
					<form name="sermon_form" action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="sermon_form"><!--SERMON FORM STRAT-->
						<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
						<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
						<input type="hidden" name="sermon_id" value="<?php echo esc_attr($sermon_id);?>"  />
						<div class="form-group">
							<div class="mb-3 row">	
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="sermon_title"><?php esc_html_e('Sermon Title','church_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<input id="sermon_title" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->sermon_title);}elseif(isset($_POST['sermon_title'])) echo esc_attr($_POST['sermon_title']);?>" name="sermon_title">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="mb-3 row">	
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="description"><?php esc_html_e('Description','church_mgt');?></label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<textarea name="description" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="description"><?php if($edit){ echo esc_textarea($result->description);}?></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="mb-3 row">	
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="sermon_type"><?php esc_html_e('Sermon Content Type','church_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<?php if($edit) 
										$sermon_type= sanitize_text_field($result->sermon_type);
									  elseif(isset($_POST['sermon_type']))
										 $sermon_type= sanitize_text_field($_POST['sermon_type']);
									  else
										  $sermon_type='';?>
									<select id="sermon_type" name="sermon_type"class="form-control validate[required]">
										<option value="" ><?php esc_html_e('Select Sermon Type','church_mgt');?></option>
										<option value="video" <?php selected($sermon_type,'video');?>><?php esc_html_e('Video','church_mgt');?></option>
										<option value="image" <?php selected($sermon_type,'image');?>><?php esc_html_e('Image','church_mgt');?></option>
										<option value="audio" <?php selected($sermon_type,'audio');?>><?php esc_html_e('Audio','church_mgt');?></option>
										<option value="pdf" <?php selected($sermon_type,'pdf');?>><?php esc_html_e('PDF','church_mgt');?></option>
										
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="mb-3 row">	
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="sermon_content"><?php esc_html_e('Sermon Content','church_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-2">
									<input id="cmgt_sermon" type="text" class="form-control validate[required]" name="cmgt_sermon" 
									value="<?php if($edit){ echo esc_attr($result->sermon_content);}elseif(isset($_POST['cmgt_sermon'])) echo esc_attr($_POST['cmgt_sermon']);?>" />	
								</div>
								<div class="col-sm-3">
									<input type="hidden" name="hidden_upload_file" value="<?php if($edit){ echo esc_attr($result->sermon_content);}elseif(isset($_POST['cmgt_sermon'])) echo esc_attr($_POST['cmgt_sermon']);?>">
									<input id="upload_file" name="upload_file" type="file" class="button upload_user_cover_button" value="<?php esc_html_e( 'Upload Song', 'church_mgt' ); ?>" />		
								 </div>
								<div class="clearfix"></div>
							</div>	
						</div>
						<div class="form-group">
							<div class="mb-3 row">	
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="sermon_type"><?php esc_html_e('Status','church_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<?php if($edit) 
										$status= sanitize_text_field($result->status);
									  elseif(isset($_POST['status']))
										 $status= sanitize_text_field($_POST['status']);
									  else
										  $status='';?>
									<select name="status"class="form-control validate[required]">
										<option value="publish" <?php selected($status,'publish');?>><?php esc_html_e('Publish','church_mgt');?></option>
										<option value="draft" <?php selected($status,'draft');?>><?php esc_html_e('Draft','church_mgt');?></option>
									</select>
								</div>
							</div>
						</div>
						<?php wp_nonce_field( 'save_sermon_nonce' ); ?>
						<div class="offset-sm-2 col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<input type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Save Sermon','church_mgt');}?>" name="save_sermon" class="btn btn-success"/>
						</div>
					</form><!--SERMON FORM END-->
				</div><!--PANEL BODY DIV END-->
			 <?php 
			}
			 ?>
		</div><!--TAB CONTENT DIV END-->
	</div><!--PANEL WHITE DIV END-->
<?php ?>