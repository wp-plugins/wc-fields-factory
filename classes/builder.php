<?php 
/**
 * @author 		: Saravana Kumar K
 * @copyright	: sarkware.com
 * @todo		: HTML generator module, which wil uses "wccpf_dao" module to get data and render HTML skeletons.
 *
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_builder {
	
	function __construct() {
		add_filter( 'wccpf/build/products_list', array( $this, 'build_products_list' ) );
		add_filter( 'wccpf/build/products_cat_list', array( $this, 'build_products_cat_list' ) );
		add_filter( 'wccpf/build/fields', array( $this, 'build_wccpf_fields' ) );
	}
	
	function build_products_list( $class ) {
		$html = '<select class="'. $class .' select">';
		$products = apply_filters( "wccpf/load/products", array() );
		$html .= '<option value="-1">All Products</option>';
		
		if( count( $products ) > 0 ) {			
			foreach ( $products as $product ) {
				$html .= '<option value="'. $product["id"] .'">'. $product["title"] .'</option>';
			}			
		}
		
		$html .= '</select>';
		return $html;
	}
	
	function build_products_cat_list( $class ) {
		$html = '<select class="'. $class .' select">';
		$pcats = apply_filters( "wccpf/load/products_cat", array() );
		$html .= '<option value="-1">All Categories</option>';
		
		if( count( $pcats ) > 0 ) {
			foreach ( $pcats as $pcat ) {
				$html .= '<option value="'. $pcat["id"] .'">'. $pcat["title"] .'</option>';
			}
		}

		$html .= '</select>';
		return $html;
	}
	
	function build_wccpf_fields( $fields ) {
		$html = "";
		foreach ( $fields as $key => $field ) { 
			//$field  =json_decode( $field, true );
					
			$html .= '<div class="wccpf-meta-row" data-key="'.$key.'">
						<table class="wccpf_table">
							<tbody>		
								<tr>
									<td class="wccpf-sortable">
										<span class="wccpf-field-order"></span>
									</td>
									<td>
										<label class="wccpf-field-label">'.$field["label"].'</label>
										<div class="wccpf-meta-option">
											<a href="#" data-key="'. $key .'" class="wccpf-meta-option-edit">Edit</a> | 
											<a href="#" data-key="'. $key .'" class="wccpf-meta-option-delete">Delete</a>
										</div>
									</td>
									<td>
										<label class="wccpf-field-name">'. $field["name"] .'</label>
									</td>
									<td>
										<label class="wccpf-field-type">'. $field["type"] .'</label>
									</td>
								</tr>
							</tbody>
						</table>
						<input type="hidden" name="'.$key.'-order" class="wccpf-field-order-index" value="'. $field["order"] .'" />
					</div>';
		}
	 	return $html;		
	}
	
}

new wccpf_builder();

?>