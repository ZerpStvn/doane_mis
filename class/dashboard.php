<?php 
 //DASHBOEARD CLASS START  
class Cmgtdashboard
{	
	//-------- COUNT GROUP --------//
	public function MJ_cmgt_count_group()
	{
		global $wpdb;
		$table_cmgt_groups = $wpdb->prefix . "cmgt_group";
		$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_groups");
		return $results;
	}
	//-------- COUNT SERVICES --------//
	public function MJ_cmgt_count_services()
	{
		global $wpdb;
		$table_cmgt_services = $wpdb->prefix . "cmgt_service";
		$results=$wpdb->get_var("SELECT count(*) FROM $table_cmgt_services");
		return $results;
	}
	//-------- GET GROUPLIST --------//
	public function MJ_cmgt_get_grouplist()
	{
		global $wpdb;
		$table_cmgt_groups = $wpdb->prefix . "cmgt_group";
		$results=$wpdb->get_results("SELECT * FROM $table_cmgt_groups ORDER BY id DESC LIMIT 3");
		return $results;
	}
	//--------- GET ALL MINISTRY DASHBOARD ------//
	public function MJ_cmgt_get_all_ministry_dashboard()
	{
		global $wpdb;
		$table_ministry = $wpdb->prefix. 'cmgt_ministry';
		$result = $wpdb->get_results("SELECT * FROM $table_ministry ORDER BY id DESC LIMIT 3");
		return $result;
		
	}
		//-------- GET ACTIVITY --------//
	public function MJ_cmgt_get_activity()
	{
		global $wpdb;
		$table_cmgt_activity = $wpdb->prefix . "cmgt_activity";
		$results=$wpdb->get_results("SELECT * FROM $table_cmgt_activity ORDER BY activity_id DESC LIMIT 3");
		return $results;
	}
	//count group members
	function MJ_cmgt_count_group_members($id)
	{		
		global $wpdb;
		$table_gmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_var("SELECT count(member_id) FROM $table_gmgt_groupmember where group_id=".$id);	
		return $result;
	}
	//count ministry members
	function MJ_cmgt_count_ministry_members($id)
	{
		global $wpdb;
		$table_gmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_var("SELECT count(member_id) FROM $table_gmgt_groupmember where type='ministry' and group_id=".$id);
		return $result;
	}
	//-------- GET MY GROUPLIST -------//
	public function MJ_cmgt_get_my_grouplist($id)
	{
		global $wpdb;
		$table_cmgt_groupmembers = $wpdb->prefix . "cmgt_groupmember";
		$table_cmgt_groups = $wpdb->prefix . "cmgt_group";
		$results=$wpdb->get_results("SELECT $table_cmgt_groups.*
		FROM $table_cmgt_groups INNER JOIN $table_cmgt_groupmembers
		where $table_cmgt_groups.id=$table_cmgt_groupmembers.group_id and $table_cmgt_groupmembers.type='group' and $table_cmgt_groupmembers.member_id=$id ORDER BY id DESC LIMIT 3");
		return $results;
	}
	//-------- GET MY MINISTRY LIST --------//
	public function MJ_cmgt_get_my_ministrylist($id)
	{
		global $wpdb;
		$table_cmgt_groupmembers = $wpdb->prefix . "cmgt_groupmember";
		$table_cmgt_ministry = $wpdb->prefix . "cmgt_ministry";
		$results=$wpdb->get_results("SELECT $table_cmgt_ministry.*
		FROM $table_cmgt_ministry INNER JOIN $table_cmgt_groupmembers
		where $table_cmgt_ministry.id=$table_cmgt_groupmembers.group_id and $table_cmgt_groupmembers.type='ministry' and $table_cmgt_groupmembers.member_id=$id ORDER BY id DESC LIMIT 3");
		return $results;
	}
}
?>