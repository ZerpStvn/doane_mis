<?php 	
$result=get_option('cmgt_access_right_management');
if(isset($_POST['save_access_right']))
{
	$role_access_right = array();
	$result=get_option('cmgt_access_right_management');
	$role_access_right['management'] = [
									    "member"=>
										["menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
											'menu_title'=>'Member',
										   "page_link"=>'member',
										   "own_data" =>isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:0,
										   "add" =>isset($_REQUEST['member_add'])?$_REQUEST['member_add']:0,
											"edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:0,
											"view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:0,
											"delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:0
										],
								        "familymember"=>
										[   "menu_icone"=>plugins_url( 'church-management/assets/images/icon/member.png' ),
											'menu_title'=>'Family Member',
											"page_link"=>'family',
											"own_data" =>isset($_REQUEST['familymember_own_data'])?$_REQUEST['familymember_own_data']:0,
											"add" =>isset($_REQUEST['familymember_add'])?$_REQUEST['familymember_add']:0,
											"edit"=>isset($_REQUEST['familymember_edit'])?$_REQUEST['familymember_edit']:0,
											"view"=>isset($_REQUEST['familymember_view'])?$_REQUEST['familymember_view']:0,
											"delete"=>isset($_REQUEST['familymember_delete'])?$_REQUEST['familymember_delete']:0
										],
                                        "accountant"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Accountant.png' ),
												'menu_title'=>'Accountant',
											   "page_link"=>'accountant',
												 "own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
												 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
												"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
												"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:0,
												"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
									],
										"group"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/group.png' ),			
									        'menu_title'=>'Group',
											"page_link"=>'group',
											 "own_data" => isset($_REQUEST['group_own_data'])?$_REQUEST['group_own_data']:0,
											 "add" => isset($_REQUEST['group_add'])?$_REQUEST['group_add']:0,
											"edit"=>isset($_REQUEST['group_edit'])?$_REQUEST['group_edit']:0,
											"view"=>isset($_REQUEST['group_view'])?$_REQUEST['group_view']:0,
											"delete"=>isset($_REQUEST['group_delete'])?$_REQUEST['group_delete']:0
								        ],
										"services"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/services.png' ),
												'menu_title'=>'Services',
												"page_link"=>'services',
												"own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
												 "add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:0,
												 "edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:0,
												"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:0,
												"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:0
									  ],
									  "attendance"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Attendance.png' ),			
									   'menu_title'=>'Attendance',
												 "page_link"=>'attendance',
												 "own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
												 "add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
												"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
												"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:0,
												"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
									],
									"ministry"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Ministry.png' ),			
												'menu_title'=>'Ministry',
												 "page_link"=>'ministry',
												 "own_data" => isset($_REQUEST['ministry_own_data'])?$_REQUEST['ministry_own_data']:0,
												 "add" => isset($_REQUEST['ministry_add'])?$_REQUEST['ministry_add']:0,
												"edit"=>isset($_REQUEST['ministry_edit'])?$_REQUEST['ministry_edit']:0,
												"view"=>isset($_REQUEST['ministry_view'])?$_REQUEST['ministry_view']:0,
												"delete"=>isset($_REQUEST['ministry_delete'])?$_REQUEST['ministry_delete']:0
								],
								"pastoral"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/pastoral.png' ),
											'menu_title'=>'Pastoral',
											   "page_link"=>'pastoral',
												 "own_data" => isset($_REQUEST['pastoral_own_data'])?$_REQUEST['pastoral_own_data']:0,
												 "add" => isset($_REQUEST['pastoral_add'])?$_REQUEST['pastoral_add']:0,
												"edit"=>isset($_REQUEST['pastoral_edit'])?$_REQUEST['pastoral_edit']:0,
												"view"=>isset($_REQUEST['pastoral_view'])?$_REQUEST['pastoral_view']:0,
												"delete"=>isset($_REQUEST['pastoral_delete'])?$_REQUEST['pastoral_delete']:0
									  ],
									  "activity"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Activity.png' ),
									  'menu_title'=>'Activity',
										"page_link"=>'activity',
									   "own_data" => isset($_REQUEST['activity_own_data'])?$_REQUEST['activity_own_data']:0,
									   "add" => isset($_REQUEST['activity_add'])?$_REQUEST['activity_add']:0,
									  "edit"=>isset($_REQUEST['activity_edit'])?$_REQUEST['activity_edit']:0,
									  "view"=>isset($_REQUEST['activity_view'])?$_REQUEST['activity_view']:0,
									  "delete"=>isset($_REQUEST['activity_delete'])?$_REQUEST['activity_delete']:0
							],
							"venue"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venue.png' ),			'menu_title'=>'Venue',
												 "page_link"=>'venue',
												 "own_data" => isset($_REQUEST['venue_own_data'])?$_REQUEST['venue_own_data']:0,
												 "add" => isset($_REQUEST['venue_add'])?$_REQUEST['venue_add']:0,
												"edit"=>isset($_REQUEST['venue_edit'])?$_REQUEST['venue_edit']:0,
												"view"=>isset($_REQUEST['venue_view'])?$_REQUEST['venue_view']:0,
												"delete"=>isset($_REQUEST['venue_delete'])?$_REQUEST['venue_delete']:0
									  ],
									  "reservation"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Venues.png' ),			
									  'menu_title'=>'Reservation',
									  "page_link"=>'reservation',
									  "own_data" => isset($_REQUEST['reservation_own_data'])?$_REQUEST['reservation_own_data']:0,
									  "add" => isset($_REQUEST['reservation_add'])?$_REQUEST['reservation_add']:0,
									  "edit"=>isset($_REQUEST['reservation_edit'])?$_REQUEST['reservation_edit']:0,
									  "view"=>isset($_REQUEST['reservation_view'])?$_REQUEST['reservation_view']:0,
									  "delete"=>isset($_REQUEST['reservation_delete'])?$_REQUEST['reservation_delete']:0
						 			 ],
									  "check-in"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Check-In.png' ),
									  'menu_title'=>'Check-In',
									   "page_link"=>'check-in',
									   "own_data" => isset($_REQUEST['check-in_own_data'])?$_REQUEST['check-in_own_data']:0,
									   "add" => isset($_REQUEST['check-in_add'])?$_REQUEST['check-in_add']:0,
									  "edit"=>isset($_REQUEST['check-in_edit'])?$_REQUEST['check-in_edit']:0,
									  "view"=>isset($_REQUEST['check-in_view'])?$_REQUEST['check-in_view']:0,
									  "delete"=>isset($_REQUEST['check-in_delete'])?$_REQUEST['check-in_delete']:0
									],
									"sermon-list"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Sermon-List.png' ),			
												'menu_title'=>'Sermon List',
												  "page_link"=>'sermon-list',
												 "own_data" => isset($_REQUEST['sermon-list_own_data'])?$_REQUEST['sermon-list_own_data']:0,
												 "add" => isset($_REQUEST['sermon-list_add'])?$_REQUEST['sermon-list_add']:0,
												"edit"=>isset($_REQUEST['sermon-list_edit'])?$_REQUEST['sermon-list_edit']:0,
												"view"=>isset($_REQUEST['sermon-list_view'])?$_REQUEST['sermon-list_view']:0,
												"delete"=>isset($_REQUEST['sermon-list_delete'])?$_REQUEST['sermon-list_delete']:0
									  ],
									  "spiritual-gift"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Spiritual-Gift.png' ),
												'menu_title'=>'Spiritual Gift',
												  "page_link"=>'spiritual-gift',
												 "own_data" => isset($_REQUEST['spiritual-gift_own_data'])?$_REQUEST['spiritual-gift_own_data']:0,
												 "add" => isset($_REQUEST['spiritual-gift_add'])?$_REQUEST['spiritual-gift_add']:0,
												"edit"=>isset($_REQUEST['spiritual-gift_edit'])?$_REQUEST['spiritual-gift_edit']:0,
												"view"=>isset($_REQUEST['spiritual-gift_view'])?$_REQUEST['spiritual-gift_view']:0,
												"delete"=>isset($_REQUEST['spiritual-gift_delete'])?$_REQUEST['spiritual-gift_delete']:0
									  ],
									  "pledges"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Pledges.png' ),
									  'menu_title'=>'Pledges',
									   "page_link"=>'pledges',
									   "own_data" => isset($_REQUEST['pledges_own_data'])?$_REQUEST['pledges_own_data']:0,
									   "add" => isset($_REQUEST['pledges_add'])?$_REQUEST['pledges_add']:0,
									  "edit"=>isset($_REQUEST['pledges_edit'])?$_REQUEST['pledges_edit']:0,
									  "view"=>isset($_REQUEST['pledges_view'])?$_REQUEST['pledges_view']:0,
									  "delete"=>isset($_REQUEST['pledges_delete'])?$_REQUEST['pledges_delete']:0
									],
									"songs"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Songs.png' ),
												'menu_title'=>'Songs',
												 "page_link"=>'songs',
												 "own_data" => isset($_REQUEST['songs_own_data'])?$_REQUEST['songs_own_data']:0,
												 "add" => isset($_REQUEST['songs_add'])?$_REQUEST['songs_add']:0,
												"edit"=>isset($_REQUEST['songs_edit'])?$_REQUEST['songs_edit']:0,
												"view"=>isset($_REQUEST['songs_view'])?$_REQUEST['songs_view']:0,
												"delete"=>isset($_REQUEST['songs_delete'])?$_REQUEST['songs_delete']:0
									  ],
									  "document"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/document.png' ),
												'menu_title'=>'Document',
											  "page_link"=>'document',
											 "own_data" => isset($_REQUEST['document_own_data'])?$_REQUEST['document_own_data']:0,
											 "add" => isset($_REQUEST['document_add'])?$_REQUEST['document_add']:0,
											 "edit"=>isset($_REQUEST['document_edit'])?$_REQUEST['document_edit']:0,
											 "view"=>isset($_REQUEST['document_view'])?$_REQUEST['document_view']:0,
											 "delete"=>isset($_REQUEST['document_delete'])?$_REQUEST['document_delete']:0
								  ],
								  "payment"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/Transaction.png' ),
								  'menu_title'=>'Payment',
								   "page_link"=>'payment',
								   "own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:0,
								   "add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:0,
								  "edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:0,
								  "view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:0,
								  "delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:0
								],
								"report"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/message.png'),
								"menu_title"=>'Report',
								"page_link"=>'report',
								"own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
								"add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
								"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
								"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:0,
								"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
						],
								"notice"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/notice.png'),
								"menu_title"=>'notice',
								"page_link"=>'notice',
								"own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:0,
								"add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:0,
								"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
								"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:0,
								"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
								],
						  		 "message"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/message.png'),
									"menu_title"=>'Message',
									"page_link"=>'message',
										"own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:0,
										"add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:0,
									"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
									"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:0,
									"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:0
								],
								"newsletter"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
								'menu_title'=>'News Letter',
								"page_link"=>'news_letter',
								"own_data" => isset($_REQUEST['newsletter_own_data'])?$_REQUEST['newsletter_own_data']:0,
								"add" => isset($_REQUEST['newsletter_add'])?$_REQUEST['newsletter_add']:0,
								"edit"=>isset($_REQUEST['newsletter_edit'])?$_REQUEST['newsletter_edit']:0,
								"view"=>isset($_REQUEST['newsletter_view'])?$_REQUEST['newsletter_view']:0,
								"delete"=>isset($_REQUEST['newsletter_delete'])?$_REQUEST['newsletter_delete']:0
								],

								"emailtemplate"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
								'menu_title'=>'Email Template',
								"page_link"=>'emailtemplate',
								"own_data" => isset($_REQUEST['emailtemplate_own_data'])?$_REQUEST['newsletter_own_data']:0,
								"add" => isset($_REQUEST['emailtemplate_add'])?$_REQUEST['emailtemplate_add']:0,
								"edit"=>isset($_REQUEST['emailtemplate_edit'])?$_REQUEST['emailtemplate_edit']:0,
								"view"=>isset($_REQUEST['emailtemplate_view'])?$_REQUEST['emailtemplate_view']:0,
								"delete"=>isset($_REQUEST['emailtemplate_delete'])?$_REQUEST['emailtemplate_delete']:0
								],   // accessright generalsetting
								"accessright"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
								'menu_title'=>'Access Right',
								"page_link"=>'accessright',
								"own_data" => isset($_REQUEST['accessright_own_data'])?$_REQUEST['accessright_own_data']:0,
								"add" => isset($_REQUEST['accessright_add'])?$_REQUEST['accessright_add']:0,
								"edit"=>isset($_REQUEST['accessright_edit'])?$_REQUEST['accessright_edit']:0,
								"view"=>isset($_REQUEST['accessright_view'])?$_REQUEST['accessright_view']:0,
								"delete"=>isset($_REQUEST['accessright_delete'])?$_REQUEST['accessright_delete']:0
								],
								"generalsetting"=>['menu_icone'=>plugins_url( 'church-management/assets/images/icon/newsletter.png' ),
								'menu_title'=>'General Setting',
								"page_link"=>'generalsetting',
								"own_data" => isset($_REQUEST['generalsetting_own_data'])?$_REQUEST['generalsetting_own_data']:0,
								"add" => isset($_REQUEST['generalsetting_add'])?$_REQUEST['generalsetting_add']:0,
								"edit"=>isset($_REQUEST['generalsetting_edit'])?$_REQUEST['generalsetting_edit']:0,
								"view"=>isset($_REQUEST['generalsetting_view'])?$_REQUEST['generalsetting_view']:0,
								"delete"=>isset($_REQUEST['generalsetting_delete'])?$_REQUEST['generalsetting_delete']:0
								]

									];

	$result=update_option( 'cmgt_access_right_management',$role_access_right);
	wp_redirect ( admin_url() . 'admin.php?page=cmgt-access_right&tab=management&message=1');
}
$access_right=get_option('cmgt_access_right_management');
?>	
<div class="panel panel-white main_home_page_div"><!--- PANEL WHITE DIV START -->
		<h2 class="first_hed">
			<?php esc_html_e('Management Access Right', 'church_mgt'); ?>
		</h2>			
		<div class="panel-body"> <!--- PANEL BODY DIV START -->
			<form name="student_form" action="" method="post" class="cmgt_access_form" id="access_right_form">	
			<div class="row access_right_hed">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_16_res"><?php esc_html_e('Menu','church_mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min word_break width_16_res"><?php esc_html_e('OwnData','church_mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 view_access_right width_16_res"><?php esc_html_e('View','church_mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 add_access_right width_16_res"><?php esc_html_e('Add','church_mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 edit_access_right width_16_res"><?php esc_html_e('Edit','church_mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_25_min delete_access_right width_16_res"><?php esc_html_e('Delete','church_mgt');?></div>
				</div>
				<div class="access_right_menucroll row border_bottom_0">
					<!-- Member module code  -->
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label menu_left_6">
								<?php esc_html_e('Member','church_mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res margin_right_10px width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['member']['own_data'],1);?> value="0" disabled name="member_own_data">	              
								</label> 
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['member']['view'],1);?> value="1" name="member_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['member']['add'],1);?> value="1" name="member_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['member']['edit'],1);?> value="1" name="member_edit">	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['member']['delete'],1);?> value="1" name="member_delete">	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Member module code end -->
					
					<!-- Family member access right -->
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label menu_left_6">
								<?php esc_html_e('Family Member','church_mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['familymember']['own_data'],1);?> value="1" name="familymember_own_data" disabled>	              
								</label> 
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['familymember']['view'],1);?> value="1" name="familymember_view" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['familymember']['add'],1);?> value="1" name="familymember_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['familymember']['edit'],1);?> value="1" name="familymember_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['familymember']['delete'],1);?> value="1" name="familymember_delete" >	              
								</label>
							</div>
						</div>								
					</div>	
					<!-- family member access right end  -->
					
					<!-- Accountant module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Accountant','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['accountant']['own_data'],1);?> value="1" disabled name="accountant_own_data">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['accountant']['view'],1);?> value="1" name="accountant_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['accountant']['add'],1);?> value="1" name="accountant_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['accountant']['edit'],1);?> value="1" name="accountant_edit">              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['accountant']['delete'],1);?> value="1" name="accountant_delete">        
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Accountant module code  -->
                    
					<!-- Group module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Group','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['group']['own_data'],1);?> value="1" name="group_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['group']['view'],1);?> value="1" name="group_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['group']['add'],1);?> value="1" name="group_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['group']['edit'],1);?> value="1" name="group_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['group']['delete'],1);?> value="1" name="group_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Group module code  -->
					
					<!-- Ministry module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Ministry','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['ministry']['own_data'],1);?> value="1" name="ministry_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['ministry']['view'],1);?> value="1" name="ministry_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['ministry']['add'],1);?> value="1" name="ministry_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['ministry']['edit'],1);?> value="1" name="ministry_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['ministry']['delete'],1);?> value="1" name="ministry_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>					
					<!-- Ministry module code  -->

					<!-- Services module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Services','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['services']['own_data'],1);?> value="1" name="services_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['services']['view'],1);?> value="1" name="services_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['services']['add'],1);?> value="1" name="services_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['services']['edit'],1);?> value="1" name="services_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['services']['delete'],1);?> value="1" name="services_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Services module code  -->

					<!-- Pastoral module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Pastoral','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['pastoral']['own_data'],1);?> value="1" name="pastoral_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['pastoral']['view'],1);?> value="1" name="pastoral_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['pastoral']['add'],1);?> value="1" name="pastoral_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['pastoral']['edit'],1);?> value="1" name="pastoral_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['pastoral']['delete'],1);?> value="1" name="pastoral_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Pastoral module code  -->
					
					<!-- Attendance module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Attendance','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['attendance']['own_data'],1);?> value="1" name="attendance_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['attendance']['view'],1);?> value="1" name="attendance_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['attendance']['add'],1);?> value="1" name="attendance_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['attendance']['edit'],1);?> value="1" name="attendance_edit" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['attendance']['delete'],1);?> value="1" name="attendance_delete" disabled>	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Attendance module code  -->
					
					<!-- Activity module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Activity','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['activity']['own_data'],1);?> value="1" name="activity_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['activity']['view'],1);?> value="1" name="activity_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['activity']['add'],1);?> value="1" name="activity_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['activity']['edit'],1);?> value="1" name="activity_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['activity']['delete'],1);?> value="1" name="activity_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Activity module code  -->

					<!-- Venue module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Venue','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['venue']['own_data'],1);?> value="1" name="venue_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['venue']['view'],1);?> value="1" name="venue_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['venue']['add'],1);?> value="1" name="venue_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['venue']['edit'],1);?> value="1" name="venue_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['venue']['delete'],1);?> value="1" name="venue_delete" >	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Venue module code  -->

					<!-- Reservation module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Reservation','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['reservation']['own_data'],1);?> value="1" name="reservation_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['reservation']['view'],1);?> value="1" name="reservation_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['reservation']['add'],1);?> value="1" name="reservation_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['reservation']['edit'],1);?> value="1" name="reservation_edit" >	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['reservation']['delete'],1);?> value="1" name="reservation_delete" >	              
								<label>
								</label>
							</div>
						</div>								
					</div>		
					<!-- Reservation module code  -->	

					<!-- Check-In module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Check-In','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['check-in']['own_data'],1);?> value="1" name="check-in_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['check-in']['view'],1);?> value="1" name="check-in_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['check-in']['add'],1);?> value="1" name="check-in_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['check-in']['edit'],1);?> value="1" name="check-in_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['check-in']['delete'],1);?> value="1" name="check-in_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Check-In module code  -->

					<!-- Document module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Document','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['document']['own_data'],1);?> value="1" name="document_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['document']['view'],1);?> value="1" name="document_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['document']['add'],1);?> value="1" name="document_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['document']['edit'],1);?> value="1" name="document_edit">	              
								</label>
							</div>
						</div>									
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['document']['delete'],1);?> value="1" name="document_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Document module code  -->

					<!-- Sermon List module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Sermon List','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['sermon-list']['own_data'],1);?> value="1" name="sermon-list_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['sermon-list']['view'],1);?> value="1" name="sermon-list_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['sermon-list']['add'],1);?> value="1" name="sermon-list_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['sermon-list']['edit'],1);?> value="1" name="sermon-list_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['sermon-list']['delete'],1);?> value="1" name="sermon-list_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Sermon List module code  -->

					<!-- Spiritual Gift module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Spiritual Gift','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['spiritual-gift']['own_data'],1);?> value="1" name="spiritual-gift_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['spiritual-gift']['view'],1);?> value="1" name="spiritual-gift_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['spiritual-gift']['add'],1);?> value="1" name="spiritual-gift_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['spiritual-gift']['edit'],1);?> value="1" name="spiritual-gift_edit" >	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['spiritual-gift']['delete'],1);?> value="1" name="spiritual-gift_delete" >	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Spiritual Gift module code  -->

					<!-- Pledges module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Pledges','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['pledges']['own_data'],1);?> value="1" name="pledges_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['pledges']['view'],1);?> value="1" name="pledges_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['pledges']['add'],1);?> value="1" name="pledges_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['pledges']['edit'],1);?> value="1" name="pledges_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['pledges']['delete'],1);?> value="1" name="pledges_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Pledges module code  -->

					<!-- Songs module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Songs','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['songs']['own_data'],1);?> value="1" name="songs_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['songs']['view'],1);?> value="1" name="songs_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['songs']['add'],1);?> value="1" name="songs_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['songs']['edit'],1);?> value="1" name="songs_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['songs']['delete'],1);?> value="1" name="songs_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Songs module code  -->

					<!-- Donate module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Donate','church_mgt');?>
							</span>
						</div>
						
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_20_res width_14_res">
					<div class="checkbox">
						<label>
							<input type="checkbox" <?php echo isset($access_right['management']['donate']['own_data']) && $access_right['management']['donate']['own_data'] == 1 ? 'checked' : ''; ?> value="1" name="donate_own_data" disabled>
						</label>
					</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
						<div class="checkbox">
							<label>
								<input type="checkbox" <?php echo isset($access_right['management']['donate']['view']) && $access_right['management']['donate']['view'] == 1 ? 'checked' : ''; ?> value="1" name="donate_view" disabled>
							</label>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
						<div class="checkbox">
							<label>
								<input type="checkbox" <?php echo isset($access_right['management']['donate']['add']) && $access_right['management']['donate']['add'] == 1 ? 'checked' : ''; ?> value="1" name="donate_add" disabled>
							</label>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
						<div class="checkbox">
							<label>
								<input type="checkbox" <?php echo isset($access_right['management']['donate']['edit']) && $access_right['management']['donate']['edit'] == 1 ? 'checked' : ''; ?> value="1" name="donate_edit" disabled>
							</label>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
						<div class="checkbox">
							<input type="checkbox" <?php echo isset($access_right['management']['donate']['delete']) && $access_right['management']['donate']['delete'] == 1 ? 'checked' : ''; ?> value="1" name="donate_delete" disabled>
							<label></label>
						</div>
					</div>
			
					</div>							
					<!-- Donate module code  -->
					
					<!-- Payment module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Payment','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['payment']['own_data'],1);?> value="1" name="payment_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['payment']['view'],1);?> value="1" name="payment_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['payment']['add'],1);?> value="1" name="payment_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['payment']['edit'],1);?> value="1" name="payment_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['payment']['delete'],1);?> value="1" name="payment_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Payment module code  -->

					<!-- Report module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Report','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['report']['own_data'],1);?> value="1" name="report_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['report']['view'],1);?> value="1" name="report_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['report']['add'],1);?> value="1" name="report_add" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['report']['edit'],1);?> value="1" name="report_edit" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['report']['delete'],1);?> value="1" name="report_delete" disabled>	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- End Report module code  -->

					<!-- Notice module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Notice','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['notice']['own_data'],1);?> value="1" name="notice_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['notice']['view'],1);?> value="1" name="notice_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['notice']['add'],1);?> value="1" name="notice_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['notice']['edit'],1);?> value="1" name="notice_edit">
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['notice']['delete'],1);?> value="1" name="notice_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Notice module code  -->

					<!-- Message module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Message','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['message']['own_data'],1);?> value="1" name="message_own_data" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['message']['view'],1);?> value="1" name="message_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['message']['add'],1);?> value="1" name="message_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['message']['edit'],1);?> value="1" name="message_edit" >	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['message']['delete'],1);?> value="1" name="message_delete">	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Message module code  -->

					<!-- News Letter module code  -->							
					<!-- <div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('News Letter','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['newsletter']['own_data'],1);?> value="1" name="newsletter_own_data" disabled>	    
									          
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['newsletter']['view'],1);?> value="1" name="newsletter_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['newsletter']['add'],1);?> value="1" name="newsletter_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['newsletter']['edit'],1);?> value="1" name="newsletter_edit" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['newsletter']['delete'],1);?> value="1" name="newsletter_delete" disabled>	              
								<label>
								</label>
							</div>
						</div>								
					</div>							 -->
					<!-- News Letter module code  -->

					<!-- Email Template module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('Email Template','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['emailtemplate']['own_data'],1);?> value="1" name="emailtemplate_own_data" disabled>	    
									          
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['emailtemplate']['view'],1);?> value="1" name="emailtemplate_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['emailtemplate']['add'],1);?> value="1" name="emailtemplate_add" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['emailtemplate']['edit'],1);?> value="1" name="emailtemplate_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['emailtemplate']['delete'],1);?> value="1" name="emailtemplate_delete" disabled>	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Email Template module code  -->

					<!-- Access Right module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e(' Access Right','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['accessright']['own_data'],1);?> value="1" name="accessright_own_data" disabled>	    
									          
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['accessright']['view'],1);?> value="1" name="accessright_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['accessright']['add'],1);?> value="1" name="accessright_add" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['accessright']['edit'],1);?> value="1" name="accessright_edit" disabled>	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['accessright']['delete'],1);?> value="1" name="accessright_delete" disabled>	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Access Right module code  -->

					<!-- General Setting module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_right_10px margin_left_min_5_res width_16_res">
							<span class="menu-label">
								<?php esc_html_e('General Setting','church_mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_20_res width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['generalsetting']['own_data'],1);?> value="1" name="generalsetting_own_data" disabled>	    
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15 width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['generalsetting']['view'],1);?> value="1" name="generalsetting_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['generalsetting']['add'],1);?> value="1" name="generalsetting_add" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_10_min width_14_res">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['management']['generalsetting']['edit'],1);?> value="1" name="generalsetting_edit">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 margin_left_15_min margin_left_20_min width_14_res">
							<div class="checkbox">
									<input type="checkbox" <?php echo checked($access_right['management']['generalsetting']['delete'],1);?> value="1" name="generalsetting_delete" disabled>	              
								<label>
								</label>
							</div>
						</div>								
					</div>							
					<!--General Setting module code  -->


				</div>						
				<div class="col-sm-6 row_bottom mt-2">	
					<input type="submit" value="<?php esc_html_e('Save', 'church_mgt' ); ?>" name="save_access_right" class="btn btn-success col-md-12 save_btn"/>
				</div>					
			</form>
		</div><!---END PANEL BODY DIV -->
</div> <!--- END PANEL WHITE DIV -->   	