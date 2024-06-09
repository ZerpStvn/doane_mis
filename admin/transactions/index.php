<?php 
MJ_cmgt_header();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'transactionlist';
$obj_transaction=new Cmgttransaction;
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="invoice_data">
			</div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner"><!-- PAGE INNER DIV START-->
	<?php 
	if(isset($_POST['save_transaction']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_transaction_nonce' ) )
		{
		//---------- EDIT TRANSACTION ---------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_transaction->MJ_cmgt_add_transaction($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=cmgt-transactions&tab=transactionlist&message=2');
			}
		}
		else
		{
			//---------- ADD TRANSACTION ---------//
				$result=$obj_transaction->MJ_cmgt_add_transaction($_POST);
				
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=cmgt-transactions&tab=transactionlist&message=1');
				}
			}
	}
	}
	//---------- DELETE TRANSACTION ---------//
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_transaction->MJ_cmgt_get_my_aadonation($_REQUEST['transaction_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=cmgt-transactions&tab=transactionlist&message=3');
		}
	}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Record inserted successfully','church_mgt');
			?></p></div>
			<?php 
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Record updated successfully.",'church_mgt');
					?></p>
					</div>
				<?php 
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Record deleted successfully','church_mgt');
		?></div></p><?php
				
		}
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Check-out successfully','church_mgt');
		?></div></p><?php
				
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->  
		<div class="row"><!-- ROW DIV START--> 
			<div class="col-md-12"><!-- COL 12 DIV START-->  
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->  
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper"><!-- NAV TAB WRAPPER MENU START--> 
							<a href="?page=cmgt-transactions&tab=transactionlist" class="nav-tab <?php echo $active_tab == 'transactionlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Transaction List', 'church_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=cmgt-transactions&tab=addtransaction&action=edit&transaction_id=<?php echo $_REQUEST['transaction_id'];?>" class="nav-tab <?php echo $active_tab == 'addtransaction' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Transaction', 'church_mgt'); ?></a>  
							<?php 
							}
							
							else
							{?>
								<a href="?page=cmgt-transactions&tab=addtransaction" class="nav-tab <?php echo $active_tab == 'addtransaction' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Transaction', 'church_mgt'); ?></a>
							<?php  } ?>
						   
						</h2>
						 <?php 
						 if($active_tab == 'transactionlist')
						 { ?>	
							<script type="text/javascript">
								$(document).ready(function()
								{
									jQuery('#transaction_list').DataTable({
										"responsive":true,
										language:<?php echo MJ_cmgt_datatable_multi_language();?>,
										"order": [[ 0, "asc" ]],
										"aoColumns":[
											          {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": false}]
										});
								} );
							</script>
							<form name="wcwm_report" action="" method="post">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="transaction_list" class="display" cellspacing="0" width="100%">
											<!-- <thead>
												<tr>
													<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Transaction Date', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Method', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'church_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th><?php  _e( 'Member Name', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Transaction Date', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Amount', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Method', 'church_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'church_mgt' ) ;?></th>
												</tr>
											</tfoot> -->
								 
											<tbody>
											 <?php 
												$transactiondata=$obj_transaction->MJ_cmgt_get_all_transaction();
											 if(!empty($transactiondata))
											 {
												foreach ($transactiondata as $retrieved_data)
												{

											 ?>
												<tr>
													<td class="name"><a href="?page=cmgt-transactions&tab=addtransaction&action=edit&transaction_id=<?php echo $retrieved_data->id;?>"><?php $user=get_userdata($retrieved_data->member_id);
													echo $user->display_name;
													?></a></td>
													<td class="stat_date"><?php echo $retrieved_data->transaction_date;?> 
													<td class="stat_date"><?php echo $retrieved_data->transaction_id; die;?> 
													
													<td class="total_amount"><?php echo $retrieved_data->amount;?> 
													<td class="method"><?php echo MJ_cmgt_get_payment_method($retrieved_data->pay_method);?> 
												   
													<td class="action"> 
													<a href="#" class="btn btn-success show-invoice-popup" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="transaction"> <?php _e('View Invoice', 'church_mgt' ) ;?></a>
													<a href="?page=cmgt-transactions&tab=addtransaction&action=edit&transaction_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'church_mgt' ) ;?></a>
													<a href="?page=cmgt-transactions&tab=transactionlist&action=delete&transaction_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');">
													<?php _e( 'Delete', 'church_mgt' ) ;?></a>
													
													</td>
												   
												</tr>
												<?php 
												} 
											}?>
											</tbody>
										</table>
									</div>
								</div><!-- PANEL BODY DIV END-->
							</form>
						 <?php 
						}
						if($active_tab == 'addtransaction')
						{	
							require_once CMS_PLUGIN_DIR. '/admin/transactions/add_transaction.php';
						}?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->
<?php ?>