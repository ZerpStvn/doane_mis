<?php 
add_action( 'admin_menu', 'church_system_menu' );
function church_system_menu()
{
	
	$user_roles=MJ_cmgt_user_roles(get_current_user_id());
	if ( in_array( 'management', $user_roles, true ) )
	{
		add_menu_page('Church Management', __('Church Management', 'church_mgt'),'management','cmgt-church_system','church_system_dashboard',plugins_url('church-management/assets/images/church-management-3.png' )); 
		


		if(isset($_SESSION['cmgt_verify']) && $_SESSION['cmgt_verify'] == '')
		{
			add_submenu_page('cmgt-church_system','Licence Settings',__( 'Licence Settings', 'church_mgt' ),'management','cmgt-setup','church_system_dashboard');
		} 
		add_submenu_page('cmgt-church_system', 'Dashboard', __( 'Dashboard', 'church_mgt' ), 'management', 'cmgt-church_system', 'church_system_dashboard');
		if(MJ_cmgt_add_check_access_for_view_add('document','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Document', __( 'Document', 'church_mgt' ), 'management', 'cmgt-document', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('group','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Group', __( 'Group', 'church_mgt' ), 'management', 'cmgt-group', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('ministry','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Ministry', __( 'Ministry', 'church_mgt' ), 'management', 'cmgt-ministry', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('member','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Member', __( 'Member', 'church_mgt' ), 'management', 'cmgt-member', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('family','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Family', __( 'Family Member', 'church_mgt' ), 'management', 'cmgt-family', 'church_system_dashboard');	
		}
		if(MJ_cmgt_add_check_access_for_view_add('services','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Services', __( 'Services', 'church_mgt' ), 'management', 'cmgt-service', 'church_system_dashboard');	
		}
		if(MJ_cmgt_add_check_access_for_view_add('activity','view') == 1)
		{
		  add_submenu_page('cmgt-church_system', 'Activity', __( 'Activity', 'church_mgt' ), 'management', 'cmgt-activity', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('attendance','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Attendance', __( 'Attendance', 'church_mgt' ), 'management', 'cmgt-attendance', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('venue','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Venue', __( 'Venue', 'church_mgt' ), 'management', 'cmgt-venue', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('check-in','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Check-In', __( 'Check-In', 'church_mgt' ), 'management', 'cmgt-checkin', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('sermon-list','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Sermon List', __( 'Sermon List', 'church_mgt' ), 'management', 'cmgt-sermon', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('songs','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Songs', __( 'Songs', 'church_mgt' ), 'management', 'cmgt-song', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('pledges','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Pledges', __( 'Pledges', 'church_mgt' ), 'management', 'cmgt-pledges', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('accountant','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Accountant', __( 'Accountant', 'church_mgt' ), 'management', 'cmgt-accountant', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('spiritual-gift','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Spiritual Gift', __( 'Spiritual Gift', 'church_mgt' ), 'management', 'cmgt-gifts', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('payment','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Payment', __( 'Payment', 'church_mgt' ), 'management', 'cmgt-payment', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('notice','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'notice', __( 'Notice', 'church_mgt' ), 'management', 'cmgt-notice', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('message','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Message', __( 'Message', 'church_mgt' ), 'management', 'cmgt-message', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('pastoral','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Pastoral', __( 'Pastoral', 'church_mgt' ), 'management', 'cmgt-pastoral', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('news_letter','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'News Letter', __( 'News Letter', 'church_mgt' ), 'management', 'cmgt-newsletter', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('report','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Report', __( 'Report', 'church_mgt' ), 'management', 'cmgt-report', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('accessright','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Access Rights', __( 'Access Rights', 'church_mgt' ), 'management', 'cmgt-access_right', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('emailtemplate','view') == 1)
		{
		add_submenu_page('cmgt-church_system', 'Mail Template', __( 'Mail Template', 'church_mgt' ), 'management', 'cmgt-mail_template', 'church_system_dashboard');
		}
		if(MJ_cmgt_add_check_access_for_view_add('generalsetting','view') == 1)
		{	
		add_submenu_page('cmgt-church_system', 'General Setting', __('General Setting', 'church_mgt' ), 'management', 'cmgt-general-setting', 'church_system_dashboard');
		}
	}
	else
	{
		add_menu_page('Church Management', __('Church Management', 'church_mgt'),'manage_options','cmgt-church_system','church_system_dashboard',plugins_url('church-management/assets/images/church-management-3.png' )); 
		
		if(isset($_SESSION['cmgt_verify']) && $_SESSION['cmgt_verify'] == '')
		{
			add_submenu_page('cmgt-church_system','Licence Settings',__( 'Licence Settings', 'church_mgt' ),'administrator','cmgt-setup','church_system_dashboard');
		} 
		add_submenu_page('cmgt-church_system', 'Dashboard', __( 'Dashboard', 'church_mgt' ), 'administrator', 'cmgt-church_system', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Document', __( 'Document', 'church_mgt' ), 'administrator', 'cmgt-document', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Group', __( 'Group', 'church_mgt' ), 'administrator', 'cmgt-group', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Ministry', __( 'Ministry', 'church_mgt' ), 'administrator', 'cmgt-ministry', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Member', __( 'Member', 'church_mgt' ), 'administrator', 'cmgt-member', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Family', __( 'Family Member', 'church_mgt' ), 'administrator', 'cmgt-family', 'church_system_dashboard');	
		
		add_submenu_page('cmgt-church_system', 'Services', __( 'Services', 'church_mgt' ), 'administrator', 'cmgt-service', 'church_system_dashboard');	
		
		add_submenu_page('cmgt-church_system', 'Activity', __( 'Activity', 'church_mgt' ), 'administrator', 'cmgt-activity', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Attendance', __( 'Attendance', 'church_mgt' ), 'administrator', 'cmgt-attendance', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Venue', __( 'Venue', 'church_mgt' ), 'administrator', 'cmgt-venue', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Check-In', __( 'Check-In', 'church_mgt' ), 'administrator', 'cmgt-checkin', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Sermon List', __( 'Sermon List', 'church_mgt' ), 'administrator', 'cmgt-sermon', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Songs', __( 'Songs', 'church_mgt' ), 'administrator', 'cmgt-song', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Pledges', __( 'Pledges', 'church_mgt' ), 'administrator', 'cmgt-pledges', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Accountant', __( 'Accountant', 'church_mgt' ), 'administrator', 'cmgt-accountant', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Spiritual Gift', __( 'Spiritual Gift', 'church_mgt' ), 'administrator', 'cmgt-gifts', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Payment', __( 'Payment', 'church_mgt' ), 'administrator', 'cmgt-payment', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'notice', __( 'Notice', 'church_mgt' ), 'administrator', 'cmgt-notice', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Message', __( 'Message', 'church_mgt' ), 'administrator', 'cmgt-message', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Pastoral', __( 'Pastoral', 'church_mgt' ), 'administrator', 'cmgt-pastoral', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'News Letter', __( 'News Letter', 'church_mgt' ), 'administrator', 'cmgt-newsletter', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Report', __( 'Report', 'church_mgt' ), 'administrator', 'cmgt-report', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Access Rights', __( 'Access Rights', 'church_mgt' ), 'administrator', 'cmgt-access_right', 'church_system_dashboard');
		
		add_submenu_page('cmgt-church_system', 'Mail Template', __( 'Mail Template', 'church_mgt' ), 'administrator', 'cmgt-mail_template', 'church_system_dashboard');
			
		add_submenu_page('cmgt-church_system', 'General Setting', __('General Setting', 'church_mgt' ), 'administrator', 'cmgt-general-setting', 'church_system_dashboard');
	}
}
function church_system_dashboard()
{
	require_once CMS_PLUGIN_DIR. '/admin/dasboard.php';
}
function cmgt_options_page()
{
	require_once CMS_PLUGIN_DIR. '/admin/setupform/index.php';
}

function cmgt_document_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/document/index.php';
}


function cmgt_member_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/member/index.php';
}

function cmgt_family_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/family/index.php';
}
function cmgt_accountant_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/accountant/index.php';
}
function cmgt_activity_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/activity/index.php';
}
function cmgt_attendance_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/attendance/index.php';
}
function venue_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/venue/index.php';
}
function venue_reservation_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/venue-reservation/index.php';
}
function cmgt_group_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/group/index.php';
}
function ministry_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/ministry/index.php';
}
function gifts_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/spiritual-gift/index.php';
}
function checkin_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/check-in/index.php';
}
function pledges_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/pledges/index.php';
}

function service_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/service/index.php';
}
function cmgt_mail_template()
{
	require_once CMS_PLUGIN_DIR. '/admin/mail-tempate/index.php';
}
function song_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/songs/index.php';
}
function sermon_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/sermon/index.php';
}
function cmgt_message_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/message/index.php';
}
function cmgt_payment_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/payment/index.php';
}


function cmgt_notice_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/notice/index.php';
}

function cmgt_pastoral_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/pastoral/index.php';
}
function cmgt_news_letter_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/news-letter/index.php';
}
function cmgt_report_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/report/index.php';
}
function cmgt_access_right_manage()
{
	require_once CMS_PLUGIN_DIR. '/admin/access_right/index.php';
}
function church_general_setting()
{
	require_once CMS_PLUGIN_DIR. '/admin/general-settings.php';
}
?>