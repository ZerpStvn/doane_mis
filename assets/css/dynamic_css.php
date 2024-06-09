<?php

    $path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
    
    require_once( $path . 'wp-load.php' );
    
    $color = get_option('cmgt_system_color_code');

   header("Content-type: text/css; charset: UTF-8"); //look carefully to this line

?>
<style>
    /* div.plugin_code_start{
        background: <?php echo $color;?>!important;
    } */
    .accordion-header button.accordion-button.class_route_list.collapsed{
        border-left: 5px solid <?php echo $color;?> !important;
    }
    
    /* .btn-sms-color {
        background-color: <?php echo $color;?> !important;
    }
    .save_btn {
        background-color: <?php echo $color;?> !important;
        background: <?php echo $color;?>;
    }
    .main_sidebar #sidebar .rs_side_menu_bgcolor{
        background-color: <?php echo $color;?> !important;
    }
 
  
  
    .steps li.current a .step-icon, .steps li.current a:active .step-icon, .steps .done::before, .steps li.done a .step-icon, .steps li.done a:active .step-icon {
        background: <?php echo $color;?> !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: <?php echo $color;?>!important;
    }
    .view_page_header_bg {
        background: <?php echo $color;?>!important;
    }
    .card_heading {
        background-color: <?php echo $color;?> !important;
    }
    .qr_main_div {
        background: <?php echo $color;?> !important;
    }
    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:focus {
        color: <?php echo $color;?> !important;
        border-bottom-color: <?php echo $color;?> !important;
    }
    .class_border_div {
        border-left: 5px solid <?php echo $color;?> !important;
    }
    #sidebar li .submenu li span:hover {
        color: <?php echo $color;?>;
    }
    .smgt_download_btn a{
        background-color: <?php echo $color;?> !important;
    }
    .save_att_btn{
        background-color: <?php echo $color;?> !important;
    }
    .add_btn {
        background-color: <?php echo $color;?> !important;
        background: <?php echo $color;?>;
    }
    .invoice_table_grand_total{
        background-color: <?php echo $color;?> !important;
    }
    .btn-place a.dt-button{
        border: 1px solid <?php echo $color;?>!important;
        background-color: <?php echo $color;?>!important;
    }
    .btn-place button.dt-button{
        border: 1px solid <?php echo $color;?>!important;
        background-color: <?php echo $color;?>!important; 
    }
    .att_download_csv_btn{
        background: <?php echo $color;?>!important;
    }
    .smgt_inbox_tab span.smgt_inbox_count_number{
        background-color: <?php echo $color;?> !important;
    }
    .main_email_template .smgt_accordion div.accordion-item{
        border-left: 5px solid <?php echo $color;?> !important;
    }
    .main_email_template .accordion-button.bg-gray{
        background-color: <?php echo $color;?>;
    }
    #message {
        border-left: 4px solid <?php echo $color;?> !important;
    }
    .smgt-navigation li .active {
        background-color: #F9FDFF !important;
        color: #5B5D6E;
    }

    .smgt-navigation li a:hover,
    .smgt-navigation li .smgt-droparrow:hover+a {
        background-color: #F9FDFF !important;
        color: #5B5D6E;
    }
    #sidebar .dropdown-menu li a {
        padding: 12px;
        text-decoration: none;
        background: #F2F5FA !important;
        font-style: normal;
        font-weight: normal;
        font-size: 15px;
        line-height: 22px;
        display: flex;
        align-items: center;
        color: #5B5D6E;
    } */


/* ========================================================================== */

    .cmgt-frontend-navigation li a, .cmgt-navigation li a {
        background-color: <?php echo $color;?> !important;
    }
    .cmgt-header .cmgt-logo {
        background-color: <?php echo $color;?> !important;
    }
    #main_sidebar-bgcolor {
        background-color: <?php echo $color;?> !important;
        background:<?php echo $color;?> !important;
    }
    .user_form input.btn {
        background: <?php echo $color;?>!important;
    }
    .table-responsive .dataTables_wrapper .dataTables_paginate .paginate_button.current{
        background: <?php echo $color;?>!important;
    }
    .btn-success{
        background-color: <?php echo $color;?> !important;
    }
    .view_pateint_header_bg{
        background: <?php echo $color;?>!important;
    }
    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:focus {
        color: <?php echo $color;?> !important;
        border-bottom-color: <?php echo $color;?> !important;
    }
    .invoice_lable{
        background-color: <?php echo $color;?> !important;
    }
    .grand_total_div , .grand_total_lable, .grand_total_amount{
        background-color: <?php echo $color;?> !important;
    }
    .print-btn{
        background-color: <?php echo $color;?> !important;
    }
    .cmgt-navigation li a:hover, .cmgt-navigation li .cmgt-droparrow:hover+a{
        background-color: #F9FDFF !important;
        color: #5B5D6E;
    }
    #navbarNav li .active {
        background-color: #F9FDFF !important;
    }
    #sidebar .dropdown-menu li a{
        background: #F2F5FA !important;
    }
    #sidebar .dropdown-menu li a:hover {
        color: <?php echo $color;?> !important;
    }
    .nav-tab-wrapper .nav-tab-active, .nav-tab-active:hover, a.nav-tab:hover, a.nav-tab:focus{
        color: <?php echo $color;?> !important;
    }
    .btn-place .dt-button.buttons-csv.buttons-html5, .btn-place .dt-button.buttons-print{
        background-color: <?php echo $color;?> !important;
    }
    .badge.badge-success{
        background: <?php echo $color;?>!important;
    }
    .main_home_page_div .accordion-button:not(.collapsed)
    {
        background-color: <?php echo $color;?> !important;
    }
    #accordionExample .accordion-item{
        border-left: 4px solid <?php echo $color;?> !important;
    }
    .gnrl_setting_image_background {
        background: <?php echo $color;?> !important;
    }
    .upload_image_btn {
        background-color: <?php echo $color;?> !important;
        border-color: <?php echo $color;?> !important;
    }
    .notice-success, div.updated{
        border-left-color : <?php echo $color;?> !important;
    }
    .invoice_color, .invoice_lable{
        background-color: <?php echo $color;?> !important;
    }
    .cmgt_dashboard_btn a.cmgt_no_data_btn_color{
        background-color: <?php echo $color;?> !important;
    }
</style>
