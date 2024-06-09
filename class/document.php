<?php
//DOCUMENTS CLASS START  
class cmgt_document
{	
	//------ ADD DOCUMENTS DATA ---------//
	public function MJ_cmgt_add_document($data)
	{
		global $wpdb;
		$table_document = $wpdb->prefix. 'cmgt_document';
		$documentdata['document_name']=MJ_cmgt_strip_tags_and_stripslashes($data['document_name']);
		$documentdata['description']=MJ_cmgt_strip_tags_and_stripslashes($data['document_description']);
		$documentdata['created_date']=date("Y-m-d");
		$documentdata['ducument_create_by']=get_current_user_id();
		//------ EDIT DOCUMENTS DATA ---------//
		 if($data['action']=='edit')
		{
			if(isset($_FILES['document']) && !empty($_FILES['document']) && $_FILES['document']['size'] !=0)
			{
				if($_FILES['document']['size'] > 0)
					$document_name=MJ_cmgt_load_documets($_FILES['document'],'document','document');
			}
			else
			{
				if(isset($_REQUEST['edit_document']))
					$document_name=$_REQUEST['edit_document'];
			}
		
		    $table_document = $wpdb->prefix. 'cmgt_document';
			$documentdata['document']=$document_name;
			$where['document_id']=$data['document_id'];
			$result=$wpdb->update( $table_document, $documentdata ,$where);
			return $result;
		}
		else
		{ 
			$result=$wpdb->insert( $table_document, $documentdata );
			$document_id=$wpdb->insert_id;
			if(isset($_FILES['document']) && !empty($_FILES['document']) && $_FILES['document']['size'] !=0)
			{
				if($_FILES['document']['size'] > 0)
				$document['document']=MJ_cmgt_load_documets($_FILES['document'],'document','document');
				$where['document_id']= $document_id;
				$wpdb->update( $table_document, $document ,$where);
			
			}
			return $result;
	    }
	}
	//------ GET ALL DOCUMENTS DATA ---------//
	public function MJ_cmgt_get_all_document()
	{
		global $wpdb;
		$table_document = $wpdb->prefix. 'cmgt_document';
		$result = $wpdb->get_results("SELECT * FROM $table_document");
		return $result;
	}
	//------ GET SINGLE DOCUMENTS DATA ---------//
	public function MJ_cmgt_get_single_document($document_id)
	{
		global $wpdb;
		$table_document = $wpdb->prefix. 'cmgt_document';
		$result = $wpdb->get_row("SELECT * FROM $table_document where document_id = ".$document_id);
		return $result;
	}
	//------ DELETE DOCUMENTS DATA ---------//
	public function MJ_cmgt_delete_document($document_id)
	{
		global $wpdb;
		$table_document = $wpdb->prefix. 'cmgt_document';
		$result = $wpdb->query("DELETE FROM $table_document where document_id = ".$document_id);
		return $result;
	}
	
}
?>