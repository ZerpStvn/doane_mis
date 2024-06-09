<?php 
 //TRANSACTION CLASS START  
class Cmgttransaction
{	
	//-------- ADD TRANSCATION DATA ------//
	public function MJ_cmgt_add_transaction($data)
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$transactiondata['member_id']=$data['member_id'];
		$transactiondata['transaction_date']=$data['transaction_date'];
		$transactiondata['amount']=MJ_cmgt_strip_tags_and_stripslashes($data['amount']);
		$transactiondata['pay_method']=MJ_cmgt_strip_tags_and_stripslashes($data['pay_method']);
		$transactiondata['donetion_type']=MJ_cmgt_strip_tags_and_stripslashes($data['donetion_type']);
		if($data['pay_method'] == 'PayUMony')
		{
			$transactiondata['transaction_id']=$data['trasaction_id'];
			$transactiondata['description']='';
		}
		else
		{
			$transactiondata['transaction_id']=$data['transaction_id'];
			$transactiondata['description']=MJ_cmgt_strip_tags_and_stripslashes($data['description']);
		}
		$transactiondata['created_date']=date("Y-m-d");
		$transactiondata['created_by']=get_current_user_id();
		     
			//-------- EDIT TRANSCATION DATA ------//
		if($data['action']=='edit')
		{
			$transactionid['id']=$data['transaction_id'];
			$result=$wpdb->update( $table_transaction, $transactiondata ,$transactionid);
			return $result;
		}
		else
		{ 
			$result=$wpdb->insert( $table_transaction, $transactiondata);
			$id=$wpdb->insert_id;
			//add transaction  invoice  send mail html contant
			$userdata=get_userdata($data['member_id']);
			$user_name=$userdata->display_name;
			$to=$userdata->user_email;
		    $subject =get_option('WPChurch_Add_Transaction_Subject');
			$page_link=home_url().'/?church-dashboard=user&&page=donate&tab=transactionlist';
			$churchname=get_option('cmgt_system_name');
			$church_logo=get_option('cmgt_system_logo');
			$message_content=get_option('WPChurch_Add_Transaction_Template');
			$subject_search=array('[CMGT_CHURCH_NAME]');
			$subject_replace=array($churchname);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			$search=array('[CMGT_MEMBERNAME]','[CMGT_PAYMENT_LINK]','[CMGT_CHURCH_NAME]');
			$replace = array($user_name,$page_link,$churchname,$church_logo);
			$message_content = str_replace($search, $replace, $message_content);
			//$resultInvoice=MJ_cmgt_send_transaction_send_mail_html_content($id,'transaction');
			//$message_content.=$resultInvoice;
			//MJ_cmgt_cmgSendEmailNotificationWithHTML($to,$subject,$message_content);
			$invoice_type = 'transaction';
			MJ_cmgt_send_invoice_generate_mail($to,$subject,$message_content,$id,$invoice_type);
			
			//paid against invoice   send mail html contant
			//$userdata=get_userdata($data['member_id']);
			$user_name=$userdata->display_name;
			$to=$userdata->user_email;
		    $subject =get_option('WPChurch_Payment_Received_against_Transaction_Invoice_Subject');
			$page_link=home_url().'/?church-dashboard=user&&page=donate&tab=transactionlist';
			$churchname=get_option('cmgt_system_name');
			$church_logo=get_option('cmgt_system_logo');
			$message_content=get_option('WPChurch_Payment_Received_against_Transaction_Invoice_Template');
			$subject_search=array('[CMGT_CHURCH_NAME]');
			$subject_replace=array($churchname);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			$search=array('[CMGT_MEMBERNAME]','[CMGT_CHURCH_NAME]');
			$replace = array($user_name,$churchname,$church_logo);
			$message_content = str_replace($search, $replace, $message_content);
			//$resultInvoice=MJ_cmgt_send_transaction_send_mail_html_content($id,'transaction');
			//$message_content.=$resultInvoice;
			//MJ_cmgt_cmgSendEmailNotificationWithHTML($to,$subject,$message_content,$church_logo);
			$invoice_type = 'transaction';
			MJ_cmgt_send_invoice_generate_mail($to,$subject,$message_content,$id,$invoice_type);
			//Add Donation Send Mail Member  Template
			$userdata=get_userdata($data['member_id']);
			$user_name=$userdata->display_name;
			$to=$userdata->user_email;
			$getpost= get_post($data['donetion_type']);
            $donention_type=$getpost->post_title;
		    $subject =get_option('WPChurch_Add_Donation_subject');
			$page_link=home_url().'/?church-dashboard=user&&page=donate&tab=transactionlist';
			$churchname=get_option('cmgt_system_name');
			$church_logo=get_option('cmgt_system_logo');
			$message_content=get_option('WPChurch_Add_Donation_Template');
			$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_DONATION_AMOUNT]','[CMGT_DONATION_DATE]');
			$subject_replace=array($churchname,$data['amount'],$data['transaction_date']);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			$search=array('[CMGT_MEMBERNAME]','[CMGT_DONATION_TYPE]','[CMGT_TRANSACTION_PAGE_LINK]','[CMGT_CHURCH_NAME]');
			$replace = array($user_name,$donention_type,$page_link,$churchname,$church_logo);
			$message_content = str_replace($search, $replace, $message_content);
			//$resultInvoice=MJ_cmgt_send_transaction_send_mail_html_content($id,'transaction');
			//$message_content.=$resultInvoice;
			//MJ_cmgt_cmgSendEmailNotificationWithHTML($to,$subject,$message_content,$church_logo);
			$invoice_type = 'transaction';
			MJ_cmgt_send_invoice_generate_mail($to,$subject,$message_content,$id,$invoice_type);
			//Add Donation Send Mail Admin  Template
			$get_members = array('role' => 'administrator');
			$membersdata=get_users($get_members);
		   if(!empty($membersdata))
		    {
				foreach ($membersdata as $retrieved_data)
				{
					if(!is_super_admin($retrieved_data->ID))
					{
						$userdata=get_userdata($data['member_id']);
						$user_name=$userdata->display_name;
						$to=$retrieved_data->user_email;
						$admin_name=$retrieved_data->display_name;
						$subject =get_option('WPChurch_Add_Donation_Admin_subject');
						$page_link=home_url().'/?church-dashboard=user&&page=donate&tab=transactionlist';
						$churchname=get_option('cmgt_system_name');
						$church_logo=get_option('cmgt_system_logo');
						$message_content=get_option('WPChurch_Add_Donation_Admin_Template');
						$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_MEMBERNAME]');
						$subject_replace=array($churchname,$user_name);
						$subject=str_replace($subject_search,$subject_replace,$subject);
						$search=array('[CMGT_ADMIN_NAME]','[CMGT_MEMBERNAME]','[CMGT_DONATION_LINK]','[CMGT_CHURCH_NAME]');
						$replace = array($admin_name,$user_name,$page_link,$churchname,$church_logo);
						$message_content = str_replace($search, $replace, $message_content);
						$resultInvoice=MJ_cmgt_send_transaction_send_mail_html_content($id,'transaction');
						$message_content.=$resultInvoice;
						MJ_cmgt_cmgSendEmailNotificationWithHTML($to,$subject,$message_content,$church_logo);
					}
				}
			}
			$userdata=get_userdata($data['member_id']);
		
			//---------------- SEND  SMS ------------------//
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			if(is_plugin_active('sms-pack/sms-pack.php'))
			{
				if(!empty($userdata->phonecode)){ $phone_code=$userdata->phonecode; }else{ $phone_code='+'.MJ_amgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }
						
				$user_number[] = $phone_code.$userdata->mobile;
				// $pastoral_title = $pastoraldata['pastoral_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="Transaction has been successfully booked for you from $church_name .";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="TRANSACTION";
				$args['message']=$message_content;					
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
				
			}

			return $result;
		}
	}
	//------------ ADD PAYPAl TRANSACTION --------//
	public function MJ_cmgt_add_paypal_transaction($transactiondata)
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$transactiondata['created_date']=date("Y-m-d");
		$transactiondata['created_by']=get_current_user_id();
		$result=$wpdb->insert( $table_transaction, $transactiondata );
	}
	//------------- GET ALL TRANSACTION ----------//
	public function MJ_cmgt_get_all_transaction()
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->get_results("SELECT * FROM $table_transaction");
		return $result;
	}
	
	//------------- GET ALL TRANSACTION Own ----------//
	public function MJ_cmgt_get_all_transaction_own_member($member_id)
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->get_results("SELECT * FROM $table_transaction where member_id=$member_id");
		return $result;
	}
	//------------- GET SINGLE TRANSACTION ----------//
	public function MJ_cmgt_get_single_transaction($id)
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->get_row("SELECT * FROM $table_transaction where id=".$id);
		return $result;
	}
	//------------- GET MY DONATIONLIST ----------//
	public function MJ_cmgt_get_my_donationlist($id)
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->get_results("SELECT * FROM $table_transaction where member_id=".$id);
		return $result;
	}
	//------------- GET MY SINGLE DONATIONLIST ----------//
	public function MJ_cmgt_get_my_single_donationlist($id)
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->get_row("SELECT * FROM $table_transaction where id=".$id);
		return $result;
	}
	//------------- GET MY DONATIONLIST DASHBOARD ----------//
	public function MJ_cmgt_get_my_donationlist_dashboard()
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->get_results("SELECT * FROM $table_transaction ORDER BY id DESC LIMIT 5 ");
		return $result;
	}
	//------------- GET MY DONATIONLIST DASHBOARD BY ROLE ----------//
	public function MJ_cmgt_get_my_donationlist_dashboard_by_role($id)
	{
		$member_id = (int)$id;
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->get_results("SELECT * FROM $table_transaction WHERE member_id = $member_id ORDER BY id DESC LIMIT 5 ");
		return $result;
	}
	//------------- GET MY DONATION  ----------//
	public function MJ_cmgt_get_my_aadonation()
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->get_results("SELECT * FROM $table_transaction");
		return $result;
	}
	//------------- DELETE TRANSCATION ----------//
	public function MJ_cmgt_delete_transaction($id)
	{
		global $wpdb;
		$table_transaction = $wpdb->prefix. 'cmgt_transaction';
		$result = $wpdb->query("DELETE FROM $table_transaction where id= ".$id);
		return $result;
	}
}
?>