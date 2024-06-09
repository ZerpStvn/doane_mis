<?php 
//ATTENDENCE CLASS START 
class Cmgtattendence
{	
	//-------  ADD ATTENDENCE DATA --------//
	public function MJ_cmgt_add_attendence($curr_date,$class_id,$user_id,$attend_by,$status)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "gmgt_attendence";
		$check_insrt_or_update =$this->MJ_cmgt_check_has_attendace($user_id,$class_id,$curr_date);
		if(empty($check_insrt_or_update))
		{
			$savedata =$wpdb->insert($table_name,array('attendence_date' =>$curr_date,
			'attendence_by' =>$attend_by,
			'class_id' =>$class_id, 'user_id' =>$user_id,'status' =>$status,'role_name'=>'member'));
		}
		else 
		{
			$savedata =$wpdb->update($table_name,
			array('attendence_by' =>$attend_by,'status' =>$status),
			array('attendence_date' =>$curr_date,'class_id' =>$class_id,'user_id' =>$user_id));
		}
	}
	//-------  CHECK HAS ATTENDENCE --------//
	public function MJ_cmgt_check_has_attendace($user_id,$class_id,$attendace_date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "gmgt_attendence";
		return $results=$wpdb->get_row("SELECT * FROM $table_name WHERE attendence_date='$attendace_date' and class_id=$class_id and user_id =".$user_id);
	}
	//-------  CHECH ATTENDENCE  --------//
	public function MJ_cmgt_heck_attendence($userid,$activity_id,$date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_attendence";
		$curr_date=$date;
		$result=$wpdb->get_row("SELECT * FROM $table_name WHERE attendence_date='$curr_date' and activity_id='$activity_id' and role_name='member' and user_id=".$userid);
		return $result;
	}
	//-------  CHECK MINISTY ATTENDENCE DATA --------//
	public function MJ_cmgt_check_ministry_attendence($userid,$activity_id,$date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_attendence";
		$curr_date=$date;
		$result=$wpdb->get_row("SELECT * FROM $table_name WHERE attendence_date='$curr_date' and activity_id='$activity_id'   and role_name='ministry' and user_id=".$userid);
		return $result;
	}
	//-------  IS TAKE ATTENDENCE DATA --------//
	public function MJ_cmgt_is_take_attendance($member_id,$activity_id,$date,$type)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_attendence";
		$result=$wpdb->get_row("SELECT * FROM $table_name WHERE attendence_date='$date' and activity_id = $activity_id AND role_name='$type' AND user_id=".$member_id);
	
		if(isset($result)) {
			return true;
		}
		else{
			return false;
		}
			
	}
	//-------  SAVE ATTENDENCE DATA --------//
	public function MJ_cmgt_save_attendence($curr_date,$activity_id,$attendence,$attend_by,$status,$role)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_attendence";	
		 foreach($attendence as $member_id)
		 {
			
			if($member_id != 'on')
			{
				if($this->MJ_cmgt_is_take_attendance($member_id,$activity_id,$curr_date,$role))
				{
					$savedata=$result=$wpdb->update($table_name,array('attendence_by' =>$attend_by,'status' =>$status),array('attendence_date' =>$curr_date,'activity_id' =>$activity_id,'user_id' =>$member_id));
				}
				else 
				{
					$savedata=$wpdb->insert($table_name,array('attendence_date' =>$curr_date,'attendence_by' =>$attend_by,'activity_id' =>$activity_id, 'user_id' =>$member_id,'status' =>$status,'role_name'=>$role));
				}
			}
		 }
		 return $savedata;
	}
	//-------  SHOW TODAY ATTENDENCE DATA --------//
	public function MJ_cmgt_show_today_attendence($activity_id,$role)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_attendence";
		$curr_date=date("Y-m-d");
		return $results=$wpdb->get_results("SELECT * FROM $table_name WHERE attendence_date='$curr_date' and activity_id=$activity_id and role_name='$role'",ARRAY_A);
	}
	//-------  UPDATE ATTENDENCE DATA --------//
	public function MJ_cmgt_update_attendence($membersdata,$curr_date,$class_id,$attendence,$attend_by,$status,$table_name)
	{
		global $wpdb;
		if($status=='Present')
			$new_status='Absent';
		else
			$new_status='Present';
		 	foreach($membersdata as $stud)
			{
				if(in_array($stud->ID ,$attendence))
				{
					 $result=$wpdb->update($table_name,array('attendence_by' =>$attend_by,'status' =>$status),array('attendence_date' =>$curr_date,'class_id' =>$class_id,'user_id' =>$stud->ID));
				}
			}
			return $result;
	}

	//--------- CHECK MEMBER ATTENDENCE DATA ---------//	
	public function MJ_cmgt_get_All_member_attendence($member_id)
	{
		// var_dump($member_id);
		// die;
		global $wpdb;
		$table_name = $wpdb->prefix . "cmgt_attendence";
		$result = $wpdb->get_results("SELECT * FROM $table_name where user_id=".$member_id);
		return $result;
	}
}
?>