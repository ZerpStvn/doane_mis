<?php 
 //POSTORAL CLASS START  
class Cmgtpastoral
{	
	//---------- ADD POSTORAl DATA ---------//
	public function MJ_cmgt_add_pastoral($data)
	{
		global $wpdb;
		$table_pastoral = $wpdb->prefix. 'cmgt_pastoral';
		$pastoraldata['pastoral_title']=stripslashes($data['pastoral_title']);
		$pastoraldata['member_id']=$data['member_id'];
		$pastoraldata['pastoral_date']=MJ_cmgt_get_format_for_db($data['pastoral_date']);
		$pastoraldata['pastoral_time']=$data['pastoral_time'];
		$pastoraldata['description']=MJ_cmgt_strip_tags_and_stripslashes($data['description']);
		$pastoraldata['created_date']=date("Y-m-d");
		$pastoraldata['created_by']=get_current_user_id();
		//---------- EDIT POSTORAl DATA ---------//
		if($data['action']=='edit')
		{
			$whereid['id']=$data['pastoral_id'];
			$result=$wpdb->update( $table_pastoral, $pastoraldata ,$whereid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_pastoral, $pastoraldata );
			// aded Pastrol in member mail notification		
			$user_info = get_userdata($data['member_id']);
			$to = $user_info->user_email; 
			$membername=$user_info->display_name;
			$userid=get_current_user_id();
			
			$getuserdata=get_userdata($userid);
			$username=$getuserdata->display_name;
			$page_link=home_url().'/?church-dashboard=user&&page=pastoral&tab=pastoral_list';
			$subject =get_option('WPChurch_Add_Pastoral_subject');
			$church_name=get_option('cmgt_system_name');
			$message_content=get_option('WPChurch_Add_Pastoral_Template');
			$subject_search=array('[CMGT_USER]','[CMGT_CHURCH_NAME]');
			$subject_replace=array($username,$church_name);
			$pastoral_date=date(MJ_cmgt_date_formate(),strtotime($pastoraldata['pastoral_date']));
			$search=array('[CMGT_MEMBERNAME]','[CMGT_PASTORAL_TITLE]','[CMGT_PASTORAL_DATE]','[CMGT_PASTORAL_TIME]','[CMGT_PASTORAL_DESCRIPTION]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
			$replace = array($membername,$data['pastoral_title'],$pastoral_date,$data['pastoral_time'],$data['description'],$page_link,$church_name);
			$message_content = str_replace($search, $replace, $message_content);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
			//end send mail notification
			
			//---------------- SEND  SMS ------------------//
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			if(is_plugin_active('sms-pack/sms-pack.php'))
			{
				if(!empty($user_info->phonecode)){ $phone_code=$user_info->phonecode; }else{ $phone_code='+'.MJ_amgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }
						
				$user_number[] = $phone_code.$user_info->mobile;
				$pastoral_title = $pastoraldata['pastoral_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="$pastoral_title has been successfully booked for you from $church_name .";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="PASTORAL";
				$args['message']=$message_content;					
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
				
			}
				return $result;
		}
	}
	//---------- GET ALL POSTORAl ---------//
	public function MJ_cmgt_get_all_pastoral()
	{
		global $wpdb;
		$table_pastoral = $wpdb->prefix. 'cmgt_pastoral';
		$result = $wpdb->get_results("SELECT * FROM $table_pastoral");
		return $result;
	}
	public function MJ_cmgt_get_all_pastoral_created_by()
	{
		global $wpdb;
		$curr_user_id=get_current_user_id();
		$table_pastoral = $wpdb->prefix. 'cmgt_pastoral';
		$result = $wpdb->get_results("SELECT * FROM $table_pastoral where created_by=".$curr_user_id);
		return $result;
	}
	//---------- GET ALL POSTORAl ---------//
	public function MJ_cmgt_get_pastoral_member($member_id)
	{
		global $wpdb;
		$table_pastoral = $wpdb->prefix. 'cmgt_pastoral';
		$result = $wpdb->get_results("SELECT * FROM $table_pastoral where member_id=".$member_id);
		return $result;
	}
	//---------- GET ALL POSTORAl DASHBOARD ---------//
	public function MJ_cmgt_get_all_pastoral_dashboard()
	{
		global $wpdb;
		$table_pastoral = $wpdb->prefix. 'cmgt_pastoral';
		$result = $wpdb->get_results("SELECT * FROM $table_pastoral ORDER BY id DESC LIMIT 3");
		return $result;
	}
	//---------- GET ALL POSTORAl DASHBOARD ---------//
	public function MJ_cmgt_get_pastoral_member_dashboard($member_id)
	{
		global $wpdb;
		$table_pastoral = $wpdb->prefix. 'cmgt_pastoral';
		$result = $wpdb->get_results("SELECT * FROM $table_pastoral where member_id=".$member_id." ORDER BY id DESC LIMIT 3");
		return $result;
	}
	//---------- GET SINGEL POSTORAl ---------//
	public function MJ_cmgt_get_single_pastoral($id)
	{
		global $wpdb;
		$table_pastoral = $wpdb->prefix. 'cmgt_pastoral';
		$result = $wpdb->get_row("SELECT * FROM $table_pastoral where id=".$id);
		return $result;
	}
	//---------- DELETE POSTORAl ---------//
	public function MJ_cmgt_delete_pastoral($id)
	{
		global $wpdb;
		$table_pastoral = $wpdb->prefix. 'cmgt_pastoral';
		$result = $wpdb->query("DELETE FROM $table_pastoral where id= ".$id);
		return $result;
	}
}
?>