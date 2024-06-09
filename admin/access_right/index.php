<?php 
	MJ_cmgt_header();
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'Member';
?>
<!-- View Popup Code start -->	
<div class="popup-bg">
    <div class="overlay-content">
    	<div class="notice_content"></div>    
    </div> 
</div>	
<!-- View Popup Code end -->
	<!-- user redirect url enter -->
<?php
	$user_access = MJ_cmgt_add_check_access_for_view('accessright');
	if($user_access == 'administrator')
	{
		$user_access_add=1;
		$user_access_edit=1;
		$user_access_delete=1;
		$user_access_view=1;
	}
	else
	{
		$user_access_view = $user_access['view'];
	}

	if (isset($_REQUEST['page'])) 
	{
		if ($user_access_view == '0') 
		{
			mj_cmgt_access_right_page_not_access_message_admin_side();
			die;
		}
	}
?>
<!-- user redirect url enter code end -->
<!-- page inner div start-->
<div class="page-inner min_height_1631">
	<!--  main-wrapper div start  -->
	<div id="" class="notice_page font_size_access">
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_html__('Record Updated Successfully.','church_mgt');
				break;		
		}
		if($message)
		{ ?>
			<div id="message" class="updated below-h2 notice is-dismissible">
				<p><?php echo $message_string;?></p>
			</div>
		<?php 
		} ?>
		<!-- panel-white div start  -->
		<div class="row panel panel-white">
			<!-- panel-body div start  -->
			<div class="cmgt_panel_body_access panel-body chart_reports">
				<h2 class="cmgt-nav-tab-wrapper nav-tab-wrapper ">
					<ul class="nav spiritual_gift_menu nav-tabs panel_tabs margin_left_1per cmgt-view-page-tab nav-tab-wrapper flex-nowrap overflow-auto" role="tablist">
						<li class="<?php if($active_tab=='Member'){?>active<?php }?>">			
							<a href="?page=cmgt-access_right&tab=Member" class="nav-tab <?php echo esc_html($active_tab) == 'Member' ? 'nav-tab-active' : ''; ?>">
							<?php echo esc_html__('Member', 'church_mgt'); ?></a>
						</li>
						<li class="<?php if($active_tab=='Accountant'){?>active<?php }?>">
							<a href="?page=cmgt-access_right&tab=Accountant" class="nav-tab <?php echo esc_html($active_tab) == 'Accountant' ? 'nav-tab-active' : ''; ?>">
							<?php echo esc_html__('Accountant', 'church_mgt'); ?></a> 
						</li>
						<li class="<?php if($active_tab=='Family_member'){?>active<?php }?>">
							<a href="?page=cmgt-access_right&tab=Family_member" class="nav-tab <?php echo esc_html($active_tab) == 'Family_member' ? 'nav-tab-active' : ''; ?>">
							<?php echo esc_html__('Family member', 'church_mgt'); ?></a>
						</li>
						<li class="<?php if($active_tab=='management'){?>active<?php }?>">
							<a href="?page=cmgt-access_right&tab=management" class="nav-tab <?php echo esc_html($active_tab) == 'management' ? 'nav-tab-active' : ''; ?>">
							<?php echo esc_html__('Management', 'church_mgt'); ?></a> 
						</li>
					</ul>
				</h2>
				<div class="clearfix"></div>
				<?php
				if($active_tab == 'Member')
				 {
					require_once CMS_PLUGIN_DIR. '/admin/access_right/Member.php';					
				 }
				 
				 elseif($active_tab == 'Accountant')
				 {
					require_once CMS_PLUGIN_DIR. '/admin/access_right/Accountant.php';
				 }
				 
				 elseif($active_tab == 'Family_member')
				 {
					require_once CMS_PLUGIN_DIR. '/admin/access_right/Family_member.php';
				 }	
				 elseif($active_tab == 'management')
				 {
					require_once CMS_PLUGIN_DIR. '/admin/access_right/Management.php';
				 }
				 ?> 
			</div>
			<!-- panel-body div end -->
	 	</div>
		<!-- panel-white div end -->
	</div>
	<!--  main-wrapper div end -->
</div>
<!-- page inner div end -->
<?php ?>