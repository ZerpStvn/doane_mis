<?php  
/*
Plugin Name: Church Management System
Plugin URI: https://mojoomla.com/
Description: Church Management System for wordpress plugin is ideal way to manage complete Church operation. It has different user roles like Member, Volunteer, Accountant and Admin users. 
Version: 2.1.0 (06-04-2024)
Author: Mojoomla
Author URI: https://codecanyon.net/search/mojoomla
Text Domain: church_mgt
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copyright 2015  Mojoomla  (email : sales@mojoomla.com)
*/
define( 'CMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( 'CMS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

define( 'CMS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

define( 'CMS_CONTENT_URL',  content_url( ));

if (!defined('CMS_PLUGIN_VERSION'))
{
	define('CMS_PLUGIN_VERSION', '5.8.2'); //Plugin version number
}
require_once CMS_PLUGIN_DIR . '/settings.php'; 
?>