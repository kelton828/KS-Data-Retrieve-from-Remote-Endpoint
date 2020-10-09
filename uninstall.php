<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { // If uninstall not called from WordPress exit
	exit();
}

/**
 * Manages KS Data Retrieve from Endpoint uninstallation
 * The goal is to remove data in cache
 *
 */
class KsData_Uninstaller {

	/**
	 * Constructor: manages uninstall
	 *
	 */
	function __construct() {
		$this->uninstall();
	}

	/**
	 * Removes ALL plugin data
	 *
	 */
	function uninstall() {
		delete_option( 'ks_employees' );
		delete_option( 'ks_request_time' );
	}
}
new KsData_Uninstaller();
