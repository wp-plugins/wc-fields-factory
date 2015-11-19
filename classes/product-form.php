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
		$wccpf_options = get_option( 'wccpf_options' );
		$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
		$field_location = isset( $wccpf_options["field_location"] ) ? $wccpf_options["field_location"] : "woocommerce_before_add_to_cart_button";
		$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		$group_fields_on_cart = isset( $wccpf_options["group_fields_on_cart"] ) ? $wccpf_options["group_fields_on_cart"] : "no";
		
		add_action( $field_location, array( $this, 'inject_wccpf' ) );
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_wccpf' ), 1, 2 );
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'save_wccpf_data' ), 10, 2 );				
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'save_wccpf_order_meta' ), 1, 3 );
		
		if( $group_fields_on_cart == "yes" && $fields_cloning == "yes" ) {			
			add_filter( 'woocommerce_cart_item_name', array( $this, 'render_wccpf_data_on_cart' ), 1, 3 );			
			add_filter( 'woocommerce_checkout_cart_item_quantity', array( $this, 'render_wccpf_data_on_checkout' ), 1, 3 );									
		} else {
			add_filter( 'woocommerce_get_item_data', array( $this, 'render_wccpf_data' ), 1, 2 );
		}
	}
	
	function inject_wccpf() {
		Global $product;
		$is_datepicker_there = false;
		$is_colorpicker_there = false;
		
		$fields_group_title = "";
		$wccpf_options = get_option( 'wccpf_options' );
		$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
		$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		
		if( isset( $wccpf_options["fields_group_title"] ) && $wccpf_options["fields_group_title"] != "" ) {
			$fields_group_title = $wccpf_options["fields_group_title"];
		} else {
			$fields_group_title = "Additional Options : ";
		}
			
		$all_fields = apply_filters( 'wccpf/load/all_fields', $product->id );
		
		do_action( 'wccpf/before/fields/start' );
		
		if( $fields_cloning == "yes" ) {
			echo '<div id="wccpf-fields-container">';
			echo '<input type="hidden" id="wccpf_fields_clone_count" value="1" />';
			echo '<div class="wccpf-fields-group">';
			echo '<h4>'. $fields_group_title .' <span class="wccpf-fields-group-title-index">1</span></h4>';
		}
		
		foreach ( $all_fields as $fields ) {
			foreach ( $fields as $key => $field ) {
				/* generate html for wccpf fields */
				$html = apply_filters( 'wccpf/render_product_field/type='.$field["type"], $field );
				/* Allow third party apps logic to render wccpf fields with their own wish */
				if( has_filter( 'wccpf/before/fields/rendering' ) ) {
					$html = apply_filters( 'wccpf/before/fields/rendering', $field, $html );
				}
				
				do_action( 'wccpf/before/field/start', $field );
				
				echo $html;
				
				do_action( 'wccpf/after/field/end', $field );
				
				if( $field["type"] == "datepicker" ) {
					$is_datepicker_there = true;
				}
				
				if( $field["type"] == "colorpicker" ) {
					$is_colorpicker_there = true;
				}				
			}
		}
		
		if( $fields_cloning == "yes" ) {
			echo '</div>';
			echo '</div>';
		}
		
		do_action( 'wccpf/after/fields/end' );

		$this->wccpf_front_end_enqueue_scripts( $is_datepicker_there, $is_colorpicker_there );
	}
	
	/**
	 * 
	 * @param 	BOOLEAN	 $unknown
	 * @param 	INT		 $pid
	 * @param 	INT		 $quantity
	 * @todo	There is an unsolved issue, when grouped products are validated ( There won't be $pid ).
	 */
	function validate_wccpf( $passed, $pid = null ) {		
		if( isset( $pid ) ) {
			$is_passed = true;
			
			$wccpf_options = get_option( 'wccpf_options' );
			$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
			$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
			
			$all_fields = apply_filters( 'wccpf/load/all_fields', $pid );
			
			if( $fields_cloning == "no" ) {
				foreach ( $all_fields as $fields ) {
					foreach ( $fields as $field ) {
						$res = true;
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
			} else  {
				if( isset( $_REQUEST["quantity"] ) ) {
					$pcount = intval( $_REQUEST["quantity"] );
					foreach ( $all_fields as $fields ) {
						foreach ( $fields as $field ) {
							for( $i = 1; $i <= $pcount; $i++ ) {
								$res = true;								
								$val = $_REQUEST[ $field["name"] . "_" . $i ];																
								if( $field["type"] != "file" ) {
									if( $field["required"] == "yes" ) {
										$res = apply_filters( 'wccpf/validate/type='.$field["type"], $val );
									}
								} else {
									$res = apply_filters( 'wccpf/upload/validate', $_FILES[ $field["name"] . "_" . $i ], $field['filetypes'], $field["required"] );
								}
								if( $res == 0 ) {
									$is_passed = false;
									wc_add_notice( $field["message"], 'error' );
								}	
							}							
						}
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
			$val = "";
			$wccpf_options = get_option( 'wccpf_options' );
			$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
			$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
			
			$all_fields = apply_filters( 'wccpf/load/all_fields', $product_id );
			
			if( $fields_cloning == "no" ) {
				foreach ( $all_fields as $fields ) {
					foreach ( $fields as $field ) {						
						if( isset( $_REQUEST[ $field["name"] ] ) || isset( $_FILES[ $field["name"] ] ) ) {
							if( $field["type"] != "checkbox" && $field["type"] != "file" ) {							
								
								$cart_item_data[ "wccpf_" . $field["name"] ] = $_REQUEST[ $field["name"] ];								
							} else if( $field["type"] == "checkbox" ) {
								$cart_item_data[ "wccpf_" . $field["name"] ] = implode( ", ", $_REQUEST[ $field["name"] ] );								
							} else {
								/* Handle the file upload */
								$res = apply_filters( 'wccpf/upload/type=file', $_FILES[ $field["name"] ] );
								if( !isset( $res['error'] ) ) {
									$cart_item_data[ "wccpf_" . $field["name"] ] = json_encode( $res );									
									do_action( 'wccpf/uploaded/file', $res );
								} else {
									wc_add_wp_error_notices( $field["message"], 'error' );
								}
							}
						}						
					}
				}
			} else {
				if( isset( $_REQUEST["quantity"] ) ) {
					$pcount = intval( $_REQUEST["quantity"] );
					foreach ( $all_fields as $fields ) {
						foreach ( $fields as $field ) {
							for( $i = 1; $i <= $pcount; $i++ ) {
								if( isset( $_REQUEST[ $field["name"] . "_" . $i ] ) || isset( $_REQUEST[ $field["name"] . "_" . $i . "[]" ] ) || isset( $_FILES[ $field["name"] . "_" . $i ] ) ) {
									if( $field["type"] != "checkbox" && $field["type"] != "file" ) {
										$cart_item_data[ "wccpf_" . $field["name"] . "_" . $i ] = $_REQUEST[ $field["name"] . "_" . $i ];									
									} else if( $field["type"] == "checkbox" ) {
										$cart_item_data[ "wccpf_" . $field["name"] . "_" . $i ] = implode( ", ", $_REQUEST[ $field["name"] . "_" . $i ] );										
									} else {
										/* Handle the file upload */
										$res = apply_filters( 'wccpf/upload/type=file', $_FILES[ $field["name"] . "_" . $i ] );
										if( !isset( $res['error'] ) ) {
											$cart_item_data[ "wccpf_" . $field["name"] . "_" . $i ] = json_encode( $res );											
											do_action( 'wccpf/uploaded/file', $res );
										} else {
											wc_add_wp_error_notices( $field["message"], 'error' );
										}
									}
								}
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
			
			$wccpf_options = get_option( 'wccpf_options' );
			$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
			$show_custom_data = isset( $wccpf_options["show_custom_data"] ) ? $wccpf_options["show_custom_data"] : "yes";
			$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
			$group_meta_on_cart = isset( $wccpf_options["group_meta_on_cart"] ) ? $wccpf_options["group_meta_on_cart"] : "no";
									
			$all_fields = apply_filters( 'wccpf/load/all_fields', $cart_item['product_id'] );
			
			if( $show_custom_data == "yes" ) {
				if( $fields_cloning == "no" ) {
					foreach ( $all_fields as $fields ) {
						foreach ( $fields as $field ) {							
							$field["visibility"] = isset( $field["visibility"] ) ? $field["visibility"] : "yes";
							if( $field["visibility"] == "yes" ) {
								if( $cart_item['wccpf_'. $field["name"] ] && trim( $cart_item['wccpf_'. $field["name"] ] ) ) {
									if( $field["type"] == "file" ) {
										$fobj = json_decode( $cart_item['wccpf_'. $field["name"] ], true );										
										$path_parts = pathinfo( $fobj['file'] );
										$wccpf_items[] = array( "name" => $field["label"], "value" => $path_parts["basename"] );
									} else {
										$wccpf_items[] = array( "name" => $field["label"], "value" => $cart_item['wccpf_'. $field["name"] ] );
									}
								}	
							}							
						}
					}
				} else {
					if( isset( $cart_item["quantity"] ) ) {
						$pcount = intval( $cart_item["quantity"] );
						foreach ( $all_fields as $fields ) {							
							if( $group_meta_on_cart == "yes" ) {								
								foreach ( $fields as $field ) {
									for( $i = 1; $i <= $pcount; $i++ ) {
										$field["visibility"] = isset( $field["visibility"] ) ? $field["visibility"] : "yes";
										if( $field["visibility"] == "yes" ) {
											if( $cart_item['wccpf_'. $field["name"] . "_" . $i ] && trim( $cart_item['wccpf_'. $field["name"] . "_" . $i ] ) ) {
												if( $field["type"] == "file" ) {
													$fobj = json_decode( $cart_item['wccpf_'. $field["name"] . "_" . $i ], true );
													$path_parts = pathinfo( $fobj['file'] );
													$wccpf_items[] = array( "name" => $field["label"] . " - " . $i, "value" => $path_parts["basename"] );
												} else {
													$wccpf_items[] = array( "name" => $field["label"] . " - " . $i, "value" => $cart_item['wccpf_'. $field["name"] . "_" . $i ] );
												}
											}
										}
									}
								}
							} else {
								for( $i = 1; $i <= $pcount; $i++ ) {
									foreach ( $fields as $field ) {
										$field["visibility"] = isset( $field["visibility"] ) ? $field["visibility"] : "yes";
										if( $field["visibility"] == "yes" ) {
											if( $cart_item['wccpf_'. $field["name"] . "_" . $i ] && trim( $cart_item['wccpf_'. $field["name"] . "_" . $i ] ) ) {
												if( $field["type"] == "file" ) {
													$fobj = json_decode( $cart_item['wccpf_'. $field["name"] . "_" . $i ], true );
													$path_parts = pathinfo( $fobj['file'] );
													$wccpf_items[] = array( "name" => $field["label"] . " - " . $i, "value" => $path_parts["basename"] );
												} else {
													$wccpf_items[] = array( "name" => $field["label"] . " - " . $i, "value" => $cart_item['wccpf_'. $field["name"] . "_" . $i ] );
												}
											}
										}
									}
								}	
							}						
						}
					}
				}
			}						
		}		
		return $wccpf_items;
	}
	
	function render_wccpf_data_on_cart( $title = null, $cart_item = null, $cart_item_key = null ) {
		if( is_cart() ) {
			return $this->render_wccpf_cloning_fields_data( $title, $cart_item, false );
		}
		return $title;
	}
	
	function render_wccpf_data_on_checkout( $quantity = null, $cart_item = null, $cart_item_key = null ) {		
		return $this->render_wccpf_cloning_fields_data( $quantity, $cart_item, true );				
	}
	
	function render_wccpf_cloning_fields_data( $html = "", $cart_item = null, $is_review_table ) {		
		$meta_html = "";
		$wccpf_options = get_option( 'wccpf_options' );
		$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
		$show_custom_data = isset( $wccpf_options["show_custom_data"] ) ? $wccpf_options["show_custom_data"] : "yes";
		$group_meta_on_cart = isset( $wccpf_options["group_meta_on_cart"] ) ? $wccpf_options["group_meta_on_cart"] : "no";		
		
		if( $show_custom_data == "no" ) {			
			return $html;
		}
		
		$fields_group_title = "";	
		if( isset( $wccpf_options["fields_group_title"] ) && $wccpf_options["fields_group_title"] != "" ) {
			$fields_group_title = $wccpf_options["fields_group_title"];
		} else {
			$fields_group_title = "Addiotnal Options : ";
		}
		
		if( isset( $cart_item['product_id'] ) && isset( $cart_item['wccpf_unique_key'] ) ) {	
			
			$all_fields = apply_filters( 'wccpf/load/all_fields', $cart_item['product_id'] );
			
			if( isset( $cart_item["quantity"] ) ) {
				
				$meta_html .= '<div class="wccpf-fields-group-on-cart">';
				
				$pcount = intval( $cart_item["quantity"] );
				foreach ( $all_fields as $fields ) {					
					for( $i = 1; $i <= $pcount; $i++ ) {
						
						$meta_html .= '<fieldset>';						
						$meta_html .= '<h5>'. esc_html( $fields_group_title ) . $i .'</h5>';
						
						foreach ( $fields as $field ) {
							$field["visibility"] = isset( $field["visibility"] ) ? $field["visibility"] : "yes";
							if( $field["visibility"] == "yes" ) {
								if( $cart_item['wccpf_'. $field["name"] . "_" . $i ] && trim( $cart_item['wccpf_'. $field["name"] . "_" . $i ] ) ) {
									$meta_html .= '<ul>';
									$meta_html .= '<li>'. $field["label"] .'</li>';
									
									if( $field["type"] == "file" ) {
										
										$fobj = json_decode( $cart_item['wccpf_'. $field["name"] . "_" . $i ], true );
										$path_parts = pathinfo( $fobj['file'] );
																				
										$meta_html .= '<li>'. wp_kses_post( $path_parts["basename"] ) .'</li>';									
										
									} else {
										$meta_html .= '<li>'. wp_kses_post( wpautop( $cart_item['wccpf_'. $field["name"] . "_" . $i ] ) ) .'</li>';										
									}
									
									$meta_html .= '</ul>';
									
								}
							}
						}						
						$meta_html .= '</fieldset>';
					}
				}				
				$meta_html .= '</div>';				
			}
		}
		
		$this->wccpf_front_end_enqueue_scripts( false, false );
		
		if( $is_review_table ) {
			$html = $html . $meta_html;
		} else {
			$html = $html . $meta_html ;
		}
		
		return $html;
	}
	
	function save_wccpf_order_meta( $item_id, $values, $cart_item_key ) {
		if( isset($values["product_id"] ) ) {
			
			$wccpf_options = get_option( 'wccpf_options' );
			$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
			$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
			
			$all_fields = apply_filters( 'wccpf/load/all_fields', $values["product_id"] );
			
			if( $fields_cloning == "no" ) {
				foreach ( $all_fields as $fields ) {
					foreach ( $fields as $field ) {
						if( isset( $values[ 'wccpf_' . $field["name"] ] ) && trim( $values['wccpf_'. $field["name"] ] ) ) {
							if( $field["type"] == "file" ) {
								$fobj = json_decode( $values[ 'wccpf_' . $field["name"] ], true );
								wc_add_order_item_meta( $item_id, $field["label"], $fobj["url"] );
							} else {
								wc_add_order_item_meta( $item_id, $field["label"], $values[ 'wccpf_' . $field["name"] ] );
							}
						}						
					}
				}	
			} else {
				if( isset( $values["quantity"] ) ) {
					$pcount = intval( $values["quantity"] );
					foreach ( $all_fields as $fields ) {
						for( $i = 1; $i <= $pcount; $i++ ) {
							foreach ( $fields as $field ) {
								if( isset( $values[ 'wccpf_' . $field["name"] . "_" . $i ] ) && trim( $values['wccpf_'. $field["name"] . "_" . $i ] ) ) {
									if( $field["type"] == "file" ) {
										$fobj = json_decode( $values[ 'wccpf_' . $field["name"] . "_" . $i ], true );
										wc_add_order_item_meta( $item_id, $field["label"] . " - " . $i, $fobj["url"] );
									} else {
										wc_add_order_item_meta( $item_id, $field["label"] . " - " . $i, $values[ 'wccpf_' . $field["name"] . "_" . $i ] );
									}
								}
							}
						}
					}
				}
			}			
			
		}			
	}
	
	function wccpf_front_end_enqueue_scripts( $is_datepicker_there, $is_colorpicker_there ) {
		if( is_shop() || is_product() || is_cart() || is_checkout() ) {			
			$wccpf_options = get_option( 'wccpf_options' );
			$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
			$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
			
			wp_register_style( 'wccpf-font-end-style', wccpf()->settings['dir'] . 'css/wccpf-front-end.css' );
			wp_enqueue_style( array( 'wccpf-font-end-style' ) );			
			
			if( $is_datepicker_there ) {
				wp_enqueue_style( 'wccpf-jquery-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-datepicker' );
			}
			
			if( $is_colorpicker_there ) {
				wp_enqueue_style( 'spectrum-css', wccpf()->settings['dir'] . 'css/spectrum.css', array(), null );
				wp_register_script( 'wccpf-color-picker', wccpf()->settings['dir'] . 'js/spectrum.js' );				
				wp_enqueue_script( 'wccpf-color-picker' );
			}		
			
			if( $fields_cloning == "yes" ) {
				wp_register_script( 'wccpf-fields-cloner', wccpf()->settings['dir'] . 'js/wccpf-fields-cloner.js' );
				wp_enqueue_script( 'wccpf-fields-cloner' );
			}
		}
	}
} 

new wccpf_product_form();

?>