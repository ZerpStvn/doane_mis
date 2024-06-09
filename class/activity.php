<?php 
//---- ACTIVITY CLASS START -------//
class Cmgtactivity{	
	//------- ADD ACTIVITY DATA ---------//
	public function MJ_cmgt_add_activity($data){		
		$event_content=array(
			'selected'=>$data['reccurence'],
			'weekly'=>array('repeat_time'=>$data['repeat_time'],
							 'weekly'=>$data['weekly']
			),
			'monthly'=>array('repeat_time'=>$data['repeat_time'],
							 'month_date'=>$data['month_date']
			),
			'daily'=>array('repeat_time'=>$data['repeat_time']),
							
			'yearly'=>array('repeat_time'=>$data['repeat_time'],
							 'yearly_date'=>MJ_cmgt_get_format_for_db($data['yearly_date'])
			),
		);
		global $wpdb;
		$table_activity = $wpdb->prefix. 'cmgt_activity';
		$activitydata['activity_cat_id']=$data['activity_cat_id'];
		//$activitydata['activity_title']=MJ_cmgt_strip_tags_and_stripslashes($data['activity_title']);
		$activitydata['activity_title']=stripslashes($data['activity_title']);
		$activitydata['speaker_name']=stripslashes($data['speaker']);
		$activitydata['venue_id']=$data['venue_id'];
		$activitydata['activity_date']=MJ_cmgt_get_format_for_db($data['activity_date']);
		$activitydata['activity_end_date']=MJ_cmgt_get_format_for_db($data['activity_end_date']);
		if(isset($data['start_time']))
		$activitydata['activity_start_time']=$data['start_time'];
		if(isset($data['full_day']) && $data['full_day']=='yes')
			$activitydata['activity_start_time']='Full Day';
	    if(isset($data['end_time']))
		$activitydata['activity_end_time']=$data['end_time'];
		if(isset($data['full_day']) && $data['full_day']=='yes')
			$activitydata['activity_end_time']='Full Day';
		$activitydata['record_start_time']=$data['record_start_time'];
		$activitydata['record_end_time']=$data['record_end_time'];
		if(isset($data['reccurence']) && $data['reccurence'])
		$activitydata['recurrence_content']=json_encode($event_content);
		$activitydata['created_date']=date("Y-m-d");
		$activitydata['created_by']=get_current_user_id();
		if(isset($data['group_id']))
		{
			$all_groups='';
			foreach($data['group_id'] as $group_id)
			{
				$all_groups.=$group_id.',';
			}
			$all_groups=rtrim($all_groups,',');
			$activitydata['groups']=$all_groups;
		}
		//-------- EDIT ACTIVITY -----//
		if($data['action']=='edit')
		{
			$activityid['activity_id']=$data['activity_id'];
			$result=$wpdb->update( $table_activity, $activitydata ,$activityid);
			return $result;
		}
		else
		{
			if(isset($data['group_id']))
			{
				foreach($data['group_id'] as $group_id)
				{
					$member_id=MJ_cmgt_get_group_users($group_id);
					global $wpdb;
					$table_cmgt_groups = $wpdb->prefix . "cmgt_group";
					$groupname=$wpdb->get_var("SELECT  group_name FROM $table_cmgt_groups where id = $group_id");
				}
				foreach($member_id as $memberid)
				{
					$membersdata=get_users($memberid);
				}
				foreach ($membersdata as $retrieved_data)
				{
					$getpost= get_post($data['activity_cat_id']);
					$posttitle= $getpost->post_title;
					$vanue_object=new Cmgtvenue;
					$vanudata=$vanue_object->MJ_cmgt_get_single_venue($data['venue_id']);
					$vanue_name=$vanudata->venue_title;
					$to=$retrieved_data->user_email;
					$user_name=$retrieved_data->display_name;
					$subject =get_option('WPChurch_Add_Activity_Subject');
					$page_link=home_url().'/?church-dashboard=user&&page=activity';
					$churchname=get_option('cmgt_system_name');
					$message_content=get_option('WPChurch_Add_Activity_Template');
					$subject_search=array('[CMGT_CHURCH_NAME]');
					$subject_replace=array($churchname);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$search=array('[CMGT_MEMBERNAME]','[CMGT_GROUPNAME]','[CMGT_ACTIVITY_TITLE]',
					'[CMGT_ACTIVITY_CATEGORY]','[CMGT_ACTIVITY_VENUE]','[CMGT_ACTIVITY_REOCCURNCE]','[CMGT_ACTIVITY_START_DATE]',
					'[CMGT_ACTIVITY_END_DATE]','[CMGT_ACTIVITY_START_TIME] ','[CMGT_ACTIVITY_END_TIME]','[CMGT_ACTIVITY_RECORD_START_TIME]',
					'[CMGT_ACTIVITY_RECORD_END_TIME]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
					$replace = array($user_name,$groupname,$data['activity_title'],$posttitle,
					$vanue_name,$data['reccurence'],
					$data['activity_date'],$data['activity_end_date'],$activitydata['activity_start_time'],
					$activitydata['activity_end_time'],$data['record_start_time'],$data['record_end_time'],$page_link,$churchname);
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
				$activity_title = $activitydata['activity_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="$activity_title has been successfully booked for you from $church_name .";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="ACTIVITY";
				$args['message']=$message_content;
				
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
			}

			$result=$wpdb->insert( $table_activity, $activitydata );
			return $result;
		}
	}
	//--------- GET ALL ACTIVITYS ---------//
	public function MJ_cmgt_get_all_activities()
	{
		global $wpdb;
		$table_activity = $wpdb->prefix. 'cmgt_activity';
		$result = $wpdb->get_results("SELECT * FROM $table_activity");
		return $result;
	}
	//--------- GET SINGLE ACTIVITYS ---------//
	public function MJ_cmgt_get_single_activity($id)
	{
		global $wpdb;
		$table_activity = $wpdb->prefix. 'cmgt_activity';
		$result = $wpdb->get_row("SELECT * FROM $table_activity where activity_id=".$id);
		return $result;
	}
	//--------- DELETE ACTIVITYS ---------//
	public function MJ_cmgt_delete_activity($id)
	{
		global $wpdb;
		$table_activity = $wpdb->prefix. 'cmgt_activity';
		$result = $wpdb->query("DELETE FROM $table_activity where activity_id= ".$id);
		return $result;
	}
}
?>