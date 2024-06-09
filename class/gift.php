<?php 
//GIFT CLASS START  
class Cmgtgift
{	
	//------------ ADD GIFT DATA ---------//
	public function MJ_cmgt_add_gift($data,$member_image_url)
	{
		global $wpdb;
		$table_gift = $wpdb->prefix. 'cmgt_gifts';
		$giftdata['gift_type']=MJ_cmgt_strip_tags_and_stripslashes($data['gift_type']);
		$giftdata['gift_name']=stripslashes($data['gift_name']);
		$giftdata['gift_price']=MJ_cmgt_strip_tags_and_stripslashes($data['gift_price']);
		$giftdata['description']=MJ_cmgt_strip_tags_and_stripslashes($data['description']);
		$giftdata['media_gift']=$member_image_url;
		$giftdata['created_date']=date("Y-m-d");
		$giftdata['created_by']=get_current_user_id();
		//------------ EDIT GIFT DATA ---------//
		if($data['action']=='edit')
		{
			$giftid['id']=$data['gift_id'];
			$result=$wpdb->update( $table_gift, $giftdata ,$giftid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_gift, $giftdata );
			return $result;
		}
	}
	//------------ GET ALL GIFT ---------//
	public function MJ_cmgt_get_all_gifts()
	{
		global $wpdb;
		$table_gift = $wpdb->prefix. 'cmgt_gifts';
		$result = $wpdb->get_results("SELECT * FROM $table_gift");
		return $result;
	}
	public function MJ_cmgt_get_all_gifts_creted_by()
	{
		$curr_user_id=get_current_user_id();
		global $wpdb;
		$table_gift = $wpdb->prefix. 'cmgt_gifts';
		$result = $wpdb->get_results("SELECT * FROM $table_gift where created_by=".$curr_user_id);
		return $result;
	}
	//------------ GET SINGLE GIFT ---------//
	public function MJ_cmgt_get_single_gift($id)
	{
		global $wpdb;
		$table_gift = $wpdb->prefix. 'cmgt_gifts';
		$result = $wpdb->get_row("SELECT * FROM $table_gift where id=".$id);
		return $result;
	}
	//------------ DELETE GIFT ---------//
	public function MJ_cmgt_delete_gift($id)
	{
		global $wpdb;
		$table_gift = $wpdb->prefix. 'cmgt_gifts';
		$result = $wpdb->query("DELETE FROM $table_gift where id= ".$id);
		return $result;
	}
	//------------ UPDATE GROUPIMAGE ---------//
	function MJ_cmgt_update_groupimage($id,$imagepath)
	{
		global $wpdb;
		$table_gift = $wpdb->prefix. 'gmgt_groups';
		$image['gmgt_groupimage']=$imagepath;
		$groupid['id']=$id;
		return $result=$wpdb->update( $table_gift, $image, $groupid);
	}
	//------------ GIVE GIFT ---------//
	public function MJ_cmgt_give_gift($data)
	{
		global $wpdb;
		$table_gift_assigned = $wpdb->prefix. 'cmgt_gift_assigned';
		$giftdata['gift_id']=$data['gift_id'];
		$giftdata['member_id']=$data['member_id'];
		$giftdata['gifted_date']=date("Y-m-d");
		$giftdata['gifted_by']=get_current_user_id();
		$result=$wpdb->insert( $table_gift_assigned, $giftdata );
		return $result;
	}
	//------------ GET MEMBERS GIFT ---------//
	public function MJ_cmgt_get_members_gift($id)
	{
		global $wpdb;
		$table_gift_assigned = $wpdb->prefix. 'cmgt_gift_assigned';
		$result = $wpdb->get_results("SELECT * FROM $table_gift_assigned WHERE member_id = $id");
		// var_dump($result);
		// die;
		return $result;
	}
	//------------ SELL GIFT ---------//
	public function MJ_cmgt_sell_gift($data)
	{
		global $wpdb;
		$table_sell_gift = $wpdb->prefix. 'cmgt_gift_store';
		$sellgiftdata['member_id']=$data['member_id'];
		$sellgiftdata['gift_id']=$data['gift_id'];
		$sellgiftdata['gift_price']=$data['gift_price'];
		$sellgiftdata['sell_date']=MJ_cmgt_get_format_for_db($data['sell_date']);
		$sellgiftdata['created_date']=date("Y-m-d");
		$sellgiftdata['created_by']=get_current_user_id();
		//--------- EDIT SELL GIFT------//
		if($data['action']=='edit')
		{
			$sellid['id']=$data['sell_id'];
			$result=$wpdb->update( $table_sell_gift, $sellgiftdata ,$sellid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_sell_gift, $sellgiftdata );
			$userdata_data=get_userdata($data['member_id']);
			$to=$userdata_data->user_email;
			$user_name=$userdata_data->display_name;
			global $wpdb;
			$table_gift = $wpdb->prefix. 'cmgt_gifts';
			$result = $wpdb->get_row("SELECT * FROM $table_gift where id=".$data['gift_id']);
			$giftname=$result->gift_name;
		    $subject =get_option('WPChurch_Sell_Spiritual_Gift_Subject');
			$page_link=home_url().'/?church-dashboard=user&&page=pledges&tab=pledgeslist';
			$churchname=get_option('cmgt_system_name');
			$message_content=get_option('WPChurch_Sell_Spiritual_Gift_Template');
			$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_GIFT_NAME]');
			$subject_replace=array($churchname,$giftname);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			$sell_date=date(MJ_cmgt_date_formate(),strtotime($data['sell_date']));
			$search=array('[CMGT_MEMBERNAME]','[CMGT_GIFT_NAME]','[CMGT_CHURCH_NAME]','[CMGT_GIFT_NAME]','[CMGT_GIFT_PRICE]','[CMGT_GIFT_GOT_DATE]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
			$replace = array($user_name,$giftname,$churchname,$giftname,$data['gift_price'],$sell_date,$page_link,$churchname);
			$message_content = str_replace($search, $replace, $message_content);
			MJ_cmgt_SendEmailNotification($to,$subject,$message_content);

			//---------------- SEND  SMS ------------------//
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			if(is_plugin_active('sms-pack/sms-pack.php'))
			{
				if(!empty($userdata_data->phonecode)){ $phone_code=$userdata_data->phonecode; }else{ $phone_code='+'.MJ_amgt_get_countery_phonecode(get_option( 'cmgt_contry' )); }
						
				$user_number[] = $phone_code.$userdata_data->mobile;
				// $pastoral_title = $pastoraldata['pastoral_title'];
				$church_name=get_option('cmgt_system_name');
				$message_content ="Gift has been successfully booked for you from $church_name .";
				$current_sms_service = get_option( 'smgt_sms_service');
				$args = array();
				$args['mobile']=$user_number;
				$args['message_from']="GIFT";
				$args['message']=$message_content;					
				if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
				{				
					$send = send_sms($args);							
				}
			
			}

			return $result;
		}
	}
	//------------ GET ALL SELl GIFT ---------//
	public function MJ_cmgt_get_all_sell_gifts()
	{
		global $wpdb;
	    $table_sell_gift = $wpdb->prefix. 'cmgt_gift_store';
		$result = $wpdb->get_results("SELECT * FROM $table_sell_gift");
		return $result;
	
	}
	public function MJ_cmgt_get_all_sell_gifts_member_id($user_id)
	{
		global $wpdb;
	    $table_sell_gift = $wpdb->prefix. 'cmgt_gift_store';
		$result = $wpdb->get_results("SELECT * FROM $table_sell_gift where member_id=".$user_id);
		return $result;
	
	}
	public function MJ_cmgt_get_all_sell_gifts_created_by()
	{
		$curr_user_id=get_current_user_id();
		global $wpdb;
	    $table_sell_gift = $wpdb->prefix. 'cmgt_gift_store';
		$result = $wpdb->get_results("SELECT * FROM $table_sell_gift where created_by=".$curr_user_id);
		return $result;
	
	}
	//------------ GET ALL SELl GIFT DASHBOARD ---------//
	public function  MJ_cmgt_get_all_sell_gifts_dashboard()
	{
		global $wpdb;
	    // $table_gifts = $wpdb->prefix. 'cmgt_gifts';
	    $table_sell_gift = $wpdb->prefix. 'cmgt_gift_store';
		$result = $wpdb->get_results("SELECT * FROM $table_sell_gift ORDER BY id DESC LIMIT 3");
		return $result;
	
	}
	//------------ GET ALL SELl GIFT DASHBOARD ---------//
	public function MJ_cmgt_get_member_sell_gifts_dashboard($id)
	{
		$member_id = (int)$id;
		global $wpdb;
	    $table_sell_gift = $wpdb->prefix. 'cmgt_gift_store';
		$result = $wpdb->get_results("SELECT * FROM $table_sell_gift WHERE member_id = $member_id ORDER BY id DESC LIMIT 3");
		return $result;
	
	}
	//------------ GET SINGLE SELL GIFT ---------//
	public function MJ_cmgt_get_single_sell_gift($id)
	{
		global $wpdb;
		$table_sell_gift = $wpdb->prefix. 'cmgt_gift_store';
		$result = $wpdb->get_row("SELECT * FROM $table_sell_gift where id=".$id);
		return $result;
	}
	//------------ DELETE SELL GIFT ---------//
	public function MJ_cmgt_delete_sell_gift($id)
	{
		global $wpdb;
		$table_sell_gift = $wpdb->prefix. 'cmgt_gift_store';
		$result = $wpdb->query("DELETE FROM $table_sell_gift where id= ".$id);
		return $result;
	}
	//generate Sell-Gift number
	public function MJ_cmgt_generate_sell_gift_number($id)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'cmgt_gift_store';
		$result = $wpdb->get_results("SELECT * FROM $table_invoice where id=".$id);
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