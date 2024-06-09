<?php 
require_once CMS_PLUGIN_DIR. '/cmgt-function.php';
require_once CMS_PLUGIN_DIR. '/class/group.php';
require_once CMS_PLUGIN_DIR. '/class/member.php';
require_once CMS_PLUGIN_DIR. '/class/venue.php';
require_once CMS_PLUGIN_DIR. '/class/activity.php';
require_once CMS_PLUGIN_DIR. '/class/notice.php';
require_once CMS_PLUGIN_DIR. '/class/venue-reservation.php';
require_once CMS_PLUGIN_DIR. '/class/ministry.php';
require_once CMS_PLUGIN_DIR. '/class/gift.php';
require_once CMS_PLUGIN_DIR. '/class/check-in.php';
require_once CMS_PLUGIN_DIR. '/class/pledges.php';
require_once CMS_PLUGIN_DIR. '/class/transaction.php';
require_once CMS_PLUGIN_DIR. '/class/service.php';
require_once CMS_PLUGIN_DIR. '/class/song.php';
require_once CMS_PLUGIN_DIR. '/class/message.php';
require_once CMS_PLUGIN_DIR. '/class/MailChimp.php';
require_once CMS_PLUGIN_DIR. '/class/MCAPI.class.php';
require_once CMS_PLUGIN_DIR. '/class/sermon.php';
require_once CMS_PLUGIN_DIR. '/class/payment.php';
require_once CMS_PLUGIN_DIR. '/class/attendence.php';
require_once CMS_PLUGIN_DIR. '/class/church-management.php';
require_once CMS_PLUGIN_DIR. '/class/dashboard.php';
require_once CMS_PLUGIN_DIR. '/class/pastoral.php';
require_once CMS_PLUGIN_DIR. '/class/document.php';
require_once CMS_PLUGIN_DIR. '/lib/paypal/paypal_class.php';

add_action( 'admin_head', 'MJ_cmgt_admin_css' );
//ADMIN SIDE CSS FUNCTION
function MJ_cmgt_admin_css()
{
	?>
<style>
a.toplevel_page_cmgt-church_system:hover,  a.toplevel_page_cmgt-church_system:focus,.toplevel_page_cmgt-church_system.opensub a.wp-has-submenu{
  background: url("<?php echo CMS_PLUGIN_URL;?>/assets/images/church-management-2.png") no-repeat scroll 8px 9px rgba(0, 0, 0, 0) !important;
  
}
.toplevel_page_cmgt-church_system:hover .wp-menu-image.dashicons-before img {
  display: none;
}

.toplevel_page_cmgt-church_system:hover .wp-menu-image.dashicons-before {
  min-width: 23px !important;
}
</style>
<script>
	$(document).ready(function () 
	{
		$('#document').change(function () 
		{
			var val = $(this).val().toLowerCase();
			var regex = new RegExp("(.*?)\.(docx|doc|pdf|xml|bmp|ppt|xls)$");
			if(!(regex.test(val))) 
			{
				$(this).val('');
				alert("<?php esc_html_e('Please select only docx,docx,pdfd ,bmp,ppt and xls file format allow','church_mgt');?>");
			} 
		}); 
	});
</script> 
<?php
}	
//SESSION MANAGER FUNCTION
add_action('init', 'MJ_cmgt_session_manager'); 
function MJ_cmgt_session_manager() 
{
	if (!session_id()) 
	{
		session_start();
		if(!isset($_SESSION['amgt_verify']))
		{
			$_SESSION['amgt_verify'] = '';
		}
	}
}
//LOGOUT FUNCTION 
function MJ_cmgt_logout()
{
	if(isset($_SESSION['cmgt_verify']))
	{ 
	  unset($_SESSION['cmgt_verify']);
	}
	wp_redirect( home_url().'/church-management-login-page' );
	exit();
}
add_action('wp_logout','MJ_cmgt_logout');
add_action('init','MJ_cmgt_setup');
function MJ_cmgt_setup()
{
	$is_cmgt_pluginpage = MJ_cmgt_is_cmgtpage();
	$is_verify = false;
	if(!isset($_SESSION['cmgt_verify']))
		$_SESSION['cmgt_verify'] = '';
	$server_name = $_SERVER['SERVER_NAME'];
	$is_localserver = MJ_cmgt_chekserver($server_name);
	if($is_localserver)
	{
		return true;
	}
	
	if($is_cmgt_pluginpage)
	{	
		if($_SESSION['cmgt_verify'] == '')
		{
			if( get_option('licence_key') && get_option('cmgt_setup_email'))
			{
				$domain_name = $_SERVER['SERVER_NAME'];
				$licence_key = get_option('licence_key');
				$email = get_option('cmgt_setup_email');
				$result = MJ_cmgt_check_productkey($domain_name,$licence_key,$email);
				$is_server_running = MJ_cmgt_check_ourserver();
				if($is_server_running)
					$_SESSION['cmgt_verify'] =$result;
				else
					$_SESSION['cmgt_verify'] = '0';
				$is_verify = MJ_cmgt_check_verify_or_not($result);
			}
		}
	}
	$is_verify = MJ_cmgt_check_verify_or_not($_SESSION['cmgt_verify']);
	if($is_cmgt_pluginpage)
		if(!$is_verify)
		{
			if($_REQUEST['page'] != 'cmgt-setup')
			wp_redirect(admin_url().'admin.php?page=cmgt-setup');
		}
}
if ( is_admin() )
{
	require_once CMS_PLUGIN_DIR. '/admin/admin.php';
	//INSTALL ROLE AND TABLE FUNCTION
	function MJ_cmgt_church_install()
	{
		add_role('accountant', __( 'Accountant' ,'church_mgt'),array( 'read' => true, 'level_1' => true ));
		add_role('member', __( 'Member' ,'church_mgt'),array( 'read' => true, 'level_0' => true ));
		add_role('family_member', __( 'Family Member' ,'church_mgt'),array( 'read' => true, 'level_0' => true ));
		add_role('management', __( 'Management' ,'church_mgt'),array( 'read' => true, 'level_1' => true , 'upload_files'=>true));
		MJ_cmgt_install_tables();			
	}
	register_activation_hook(CMS_PLUGIN_BASENAME, 'MJ_cmgt_church_install' );
	//ADD OPTION FUNCTION
	function MJ_cmgt_option()
	{
		$role_access_right_member= array();
		$role_access_right_accountant= array();
		$role_access_right_family_member= array();
		
		$role_access_right_member['member'] = [
									"member"=>["menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
												'menu_title'=>'Member',
												"page_link"=>'member',
												"own_data" =>isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:1,
												"add" =>isset($_REQUEST['member_add'])?$_REQUEST['member_add']:0,
													"edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:0,
													"view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:1,
													"delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:0
													],
									"familymember"=>["menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
													'menu_title'=>'Family Member',
													"page_link"=>'familymember',
													"own_data" =>isset($_REQUEST['familymember_own_data'])?$_REQUEST['familymember_own_data']:1,
													"add" =>isset($_REQUEST['familymember_add'])?$_REQUEST['familymember_add']:1,
													"edit"=>isset($_REQUEST['familymember_edit'])?$_REQUEST['familymember_edit']:0,
													"view"=>isset($_REQUEST['familymember_view'])?$_REQUEST['familymember_view']:1,
													"delete"=>isset($_REQUEST['familymember_delete'])?$_REQUEST['familymember_delete']:0
									],					
									"document"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/document.png' ),
													'menu_title'=>'Document',
												"page_link"=>'document',
												"own_data" => isset($_REQUEST['document_own_data'])?$_REQUEST['document_own_data']:0,
												"add" => isset($_REQUEST['document_add'])?$_REQUEST['document_add']:0,
												"edit"=>isset($_REQUEST['document_edit'])?$_REQUEST['document_edit']:0,
												"view"=>isset($_REQUEST['document_view'])?$_REQUEST['document_view']:1,
												"delete"=>isset($_REQUEST['document_delete'])?$_REQUEST['document_delete']:0
									],
												
										"group"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/group.png' ),			'menu_title'=>'Group',
												"page_link"=>'group',
												"own_data" => isset($_REQUEST['group_own_data'])?$_REQUEST['group_own_data']:0,
												"add" => isset($_REQUEST['group_add'])?$_REQUEST['group_add']:0,
												"edit"=>isset($_REQUEST['group_edit'])?$_REQUEST['group_edit']:0,
												"view"=>isset($_REQUEST['group_view'])?$_REQUEST['group_view']:1,
												"delete"=>isset($_REQUEST['group_delete'])?$_REQUEST['group_delete']:0
									],
												
										"services"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/services.png' ),
													'menu_title'=>'Services',
													"page_link"=>'services',
													"own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
													"add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:0,
													"edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:0,
													"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:1,
													"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:0
										],
										
										"ministry"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Ministry.png' ),			
													'menu_title'=>'Ministry',
													"page_link"=>'ministry',
													"own_data" => isset($_REQUEST['ministry_own_data'])?$_REQUEST['ministry_own_data']:0,
													"add" => isset($_REQUEST['ministry_add'])?$_REQUEST['ministry_add']:0,
													"edit"=>isset($_REQUEST['ministry_edit'])?$_REQUEST['ministry_edit']:0,
													"view"=>isset($_REQUEST['ministry_view'])?$_REQUEST['ministry_view']:1,
													"delete"=>isset($_REQUEST['ministry_delete'])?$_REQUEST['ministry_delete']:0
										],
										"activity"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Activity.png' ),
													'menu_title'=>'Activity',
													"page_link"=>'activity',
													"own_data" => isset($_REQUEST['activity_own_data'])?$_REQUEST['activity_own_data']:0,
													"add" => isset($_REQUEST['activity_add'])?$_REQUEST['activity_add']:0,
													"edit"=>isset($_REQUEST['activity_edit'])?$_REQUEST['activity_edit']:0,
													"view"=>isset($_REQUEST['activity_view'])?$_REQUEST['activity_view']:1,
													"delete"=>isset($_REQUEST['activity_delete'])?$_REQUEST['activity_delete']:0
										],
										
											"attendance"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Attendance.png' ),			'menu_title'=>'Attendance',
													"page_link"=>'attendance',
													"own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
													"add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
													"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
													"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:0,
													"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
										],
										
										
											"venue"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venue.png' ),			'menu_title'=>'Venue',
													"page_link"=>'venue',
													"own_data" => isset($_REQUEST['venue_own_data'])?$_REQUEST['venue_own_data']:1,
													"add" => isset($_REQUEST['venue_add'])?$_REQUEST['venue_add']:0,
													"edit"=>isset($_REQUEST['venue_edit'])?$_REQUEST['venue_edit']:0,
													"view"=>isset($_REQUEST['venue_view'])?$_REQUEST['venue_view']:1,
													"delete"=>isset($_REQUEST['venue_delete'])?$_REQUEST['venue_delete']:0
										],
									  
										"reservation"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venue.png' ),			
														'menu_title'=>'Reservation',
														"page_link"=>'reservation',
														"own_data" => isset($_REQUEST['reservation_own_data'])?$_REQUEST['reservation_own_data']:1,
														"add" => isset($_REQUEST['reservation_add'])?$_REQUEST['reservation_add']:1,
														"edit"=>isset($_REQUEST['reservation_edit'])?$_REQUEST['reservation_edit']:0,
														"view"=>isset($_REQUEST['reservation_view'])?$_REQUEST['reservation_view']:1,
														"delete"=>isset($_REQUEST['reservation_delete'])?$_REQUEST['reservation_delete']:0
											],
											"check-in"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Check-In.png' ),
													'menu_title'=>'Check-In',
													"page_link"=>'check-in',
													"own_data" => isset($_REQUEST['check-in_own_data'])?$_REQUEST['check-in_own_data']:1,
													"add" => isset($_REQUEST['check-in_add'])?$_REQUEST['check-in_add']:0,
													"edit"=>isset($_REQUEST['check-in_edit'])?$_REQUEST['check-in_edit']:0,
													"view"=>isset($_REQUEST['check-in_view'])?$_REQUEST['check-in_view']:1,
													"delete"=>isset($_REQUEST['check-in_delete'])?$_REQUEST['check-in_delete']:0
										],
											"sermon-list"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Sermon-List.png' ),			
													'menu_title'=>'Sermon List',
													"page_link"=>'sermon-list',
													"own_data" => isset($_REQUEST['sermon-list_own_data'])?$_REQUEST['sermon-list_own_data']:0,
													"add" => isset($_REQUEST['sermon-list_add'])?$_REQUEST['sermon-list_add']:0,
													"edit"=>isset($_REQUEST['sermon-list_edit'])?$_REQUEST['sermon-list_edit']:0,
													"view"=>isset($_REQUEST['sermon-list_view'])?$_REQUEST['sermon-list_view']:1,
													"delete"=>isset($_REQUEST['sermon-list_delete'])?$_REQUEST['sermon-list_delete']:0
										],
										
										"songs"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Songs.png' ),
													'menu_title'=>'Songs',
													"page_link"=>'songs',
													"own_data" => isset($_REQUEST['songs_own_data'])?$_REQUEST['songs_own_data']:0,
													"add" => isset($_REQUEST['songs_add'])?$_REQUEST['songs_add']:0,
													"edit"=>isset($_REQUEST['songs_edit'])?$_REQUEST['songs_edit']:0,
													"view"=>isset($_REQUEST['songs_view'])?$_REQUEST['songs_view']:1,
													"delete"=>isset($_REQUEST['songs_delete'])?$_REQUEST['songs_delete']:0
										],
										
										"pledges"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Pledges.png' ),
													'menu_title'=>'Pledges',
													"page_link"=>'pledges',
													"own_data" => isset($_REQUEST['pledges_own_data'])?$_REQUEST['pledges_own_data']:1,
													"add" => isset($_REQUEST['pledges_add'])?$_REQUEST['pledges_add']:1,
													"edit"=>isset($_REQUEST['pledges_edit'])?$_REQUEST['pledges_edit']:0,
													"view"=>isset($_REQUEST['pledges_view'])?$_REQUEST['pledges_view']:1,
													"delete"=>isset($_REQUEST['pledges_delete'])?$_REQUEST['pledges_delete']:0
										],
										"accountant"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Accountant.png' ),
													'menu_title'=>'Accountant',
												"page_link"=>'accountant',
													"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:1,
													"add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
													"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
													"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
													"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
										],
										"spiritual-gift"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Spiritual-Gift.png' ),
													'menu_title'=>'Spiritual Gift',
													"page_link"=>'spiritual-gift',
													"own_data" => isset($_REQUEST['spiritual-gift_own_data'])?$_REQUEST['spiritual-gift_own_data']:1,
													"add" => isset($_REQUEST['spiritual-gift_add'])?$_REQUEST['spiritual-gift_add']:0,
													"edit"=>isset($_REQUEST['spiritual-gift_edit'])?$_REQUEST['spiritual-gift_edit']:0,
													"view"=>isset($_REQUEST['spiritual-gift_view'])?$_REQUEST['spiritual-gift_view']:1,
													"delete"=>isset($_REQUEST['spiritual-gift_delete'])?$_REQUEST['spiritual-gift_delete']:0
										],
										"payment"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Transaction.png' ),
													'menu_title'=>'Payment',
													"page_link"=>'payment',
													"own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:1,
													"add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:0,
													"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:0,
													"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:1,
													"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:0
										],
										
										"notice"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/notice.png'),
										"menu_title"=>'notice',
										"page_link"=>'notice',
										"own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:0,
											"add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:0,
										"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
										"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:1,
										"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
											],
											
											"donate"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/donate.png' ),
											'menu_title'=>'Donate',
											"page_link"=>'donate',
											"own_data" => isset($_REQUEST['donate_own_data'])?$_REQUEST['donate_own_data']:1,
											"add" => isset($_REQUEST['donate_add'])?$_REQUEST['donate_add']:1,
											"edit"=>isset($_REQUEST['donate_edit'])?$_REQUEST['donate_edit']:0,
											"view"=>isset($_REQUEST['donate_view'])?$_REQUEST['donate_view']:1,
											"delete"=>isset($_REQUEST['donate_delete'])?$_REQUEST['donate_delete']:0
											],

										"message"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/message.png'),
													"menu_title"=>'Message',
													"page_link"=>'message',
													"own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
													"add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
													"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
													"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
													"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
										],
										"report"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/report.png'),
													"menu_title"=>'Report',
													"page_link"=>'report',
													"own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
													"add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
													"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
													"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
													"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
											],
										
										
										"pastoral"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/pastoral.png' ),
												'menu_title'=>'Pastoral',
												"page_link"=>'pastoral',
													"own_data" => isset($_REQUEST['pastoral_own_data'])?$_REQUEST['pastoral_own_data']:0,
													"add" => isset($_REQUEST['pastoral_add'])?$_REQUEST['pastoral_add']:0,
													"edit"=>isset($_REQUEST['pastoral_edit'])?$_REQUEST['pastoral_edit']:0,
													"view"=>isset($_REQUEST['pastoral_view'])?$_REQUEST['pastoral_view']:1,
													"delete"=>isset($_REQUEST['pastoral_delete'])?$_REQUEST['pastoral_delete']:0
										],
										
										
										
										"newsletter"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
													'menu_title'=>'News Letter',
													"page_link"=>'news_letter',
													"own_data" => isset($_REQUEST['newsletter_own_data'])?$_REQUEST['newsletter_own_data']:0,
													"add" => isset($_REQUEST['newsletter_add'])?$_REQUEST['newsletter_add']:0,
													"edit"=>isset($_REQUEST['newsletter_edit'])?$_REQUEST['newsletter_edit']:0,
													"view"=>isset($_REQUEST['newsletter_view'])?$_REQUEST['newsletter_view']:0,
													"delete"=>isset($_REQUEST['newsletter_delete'])?$_REQUEST['newsletter_delete']:0
										],
										"account"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/account.png' ),
													'menu_title'=>'Account',
													"page_link"=>'account',
													"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:0,
													"add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
													"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
													"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
													"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
									],
										];
										
		$role_access_right_accountant['accountant'] = [
											"member"=>["menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
														'menu_title'=>'Member',
														"page_link"=>'member',
														"own_data" =>isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:0,
														"add" =>isset($_REQUEST['member_add'])?$_REQUEST['member_add']:0,
														"edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:0,
														"view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:1,
														"delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:0
														],
																
											"document"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/document.png' ),
														'menu_title'=>'Document',
														"page_link"=>'document',
														"own_data" => isset($_REQUEST['document_own_data'])?$_REQUEST['document_own_data']:0,
														"add" => isset($_REQUEST['document_add'])?$_REQUEST['document_add']:0,
														"edit"=>isset($_REQUEST['document_edit'])?$_REQUEST['document_edit']:0,
														"view"=>isset($_REQUEST['document_view'])?$_REQUEST['document_view']:1,
														"delete"=>isset($_REQUEST['document_delete'])?$_REQUEST['document_delete']:0
											],
														
											"group"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/group.png' ),			'menu_title'=>'Group',
													"page_link"=>'group',
														"own_data" => isset($_REQUEST['group_own_data'])?$_REQUEST['group_own_data']:0,
														"add" => isset($_REQUEST['group_add'])?$_REQUEST['group_add']:0,
													"edit"=>isset($_REQUEST['group_edit'])?$_REQUEST['group_edit']:0,
													"view"=>isset($_REQUEST['group_view'])?$_REQUEST['group_view']:1,
													"delete"=>isset($_REQUEST['group_delete'])?$_REQUEST['group_delete']:0
											],
														
												"services"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/services.png' ),
														'menu_title'=>'Services',
														"page_link"=>'services',
														"own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
															"add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:0,
															"edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:0,
														"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:1,
														"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:0
												],
												
												"ministry"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Ministry.png' ),			
														'menu_title'=>'Ministry',
															"page_link"=>'ministry',
															"own_data" => isset($_REQUEST['ministry_own_data'])?$_REQUEST['ministry_own_data']:0,
															"add" => isset($_REQUEST['ministry_add'])?$_REQUEST['ministry_add']:0,
														"edit"=>isset($_REQUEST['ministry_edit'])?$_REQUEST['ministry_edit']:0,
														"view"=>isset($_REQUEST['ministry_view'])?$_REQUEST['ministry_view']:1,
														"delete"=>isset($_REQUEST['ministry_delete'])?$_REQUEST['ministry_delete']:0
												],
												"activity"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Activity.png' ),
														'menu_title'=>'Activity',
															"page_link"=>'activity',
															"own_data" => isset($_REQUEST['activity_own_data'])?$_REQUEST['activity_own_data']:0,
															"add" => isset($_REQUEST['activity_add'])?$_REQUEST['activity_add']:0,
														"edit"=>isset($_REQUEST['activity_edit'])?$_REQUEST['activity_edit']:0,
														"view"=>isset($_REQUEST['activity_view'])?$_REQUEST['activity_view']:1,
														"delete"=>isset($_REQUEST['activity_delete'])?$_REQUEST['activity_delete']:0
												],
												
												"attendance"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Attendance.png' ),			'menu_title'=>'Attendance',
															"page_link"=>'attendance',
															"own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
															"add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
														"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
														"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:1,
														"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
												],
												
												
												"venue"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venue.png' ),			'menu_title'=>'Venue',
															"page_link"=>'venue',
															"own_data" => isset($_REQUEST['venue_own_data'])?$_REQUEST['venue_own_data']:0,
															"add" => isset($_REQUEST['venue_add'])?$_REQUEST['venue_add']:1,
														"edit"=>isset($_REQUEST['venue_edit'])?$_REQUEST['venue_edit']:1,
														"view"=>isset($_REQUEST['venue_view'])?$_REQUEST['venue_view']:1,
														"delete"=>isset($_REQUEST['venue_delete'])?$_REQUEST['venue_delete']:1
												],
												"reservation"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venue.png' ),			
															'menu_title'=>'Reservation',
															"page_link"=>'reservation',
															"own_data" => isset($_REQUEST['reservation_own_data'])?$_REQUEST['reservation_own_data']:1,
															"add" => isset($_REQUEST['reservation_add'])?$_REQUEST['reservation_add']:1,
															"edit"=>isset($_REQUEST['reservation_edit'])?$_REQUEST['reservation_edit']:0,
															"view"=>isset($_REQUEST['reservation_view'])?$_REQUEST['reservation_view']:1,
															"delete"=>isset($_REQUEST['reservation_delete'])?$_REQUEST['reservation_delete']:0
												],
												"check-in"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Check-In.png' ),
														'menu_title'=>'Check-In',
															"page_link"=>'check-in',
															"own_data" => isset($_REQUEST['check-in_own_data'])?$_REQUEST['check-in_own_data']:0,
															"add" => isset($_REQUEST['check-in_add'])?$_REQUEST['check-in_add']:0,
														"edit"=>isset($_REQUEST['check-in_edit'])?$_REQUEST['check-in_edit']:0,
														"view"=>isset($_REQUEST['check-in_view'])?$_REQUEST['check-in_view']:1,
														"delete"=>isset($_REQUEST['check-in_delete'])?$_REQUEST['check-in_delete']:0
												],
												"sermon-list"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Sermon-List.png' ),			
														'menu_title'=>'Sermon List',
															"page_link"=>'sermon-list',
															"own_data" => isset($_REQUEST['sermon-list_own_data'])?$_REQUEST['sermon-list_own_data']:0,
															"add" => isset($_REQUEST['sermon-list_add'])?$_REQUEST['sermon-list_add']:0,
														"edit"=>isset($_REQUEST['sermon-list_edit'])?$_REQUEST['sermon-list_edit']:0,
														"view"=>isset($_REQUEST['sermon-list_view'])?$_REQUEST['sermon-list_view']:1,
														"delete"=>isset($_REQUEST['sermon-list_delete'])?$_REQUEST['sermon-list_delete']:0
												],
												
												"songs"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Songs.png' ),
														'menu_title'=>'Songs',
															"page_link"=>'songs',
															"own_data" => isset($_REQUEST['songs_own_data'])?$_REQUEST['songs_own_data']:0,
															"add" => isset($_REQUEST['songs_add'])?$_REQUEST['songs_add']:0,
														"edit"=>isset($_REQUEST['songs_edit'])?$_REQUEST['songs_edit']:0,
														"view"=>isset($_REQUEST['songs_view'])?$_REQUEST['songs_view']:0,
														"delete"=>isset($_REQUEST['songs_delete'])?$_REQUEST['songs_delete']:0
												],
												
												"pledges"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Pledges.png' ),
														'menu_title'=>'Pledges',
															"page_link"=>'pledges',
															"own_data" => isset($_REQUEST['pledges_own_data'])?$_REQUEST['pledges_own_data']:0,
															"add" => isset($_REQUEST['pledges_add'])?$_REQUEST['pledges_add']:1,
														"edit"=>isset($_REQUEST['pledges_edit'])?$_REQUEST['pledges_edit']:0,
														"view"=>isset($_REQUEST['pledges_view'])?$_REQUEST['pledges_view']:1,
														"delete"=>isset($_REQUEST['pledges_delete'])?$_REQUEST['pledges_delete']:0
												],
												"accountant"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Accountant.png' ),
														'menu_title'=>'Accountant',
														"page_link"=>'accountant',
															"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:1,
															"add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
														"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
														"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
														"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
												],
												"spiritual-gift"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Spiritual-Gift.png' ),
														'menu_title'=>'Spiritual Gift',
															"page_link"=>'spiritual-gift',
															"own_data" => isset($_REQUEST['spiritual-gift_own_data'])?$_REQUEST['spiritual-gift_own_data']:0,
															"add" => isset($_REQUEST['spiritual-gift_add'])?$_REQUEST['spiritual-gift_add']:1,
														"edit"=>isset($_REQUEST['spiritual-gift_edit'])?$_REQUEST['spiritual-gift_edit']:1,
														"view"=>isset($_REQUEST['spiritual-gift_view'])?$_REQUEST['spiritual-gift_view']:1,
														"delete"=>isset($_REQUEST['spiritual-gift_delete'])?$_REQUEST['spiritual-gift_delete']:1
												],
												"payment"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Transaction.png' ),
														'menu_title'=>'Payment',
															"page_link"=>'payment',
															"own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:0,
															"add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:1,
														"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:1,
														"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:1,
														"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:1
												],
												
												"notice"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/notice.png'),
												"menu_title"=>'notice',
												"page_link"=>'notice',
												"own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:0,
												"add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:1,
												"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
												"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:1,
												"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
													],
												
												"donate"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/donate.png' ),
													'menu_title'=>'Donate',
													"page_link"=>'donate',
													"own_data" => isset($_REQUEST['donate_own_data'])?$_REQUEST['donate_own_data']:0,
													"add" => isset($_REQUEST['donate_add'])?$_REQUEST['donate_add']:0,
													"edit"=>isset($_REQUEST['donate_edit'])?$_REQUEST['donate_edit']:0,
													"view"=>isset($_REQUEST['donate_view'])?$_REQUEST['donate_view']:0,
													"delete"=>isset($_REQUEST['donate_delete'])?$_REQUEST['donate_delete']:0
													],

												"message"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/message.png'),
														"menu_title"=>'Message',
														"page_link"=>'message',
															"own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
															"add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
														"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
														"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
														"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
												],
												"report"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/report.png'),
															"menu_title"=>'Report',
															"page_link"=>'report',
															"own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
															"add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
															"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
															"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
															"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
													],
												
												"pastoral"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/pastoral.png' ),
													'menu_title'=>'Pastoral',
														"page_link"=>'pastoral',
															"own_data" => isset($_REQUEST['pastoral_own_data'])?$_REQUEST['pastoral_own_data']:0,
															"add" => isset($_REQUEST['pastoral_add'])?$_REQUEST['pastoral_add']:1,
														"edit"=>isset($_REQUEST['pastoral_edit'])?$_REQUEST['pastoral_edit']:1,
														"view"=>isset($_REQUEST['pastoral_view'])?$_REQUEST['pastoral_view']:1,
														"delete"=>isset($_REQUEST['pastoral_delete'])?$_REQUEST['pastoral_delete']:0
												],
												
												
												
												"newsletter"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
														'menu_title'=>'News Letter',
															"page_link"=>'news_letter',
															"own_data" => isset($_REQUEST['newsletter_own_data'])?$_REQUEST['newsletter_own_data']:0,
															"add" => isset($_REQUEST['newsletter_add'])?$_REQUEST['newsletter_add']:0,
														"edit"=>isset($_REQUEST['newsletter_edit'])?$_REQUEST['newsletter_edit']:0,
														"view"=>isset($_REQUEST['newsletter_view'])?$_REQUEST['newsletter_view']:1,
														"delete"=>isset($_REQUEST['newsletter_delete'])?$_REQUEST['newsletter_delete']:0
												],
												"account"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/account.png' ),
														'menu_title'=>'Account',
															"page_link"=>'account',
															"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:0,
															"add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
														"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
														"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
														"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
												]
											];
		$role_access_right_family_member['family_member'] = [
													"member"=>["menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
													'menu_title'=>'Member',
													"page_link"=>'member',
													"own_data" =>isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:1,
													"add" =>isset($_REQUEST['member_add'])?$_REQUEST['member_add']:0,
													"edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:0,
													"view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:1,
													"delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:0
													],
													
										"familymember"=>["menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
														'menu_title'=>'Family Member',
														"page_link"=>'familymember',
														"own_data" =>isset($_REQUEST['familymember_own_data'])?$_REQUEST['familymember_own_data']:1,
														"add" =>isset($_REQUEST['familymember_add'])?$_REQUEST['familymember_add']:0,
														"edit"=>isset($_REQUEST['familymember_edit'])?$_REQUEST['familymember_edit']:0,
														"view"=>isset($_REQUEST['familymember_view'])?$_REQUEST['familymember_view']:1,
														"delete"=>isset($_REQUEST['familymember_delete'])?$_REQUEST['familymember_delete']:0
										],			
										"document"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/document.png' ),
													'menu_title'=>'Document',
												"page_link"=>'document',
												"own_data" => isset($_REQUEST['document_own_data'])?$_REQUEST['document_own_data']:0,
												"add" => isset($_REQUEST['document_add'])?$_REQUEST['document_add']:0,
												"edit"=>isset($_REQUEST['document_edit'])?$_REQUEST['document_edit']:0,
												"view"=>isset($_REQUEST['document_view'])?$_REQUEST['document_view']:1,
												"delete"=>isset($_REQUEST['document_delete'])?$_REQUEST['document_delete']:0
										],
												
										"group"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/group.png' ),			'menu_title'=>'Group',
												"page_link"=>'group',
												"own_data" => isset($_REQUEST['group_own_data'])?$_REQUEST['group_own_data']:0,
												"add" => isset($_REQUEST['group_add'])?$_REQUEST['group_add']:0,
												"edit"=>isset($_REQUEST['group_edit'])?$_REQUEST['group_edit']:0,
												"view"=>isset($_REQUEST['group_view'])?$_REQUEST['group_view']:1,
												"delete"=>isset($_REQUEST['group_delete'])?$_REQUEST['group_delete']:0
										],
												
										"services"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/services.png' ),
													'menu_title'=>'Services',
													"page_link"=>'services',
													"own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
													"add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:0,
													"edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:0,
													"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:1,
													"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:0
										],
										
										"ministry"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Ministry.png' ),			
													'menu_title'=>'Ministry',
													"page_link"=>'ministry',
													"own_data" => isset($_REQUEST['ministry_own_data'])?$_REQUEST['ministry_own_data']:0,
													"add" => isset($_REQUEST['ministry_add'])?$_REQUEST['ministry_add']:0,
													"edit"=>isset($_REQUEST['ministry_edit'])?$_REQUEST['ministry_edit']:0,
													"view"=>isset($_REQUEST['ministry_view'])?$_REQUEST['ministry_view']:1,
													"delete"=>isset($_REQUEST['ministry_delete'])?$_REQUEST['ministry_delete']:0
										],
										"activity"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Activity.png' ),
													'menu_title'=>'Activity',
													"page_link"=>'activity',
													"own_data" => isset($_REQUEST['activity_own_data'])?$_REQUEST['activity_own_data']:0,
													"add" => isset($_REQUEST['activity_add'])?$_REQUEST['activity_add']:0,
													"edit"=>isset($_REQUEST['activity_edit'])?$_REQUEST['activity_edit']:0,
													"view"=>isset($_REQUEST['activity_view'])?$_REQUEST['activity_view']:1,
													"delete"=>isset($_REQUEST['activity_delete'])?$_REQUEST['activity_delete']:0
										],
										
											"attendance"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Attendance.png' ),			'menu_title'=>'Attendance',
													"page_link"=>'attendance',
													"own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
													"add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
													"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
													"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:0,
													"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
										],
										
										
											"venue"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venue.png' ),			'menu_title'=>'Venue',
													"page_link"=>'venue',
													"own_data" => isset($_REQUEST['venue_own_data'])?$_REQUEST['venue_own_data']:0,
													"add" => isset($_REQUEST['venue_add'])?$_REQUEST['venue_add']:0,
													"edit"=>isset($_REQUEST['venue_edit'])?$_REQUEST['venue_edit']:0,
													"view"=>isset($_REQUEST['venue_view'])?$_REQUEST['venue_view']:0,
													"delete"=>isset($_REQUEST['venue_delete'])?$_REQUEST['venue_delete']:0
										],
											"check-in"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Check-In.png' ),
													'menu_title'=>'Check-In',
													"page_link"=>'check-in',
													"own_data" => isset($_REQUEST['check-in_own_data'])?$_REQUEST['check-in_own_data']:0,
													"add" => isset($_REQUEST['check-in_add'])?$_REQUEST['check-in_add']:0,
													"edit"=>isset($_REQUEST['check-in_edit'])?$_REQUEST['check-in_edit']:0,
													"view"=>isset($_REQUEST['check-in_view'])?$_REQUEST['check-in_view']:0,
													"delete"=>isset($_REQUEST['check-in_delete'])?$_REQUEST['check-in_delete']:0
										],
											"sermon-list"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Sermon-List.png' ),			
													'menu_title'=>'Sermon List',
													"page_link"=>'sermon-list',
													"own_data" => isset($_REQUEST['sermon-list_own_data'])?$_REQUEST['sermon-list_own_data']:0,
													"add" => isset($_REQUEST['sermon-list_add'])?$_REQUEST['sermon-list_add']:0,
													"edit"=>isset($_REQUEST['sermon-list_edit'])?$_REQUEST['sermon-list_edit']:0,
													"view"=>isset($_REQUEST['sermon-list_view'])?$_REQUEST['sermon-list_view']:1,
													"delete"=>isset($_REQUEST['sermon-list_delete'])?$_REQUEST['sermon-list_delete']:0
										],
										
										"songs"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Songs.png' ),
													'menu_title'=>'Songs',
													"page_link"=>'songs',
													"own_data" => isset($_REQUEST['songs_own_data'])?$_REQUEST['songs_own_data']:0,
													"add" => isset($_REQUEST['songs_add'])?$_REQUEST['songs_add']:0,
													"edit"=>isset($_REQUEST['songs_edit'])?$_REQUEST['songs_edit']:0,
													"view"=>isset($_REQUEST['songs_view'])?$_REQUEST['songs_view']:1,
													"delete"=>isset($_REQUEST['songs_delete'])?$_REQUEST['songs_delete']:0
										],
										
										"pledges"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Pledges.png' ),
													'menu_title'=>'Pledges',
													"page_link"=>'pledges',
													"own_data" => isset($_REQUEST['pledges_own_data'])?$_REQUEST['pledges_own_data']:0,
													"add" => isset($_REQUEST['pledges_add'])?$_REQUEST['pledges_add']:0,
													"edit"=>isset($_REQUEST['pledges_edit'])?$_REQUEST['pledges_edit']:0,
													"view"=>isset($_REQUEST['pledges_view'])?$_REQUEST['pledges_view']:0,
													"delete"=>isset($_REQUEST['pledges_delete'])?$_REQUEST['pledges_delete']:0
										],
										"accountant"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Accountant.png' ),
													'menu_title'=>'Accountant',
												"page_link"=>'accountant',
													"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
													"add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
													"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
													"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:0,
													"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
										],
										"spiritual-gift"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Spiritual-Gift.png' ),
													'menu_title'=>'Spiritual Gift',
													"page_link"=>'spiritual-gift',
													"own_data" => isset($_REQUEST['spiritual-gift_own_data'])?$_REQUEST['spiritual-gift_own_data']:1,
													"add" => isset($_REQUEST['spiritual-gift_add'])?$_REQUEST['spiritual-gift_add']:0,
													"edit"=>isset($_REQUEST['spiritual-gift_edit'])?$_REQUEST['spiritual-gift_edit']:0,
													"view"=>isset($_REQUEST['spiritual-gift_view'])?$_REQUEST['spiritual-gift_view']:1,
													"delete"=>isset($_REQUEST['spiritual-gift_delete'])?$_REQUEST['spiritual-gift_delete']:0
										],
										"payment"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Transaction.png' ),
													'menu_title'=>'Payment',
													"page_link"=>'payment',
													"own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:0,
													"add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:0,
													"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:0,
													"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:0,
													"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:0
										],
										
										"notice"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/notice.png'),
										"menu_title"=>'notice',
										"page_link"=>'notice',
										"own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:0,
											"add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:0,
										"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
										"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:0,
										"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
											],
											
											"donate"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/donate.png' ),
											'menu_title'=>'Donate',
											"page_link"=>'donate',
											"own_data" => isset($_REQUEST['donate_own_data'])?$_REQUEST['donate_own_data']:0,
											"add" => isset($_REQUEST['donate_add'])?$_REQUEST['donate_add']:0,
											"edit"=>isset($_REQUEST['donate_edit'])?$_REQUEST['donate_edit']:0,
											"view"=>isset($_REQUEST['donate_view'])?$_REQUEST['donate_view']:0,
											"delete"=>isset($_REQUEST['donate_delete'])?$_REQUEST['donate_delete']:0
											],

										"message"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/message.png'),
													"menu_title"=>'Message',
													"page_link"=>'message',
													"own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:0,
													"add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:0,
													"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
													"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:0,
													"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:0
										],
										
										
										"pastoral"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/pastoral.png' ),
												'menu_title'=>'Pastoral',
												"page_link"=>'pastoral',
													"own_data" => isset($_REQUEST['pastoral_own_data'])?$_REQUEST['pastoral_own_data']:0,
													"add" => isset($_REQUEST['pastoral_add'])?$_REQUEST['pastoral_add']:0,
													"edit"=>isset($_REQUEST['pastoral_edit'])?$_REQUEST['pastoral_edit']:0,
													"view"=>isset($_REQUEST['pastoral_view'])?$_REQUEST['pastoral_view']:0,
													"delete"=>isset($_REQUEST['pastoral_delete'])?$_REQUEST['pastoral_delete']:0
										],
										
										"newsletter"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
													'menu_title'=>'News Letter',
													"page_link"=>'news_letter',
													"own_data" => isset($_REQUEST['newsletter_own_data'])?$_REQUEST['newsletter_own_data']:0,
													"add" => isset($_REQUEST['newsletter_add'])?$_REQUEST['newsletter_add']:0,
													"edit"=>isset($_REQUEST['newsletter_edit'])?$_REQUEST['newsletter_edit']:0,
													"view"=>isset($_REQUEST['newsletter_view'])?$_REQUEST['newsletter_view']:0,
													"delete"=>isset($_REQUEST['newsletter_delete'])?$_REQUEST['newsletter_delete']:0
										],
										"account"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/account.png' ),
													'menu_title'=>'Account',
													"page_link"=>'account',
													"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:0,
													"add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
													"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
													"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
													"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
											]
										];
					$role_access_right_management = array();
					$role_access_right_management['management'] = 
						[
							// management default //
								"member"=>
									["menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
									'menu_title'=>'Member',
									"page_link"=>'member',
									"own_data" =>isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:0,
									"add" =>isset($_REQUEST['member_add'])?$_REQUEST['member_add']:1,
									"edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:1,
									"view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:1,
									"delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:1
									],
									"familymember"=>
									[   "menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
										'menu_title'=>'Family Member',
										"page_link"=>'family',
										"own_data" =>isset($_REQUEST['familymember_own_data'])?$_REQUEST['familymember_own_data']:0,
										"add" =>isset($_REQUEST['familymember_add'])?$_REQUEST['familymember_add']:1,
										"edit"=>isset($_REQUEST['familymember_edit'])?$_REQUEST['familymember_edit']:1,
										"view"=>isset($_REQUEST['familymember_view'])?$_REQUEST['familymember_view']:1,
										"delete"=>isset($_REQUEST['familymember_delete'])?$_REQUEST['familymember_delete']:1
									],
									"accountant"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Accountant.png' ),
										'menu_title'=>'Accountant',
										"page_link"=>'accountant',
										"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
										"add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:1,
										"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:1,
										"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
										"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:1
									],
									"group"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/group.png' ),			
										'menu_title'=>'Group',
										"page_link"=>'group',
										"own_data" => isset($_REQUEST['group_own_data'])?$_REQUEST['group_own_data']:0,
										"add" => isset($_REQUEST['group_add'])?$_REQUEST['group_add']:1,
										"edit"=>isset($_REQUEST['group_edit'])?$_REQUEST['group_edit']:1,
										"view"=>isset($_REQUEST['group_view'])?$_REQUEST['group_view']:1,
										"delete"=>isset($_REQUEST['group_delete'])?$_REQUEST['group_delete']:1
								        ],
									"services"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/services.png' ),
										'menu_title'=>'Services',
										"page_link"=>'services',
										"own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
										"add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:1,
										"edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:1,
										"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:1,
										"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:1
									  ],
									  "attendance"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Attendance.png' ),			
									   'menu_title'=>'Attendance',
											"page_link"=>'attendance',
											"own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
											"add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:1,
											"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:1,
											"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:0,
											"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
									],
									
									"ministry"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Ministry.png' ),			
											'menu_title'=>'Ministry',
											"page_link"=>'ministry',
											"own_data" => isset($_REQUEST['ministry_own_data'])?$_REQUEST['ministry_own_data']:0,
											"add" => isset($_REQUEST['ministry_add'])?$_REQUEST['ministry_add']:1,
											"edit"=>isset($_REQUEST['ministry_edit'])?$_REQUEST['ministry_edit']:1,
											"view"=>isset($_REQUEST['ministry_view'])?$_REQUEST['ministry_view']:1,
											"delete"=>isset($_REQUEST['ministry_delete'])?$_REQUEST['ministry_delete']:1
								],
								"pastoral"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/pastoral.png' ),
											'menu_title'=>'Pastoral',
											"page_link"=>'pastoral',
											 "own_data" => isset($_REQUEST['pastoral_own_data'])?$_REQUEST['pastoral_own_data']:0,
											"add" => isset($_REQUEST['pastoral_add'])?$_REQUEST['pastoral_add']:1,
											"edit"=>isset($_REQUEST['pastoral_edit'])?$_REQUEST['pastoral_edit']:1,
											"view"=>isset($_REQUEST['pastoral_view'])?$_REQUEST['pastoral_view']:1,
											"delete"=>isset($_REQUEST['pastoral_delete'])?$_REQUEST['pastoral_delete']:1
									  ],
									  "activity"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Activity.png' ),
											'menu_title'=>'Activity',
											"page_link"=>'activity',
											"own_data" => isset($_REQUEST['activity_own_data'])?$_REQUEST['activity_own_data']:0,
											"add" => isset($_REQUEST['activity_add'])?$_REQUEST['activity_add']:1,
											"edit"=>isset($_REQUEST['activity_edit'])?$_REQUEST['activity_edit']:1,
											"view"=>isset($_REQUEST['activity_view'])?$_REQUEST['activity_view']:1,
											"delete"=>isset($_REQUEST['activity_delete'])?$_REQUEST['activity_delete']:1
							],
							"venue"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venue.png' ),		'menu_title'=>'Venue',
											"page_link"=>'venue',
											"own_data" => isset($_REQUEST['venue_own_data'])?$_REQUEST['venue_own_data']:0,
											"add" => isset($_REQUEST['venue_add'])?$_REQUEST['venue_add']:1,
											"edit"=>isset($_REQUEST['venue_edit'])?$_REQUEST['venue_edit']:1,
											"view"=>isset($_REQUEST['venue_view'])?$_REQUEST['venue_view']:1,
											"delete"=>isset($_REQUEST['venue_delete'])?$_REQUEST['venue_delete']:1
									  ],
									  "reservation"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venues.png' ),			
											'menu_title'=>'Reservation',
											"page_link"=>'reservation',
											"own_data" => isset($_REQUEST['reservation_own_data'])?$_REQUEST['reservation_own_data']:0,
											"add" => isset($_REQUEST['reservation_add'])?$_REQUEST['reservation_add']:1,
											"edit"=>isset($_REQUEST['reservation_edit'])?$_REQUEST['reservation_edit']:1,
											"view"=>isset($_REQUEST['reservation_view'])?$_REQUEST['reservation_view']:1,
											"delete"=>isset($_REQUEST['reservation_delete'])?$_REQUEST['reservation_delete']:1
						 			 ],
									  "check-in"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Check-In.png' ),
											'menu_title'=>'Check-In',
											"page_link"=>'check-in',
											"own_data" => isset($_REQUEST['check-in_own_data'])?$_REQUEST['check-in_own_data']:0,
											"add" => isset($_REQUEST['check-in_add'])?$_REQUEST['check-in_add']:1,
											"edit"=>isset($_REQUEST['check-in_edit'])?$_REQUEST['check-in_edit']:1,
											"view"=>isset($_REQUEST['check-in_view'])?$_REQUEST['check-in_view']:1,
											"delete"=>isset($_REQUEST['check-in_delete'])?$_REQUEST['check-in_delete']:1
									],
									"sermon-list"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Sermon-List.png' ),			
											'menu_title'=>'Sermon List',
											"page_link"=>'sermon-list',
											"own_data" => isset($_REQUEST['sermon-list_own_data'])?$_REQUEST['sermon-list_own_data']:0,
											"add" => isset($_REQUEST['sermon-list_add'])?$_REQUEST['sermon-list_add']:1,
											"edit"=>isset($_REQUEST['sermon-list_edit'])?$_REQUEST['sermon-list_edit']:1,
											"view"=>isset($_REQUEST['sermon-list_view'])?$_REQUEST['sermon-list_view']:1,
											"delete"=>isset($_REQUEST['sermon-list_delete'])?$_REQUEST['sermon-list_delete']:1
									  ],
									  "spiritual-gift"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Spiritual-Gift.png' ),
											'menu_title'=>'Spiritual Gift',
											"page_link"=>'spiritual-gift',
											"own_data" => isset($_REQUEST['spiritual-gift_own_data'])?$_REQUEST['spiritual-gift_own_data']:0,
											"add" => isset($_REQUEST['spiritual-gift_add'])?$_REQUEST['spiritual-gift_add']:1,
											"edit"=>isset($_REQUEST['spiritual-gift_edit'])?$_REQUEST['spiritual-gift_edit']:1,
											"view"=>isset($_REQUEST['spiritual-gift_view'])?$_REQUEST['spiritual-gift_view']:1,
											"delete"=>isset($_REQUEST['spiritual-gift_delete'])?$_REQUEST['spiritual-gift_delete']:1
									  ],
									  "pledges"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Pledges.png' ),
											'menu_title'=>'Pledges',
											"page_link"=>'pledges',
											"own_data" => isset($_REQUEST['pledges_own_data'])?$_REQUEST['pledges_own_data']:0,
											"add" => isset($_REQUEST['pledges_add'])?$_REQUEST['pledges_add']:1,
											"edit"=>isset($_REQUEST['pledges_edit'])?$_REQUEST['pledges_edit']:1,
											"view"=>isset($_REQUEST['pledges_view'])?$_REQUEST['pledges_view']:1,
											"delete"=>isset($_REQUEST['pledges_delete'])?$_REQUEST['pledges_delete']:1
									],
									"songs"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Songs.png' ),
											'menu_title'=>'Songs',
											"page_link"=>'songs',
											"own_data" => isset($_REQUEST['songs_own_data'])?$_REQUEST['songs_own_data']:0,
											"add" => isset($_REQUEST['songs_add'])?$_REQUEST['songs_add']:1,
											"edit"=>isset($_REQUEST['songs_edit'])?$_REQUEST['songs_edit']:1,
											"view"=>isset($_REQUEST['songs_view'])?$_REQUEST['songs_view']:1,
											"delete"=>isset($_REQUEST['songs_delete'])?$_REQUEST['songs_delete']:1
									  ],
									  "document"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/document.png' ),
											'menu_title'=>'Document',
											"page_link"=>'document',
											"own_data" => isset($_REQUEST['document_own_data'])?$_REQUEST['document_own_data']:0,
											"add" => isset($_REQUEST['document_add'])?$_REQUEST['document_add']:1,
											"edit"=>isset($_REQUEST['document_edit'])?$_REQUEST['document_edit']:1,
											"view"=>isset($_REQUEST['document_view'])?$_REQUEST['document_view']:1,
											"delete"=>isset($_REQUEST['document_delete'])?$_REQUEST['document_delete']:1
								  ],
								  "payment"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Transaction.png' ),
											'menu_title'=>'Payment',
											"page_link"=>'payment',
											"own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:0,
											"add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:1,
											"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:1,
											"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:1,
											"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:1
								],
								"report"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/message.png'),
											"menu_title"=>'Report',
											"page_link"=>'report',
											"own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
											"add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
											"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
											"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
											"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
						],
								"notice"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/notice.png'),
											"menu_title"=>'notice',
											"page_link"=>'notice',
											"own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:0,
											"add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:1,
											"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:1,
											"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:1,
											"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:1
								],
						  		 "message"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/message.png'),
											"menu_title"=>'Message',
											"page_link"=>'message',
											"own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:0,
											"add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
											"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
											"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
											"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
								],
								"newsletter"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
											'menu_title'=>'News Letter',
											"page_link"=>'news_letter',
											"own_data" => isset($_REQUEST['newsletter_own_data'])?$_REQUEST['newsletter_own_data']:0,
											"add" => isset($_REQUEST['newsletter_add'])?$_REQUEST['newsletter_add']:1,
											"edit"=>isset($_REQUEST['newsletter_edit'])?$_REQUEST['newsletter_edit']:0,
											"view"=>isset($_REQUEST['newsletter_view'])?$_REQUEST['newsletter_view']:1,
											"delete"=>isset($_REQUEST['newsletter_delete'])?$_REQUEST['newsletter_delete']:0
								],

								"emailtemplate"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
											'menu_title'=>'Email Template',
											"page_link"=>'emailtemplate',
											"own_data" => isset($_REQUEST['emailtemplate_own_data'])?$_REQUEST['newsletter_own_data']:0,
											"add" => isset($_REQUEST['emailtemplate_add'])?$_REQUEST['emailtemplate_add']:0,
											"edit"=>isset($_REQUEST['emailtemplate_edit'])?$_REQUEST['emailtemplate_edit']:1,
											"view"=>isset($_REQUEST['emailtemplate_view'])?$_REQUEST['emailtemplate_view']:1,
											"delete"=>isset($_REQUEST['emailtemplate_delete'])?$_REQUEST['emailtemplate_delete']:0
									],   // accessright generalsetting
								"accessright"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
											'menu_title'=>'Access Right',
											"page_link"=>'accessright',
											"own_data" => isset($_REQUEST['accessright_own_data'])?$_REQUEST['accessright_own_data']:0,
											"add" => isset($_REQUEST['accessright_add'])?$_REQUEST['accessright_add']:0,
											"edit"=>isset($_REQUEST['accessright_edit'])?$_REQUEST['accessright_edit']:0,
											"view"=>isset($_REQUEST['accessright_view'])?$_REQUEST['accessright_view']:0,
											"delete"=>isset($_REQUEST['accessright_delete'])?$_REQUEST['accessright_delete']:0
								],
								"generalsetting"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
											'menu_title'=>'General Setting',
											"page_link"=>'generalsetting',
											"own_data" => isset($_REQUEST['generalsetting_own_data'])?$_REQUEST['generalsetting_own_data']:0,
											"add" => isset($_REQUEST['generalsetting_add'])?$_REQUEST['generalsetting_add']:0,
											"edit"=>isset($_REQUEST['generalsetting_edit'])?$_REQUEST['generalsetting_edit']:1,
											"view"=>isset($_REQUEST['generalsetting_view'])?$_REQUEST['generalsetting_view']:1,
											"delete"=>isset($_REQUEST['generalsetting_delete'])?$_REQUEST['generalsetting_delete']:0
								]
							];

		$options=array("cmgt_system_name"=> __( 'Church Management System' ,'church_mgt'),
					"cmgt_staring_year"=>"2023",
					"cmgt_church_address"=>"Near cross road-5",
					"cmgt_contact_number"=>"9999999999",
					"cmgt_contry"=>"United States",
					"cmgt_email"=>get_option('admin_email'),
					"cmgt_system_logo"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/WP-Church-Management-Logo.png',
					"cmgt_system_logo1"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/logo1.jpg',
					"cmgt_invoice_thumbnail_logo"=>CMS_PLUGIN_URL.'/assets/images/invoice.png',
					"cmgt_church_other_data_logo"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/invoice_thumb.png',
					"cmgt_church_app_icon"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/invoice_thumb.png',

					"cmgt_group_logo"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/cardlist_icon/Group.png',
					"cmgt_ministry_logo"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/cardlist_icon/Ministry.png',
					"cmgt_family_logo"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/cardlist_icon/Family.png',
					"cmgt_accountant_logo"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/cardlist_icon/Accountant.png',
					"cmgt_gift_logo"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/cardlist_icon/Spiritual-Gifts.png',
					"cmgt_Dashboard_defualt_img"=> CMS_PLUGIN_URL.'/assets/images/icons/No-Data.png',
					"cmgt_no_data_plus_img"=> CMS_PLUGIN_URL.'/assets/images/Church-icons/plus-icon.png',
					
					"cmgt_church_background_image"=>CMS_PLUGIN_URL.'/assets/images/church-background.png',
					"cmgt_member_thumb"=>CMS_PLUGIN_URL.'/assets/images/Church-icons/cardlist_icon/Member.png',
					"cmgt_mailchimp_api"=>'',
					"cmgt_paypal_email"=>'',
					"cmgt_currency_code"=>'USD',
					"cmgt_enable_sandbox"=>'yes',
					"cmgt_payment_prefix"=>'INV-',
					"cmgt_access_right_member"=>$role_access_right_member,
					"cmgt_access_right_accountant"=>$role_access_right_accountant,
					"cmgt_access_right_family_member"=>$role_access_right_family_member,
					"cmgt_access_right_management"=>$role_access_right_management,
					"cmgt_footer_description" => "Copyright ©2023 Mojoomla. All rights reserved.",
					//"cmgt_enable_change_profile_picture"=>'yes',
					"cmgt_datepicker_format"=>'Y-m-d',
					"cmgt_enable_notifications" => 'yes',
					"cmgt_take_past_attendance" => 'yes',
					'cmgt_birthday_mail_subject'=>'Happy Birthday Wish',
					'cmgt_header_enable'=>'yes',
					'cmgt_family_without_email_pass'=>'no',
					'cmgt_family_can_login'=>'no',
					"cmgt_system_color_code"=>"#149A91",
					// "app_email"=>"",
					// "app_url"=>"",
					// "app_license_key"=>"",
					'cmgt_birthday_mail_content'=>'
					Dear {{member_name}} May You Have the best of luck on your special day, bringing you the joy,peace and wonder you so rightfully deserve. Stay Blessed. 
		
	    Happy Birthday!
	   
 {{system_name}}',
					
					'WPChurch_Member_Registration'=>'You are successully registered at [CMGT_CHURCH_NAME]',
					
					'WPChurch_registration_email_template'=>'Dear [CMGT_MEMBERNAME],
					 
	    You are successfully registered at [CMGT_CHURCH_NAME].
	
 Regards From [CMGT_CHURCH_NAME].',
 
                    'WPChurch_Member_Approve_Subject'=>'You profile has been approved by admin at [CMGT_CHURCH_NAME]',
					
					'WPChurch_Member_Approve_Template'=>'Hello [CMGT_MEMBERNAME],

         You are successully registered at [CMGT_CHURCH_NAME]. You profile has been approved by admin and you can signin using this link. [CMGT_LOGIN_LINK] 
 
 Regards From [CMGT_CHURCH_NAME].',

 
                    'WPChurch_add_user_subject'=>'Your have been assigned role of [CMGT_ROLE_NAME] in [CMGT_CHURCH_NAME]',
					'WPChurch_add_user_email_template'=>'Dear  [CMGT_MEMBER_NAME] ,
					
        You are Added by admin of [CMGT_CHURCH_NAME].Your have been assigned role of [CMGT_ROLE_NAME] in [CMGT_CHURCH_NAME]. You can access system using your username and password.You can signin using this link. [CMGT_LOGIN_LINK]
		
 UserName : [CMGT_USERNAME]
 Password : [CMGT_PASSWORD]
 
 Regards From [CMGT_CHURCH_NAME].',
 
                    'WPChurch_Member_Added_In_Group_subject'=>'You are added in [CMGT_GROUPNAME] at [CMGT_CHURCH_NAME]',
					
					'WPChurch_Member_Added_In_Group_Template'=>'Dear [CMGT_MEMBERNAME],
					
         You are added in [CMGT_GROUPNAME] Group. 
     
Regards From [CMGT_CHURCH_NAME].',
                    'WPChurch_Member_Added_In_Ministry_subject'=>'You are added in [CMGT_MINISTRY] at [CMGT_CHURCH_NAME]',
					
					'WPChurch_Member_Added_In_Ministry_Template'=>'Dear [CMGT_MEMBERNAME],
					
         You are added in [CMGT_MINISTRY] Ministry. 
     
Regards From [CMGT_CHURCH_NAME].',

                    'WPChurch_add_notice_subject'=>'New Notice from [GMGT_MEMBERNAME] at [CMGT_CHURCH_NAME] ',
					
					'WPChurch_add_notice_email_template'=>'Dear [GMGT_MEMBERNAME],
					
         Here is the new Notice from  [GMGT_USERNAME].
Title : [CMGT_NOTICE_TITLE].
Notice Start Date :[CMGT_NOTICE_START_DATE].
Notice End Date : [CMGT_NOTICE_END_DATE].
Description : [CMGT_NOTICE_CONTENT].
View Notice Click [CMGT_NOTICE_PAGE_LINK].

Regards From [CMGT_CHURCH_NAME] .',

                    'WPChurch_servic_subject'=>'New Service From [CMGT_CHURCH_NAME]',
					
					'WPChurch_Add_Service_Template'=>'Dear [CMGT_MEMBERNAME],

        Here is the new Service from [CMGT_CHURCH_NAME]

               Title : [CMGT_SERVICE_TITLE].
               Service Start Date : [CMGT_SERVICE_START_DATE].
               Service End Date : [CMGT_SERVICE_END_DATE].
               Service Start Time: [CMGT_SERVICE_START_TIME].
               Service End Time: [CMGT_SERVICE_END_TIME].

        Other Service  Times data.
              
               Other Service Title :  [CMGT_OTHER_SERVICE_TITLE].
               Other Service Type :  [CMGT_OTHER_SERVICE_TYPE].  
               Other Service Date :  [CMGT_OTHER_SERVICE_DATE].
               Other Service Start Time : [CMGT_OTHER_SERVICE_START_TIME].
               Other Service End Time : [CMGT_OTHER_SERVICE_END_TIME].
               [CMGT_PAGE_LINK]
     
Regards From [CMGT_CHURCH_NAME].',

                    'WPChurch_Add_Activity_Subject'=>'New Service From [CMGT_CHURCH_NAME]',
					
					'WPChurch_Add_Activity_Template'=>'Dear [CMGT_MEMBERNAME],

        Here is the new Activity from [CMGT_CHURCH_NAME]. You are invited in this activity from 
       [CMGT_GROUPNAME].

           Title : [CMGT_ACTIVITY_TITLE].
           Activity Category : [CMGT_ACTIVITY_CATEGORY].
           Activity Venue : [CMGT_ACTIVITY_VENUE].
           Activity Reoccurence : [CMGT_ACTIVITY_REOCCURNCE].
           Activity Start Date : [CMGT_ACTIVITY_START_DATE].
           Activity End Date : [CMGT_ACTIVITY_END_DATE].
           Activity Start Time: [CMGT_ACTIVITY_START_TIME] .
           Activity End Time: [CMGT_ACTIVITY_END_TIME].
           Activity Record Start Time: [CMGT_ACTIVITY_RECORD_START_TIME].
           Activity Record End Time: [CMGT_ACTIVITY_RECORD_END_TIME].
           [CMGT_PAGE_LINK]
           
 Regards From [CMGT_CHURCH_NAME].',
 
                    'WPChurch_Check_In_church_venue_subject'=>'You have checked in [CMGT_ROOM_TITLE] from [CMGT_CHURCH_NAME]',
					
					'WPChurch_Check_In_church_venue_Template'=>'Dear [CMGT_MEMBERNAME],

        You are checked-in in [CMGT_ROOM_TITLE]  for [CMGT_CHEKED_INDATE] To [CMGT_CHEKED_OUTDATE] with [CMGT_NO_OF_FAMILY_MEMBER] Family Member.  

[CMGT_PAGE_LINK]

Regards From [CMGT_CHURCH_NAME].',

                    'WPChurch_Ckeck_Out_From_Church_Venue_Subject'=>'You have checked Out [CMGT_ROOM_TITLE] from [CMGT_CHURCH_NAME]',
					
					'WPChurch_Ckeck_Out_From_Church_Venue_Template'=>'Dear [CMGT_MEMBERNAME],

        You are checked-out from [CMGT_ROOM_TITLE]  at  [CMGT_CHEKED_INDATE] on 
  [CMGT_CHEKED_OUTDATE] with Family Member  [CMGT_NO_OF_FAMILY_MEMBER]. 
  [CMGT_PAGE_LINK]

Regards From [CMGT_CHURCH_NAME].',

                    'WPChurch_Add_Sermon_Subject'=>'[CMGT_SERMONADDEDBY] Added new sermon in [CMGT_CHURCH_NAME]',
					
					'WPChurch_Add_Sermon_Template'=>'Dear [CMGT_MEMBERNAME], 
             
         Added New sermon in [CMGT_CHURCH_NAME] by [CMGT_SERMONADDEDBY]. 
              Sermon Title :  [CMGT_SERMON_TITLE]
              Sermon Description :  [CMGT_SERMON_DESCRIPTION]
        [CMGT_PAGE_LINK]

Regards From [CMGT_CHURCH_NAME].',

                     'WPChurch_Add_Song_Subject'=>'[CMGT_SONGADDEDBY] Added new Song in [CMGT_CHURCH_NAME]',
					
					'WPChurch_Add_Song_Template'=>'Dear [CMGT_MEMBERNAME], 
             
       Added New song in [CMGT_CHURCH_NAME]  by [CMGT_SONGADDEDBY]. 
            Song Name : [CMGT_SONG_NAME]
            Song Category : [CMGT_SONG_CATEGORY]
            Song Description :  [CMGT_SONG_DESCRIPTION]
            [CMGT_PAGE_LINK]

Regards From [CMGT_CHURCH_NAME] .',

                    'WPChurch_Add_Pledges_Subject'=>' [USER]  Added new pledges in [CMGT_CHURCH_NAME]',
					
					'WPChurch_Add_Pledges_Template'=>'Dear [CMGT_MEMBERNAME], 
             
        Added New pledges for you in ChurchName. 
              Start Date: [CMGT_START_DATE]
              End Date: [CMGT_END_DATE] 
              Pledges Amount: [CMGT_PLEDGES_AMOUNT]
              Pledges frequecy: [CMGT_PLEDGES_FREQUENCY] 
              Pledges Total Amount: [CMGT_PLEDGES_TOTAL_AMOUNT]
              [CMGT_PAGE_LINK]

Regards From Church Name.',

                    'WPChurch_Sell_Spiritual_Gift_Subject'=>' You have Got new [CMGT_GIFT_NAME] from [CMGT_CHURCH_NAME]',
					
					'WPChurch_Sell_Spiritual_Gift_Template'=>'Dear [CMGT_MEMBERNAME], 
             
       You have get new spiritual Gift [CMGT_GIFT_NAME] from [CMGT_CHURCH_NAME]. 
              Gift Name :[CMGT_GIFT_NAME].
              Gift Price :[CMGT_GIFT_PRICE].
              Gift Got  Date : [CMGT_GIFT_GOT_DATE].
             [CMGT_PAGE_LINK]

Regards From [CMGT_CHURCH_NAME].',

                    'WPChurch_Add_Transaction_Subject'=>' Your have a new invoice from [CMGT_CHURCH_NAME]',
					
					'WPChurch_Add_Transaction_Template'=>'Dear [CMGT_MEMBERNAME],

        Your have a new transaction invoice. You can check the invoice attached here. For payment click [CMGT_PAYMENT_LINK]
 
Regards From [CMGT_CHURCH_NAME].',

                     'WPChurch_Payment_Received_against_Transaction_Invoice_Subject'=>' Your have successfully paid your transaction invoice at [CMGT_CHURCH_NAME]',
					 
					 'WPChurch_Payment_Received_against_Transaction_Invoice_Template'=>'Dear [CMGT_MEMBERNAME],

        Your have successfully paid your transaction invoice.  You can check the invoice attached here.
 
Regards From Church Name.',

                      'WPChurch_Add_Donation_subject'=>'Your have successfully donated [CMGT_DONATION_AMOUNT] at [CMGT_CHURCH_NAME] on [CMGT_DONATION_DATE]',
					 
					  'WPChurch_Add_Donation_Template'=>'Dear [CMGT_MEMBERNAME] ,

        Your have successfully donate your [CMGT_DONATION_TYPE].  You can check the invoice attached here.
 
Regards From [CMGT_CHURCH_NAME].',

                      'WPChurch_Add_Donation_Admin_subject'=>'New donation arrived from [CMGT_MEMBERNAME] in [CMGT_CHURCH_NAME]',
					 
					  'WPChurch_Add_Donation_Admin_Template'=>'Dear [CMGT_ADMIN_NAME],

        New donation arrived from [CMGT_MEMBERNAME].  You can check the invoice attached here.
        View Donation : [CMGT_DONATION_LINK]
 
Regards From [CMGT_CHURCH_NAME].',

                      'WPChurch_Add_Income_Subject'=>'Your have a new Payment Invoice raised by [CMGT_USER_ROLE] at [CMGT_CHURCH_NAME]',
					 
					  'WPChurch_Add_Income_Template'=>'Dear [CMGT_MEMBERNAME],

        Your have a new Payment Invoice raised by Admin. You can check the Invoice attached here.
   [CMGT_INVOICE_LINK]

Regards From [CMGT_CHURCH_NAME].',
 
                      'WPChurch_Message_Received_subject'=>'You have received new message from [CMGT_SENDER_NAME] at [CMGT_CHURCH_NAME]',
					 
					  'WPChurch_Message_Received_Template'=>'Dear [CMGT_RECEIVER_NAME],

         You have received new message from [CMGT_SENDER_NAME]. [CMGT_MESSAGE_CONTENT].

         [CMGT_MESSAGE_LINK] 

 Regards From [CMGT_CHURCH_NAME].',
 
                      'WPChurch_Add_Pastoral_subject'=>'[CMGT_USER] Added new pastoral in [CMGT_CHURCH_NAME]',
					 
					  'WPChurch_Add_Pastoral_Template'=>'Dear [CMGT_MEMBERNAME], 
             
        Added New Pastoral [CMGT_PASTORAL_TITLE]  for you in [CMGT_CHURCH_NAME]. 
               Pastoral Date : [CMGT_PASTORAL_DATE].
               Pastoral Time : [CMGT_PASTORAL_TIME].
               Pastoral  Description : [CMGT_PASTORAL_DESCRIPTION].
               [CMGT_PAGE_LINK]

Regards From [CMGT_CHURCH_NAME].',

                      'WPChurch_Add_Notice_Admin_subject'=>'New notice created by [CMGT_MEMBERNAME] in [CMGT_CHURCH_NAME]',
					 
					  'WPChurch_Add_Notice_Template'=>'Dear [CMGT_ADMIN],

        New notice created by [CMGT_MEMBERNAME]  in [CMGT_CHURCH_NAME].  Please check that notice and 
 approve it.
 
Regards From [CMGT_CHURCH_NAME].',

		);
		return $options;
	}
add_action('admin_init','MJ_cmgt_general_setting');
//ADD GENERAL SETTINGS OPTION FUNCTION	
function MJ_cmgt_general_setting()
{
	$options=MJ_cmgt_option();
	foreach($options as $key=>$val)
	{
		add_option($key,$val); 

	}
}
//ADMIN SIDE CSS AND JS ADD FUNCTION
function MJ_cmgt_change_adminbar_css($hook) 
{	
	$current_page = $_REQUEST['page'];
	$pos = strrpos($current_page, "cmgt-");
	if($pos !== false)			
	{
			wp_register_script( 'jquery-3.0.6', plugins_url( '/assets/js/jquery.min.js', __FILE__), array( 'jquery' ) );
			wp_enqueue_script( 'jquery-3.0.6' );			
			wp_enqueue_style( 'accordian-jquery-ui-css', plugins_url( '/assets/accordian/jquery-ui.css', __FILE__) );
			wp_enqueue_script('accordian-jquery-ui', plugins_url( '/assets/accordian/jquery-ui.js',__FILE__ ));
			//Datatable //
			
			wp_enqueue_style( 'cmgt-datatable-css', plugins_url( '/assets/css/dataTables.css', __FILE__) );
			wp_enqueue_script('cmgt-datatable', plugins_url( '/assets/js/jquery.dataTables.min.js',__FILE__ ));
			wp_enqueue_script('cmgt-datatable-tools', plugins_url( '/assets/js/dataTables.tableTools.min.js',__FILE__ ));
			wp_enqueue_script('cmgt-datatable-editor', plugins_url( '/assets/js/dataTables.editor.min.js',__FILE__ ));	
			wp_enqueue_script('cmgt-dataTables.responsive-js', plugins_url( '/assets/js/dataTables.responsive.js',__FILE__ ));
			wp_enqueue_style( 'cmgt-datatable-select-css', plugins_url( '/assets/css/select.dataTables.min.css', __FILE__) );
			wp_enqueue_style( 'cmgt-dataTables.responsive-css', plugins_url( '/assets/css/dataTables.responsive.css', __FILE__) );
			//END Datatable //
			
			if ( is_rtl() ) {
				// Load RTL CSS.
				wp_enqueue_style( 'cmgt-rtl-css', plugins_url( '/assets/css/new_design_rtl.css', __FILE__) );
			}

			wp_enqueue_style( 'cmgt  -calender-css-min', plugins_url( '/assets/css/fullcalendar.min.css', __FILE__) );
			wp_enqueue_style( 'cmgt-dashboard-css', plugins_url( '/assets/css/dashboard.css', __FILE__) );
				
			wp_enqueue_style( 'cmgt-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
			wp_enqueue_style( 'cmgt-new-desig-css', plugins_url( '/assets/css/new-design.css', __FILE__) );
			wp_enqueue_style( 'cmgt-dynamic_css-css', plugins_url( '/assets/css/dynamic_css.php', __FILE__) );

			// hitesh start 
			wp_enqueue_style( 'cmgt-font-poppins-css', plugins_url( '/assets/css/popping_font.css', __FILE__) );
			// hitesh end

			wp_enqueue_style( 'cmgt-popup-css', plugins_url( '/assets/css/popup.css', __FILE__) );
			wp_enqueue_style( 'cmgt-custom-css', plugins_url( '/assets/css/custom.css', __FILE__) );
			wp_enqueue_style( 'cmgt-custom-admin-css', plugins_url( '/assets/css/custom-admin.css', __FILE__) );
			wp_enqueue_style( 'cmgt-select2-css', plugins_url( '/lib/select2-3.5.3/select2.css', __FILE__) );
					
			wp_enqueue_script('cmgt-select2', plugins_url( '/lib/select2-3.5.3/select2.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
			wp_enqueue_script('cmgt-calender_moment', plugins_url( '/assets/js/moment.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
			
			if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'cmgt-church_system')
			wp_enqueue_script('cmgt-calender', plugins_url( '/assets/js/fullcalendar.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
			
			/*--------Full calendar multilanguage---------*/
			$lancode=get_locale();
			$code=substr($lancode,0,2);
			wp_enqueue_script('cmgt-calender-es', plugins_url( '/assets/js/calendar-lang/'.$code.'.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );	
			
			wp_enqueue_script('cmgt-popup', plugins_url( '/assets/js/popup.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
			//popup file alert msg languages translation//				
			wp_localize_script('cmgt-popup', 'language_translate', array(
				'please_enter_caegory_name_alert' => __( 'Please enter Category Name.', 'church_mgt' ),
				'Please_select_at_least_one_record_alert' => __( 'Please select at least one record.', 'church_mgt' ),
				'delete_record_alert' => __( 'Are you sure want to delete this record?', 'church_mgt' ),
				'max_limit_member_alert' => __( 'Participant value must be less than or equals to the capacity', 'church_mgt' ),
				)
			);
			wp_localize_script( 'cmgt-popup', 'cmgt  ', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script('jquery');
			wp_enqueue_media();
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			wp_enqueue_script('cmgt-image-upload', plugins_url( '/assets/js/image-upload.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
		
			wp_enqueue_style( 'cmgt-bootstrap-css', plugins_url( '/assets/css/bootstrap.min.css', __FILE__) );
			wp_enqueue_style( 'cmgt-bootstrap-multiselect-css', plugins_url( '/assets/css/bootstrap-multiselect.min.css', __FILE__) );
			
			// meterial design css and js
			wp_enqueue_style( 'cmgt-bootstrap-meterial-css', plugins_url( '/assets/default/material/bootstrap-inputs.css', __FILE__) );

			wp_enqueue_script( 'cmgt-bootstrap-meterial-js', plugins_url( '/assets/default/material/material.min.js', __FILE__) );

			//END meterial design css and js
			wp_enqueue_style( 'cmgt-time-css', plugins_url( '/assets/css/time.css', __FILE__) );
			wp_enqueue_style( 'cmgt-font-awesome-css', plugins_url( '/assets/css/font-awesome.min.css', __FILE__) );

			wp_enqueue_style( 'cmgt-white-css', plugins_url( '/assets/css/white.css', __FILE__) );
			wp_enqueue_style( 'cmgt-gymmgt-min-css', plugins_url( '/assets/css/gymmgt.min.css', __FILE__) );

			//  popup design start
			wp_enqueue_style( 'cmgt-hospitalmgt-css', plugins_url( '/assets/css/hospitalmgt.min.css', __FILE__) );
			//popup design  end

			//New Wordpress Version CSS//
			wp_enqueue_style( 'cmgt-new-version-css', plugins_url( '/assets/css/newversion.css', __FILE__) );
			//ENd New Wordpress Version CSS//
			if (is_rtl())
			{
				wp_enqueue_style( 'cmgt-bootstrap-rtl-css', plugins_url( '/assets/css/bootstrap-rtl.min.css', __FILE__) );
				
			}
			wp_enqueue_style( 'cmgt-gym-responsive-css', plugins_url( '/assets/css/gym-responsive.css', __FILE__) );

			wp_enqueue_style( 'cmgt-responsive-css', plugins_url( '/assets/css/cmgt-responsive.css', __FILE__) );
		
			wp_enqueue_script('cmgt-bootstrap-multiselect-js', plugins_url( '/assets/js/bootstrap-multiselect.min.js', __FILE__ ) );
			
			wp_enqueue_script('cmgt-bootstrap-bundle-js', plugins_url( '/assets/js/bootstrap.bundle.min.js', __FILE__ ) );
			
			wp_enqueue_script('cmgt-popper-js', plugins_url( '/assets/js/popper.min.js', __FILE__ ) );
			 
			wp_enqueue_script('cmgt-time-js', plugins_url( '/assets/js/time.js', __FILE__ ) );
			 
			wp_enqueue_script('cmgt-timeago-js', plugins_url( '/assets/js/jquery.timeago.js', __FILE__ ) );

			wp_enqueue_script('cmgt-datatable-button-js', plugins_url( '/assets/js/cmgt-dataTables-buttons-min.js', __FILE__ ) );

			wp_enqueue_script('cmgt-buttons-button-js', plugins_url( '/assets/js/cmgt-buttons-print-min.js', __FILE__ ) );

			wp_enqueue_script('cmgt-pdfmake-min-js', plugins_url( '/assets/js/cmgt-pdfmake-min.js', __FILE__ ) );

			wp_enqueue_script('cmgt-buttons-html5-min-js', plugins_url( '/assets/js/cmgt-buttons.html5.min.js', __FILE__ ) );
			
			// wp_enqueue_style( 'cmgt-button-datatable-css', plugins_url( '/assets/css/buttons.dataTables.min.css', __FILE__) );

			wp_enqueue_style( 'cmgt-vfs-fonts-js', plugins_url( '/assets/js/cmgt-vfs_fonts.js', __FILE__) );
			
			//Validation style And Script//
			
			//validation lib//
			$lancode=get_locale();
			$code=substr($lancode,0,2);
			
			wp_enqueue_style( 'cmgt-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );	
			wp_register_script( 'jquery-validationEngine-'.$code.'', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
			wp_enqueue_script( 'jquery-validationEngine-'.$code.'' ); 	
			wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array( 'jquery' ) );
			wp_enqueue_script( 'jquery-validationEngine' );
			
	}
}
if(isset($_REQUEST['page']))
		add_action( 'admin_enqueue_scripts', 'MJ_cmgt_change_adminbar_css' );
}
//INSTALL LOGIN PAGE
function MJ_cmgt_install_login_page()
{
	// remove_role("management");
	if ( !get_option('cmgt_login_page') ) 
	{
		$curr_page = array(
				'post_title' => __('Church Management Login Page', 'church_mgt'),
				'post_content' => '[cmgt_login]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		
		$curr_created = wp_insert_post( $curr_page );
		update_option('cmgt_login_page', $curr_created );
	}
}
//FRONTEN SIDE GET USER DASHBOARD REQUEST FUNCTION
function MJ_cmgt_user_dashboard()
{
	if(isset($_REQUEST['church-dashboard']))
	{
		require_once CMS_PLUGIN_DIR. '/fronted_template.php';
		exit;
	}
}
add_action('wp_head','MJ_cmgt_user_dashboard');
// add_action( "init","MJ_cmgt_redirect_dashboard");
// function MJ_cmgt_redirect_dashboard()
// {
// 	$current_page = $_REQUEST['page'];
// 	$pos = strrpos($current_page, "cmgt-");
// 	if($pos !== false)			
// 	{
// 		require_once CMS_PLUGIN_DIR. '/admin_template.php';
// 		exit;
// 	}
// }
function MJ_cmgt_remove_all_theme_styles()
{
	global $wp_styles;
	$wp_styles->queue = array();
}
if(isset($_REQUEST['church-dashboard']) && $_REQUEST['church-dashboard'] == 'user')
{
	add_action('wp_print_styles', 'MJ_cmgt_remove_all_theme_styles', 100);
}
function MJ_cmgt_load_script1()
{
	if(isset($_REQUEST['church-dashboard']) && $_REQUEST['church-dashboard'] == 'user')
	{
		// wp_register_script('cmgt  -popup-front', plugins_url( 'assets/js/popup.js', __FILE__ ), array( 'jquery' ));
		wp_enqueue_script('cmgt  -popup-front');
		//popup file alert msg languages translation//				
		wp_localize_script('cmgt  -popup-front', 'language_translate', array(
				'please_enter_caegory_name_alert' => __( 'Please enter Category Name.', 'church_mgt' ),
			)
		);
		wp_localize_script( 'cmgt  -popup-front', 'cmgt  ', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script('jquery');
	}
}
function MJ_cmgt_domain_load()
{
	load_plugin_textdomain( 'church_mgt', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
}
function MJ_cmgt_install_member_registration_page() {
	

	if ( !get_option('cmgt_member_registration_page') ) {
		

		$curr_page = array(
				'post_title' => __('Member Registration', 'church_mgt'),
				'post_content' => '[cmgt_member_registration]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		$curr_created = wp_insert_post( $curr_page );
		update_option( 'cmgt_member_registration_page', $curr_created );
	}
}
add_action( 'plugins_loaded', 'MJ_cmgt_domain_load' );
add_action('init','MJ_cmgt_install_login_page');
add_shortcode( 'cmgt_login','MJ_cmgt_login_link' );

add_action('wp_enqueue_scripts','MJ_cmgt_load_script1');
add_action('init','MJ_cmgt_output_ob_start');
add_action('init','MJ_cmgt_install_member_registration_page');
add_shortcode( 'cmgt_member_registration', 'MJ_cmgt_custom_registration_shortcode' );
 
// The callback function that will replace [hook]
function MJ_cmgt_custom_registration_shortcode() {
    ob_start();
    MJ_cmgt_member_registration_function();
    return ob_get_clean();
}
// MEMBER REGISTRATION FUNCTION
function MJ_cmgt_member_registration_function()
{
	global $first_name,$middle_name,$last_name,$gender,$birth_date,$marital_status,$occupation,$education,$address,$city_name,$mobile_number,$phone,$email,$fax_number,$skyp_id,$begin_date,$baptist_date,$volunteer,$username,$password,$cmgt_user_avatar,$phonecode;
	$ministry_id=array();
	$group_id=array();
	   
    if ( isset($_POST['save_member_front'] ) ) 
	{ 
        MJ_cmgt_registration_validation(
		
		$_POST['first_name'],
		$_POST['middle_name'],
		$_POST['last_name'],
		$_POST['gender'],
		$_POST['birth_date'],
		$_POST['marital_status'],
		$_POST['address'],
		$_POST['city_name'],
		$_POST['mobile'],
		$_POST['email'],
		$_POST['begin_date'],
		$_POST['baptist_date'],
        $_POST['username'],
        $_POST['phonecode'],
        $_POST['password']);
         
		 
		$member_id =    $_POST['member_id'];
		$first_name =    $_POST['first_name'];
		$middle_name =   $_POST['middle_name'];
		$last_name =  $_POST['last_name'];
		$birth_date =   $_POST['birth_date'];
		$birth_day = date("m/d", strtotime($_POST['birth_date']));
		$gender =   $_POST['gender'];
		$marital_status =   $_POST['marital_status'];
		$ministry_id =   $_POST['ministry_id'];
		$group_id =   $_POST['group_id'];
		$occupation =   $_POST['occupation'];
		$education =   $_POST['education'];
		$address =   $_POST['address'];
		$city_name =    $_POST['city_name'];
		$mobile_number =   $_POST['mobile'];
		$phone =   $_POST['phone'];		
		$fax_number =   $_POST['fax_number'];		
		$skyp_id =   $_POST['skyp_id'];		
		$begin_date =   $_POST['begin_date'];		
		$baptist_date =   $_POST['baptist_date'];		
		$volunteer =   isset($_POST['volunteer'])?$_POST['volunteer']:'no';		
		$username   =    $_POST['username'];
        $password   =    $_POST['password'];
        $email      =    $_POST['email'];
        $phonecode      =    $_POST['phonecode'];
        $cmgt_user_avatar      =    isset($_POST['cmgt_user_avatar'])?$_POST['cmgt_user_avatar'] : "";
        
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        MJ_cmgt_complete_registration(
        $member_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$birth_day,$marital_status,$ministry_id,$group_id,$occupation,$education,$address,$city_name,$mobile_number,$phone,$email,$fax_number,$skyp_id,$begin_date,$baptist_date,$volunteer,$username,$password,$cmgt_user_avatar,$phonecode
        );
	}
    MJ_cmgt_registration_form(
      isset($member_id),$first_name,$middle_name,$last_name,$gender,$birth_date,isset($birth_day),$marital_status,$ministry_id,$group_id,$occupation,$education,$address,$city_name,$mobile_number,$phone,$email,$fax_number,$skyp_id,$begin_date,$baptist_date,$volunteer,$username,$password,$cmgt_user_avatar,$phonecode);
}
function MJ_cmgt_registration_validation($first_name,$middle_name,$last_name,$gender,$birth_date,$marital_status,$address,$city_name,$mobile_number,$email,$begin_date,$baptist_date,$username,$password,$phonecode)  
{
	global $reg_errors;
	$reg_errors = new WP_Error;
	if ( empty( $first_name ) || empty( $last_name ) || empty( $birth_date ) || empty( $marital_status ) || empty( $address ) || empty( $city_name ) ||  empty( $email ) ||  empty( $begin_date ) ||  empty( $baptist_date ) || empty( $username ) || empty( $password ) || empty( $phonecode ) ) 
	{
		$reg_errors->add('field', 'Required form field is missing');
	}
	if ( 4 > strlen( $username ) ) {
		$reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
	}
	if ( username_exists( $username ) )
		$reg_errors->add('user_name', 'Sorry, that username already exists!');
	if ( ! validate_username( $username ) ) {
    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
	}
	
	if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid' );
	}
	if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use' );
	}
	
	if ( is_wp_error( $reg_errors ) ) 
	{
 
		foreach ( $reg_errors->get_error_messages() as $error ) 
		{
			echo '<div class="student_reg_error">';
			echo '<strong>ERROR</strong> : ';
			echo '<span class="error"> '.$error . ' </span><br/>';
			echo '</div>';
		}
	}	
}
function MJ_cmgt_registration_form($member_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$birth_day,$marital_status,$ministry_id,$group_id,$occupation,$education,$address,$city_name,$mobile_number,$phone,$email,$fax_number,$skyp_id,$begin_date,$baptist_date,$volunteer,$username,$password,$cmgt_user_avatar,$phonecode) 
{
	$obj_group=new Cmgtgroup;
	$obj_member=new Cmgtmember;
	$obj_ministry=new Cmgtministry;
	$theme_name=get_current_theme();
	// var_dump($theme_name);
		if($theme_name == 'Twenty Twenty-Two')
		{	
	
			wp_enqueue_script('cmgt-popper-js', plugins_url( '/assets/js/jquery.min.js', __FILE__ ) );
			wp_enqueue_script('cmgt-popper-js', plugins_url( '/assets/js/bootstrap-datepicker.js', __FILE__ ) );
		}
	
		$lancode=get_locale();
		$code=substr($lancode,0,2);
		wp_enqueue_style( 'wcwm-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );	
		wp_register_script( 'jquery-validationEngine-'.$code.'', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
		wp_enqueue_script( 'jquery-validationEngine-'.$code.'' ); 	
		wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array( 'jquery' ) );
		wp_enqueue_script( 'jquery-validationEngine' );

	 	wp_enqueue_style( 'wcwm-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );
	
		wp_enqueue_style( 'accordian-jquery-ui-css', plugins_url( '/assets/accordian/jquery-ui.css', __FILE__) );
		wp_enqueue_script('smgt-custom_jobj', plugins_url( '/assets/js/smgt_custom_confilict_obj.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
		
		// meterial design css and js
		wp_enqueue_style( 'cmgt-bootstrap-meterial-css', plugins_url( '/assets/default/material/bootstrap-inputs.css', __FILE__) );

		wp_enqueue_script( 'cmgt-bootstrap-meterial-js', plugins_url( '/assets/default/material/material.min.js', __FILE__) );
		//END meterial design css and js

		// hitesh start 
		wp_enqueue_style( 'cmgt-font-poppins-css', plugins_url( '/assets/css/popping_font.css', __FILE__) );
		wp_enqueue_style( 'cmgt-font-poppins-css111', plugins_url( '/assets/css/font-awesome.min.css', __FILE__) );
		// hitesh end

		//  popup design start
		wp_enqueue_style( 'cmgt-hospitalmgt-css', plugins_url( '/assets/css/hospitalmgt.min.css', __FILE__) );
		//popup design  end

		wp_enqueue_style( 'cmgt-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
		wp_enqueue_style( 'cmgt-new-desig-css', plugins_url( '/assets/css/new-design.css', __FILE__) );
		wp_enqueue_style( 'cmgt-dynamic_css-css', plugins_url( '/assets/css/dynamic_css.php', __FILE__) );

		//new date picker  css and js //
		wp_enqueue_style( 'cmgt-datepicker-css', plugins_url( '/assets/css/datepicker.css', __FILE__) );
		wp_enqueue_script('cmgt-bootstrap-datepicker-js', plugins_url( '/assets/js/bootstrap-datepicker.js', __FILE__ ) );
		wp_enqueue_style( 'cmgt-bootstrap-css', plugins_url( '/assets/css/bootstrap.min.css', __FILE__) );
		wp_enqueue_style( 'cmgt-bootstrap-multiselect-css', plugins_url( '/assets/css/bootstrap-multiselect.min.css', __FILE__) );
		wp_enqueue_script('cmgt-bootstrap-multiselect-js', plugins_url( '/assets/js/bootstrap-multiselect.min.js', __FILE__ ) );
		wp_enqueue_script('cmgt-bootstrap-bundle-js', plugins_url( '/assets/js/bootstrap.bundle.min.js', __FILE__ ) );
		 wp_enqueue_script('cmgt-bootstrap-js', plugins_url( '/assets/js/bootstrap.min.js', __FILE__ ) );
		 
		 wp_enqueue_script('cmgt-image-upload', plugins_url( '/assets/js/image-upload.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
		
		 wp_enqueue_script('cmgt-popper-js', plugins_url( '/assets/js/popper.min.js', __FILE__ ) );
		?>
		<?php
			if($theme_name != 'Twenty Twenty-Two')
			{
				wp_enqueue_script( 'cmgt-popper-js', plugins_url( '/assets/js/jquery.min.js', __FILE__ ), array( 'jquery' ) );
				?>
					<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/jquery.min.js'; ?>"></script>
					<script type="text/javascript"	src="<?php echo CMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script>
				<?php
			}
		?>
	
		
		<?php
    echo '
    <style>
	.student_registraion_form .form-group,.student_registraion_form .form-group .form-control{float:left;width:100%}
	.student_registraion_form .form-group .require-field{color:red;}
	.student_registraion_form select.form-control,.student_registraion_form input[type="file"] {
	padding: 0.5278em;
	margin-bottom: 5px;
	}
	.student_registraion_form  .radio-inline {
		float: left;
		margin-bottom: 10px;
		margin-top: 10px;
		margin-right: 15px;
	}
	.student_registraion_form  .radio-inline .tog {
		margin-right: 5px;
	}
	.student_registraion_form .col-sm-2.control-label {
	line-height: 50px;
	text-align: right;
	}
	/* .student_registraion_form .form-group .col-sm-2 {width: 32.666667%;}
	.student_registraion_form .form-group .col-sm-8 {     width: 66.66666667%;}
	.student_registraion_form .form-group .col-sm-7{  width: 53.33333333%;}
	.student_registraion_form .form-group .col-sm-1{  width: 13.33333333%;} */
	// .student_registraion_form .form-group .col-sm-8, .student_registraion_form .form-group .col-sm-2,.student_registraion_form .form-group .col-sm-7,.student_registraion_form .form-group .col-sm-1{      
	// padding-left: 15px;
	//  padding-right: 15px;
	// float:left;}
	.student_registraion_form .form-group .col-sm-8, .student_registraion_form .form-group .col-sm-2,.student_registraion_form .form-group .col-sm-7{
		position: relative;
		min-height: 1px;   
	}

    div {
        margin-bottom:2px;
    }
     
    input{
        margin-bottom:4px;
    }
	.student_registraion_form .col-sm-offset-2.col-sm-8 {
	  float: left;
	  margin-left: 35%;
	  margin-top: 15px;
	}
	#registration_form .col-sm-3.control-label {
		text-align: left !importants;
		margin-top: 8px;
	}
	.student_reg_error .error{color:red;}
	@media (max-width: 420px){
		#registration_form .col-sm-3.control-label {
			text-align: left;
		}
	}
	@media (min-width: 340px) and (max-width: 420px){
		#registration_form .formError{
			left: 230px !important;
		}
	}
	@media (min-width: 320px) and (max-width: 340px){
		#registration_form .formError{
			left: 185px !important;
		}
	}
	.student_registraion_form .check_box_responsive_reg_form input{
		width: 20px !important;
		height: 20px;
	}
	body.is-light-theme{
		background-color: var(--global--color-background);
	}
	
	/* Fronted Member Registration css start */
	.student_registraion_form select.cmgt_select_frontend{
	height: 48px!important;
	}
	.student_registraion_form .cmgt_input_height_frontend{
	height: 48px!important;
	}
	.datepicker table {
	width: 100% !important;
	}
	.student_registraion_form .user_form #volunteer{
		background:#FFFFFF!important;
		margin-top: 5px;
	}
	// .entry .entry-content, .entry .entry-summary {
	// margin: 0px 21%!important;
	// }
	.student_registraion_form #registration_form label.cmgt_frontend_profile_label{
		transform: translate(-12.5%,-1.5em) scale(.9,.9)!important;
		background-color: #fff;
		padding: 0 10px;
		position: absolute;
	}
	.student_registraion_form input[type="file"] {
	margin-top: 5px!important;
	}
	.student_registraion_form .check_box_responsive_reg_form input[type=checkbox]:checked::before {
		background-color: #4574FF !important;
		color: #FFFFFF!important;
		width: 24px !important;
		height: 22px;
	}
	.student_registraion_form .header h3 {
		color: #818386 !important;
		font-family: Poppins!important;
		font-style: normal !important;
		font-weight: normal !important;
		font-size: 20px !important;
	}
	.student_registraion_form #cmgt_frontend_margin_0{
		margin: 0!important;
	}
	.student_registraion_form input.btn.save_btn{
		font-family: Poppins!important;
		text-decoration: none;
		font-weight: 500;
	}
	.student_registraion_form .save_btn{
		width: 60%!important;
		height: 48px!important;
		border-radius: 28px;
	}
	@media (min-width: 700px)
	{
		.entry-content h3 {
			margin-top: 4px;
		}
	}
	@media screen and (max-width: 576px) {
		.student_registraion_form .user_form #volunteer {
			width: 20px!important;
		}
		.student_registraion_form .user_form .cmgt_frontend_label_width{
			width: 100px!important;
		}
	}

	/* Fronted Member Registration css End */
	
	
	
	
    </style>
    ';?>
	<script type="text/javascript">
		$(document).ready(function()
		{
			
			$(".notice_dismiss_ragistarion").click(function()
			{
                $(".sucees_messsage").hide();
            });
			$('#registration_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			jQuery('#birth_date').datepicker({
				dateFormat: "yy-mm-dd",
				maxDate : 0,
				changeMonth: true,
				changeYear: true,
				autoclose: true,
				yearRange:'-100:+25',
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
						
			$('#baptist_date').datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange:'-100:+25',
				onChangeMonthYear: function(year, month, inst) {
				 $(this).val(month + "/" + year);
				}
			}); 
			$('#begin_date').datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange:'-100:+25',
				onChangeMonthYear: function(year, month, inst) {
				$(this).val(month + "/" + year);
				}
			}); 
			
			$('.onlynumber_and_plussign').on('keyup', function()
			{
				var phoneno = /^[0-9\+]+$/;
				if(($(this).val().match(phoneno)))
				{
					
				}
				else
				{
					alert('Please Enter Only + and 0-9');			
					$(this).val('');
					return false;
				} 		
			});	
		});
		function fileCheck(obj) 
		{
			var fileExtension = ['jpeg', 'jpg', 'png', 'bmp',''];
			if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
			{
				alert("Only "+fileExtension+"formats are allowed.");
				$(obj).val('');
			}	
		}
	</script>		
		
	<?php   
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == "success_registration_message")
	{
		?>
		<div  id="message" class="sucees_messsage">
		<p>
			<?php
			esc_html_e('Registration complete.Your account active after admin can approve.','church_mgt');
			?>
		</p>
		<button type="button" class="notice_dismiss_ragistarion"><i class="fa fa-close" style="font-size:20px"></i></button>
		</div>
		<?php
	}
	$role="member";
	$lastmember_id=MJ_cmgt_get_lastmember_id($role);
		$nodate=substr($lastmember_id,0,-4);
		$memberno=substr($nodate,1);
		$add="1";
		$test=(int)$memberno+(int)$add;
		$newmember='M'.$test.date("my");

	$edit = 0;
	echo '
	<div class="student_registraion_form">   
    <form id="registration_form" action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">';
		
	?>
		<div class="form-body user_form">
			<div class="header mb-3">
				<h3><?php esc_html_e('Personal Information','church_mgt');?></h3>
			</div>  
			<div class="row">
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="member_id" class="form-control validate[required]" type="text" 
							value="<?php if($edit){ echo $user_info->member_id;} else echo $newmember;?>"  readonly name="member_id">
							<label class="" for="member_id"><?php esc_html_e('Member Id','church_mgt');?><span class="require-field">*</span></label>
						</div>	
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="first_name"  class="form-control no-outline validate[required,custom[onlyLetterSp]] text-input" type="text" maxlength="30" <?php if($edit){ ?>value="<?php	 echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>"  name="first_name">
							<label class="" for="first_name"><?php esc_html_e('First Name','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="middle_name" maxlength="30" class="form-control " type="text" <?php if($edit){ ?>value="<?php echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
							<label class="" for="middle_name"><?php esc_html_e('Middle Name','church_mgt');?></label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text"  maxlength="30" <?php if($edit){ ?> value="<?php echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
							<label class="" for="last_name"><?php esc_html_e('Last Name','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group mb-3">
						<div class="col-md-12 form-control cmgt_input_height_frontend">
							<div class="row padding_radio">
								<div class="input-group">
									<label class="custom-top-label margin_left_0" for="gender"><?php esc_html_e('Gender','church_mgt');?><span class="require-field">*</span></label>

									<div class="d-inline-block">
										<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
										
										<input type="radio" value="male" class="custom-control-input tog" name="gender"  <?php  checked( 'male', $genderval);  ?>/>
										<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male','church_mgt');?></label>
										<input type="radio" value="female" class="custom-control-input tog" name="gender"  <?php  checked( 'female', $genderval);  ?>/>
										<label class="custom-control-label" for="female"><?php esc_html_e('Female','church_mgt');?></label>
									</div>
								</div>
							</div>		
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="birth_date" autocomplete="off" class="form-control validate[required]" type="text"  name="birth_date" data-date-format="yyyy-mm-dd"  
							value="<?php if($edit){ echo $user_info->birth_date;}elseif(isset($_POST['birth_date'])){ echo $_POST['birth_date'];}else{ echo date('Y-m-d'); }?>" autocomplete="off" readonly>
							<label class="" for="birth_date"><?php esc_html_e('Date of Birth','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group mb-3">
						<div class="col-md-12 form-control cmgt_input_height_frontend">
							<div class="row padding_radio">
								<div class="input-group">
									<label class="custom-top-label margin_left_0" for="marital-status"><?php esc_html_e('Marital Status','church_mgt');?><span class="require-field">*</span></label>

									<div class="d-inline-block">
										<?php $marital_val = "unmarried"; if($edit){ $marital_val=$user_info->marital_status; }elseif(isset($_POST['marital_status'])) {$marital_val=$_POST['marital_status'];}?>

										<input type="radio" value="unmarried" class="custom-control-input tog" name="marital_status"  <?php  checked( 'unmarried', $marital_val);  ?>/>

										<label class="custom-control-label margin_right_20px"><?php esc_html_e('Unmarried','church_mgt');?></label>

										<input type="radio" value="married" class="custom-control-input tog" name="marital_status"  <?php  checked( 'married', $marital_val);  ?>/>

										<label class="custom-control-label margin_right_20px"><?php esc_html_e('Married','church_mgt');?></label>
									</div>
								</div>
							</div>		
						</div>
					</div>
				</div>
				<div class="col-md-12 input cmgt_display">
					<label class="ml-1 custom-top-label top" for="ministry_id"><?php esc_html_e('Ministry','church_mgt');?></label>
					<select class="form-control line_height_30px cmgt_select_frontend" id="ministry_id"  name="ministry_id[]">
						<option selected><?php esc_html_e('Select Ministry','church_mgt');?></option>
							<?php $ministrydata=$obj_ministry->MJ_cmgt_get_all_ministry();
							if(!empty($ministrydata))
							{
								foreach ($ministrydata as $ministry)
								{ ?>
									<option value="<?php echo $ministry->id;?>" ><?php echo $ministry->ministry_name; ?> </option>
									<?php 
								} 
							} ?>
					</select>
				</div>

				<div class="col-md-12 input cmgt_display">
					<label class="ml-1 custom-top-label top" for="group_id"><?php esc_html_e('Group','church_mgt');?></label>
					<select class="form-control line_height_30px cmgt_select_frontend" id="group_id"  name="group_id[]">
						<option selected><?php esc_html_e('Select Group','church_mgt');?></option>
						<?php $groupdata=$obj_group->MJ_cmgt_get_all_groups();
						if(!empty($groupdata))
						{
							foreach ($groupdata as $group)
							{ ?>
								<option value="<?php echo $group->id;?>" ><?php echo $group->group_name; ?> </option>
								<?php 
							} 
						} ?>
					</select>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="occupation" class="form-control text-input" type="text"  maxlength="50" <?php if($edit){ ?>value="<?php echo $user_info->occupation;}elseif(isset($_POST['occupation'])) echo $_POST['occupation'];?>" name="occupation">
							<label class="" for="occupation"><?php esc_html_e('Occupation','church_mgt');?></label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">	
							<input id="education" class="form-control text-input" maxlength="30"type="text" <?php if($edit){ ?>value="<?php echo $user_info->education;}elseif(isset($_POST['education'])) echo $_POST['education'];?>" name="education">
							<label class="" for="education"><?php esc_html_e('Education','church_mgt');?></label>
						</div>
					</div>
				</div>	
			</div>
			<div class="header mb-3">
				<h3><?php esc_html_e('Contact Information','church_mgt');?></h3>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">	
							<input id="address" class="form-control validate[required]" type="text" maxlength="150" name="address" <?php if($edit){ ?>value="<?php echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
							<label class="" for="address"><?php esc_html_e('Address','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="city_name" class="form-control validate[required,custom[onlyLetterSp]]" type="text"  maxlength="50" name="city_name" <?php if($edit){ ?>value="<?php echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
							<label class="" maxlength="50" for="city_name"><?php esc_html_e('City','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-5 col-lg-4">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input type="text" value="+<?php echo MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' ));?>"  class="form-control validate[required] onlynumber_and_plussign" maxlength="5"  name="phonecode">
									<label for="country_code" class="pl-2 cmgt_country_code"><?php esc_html_e('Country Code','church_mgt');?><span class="require-field">*</span></label>
								</div>											
							</div>
						</div>
						<div class="col-md-7 col-lg-8">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mobile" class="form-control validate[required,custom[phone]] text-input" type="text"  name="mobile" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" maxlength="10" <?php if($edit){ ?>value="<?php  echo $user_info->mobile;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>" >
									<label class="" for="mobile"><?php esc_html_e('Mobile Number','church_mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
					</div>
				</div> 

				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="phone" class="form-control validate[,custom[phone]] text-input" type="text"  
							onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" maxlength="10" name="phone" 
							<?php if($edit){ ?>value="<?php echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
							<label class="" for="phone"><?php esc_html_e('Phone','church_mgt');?></label>
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="fax_number" class="form-control text-input" type="text"  name="fax_number" maxlength="12" <?php if($edit){ ?>value="<?php echo $user_info->fax_number;}elseif(isset($_POST['fax_number'])) echo $_POST['fax_number'];?>">
							<label class="" for="fax_number"><?php esc_html_e('Fax','church_mgt');?></label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="skyp_id" class="form-control text-input" type="text"  name="skyp_id" 
							<?php if($edit){ ?>value="<?php echo $user_info->skyp_id;}elseif(isset($_POST['skyp_id'])) echo $_POST['skyp_id'];?>">
							<label class="" for="skyp_id"><?php esc_html_e('Skyp Id','church_mgt');?></label>
						</div>
					</div>
				</div>
			</div>
			<div class="header mb-3">
				<h3><?php esc_html_e('Religion Information','church_mgt');?></h3>
			</div>
			<div class="row">	
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="begin_date" class="form-control validate[required]" type="text"  name="begin_date" data-date-format="yyyy-mm-dd" <?php if($edit){ ?>value="<?php echo $user_info->begin_date;}elseif(isset($_POST['begin_date'])) echo $_POST['begin_date'];?>" autocomplete="off" readonly>
							<label class="" for="begin_date"><?php esc_html_e('Join Church Date','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="baptist_date" class="form-control validate[required]" type="text"  name="baptist_date" data-date-format="yyyy-mm-dd" <?php if($edit){ ?>value="<?php  echo $user_info->baptist_date;}elseif(isset($_POST['baptist_date'])) echo $_POST['baptist_date'];?>" autocomplete="off" readonly>
							<label class="" for="baptist_date"><?php esc_html_e('Baptist Date','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
						
				<div class="col-md-12">
					<div class="form-group mb-3">
						<div class="col-md-12 form-control input_height_48px">
							<div class="row check_box_responsive_reg_form" style="" id="cmgt_frontend_margin_0">
								<input id="volunteer" class="col-sm-1" style="" type="checkbox"  name="volunteer" value="yes" <?php if($edit){ if($user_info->volunteer=='yes'){?> checked <?php } } ?>>
								<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label form-label cmgt_frontend_label_width" for="Volunteer"><?php esc_html_e('Volunteer','church_mgt');?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="header mb-3">
				<h3><?php esc_html_e('Login Information','church_mgt');?></h3>
			</div>  
			<div class="row">	
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="email" class="form-control validate[required,custom[email]] text-input" type="text"  name="email" maxlength="150"
							<?php if($edit){ ?>value="<?php echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
							<label class="" for="email"><?php esc_html_e('Email','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="username" class="form-control validate[required]" type="text"  name="username" <?php if($edit){ ?>value="<?php echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
							<label class="" for="username"><?php esc_html_e('User Name','church_mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password"  name="password">
							<label class="" for="password"><?php esc_html_e('Password','church_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
						</div>
					</div>
				</div>
			</div>
			<div class="header mb-3">
				<h3><?php esc_html_e('Profile Image','church_mgt');?></h3>
			</div>  
			<div class="row">
				<div class="col-md-12">
					<div class="input">
						<div class="col-md-12 form-control">
							<label class="cmgt_frontend_profile_label" for="photo"><?php esc_html_e('Image','church_mgt');?></label>
							<input type="file" style="" onchange="fileCheck(this);" class="form-control" name="cmgt_user_avatar" >
						</div>	
					</div>	
				</div> 
			</div>
			<div class="row">
				<div class="col-md-12 mt-2">
					<div class="offset-sm-0">
						<input type="submit" value="<?php esc_html_e('Registration','church_mgt');?>" name="save_member_front" class="btn btn-success col-md-12 save_btn"/>
					</div>
				</div>
			</div>
		</div>
    </form><!-- MEMBER REGISTRATION FORM END-->
	</div><!-- MEMBER REGISTRATION DIV END-->
    <?php
}
function MJ_cmgt_complete_registration($member_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$birth_day,$marital_status,$ministry_id,$group_id,$occupation,$education,$address,$city_name,$mobile_number,$phone,$email,$fax_number,$skyp_id,$begin_date,$baptist_date,$volunteer,$username,$password,$cmgt_user_avatar,$phonecode) 
 {
    global $reg_errors;
	 $member_image_url = '';	
		
    if ( 1 > count( $reg_errors->get_error_messages() ) ) 
	{
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'user_url'      =>   NULL,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'nickname'      =>   NULL
        
        );
        
	  $user_id = wp_insert_user( $userdata );
 	  $user = new WP_User($user_id);
	  $user->set_role('member');
	  $hash = md5( rand(0,1000) );
      update_user_meta( $user_id, 'cmgt_hash', $hash );
	  
	  $member_image_url = '';
	if(!empty($_FILES['cmgt_user_avatar']) && $_FILES['cmgt_user_avatar']['size'] > 0)
	{
		$member_image=MJ_cmgt_load_documets($_FILES['cmgt_user_avatar'],'cmgt_user_avatar','mimg');
		$upload_dir_url=MJ_cmgt_upload_url_path('church_assets'); 
		$member_image_url = $member_image;
	}
	else 
	{
		$member_image_url = '';
	}
		$usermetadata=array(					
						'middle_name'=>$middle_name,
						'member_id'=>$member_id,
						'gender'=>$gender,
						'birth_date'=>$birth_date,
						'birth_day'=>$birth_day,
						
						'marital_status'=>$marital_status,
						'occupation'=>$occupation,
						'education'=>$education,
						'address'=>$address,
						'city_name'=>$city_name,
						'phone'=>$phone,
						'mobile'=>$mobile_number,
						'fax_number'=>$fax_number,
						'skyp_id'=>$skyp_id,
						'begin_date'=>$begin_date,
						'baptist_date'=>$baptist_date,
						'volunteer'=>$volunteer,
						'phonecode'=>$phonecode,
						'cmgt_user_avatar'=>$member_image_url);
		 
		foreach($usermetadata as $key=>$val)
		{		
			$result=update_user_meta( $user_id, $key,$val );	
	
		}
		
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix.'cmgt_groupmember';
			if(!empty($group_id))
				{
					foreach($group_id as $id)
					{
						$group_data['group_id']=$id;
						$group_data['member_id']=$user_id;						
						$group_data['type']='group';						
						$group_data['created_date']=date("Y-m-d");
						$group_data['created_by']=$user_id;
						$wpdb->insert( $table_cmgt_groupmember, $group_data );
					}
				}
			
				if(!empty($ministry_id))
				{
					foreach($ministry_id as $id)
					{
						$ministry_data['group_id']=$id;
						$ministry_data['member_id']=$user_id;						
						$ministry_data['type']='ministry';						
						$ministry_data['created_date']=date("Y-m-d");
						$ministry_data['created_by']=$user_id;
						$wpdb->insert( $table_cmgt_groupmember, $ministry_data );
					}
				}
				//member ragistation mail template send  mail
				$user_info = get_userdata($user_id);
		        $to = $user_info->user_email; 
				$member_name=$user_info->display_name;
				$loginlink=home_url();
				$subject =get_option('WPChurch_Member_Registration');
		        $church_name=get_option('cmgt_system_name');
		         $message_content=get_option('WPChurch_registration_email_template');
				$subject_search=array('[CMGT_CHURCH_NAME]');
		        $subject_replace=array($church_name);
		        $search=array('[CMGT_MEMBERNAME]','[CMGT_CHURCH_NAME]','[CMGT_LOGIN_LINK]');
		        $replace = array($member_name,$church_name,$loginlink);
		        $message_content = str_replace($search, $replace, $message_content);
		        $subject=str_replace($subject_search,$subject_replace,$subject);
				
				$headers="";
	            $headers .= 'From: '.$church_name.' <noreplay@gmail.com>' . "\r\n";
	            $headers .= "MIME-Version: 1.0\r\n";
	            $headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
	            $enable_notofication=get_option('cmgt_enable_notifications');
	            if($enable_notofication=='yes'){
					wp_mail($to, $subject, $message_content,$headers); 
				}
		// if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) 
		// {
			if($result)
			{
				$page_id = get_option ('cmgt_member_registration_page');			
					$referrer_ipn = array(				
						'page_id' => $page_id,
						'action'=>'success_registration_message'
					);				
					$referrer_ipn = add_query_arg( $referrer_ipn, home_url() );	
					wp_redirect ($referrer_ipn);	
					exit;
				?>
				<?php
				
			}
		// }
	}
}
//OUTPUT OB START FUNCTION
function MJ_cmgt_output_ob_start()
{
	ob_start();
}
///INSTALL TABLE PLUGIN ACTIVATE DEAVTIVATE TIME
function MJ_cmgt_install_tables()
{
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	
	$table_cmgt_activity = $wpdb->prefix . 'cmgt_activity';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_activity." (
				  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
				  `activity_cat_id` int(11) NOT NULL,
				  `activity_title` varchar(255) NOT NULL,
				  `speaker_name` varchar(100) NOT NULL,
				  `venue_id` int(11) NOT NULL,
				  `activity_date` varchar(50) NOT NULL,
				  `activity_end_date` varchar(20) NOT NULL,
				  `activity_start_time` varchar(50) NOT NULL,
				  `activity_end_time` varchar(50) NOT NULL,
				  `record_start_time` varchar(50) NOT NULL,
				  `record_end_time` varchar(50) NOT NULL,
				  `groups` varchar(255) NOT NULL,
				  `recurrence_content` text NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`activity_id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_attendence = $wpdb->prefix . 'cmgt_attendence';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_attendence." (
				  `attendence_id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `activity_id` int(11) NOT NULL,
				  `attendence_date` date NOT NULL,
				  `status` varchar(50) NOT NULL,
				  `attendence_by` int(11) NOT NULL,
				  `role_name` varchar(50) NOT NULL,
				  PRIMARY KEY (`attendence_id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		
		$table_cmgt_notice = $wpdb->prefix . 'cmgt_notice';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_notice." (
				   `id` int(20) NOT NULL AUTO_INCREMENT,
				  `notice_title` varchar(255) NOT NULL,
				  `notice_content` text NOT NULL,
				  `start_date` varchar(20) NOT NULL,
				  `end_date` varchar(20) NOT NULL,
				  `status` int(20) NOT NULL,
				  `created_at` varchar(20) NOT NULL,
				  `created_by` int(20) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_checkin = $wpdb->prefix . 'cmgt_checkin';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_checkin." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `room_id` int(11) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `family_members` int(11) NOT NULL,
				  `checkin_date` varchar(20) NOT NULL,
				  `checkout_date` varchar(20) NOT NULL,
				  `status` varchar(20) NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_gifts = $wpdb->prefix . 'cmgt_gifts';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_gifts." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `gift_type` varchar(50) NOT NULL,
				  `gift_name` varchar(200) NOT NULL,
				  `gift_price` varchar(5) NOT NULL,
				  `description` text NOT NULL,
				  `media_gift` varchar(255) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_gift_assigned = $wpdb->prefix . 'cmgt_gift_assigned';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_gift_assigned." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `gift_id` int(11) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `gifted_by` int(11) NOT NULL,
				  `gifted_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_group = $wpdb->prefix . 'cmgt_group';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_group." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `group_name` varchar(100) NOT NULL,
				  `cmgt_groupimage` varchar(255) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_groupmember = $wpdb->prefix . 'cmgt_groupmember';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_groupmember." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `group_id` int(11) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `type` varchar(20) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_income_expense = $wpdb->prefix . 'cmgt_income_expense';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_income_expense." (
				  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
				  `invoice_type` varchar(100) NOT NULL,
				  `invoice_label` varchar(100) NOT NULL,
				  `supplier_name` varchar(100) NOT NULL,
				  `entry` text NOT NULL,
				  `payment_status` varchar(50) NOT NULL,
				  `receiver_id` int(11) NOT NULL,
				  `invoice_date` date NOT NULL,
				  PRIMARY KEY (`invoice_id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_message = $wpdb->prefix . 'cmgt_message';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_message." (
				  `message_id` int(11) NOT NULL AUTO_INCREMENT,
				  `sender` int(11) NOT NULL,
				  `receiver` int(11) NOT NULL,
				  `msg_date` datetime NOT NULL,
				  `msg_subject` varchar(150) NOT NULL,
				  `message_body` text NOT NULL,
				  `post_id` int(11) NOT NULL,
				  `msg_status` int(11) NOT NULL,
				  PRIMARY KEY (`message_id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_ministry = $wpdb->prefix . 'cmgt_ministry';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_ministry." (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `ministry_name` varchar(100) NOT NULL,
					  `ministry_image` varchar(255) NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `created_date` date NOT NULL,
					  PRIMARY KEY (`id`)
					) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_pledges = $wpdb->prefix . 'cmgt_pledges';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_pledges." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `member_id` int(11) NOT NULL,
				  `start_date` varchar(20) NOT NULL,
				  `amount` varchar(10) NOT NULL,
				  `period_id` varchar(50) NOT NULL,
				  `times_number` int(11) NOT NULL,
				  `end_date` varchar(20) NOT NULL,
				  `total_amount` varchar(10) NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_room = $wpdb->prefix . 'cmgt_room';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_room." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `room_title` varchar(200) NOT NULL,
				  `capacity` int(11) NOT NULL,
				  `demographics` varchar(255) NOT NULL,
				  `status` varchar(20) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_sermon = $wpdb->prefix . 'cmgt_sermon';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_sermon." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `sermon_title` varchar(200) NOT NULL,
				  `description` text NOT NULL,
				  `sermon_type` varchar(100) NOT NULL,
				  `sermon_content` varchar(255) NOT NULL,
				  `status` varchar(50) NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_service = $wpdb->prefix . 'cmgt_service';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_service." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `service_type_id` int(11) NOT NULL,
				  `service_title` varchar(100) NOT NULL,
				  `start_date` varchar(20) NOT NULL,
				  `end_date` varchar(20) NOT NULL,
				  `start_time` varchar(20) NOT NULL,
				  `end_time` varchar(20) NOT NULL,
				  `other_title` varchar(100) NOT NULL,
				  `other_service_type` varchar(100) NOT NULL,
				  `other_service_date` varchar(20) NOT NULL,
				  `other_start_time` varchar(20) NOT NULL,
				  `other_end_time` varchar(20) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_songs = $wpdb->prefix . 'cmgt_songs';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_songs." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `song_cat_id` int(11) NOT NULL,
				  `song_name` varchar(100) NOT NULL,
				  `description` text NOT NULL,
				  `song` varchar(255) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_transaction = $wpdb->prefix . 'cmgt_transaction';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_transaction." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `member_id` int(11) NOT NULL,
				  `transaction_date` varchar(20) NOT NULL,
				  `amount` varchar(11) NOT NULL,
				  `pay_method` varchar(100) NOT NULL,
				  `transaction_id` varchar(100) NOT NULL,
				  `description` text NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_venue = $wpdb->prefix . 'cmgt_venue';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_venue." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `venue_title` varchar(100) NOT NULL,
				  `capacity` int(11) NOT NULL,
				  `request_before_days` int(11) NOT NULL,
				  `multiple_booking` varchar(5) NOT NULL,
				  `equipments` varchar(255) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_venue_reservation = $wpdb->prefix . 'cmgt_venue_reservation';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_venue_reservation." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `usage_title` varchar(200) NOT NULL,
				  `vanue_id` int(11) NOT NULL,
				  `reserve_date` varchar(20) NOT NULL,
				  `reservation_start_time` varchar(20) NOT NULL,
				  `reservation_end_time` varchar(20) NOT NULL,
				  `reservation_end_date` varchar(20) NOT NULL,
				  `participant` int(11) NOT NULL,
				  `applicant_id` int(11) NOT NULL,
				  `participant_max_limit` int(11) NOT NULL,
				  `description` text NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
		$table_cmgt_message_replies = $wpdb->prefix . 'cmgt_message_replies';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_message_replies." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `message_id` int(11) NOT NULL,
				  `sender_id` int(11) NOT NULL,
				  `receiver_id` int(11) NOT NULL,
				  `message_comment` text NOT NULL,
				  `created_date` datetime NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);	
		
		$table_cmgt_gift_store = $wpdb->prefix . 'cmgt_gift_store';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_gift_store." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `member_id` int(11) NOT NULL,
				  `gift_id` int(11) NOT NULL,
				  `gift_price` varchar(5) NOT NULL,
				  `sell_date` varchar(20) NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);	
		
		$table_cmgt_pastoral = $wpdb->prefix . 'cmgt_pastoral';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_pastoral." (
				   `id` int(11) NOT NULL AUTO_INCREMENT,
				  `pastoral_title` varchar(255) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `pastoral_date` date NOT NULL,
				  `pastoral_time` varchar(255) NOT NULL,
				  `description` text NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);	
		
		$table_cmgt_document = $wpdb->prefix . 'cmgt_document';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_cmgt_document." (
			`document_id` int(11) NOT NULL AUTO_INCREMENT,
			  `created_date` date NOT NULL,
			  `document_name` varchar(100) NOT NULL,
			  `document` varchar(500) NOT NULL,
			  `ducument_create_by` int(11) NOT NULL,
			  `description` text NOT NULL,
			  PRIMARY KEY (`document_id`)
			) DEFAULT CHARSET=utf8";
			
		$wpdb->query($sql);	
		
		$new_field='donetion_type';
		$table_cmgt_transaction = $wpdb->prefix . 'cmgt_transaction';	
		if (!in_array($new_field, $wpdb->get_col( "DESC " . $table_cmgt_transaction, 0 ) ))
		{  
			$result= $wpdb->query("ALTER     TABLE $table_cmgt_transaction  ADD   $new_field   varchar(50)");
		}
		$tbl_cmgt_message_replies = $wpdb->prefix . 'cmgt_message_replies';
		$status_msg='msg_status';
		if (!in_array($status_msg, $wpdb->get_col( "DESC " . $tbl_cmgt_message_replies, 0 ) )){  
			$result= $wpdb->query("ALTER     TABLE $tbl_cmgt_message_replies  ADD   $status_msg   tinyint(4) NOT NULL");
		}
}
// FRONTEND MENU LIST FUNCTION
function MJ_cmgt_frontend_menu_list()
{
	$access_array=array('member' => 
      array (
      'menu_icone' =>plugins_url( 'church-management/assets/images/icon/member.png' ),
      'menu_title' =>__('Members','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
      'family_member' =>'1',
      'page_link' =>'member'),
	  
	   'document' => 
	  array (
      'menu_icone' =>plugins_url( 'church-management/assets/images/icon/document.png' ),
      'menu_title' =>__('Document','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'document'),
	  
	  
	    'group' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/group.png'),
     'menu_title' =>__('Group','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'group'),
	  
	  
	    'services' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/services.png'),
     'menu_title' =>__('Services','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'services'),
	  
	  
	    'ministry' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Ministry.png'),
     'menu_title' =>__('Ministry','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'ministry'),

	   'activity' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Activity.png'),
     'menu_title' =>__('Activity','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'activity'),

	   'attendance' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Attendance.png'),
     'menu_title' =>__('Attendance','church_mgt'),
      'member' =>'0',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'attendance'),

	  'venue' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Venue.png'),
     'menu_title' =>__('Venue','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'venue'),
	  
	  'check-in' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Check-In.png'),
     'menu_title' =>__('Check-In','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'check-in'),
	  
	  'sermon-list' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Sermon-List.png'),
     'menu_title' =>__('Sermon List','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'sermon-list'),
	  
	  'songs' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Songs.png'),
     'menu_title' =>__('Songs','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'songs'),
	  
	  'pledges' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Pledges.png'),
     'menu_title' =>__('Pledges','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'pledges'),
	  
	  'accountant' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Accountant.png'),
     'menu_title' =>__('Accountant','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'accountant'),
	  
	  'spiritual-gift' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Spiritual-Gift.png'),
     'menu_title' =>__('Spiritual Gift','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'spiritual-gift'),
	  
	  'payment' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/Transaction.png'),
     'menu_title' =>__('Payment','church_mgt'),
      'member' =>'0',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'payment'),
	  
	  'notice'=>
	  array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/notice.png'),
     'menu_title' =>__('Notice','church_mgt'),
      'member' =>'0',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'notice'),
	  
	  'donate' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/donate.png'),
     'menu_title' =>__('Donate','church_mgt'),
      'member' =>'1',
      'accountant' =>'0',
	  'family_member' =>'0',
      'page_link' =>'donate'),
	  
	   'message' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/message.png'),
     'menu_title' =>__('Message','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'message'),
	  
	  'pastoral' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/pastoral.png'),
     'menu_title' =>__('Pastoral','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'pastoral'),
	  
	  'newsletter' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/newsletter.png'),
     'menu_title' =>__('News Letter','church_mgt'),
      'member' =>'0',
      'accountant' =>'1',
	  'family_member' =>'0',
      'page_link' =>'news_letter'),
	  'account' => 
    array (
      'menu_icone' =>plugins_url('church-management/assets/images/icon/account.png'),
     'menu_title' =>__('Account','church_mgt'),
      'member' =>'1',
      'accountant' =>'1',
	  'family_member' =>'1',
      'page_link' =>'account')  
	  );
	if ( !get_option('cmgt_access_right') ) 
	{
		update_option( 'cmgt_access_right', $access_array );
	}
}
add_action('init','MJ_cmgt_frontend_menu_list');
/* This function used in MJ_cmgt_header() call this function */
function MJ_cmgt_customcss()
{
	global $current_user, $wp_roles, $current_user_name;
	if (isset($current_user->roles[0])) 
	{
	  $current_user_role=$current_user->roles[0];
	}
	else 
	{
		$current_user_role='';
	}
	if($current_user_role=='administrator' OR $current_user_role=='management'  )
	{
	echo "<style>
	.notice-info{ display:none !important; }
	  .owncls { display:none !important;}
	  .content-wrapper, .right-side, .main-footer{margin-left:0px;}
	  #wpfooter{position: relative !important;}
	  #wpfooter{display:none;}
	  #wpbody-content{display: contents;}
	  #adminmenumain{display:none !important;}
	  #wpadminbar{display:none !important;}
	  </style>";
	}
	else 
	{
	  echo "<style>
	  .update-nag {display:none !important;}
	  #wpadminbar{display:none !important;}
	  #adminmenumain{display:none !important;}
	  #wpcontent, #wpfooter{margin-left: 0;}
	  #wpcontent{padding-left:0px;}
	  #wpfooter{position: relative !important;}
	  </style>";
	}
  }

/* This function used Header print custom css. */
function MJ_cmgt_header(){
	MJ_cmgt_customcss();
}

function my_custom_title() 
{ 
	$page_name = isset($_REQUEST ['page']);	
	if($page_name == "member")
	{
		$title['title'] =__("Member","church_mgt");
	}
	elseif($page_name == "familymember")
	{
		$title['title'] =__("FamilyMember","church_mgt");
	}
	elseif($page_name == "accountant")
	{
		$title['title'] =__("Accountant","church_mgt");
	}
	elseif($page_name == "group")
	{
		$title['title'] =__("Group","church_mgt");
	}
	elseif($page_name == "ministry")
	{
		$title['title'] =__("Ministry","church_mgt");
	}
	elseif($page_name == "services")
	{
		$title['title'] =__("Services","church_mgt");
	}
	elseif($page_name == "pastoral")
	{
		$title['title'] =__("Pastoral","church_mgt");
	}
	elseif($page_name == "activity")
	{
		$title['title'] =__("Activity","church_mgt");
	}
	elseif($page_name == "venue")
	{
		$title['title'] =__("Venue","church_mgt");
	}
	elseif($page_name == "reservation")
	{
		$title['title'] =__("Reservation","church_mgt");
	}
	elseif($page_name == "check-in")
	{
		$title['title'] =__("Check-in","church_mgt");
	}
	elseif($page_name == "document")
	{
		$title['title'] =__("Document","church_mgt");
	}
	elseif($page_name == "sermon-list")
	{
		$title['title'] =__("Sermon List","church_mgt");
	}
	elseif($page_name == "spiritual-gift")
	{
		$title['title'] =__("Spiritual Gift","church_mgt");
	}
	elseif($page_name == "pledges")
	{
		$title['title'] =__("Pledges","church_mgt");
	}
	elseif($page_name == "songs")
	{
		$title['title'] =__("Songs","church_mgt");
	}
	elseif($page_name == "notice")
	{
		$title['title'] =__("Notice","church_mgt");
	}
	elseif($page_name == "message")
	{
		$title['title'] =__("Message","church_mgt");
	}
	elseif($page_name == "donate")
	{
		$title['title'] =__("Donate","church_mgt");
	}
	elseif($page_name == "payment")
	{
		$title['title'] =__("Payment","church_mgt");
	}
	elseif($page_name == "account")
	{
		$title['title'] =__("Account","church_mgt");
	}
	elseif($page_name == "report")
	{
		$title['title'] =__("Report","church_mgt");
	}
	else
	{
		$title['title'] =__("WP_Church","church_mgt");
	}
	if (is_singular('post')) 
	{ 
		$title['title'] = get_option('cmgt_system_name').' '. $title['title']; 
	}
	return $title; 
}

?>