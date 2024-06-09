<?php 
 //VENUE CLASS START  
class Cmgtvenue
{	
	//------ ADD VENUE DATA ---------//
	public function MJ_cmgt_add_venue($data)
	{
		global $wpdb;
		$table_venue = $wpdb->prefix. 'cmgt_venue';
		$venuedata['venue_title']=stripslashes($data['venue_title']);
		$venuedata['capacity']=MJ_cmgt_strip_tags_and_stripslashes($data['capacity']);
		$venuedata['request_before_days']=$data['request_days'];
		if(isset($data['multiple_sreservation']))
			$venuedata['multiple_booking']=$data['multiple_sreservation'];
		else
			$venuedata['multiple_booking']='no';
		if(isset($data['equipment_id']))
		{
			$all_equipments='';
			foreach($data['equipment_id'] as $equipment)
			{
				$all_equipments.=$equipment.',';
			}
			$venuedata['equipments']=$all_equipments;
		}
		//------ EDIT VENUE DATA ---------//
		if($data['action']=='edit')
		{
			$venueid['id']=$data['venue_id'];
			$result=$wpdb->update($table_venue, $venuedata ,$venueid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert($table_venue, $venuedata);
			return $result;
		}
	}
	//------ GET ALL VENUE ---------//
	public function MJ_cmgt_get_all_venue()
	{
		global $wpdb;
		$table_venue = $wpdb->prefix. 'cmgt_venue';
		$result = $wpdb->get_results("SELECT * FROM $table_venue");
		return $result;
	}
	//------ GET ALL VENUE ---------//
	
	//------ GET SINGLE VENUE ---------//
	public function  MJ_cmgt_get_single_venue($id)
	{
		global $wpdb;
		$table_venue = $wpdb->prefix. 'cmgt_venue';
		$result = $wpdb->get_row("SELECT * FROM $table_venue where id=".$id);
		return $result;
	}
	//------ DALETE VENUE -------//
	public function MJ_cmgt_delete_venue($id)
	{
		global $wpdb;
		$table_venue = $wpdb->prefix. 'cmgt_venue';
		$result = $wpdb->query("DELETE FROM $table_venue where id= ".$id);
		return $result;
	}
}
?>