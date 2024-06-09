<?php 
 //PAYMENT CLASS START  
class Cmgtpayment
{	
	//-------- GET ENTRY RECORD -------//
	public function MJ_cmgt_get_entry_records($data)
	{
		$all_income_entry=$data['income_entry'];
		$all_income_amount=$data['income_amount'];
		$entry_data=array();
		$i=0;
		foreach($all_income_entry as $one_entry)
		{
			$entry_data[]= array('entry'=>$one_entry,
						'amount'=>$all_income_amount[$i]);
				$i++;
		}
		return json_encode($entry_data);
	}
	//-------- ADD INCOME ----------//
	public function MJ_cmgt_add_income($data)
	{
		$entry_value=$this->MJ_cmgt_get_entry_records($data);
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$incomedata['invoice_type']=MJ_cmgt_strip_tags_and_stripslashes($data['invoice_type']);
		$incomedata['invoice_label']=stripslashes(MJ_cmgt_strip_tags_and_stripslashes($data['invoice_label']));
		$incomedata['supplier_name']=MJ_cmgt_strip_tags_and_stripslashes($data['supplier_name']);
		$incomedata['invoice_date']=MJ_cmgt_get_format_for_db($data['invoice_date']);
		$incomedata['payment_status']=MJ_cmgt_strip_tags_and_stripslashes($data['payment_status']);
		$incomedata['entry']=$entry_value;
		$incomedata['receiver_id']=get_current_user_id();
		if($data['action']=='edit')
		{
			$income_dataid['invoice_id']=$data['income_id'];
			$result=$wpdb->update( $table_income, $incomedata ,$income_dataid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_income,$incomedata);
			$id=$wpdb->insert_id;
			//Add Income Send Mailnoitification Template
			$userdata=get_userdata($data['supplier_name']);
			$user_name=$userdata->display_name;
			$to=$userdata->user_email;
			       //get userrolesbyid
			         $user= get_userdata($data['supplier_name']);
					 $role=$user->roles;
					 $userrole=$role[0];
		    $subject =get_option('WPChurch_Add_Income_Subject');
			$page_link=home_url().'/?church-dashboard=user&&page=donate&tab=transactionlist';
			$churchname=get_option('cmgt_system_name');
			$message_content=get_option('WPChurch_Add_Income_Template');
			$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_USER_ROLE]');
			$subject_replace=array($churchname,$userrole);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			$search=array('[CMGT_MEMBERNAME]','[CMGT_INVOICE_LINK]','[CMGT_CHURCH_NAME]');
			$replace = array($user_name,$page_link,$churchname);
			$message_content = str_replace($search, $replace, $message_content);
			//$resultInvoice=MJ_cmgt_send_transaction_send_mail_html_content($id,'income');
			//$message_content.=$resultInvoice;
			//MJ_cmgt_cmgSendEmailNotificationWithHTML($to,$subject,$message_content);
			$invoice_type = 'income';
			MJ_cmgt_send_invoice_generate_mail($to,$subject,$message_content,$id,$invoice_type);
			return $result;
		}
	}
	//------- GET ALL INCOME ----------//
	public function MJ_cmgt_get_all_income_data()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income'");
		return $result;
	}
	//------- GET ALL INVOICE ----------//
	public function MJ_cmgt_get_all_invoice_data()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_income");
		return $result;
	}
	
	//------- GET ALL INCOME ----------//
	public function MJ_cmgt_get_all_income_data_own_member($member_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' and supplier_name = $member_id");
		return $result;
	}
	//------- GET ALL INCOME DASHBOARD ----------//
	public function MJ_cmgt_get_all_income_data__dashboard()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' ORDER BY invoice_id DESC LIMIT 3");
		return $result;
	}
	//------- DELETE INCOME ----------//
	public function MJ_cmgt_delete_income($income_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->query("DELETE FROM $table_income where invoice_id= ".$income_id);
		return $result;
	}
	//------- DELETE Transaction ----------//
	public function MJ_cmgt_delete_transaction($id)
	{
		global $wpdb;
		$table_cmgt_transaction=$wpdb->prefix.'cmgt_transaction';
		$result = $wpdb->query("DELETE FROM $table_cmgt_transaction where id= ".$id);
		return $result;
	}
	//------- GET ALL INCOME DATA ----------//
	public function MJ_cmgt_get_income_data($income_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_row("SELECT * FROM $table_income where invoice_id= ".$income_id);
		return $result;
	}
	//-----------Expense-----------------//
	//----------- ADD EXPENSE DATA -------//
	public function MJ_cmgt_add_expense($data)
	{
		$entry_value=$this->MJ_cmgt_get_entry_records($data);
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$incomedata['invoice_type']=$data['invoice_type'];
		$incomedata['supplier_name']=$data['supplier_name'];
		$incomedata['invoice_date']=MJ_cmgt_get_format_for_db($data['invoice_date']);
		$incomedata['payment_status']=$data['payment_status'];
		$incomedata['entry']=$entry_value;
		$incomedata['receiver_id']=get_current_user_id();
		//------- EDIT EXPENCE ------//
		if($data['action']=='edit')
		{
			$expense_dataid['invoice_id']=$data['expense_id'];
			$result=$wpdb->update( $table_income, $incomedata ,$expense_dataid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_income,$incomedata);
			return $result;
		}
	}
	//------- GET ALL EXPENSE DATA ----------//
	public function MJ_cmgt_get_all_expense_data()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='expense'");
		return $result;
	}
	//------- GET ONE PARTY INCOME DATA ----------//
	public function  MJ_cmgt_get_oneparty_income_data($party_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_income where supplier_name= '".$party_id."' order by invoice_date desc");
		return $result;
	}
	//generate Payment number
	public function MJ_cmgt_generate_invoce_number($id)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'cmgt_transaction';
		
		$result = $wpdb->get_results("SELECT * FROM $table_invoice where id= ".$id);
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
	
	//generate Income/Expence number
	public function MJ_cmgt_generate_income_expence_number($id)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_invoice where invoice_id= ".$id);
		foreach($result as $id)
		{
			$result_1 = $id->invoice_id;
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

	public function  MJ_cmgt_get_single_income_data_by_invoice_id($invoice_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'cmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_id= '".$invoice_id."' order by invoice_date desc");
		// var_dump($result);
		// die;
		return $result;
	}

}
?>