<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { // If uninstall not called from WordPress exit
	exit();
}

use KsDataRetrieve\Helper\KsDataRetrieveHelper;

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
		// clear data
		KsDataRetrieveHelper::clear_employee_data();
	}
}		
new KsData_Uninstaller();