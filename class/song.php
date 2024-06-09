<?php 
 //SONG CLASS START  
class Cmgtsong
{	
	//------ ADD SONG DATA ------//
	public function MJ_cmgt_add_song($data,$member_image_url)
	{
		global $wpdb;
		$table_song = $wpdb->prefix. 'cmgt_songs';
		$songdata['song_cat_id']=$data['song_cat_id'];
		$songdata['song_name']=stripslashes($data['song_name']);
		$songdata['description']=MJ_cmgt_strip_tags_and_stripslashes($data['description']);
		$songdata['song']=$member_image_url;
		$songdata['created_date']=date("Y-m-d");
		$songdata['created_by']=get_current_user_id();
		//------ EDIT SONG DATA ------//
		if($data['action']=='edit')
		{
			$songid['id']=$data['song_id'];
			$result=$wpdb->update( $table_song, $songdata ,$songid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_song, $songdata);
			$get_members = array('role' => 'member');
			$membersdata=get_users($get_members);
		    if(!empty($membersdata))
		    {
		 	foreach ($membersdata as $retrieved_data){
				$getpost= get_post($data['song_cat_id']);
                $song_category= $getpost->post_title;
			$curentuser_id=get_current_user_id();
			$currentuserdata=get_userdata($curentuser_id);
			$curent_user_name=$currentuserdata->display_name;
			$to=$retrieved_data->user_email;
			$user_name=$retrieved_data->display_name;
		    $subject =get_option('WPChurch_Add_Song_Subject');
			$page_link=home_url().'/?church-dashboard=user&&page=songs&tab=songlist';
			$churchname=get_option('cmgt_system_name');
			$message_content=get_option('WPChurch_Add_Song_Template');
			$subject_search=array('[CMGT_CHURCH_NAME]','[CMGT_SONGADDEDBY]');
			$subject_replace=array($churchname,$curent_user_name);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			$search=array('[CMGT_MEMBERNAME]','[CMGT_SONGADDEDBY]','[CMGT_CHURCH_NAME]','[CMGT_SONG_NAME]','[CMGT_SONG_CATEGORY]','[CMGT_SONG_DESCRIPTION]','[CMGT_PAGE_LINK]','[CMGT_CHURCH_NAME]');
			$replace = array($user_name,$curent_user_name,$churchname,$data['song_name'],$song_category,$data['description'],$page_link,$churchname);
			$message_content = str_replace($search, $replace, $message_content);
			MJ_cmgt_SendEmailNotification($to,$subject,$message_content);
			}
			}
			return $result;
		}
	}
	//---------- GET ALL SONG -------//
	public function MJ_cmgt_get_all_song()
	{
		global $wpdb;
		$table_song = $wpdb->prefix. 'cmgt_songs';
		$result = $wpdb->get_results("SELECT * FROM $table_song");
		return $result;
	}
	public function MJ_cmgt_get_all_song_created_by()
	{
		$curr_user_id=get_current_user_id();
		global $wpdb;
		$table_song = $wpdb->prefix. 'cmgt_songs';
		$result = $wpdb->get_results("SELECT * FROM $table_song where created_by=".$curr_user_id);
		return $result;
	}
	//------ GET SINGLE SONG  ------//
	public function MJ_cmgt_get_single_song($id)
	{
		global $wpdb;
		$table_song = $wpdb->prefix. 'cmgt_songs';
		$result = $wpdb->get_row("SELECT * FROM $table_song where id=".$id);
		return $result;
	}
	//------ DELETE SONG  ------//
	public function MJ_cmgt_delete_song($id)
	{
		global $wpdb;
		$table_song = $wpdb->prefix. 'cmgt_songs';
		$result = $wpdb->query("DELETE FROM $table_song where id= ".$id);
		return $result;
	}
}
?>