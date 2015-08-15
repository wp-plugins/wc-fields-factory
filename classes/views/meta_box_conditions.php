<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$index = 0;

$rule_group = apply_filters( 'wccpf/load/rules', $post->ID );
$rule_group = json_decode( $rule_group, true );

$contexts = array( array( "id"=>"product", "title"=>"Product" ), array( "id"=>"product_cat", "title"=>"Product Category" ) );
$logics = array( array( "id"=>"==", "title"=>"is equal to" ), array( "id"=>"!=", "title"=>"is not equal to" ) );

$products = apply_filters( "wccpf/load/products", array() );
array_unshift( $products , array( "id" => "-1", "title" => "All Products" ));

$pcats = apply_filters( "wccpf/load/products_cat", array() );
array_unshift( $pcats , array( "id" => "-1", "title" => "All Categories" ));

?>

<div class="wccpf_logic_wrapper">
	<table class="wccpf_table">
		<tbody>
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Rules', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Add rules to determines which products or product categories will have this custom fields group', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf_logic_groups">
					<?php if( is_array( $rule_group ) && count( $rule_group ) > 0 ) {					
						foreach ( $rule_group as $group ) { ?>
																			
							<div class="wccpf_logic_group"> 
								<h4><?php echo ( $index == 0 ) ? __( 'Show this product fields group if', 'wc-fields-factory' ) : __( 'or', 'wc-fields-factory' ); ?></h4>
								<table class="wccpf_table wccpf_rules_table">
								<tbody>
									<?php foreach ( $group as $rule ) { ?>
									<tr>
										<td>
											<select class="wccpf_condition_param select">
												<?php foreach ( $contexts as $context ) {
													$selected = ( $context["id"] == $rule["context"] ) ? 'selected="selected"' : '';
													echo '<option value="'. $context["id"] .'" '. $selected .'>'. $context["title"] .'</option>';													
												} ?>																			
											</select>
										</td>
										<td>
											<select class="wccpf_condition_operator select">
												<?php foreach ( $logics as $logic ) {
													$selected = ( $logic["id"] == $rule["logic"] ) ? 'selected="selected"' : '';
													echo '<option value="'. $logic["id"] .'" '. $selected .'>'. $logic["title"] .'</option>';													
												} ?>												
											</select>
										</td>
										<td class="condition_value_td">
											<select class="wccpf_condition_value select">
											<?php 	
												$endpoints = array();											
												if( $rule["context"] == "product" ) {
													$endpoints = $products;
												} else {
													$endpoints = $pcats;
												}	

												foreach ( $endpoints as $endpoint ) {
													$selected = ( $endpoint["id"] == $rule["endpoint"] ) ? 'selected="selected"' : '';
													echo '<option value="'. $endpoint["id"] .'" '. $selected .'>'. $endpoint["title"] .'</option>';
												}
											?>
											</select>
										</td>
										<td class="add"><a href="#" class="condition-add-rule button"><?php _e( 'and', 'wc-fields-factory' ); ?></a></td>
										<td class="remove"><?php echo ( $index != 0 ) ? '<a href="#" class="condition-remove-rule wccpf-button-remove"></a>' : ''; ?></td>
									</tr>
									<?php $index++; } ?>
								</tbody>
							</table>
							</div>					
					
					<?php } } else { ?>					
						<div class="wccpf_logic_group"> 
							<h4><?php _e( 'Show this product fields group if', 'wc-fields-factory' ); ?></h4>
							<table class="wccpf_table wccpf_rules_table">
								<tbody>
									<tr>
										<td>
											<select class="wccpf_condition_param select">
												<option value="product" selected="selected"><?php _e( 'Product', 'wc-fields-factory' ); ?></option>
												<option value="product_cat"><?php _e( 'Product Category', 'wc-fields-factory' ); ?></option>
												
											</select>
										</td>
										<td>
											<select class="wccpf_condition_operator select">
												<option value="==" selected="selected"><?php _e( 'is equal to', 'wc-fields-factory' ); ?></option>
												<option value="!="><?php _e( 'is not equal to', 'wc-fields-factory' ); ?></option>
											</select>
										</td>
										<td class="condition_value_td">
											<?php echo apply_filters( 'wccpf/build/products_list', "wccpf_condition_value" ); ?>											
										</td>
										<td class="add"><a href="#" class="condition-add-rule button"><?php _e( 'and', 'wc-fields-factory' ); ?></a></td>
										<td class="remove"></td>
									</tr>
								</tbody>
							</table>
						</div>													
					
					<?php } ?>
						<h4>or</h4>
						<a href="#" class="condition-add-group button"><?php _e( 'Add rule group', 'wc-fields-factory' ); ?></a>	
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="wccpf_group_rules" id="wccpf_rules" value="Sample Rules"/>
</div>
