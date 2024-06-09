<?php 
 //MESSAGE CLASS START  
class Cmgt_message
{	
	//-------- ADD MESSAGE DATA --------//
	public function MJ_cmgt_add_message($data)
	{
		
		global $wpdb;
		$table_message=$wpdb->prefix."cmgt_message";
		$created_date = date("Y-m-d H:i:s");
		$subject = MJ_cmgt_strip_tags_and_stripslashes($data['subject']);
		$message_body = MJ_cmgt_strip_tags_and_stripslashes($data['message_body']);
		$role=$data['receiver'];
		$userdata=get_users(array('role'=>$role));
		
		if($role == 'member' || $role == 'accountant')
		{ 
		if(!empty($userdata))
		{
			$mail_id = array();
			foreach($userdata as $user)
			{
				$mail_id[]=$user->ID;
			}
			
			$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'cmgt_message',
					'post_title' => $subject,
					'post_content' =>$message_body
			) );
			foreach($mail_id as $user_id)
			{
				$reciever_id = $user_id;
				$message_data=array('sender'=>get_current_user_id(),
						'receiver'=>$user_id,
						'msg_subject'=>$subject,
						'message_body'=>$message_body,
						'msg_date'=>$created_date,
						'post_id'=>$post_id,
						'msg_status' =>0
				);
				$result=$wpdb->insert( $table_message, $message_data );
				// Send Mail Notification mail notification	
                $sender_id=get_current_user_id();
				$senderdata=get_userdata($sender_id);
				$sendername_name=$senderdata->user_login;	
				$reciever_data=get_userdata($user_id);
				$reciever_name=$reciever_data->user_login;
				$to=$reciever_data->user_email;
				$current_user_id=get_current_user_id();
				$current_user_info=get_userdata($current_user_id);						
				if($current_user_info->roles[0] == 'administrator') 
				{
					$page_link=admin_url().'admin.php?page=cmgt-message';
				}
				else{
					$page_link=home_url().'/?church-dashboard=user&&page=message';
				}
				$loginlink=home_url();
				$subject1 =get_option('WPChurch_Message_Received_subject');
		        $church_name=get_option('cmgt_system_name');
		        $message_content=get_option('WPChurch_Message_Received_Template');
				$subject_search=array('[CMGT_SENDER_NAME]','[CMGT_CHURCH_NAME]');
		        $subject_replace=array($sendername_name,$church_name);
		        $search=array('[CMGT_RECEIVER_NAME]','[CMGT_SENDER_NAME]','[CMGT_MESSAGE_CONTENT]','[CMGT_MESSAGE_LINK]','[CMGT_CHURCH_NAME]');
		        $replace = array($reciever_name,$sendername_name,$message_body,$page_link,$church_name);
		        $message_content = str_replace($search, $replace, $message_content);
		        $subject1=str_replace($subject_search,$subject_replace,$subject1);
				MJ_cmgt_SendEmailNotification($to,$subject1,$message_content);
				//end send mail notification
			}
			$result=add_post_meta($post_id, 'message_for',$role);
		
			$result = 1;
		}
		}
		else 
		{
			$user_id = $data['receiver'];
			$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'cmgt_message',
					'post_title' => $subject,
					'post_content' =>$message_body			
			) );
			$message_data=array('sender'=>get_current_user_id(),	
					'receiver'=>$user_id,
					'msg_subject'=>$subject,
					'message_body'=>$message_body,
					'post_id'=>$post_id,
					'msg_date'=>$created_date,
					'msg_status' =>0
			);
			$result=$wpdb->insert($table_message, $message_data );
			// Send Mail Notification mail notification	
                $sender_id=get_current_user_id();
				$senderdata=get_userdata($sender_id);
				$sendername_name=$senderdata->user_login;	
				$reciever_data=get_userdata($user_id);
				$reciever_name=$reciever_data->user_login;
				$to=$reciever_data->user_email;
				$current_user_id=get_current_user_id();
				$current_user_info=get_userdata($current_user_id);						
				if($current_user_info->roles[0] == 'administrator') 
				{						
					$page_link=admin_url().'admin.php?page=cmgt-message';
				}
				else
				{					   
					$page_link=home_url().'/?church-dashboard=user&&page=message';						
				}
				$subject =get_option('WPChurch_Message_Received_subject');
		        $church_name=get_option('cmgt_system_name');
		        $message_content=get_option('WPChurch_Message_Received_Template');
				$subject_search=array('[CMGT_SENDER_NAME]','[CMGT_CHURCH_NAME]');
		        $subject_replace=array($sendername_name,$church_name);
		        $search=array('[CMGT_RECEIVER_NAME]','[CMGT_SENDER_NAME]','[CMGT_MESSAGE_CONTENT]','[CMGT_MESSAGE_LINK]','[CMGT_CHURCH_NAME]');
		        $replace = array($reciever_name,$sendername_name,$message_body,$page_link,$church_name);
		        $message_content = str_replace($search, $replace, $message_content);
		        $subject=str_replace($subject_search,$subject_replace,$subject);
				MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
				//end send mail notification
			$result=add_post_meta($post_id, 'message_for','user');
			$result=add_post_meta($post_id, 'message_for_userid',$user_id);
			
			//---------------- SEND  SMS ------------------//
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			if(is_plugin_active('sms-pack/sms-pack.php'))
			{
				if(!empty($reciever_data->phonecode)){ $phone_code=$reciever_data->phonecode; }else{ $phone_code='+'.MJ_amgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }
						
				$user_number[] = $phone_code.$reciever_data->mobile;
				// $service_title = $servicedata['service_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="$message_body";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="MESSAGE";
				$args['message']=$message_content;
				
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
			}
		}
		return $result;
	}
	//---------- DELETE MESSAGE ---------//
	/*public function MJ_cmgt_delete_message($mid)
	{
		global $wpdb;
		$table_hmgt_message = $wpdb->prefix. 'cmgt_message';
		$result = $wpdb->query("DELETE FROM $table_hmgt_message where message_id= ".$mid);
		
		return $result;
	}*/
	public function MJ_cmgt_delete_message($mid)
	{
		global $wpdb;
		$table_hmgt_message = $wpdb->prefix. 'cmgt_message';
		$result = $wpdb->query("DELETE FROM $table_hmgt_message where post_id= ".$mid);
		
		return $result;
	}
	//-------- COUNT SEND ITEMS --------//
	public function MJ_cmgt_count_send_item($user_id)
	{
		global $wpdb;
		$posts = $wpdb->prefix."posts";
		$total =$wpdb->get_var("SELECT Count(*) FROM ".$posts." Where post_type = 'cmgt_message' AND post_author = $user_id");
		return $total;
	}
	//----------- COUNT INBOX ITEMS --------//
	public function MJ_cmgt_count_inbox_item($user_id)
	{
		global $wpdb;
		$tbl_name_message = $wpdb->prefix .'cmgt_message';
		$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name_message where receiver = $user_id");
		return $inbox;
	}
	//----------- GET INBOX MESSAGES --------//
	public function MJ_cmgt_get_inbox_message($user_id,$p=0,$lpm1=10)
	{
		global $wpdb;
		$tbl_name_message = $wpdb->prefix .'cmgt_message';
		$tbl_name_message_replies = $wpdb->prefix .'cmgt_message_replies';
		$inbox = $wpdb->get_results("SELECT DISTINCT b.message_id, a.* FROM $tbl_name_message a LEFT JOIN $tbl_name_message_replies b ON a.post_id = b.message_id WHERE ( a.receiver = $user_id OR b.receiver_id =$user_id) group by a.post_id ORDER BY msg_date DESC limit $p , $lpm1");

		return $inbox;
	}
	//----------- CMGT PAGINATION --------//
	public function MJ_cmgt_pagination($totalposts,$p,$prev,$next,$page)
	{
		$pagination = "";
		if($totalposts > 1)
		{
			$pagination .= '<div class="btn-group">';
			if ($p > 1)
				$pagination.= "<a href=\"?$page&pg=$prev\" class=\"btn btn-default\"><i class=\"fa fa-angle-left\"></i></a> ";
			else
				$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-left\"></i></a> ";
		
			if ($p < $totalposts)
				$pagination.= " <a href=\"?$page&pg=$next\" class=\"btn btn-default next-page\"><i class=\"fa fa-angle-right\"></i></a>";
			else
				$pagination.= " <a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-right\"></i></a>";
			$pagination.= "</div>\n";
		}
		
		return $pagination;
	}
	//----------- GET SEND MESSAGE --------//
	public function MJ_cmgt_get_send_message($user_id,$max=10,$offset=0)
	{	
		$args['post_type'] = 'cmgt_message';
		$args['posts_per_page'] =$max;
		$args['offset'] = $offset;
		$args['post_status'] = 'public';
		$args['author'] = $user_id;			
		$q = new WP_Query();
		$sent_message = $q->query( $args );
		return $sent_message;
	}
	//----------- GET MESSAGE BY ID --------//
	public function MJ_cmgt_get_message_by_id($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_message";
		$qry = $wpdb->prepare( "SELECT * FROM $table_name WHERE message_id= %d",$id);
		return $retrieve_subject = $wpdb->get_row($qry);
	}
	//----------- SEND REPLAY MESSAGE --------//
	public function MJ_cmgt_send_replay_message($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_message_replies";
		if(!empty($data['receiver_id']))
		{
			foreach($data['receiver_id'] as $receiver_id)
			{
				$messagedata['message_id'] = $data['message_id'];
				$messagedata['sender_id'] = $data['user_id'];
				$messagedata['receiver_id'] = $receiver_id;
				$messagedata['msg_status'] =0;
				$messagedata['message_comment'] = $data['replay_message_body'];
				$messagedata['created_date'] = date("Y-m-d h:i:s");
				$result=$wpdb->insert( $table_name, $messagedata );
			}
		}
		if($result)	
			return $result;
	}
	//----------- GET ALL REPLAYS --------//
	public function MJ_cmgt_get_all_replies($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_message_replies";
		$result =$wpdb->get_results("SELECT * FROM $table_name where message_id = $id GROUP BY message_id,sender_id,message_comment ORDER BY id ASC");
		return $result;
		//return $result =$wpdb->get_results("SELECT *  FROM $table_name where message_id = $id");
	}
	public function MJ_cmgt_get_all_replies_frontend($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_message_replies";
		$result =$wpdb->get_results("SELECT * FROM $table_name where message_id = $id ");
		return $result;
	}
	//----------- DELETE REPLAY --------//
	public function MJ_cmgt_delete_reply($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_message_replies";
		$reply_id['id']=$id;
		return $result=$wpdb->delete( $table_name, $reply_id);
	}
	//-------- GET Message dashbord --------//
	public function MJ_cmgt_get_my_messagelist_dashboard()
	{
		$user_id = get_current_user_id();
		$p=0;
		$lpm1=3;
		global $wpdb;
		$tbl_name_message = $wpdb->prefix .'cmgt_message';
		$tbl_name_message_replies = $wpdb->prefix .'cmgt_message_replies';
		$inbox = $wpdb->get_results("SELECT DISTINCT b.message_id, a.* FROM $tbl_name_message a LEFT JOIN $tbl_name_message_replies b ON a.post_id = b.message_id WHERE ( a.receiver = $user_id OR b.receiver_id =$user_id) group by a.post_id ORDER BY msg_date DESC limit $p , $lpm1");
		return $inbox;
	}
	
	//-------- GET SINGLE Message dashbord --------//
	public function MJ_cmgt_get_my_single_message_dashboard()
	{
		$user_id = get_current_user_id();
		// var_dump($message_id);
		// die;
		$p=0;
		$lpm1=3;
		global $wpdb;
		$tbl_name_message = $wpdb->prefix .'cmgt_message';
		$tbl_name_message_replies = $wpdb->prefix .'cmgt_message_replies';
		$inbox = $wpdb->get_results("SELECT  b.message_id, a.* FROM $tbl_name_message a LEFT JOIN $tbl_name_message_replies b ON a.post_id = b.message_id WHERE ( a.message_id = $user_id OR b.message_id =$user_id) group by a.post_id ORDER BY msg_date DESC limit $p , $lpm1");
		// var_dump($inbox);
		// die;
		return $inbox;
	}
	//-------- GET SINGLE Message-User dashbord --------//
	public function MJ_cmgt_get_my_single_member_message_dashboard($id)
	{
		global $wpdb;
		$tbl_name_message = $wpdb->prefix. 'cmgt_message';
		$result = $wpdb->get_row("SELECT * FROM $tbl_name_message where message_id=".$id);
		return $result;
	}
	
	//----------- COUNT REPLAY ITEMS --------//
	public function MJ_cmgt_count_reply_item($id)
	{
		global $wpdb;
		$tbl_name = $wpdb->prefix .'cmgt_message_replies';
		$result=$wpdb->get_results("SELECT * FROM $table_name where message_id = $id");
		
		return $result;
	}

	// hitesh
}


	

?>