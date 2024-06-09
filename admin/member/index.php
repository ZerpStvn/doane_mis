<script type="text/javascript">
	$(document).ready(function() 
	{
		//------------ CLOSE MESSAGE ---------//
		$('.notice-dismiss').click(function() {
			$('#message').hide();
		}); 
	} );				
</script>
<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('member');
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
			if ($user_access['page_link'] == "member" && ($_REQUEST['action']=="edit"))
			{
				if ($user_access_edit == '0') 
				{
					mj_cmgt_access_right_page_not_access_message_admin_side();
					die;
				}
			}
			if ('member' == $user_access['page_link'] && $_REQUEST['action'] == 'add') 
			{	
				if ($user_access_add == '0') 
				{
					mj_cmgt_access_right_page_not_access_message_admin_side();
					die;
				}
			}
			if ('member' == $user_access['page_link'] && $_REQUEST['action'] == 'delete') 
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
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list">
			</div>	
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->

<div class="page-inner margin_right_10px"><!-- PAGE INNER DIV START-->
	<?php 
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'memberlist';
	$obj_group=new Cmgtgroup;
	$obj_member=new Cmgtmember;
	$obj_ministry=new Cmgtministry;
	if(isset($_POST['save_member']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_member_nonce' ) )
		{
			//------ EDIT MEMBER ------//
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$txturl=$_POST['cmgt_user_avatar'];
				$ext=MJ_cmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{
					$result=$obj_member->MJ_cmgt_add_user($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=memberlist&message=2');
					}
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php?page=cmgt-member&tab=memberlist&message=5');
				}
			}
			else
			{
				//------- ADD MEMBER ----------//
				if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) 
				{
					$txturl=$_POST['cmgt_user_avatar'];
					$ext=MJ_cmgt_check_valid_extension($txturl);
					if(!$ext == 0)
					{
						$result=$obj_member->MJ_cmgt_add_user($_POST);
						if($result>0)
						{
							wp_redirect ( admin_url() . 'admin.php?page=cmgt-member&tab=memberlist&message=1');
						}
					}
					else
					{
						wp_redirect ( admin_url() . 'admin.php?page=cmgt-member&tab=addmember&message=1');
				
					}
				}
				else
				{	
					?>
					<div id="message" class="updated below-h2 notice is-dismissible">
						<p><?php _e('Username Or Emailid All Ready Exist.','church_mgt');?></p> 
						<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>		
					<?php  
				}
			}
		}
	}
	//------------ DELETE MEMBER ------------//
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$obj_member->MJ_cmgt_delete_usedata($_REQUEST['member_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=memberlist&message=3');
			//wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=memberlist');
		}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['selected_id']))
		{
			
			foreach($_REQUEST['selected_id'] as $_REQUEST['member_id'])
			{
				$result=$obj_member->MJ_cmgt_delete_usedata($_REQUEST['member_id']);
				wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=memberlist&message=3');
			}
		}
		else
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=memberlist&message=10');
		}
	}
		
	if(isset($_POST['download_csv_file']))
	{	
		$member_data = get_users(array('role'=>'member'));
		if(!empty($member_data))
		{
			$header = array();	
			$header[] = 'Username';
			$header[] = 'Email';
			$header[] = 'Password';
			$header[] = 'member_id';
			$header[] = 'first_name';
			$header[] = 'middle_name';
			$header[] = 'last_name';
			$header[] = 'gender';
			$header[] = 'birth_date';
			$header[] = 'address';
			$header[] = 'city_name';
			$header[] = 'mobile_number';
			$header[] = 'phone';
			$header[] = 'fax_number';
			$header[] = 'skype_id';
			$header[] = 'join_church_date';
			$header[] = 'baptist_date';
			$header[] = 'Volunteer';
			$header[] = 'occupation';
			$header[] = 'education';
			$header[] = 'marital_status';
			$filename='member_data/member_data.csv';
			$fh = fopen(CMS_PLUGIN_DIR.'/'.$filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			foreach($member_data as $retrive_data)
			{
				// var_dump($retrive_data);
				// die;
				$row = array();
				$user_info = get_userdata($retrive_data->ID);
				$row[] = $user_info->user_login;
				$row[] = $user_info->user_email;	
				$row[] = $user_info->user_pass;
				$row[] =  get_user_meta($retrive_data->ID, 'member_id',true);
				$row[] =  get_user_meta($retrive_data->ID, 'first_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'middle_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'last_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'gender',true);
				$row[] =  get_user_meta($retrive_data->ID, 'birth_date',true);
				$row[] =  get_user_meta($retrive_data->ID, 'address',true);
				$row[] =  get_user_meta($retrive_data->ID, 'city_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'mobile',true);
				$row[] =  get_user_meta($retrive_data->ID, 'phone',true);
				$row[] =  get_user_meta($retrive_data->ID, 'fax_number',true);
				$row[] =  get_user_meta($retrive_data->ID, 'skyp_id',true);
				$row[] =  get_user_meta($retrive_data->ID, 'begin_date',true);
				$row[] =  get_user_meta($retrive_data->ID, 'baptist_date',true);
				$row[] =  get_user_meta($retrive_data->ID, 'volunteer',true);
				$row[] =  get_user_meta($retrive_data->ID, 'occupation',true);
				$row[] =  get_user_meta($retrive_data->ID, 'education',true);
				$row[] =  get_user_meta($retrive_data->ID, 'marital_status',true);
				fputcsv($fh, $row);
			}
				fclose($fh);
				//download csv file.
				ob_clean();
				$file=CMS_PLUGIN_DIR.'/member_data/member_data.csv';//file location
					$mime = 'text/plain';
					header('Content-Type:application/force-download');
					header('Pragma: public');       // required
					header('Expires: 0');           // no cache
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
					header('Cache-Control: private',false);
					header('Content-Type: '.$mime);
					header('Content-Disposition: attachment; filename="'.basename($file).'"');
					header('Content-Transfer-Encoding: binary');
					//header('Content-Length: '.filesize($file_name));      // provide file size
					header('Connection: close');
					readfile($file);
					exit;							
		}
			
	}
	if(isset($_REQUEST['upload_csv_file']))
	{
		if(isset($_FILES['csv_file']))
		{
			$errors= array();
			$file_name = $_FILES['csv_file']['name'];
			$file_size =$_FILES['csv_file']['size'];
			$file_tmp =$_FILES['csv_file']['tmp_name'];
			$file_type=$_FILES['csv_file']['type'];
			$value = explode(".", $_FILES['csv_file']['name']);
			$file_ext = strtolower(array_pop($value));
			$extensions = array("csv");
			$upload_dir = wp_upload_dir();
			
			if(in_array($file_ext,$extensions )=== false)
			{
				$errors[]="this file not allowed, please choose a CSV file.";
				//wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=uploadcsvfile&message=6');
				wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=memberlist&message=6');
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=uploadcsvfile&message=7');
			}
			if(empty($errors)==true)
			{
				
				$rows = array_map('str_getcsv', file($file_tmp));		
				$header = array_map('strtolower',array_shift($rows));
				// var_dump($rows);
				// die;
				$csv = array();
				foreach ($rows as $row)
				{
					// var_dump($row);
					// die;
					$csv = array_combine($header, $row);
					$csv_member_id = $csv['member_id'];
					// var_dump($csv_member_id);
					// die;
					$user_array = get_users( array(
					"meta_key" => "member_id",
					"meta_value" => $csv_member_id
				
					) ); 
					
					if(empty($user_array))
					{
						
						$member_id = $csv['member_id'];
						$txturl=$_POST['cmgt_user_avatar'];
						$ext=MJ_cmgt_check_valid_extension($txturl);
						
						if(!$ext == 0)
						{
							
							$result=$obj_member->MJ_cmgt_add_user($row);
							// var_dump($result);
							// die;
							if($result > 0)
							{
								wp_redirect ( admin_url() . 'admin.php?page=cmgt-member&tab=memberlist&message=8');
							}
						}
						
				
					}
					else
					{
						$member_id = $csv['member_id'];
					
						
						$username = $csv['username'];
						// $userdata['display_name']
						$email = $csv['email'];
						$user_id = 0;
						$password = $csv['password'];
						$problematic_row = false;
						
						if( username_exists($username) )
						{ // if user exists, we take his ID by login
							$user_object = get_user_by( "login", $username );
							$user_id = $user_object->ID;
							if( !empty($password))
								wp_set_password( $password, $user_id );
						}
						elseif( email_exists( $email ) )
						{ // if the email is registered, we take the user from this
							$user_object = get_user_by( "email", $email );
							$user_id = $user_object->ID;					
							$problematic_row = true;
							if( !empty($password) )
								wp_set_password( $password, $user_id );
						}
						else
						{
							if( empty($password) ) // if user not exist and password is empty but the column is set, it will be generated
							$password = wp_generate_password();

							$userdata['user_login']=sanitize_user($username);
							$userdata['user_email']=sanitize_email( $email);
							$userdata['display_name']=sanitize_text_field($csv['first_name'])." ".sanitize_text_field($csv['last_name']);
							$userdata['user_pass']=sanitize_text_field($password);

							$user_id = wp_insert_user( $userdata );
							//$user_id = wp_create_user($username, $password, $email);
						}
						if( is_wp_error($user_id) )
						{ // in case the user is generating errors after this checks
							echo '<script>alert("<?php _e("Problems with user: ' . $username . ', we are going to skip","church_mgt");?>");</script>';
							continue;
						}
						
						if(!( in_array("administrator", MJ_church_get_roles($user_id), FALSE) || is_multisite() && is_super_admin( $user_id ) ))
						wp_update_user(array ('ID' => $user_id, 'role' => 'member')) ;
						update_user_meta( $user_id, "active", true );
						if(isset($csv['member_id']))
							update_user_meta( $user_id, "member_id", $member_id );
						if(isset($csv['first_name']))
							update_user_meta( $user_id, "first_name", $csv['first_name'] );
						if(isset($csv['last_name']))
							update_user_meta( $user_id, "last_name", $csv['last_name'] );
						if(isset($csv['middle_name']))
							update_user_meta( $user_id, "middle_name", $csv['middle_name'] );
						if(isset($csv['gender']))
							update_user_meta( $user_id, "gender", $csv['gender'] );
						if(isset($csv['birth_date']))
							update_user_meta( $user_id, "birth_date",  MJ_cmgt_get_format_for_db($csv['birth_date']) );
						if(isset($csv['address']))
						update_user_meta( $user_id, "address", $csv['address'] );
						if(isset($csv['city_name']))
						update_user_meta( $user_id, "city_name", $csv['city_name'] );
						if(isset($csv['fax_number']))
							update_user_meta( $user_id, "fax_number", $csv['fax_number'] );						
						if(isset($csv['skype_id']))
							update_user_meta( $user_id, "skyp_id", $csv['skype_id'] );
						if(isset($csv['mobile_number']))
							update_user_meta( $user_id, "mobile", $csv['mobile_number'] );
						if(isset($csv['phone']))
							update_user_meta( $user_id, "phone", $csv['phone'] );
						if(isset($csv['join_church_date']))
							update_user_meta( $user_id, "begin_date",  MJ_cmgt_get_format_for_db($csv['join_church_date']) );
						if(isset($csv['baptist_date']))
							update_user_meta( $user_id, "baptist_date", MJ_cmgt_get_format_for_db($csv['baptist_date'] ));
						if(isset($csv['volunteer']))
							update_user_meta( $user_id, "volunteer", $csv['volunteer'] );
						if(isset($csv['occupation']))
							update_user_meta( $user_id, "occupation", $csv['occupation'] );
						if(isset($csv['education']))
							update_user_meta( $user_id, "education", $csv['education'] );
						if(isset($csv['marital_status']))
							update_user_meta( $user_id, "marital_status", $csv['marital_status'] );
						
						$success = 1;
					}
					
				}
			}
			else
			{
				foreach($errors as &$error) echo $error;
			}
			if(isset($success))
			{
			?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Member CSV Uploaded Successfully.','church_mgt');?></p>
					<button type="button" class="notice-dismiss margin_top_12px" ><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
			<?php
			} 
		}	
	}
	?>
	<?php 
	if(isset($_REQUEST['action'] ) && $_REQUEST['action']=='active_member')
	{
		
		if( get_user_meta($_REQUEST['member_id'], 'cmgt_hash', true))
		{ 
			$result=delete_user_meta($_REQUEST['member_id'], 'cmgt_hash');
			//member ragistation mail template send  mail
			$user_info = get_userdata($_REQUEST['member_id']);
			$to = $user_info->user_email; 
			$member_name=$user_info->display_name;
			$loginlink=home_url();
			$subject =get_option('WPChurch_Member_Approve_Subject');
			$church_name=get_option('cmgt_system_name');
			$message_content=get_option('WPChurch_Member_Approve_Template');
			$subject_search=array('[CMGT_CHURCH_NAME]');
			$subject_replace=array($church_name);
			$search=array('[CMGT_MEMBERNAME]','[CMGT_CHURCH_NAME]','[CMGT_LOGIN_LINK]');
			$replace = array($member_name,$church_name,$loginlink);
			$message_content = str_replace($search, $replace, $message_content);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
			if($result)
			wp_redirect ( admin_url().'admin.php?page=cmgt-member&tab=memberlist&message=4');
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
							<div id="message" class="updated below-h2 notice is-dismissible ">
								<p>
								<?php 
									_e('Record inserted successfully','church_mgt');
								?></p>
								<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
							</div>
							<?php 
						}
						elseif($message == 2)
						{?><div id="message" class="updated below-h2 notice is-dismissible "><p><?php
								_e("Record updated successfully.",'church_mgt');
									?></p>
									<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
							</div>
						<?php 
						}
						elseif($message == 3) 
						{?>
							<div id="message" class="updated below-h2 notice is-dismissible"><p>
							<?php 
								_e('Record deleted successfully','church_mgt');
							?></p>
							<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
							</div><?php
						}
							elseif($message == 4) 
						{?>
							<div id="message" class="updated below-h2 notice is-dismissible"><p>
							<?php 
								_e('Member Approved successfully','church_mgt');
							?></p>
							<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
							</div><?php
						}
						elseif($message == 5) 
						{?>
							<div id="message" class="updated below-h2 notice is-dismissible"><p>
							<?php 
								_e('Only jpeg ,jpg ,png and gif files are allowed!.','church_mgt');
							?></p>
							<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
							</div><?php
						}
						elseif($message == 6) 
						{?>
							<div id="message" class="updated below-h2 notice is-dismissible"><p>
							<?php 
								_e('Only CSV file are allow.','church_mgt');
							?></p>
							<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
							</div><?php
						}
					
						elseif($message == 7) 
						{?>
						<div id="message" class="updated below-h2 notice is-dismissible"><p>
						<?php 
							_e('File size limit 2 MB allow.','church_mgt');
						?></p>
						<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
						</div><?php
						}
						elseif($message == 8) 
						{?>
						<div id="message" class="updated below-h2 notice is-dismissible"><p>
							<?php 
							_e('Member CSV Uploaded Successfully. ');
							?></p>
							<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
						</div><?php
						}
						elseif($message == 9) 
						{?>
						<div id="message" class="updated below-h2 notice is-dismissible"><p>
						<?php 
							_e('Please select at least one record.','church_mgt');
						?></p>
						<button type="button" class="notice-dismiss" ><span class="screen-reader-text">Dismiss this notice.</span></button>
						</div><?php
						}
						elseif($message == 10) 
						{?>
						<div id="message" class="updated below-h2 notice is-dismissible "><p>
						<?php 
							_e('Please select at least one record.','church_mgt');
						?></div></p><?php
								
						}
					}
					?>
					<div class="panel-body av_tab_responsive_4_tab"><!-- PANEL BODY DIV START-->
						 <?php 
						//Report 1 
						if($active_tab == 'memberlist')
						{ 
							$get_members = array('role' => 'member');
							$membersdata=get_users($get_members);
							if(!empty($membersdata))
							{
								?>	
								<script type="text/javascript">
									$(document).ready(function() 
									{
										jQuery('#members_list').DataTable({
										// "responsive": true,
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
								
								<form name="member_form" action="" method="post"><!-- MEMBER FORM START-->
									<div class="panel-body"><!-- PANEL BODY DIV START-->
										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
											<table id="members_list" class="display" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
														<th><?php _e( 'Member Name & Email', 'church_mgt' ) ;?></th>
														<th><?php _e( 'Gender', 'church_mgt' ) ;?></th>
														<th><?php _e( 'Member Id', 'church_mgt' ) ;?></th>
														<th><?php _e( 'Volunteer Member', 'church_mgt' ) ;?></th>
														<th> <?php _e( 'Join Date', 'church_mgt' ) ;?></th>
														<th> <?php _e( 'Mobile No.', 'church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Photo', 'church_mgt' ) ;?></th>
														<th><?php _e( 'Member Name & Email', 'church_mgt' ) ;?></th>
														<th><?php _e( 'Gender', 'church_mgt' ) ;?></th>
														<th><?php _e( 'Member Id', 'church_mgt' ) ;?></th>
														<th><?php _e( 'Volunteer Member', 'church_mgt' ) ;?></th>
														<th> <?php _e( 'Join Date', 'church_mgt' ) ;?></th>
														<th> <?php _e( 'Mobile No.', 'church_mgt' ) ;?></th>
														<th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
												<?php 
													if(!empty($membersdata))
													{
														foreach ($membersdata as $retrieved_data)
														{
															?>
															<tr>
																<td class="cmgt-checkbox_width_10px"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo esc_attr($retrieved_data->id); ?>"></td>
																
																
																<td class="user_image cmgt-checkbox_width_50px padding_left_0">
																	<?php $uid=$retrieved_data->ID;
																		$userimage=get_user_meta($uid, 'cmgt_user_avatar', true);
																		if(empty($userimage))
																		{
																			echo '<img src='.get_option( 'cmgt_member_thumb' ).' height="50px" width="50px" class="img-circle" />';
																		}
																		else
																			echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
																	?>
																</td>
																<td class="name">
																	<a class="color_black" href="?page=cmgt-member&tab=viewmember&action=view&member_id=<?php echo $retrieved_data->ID;?>"><?php echo esc_html($retrieved_data->display_name);?></a>
																	<br>
																	<label class="email_color"><?php echo esc_html($retrieved_data->user_email);?></label>
																</td>
																<td class="gender">
																	<?php echo _e(ucfirst($retrieved_data->gender) , 'church_mgt');?> 
																</td>
																<td class="memberid"><?php if(!empty($retrieved_data->member_id)){echo $retrieved_data->member_id;}else{ echo "N/A";}?> </td>
																<td class="volunteer"><?php if($retrieved_data->volunteer=='yes') echo esc_html__("Yes","church_mgt"); else echo esc_html__("No","church_mgt"); ?> </td>
																		
																<td class="date"><?php echo esc_html(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->begin_date)));?> </td>
																
																<td class="mobile"><?php echo esc_attr($retrieved_data->phonecode).' '.get_user_meta($uid, 'mobile', true);?> </td>
																<td class="action cmgt_pr_0px"> 
																	<?php 
																	if( get_user_meta($retrieved_data->ID, 'cmgt_hash', true))
																	{ ?>
																	<a  href="?page=cmgt-member&action=active_member&member_id=<?php echo $retrieved_data->ID?>" class="btn btn-default" > <?php _e('Active', 'church_mgt');?></a>
																	<?php 
																	} ?>
																	<div class="cmgt-user-dropdown mt-2">
																		<ul class="">
																			<!-- BEGIN USER LOGIN DROPDOWN -->
																			<li class="">
																				<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																					<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
																				</a>
																				<ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
																					<li><a class="dropdown-item" href="?page=cmgt-member&tab=viewmember&action=view&member_id=<?php echo $retrieved_data->ID?>"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
																					<li><a class="dropdown-item view_gift_list cursor_poi_css" mem_id="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-gift margin_right_15px"></i> <?php _e('Gifts', 'church_mgt' ) ;?></a></li>
																					<?php
																						if ($user_access_edit == 1) 
																						{ 
																					?>
																					<li><a class="dropdown-item" href="?page=cmgt-member&tab=addmember&action=edit&member_id=<?php echo $retrieved_data->ID?>"><i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
																					<?php
																						}
																					?>
																					<div class="cmgt-dropdown-deletelist">
																					<?php
																						if ($user_access_delete == 1) 
																						{ 
																					?>
																						<li><a class="dropdown-item" href="?page=cmgt-member&tab=memberlist&action=delete&member_id=<?php echo $retrieved_data->ID;?>" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?></a></li>
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
														} 
													}?>
												</tbody>
											</table>
											<div class="print-button pull-left cmgt_print_btn_p0">
													<button class="btn btn-success btn-niftyhms">
														<input type="checkbox" name="selected_id[]" class="select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">
														<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'church_mgt' ) ;?></label>
													</button>
												<?php
												if($user_access_delete == 1)
												{
												?>
												<button data-toggle="tooltip" title="<?php esc_html_e('Delete All','church_mgt');?>" name="delete_selected" class="delete_all_button"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Delete.png" ?>" alt=""></button>
												<?php
												}
												if($user_access_add == 1)
												{
												?>
												<button data-toggle="tooltip" title="<?php esc_html_e('Export CSV','church_mgt');?>" name="download_csv_file" id="download_csv_headers" class="export_import_csv_btn"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/export_csv.png" ?>" alt=""></button>

												<button data-toggle="tooltip" title="<?php esc_html_e('Import CSV','church_mgt');?>" type="button" class=" export_import_csv_btn" id="cmgt_addremove" data-bs-toggle="modal" data-bs-target="#myModal_import_csv"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/import_csv.png" ?>" alt=""> </button>
												<?php
												}
												?>
												
											</div>
										</div><!-- TABLE RESPONSIVE DIV END-->
									</div><!-- PANEL BODY DIV END-->
								</form><!-- MEMBER FORM END-->
						 		<?php 
							}
							else
							{
								?>
								<div class="no_data_list_div row"> 
									<div class="offset-md-2 col-md-4">
										<a href="<?php echo admin_url().'admin.php?page=cmgt-member&tab=addmember';?>">
											<img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
										</a>
										<div class="col-md-12 dashboard_btn margin_top_20px">
											<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
										</div> 
									</div> 
									<div class="col-md-4">
										<a data-toggle="tooltip"  class=" export_import_csv_btn" id="cmgt_addremove" data-bs-toggle="modal" data-bs-target="#myModal_import_csv">
											<img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Import_list.png" ?>" alt="">
										</a>
										<div class="col-md-12 dashboard_btn margin_top_20px">
											<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to import CSV.','church_mgt'); ?> </label>
										</div> 
									</div>
								</div>		
								<?php
							}
						}
						if($active_tab == 'addmember')
						{
							require_once CMS_PLUGIN_DIR.'/admin/member/add_member.php';
						}
						if($active_tab == 'viewmember')
						{
							require_once CMS_PLUGIN_DIR.'/admin/member/view_member.php';
						}
						if($active_tab == 'view_invoice')
						{
							require_once CMS_PLUGIN_DIR.'/admin/member/view_invoice.php';
						}
						if($active_tab == 'uploadcsvfile')
						{
							require_once CMS_PLUGIN_DIR.'/admin/member/uploadmember.php';
						}
						 ?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->


<!-----  Upload Student From CSV--->

<?php 
//Upload Student From CSV
?>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('#upload_header_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	});
</script>
<div class="modal fade cmgt_main_modal" id="myModal_import_csv" tabindex="-1" aria-labelledby="myModal_import_csv" aria-hidden="true" role="dialog"><!-- MAIN MODAL DIV START-->
	<div class="modal-dialog modal-lg cmgt_popup_box_shadow" id="cmgt_csv_modal_top"><!-- MODAL DIALOG DIV START-->
        <div class="modal-content" id="cmgt_csv_modal_content"><!-- MODAL CONTENT DIV START-->
			<div class="modal-header">
			  	<h3 class="modal-title"><?php _e('Member','church_mgt');?>
					<a href="#" class="btn float-end mt-2 badge badge-danger rtl_float_left" data-bs-dismiss="modal">X</a>
				</h3>
			</div>
				
			<div class="modal-body"><!-- MODAL BODY DIV START-->
				<div class=""><!-- PANEL BODY DIV START-->
					<form name="upload_header_form" action="" method="post" class="cmgt_form_horizontal form-horizontal" id="upload_header_form" enctype="multipart/form-data">
						<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
						<div class="cmgt_form_body form-body user_form">
							<div class="row">
								<div class="col-md-9">
									<div class="form-group">
										<div class="col-md-12 form-control">
											
											<label class="custom-control-label custom-top-label ml-2 margin_left_30px" for="city_name"><?php _e('Select CSV file','church_mgt');?><span class="require-field">*</span></label>

											<div class="col-sm-12 cmgt_csv_mb_0">
												<input id="csv_file" type="file" name="csv_file" class="form-control validate[required]">
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 export_csv_button">
									<input id="upload_csv_headers" type="submit" value="<?php _e('Save','church_mgt');?>" name="upload_csv_file" class="btn btn-success col-md-12 save_btn rtl_margin_top_0px"/>
								</div>
							</div>
						</div>						
					</form>
				</div><!-- PANEL BODY DIV END-->
			</div>
		</div>
	</div>
</div>