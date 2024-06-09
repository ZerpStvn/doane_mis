<?php
MJ_cmgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=MJ_cmgt_get_userrole_wise_access_right_array();
$curr_user_id=get_current_user_id();
$obj_church = new Church_management(get_current_user_id());
$obj_venue=new Cmgtvenue;
$obj_reservation = new Cmgtreservation;
$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'reservation_list');
$time_validation = '';
if (isset ( $_REQUEST ['page'] ))
{
	if($user_access['view']=='0')
	{	
		MJ_cmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_cmgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
if(isset($_POST['save_reservation']))
{   
		//---------- SAVE RESERVATION DATA ------------//
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			if($_POST['start_time'] <= $_POST['end_time'])
			{
				$result=$obj_reservation->MJ_cmgt_add_reservation($_POST);
                
				if($result)
				{
					wp_redirect ( home_url().'?church-dashboard=user&page=reservation&tab=reservation_list&message=2');
				}
			}
			else
			{
				$time_validation='1';
			}
		}
		else
		{
			if($_POST['start_time'] <= $_POST['end_time'])
			{
				$result=$obj_reservation->MJ_cmgt_add_reservation($_POST);
				if($result)
				{
					wp_redirect ( home_url().'?church-dashboard=user&page=reservation&tab=reservation_list&message=1');
				}
			}
			else
			{
				$time_validation='1';
			}
		}
		if($time_validation=='1')
		{
		?>
            <div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			
                <?php
                    esc_html_e('End Time should be greater than Start Time','church_mgt');
                ?>
                <button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		    </div>
            
		<?php 
		}
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
    if(isset($_REQUEST['reservation_id']))
    {
        $result=$obj_reservation->MJ_cmgt_delete_reservation(sanitize_text_field($_REQUEST['reservation_id']));
        if($result)
        {
            wp_redirect ( home_url().'??church-dashboard=user&page=reservation&tab=reservation_list&message=3');
        }
    }
}
if(isset($_REQUEST['message']))
{
	$message = sanitize_text_field($_REQUEST['message']);
	if($message == 1)
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			
			<?php
			esc_html_e('Record inserted successfully','church_mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
			<?php 
	}
	elseif($message == 2)
	{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e("Record updated successfully.",'church_mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
			<?php 
	}
	elseif($message == 3) 
		{?>
		<div id="message_template" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<?php
			esc_html_e('Record deleted successfully','church_mgt');
			?>
			<button type="button" class="close btn-close float-end p-3" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php
				
		}
}	
?>
<script type="text/javascript">
$(document).ready(function()
{
	//------------ CLOSE MESSAGE ---------//
	$('.notice-dismiss').click(function() {
		$('#message_template').hide();
	}); 
}); 
</script>
<style>
   input::placeholder {
    color: #555 !important;
}
</style>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#equipment_category').multiselect({
		nonSelectedText :'<?php esc_html_e('Select Equipment','church_mgt');?>',
		selectAllText : '<?php esc_html_e('Select all','church_mgt'); ?>',
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: '<?php esc_html_e('Search for Equipment...','church_mgt');?>',
		templates: {
            button: '<button type="button" class="multiselect btn btn-default dropdown-toggle" data-bs-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
        },
		buttonContainer: '<div class="dropdown" />'
		
		});
		
		$('#reservation_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		
		$(".reservation_date").datepicker({
       	dateFormat: "yy-mm-dd",
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".reservation_end_date").datepicker("option", "minDate", dt);
        }
	    });
	    $(".reservation_end_date").datepicker({
	      dateFormat: "yy-mm-dd",
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() - 0);
	            $(".reservation_date").datepicker("option", "maxDate", dt);
	        }
	    });	
	    
		$('#reservation_start_time').timepicki(
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:15,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: true
			}
		);
		$('#reservation_end_time').timepicki(
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:15,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: true
			}
		);
	
	   $('#capacity').keydown(function( e ) 
		{
			if(e.which == 189 || e.which == 109)
			 return false;
		});

		//not aloow - value
		$('#participant').keydown(function( e ) 
		{
			if(e.which === 189 || e.which == 109) 
			 return false;
		});	
        $(".check_memeber").click(function()
		{	
			var max_value=$('#capacity').val() ;
			var participant_value=$('#participant').val() ;

			if(participant_value > max_value)
			{
				alert("Participant value must be less than or equals to the capacity","church_mgt");
                //alert(language_translate.max_limit_member_alert);
				return false;
			}			
		}); 
	} );
</script>
<div class="panel-white"><!--PANEL WHITE DIV START-->
	<div class="tab-content padding_frontendlist_body"><!--TAB CONTENT DIV STRAT-->
        <?php
        if($active_tab == 'reservation_list')
        { 
            $own_data=$user_access['own_data'];
            if($obj_church->role == 'accountant')
            {
                $reservationdata=$obj_reservation->MJ_cmgt_get_all_reservation();
            }
            else
            {
            
                if($own_data == '1')
                { 
                    $reservationdata=$obj_reservation->MJ_cmgt_get_members_reservation($user_id);
                }
                else
                {
                    $reservationdata=$obj_reservation->MJ_cmgt_get_all_reservation();
                }
            }
            if(!empty($reservationdata))
            {
                ?>
                <script type="text/javascript">
                    $(document).ready(function() 
                    {
                        jQuery('#reservation_list').DataTable({
                            // "responsive":true,
                            "order": [[ 0, "asc" ]],
                            "sSearch": "<i class='fa fa-search'></i>",
                            "dom": 'lifrtp',
                            language:<?php echo MJ_cmgt_datatable_multi_language();?>,
                            "aoColumns":[
                                        {"bSortable": false},
                                        {"bSortable": true},
                                        {"bSortable": true},
                                        {"bSortable": true},
                                        {"bSortable": true},
                                        {"bSortable": true},
                                        {"bSortable": false},
                                    ]
                                });	
                        $('.dataTables_filter input').attr("placeholder", "Search...");	
                    } );
                </script>
                <div class="popup-bg">
                    <div class="overlay-content">
                        <div class="modal-content">
                            <div class="category_list"></div>
                        </div>
                    </div> 
                </div>
                <div class="padding_left_15px"><!--PANEL BODY DIV START-->
                    <div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->  
                        <table id="reservation_list" class="display" cellspacing="0" width="100%">
                            <thead class="<?php echo MJ_cmgt_datatable_heder(); ?>">
                                <tr>
                                    <th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
                                    <th><?php _e( 'Usage Title', 'church_mgt' ) ;?></th>
                                    <th><?php _e( 'Venue', 'church_mgt' ) ;?></th>
                                    <th><?php _e( 'Start Date To End Date', 'church_mgt' ) ;?></th>
                                    <th> <?php _e( 'Start Time To End Time', 'church_mgt' ) ;?></th>
                                    <th><?php _e( 'Applicant', 'church_mgt' ) ;?></th>
                                    <th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th><?php  _e( 'Image', 'church_mgt' ) ;?></th>
                                    <th><?php _e( 'Usage Title', 'church_mgt' ) ;?></th>
                                    <th><?php _e( 'Venue', 'church_mgt' ) ;?></th>
                                    <th><?php _e( 'Reserved End Date', 'church_mgt' ) ;?></th>
                                    <th> <?php _e( 'Start Time', 'church_mgt' ) ;?></th>
                                    <th><?php _e( 'Applicant', 'church_mgt' ) ;?></th>
                                    <th class="text_align_end"><?php  _e( 'Action', 'church_mgt' ) ;?></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php 
                                if(!empty($reservationdata))
                                {
                                    $i=0;
                                    foreach ($reservationdata as $retrieved_data)
                                    {
                                       
                                        $id = $retrieved_data->vanue_id;
                                        $obj_venue=new Cmgtvenue;
                                        $result = $obj_venue->MJ_cmgt_get_single_venue($id);
                                        if($i == 0)
                                        {
                                            $color_class='cmgt_list_page_image_color0';
                                        }
                                        elseif($i == 1)
                                        {
                                            $color_class='cmgt_list_page_image_color1';
                                        }
                                        elseif($i == 2)
                                        {
                                            $color_class='cmgt_list_page_image_color2';
                                        }
                                        elseif($i == 3)
                                        {
                                            $color_class='cmgt_list_page_image_color3';
                                        }
                                        elseif($i == 4)
                                        {
                                            $color_class='cmgt_list_page_image_color4';
                                        }
                                        elseif($i == 5)
                                        {
                                            $color_class='cmgt_list_page_image_color5';
                                        }
                                        elseif($i == 6)
                                        {
                                            $color_class='cmgt_list_page_image_color6';
                                        }
                                        elseif($i == 7)
                                        {
                                            $color_class='cmgt_list_page_image_color7';
                                        }
                                        elseif($i == 8)
                                        {
                                            $color_class='cmgt_list_page_image_color8';
                                        }
                                        elseif($i == 9)
                                        {
                                            $color_class='cmgt_list_page_image_color9';
                                        }
                                        ?>
                                        <tr>
                                            <td class="user_image width_50px profile_image_prescription padding_left_0">
                                                <p class="padding_15px prescription_tag <?php echo $color_class; ?>">	
                                                    <img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/cardlist_icon/Reservation-white.png"?>" alt="" class="massage_image center">
                                                </p>
                                            </td>
                                            
                                            <td class="name width_25_per"><a class="color_black view_reservation" id="<?php echo esc_attr($retrieved_data->id);?>" href="#"><?php echo esc_attr(ucfirst($retrieved_data->usage_title));?></a> </td>

                                            <td class="reserv_date width_15_per"><?php if(!empty($result->venue_title)){ echo $result->venue_title;}else{echo "N/A";}?> </td>

                                            <td class="start_date width_22_per"><?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->reserve_date)));?> <?php _e('To','church_mgt');?> <?php echo esc_attr(date(MJ_cmgt_date_formate(),strtotime($retrieved_data->reservation_end_date)));?> </td>

                                            <td class="width_12_per"><?php echo esc_attr($retrieved_data->reservation_start_time);?> <?php _e('To','church_mgt');?> <?php echo esc_attr($retrieved_data->reservation_end_time);?> </td>

                                            <td class="reserv_date width_15_per"><?php echo MJ_cmgt_church_get_display_name(esc_attr($retrieved_data->applicant_id));?> </td>
                                            <td class="action cmgt_pr_0px">
                                                <div class="cmgt-user-dropdown mt-2">
                                                    <ul class="">
                                                        <!-- BEGIN USER LOGIN DROPDOWN -->
                                                        <li class="">
                                                            <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/More.png"?>" class="more_img_mr">
                                                            </a>
                                                            <ul class="dropdown-menu extended logout heder-dropdown-menu cmgt-list-dropdown" aria-labelledby="dropdownMenuLink">
                                                                <li><a class="dropdown-item view_reservation" id="<?php echo esc_attr($retrieved_data->id);?>" href="#"><i class="fa fa-eye"></i><?php _e('View', 'church_mgt' ) ;?></a></li>
                                                                <?php 
                                                                if($user_access['edit'] == '1')
                                                                { 
                                                                    ?>
                                                                    <li><a class="dropdown-item" href="?church-dashboard=user&page=reservation&tab=add_reservation&action=edit&reservation_id=<?php echo esc_attr($retrieved_data->id);?>"> <i class="fa fa-pencil" aria-hidden="true"></i><?php _e('Edit', 'church_mgt' ) ;?></a></li>
                                                                    <?php
                                                                }
                                                                if($user_access['delete'] == '1')
                                                                { 
                                                                    ?>
                                                                    <div class="cmgt-dropdown-deletelist">
                                                                        <li><a class="dropdown-item" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','church_mgt');?>');" href="?church-dashboard=user&page=reservation&action=delete&reservation_id=<?php echo esc_attr($retrieved_data->id);?>"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete', 'church_mgt' ) ;?></a></li>
                                                                    </div>
                                                                    <?php 
                                                                } 
                                                                ?>
                                                            </ul>
                                                        </li>
                                                        <!-- END USER LOGIN DROPDOWN -->
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                        $i++;
                                    } 
                                }?>
                            </tbody>
                        </table>
                    </div><!--TABLE RESPONSIVE DIV END-->   
                </div><!--PANEL BODY DIV END-->  
                <?php 
            }
            else
            {
                if($user_access['add']=='1')
                {
                    ?>
                    <div class="no_data_list_div"> 
                        <a href="<?php echo home_url().'?church-dashboard=user&page=reservation&tab=add_reservation';?>">
                            <img class="width_100px" src="<?php echo get_option( 'cmgt_no_data_plus_img' ) ?>" >
                        </a>
                        <div class="col-md-12 dashboard_btn margin_top_20px">
                            <label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','church_mgt'); ?> </label>
                        </div> 
                    </div>		
                    <?php
                }
                else
                {
                    ?>
                    <div class="calendar-event-new"> 
                        <img class="no_data_img" src="<?php echo get_option( 'cmgt_Dashboard_defualt_img' ) ?>" >
                    </div>	
                    <?php
                }
            }

        }
        if($active_tab == 'add_reservation')
        {
            $reservation_id=0;
            if(isset($_REQUEST['reservation_id']))
                $reservation_id= sanitize_text_field($_REQUEST['reservation_id']);
                $edit=0;
                if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
                {
                    $edit=1;
                    $result =$obj_reservation->MJ_cmgt_get_single_reservation($reservation_id);
                }
                $user = wp_get_current_user();
                $membersdata =get_userdata( sanitize_text_field($user->ID));
                $member_id = $membersdata->ID;
                ?>
            <div class="panel-body"><!--PANEL BODY DIV START-->   
                <form name="reservation_form" action="" method="post" class="form-horizontal" id="reservation_form"><!--RESERVATION FORM START-->
                    <?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
                    <input type="hidden" name="action" value="<?php echo $action;?>">
                    <input type="hidden" name="reservation_id" value="<?php echo $reservation_id;?>"  />
                    <div class="form-body user_form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group input">
                                    <div class="col-md-12 form-control">
                                        <input id="usage_title" class="form-control validate[required,custom[onlyLetterSp]] text-input"  maxlength="50"  type="text" value="<?php if($edit){ echo esc_attr($result->usage_title);}elseif(isset($_POST['usage_title'])) echo esc_attr($_POST['usage_title']);?>" name="usage_title">
                                        <label class="" for="usage_title"><?php _e('Usage Title','church_mgt');?><span class="require-field">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 cmgt_display">
                                <div class="form-group input row margin_buttom_0">
                                    <div class="col-md-12">
                                        <label class="ml-1 custom-top-label top" for="venue"><?php _e('Venue','church_mgt');?><span class="require-field">*</span></label>
                                        <select class="form-control" name="vanue_id" id="vanue_id">
                                            <option value=""><?php _e('Select Venue','church_mgt');?></option>
                                            <?php 
                                            if(isset($_REQUEST['vanue_id']))
                                                $venue =sanitize_text_field($_REQUEST['vanue_id']);  
                                            elseif($edit)
                                                $venue = sanitize_text_field($result->vanue_id);
                                            else 
                                                $venue = "";
                                            $venuedata=$obj_venue->MJ_cmgt_get_all_venue();
                                            if(!empty($venuedata))
                                            {
                                                foreach ($venuedata as $retrive_data)
                                                {
                                                    echo '<option value="'.esc_attr($retrive_data->id).'" '.selected($venue,$retrive_data->id).'>'.esc_attr($retrive_data->venue_title).'</option>';
                                                }
                                            }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input">
                                <div class="col-md-12 form-control">
                                        <input  class="form-control validate[required] reservation_date" type="text"  name="reservation_date" data-date-format="yyyy-mm-dd" 
                                        value="<?php if($edit){ echo esc_attr($result->reserve_date);}elseif(isset($_POST['reservation_date'])) { echo esc_attr($_POST['reservation_date']); }else{ echo date(MJ_cmgt_date_formate("Y-m-d")); }?>" autocomplete="off" readonly>
                                        <label for="reservation_date"><?php _e('Reserve Start Date','church_mgt');?><span class="require-field">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input">
                                <div class="col-md-12 form-control">
                                        <input id="reservation_start_time" class="form-control validate[required]  timepicker" type="text"  name="start_time"   placeholder="<?php esc_html_e('Reservation Start Time*','church_mgt');?>"
                                        value="<?php if($edit){ echo esc_attr($result->reservation_start_time);}elseif(isset($_POST['start_time'])) echo esc_attr($_POST['start_time']);?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input">
                                <div class="col-md-12 form-control">
                                        <input  class="form-control validate[required] reservation_end_date" type="text"  name="reservation_end_date"   
                                        value="<?php if($edit){ echo esc_attr($result->reservation_end_date);}elseif(isset($_POST['reservation_end_date'])){ echo esc_attr($_POST['reservation_end_date']);}else{ echo date(MJ_cmgt_date_formate("Y-m-d")); }?>" autocomplete="off" readonly>
                                        <label for="reservation_date"><?php _e('Reserve End Date','church_mgt');?><span class="require-field">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input">
                                    <div class="col-md-12 form-control">
                                        
                                        <input id="reservation_end_time" class="form-control validate[required]  timepicker" type="text"  name="end_time"   placeholder="<?php esc_html_e('Reservation End Time*','church_mgt');?>"
                                        value="<?php if($edit){ echo esc_attr($result->reservation_end_time);}elseif(isset($_POST['end_time'])) echo esc_attr($_POST['end_time']);?>">
								   </div>
                                </div>
                            </div> 
                            <div class="col-md-4 rtl_margin_top_15px">
                                <div class="form-group input">
                                <div class="col-md-12 form-control">
                                        <input id="participant" type="text" class="form-control participant_class validate[required,custom[onlyNumber],min[0]]" max="<?php if($edit){ echo esc_attr($result->participant_max_limit); }?>" <?php if($edit){ ?>value="<?php echo esc_attr($result->participant);}elseif(isset($_POST['participant'])) echo esc_attr($_POST['participant']);?>" name="participant">
                                        <label class="" for="users"><?php _e('Number of Participant','church_mgt');?><span class="require-field">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 rtl_margin_top_15px">
                                <div class="form-group input">
                                <div class="col-md-12 form-control">
                                        <input id="capacity" class="form-control" type="text" value="<?php if($edit){ echo esc_attr($result->participant_max_limit);}elseif(isset($_POST['capacity'])) echo esc_attr($_POST['capacity']);?>"  readonly  name="capacity">
                                        <label class="" for="users"><?php _e('Max-Limit','church_mgt');?><span class="require-field">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 cmgt_display">
                                <div class="form-group input row margin_buttom_0">
                                    <div class="col-md-12">
                                        <label class="ml-1 custom-top-label top" for="applicant"><?php _e('Applicant','church_mgt');?><span class="require-field">*</span></label>
                                        
                                            <select class="form-control validate[required]" name="applicant_id" id="vanue_id">
											<?php
											    if($edit)
                                                {
                                                    $applicant = sanitize_text_field($result->applicant_id);
                                                }
                                                $user_id=get_current_user_id();										
											    if($obj_church->role == 'member')
											    {
												   $user_data=get_userdata($user_id);
												   echo '<option value="'.esc_attr($user_data->ID).'" '.selected($applicant,$user_data->ID).'>'.esc_attr($user_data->display_name).'</option>';
											    }
												else
												{ 
											    ?>
											    <option value=""><?php _e('Select Applicant','church_mgt');?></option>
											    <?php
												    $get_members = array('role' => 'member');
                                                    $membersdata=get_users($get_members);
													if(!empty($membersdata))
													{
														foreach ($membersdata as $retrieved_data)
														{
															echo '<option value="'.esc_attr($retrieved_data->ID).'" '.selected($applicant,$retrieved_data->ID).'>'.esc_attr($retrieved_data->display_name).'</option>';
														}
													}
											    }
											  ?>
                                        </select>
                                    </div>
                                </div>	
                            </div>
                            <div class="col-md-6 note_text_notice">
                                <div class="form-group input">
                                    <div class="col-md-12 note_border margin_bottom_15px_res">
                                        <div class="form-field">
                                            <textarea name="description" class="textarea_height form-control validate[custom[address_description_validation]]" maxlength="150" id="description"><?php if($edit){ echo esc_attr($result->description);}?></textarea>
                                            <span class="txt-title-label"></span>
                                            <label class="text-area address"><?php _e('Description','church_mgt');?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <?php wp_nonce_field( 'save_reservation_nonce' ); ?>
                            <div class="offset-sm-0">
                                <input type="submit" value="<?php if($edit){ esc_html_e('Save Reservation','church_mgt'); }else{ esc_html_e('Add Reservation','church_mgt');}?>" name="save_reservation" class="btn btn-success check_memeber save_btn reduce_sp"/>
                            </div>
                        </div>	
                    </div>
        
                </form><!--RESERVATION FORM END-->
            </div><!--PANEL BODY DIV END-->
            <?php 
        }
            ?>
    </div><!--TAB CONTENT DIV END-->
</div><!--PANEL WHITE DIV END-->
<?php ?>