<?php 
/**
 * @author 		: Saravana Kumar K
 * @copyright	: sarkware.com
 * @todo		: This is the core Data Access Object for the entire wccpf related CRUD operations. 
 * 
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_dao {
	/* Namespace for WCCPF related post meta */
	var $wccpf_key_prefix = "wccpf_";
	
	function __construct() {
		add_action( 'save_post', array( $this, 'save_wccpf_rules' ), 1, 3 );
		add_filter( 'wccpf/load/rules', array( $this, 'load_wccpf_rules' ), 5, 1 ); 
		
		add_filter( 'wccpf/load/products', array( $this, 'load_products' ) );
		add_filter( 'wccpf/load/products_cat', array( $this, 'load_products_cat' ) );
		
		add_filter( 'wccpf/load/all_fields', array( $this, 'load_all_wccpf_fields' ) );
		add_filter( 'wccpf/load/fields', array( $this, 'load_wccpf_fields' ) );
		add_filter( 'wccpf/load/field', array( $this, 'load_wccpf_field' ), 5, 2 );
		add_filter( 'wccpf/save/field', array( $this, 'save_wccpf_field' ), 5, 2 );
		add_filter( 'wccpf/update/field', array( $this, 'update_wccpf_field' ), 5, 2 );
		add_filter( 'wccpf/remove/field', array( $this, 'remove_wccpf_field' ), 5, 2 );		
	}
	
	/**
	 * @return 	ARRAY
	 * @todo	Used to load all woocommerce products
	 * 			Used in "Conditions" Widget 
	 */
	function load_products() {
		$args = array( 'post_type' => 'product', 'order' => 'ASC', 'posts_per_page' => -1 );
		$products = get_posts( $args );
		$productsList = array();
		
		if( count( $products ) > 0 ) {
			foreach( $products as $product ) {				
				$productsList[] = array( "id" => $product->ID, "title" => $product->post_title );
			}
		}
		
		return $productsList;
	}
	
	/**
	 * @return 	ARRAY
	 * @todo	Used to load all woocommerce products category
	 * 			Used in "Conditions" Widget
	 */
	function load_products_cat() {
		$product_cat = array();
		$pcat_terms = get_terms( 'product_cat', 'orderby=count&hide_empty=0' );
		
		foreach( $pcat_terms as $pterm ) {
			$product_cat[] = array( "id" => $pterm->slug, "title" => $pterm->name );
		}
		
		return $product_cat;
	}		
	
	/**
	 * 
	 * @param 	INT 	$pid	- WCCPF Post Id
	 * @return 	ARRAY
	 * @todo	This function is used to load wccpf fields for a single WCCPF post
	 * 			mostly used in editing wccpf fields in admin screen 
	 */
	function load_wccpf_fields( $pid, $sort = true ) {
		$fields = array();
		$meta = get_post_meta( $pid );
		
		foreach ( $meta as $key => $val ) {
		 	if( preg_match('/wccpf_/', $key) ) {
		 		if( $key != $this->wccpf_key_prefix.'group_rules' ) {		 			
					$fields[ $key ] = json_decode( $val[0], true );
				}	
		 	}
		 }
		 
		 if( $sort ) {
		 	$this->usort_by_column( $fields, "order" );
		 }
		 
		 return $fields;
	}
	
	/**
	 * 
	 * @param 	INT 	$pid	- Product Id
	 * @return 	ARRAY 	( Two Dimentional )
	 * @todo	This function is used to Load all WCCPF groups. which is used by "wccpf_product_form" module
	 * 			to render actual wccpf fields on the Product Page.
	 */
	function load_all_wccpf_fields( $pid ) { 
		$fields = array();
		$all_fields = array();
		$args = array( 'post_type' => 'wccpf', 'order' => 'ASC', 'posts_per_page' => -1 );
		$wccpfs = get_posts( $args );
	
		if( count( $wccpfs ) > 0 ) {
			foreach ( $wccpfs as $wccpf ) {				
				$fields = array();		
				$rules_applicable = false;
						
				$meta = get_post_meta( $wccpf->ID );
				$rules = get_post_meta( $wccpf->ID, $this->wccpf_key_prefix.'group_rules', true );
				$rules = json_decode( $rules, true );
				
				if( is_array( $rules ) ) {
					$rules_applicable = $this->check_wccpf_for_product( $pid, $rules );
				} else {
					$rules_applicable = true;
				}
				
				if( $rules_applicable ) {
					foreach ( $meta as $key => $val ) {
						if( preg_match('/wccpf_/', $key) ) {
							if( $key != $this->wccpf_key_prefix.'group_rules' ) {
								$fields[ $key ] = json_decode( $val[0], true );
							}
						}
					}
					$this->usort_by_column( $fields, "order" );
					$all_fields[] = $fields;
				}				
			}
		}
		return $all_fields;
	}
	
	/**
	 * @param 	INT 		$pid	- Product Id
	 * @param 	ARRAY 		$groups
	 * @return 	boolean
	 * @todo	WCCPF Rules Engine, This is function is used to determine whether or not to include 
	 * 			a particular wccpf group to a particular Product  	
	 */
	function check_wccpf_for_product( $pid, $groups ) {
		$matches = array();
		$final_matches = array();
		foreach ( $groups as $rules ) {
			$ands = array();
			foreach ( $rules as $rule ) {
				if( $rule["context"] == "product" ) {
					if( $rule["endpoint"] == -1 ) {
						if( $rule["logic"] == "==") {
							$ands[] = true;
						} else {
							$ands[] = false;
						}
					} else {
						if( $rule["logic"] == "==") {							
							$ands[] = ( $pid == $rule["endpoint"] );
						} else {
							$ands[] = ( $pid != $rule["endpoint"] );
						}	
					}				
				} else {
					if( $rule["endpoint"] == -1 ) {
						if( $rule["logic"] == "==") {
							$ands[] = true;
						} else {
							$ands[] = false;
						}
					} else {
						if( $rule["logic"] == "==") {						
							$ands[] = has_term( $rule["endpoint"], 'product_cat', $pid );
						} else {
							$ands[] = !has_term( $rule["endpoint"], 'product_cat', $pid );
						}
					}
				}
			}
			$matches[] = $ands;
		}
		
		foreach ( $matches as $match ) {
			$final_matches[] = !in_array( false, $match );
		}
		
		return in_array( true, $final_matches );
	}
	
	function load_wccpf_rules( $pid ) {
	 	return get_post_meta( $pid, $this->wccpf_key_prefix.'group_rules', true );
	}

	function save_wccpf_rules( $post_id, $post, $update ) {		
		if( $post->post_type != "wccpf" ) {
			return;
		}
		
		delete_post_meta( $post_id, $this->wccpf_key_prefix.'group_rules' );				
		add_post_meta( $post_id, $this->wccpf_key_prefix.'group_rules', $_REQUEST["wccpf_group_rules"] );				
		$this->update_wccpf_fields_order( $post_id );
		return true;
	}
	
	function update_wccpf_fields_order( $pid ) {
		$fields = $this->load_wccpf_fields( $pid, false );
		foreach ( $fields as $key => $field ) {
			$field["order"] = $_REQUEST[ $key."-order" ];
			update_post_meta( $pid, $key, wp_slash( json_encode( $field ) ) );
		}
		return true;
	}
	
	function load_wccpf_field( $pid, $mkey ) {
		return get_post_meta( $pid, $mkey, true );
	}
	
	function save_wccpf_field( $pid, $payload ) {		
		return add_post_meta( $pid, $this->wccpf_key_prefix.$payload["name"], wp_slash( json_encode( $payload ) ) );
	}
	
	function update_wccpf_field( $pid, $payload ) {		
		return update_post_meta( $pid, $payload["key"], wp_slash( json_encode( $payload ) ) );
	}
	
	function remove_wccpf_field( $pid, $mkey ) {
		return delete_post_meta( $pid, $mkey );
	}

	function usort_by_column( &$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}	
		array_multisort( $sort_col, $dir, $arr);
	}
}

new wccpf_dao();

?>