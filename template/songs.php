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
$volunteer=MJ_cmgt_check_volunteer($curr_user_id);
$obj_song=new Cmgtsong;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'songlist');

	if(isset($_POST['save_song']))
	{
		$nonce = sanitize_text_field($_POST['_wpnonce']);
		if (wp_verify_nonce( $nonce, 'save_song_nonce' ) )
		{ 
			//----------- SAVE SONG DATA -----------------//
			if(isset($_FILES['upload_song_file']) && !empty($_FILES['upload_song_file']) && $_FILES['upload_song_file']['size'] !=0)
			{
				if($_FILES['upload_song_file']['size'] > 0)
					$file_name=MJ_cmgt_load_documets($_FILES['upload_song_file'],'upload_song_file','song');
					$upload_dir_url=MJ_cmgt_upload_url_path('church_assets'); 
					$file_url = $upload_dir_url.''.$file_name;
			}
			else
			{
				if(isset($_REQUEST['hidden_upload_song_file']))
					$file_name= sanitize_text_field($_REQUEST['hidden_upload_song_file']);
					$file_url=$file_name;
			}
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
					//-------- EDIT SONG ------------//
				$result=$obj_song->MJ_cmgt_add_song($_POST,$file_url);
				if($result)
				{
					wp_redirect ( home_url().'?church-dashboard=user&&page=songs&tab=songlist&message=2');
					exit();
				}
			}
			else
			{
			//-------- ADD SONG ------------//
				$result=$obj_song->MJ_cmgt_add_song($_POST,$file_url);
				echo $result;
				if($result)
				{
					wp_redirect ( home_url().'?church-dashboard=user&&page=songs&tab=songlist&message=1');
					exit();
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
			jQuery('#song_list').DataTable({
				// "responsive":true,
				"sSearch": "<i class='fa fa-search'></i>",
				"dom": 'lifrtp',
				language:<?php echo MJ_cmgt_datatable_multi_language();?>,
				"order": [[ 0, "asc" ]],
				"aoColumns":[
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true},
							 {"bSortable": false}]
					});
			$('#song_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			$('#upload_song_file').change(function (e) {
				 var file =$('#upload_song_file').val();
				$('#song_text').val(file);
				
			});
			$("#save_song").click(function()
			{
				var ext = $('#song_text').val().split('.').pop().toLowerCase();
				if(ext =='' || ext == null)
				{
					alert("<?php esc_html_e('please fill in the required fields','church_mgt');?>");
					return false;	
				} 
				else
				{
					var ext = $('#song_text').val().split('.').pop().toLowerCase();
					if($.inArray(ext, ['mp3','wma','wav','ogg']) == -1) {
						alert("<?php esc_html_e('Only audio formats files are allowed!','church_mgt');?>");
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
	
	<div class="panel-white"><!--PANEL WHITE DIV START-->
		<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT DIV STRAT-->
			<?php 
			if($active_tab == 'songlist')
			{ 
				$own_data=$user_access['own_data'];
				if($obj_church->role == 'accountant')
				{
					if($own_data == '1')
					{ 
						$songdata=$obj_song->MJ_cmgt_get_all_song_created_by();
					}
					else
					{
						$songdata=$obj_song->MJ_cmgt_get_all_song();
					}
				}
				else
				{
					$songdata=$obj_song->MJ_cmgt_get_all_song();
				}	
				if(!empty($songdata))
				{
					?>	
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="song_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Song Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Song Category', 'church_mgt' ) ;?></th>
										<th class=""><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Song Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Song Category', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									if(!empty($songdata))
									{	
										$i=0;
										foreach ($songdata as $retrieved_data)
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
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Songs-white.png"?>" alt="" class="massage_image center">
													</p>
												</td>

												<td class="giftname width_25_per"><a class="color_black" href="?church-dashboard=user&&page=songs&tab=viewsong&action=view-song&song_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_attr(ucfirst($retrieved_data->song_name));?></a> </td>

												<td class="songcategory width_20_per"><?php echo ucfirst(get_the_title(esc_attr($retrieved_data->song_cat_id)));?> </td>

												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown action_menu mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																	<li><a href="?church-dashboard=user&&page=songs&tab=viewsong&action=view-song&song_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-eye"></i><?php _e('view Song', 'church_mgt' );?></a></li>
																	<?php if($user_access['edit'] == '1'){?>
																	<li><a href="?church-dashboard=user&&page=songs&tab=addsong&action=edit&song_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"> <i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
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
							<a href="<?php echo home_url().'?church-dashboard=user&page=songs&tab=addsong';?>">
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
			if($active_tab == 'addsong')
			{
				$song_id=0;
				if(isset($_REQUEST['song_id']))
					$song_id= sanitize_text_field($_REQUEST['song_id']);
					$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						$edit=1;
						$result = $obj_song->MJ_cmgt_get_single_song($song_id);
						
					}?>
				
				<div class="padding_left_15px margin_top_15px margin_top_15_per_res"><!--PANEL BODY DIV START-->
					<form name="song_form" action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="song_form"><!--SONG FORM START-->
						<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
						<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
						<input type="hidden" name="song_id" value="<?php echo esc_attr($song_id);?>"  />
						<div class="form-body user_form">
							<div class="row">
								<div class="col-md-6 cmgt_display">
									<div class="form-group input row margin_buttom_0">
										<div class="col-md-8">
											<label class="ml-1 custom-top-label top" for="activity_category"><?php _e('Song Category','church_mgt');?><span class="require-field">*</span></label>

											<select class="form-control line_height_30px validate[required]" name="song_cat_id" id="song_category">

												<option value=""><?php _e('Select Song Category','church_mgt');?></option>
												<?php 
												
												if(isset($_REQUEST['song_cat_id']))
													$category =$_REQUEST['song_cat_id'];  
												elseif($edit)
													$category =$result->song_cat_id;
												else 
													$category = "";
												
												$activity_category=MJ_cmgt_get_all_category('song_category');
												if(!empty($activity_category))
												{
													foreach ($activity_category as $retrive_data)
													{
														echo '<option value="'.esc_attr($retrive_data->ID).'" '.selected($category,$retrive_data->ID).'>'.esc_attr($retrive_data->post_title).'</option>';
													}
												}?>
											</select>
										</div>
										<div class="col-md-4">
											<button class="btn btn-success width_100 btn_height" id="addremove" model="song_category"><?php _e('Add Or Remove','church_mgt');?></button>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="song_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo esc_attr($result->song_name);}elseif(isset($_POST['song_name'])) echo esc_attr($_POST['song_name']);?>" name="song_name">
											<label class="" for="song_name"><?php _e('Song Name','church_mgt');?><span class="require-field">*</span></label>
										</div>	
									</div>
								</div>
								<div class="col-md-6 note_text_notice">
									<div class="form-group input">
										<div class="col-md-12 note_border margin_bottom_15px_res">
											<div class="form-field">
												<textarea name="description" class="form-control textarea_height validate[custom[address_description_validation]]" maxlength="150" id="description"><?php if($edit){ echo esc_attr($result->description);}?></textarea>
												<span class="txt-title-label"></span>
												<label class="text-area address"><?php esc_html_e('Description','wpnc');?></label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-12 form-control upload-profile-image-patient">
											<label class="ustom-control-label custom-top-label ml-2" for="gmgt_membershipimage"><?php esc_html_e('Song','church_mgt');?><span class="require-field">*</span></label>
											<div class="col-sm-12 display_flex">
												<input id="song_text" type="hidden" name="song"  class="form-control validate[required]" value="<?php if($edit){ echo esc_attr($result->song);}elseif(isset($_POST['song'])) echo esc_attr($_POST['song']);?>" readonly/>	
											
												<input id="upload_song_file" name="upload_song_file" type="file" class="form-control button upload_user_cover_button" value="<?php if($edit){ echo esc_attr($result->song);}elseif(isset($_POST['song'])) echo esc_attr($_POST['song']);?>" />		
												<input type="hidden" name="hidden_upload_song_file" value="<?php if($edit){ echo $result->song;}elseif(isset($_POST['song'])) echo $_POST['song'];?>">
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
											<div id="upload_gym_cover_preview" style="min-height: 50px; padding-bottom:10px;padding-top:15px;">
												<audio id="audio-player" style="outline:none;"src="<?php if($edit && $result->song != ''){ echo esc_attr($result->song);}elseif(isset($_POST['song'])) echo esc_attr($_POST['song']);?>" type="audio/mp3" controls="controls" style="margin-top:10px;"></audio>
											</div>
										</div>
									</div>
								</div>	
							</div> 
						</div>
						<div class="row">
							<div class="col-md-6">
								<?php wp_nonce_field( 'save_song_nonce' ); ?>
								<div class="offset-sm-0">
								<input id="save_song" type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Add Song','church_mgt');}?>" name="save_song" class="btn btn-success save_btn reduce_sp"/>
								</div>
							</div>	
						</div>
					</form><!--SONG FORM END-->
				</div><!--PANEL BODY DIV END-->
			<?php 
			}
			if($active_tab == 'viewsong')
			{
				$song_id=0;
				if(isset($_REQUEST['song_id']))		
					$song_id= sanitize_text_field($_REQUEST['song_id']);
					$result = $obj_song->MJ_cmgt_get_single_song($song_id);
					?>
				<div class="panel-body"><!--PANEL BODY DIV START-->
					<form name="gift_form" action="" method="post" class="cmgt_frontend_songview_mt form-horizontal" id="gift_form "><!--SONG FORM START-->
						<!-- <div class="form-group">
							<label class="col-sm-12 col-xs-12 col-md-12" for="sermon_title"><h2><?php echo esc_attr($result->song_name);?></h2></label>
							<div class="sermon-audio col-xs-12 col-sm-12 col-md-12">
								<audio id="audio-player"  class="aud_width" width="100%" height="600px" style="outline:none;" src="<?php if($result->song != ''){ echo esc_attr($result->song);}?>" type="audio/mp3" controls="controls"></audio>
							</div>
						</div> -->

						<div class="form-group cmgt_view_sermon">
							<div class="mb-3 row">
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Song Name','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr (ucfirst($result->song_name));?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Song Category','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo ucfirst(get_the_title(esc_attr($result->song_cat_id)));?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Description','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php 
										if(!empty($result->description))
										{
											echo esc_attr(ucfirst($result->description));
										}else{
											echo esc_html( __( 'N/A', 'church_mgt' ) );
										}
										?>
									</label>
								</div>
							</div>
							<div class="sermon-audio col-xs-12 col-sm-12 col-md-12 ">
								<audio id="audio-player" width="100%" height="600px" style="outline: none;"src="<?php if($result->song != ''){ echo esc_attr($result->song);}?>" type="audio/mp3" controls="controls"></audio>
							</div>
						</div>

					</form><!--SONG FORM END-->
				</div><!--PANEL BODY DIV END-->
		<?php }
		?>
		</div><!--TAB CONTENT DIV END-->
	</div><!--PANEL WHITE DIV END-->
<?php ?>