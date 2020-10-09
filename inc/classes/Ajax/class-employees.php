<?php
/**
 * This is the class responsible for the 'ks_employees' ajax endpoint.
 */
namespace KsDataRetrieve\Lib\Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to access this page directly.' );
}

use KsDataRetrieve\Helper\Ks_Data_Retrieve_Helper;
 
class Employees {

	public function init() {
		add_action( 'wp_ajax_ks_employees', array( $this, 'register' ) );
		add_action( 'wp_ajax_nopriv_ks_employees', array( $this, 'register' ) );
	}

	public function register( $atts, $content = null ) {
		$data_key = Ks_Data_Retrieve_Helper::get_data_key();
		$data_time_key = Ks_Data_Retrieve_Helper::get_data_time_key();
		$data_expiry_time = Ks_Data_Retrieve_Helper::get_data_expiry();

		// get data from wp_options table
		$old_data = get_option( $data_key );
		$last_req_time = get_option( $data_time_key );

		if ( ! $old_data ) {
			$ks_force_request = true;

		} else {
			// check if data is expired
			$current_time = time(); // get timestamp
			if ( (int) $current_time - (int) $last_req_time > $data_expiry_time ) { // passed 12 hours => green light for api call.
				// clear data
				Ks_Data_Retrieve_Helper::clear_employee_data();

				$ks_force_request = true;
			}			
		}

		if ( $ks_force_request ) {
			$data = array();
			
			$api_url = Ks_Data_Retrieve_Helper::get_apiurl();

			$data = file_get_contents( $api_url );
			$data = json_decode( $data );

			if ( $data && $data->status = 'success' ) {
				// successfully received data

				// store received customers data and current time into wp_options table
				if ( ! $old_data )
					add_option( $data_key, json_encode( $data ) );
				else
					update_option( $data_key, json_encode( $data ) );

				if (! $last_req_time )
					add_option( $data_time_key, time() );
				else
					update_option( $data_time_key, time() );

			} else {
				$data = array (
					'status' => 'error',
					'message' => __e('Something went wrong while getting data. Please contact support.', 'ks-employees')
				);
			}
		} else {
			$data = json_decode( $old_data );
		}

		header( 'HTTP/1.1 200 OK' );
		header( 'Content-Type: application/json' );
		echo json_encode( $data );

		wp_die();
	}
}