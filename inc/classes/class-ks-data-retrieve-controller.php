<?php
/**
 * This is the main class for this plugin.
 */
namespace KsDataRetrieve\Lib;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to access this page directly.' );
}

use KsDataRetrieve\Helper\Ks_Data_Retrieve_Helper;

class Ks_Data_Retrieve_Controller {

	private $endpoints; // collection for endpoints
	private $shortcodes; // collection for shortcodes
	private $pages; // collection for pages

	public function __construct() {
		$this->endpoints = array();
		$this->shortcodes = array();
		$this->pages = array();
	}

	public function add_endpoint( $endpoint )	{
		array_push( $this->endpoints, $endpoint );
	}

	private function register_endpoints()	{
		if ( count( $this->endpoints ) ) {
			foreach ( $this->endpoints as $endpoint ) {
				$endpoint->init();
			}
		}
	}

	public function add_shortcode( $shortcode )	{
		array_push( $this->shortcodes, $shortcode );
	}

	private function register_shortcodes()	{
		if ( count( $this->shortcodes ) ) {
			foreach ( $this->shortcodes as $shortcode ) {
				$shortcode->init();
			}
		}
	}

	public function add_page( $page )	{
		array_push( $this->pages, $page );
	}

	private function register_pages()	{
		if ( count( $this->pages ) ) {
			foreach ( $this->pages as $page ) {
				$page->init();
			}
		}
	}

	public function load_assets( $atts, $content = null ) {
		if ( !is_admin() ) {
			wp_enqueue_style( 'ks_data_style' );

			if ( !wp_script_is( 'jquery', 'enqueued' ) ) {
				wp_enqueue_script( 'jquery' );
			}

			wp_enqueue_script( 'ks_data_script' );
		}
	}

	public function load_admin_assets( $atts, $content = null ) {
		if ( is_admin() ) {
			wp_enqueue_style( 'ks_data_style_admin' );
		}
	}

	public function init() {
		// load javascript & css files
		if ( !is_admin() ) {
			wp_register_style( 
				'ks_data_style', 
				Ks_Data_Retrieve_Helper::plugin_url() . '/assets/css/style.css', 
				array(), 
				Ks_Data_Retrieve_Helper::plugin_version() 
			);

			wp_register_script( 
				'ks_data_script', 
				Ks_Data_Retrieve_Helper::plugin_url() . '/assets/js/script.js', 
				array( 'jquery' ), 
				Ks_Data_Retrieve_Helper::plugin_version(), 
				true 
			);

			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );

		} else {
			wp_register_style( 
				'ks_data_style_admin', 
				Ks_Data_Retrieve_Helper::plugin_url() . '/assets/css/style_admin.css', 
				array(), 
				Ks_Data_Retrieve_Helper::plugin_version() 
			);

			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) );
		}

		// Register all ajax endpoints
		$this->register_endpoints();

		// Register all shortcodes
		$this->register_shortcodes();

		// Register all pages
		$this->register_pages();
	}
}