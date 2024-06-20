<?php  
/*
Plugin Name: Doane Church Management
Plugin URI: https://doane.com
Description: "Church Management For Doane". 
Version: 2.1.0 (06-04-2024)
Author: "The Thesis Group
Author URI: ""
Text Domain: church_mgt
Domain Path: /languages/
License: Github GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copyright 2024  Thesis  (email : doane@gmail.com)
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
