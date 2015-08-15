<?php 
/**
 * @author 		: Saravana Kumar K
 * @copyright	: sarkware.com
 * @todo		: One of the core module, which renders the actual wccpf fields to the product page.
 * 
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_product_form {
	
	function __construct() {
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'inject_wccpf' ) );
		add_action( 'woocommerce_add_to_cart_validation', array( $this, 'validate_wccpf' ), 10, 3 );
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'save_wccpf_data' ), 10, 2 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'render_wccpf_data' ), 1, 2 );		
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'save_wccpf_order_meta' ), 1, 3 );
	}
	
	function inject_wccpf() {
		Global $product;
		$is_datepicker_there = false;
		$is_colorpicker_there = false;
		$all_fields = apply_filters( 'wccpf/load/all_fields', $product->id );
		
		foreach ( $all_fields as $fields ) {
			foreach ( $fields as $key => $field ) {
				/* generate html for wccpf fields */
				$html = apply_filters( 'wccpf/render_product_field/type='.$field["type"], $field );
				/* Allow third party apps logic to render wccpf fields with their own wish */
				if( has_filter( 'wccpf/before/fields/rendering' ) ) {
					$html = apply_filters( 'wccpf/before/fields/rendering', $field, $html );
				}
				echo $html;
				
				if( $field["type"] == "datepicker" ) {
					$is_datepicker_there = true;
				}
				
				if( $field["type"] == "colorpicker" ) {
					$is_colorpicker_there = true;
				}				
			}
		}

		$this->wccpf_front_end_enqueue_scripts( $is_datepicker_there, $is_colorpicker_there );
	}
	
	/**
	 * 
	 * @param 	BOOLEAN	 $unknown
	 * @param 	INT		 $pid
	 * @param 	INT		 $quantity
	 * @todo	There is an unsolved issue, when grouped products are validated ( There won't be $pid ).
	 */
	function validate_wccpf( $unknown, $pid = null, $quantity ) {		
		if( isset( $pid ) ) {
			$is_passed = true;			
			$all_fields = apply_filters( 'wccpf/load/all_fields', $pid );
			foreach ( $all_fields as $fields ) {
				foreach ( $fields as $field ) {
					$val = $_REQUEST[ $field["name"] ];	
					if( $field["type"] != "file" ) {				
						if( $field["required"] == "yes" ) {			
							$res = apply_filters( 'wccpf/validate/type='.$field["type"], $val );							
						}
					} else {						
						$res = apply_filters( 'wccpf/upload/validate', $_FILES[ $field["name"] ], $field['filetypes'], $field["required"] );												
					}
					if( $res == 0 ) {
						$is_passed = false;
						wc_add_notice( $field["message"], 'error' );
					}
				}
			}
			return $is_passed;			
		} else {
			return true;
		}
	}
	
	function save_wccpf_data( $cart_item_data, $product_id ) {
		$unique_cart_item_key = md5( microtime().rand() );
		$cart_item_data['wccpf_unique_key'] = $unique_cart_item_key;
		if( $product_id ) {
			$all_fields = apply_filters( 'wccpf/load/all_fields', $product_id );
			foreach ( $all_fields as $fields ) {
				foreach ( $fields as $field ) {					
					if( isset( $_REQUEST[ $field["name"] ] ) || isset( $_FILES[ $field["name"] ] ) ) {
						if( $field["type"] != "checkbox" && $field["type"] != "file" ) {
							WC()->session->set( $unique_cart_item_key.$field["name"], $_REQUEST[ $field["name"] ] );
						} else if( $field["type"] == "checkbox" ) {
							WC()->session->set( $unique_cart_item_key.$field["name"], implode( ", ", $_REQUEST[ $field["name"] ] ) );
						} else {									
							/* Handle the file upload */
							$res = apply_filters( 'wccpf/upload/type=file', $_FILES[ $field["name"] ] );
							if( !isset( $res['error'] ) ) {								
								WC()->session->set( $unique_cart_item_key.$field["name"], json_encode( $res ) );
								do_action( 'wccpf/uploaded/file', $res );
							} else {
								wc_add_wp_error_notices( $field["message"], 'error' );
							}												
						}
					}
				}
			}
		}		
		return $cart_item_data;
	}

	function render_wccpf_data( $cart_data, $cart_item = null ) {		
		$wccpf_items = array();
		/* Woo 2.4.2 updates */
		if( !empty( $cart_data ) ) {
			$wccpf_items = $cart_data;
		}
		if( isset( $cart_item['product_id'] ) && isset( $cart_item['wccpf_unique_key'] ) ) {			
			$all_fields = apply_filters( 'wccpf/load/all_fields', $cart_item['product_id'] );
			foreach ( $all_fields as $fields ) {
				foreach ( $fields as $field ) {
					if( WC()->session->__isset( $cart_item['wccpf_unique_key'].$field["name"] ) && trim( WC()->session->get( $cart_item['wccpf_unique_key'].$field["name"] ) ) ) {
						if( $field["type"] == "file" ) {
							$fobj = json_decode( WC()->session->get( $cart_item['wccpf_unique_key'].$field["name"] ), true );
							$path_parts = pathinfo( $fobj['file'] );
							$wccpf_items[] = array( "name" => $field["label"], "value" => $path_parts["basename"] );
						} else {
							$wccpf_items[] = array( "name" => $field["label"], "value" => WC()->session->get( $cart_item['wccpf_unique_key'].$field["name"] ) );
						}
					}					
				}
			}			
		}		
		return $wccpf_items;
	}
	
	function save_wccpf_order_meta( $item_id, $values, $cart_item_key ) {
		if( isset($values["product_id"] ) ) {
			$all_fields = apply_filters( 'wccpf/load/all_fields', $values["product_id"] );
			foreach ( $all_fields as $fields ) {
				foreach ( $fields as $field ) {
					if( WC()->session->__isset( $values['wccpf_unique_key'].$field["name"] ) && trim( WC()->session->get( $values['wccpf_unique_key'].$field["name"] ) ) ) {
						if( $field["type"] == "file" ) {
							$fobj = json_decode( WC()->session->get( $values['wccpf_unique_key'].$field["name"] ), true );
							wc_add_order_item_meta( $item_id, $field["label"], $fobj["url"] );
						} else {
							wc_add_order_item_meta( $item_id, $field["label"], WC()->session->get( $values['wccpf_unique_key'].$field["name"] ) );
						}						
					}
				}
			}
		}			
	}
	
	function wccpf_front_end_enqueue_scripts( $is_datepicker_there, $is_colorpicker_there ) {
		if( is_shop() || is_product() ) {			
			wp_register_style( 'wccpf-font-end-style', wccpf()->settings['dir'] . 'css/wccpf-front-end.css' );
			wp_enqueue_style( array( 'wccpf-font-end-style' ) );			
			
			if( $is_datepicker_there ) {
				wp_enqueue_style( 'wccpf-jquery-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-datepicker' );
			}
			
			if( $is_colorpicker_there ) {
				wp_register_script( 'wccpf-color-picker', wccpf()->settings['dir'] . 'js/jqColorPicker.js' );
				wp_register_script( 'wccpf-colors', wccpf()->settings['dir'] . 'js/colors.js' );
				wp_enqueue_script( 'wccpf-colors' );
				wp_enqueue_script( 'wccpf-color-picker' );
			}			
		}
	}
} 

new wccpf_product_form();

?>