<?php
	MJ_cmgt_header();
?>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;" >
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list">
			</div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('document');
	if($user_access == 'administrator')
	{
		$user_access_add=1;
		$user_access_edit=1;
		$user_access_delete=1;
		$user_access_view=1;
	}
	else
	{
		$user_access_view = $user_access['view'];
		$user_access_add = $user_access['add'];
		$user_access_edit = $user_access['edit'];
		$user_access_delete = $user_access['delete'];

		if (isset($_REQUEST['page'])) 
		{
			if ($user_access_view == '0') 
			{
				mj_cmgt_access_right_page_not_access_message_admin_side();
				die;
			}
			if(!empty($_REQUEST['action']))
			{
				if ('document' == $user_access['page_link'] && $_REQUEST['action'] == 'edit') 
				{
					if ($user_access_edit == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('document' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
				{	
					if ($user_access_add == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
				if ('document' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
				{	
					if ($user_access_delete == '0') 
					{
						mj_cmgt_access_right_page_not_access_message_admin_side();
						die;
					}
				}
			}
		}
	}
?>
<!-- user redirect url enter code end -->
<script type="text/javascript">
$(document).ready(function()
{
	//------------ CLOSE MESSAGE ---------//
	$('.notice-dismiss').click(function() {
		$('#message').hide();
	}); 
}); 
</script>
<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'documentlist';
$obj_document=new cmgt_document;
?>
<div class="page-inner"><!-- PAGE INNNER DIV START-->
	<?php 
	 if(isset($_REQUEST['save_document']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_document_nonce' ) )
		{
				//------------ EDIT DOCUMENTS ------------//
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
			$result=$obj_document->MJ_cmgt_add_document($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-document&tab=documentlist&message=2');
				}
				else
				{	
					wp_redirect ( admin_url().'admin.php?page=cmgt-document&tab=documentlist&message=1');
				}
			}
		}
		}
	}
	//------------ DELETE DOCUMENTS ------------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
			{
				
				$result=$obj_document->MJ_cmgt_delete_document($_REQUEST['document_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-document&tab=documentlist&message=3');
				}
			} 

			if(isset($_REQUEST['delete_selected']))
			{	
				if(!empty($_REQUEST['selected_id']))
				{
					
					foreach($_REQUEST['selected_id'] as $_REQUEST['document_id'])
					{
						$result=$obj_document->MJ_cmgt_delete_document($_REQUEST['document_id']);
						wp_redirect ( admin_url().'admin.php?page=cmgt-document&tab=documentlist&message=3');
					}
				}
				else
				{
						wp_redirect ( admin_url().'admin.php?page=cmgt-document&tab=documentlist&message=5');
				}
			}
	?>
		
	<div id=""><!-- MAIN WRAPPER DIV START--> 
		<div class="row"><!-- ROW DIV START--> 
			<div class="col-md-12"><!-- COL 12 DIV START-->  
				<div class="panel panel-white main_home_page_div"><!-- PANEL WHITE DIV START-->  
					<?php
						if(isset($_REQUEST['message']))
						{
							$message =$_REQUEST['message'];
							if($message == 1)
							{?>
									<div id="message" class="updated below-h2 notice is-dismissible">
									<p>
									<?php 
										_e('Record inserted successfully','church_mgt');
									?></p></div>
									<?php 
							}
							elseif($message == 2)
							{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
										_e("Record updated successfully.",'church_mgt');
										?></p>
										</div>
									<?php 
							}
							elseif($message == 3) 
							{?>
							<div id="message" class="updated below-h2 notice is-dismissible"><p>
							<?php 
								_e('Record deleted successfully','church_mgt');
							?></div></p><?php
							}
							elseif($message == 4) 
							{?>
							<div id="message" class="updated below-h2 notice is-dismissible"><p>
							<?php 
								_e('Group Members Added successfully','church_mgt');
							?></div></p><?php
							}
							elseif($message == 5) 
							{
								?>
								<div id="message" class="updated below-h2 notice is-dismissible">
									<p>
									<?php 
										_e('Please select at least one record.','church_mgt');
									?></p>
								</div>
								<?php
							}
						}
					?>
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<?php 
						//Report 1 
						if($active_tab == 'documentlist')
						{ 
							$documentdata=$obj_document->MJ_cmgt_get_all_document();
							if(!empty($documentdata))
							{
								?>	
								<script type="text/javascript">
									$(document).ready(function() {
									jQuery('#document_list').DataTable({
										//"responsive": true,
										"dom": 'lifrtp',
										language:<?php echo MJ_cmgt_datatable_multi_language();?>,
										"order": [[ 2, "asc" ]],
										"sSearch": "<i class='fa fa-search'></i>",
										"aoColumns":[
														{"bSortable": false},
														{"bSortable": false},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": true},
														{"bSortable": false}]
										});
										$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
										$('.select_all').on('click', function(e)
											{
													if($(this).is(':checked',true))  
													{
													$(".sub_chk").prop('checked', true);  
													$(".select_all").prop('checked', true);	
													}  
													else  
													{  
													$(".sub_chk").prop('checked',false);  
													$(".select_all").prop('checked', false);
													} 
											});
										
											$('.sub_chk').on('change',function()
											{ 
												if(false == $(this).prop("checked"))
												{ 
													$(".select_all").prop('checked', false); 
												}
												if ($('.sub_chk:checked').length == $('.sub_chk').length )
												{
													$(".select_all").prop('checked', true);
												}
											});
									} );
								</script>
								<form name="document_form" action="" method="post"><!-- ACTIVITY FORM START-->
									<div class="panel-body"><!-- PANEL BODY DIV START-->
										<div class="table-responsive">
											<table id="document_list"  class="display" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Documant Name', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Document Created By', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Document Created Date', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Description', 'church_mgt' ) ;?></th>
														<th><?php  _e('View Document','church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Documant Name', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Document Created By', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Document Created Date', 'church_mgt' ) ;?></th>
														<th><?php  _e( 'Description', 'church_mgt' ) ;?></th>
														<th><?php  _e('View Document','church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
													<?php 
													$i=0;
													if(!empty($documentdata))
													{
														foreach ($documentdata as $retrieved_data)
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
																<td class="cmgt-checkbox_width_10px"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo esc_attr($retrieved_data->document_id); ?>"></td>

																<td class="user_image width_50px profile_image_prescription padding_left_0">
																	<p class="padding_15px prescription_tag <?php echo $color_class; ?>">	
																		<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Documents-white.png"?>" alt="" class="massage_image center">
																	</p>
																</td>
																<td class="name width_15_per"><a class="color_black view_document" id="<?php echo esc_attr($retrieved_data->document_id);?>" href="#"><?php echo esc_attr(ucfirst($retrieved_data->document_name));?></a> </td>
																
																<?php $user_info = get_userdata($retrieved_data->ducument_create_by);
																if(isset($user_info->user_login))
																$username = $user_info->user_login;
																?>
																<td class="width_15_per"><?php if(!empty($username)){echo esc_attr($username);}else{echo "N/A";} ?> </td>
																<td class="width_15_per"><?php echo $retrieved_data->created_date; ?> </td>
																<td class="width_25_per"> <?php echo esc_attr(wp_trim_words($retrieved_data->description,4,'...')); ?> </td>	
																<td class="">
																<?php 
																//var_dump($retrieved_data->document);
																// die;
																	if(trim($retrieved_data->document) != "")
																	{
																		echo '<a  target="blank" href="'.esc_url($retrieved_data->document).'" class="btn btn-default"><i class="fa fa-eye"></i> '.esc_html__("View","church_mgt").'</a>';
																	}
																	else 
																	{
																		echo esc_html( __( 'No any document', 'church_mgt' ) );
																	}
																?>
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

																					<li><a class="dropdown-item view_document" id="<?php echo esc_attr($retrieved_data->document_id);?>" href="#"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																					<?php
																						if ($user_access_edit == 1) 
																						{ 
																					?>
																					<li><a href="?page=cmgt-document&tab=add_document&action=edit&document_id=<?php echo esc_attr($retrieved_data->document_id);?>" 
																					class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																					<?php
																						}
																					?>
																					
																					<div class="cmgt-dropdown-deletelist">
																					<?php
																						if ($user_access_delete == 1) 
																						{ 
																					?>
																						<li><a href="?page=cmgt-document&tab=documentlist&action=delete&document_id=<?php echo esc_attr($retrieved_data->document_id);?>" 
																						class="dropdown-item" 
																						onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i>
																						<?php _e( 'Delete', 'church_mgt' ) ;?> </a></li>
																						<?php
																						}
																						?>
																					</div>
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
													} 
													?>
												</tbody>
											</table>
											<div class="print-button pull-left cmgt_print_btn_p0">
												<button class="btn btn-success btn-niftyhms">
													<input type="checkbox" name="selected_id[]" class="select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">
													<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'church_mgt' ) ;?></label>
												</button>
												<?php
													if ($user_access_delete == 1) 
													{ 
												?>
												<button data-toggle="tooltip" title="<?php esc_html_e('Delete All','church_mgt');?>" name="delete_selected" class="delete_all_button"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Delete.png" ?>" alt=""></button>
												<?php
												}
												?>
											</div>
										</div>
									</div><!-- PANEL BODY DIV START-->
								</form><!-- ACTIVITY FORM END-->
								<?php 
							}
							else
							{
								?>
								<div class="no_data_list_div"> 
									<a href="<?php echo admin_url().'admin.php?page=cmgt-document&tab=add_document';?>">
										<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
									</div> 
								</div>		
								<?php
							}
						}
						if($active_tab == 'add_document')
						{
						require_once CMS_PLUGIN_DIR. '/admin/document/add_document.php';
						}
						?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->