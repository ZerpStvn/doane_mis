<?php 
//CHECKIN CLASS START 
class Cmgtcehckin
{	
	//--------- ADD ROOM -------//
	public function MJ_cmgt_add_room($data)
	{
		global $wpdb;
		$table_room = $wpdb->prefix. 'cmgt_room';
		$roomdata['room_title']=stripslashes($data['room_title']);
		$roomdata['capacity']=MJ_cmgt_strip_tags_and_stripslashes($data['capacity']);
		$roomdata['status']='Available';
		$roomdata['created_by']=get_current_user_id();
		$roomdata['created_date']=date("Y-m-d");
		$checkbox1=array();
		if(isset($data['demographics']))
		  $checkbox1=$data['demographics'];  
		$chk="";  
		foreach($checkbox1 as $chk1)  
		   {  
			  $chk .= $chk1.",";  
		   }  
		$roomdata['demographics']= $chk;   
		if($data['action']=='edit')
		{
			$roomid['id']=$data['room_id'];
			$result=$wpdb->update( $table_room, $roomdata ,$roomid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_room, $roomdata );
			return $result;
		}
	}
	//--------- GET ALL ROOM -------//
	public function MJ_cmgt_get_all_room()
	{
		global $wpdb;
		$table_room = $wpdb->prefix. 'cmgt_room';
		$result = $wpdb->get_results("SELECT * FROM $table_room");
		return $result;
	}
	//--------- GET MEMBERS ROOM -------//
	public function MJ_cmgt_get_members_room($user_id)
	{
		global $wpdb;
		$table_room = $wpdb->prefix. 'cmgt_room';
		$table_cmgt_checkin = $wpdb->prefix. 'cmgt_checkin';
		$result = $wpdb->get_results("SELECT DISTINCT room.id, room.* FROM $table_room as room,$table_cmgt_checkin as chkroom where room.id = chkroom.room_id AND chkroom.member_id = $user_id");
		return $result;
	}
	//--------- GET SINGLE ROOM -------//
	public function MJ_cmgt_get_single_room($id)
	{
		global $wpdb;
		$table_room = $wpdb->prefix. 'cmgt_room';
		$result = $wpdb->get_row("SELECT * FROM $table_room where id=".$id);
		return $result;
	}
	//--------- DELETE ROOM -------//
	public function MJ_cmgt_delete_room($id)
	{
		global $wpdb;
		$table_room = $wpdb->prefix. 'cmgt_room';
		$result = $wpdb->query("DELETE FROM $table_room where id= ".$id);
		return $result;
	}
	//--------- GET ROOM RESERVATION -------//
	public function MJ_cmgt_get_room_reservation($id)
	{
		global $wpdb;
		$table_checkin = $wpdb->prefix. 'cmgt_checkin';
		$result = $wpdb->get_row("SELECT * FROM $table_checkin where status='checkin' and room_id=".$id);	
		return $result;
	}
	//--------- ADD ROOM CHECKIN -------//
	public function MJ_cmgt_add_room_checkin($data)
	{
		global $wpdb;
		$table_checkin = $wpdb->prefix. 'cmgt_checkin';
		$checkindata['room_id']=$data['room_id'];
		$checkindata['member_id']=$data['member_id'];
		$checkindata['family_members']=$data['family_member'];
		$checkindata['checkin_date']=MJ_cmgt_get_format_for_db($data['checkin_date']);
		$checkindata['checkout_date']=MJ_cmgt_get_format_for_db($data['checkout_date']);
		$checkindata['status']='Checkin';
		$checkindata['created_by']=get_current_user_id();
		$checkindata['created_date']=date("Y-m-d");
		$result=$wpdb->insert( $table_checkin, $checkindata );
		$membersdata=get_userdata($data['member_id']);
		$to=$membersdata->user_email;
		$user_name=$membersdata->display_name;
		$roomname=MJ_get_room_name($data['room_id']);
		$subject =get_option('WPChurch_Check_In_church_venue_subject');
		$page_link=home_url().'/?church-dashboard=user&&page=check-in&tab=roomlist';
		$churchname=get_option('cmgt_system_name');
		$message_content=get_option('WPChurch_Check_In_church_venue_Template');
		$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_ROOM_TITLE]');
		$subject_replace=array($churchname,$roomname);
		$subject=str_replace($subject_search,$subject_replace,$subject);
		$check_in_date=date(MJ_cmgt_date_formate(),strtotime($data['checkin_date']));
	    $check_out_date=date(MJ_cmgt_date_formate(),strtotime($data['checkout_date']));
		$search=array('[CMGT_MEMBERNAME]','[CMGT_ROOM_TITLE]','[CMGT_CHEKED_INDATE]','[CMGT_CHEKED_OUTDATE]','[CMGT_NO_OF_FAMILY_MEMBER]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
		$replace = array($user_name,$roomname,$check_in_date,$check_out_date,$data['family_member'],$page_link,$churchname);
		$message_content = str_replace($search, $replace, $message_content);
		MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
		return $result;
	}
	//--------- ROOM CHECKOUT -------//
	public function MJ_cmgt_room_checkout($id)
	{
		global $wpdb;
		$table_checkin = $wpdb->prefix. 'cmgt_checkin';
		$checkindata['status']='checkout';
		$checkindata['checkout_date']=date("m/d/Y");
		$checkinid['id']=$id;
		$result=$wpdb->update($table_checkin,$checkindata,$checkinid);
		global $wpdb;
		$table_checkin = $wpdb->prefix. 'cmgt_checkin';
		$result = $wpdb->get_row("SELECT * FROM $table_checkin where id=".$id);
		$room_id=$result->room_id;
		$family_member=$result->family_members;
		$member_id=$result->member_id;
		$roomname=MJ_get_room_name($room_id);
		$membersdata=get_userdata($member_id);
		$to=$membersdata->user_email;
		$user_name=$membersdata->display_name;
		$subject =get_option('WPChurch_Ckeck_Out_From_Church_Venue_Subject');
		$page_link=home_url().'/?church-dashboard=user&&page=check-in&tab=roomlist';
		$churchname=get_option('cmgt_system_name');
		
		$check_in_date=date(MJ_cmgt_date_formate(),strtotime($result->checkin_date));
	    $check_out_date=date(MJ_cmgt_date_formate(),strtotime($checkindata['checkout_date']));
		
		$message_content=get_option('WPChurch_Ckeck_Out_From_Church_Venue_Template');
		$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_ROOM_TITLE]');
		$subject_replace=array($churchname,$roomname);
		$subject=str_replace($subject_search,$subject_replace,$subject);
		$search=array('[CMGT_MEMBERNAME]','[CMGT_ROOM_TITLE]','[CMGT_CHEKED_INDATE]','[CMGT_CHEKED_OUTDATE]','[CMGT_NO_OF_FAMILY_MEMBER]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
		$replace = array($user_name,$roomname,$check_in_date,$check_out_date,$family_member,$page_link,$churchname);
		$message_content = str_replace($search, $replace, $message_content);
		MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
		return $result;
	}
}
?>