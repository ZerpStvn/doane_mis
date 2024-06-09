<?php 
$curr_user_id=get_current_user_id();
$obj_church = new Church_management(get_current_user_id());
$obj_dashboard= new Cmgtdashboard;
$obj_pastoral=new Cmgtpastoral;
$obj_pledge=new Cmgtpledes;
$obj_gift=new Cmgtgift();
$obj_transaction=new Cmgttransaction;
$obj_attend=new Cmgtattendence;
$obj_activity=new Cmgtactivity;
$obj_service=new Cmgtservice;
	
	$service_id=0;
	if(isset($_REQUEST['service_id']))
	{
		$service_id=$_REQUEST['service_id'];
		$edit=0;					
		$edit=1;
		$user_info = get_userdata($service_id);
	}	
?>
<?php
if(isset ( $_REQUEST ['tab'] ) && $_REQUEST['tab']=='viewservice')
{
    $active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
    ?>
    <div class="panel-body view_patient_main"><!-- START PANEL BODY DIV-->
    <?php
    $servicedata=$obj_service->MJ_cmgt_get_single_services($service_id);
            ?>
            <div class="content-body">
                <section id="user_information" class="">
                    <div class="view_pateint_header_bg">
                        <div class="row">
                            <div class="col-xl-10 col-md-10 col-sm-10">
                                <div class="user_profile_header_left float_left_width_100">
                                    <?php 
                                    if($user_info->cmgt_user_avatar == "")
                                    {
                                        ?>
                                        <img class="user_view_profile_image" src="<?php echo get_option( 'cmgt_family_logo' )?>">
                                    <?php 
                                    }
                                    else 
                                    {
                                    ?>
                                        <img class="user_view_profile_image" src="<?php if($edit)echo esc_url( $user_info->cmgt_user_avatar ); ?>" />
                                    <?php 
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="float_left view_top1">
                                            <div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
                                                <label class="view_user_name_label"><?php echo $servicedata->service_title;?> </label>
                                                <div class="view_user_edit_btn ">
                                                    <a class="color_white margin_left_2px" href="?page=cmgt-service&tab=addservice&action=edit&service_id=<?php echo esc_attr($retrieved_data->id);?>">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
                                                <div class="view_user_phone float_left_width_100">
                                                <i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<lable>
                                                <?php echo '+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta($family_id, 'mobile_number', true);?>
                                                </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12">
                                            <div class="view_top2">
                                                <div class="view_user_doctor_label">
                                                <i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;<lable class="doctor_label"><?php echo esc_html_e(chunk_split(($user_info->user_login),17));?> </lable>
                                                <i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;<lable><?php echo  $user_info->address.', '.get_user_meta($family_id, 'city', true).', '.get_user_meta($family_id, 'state', true) .', '.get_user_meta($family_id, 'zip_code', true) ?> </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-2 col-sm-2 group_thumbs">
                                <img src="<?php echo CMS_PLUGIN_URL."/assets/images/Church-icons/Group.png"?>">
                            </div>
                        </div>
                    </div>
                </section>
                <section id="body_content_area" class="margin_top_7per">
                    <div class="panel-body"><!-- START PANEL BODY DIV-->
                        <?php 
                        if($active_tab1 == "general")
                        {
                            $user_meta =get_user_meta($_REQUEST['family_id'], 'member_id', true); 
                            ?>
                            <div class="row">
                                <div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
                                    <label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'church_mgt'); ?> </label><br/>
                                    <label class="view_page_content_labels"><?php echo esc_html(chunk_split(($user_info->user_email),19));?></label>
                                </div>
                                <div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
                                    <label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'church_mgt'); ?> </label><br/>
                                    <label class="view_page_content_labels"><?php echo esc_html(date(MJ_cmgt_date_formate(),strtotime($user_info->birth_date)));?></label>
                                </div>
                                <div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
                                    <label class="view_page_header_labels"> <?php esc_html_e('Gender', 'church_mgt'); ?> </label><br/>
                                    <label class="view_page_content_labels"><?php 
                                        if($user_info->gender == "male")
                                        {
                                            $gender=esc_html__('Male','church_mgt');
                                        }
                                        elseif($user_info->gender == "female")
                                        {
                                            $gender=esc_html__('Female','church_mgt');
                                        }
                                        echo $gender;?> 
                                    </label>	
                                </div>
                                <div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
                                    <label class="view_page_header_labels"> <?php esc_html_e('Relation', 'church_mgt'); ?> </label><br/>
                                    <label class="view_page_content_labels"><?php echo esc_html($user_info->relation);?></label>
                                </div>
                                <div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
                                    <label class="view_page_header_labels"> <?php esc_html_e('Member Name', 'church_mgt'); ?> </label><br/>
                                    <label class="view_page_content_labels"><?php echo get_user_meta($user_meta, 'first_name', true);?> <?php echo get_user_meta($user_meta, 'last_name', true);?></label>
                                </div>
                            </div>
                            <div class="row margin_top_20px">
                                <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px">
                                        <div class="guardian_div">
                                            <label class="view_page_label_heading"> <?php esc_html_e('Address Information', 'church_mgt'); ?> </label>
                                            <div class="row">
                                                <div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
                                                    <label class="guardian_labels view_page_content_labels"> <?php esc_html_e('City','church_mgt'); ?> </label>: <label class=""><?php echo get_user_meta($family_id, 'city', true);?></label>
                                                </div>
                                                <div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
                                                    <label class="guardian_labels view_page_content_labels"> <?php _e('State','church_mgt');?> </label>: <label class=""><?php echo get_user_meta($family_id, 'state', true);?></label>
                                                </div>
                                                <div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
                                                    <label class="guardian_labels view_page_content_labels"> <?php _e('Zip Code','church_mgt');?> </label>: <label class=""><?php echo get_user_meta($family_id, 'zip_code', true);?></label>
                                                </div>
                                            </div>
                                        </div>	
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px">
                                        <div class="guardian_div">
                                            <label class="view_page_label_heading"> <?php esc_html_e('Contact Information', 'church_mgt'); ?> </label>
                                            <div class="row">
                                            <div class="col-xl-6 col-md-6 col-sm-12 margin_top_15px">
                                                    <label class="guardian_labels view_page_content_labels"> <?php _e('Mobile Number','church_mgt');?> </label>: <label class=""><?php echo '+'.MJ_cmgt_get_countery_phonecode(get_option( 'cmgt_contry' )).' '.get_user_meta($family_id, 'mobile_number', true);?></label>
                                                </div>
                                                <div class="col-xl-6 col-md-6 col-sm-12 margin_top_15px">
                                                    <label class="guardian_labels view_page_content_labels"> <?php esc_html_e('Phone No','church_mgt'); ?> </label>: <label class=""><?php echo esc_html($user_info->phone);?></label>
                                                </div>
                                                
                                            </div>
                                        </div>	
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </section>
            </div>
        </div>
    <?php
} 
?>