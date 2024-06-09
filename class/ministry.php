<?php 
 //MIISTRY CLASS START  
class Cmgtministry
{	
	//----------- ADD MINISTRY DATA ---------------//
	public function MJ_cmgt_add_ministry($data,$member_image_url)
	{
		global $wpdb;
		$table_ministry = $wpdb->prefix. 'cmgt_ministry';
		$ministrydata['ministry_name']=stripslashes($data['ministry_name']);
		//$ministrydata['ministry_name']=MJ_cmgt_strip_tags_and_stripslashes($data['ministry_name']);
		$ministrydata['ministry_image']=$member_image_url;
		$ministrydata['created_date']=date("Y-m-d");
		$ministrydata['created_by']=get_current_user_id();
		//--------- EDIT MINISTRY ------//
		if($data['action']=='edit')
		{
			$ministryid['id']=$data['ministry_id'];
			$result=$wpdb->update( $table_ministry, $ministrydata ,$ministryid);
			return $result;
		}
		//--------- ADD MINISTRY ------//
		else
		{
			$result=$wpdb->insert( $table_ministry, $ministrydata );
			return $result;
		}
	}
	//--------- GET ALL MINISTRY ------//
	public function MJ_cmgt_get_all_ministry()
	{
		global $wpdb;
		$table_ministry = $wpdb->prefix. 'cmgt_ministry';
		$result = $wpdb->get_results("SELECT * FROM $table_ministry");
		return $result;
	}
	//--------- GET SINGLE MINISTRY ------//
	public function MJ_cmgt_get_single_ministry($id)
	{
		global $wpdb;
		$table_ministry = $wpdb->prefix. 'cmgt_ministry';
		$result = $wpdb->get_row("SELECT * FROM $table_ministry where id=".$id);
		return $result;
	}
	//--------- DELETE MINISTRY ------//
	public function MJ_cmgt_delete_ministry($id)
	{
		global $wpdb;
		$table_ministry = $wpdb->prefix. 'cmgt_ministry';
		$result = $wpdb->query("DELETE FROM $table_ministry where id= ".$id);
		return $result;
	}
	//--------- COUNT MIISTRY MEMBERS ------//
	function MJ_cmgt_count_ministry_members($id)
	{
		global $wpdb;
		$table_gmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_var("SELECT count(member_id) FROM $table_gmgt_groupmember where type='ministry' and group_id=".$id);
		return $result;
	}	
	//---- UPDATE MIISTRY IMAGE ---------//
	function MJ_cmgt_update_ministryimage($id,$imagepath)
	{
		global $wpdb;
		$table_ministry = $wpdb->prefix. 'cmgt_ministry';
		$image['gmgt_groupimage']=$imagepath;
		$ministryid['id']=$id;
		return $result=$wpdb->update( $table_ministry, $image, $ministryid);
	}
	//--------- ADD MIISTRY MEMBERS ---------//
	function MJ_cmgt_add_ministary_members($members,$group_id)
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
				$group_data['type']='ministry';
				$group_data['created_date']=date("Y-m-d");
				$group_data['created_by']=get_current_user_id();
				$result=$wpdb->insert( $table_cmgt_groupmember, $group_data );
			}
	    }
		return $result;
	}
	//--------- DELETE MEMBERS FROM GROUP ---------//
	public function MJ_cmgt_delete_member_from_group($member_id,$group_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_cmgt_groupmember WHERE type='ministry' and group_id=$group_id and member_id= %d",$member_id));
	}
	//---------  MEMBERS EXIT IN GROUP ---------//
	public function MJ_cmgt_member_exist_ingroup($member_id,$group_id)
	{
		global $wpdb;
		$table_cmgt_groupmember = $wpdb->prefix. 'cmgt_groupmember';
		$result = $wpdb->get_results("SELECT * FROM $table_cmgt_groupmember where type='ministry' and group_id=$group_id and member_id=".$member_id);
		if(!empty($result))
			return true;
		return false;
	}
}
?>