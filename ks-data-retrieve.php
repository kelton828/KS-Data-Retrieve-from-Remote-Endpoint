<?php
/**
 * Plugin Name: KS Data Retrieve from Remote Endpoint
 * Plugin URI: https://example.com/ktusers
 * Description: Plugin to retrieves data from a remote endpoint and makes that data accessible via AJAX on the WordPress frontend using a shortcode
 * Version: 1.0
 * Author: Kelton Smith
 * Author URI: https://example.com
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to access this page directly.' );
}
 
define( 'BASE_PATH', plugin_dir_path(__FILE__) );
define( 'BASE_URL', plugin_dir_url(__FILE__) );

// include the Composer autoload file
require BASE_PATH . 'vendor/autoload.php';

use KsDataRetrieve\Lib\Ajax;
use KsDataRetrieve\Lib\Shortcodes;
use KsDataRetrieve\Lib\Ks_Data_Retrieve_Controller;
use KsDataRetrieve\View\Admin;

$endpoint = new Ajax\Employees();
$shortcode = new Shortcodes\Employee_List();

$admin_page = new Admin\Admin_Dashboard();

$plugin_controller = new Ks_Data_Retrieve_Controller();
$plugin_controller->add_endpoint( $endpoint );// initialise the plugin
$plugin_controller->add_shortcode( $shortcode );// initialise the plugin
$plugin_controller->add_page( $admin_page );
$plugin_controller->init();