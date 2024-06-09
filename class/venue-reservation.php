<?php  
//RESERVATION CLASS START  
class Cmgtreservation
{	
	//----- ADD RESERVATION DATA -----------//
	public function MJ_cmgt_add_reservation($data)
	{
		global $wpdb;
		$table_reservation = $wpdb->prefix. 'cmgt_venue_reservation';
		$reservationdata['usage_title']=stripslashes($data['usage_title']);
		$reservationdata['vanue_id']=$data['vanue_id'];
		$reservationdata['reserve_date']=MJ_cmgt_get_format_for_db($data['reservation_date']);
		$reservationdata['reservation_end_date']=MJ_cmgt_get_format_for_db($data['reservation_end_date']);
		$reservationdata['reservation_start_time']=$data['start_time'];
		$reservationdata['reservation_end_time']=$data['end_time'];
		$reservationdata['participant']=MJ_cmgt_strip_tags_and_stripslashes($data['participant']);
		$reservationdata['applicant_id']=$data['applicant_id'];
		$reservationdata['participant_max_limit']=MJ_cmgt_strip_tags_and_stripslashes($data['capacity']);
		$reservationdata['description']=MJ_cmgt_strip_tags_and_stripslashes($data['description']);
		$reservationdata['created_by']=get_current_user_id();
		$reservationdata['created_date']=date("Y-m-d");
		//----- EDIT RESERVATION DATA -----------//
		if($data['action']=='edit')
		{
			$reservationid['id']=$data['reservation_id'];
			$result=$wpdb->update( $table_reservation, $reservationdata ,$reservationid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_reservation, $reservationdata );
			return $result;
		}
	}
	//----- GET ALL RESERVATION -----------//
	public function MJ_cmgt_get_all_reservation()
	{
		global $wpdb;
		$table_reservation = $wpdb->prefix. 'cmgt_venue_reservation';
		$result = $wpdb->get_results("SELECT * FROM $table_reservation");
		return $result;
	}
	//----- GET ALL RESERVATION DASHBOARD -----------//
	public function MJ_cmgt_get_all_reservation_dashboard()
	{
		global $wpdb;
		$table_reservation = $wpdb->prefix. 'cmgt_venue_reservation';
		$result = $wpdb->get_results("SELECT * FROM $table_reservation ORDER BY id DESC LIMIT 3");
		return $result;
	}
	//----- GET MEMBER RESERVATION DASHBOARD -----------//
	public function MJ_cmgt_get_member_reservation_dashboard($id)
	{
		$applicant_id = (int)$id;
		global $wpdb;
		$table_reservation = $wpdb->prefix. 'cmgt_venue_reservation';
		$result = $wpdb->get_results("SELECT * FROM $table_reservation WHERE applicant_id = $applicant_id ORDER BY id DESC LIMIT 3");
		return $result;
	}
	//------- GET MEMBER RESERVATION -----//
	public function MJ_cmgt_get_members_reservation($user_id)
	{
		global $wpdb;
		$table_reservation = $wpdb->prefix. 'cmgt_venue_reservation';
		$result = $wpdb->get_results("SELECT * FROM $table_reservation WHERE applicant_id = $user_id");
		return $result;
	}
	//------- GET SINGLE RESERVATION -----//
	public function MJ_cmgt_get_single_reservation($id)
	{
		global $wpdb;
		$table_reservation = $wpdb->prefix. 'cmgt_venue_reservation';
		$result = $wpdb->get_row("SELECT * FROM $table_reservation where id=".$id);
		return $result;
	}
	//------- DELETE RESERVATION -----//
	public function MJ_cmgt_delete_reservation($id)
	{
		global $wpdb;
		$table_reservation = $wpdb->prefix. 'cmgt_venue_reservation';
		$result = $wpdb->query("DELETE FROM $table_reservation where id= ".$id);
		return $result;
	}
	// count reservation data rolr wise
	function MJ_cmgt_count_reservation_data($id)
	{
		global $wpdb;
		$tbl_name = $wpdb->prefix .'cmgt_venue_reservation';
		$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name where applicant_id = $id");
		return $inbox;
	}
}
?>