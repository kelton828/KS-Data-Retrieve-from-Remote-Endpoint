<?php
namespace KsDataRetrieve\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to access this page directly.' );
}

class Ks_Data_Retrieve_Helper {
	public static $version = '1.0';
	public static $api_url = 'https://dummy.restapiexample.com/api/v1/employees/';
	public static $data_expiry = '43200'; // 12 hours
	public static $data_key = 'ks_employees';
	public static $data_time_key = 'ks_request_time';
	public static $default_currency = '$';

	public static function plugin_version() {
		return self::$version;
	}

	public static function plugin_path() {
		return dirname( dirname( dirname( __FILE__ ) ) );
	}

	public static function plugin_file() {
		return dirname( dirname( dirname( __FILE__ ) ) ) . '/ks-data-retrieve.php';
	}

	public static function plugin_folder() {
		return basename( self::plugin_path() );
	}

	public static function plugin_url() {
		return plugins_url( '', self::plugin_path() . '/ks-data-retrieve.php' );
	}	

	public static function get_apiurl() {
		return self::$api_url;
	}

	public static function get_data_expiry() {
		return self::$data_expiry;
	}

	public static function get_data_key() {
		return self::$data_key;
	}

	public static function get_data_time_key() {
		return self::$data_time_key;
	}

	public static function get_default_currency() {
		return self::$default_currency;
	}

	public static function clear_employee_data() {
		delete_option( self::$data_key );
		delete_option( self::$data_time_key );		
	}
}