<?php 
//MEMBER CLASS START  
class Cmgtmember
{	
	//----------- ADD MEMBER DATA --------------//
	public function MJ_cmgt_add_user($data)
	{
		global $wpdb;
		$table_members = $wpdb->prefix. 'usermeta';
		$table_cmgt_groupmember = $wpdb->prefix.'cmgt_groupmember';
		//-------usersmeta table data--------------
		if(isset($data['middle_name']))
		$usermetadata['middle_name']=MJ_cmgt_strip_tags_and_stripslashes($data['middle_name']);
		if(isset($data['gender']))
		$usermetadata['gender']=sanitize_text_field($data['gender']);
		if(isset($data['birth_date']))
		$usermetadata['birth_date']=MJ_cmgt_get_format_for_db($data['birth_date']);
		if(isset($data['marital_status']))
		$usermetadata['marital_status']=MJ_cmgt_strip_tags_and_stripslashes($data['marital_status']);
		if(isset($data['occupation']))
		$usermetadata['occupation']=MJ_cmgt_strip_tags_and_stripslashes($data['occupation']);
		if(isset($data['education']))
		$usermetadata['education']=MJ_cmgt_strip_tags_and_stripslashes($data['education']);
		if(isset($data['address']))
		$usermetadata['address']=MJ_cmgt_strip_tags_and_stripslashes($data['address']);
		$usermetadata['birth_day']=  date("m/d", strtotime($data['birth_date']));
		if(isset($data['city_name']))
		$usermetadata['city_name']=MJ_cmgt_strip_tags_and_stripslashes($data['city_name']);
	   if(isset($data['mobile']))
		$usermetadata['mobile']=sanitize_text_field($data['mobile']);
		if(isset($data['phonecode']))
		$usermetadata['phonecode']=sanitize_text_field($data['phonecode']);
		if(isset($data['fax_number']))
		$usermetadata['fax_number']=MJ_cmgt_strip_tags_and_stripslashes($data['fax_number']);
		if(isset($data['skyp_id']))
		$usermetadata['skyp_id']=sanitize_text_field($data['skyp_id']);
		if(isset($data['phone']))
		$usermetadata['phone']=sanitize_text_field($data['phone']);
		if(isset($data['cmgt_user_avatar']))
		$usermetadata['cmgt_user_avatar']=sanitize_text_field($data['cmgt_user_avatar']);
		
		if($data['role']=='member')
		{	
			if(isset($data['member_id']))
			$usermetadata['member_id']=sanitize_text_field($data['member_id']);
			if(isset($data['begin_date']))
				$usermetadata['begin_date']=MJ_cmgt_get_format_for_db($data['begin_date']);
			if(isset($data['baptist_date']))
				$usermetadata['baptist_date']=MJ_cmgt_get_format_for_db($data['baptist_date']);
			if(isset($data['volunteer']))
				$usermetadata['volunteer']=sanitize_text_field($data['volunteer']);
			else
				$usermetadata['volunteer']="no";
		}
		
		if(isset($data['username']))
		$userdata['user_login']=sanitize_user($data['username']);
		if(isset($data['email']))
		$userdata['user_email']=sanitize_email($data['email']);
		$userdata['user_nicename']=NULL;
		$userdata['user_url']=NULL;
		if(isset($data['first_name']))
		$userdata['display_name']=sanitize_text_field($data['first_name'])." ".sanitize_text_field($data['last_name']);
		if($data['password'] != "")
				$userdata['user_pass']=sanitize_text_field($data['password']);
		//--------- EDIT MEMBERS ----------//
		if($data['action']=='edit')
		{
			$userdata['ID']=$data['user_id'];
			$user_id = wp_update_user($userdata);
			if(isset($data['first_name']))
			{
				$returnans=update_user_meta( $user_id, 'first_name', $data['first_name'] );
			}
			if(isset($data['last_name']))
			{
				$returnans=update_user_meta( $user_id, 'last_name', $data['last_name'] );
			}
				foreach($usermetadata as $key=>$val)
				{
					$returnans=update_user_meta( $user_id, $key,$val );
				}
				if(isset($data['group_id']))
				{
					if(!empty($data['group_id']))
					{
						if($this->MJ_cmgt_member_exist_ingrouptable($data['user_id']))
							$this->MJ_cmgt_delete_member_from_grouptable($data['user_id']);
						foreach($data['group_id'] as $id)
						{
							$group_data['group_id']=$id;
							$group_data['member_id']=$data['user_id'];
							$group_data['type']='group';
							$group_data['created_date']=date("Y-m-d");
							$group_data['created_by']=get_current_user_id();
							$wpdb->insert( $table_cmgt_groupmember, $group_data );
						}
					}
				}
				else
				{
					foreach($data as $id)
					{
						$member_id = $data['user_id'];
						$group_type = 'group';
						$result = $wpdb->query($wpdb->prepare("DELETE FROM $table_cmgt_groupmember WHERE type='group' and member_id= %d",$member_id));
					}
					
				}
					
				if(isset($data['ministry_id']))
				{
					if(!empty($data['ministry_id']))
					{
						if($this->MJ_cmgt_ministry_member_exist_ingrouptable($data['user_id']))
							$this->MJ_cmgt_delete_ministrymember_from_grouptable($data['user_id']);
						foreach($data['ministry_id'] as $id)
						{
							$ministry_data['group_id']=$id;
							$ministry_data['member_id']=$data['user_id'];
							$ministry_data['type']='ministry';
							$ministry_data['created_date']=date("Y-m-d");
							$ministry_data['created_by']=get_current_user_id();
							$wpdb->insert( $table_cmgt_groupmember, $ministry_data );
						}
					}
				}
				else
				{
					foreach($data as $id)
					{
						$member_id = $data['user_id'];
						$ministry_data['member_id']=$data['user_id'];
						$ministry_data['type']='ministry';
						$result = $wpdb->query($wpdb->prepare("DELETE FROM $table_cmgt_groupmember WHERE type='ministry' and member_id= %d",$member_id));
					}
				}
				return $user_id;
		}
		else
		{
			//--------- ADD MEMBERS ----------//
			$user_id = wp_insert_user( $userdata );
			$user = new WP_User($user_id);
			$user->set_role($data['role']);
			
			foreach($usermetadata as $key=>$val){
				$returnans=add_user_meta( $user_id, $key,$val, true );
			}
			if(isset($data['first_name']))
			$returnans=update_user_meta( $user_id, 'first_name', $data['first_name'] );
			if(isset($data['last_name']))
			$returnans=update_user_meta( $user_id, 'last_name', $data['last_name'] );
			
			if(isset($data['group_id']))
				if(!empty($data['group_id']))
				{
					foreach($data['group_id'] as $id)
					{
						$group_data['group_id']=$id;
						$group_data['member_id']=$user_id;						
						$group_data['type']='group';						
						$group_data['created_date']=date("Y-m-d");
						$group_data['created_by']=get_current_user_id();
						$wpdb->insert( $table_cmgt_groupmember, $group_data );
						// aded group in member mail notification		
						$user_info = get_userdata($user_id);
						$to = $user_info->user_email; 
						$username=$userdata['display_name'];
						$obj_group=new Cmgtgroup;
						$groupdata=$obj_group->MJ_cmgt_get_single_group($id);
						$groupname=$groupdata->group_name;
						$loginlink=home_url();
						$subject =get_option('WPChurch_Member_Added_In_Group_subject');
						$church_name=get_option('cmgt_system_name');
						$message_content=get_option('WPChurch_Member_Added_In_Group_Template');
						$subject_search=array('[CMGT_GROUPNAME]','[CMGT_CHURCH_NAME]');
						$subject_replace=array($groupname,$church_name);
						$search=array('[CMGT_MEMBERNAME]','[CMGT_GROUPNAME]','[CMGT_CHURCH_NAME]');
						$replace = array($username,$groupname,$church_name);
						$message_content = str_replace($search, $replace, $message_content);
						$subject=str_replace($subject_search,$subject_replace,$subject);
						MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
						//end send mail notification
					}
				}
			if(isset($data['ministry_id']))
				if(!empty($data['ministry_id']))
				{
					foreach($data['ministry_id'] as $id)
					{
						$ministry_data['group_id']=$id;
						$ministry_data['member_id']=$user_id;						
						$ministry_data['type']='ministry';						
						$ministry_data['created_date']=date("Y-m-d");
						$ministry_data['created_by']=get_current_user_id();
						$wpdb->insert( $table_cmgt_groupmember, $ministry_data );
						// aded group in member mail notification		
						$user_info = get_userdata($user_id);
						$to = $user_info->user_email; 
						$username=$userdata['display_name'];
						$obj_ministry=new Cmgtministry;
						$ministarydata=$obj_ministry->MJ_cmgt_get_single_ministry($id);
						$ministaryname=$ministarydata->ministry_name;
						$loginlink=home_url();
						$subject =get_option('WPChurch_Member_Added_In_Ministry_subject');
						$church_name=get_option('cmgt_system_name');
						$message_content=get_option('WPChurch_Member_Added_In_Ministry_Template');
						$subject_search=array('[CMGT_MINISTRY]','[CMGT_CHURCH_NAME]');
						$subject_replace=array($ministaryname,$church_name);
						$search=array('[CMGT_MEMBERNAME]','[CMGT_MINISTRY]','[CMGT_CHURCH_NAME]');
						$replace = array($username,$ministaryname,$church_name);
						$message_content = str_replace($search, $replace, $message_content);
						$subject=str_replace($subject_search,$subject_replace,$subject);
						MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
						//end send mail notification
					}
				}
						$rolename=ucwords($data['role']);
						$user_info = get_userdata($user_id);
						$to = $user_info->user_email; 
						$username=$userdata['display_name'];
						$userlogin=$data['username'];
						$password=$data['password'];
						$loginlink=home_url();
						$subject =get_option('WPChurch_add_user_subject');
						$church_name=get_option('cmgt_system_name');
						$message_content=get_option('WPChurch_add_user_email_template');
						$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_ROLE_NAME]');
						$subject_replace=array($church_name,$rolename);
						$search=array('[CMGT_MEMBER_NAME]','[CMGT_CHURCH_NAME]','[CMGT_ROLE_NAME]','[CMGT_USERNAME]','[CMGT_PASSWORD]','[CMGT_LOGIN_LINK]');
						$replace = array($username,$church_name,$rolename,$userlogin,$password,$loginlink);
						$message_content = str_replace($search, $replace, $message_content);
						$subject=str_replace($subject_search,$subject_replace,$subject);
						MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
						
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
						MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
						
						//---------------- SEND  SMS ------------------//
						include_once(ABSPATH.'wp-admin/includes/plugin.php');
						if(is_plugin_active('sms-pack/sms-pack.php'))
						{
							if(!empty($user_info->phonecode)){ $phone_code=$user_info->phonecode; }else{ $phone_code='+'.MJ_amgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }
								
							$user_number[] = $phone_code.$user_info->mobile;
							// $pastoral_title = $pastoraldata['pastoral_title'];
							$church_name=get_option('cmgt_system_name');
							$message_content ="Member has been successfully registreted from $church_name .";
							$current_sms_service = get_option( 'smgt_sms_service');
							$args = array();
							$args['mobile']=$user_number;
							$args['message_from']="MEMBER REGISTRATION";
							$args['message']=$message_content;		
								
							if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
							{				
								$send = send_sms($args);							
							}
							
						}

			return $user_id;
		}
	}
	//--------- GET ALL GROUPS MEMBERS ----------//
	public function MJ_cmgt_get_all_groups()
	{
		global $wpdb;
		$table_members = $wpdb->prefix. 'cmgt_groups';
		$result = $wpdb->get_results("SELECT * FROM $table_members");
		return $result;
	}
	//--------- GET SINGLE GROUPS MEMBERS ----------//
	public function MJ_cmgt_get_single_group($id)
	{
		global $wpdb;
		$table_members = $wpdb->prefix. 'gmgt_groups';
		$result = $wpdb->get_row("SELECT * FROM $table_members where id=".$id);
		return $result;
	}
	//--------- DELETE  GROUP MEMBERS DATA ----------//
	public function MJ_cmgt_delete_usedata($record_id){
		global $wpdb;		
		$table_name = $wpdb->prefix . 'usermeta';
		$table_cmgt_groupmember = $wpdb->prefix . 'cmgt_groupmember';
		$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE user_id= %d",$record_id));
		$retuenval=wp_delete_user( $record_id );
		$resulta = $wpdb->delete( $table_cmgt_groupmember, array('member_id'=>$record_id));
		return $retuenval;
	}
	//--------- MEMBERS_exist_ingrouptable ----------//
	public function MJ_cmgt_member_exist_ingrouptable($member_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_results("SELECT * FROM $table_cmgt_groupmember where type='group' and member_id=".$member_id);
		if(!empty($result))
			return true;
		return false;
	}
	//--------- MINISTRY_exist_ingrouptable ----------//
	public function MJ_cmgt_ministry_member_exist_ingrouptable($member_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_row("SELECT * FROM $table_cmgt_groupmember where type='ministry' and member_id=".$member_id);
		if(!empty($result))
			return true;
		return false;
	}
	//--------- MJ_cmgt_delete_member_from_grouptable ----------//
	public function MJ_cmgt_delete_member_from_grouptable($member_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_cmgt_groupmember WHERE type='group' and member_id= %d",$member_id));
	}
	//--------- MJ_cmgt_delete_ministrymember_from_grouptable ----------//
	public function MJ_cmgt_delete_ministrymember_from_grouptable($member_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_cmgt_groupmember WHERE type='ministry' and member_id= %d",$member_id));
	}
	//------------ GET ALL JOIN GROUP ---------//
	public function MJ_cmgt_get_all_joingroup($member_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_results("SELECT group_id FROM $table_cmgt_groupmember where type='group' and member_id=".$member_id,ARRAY_A);
		return $result;
	}
	//------------- GET ALL JOIN MINISTRY ---------//
	public function MJ_cmgt_get_all_joinministry($member_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_results("SELECT group_id FROM $table_cmgt_groupmember where type='ministry' and member_id=".$member_id,ARRAY_A);
		return $result;
	}
	//-------------- CONVERT GROUP ARRAY --------//
	public function MJ_cmgt_convert_grouparray($join_group)
	{
		$groups = array();
		foreach($join_group as $group)
			$groups[] = $group['group_id'];
		return $groups;
	}
}
?>