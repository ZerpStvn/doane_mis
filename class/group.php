<?php 
 //GROUP CLASS START  
class Cmgtgroup
{	
	//---- ADD GROUP DATA ---------//
	public function MJ_cmgt_add_group($data,$member_image_url)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'cmgt_group';
		$groupdata['group_name']=stripslashes($data['group_name']);
		$groupdata['cmgt_groupimage']=$member_image_url;
		$groupdata['created_date']=date("Y-m-d");
		$groupdata['created_by']=get_current_user_id();
		     $image_url=$groupdata['cmgt_groupimage'];
			  $ext=MJ_cmgt_check_valid_extension($image_url);
			    if(!$ext == 0)
		//---- EDIT GROUP DATA ---------//
	{
		if($data['action']=='edit')
		{
			$groupid['id']=$data['group_id'];
			$result=$wpdb->update( $table_group, $groupdata ,$groupid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_group, $groupdata );
			return $result;
		}
	}
	}
	//---- GET ALL GROUP DATA ---------//
	public function MJ_cmgt_get_all_groups()
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'cmgt_group';
		$result = $wpdb->get_results("SELECT * FROM $table_group");
		return $result;
	}
	//---- GET SINGLE GROUP DATA ---------//
	public function MJ_cmgt_get_single_group($id)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'cmgt_group';
		$result = $wpdb->get_row("SELECT * FROM $table_group where id=".$id);
		return $result;
	}
	//---- DELETE GROUP  ---------//
	public function MJ_cmgt_delete_group($id)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'cmgt_group';
		$result = $wpdb->query("DELETE FROM $table_group where id= ".$id);
		return $result;
	}
	//---- COUNT GROUP MEMBERS ---------//
	function MJ_cmgt_count_group_members($id)
	{
		global $wpdb;
		$table_gmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_var("SELECT count(member_id) FROM $table_gmgt_groupmember where type='group' and group_id=".$id);
		return $result;
	}
	//---- GET MINISTRY MEMBERS ---------//
	function MJ_cmgt_get_ministry_members($id){
		global $wpdb;
		$table_gmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_groupmember where type='ministry' and group_id=".$id);
		return $result;
	}
	//---- UPDATE GROUPIMAGE ---------//
	function MJ_cmgt_update_groupimage($id,$imagepath)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
		$image['gmgt_groupimage']=$imagepath;
		$groupid['id']=$id;
		return $result=$wpdb->update( $table_group, $image, $groupid);
	}


	//---- ADD GROUP MEMBERS ---------//
	function MJ_cmgt_add_group_members($members,$group_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		if(!empty($members))
		{
			foreach($members as $id)
			{
				if($this->MJ_cmgt_member_exist_ingroup($id,$group_id))
					$this->MJ_cmgt_delete_member_from_group($id,$group_id);		
				$group_data['group_id']=$group_id;
				$group_data['member_id']=$id;
				$group_data['type']='group';
				$group_data['created_date']=date("Y-m-d");
				$group_data['created_by']=get_current_user_id();
				$result=$wpdb->insert( $table_cmgt_groupmember, $group_data );
			}
	    }
		return $result;
	}
	//---- DELETE GROUP MEMBER FROM GROUP ---------//
	public function MJ_cmgt_delete_member_from_group($member_id,$group_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_cmgt_groupmember WHERE type='group' and group_id=$group_id and member_id= $member_id",$member_id));
	}
	//---- MEMBER EXITS ---------//
	public function MJ_cmgt_member_exist_ingroup($member_id,$group_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_results("SELECT * FROM $table_cmgt_groupmember where type='group' and group_id=$group_id and member_id=".$member_id);
		if(!empty($result))
			return true;
		return false;
	}
	//---- GET GROUP MEMBERS ---------//
	function MJ_cmgt_get_group_members($id)
	{
		global $wpdb;
		$table_gmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';		
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_groupmember where group_id=".$id." AND type='group'");
		return $result;
	}

	function MJ_cmgt_get_group_members_id($id,$type)
	{
		global $wpdb;
		$table_gmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';		
		$result = $wpdb->get_results("SELECT member_id FROM $table_gmgt_groupmember where group_id=".$id."");
		return $result;
	} 

		//---- GET ALL GROUP DATA ---------//
	public function MJ_cmgt_get_all_groups_dashboard()
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'cmgt_group';
		$result = $wpdb->get_results("SELECT * FROM $table_group ORDER BY id DESC LIMIT 3");
		return $result;
	}
}
?>