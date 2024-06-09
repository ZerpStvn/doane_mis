<?php 
//SERVICES CLASS START  
class Cmgtservice
{	
	//-------- ADD SERVICES DATA-----------//
	public function MJ_cmgt_add_service($data)
	{
		global $wpdb;
		$table_service = $wpdb->prefix. 'cmgt_service';
		$servicedata['service_type_id']=$data['service_type_id'];
		//$servicedata['service_title']=MJ_cmgt_strip_tags_and_stripslashes($data['service_title']);
		$servicedata['service_title']=stripslashes($data['service_title']);
		$servicedata['start_date']=MJ_cmgt_get_format_for_db($data['start_date']);
		$servicedata['end_date']=MJ_cmgt_get_format_for_db($data['end_date']);
		$servicedata['start_time']=$data['start_time'];
		$servicedata['end_time']=$data['end_time'];
		$servicedata['other_title']=MJ_cmgt_strip_tags_and_stripslashes($data['other_title']);
		$servicedata['other_service_type']=MJ_cmgt_strip_tags_and_stripslashes($data['other_service_type']);
		$servicedata['other_service_date']=MJ_cmgt_get_format_for_db($data['other_service_date']);
		$servicedata['other_start_time']=$data['other_start_time'];
		$servicedata['other_end_time']=$data['other_end_time'];
		$servicedata['created_date']=date("Y-m-d");
		$servicedata['created_by']=get_current_user_id();
		//-------- EDIT SERVICES DATA-----------//
		if($data['action']=='edit')
		{
			$serviceid['id']=$data['service_id'];
			$result=$wpdb->update( $table_service, $servicedata ,$serviceid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_service, $servicedata);
			$get_members = array('role' => 'member');
			$membersdata=get_users($get_members);
			if(!empty($membersdata))
		    {
				foreach ($membersdata as $retrieved_data){
				$to=$retrieved_data->user_email;
				$user_name=$retrieved_data->display_name;
				$subject =get_option('WPChurch_servic_subject');
				$page_link=home_url().'/?church-dashboard=user&&page=services';
				$churchname=get_option('cmgt_system_name');
				$message_content=get_option('WPChurch_Add_Service_Template');
				$subject_search=array('[CMGT_CHURCH_NAME]');
				$subject_replace=array($churchname);
				$convert_start_date=date(MJ_cmgt_date_formate(),strtotime($data['start_date']));
				$convert_end_date=date(MJ_cmgt_date_formate(),strtotime($data['end_date']));
				if(isset($data['other_service_date']))
				{
					$convert_other_service_date=date(MJ_cmgt_date_formate(),strtotime($data['other_service_date']));
				}
				$subject=str_replace($subject_search,$subject_replace,$subject);
				$search=array('[CMGT_MEMBERNAME]','[CMGT_SERVICE_TITLE]','[CMGT_SERVICE_START_DATE]','[CMGT_SERVICE_END_DATE]','[CMGT_SERVICE_START_TIME]','[CMGT_SERVICE_END_TIME]','[CMGT_OTHER_SERVICE_TITLE]','[CMGT_OTHER_SERVICE_TYPE]','[CMGT_OTHER_SERVICE_DATE]','[CMGT_OTHER_SERVICE_START_TIME]','[CMGT_OTHER_SERVICE_END_TIME]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
				$replace = array($user_name,$data['service_title'],$convert_start_date,$convert_end_date,$data['start_time'],$data['end_time'],$data['other_title'],$data['other_service_type'],$convert_other_service_date,$data['other_start_time'],$data['other_end_time'],$page_link,$churchname);
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
				$service_title = $servicedata['service_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="$service_title has been successfully booked for you from $church_name .";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="SERVICE";
				$args['message']=$message_content;
				
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
			}
			return $result;
		}
	}
	//---------- GET ALL SERVICES ----------//
	public function MJ_cmgt_get_all_services()
	{
		global $wpdb;
		$table_service = $wpdb->prefix. 'cmgt_service';
		$result = $wpdb->get_results("SELECT * FROM $table_service");
		return $result;
	}
	//---------- GET ALL SERVICES DASHBOARD ----------//
	public function MJ_cmgt_get_all_services_dashboard()
	{
		global $wpdb;
		$table_service = $wpdb->prefix. 'cmgt_service';
		$result = $wpdb->get_results("SELECT * FROM $table_service ORDER BY id DESC LIMIT 3");
		return $result;
	}
	//---------- GET SINGLE SERVICES ----------//
	public function MJ_cmgt_get_single_services($id)
	{
		global $wpdb;
		$table_service = $wpdb->prefix. 'cmgt_service';
		$result = $wpdb->get_row("SELECT * FROM $table_service where id=".$id);
		return $result;
	}
	//---------- DELETE SERVICES ----------//
	public function MJ_cmgt_delete_services($id)
	{
		global $wpdb;
		$table_service = $wpdb->prefix. 'cmgt_service';
		$result = $wpdb->query("DELETE FROM $table_service where id= ".$id);
		return $result;
	}
}
?>