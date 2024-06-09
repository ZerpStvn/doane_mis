<?php 
 //PLADGE CLASS START  
class Cmgtpledes
{	
	//--------- ADD PLEDGE DATA -----------//
	public function MJ_cmgt_add_pledges($data)
	{
		global $wpdb;
		$table_pledge = $wpdb->prefix. 'cmgt_pledges';
		$pledgesdata['member_id']=$data['member_id'];
		$pledgesdata['start_date']=MJ_cmgt_get_format_for_db($data['start_date']);
		$pledgesdata['amount']=MJ_cmgt_strip_tags_and_stripslashes($data['amount']);
		$pledgesdata['period_id']=$data['period_id'];
		$pledgesdata['times_number']=MJ_cmgt_strip_tags_and_stripslashes($data['times_number']);
		$pledgesdata['end_date']=MJ_cmgt_get_format_for_db($data['end_date']);
		$pledgesdata['total_amount']=MJ_cmgt_strip_tags_and_stripslashes($data['total_amount']);
		$pledgesdata['created_date']=date("Y-m-d");
		$pledgesdata['created_by']=get_current_user_id();
		//--------- EDIT PLEDGE DATA -----------//
		if($data['action']=='edit')
		{
			$pledged['id']=$data['pledge_id'];
			$result=$wpdb->update( $table_pledge, $pledgesdata ,$pledged);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_pledge, $pledgesdata);
			$curentuser_id=get_current_user_id();
			$currentuserdata=get_userdata($curentuser_id);
			$curent_user_name=$currentuserdata->display_name;
			$retrieved_data=get_userdata($data['member_id']);
			$to=$retrieved_data->user_email;
			$user_name=$retrieved_data->display_name;
		    $subject =get_option('WPChurch_Add_Pledges_Subject');
			$page_link=home_url().'/?church-dashboard=user&&page=pledges&tab=pledgeslist';
			$churchname=get_option('cmgt_system_name');
			$message_content=get_option('WPChurch_Add_Pledges_Template');
			$subject_search=array('[CMGT_CHURCH_NAME]',' [USER]');
			$subject_replace=array($churchname,$curent_user_name);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			
			$start_date=date(MJ_cmgt_date_formate(),strtotime($data['start_date']));
	        $end_date=date(MJ_cmgt_date_formate(),strtotime($data['end_date']));
			
			$search=array('[CMGT_MEMBERNAME]','[CMGT_CHURCH_NAME]',' [CMGT_START_DATE]','[CMGT_END_DATE]','[CMGT_PLEDGES_AMOUNT]','[CMGT_PLEDGES_FREQUENCY]','[CMGT_PLEDGES_TOTAL_AMOUNT]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
			$replace = array($user_name,$churchname,$start_date,$end_date,$data['amount'],$data['period_id'],$data['total_amount'],$page_link,$churchname);
			$message_content = str_replace($search, $replace, $message_content);
			MJ_cmgt_SendEmailNotification($to,$subject,$message_content);

			//---------------- SEND  SMS ------------------//
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			if(is_plugin_active('sms-pack/sms-pack.php'))
			{
				if(!empty($retrieved_data->phonecode)){ $phone_code=$retrieved_data->phonecode; }else{ $phone_code='+'.MJ_amgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }
						
				$user_number[] = $phone_code.$retrieved_data->mobile;
				// $pastoral_title = $pastoraldata['pastoral_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="Pledges has been successfully booked for you from $church_name .";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="PLEDGES";
				$args['message']=$message_content;					
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
				
			}

			return $result;
		}
	}
	//--------- GET ALL PLEDGE ---------//	
	public function MJ_cmgt_get_all_pledges()
	{
		global $wpdb;
		$table_pledge = $wpdb->prefix. 'cmgt_pledges';
		$result = $wpdb->get_results("SELECT * FROM $table_pledge");
		return $result;
	}
	public function MJ_cmgt_get_my_pledgeslist_creted_by()
	{
		$curr_user_id=get_current_user_id();
		global $wpdb;
		$table_pledge = $wpdb->prefix. 'cmgt_pledges';
		$result = $wpdb->get_results("SELECT * FROM $table_pledge where created_by=".$curr_user_id);
		return $result;
	}
	//--------- GET SINGLE PLEDGE ---------//	
	public function MJ_cmgt_get_single_pledges($id)
	{
		global $wpdb;
		$table_pledge = $wpdb->prefix. 'cmgt_pledges';
		$result = $wpdb->get_row("SELECT * FROM $table_pledge where id=".$id);
		return $result;
	}
	//--------- DELETE PLEDGE ---------//	
	public function MJ_cmgt_delete_pledges($id)
	{
		global $wpdb;
		$table_pledge = $wpdb->prefix. 'cmgt_pledges';
		$result = $wpdb->query("DELETE FROM $table_pledge where id= ".$id);
		return $result;
	}
	//--------- GET MY PLEDGELIST ---------//	
	public function MJ_cmgt_get_my_pledgeslist($id)
	{
		global $wpdb;
		$table_pledge = $wpdb->prefix. 'cmgt_pledges';
		$result = $wpdb->get_results("SELECT * FROM $table_pledge where member_id=".$id);
		return $result;
	}
	//------------- GET MY PLEDGES DASHBOARD ----------//
	public function MJ_cmgt_get_my_pledgeslist_dashboard()
	{
		global $wpdb;
		$table_pledge = $wpdb->prefix. 'cmgt_pledges';
		$result = $wpdb->get_results("SELECT * FROM $table_pledge ORDER BY id DESC LIMIT 3 ");
		return $result;
	}
	//--------- GET ALL PLEDGE ---------//	
	public function MJ_cmgt_get_All_member_pledges($member_id)
	{
		global $wpdb;
		$table_pledge = $wpdb->prefix. 'cmgt_pledges';
		//$result = $wpdb->get_results("SELECT * FROM $table_pledge where member_id=".$member_id);

		$result = $wpdb->get_results("SELECT * FROM $table_pledge where member_id=".$member_id." ORDER BY id DESC LIMIT 3");
		return $result;
	}
	//generate Pledges number
	public function MJ_cmgt_generate_pledges_number($id)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'cmgt_pledges';
		
		$result = $wpdb->get_results("SELECT * FROM $table_invoice  where id= ".$id);
		foreach($result as $id)
		{
			$result_1 = $id->id;
		}
		
		if(!empty($result))
		{	
			$res = $result_1;
			$number = str_pad($res, 4, '0', STR_PAD_LEFT);
			return $number;
		}
		else 
		{			
			$res = 1;
			$number = str_pad($res, 4, '0', STR_PAD_LEFT);
			return $number;
		}
	}
}
?>