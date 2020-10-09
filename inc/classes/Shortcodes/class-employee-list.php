<?php
/**
 * This is the class responsible for the 'ks_employees_list' shortcode.
 */
namespace KsDataRetrieve\Lib\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to access this page directly.' );
}

use KsDataRetrieve\View\FrontEnd\Employees_List_Table;

class Employee_List {

	public function init()	{
		add_shortcode( 'ks_employees_list', array( $this, 'register' ) );
	}

	public function register( $atts, $content = null ) {
		return Employees_List_Table::get_employee_list_content();
	}
}