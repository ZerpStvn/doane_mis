<?php 
 //SERMON CLASS START  
class Cmgtsermon
{	
	//---------- ADD SERMON DATA ----------//
	public function MJ_cmgt_add_sermon($data,$sermon_content_url)
	{
		global $wpdb;
		$table_sermon = $wpdb->prefix. 'cmgt_sermon';
		$sermondata['sermon_type']=MJ_cmgt_strip_tags_and_stripslashes($data['sermon_type']);
		$sermondata['sermon_title']=stripslashes($data['sermon_title']);
		$sermondata['description']=MJ_cmgt_strip_tags_and_stripslashes($data['description']);
		$sermondata['status']=MJ_cmgt_strip_tags_and_stripslashes($data['status']);
		$sermondata['sermon_content']=$sermon_content_url;
		$sermondata['created_date']=date("Y-m-d");
		$sermondata['created_by']=get_current_user_id();
		//----------EDIT SERMON -----------//
		if($data['action']=='edit')
		{
			$sermonid['id']=$data['sermon_id'];
			$result=$wpdb->update( $table_sermon, $sermondata ,$sermonid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_sermon, $sermondata );
			$get_members = array('role' => 'member');
			$membersdata=get_users($get_members);
		   	if(!empty($membersdata))
		    {
				foreach ($membersdata as $retrieved_data){
				$curentuser_id=get_current_user_id();
				$currentuserdata=get_userdata($curentuser_id);
				$curent_user_name=$currentuserdata->display_name;
				$to=$retrieved_data->user_email;
				$user_name=$retrieved_data->display_name;
				$subject =get_option('WPChurch_Add_Sermon_Subject');
				$page_link=home_url().'/?church-dashboard=user&&page=sermon-list&tab=sermonlist';
				$churchname=get_option('cmgt_system_name');
				$message_content=get_option('WPChurch_Add_Sermon_Template');
				$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_SERMONADDEDBY]');
				$subject_replace=array($churchname,$curent_user_name);
				$subject=str_replace($subject_search,$subject_replace,$subject);
				$search=array('[CMGT_MEMBERNAME]','[CMGT_SERMONADDEDBY]','[CMGT_CHURCH_NAME]','[CMGT_SERMON_TITLE]',' [CMGT_SERMON_DESCRIPTION]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
				$replace = array($user_name,$curent_user_name,$churchname,$data['sermon_title'],$data['description'],$page_link,$churchname);
				$message_content = str_replace($search, $replace, $message_content);
				MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
				}
			}
			$get_members = get_users('role=member');
			foreach ($get_members as $user) {
				$phone_code = $user->ID;
				
			}
			$getmemberdata=get_userdata($phone_code);
			
			//---------------- SEND  SMS ------------------//
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			if(is_plugin_active('sms-pack/sms-pack.php'))
			{
				if(!empty($getmemberdata->phonecode)){ $phone_code=$getmemberdata->phonecode; }else{ $phone_code='+'.MJ_amgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }
						
				$user_number[] = $phone_code.$getmemberdata->mobile;
				$sermon_title = $sermondata['sermon_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="$sermon_title has been successfully booked for you from $church_name .";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="SERMON";
				$args['message']=$message_content;
				
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
			}
			 return $result;
		}
	}
	//------- GET ALL SERMON ---------//
	public function MJ_cmgt_get_all_sermons()
	{
		global $wpdb;
		$table_sermon = $wpdb->prefix. 'cmgt_sermon';
		$result = $wpdb->get_results("SELECT * FROM $table_sermon");
		return $result;
	}
	//------- GET SINGLE SERMON ---------//
	public function MJ_cmgt_get_single_sermon($id)
	{
		global $wpdb;
		$table_sermon = $wpdb->prefix. 'cmgt_sermon';
		$result = $wpdb->get_row("SELECT * FROM $table_sermon where id=".$id);
		return $result;
	}
	//------- DELETE SERMON ---------//
	public function MJ_cmgt_delete_sermon($id)
	{
		global $wpdb;
		$table_sermon = $wpdb->prefix. 'cmgt_sermon';
		$result = $wpdb->query("DELETE FROM $table_sermon where id= ".$id);
		return $result;
	}
	//------- GET ALL SERMON ---------//
	function MJ_cmgt_update_groupimage($id,$imagepath)
	{
		global $wpdb;
		$table_sermon = $wpdb->prefix. 'gmgt_groups';
		$image['gmgt_groupimage']=$imagepath;
		$groupid['id']=$id;
		return $result=$wpdb->update( $table_sermon, $image, $groupid);
	}
	//------- GIVE GIFT ---------//
	public function MJ_cmgt_give_gift($data)
	{
		global $wpdb;
		$table_gift_assigned = $wpdb->prefix. 'cmgt_gift_assigned';
		$sermondata['gift_id']=$data['gift_id'];
		$sermondata['member_id']=$data['member_id'];
		$sermondata['gifted_date']=date("Y-m-d");
		$sermondata['gifted_by']=get_current_user_id();
		$result=$wpdb->insert( $table_gift_assigned, $sermondata );
		return $result;
	}
	//------- GET MEMBERS GIFT ---------//
	public function MJ_cmgt_get_members_gift($id)
	{
		global $wpdb;
		$table_gift_assigned = $wpdb->prefix. 'cmgt_gift_assigned';
		$result = $wpdb->get_results("SELECT * FROM $table_gift_assigned where member_id=".$id);
		return $result;
	}
}
?>