<?php 
//CHURCH MANAGMENT CLASS START  
class Church_management
{
	public $member;
	public $accountant;
	public $role;
	public $notice;
	public $family_member;
	function __construct($user_id = NULL)
	{
		if($user_id)
		{
			$this->role=$this->MJ_cmgt_get_current_user_role();
		}
	}
	private function MJ_cmgt_get_current_user_role () 
	{
		global $current_user;
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	}
	//----- MEMBER ATTENDANCE REPORTS ------//
	public function MJ_cmgt_member_attendance_report($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix."cmgt_attendence";
		$table_activity = $wpdb->prefix."cmgt_activity";
		$q="SELECT activity.activity_title, attend.user_id, attend.activity_id, count(*) as attendance, 
		SUM(case when attend.status ='Present' then 1 else 0 end) as Present, 
		SUM(case when attend.status ='Absent' then 1 else 0 end) as Absent 
		From $table_name AS attend, $table_activity AS activity  where attend.user_id=".$user_id." and activity.activity_id=attend.activity_id GROUP BY activity_id ";
		$result=$wpdb->get_results($q);
		$chart_array[] = array(__('Activity','church_mgt'),__('Present','church_mgt'),__('Absent','church_mgt'));
		foreach($result as $retrive)
		{
			$chart_array[] = array($retrive->activity_title,(int)$retrive->Present,(int)$retrive->Absent);
		}
		return $chart_array;
	}
}
?>