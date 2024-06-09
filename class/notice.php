<?php 
//NOTICE CLASS START  
class Cmgtnotice{		
	//----------- ADD NOTICE DATA ----------//
	public function MJ_cmgt_add_notice($data){				
		global $wpdb;
		$table_notice = $wpdb->prefix. 'cmgt_notice';
		$noticedata['notice_title']=stripslashes($data['notice_title']);
		$noticedata['notice_content']=MJ_cmgt_strip_tags_and_stripslashes($data['notice_content']);
		$noticedata['start_date']= MJ_cmgt_get_format_for_db($data['start_date']);
		$noticedata['end_date']= MJ_cmgt_get_format_for_db($data['end_date']);
		$noticedata['status']=MJ_cmgt_strip_tags_and_stripslashes($data['status']);		
		$noticedata['created_at']=date("Y-m-d");		
		$noticedata['created_by']=get_current_user_id();		
		//----------- EDIT NOTICE DATA ----------//
		if($data['action']=='edit'){			
			$activityid['id']=$data['id'];
			$result=$wpdb->update( $table_notice, $noticedata ,$activityid);
			return $result;
		}
		else{
			$result=$wpdb->insert( $table_notice, $noticedata );
				//get userrolesbyid
			$blogusers = get_users('role=Administrator');
			
			foreach ($blogusers as $user) {
			   $to=$user->user_email;
			   $admin_name=$user->display_name;
			}  
		
			$userid=get_current_user_id();
			$getuserdata=get_userdata($userid);
			$username=$getuserdata->display_name;
			$page_link=admin_url().'admin.php?page=cmgt-notice&tab=noticelist';
			$subject =get_option('WPChurch_Add_Notice_Admin_subject');
			$church_name=get_option('cmgt_system_name');
			$message_content=get_option('WPChurch_Add_Notice_Template');
			$subject_search=array('[CMGT_MEMBERNAME]','[CMGT_CHURCH_NAME]');
			$subject_replace=array($username,$church_name);
			$search=array('[CMGT_ADMIN]','[CMGT_MEMBERNAME]','[CMGT_CHURCH_NAME]','[CMGT_PAGE_LINK]');
			$replace = array($admin_name,$username,$church_name,$page_link);
			$message_content = str_replace($search, $replace, $message_content);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
			//end send mail notification	
			$get_members = get_users('role=member');
			foreach ($get_members as $user) {
				$phone_code = $user->ID;
				
			}
			$getmemberdata=get_userdata($phone_code);
			// var_dump($getmemberdata->mobile);
			// die;
			//---------------- SEND  SMS ------------------//
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			if(is_plugin_active('sms-pack/sms-pack.php'))
			{
				if(!empty($getmemberdata->phonecode)){ $phone_code=$getmemberdata->phonecode; }else{ $phone_code='+'.MJ_amgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }
						
				$user_number[] = $phone_code.$getmemberdata->mobile;
				$notice_title = $noticedata['notice_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="$notice_title has been successfully booked for you from $church_name .";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="NOTICE";
				$args['message']=$message_content;
				
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
			}

			return $result;
		}
	}
	//------- GET ALL NOTICE ---------//
	public function MJ_cmgt_get_all_notice(){
		global $wpdb;
		$table_notice = $wpdb->prefix. 'cmgt_notice';	
		$result = $wpdb->get_results("SELECT * FROM $table_notice");
		return $result;	
	}
	public function MJ_cmgt_get_all_notice_creted_by()
	{
		$curr_user_id=get_current_user_id();
		global $wpdb;
		$table_notice = $wpdb->prefix. 'cmgt_notice';	
		$result = $wpdb->get_results("SELECT * FROM $table_notice WHERE created_by=".$curr_user_id);
		return $result;	
	}
	//------- GET ALL APPROVE NOTICE ---------//
	public function MJ_cmgt_get_all_approve_notice(){
		global $wpdb;
		$table_notice = $wpdb->prefix. 'cmgt_notice';	
		$result = $wpdb->get_results("SELECT * FROM $table_notice WHERE status=1");
		return $result;	
	}
	//------- GET  APPROVE NOTICE ---------//
	public function MJ_cmgt_approve_notice($notice_id){
		global $wpdb;
		$table_notice = $wpdb->prefix. 'cmgt_notice';	
		$result = $wpdb->query("UPDATE $table_notice SET status='1' where id=".$notice_id);
		return $result;
	}
	//------- GET SINGLE NOTICE ---------//
	public function MJ_cmgt_get_single_notice($id)
	{
		global $wpdb;
		$table_notice = $wpdb->prefix. 'cmgt_notice';
		$result = $wpdb->get_row("SELECT * FROM $table_notice where id=".$id);
		return $result;
	}
	//------- DELETE NOTICE ---------//
	public function MJ_cmgt_delete_notice($id)
	{
		global $wpdb;
		$table_notice = $wpdb->prefix. 'cmgt_notice';
		$result = $wpdb->query("DELETE FROM $table_notice where id= ".$id);
		return $result;
	}
	//---------- GET ALL NOTICE DASHBOARD ----------//
	public function MJ_cmgt_get_all_notice_dashboard()
	{
		global $wpdb;
		$table_notice = $wpdb->prefix. 'cmgt_notice';
		$result = $wpdb->get_results("SELECT * FROM $table_notice ORDER BY id DESC LIMIT 3");
		// var_dump($result);
		// die;
		return $result;
	}
}
?>