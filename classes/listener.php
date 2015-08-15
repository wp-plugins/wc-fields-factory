<?php 
/**
 * @author		: Saravana Kumar K
 * @copyright	: sarkware.com  
 * @todo		: Wccpf core Ajax handler. common hub for all ajax related actions of wccpf
 *  
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_ajax {
	
	function __construct() {		
		add_action("wp_ajax_wccpf_ajax", array( $this, "listen" ) );		
	}
	
	function listen() {
		/* Parse the incoming request */
		wccpf()->request = apply_filters( 'wccpf/request', array() );		
		/* Handle the request */
		$this->handleRequest();
		/* Respond the request */
		echo wccpf()->response;
		/* end the request - response cycle */
		die();
	}
	
	function handleRequest() {
				
		if( wccpf()->request["context"] == "product" ) {		
			if( wccpf()->request["type"] == "GET" ) {
				$products = apply_filters( 'wccpf/build/products_list', "wccpf_condition_value" );				
				wccpf()->response = apply_filters( 'wccpf/response', true, "Success", $products );	
			}			
		} else if( wccpf()->request["context"] == "product_cat" ) {
			if( wccpf()->request["type"] == "GET" ) {
				$products_cat = apply_filters( 'wccpf/build/products_cat_list', "wccpf_condition_value" );
				wccpf()->response = apply_filters( 'wccpf/response', true, "Success", $products_cat );
			}
		} else if( wccpf()->request["context"] == "wccpf_meta_fields" ) {
			if( wccpf()->request["type"] == "GET" ) {
				$fields = apply_filters( 'wccpf/render_admin_field/type='.wccpf()->request["payload"]["type"], array() );
				wccpf()->response = apply_filters( 'wccpf/response', true, "Success", $fields );
			}
		} else if( wccpf()->request["context"] == "wccpf_field_single" ) {
			
		} else {
			if( wccpf()->request["type"] == "GET" ) {				
				$res = apply_filters( 'wccpf/load/field', wccpf()->request["post"], wccpf()->request["payload"]["field_key"] );
				if( $res ) {
					wccpf()->response = apply_filters( 'wccpf/response', true, "Successfully Loaded", $res );
				} else {
					wccpf()->response = apply_filters( 'wccpf/response', false, "Failed to load wccpf meta", array() );
				}
			} else if( wccpf()->request["type"] == "POST" ) {
				$message = "";
				$fields = array();				
				$res = apply_filters( 'wccpf/save/field', wccpf()->request["post"], wccpf()->request["payload"] );				
				if( $res ) {
					$message = "Successfully Inserted";
					$fields = apply_filters( 'wccpf/load/fields', wccpf()->request["post"] );
					$fields = apply_filters( 'wccpf/build/fields', $fields );
				} else {
					$message = "Failed to create custom field";
				}
				wccpf()->response = apply_filters( 'wccpf/response', $res, $message, $fields );
			} else if( wccpf()->request["type"] == "PUT" ) {
				$message = "";
				$fields = array();
				$res = apply_filters( 'wccpf/update/field', wccpf()->request["post"], wccpf()->request["payload"] );
				if( $res ) {
					$message = "Successfully Updated";
					$fields = apply_filters( 'wccpf/load/fields', wccpf()->request["post"] );
					$fields = apply_filters( 'wccpf/build/fields', $fields );
				} else {
					$message = "Failed to update the custom field";
				}
				wccpf()->response = apply_filters( 'wccpf/response', $res, $message, $fields );
			} else {
				$message = "";		
				$fields = array();
				$res = apply_filters( 'wccpf/remove/field', wccpf()->request["post"], wccpf()->request["payload"]["field_key"] );
				if( $res ) {
					$message = "Successfully removed";
					$fields = apply_filters( 'wccpf/load/fields', wccpf()->request["post"] );
					$fields = apply_filters( 'wccpf/build/fields', $fields );
				} else {
					$message = "Failed to remove the custom field";
				}
				wccpf()->response = apply_filters( 'wccpf/response', $res, $message, $fields );
			}
		}
		
	}
	
}

/* Init wccpf ajax object */
new wccpf_ajax();

?>