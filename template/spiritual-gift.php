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
$obj_gift=new Cmgtgift;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'giftlist');
$obj_gift=new Cmgtgift();
$members_gift=$obj_gift->MJ_cmgt_get_members_gift($curr_user_id);	
$mygift_array=array();
foreach($members_gift as $gift)
{
	$mygift_array[]=$gift->gift_id;
}
//---------------- SAVE GIFT DATA ----------------//
if(isset($_POST['save_gift']))
{
	$nonce = sanitize_text_field($_POST['_wpnonce']);
	if (wp_verify_nonce( $nonce, 'save_gift_nonce' ) )
	{
	if(isset($_FILES['upload_gift_file']) && !empty($_FILES['upload_gift_file']) && $_FILES['upload_gift_file']['size'] !=0)
	{
		
		if($_FILES['upload_gift_file']['size'] > 0)
			$file_name=MJ_cmgt_load_documets($_FILES['upload_gift_file'],'upload_gift_file','file');
			//$upload_dir_url=MJ_cmgt_upload_url_path('church_assets'); 
			//$file_url = $upload_dir_url.''.$file_name;			
			$file_url =$file_name;			
	}
	else
	{
		if(isset($_REQUEST['hidden_upload_gift_file']))
				$file_name= sanitize_text_field($_REQUEST['hidden_upload_gift_file']);
				$file_url=$file_name;
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
		//---------------- EDIT GIFT DATA ----------------//
		$result=$obj_gift->MJ_cmgt_add_gift($_POST,$file_url);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&page=spiritual-gift&tab=giftlist&message=2');
		}
	}
	else
	{
		//---------------- ADD GIFT DATA ----------------//
		$result=$obj_gift->MJ_cmgt_add_gift($_POST,$file_url);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&page=spiritual-gift&tab=giftlist&message=1');
		}
	}
	}
}
//---------------- SELL GIFT DATA ----------------//
if(isset($_POST['sell_gift']))
{
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
		$result=$obj_gift->MJ_cmgt_sell_gift($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&page=spiritual-gift&tab=sellgiftlist&message=2');
		}
	}
	else
	{
		$result=$obj_gift->MJ_cmgt_sell_gift($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?church-dashboard=user&page=spiritual-gift&tab=sellgiftlist&message=1');
		}
	}
}
if(isset($_POST['give_gift']))
{
	
	$result=$obj_gift->MJ_cmgt_give_gift($_POST);
	if($result)
	{
		wp_redirect ( home_url().'?church-dashboard=user&page=spiritual-gift&tab=giftlist&message=4');
	}
}
//---------------- DLETE GIFT DATA ----------------//
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		if(isset($_REQUEST['gift_id']))
		{
			$result=$obj_gift->MJ_cmgt_delete_gift(sanitize_text_field($_REQUEST['gift_id']));
			if($result)
			{
				wp_redirect (home_url().'?church-dashboard=user&page=spiritual-gift&tab=giftlist&message=3');
			}
		}
		if(isset($_REQUEST['sell_id']))
		{
			$result=$obj_gift->MJ_cmgt_delete_sell_gift(sanitize_text_field($_REQUEST['sell_id']));
			if($result)
			{
				wp_redirect ( home_url().'?church-dashboard=user&page=spiritual-gift&tab=sellgiftlist&message=3');
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
	elseif($message == 3) 
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
		<?php
		esc_html_e('Record deleted successfully','church_mgt');
		?>
		<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php
	}
	elseif($message == 4) 
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e('Gift Assigned  successfully','church_mgt');
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
			$('#gift_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$('#sell_gift_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			$(".display-members").select2();
			
			jQuery('#sell_date').datepicker({
				dateFormat: "yy-mm-dd",
				minDate:'today',
				changeMonth: true,
		        changeYear: true,
		        yearRange:'-65:+25',
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
			
			
			$('#upload_gift_file').change(function (e) {
				 var file =$('#upload_gift_file').val();
				$('#cmgt_church_gifte').val(file);
				var ext = $('#cmgt_church_gifte').val().split('.').pop().toLowerCase();
				var gift_type=$('#gift_type').val();
				if(gift_type=='video')
				{
					if($.inArray(ext, ['mkv','flv','mp4','3gp','vob','wmv']) == -1) {
						alert("<?php esc_html_e('Only video formats files are allowed!','church_mgt');?>");
						$('#cmgt_church_gifte').val('');
						$('#upload_gift_file').val('');
						return false;
					}
				}
				if(gift_type=='image')
				{
					if($.inArray(ext, ['gif','jpg','png','tif','psd','bmp','pspimage']) == -1) {
						alert("<?php esc_html_e('Only image files are allowed!','church_mgt');?>");
						$('#cmgt_church_gifte').val('');
						$('#upload_gift_file').val('');
						return false;
					}
				}
				if(gift_type=='audio')
				{
					if($.inArray(ext, ['mp3','wma','wav','ogg']) == -1) {
						alert("<?php esc_html_e('Only audio formats files are allowed!','church_mgt');?>");
						$('#cmgt_church_gifte').val('');
						$('#upload_gift_file').val('');
						return false;
					}
				}
				if(gift_type=='pdf')
				{
					if($.inArray(ext, ['pdf']) == -1) {
						alert("<?php esc_html_e('Only PDF files are allowed!','church_mgt');?>");
						$('#cmgt_church_gifte').val('');
						$('#upload_gift_file').val('');
						return false;
					}
				}
			});		
			
			//---member validation-----//
			$("#sell_gift").click(function() 
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

<script>
	function showMyImage(fileInput) {
	var files = fileInput.files;
	for (var i = 0; i < files.length; i++) {
	var file = files[i];
	var imageType = /image.*/;
	if (!file.type.match(imageType)) {
	continue;
	}
	var img=document.getElementById("thumbnil");
	img.file = file;
	var reader = new FileReader();
	reader.onload = (function(aImg) {
	return function(e) {
	aImg.src = e.target.result;
	};
	})(img);
	reader.readAsDataURL(file);
	}
	}
</script>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
			<!-- <div class="category_list"> -->
				<div class="invoice_data"></div>	 
			<!-- </div> -->
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->

	<div class="panel-white padding_frontendlist_body"><!--PANEL WHITE DIV START-->
		<ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per flex-nowrap overflow-auto" role="tablist">
			<li class="<?php if($active_tab =='giftlist'){?>active<?php }?>">
				<a href="?church-dashboard=user&page=spiritual-gift&tab=giftlist" class="padding_left_0 tab <?php echo $active_tab == 'giftlist' ? 'active' : ''; ?>">
				<?php esc_html_e('Spiritual Gifts List', 'church_mgt'); ?></a> 
			</li> 
			<?php 
			if($user_access['add'] == '1') 
			{
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['gift_id']))
				{
					?>
					<li class="<?php if($active_tab =='addgift'){?>active<?php }?>">
						<a href="?church-dashboard=user&page=spiritual-gift&tab=addgift&action=edit&gift_id=<?php echo esc_attr($_REQUEST['gift_id']);?>" class="padding_left_0 tab <?php echo $active_tab == 'addgift' ? 'active' : ''; ?>">
						<?php esc_html_e('Edit Spiritual Gift', 'church_mgt'); ?></a> 
					</li> 
				<?php
				}
				else
				{
					?>
					<li class="<?php if($active_tab =='addgift'){?>active<?php }?>">
						<a href="?church-dashboard=user&page=spiritual-gift&tab=addgift" class="padding_left_0 tab <?php echo $active_tab == 'addgift' ? 'active' : ''; ?>">
						<?php esc_html_e('Add Spiritual Gift', 'church_mgt'); ?></a> 
					</li> 
				<?php
				}
			}
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view-gift')
			{
				?>
				<li class="<?php if($active_tab =='view-gift'){?>active<?php }?>">
					<a href="?church-dashboard=user&page=spiritual-gift&tab=view-gift&action=view-sermon&sermon_id=<?php echo esc_attr($_REQUEST['gift_id']);?>" class="padding_left_0 tab <?php echo $active_tab == 'sellgiftlist' ? 'active' : ''; ?>">
					<?php esc_html_e('View Gifts', 'church_mgt'); ?></a> 
				</li> 
				<?php
			}
			?>
			<li class="<?php if($active_tab =='sellgiftlist'){?>active<?php }?>">
				<a href="?church-dashboard=user&page=spiritual-gift&tab=sellgiftlist" class="padding_left_0 tab <?php echo $active_tab == 'sellgiftlist' ? 'active' : ''; ?>">
				<?php esc_html_e('Sell Gifts List', 'church_mgt'); ?></a> 
			</li> 
			<?php
			if($user_access['add'] == '1')
			{
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['sell_id']))
				{
					?>
					<li class="<?php if($active_tab =='sellgift'){?>active<?php }?>">
						<a href="?church-dashboard=user&page=spiritual-gift&tab=sellgift&action=edit&sell_id=<?php echo esc_attr($_REQUEST['sell_id']);?>" class="padding_left_0 tab <?php echo $active_tab == 'sellgift' ? 'active' : ''; ?>">
						<?php esc_html_e('Edit Sell Gifts', 'church_mgt'); ?></a> 
					</li> 
					<?php
				}
				else
				{
					?>
					<li class="<?php if($active_tab =='sellgift'){?>active<?php }?>">
						<a href="?church-dashboard=user&page=spiritual-gift&tab=sellgift" class="padding_left_0 tab <?php echo $active_tab == 'sellgift' ? 'active' : ''; ?>">
						<?php esc_html_e('Add Sell Gifts', 'church_mgt'); ?></a> 
					</li> 
					<?php
				}
			}
			if($active_tab == 'view_invoice')
			{
				?>
				<li class="<?php if($active_tab=='view_sellgift'){?>active<?php }?>">
				<a href="?church-dashboard=user&page=spiritual-gift&tab=sellgiftlist" class="padding_left_0 tab <?php echo $active_tab == 'view_invoice' ? 'active' : ''; ?>">
				<?php _e('View Invoice', 'church_mgt'); ?></a>
				<?php
			}
			?>
		</ul>
		<div class="tab-content padding_top_25px"><!--TAB CONTENT DIV STRAT-->
			<?php 
			if($active_tab == 'giftlist')
			{ 
				$own_data=$user_access['own_data'];
				if($obj_church->role == 'accountant')
				{
					if($own_data == '1')
					{ 
					$giftdata=$obj_gift->MJ_cmgt_get_all_gifts_creted_by();
					}
					else
					{
					$giftdata=$obj_gift->MJ_cmgt_get_all_gifts();
					}
				}
				else
				{
					$giftdata=$obj_gift->MJ_cmgt_get_all_gifts();
				}	
				
				if(!empty($giftdata))
				{
					?>	
					<script type="text/javascript">
						$(document).ready(function()
						{
							jQuery('#gift_list').DataTable({
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
							<table id="gift_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Price', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Type', 'church_mgt' ) ;?></th>
										<th class=""><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Price', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Type', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									if(!empty($giftdata))
									{
										foreach ($giftdata as $retrieved_data)
										{?>
											<tr>
												<td class="user_image width_50px profile_image_prescription padding_left_0">
													<?php
													if($retrieved_data->gift_type == "image")
													{
														?>
														<img style="" src="<?php echo esc_attr($retrieved_data->media_gift);?>" height="50px" width="50px" class="img-circle" />
														<?php
													}else{
														echo '<img src='.esc_url(get_option( 'cmgt_gift_logo' )).' height="50px" width="50px" class="img-circle" />';
													}
													?>
												</td>
												<td class="giftname width_25_per">
													<a class="color_black" href="?church-dashboard=user&&page=spiritual-gift&tab=view-gift&action=view-gift&gift_id=<?php echo $retrieved_data->id;?>">
														<?php echo ucfirst($retrieved_data->gift_name);?>
													</a> 
												</td>
												<td class="giftprice width_15_per">
													<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php if($retrieved_data->gift_price!='') echo esc_attr($retrieved_data->gift_price); else echo "Free";?> 
												</td>
												<td class="giftprice width_10_per"><?php echo _e($retrieved_data->gift_type,'church_mgt');?> </td>
											
												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown action_menu mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">

																	<li>
																		<a href="?church-dashboard=user&page=spiritual-gift&tab=view-gift&action=view-gift&gift_id=<?php echo $retrieved_data->id;?>" class="dropdown-item"><i class="fa fa-eye"></i><?php _e('View','church_mgt');?></a>
																	</li>
																	<?php
																	if($user_access['edit'] == '1')
																	{ 
																		?>
																		<li>
																			<a href="?church-dashboard=user&page=spiritual-gift&tab=addgift&action=edit&gift_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a>
																		</li>
																		<?php
																	}
																	if($user_access['add'] == '1')
																	{ 
																		?>
																		<li>
																			<a href="#" class="dropdown-item give_gift" id="<?php echo esc_attr($retrieved_data->id);?>"><i class="fa fa-gift" aria-hidden="true"></i><?php _e('Give Gift', 'church_mgt' );?></a>
																		</li>
																		<?php
																	}
																	if($user_access['delete'] == '1')
																	{ 
																		?>
																		<div class="cmgt-dropdown-deletelist">
																			<li><a href="?church-dashboard=user&page=spiritual-gift&tab=giftlist&action=delete&gift_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?> </a></li>
																		</div>
																		<?php
																	}
																	?>
																</ul>
															</li>
															<!-- END USER LOGIN DROPDOWN -->
														</ul>
													</div>
												</td>
											</tr>
									<?php } 
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
							<a href="<?php echo home_url().'?church-dashboard=user&page=spiritual-gift&tab=addgift';?>">
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
			if($active_tab == 'view-gift')
			{
				$gift_id=0;
				if(isset($_REQUEST['gift_id']))		
					$gift_id= sanitize_text_field($_REQUEST['gift_id']);
					$result = $obj_gift->MJ_cmgt_get_single_gift($gift_id);
			 	?>
				<div class="panel-body"><!--PANEL BODY DIV START-->
					<form name="gift_form" action="" method="post" class="" id="gift_form"><!--Spiritual FORM START-->
						 <?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
						<?php 
						if($result->gift_type=='video'){?>	
						<div class="form-group cmgt_view_sermon">
							<div class="mb-3 row">
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Name','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->gift_name);?>
									</label>
								</div>
								
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Price','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
									<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Type','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->gift_type);?>
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
												echo esc_attr($result->description);
											}else
											{
												echo esc_html( __( 'N/A', 'church_mgt' ) );
											}
										?>
									</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12">
								<video width="100%" style="outline: none;" controls>
									<source src="<?php echo $result->media_gift;?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>	
							</div>
						</div>

						
						<?php } 
						if($result->gift_type=='image'){?>
							<div class="form-group cmgt_view_sermon">
								<div class="mb-3 row">
									<div class="col-sm-6 col-xs-6 width_50 mb-2">
										<label class="popup_label_heading"  for="venue_name">
											<?php _e('Gift Name','church_mgt');?> 
										</label>
										<br>
										<label class="popup_label_value" for="venue_name">
											<?php echo esc_attr($result->gift_name);?>
										</label>
									</div>
									
									<div class="col-sm-6 col-xs-6 width_50 mb-2">
										<label class="popup_label_heading"  for="venue_name">
											<?php _e('Gift Price','church_mgt');?> 
										</label>
										<br>
										<label class="popup_label_value" for="venue_name">
										<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
										</label>
									</div>
									<div class="col-sm-6 col-xs-6 width_50 mb-2">
										<label class="popup_label_heading"  for="venue_name">
											<?php _e('Gift Type','church_mgt');?> 
										</label>
										<br>
										<label class="popup_label_value" for="venue_name">
											<?php echo esc_attr($result->gift_type);?>
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
													echo esc_attr($result->description);
												}else
												{
													echo esc_html( __( 'N/A', 'church_mgt' ) );
												}
											?>
										</label>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 ">
							<image  class="sermon_image" width="680px" height="460px" style="outline: none;" src="<?php echo $result->media_gift;?>">
							</div>
						<?php }
						if($result->gift_type=='pdf'){?>

						<div class="form-group cmgt_view_sermon">
							<div class="mb-3 row">
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Name','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->gift_name);?>
									</label>
								</div>
								
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Price','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
									<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Type','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->gift_type);?>
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
												echo esc_attr($result->description);
											}else
											{
												echo esc_html( __( 'N/A', 'church_mgt' ) );
											}
										?>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
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
												<a href="https://drive.google.com/viewerng/viewer?embedded=true&url=<?php echo $result->media_gift;?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
												<?php
											}else{
												?>
												<a href="<?php echo esc_attr($result->media_gift);?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
												<?php
											}
											?>
										</div>
									</div>
								</div>
								<?php
							}
							else{
								?>
								<iframe src="<?php echo $result->media_gift;?>" frameborder="0" width="100%" height="600px"></iframe>
								<?php
							}  ?>
						</div>


						<?php }
						if($result->gift_type=='audio'){?>
						
						<div class="form-group cmgt_view_sermon">
							<div class="mb-3 row">
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Name','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->gift_name);?>
									</label>
								</div>
								
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Price','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
									<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Type','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->gift_type);?>
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
												echo esc_attr($result->description);
											}else
											{
												echo esc_html( __( 'N/A', 'church_mgt' ) );
											}
										?>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group col-sm-12 col-md-12 col-xs-12">
							<audio id="audio-player" width="100%" height="600px" style="outline: none;" src="<?php if($result->media_gift != ''){ echo $result->media_gift;}?>" type="audio/mp3" controls="controls"></audio>
						</div>


						<?php }
						if($result->gift_type=='service')
						{
							$ext = pathinfo($result->media_gift, PATHINFO_EXTENSION);
									?>
						
						<div class="form-group cmgt_view_sermon">
							<div class="mb-3 row">
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Name','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->gift_name);?>
									</label>
								</div>
								
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Price','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
									<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($result->gift_price);?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6 width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Gift Type','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php echo esc_attr($result->gift_type);?>
									</label>
								</div>
								<div class="col-sm-6 col-xs-6  width_50 mb-2">
									<label class="popup_label_heading"  for="venue_name">
										<?php _e('Description','church_mgt');?> 
									</label>
									<br>
									<label class="popup_label_value" for="venue_name">
										<?php
											if(!empty($result->description))
											{
												echo esc_attr($result->description);
											}else
											{
												echo esc_html( __( 'N/A', 'church_mgt' ) );
											}
										?>
									</label>
								</div>
							</div>
						</div>


						<?php 
							if($ext=='mkv' || $ext=='mp4' || $ext=='flv' || $ext=='3gp' || $ext=='vob' || $ext=='wmv')
							{
							?>
							<div class=" col-sm-12 col-md-12 col-xs-12 ">
								<video width="100%" height="400px" style="outline: none;" controls>
									<source src="<?php echo $result->media_gift;?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>	
							</div>
							<?php 
							}
							elseif($ext=='gif' || $ext=='jpg' || $ext=='png' || $ext=='tif' || $ext=='psd' || $ext=='bmp' || $ext=='pspimage' )
							{ 
							?>
							<div class="col-xs-12 col-sm-12 ">
							<image  class="sermon_image" width="680px" height="460px" style="outline: none;" src="<?php echo $result->media_gift;?>">
							</div>
							<?php 
							}
							elseif($ext=='pdf')
							{ 
							?>
							<div class="form-group">
								<iframe src="<?php echo esc_attr($result->media_gift);?>" frameborder="0" width="100%" height="600px"></iframe>
							</div>
							<?php 
							}
							elseif($ext=='mp3' || $ext=='wma' || $ext=='wav' || $ext=='ogg' )
							{ 
							?>
							<div class="form-group col-sm-12 col-md-12 col-xs-12">
							<audio id="audio-player" width="100%" height="600px" style="outline: none;" src="<?php if($result->media_gift != ''){ echo $result->media_gift;}?>" type="audio/mp3" controls="controls"></audio>
							</div>
							<?php
							}
						}
						if($result->gift_type=='product')
						{
							$ext = pathinfo($result->media_gift, PATHINFO_EXTENSION);
						?>
						<div class="form-group">
							<div class="mb-3 row">	
								<label class="col-sm-2" style="" for="venue_name">
									<?php esc_html_e('Gift Name','church_mgt');?>: 
								</label>
								<div class="col-sm-6">
									<h3>
									<?php echo esc_attr($result->gift_name);?>
									</h3>
								</div>
							</div>
						</div>
						<?php 
							if($ext=='mkv' || $ext=='mp4' || $ext=='flv' || $ext=='3gp' || $ext=='vob' || $ext=='wmv')
							{
							?>
							<div class=" col-sm-12 col-md-12 col-xs-12 ">
								<video width="100%" height="400px" style="outline: none;" controls>
									<source src="<?php echo $result->media_gift;?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>	
							</div>
							<?php 
							}
							elseif($ext=='gif' || $ext=='jpg' || $ext=='png' || $ext=='tif' || $ext=='psd' || $ext=='bmp' || $ext=='pspimage' )
							{ 
							?>
							<div class="col-xs-12 col-sm-12 ">
							<image  class="sermon_image" width="680px" height="460px" style="outline: none;" src="<?php echo $result->media_gift;?>">
							</div>
							<?php 
							}
							elseif($ext=='pdf')
							{ 
							?>
							<div class="form-group">
								<iframe src="<?php echo esc_attr($result->media_gift);?>" frameborder="0" width="100%" height="600px"></iframe>
							</div>
							<?php 
							}
							elseif($ext=='mp3' || $ext=='wma' || $ext=='wav' || $ext=='ogg' )
							{ 
							?>
							<div class="form-group col-sm-12 col-md-12 col-xs-12">
							<audio id="audio-player" width="100%" height="600px" style="outline: none;" src="<?php if($result->media_gift != ''){ echo esc_attr($result->media_gift);}?>" type="audio/mp3" controls="controls"></audio>
							</div>
							<?php
							}
						}
				?>
				</form><!--Spiritual FORM END-->
				</div><!--PANEL BODY DIV END-->
				<?php 
			}
			if($active_tab == 'addgift')
			{
				?>
				<script type="text/javascript">
					$(document).ready(function() 
					{
						$("#gift_type").change(function() {

							var gift_type=$('#gift_type').val();
							
							if(gift_type=='video' || gift_type=='audio' || gift_type=='pdf')
							{
								$("#upload_gym_cover_preview").hide();
								return true;
								
							}
							if(gift_type=='image' || gift_type=='product' ||  gift_type=='service')
							{
								$("#upload_gym_cover_preview").show();
								return true;
							}
						});
					});
				</script>
				<style>
					input[type="file"]::file-selector-button{
						background-color: #22BAA0;
						border: none;
					}
				</style>
				<?php
				$gift_id=0;
				if(isset($_REQUEST['gift_id']))
					$gift_id=sanitize_text_field($_REQUEST['gift_id']);
					$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						$edit=1;
						$result = $obj_gift->MJ_cmgt_get_single_gift($gift_id);
					}?>
				<div class="panel-body padding_0"><!--PANEL BODY DIV START-->
					<form name="gift_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="gift_form"><!--Spiritual FORM STRAT-->
						 <?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
						<input type="hidden" name="action" value="<?php echo $action;?>">
						<input type="hidden" name="gift_id" value="<?php echo $gift_id;?>"  />

						<div class="form-body user_form">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="gift_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo esc_attr($result->gift_name);}elseif(isset($_POST['gift_name'])) echo esc_attr($_POST['gift_name']);?>" name="gift_name">
											<label class="" for="gift_name"><?php _e('Gift Name','church_mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="gift_price"  class="form-control validate[required,min[0],maxSize[8]] text-input" step="0.01" type="text" maxlength="8" name="gift_price" <?php if($edit){ ?>value="<?php echo esc_attr($result->gift_price);}elseif(isset($_POST['gift_price'])) echo esc_attr($_POST['gift_price']);?>">
											<label class="" for="gift_price"><?php _e('Gift Price','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-6 note_text_notice">
									<div class="form-group input">
										<div class="col-md-12 note_border margin_bottom_15px_res">
											<div class="form-field">
												<textarea name="description" class="form-control textarea_height validate[custom[address_description_validation]]" maxlength="150" id="description"><?php if($edit){ echo esc_attr($result->description);}?></textarea>
												<span class="txt-title-label"></span>
												<label class="text-area address"><?php esc_html_e('Description','church_mgt');?></label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 input cmgt_display">
									<label class="ml-1 custom-top-label top" for="gift_type"><?php _e('Gift Type','church_mgt');?><span class="require-field">*</span></label>
									<?php if($edit) 
										$gift_type= sanitize_text_field($result->gift_type);
										elseif(isset($_POST['gift_type']))
											$gift_type=sanitize_text_field($_POST['gift_type']);
										else
										$gift_type='';
									?>
									<select name="gift_type" id="gift_type" class="form-control validate[required] line_height_30px" >
										<option value="" ><?php _e('Select Gift Type','church_mgt');?></option>
										<option value="video" <?php selected($gift_type,'video');?>><?php _e('Video','church_mgt');?></option>
										<option value="image" <?php selected($gift_type,'image');?>><?php _e('Image','church_mgt');?></option>
										<option value="audio" <?php selected($gift_type,'audio');?>><?php _e('Audio','church_mgt');?></option>
										<option value="pdf" <?php selected($gift_type,'pdf');?>><?php _e('PDF','church_mgt');?></option>
										<option value="product" <?php selected($gift_type,'product');?>><?php _e('Product','church_mgt');?></option>
										<option value="service" <?php selected($gift_type,'service');?>><?php _e('Service','church_mgt');?></option>
									</select>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-12 form-control upload-profile-image-patient">
											<label class="ustom-control-label custom-top-label ml-2" for="photo"><?php _e('Gift','church_mgt');?><span class="require-field">*</span></label>
											<div class="col-sm-12">
												<input type="hidden" class="form-control " id="cmgt_church_gifte" name="cmgt_gift" value="<?php if($edit){ echo esc_attr($result->media_gift);}elseif(isset($_POST['cmgt_gift'])) echo esc_attr($_POST['cmgt_gift']);?>" readonly/>
												<input type="hidden" name="hidden_upload_gift_file" value="<?php if($edit){ echo esc_attr($result->media_gift);}elseif(isset($_POST['upload_gift_file'])) echo esc_attr($_POST['upload_gift_file']);?>">
												<input id="upload_gift_file" name="upload_gift_file" type="file" class="form-control validate[required] file upload_user_cover_button cmgt_choose_btn_width"  onchange="showMyImage(upload_gift_file)"/>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
											<div id="upload_gym_cover_preview" style="min-height: 100px;">
												<img class="image_preview_css" id="thumbnil" style="max-width:100%;" src="<?php if($edit && $result->media_gift != ''){ echo esc_attr($result->media_gift);}elseif(isset($_POST['cmgt_gift'])) echo esc_attr($_POST['cmgt_gift']); else echo get_option( 'cmgt_gift_logo' );?>" />
											</div>
										</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<?php wp_nonce_field( 'save_gift_nonce' ); ?>
									<div class="offset-sm-0">
									<input type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Add','church_mgt');}?>" name="save_gift" class="btn btn-success save_btn save_gift"/>
									</div>
								</div>
							</div>
						</div>

					</form><!--Spiritual FORM END-->
				</div><!--PANEL BODY DIV END-->
        
			 <?php 
			}
			if($active_tab == 'sellgiftlist')
			{ 
				$own_data=$user_access['own_data'];
				$user_id=get_current_user_id();
				if($obj_church->role == 'accountant')
				{
					if($own_data == '1')
					{ 
						$giftdata=$obj_gift->MJ_cmgt_get_all_sell_gifts_created_by();
					}
					else
					{
						$giftdata=$obj_gift->MJ_cmgt_get_all_sell_gifts();
					}
				}
				else
				{
						if($own_data == '1')
						{ 
							$giftdata=$obj_gift->MJ_cmgt_get_all_sell_gifts_member_id($user_id);
						}
						else
						{
							$giftdata=$obj_gift->MJ_cmgt_get_all_sell_gifts();
						}
				}	
				if(!empty($giftdata))
				{
					?>	
					<script type="text/javascript">
						$(document).ready(function()
						{
							jQuery('#sell_gift_list').DataTable({
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
										{"bSortable": true},
										{"bSortable": true},
										{"bSortable": false}]
							});	
							$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
						} );
					</script>
					<div class="padding_left_15px"><!--PANEL BODY DIV START-->
						<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
							<table id="sell_gift_list" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Invoice Number', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Date', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Price', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Name', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Invoice Number', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Date', 'church_mgt' ) ;?></th>
										<th><?php  _e( 'Gift Price', 'church_mgt' ) ;?></th>
										<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
									</tr>
								</tfoot>
								<tbody>
									<?php 
									$i=0;
									if(!empty($giftdata))
									{
										foreach ($giftdata as $retrieved_data)
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
													<p class="padding_15px prescription_tag margin_bottom_0 <?php echo $color_class; ?>">	
														<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Sell-Gift-white.png"?>" alt="" class="massage_image center">
													</p>
												</td>
												<td class="giftname">
													<a class="color_black show-invoice-popup-s" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="sell_gift" href="#">
														<?php echo MJ_cmgt_church_get_display_name(esc_attr($retrieved_data->member_id));?>
													</a> 
												</td>
												<td class="gift">
													<?php echo MJ_cmgt_church_get_gift_name(esc_attr($retrieved_data->gift_id));?> 
												</td>
												<td class="gift">
													<?php 
													$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($retrieved_data->id));
													echo get_option( 'cmgt_payment_prefix' ).''.$invoice_number;?> 
												</td>
												<td class="sell_date">
													<?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($retrieved_data->sell_date)));?> 
												</td>
												<td class="giftprice width_15_per">
													<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($retrieved_data->gift_price);?> 
												</td>
												<td class="action cmgt_pr_0px">
													<div class="cmgt-user-dropdown mt-2">
														<ul class="">
															<!-- BEGIN USER LOGIN DROPDOWN -->
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																</a>
																<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">

																	<li>
																		<a href="?church-dashboard=user&&page=spiritual-gift&tab=view_invoice&idtest=<?php echo esc_attr($retrieved_data->id);?>&invoice_type=sell_gift" class="dropdown-item " ><i class="fa fa-eye"></i><?php _e('View Invoice', 'church_mgt' ) ;?></a>
																	</li>
																	<?php 
																	if($user_access['add'] == '1')
																	{
																		?>
																		<li>
																			<a href="?church-dashboard=user&&page=spiritual-gift&tab=sellgift&action=edit&sell_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a>
																		</li>
																		<?php
																	} 
																	if($user_access['delete'] == '1')
																	{
																		?>
																		<div class="cmgt-dropdown-deletelist">
																			<li>
																				<a href="?church-dashboard=user&&page=spiritual-gift&tab=sellgiftlist&action=delete&sell_id=<?php echo esc_attr($retrieved_data->id);?>" class="dropdown-item" 
																				onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?> </a>
																			</li>
																		</div>
																		<?php
																	}
																	?>
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
									} ?>
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
							<a href="<?php echo home_url().'?church-dashboard=user&page=spiritual-gift&tab=sellgift';?>">
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
			if($active_tab == 'sellgift')
			{
				$sell_id=0;
				if(isset($_REQUEST['sell_id']))
				$sell_id= sanitize_text_field($_REQUEST['sell_id']);
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = $obj_gift->MJ_cmgt_get_single_sell_gift($sell_id);
				}?>
				<div class="panel-body padding_0"><!--PANEL BODY DIV START-->
					<form name="sell_gift_form" action="" method="post" class="form-horizontal" id="sell_gift_form"><!--Spiritual FORM STRAT-->
						<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
						<input type="hidden" name="action" value="<?php echo $action;?>">
						<input type="hidden" name="sell_id" value="<?php echo $sell_id;?>"  />
						<div class="form-body user_form">
							<div class="row">
								<div class="col-md-6 cmgt_display">
									<label class="ml-1 custom-top-label top" for="day"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>								
									<select class="form-control line_height_30px" name="member_id" id="member_list">		
										<option value=""><?php _e('Select Member','church_mgt');?></option>
										<?php
											if($edit)
											$member_id=$result->member_id;
										elseif(isset($_POST['member_id'])) 
											$member_id=$_POST['member_id'];
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
											<?php }
											}
										}?>
									</select>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="sell_date" class="form-control" type="text" name="sell_date" 
											value="<?php if($edit){ echo date("Y-m-d",strtotime(esc_attr($result->sell_date)));}elseif(isset($_POST['sell_date'])){ echo esc_attr($_POST['sell_date']);}else{ echo date("Y-m-d"); }?>" autocomplete="off" readonly>
											<label class="" for="sell_date"><?php _e('Date','church_mgt');?></label>
										</div>	
									</div>
								</div>
								<div class="col-md-6  cmgt_display">
									<label class="ml-1 custom-top-label top" for="gift_id"><?php _e('Gift','church_mgt');?><span class="require-field">*</span></label>
									<?php if($edit){ $gift_id=$result->gift_id; }elseif(isset($_POST['gift_id'])){$gift_id=$_POST['gift_id'];}else{$gift_id='';}?>
									<select id="gift_id" class="form-control validate[required]" name="gift_id">
										<option value=""><?php _e('Select Gift','church_mgt');?></option>
											<?php 
												if($edit)
													$gift_id= sanitize_text_field($result->gift_id);
												elseif(isset($_POST['gift_id'])) 
													$gift_id= sanitize_text_field($_POST['gift_id']);
												else
													$gift_id=0;
													$giftdata=$obj_gift->MJ_cmgt_get_all_gifts();
												if(!empty($giftdata))
												{
													foreach ($giftdata as $gift)
													{?>
														<option value="<?php echo $gift->id;?>" <?php selected($gift_id,$gift->id);  ?>><?php echo $gift->gift_name; ?> </option>
														<?php
													} 
												} ?>
									</select>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
									<div class="col-md-12 form-control">
											<input id="gift_price" class="form-control validate[required,custom[amount]] text-input"  maxlength="8" type="text" value="<?php if($edit){ echo esc_attr($result->gift_price);}elseif(isset($_POST['gift_price'])) echo esc_attr($_POST['gift_price']);?>" name="gift_price">
											<label class="" for="gift_price"><?php _e('Gift Price','church_mgt');?>(<?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mt-2">
									<div class="offset-sm-0">
										<input id="sell_gift" type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Add Sell Gift','church_mgt');}?>" name="sell_gift" class="btn btn-success save_btn"/>
									</div>
								</div>
							</div>

					</form><!--Spiritual FORM END-->
				</div><!--PANEL BODY DIV END-->
				<?php 	
			} 
			if($active_tab == 'view_invoice')
			{
				$invoice_type=$_REQUEST['invoice_type'];
				$invoice_id=$_REQUEST['idtest'];
				MJ_cmgt_view_invoice_page($invoice_type,$invoice_id);
			} ?>
		</div><!--TAB CONTENT DIV END-->
	</div><!--PANEL WHITE DIV END-->
<?php ?>