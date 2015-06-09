<?php
/*
Plugin Name: WC Fields Factory
Plugin URI: http://sarkware.com/wc-fields-factory-a-wordpress-plugin-to-add-custom-fields-to-woocommerce-product-page/
Description: It allows you to add custom fields to your woocommerce product page. You can add custom fields and validations without tweaking any of your theme's code & templates, It also allows you to group the fields and add them to particular products or for particular product categories. Supported field types are text, numbers, email, textarea, checkbox, radio and select.
Version: 1.1.5
Author: Saravana Kumar K
Author URI: http://www.iamsark.com/
License: GPL
Copyright: sarkware
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

if( !class_exists('wccpf') ):

class wccpf {
	
	var $settings,
	$request,
	$response;
	
	public function __construct() {

		$this->settings = array(
			'path'				=> plugin_dir_path( __FILE__ ),
			'dir'				=> plugin_dir_url( __FILE__ ),
			'version'			=> '1.1.5'
		);
		
		add_action( 'init', array( $this, 'init' ), 1 );
		add_filter( 'wccpf/get_info', array( $this, 'wccpf_get_info' ), 1, 1 );
		
		$this->wccpf_includes();
		
	}
	
	function admin_menu() {
		$admin = add_menu_page( 
			"WC Fields Factory", 
			"Fields Factory", 
			'manage_options', 
			'edit.php?post_type=wccpf', 
			false,
			null
		);
		add_submenu_page(
			'edit.php?post_type=wccpf',
			"Add WC Product Fields",
			"Create Fields",
			"manage_options",
			'post-new.php?post_type=wccpf'
		);
	}
	
	function init() {	
		$labels = array (
			'name' => 'WC Product&nbsp;Field&nbsp;Groups',
			'singular_name' => 'WC Product Custom Fields',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New WC Product Field Group',
			'edit_item' => 'Edit WC Product Field Group',
			'new_item' =>  'New WC Product Field Group',
			'view_item' => 'View Product Field Group',
			'search_items' => 'Search WC Product Field Groups',
			'not_found' =>  'No WC Product Field Groups found',
			'not_found_in_trash' => 'No WC Product Field Groups found in Trash',
		);
	
		register_post_type(
			'wccpf', 
			array (
				'labels' => $labels,
				'public' => false,
				'show_ui' => true,
				'_builtin' =>  false,
				'capability_type' => 'page',
				'hierarchical' => true,
				'rewrite' => false,
				'query_var' => "wccpf",
				'supports' => array( 'title' ),
				'show_in_menu'	=> false
			)
		);	

		wp_register_script( 'wccpf-script', $this->settings['dir'] . "js/wccpf.js", 'jquery', $this->settings['version'] );	
		wp_register_style( 'wccpf-style', $this->settings['dir'] . 'css/wccpf.css' );
		
		if( is_admin() ) {
			add_action('admin_menu', array($this,'admin_menu'));
		}
	}
	
	function wccpf_get_info( $i ) {
		$return = false;	
	
		if( isset($this->settings[ $i ]) ) {
			$return = $this->settings[ $i ];
		}
		
		if( $i == 'all' ) {
			$return = $this->settings;
		}	
	
		return $return;
	}
	
	function wccpf_includes() {				
		
		include_once('classes/misc/wccpf-request.php');
		include_once('classes/misc/wccpf-response.php');
		include_once('classes/dao.php');
		include_once('classes/builder.php');
		include_once('classes/listener.php');
		include_once('classes/admin-form.php');
		include_once('classes/product-form.php');
		
		include_once('classes/fields/fields.php');
		include_once('classes/fields/text.php');
		include_once('classes/fields/number.php');
		include_once('classes/fields/email.php');
		include_once('classes/fields/textarea.php');			
		include_once('classes/fields/checkbox.php');			
		include_once('classes/fields/radio.php');
		include_once('classes/fields/select.php');
		include_once('classes/fields/datepicker.php');
		include_once('classes/fields/colorpicker.php');
		
	}
	
}


function wccpf() {
	
	global $wccpf;
	
	if( !isset( $wccpf ) ) {
		$wccpf = new wccpf();
	}
	
	return $wccpf;
	
}

wccpf();

endif;

?>