<?php
//-- Login Redirect Function --//
add_filter( 'login_redirect', 'MJ_cmgt_login_redirect',10, 3 ); 
  
function MJ_cmgt_login_redirect($redirect_to, $request, $user )
{
	if (isset($user->roles) && is_array($user->roles)) 
	{
		//if($user->roles == 'administrator' OR $user->roles == 'management' )
		if(in_array("administrator", $user->roles) OR (in_array("management", $user->roles)))
		{
			$redirect_to =  home_url('wp-admin/admin.php?page=cmgt-church_system');
		}
			else
		{
			$roles = ['family_member','member','accountant'];
			foreach($roles as $role)
			{
				if (in_array($role, $user->roles))
				{
					$redirect_to =  home_url('?church-dashboard=user');
					break;
				}		
			}
		}
	}
	return $redirect_to;
}  
//--- CHANGE MENUTITLE FUNCTION --- //
function MJ_cmgt_change_menutitle($key)
{
	$menu_titlearray=array(
	'member'=>_e('Member','church_mgt'),
	'document'=>_e('Document','church_mgt'),
	'group'=>_e('Group','church_mgt'),
	'services'=>_e('Services','church_mgt'),
	'ministry'=>_e('Ministry','church_mgt'),
	'activity'=>_e('Activity','church_mgt'),
	'attendance'=>_e('Attendance','church_mgt'),
	'venue'=>_e('Venue','church_mgt'),
	'check-in'=>_e('Check-In','church_mgt'),
	'sermon-list'=>_e('Sermon List','church_mgt'),
	'songs'=>_e('Songs','church_mgt'),
	'pledges'=>_e('Pledges','church_mgt'),
	'accountant'=>_e('Accountant','church_mgt'),
	'spiritual-gift'=>_e('Spiritual Gift','church_mgt'),
	'payment'=>_e('Payment','church_mgt'),
	'notice'=>_e('Notice','church_mgt'),
	'donate'=>_e('Donate','church_mgt'),
	'message'=>_e('Message','church_mgt'),
	'pastoral'=>_e('Pastoral','church_mgt'),
	'newsletter'=>_e('News Letter','church_mgt'),
	'account'=>_e('Account','church_mgt'));
	return $menu_titlearray[$key];
}
//--- CHECk SERVER FUNCTION ---//
function MJ_cmgt_check_ourserver()
{
	//$api_server = 'http://license.dasinfomedia.com';
	$api_server = 'license.dasinfomedia.com';
	//$api_server = '192.168.1.22';
	$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=cmgt-church_system';
	if (!$fp)
              return false; /*server down*/
        else
              return true; /*Server up*/
}
//-------- CHECK PRODUCTKEY -----//
function MJ_cmgt_check_productkey($domain_name,$licence_key,$email)
{
	//$api_server = 'http://license.dasinfomedia.com';
	$api_server = 'license.dasinfomedia.com';
	//$api_server = '192.168.1.22';
	$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=cmgt-church_system';
	if (!$fp)
              $server_rerror = 'Down';
        else
              $server_rerror = "up";
	if($server_rerror == "up")
	{
		//$url = 'http://192.168.1.22/php/test/index.php';
		$url = 'http://license.dasinfomedia.com/index.php';
		$fields = 'result=2&domain='.$domain_name.'&licence_key='.$licence_key.'&email='.$email.'&item_name=church';
		//open connection
		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
		//execute post
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	else
	{
		return '3';
	}
}
/* Setup form submit*/
function MJ_cmgt_submit_setupform($data)
{
	$domain_name= sanitize_text_field($data['domain_name']);
	$licence_key = sanitize_text_field($data['licence_key']);
	$email = sanitize_text_field($data['enter_email']);
	$result = MJ_cmgt_check_productkey($domain_name,$licence_key,$email);
	if($result == '1')
	{
		$message = 'Please provide correct Envato purchase key.';
			$_SESSION['cmgt_verify'] = '1';
	}
	elseif($result == '2')
	{
		$message = 'This purchase key is already registered with the different domain. If have any issue please contact us at sales@mojoomla.com';
			$_SESSION['cmgt_verify'] = '2';
	}
	elseif($result == '3')
	{
		$message = 'There seems to be some problem please try after sometime or contact us on sales@mojoomla.com';
			$_SESSION['cmgt_verify'] = '3';
	}
	if($result == '4')
	{
		$message = 'Please provide correct Envato purchase key for this plugin.';
			$_SESSION['cmgt_verify'] = '1';
	}
	else
	{
		update_option('domain_name',$domain_name,true);
		update_option('licence_key',$licence_key,true);
		update_option('cmgt_setup_email',$email,true);
		$message = 'Success fully register';
			$_SESSION['cmgt_verify'] = '0';
	}
		
	$result_array = array('message'=>$message,'cmgt_verify'=>$_SESSION['cmgt_verify']);
	return $result_array;
}
/* check server live */
function MJ_cmgt_chekserver($server_name)
{
	if($server_name == 'localhost')
	{
		return true;
	}
}
/*Check is_verify*/
function MJ_cmgt_check_verify_or_not($result)
{	
	$server_name = sanitize_text_field($_SERVER['SERVER_NAME']);
	$current_page = sanitize_text_field(isset($_REQUEST['page'])?$_REQUEST['page']:'');
	$pos = strrpos($current_page, "cmgt-");	
	if($pos !== false)			
	{
		if($server_name == 'localhost')
		{
			return true;
		}
		else
		{
			if($result == '0')
			{
				return true;
			}
		}
		return false;
	}
}
function MJ_cmgt_is_cmgtpage()
{
	$current_page = sanitize_text_field(isset($_REQUEST['page'])?$_REQUEST['page']:'');
	$pos = strrpos($current_page, "cmgt-");	
	
	if($pos !== false)			
	{
		return true;
	}
	return false;
}

//------ GET ROLES FUNCTION --------//
function MJ_church_get_roles($user_id){
	$roles = array();
	$user = new WP_User( $user_id );

	if ( !empty( $user->roles ) && is_array( $user->roles ) ) 
	{
		foreach ( $user->roles as $role )
			$roles[] = $role;
	}
	return $roles;
}
//-------- GET LAST MEMBER ID FUNCTION -----//
function MJ_cmgt_get_lastmember_id($role)
{
	global $wpdb;	
	$this_role = "'[[:<:]]".$role."[[:>:]]'";
	$table_name = $wpdb->prefix .'usermeta';
	$metakey=$wpdb->prefix .'capabilities';
	$userid=$wpdb->get_row("SELECT MAX(user_id)as uid FROM $table_name where meta_key = '$metakey' AND meta_value RLIKE $this_role");
	if(!empty($userid))
	{
		return get_user_meta($userid->uid,'member_id',true);
	}
	else
	{
		return '';
	}
}
//--------- GET COUNTRY PHONECODE FUNCTION -------//
function MJ_cmgt_get_countery_phonecode($country_name)
{
	$url = plugins_url( 'countrylist.xml', __FILE__ );
	$xml=simplexml_load_file(plugins_url( 'countrylist.xml', __FILE__ )) or die("Error: Cannot create object");
	foreach($xml as $country)
	{
		if($country_name == $country->name)
			return $country->phoneCode;
	}
}
//-------- COUNT GROUP FUNCTION -----//
function MJ_cmgt_count_group()
{
	global $wpdb;
	$table_cmgt_groups = $wpdb->prefix . "cmgt_group";
	$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_groups");
	return $results;
}
//-------- COUNT Ministry FUNCTION -----//
function MJ_cmgt_count_ministry()
{
	global $wpdb;
	$table_cmgt_ministrys = $wpdb->prefix . "cmgt_ministry";
	$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_ministrys");
	return $results;
}
//-------- COUNT Services FUNCTION -----//
function MJ_cmgt_count_services()
{
	global $wpdb;
	$table_cmgt_services = $wpdb->prefix . "cmgt_service";
	$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_services");
	return $results;
}
//-------- COUNT Reservation FUNCTION -----//
function MJ_cmgt_count_reservation()
{
	global $wpdb;
	$table_cmgt_reservation = $wpdb->prefix . "cmgt_venue_reservation";
	$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_reservation");
	return $results;
}
//-------- COUNT Pledges FUNCTION -----//
function MJ_cmgt_count_pledges()
{
	global $wpdb;
	$table_cmgt_pledges = $wpdb->prefix . "cmgt_pledges";
	$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_pledges");
	return $results;
}
//-------- COUNT Songs FUNCTION -----//
function MJ_cmgt_count_song()
{
	global $wpdb;
	$table_cmgt_song = $wpdb->prefix . "cmgt_songs";
	$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_song");
	return $results;
}
//-------- COUNT Notice FUNCTION -----//
function MJ_cmgt_count_notice()
{
	global $wpdb;
	$table_cmgt_notice = $wpdb->prefix . "cmgt_notice";
	$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_notice");
	return $results;
}
//-------- COUNT Today Attendance FUNCTION -----//
function MJ_cmgt_today_presents()
{
	global $wpdb;
	$table_cmgt_attendence = $wpdb->prefix . "cmgt_attendence";
	$curr_date=date("Y-m-d");
	return $result=$wpdb->get_var("SELECT COUNT(*) FROM $table_cmgt_attendence WHERE attendence_date='$curr_date' and status='Present'");
}
//-------- COUNT ACTIVITY FUNCTION -----//
function MJ_cmgt_count_activities()
{
	global $wpdb;
	$table_cmgt_activity = $wpdb->prefix . "cmgt_activity";
	$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_activity");
	return $results;
}
//-------- ROOM CHECK IN HISTORY  FUNCTION -----//
function MJ_cmgt_room_checkin_history($room_id)
{
	global $wpdb;
	$table_checkin = $wpdb->prefix. 'cmgt_checkin';
	$result=$wpdb->get_results("select * from $table_checkin where room_id=".$room_id);
	return $result;
}
//-------- COUNT INBOX ITEM FUNCTION -----//
function MJ_cmgt_count_inbox_item($id)
{
	global $wpdb;
	$tbl_name = $wpdb->prefix .'cmgt_message';
	$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name where receiver = $id");
	return $inbox;
}
function MJ_cmgt_get_remote_file($url, $timeout = 30){
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return ($file_contents) ? $file_contents : FALSE;
}
//-------- GET ROOM NAME  FUNCTION -----//
function MJ_get_room_name($id)
{
	global $wpdb;
	$table_room = $wpdb->prefix. 'cmgt_room';
	$result = $wpdb->get_row("SELECT * FROM $table_room where id=".$id);
	return $result->room_title;
}
//-------- GET ACTIVITY NAME  FUNCTION -----//
function MJ_cmgt_get_activity_name($id)
{
	global $wpdb;
	$table_activity = $wpdb->prefix. 'cmgt_activity';
	$result = $wpdb->get_row("SELECT * FROM $table_activity where activity_id=".$id);
	if(isset($result->activity_title))
	{
		$activity=$result->activity_title;
	}else{
		$activity = "N/A";
	}
	return $activity;
}
//-------- GET MINISTRY NAME  FUNCTION -----//
function MJ_cmgt_get_ministry_name($id)
{
	global $wpdb;
	$table_ministry = $wpdb->prefix. 'cmgt_ministry';
	$result = $wpdb->get_row("SELECT * FROM $table_ministry where id=".$id);
	if($result)
	{
		return $result->ministry_name;
	}
	
}
//-------- CHECK VOLUNTEER FUNCTION -----//
function MJ_cmgt_check_volunteer($id)
{
	return $volunteer=get_user_meta($id,'volunteer',true);
}
//--------- GET MEMBER OF ACTIVITY FUNCTION ------//
function MJ_cmgt_get_members_ofactivity($id)
{
	$obj_group=new Cmgtgroup;
	global $wpdb;
	$table_activity = $wpdb->prefix. 'cmgt_activity';
	$result = $wpdb->get_row("SELECT * FROM $table_activity where activity_id=".$id);
	$groups_array =(explode(",",$result->groups));
	
	$members='';
	$all_members=array();
	foreach($groups_array as $group)
	{
		$grp_membs=$obj_group->MJ_cmgt_get_group_members($group);
		foreach($grp_membs as $member)
		{
			array_push($all_members,$member->member_id);
		}
		
	} 
	$members=array_unique($all_members);
	if(!empty($members))
		return $members;
}
//-------- LOAD DOCUMENTS FUNCTION -----//
/* function MJ_cmgt_load_documets($file,$type,$nm)
{
	// var_dump($nm);
	// var_dump($file);
	// var_dump($type);
	$parts = pathinfo($_FILES[$type]['name']);
	
	$inventoryimagename = time()."-".$nm."-"."in".".".$parts['extension'];
	$upload_dir = wp_upload_dir(); 
	$document_dir = ''.$upload_dir['path'].'/church_assets/';
	$document_path = $document_dir;
	
	if($document_path != "")
	{	
		if(file_exists(WP_CONTENT_DIR.$document_path))
		unlink(WP_CONTENT_DIR.$document_path);
	}
	if (!file_exists($document_path))
	{
		mkdir($document_path, 0777, true);
	}	
	if (move_uploaded_file($_FILES[$type]['tmp_name'], $document_path.$inventoryimagename)) 
	{
		$document_path= $inventoryimagename;	
	}
	return $document_path;
} */
//-------- GET MEMBERS OF MINISTRY  FUNCTION -----//
function MJ_cmgt_get_members_ofministry($id)
{
	$obj_group=new Cmgtgroup;
	global $wpdb;
	$table_ministry = $wpdb->prefix. 'cmgt_ministry';
	$result = $wpdb->get_row("SELECT * FROM $table_ministry where id=".$id);
	$groups_array =(explode(",",$result->groups));
	$members='';
	$all_members=array();
	foreach($groups_array as $group)
	{
		$grp_membs=$obj_group->MJ_cmgt_get_group_members($group);
		foreach($grp_membs as $member)
		{
			array_push($all_members,$member->member_id);
		}
	} 
	$members=array_unique($all_members);
	if(!empty($members))
		return $members;
}
//-------- GET GIFT NAME  FUNCTION -----//
function MJ_cmgt_get_gift_name($id)
{
	global $wpdb;
	$table_gift = $wpdb->prefix. 'cmgt_gifts';
	$result = $wpdb->get_row("SELECT * FROM $table_gift where id=".$id);
	return sanitize_text_field($result->gift_name);
}
//-------- GET TRANSACTION DATA FUNCTION -----//
function MJ_cmgt_get_transaction_data($id)
{
	global $wpdb;
	$table_transaction = $wpdb->prefix. 'cmgt_transaction';
	return $result = $wpdb->get_row("SELECT * FROM $table_transaction where id=".$id);
}
//-------- GET GROUP USERS FUNCTION -----//
function MJ_cmgt_get_group_users($id)
{
	global $wpdb;
	$table_groupmember = $wpdb->prefix. 'cmgt_groupmember';
	return $result = $wpdb->get_results("SELECT member_id FROM $table_groupmember where group_id=".$id);
}
//-------- GET PLEDGE DATA FUNCTION -----//
function MJ_cmgt_get_pledges_data($id)
{
	global $wpdb;
	$table_pledges = $wpdb->prefix. 'cmgt_pledges';
	return $result = $wpdb->get_row("SELECT * FROM $table_pledges where id=".$id);
}
//-------- GET DISPLAY NAME  FUNCTION -----//
function MJ_cmgt_church_get_display_name($id)
{
	$result=get_userdata($id);
	if($result)
	{
		return sanitize_text_field($result->display_name);
	}
	
}
//-------- GET GIFT NAME  FUNCTION -----//
function MJ_cmgt_church_get_gift_name($id)
{
	global $wpdb;
	$cmgt_gifts = $wpdb->prefix. 'cmgt_gifts';
	$result = $wpdb->get_row("SELECT * FROM $cmgt_gifts where id=".$id);
	return sanitize_text_field($result->gift_name);
}
//-------- GET EMIAL_ID  FUNCTION -----//
function MJ_cmgt_get_emailid_byuser_id($id)
{
	if (!$user = get_userdata($id))
		return false;
	return sanitize_text_field($user->data->user_email);
}
//-------- GET ALL USER IN MASSAGE FUNCTION -----//
function MJ_cmgt_get_all_user_in_message()
{
	$member=get_users(array('role'=>'member'));
	$accountant = get_users(array('role'=>'accountant'));
//	$admin = get_users(array('role'=>'administrator'));
	$all_user = array('member'=>$member,
					'accountant'=>$accountant,
					//'administrator'=>$admin,
					);	
	$return_array = array();
	foreach($all_user as $key => $value)
	{ 
		if(!empty($value))
		{
			 echo '<optgroup label="'.esc_html__($key,"church_mgt").'" style = "text-transform: capitalize;">';
			foreach($value as $user)
			{
			//	if(!is_super_admin($user->ID))
			//	{	
				if(empty($user->cmgt_hash)){
					echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
			//	}
				}
			}
		}
	}	
}
//-------- GET MEDIA TYPE FUNCTION -----//
function MJ_cmgt_get_media_type($type)
{
	$type_label='';
	if($type=='video')
	{
		$type_label=esc_html_e('Video','church_mgt');
	}
    if($type=='image')
	{
		$type_label=esc_html_e('Image','church_mgt');
	}
    if($type=='audio')
	{
		$type_label=esc_html_e('Audio','church_mgt');
	}
    if($type=='pdf')
	{
		$type_label=esc_html_e('PDF','church_mgt');
	}
  return $type_label;
}
//-------- GET ROLE NAME  FUNCTION -----//
function MJ_cmgt_get_role_name_in_message($role)
{
	if($role == "member")
	{
		return esc_html_e( 'Members' ,'church_mgt');
	}
	elseif($role == "accountant")
	{
		return esc_html_e( 'Accountant' ,'church_mgt');
	}
	/* $profile_pict=array(
			'member'=> ,
			'accountant'=> esc_html_e( 'Accountant' ,'church_mgt')
	);
	return $profile_pict[$role]; */
}
//-------- CMGT MENU FUNCTION -----//
function MJ_cmgt_menu()
{
	$user_menu = array();
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/member.png' ),'menu_title'=>esc_html_e( 'Members', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'member');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/group.png' ),'menu_title'=>esc_html_e( 'Group', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'group');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/services.png' ),'menu_title'=>esc_html_e( 'Services', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'services');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Ministry.png' ),'menu_title'=>esc_html_e( 'Ministry', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'ministry');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Activity.png' ),'menu_title'=>esc_html_e( 'Activity', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'activity');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Attendance.png' ),'menu_title'=>esc_html_e( 'Attendance', 'church_mgt' ),'member'=>0,'accountant'=>1,'page_link'=>'attendance');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venue.png' ),'menu_title'=>esc_html_e( 'Venue', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'venue');
	 
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Check-In.png' ),'menu_title'=>esc_html_e( 'Check-In', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'check-in');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Sermon-List.png' ),'menu_title'=>esc_html_e( 'Sermon List', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'sermon-list');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Songs.png' ),'menu_title'=>esc_html_e( 'Songs', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'songs');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Pledges.png' ),'menu_title'=>esc_html_e( 'Pledges', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'pledges');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Accountant.png' ),'menu_title'=>esc_html_e( 'Accountant', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'accountant');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Spiritual-Gifts.png' ),'menu_title'=>esc_html_e( 'Spiritual Gift', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'spiritual-gift');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/Transaction.png' ),'menu_title'=>esc_html_e( 'Payment', 'church_mgt' ),'member'=>0,'accountant'=>1,'page_link'=>'payment');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/donate.png' ),'menu_title'=>esc_html_e( 'Donate', 'church_mgt' ),'member'=>1,'accountant'=>0,'page_link'=>'donate');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/notice.png' ),'menu_title'=>esc_html_e( 'Notice', 'church_mgt' ),'member'=>1,'accountant'=>0,'page_link'=>'notice');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/message.png' ),'menu_title'=>esc_html_e( 'Message', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'message');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/pastoral.png' ),'menu_title'=>esc_html_e( 'Pastoral', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'pastoral');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),'menu_title'=>esc_html_e( 'News Letter', 'church_mgt' ),'member'=>0,'accountant'=>1,'page_link'=>'news_letter');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'church-management/assets/images/icon/account.png' ),'menu_title'=>esc_html_e( 'Account', 'church_mgt' ),'member'=>1,'accountant'=>1,'page_link'=>'account');
	
	return $user_menu;
}
//-------- GET PAYMENT METHOD FUNCTION -----//
function  MJ_cmgt_get_payment_method($method)
{
	$method_label='';
	if($method=='cash')
	  $method_label=esc_html_e('Cash','church_mgt');
    if($method=='check')
	  $method_label=esc_html_e('Check','church_mgt');
    if($method=='credit_card')
	  $method_label=esc_html_e('Credit Card','church_mgt');
    if($method=='bank_transfer')
	  $method_label=esc_html_e('Bank Transfer','church_mgt');
    if($method=='autometic_direct_debit')
	  $method_label=esc_html_e('Autometic Direct Debit','church_mgt');
    if($method=='online')
	  $method_label=esc_html_e('Online','church_mgt');
   if($method=='paypal')
	  $method_label=esc_html_e('Paypal','church_mgt');
	if($method=='other')
	  $method_label=esc_html_e('Other','church_mgt');
  
  return $method_label;
}

//-------- LOGIN LINK  FUNCTION -----//

function MJ_cmgt_login_link()
{
$theme_name=get_current_theme();
if($theme_name == 'Twenty Twenty-Two' || $theme_name == 'Twenty Twenty-Three' || $theme_name == 'Twenty Twenty-Four' )
{
	?>
	<style>
	.custom_login_form
	{
		position: absolute;
		top: 500px;
		margin: 0 40%;
		/* left: 470px; */
	}
	footer .wp-container-10.wp-block-group.alignwide{
		padding-top: 20rem!important;
	}
	#loginform .login-username input, .login-password input 
	{
		width: 100%;
		height: 48px;
	}
	#loginform input[type="checkbox"]{
		width: 20px;
		height: 20px;
	}
	#loginform .login-submit .button {
		border-radius: 28px;
		padding: 10px 110px;
		background-color: <?php echo get_option('cmgt_system_color_code');?>;
		border: 0px;
		color: #ffffff;
		font-size: 20px;
		text-transform: uppercase;
	}
	#loginform label{
		/* font-family: 'Poppins'!important; */
		font-family: 'Poppins'!important;
	}
	#loginform p label{
		margin: 5px 0;
	}
	#loginform input[type="submit"] {
		border-radius: 28px!important;
		width: 60%;
		height: 48px!important;
		padding: 0!important;
		background-color: <?php echo get_option('cmgt_system_color_code');?>!important;
	}
	#registration_form .col-sm-3.control-label {
		margin-top: 4px !important;
	}
	@media screen and (max-width: 912px) {
		footer .wp-container-10.wp-block-group.alignwide {
			padding-top: 30rem!important;
		}
		.custom_login_form {
			margin: 0 30%;
		}
	}
	@media screen and (max-width: 820px) {
		.custom_login_form {
			margin: 0 36%;
		}
		.custom_login_form #loginform{
			padding: 0 5px;
		}
	}
	
	@media screen and (max-width: 414px)
	{
		.custom_login_form {
			margin: 0% 20%;
		}
		#loginform input[type="submit"] {
			border-radius: 28px!important;
		}
	}
	@media screen and (max-width: 375px)
	{
		#loginform input[type="submit"] {
			border-radius: 28px!important;
		}
	}
	
	/* #loginform .login-username label, .login-password label {
    	display: unset!important;
	} */
	</style>
<?php 
} 
if($theme_name == 'Twenty Twenty-Three'  || $theme_name == 'Twenty Twenty-Four' || $theme_name == 'Twenty Twenty-Two' )
{
	?>
	<style>
		.custom_login_form {
			top: 369px!important;
		}
		footer {
			margin-top: 29rem!important;
		}
		#registration_form .col-sm-3.control-label {
			margin-top: 4px !important;
		}
	</style>
	<?php
}
if($theme_name == 'Twenty Twenty-Four')
{
	?>
	<style>
		@media screen and (max-width: 414px){
			.custom_login_form {
    			margin: 0% 20%;
			}	
		}
	</style>
	<?php
}
if($theme_name == 'Twenty Twenty-Two')
{
	?>
	<style>
		.custom_login_form {
   			top: 450px !important;
		}
	</style>
	<?php
}
$theme_name=get_current_theme();
if($theme_name == 'Divi')
{	?>
	<style>
		#loginform input[type="button"], input[type="reset"], input[type="submit"] {
			border-radius: 28px!important;
			width: 40%!important;
			background-color:  <?php echo get_option('cmgt_system_color_code');?>!important;
			height: 48px!important;
			color: #ffff!important;
		}
		#loginform .login-username input, .login-password input {
			width: 60%!important;
			height: 48px!important;
		}


	
	</style>
<?php 
} 
	echo '
    <style>
	#loginform p{
		margin-bottom: 10px;
	}
	#loginform .login-username label, .login-password label{
		display: block;
	}
	#loginform .login-username input, .login-password input{
		width: 100%;
	}
	#loginform input[type="button"], input[type="reset"], input[type="submit"] {
		border-radius: 28px!important;
		width: 60%;
		background-color:  '.get_option('cmgt_system_color_code').' !important;
	}
	#loginform label{
		font-family: Poppins!important;
	}
	</style>'; 
	
	$args = array( 'redirect' => site_url() );
	
	if(isset($_GET['login']) && $_GET['login'] == 'failed')
	{?>
		<div id="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;"><p>
		<?php
		  esc_html_e('Login failed: You have entered an incorrect Username or password, please try again.','church_mgt'); 
		  ?></p>
		</div>
<?php
	}
	 $args = array(
			'echo' => true,
			'redirect' => site_url( $_SERVER['REQUEST_URI'] ),
			'form_id' => 'loginform',
			'label_username' => __( 'Username' , 'church_mgt'),
			'label_password' => __( 'Password', 'church_mgt' ),
			'label_remember' => __( 'Remember Me' , 'church_mgt'),
			'label_log_in' => __( 'Log In' , 'church_mgt'),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
			'value_remember' => false ); 
			
	 $args = array('redirect' => site_url('/?church-dashboard=user') );
	 
	if ( is_user_logged_in() )
	{
	 ?>
		<a href="<?php echo home_url('/')."?church-dashboard=user"; ?>" style="margin-left: 7%;"><i
		class="fa fa-sign-out m-r-xs"></i>
			<?php esc_html_e('Dashboard','church_mgt');?>
		</a>
		<br /><a href="<?php echo wp_logout_url(); ?>" style="margin-left: 7%;"><i class="fa fa-sign-out m-r-xs" /><?php esc_html_e('Logout','church_mgt');?></a> 
	<?php 
	}
	else 
	{
	?>
    <div class="custom_login_form">
    <?php 
	   wp_login_form( $args );
	    echo '<a class="forgot_link" href="'.wp_lostpassword_url().'" title="Lost Password"> '. _e("Forgot your password?","church_mgt") .' </a>'; 
	?>
	</div>
	<?php 
	} 
}
add_action( 'wp_ajax_MJ_cmgt_load_capacity', 'MJ_cmgt_load_capacity');
add_action( 'wp_ajax_MJ_cmgt_add_or_remove_category', 'MJ_cmgt_add_or_remove_category');
add_action( 'wp_ajax_MJ_cmgt_add_category', 'MJ_cmgt_add_category');
add_action( 'wp_ajax_nopriv_MJ_cmgt_add_category', 'MJ_cmgt_add_category');
add_action( 'wp_ajax_MJ_cmgt_remove_category', 'MJ_cmgt_remove_category');
add_action( 'wp_ajax_MJ_cmgt_venue_view', 'MJ_cmgt_venue_view');
add_action( 'wp_ajax_MJ_cmgt_reservation_view', 'MJ_cmgt_reservation_view');
add_action( 'wp_ajax_MJ_cmgt_document_view', 'MJ_cmgt_document_view');
add_action( 'wp_ajax_MJ_cmgt_pastoral_view', 'MJ_cmgt_pastoral_view');
add_action( 'wp_ajax_MJ_cmgt_service_view', 'MJ_cmgt_service_view');
add_action( 'wp_ajax_MJ_cmgt_notice_view', 'MJ_cmgt_notice_view');

add_action( 'wp_ajax_MJ_cmgt_activity_view', 'MJ_cmgt_activity_view');
add_action( 'wp_ajax_MJ_cmgt_room_checkin_view', 'MJ_cmgt_room_checkin_view');
add_action( 'wp_ajax_MJ_cmgt_give_gifts', 'MJ_cmgt_give_gifts');
add_action( 'wp_ajax_MJ_cmgt_view_gifts_list', 'MJ_cmgt_view_gifts_list');
add_action( 'wp_ajax_MJ_cmgt_get_enddate_total_amount', 'MJ_cmgt_get_enddate_total_amount');
add_action( 'wp_ajax_MJ_cmgt_invoice_view', 'MJ_cmgt_invoice_view');
add_action( 'wp_ajax_MJ_cmgt_verify_pkey', 'MJ_cmgt_verify_pkey');
add_action( 'wp_ajax_MJ_cmgt_group_member_view', 'MJ_cmgt_group_member_view');
add_action( 'wp_ajax_MJ_cmgt_group_member_add', 'MJ_cmgt_group_member_add');
add_action( 'wp_ajax_MJ_cmgt_remove_group_member', 'MJ_cmgt_remove_group_member');
add_action( 'wp_ajax_MJ_cmgt_load_gift_price', 'MJ_cmgt_load_gift_price');
add_action( 'wp_ajax_nopriv_MJ_cmgt_load_gift_price', 'MJ_cmgt_load_gift_price');
add_action( 'wp_ajax_MJ_cmgt_add_ministry_popup', 'MJ_cmgt_add_ministry_popup');
add_action( 'wp_ajax_MJ_cmgt_add_group_popup', 'MJ_cmgt_add_group_popup');
add_action( 'wp_ajax_MJ_cmgt_add_vanue_popup', 'MJ_cmgt_add_vanue_popup');
add_action( 'wp_ajax_MJ_cmgt_add_member_popup', 'MJ_cmgt_add_member_popup');
add_action( 'wp_ajax_MJ_cmgt_view_family_member', 'MJ_cmgt_view_family_member');
add_action( 'wp_ajax_MJ_cmgt_change_profile_photo', 'MJ_cmgt_change_profile_photo');
add_action( 'wp_ajax_MJ_cmgt_show_event_task', 'MJ_cmgt_show_event_task');
add_action( 'wp_ajax_nopriv_MJ_cmgt_show_event_task', 'MJ_cmgt_show_event_task');



//add_action( 'wp_ajax_MJ_cmgt_import_data',  'MJ_cmgt_import_data');

// Import data function popup //
function MJ_cmgt_import_data()
{	
	?>
	<!-- <div class="modal-header model_header_padding"> 
  		<h4 id="myLargeModalLabel" class="modal-title"><?php esc_html_e('Member','church_mgt');?><a href="#" class="event_close-btn badge badge-danger pull-right">X</a></h4>
	</div>
	<div class="panel-body">
        <form name="upload_header_form" action="" method="post" class="form-horizontal" id="upload_header_form" enctype="multipart/form-data">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<div class="form-group">
				<div class="mb-3 row">
					<label class="col-sm-2 control-label form-label" for="city_name"><?php _e('Select CSV file','church_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="csv_file" type="file" style="display: inline;" name="csv_file">
					</div>
				</div>	
			</div>
			<div class="offset-sm-2 col-sm-8">
				<input id="upload_csv_headers" type="submit" value="<?php _e('Upload CSV File','church_mgt');?>" name="upload_csv_file" class="btn btn-success"/>
			</div>
		</form>
	</div> -->
	<?php 
}


//-------- LOED GIFT PRICE FUNCTION -----//
function MJ_cmgt_load_gift_price()
{
	$obj_gift=new Cmgtgift;
	$result = $obj_gift->MJ_cmgt_get_single_gift($_REQUEST['gift_id']);
	echo esc_attr($result->gift_price);
	die();
}
//-------- ADD MINISTRY POPUP FUNCTION -----//
function MJ_cmgt_add_ministry_popup() 
{
	 
	$imgurl=$_POST['cmgt_ministryimage'];
	$extension=MJ_cmgt_check_valid_extension($imgurl);
	if(!$extension == 0)
	{
		global $wpdb;
		$obj_cmgtministry=new Cmgtministry;
		$result=$obj_cmgtministry->MJ_cmgt_add_ministry($_POST,$_POST['cmgt_ministryimage']);
		if($result)
		{
		?>
			<script type="text/javascript">
				$(document).ready(function(){
		        	$(".show").removeClass("modal-backdrop");
		        	$(".show").removeClass("fade");
				});
			</script>
		<?php
		}
		$last_id=$wpdb->insert_id;
		$ministraydata=$obj_cmgtministry->MJ_cmgt_get_single_ministry($last_id);
		$option="<option value='".$ministraydata->id."'>" .$ministraydata->ministry_name."</option>";
		echo $option;
		die();    
    }
	else
	{
	 ?>
		<script>
			alert("<?php esc_html_e('Only jpg jpeg png and gif files are allowed!','church_mgt');?>");
		</script>
   <?php  
	}
}
//-------- ADD GRUOP POPUP FUNCTION -----//
function MJ_cmgt_add_group_popup()
{
	$imgurl=sanitize_text_field($_POST['cmgt_groupimage']);
	$extension=MJ_cmgt_check_valid_extension($imgurl);
	if(!$extension == 0)
	{
		global $wpdb;
		$obj_group=new Cmgtgroup;
		$result=$obj_group->MJ_cmgt_add_group($_POST,$_POST['cmgt_groupimage']);
		if($result)
		{
		?>
			<script type="text/javascript">
				$(document).ready(function(){
		        	$(".show").removeClass("modal-backdrop");
		        	$(".show").removeClass("fade");
				});
			</script>
		<?php
		}
		$last_id=$wpdb->insert_id;
		$groupdata=$obj_group->MJ_cmgt_get_single_group($last_id);
		$option="<option value='".$groupdata->id."'>" .$groupdata->group_name."</option>";
		echo $option;
		die(); 
	}
   else
	{ ?>
	  <script>
		alert("<?php esc_html_e('Only jpg jpeg png and gif files are allowed!','church_mgt');?>");
	  </script>
   <?php
	}
}
//-------- ADD VANUE POPUP FUNCTION -----//
function MJ_cmgt_add_vanue_popup() 
{
   global $wpdb;
   $obj_Cmgtvenue=new Cmgtvenue;
   $result=$obj_Cmgtvenue->MJ_cmgt_add_venue($_POST);
   if($result)
	{
	?>
		<script type="text/javascript">
			$(document).ready(function(){
	        	$(".show").removeClass("modal-backdrop");
	        	$(".show").removeClass("fade");
			});
		</script>
	<?php
	}
   $last_id=$wpdb->insert_id;
   $vanue_data=$obj_Cmgtvenue->MJ_cmgt_get_single_venue($last_id);
   $option="<option value='".$vanue_data->id."'>" .$vanue_data->venue_title."</option>";
   echo $option;
   die();    
}
//-------- ADD MEMBER POPUP FUNCTION -----//
function MJ_cmgt_add_member_popup()
{
   global $wpdb;
   $obj_cmgtmember=new Cmgtmember();
   $result=$obj_cmgtmember->MJ_cmgt_add_user($_POST);
   $member_id=get_userdata($result);
   $option = "<option value='".$member_id->ID ."'>" .$member_id->display_name . "-" .$member_id->member_id . "</option>";
   $array_var[] = $option;
   echo json_encode($array_var);
   die();    
}
function MJ_cmgt_verify_pkey()
{
	//$api_server = '192.168.1.22';
	//$api_server = 'http://license.dasinfomedia.com';
	$api_server = 'license.dasinfomedia.com';
	$fp = fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=cmgt-church_system';
	if (!$fp)
            $server_rerror = 'Down';
        else
            $server_rerror = "up";
	if($server_rerror == "up")
	{
		$domain_name= sanitize_text_field($_SERVER['SERVER_NAME']);
		$licence_key = sanitize_text_field($_REQUEST['licence_key']);
		$email = sanitize_text_field($_REQUEST['enter_email']);
		$data['domain_name']= $domain_name;
		$data['licence_key']= $licence_key;
		$data['enter_email']= $email;

		//$verify_result = amgt_submit_setupform($data);
			$result = MJ_cmgt_check_productkey($domain_name,$licence_key,$email);
		if($result == '1')
		{
			$message = 'Please provide correct Envato purchase key.';
				$_SESSION['cmgt_verify'] = '1';
		}
		elseif($result == '2')
		{
			$message = 'This purchase key is already registered with the different domain. If have any issue please contact us at sales@mojoomla.com ';
				$_SESSION['cmgt_verify'] = '2';
		}
		elseif($result == '3')
		{
			$message = 'There seems to be some problem please try after sometime or contact us on sales@mojoomla.com';
				$_SESSION['cmgt_verify'] = '3';
		}
		elseif($result == '4')
		{
			$message = 'Please provide correct Envato purchase key for this plugin.';
				$_SESSION['cmgt_verify'] = '4';
		}
		else
		{
			update_option('domain_name',$domain_name,true);
			update_option('licence_key',$licence_key,true);
			update_option('cmgt_setup_email',$email,true);
			$message = 'Success fully register';
				$_SESSION['cmgt_verify'] = '0';
		}
		$result_array = array('message'=>$message,'cmgt_verify'=>$_SESSION['cmgt_verify'],'location_url'=>$location_url);
		echo json_encode($result_array);
	}
	else
	{
		$message = 'Server is down Please wait some time';
		$_SESSION['cmgt_verify'] = '3';
		$result_array = array('message'=>$message,'cmgt_verify'=>$_SESSION['cmgt_verify'],'location_url'=>$location_url);
		echo json_encode($result_array);
	}
	die();
}
//-------- STRING REPLACEMENT FUNCTION -----//
function MJ_cmgt_string_replacemnet($arr,$message)
{
	$data = str_replace(array_keys($arr),array_values($arr),$message);
	return $data;
}
//-------- ADD OR REMOVE CATEGORY FUNCTION -----//
function MJ_cmgt_add_or_remove_category()
{
	$model = sanitize_text_field($_REQUEST['model']);
	
	$title = esc_html__("title",'church_mgt');
	$table_header_title =  esc_html__("header",'church_mgt');
	$button_text=  esc_html__("Add category",'church_mgt');
	$label_text =  esc_html__("category Name",'church_mgt');

	if($model == 'activity_category')
	{
		$title = esc_html__("Activity",'church_mgt');
		$table_header_title =  esc_html__("Activity Category Name",'church_mgt');
		$button_text=  esc_html__("ADD",'church_mgt');
		$label_text =  esc_html__("Add Activity",'church_mgt');	
	}
	if($model == 'document_category')
	{
		$title = esc_html__("Document",'church_mgt');
		$table_header_title =  esc_html__("Document Category Name",'church_mgt');
		$button_text=  esc_html__("ADD",'church_mgt');
		$label_text =  esc_html__("Add Document Category Name",'church_mgt');	
	}
	if($model == 'equipment_category')
	{
		$title = esc_html__("Equipment",'church_mgt');
		$table_header_title =  esc_html__("Equipment Name",'church_mgt');
		$button_text=  esc_html__("ADD",'church_mgt');
		$label_text =  esc_html__("Add Equipment",'church_mgt');	
	}
	if($model == 'song_category')
	{
		$title = esc_html__("Song Category",'church_mgt');
		$table_header_title =  esc_html__("Category Name",'church_mgt');
		$button_text=  esc_html__("ADD",'church_mgt');
		$label_text =  esc_html__("Add Category Name",'church_mgt');	
	}	
	if($model == 'service_type')
	{
		$title = esc_html__("Service",'church_mgt');
		$table_header_title =  esc_html__("Service Type Name",'church_mgt');
		$button_text=  esc_html__("ADD",'church_mgt');
		$label_text =  esc_html__("Add Service Type",'church_mgt');	
	}	
	if($model == 'donation_category')
	{
		$title = esc_html__("Donation Type",'church_mgt');
		$table_header_title =  esc_html__("Donation Type Name",'church_mgt');
		$button_text=  esc_html__("ADD",'church_mgt');
		$label_text =  esc_html__("Add Donation Name",'church_mgt');	
	}	
	$cat_result = MJ_cmgt_get_all_category( $model );
	?>
	<?php
	if($model == 'equipment_category')
	{
		?>
	<div class="modal-header"> 
		<a href="javascript:void(0)" model="equipment_category" class="close-btn badge badge-danger pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php echo esc_html($title);?></h4>
	</div>
	<?php
	}
	else
	{ ?>
	
	<div class="modal-header"> 
		<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php echo esc_html($title);?></h4>
	</div>
	<?php
	}
	?>
	<div class="panel-white"> <!-- PANEL WHITE DIV START-->  
		<form name="category_form" action="" method="post" class="cmgt_category_form" id="category_form">
			<div class="form-body user_form">
				<div class="row"><!--Row Div--> 
					<div class="col-md-9 col-sm-9">
						<div class="form-group input">
							<div class="col-md-12 form-control cmgt_category_input">
								<input id="category_name" class="form-control validate[required,custom[popup_category_validation]]" type="text" maxlength="50"  name="category_name">
								<label for="category_name"><?php echo esc_attr($label_text);?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 rtl_margin_top_15px">
						<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success col-md-12 col-sm-12 cmgt_category_btn" model="<?php echo $model;?>" id="btn-add-cat"/>
					</div>
				</div>
			</div>
	  	 	
			</div>
  		</form>

  		<div class="category_listbox"><!-- CATEGORY DIV START-->  
  			<div class="cmgt_category_listbox table-responsive"><!-- TABLE RESPONSIVE DIV START-->
		  		<table class="table ">
			  		<!-- <thead>
			  			<tr>
			                <th><?php echo esc_attr($table_header_title);?></th>
			                <th><?php esc_html_e('Action','church_mgt');?></th>
			            </tr>
			        </thead> -->
			         <?php 
					$i = 1;
					if(!empty($cat_result))
					{
						$delete_image = CMS_PLUGIN_URL."/assets/images/Church-icons/Delete.png";
						foreach ($cat_result as $retrieved_data)
						{
							echo '<tr id="cat-'.esc_attr($retrieved_data->ID).'">';
							echo '<td class="popup_width_90">'.esc_attr($retrieved_data->post_title).'</td>';
							echo '<td id='.esc_attr($retrieved_data->ID).'><a class="btn-delete-cat badge " model='.esc_attr($model).' href="#" id='.esc_attr($retrieved_data->ID).'><div class="col-md-2"><img src='.$delete_image.' class="massage_image center"></div></a></td>';
							echo '</tr>';
							$i++;		
						}
					}
				?>
				</table>
		    </div><!-- TABLE RESPONSIVE DIV END-->
  		</div><!-- CATEGORY DIV END-->
  	</div><!-- PANEL WHITE DIV END-->  
	<?php 
	die();	
}
//-------- GET ALL CATEGORY FUNCTION -----//
function  MJ_cmgt_get_all_category($model)
{
	$args= array('post_type'=> $model,'posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
	$cat_result = get_posts( $args );
	return $cat_result;
}
//-------- ADD CATEGORY FUNCTION -----//
function MJ_cmgt_add_category()
{
	global $wpdb;
	$model = sanitize_text_field($_REQUEST['model']);
	$array_var = array();
	$data['category_name'] = sanitize_text_field($_REQUEST['category_name']);
	$data['category_type'] = sanitize_text_field($_REQUEST['model']);
	$delete_image = CMS_PLUGIN_URL."/assets/images/Church-icons/Delete.png";
	$id = MJ_cmgt_add_categorytype($data);
	$row1 = '<tr id="cat-'.$id.'"><td class="popup_width_90">'.$_REQUEST['category_name'].'</td><td><a class="btn-delete-cat badge" href="#" id='.$id.' model="'.$model.'"><div class="col-md-2"><img src='.$delete_image.' class="massage_image center"></div></a></td></tr>';
	$option = "<option value='$id'>".$_REQUEST['category_name']."</option>";
	$array_var[] = $row1;
	$array_var[] = $option;
	echo json_encode($array_var);
	die();
}
//-------- ADD CATEGORY TYPE FUNCTION -----//
function MJ_cmgt_add_categorytype($data)
{
	global $wpdb;
	$result = wp_insert_post( array(
		'post_status' => 'publish',
		'post_type' => $_REQUEST['model'],
		'post_title' => $_REQUEST['category_name']) );
	$id = sanitize_text_field($wpdb->insert_id);
	return $id;
}
//-------- ADD OR REMOVE CATEGORY FUNCTION -----//
function MJ_cmgt_remove_category()
{
	wp_delete_post(sanitize_text_field($_REQUEST['cat_id']));
	die();
}
//-------- LOAD CAPACITY FUNCTION -----//
function MJ_cmgt_load_capacity()
{
	$id=$_REQUEST['vanue_id'];
	global $wpdb;
	$table_venue = $wpdb->prefix. 'cmgt_venue';
	$result = $wpdb->get_row("SELECT * FROM $table_venue where id=".$id);
	echo esc_attr($result->capacity);
	die();
}
//-------- VIEW VENUE FUNCTION -----//
function MJ_cmgt_venue_view()
{
	$obj_venue=new Cmgtvenue;
	$curr_user_id=get_current_user_id();
	$obj_church = new Church_management(get_current_user_id());
	if(isset($_REQUEST['venue_id']))
	{
		$result = $obj_venue->MJ_cmgt_get_single_venue(sanitize_text_field($_REQUEST['venue_id']));?>
			<div class="modal-header margin_bottom_20">
				<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
				<h4 class="modal-title" id="myLargeModalLabel"><img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Venue-black.png"?>"><?php 
				echo  esc_html__('Venue Details','church_mgt');
				?></h4>
			</div>
			<div class="panel panel-white form-horizontal cmgt_main_popup_div cmgt_box_unset">
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading" for="venue_name">
									<?php esc_html_e('Venue Name','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_attr($result->venue_title);?> </label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading" for="notice_title">
								<?php esc_html_e('Capacity Seats','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($result->capacity);?><?php esc_html_e(' Seats','church_mgt');?></label>
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading"for="dinner">
									<?php esc_html_e('Request Before Days','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_attr($result->request_before_days);?><?php esc_html_e(' Days','church_mgt');?> </label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading" for="afternoon_snack">
								<?php esc_html_e('Equipments','church_mgt');?>
								</label><br>
								<?php $quipment='';
									$equipment_array = array();
									
									$equipment_array =(explode(",",$result->equipments));
										foreach ($equipment_array as $retrive_data)
										{ 
											if($retrive_data!='')
												$quipment.=get_the_title($retrive_data).',';
										}
										if(!empty($quipment))
										{ 
											$quipment_title= rtrim($quipment,',');
											?>
											<label for="" class="popup_label_value">
												<?php echo esc_attr($quipment_title);?>
											</label>
											<?php
										}else
										{
											//$quipment_title='N/A';
											?>
											<label for="" class="popup_label_value">
												<?php _e('N/A','church_mgt');?>
											</label>
											<?php
										}
									?>
								
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50 rtl_float_right">
								<label class="popup_label_heading" for="lunch">
									<?php esc_html_e('Multiple Reservation','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php if($result->multiple_booking=='yes') echo esc_html_e('Yes','church_mgt'); else echo esc_html_e('No','church_mgt');?> </label>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- <?php if($obj_church->role == 'accountant'){?>
				   <div class="print-button pull-left">
						<a  href="?church-dashboard=user&page=venue&tab=add_reservation&action=booking&vanue_id=<?php echo $_REQUEST['venue_id'];?>" class="btn btn-success"><?php esc_html_e('Book','church_mgt');?></a>
					</div>
			  <?php } ?> -->
			</div>

			<?php 
	}
	else
	{?>
		<p><?php esc_html_e('No Venue Data','church_mgt');?></p>
			</div>
	<?php 
	}
	die();
}


//-------- VIEW DOCUMENT FUNCTION- hitesh-----//
function MJ_cmgt_document_view()
{

	$obj_document=new cmgt_document;

	if(isset($_REQUEST['document_id']))
	{
		$result = $obj_document->MJ_cmgt_get_single_document(sanitize_text_field($_REQUEST['document_id']));
			?>
			<div class="modal-header margin_bottom_20">
				<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
				<h4 class="modal-title" id="myLargeModalLabel"><img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Documents.png"?>"><?php 
					echo esc_html__('Document Details','church_mgt');
					?></h4>
			</div>
			<div class="panel panel-white form-horizontal cmgt_main_popup_div cmgt_box_unset">
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Document Name','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_html(ucfirst($result->document_name));?> </label>
							</td>
								<?php $user_info = get_userdata($result->ducument_create_by);
									$username = $user_info->user_login;
								?>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Document Created By','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php if(!empty($username)){ echo esc_attr(ucfirst($username));}else{echo "N/A";} ?></label>
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
									<?php esc_html_e('View Document','church_mgt');?>
								</label><br>
								
								<?php 
									if(trim($result->document) != "")
									{
										echo '<a  target="blank" href="'.esc_url($result->document).'" class="btn btn-default"><i class="fa fa-eye"></i> '.esc_html__("View","church_mgt").'</a>';
									}
									else 
									{
										echo "No any document";
									}
								?>
							</td>	
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Document Created Date','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_html(ucfirst($result->created_date));?> </label>
							</td>	
						
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="" style="width: 100%;">
								<label class="popup_label_heading">
								<?php esc_html_e('Description','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_html(ucfirst($result->description));?> </label>
							</td>
						</tr>
					</tbody>
				</table>
			  <?php  
	}
	else
	{ ?>
				<p><?php esc_html_e('No Pastoral Data','church_mgt');?></p>
			</div>
	<?php
	}
	die();
	
}
//-------- VIEW RESERVATION FUNCTION- hitesh-----//
function MJ_cmgt_reservation_view()
{
	$obj_venue=new Cmgtvenue;
	$obj_reservation=new Cmgtreservation;
	$curr_user_id=get_current_user_id();
	$obj_church = new Church_management(get_current_user_id());
	if(isset($_REQUEST['reservation_id']))
	{
		$result = $obj_reservation->MJ_cmgt_get_single_reservation(sanitize_text_field($_REQUEST['reservation_id']));
		// var_dump($result);
		// die;
		$obj_venue=new Cmgtvenue;
		$venue_title = $obj_venue->MJ_cmgt_get_single_venue($result->vanue_id);
		?>
		<div class="modal-header margin_bottom_20">
			<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel"><img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Reservation-Black.png"?>"><?php 
				echo esc_html__('Reservation Details','church_mgt');
				?></h4>
		</div>
			<div class="panel panel-white form-horizontal cmgt_main_popup_div cmgt_box_unset">
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Usage Title','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_html(ucfirst($result->usage_title));?> </label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Venue','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr(ucfirst($venue_title->venue_title));?></label>
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Start Date To End Date','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->reserve_date)));?> <?php _e('To','church_mgt');?> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->reservation_end_date)));?> </label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Start Time To End Time','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($result->reservation_start_time);?> <?php _e('To','church_mgt');?> <?php echo esc_attr($result->reservation_end_time);?></label>
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Number of Participant','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_html($result->participant);?> <?php esc_html_e('Out of','church_mgt');?> <?php echo esc_html($result->participant_max_limit);?></label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Applicant','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo MJ_cmgt_church_get_display_name(esc_attr($result->applicant_id));?></label>
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="">
								<label class="popup_label_heading">
								<?php esc_html_e('Description','church_mgt');?>
								</label><br>
								<?php if(!empty($result->description)) 
									{
										$description=$result->description;
										?>
											<label for="" class="popup_label_value"><?php echo esc_html($description);?> </label>
										<?php
									}
									else
									{
										//$description='N/A';
										?>
										<label for="" class="popup_label_value"><?php _e('N/A','church_mgt');?></label>
										<?php
									} ?>
							</td>	
						</tr>
					</tbody>
				</table>
			  <?php  
	}
	else
	{ ?>
				<p><?php esc_html_e('No Pastoral Data','church_mgt');?></p>
			</div>
	<?php
	}
	die();
	
}

//-------- VIEW NOTICE FUNCTION- hitesh-----//
function MJ_cmgt_notice_view()
{
	// echo "hello";
	// die;
	$obj_notice=new Cmgtnotice;

	//$obj_service=new Cmgtservice;
	$curr_user_id=get_current_user_id();
	$obj_church = new Church_management(get_current_user_id());
	if(isset($_REQUEST['notice_id']))
	{
		$result = $obj_notice->MJ_cmgt_get_single_notice(sanitize_text_field($_REQUEST['notice_id']));
		?>
		<div class="modal-header margin_bottom_20">
			<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel"><img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/notice_black.png"?>"><?php esc_html_e('Notice Details','church_mgt'); ?></h4>
		</div>
			<div class="panel panel-white form-horizontal cmgt_main_popup_div cmgt_box_unset">
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Notice Title','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_html($result->notice_title);?> </label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Notice By','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo MJ_cmgt_church_get_display_name(esc_attr($result->created_by));?></label>
								
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Notice Start Date To End Date','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->start_date)));?> <?php esc_html_e('To','church_mgt');?> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->end_date)));?></label>
							</td>

							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Notice Comment','church_mgt');?>
								</label><br>
								<?php
									if(!empty($result->notice_content)) 
									{ ?>
										<label for="" class="popup_label_value"><?php echo esc_attr($result->notice_content);?></label>
										<?php
									}
									else
									{ ?>
										<label for="" class="popup_label_value"><?php echo esc_html( __( 'N/A', 'church_mgt' ) );?></label>
										<?php
									} 
								?>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
	}
	else
	{ ?>
				<p><?php esc_html_e('No Notice Data','church_mgt');?></p>
			</div>
	<?php
	}
	die();
}

//-------- VIEW SERVICE FUNCTION- hitesh-----//
function MJ_cmgt_service_view()
{
	// $obj_pastoral=new Cmgtpastoral;
	$obj_service=new Cmgtservice;
	$curr_user_id=get_current_user_id();
	$obj_church = new Church_management(get_current_user_id());
	if(isset($_REQUEST['service_id']))
	{
		$result = $obj_service->MJ_cmgt_get_single_services(sanitize_text_field($_REQUEST['service_id']));
		
		?>
		<div class="modal-header margin_bottom_20">
			<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel"><img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/services.png"?>"><?php esc_html_e('Service Details','church_mgt'); ?></h4>
		</div>
			<div class="panel panel-white form-horizontal cmgt_main_popup_div cmgt_box_unset">
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Service Title','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_html($result->service_title);?> </label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Service Type','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value">
									<?php 
									if(!empty($result->service_type_id) == "0")
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
									else
									{
										echo esc_attr(get_the_title($result->service_type_id));
									}
										
									?>
								</label>
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Start Date To End Date','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->start_date)));?> <?php esc_html_e('To','church_mgt');?> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->end_date)));?></label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Start Time To End Time','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($result->start_time);?>	<?php esc_html_e('To','church_mgt');?> <?php echo esc_attr($result->end_time);?></label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
				if(!empty($result->other_title))
				{
					?>
					<hr>
					<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
						<tbody>
							<tr>
								<td class="width_50">
									<label class="popup_label_heading">
									<?php esc_html_e('Other title','church_mgt');?>
									</label><br>
									<label for="" class="popup_label_value"><?php echo esc_attr($result->other_title);?></label>
								</td>
								<td class="width_50">
									<label class="popup_label_heading">
									<?php esc_html_e('Other Service Type','church_mgt');?>
									</label><br>
									<label for="" class="popup_label_value"><?php echo esc_attr($result->other_service_type);?></label>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
						<tbody>
							</tr>
								<td class="width_50">
									<label class="popup_label_heading">
									<?php esc_html_e('Other Service Date','church_mgt');?>
									</label><br>
									<label for="" class="popup_label_value"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($result->other_service_date)));?></label>
								</td>
								<td class="width_50">
									<label class="popup_label_heading">
									<?php esc_html_e('Other Start Time To End Time', 'church_mgt' ) ;?>
									</label><br>
									<label for="" class="popup_label_value"><?php echo esc_attr($result->other_start_time);?> <?php esc_html_e('To','church_mgt');?> <?php echo esc_attr($result->other_end_time);?> </label>
								</td>
							</tr>
						</tbody>
					</table>
					<?php
				}
				?>
			  <?php  
	}
	else
	{ ?>
				<p><?php esc_html_e('No Pastoral Data','church_mgt');?></p>
			</div>
	<?php
	}
	die();
}

//-------- VIEW Activity FUNCTION- hitesh-----//
function MJ_cmgt_activity_view()
{
	$obj_activity=new Cmgtactivity;
	$curr_user_id=get_current_user_id();
	$obj_church = new Church_management(get_current_user_id());
	if(isset($_REQUEST['activity_id']))
	{
		$activitydata = $obj_activity->MJ_cmgt_get_single_activity(sanitize_text_field($_REQUEST['activity_id']));
	
		$obj_venue=new Cmgtvenue;
		$obj_group=new Cmgtgroup;
		$group_data=$obj_group->MJ_cmgt_get_single_group($activitydata->groups);
		$result = $obj_venue->MJ_cmgt_get_single_venue($activitydata->venue_id);
		$reccurence_array=json_decode($activitydata->recurrence_content);
		?>
		<div class="modal-header margin_bottom_20">
			<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel"><img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Activity.png"?>"><?php esc_html_e('Activity Details','church_mgt'); ?></h4>
		</div>
			<div class="panel panel-white form-horizontal cmgt_main_popup_div cmgt_box_unset">
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr >
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Activity Category', 'church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr(ucfirst(get_the_title($activitydata->activity_cat_id)));?> </label>
							</td>	
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e( 'Activity Title', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($activitydata->activity_title);?></label>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e( 'Guest Speaker', 'church_mgt' ) ;?>
								</label><br>
								<?php if(!empty($activitydata->speaker_name)) 
									{
										$speaker_name=$activitydata->speaker_name;
										?>
											<label for="" class="popup_label_value"><?php echo esc_attr($speaker_name); ?></label>
										<?php
									}
									else
									{
										//$speaker_name='N/A';
										?>
											<label for="" class="popup_label_value"><?php _e('N/A','church_mgt');?></label>
										<?php
									} ?>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Venue', 'church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value">
									<?php 
										if(($activitydata->venue_id) == "0")
										{
											echo esc_html( __('N/A','church_mgt' ) );
										}else{
											if(!empty($result->venue_title))
											{
												echo esc_attr($result->venue_title);
											}else{
												echo "N/A";
											}
										}
									?>
								</label>
							</td>	
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr >
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e( 'Start Date To End Date', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($activitydata->activity_date)));?> <?php esc_html_e( 'To', 'church_mgt' ) ;?> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($activitydata->activity_end_date)));?></label>
							</td>	
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e( 'Start Time To End Time', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value">
									<?php
									if(($activitydata->activity_start_time) == "Full Day" && ($activitydata->activity_end_time) == "Full Day")
									{
										echo esc_html( __( 'Full Day', 'church_mgt' ) );
									}
									else
									{
										echo esc_attr($activitydata->activity_start_time);?> <?php _e('To','church_mgt');?> <?php echo esc_attr($activitydata->activity_end_time);?> 
										<?php
									}
									?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50 cmgt_rtl_das_pop_right">
								<label class="popup_label_heading">
								<?php esc_html_e( 'Recurrence', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value">
									<?php 
									$reccurence_day=$reccurence_array->selected;
									
									$reccurence_weekly=$reccurence_array->weekly;
									$day_array=$reccurence_weekly->weekly;

									$reccurence_month_date=$reccurence_array->monthly;
									$reccurence_yearly_date=$reccurence_array->yearly;
										if($reccurence_day == 'daily')
										{
											echo _e(ucfirst($reccurence_day),'church_mgt');
										}
										if($reccurence_day == 'weekly')
										{
											$day_array_new=array();
											foreach($day_array as $value)
											{
												$day_array_new[]=$value;
											}
											$day_name_arry= implode(",",($day_array_new));
											echo _e(ucfirst($reccurence_day) , 'church_mgt').'('.esc_attr($day_name_arry , 'church_mgt').')';
											
										}
										if($reccurence_day == 'monthly')
										{
											
											echo _e(ucfirst($reccurence_day) , 'church_mgt'). _e('(Day: ' , 'church_mgt') .esc_attr($reccurence_month_date->month_date).')';
										}
										if($reccurence_day == 'yearly')
										{
											echo _e(ucfirst($reccurence_day) , 'church_mgt'). _e('(Date: ' , 'church_mgt') .esc_attr(date(MJ_cmgt_date_formate(),strtotime($reccurence_yearly_date->yearly_date))).')';
										}
										if($reccurence_day == 'none')
										{
											echo _e(ucfirst($reccurence_day) , 'church_mgt');
										}
									?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
				if(!empty($group_data->group_name && $activitydata->record_start_time && $activitydata->record_end_time))
				{
					?>
					<hr>
					<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
						<tbody>
							<tr>
							<?php
								if($group_data->group_name)
								{
									?>
									<td class="width_50">
										<label class="popup_label_heading">
										<?php esc_html_e('Group Name', 'church_mgt');?>
										</label><br>
										<label for="" class="popup_label_value"><?php echo esc_attr($group_data->group_name);?></label>
									</td>
									<?php
								}else{
									?>
									<td class="width_50">
										<label class="popup_label_heading">
										<?php esc_html_e('Group Name', 'church_mgt');?>
										</label><br>
										<label for="" class="popup_label_value"><?php esc_html_e('N/A', 'church_mgt');?></label>
									</td>
									<?php
								}
								?>
								<?php
								if($activitydata->record_start_time && $activitydata->record_end_time )
								{
									?>
									<td class="width_50">
										<label class="popup_label_heading">
										<?php esc_html_e('Other Start Time To End Time', 'church_mgt' ) ;?>
										</label><br>
										<label for="" class="popup_label_value"><?php echo esc_attr($activitydata->record_start_time);?> <?php esc_html_e( 'To', 'church_mgt' ) ;?> <?php echo esc_attr($activitydata->record_end_time);?></label>
									</td>
									<?php
								}else{
									?>
									<td class="width_50">
										<label class="popup_label_heading">
										<?php esc_html_e('Start Time To End time', 'church_mgt' ); ?>
										</label><br>
										<label for="" class="popup_label_value"><?php esc_html_e('N/A', 'church_mgt');?></label>
									</td>
									<?php
								}
								?>
								
							</tr>
						</tbody>
					</table>				
			 	 	<?php  
				}
	}
	else
	{ ?>
				<p><?php esc_html_e('No Activity Data','church_mgt');?></p>
			</div>
	<?php
	}
	die();
}


//-------- VIEW POSTORAL FUNCTION -----//
function MJ_cmgt_pastoral_view()
{
	$obj_pastoral=new Cmgtpastoral;
	$curr_user_id=get_current_user_id();
	$obj_church = new Church_management(get_current_user_id());
	if(isset($_REQUEST['pastoral_id']))
	{
		$result = $obj_pastoral->MJ_cmgt_get_single_pastoral(sanitize_text_field($_REQUEST['pastoral_id']));?>

		<div class="modal-header margin_bottom_20">
			<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
			
			<h4 class="modal-title" id="myLargeModalLabel"><img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pledges-Black.png"?>"><?php esc_html_e('Pastoral Details','church_mgt'); ?></h4>
		</div>
		<div class="panel panel-white form-horizontal cmgt_main_popup_div cmgt_box_unset">
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr >
							<td class="width_50">
								<label class="popup_label_heading" for="pastoral_name">
								<?php esc_html_e('Pastoral Title','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($result->pastoral_title);?></label>
							</td>	
							<td class="width_50">
								<label class="popup_label_heading" for="pastoral_title">
								<?php esc_html_e('Member Name','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> <?php $user=get_userdata($result->member_id); echo esc_attr($user->display_name);?> </label>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr >
							<td class="width_50">
								<label class="popup_label_heading" for="pastoral_name">
								<?php esc_html_e('Pastoral Date','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($result->pastoral_date)));?> </label>
							</td>	
							<td class="width_50">
								<label class="popup_label_heading" for="pastoral_name">
								<?php esc_html_e('Pastoral Time','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"> 
									<?php if(!empty($result->pastoral_time)){ echo esc_attr($result->pastoral_time);}else{ echo "N/A";}?> 
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr >
							<td class="">
								<label class="popup_label_heading" for="pastoral_name">
								<?php esc_html_e('Description','church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value">
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
							</td>
						</tr>
					</tbody>
				</table>
			  <?php  
	}
	else
	{ ?>
		<p><?php esc_html_e('No Pastoral Data','church_mgt');?></p>
		</div>
	<?php
	}
	die();
}
//-------- VIEW ROOM CHECKIN FUNCTION -----//
function MJ_cmgt_room_checkin_view()
{
	$obj_church = new Church_management(get_current_user_id());
	$obj_room=new Cmgtcehckin;
	if(isset($_REQUEST['room_id']))
	{
		$result = $obj_room->MJ_cmgt_get_room_reservation(sanitize_text_field($_REQUEST['room_id']));
		$room_history =MJ_cmgt_room_checkin_history(sanitize_text_field($_REQUEST['room_id']));
		?>
		<div class="modal-header">
			<h4 class="modal-title w-auto float-start" id="myLargeModalLabel">
				<?php echo MJ_get_room_name($_REQUEST['room_id']) ." ". _e('Details Room: ','church_mgt'); ?>
			</h4>
			<a href="#" class="close-btn badge badge-danger pull-right w-auto ms-auto mt-2 me-3">X</a>
		</div>
		<hr>


		<div class="modal-body max_height_425_res" style="min-height: 180px; max-height: 180px; overflow-y:auto;">
			<?php 
			if($result)	
			{
				$result->member_id;
				$user=get_userdata($result->member_id);	
				?>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading"><?php esc_html_e( 'Member Name', 'church_mgt' ) ;?></label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($user->display_name);?></label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading"><?php esc_html_e( 'Number Of Family Member', 'church_mgt' ) ;?></label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($result->family_members);?> </label>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading"><?php esc_html_e( 'Check In Date', 'church_mgt' ) ;?></label><br>
								<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($result->checkin_date));?></label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading"><?php esc_html_e( 'Expected Check Out Date', 'church_mgt' ) ;?></label><br>
								<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($result->checkout_date));?> </label>
								<?php $date1=date("Y-m-d");
								  $date2 = MJ_cmgt_get_format_for_db($result->checkout_date);
								if($date1>$date2)
									$checkout=$obj_room->MJ_cmgt_room_checkout($result->id);?>
							</td>
						</tr>
					</tbody>
				</table>
				<?php 
				if($result->member_id==get_current_user_id() || $obj_church->role == 'accountant')
				{ ?>
					<div class="margin_top_10">
						<a  href="?church-dashboard=user&page=check-in&tab=roomlist&action=checkout&check_id=<?php echo esc_attr($result->id);?>" class="btn btn-danger"><?php esc_html_e('Check-Out','church_mgt');?></a>
					</div>
					<?php 
				}
				$user_id=get_current_user_id();
				$current_user_info=get_userdata($user_id);						
				if($current_user_info->roles[0] == 'administrator') 
				{?>
					<a  href="?page=cmgt-checkin&tab=roomlist&action=checkout&check_id=<?php echo esc_attr($result->id);?>" class="btn btn-danger"><?php esc_html_e('Check-Out','church_mgt');?></a>
					<?php  
				} 
				?>
				</div>
				<div class="cmgt_checkin_popup panel-body" style="min-height: 200px; max-height: 200px; overflow-y:auto;">
					<hr>
					<h4 class="cmgt_pop_header_color"><?php esc_html_e('Room History','church_mgt');?></h4>
					<?php 
					if(!empty($room_history))
					{
						?>
						<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
							<thead>
								<tr>
									<th class="text-left"><?php esc_html_e('Member','church_mgt');?></th>
									<th class="text-left"> <?php esc_html_e('Check-in Date','church_mgt');?></th>
									<!-- <th class="text-left"><?php esc_html_e('Check-out Date','church_mgt');?> </th> -->
									<th class="text-left"><?php esc_html_e('Expected Check Out Date','church_mgt');?> </th>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach($room_history as  $retrive_date)
								{
									if($obj_church->role == 'administrator' || $obj_church->role == 'accountant'  || $obj_church->role == 'management')
									{
										?>
										<tr>
											<td class="popup_label_value"><?php echo MJ_cmgt_church_get_display_name($retrive_date->member_id);?></td>
											<td class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrive_date->checkin_date));?></td>
											<td class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrive_date->checkout_date));?></td>
										</tr>
										<?php 
									}
									elseif(get_current_user_id()==$retrive_date->member_id)
									{	
										?>
										<tr>
											<td><?php echo MJ_cmgt_church_get_display_name($retrive_date->member_id);?></td>
											<td><?php echo date(MJ_cmgt_date_formate(),strtotime($retrive_date->checkin_date));?></td>
											<td><?php echo date(MJ_cmgt_date_formate(),strtotime($retrive_date->checkout_date));?></td>
										</tr>
										<?php 
									}  
								}
								?>
							</tbody>
						</table>
						<?php 
					}?>
				</div>
				<?php 
			}
			else
			{ 	
				?>
				<div class="cmgt_checkin_popup panel panel-white form-horizontal"><!-- PANEL WHITE DIV START-->  
					<div class="panel-body float-start w-100"><!-- PANEL BODY DIV START-->
						<div class="form-group">
							<label class="col-sm-5 cmgt_pop_header_color" for="not found label"><strong>
							<?php esc_html_e('Do not have any active bookings','church_mgt');?></strong>
							</label>
						</div>
						<div class="margin_top_10">
							<?php  
							$user_id=get_current_user_id();
							$current_user_info=get_userdata($user_id);						
							if($current_user_info->roles[0] == 'administrator') 
							{	?>
								<a  href="?page=cmgt-checkin&tab=checkin&action=booking&room_id=<?php echo esc_attr($_REQUEST['room_id']);?>" class="btn btn-success"><?php esc_html_e('Check-In','church_mgt');?></a>
								<?php 
							}
							/* if($obj_church->role == 'accountant')
							{ 
								?>
								<a href="?church-dashboard=user&page=check-in&tab=checkin&action=booking&room_id=<?php echo esc_attr($_REQUEST['room_id']);?>" class="btn btn-success"><?php esc_html_e('Check-In','church_mgt');?></a>
								<?php 
							} */
								?>
						</div>
					</div>
					<div class="cmgt_padding_0px panel-body float-start w-100">
						<hr>
						<h4 class="cmgt_pop_header_color"><?php esc_html_e('Room History','church_mgt');?></h4>
						<?php if(!empty($room_history))
						{
							?>
							<table class="table table-bordered margin_bottom_10px" width="100%" border="1" style="border-collapse:collapse;">
								<thead>
									<tr>
										<th class="text-left"><?php esc_html_e('Member','church_mgt');?></th>
										<th class="text-left"> <?php esc_html_e('Check-in Date','church_mgt');?></th>
										<!-- <th class="text-left"><?php esc_html_e('Check-out Date','church_mgt');?> </th> -->
										<th class="text-left"><?php esc_html_e('Expected Check Out Date','church_mgt');?> </th>
									</tr>
								</thead>
								<tbody>
									<?php 
									foreach($room_history as  $retrive_date)
									{
										if($obj_church->role == 'administrator' || $obj_church->role == 'accountant')
										{?>
											<tr>
									<td class="popup_label_value"><?php echo MJ_cmgt_church_get_display_name($retrive_date->member_id);?></td>
									<td class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrive_date->checkin_date));?></td>
									<td class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($retrive_date->checkout_date));?></td>
									</tr>
										<?php }
										elseif(get_current_user_id()==$retrive_date->member_id){
									?>
									<tr>
									<td><?php echo MJ_cmgt_church_get_display_name($retrive_date->member_id);?></td>
									<td><?php echo date(MJ_cmgt_date_formate(),strtotime($retrive_date->checkin_date));?></td>
									<td><?php echo date(MJ_cmgt_date_formate(),strtotime($retrive_date->checkout_date));?></td>
									</tr>
									<?php } }?>
								</tbody>
							</table>
							<?php
						}else
						{
							?>
							<p class="cmgt_pop_header_color"><?php esc_html_e('No History available','church_mgt');?></p>
							<?php
						} ?>
					</div>
				<?php 
			}
			
	}
	else
	{?>
		<p><?php esc_html_e('No Venue Data','church_mgt');?></p>
	<?php 
	}?>
				</div><!-- PANEL WHITE DIV END-->  
				</div><!-- PANEL WHITE DIV END-->  
		</div><!-- MODEl BODY DIV END-->
			<?php	die();
}
//-------- GIVE GIFT FUNCTION -----//
function MJ_cmgt_give_gifts()
{?>
	<script type="text/javascript">
		jQuery(document).ready(function($) 
		{
			$('#gift_form_function').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		} );
	</script>
	<div class="modal-header">
		<h4 class="modal-title w-auto float-start" id="myLargeModalLabel">
			<?php echo  esc_html_e('Give Gift To Member','church_mgt'); ?>
		</h4>
		<a href="#" class="close-btn badge badge-danger pull-right w-auto ms-auto mt-2 me-3 give_gift_top">X</a>
	</div>
	<hr>
	</hr>
		<div class="panel-white form-horizontal" >
		<form name="gift_form" style="height:auto;" action="" method="post" class="form-horizontal" id="gift_form_function">
			<input type="hidden" name="gift_id" value="<?php echo $_REQUEST['gift_id'];?>"  />
			<div class="row">
				<div class="col-md-9 input">
					<label class="ml-1 custom-top-label top" for="venue_name"><?php _e('Member','church_mgt');?><span class="require-field">*</span></label>								
					<select class="form-control validate[required] line_height_30px" name="member_id" id="member_list">	
						<option value=""><?php _e('Select Member','church_mgt');?></option>
						<?php $get_members = array('role' => 'member');
								$membersdata=get_users($get_members);
							if(!empty($membersdata))
							{
								foreach ($membersdata as $member){?>
									<option  value="<?php echo $member->ID;?>"><?php echo $member->display_name." - ".$member->member_id; ?> </option>
								<?php }
							}?>
					</select>
				</div>
				<div class="col-md-3">
					<div class="offset-sm-0 submit_btn">
						<input type="submit" value="<?php  _e('Give This Gift','church_mgt');?>" name="give_gift" class="btn btn-success col-md-12 save_btn save_btn_height_47px give_gift_member_sp"/>
					</div>
				</div>
			</div>
		</form>
	</div>

<?php die();
}
//-------- VIEW GIFT LIST FUNCTION -----//
function MJ_cmgt_view_gifts_list()
{
	$obj_gift=new Cmgtgift();
	
	$members_gift=$obj_gift->MJ_cmgt_get_members_gift($_REQUEST['member_id']);

	
	?> 
	<div class="modal-header">
		<h4 class="modal-title w-auto float-start" id="myLargeModalLabel">
			<?php echo  esc_html_e('Give Gift To Member','church_mgt'); ?>
		</h4>
		<a href="#" class="close-btn badge badge-danger pull-right w-auto ms-auto mt-2 me-3">X</a>
	</div>
	<hr>
	<div class="panel cmgt_panel_gift panel-white form-horizontal cmgt_checkin_popup">
		<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
			<thead >
			<tr>
				<th class="text-left"><?php _e('No','church_mgt');?></th>
				<th class="text-left"><?php _e('Gift Name','church_mgt');?></th>
				<th class="text-left"><?php _e('View','church_mgt');?></th>
			</tr>
			</thead>
			<tbody>
			<?php  
			if(!empty($members_gift))
			{
				$i=1;
				foreach($members_gift as $gift){?>
				<tr>
					<td class="popup_label_value"><?php echo $i;?></td>
					<td class="popup_label_value"><?php echo MJ_cmgt_get_gift_name($gift->gift_id);?></td>
					<td>
						<?php 
						$user_id=get_current_user_id();
						$current_user_info=get_userdata($user_id);						
						if($current_user_info->roles[0] == 'administrator') 
						{	
						?>
						<a  href="?page=cmgt-gifts&tab=view-gift&action=view-gift&gift_id=<?php echo $gift->gift_id;?>" class="btn btn-success"><?php _e('View','church_mgt');?></a>
						<?php 
						}
						else
						{?>
								<a  href="?church-dashboard=user&page=spiritual-gift&tab=view-gift&action=view-gift&gift_id=<?php echo $gift->gift_id;?>" class="btn btn-success"><?php _e('View','church_mgt');?></a>
					<?php 	
						} ?>
					</td>
				</tr>
			<?php 
				$i++;
				}
			}
			else
			{
				?>
				<tr>
				<td colspan="3" style="text-align:center"><?php _e('No Data Available','church_mgt'); ?></td>
				</tr>
				<?php
			}		
			?>
			</tbody>
		</table>
	</div>
<?php 
	die();
}
//-------- GET END DATE TOTAL AMOUNT FUNCTION -----//
function MJ_cmgt_get_enddate_total_amount()
{
	$total_amount=0;
	$end_date='';
	if($_REQUEST['period']=='one_time')
	{
		$end_date= sanitize_text_field($_REQUEST['start_date']);
		$total_amount=sanitize_text_field($_REQUEST['times_number'])*sanitize_text_field($_REQUEST['amount']);
	}
	if($_REQUEST['period']=='weekly')
	{
		$add_days=sanitize_text_field($_REQUEST['times_number'])*7;
		$end_date=date("Y-m-d", strtotime($_REQUEST['start_date']."+$add_days days"));
		$total_amount=sanitize_text_field($_REQUEST['times_number'])*sanitize_text_field($_REQUEST['amount']);
	}	
	if($_REQUEST['period']=='monthly')
	{
		$add_days=sanitize_text_field($_REQUEST['times_number'])*30;
		$end_date= date("Y-m-d", strtotime($_REQUEST['start_date']."+$add_days days"));
		$total_amount=sanitize_text_field($_REQUEST['times_number'])*sanitize_text_field($_REQUEST['amount']);
	}	
	if($_REQUEST['period']=='yearly')
	{
		$add_days=sanitize_text_field($_REQUEST['times_number'])*365;
		$end_date=date("Y-m-d", strtotime($_REQUEST['start_date']."+$add_days days"));
		$total_amount=sanitize_text_field($_REQUEST['times_number'])*sanitize_text_field($_REQUEST['amount']);
	}?>
	<!-- <div class="form-group">
		<div class="mb-3 row">	
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="end_date"><?php esc_html_e('End Date','church_mgt');?></label>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<input id="end_date" readonly class="form-control" type="text"  name="end_date" 
				value="<?php echo $end_date;?>">
			</div>	
		</div>	
	</div> -->

	<div class="col-md-6">
		<div class="form-group input">
			<div class="col-md-12 form-control">
					<input id="end_date" readonly class="form-control" type="text"  name="end_date" value="<?php echo $end_date;?>">
				<label class="" for="end_date"><?php esc_html_e('End Date','church_mgt');?></label>
			</div>
		</div>
	</div>

	<!-- <div class="form-group">
		<div class="mb-3 row">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label form-label" for="total_amount"><?php esc_html_e('Total Amount','church_mgt');?></label>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<input id="total_amount" readonly class="form-control" type="text"  name="total_amount" 
				value="<?php echo $total_amount;?>">
			</div>
		</div>
	</div>	 -->

	<div class="col-md-6">
		<div class="form-group input">
			<div class="col-md-12 form-control">
				<input id="total_amount" readonly class="form-control" type="text"  name="total_amount" 
				value="<?php echo $total_amount;?>">
				<label class="" for="total_amount"><?php esc_html_e('Total Amount','church_mgt');?></label>
			</div>
		</div>
	</div>

	<?php die();
}


//-------- VIEW INVOICE FUNCTION -----//
function MJ_cmgt_invoice_view()
{
	$obj_payment=new Cmgtpayment;
	$obj_gift=new Cmgtgift;
	$obj_pledges = new Cmgtpledes;
	if($_POST['invoice_type']=='income')
	{
		$income_data=$obj_payment->MJ_cmgt_get_income_data(sanitize_text_field($_POST['idtest']));
	}
	if($_POST['invoice_type']=='expense')
	{
		$expense_data=$obj_payment->MJ_cmgt_get_income_data(sanitize_text_field($_POST['idtest']));
	}
	if($_POST['invoice_type']=='transaction')
	{
		$transaction_data=MJ_cmgt_get_transaction_data(sanitize_text_field($_POST['idtest']));
	}
	if($_POST['invoice_type']=='sell_gift')
	{
		$sell_gift_data=$obj_gift->MJ_cmgt_get_single_sell_gift(sanitize_text_field($_POST['idtest']));
	}
	if($_POST['invoice_type']=='pledges')
	{
		$pledges_data=MJ_cmgt_get_pledges_data(sanitize_text_field($_POST['idtest']));
	}?>
	<div class="modal-header">
		<h4 class="modal-title w-auto float-start"><?php echo get_option('cmgt_system_name','church_mgt');?>
			
		</h4>
		<a href="#" class="close-btn badge badge-danger pull-right">X</a>
	</div>
	<div class="modal-body invoice_body" >
		<div id="invoice_print" class="invoice_print_main_div">
			<img class="invoicefont1 church_image rtl_invioce_img" style="vertical-align:top;background-repeat:no-repeat;" src="<?php echo plugins_url('/church-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div invoice_main_div_for_new_design" id="paitient_print">
				<table class="width_100 rtl_invioce_header" border="0">					
					<tbody>
						<tr>
							<td class="width_1">
								<img class="system_logo" src="<?php echo get_option( 'cmgt_church_other_data_logo' ); ?>">
							</td>							
							<td class="only_width_20">
								<table class="width_100" border="0">					
									<tbody>
										<tr>
											<td class="address_div">
												<label class="popup_label_heading width_20px_rs"><?php esc_html_e('Address','church_mgt');

												$address_length=strlen(get_option( 'cmgt_church_address' ));

												if($address_length>120)

												{

												?>

												<BR><BR><BR><BR><BR>

												<?php

												}

												elseif($address_length>90)

												{													

												?>

													<BR><BR><BR><BR>

												<?php												

												}

												elseif($address_length>60)

												{?>

													<BR><BR><BR>

												<?php

												}

												elseif($address_length>30)

												{?>

													<BR><BR>

												<?php

												}

												?>

												</label>&nbsp;&nbsp;
												<label for="" class="label_value">	<?php

													echo chunk_split(get_option( 'cmgt_church_address' ),42,"<BR>").""; 

												?></label>
											</td>
										</tr>
										<tr>
											<td class="address_div">
												<label class="popup_label_heading width_20px_rs"><?php esc_html_e('Email','church_mgt');?> </label>&nbsp;&nbsp;
												<label for="" class="label_value"><?php echo get_option( 'cmgt_email' ),"<BR>";  ?></label>
											</td>
										</tr>
										<tr>
											<td class="address_div">
												<label class="popup_label_heading width_20px_rs"><?php esc_html_e('Phone','church_mgt');?> </label>&nbsp;&nbsp;
												<label for="" class="label_value"><?php echo '+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_option( 'cmgt_contact_number' ),"<BR>";  ?></label>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td align="right" class="width_24">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_50 billing_information_div rtl_billing_information_div" border="0">
					<tbody>				
						<tr class="invoice_address_heading">
							<td colspan="2"  class="" style="display: flex;">								
								<h3 class="billed_to_lable invoice_model_bill_heading"><?php esc_html_e('Bill To','church_mgt');?> :</h3>
								<?php 
									if(!empty($expense_data))
									{
										echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($party_name=$expense_data->supplier_name),30,"<BR>"). "</h3>"; 
									}
									else
									{
										if(!empty($income_data))
											$member_id= sanitize_text_field($income_data->supplier_name);
										if(!empty($transaction_data))
											$member_id= sanitize_text_field($transaction_data->member_id);
										if(!empty($sell_gift_data))
											$member_id= sanitize_text_field($sell_gift_data->member_id);
										if(!empty($pledges_data))
											$member_id= sanitize_text_field($pledges_data->member_id);
										$patient=get_userdata($member_id);
										echo "<h3 style='font-weight: bold;'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>"; 
									}
								?>	
							</td>
							
						</tr>	
						<tr>
						<td class="width_40 address_information_invoice">								
							<?php 
								if(!empty($expense_data))
								{
								   echo "<h3 class='display_name invoice_width_100' style='font-weight: 400 !important;'>".chunk_split(ucwords($party_name=$expense_data->supplier_name),30,"<BR>"). "</h3>"; 
								}
								else
								{
									if(!empty($income_data))
										$member_id= sanitize_text_field($income_data->supplier_name);
									if(!empty($transaction_data))
										$member_id= sanitize_text_field($transaction_data->member_id);
									if(!empty($sell_gift_data))
										$member_id= sanitize_text_field($sell_gift_data->member_id);
									if(!empty($pledges_data))
										$member_id= sanitize_text_field($pledges_data->member_id);
									$patient=get_userdata($member_id);
									// echo "<h3 style='font-weight: bold;'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>"; 
									 $address=get_user_meta( $member_id,'address',true);
									 echo chunk_split($address,30,"<BR>"); 									 
									 echo get_user_meta( $member_id,'city_name',true )."<br>";
									echo '+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta( $member_id,'mobile',true )."<br>"; 
								}
							?>			
							</td>
						</tr>								
					</tbody>
				</table>
				<?php 
					$issue_date='DD-MM-YYYY';
					if(!empty($income_data))
					{
						$issue_date= sanitize_text_field($income_data->invoice_date);
						$payment_status=sanitize_text_field($income_data->payment_status);
						$invoice_no=sanitize_text_field($income_data->invoice_id);
						$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($income_data->invoice_id)); 
					}
					if(!empty($expense_data))
					{
						$issue_date=sanitize_text_field($expense_data->invoice_date);
						$payment_status=sanitize_text_field($expense_data->payment_status);
						$invoice_no=sanitize_text_field($expense_data->invoice_id);
						$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($expense_data->invoice_id)); 
					}
					if(!empty($transaction_data))
					{
						$issue_date=sanitize_text_field($transaction_data->created_date);
						$invoice_no=sanitize_text_field($transaction_data->id);
						$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($transaction_data->id)); 
					}
					if(!empty($sell_gift_data))
					{
						$issue_date=sanitize_text_field($sell_gift_data->sell_date);						
						$invoice_no=sanitize_text_field($sell_gift_data->id);
						$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($sell_gift_data->id)); 
					}
					if(!empty($pledges_data))
					{
						$issue_date=sanitize_text_field($pledges_data->created_date);						
						$invoice_no=sanitize_text_field($pledges_data->id);
						$invoice_number = esc_attr($obj_pledges->MJ_cmgt_generate_pledges_number($pledges_data->id)); 
					} 
					?>
					<table class="width_50 billing_information_div " border="0">
					<tbody>				
						<tr>	
						
							<td class="width_30 date_status_div" align="left">
								<?php
								if($_POST['invoice_type']!='expense')
								{
								?>	
									<h3 class="invoice_lable"  ><?php echo esc_html_e('INVOICE','church_mgt')." #".get_option( 'cmgt_payment_prefix' ).$invoice_number;?></h3>								
								<?php
								}
								?>
								<h5><?php 
										$issue_date=MJ_cmgt_date_formate();
											if(!empty($income_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($income_data->invoice_date));
											}
											if(!empty($expense_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($expense_data->invoice_date));
											}
											if(!empty($sell_gift_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));
											}
										  if(!empty($transaction_data)){
											$issue_date=date(MJ_cmgt_date_formate(),strtotime($transaction_data->transaction_date));
											}
										 if(!empty($pledges_data)){
											$issue_date=date(MJ_cmgt_date_formate(),strtotime($pledges_data->created_date));
											}
									?>
									<label class="popup_label_heading text-transfer-upercase"><?php echo esc_html_e('Date :','church_mgt') ?> </label>
									<label class="invoice_model_value"><?php echo $issue_date; ?></label></h5>
								<?php
								if($_POST['invoice_type']=='expense' || $_POST['invoice_type']=='income')
								{
								?>	
									<h5>
										<label class="popup_label_heading text-transfer-upercase"><?php echo esc_html_e('Status :','church_mgt') ?> </label>	
										<label class="invoice_model_value"><?php echo esc_html_e( $payment_status,'church_mgt'); ?></label></h5>								
								<?php
								}
								?>									
							</td>
							<td class="width_30">
							</td>							
						</tr>									
					</tbody>
				</table>
				<?php
				if($_POST['invoice_type']=='expense')
				{ 
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Expense Entries','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	
					
				<?php 	
				}				
				elseif($_POST['invoice_type']=='transaction')
				{ 
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Transaction Entries','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
				
				<?php 	
				}
				elseif($_POST['invoice_type']=='sell_gift')
				{ 
				   ?>
				   <table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Sell Gift','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
					
				  <?php
				}
				elseif($_POST['invoice_type']=='pledges')
				{ 
				   ?>
				   <table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Pledges Entries','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
					
				  <?php
				}
				else
				{ ?>
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Income Entries','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	
					 
				<?php 	
				}
			   ?>
				<table class="table model_invoice_table" class="width_93">
					<thead class="entry_heading invoice_model_entry_heading" style="background-color: #F2F2F2 !important;">
						<?php
						if($_POST['invoice_type']=='income' || $_POST['invoice_type']=='expense')
						{
						?>
						<tr>
							<th class="entry_table_heading">#</th>
							<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
							<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?> </th>
							<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
							<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
						</tr>
						<?php
						}
						elseif($_POST['invoice_type']=='transaction')
						{  
						?>
							<tr>
								<th class="entry_table_heading">#</th>
								<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
								<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?> </th>
								<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
								<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
							</tr>
						<?php 
						}
						elseif($_POST['invoice_type']=='sell_gift')
						{  
						?>
							<tr>
								<th class="entry_table_heading">#</th>
								<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
								<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?></th>
								<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
								<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
							</tr>
						<?php 
						}
						elseif($_POST['invoice_type']=='pledges')
						{  
						?>
							<tr>
								<th class="entry_table_heading">#</th>
								<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
								<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?> </th>
								<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
								<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
							</tr>
						<?php 
						}
						else
						{ 
						?>
							<tr>
								<th class="entry_table_heading">#</th>
								<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
								<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?> </th>
								<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
								<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
							</tr>	 
						<?php 
						}	
						?>
					</thead>
					<tbody class="invoice_model_table_body" style="border-bottom: 1px solid #E1E3E5 !important;">
					<?php 
						if(!empty($income_data) || !empty($expense_data))
						{
							$id=1;
							$total_amount=0;
							if(!empty($expense_data))
							$income_data=$expense_data;
							$church_all_income=$obj_payment->MJ_cmgt_get_single_income_data_by_invoice_id($income_data->invoice_id);
							// var_dump($church_all_income);
							// die;
							foreach($church_all_income as $result_income)
							{
								$income_entries=json_decode($result_income->entry);
								foreach($income_entries as $each_entry){
								$total_amount+=$each_entry->amount;
								$total_amount1=$each_entry->amount;?>
								<tr class="entry_list">
									<td class="invoice_table_data"><?php echo $id;?></td>
									<td class="invoice_table_data"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($result_income->invoice_date)));?></td>
									<td class="invoice_table_data"><?php echo esc_attr($each_entry->entry); ?> </td>
									<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_display_name(esc_attr($result_income->receiver_id));?></td>
									<td class="invoice_table_data align_right">  <span style="font-size:14px;"><?php echo number_format($total_amount1,2); ?> </span></td>
								</tr>
								<?php $id+=1;}
							}
						}
					?>
					<?php
					if(!empty($sell_gift_data))
					{
						$id=1;
						$total_amount=0;
						$total_amount=$sell_gift_data->gift_price;
						?>
						<tr class="entry_list">
							<td class="invoice_table_data"><?php echo $id;?></td>
							<td class="invoice_table_data"><?php echo date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));?></td>
							<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_gift_name($sell_gift_data->gift_id);?></td>
							<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_display_name($sell_gift_data->created_by); ?></td>
							<td class="invoice_table_data align_right"><?php echo number_format($sell_gift_data->gift_price,2); ?></td>

						</tr>
						<?php
					}
					?>
					<?php
					if(!empty($pledges_data))
					{
						$id=1;
						$total_amount=0;
						$total_amount=$pledges_data->total_amount;
						?>
						<tr class="entry_list">
							<td class="invoice_table_data"><?php echo $id;?></td>
							<td class="invoice_table_data"><?php echo date(MJ_cmgt_date_formate(),strtotime($pledges_data->start_date));?></td>
							<td class="invoice_table_data"><?php echo esc_html_e('Pledge','church_mgt');?></td>
							<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_display_name($pledges_data->created_by); ?></td>
							<td class="invoice_table_data align_right"><?php echo number_format($pledges_data->total_amount,2); ?></td>
						</tr>
						<?php
					}
					?>
					<?php
					if(!empty($transaction_data))
					{
						$id=1;
						$total_amount=0;
						$total_amount=$transaction_data->amount;
					?>
					<tr class="entry_list">
						<td class="invoice_table_data"><?php echo $id;?></td>
						<td class="invoice_table_data"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($transaction_data->transaction_date)));?></td>
						<td class="invoice_table_data"><?php echo get_the_title(esc_attr($transaction_data->donetion_type));?></td>
						<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_display_name(esc_attr($transaction_data->created_by));?></td>
						<td class="invoice_table_data align_right"><?php echo esc_attr($transaction_data->amount); ?></td>
					</tr>
				<?php } ?>			
					</tbody>
				</table>
				<table class="width_54" border="0">
					<tbody>
						<tr>
							<td class="width_70 align_right model_body_amount_label"><h4 class="margin"><?php esc_html_e('Subtotal :','church_mgt');?></h4></td>
							<td class="align_right model_body_amount_value"> <h4 class="margin"><span style=""><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?></span><?php echo number_format($total_amount,2);?></h4></td>
						</tr>
						<tr>
							<td class="width_56 align_right grand_total_lable" style="margin-right: 5px;"><h3 class="color_white margin"><?php esc_html_e('Grand Total','church_mgt');?></h3></td>
							<td class="align_left grand_total_amount"><h3 class="color_white margin"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?> <?php echo number_format($total_amount,2);?></h3></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row col-md-12 col-sm-12 col-xs-12 print-button pull-left invoice_print_pdf_btn">
		
			<div class="col-md-1 print_btn_rs">
				<a href="?page=cmgt-transactions&print=print&invoice_type=<?php echo esc_attr($_POST['invoice_type']);?>&idtest=<?php echo esc_attr($_POST['idtest']);?>" target="_blank" style="margin-right:15px;"class="btn print-btn print_btn_height rtl_print_icon_ml_15px"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/print.png" ?>" ></a>
			</div>
			<div class="col-md-1 pdf_btn_rs">
				<a href="?page=cmgt-transactions&invoicepdf=invoicepdf&invoice_type=<?php echo $_POST['invoice_type'];?>&idtest=<?php echo $_POST['idtest'];?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
			</div>
			
		</div>
	</div>
	<?php 
	die();
}
//-------- PRINT INIT FUNCTION -----//
function MJ_cmgt_print_init()
{
	if (is_user_logged_in ()) 
	{
		if(isset($_REQUEST['print']) && isset($_REQUEST['print']) == 'print' && isset($_REQUEST['page']) == 'cmgt-transactions' || isset($_REQUEST['page']) == 'cmgt-gifts' && isset($_REQUEST['print']) && $_REQUEST['print'] == 'print')
		{
		?>
		<script>window.onload = function(){ window.print(); };</script>
		<style>
			@media print 
{
  a[href]:after { content: none !important; }
  img[src]:after { content: none !important; }
}
		</style>
		<?php 	
			MJ_cmgt_invoice_print($_REQUEST['idtest']);
				exit;
		}
	}
	
}
add_action('init','MJ_cmgt_print_init');
function MJ_cmgt_invoice_print($id)	
{
	$obj_gift=new Cmgtgift;
	$obj_payment=new Cmgtpayment;
	$obj_pledges = new Cmgtpledes;
	if($_REQUEST['invoice_type']=='income'){
		$income_data=$obj_payment->MJ_cmgt_get_income_data(sanitize_text_field($_REQUEST['idtest']));
	}
	if($_REQUEST['invoice_type']=='expense'){
		$expense_data=$obj_payment->MJ_cmgt_get_income_data(sanitize_text_field($_REQUEST['idtest']));
	}
	if($_REQUEST['invoice_type']=='transaction')
	{
		$transaction_data=MJ_cmgt_get_transaction_data(sanitize_text_field($_REQUEST['idtest']));
	}
	if($_REQUEST['invoice_type']=='pledges')
	{
		$pledges_data=MJ_cmgt_get_pledges_data(sanitize_text_field($_REQUEST['idtest']));
	}
	if($_REQUEST['invoice_type']=='sell_gift')
	{
		$sell_gift_data=$obj_gift->MJ_cmgt_get_single_sell_gift(sanitize_text_field($_REQUEST['idtest']));
		
	}
	if(!empty($income_data))
		$member_id= sanitize_text_field($income_data->supplier_name);
	if(!empty($transaction_data))
		$member_id= sanitize_text_field($transaction_data->member_id);
	if(!empty($sell_gift_data))
		$member_id= sanitize_text_field($sell_gift_data->member_id);
	if(!empty($pledges_data))
		$member_id= sanitize_text_field($pledges_data->member_id);
	if(!empty($member_id))
		$patient=get_userdata($member_id);
	
	echo '<link rel="stylesheet"  type = "text/css" href="'.plugins_url( '/assets/css/style.css', __FILE__).'"></link>';
	echo '<link rel="stylesheet" type = "text/css" href="'.plugins_url( '/assets/css/custom.css', __FILE__).'"></link>';
	echo '<link rel="stylesheet" type = "text/css" href="'.plugins_url( '/assets/css/dynamic_css.php', __FILE__).'"></link>';
	echo '<link rel="stylesheet" type = "text/css" href="'.plugins_url( '/assets/css/new-design.css', __FILE__).'"></link>';
	echo '<link rel="stylesheet" type = "text/css" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap/bootstrap-rtl.min.css', __FILE__).'"></link>';
	?>
		<style>
			@import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');
		
			body, body * {
				font-family: 'Poppins' !important;
				}
			.col-md-1 {
				flex: 0 0 auto;
				width: 8.3333333333%;
			}
			.col-md-2 {
				flex: 0 0 auto;
				width: 16.6666666667%;
			}
			.col-md-3 {
				flex: 0 0 auto;
				width: 25%;
			}
			.col-md-4 {
				flex: 0 0 auto;
				width: 33.3333333333%;
			}
			.col-md-11 {
				flex: 0 0 auto;
				width: 91.6666666667%;
			}
			.col-md-8 {
				flex: 0 0 auto;
				width: 66.6666666667%;
			}
			.col-md-9 {
				flex: 0 0 auto;
				width: 75%;
			}
			.col-md-10 {
				flex: 0 0 auto;
				width: 83.3333333333%;
			}
			.padding_left_30px{
				padding-left:30px;	
			}
			.width_15{
				width :15% !important;
			}
			.width_80{
				width :80% !important;
			}
			.invoice_lable {
				background-color: #149a91 !important;
				color: #FFFFFF !important;
				padding: 10px!important;
				margin-top: 0px!important;
			}
			.view_grand_total_lable, .view_amount_label {
				width: 217px;
				padding: 5px;
			}
			h3.invoice_font{
				font-size: 16px;
				margin-right : 5px;
			}
			.margin_right_0px{
				margin-right: 0px;
			}
			.margin_top_10px{
				margin-top: 10px;
			}
			
		</style>
		
	<!-- <div class="modal-header">
		<h4 class="modal-title"><?php echo get_option('cmgt_system_name','church_mgt');?></h4>
	</div> -->
	<div class="modal-body invoice_body" >
		<div id="invoice_print1" class="invoice_print_main_div">
			<img class="invoicefont1" style="vertical-align:top;background-repeat:no-repeat;" src="<?php echo plugins_url('/church-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div invoice_main_div_for_new_design" id="paitient_print">
				<h4 class="modal-title margin_top_10px"><?php echo get_option('cmgt_system_name','church_mgt');?></h4>
				<div class="row padding_top_20px">
						<div class="col-md-1">
							<img class="system_logo width_64" src="<?php echo get_option( 'cmgt_church_other_data_logo' ); ?>">
						</div>
						<div class="col-md-11 padding_left_30px">
							<div class="row">
								<div class="col-md-1 popup_label_heading width_15"><?php esc_html_e('Email','church_mgt');?></div>
								<div class="col-md-11 label_value width_80"><?php echo ': '.get_option( 'cmgt_email' ); ?></div>
								<div class="col-md-1 popup_label_heading width_15"><?php esc_html_e('Phone','church_mgt');?></div>
								<div class="col-md-11 label_value width_80"><?php echo ':  +'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_option( 'cmgt_contact_number' );  ?></div>
								<div class="col-md-1 popup_label_heading width_15"><?php esc_html_e('Address','church_mgt');?></div>
								<div class="col-md-11 label_value width_80"><?php echo ': '.get_option( 'cmgt_church_address' ); ?></div>
							</div>
						</div>
					</div>
					<div class="row padding_top_40px">
						<div class="col-md-8">
							<div class="row">
								<div class="col-md-1 popup_label_heading width_15"><?php esc_html_e('Bill To','church_mgt');?></div>
								<div class="col-md-11 label_value width_80">
									<?php
									if(!empty($expense_data))
									{
										$party_name=$expense_data->supplier_name;
										echo ': '.$party_name;
									}else{
										echo ': '.$patient->display_name;
									}
									?>
								</div>
								<?php
								if(empty($expense_data))
								{ ?>
									<div class="col-md-1 popup_label_heading width_15"><?php esc_html_e('Address','church_mgt');?></div>
									<div class="col-md-11 label_value width_80"><?php echo ': '.get_user_meta( $member_id,'address',true); ?></div>
									<div class="col-md-1 popup_label_heading width_15"><?php esc_html_e('Phone','church_mgt');?></div>
									<div class="col-md-11 label_value width_80"><?php echo ': +'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta( $member_id,'mobile',true ); ?></div>
									<?php
								} ?>
								</div>
						</div>

						<div class="col-md-4">
							<div class="row margin_right_0px">
								<?php
									$issue_date='DD-MM-YYYY';
									if(!empty($income_data))
									{
										$issue_date= sanitize_text_field($income_data->invoice_date);
										$payment_status=sanitize_text_field($income_data->payment_status);
										$invoice_no=sanitize_text_field($income_data->invoice_id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($income_data->invoice_id)); 
									}
									if(!empty($expense_data))
									{
										$issue_date=sanitize_text_field($expense_data->invoice_date);
										$payment_status=sanitize_text_field($expense_data->payment_status);
										$invoice_no=sanitize_text_field($expense_data->invoice_id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($expense_data->invoice_id)); 
									}
									if(!empty($transaction_data))
									{
										$issue_date=sanitize_text_field($transaction_data->created_date);
										$invoice_no=sanitize_text_field($transaction_data->id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($transaction_data->id)); 
									}
									if(!empty($sell_gift_data))
									{
										$issue_date=sanitize_text_field($sell_gift_data->sell_date);						
										$invoice_no=sanitize_text_field($sell_gift_data->id);
										$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($sell_gift_data->id)); 
									}
									if(!empty($pledges_data))
									{
										$issue_date=sanitize_text_field($pledges_data->created_date);						
										$invoice_no=sanitize_text_field($pledges_data->id);
										$invoice_number = esc_attr($obj_pledges->MJ_cmgt_generate_pledges_number($pledges_data->id)); 
									}
									if($_REQUEST['invoice_type']!='expense')
									{
										?>	
											<h3 class="invoice_color invoice_font" ><?php echo esc_html_e('INVOICE','church_mgt')." #".get_option( 'cmgt_payment_prefix' ).$invoice_number;?></h3>								
										<?php
									}
									?>
									<h5>
										<?php 
											$issue_date=MJ_cmgt_date_formate();
												if(!empty($income_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($income_data->invoice_date));
												}
												if(!empty($expense_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($expense_data->invoice_date));
												}
												if(!empty($sell_gift_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));
												}
											if(!empty($transaction_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($transaction_data->transaction_date));
												}
											if(!empty($pledges_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($pledges_data->created_date));
												}
										?>
										<label class="popup_label_heading text-transfer-upercase"><?php echo esc_html_e('Date :','church_mgt') ?> </label>
										<label class="invoice_model_value"><?php echo $issue_date; ?></label>
									</h5>
									<?php
									if($_REQUEST['invoice_type']=='expense' || $_REQUEST['invoice_type']=='income')
									{
										?>	
										<h5>
											<label class="popup_label_heading text-transfer-upercase"><?php echo esc_html_e('Status :','church_mgt') ?> </label>	
											<label class="invoice_model_value"><?php echo esc_html_e( $payment_status,'church_mgt'); ?></label>
										</h5>								
										<?php
									}
								?>
							</div>
						</div>
					</div>
			<?php

				if($_REQUEST['invoice_type']=='expense')
				{ 
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Expense Entries','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	
					
				<?php 	
				}				
				elseif($_REQUEST['invoice_type']=='transaction')
				{ 
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Transaction Entries','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
				
				<?php 	
				}
				elseif($_REQUEST['invoice_type']=='sell_gift')
				{ 
				   ?>
				   <table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Sell Gift','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
					
				  <?php
				}
				elseif($_REQUEST['invoice_type']=='pledges')
				{ 
				   ?>
				   <table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Pledges Entries','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
					
				  <?php
				}
				else
				{ ?>
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Income Entries','church_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	    
					 
				<?php 	
				}
			   ?>
				<table class="table model_invoice_table" style="float: left;width: 100%;" class="width_93">
					<thead class="entry_heading invoice_model_entry_heading">
						<?php
						if($_REQUEST['invoice_type']=='income' || $_REQUEST['invoice_type']=='expense')
						{
						?>
						<tr>
							<th class="entry_table_heading padding_10_px text-aline-center">#</th>
							<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Date','church_mgt');?></th>
							<th class="entry_table_heading padding_10_px text-aline-center"><?php esc_html_e('Description','church_mgt');?> </th>
							<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Issue By','church_mgt');?> </th>
							<th class="entry_table_heading padding_10_px align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
						</tr>
						<?php
						}
						elseif($_REQUEST['invoice_type']=='transaction')
						{  
						?>
							<tr>
								<th class="entry_table_heading padding_10_px text-aline-center">#</th>
								<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Date','church_mgt');?></th>
								<th class="entry_table_heading padding_10_px text-aline-center"><?php esc_html_e('Description','church_mgt');?> </th>
								<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Issue By','church_mgt');?> </th>
								<th class="entry_table_heading padding_10_px align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
							</tr>
						<?php 
						}
						elseif($_REQUEST['invoice_type']=='sell_gift')
						{  
						?>
							<tr>
								<th class="entry_table_heading padding_10_px text-aline-center">#</th>
								<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Date','church_mgt');?></th>
								<th class="entry_table_heading padding_10_px text-aline-center"><?php esc_html_e('Description','church_mgt');?></th>
								<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Issue By','church_mgt');?> </th>
								<th class="entry_table_heading padding_10_px align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
							</tr>
						<?php 
						}
						elseif($_REQUEST['invoice_type']=='pledges')
						{  
						?>
							<tr>
								<th class="entry_table_heading padding_10_px text-aline-center">#</th>
								<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Date','church_mgt');?></th>
								<th class="entry_table_heading padding_10_px text-aline-center"><?php esc_html_e('Description','church_mgt');?> </th>
								<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Issue By','church_mgt');?> </th>
								<th class="entry_table_heading padding_10_px align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
							</tr>
						<?php 
						}
						else
						{ 
						?>
							<tr>
								<th class="entry_table_heading padding_10_px text-aline-center">#</th>
								<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Date','church_mgt');?></th>
								<th class="entry_table_heading padding_10_px text-aline-center"><?php esc_html_e('Description','church_mgt');?> </th>
								<th class="entry_table_heading padding_10_px text-aline-center"> <?php esc_html_e('Issue By','church_mgt');?> </th>
								<th class="entry_table_heading padding_10_px align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
							</tr>	 
						<?php 
						}	
						?>
					</thead>
					<tbody class="invoice_model_table_body" style="border-bottom: 1px solid #E1E3E5 !important;">
					<?php 
						if(!empty($income_data) || !empty($expense_data))
						{
							$id=1;
							$total_amount=0;
							if(!empty($expense_data))
							$income_data=$expense_data;
							$church_all_income=$obj_payment->MJ_cmgt_get_single_income_data_by_invoice_id($income_data->invoice_id);
							foreach($church_all_income as $result_income)
							{
								$income_entries=json_decode($result_income->entry);
								foreach($income_entries as $each_entry){
								$total_amount+=$each_entry->amount;
								$total_amount1=$each_entry->amount;?>
								<tr class="entry_list">
									<td class="invoice_table_data text-aline-center"><?php echo $id;?></td>
									<td class="invoice_table_data text-aline-center"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($result_income->invoice_date)));?></td>
									<td class="invoice_table_data text-aline-center"><?php echo esc_attr($each_entry->entry); ?> </td>
									<td class="invoice_table_data text-aline-center"><?php echo MJ_cmgt_church_get_display_name(esc_attr($result_income->receiver_id));?></td>
									<td class="invoice_table_data align_right">  <span style="font-size:14px;"><?php echo number_format($total_amount1,2); ?> </span></td>
								</tr>
								<?php $id+=1;}
							}
						}
					?>
					<?php
					if(!empty($sell_gift_data))
					{
						$id=1;
						$total_amount=0;
						$total_amount=$sell_gift_data->gift_price;
						?>
						<tr class="entry_list">
							<td class="invoice_table_data text-aline-center"><?php echo $id;?></td>
							<td class="invoice_table_data text-aline-center"><?php echo date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));?></td>
							<td class="invoice_table_data text-aline-center"><?php echo MJ_cmgt_church_get_gift_name($sell_gift_data->gift_id);?></td>
							<td class="invoice_table_data text-aline-center"><?php echo MJ_cmgt_church_get_display_name($sell_gift_data->created_by); ?></td>
							<td class="invoice_table_data align_right"><?php echo number_format($sell_gift_data->gift_price,2); ?></td>

						</tr>
						<?php
					}
					?>
					<?php
					if(!empty($pledges_data))
					{
						$id=1;
						$total_amount=0;
						$total_amount=$pledges_data->total_amount;
						// var_dump($total_amount); die;
						?>
						<tr class="entry_list">
							<td class="invoice_table_data text-aline-center"><?php echo $id;?></td>
							<td class="invoice_table_data text-aline-center"><?php echo date(MJ_cmgt_date_formate(),strtotime($pledges_data->start_date));?></td>
							<td class="invoice_table_data text-aline-center"><?php echo esc_html_e('Pledge','church_mgt');?></td>
							<td class="invoice_table_data text-aline-center"><?php echo MJ_cmgt_church_get_display_name($pledges_data->created_by); ?></td>
							<td class="invoice_table_data align_right"><?php echo number_format($pledges_data->total_amount,2); ?></td>
						</tr>
						<?php
					}
					?>
					<?php
					if(!empty($transaction_data))
					{
						$id=1;
						$total_amount=0;
						$total_amount=$transaction_data->amount;
					?>
					<tr class="entry_list">
						<td class="invoice_table_data text-aline-center"><?php echo $id;?></td>
						<td class="invoice_table_data text-aline-center"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($transaction_data->transaction_date))); ?></td>
						<td class="invoice_table_data text-aline-center"><?php echo get_the_title(esc_attr($transaction_data->donetion_type));?></td>
						<td class="invoice_table_data text-aline-center"><?php echo MJ_cmgt_church_get_display_name(esc_attr($transaction_data->created_by));?></td>
						<td class="invoice_table_data align_right"><?php echo esc_attr($transaction_data->amount); ?></td>
					</tr>
				<?php } ?>			
					</tbody>
				</table>
				<table class="width_54" style="background-color: <?php echo get_option('cmgt_system_color_code');?>" border="0">
					<tbody>
						<tr>
							<td class="align_right model_body_amount_label view_amount_label"><h4 class="margin"><?php esc_html_e('Subtotal :','church_mgt');?></h4></td>
							<td class="align_right model_body_amount_value"> <h4 class="margin"><span style=""><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?></span><?php echo number_format($total_amount,2); ?></h4></td>
						</tr>
						<tr>
							<td class="align_right grand_total_lable1 view_grand_total_lable" style="margin-right: 5px; background-color:<?php echo get_option('cmgt_system_color_code');?>;"><h4 class="color_white margin"><?php esc_html_e('Grand Total :','church_mgt');?></h4></td>
							<td class="align_right grand_total_amount1" style="background-color:<?php echo get_option('cmgt_system_color_code');?>;"><h5 class="color_white margin"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).''.number_format($total_amount,2);?></h5></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php die();
}
//-------- VIEW GROUP MEMBER FUNCTION -----//
function MJ_cmgt_group_member_view()
{
	$group_id = $_REQUEST['group_id'];
	$group_type ='';

	if(isset($_REQUEST['group_type']))
		$group_type = $_REQUEST['group_type'];
	$obj_group=new Cmgtgroup;
	if($group_type == 'ministry')
	{
		$allmembers =$obj_group->MJ_cmgt_get_ministry_members($group_id);
		
	}
	else
	{
		$allmembers =$obj_group->MJ_cmgt_get_group_members($group_id);
	}
	?>
    <div class="form-group">
		<a href="#" class="close-btn badge badge-danger pull-right mt-2">X</a>
		<h4 class="modal-title" id="myLargeModalLabel">
			<?php if($group_type =='ministry')
					echo  __('Ministry Member','church_mgt'); 
				  else
					echo  __('Group Member','church_mgt'); ?>
		</h4>
	</div>
	<hr>
	<div class="panel-body1">
		<div class="slimScrollDiv addScroll">
			<div class="inbox-widget slimscroll">
			<?php 
			if(!empty($allmembers))
			foreach ($allmembers as $retrieved_data)
			{
				?>
				<div class="inbox-item d-flex" id="cat-<?php echo $retrieved_data->member_id;?>">
					<div class="inbox-item-img">
						<?php 
							$uid=$retrieved_data->member_id;
							
							$userimage=get_user_meta($uid, 'cmgt_user_avatar', true);
							if(empty($userimage))
							{
								echo '<img src='.get_option( 'cmgt_member_thumb' ).' height="50px" width="50px" class="img-circle" />';
							}
							else
								echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';	
						?>
					</div>
					<p class="col-sm-9 inbox-item-author"><?php echo MJ_cmgt_church_get_display_name($retrieved_data->member_id);?></p>
					<?php 					
					$user_id=get_current_user_id();
					$current_user_info=get_userdata($user_id);					
					if($current_user_info->roles[0] == 'administrator') 
					{			
						?>
						<p class="col-sm-2 delete_btn_groupmember">
							<a id="delete_groupmember" class="delete_group_member cmgt_group_member_dlt_btn" group_id=<?php echo $group_id;?> mem_id="<?php echo $retrieved_data->member_id;?>" member_groputype="<?php echo $retrieved_data->type;?>" type="button"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Delete.png"?>" alt="" class="massage_image center"> </a>
						</p>
						<?php 
					} ?>
				</div>
				<?php 
			}
			else 
			{
				?>
				<p><?php esc_html_e('No any member yet.','church_mgt');?></p>
				<?php
			} 
			?>
			</div>
		</div>
		<?php
		$user_id=get_current_user_id();
		
		$current_user_info=get_userdata($user_id);						
		if($current_user_info->roles[0] == 'administrator') 
		{	
			?>
		<div class="print-button pull-left">
			<a  href="#" class="btn btn-success add_group_member"  id="<?php echo $group_id;?>" group_type="<?php echo $group_type; ?>" ><?php esc_html_e('Add Members','church_mgt');?></a>

		</div>
		<?php } ?>
	</div>
	<?php 
?>
<?php 
	die();
}
//-------- ADD GROUP MEMBER FUNCTION -----//
function MJ_cmgt_group_member_add()
{
	$group_id = $_REQUEST['group_id'];
	$group_type =$_REQUEST['group_type'];
	?>
	<script type="text/javascript">
		$(document).ready(function() 
		{
			var table =  jQuery('#members_list').DataTable({
			 'order': [2, 'asc'],
			 //"responsive": true,
			 "dom": 'lifrtp',
			 "aoColumns":[
							  {"bSortable": false},
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true}],
					language:<?php echo MJ_cmgt_datatable_multi_language();?>
			   });
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'church_mgt');?>");
			$('#example-select-all').on('click', function(){
			  var rows = table.rows({ 'search': 'applied' }).nodes();
			  $('input[type="checkbox"]', rows).prop('checked', this.checked);
		   });
		   $('#members_list tbody').on('change', 'input[type="checkbox"]', function(){
			  if(!this.checked){
				 var el = $('#example-select-all').get(0);
				 if(el && el.checked && ('indeterminate' in el)){
					el.indeterminate = true;
				 }
			  }
		   });
		   
			$('#add_group_member').on('click', function(){
				
				$('#frm-example').submit();
			});
		} );
</script>
	<div class="form-group"> 	
		<a href="#" class="close-btn badge badge-danger pull-right mt-2">X</a>
		<h4 class="modal-title" id="myLargeModalLabel">
			<?php if($group_type=='ministry')
				
					echo  esc_html_e('Add Ministry Member','church_mgt'); 
				  else
					echo  esc_html_e('Add Group Member','church_mgt'); ?>
		</h4>
	</div>
	
	<hr>
	<div class="panel-body">
		<div class="slimScrollDiv addScroll inbox-widget slimscroll">
			<?php
			$get_members = array('role' => 'member');
						
			$membersdata=get_users($get_members);
		
			$obj_group=new Cmgtgroup;
			
			$get_member_data = $obj_group->MJ_cmgt_get_group_members_id($group_id,$group_type);
			$id_array = array_column($get_member_data, 'member_id');
			if(!empty($membersdata))
			{
				?>
				<form id="frm-example" name="frm-example" method="post">
					<table id="members_list" class="display " cellspacing="0" width="100%" >
						<tbody>
							<?php 
							
							foreach ($membersdata as $retrieved_data)
							{
								if (!in_array($retrieved_data->ID, $id_array))
								{ 
									?>
									<tr>
										<!-- <td class="cmgt-checkbox_width_10px">
											<input type="checkbox" name="id[]" class="validate[minCheckbox[1]] checkbox" value="<?php echo $retrieved_data->ID;?>">
											<input type="hidden" name="group_id" value="<?php echo $_REQUEST['group_id']?>">
										</td> -->
										<td class="cmgt-checkbox_width_10px">
											<input type="checkbox" name="id[]" class="checkbox" value="<?php echo $retrieved_data->ID;?>">
											<input type="hidden" name="group_id" value="<?php echo $_REQUEST['group_id']?>">
										</td>
										<td class="user_image cmgt-checkbox_width_50px"><?php $uid=$retrieved_data->ID;
												$userimage=get_user_meta($uid, 'cmgt_user_avatar', true);
											if(empty($userimage))
											{
												echo '<img src='.get_option( 'cmgt_member_thumb' ).' height="50px" width="50px" class="img-circle" />';
											}
											else
												echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
										?></td>
										<td class="name"><a href="#" class="color_black"><?php echo $retrieved_data->display_name;?>  </a> </td>
										<td class="memberid"><?php echo $retrieved_data->member_id;?> </td>
									</tr>
									<?php 
								} 
							}
							?>	
						</tbody>
					</table>
					<div class="print-button pull-left cmgt_print_btn_p0">
						<button class="btn btn-success btn-niftyhms">
							<input type="checkbox" name="select_all" id="example-select-all"  style="margin-top: 0px;">
							<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'church_mgt' ) ;?></label>
						</button>
					</div>
				</form>
				<script>
  						  // Function to validate the form before submission
						function validateForm() {
							// Get all the checkboxes with the class "checkbox"
							var checkboxes = document.getElementsByClassName("checkbox");

							// Check if at least one checkbox is checked
							var checked = false;
							for (var i = 0; i < checkboxes.length; i++) {
								if (checkboxes[i].checked) {
									checked = true;
									break;
								}
							}

							// Display an alert if no checkbox is checked and return false to prevent form submission
							if (!checked) {
								alert("Please select at least one Member.");
								return false;
							}

							// If at least one checkbox is checked, the form will be submitted
							return true;
						}
					</script>

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
			?>
		</div>
		<div class="print-button pull-left">
			<input id="add_group_member" onclick="return validateForm();" type="submit" value="<?php if($edit){ esc_html_e('Save','church_mgt'); }else{ esc_html_e('Add Member','church_mgt');}?>" name="add_member" class="btn btn-success add_group_member"/>
		</div>
	</div>
	<?php 
?>
<?php 
	die();
}
//-------- REMOVE GROUP MEMBER FUNCTION -----//
function MJ_cmgt_remove_group_member()
{
	$id=$_REQUEST['member_id'];
	$group_id=$_REQUEST['group_id'];
	$obj_group=new Cmgtgroup;
	$obj_group->MJ_cmgt_delete_member_from_group($id,$group_id);
    $obj_ministry=new Cmgtministry;
	$obj_ministry->MJ_cmgt_delete_member_from_group($id,$group_id);
	die();
}
//-------- GET CURRENCYSYMBOL FUNCTION -----//
function MJ_cmgt_getCurrencySymbol($locale, $currency)
{
    // Create a NumberFormatter
    $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

    // Prevent any extra spaces, etc. in formatted currency
    $formatter->setPattern('¤');

    // Prevent significant digits (e.g. cents) in formatted currency
    $formatter->setAttribute(NumberFormatter::MAX_SIGNIFICANT_DIGITS, 0);

    // Get the formatted price for '0'
    $formattedPrice = $formatter->formatCurrency(0, $currency);

    // Strip out the zero digit to get the currency symbol
    $zero = $formatter->getSymbol(NumberFormatter::ZERO_DIGIT_SYMBOL);
    $currencySymbol = str_replace($zero, '', $formattedPrice);
    return $currencySymbol;
}
// SEND EMAIL FOR NOTIFICATIONS WITH HTML CONTENT 
function  MJ_cmgt_cmgSendEmailNotificationWithHTML($to, $subject, $message_content)
{
	$church_name=get_option('cmgt_system_name');
	$headers="";
	$headers .= 'From: '.$church_name.' <noreplay@gmail.com>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
	//$headers .= "Content-Type: multipart/alternative; boundary='boundary2'"; 
	$headers .= "Content-Transfer-Encoding: base64\r\n"; 
	$enable_notofication=get_option('cmgt_enable_notifications');
	if($enable_notofication=='yes'){
		wp_mail($to, $subject, $message_content,$headers); 
	}
}
// SEND EMAIL FOR NOTIFICATIONS WITH TEXT CONTENT  
function MJ_cmgt_SendEmailNotification($to, $subject, $message_content)
{
	$church_name=get_option('cmgt_system_name');
	$headers="";
	$headers .= 'From: '.$church_name.' <noreplay@gmail.com>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
	 $enable_notofication=get_option('cmgt_enable_notifications');
	 if($enable_notofication=='yes'){
          wp_mail($to, $subject, $message_content,$headers); 
	 }
}

function MJ_cmgt_send_transaction_send_mail_html_content($id,$invoice_type)
{
	
	$currency_symbol=MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
	
   $obj_payment=new Cmgtpayment;
   $obj_gift=new Cmgtgift;
	if($invoice_type=='income')
	{
		$income_data=$obj_payment->MJ_cmgt_get_income_data($id);
	}

	if($invoice_type=='expense')
	{
		$expense_data=$obj_payment->MJ_cmgt_get_income_data($id);
	}
	if($invoice_type=='transaction')
	{
		$transaction_data=MJ_cmgt_get_transaction_data($id);
	}
	if($invoice_type=='sell_gift')
	{
		$sell_gift_data=$obj_gift->MJ_cmgt_get_single_sell_gift($id);
	}
	if($invoice_type=='pledges')
	{
		$pledges_data=MJ_cmgt_get_pledges_data($id);
	}
	//$logo=get_option("cmgt_system_logo");
	
		$message="";
		$message.='<div class="modal-header">
			<h4 class="modal-title">'. get_option("cmgt_system_name").'</h4>
			</div>
			<div class="modal-body" style="height:500px;overflow:auto;">
				<div id="invoice_print"> 
					<table width="100%" border="0">
						<tbody>         
							<tr>
								<td width="70%">';
									/* <img style="max-height:80px;" src="'.get_option("cmgt_system_logo").'"> */
								$message.='</td>
								<td align="right" width="24%">
									<h5>'; 
									$issue_date='DD-MM-YYYY';
											  $payment_status='';
											  
												if(!empty($income_data)){
												 $issue_date=$income_data->invoice_date;
												$payment_status=$income_data->payment_status;
												}
												if(!empty($expense_data)){
													$issue_date=$expense_data->invoice_date;
													$payment_status=$expense_data->payment_status;
												}
											  	if(!empty($transaction_data)){
													$issue_date=$transaction_data->transaction_date;
												}
												if(!empty($sell_gift_data)){
													$issue_date=$sell_gift_data->sell_date;
												}
											 	if(!empty($pledges_data)){
													$issue_date=$pledges_data->created_date;
												} 
									 $message.=' '.esc_html_e("Issue Date","church_mgt").' : '.$issue_date.'</h5> ';
									 if($payment_status!=''){ 
									 $message.='<h5> '.esc_html_e("Status","church_mgt").' : '.$payment_status .'</h5>';
									 } 
								$message.='</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td align="left">
									<h4> '.esc_html_e("Payment To","church_mgt").'  </h4>
								</td>
								<td align="right">
									<h4>'. esc_html_e("Bill To","church_mgt") .'</h4>
								</td>
							</tr>
							<tr>
								<td valign="top" align="left">';
									 $message.=get_option('cmgt_system_name')."<br>";
									 $message.=get_option('cmgt_church_addres').",";
									 $message.=get_option('cmgt_contry')."<br>";
									 $message.=get_option('cmgt_contact_number')."<br>";
									
								$message.='</td>
								<td valign="top" align="right">';
									if(!empty($expense_data)){
									  $message.=$party_name=$expense_data->supplier_name;
									}
									else
									{			
										if(!empty($income_data))
											$member_id=$income_data->supplier_name;
										if(!empty($transaction_data))
											$member_id=$transaction_data->member_id;
										if(!empty($sell_gift_data))
											$member_id=$sell_gift_data->member_id;
										if(!empty($pledges_data))
											$member_id=$pledges_data->member_id;
										 $message.= MJ_cmgt_church_get_display_name($member_id)."<br>"; 
										$message.= get_user_meta( $member_id,'address',true ).","; 
										$message.=get_user_meta( $member_id,'city_name',true ).","; 
										$message.= '+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta( $member_id,'mobile',true )."<br>"; 
									}
									
								$message.='</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<h4>'. esc_html_e("Invoice Entries","church_mgt") .'</h4>
					<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">  '.esc_html_e("Date","church_mgt").'</th>
								<th width="60%">'.esc_html_e("Entry","church_mgt") .' </th>
								<th> '.esc_html_e("Amount","church_mgt").'</th>
								<th class="text-center">  '.esc_html_e("Issue By","church_mgt").' </th>
							</tr>
						</thead>
						<tbody>';
						
						
							if(!empty($income_data) || !empty($expense_data))
							{
							$id=1;
							$total_amount=0;
							
							if(!empty($expense_data))
								$income_data=$expense_data;
							
							$church_all_income=$obj_payment->MJ_cmgt_get_oneparty_income_data($income_data->supplier_name);
							foreach($church_all_income as $result_income){
								
								$income_entries=json_decode($result_income->entry);
								
								foreach($income_entries as $each_entry){
								$total_amount+=$each_entry->amount; 
								
							$message.='<tr>
								<td class="text-center">'. $id .'</td>
								<td class="text-center"> '. $result_income->invoice_date .'</td>
								<td>'. $each_entry->entry .' </td>
								<td class="text-right"> '. $currency_symbol .'  '. $each_entry->amount .'</td>
								<td class="text-center">'.MJ_cmgt_church_get_display_name($result_income->receiver_id) .'</td>
							</tr>';
								 $id+=1;}
								}
							}
							else
							{
									$id=1;
									$total_amount=0;
								if(!empty($transaction_data))
									$total_amount=$transaction_data->amount;
								if(!empty($pledges_data))
									$total_amount=$pledges_data->total_amount;
								if(!empty($sell_gift_data))
									$total_amount=$sell_gift_data->gift_price;
								
								 $message.='<tr>
										<td class="text-center">'. $id.'</td>
										
										<td class="text-center">';
										 if(!empty($transaction_data)){ $message.= $transaction_data->transaction_date; } if(!empty($pledges_data)){ echo $pledges_data->start_date; } if(!empty($sell_gift_data)){ echo $sell_gift_data->sell_date;}
										$message.=' </td>
										<td>'; if(!empty($sell_gift_data)){
											$message.=MJ_cmgt_church_get_gift_name($sell_gift_data->gift_id);
										}
										
										$donetion=$transaction_data->donetion_type;
										   
										if($donetion!="")  {
										$title=get_the_title($donetion);
										$message.= $title;
										}
										else {
											$message.= esc_html_e('Donation','church_mgt');
										}
											
										 $message.=' </td>
										<td class="text-right"> ';
										 if(!empty($transaction_data)){ 
													$message.= $currency_symbol .' '. $transaction_data->amount;
											 }
											 if(!empty($sell_gift_data)){
												 $message.= $currency_symbol .' '. $sell_gift_data->gift_price;
											 }
											 if(!empty($pledges_data)){ $message.= $currency_symbol .' '. $pledges_data->total_amount;} $message.='</td>
										<td class="text-center">';
										 if(!empty($transaction_data)){ $message.= MJ_cmgt_church_get_display_name($transaction_data->created_by); } if(!empty($sell_gift_data)){ echo MJ_cmgt_church_get_display_name($sell_gift_data->created_by); } if(!empty($pledges_data)) { echo MJ_cmgt_church_get_display_name($pledges_data->created_by); }
										 $message.='</td>
									</tr>';
							 } 		
						$message.='</tbody>
					</table>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td width="80%" align="right">'. esc_html_e("Grand Total","church_mgt").'</td>
								<td align="right"><h4> '. $currency_symbol .' '.$total_amount .' </h4></td>
							</tr>
						</tbody>
					</table>
				</div>
				
			</div>';
			return $message;
}
  // LOAD DOCUMENTS 
function MJ_cmgt_load_documets($file,$type,$nm) 
{
	$imagepath =$file;     
	$parts = pathinfo($_FILES[$type]['name']);
	$inventoryimagename = time()."-".$nm."-"."in".".".$parts['extension'];
		
	$upload_dir = wp_upload_dir(); 
	$document_dir = ''.$upload_dir['path'].'/church_assets/';
	
		$document_path = $document_dir;
	if($imagepath != "")
	{	
	if(file_exists(WP_CONTENT_DIR.$document_dir.$imagepath['name']))
	unlink(WP_CONTENT_DIR.$document_dir.$imagepath['name']);
	}
	if (!file_exists($document_path)) 
	{
		mkdir($document_path, 0777, true);
	} 	
	   if (move_uploaded_file($_FILES[$type]['tmp_name'], $document_path.$inventoryimagename)) {
		  $imagepath= $inventoryimagename;	
	   }
	$upload_dir_url=MJ_cmgt_upload_url_path('church_assets'); 
	$imageurl = $upload_dir_url.''.$imagepath;	
	return $imageurl;
}
//-------- CHECK VALID EXTENSTION FUNCTION -----//
function MJ_cmgt_check_valid_extension($filename)
{
	$flag = 2; 
	if($filename != '')
	{
		$flag = 0;
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$valid_extension = ['gif','png','jpg','jpeg',""];
		if(in_array($ext,$valid_extension) )
		{
		$flag = 1;
		}
	}
	return $flag;
}
//-------- GET CURRENCY SYMBOL FUNCTION -----//
function MJ_cmgt_get_currency_symbol( $currency = '' ) 
{			

		switch ( $currency ) {
		case 'AED' :
		$currency_symbol = 'د.إ';
		break;
		case 'AUD' :
		$currency_symbol = '&#36;';
		break;
		case 'CAD' :
		$currency_symbol = 'C&#36;';
		break;
		case 'CLP' :
		case 'COP' :
		case 'HKD' :
		$currency_symbol = '&#36';
		break;
		case 'MXN' :
		$currency_symbol = '&#36';
		break;
		case 'NZD' :
		$currency_symbol = '&#36';
		break;
		case 'SGD' :
		case 'USD' :
		$currency_symbol = '&#36;';
		break;
		case 'BDT':
		$currency_symbol = '&#2547;&nbsp;';
		break;
		case 'BGN' :
		$currency_symbol = '&#1083;&#1074;.';
		break;
		case 'BRL' :
		$currency_symbol = '&#82;&#36;';
		break;
		case 'CHF' :
		$currency_symbol = '&#67;&#72;&#70;';
		break;
		case 'CNY' :
		case 'JPY' :
		case 'RMB' :
		$currency_symbol = '&yen;';
		break;
		case 'CZK' :
		$currency_symbol = '&#75;&#269;';
		break;
		case 'DKK' :
		$currency_symbol = 'kr.';
		break;
		case 'DOP' :
		$currency_symbol = 'RD&#36;';
		break;
		case 'EGP' :
		$currency_symbol = 'EGP';
		break;
		case 'EUR' :
		$currency_symbol = '&euro;';
		break;
		case 'GBP' :
		$currency_symbol = '&pound;';
		break;
		case 'HRK' :
		$currency_symbol = 'Kn';
		break;
		case 'HUF' :
		$currency_symbol = '&#70;&#116;';
		break;
		case 'IDR' :
		$currency_symbol = 'Rp';
		break;
		case 'ILS' :
		$currency_symbol = '&#8362;';
		break;
		case 'INR' :
		$currency_symbol = 'Rs.';
		break;
		case 'ISK' :
		$currency_symbol = 'Kr.';
		break;
		case 'KIP' :
		$currency_symbol = '&#8365;';
		break;
		case 'KRW' :
		$currency_symbol = '&#8361;';
		break;
		case 'MYR' :
		$currency_symbol = '&#82;&#77;';
		break;
		case 'NGN' :
		$currency_symbol = '&#8358;';
		break;
		case 'NOK' :
		$currency_symbol = '&#107;&#114;';
		break;
		case 'NPR' :
		$currency_symbol = 'Rs.';
		break;
		case 'PHP' :
		$currency_symbol = '&#8369;';
		break;
		case 'PLN' :
		$currency_symbol = '&#122;&#322;';
		break;
		case 'PYG' :
		$currency_symbol = '&#8370;';
		break;
		case 'RON' :
		$currency_symbol = 'lei';
		break;
		case 'RUB' :
		$currency_symbol = '&#1088;&#1091;&#1073;.';
		break;
		case 'SEK' :
		$currency_symbol = '&#107;&#114;';
		break;
		case 'THB' :
		$currency_symbol = '&#3647;';
		break;
		case 'TRY' :
		$currency_symbol = '&#8378;';
		break;
		case 'TWD' :
		$currency_symbol = '&#78;&#84;&#36;';
		break;
		case 'UAH' :
		$currency_symbol = '&#8372;';
		break;
		case 'VND' :
		$currency_symbol = '&#8363;';
		break;
		case 'ZAR' :
		$currency_symbol = '&#82;';
		break;
		default :
		$currency_symbol = $currency;
		break;
	}
	return $currency_symbol;
}

//---------------FOR ADD NEW USER --------------------------
function MJ_cmgt_add_newuser($userdata,$usermetadata,$firstname,$lastname,$role)
{
	$returnval;
	$user_id = wp_insert_user( $userdata );
 	$user = new WP_User($user_id);
	$roles=$user->set_role($role);
	foreach($usermetadata as $key=>$val)
	{
		$returnans=add_user_meta( $user_id, $key,$val, true );
	}
	$returnval=update_user_meta( $user_id, 'first_name', $firstname );
	$returnval=update_user_meta( $user_id, 'last_name', $lastname );
	if($role=='family_member')
	{
		$member_id = $_REQUEST['member_id'];
		$member_data = get_user_meta($member_id, 'family_id', true);
		$family_data = get_user_meta($user_id, 'member', true); 
		if($member_data)
		{
			if(!in_array($user_id, $member_data))
			{
				$update = array_push($member_data,$user_id);
			
			$returnans=update_user_meta($member_id,'family_id', $member_data);
			if($returnans)
			$returnval=$returnans;
			}
			
		} else 
		{
			$family_id = array($user_id);
			$returnans=add_user_meta($member_id,'family_id', $family_id );
			if($returnans)
			$returnval=$returnans;
		}
		if ($family_data)
		{
			if(!in_array($member_id, $family_data))
			{
				$update = array_push($family_data,$member_id);
			
			$returnans=update_user_meta($user_id,'member', $family_data);
			if($returnans)
			$returnval=$returnans;
			}
			
		} 
		else 
		{
			$member_id = array($member_id);
			$returnans=add_user_meta($user_id,'member', $member_id );
			if($returnans)
			$returnval=$returnans;
		}
	}

	$cmgt_family_without_email_pass = get_option('cmgt_family_without_email_pass');

	if($cmgt_family_without_email_pass == 'yes')
	{ 
		//Family member ragistation mail template send  mail to Member

		$memberdata = get_userdata($member_id[0]);
		$family_data = get_userdata($user_id);

		$to = $memberdata->user_email; 
		$member_name=$memberdata->display_name;

		$relation=$family_data->relation;
		$family_email=$family_data->user_email;
		$family_password=$userdata['user_pass'];

		$loginlink = home_url();
		$subject ='Your Family Member are successully registered at [CMGT_CHURCH_NAME]';
		$church_name=get_option('cmgt_system_name');
		$message_content='Dear  [CMGT_MEMBER_NAME]
			Your Family Member is successfully registered as a [CMGT_FAMILY_RELATION] at [CMGT_CHURCH_NAME].
		You can signin using this link. [CMGT_LOGIN_LINK]
			
			UserName : [CMGT_USERNAME]
			Password : [CMGT_PASSWORD]
			
			Regards From [CMGT_CHURCH_NAME].';
		
		$subject_search=array('[CMGT_CHURCH_NAME]');
		$subject_replace=array($church_name);
		$search=array('[CMGT_MEMBER_NAME]','[CMGT_FAMILY_RELATION]','[CMGT_CHURCH_NAME]','[CMGT_LOGIN_LINK]','[CMGT_USERNAME]','[CMGT_PASSWORD]','[CMGT_CHURCH_NAME]');
		$replace = array($member_name,$relation,$church_name,$loginlink,$family_email,$family_password,$church_name);
		$message_content = str_replace($search, $replace, $message_content);
		$subject=str_replace($subject_search,$subject_replace,$subject);

		MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
	}


	return $returnval;
}
//-----------------FOR UPDATE USER-------------------------------------------
function MJ_cmgt_update_user($userdata,$usermetadata,$firstname,$lastname,$role)
{
	
	$user_id = wp_update_user($userdata);
	$returnval123=update_user_meta( $user_id, 'first_name', $firstname );
	$returnval123=update_user_meta( $user_id, 'last_name', $lastname );

	foreach($usermetadata as $key=>$val)
	{
	
		$returnans=update_user_meta( $user_id, $key,$val );

		if($returnans)
			$returnval=$returnans;
	
	}

	if($role=='family_member')
	{
	$old_member_id = $_REQUEST['old_member_id'];
	$memmber_id = $_REQUEST['member_id'];
	$memmber_data = get_user_meta($memmber_id, 'family_id', true); 
	// start if empty member id then check and add new//
	if(empty($old_member_id))
	{
		
		if($memmber_data)
		{
			//If allredy exit familumemer in memer so add new//
			if(!in_array($_REQUEST['family_id'], $memmber_data))
			{
				$update = array_push($memmber_data,$_REQUEST['family_id']);
				$returnans=update_user_meta($memmber_id,'family_id', $memmber_data);
				if($returnans)
				{
					$returnval=$returnans;
				}
			}
			else
			{
				$returnval=$returnans;
			}
			
		} 
		else 
		{
			 //if no exit any familty member then add new array //
			$family_id = array($_REQUEST['family_id']);
			$returnans=add_user_meta($memmber_id,'family_id', $family_id );
			if($returnans)
			{
				$returnval=$returnans;
			}
		}
		
	} // end if empty member id then check and add new//
	else
	{
	    if($old_member_id == $memmber_id)
		{
		  $returnval=1;
	    }
	    else
	    {  
		//Delete family member in old member array//
		$memmber_data_array = get_user_meta($old_member_id, 'family_id', true); 
		unset($memmber_data_array[array_search($_REQUEST['family_id'], $memmber_data_array)]);
		$returnans1=update_user_meta($old_member_id,'family_id', $memmber_data_array);
		if(count($memmber_data_array) == 0)
		{
			delete_user_meta($old_member_id, 'family_id');
		}
		//END Delete family member in old member array //
		if($memmber_data)
		{
		    //If allredy exit familumemer in memer so add new//
			if(!in_array($_REQUEST['family_id'], $memmber_data))
			{
				$update = array_push($memmber_data,$_REQUEST['family_id']);
				$returnans=update_user_meta($memmber_id,'family_id', $memmber_data);

				if($returnans)
				{
					$returnval=$returnans;
				}
			}
			else
			{
				$returnval=$returnans;
			}
			
		} 
		else 
		{
			//if no exit any familty member then add new array //
			$family_id = array($_REQUEST['family_id']);
			$returnans=add_user_meta($memmber_id,'family_id', $family_id );
			if($returnans)
			{
				$returnval=$returnans;
			}
		}
		
		  $returnval=1;
	  }
	}
	}
	return $returnval;
}

//------------------FOR GET USER IMAGE------------------
function MJ_cmgt_get_user_image($uid)
{
	global $wpdb;
	$query = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = $uid AND meta_key='cmgt_user_avatar'";
	$usersdata = $wpdb->get_results($query,ARRAY_A); 
	foreach($usersdata as $data)
	{
		return $data;
	}
}
//delete userdata
function MJ_cmgt_delete_usedata($record_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'usermeta';
	$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE user_id= %d",$record_id));
	$retuenval=wp_delete_user( $record_id );
	return $retuenval;
}
function MJ_cmgt_view_family_member()
{
	$user_meta =get_user_meta($_REQUEST['member_id'], 'family_id', true); 
?>
	<div class="modal-header"> 
	  <h4 id="myLargeModalLabel" class="modal-title"><?php esc_html_e('Family Member','church_mgt');?></h4>
	  <a href="#" class="close-btn badge badge-success pull-right">X</a>
	</div>
	<hr />
		<div class="panel-body">
			<div class="addScroll">
			  <!-- <p class="Member_name"><?php echo $firstname=get_user_meta($_REQUEST['member_id'], 'first_name', true);?></p> -->
			  <?php $firstname=get_user_meta($_REQUEST['member_id'], 'first_name', true); 
					 $lastname=get_user_meta($_REQUEST['member_id'], 'last_name', true); ?>
			  <p class="Member_name"><?php echo  "$firstname" ." " .  "$lastname" ?></p>
				<table class="table table-bordered" border="1">
					<tr>
						<th><?php esc_html_e('Photo','church_mgt');?></th>
						<th><?php esc_html_e('Name','church_mgt');?></th>
						<th> <?php esc_html_e('Relation','church_mgt');?></th>
					</tr>
				<?php
						if($user_meta)
						{
							foreach($user_meta as $parentsdata)
							{
								
								$parent=get_userdata($parentsdata);?>
				<tr>
					<td><?php if($parentsdata)
								{
									$umetadata=MJ_cmgt_get_user_image($parentsdata);
								}
								if(empty($umetadata['meta_value']))
								{
									echo '<img src='.get_option( 'cmgt_family_logo' ).' height="50px" width="50px" class="img-circle" />';
								}
								else
								echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';?></td>
					<td><?php echo $parent->first_name." ".$parent->last_name;?></td>
					<td><?php if($parent->relation=='Father'){ echo esc_html_e('Father','church_mgt'); }elseif($parent->relation=='Mother'){ echo esc_html_e('Mother','church_mgt');}
					elseif($parent->relation=='Husband'){ echo esc_html_e('Husband','church_mgt');} elseif($parent->relation=='Wife'){ echo esc_html_e('Wife','church_mgt');}
					  elseif($parent->relation=='Daughter'){ echo esc_html_e('Daughter','church_mgt');} 
						elseif($parent->relation=='Son'){ echo esc_html_e('Son','church_mgt');}
						elseif($parent->relation=='Brother'){echo esc_html_e('Brother','church_mgt');}
						elseif($parent->relation == 'Sister'){echo esc_html_e('Sister','church_mgt');}
						
						
						?></td>
					
				</tr>
				<?php
							}
						}
						else 
						{
							echo '<td colspan=3>';
							esc_html_e('No Family Member','church_mgt');
							echo '</td>';
						}
					if(count($user_meta) >= 2)
					{
						 count($user_meta);
					}
				?>
				</table>
			</div>
		</div>
		<hr/>
<?php
	exit;
}
 // CHANGE PROFILE PHOTO
function MJ_cmgt_change_profile_photo()
{
	?>

		<div class="modal-header margin_bottom_20">
			<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel"><?php echo esc_html__('Upload Profile','church_mgt');?></h4>
		</div>
		<form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
			<div class="cmgt_csv_mb_0 form-body user_form" >
				<div class="row">	
					<div class="col-md-9">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="input-1" name="profile" type="file" class="form-control profile_file file">
							</div>	
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input">
							<button type="submit" class="btn btn-success save_btn save_profile_pic rtl_margin_top_0" name="save_profile_pic"><?php esc_html_e('Save','church_mgt');?></button>
						</div>
					</div>
				</div>
			</div>
		</form>
    <?php 
	die();
}
//-------- CHECK VALID EXTENSION FILE FUNCTION -----//
function MJ_cmgt_check_valid_extension_file($filename)
{
	$flag = 2; 
	if($filename != '')
	{
		$flag = 0;
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$valid_extension = ['csv',""];
		if(in_array($ext,$valid_extension) )
		{
		$flag = 1;
		}
	}
	return $flag;
}
//-------- DATE FORMATE FUNCTION -----//
function MJ_cmgt_date_formate()
{
	$dateFormat=get_option( 'cmgt_datepicker_format' );
	if($dateFormat == 'F j, Y')
	{
		$date_formate='M j, Y';
	}
	else
	{
		$date_formate=$dateFormat;
	}
	return $date_formate;
}
//-------- dateformat_PHP_to_jQueryUI FUNCTION -----//
function MJ_cmgt_dateformat_PHP_to_jQueryUI($php_format)
{
	$SYMBOLS_MATCHING = array(
	// Day
	'd' => 'dd',
	'D' => 'D',
	'j' => 'd',
	'l' => 'DD',
	'N' => '',
	'S' => '',
	'w' => '',
	'z' => 'o',
	// Week
	'W' => '',
	// Month
	'F' => 'MM',
	'm' => 'mm',
	'M' => 'M',
	'n' => 'm',
	't' => '',
	// Year
	'L' => '',
	'o' => '',
	'Y' => 'yyyy',
	'y' => 'y',
	// Time
	'a' => '',
	'A' => '',
	'B' => '',
	'g' => '',
	'G' => '',
	'h' => '',
	'H' => '',
	'i' => '',
	's' => '',
	'u' => ''
	);
		$jqueryui_format = "";
		$escaping = false;
		for($i = 0; $i < strlen($php_format); $i++)
		{
			$char = $php_format[$i];
			if($char === '\\') // PHP date format escaping character
			{
			$i++;
			if($escaping) $jqueryui_format .= $php_format[$i];
			else $jqueryui_format .= '\'' . $php_format[$i];
			$escaping = true;
			}
			else
			{
			if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
			if(isset($SYMBOLS_MATCHING[$char]))
			$jqueryui_format .= $SYMBOLS_MATCHING[$char];
			else
			$jqueryui_format .= $char;
			}
		}
return $jqueryui_format;
}
  //get date formate for database
 function MJ_cmgt_get_format_for_db($date)
{
	if(!empty($date))
	{
		$date = trim($date);
		//$new_date = DateTime::createFromFormat(MJ_cmgt_date_formate(), $date);
		//$new_date=$new_date->format('Y-m-d');
		$new_date=date("Y-m-d",strtotime($date));
		return $new_date;
	}
	else
	{
		$new_date ='';
		return $new_date;
	}
}
//-------DATA TABLE MULTILANGUAGE-----------
function MJ_cmgt_datatable_multi_language()
{
	$datatable_attr=array("sEmptyTable"=> __("No data available in table","church_mgt"),
	"sInfo"=>__("Showing _START_ to _END_ of _TOTAL_ entries","church_mgt"),
	"sInfoEmpty"=>__("Showing 0 to 0 of 0 entries","church_mgt"),
	"sInfoFiltered"=>__("(filtered from _MAX_ total entries)","church_mgt"),
	"sInfoPostFix"=> "",
	"sInfoThousands"=>",",
	"sLengthMenu"=>__(" _MENU_ ","church_mgt"),
	"sLoadingRecords"=>__("Loading...","church_mgt"),
	"sProcessing"=>__("Processing...","church_mgt"),
	"sSearch"=>__("","church_mgt"),
	"sZeroRecords"=>__("No matching records found","church_mgt"),
	"oPaginate"=>array(
	"sFirst"=>__("First","church_mgt"),
	"sLast"=>__("Last","church_mgt"),
	"sNext"=>__("Next","church_mgt"),
	"sPrevious"=>__("Previous","church_mgt")
	),
	"oAria"=>array(
	"sSortAscending"=>__(": activate to sort column ascending","church_mgt"),
	"sSortDescending"=>__(": activate to sort column descending","church_mgt")
	)
	);

return $data=json_encode( $datatable_attr);
}
// Header User Location with Rtl // 

//Create Default Folders In Site Wise Folder//
function MJ_cmgt_upload_dir_path($folder_name)
{
	$upload_dir = wp_upload_dir(); 
	$document_dir = ''.$upload_dir['path'].'/'.$folder_name.'/';
	$document_path = $document_dir;
	if (!file_exists($document_path)) 
	{
		mkdir($document_path, 0777, true);	
	}
	return $document_path;
}
//Create url path function//
function MJ_cmgt_upload_url_path($folder_name)
{
	$upload_dir = wp_upload_dir(); 
	$document_dir = ''.$upload_dir['url'].'/'.$folder_name.'/';
	$document_path = $document_dir;
	return $document_path;
}
//Create Default Folders In uploads Folder End//

//strip tags and slashes
function MJ_cmgt_strip_tags_and_stripslashes($post_string)
{
   $string = str_replace('&nbsp;', ' ', $post_string);
   $string = html_entity_decode($string, ENT_QUOTES | ENT_COMPAT , 'UTF-8');
   $string = html_entity_decode($string, ENT_HTML5, 'UTF-8');
   $string = html_entity_decode($string);
   $string = htmlspecialchars_decode($string);
   $string = strip_tags($string);
  // $replace_string=preg_replace('/[^\x00-\x80]|[^0-9a-zA-Z\ \_\,\`\.\'\^\-\&\@\()\{}\|\|\=\%\*\#\!\~\$\+\n]/s', '', $string);
   return $string;
}

function MJ_cmgt_get_payment_report($sdate,$edate)
{
	
	global $wpdb;
	//$smgt_fees_payment = $wpdb->prefix .'cmgt_transaction';
	$table_name=$wpdb->prefix.'cmgt_transaction';
	$result = $wpdb->get_results("select *from $table_name where created_date BETWEEN '$sdate' AND '$edate'");  
	
	//$result = $wpdb->get_results($sql);
	return $result;
	
}

function MJ_cmgt_convert_time($time) 
{
	$timestamp = strtotime( $time ); // Converting time to Unix timestamp
	$offset = get_option( 'gmt_offset' ) * 60 * 60; // Time offset in seconds
	$local_timestamp = $timestamp + $offset;
	$local_time = date_i18n('Y-m-d H:i:s', $local_timestamp );
	return $local_time;
}
//show event and task model code
function MJ_cmgt_show_event_task()
{	
	$id = $_REQUEST['id'];
	$model = $_REQUEST['model'];
	if($model=='Group Details')
	{
		$obj_group=new Cmgtgroup;
		$groupdata =$obj_group->MJ_cmgt_get_single_group($id);
	}
	if($model=='ministry Details')
	{
		$obj_ministry=new Cmgtministry;
		$ministrydata =$obj_ministry->MJ_cmgt_get_single_ministry($id);
	}
	if($model=='activity Details')
	{
		$obj_activity=new Cmgtactivity;
		$activitydata =$obj_activity->MJ_cmgt_get_single_activity($id);
	}
	if($model=='service Details')
	{
		$obj_service=new Cmgtservice;
		$servicedata =$obj_service->MJ_cmgt_get_single_services($id);
	}
	if($model=='Reservation Details')
	{
		$obj_reservation=new Cmgtreservation;
		$reservationdata =$obj_reservation->MJ_cmgt_get_single_reservation($id);
	}
	if($model=='Donation Details')
	{
		$obj_donation=new Cmgttransaction;
		$donationdata =$obj_donation->MJ_cmgt_get_my_single_donationlist($id);
	}
	if($model=='Pastoral Details')
	{
		$obj_pastoral=new Cmgtpastoral;
		$pastoraldata =$obj_pastoral->MJ_cmgt_get_single_pastoral($id);
	}
	if($model=='Pledges Details')
	{
		$obj_peldges=new Cmgtpledes;
		$peldgesaldata =$obj_peldges->MJ_cmgt_get_single_pledges($id);
	}
	
	if($model=='Message Details')
	{	
		$obj_message=new Cmgt_message;
		$messagedata=$obj_message->MJ_cmgt_get_my_single_member_message_dashboard($id);
	}
	if($model=='Notice Details')
	{	
		$obj_notice=new Cmgtnotice;
		$notice_data=$obj_notice->MJ_cmgt_get_single_notice($id);
	}
	if($model=='Sell Gift Details')
	{
		$obj_sellgift=new Cmgtgift;
		$sellgiftdata =$obj_sellgift->MJ_cmgt_get_single_sell_gift($id);
	}
	?>
		<div class="modal-header margin_bottom_20 space_re_no"> 
		<a href="javascript:void(0)" class="close-btn badge badge-danger pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title">
			  	<?php 
			  	if($model=='Group Details')
			  	{ 
					?>
				  <img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Group-Black.png"?>">
					<?php
				  esc_html_e('Group Details','church_mgt'); 
			  	} 
			  	elseif($model=='ministry Details')
			  	{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Ministry-Black.png"?>">
			  	<?php 
			  		esc_html_e('Ministry Details','church_mgt'); 
			  	}
			 	elseif($model=='Pledges Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pledges-Black.png"?>">
					<?php
					esc_html_e('Pledges Details','church_mgt'); 
				}
				elseif($model=='activity Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Activity.png"?>">
					<?php
					 esc_html_e('Activity Details','church_mgt'); 
				}
				elseif($model=='service Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/services.png"?>">
					<?php
					esc_html_e('Service Details','church_mgt'); 
				}
				elseif($model=='Reservation Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Reservation-Black.png"?>">
					<?php
					esc_html_e('Reservation Details','church_mgt'); 
				}
				elseif($model=='Message Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Message-Black.png"?>">
					<?php
					esc_html_e('Message Details','church_mgt'); 
				}
				elseif($model=='Notice Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Notificationa.png"?>">
					<?php
					esc_html_e('Notice Details','church_mgt'); 
				}
				elseif($model=='Donation Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Donation-Black.png"?>">
					<?php
					esc_html_e('Donation Details','church_mgt'); 
				}
				elseif($model=='Pastoral Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Pastoral-Black.png"?>">
					<?php
					esc_html_e('Pastoral Details','church_mgt'); 
				}
				elseif($model=='Sell Gift Details')
				{ ?>
					<img class="cmgt_popup_header_img" src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Sell-Gift-Black.png"?>">
					<?php
					esc_html_e('Sell Gift Details','church_mgt'); 
				} ?>
			</h4>
		</div>
	<?php
	if($model=='Group Details')
	{
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr >
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e('Group Name', 'church_mgt');?>
							</label><br>
							<label for="" class="popup_label_value"> <?php echo $groupdata->group_name; ?> </label>
						</td>	
						<td class="width_50">
							<label class="popup_label_heading">
								<?php esc_html_e('Total Members', 'church_mgt');?>
							</label><br>
							<label for="" class="popup_label_value"> <?php echo $obj_group->MJ_cmgt_count_group_members($groupdata->id); ?></label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='ministry Details')
	{
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr >
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e('Ministry Name', 'church_mgt');?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo $ministrydata->ministry_name; ?> </label>
						</td>	
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e('Total Members', 'church_mgt');?>
							</label><br>
							<label for="" class="popup_label_value"><?php  
							echo $obj_ministry->MJ_cmgt_count_ministry_members($ministrydata->id); ?></label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='activity Details')
	{
		$obj_venue=new Cmgtvenue;
		$obj_group=new Cmgtgroup;
		$group_data=$obj_group->MJ_cmgt_get_single_group($activitydata->groups);
		$result = $obj_venue->MJ_cmgt_get_single_venue($activitydata->venue_id);
		$reccurence_array=json_decode($activitydata->recurrence_content);
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr >
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e('Activity Category', 'church_mgt');?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr(get_the_title($activitydata->activity_cat_id));?> </label>
						</td>	
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Activity Title', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr($activitydata->activity_title);?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e('Guest Speaker','church_mgt');?>
							</label><br>
								<?php 
									if(!empty($activitydata->speaker_name)) 
									{
										$speaker_name=$activitydata->speaker_name;
										?>
										<label for="" class="popup_label_value"> <?php echo esc_attr(ucfirst($speaker_name));?> </label>
										<?php
									}
									else
									{
										//$speaker_name= esc_html_e('N/A','church_mgt');
										?>
										<label for="" class="popup_label_value"> <?php esc_html_e('N/A', 'church_mgt');?> </label>
										<?php
									} 
								?>
							<label for="" class="popup_label_value"> <?php echo esc_attr(ucfirst($speaker_name));?> </label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e('Venue', 'church_mgt');?>
							</label><br>
							<label for="" class="popup_label_value">
								<?php 
									if(($activitydata->venue_id) == "0")
									{
										echo esc_html( __('N/A','church_mgt' ) );
									}else{
										echo esc_attr($result->venue_title);
									}
								?>
								
							</label>
						</td>	
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr >
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Start Date To End Date', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($activitydata->activity_date)));?> <?php esc_html_e( 'To', 'church_mgt' ) ;?> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($activitydata->activity_end_date)));?></label>
						</td>	
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Start Time To End Time', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value">
									<?php
									if(($activitydata->activity_start_time) == "Full Day" && ($activitydata->activity_end_time) == "Full Day")
									{
										echo esc_html( __( 'Full Day', 'church_mgt' ) );
									}
									else
									{
										echo esc_attr($activitydata->activity_start_time);?> <?php _e('To','church_mgt');?> <?php echo esc_attr($activitydata->activity_end_time);?> 
										<?php
									}
									?>
								</label>


						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr>
						<?php
						if($activitydata->record_start_time && $activitydata->record_end_time )
						{
							?>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Other Start Time To End Time', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($activitydata->record_start_time);?> <?php esc_html_e( 'To', 'church_mgt' ) ;?> <?php echo esc_attr($activitydata->record_end_time);?></label>
							</td>
							<?php
						}else{
							?>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e( 'Start Time To End time', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value"><?php esc_html_e('N/A', 'church_mgt');?></label>
							</td>
							<?php
						}
						?>
						<?php
						if($group_data->group_name)
						{
							?>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Group Name', 'church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($group_data->group_name);?></label>
							</td>
							<?php
						}else{
							?>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Group Name', 'church_mgt');?>
								</label><br>
								<label for="" class="popup_label_value"><?php esc_html_e('N/A', 'church_mgt');?></label>
							</td>
							<?php
						}
						?>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr>
						
						<td class="width_50 cmgt_rtl_das_pop_right">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Recurrence', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value">
								<?php 
								$reccurence_day=$reccurence_array->selected;

								$reccurence_weekly=$reccurence_array->weekly;
								$day_array=$reccurence_weekly->weekly;

								$reccurence_month_date=$reccurence_array->monthly;
								$reccurence_yearly_date=$reccurence_array->yearly;
							
									if($reccurence_day == 'daily')
									{
										echo esc_attr(ucfirst($reccurence_day));
									}
									if($reccurence_day == 'weekly')
									{
										$day_array_new=array();
										foreach($day_array as $value)
										{
											$day_array_new[]=$value;
										}
										$day_name_arry= implode(",",($day_array_new));
										echo esc_attr(ucfirst($reccurence_day)).'('.esc_attr($day_name_arry).')';
										
									}
									if($reccurence_day == 'monthly')
									{
										
										echo esc_attr(ucfirst($reccurence_day)).'(Day: '.esc_attr($reccurence_month_date->month_date).')';
									}
									if($reccurence_day == 'yearly')
									{
										echo esc_attr(ucfirst($reccurence_day)).'(Date: '.esc_attr($reccurence_yearly_date->yearly_date).')';
									}
								
									if($reccurence_day == 'none')
									{
										echo "N/A";
									}
								 ?>
							</label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
<?php
	if($model=='Message Details')
	{
		// var_dump($messagedata);
		// die;
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Message For', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo MJ_cmgt_church_get_display_name($messagedata->sender);?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Subject', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo $messagedata->msg_subject;?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Date', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($messagedata->msg_date));?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Message Comment', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo $messagedata->message_body;?></label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='Notice Details')
	{
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr >
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Notice Title', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr($notice_data->notice_title);?> </label>
						</td>	
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Start Date To End Date', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($notice_data->start_date)));?> <?php _e('To','church_mgt');?> <?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($notice_data->end_date)));?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr >
						<td class="">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Notice Comment', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value">
								<?php echo $notice_data->notice_content;?>
								<?php 
								if(!empty($notice_data->notice_content))
								{
									echo esc_attr($notice_data->notice_content);
								}
								else
								{
									echo esc_html( __( 'N/A', 'church_mgt' ) );
								}
								?>
								
							</label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='service Details')
	{
	?>
		 <div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Service Title', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr($servicedata->service_title);?> </label>
						</td>	
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Service Type', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value">
								<?php 
								if(!empty($servicedata->service_type_id))
								{
									echo esc_attr(get_the_title($servicedata->service_type_id));
								}
								else
								{
									echo esc_html( __( 'N/A', 'church_mgt' ) );
								}
								?>
							</label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Start Date To End Date', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($servicedata->start_date)));?><?php esc_html_e( ' To ', 'church_mgt' ) ;?><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($servicedata->end_date)));?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading">
							<?php esc_html_e( 'Start Time To End Time', 'church_mgt' ) ;?>
							</label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr($servicedata->start_time);?> <?php esc_html_e('To', 'church_mgt' ) ;?> <?php echo esc_attr($servicedata->end_time);?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			if(!empty($servicedata->other_title))
			{
				?>
				<hr>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Other Title', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($servicedata->other_title);?></label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e( 'Other Service Type', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($servicedata->other_service_type);?></label>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">				
					<tbody>
						<tr>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e( 'Other Service Date', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($servicedata->other_service_date)));?></label>
							</td>
							<td class="width_50">
								<label class="popup_label_heading">
								<?php esc_html_e('Other Start Time To End Time', 'church_mgt' ) ;?>
								</label><br>
								<label for="" class="popup_label_value"><?php echo esc_attr($servicedata->other_start_time);?> <?php esc_html_e('To', 'church_mgt' ) ;?> <?php echo esc_attr($servicedata->other_end_time);?></label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
			}
			?>
        </div>  		
	 <?php
	}
	?><?php
	if($model=='Pledges Details')
	{
		// var_dump($peldgesaldata);
		// die;
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="">
							<label class="popup_label_heading"><?php esc_html_e( 'Member Name', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php $user=get_userdata($peldgesaldata->member_id); echo $user->display_name; ?></label>
						</td>
					</tr>
					
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Start Date To End Date', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($peldgesaldata->start_date)));?> <?php esc_html_e( 'To', 'church_mgt');?> <?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($peldgesaldata->end_date)));?></label>
						</td>
						<!-- <?php
						if($peldgesaldata->period_id == "one_time")
						{
							echo "1 Time";
							
						
						}
						?> -->
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e('Frequency Number Of Time', 'church_mgt') ;?></label><br>
							<?php
							if($peldgesaldata->period_id == "one_time")
							{
								?>
								<label for="" class="popup_label_value"><?php esc_html_e( '1 Time', 'church_mgt' ) ;?><?php esc_html_e('(', 'church_mgt' );?><?php echo esc_attr($peldgesaldata->times_number);?><?php esc_html_e('-Time)', 'church_mgt' );?></label>
								<?php
							}else
							{
								?>
								<label for="" class="popup_label_value"><?php echo _e(ucfirst($peldgesaldata->period_id),'church_mgt');?><?php esc_html_e('(', 'church_mgt' );?><?php echo esc_attr($peldgesaldata->times_number);?><?php esc_html_e('-Time)', 'church_mgt' );?></label>
								<?php
							}
							?>
							
						</td>
					</tr> 
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Amount', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($peldgesaldata->amount);?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Total Amount', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($peldgesaldata->total_amount);?></label>
						</td>
					</tr> 
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='Reservation Details')
	{
		// var_dump($reservationdata);
		// die;
		$obj_venue=new Cmgtvenue;
		$result = $obj_venue->MJ_cmgt_get_single_venue($reservationdata->vanue_id);
		?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Usage Title', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr($reservationdata->usage_title); ?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Venue', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr($result->venue_title); ?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Start Date To End Date', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($reservationdata->reserve_date));?> <?php esc_html_e( 'To', 'church_mgt' );?> <?php echo date(MJ_cmgt_date_formate(),strtotime($reservationdata->reservation_end_date));?> </label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Start Time To End Time', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr($reservationdata->reservation_start_time); ?> <?php esc_html_e( 'To', 'church_mgt' );?> <?php echo esc_attr($reservationdata->reservation_end_time); ?> </label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e('Number of Participant','church_mgt');?></label><br>
							<label for="" class="popup_label_value">
								<?php echo esc_html($reservationdata->participant);?> <?php esc_html_e('Out of','church_mgt');?> <?php echo esc_html($reservationdata->participant_max_limit);?>
							</label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Applicant', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value">
								<?php
								 	echo MJ_cmgt_church_get_display_name($reservationdata->applicant_id);
								?>
							 </label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="">
							<label class="popup_label_heading"><?php esc_html_e( 'Description', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value">
								<?php 
								if(!empty($reservationdata->description)){
									echo esc_attr($reservationdata->description); 
								}else{
									echo esc_html( __( 'N/A', 'church_mgt' ) );
								}
								?> 
							</label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='Donation Details')
	{
		// var_dump($donationdata);
		// die;
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Member Name', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php $user=get_userdata($donationdata->member_id); echo esc_attr($user->display_name); ?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Donation Type', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr(get_the_title($donationdata->donetion_type));?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Donation Date', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($donationdata->transaction_date));?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Total Amount', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php echo number_format($donationdata->amount);?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50 cmgt_rtl_das_pop_right">
							<label class="popup_label_heading"><?php esc_html_e( 'Payment Method', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo _e($donationdata->pay_method,'church_mgt');?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="">
							<label class="popup_label_heading"><?php esc_html_e( 'Comment', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value">
								<?php 
									if(!empty($donationdata->description))
									{
										echo esc_attr($donationdata->description);
									}
									else
									{
										echo esc_html( __( 'N/A', 'church_mgt' ) );
									}
								?>
							</label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='Pastoral Details')
	{
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Pastoral Title', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo esc_attr($pastoraldata->pastoral_title);?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Member Name', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php $user=get_userdata($pastoraldata->member_id); echo $user->display_name; ?> </label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Pastoral Date', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($pastoraldata->pastoral_date));?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Pastoral Time', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php if(!empty($pastoraldata->pastoral_time)){ echo esc_attr($pastoraldata->pastoral_time); }else{ echo "N/A";}?> </label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="">
							<label class="popup_label_heading"><?php esc_html_e( 'Description', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value">
								<?php
								if(!empty($pastoraldata->description))
								{
									echo esc_attr($pastoraldata->description);
								}
								else
								{
									echo esc_html( __( 'N/A', 'church_mgt' ) );
								}
								?>
							</label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='Sell Gift Details')
	{
	?>
		<div class="modal-body">
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Gift Name', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo MJ_cmgt_church_get_gift_name($sellgiftdata->gift_id);?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Member Name', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php $user=get_userdata($sellgiftdata->member_id); echo esc_attr($user->display_name); ?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="width_100 margin_bottom_20 cmgt_popup_table" border="0">
				<tbody>
					<tr>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Sell Date', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo date(MJ_cmgt_date_formate(),strtotime($sellgiftdata->sell_date));?></label>
						</td>
						<td class="width_50">
							<label class="popup_label_heading"><?php esc_html_e( 'Gift Price', 'church_mgt' ) ;?></label><br>
							<label for="" class="popup_label_value"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?><?php echo esc_attr($sellgiftdata->gift_price);?></label>
						</td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php  
	die();	 
}

//dashboard count total reservation by access right 
function MJ_cmgt_count_total_reservation_dashboard_by_access_right($page)
{
	$obj_reservation=new Cmgtreservation;
	$curr_user_id=get_current_user_id();
	$obj_church=new Church_management($curr_user_id);
	if($obj_church->role == 'member')
	{
		$reservationdata=$obj_reservation->MJ_cmgt_count_reservation_data($curr_user_id);	
	}
	else
	{		
		$reservationdata=$obj_reservation->MJ_cmgt_get_all_reservation();
	}
	$reservationdata_count= count($reservationdata);
	return $reservationdata_count;
}
//dashboard count total pledges by access right 
function MJ_cmgt_count_total_pledges_dashboard_by_access_right($page)
{
	$obj_reservation=new Cmgtpledes;
	$curr_user_id=get_current_user_id();
	$obj_church=new Church_management($curr_user_id);
	if($obj_church->role == 'member')
	{
		$reservationdata=$obj_reservation->MJ_cmgt_get_my_pledgeslist($curr_user_id);	
	}
	else
	{		
		$reservationdata=$obj_reservation->MJ_cmgt_get_all_pledges();
	}
	$reservationdata_count= count($reservationdata);
	return $reservationdata_count;
}
//-- Change Message Read Status
function MJ_cmgt_change_read_status($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "cmgt_message";
	$data['msg_status']=1;
	$whereid['message_id']=$id;
	return $retrieve_subject = $wpdb->update($table_name,$data,$whereid);
}
function MJ_cmgt_change_read_status_reply($id)
{
	global $wpdb;
	$cmgt_message_replies = $wpdb->prefix . 'cmgt_message_replies';
		
	$data['msg_status']=1;
	$whereid['message_id']=$id;
	$whereid['receiver_id']=get_current_user_id();
	$retrieve_message_reply_status = $wpdb->update($cmgt_message_replies,$data,$whereid);
	
	return $retrieve_message_reply_status;
}
function MJ_cmgt_get_receiver_name_array($message_id,$sender_id,$created_date,$message_comment)
{
	$message_id=(int)$message_id;
	$sender_id=(int)$sender_id;
	global $wpdb;
	$new_name_array=array();
	$receiver_name=array();
	$tbl_name = $wpdb->prefix .'cmgt_message_replies';
	$reply_msg =$wpdb->get_results("SELECT receiver_id  FROM $tbl_name where message_id = $message_id AND sender_id = $sender_id AND message_comment='$message_comment' OR created_date='$created_date'");
	if (!empty($reply_msg)) {
		foreach ($reply_msg as $receiver_id) {
			$receiver_name[]=MJ_cmgt_church_get_display_name($receiver_id->receiver_id);
		}
	}
	$new_name_array=implode(", ",$receiver_name);
	return $new_name_array;
}
function MJ_cmgt_count_reply_item($id)
{
	global $wpdb;
	$tbl_cmgt_message = $wpdb->prefix .'cmgt_message';
	$cmgt_message_replies = $wpdb->prefix .'cmgt_message_replies';	
	
	/* $result=$wpdb->get_var("SELECT count(*)  FROM $smgt_message_replies where message_id = $id");
	
	return $result;  */
	$user_id=get_current_user_id();
	$inbox_sent_box =$wpdb->get_results("SELECT *  FROM $tbl_cmgt_message where ((receiver = $user_id) AND (sender != $user_id)) AND (post_id = $id) AND (msg_status=0)");
	
	$reply_msg =$wpdb->get_results("SELECT *  FROM $cmgt_message_replies where (receiver_id = $user_id) AND (message_id = $id) AND ((msg_status=0) OR (msg_status IS NULL))");
	
	$count_total_message=count($inbox_sent_box) + count($reply_msg); 
	
	return $count_total_message; 
}
function MJ_cmgt_count_unread_message($user_id)
{
	
	global $wpdb;
	$tbl_name = $wpdb->prefix .'cmgt_message';
	$cmgt_message_replies = $wpdb->prefix . 'cmgt_message_replies';
	
	$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name where ((receiver = $user_id) AND (sender != $user_id)) AND (msg_status=0)");
	
	$reply_msg =$wpdb->get_results("SELECT *  FROM $cmgt_message_replies where (receiver_id = $user_id) AND ((msg_status=0) OR (msg_status IS NULL))");
	
	$count_total_message=count($inbox) + count($reply_msg);
	return $count_total_message;
}
function MJ_cmgt_count_unread_message_admin($user_id)
{
	global $wpdb;
	$tbl_name_message = $wpdb->prefix .'cmgt_message';
	$cmgt_message_replies = $wpdb->prefix . 'cmgt_message_replies';

	$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name_message where ((receiver = $user_id) AND (sender != $user_id)) AND (msg_status=0)");

	$reply_msg =$wpdb->get_results("SELECT *  FROM $cmgt_message_replies where (receiver_id = $user_id) AND ((msg_status=0) OR (msg_status IS NULL))");

	$count_total_message=count($inbox) + count($reply_msg);

	return $count_total_message;
}
function MJ_cmgt_browser_javascript_check()
{
	$plugins_url = plugins_url( 'church-management/ShowErrorPage.php' );
?>
	<noscript><meta http-equiv="refresh" content="0;URL=<?php echo $plugins_url;?>"></noscript> 
<?php
}
//user role wise access right array
function MJ_cmgt_get_userrole_wise_access_right_array()
{
	$role = MJ_cmgt_get_user_role(get_current_user_id());
	$page_name = "";
	if(!empty($_REQUEST ['page']))
	{
		$page_name = $_REQUEST ['page'];
	}
	if($role=='member')
	{
		$menu = get_option( 'cmgt_access_right_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'cmgt_access_right_accountant');
	}
	elseif($role=='family_member')
	{
		$menu = get_option( 'cmgt_access_right_family_member');
	}
	elseif($role=='management')
	{
		$menu = get_option( 'cmgt_access_right_management');
	}
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if(isset($page_name))
			{
				if ($page_name == $value['page_link'])
				{				
					return $value;
				}
			}			
			
		}
	}
}
//-- Get User Role by User ID
function MJ_cmgt_get_user_role($user_id)
{
	$user = new WP_User( $user_id );
	if ( !empty( $user->roles ) && is_array( $user->roles ) ) 
	{
	foreach ( $user->roles as $role )
		return $role;
	}
}
//-- Get Static User Type
function MJ_cmgt_get_user_roles($user_id)
{
	$user = new WP_User( $user_id );
	if ( !empty( $user->roles ) && is_array( $user->roles ) ) 
	{
	    foreach ( $user->roles as $role )
		{
	      if($role == 'member')
		  {
			$user_type=esc_html__('Member','church_mgt');
		  }
	      else if($role == 'accountant')
		  {
		   
			$user_type=esc_html__('Accountant','church_mgt');
		  }
		  else if($role == 'family_member')
		  {
		    
			$user_type=esc_html__('Family member','church_mgt');
		  }
		  else if($role == 'management')
		  {
		    
			$user_type=esc_html__('Management','church_mgt');
		  }
		  else if($role == 'administrator')
		  {
		    
			$user_type=esc_html__('Administrator','church_mgt');
		  }
		}
		
		return $user_type;
	}
}
//-- End Get Static User Type
//access right page not access message
function MJ_cmgt_access_right_page_not_access_message()
{
	?>
	<script type="text/javascript">
		$(document).ready(function() 
		{
			"use strict";
			alert('<?php esc_html_e('You do not have permission to perform this operation.','church_mgt');?>');
			window.location.href='?dashboard=user';
		});
	</script>
<?php
}
//dashboard page access right
function MJ_cmgt_page_access_rolewise_accessright_dashboard($page)
{
	$role = MJ_cmgt_get_user_role(get_current_user_id());
	$flage = 0;
	if($role=='member')
	{ 
		$menu = get_option( 'cmgt_access_right_member');
	
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'cmgt_access_right_accountant');
	}
	elseif($role=='family_member')
	{
		$menu = get_option( 'cmgt_access_right_family_member');
	}	
	elseif($role=='management')
	{
		$menu = get_option( 'cmgt_access_right_management');
	}	
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{				
				if($value['view']=='0')
				{			
					$flage=0;
				}
				else
				{
					$flage=1;
				}
			}
		}
	}	
	return $flage;
} 
function MJ_cmgt_calander_laungage()
{
	$lancode=get_locale();
	$code=substr($lancode,0,2);	
     return $code;
}
//manually page wise access right
function MJ_cmgt_get_userrole_wise_manually_page_access_right_array($page)
{
	$role = MJ_cmgt_get_user_role(get_current_user_id());
		
	if($role=='member')
	{ 
		$menu = get_option( 'cmgt_access_right_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'cmgt_access_right_accountant');
	}
	elseif($role=='family_member')
	{
		$menu = get_option( 'cmgt_access_right_family_member');
	}
	
	foreach ( $menu as $key1=>$value1 ) 
	{								
		foreach ( $value1 as $key=>$value ) 
		{			
			if ($page == $value['page_link'])
			{			
				return $value;
			}
		}
	}	
}
function MJ_cmgt_check_resevation_on_day($reservation_date,$reservation_end_date,$start_time,$end_time)
{
	global $wpdb;
	$table_reservation = $wpdb->prefix. 'cmgt_venue_reservation';
	$result = $wpdb->get_row("SELECT * FROM $table_reservation where reserve_date='$reservation_date' AND reservation_end_date='$reservation_end_date' AND reservation_start_time='$start_time' AND reservation_end_time='$end_time'");
	return $result;
}
 function MJ_cmgt_send_invoice_generate_mail($to,$subject,$message_content,$id,$invoice_type)
{
	ob_start();
	
	$currency_symbol=MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
	
   $obj_payment=new Cmgtpayment;
   $obj_gift=new Cmgtgift;
	if($invoice_type=='income')
	{
		$income_data=$obj_payment->MJ_cmgt_get_income_data($id);
		/* var_dump($income_data);
		die; */
	}

	if($invoice_type=='expense')
	{
		$expense_data=$obj_payment->MJ_cmgt_get_income_data($id);
	}
	if($invoice_type=='transaction')
	{
		$transaction_data=MJ_cmgt_get_transaction_data($id);
	}
	if($invoice_type=='sell_gift')
	{
		$sell_gift_data=$obj_gift->MJ_cmgt_get_single_sell_gift($id);
	}
	if($invoice_type=='pledges')
	{
		$pledges_data=MJ_cmgt_get_pledges_data($id);
	}
	
	
	
	
   $currency_symbol=MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
	$invoice_type = 'transaction';
    $obj_payment=new Cmgtpayment;
	$obj_gift=new Cmgtgift;
	if($invoice_type=='income')
	{
		$income_data=$obj_payment->MJ_cmgt_get_income_data($id);
	}

	if($invoice_type=='expense')
	{
		$expense_data=$obj_payment->MJ_cmgt_get_income_data($id);
		
	}
	
	if($invoice_type=='transaction')
	{
		$transaction_data=MJ_cmgt_get_transaction_data($id);
		
	}

	if($invoice_type=='sell_gift')
	{
		$sell_gift_data=$obj_gift->MJ_cmgt_get_single_sell_gift($id);
	}
	if($invoice_type=='pledges')
	{
		$pledges_data=MJ_cmgt_get_pledges_data($id);
	}
	
	wp_enqueue_style( 'bootstrap_min-css', plugins_url( '/assets/css/bootstrap_min.css', __FILE__) );
	wp_enqueue_script('bootstrap_min-js', plugins_url( '/assets/js/bootstrap_min.js', __FILE__ ) );
	
	
	require_once CMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';
	
	$stylesheet = file_get_contents(CMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
	$stylesheet1 = file_get_contents(CMS_PLUGIN_DIR. '/assets/css/style.css'); // Get css content
	$stylesheet1 = file_get_contents(CMS_PLUGIN_DIR. '/assets/css/new-design.css'); // Get css content
	

	
	
	$mpdf = new Mpdf\Mpdf; 
	$mpdf->autoScriptToLang = true;
     $mpdf->autoLangToFont = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML($stylesheet1,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Income Invoice');
	$mpdf->WriteHTML('<div class="modal-header">');
			$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('cmgt_system_name').'</h4>');
		$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML('<div id="invoice_print">');		
			$mpdf->WriteHTML('<img class="invoicefont1" src="'.plugins_url('/church-management/assets/images/invoice.jpg').'" width="100%">');
			$mpdf->WriteHTML('<div class="main_div">');	
				
				
				$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo padding_left_15" src="'.get_option( 'cmgt_church_other_data_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');								
								$mpdf->WriteHTML('A. '.chunk_split(get_option( 'cmgt_church_address' ),30,"<BR>").'<br>'); 
								 $mpdf->WriteHTML('E. '.get_option( 'cmgt_email' ).'<br>'); 
								 $mpdf->WriteHTML('P. +'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_option( 'cmgt_contact_number' ).'<br>'); 
							$mpdf->WriteHTML('</td>'); 
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				
				$mpdf->WriteHTML('<table>');
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td>');
		
							$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
								$mpdf->WriteHTML('<tbody>');				
								$mpdf->WriteHTML('<tr>');
									$mpdf->WriteHTML('<td colspan="2" class="billed_to_print" align="center">');	
										$mpdf->WriteHTML('<h3 class="billed_to_lable"> |'.esc_html__('Bill To','church_mgt').'. </h3>');
									$mpdf->WriteHTML('</td>');
									$mpdf->WriteHTML('<td class="width_40_print">');
									 if(!empty($expense_data))
										{
										  $mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($expense_data->supplier_name),30,"<BR>").'</h3>'); 
										}
										else
										{
											if(!empty($income_data))
												$member_id= sanitize_text_field($income_data->supplier_name);
											 if(!empty($transaction_data))
												$member_id= sanitize_text_field($transaction_data->member_id);
												
											 if(!empty($sell_gift_data))
												$member_id= sanitize_text_field($sell_gift_data->member_id);
											if(!empty($pledges_data))
												$member_id= sanitize_text_field($pledges_data->member_id);
											$bill_to=get_userdata($member_id);
											
											$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($bill_to->display_name),30,"<BR>").'</h3>'); 
											$address=get_user_meta( $member_id,'address',true);									
											$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
											$mpdf->WriteHTML(''.get_user_meta( $member_id,'city_name',true ).','); 
											$mpdf->WriteHTML('+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta( $member_id,'mobile',true ).'<br>');   
										}  	
									 $mpdf->WriteHTML('</td>');
								 $mpdf->WriteHTML('</tr>');									
							 $mpdf->WriteHTML('</tbody>');
							$mpdf->WriteHTML('</table>');
							
						$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('<td>');
						
						$issue_date='DD-MM-YYYY';
										if(!empty($income_data))
										{
											$issue_date= sanitize_text_field($income_data->invoice_date);
											$payment_status=sanitize_text_field($income_data->payment_status);
											$invoice_no=sanitize_text_field($income_data->invoice_id);
										}
										if(!empty($expense_data))
										{
											$issue_date=sanitize_text_field($expense_data->invoice_date);
											$payment_status=sanitize_text_field($expense_data->payment_status);
											$invoice_no=sanitize_text_field($expense_data->invoice_id);
										}
										if(!empty($transaction_data))
										{
											$issue_date=sanitize_text_field($transaction_data->created_date);
											$invoice_no=sanitize_text_field($transaction_data->id);
										}
										if(!empty($sell_gift_data))
										{
											$issue_date=sanitize_text_field($sell_gift_data->sell_date);						
											$invoice_no=sanitize_text_field($sell_gift_data->id);
										}
										if(!empty($pledges_data))
										{
											$issue_date=sanitize_text_field($pledges_data->created_date);						
											$invoice_no=sanitize_text_field($pledges_data->id);
										} 
							$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
								$mpdf->WriteHTML('<tbody>');				
									$mpdf->WriteHTML('<tr>');	
										$mpdf->WriteHTML('<td class="width_30_print">');
										$mpdf->WriteHTML('</td>');
										$mpdf->WriteHTML('<td class="width_20_print invoice_lable padding_right_30" align="left">');
										
										
										if($_POST['invoice_type']!='expense')
										{
											$mpdf->WriteHTML('<h3 class="invoice_lable"  >'.esc_html__('INVOICE','church_mgt').'</br> #'.$invoice_no.'</h3>');	
										}
							
																		
										$mpdf->WriteHTML('</td>');
									$mpdf->WriteHTML('</tr>');
									
									$mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print padding_right_30" align="left">');
								$issue_date=MJ_cmgt_date_formate();
											if(!empty($income_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($income_data->invoice_date));
											}
											if(!empty($expense_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($expense_data->invoice_date));
											}
											if(!empty($sell_gift_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));
											}
										  if(!empty($transaction_data)){
											$issue_date=date(MJ_cmgt_date_formate(),strtotime($transaction_data->transaction_date));
											}
										 if(!empty($pledges_data)){
											$issue_date=date(MJ_cmgt_date_formate(),strtotime($pledges_data->created_date));
											}
								$mpdf->WriteHTML('<h5>'. esc_html__('Date','church_mgt').' : '.$issue_date.'</h5>');
																		
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
			
				if($type=='expense')
				{	
					$mpdf->WriteHTML('<table class="width_100">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td>');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Expense Entries','church_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');				
					
				}		
				elseif($type=='transaction')
				{ 
					$mpdf->WriteHTML('<table class="width_100">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td>');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Transaction Entries','church_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				
				}
				elseif($type=='sell_gift')
				{ 
					$mpdf->WriteHTML('<table class="width_100">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td>');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Sell Gift','church_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				  
				}
				elseif($type=='pledges')
				{ 
					$mpdf->WriteHTML('<table class="width_100">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td>');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Pledges Entries','church_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				  
				}
				else
				{ 
					$mpdf->WriteHTML('<table class="width_100">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td>');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Income Entries','church_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');	
				}	

				$mpdf->WriteHTML('<table class="table table-bordered" class="width_93" border="1">');
					$mpdf->WriteHTML('<thead>');	
						
						if($type=='income || expense')
						{						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Date','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.esc_html__('Description','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.esc_html__('Issue By','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.esc_html__('Amount','church_mgt').'</th>');								
							$mpdf->WriteHTML('</tr>');
						}
						elseif($type=='transaction')
						{  
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Date','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.esc_html__('Description','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.esc_html__('Issue By','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.esc_html__('Amount','church_mgt').'</th>');
								
							$mpdf->WriteHTML('</tr>');
						
						} 
						elseif($type=='sell_gift')
						{  
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Date','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.esc_html__('Description','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.esc_html__('Issue By','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.esc_html__('Amount','church_mgt').'</th>');
								
							$mpdf->WriteHTML('</tr>');
						
						} 
						elseif($type=='pledges')
						{  
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Date','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.esc_html__('Description','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.esc_html__('Issue By','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.esc_html__('Amount','church_mgt').'</th>');
								
							$mpdf->WriteHTML('</tr>');
						
						} 
						else
						{ 						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Date','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Description','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Issue By','church_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Amount','church_mgt').'</th>');						
							$mpdf->WriteHTML('</tr>');
						}	
						
					$mpdf->WriteHTML('<tbody>');
					
						if(!empty($income_data) || !empty($expense_data))
						{
							$id=1;
							$total_amount=0;
							if(!empty($expense_data))
							$income_data=$expense_data;
							$church_all_income=$obj_payment->MJ_cmgt_get_oneparty_income_data($income_data->supplier_name);
							foreach($church_all_income as $result_income)
							{
								$income_entries=json_decode($result_income->entry);
								foreach($income_entries as $each_entry)
								{
									$total_amount+=$each_entry->amount;
									$total_amount1=$each_entry->amount;
									$mpdf->WriteHTML('<tr class="entry_list">');
										$mpdf->WriteHTML('<td class="align_center">'.$id.'</td>');
										$mpdf->WriteHTML('<td class="align_center">'.date(MJ_cmgt_date_formate(),strtotime(esc_attr($result_income->invoice_date))).'</td>');
										$mpdf->WriteHTML('<td >'.esc_attr($each_entry->entry).'</td>');
										$mpdf->WriteHTML('<td class="align_right">'. MJ_cmgt_church_get_display_name(esc_attr($result_income->receiver_id)).'</td>');
										$mpdf->WriteHTML('<td class="align_right"><span style="font-size:14px;">'. number_format($total_amount1,2).'</span></td>');
									$mpdf->WriteHTML('</tr>');
									
									$id+=1;
								
								}
							}
						}
						if(!empty($sell_gift_data))
						{
							
							$id=1;
							$total_amount=0;
							$total_amount=$sell_gift_data->gift_price;
							$mpdf->WriteHTML('<tr class="entry_list">');
									$mpdf->WriteHTML('<td class="align_center">'. $id.'</td>');
									$mpdf->WriteHTML('<td class="align_center">'. date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date)).'</td>');
									$mpdf->WriteHTML('<td >'.MJ_cmgt_church_get_gift_name($sell_gift_data->gift_id).'</td>');
									$mpdf->WriteHTML('<td class="align_right">'. MJ_cmgt_church_get_display_name($sell_gift_data->created_by).'</td>');
									$mpdf->WriteHTML('<td class="align_right"><span style="font-size:14px;">'.number_format($sell_gift_data->gift_price,2).'</span></td>');
							$mpdf->WriteHTML('</tr>');
							
						}
						if(!empty($pledges_data))
						{
							$id=1;
							$total_amount=0;
							$total_amount=$pledges_data->total_amount;
							
							$mpdf->WriteHTML('<tr class="entry_list">');
								$mpdf->WriteHTML('<td class="align_center">'. $id.'</td>');
								$mpdf->WriteHTML('<td class="align_center">'. date(MJ_cmgt_date_formate(),strtotime($pledges_data->start_date)).'</td>');
								$mpdf->WriteHTML('<td>'.  esc_html__('Pledge','church_mgt').'</td>');
								$mpdf->WriteHTML('<td class="align_center">'. MJ_cmgt_church_get_display_name($pledges_data->created_by).'</td>');
								$mpdf->WriteHTML('<td class="align_right">'. number_format($pledges_data->total_amount,2).'</td>');
							$mpdf->WriteHTML('</tr>');
							
						}
					
						if(!empty($transaction_data))
						{
								$id=1;
								$total_amount=0;
								$total_amount=$transaction_data->amount;
							
							$mpdf->WriteHTML('<tr class="entry_list">');
								$mpdf->WriteHTML('<td class="align_center">'. $id.'</td>');
								$mpdf->WriteHTML('<td class="align_center">'. date(MJ_cmgt_date_formate(),strtotime(esc_attr($transaction_data->transaction_date))).'</td>');
								$mpdf->WriteHTML('<td>'. get_the_title(esc_attr($transaction_data->donetion_type)).'</td>');
								$mpdf->WriteHTML('<td class="align_center">'. MJ_cmgt_church_get_display_name(esc_attr($transaction_data->created_by)).'</td>');
								$mpdf->WriteHTML('<td class="align_right">'. esc_attr($transaction_data->amount).'</td>');
							$mpdf->WriteHTML('</tr>');
						} 			
										
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
					$mpdf->WriteHTML('<table class="table  width_54_print width_54_print1"   border="0" >');
						$mpdf->WriteHTML('<tbody>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin">'.esc_html__('Subtotal :','church_mgt').'</h4></td>');
									$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span>'. MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).'</span>'. number_format($total_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');							
								$mpdf->WriteHTML('<td  class="width_56 align_right grand_total_lable"><h3 class="color_white margin">'.esc_html__('Grand Total','church_mgt').'</h3></td>');
								$mpdf->WriteHTML('<td class="align_right grand_total_amount"><h3 class="color_white margin"><span>'.MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )).'</span>'.number_format($total_amount,2).'</h3></td>');
							$mpdf->WriteHTML('</tr>');
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
			$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML('</div>'); 
	$mpdf->WriteHTML('</body>'); 
$mpdf->WriteHTML('</html>'); 
	
	ob_clean();
	
	$mpdf->Output(WP_CONTENT_DIR . '/uploads/'.$id.'-'.$invoice_type.'.pdf','F');
	ob_end_flush();
	unset($mpdf);	
	$system_name=get_option('cmgt_system_name');
	
	$headers = "From: ".$system_name.' <noreplay@gmail.com>' . "\r\n";	
	
	$mail_attachment = array(WP_CONTENT_DIR . '/uploads/'.$id.'-'.$invoice_type.'.pdf');
	
		$mail_result=wp_mail($to,$subject,$message_content,$headers,$mail_attachment); 
	return $mail_result;
}  
add_action('init','MJ_cmgt_pdf_init');
function MJ_cmgt_pdf_init()
{
	if (is_user_logged_in ()) 
	{
		
		if(isset($_REQUEST['invoicepdf']) && $_REQUEST['invoicepdf'] == 'invoicepdf')
		{
				
			MJ_cmgt_invoice_pdf($_REQUEST['idtest'],$_REQUEST['invoice_type']);
			exit;
		}
	}
}

function MJ_cmgt_invoice_pdf($invoice_id,$invoice_type)
{
	$currency_symbol=MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
	
	$obj_payment = new Cmgtpayment;
	$obj_gift=new Cmgtgift;
	$obj_pledges = new Cmgtpledes;
	if($invoice_type=='income')
	{
		$income_data=$obj_payment->MJ_cmgt_get_income_data($invoice_id);
	}
	if($invoice_type=='expense')
	{
		$expense_data=$obj_payment->MJ_cmgt_get_income_data($invoice_id);
	}
	if($invoice_type=='transaction')
	{
		$transaction_data=MJ_cmgt_get_transaction_data($invoice_id);
	}
	if($invoice_type=='sell_gift')
	{
		$sell_gift_data=$obj_gift->MJ_cmgt_get_single_sell_gift($invoice_id);
	}
	if($invoice_type=='pledges')
	{
		$pledges_data=MJ_cmgt_get_pledges_data($invoice_id);
	}
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/dynamic_css.php', __FILE__).'"></link>';

	echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>';

	   if (is_rtl())

	   {

		?>					 	

		<link rel="stylesheet" type="text/css"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/custom-rtl.css'; ?>"/>					 	

	   <?php

	   }
	   ?>
	   <?php
	   
   ob_clean();
 
   header('Content-type: application/pdf');
	
   header('Content-Disposition: inline; filename="invoice.pdf"');

   header('Content-Transfer-Encoding: binary');

   header('Accept-Ranges: bytes');

   require_once CMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';

   $stylesheet = file_get_contents(CMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content

	$mpdf = new \Mpdf\Mpdf;

	// $mpdf->debug = true;
	$mpdf->autoScriptToLang = true;
	$mpdf->autoLangToFont = true;
	
		if (is_rtl())
		{
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			$mpdf->SetDirectionality('rtl');
		}   

	$mpdf->WriteHTML('<html>');

	$mpdf->WriteHTML('<head>');

	$mpdf->WriteHTML('<style></style>');

	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf

	$mpdf->WriteHTML('</head>');

	if (is_rtl())

	{
	   $mpdf->WriteHTML('<body class="direction_rtl" style="font-family: "Poppins" !important;">');			 	
	}

	else
	{
	   $mpdf->WriteHTML('<body>');
	}	
	$mpdf->SetTitle('Invoice');	
	
	
			if (is_rtl())

			{
		
				$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/church-management/assets/images/invoice.jpg').'" class="rtl1">');
		
				$mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print" class="rtl2">');
		
				
		
			}	else{
		
				$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/church-management/assets/images/invoice.jpg').'" width="100%">');
		
				$mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print">');
		
			} 
			$mpdf->WriteHTML('<h4 class="modal-title margin_top_0px">'.get_option('cmgt_system_name').'</h4>');
			$mpdf->WriteHTML('<table class="width_70_print" border="0">');					
				$mpdf->WriteHTML('<tbody>');
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td class="width_70_print">');
							$mpdf->WriteHTML('<img class="system_logo system_logo_print" src="'.get_option( 'cmgt_church_other_data_logo' ).'">');
						$mpdf->WriteHTML('</td>');						
						$mpdf->WriteHTML('<td class="only_width_20_print">');	

								$mpdf->WriteHTML('<table border="0">');					

									$mpdf->WriteHTML('<tbody>');

										$mpdf->WriteHTML('<tr>');

											$mpdf->WriteHTML('<td>');

												$mpdf->WriteHTML('<label class="popup_label_heading">'.esc_html__('Address ','church_mgt').'');

												$address_length=strlen(get_option( 'cmgt_church_address' ));

													if($address_length>120)

													{												

														$mpdf->WriteHTML('<BR><BR><BR><BR><BR><BR>');

													}

													elseif($address_length>90)

													{	

														$mpdf->WriteHTML('<BR><BR><BR><BR><BR>');

													}

													elseif($address_length>60)

													{

														$mpdf->WriteHTML('<BR><BR><BR><BR>');

													}

													elseif($address_length>30)

													{

													$mpdf->WriteHTML('<BR><BR><BR>');

													}

											$mpdf->WriteHTML('</label></td>');	

											$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family"><label for="" class="label_value"> : '.chunk_split(get_option( 'cmgt_church_address' ),30,"<BR>").'');

											$mpdf->WriteHTML('</label></td>');

										$mpdf->WriteHTML('</tr>');

										$mpdf->WriteHTML('<tr>');				

											$mpdf->WriteHTML('<td><label class="popup_label_heading">'.esc_html__('Email','church_mgt').' ');

											$mpdf->WriteHTML('</label></td>');	

											$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family"><label for="" class="label_value">: '.get_option( 'cmgt_email' )."<br>".'');

											$mpdf->WriteHTML('</label></td>');	

										$mpdf->WriteHTML('</tr>');

										$mpdf->WriteHTML('<tr>');

											$mpdf->WriteHTML('<td>');

												$mpdf->WriteHTML('<label class="popup_label_heading">'.esc_html__('Phone ','church_mgt').'');

											$mpdf->WriteHTML(' </label></td>');	

											$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family"><label for="" class="label_value">: +'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_option( 'cmgt_contact_number' )."<br>".'');

											$mpdf->WriteHTML('</label></td>');

										$mpdf->WriteHTML('</tr>');

									$mpdf->WriteHTML('</tbody>');

								$mpdf->WriteHTML('</table>');

								$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('<td align="right" class="width_24">');
						$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');

			$mpdf->WriteHTML('<table>');
				$mpdf->WriteHTML('<tr>');
					$mpdf->WriteHTML('<td class="width_65">');

						$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
							$mpdf->WriteHTML('<tbody>');				
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td colspan="2" class="billed_to_print" >');	
									$mpdf->WriteHTML('<h3 class="font_family"> '.esc_html__('Bill To','church_mgt').' : </h3>');
									if(!empty($expense_data))
									{
										$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($expense_data->supplier_name),30,"<BR>").'</h3>'); 
									}
									else
									{
										if(!empty($income_data))
											$member_id= sanitize_text_field($income_data->supplier_name);
											if(!empty($transaction_data))
											$member_id= sanitize_text_field($transaction_data->member_id);
											
											if(!empty($sell_gift_data))
											$member_id= sanitize_text_field($sell_gift_data->member_id);
										if(!empty($pledges_data))
											$member_id= sanitize_text_field($pledges_data->member_id);
										$bill_to=get_userdata($member_id);
										
										$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($bill_to->display_name),30,"<BR>").'</h3>'); 
									}  	
								$mpdf->WriteHTML('</td>');
								$mpdf->WriteHTML('</tr>');
								$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_40_print">');
									if(!empty($expense_data))
									{
										$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($expense_data->supplier_name),30,"<BR>").'</h3>'); 
									}
									else
									{
										if(!empty($income_data))
											$member_id= sanitize_text_field($income_data->supplier_name);
											if(!empty($transaction_data))
											$member_id= sanitize_text_field($transaction_data->member_id);
											
											if(!empty($sell_gift_data))
											$member_id= sanitize_text_field($sell_gift_data->member_id);
										if(!empty($pledges_data))
											$member_id= sanitize_text_field($pledges_data->member_id);
										$bill_to=get_userdata($member_id);
										
										$address=get_user_meta( $member_id,'address',true);									
										$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").'');  
										$mpdf->WriteHTML('+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta( $member_id,'mobile',true ).'<br>');   
									}  	
									$mpdf->WriteHTML('</td>');	
								$mpdf->WriteHTML('</tr>');								
							$mpdf->WriteHTML('</tbody>');
						$mpdf->WriteHTML('</table>');
						
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td class="width_30">');
					
						$issue_date='DD-MM-YYYY';
									if(!empty($income_data))
									{
										$issue_date= sanitize_text_field($income_data->invoice_date);
										$payment_status=sanitize_text_field($income_data->payment_status);
										$invoice_no=sanitize_text_field($income_data->invoice_id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($income_data->invoice_id)); 
									}
									if(!empty($expense_data))
									{
										$issue_date=sanitize_text_field($expense_data->invoice_date);
										$payment_status=sanitize_text_field($expense_data->payment_status);
										$invoice_no=sanitize_text_field($expense_data->invoice_id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($expense_data->invoice_id)); 
									}
									if(!empty($transaction_data))
									{
										$issue_date=sanitize_text_field($transaction_data->created_date);
										$invoice_no=sanitize_text_field($transaction_data->id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($transaction_data->id)); 
									}
									if(!empty($sell_gift_data))
									{
										$issue_date=sanitize_text_field($sell_gift_data->sell_date);						
										$invoice_no=sanitize_text_field($sell_gift_data->id);
										$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($sell_gift_data->id)); 
									}
									if(!empty($pledges_data))
									{
										$issue_date=sanitize_text_field($pledges_data->created_date);						
										$invoice_no=sanitize_text_field($pledges_data->id);
										$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($pledges_data->id)); 
									} 
								
						$mpdf->WriteHTML('<table class=""  border="0">');
							$mpdf->WriteHTML('<tbody>');
								if($invoice_type!='expense')
								{				
									$mpdf->WriteHTML('<tr>');	
										
									
										$mpdf->WriteHTML('<td class="width_50_heading invoice_lable align_center" style="background-color: '.get_option('cmgt_system_color_code').' !important;">');
										
										
										
											$mpdf->WriteHTML('<h3 class="invoice_color invoice_lable"  style="background-color: '.get_option('cmgt_system_color_code').' !important;"><span class="font_12">'.esc_html__('INVOICE','church_mgt').' #</span><span class="font_18">'.get_option( 'cmgt_payment_prefix' ).' '.$invoice_number.'</span></h3>');	
										
							
																		
										$mpdf->WriteHTML('</td>');
									$mpdf->WriteHTML('</tr>');
								}
								$mpdf->WriteHTML('<tr>');	
									$mpdf->WriteHTML('<td class="width_20_print padding_right_30" align="left">');
									$issue_date=MJ_cmgt_date_formate();
												if(!empty($income_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($income_data->invoice_date));
													$payment_status=sanitize_text_field($income_data->payment_status);
												}
												if(!empty($expense_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($expense_data->invoice_date));
													$payment_status=sanitize_text_field($expense_data->payment_status);
												}
												if(!empty($sell_gift_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));
												}
												if(!empty($transaction_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($transaction_data->transaction_date));
												}
												if(!empty($pledges_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($pledges_data->created_date));
												}
											
									$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'. esc_html__('Date','church_mgt').' : '.$issue_date.'</h5><br>');
									if($invoice_type=='expense' || $invoice_type=='income')
									{
									$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'. esc_html__('Status','church_mgt').' : '.esc_html__( $payment_status,'church_mgt').'</h5>');
									}
									$mpdf->WriteHTML('</td>');							
								$mpdf->WriteHTML('</tr>');						
							$mpdf->WriteHTML('</tbody>');
						$mpdf->WriteHTML('</table>');	
					$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');

			if($invoice_type=='expense')
			{	
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Expense Entries','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');				
				
			}		
			elseif($invoice_type=='transaction')
			{ 
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Transaction Entries','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
			
			}
			elseif($invoice_type=='sell_gift')
			{ 
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Sell Gift','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
			  
			}
			elseif($invoice_type=='pledges')
			{ 
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Pledges Entries','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
			  
			}
			else
			{ 
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Income Entries','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');	
			}	
			$mpdf->WriteHTML('<table class="table model_invoice_table" class="width_100_print" border="0">');
			$mpdf->WriteHTML('<thead>');	
				
				if($invoice_type=='income || expense')
				{						
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');								
					$mpdf->WriteHTML('</tr>');
				}
				elseif($invoice_type=='transaction')
				{  
				
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');
						
					$mpdf->WriteHTML('</tr>');
				
				} 
				elseif($invoice_type=='sell_gift')
				{  
				
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');
						
					$mpdf->WriteHTML('</tr>');
				
				} 
				elseif($invoice_type=='pledges')
				{  
				
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');
						
					$mpdf->WriteHTML('</tr>');
				
				} 
				else
				{ 						
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');						
					$mpdf->WriteHTML('</tr>');
				}	
				
			$mpdf->WriteHTML('<tbody>');
			
				if(!empty($income_data) || !empty($expense_data))
				{
					$id=1;
					$total_amount=0;
					if(!empty($expense_data))
					$income_data=$expense_data;
					$church_all_income=$obj_payment->MJ_cmgt_get_single_income_data_by_invoice_id($income_data->invoice_id);
					foreach($church_all_income as $result_income)
					{
						$income_entries=json_decode($result_income->entry);
						foreach($income_entries as $each_entry)
						{
							$total_amount+=$each_entry->amount;
							$total_amount1=$each_entry->amount;
							$mpdf->WriteHTML('<tr class="entry_list">');
								$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.$id.'</td>');
								$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.date(MJ_cmgt_date_formate(),strtotime(esc_attr($result_income->invoice_date))).'</td>');
								$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.esc_attr($each_entry->entry).'</td>');
								$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. MJ_cmgt_church_get_display_name(esc_attr($result_income->receiver_id)).'</td>');
								$mpdf->WriteHTML('<td class="align_right padding_10_pdf"><span style="font-size:14px;">'.$currency_symbol.' '. number_format($total_amount1,2).'</span></td>');
							$mpdf->WriteHTML('</tr>');
							
							$id+=1;
						
						}
					}
				}
				if(!empty($sell_gift_data))
				{
					
					$id=1;
					$total_amount=0;
					$total_amount=$sell_gift_data->gift_price;
					$mpdf->WriteHTML('<tr class="entry_list">');
							$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. $id.'</td>');
							$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date)).'</td>');
							$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.MJ_cmgt_church_get_gift_name($sell_gift_data->gift_id).'</td>');
							$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. MJ_cmgt_church_get_display_name($sell_gift_data->created_by).'</td>');
							$mpdf->WriteHTML('<td class="align_right padding_10_pdf"><span style="font-size:14px;">'.$currency_symbol.' '.number_format($sell_gift_data->gift_price,2).'</span></td>');
					$mpdf->WriteHTML('</tr>');
					
				}
				if(!empty($pledges_data))
				{
					$id=1;
					$total_amount=0;
					$total_amount=$pledges_data->total_amount;
					
					$mpdf->WriteHTML('<tr class="entry_list">');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. $id.'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. date(MJ_cmgt_date_formate(),strtotime($pledges_data->start_date)).'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.  esc_html__('Pledge','church_mgt').'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. MJ_cmgt_church_get_display_name($pledges_data->created_by).'</td>');
						$mpdf->WriteHTML('<td class="align_right padding_10_pdf">'.$currency_symbol.' '. number_format($pledges_data->total_amount,2).'</td>');
					$mpdf->WriteHTML('</tr>');
					
				}
			
				if(!empty($transaction_data))
				{
						$id=1;
						$total_amount=0;
						$total_amount=$transaction_data->amount;
					
					$mpdf->WriteHTML('<tr class="entry_list">');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. $id.'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. date(MJ_cmgt_date_formate(),strtotime(esc_attr($transaction_data->transaction_date))).'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. get_the_title(esc_attr($transaction_data->donetion_type)).'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. MJ_cmgt_church_get_display_name(esc_attr($transaction_data->created_by)).'</td>');
						$mpdf->WriteHTML('<td class="align_right padding_10_pdf">'.$currency_symbol.' '. esc_attr($transaction_data->amount).'</td>');
					$mpdf->WriteHTML('</tr>');
				} 			
								
			$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<table class="width_97"   border="0" >');
			$mpdf->WriteHTML('<tbody>');
				$mpdf->WriteHTML('<tr>');
					$mpdf->WriteHTML('<td style=" color: #818386 !important;" class="width_70 align_right model_body_amount_label"><label style="font-size: 18px !important;" class="margin h4_pdf pdf_amount_label">'.esc_html__('Subtotal :','church_mgt').'</label></td>');
					$mpdf->WriteHTML('<td class="align_right amount_padding_8 model_body_amount_value"> <h4 class="margin h4_pdf"><span>'.$currency_symbol.'</span>'. number_format($total_amount,2).'</h4></td>');
				$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('<tr class="grand_total_lable" style=" background-color: '.get_option('cmgt_system_color_code').' !important;">');		
					$mpdf->WriteHTML('<td style=" background-color: '.get_option('cmgt_system_color_code').' !important; margin-right: 5px;" class="align_right grand_total_lable grand_total_lable1 padding_11 "><h3 class=" color_white invoice_total_label margin font_family" style="font-size: 19px;font-weight: bold;">'.esc_html__('Grand Total :','church_mgt').'</h3></td>');
					$mpdf->WriteHTML('<td style=" background-color: '.get_option('cmgt_system_color_code').' !important;" class="align_right grand_total_amount amount_padding_8"><h3 class="color_white margin invoice_total_value" style="font-size: 19px;font-weight: bold;"> <span>'.$currency_symbol.'</span> '.number_format((float)$total_amount,2,'.','').'</h3></td>');		
				$mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('</div>');

		$mpdf->WriteHTML("</body>");

		$mpdf->WriteHTML("</html>");
	
		$mpdf->Output();	

	ob_end_flush();

	unset($mpdf);	
} 
function MJ_cmgt_generate_invoice_pdf($invoice_id,$invoice_type)
{
	$currency_symbol=MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
	
	$obj_payment = new Cmgtpayment;
	$obj_gift=new Cmgtgift;
	$obj_pledges = new Cmgtpledes;
	if($invoice_type=='income')
	{
		$income_data=$obj_payment->MJ_cmgt_get_income_data($invoice_id);
	}
	if($invoice_type=='expense')
	{
		$expense_data=$obj_payment->MJ_cmgt_get_income_data($invoice_id);
	}
	if($invoice_type=='transaction')
	{
		$transaction_data=MJ_cmgt_get_transaction_data($invoice_id);
	}
	if($invoice_type=='sell_gift')
	{
		$sell_gift_data=$obj_gift->MJ_cmgt_get_single_sell_gift($invoice_id);
	}
	if($invoice_type=='pledges')
	{
		$pledges_data=MJ_cmgt_get_pledges_data($invoice_id);
	}
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/dynamic_css.php', __FILE__).'"></link>';

	echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>';

	   if (is_rtl())

	   {

		?>					 	

		<link rel="stylesheet" type="text/css"	href="<?php echo CMS_PLUGIN_URL.'/assets/css/custom-rtl.css'; ?>"/>					 	

	   <?php

	   }
	   ?>
	   <?php
	   
   ob_clean();
 
   header('Content-type: application/pdf');
	
   header('Content-Disposition: inline; filename="invoice.pdf"');

   header('Content-Transfer-Encoding: binary');

   header('Accept-Ranges: bytes');

   require_once CMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';

   $stylesheet = file_get_contents(CMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content

	$mpdf = new \Mpdf\Mpdf;

	// $mpdf->debug = true;
	$mpdf->autoScriptToLang = true;
	$mpdf->autoLangToFont = true;
	
		if (is_rtl())
		{
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			$mpdf->SetDirectionality('rtl');
		}   

	$mpdf->WriteHTML('<html>');

	$mpdf->WriteHTML('<head>');

	$mpdf->WriteHTML('<style></style>');

	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf

	$mpdf->WriteHTML('</head>');

	if (is_rtl())

	{
	   $mpdf->WriteHTML('<body class="direction_rtl" style="font-family: "Poppins" !important;">');			 	
	}

	else
	{
	   $mpdf->WriteHTML('<body>');
	}	
	$mpdf->SetTitle('Invoice');	
	
	
			if (is_rtl())

			{
		
				$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/church-management/assets/images/invoice.jpg').'" class="rtl1">');
		
				$mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print" class="rtl2">');
		
				
		
			}	else{
		
				$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/church-management/assets/images/invoice.jpg').'" width="100%">');
		
				$mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print">');
		
			} 
			$mpdf->WriteHTML('<h4 class="modal-title margin_top_0px">'.get_option('cmgt_system_name').'</h4>');
			$mpdf->WriteHTML('<table class="width_70_print" border="0">');					
				$mpdf->WriteHTML('<tbody>');
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td class="width_70_print">');
							$mpdf->WriteHTML('<img class="system_logo system_logo_print" src="'.get_option( 'cmgt_church_other_data_logo' ).'">');
						$mpdf->WriteHTML('</td>');						
						$mpdf->WriteHTML('<td class="only_width_20_print">');	

								$mpdf->WriteHTML('<table border="0">');					

									$mpdf->WriteHTML('<tbody>');

										$mpdf->WriteHTML('<tr>');

											$mpdf->WriteHTML('<td>');

												$mpdf->WriteHTML('<label class="popup_label_heading">'.esc_html__('Address ','church_mgt').'');

												$address_length=strlen(get_option( 'cmgt_church_address' ));

													if($address_length>120)

													{												

														$mpdf->WriteHTML('<BR><BR><BR><BR><BR><BR>');

													}

													elseif($address_length>90)

													{	

														$mpdf->WriteHTML('<BR><BR><BR><BR><BR>');

													}

													elseif($address_length>60)

													{

														$mpdf->WriteHTML('<BR><BR><BR><BR>');

													}

													elseif($address_length>30)

													{

													$mpdf->WriteHTML('<BR><BR><BR>');

													}

											$mpdf->WriteHTML('</label></td>');	

											$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family"><label for="" class="label_value"> : '.chunk_split(get_option( 'cmgt_church_address' ),30,"<BR>").'');

											$mpdf->WriteHTML('</label></td>');

										$mpdf->WriteHTML('</tr>');

										$mpdf->WriteHTML('<tr>');				

											$mpdf->WriteHTML('<td><label class="popup_label_heading">'.esc_html__('Email','church_mgt').' ');

											$mpdf->WriteHTML('</label></td>');	

											$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family"><label for="" class="label_value">: '.get_option( 'cmgt_email' )."<br>".'');

											$mpdf->WriteHTML('</label></td>');	

										$mpdf->WriteHTML('</tr>');

										$mpdf->WriteHTML('<tr>');

											$mpdf->WriteHTML('<td>');

												$mpdf->WriteHTML('<label class="popup_label_heading">'.esc_html__('Phone ','church_mgt').'');

											$mpdf->WriteHTML(' </label></td>');	

											$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family"><label for="" class="label_value">: +'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_option( 'cmgt_contact_number' )."<br>".'');

											$mpdf->WriteHTML('</label></td>');

										$mpdf->WriteHTML('</tr>');

									$mpdf->WriteHTML('</tbody>');

								$mpdf->WriteHTML('</table>');

								$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('<td align="right" class="width_24">');
						$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');

			$mpdf->WriteHTML('<table>');
				$mpdf->WriteHTML('<tr>');
					$mpdf->WriteHTML('<td class="width_65">');

						$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
							$mpdf->WriteHTML('<tbody>');				
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td colspan="2" class="billed_to_print" >');	
									$mpdf->WriteHTML('<h3 class="font_family"> '.esc_html__('Bill To','church_mgt').' : </h3>');
									if(!empty($expense_data))
									{
										$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($expense_data->supplier_name),30,"<BR>").'</h3>'); 
									}
									else
									{
										if(!empty($income_data))
											$member_id= sanitize_text_field($income_data->supplier_name);
											if(!empty($transaction_data))
											$member_id= sanitize_text_field($transaction_data->member_id);
											
											if(!empty($sell_gift_data))
											$member_id= sanitize_text_field($sell_gift_data->member_id);
										if(!empty($pledges_data))
											$member_id= sanitize_text_field($pledges_data->member_id);
										$bill_to=get_userdata($member_id);
										
										$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($bill_to->display_name),30,"<BR>").'</h3>'); 
									}  	
								$mpdf->WriteHTML('</td>');
								$mpdf->WriteHTML('</tr>');
								$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_40_print">');
									if(!empty($expense_data))
									{
										$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($expense_data->supplier_name),30,"<BR>").'</h3>'); 
									}
									else
									{
										if(!empty($income_data))
											$member_id= sanitize_text_field($income_data->supplier_name);
											if(!empty($transaction_data))
											$member_id= sanitize_text_field($transaction_data->member_id);
											
											if(!empty($sell_gift_data))
											$member_id= sanitize_text_field($sell_gift_data->member_id);
										if(!empty($pledges_data))
											$member_id= sanitize_text_field($pledges_data->member_id);
										$bill_to=get_userdata($member_id);
										
										$address=get_user_meta( $member_id,'address',true);									
										$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").'');  
										$mpdf->WriteHTML('+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta( $member_id,'mobile',true ).'<br>');   
									}  	
									$mpdf->WriteHTML('</td>');	
								$mpdf->WriteHTML('</tr>');								
							$mpdf->WriteHTML('</tbody>');
						$mpdf->WriteHTML('</table>');
						
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td class="width_30">');
					
						$issue_date='DD-MM-YYYY';
									if(!empty($income_data))
									{
										$issue_date= sanitize_text_field($income_data->invoice_date);
										$payment_status=sanitize_text_field($income_data->payment_status);
										$invoice_no=sanitize_text_field($income_data->invoice_id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($income_data->invoice_id)); 
									}
									if(!empty($expense_data))
									{
										$issue_date=sanitize_text_field($expense_data->invoice_date);
										$payment_status=sanitize_text_field($expense_data->payment_status);
										$invoice_no=sanitize_text_field($expense_data->invoice_id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($expense_data->invoice_id)); 
									}
									if(!empty($transaction_data))
									{
										$issue_date=sanitize_text_field($transaction_data->created_date);
										$invoice_no=sanitize_text_field($transaction_data->id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($transaction_data->id)); 
									}
									if(!empty($sell_gift_data))
									{
										$issue_date=sanitize_text_field($sell_gift_data->sell_date);						
										$invoice_no=sanitize_text_field($sell_gift_data->id);
										$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($sell_gift_data->id)); 
									}
									if(!empty($pledges_data))
									{
										$issue_date=sanitize_text_field($pledges_data->created_date);						
										$invoice_no=sanitize_text_field($pledges_data->id);
										$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($pledges_data->id)); 
									} 
								
						$mpdf->WriteHTML('<table class=""  border="0">');
							$mpdf->WriteHTML('<tbody>');
								if($invoice_type!='expense')
								{				
									$mpdf->WriteHTML('<tr>');	
										
									
										$mpdf->WriteHTML('<td class="width_50_heading invoice_lable align_center" style="background-color: '.get_option('cmgt_system_color_code').' !important;">');
										
										
										
											$mpdf->WriteHTML('<h3 class="invoice_color invoice_lable"  style="background-color: '.get_option('cmgt_system_color_code').' !important;"><span class="font_12">'.esc_html__('INVOICE','church_mgt').' #</span><span class="font_18">'.get_option( 'cmgt_payment_prefix' ).' '.$invoice_number.'</span></h3>');	
										
							
																		
										$mpdf->WriteHTML('</td>');
									$mpdf->WriteHTML('</tr>');
								}
								$mpdf->WriteHTML('<tr>');	
									$mpdf->WriteHTML('<td class="width_20_print padding_right_30" align="left">');
									$issue_date=MJ_cmgt_date_formate();
												if(!empty($income_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($income_data->invoice_date));
													$payment_status=sanitize_text_field($income_data->payment_status);
												}
												if(!empty($expense_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($expense_data->invoice_date));
													$payment_status=sanitize_text_field($expense_data->payment_status);
												}
												if(!empty($sell_gift_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));
												}
												if(!empty($transaction_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($transaction_data->transaction_date));
												}
												if(!empty($pledges_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($pledges_data->created_date));
												}
											
									$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'. esc_html__('Date','church_mgt').' : '.$issue_date.'</h5><br>');
									if($invoice_type=='expense' || $invoice_type=='income')
									{
									$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'. esc_html__('Status','church_mgt').' : '.esc_html__( $payment_status,'church_mgt').'</h5>');
									}
									$mpdf->WriteHTML('</td>');							
								$mpdf->WriteHTML('</tr>');						
							$mpdf->WriteHTML('</tbody>');
						$mpdf->WriteHTML('</table>');	
					$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');

			if($invoice_type=='expense')
			{	
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Expense Entries','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');				
				
			}		
			elseif($invoice_type=='transaction')
			{ 
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Transaction Entries','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
			
			}
			elseif($invoice_type=='sell_gift')
			{ 
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Sell Gift','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
			  
			}
			elseif($invoice_type=='pledges')
			{ 
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Pledges Entries','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
			  
			}
			else
			{ 
				$mpdf->WriteHTML('<table class="width_100">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td>');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__('Income Entries','church_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');	
			}	
			$mpdf->WriteHTML('<table class="table model_invoice_table" class="width_100_print" border="0">');
			$mpdf->WriteHTML('<thead>');	
				
				if($invoice_type=='income || expense')
				{						
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');								
					$mpdf->WriteHTML('</tr>');
				}
				elseif($invoice_type=='transaction')
				{  
				
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');
						
					$mpdf->WriteHTML('</tr>');
				
				} 
				elseif($invoice_type=='sell_gift')
				{  
				
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');
						
					$mpdf->WriteHTML('</tr>');
				
				} 
				elseif($invoice_type=='pledges')
				{  
				
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');
						
					$mpdf->WriteHTML('</tr>');
				
				} 
				else
				{ 						
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">#</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Date','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Description','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_center padding_10_pdf font_family">'.esc_html__('Issue By','church_mgt').'</th>');
						$mpdf->WriteHTML('<th class="entry_table_heading entry_heading align_right padding_10_pdf font_family">'.esc_html__('Amount','church_mgt').'</th>');						
					$mpdf->WriteHTML('</tr>');
				}	
				
			$mpdf->WriteHTML('<tbody>');
			
				if(!empty($income_data) || !empty($expense_data))
				{
					$id=1;
					$total_amount=0;
					if(!empty($expense_data))
					$income_data=$expense_data;
					$church_all_income=$obj_payment->MJ_cmgt_get_single_income_data_by_invoice_id($income_data->invoice_id);
					foreach($church_all_income as $result_income)
					{
						$income_entries=json_decode($result_income->entry);
						foreach($income_entries as $each_entry)
						{
							$total_amount+=$each_entry->amount;
							$total_amount1=$each_entry->amount;
							$mpdf->WriteHTML('<tr class="entry_list">');
								$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.$id.'</td>');
								$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.date(MJ_cmgt_date_formate(),strtotime(esc_attr($result_income->invoice_date))).'</td>');
								$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.esc_attr($each_entry->entry).'</td>');
								$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. MJ_cmgt_church_get_display_name(esc_attr($result_income->receiver_id)).'</td>');
								$mpdf->WriteHTML('<td class="align_right padding_10_pdf"><span style="font-size:14px;">'.$currency_symbol.' '. number_format($total_amount1,2).'</span></td>');
							$mpdf->WriteHTML('</tr>');
							
							$id+=1;
						
						}
					}
				}
				if(!empty($sell_gift_data))
				{
					
					$id=1;
					$total_amount=0;
					$total_amount=$sell_gift_data->gift_price;
					$mpdf->WriteHTML('<tr class="entry_list">');
							$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. $id.'</td>');
							$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date)).'</td>');
							$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.MJ_cmgt_church_get_gift_name($sell_gift_data->gift_id).'</td>');
							$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. MJ_cmgt_church_get_display_name($sell_gift_data->created_by).'</td>');
							$mpdf->WriteHTML('<td class="align_right padding_10_pdf"><span style="font-size:14px;">'.$currency_symbol.' '.number_format($sell_gift_data->gift_price,2).'</span></td>');
					$mpdf->WriteHTML('</tr>');
					
				}
				if(!empty($pledges_data))
				{
					$id=1;
					$total_amount=0;
					$total_amount=$pledges_data->total_amount;
					
					$mpdf->WriteHTML('<tr class="entry_list">');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. $id.'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. date(MJ_cmgt_date_formate(),strtotime($pledges_data->start_date)).'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'.  esc_html__('Pledge','church_mgt').'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. MJ_cmgt_church_get_display_name($pledges_data->created_by).'</td>');
						$mpdf->WriteHTML('<td class="align_right padding_10_pdf">'.$currency_symbol.' '. number_format($pledges_data->total_amount,2).'</td>');
					$mpdf->WriteHTML('</tr>');
					
				}
			
				if(!empty($transaction_data))
				{
						$id=1;
						$total_amount=0;
						$total_amount=$transaction_data->amount;
					
					$mpdf->WriteHTML('<tr class="entry_list">');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. $id.'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. date(MJ_cmgt_date_formate(),strtotime(esc_attr($transaction_data->transaction_date))).'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. get_the_title(esc_attr($transaction_data->donetion_type)).'</td>');
						$mpdf->WriteHTML('<td class="text-aline-center padding_10_pdf">'. MJ_cmgt_church_get_display_name(esc_attr($transaction_data->created_by)).'</td>');
						$mpdf->WriteHTML('<td class="align_right padding_10_pdf">'.$currency_symbol.' '. esc_attr($transaction_data->amount).'</td>');
					$mpdf->WriteHTML('</tr>');
				} 			
								
			$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<table class="width_97"   border="0" >');
			$mpdf->WriteHTML('<tbody>');
				$mpdf->WriteHTML('<tr>');
					$mpdf->WriteHTML('<td style=" color: #818386 !important;" class="width_70 align_right model_body_amount_label"><label style="font-size: 18px !important;" class="margin h4_pdf pdf_amount_label">'.esc_html__('Subtotal :','church_mgt').'</label></td>');
					$mpdf->WriteHTML('<td class="align_right amount_padding_8 model_body_amount_value"> <h4 class="margin h4_pdf"><span>'.$currency_symbol.'</span>'. number_format($total_amount,2).'</h4></td>');
				$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('<tr class="grand_total_lable" style=" background-color: '.get_option('cmgt_system_color_code').' !important;">');		
					$mpdf->WriteHTML('<td style=" background-color: '.get_option('cmgt_system_color_code').' !important; margin-right: 5px;" class="align_right grand_total_lable grand_total_lable1 padding_11 "><h3 class=" color_white invoice_total_label margin font_family" style="font-size: 19px;font-weight: bold;">'.esc_html__('Grand Total :','church_mgt').'</h3></td>');
					$mpdf->WriteHTML('<td style=" background-color: '.get_option('cmgt_system_color_code').' !important;" class="align_right grand_total_amount amount_padding_8"><h3 class="color_white margin invoice_total_value" style="font-size: 19px;font-weight: bold;"> <span>'.$currency_symbol.'</span> '.number_format((float)$total_amount,2,'.','').'</h3></td>');		
				$mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('</div>');

		$mpdf->WriteHTML("</body>");

		$mpdf->WriteHTML("</html>");


		$upload_dir = wp_upload_dir();
		$invoice_dir = WP_CONTENT_DIR;
	
		$invoice_dir .= '/uploads/invoice_pdf/';
	
		$invoice_path = $invoice_dir;
	
		$currentdate = date("d-M-Y");

		if($invoice_type=='income')
		{
			$member_id=$income_data->supplier_name;
		}
		if($invoice_type=='expense')
		{
			$member_id=$expense_data->supplier_name;
		}
		if($invoice_type=='transaction')
		{
			$member_id=$transaction_data->member_id;
		}
		if($invoice_type=='sell_gift')
		{
			$member_id=$sell_gift_data->member_id;
		}
		if($invoice_type=='pledges')
		{
			$member_id=$pledges_data->member_id;
		}

		$member=get_userdata($member_id);
	
		if($invoice_type=='expense')
		{
			$membername = $member_id;
		}else{
			$membername = $member->display_name;
		}
	
		$pdf_name = $membername."(".$currentdate.")";
	
		mkdir($invoice_path, 0777, true);
	
		$mpdf->Output( WP_CONTENT_DIR . '/uploads/invoice_pdf/'.$membername.'_'.$invoice_id.'.pdf','F');


	
		// $mpdf->Output();	

	ob_end_flush();

	unset($mpdf);	
} 

function MJ_cmgt_get_day_number($value)
{
	if($value == "Sunday")
	{
		$day_number = 0;
	}
	elseif($value == "Monday")
	{
		$day_number = 1;
	}
	elseif($value == "Tuesday")
	{
		$day_number = 2;
	}
	elseif($value == "Wednesday")
	{
		$day_number = 3;
	}
	elseif($value == "Thursday")
	{
		$day_number = 4;
	}
	elseif($value == "Friday")
	{
		$day_number = 5;
	}
	elseif($value == "Saturday")
	{
		$day_number = 6;
	}
	return $day_number;
}

function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
{
	$startDate = strtotime($startDate);
	$endDate = strtotime($endDate);

	$dateArr = array();

	do
	{
		if(date("w", $startDate) != $weekdayNumber)
		{
			$startDate += (24 * 3600); // add 1 day
		}
	} while(date("w", $startDate) != $weekdayNumber);


	while($startDate <= $endDate)
	{
		$dateArr[] = date('Y-m-d', $startDate);
		$startDate += (7 * 24 * 3600); // add 7 days
	}

	return($dateArr);
}

function get_between_date_array($startDate,$endDate,$day)
{
	$start_date = date("m/d/Y", strtotime($startDate));
    $end_date   = date("m/d/Y", strtotime($endDate));
    $start = strtotime($start_date);
    $end = strtotime($end_date);

    $month = $start;
    $months[] = date('Y-m', $start);
    while($month < $end) 
	{
		$months[] = date('Y-m', $month);
      	$month = strtotime("+1 month", $month);
    }
	$month_new_array = array_unique($months);
    foreach($month_new_array as $mon)
    {
        $mon_arr = explode( "-", $mon);
        $y = $mon_arr[0];
        $m = $mon_arr[1];
        $start_dates_arr[] = date("Y-m-d", strtotime($m.'/'.$day.'/'.$y.' 00:00:00'));
    }
	return $start_dates_arr;
}

function get_yearly_between_date_array($startDate,$endDate,$date)
{
	$start_date = date("m/d/Y", strtotime($startDate));
    $end_date   = date("m/d/Y", strtotime($endDate));
    $start = strtotime($start_date);
    $end = strtotime($end_date);

    $year = $start;
    $years[] = date('Y', $start);

	while($year < $end) 
	{
		$years[] = date('Y', $year);
      	$year = strtotime("+1 year", $year);
    }
	$year_new_array = array_unique($years);
	foreach($year_new_array as $year)
    {
		$year_arr = explode( "-", $date);
        $d = $year_arr[2];
        $m = $year_arr[1];
		$start_dates_arr[] = date("Y-m-d", strtotime($m.'/'.$d.'/'.$year));
    }
	
	return $start_dates_arr;
}

add_action( 'wp_ajax_MJ_cmgt_load_group_by_activity_id', 'MJ_cmgt_load_group_by_activity_id');
add_action( 'wp_ajax_nopriv_MJ_cmgt_load_group_by_activity_id', 'MJ_cmgt_load_group_by_activity_id');
function MJ_cmgt_load_group_by_activity_id()
{
	$obj_group=new Cmgtgroup;
	$activity_id = $_REQUEST['activity_id'];
	$obj_group=new Cmgtgroup;
	global $wpdb;
	$table_activity = $wpdb->prefix. 'cmgt_activity';
	$result = $wpdb->get_row("SELECT * FROM $table_activity where activity_id=".$activity_id);
	$groups_array =(explode(",",$result->groups));
	$members='';
	$all_members=array();
	$group_id=0; 
	?>
	<option value=""><?php _e('Select Group Name','church_mgt');?></option>
	<option value="member" <?php selected('member',$group_id)?>><?php _e('All Members','church_mgt');?></option>	
	<optgroup label="<?php _e('Group','church_mgt'); ?>" style = "text-transform: capitalize;">
	<?php
	$i = 0;
	$j = 0;
	$new_val = array();

	foreach($groups_array as $key_1=>$val_1)
	{ 
		$new_val[$i] = $val_1;
		$i++;
	}
	for ($j = 0; $j < $i; $j++) 
	{
		$groupdata_new=$obj_group->MJ_cmgt_get_single_group($new_val[$j]);
		
		?>
		
		<option  value="<?php echo esc_attr($groupdata_new->id);?>" <?php selected($groupdata_new->id,$group_id)?>><?php echo esc_attr($groupdata_new->group_name); ?></option>
		
		<?php 
		
	}
	
	die();
}

//--- Datatable Heder Display show class ----//
function MJ_cmgt_datatable_heder()
{
	$datatbl_heder_value = get_option( 'cmgt_header_enable' ); 
	if($datatbl_heder_value == "no")
	{
		$cmgt_heder_none= "cmgt_heder_none";
	}
	else
	{		
		$cmgt_heder_none= "cmgt_heder_block";
	}
	return $cmgt_heder_none;
	
}
add_action( 'wp_ajax_nopriv_get_data_razorpay', 'get_data_razorpay' );
add_action( 'wp_ajax_get_data_razorpay', 'get_data_razorpay' );
function get_data_razorpay() {

	$transaction = new Cmgttransaction();
	$data['member_id']=$_REQUEST['member_id'];
	$data['amount']= sanitize_text_field($_REQUEST['amount']);
	$data['donetion_type']= sanitize_text_field($_REQUEST['donetion_type']);
	$data['pay_method']=$_REQUEST['payment_method'];	
	$data['transaction_id']=$_REQUEST['transaction_id'];
	$data['created_date']=date("Y-m-d");
	$data['created_by']=$_REQUEST['created_by'];
	$data['transaction_date']=date('Y-m-d');
	$data['description']=$_REQUEST['description'];
	 
	$result = $transaction->MJ_cmgt_add_transaction($data);	
	echo $result;
	die();
}
function MJ_cmgt_user_roles($user_id)
{
	$user = get_userdata( $user_id );
	$user_roles = $user->roles;
	return $user_roles;
}

function MJ_cmgt_add_check_access_for_view($page_name)
{
	$user_roles=MJ_cmgt_user_roles(get_current_user_id());
	if(in_array('management', $user_roles))
	{
		$menu = get_option('cmgt_access_right_management');
		if(!empty($menu))
		{
			foreach ( $menu as $key1=>$value1 )
			{	
				foreach ( $value1 as $key=>$value ) 
				{	
					if ($page_name == $value['page_link'])
					{	
						return $value;
					}
				}
			}
		}
	}
	else
	{
		return 'administrator';
	}
}
function MJ_cmgt_add_check_access_for_view_add($page_name,$action)
{
	$user_roles=MJ_cmgt_user_roles(get_current_user_id());
	if(in_array('management', $user_roles))
	{
		$menu = get_option('cmgt_access_right_management');
		if(!empty($menu))
		{
			foreach ( $menu as $key1=>$value1 )
			{	
				foreach ( $value1 as $key=>$value ) 
				{	
					if ($page_name == $value['page_link'])
					{	
						return $value[$action];
					}
				}
			}
		}
	}
	else
	{
		return 1;
	}
}

//access right page not access message admin side //
function mj_cmgt_access_right_page_not_access_message_admin_side()
{
	?>
	<script type="text/javascript">
		$(document).ready(function() 
		{	
			alert('<?php esc_attr_e('You do not have permission to perform this operation.','church_mgt');?>');
			window.location.href='?page=cmgt-church_system';
		});
	</script>
<?php
}
function mj_cmgt_all_date_type_value($date_type)
{
	$array_res = array();
	$start_date = "";
	$end_date = "";
	if($date_type=="today")
	{
		$start_date = date('Y-m-d');
		$end_date= date('Y-m-d');
	}
	elseif($date_type=="this_week")
	{
		//check the current day
		if(date('D')!='Mon')
		{    
		//take the last monday
		$start_date = date('Y-m-d',strtotime('last sunday'));    

		}else{
			$start_date = date('Y-m-d');   
		}
		//always next saturday
		if(date('D')!='Sat')
		{
			$end_date = date('Y-m-d',strtotime('next saturday'));
		}else{
			$end_date = date('Y-m-d');
		}
	}
	elseif($date_type=="last_week")
	{
		$previous_week = strtotime("-1 week +1 day");
		$start_week = strtotime("last sunday midnight",$previous_week);
		$end_week = strtotime("next saturday",$start_week);

		$start_date = date("Y-m-d",$start_week);
		$end_date = date("Y-m-d",$end_week);
	}
	elseif($date_type=="this_month")
	{
		$start_date = date('Y-m-d',strtotime('first day of this month'));
		$end_date = date('Y-m-d',strtotime('last day of this month'));
	}
	elseif($date_type=="last_month")
	{
		$start_date = date('Y-m-d',strtotime("first day of previous month"));
		$end_date =  date('Y-m-d',strtotime("last day of previous month"));
	}
	elseif($date_type=="last_3_month")
	{
		$month_date =  date('Y-m-d', strtotime('-2 month'));
		$start_date = date("Y-m-01", strtotime($month_date));
		$end_date = date('Y-m-d',strtotime('last day of this month'));
		
	}
	elseif($date_type=="last_6_month")
	{
		$month_date =  date('Y-m-d', strtotime('-5 month'));
		$start_date = date("Y-m-01", strtotime($month_date));
		$end_date = date('Y-m-d',strtotime('last day of this month'));
	}
	elseif($date_type=="last_12_month")
	{
		$month_date =  date('Y-m-d', strtotime('-11 month'));
		$start_date = date("Y-m-01", strtotime($month_date));
		$end_date = date('Y-m-d',strtotime('last day of this month'));
	}
	elseif($date_type=="this_year")
	{
		$start_date = date("Y-01-01", strtotime("0 year"));
		$end_date = date("Y-12-t", strtotime($start_date));

	}
	elseif($date_type=="last_year")
	{
		$start_date = date("Y-01-01", strtotime("-1 year"));
		$end_date = date("Y-12-t", strtotime($start_date));
	}
	elseif($date_type=="period")
	{
		//$result= mj_smgt_admission_repot_load_date();

	}
	$array_res[] = $start_date;
	$array_res[] = $end_date;
	return json_encode($array_res);
}

add_action( 'wp_ajax_mj_cmgt_admission_repot_load_date', 'mj_cmgt_admission_repot_load_date');
add_action( 'wp_ajax_nopriv_mj_cmgt_admission_repot_load_date',  'mj_cmgt_admission_repot_load_date');

function mj_cmgt_admission_repot_load_date()
{
	 $date_type = $_REQUEST['date_type'];
	 ?>
	
	<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			"use strict";	
			$("#report_sdate").datepicker({
				dateFormat: "yy-mm-dd",
				changeYear: true,
				changeMonth: true,
				maxDate:0,
				onSelect: function (selected) {
					var dt = new Date(selected);
					dt.setDate(dt.getDate() + 0);
					$("#report_edate").datepicker("option", "minDate", dt);
				}
			});

			$("#report_edate").datepicker({
			dateFormat: "yy-mm-dd",
			changeYear: true,
			changeMonth: true,
			maxDate:0,
				onSelect: function (selected) {
					var dt = new Date(selected);
					dt.setDate(dt.getDate() - 0);
					$("#report_sdate").datepicker("option", "maxDate", dt);
				}
			});
		} );
	</script>
	<?php
	if($date_type=='period')
	{ 
		?>
		<div class="row">
		<div class="col-md-6 mb-2">
			<div class="form-group input">
				<div class="col-md-12 form-control">
					<input type="text" id="report_sdate" class="form-control" name="start_date" value="<?php if(isset($_REQUEST['start_date'])) echo $_REQUEST['start_date'];else echo date('Y-m-d');?>" readonly>
					<label for="userinput1" class="active"><?php esc_html_e('Start Date','school-mgt');?></label>
				</div>
			</div>
		</div>
		<div class="col-md-6 mb-2">
			<div class="form-group input">
				<div class="col-md-12 form-control">
					<input type="text" id="report_edate" class="form-control" name="end_date" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['end_date'];else echo date('Y-m-d');?>" readonly>
					<label for="userinput1" class="active"><?php esc_html_e('End Date','school-mgt');?></label>
				</div>
			</div>
		</div> 
		</div>
		<?php 
	} 
	die();
	
} 
function mj_cmgt_get_total_income($start_date,$end_date)
{
	global $wpdb;
	$table_income=$wpdb->prefix.'cmgt_income_expense';
	$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type = 'income' AND invoice_date BETWEEN '$start_date' AND '$end_date' ");
	return $result;
}
function mj_cmgt_get_total_expense($start_date,$end_date)
{
 	global $wpdb;
	$table_income=$wpdb->prefix.'cmgt_income_expense';
	$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type = 'expense' AND invoice_date BETWEEN '$start_date' AND '$end_date' ");
	return $result;
}
function mj_cmgt_get_all_members()
{
	$members = get_users(array('role'=>'member'));
	return $members;
}
function mj_cmgt_get_all_regular_member()
{
	$regular_member = get_users(
		array(
			'role' => 'member',
			'meta_query' => array(
				array(
					'key' => 'volunteer',
					'value' => 'no',
					'compare' => '=='
				)
			)
		)
	);
	return $regular_member;
}
function mj_cmgt_get_all_volunteer_member()
{
	$volunteer_member = get_users(
		array(
			'role' => 'member',
			'meta_query' => array(
				array(
					'key' => 'volunteer',
					'value' => 'yes',
					'compare' => '=='
				)
			)
		)
	);
	return $volunteer_member;
}
function mj_cmgt_get_all_family_member()
{
	$family_member = get_users(
		array(
			'role' => 'family_member',
		)
	);		
	return $family_member;
}
function mj_cmgt_get_all_accountant()
{
	$accountant = get_users(
		array(
			'role' => 'accountant',
		)
	);		
	return $accountant;
}
function mj_cmgt_get_all_management()
{
	$management = get_users(
		array(
			'role' => 'management',
		)
	);		
	return $management;
}
function mj_cmgt_get_all_income()
{
	$role = MJ_cmgt_get_user_role(get_current_user_id());
	$user_id =get_current_user_id();
	$total_income = 0;
	global $wpdb;
	$table_income=$wpdb->prefix.'cmgt_income_expense';
	if($role == "member")
	{
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type = 'income' AND supplier_name=$user_id");
	}else{
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type = 'income' ");
	}
		
	
	if(!empty($result)){
		foreach($result as $retrive_data)
		{
			$income_array = json_decode($retrive_data->entry);
			foreach($income_array as $amount)
			{
				$total_income += $amount->amount;
			}
		}
	}
	return $total_income;
}
function mj_cmgt_get_all_expense()
{
	$role = MJ_cmgt_get_user_role(get_current_user_id());
	$user_id =get_current_user_id();
	$total_expense = 0;
	global $wpdb;
	$table_expense=$wpdb->prefix.'cmgt_income_expense';
	if($role == "member")
	{
		$result = $wpdb->get_results("SELECT * FROM $table_expense where invoice_type = 'expense'  AND supplier_name=$user_id");
	}else{
		$result = $wpdb->get_results("SELECT * FROM $table_expense where invoice_type = 'expense' ");
	}
	
	if(!empty($result)){
		foreach($result as $retrive_data)
		{
			$expense_array = json_decode($retrive_data->entry);
			foreach($expense_array as $amount)
			{
				$total_expense += $amount->amount;
			}
		}
	}
	return $total_expense;
}
function mj_cmgt_get_netprofit()
{
	$role = MJ_cmgt_get_user_role(get_current_user_id());
	$user_id =get_current_user_id();
	$total_income = 0;
	$total_expense = 0;
	global $wpdb;
	$table_expense=$wpdb->prefix.'cmgt_income_expense';
	if($role == "member")
	{
		$income = $wpdb->get_results("SELECT * FROM $table_expense where invoice_type = 'income' AND supplier_name=$user_id");
		$expense = $wpdb->get_results("SELECT * FROM $table_expense where invoice_type = 'expense' AND supplier_name=$user_id");
	}else{
		$income = $wpdb->get_results("SELECT * FROM $table_expense where invoice_type = 'income' ");
		$expense = $wpdb->get_results("SELECT * FROM $table_expense where invoice_type = 'expense' ");
	}
	
	if(!empty($income)){
		foreach($income as $retrive_data)
		{
			$income_array = json_decode($retrive_data->entry);
			foreach($income_array as $amount)
			{
				$total_income += $amount->amount;
			}
		}
	}
	if(!empty($expense)){
		foreach($expense as $retrive_data)
		{
			$expense_array = json_decode($retrive_data->entry);
			foreach($expense_array as $amount)
			{
				$total_expense += $amount->amount;
			}
		}
	}
	$net_profit = $total_income - $total_expense;
	return $net_profit;
}
function mj_cmgt_get_income_netprofit_graphdata()
{
	$role = MJ_cmgt_get_user_role(get_current_user_id());
	$user_id =get_current_user_id();
	$obj_payment=new Cmgtpayment;
	$invoice_data= $obj_payment->MJ_cmgt_get_all_invoice_data();
    
	foreach($invoice_data as $retrieved_data)
	{
		$datetime = DateTime::createFromFormat('Y-m-d',$retrieved_data->invoice_date);
		// $year_new = $datetime->format('Y');
       
		$year =isset($year_new)?$year_new:date('Y');
	}
	$current_year = Date("Y");
	$month =array('1'=>esc_html__('Jan','church_mgt'),'2'=>esc_html__('Feb','church_mgt'),'3'=>esc_html__('Mar','church_mgt'),'4'=>esc_html__('Apr','church_mgt'),'5'=>esc_html__('May','church_mgt'),'6'=>esc_html__('Jun','church_mgt'),'7'=>esc_html__('Jul','church_mgt'),'8'=>esc_html__('Aug','church_mgt'),'9'=>esc_html__('Sep','church_mgt'),'10'=>esc_html__('Oct','church_mgt'),'11'=>esc_html__('Nov','church_mgt'),'12'=>esc_html__('Dec','church_mgt'),);
	$result = array();
	$dataPoints_2 = array();
	//array_push($dataPoints_2, array('Month','Income','Expense'));
	array_push($dataPoints_2, array(esc_html__('Month','church_mgt'),esc_html__('Income','church_mgt'),esc_html__('Expense','church_mgt'),esc_html__('Net Profit','church_mgt')));
	$dataPoints_1 = array();
    $currency_symbol = MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
    $new_currency_symbol = html_entity_decode($currency_symbol);
	foreach($month as $key=>$value)
	{
		global $wpdb;
		$table_name = $wpdb->prefix."cmgt_income_expense";

        if(!empty($year)){
			if($role == "member")
			{
				$q = "SELECT * FROM $table_name WHERE YEAR(invoice_date) = $year AND MONTH(invoice_date) = $key AND invoice_type = 'income' AND supplier_name=$user_id";
			}else{
				$q = "SELECT * FROM $table_name WHERE YEAR(invoice_date) = $year AND MONTH(invoice_date) = $key AND invoice_type = 'income'";
			}
            $result=$wpdb->get_results($q);
        }
		if($role == "member")
		{
       		$q1 = "SELECT * FROM $table_name WHERE YEAR(invoice_date) = $current_year AND MONTH(invoice_date) = $key AND invoice_type = 'expense'  AND supplier_name=$user_id";
		}else{
			$q1 = "SELECT * FROM $table_name WHERE YEAR(invoice_date) = $current_year AND MONTH(invoice_date) = $key AND invoice_type = 'expense'";
		}
        $result1=$wpdb->get_results($q1);
        
		$income_yearly_amount = 0;
        foreach($result as $income_entry)
		{
            $entry = json_decode($income_entry->entry);
		    $income_yearly_amount += $entry[0]->amount;
		}

		if($income_yearly_amount == 0)
		{
			$income_amount = 0;
		}
		else
		{
			$income_amount = $income_yearly_amount;
		}

        $expense_yearly_amount = 0;
		foreach($result1 as $expense_entry)
		{
            $entry = json_decode($expense_entry->entry);
            $expense_yearly_amount += $entry[0]->amount;
          
		}
        
		if($expense_yearly_amount == 0)
		{
			$expense_amount = 0;
		}
		else
		{
			$expense_amount = $expense_yearly_amount;
		}
		$net_profit_array = $income_amount - $expense_amount;
		
		array_push($dataPoints_2, array($value,$income_amount,$expense_amount,$net_profit_array));
    }

	$new_array = json_encode($dataPoints_2);
	return $new_array;
}
function mj_cmgt_get_transaction_donation_graphdata()
{
	$role = MJ_cmgt_get_user_role(get_current_user_id());
	$user_id =get_current_user_id();
	
	global $wpdb;
	$table_name = $wpdb->prefix."cmgt_transaction";
	$transaction_data = $wpdb->get_results("SELECT * FROM $table_name");
	
	foreach($transaction_data as $retrieved_data)
	{
		$datetime = DateTime::createFromFormat('Y-m-d',$retrieved_data->created_date);
		// $year_new = $datetime->format('Y');
       
		$year =isset($year_new)?$year_new:date('Y');
	}
	$current_year = Date("Y");
	$month =array('1'=>esc_html__('Jan','church_mgt'),'2'=>esc_html__('Feb','church_mgt'),'3'=>esc_html__('Mar','church_mgt'),'4'=>esc_html__('Apr','church_mgt'),'5'=>esc_html__('May','church_mgt'),'6'=>esc_html__('Jun','church_mgt'),'7'=>esc_html__('Jul','church_mgt'),'8'=>esc_html__('Aug','church_mgt'),'9'=>esc_html__('Sep','church_mgt'),'10'=>esc_html__('Oct','church_mgt'),'11'=>esc_html__('Nov','church_mgt'),'12'=>esc_html__('Dec','church_mgt'),);
	$result = array();
	$dataPoints_2 = array();
	//array_push($dataPoints_2, array('Month','Income','Expense'));
	array_push($dataPoints_2, array(esc_html__('Month','church_mgt'),esc_html__('Transaction/Donation','church_mgt')));
	$dataPoints_1 = array();
    $currency_symbol = MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));
    $new_currency_symbol = html_entity_decode($currency_symbol);
	foreach($month as $key=>$value)
	{
		if(!empty($year)){
			if($role == "member")
			{
				$q = "SELECT * FROM $table_name WHERE YEAR(transaction_date) = $year AND MONTH(transaction_date) = $key AND member_id = $user_id";
			}else{
				$q = "SELECT * FROM $table_name WHERE YEAR(transaction_date) = $year AND MONTH(transaction_date) = $key";
			}
			$result=$wpdb->get_results($q);
			
		}

		$income_yearly_amount = 0;
        foreach($result as $income_entry)
		{
            $entry = $income_entry->amount;
			$income_yearly_amount += $entry;
		
		}

		if($income_yearly_amount == 0)
		{
			$transaction = 0;
		}
		else
		{
			$transaction = $income_yearly_amount;
		}

		array_push($dataPoints_2, array($value,$transaction));
	}
	$new_array = json_encode($dataPoints_2);
	return $new_array;
}
//-------- VIEW INVOICE FUNCTION -----//
function MJ_cmgt_view_invoice_page($invoice_type,$invoice_id)
{
	$obj_payment=new Cmgtpayment;
	$obj_gift=new Cmgtgift;
	$obj_pledges = new Cmgtpledes;
	if($invoice_type=='income')
	{
		$income_data=$obj_payment->MJ_cmgt_get_income_data(sanitize_text_field($invoice_id));
	}
	if($invoice_type=='expense')
	{
		$expense_data=$obj_payment->MJ_cmgt_get_income_data(sanitize_text_field($invoice_id));
	}
	if($invoice_type=='transaction')
	{
		$transaction_data=MJ_cmgt_get_transaction_data(sanitize_text_field($invoice_id));
	}
	if($invoice_type=='sell_gift')
	{
		$sell_gift_data=$obj_gift->MJ_cmgt_get_single_sell_gift(sanitize_text_field($invoice_id));
	}
	if($invoice_type=='pledges')
	{
		$pledges_data=MJ_cmgt_get_pledges_data(sanitize_text_field($invoice_id));
	}
	if(!empty($income_data))
		$member_id= sanitize_text_field($income_data->supplier_name);
	if(!empty($transaction_data))
		$member_id= sanitize_text_field($transaction_data->member_id);
	if(!empty($sell_gift_data))
		$member_id= sanitize_text_field($sell_gift_data->member_id);
	if(!empty($pledges_data))
		$member_id= sanitize_text_field($pledges_data->member_id);
	if(!empty($member_id))
		$patient=get_userdata($member_id);
	?>
	
		<div class="modal-body invoice_body invoice_border" >
			<h4 class="modal-title float-start width_100p"><?php echo get_option('cmgt_system_name','church_mgt');?></h4>
			<img class="invoicefont1 church_image rtl_invioce_img" style="vertical-align:top;background-repeat:no-repeat;" src="<?php echo plugins_url('/church-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div id="invoice_print" class="invoice_print_main_div">
				<div class="main_div invoice_main_div_for_new_design" id="paitient_print">
					<!-- <h4 class="modal-title float-start width_100p"><?php echo get_option('cmgt_system_name','church_mgt');?></h4> -->
					<div class="row padding_top_20px">
						<div class="col-md-1">
							<img class="system_logo width_64" src="<?php echo get_option( 'cmgt_church_other_data_logo' ); ?>">
						</div>
						<div class="col-md-11">
							<div class="row margin_top_10px_res">
								<div class="col-md-1 popup_label_heading"><?php esc_html_e('Email','church_mgt');?></div>
								<div class="col-md-11 label_value"><?php echo ': '.get_option( 'cmgt_email' ); ?></div>
								<div class="col-md-1 popup_label_heading"><?php esc_html_e('Phone','church_mgt');?></div>
								<div class="col-md-11 label_value"><?php echo ':  +'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_option( 'cmgt_contact_number' );  ?></div>
								<div class="col-md-1 popup_label_heading"><?php esc_html_e('Address','church_mgt');?></div>
								<div class="col-md-11 label_value"><?php echo ': '.get_option( 'cmgt_church_address' ); ?></div>
							</div>
						</div>
					</div>
					<div class="row padding_top_40px">
						<div class="col-md-10">
							<div class="row">
								<div class="col-md-1 popup_label_heading"><?php esc_html_e('Bill To','church_mgt');?></div>
								<div class="col-md-11 label_value">
									<?php
									if(!empty($expense_data))
									{
										$party_name=$expense_data->supplier_name;
										echo ': '.$party_name;
									}else{
										echo ': '.$patient->display_name;
									}
									?>
								</div>
								<?php
								if(empty($expense_data))
								{ ?>
									<div class="col-md-1  popup_label_heading"><?php esc_html_e('Address','church_mgt');?> :</div>
									<div class="col-md-11 label_value"><?php echo get_user_meta( $member_id,'address',true); ?></div>
									<div class="col-md-1 popup_label_heading"><?php esc_html_e('Phone','church_mgt');?></div>
									<div class="col-md-11 label_value"><?php echo ': +'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta( $member_id,'mobile',true ); ?></div>
									<?php
								} ?>
							</div>
						</div>

						<div class="col-md-2 margin_top_20px">
							<div class="row">
								<?php
									$issue_date='DD-MM-YYYY';
									if(!empty($income_data))
									{
										$issue_date= sanitize_text_field($income_data->invoice_date);
										$payment_status=sanitize_text_field($income_data->payment_status);
										$invoice_no=sanitize_text_field($income_data->invoice_id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($income_data->invoice_id)); 
									}
									if(!empty($expense_data))
									{
										$issue_date=sanitize_text_field($expense_data->invoice_date);
										$payment_status=sanitize_text_field($expense_data->payment_status);
										$invoice_no=sanitize_text_field($expense_data->invoice_id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_income_expence_number($expense_data->invoice_id)); 
									}
									if(!empty($transaction_data))
									{
										$issue_date=sanitize_text_field($transaction_data->created_date);
										$invoice_no=sanitize_text_field($transaction_data->id);
										$invoice_number = esc_attr($obj_payment->MJ_cmgt_generate_invoce_number($transaction_data->id)); 
									}
									if(!empty($sell_gift_data))
									{
										$issue_date=sanitize_text_field($sell_gift_data->sell_date);						
										$invoice_no=sanitize_text_field($sell_gift_data->id);
										$invoice_number = esc_attr($obj_gift->MJ_cmgt_generate_sell_gift_number($sell_gift_data->id)); 
									}
									if(!empty($pledges_data))
									{
										$issue_date=sanitize_text_field($pledges_data->created_date);						
										$invoice_no=sanitize_text_field($pledges_data->id);
										$invoice_number = esc_attr($obj_pledges->MJ_cmgt_generate_pledges_number($pledges_data->id)); 
									}
									if($invoice_type!='expense')
									{
										?>	
											<h3 class="invoice_lable"  ><?php echo esc_html_e('INVOICE','church_mgt')." #".get_option( 'cmgt_payment_prefix' ).$invoice_number;?></h3>								
										<?php
									}
									?>
									<h5>
										<?php 
											$issue_date=MJ_cmgt_date_formate();
												if(!empty($income_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($income_data->invoice_date));
												}
												if(!empty($expense_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($expense_data->invoice_date));
												}
												if(!empty($sell_gift_data)){
													$issue_date=date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));
												}
											if(!empty($transaction_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($transaction_data->transaction_date));
												}
											if(!empty($pledges_data)){
												$issue_date=date(MJ_cmgt_date_formate(),strtotime($pledges_data->created_date));
												}
										?>
										<label class="popup_label_heading text-transfer-upercase"><?php echo esc_html_e('Date :','church_mgt') ?> </label>
										<label class="invoice_model_value"><?php echo $issue_date; ?></label>
									</h5>
									<?php
									if($invoice_type=='expense' || $invoice_type=='income')
									{	
										?>	
										<h5>
											<label class="popup_label_heading text-transfer-upercase"><?php echo esc_html_e('Status :','church_mgt') ?> </label>	
											<label class="invoice_model_value">
											<span class="<?php if($payment_status == "Unpaid"){ ?>red_color<?php }elseif($payment_status == "Paid"){ ?>green_color<?php }else{ echo"blue_color";} ?>"><?php echo esc_html_e( $payment_status,'church_mgt'); ?></span></label>
										</h5>								
										<?php
									}
								?>
							</div>
						</div>
					</div>
					
					<?php
					if($invoice_type=='expense')
					{ 
					?>	
						<table class="width_100">	
							<tbody>	
								<tr>
									<td>
										<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Expense Entries','church_mgt');?></h3>
									</td>	
								</tr>	
							</tbody>
						</table>	
						
					<?php 	
					}				
					elseif($invoice_type=='transaction')
					{ 
					?>	
						<table class="width_100">	
							<tbody>	
								<tr>
									<td>
										<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Transaction Entries','church_mgt');?></h3>
									</td>	
								</tr>	
							</tbody>
						</table>
					
					<?php 	
					}
					elseif($invoice_type=='sell_gift')
					{ 
					?>
						<table class="width_100">	
							<tbody>	
								<tr>
									<td>
										<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Sell Gift','church_mgt');?></h3>
									</td>	
								</tr>	
							</tbody>
						</table>
						
					<?php
					}
					elseif($invoice_type=='pledges')
					{ 
					?>
						<table class="width_100">	
							<tbody>	
								<tr>
									<td>
										<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Pledges Entries','church_mgt');?></h3>
									</td>	
								</tr>	
							</tbody>
						</table>
						
					<?php
					}
					else
					{ ?>
						<table class="width_100">	
							<tbody>	
								<tr>
									<td>
										<h3  class="entry_lable invoice_model_heading"><?php esc_html_e('Income Entries','church_mgt');?></h3>
									</td>	
								</tr>	
							</tbody>
						</table>	
						
					<?php 	
					}
					?>
					<div class="invoice_table_res">
						<table class="table model_invoice_table" class="width_93">
							<thead class="entry_heading invoice_model_entry_heading" style="background-color: #F2F2F2 !important;">
								<?php
								if($invoice_type=='income' || $invoice_type=='expense')
								{
								?>
									<tr>
										<th class="entry_table_heading">#</th>
										<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
										<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?> </th>
										<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
										<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
									</tr>
								<?php
								}
								elseif($invoice_type=='transaction')
								{  
								?>
									<tr>
										<th class="entry_table_heading">#</th>
										<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
										<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?> </th>
										<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
										<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
									</tr>
								<?php 
								}
								elseif($invoice_type=='sell_gift')
								{  
								?>
									<tr>
										<th class="entry_table_heading">#</th>
										<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
										<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?></th>
										<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
										<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
									</tr>
								<?php 
								}
								elseif($invoice_type=='pledges')
								{  
								?>
									<tr>
										<th class="entry_table_heading">#</th>
										<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
										<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?> </th>
										<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
										<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
									</tr>
								<?php 
								}
								else
								{ 
								?>
									<tr>
										<th class="entry_table_heading">#</th>
										<th class="entry_table_heading"> <?php esc_html_e('Date','church_mgt');?></th>
										<th class="entry_table_heading"><?php esc_html_e('Description','church_mgt');?> </th>
										<th class="entry_table_heading"> <?php esc_html_e('Issue By','church_mgt');?> </th>
										<th class="entry_table_heading align_right" ><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' )); ?> <?php esc_html_e('Amount','church_mgt');?></th>
									</tr>	 
								<?php 
								}	
								?>
							</thead>
							<tbody class="invoice_model_table_body" style="border-bottom: 1px solid #E1E3E5 !important;">
								<?php 
									if(!empty($income_data) || !empty($expense_data))
									{
										$id=1;
										$total_amount=0;
										if(!empty($expense_data))
										$income_data=$expense_data;
										$church_all_income=$obj_payment->MJ_cmgt_get_single_income_data_by_invoice_id($income_data->invoice_id);
										// var_dump($church_all_income);
										// die;
										foreach($church_all_income as $result_income)
										{
											$income_entries=json_decode($result_income->entry);
											foreach($income_entries as $each_entry){
											$total_amount+=$each_entry->amount;
											$total_amount1=$each_entry->amount;?>
											<tr class="entry_list">
												<td class="invoice_table_data"><?php echo $id;?></td>
												<td class="invoice_table_data"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($result_income->invoice_date)));?></td>
												<td class="invoice_table_data"><?php echo esc_attr($each_entry->entry); ?> </td>
												<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_display_name(esc_attr($result_income->receiver_id));?></td>
												<td class="invoice_table_data align_right">  <span style="font-size:14px;"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?> <?php echo number_format($total_amount1,2); ?> </span></td>
											</tr>
											<?php $id+=1;}
										}
									}
								?>
								<?php
								if(!empty($sell_gift_data))
								{
									$id=1;
									$total_amount=0;
									$total_amount=$sell_gift_data->gift_price;
									?>
									<tr class="entry_list">
										<td class="invoice_table_data"><?php echo $id;?></td>
										<td class="invoice_table_data"><?php echo date(MJ_cmgt_date_formate(),strtotime($sell_gift_data->sell_date));?></td>
										<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_gift_name($sell_gift_data->gift_id);?></td>
										<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_display_name($sell_gift_data->created_by); ?></td>
										<td class="invoice_table_data align_right"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?> <?php echo number_format($sell_gift_data->gift_price,2); ?></td>

									</tr>
									<?php
								}
								?>
								<?php
								if(!empty($pledges_data))
								{
									$id=1;
									$total_amount=0;
									$total_amount=$pledges_data->total_amount;
									?>
									<tr class="entry_list">
										<td class="invoice_table_data"><?php echo $id;?></td>
										<td class="invoice_table_data"><?php echo date(MJ_cmgt_date_formate(),strtotime($pledges_data->start_date));?></td>
										<td class="invoice_table_data"><?php echo esc_html_e('Pledge','church_mgt');?></td>
										<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_display_name($pledges_data->created_by); ?></td>
										<td class="invoice_table_data align_right"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?> <?php echo number_format($pledges_data->total_amount,2); ?></td>
									</tr>
									<?php
								}
								?>
								<?php
								if(!empty($transaction_data))
								{
									$id=1;
									$total_amount=0;
									$total_amount=$transaction_data->amount;
										?>
										<tr class="entry_list">
											<td class="invoice_table_data"><?php echo $id;?></td>
											<td class="invoice_table_data"><?php echo date(MJ_cmgt_date_formate(),strtotime(esc_attr($transaction_data->transaction_date)));?></td>
											<td class="invoice_table_data"><?php echo get_the_title(esc_attr($transaction_data->donetion_type));?></td>
											<td class="invoice_table_data"><?php echo MJ_cmgt_church_get_display_name(esc_attr($transaction_data->created_by));?></td>
											<td class="invoice_table_data align_right"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?> <?php echo esc_attr($transaction_data->amount); ?></td>
										</tr>
									<?php 
								} ?>			
							</tbody>
						</table>
					<div>
					<table class="width_54 " border="0">
						<tbody>
							<tr>
								<td class="align_right view_amount_label"><h4 class="margin"><?php esc_html_e('Subtotal :','church_mgt');?></h4></td>
								<td class="align_right model_body_amount_value"> <h4 class="margin"><span style=""><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?></span><?php echo number_format($total_amount,2);?></h4></td>
							</tr>
							<tr class="grand_total_div">
								<td class="align_right grand_total_lable view_grand_total_lable" style="margin-right: 5px;"><h3 class="color_white margin"><?php esc_html_e('Grand Total :','church_mgt');?></h3></td>
								<td class="align_left grand_total_amount"><h3 class="color_white margin"><?php echo MJ_cmgt_get_currency_symbol(get_option( 'cmgt_currency_code' ));?> <?php echo number_format($total_amount,2);?></h3></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			</div>
		</div>

	<div class="row col-md-12 col-sm-12 col-xs-12 print-button pull-left invoice_print_pdf_btn margin_top_20px print-button-app">
		<?php
		if(isset($_REQUEST['web_type']) && $_REQUEST['web_type'] == "church_app")
		{
			if($invoice_type=='income')
			{
				$member_id=$income_data->supplier_name;
			}
			if($invoice_type=='expense')
			{
				$member_id=$expense_data->supplier_name;
			}
			if($invoice_type=='transaction')
			{
				$member_id=$transaction_data->member_id;
			}
			if($invoice_type=='sell_gift')
			{
				$member_id=$sell_gift_data->member_id;
			}
			if($invoice_type=='pledges')
			{
				$member_id=$pledges_data->member_id;
			}

			if($invoice_type=='expense')
			{
				$pdf_name = $member_id.'_'.$invoice_id;
			}else{
				$member=get_userdata($member_id);
				$pdf_name = $member->display_name.'_'.$invoice_id;
			}
			
			$invoice_id = $_REQUEST['idtest'];
			if(isset($_REQUEST['app_pdf']))
			{
				$generate_pdf = MJ_cmgt_generate_invoice_pdf($invoice_id,$invoice_type);
			
				wp_redirect ( content_url() .'/uploads/invoice_pdf/'.$pdf_name.'.pdf');
			}
			
			?>
			<form name="wcwm_report" action="" target="_blank" method="post">
				<div class="form-body user_form margin_top_40px">
					<div class="row">
						<div class="col-md-1 print_btn_rs">
							<button data-toggle="tooltip" name="app_pdf" class="btn print-btn print_btn_height margin_right_10px" ><img
									src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" alt="pdf"></button>
						</div>
						<div class="col-md-1 print_btn_rs">
							<button data-toggle="tooltip" name="app_pdf" class="btn print-btn print_btn_height" ><img
									src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" alt="pdf"></button>
						</div>
					</div>
				</div>
			</form>
			<?php
		}else{
			?>
			<div class="col-md-1 print_btn_rs">
				<a href="?page=cmgt-transactions&print=print&invoice_type=<?php echo esc_attr($invoice_type);?>&idtest=<?php echo esc_attr($invoice_id);?>" target="_blank" style="margin-right:15px;"class="btn print-btn print_btn_height rtl_print_icon_ml_15px"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/print.png" ?>" ></a>
			</div>
			<div class="col-md-1 pdf_btn_rs">
				<a href="?page=cmgt-transactions&invoicepdf=invoicepdf&invoice_type=<?php echo $invoice_type;?>&idtest=<?php echo $invoice_id;?>" target="_blank" class="btn print-btn print_btn_height"><img src="<?php echo CMS_PLUGIN_URL."/assets/images/icons/pdf.png" ?>" ></a>
			</div>
			<?php
		} ?>
		
	</div>
	<?php 
	die();
}
?>
