<?php

wp_register_style( 'wccpf-style', plugin_dir_url( __FILE__ ) . '../css/wccpf.css' );
wp_enqueue_style('wccpf-style');

if( is_admin() ) {
	add_action( 'admin_init', 'wccpf_register_options' );
}

function wccpf_register_options() {	
	register_setting( 'wccpf_options', 'wccpf_options' );		
}

function wccpf_render_option_page() {	
	
	$wccpf_options = get_option( 'wccpf_options' );
	$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();		
	$show_custom_data = isset( $wccpf_options["show_custom_data"] ) ? $wccpf_options["show_custom_data"] : "yes";	
	$fields_location = isset( $wccpf_options["field_location"] ) ? $wccpf_options["field_location"] : "woocommerce_before_add_to_cart_button";
	//$hide_on_dropdown = isset( $wccpf_options["hide_on_dropdown"] ) ? $wccpf_options["hide_on_dropdown"] : "no";
	$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
	$group_title =  isset( $wccpf_options["fields_group_title"] ) ? $wccpf_options["fields_group_title"] : "";  
	$group_meta_on_cart = isset( $wccpf_options["group_meta_on_cart"] ) ? $wccpf_options["group_meta_on_cart"] : "no"; 
	$group_fields_on_cart = isset( $wccpf_options["group_fields_on_cart"] ) ? $wccpf_options["group_fields_on_cart"] : "no"; ?>

	<?php if( isset( $_GET["settings-updated"] ) ) :?>
	<div id="message" class="updated fade"><p><strong>Your settings have been saved.</strong></p></div>
	<?php endif; ?>

	<div class="wrap wccpf-options-wrapper">		
		<h2><?php _e( 'WC Fields Factory Options', 'wc-fields-factory' ); ?></h2>
		<form action='options.php' method='post' class='wccpf-options-form'>		
			<?php settings_fields('wccpf_options'); ?>
					
			<table class="wccpf-option-field-row wccpf_table">			
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Display on Cart & Checkout', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Display custom meta data on Cart & Checkout page.!', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wccpf-field-types-meta">
							<ul class="wccpf-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[show_on_cart]" value="yes" <?php echo ( $show_custom_data == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[show_on_cart]" value="no" <?php echo ( $show_custom_data == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>			
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Fields Location', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Choose where the fields should be displayed on product page', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wccpf-field-types-meta">
							<ul class="wccpf-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[field_location]" value="woocommerce_before_add_to_cart_button" <?php echo ( $fields_location == "woocommerce_before_add_to_cart_button" ) ? "checked" : ""; ?>/> <?php _e( 'Before Add To Cart Button', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[field_location]" value="woocommerce_after_add_to_cart_button" <?php echo ( $fields_location == "woocommerce_after_add_to_cart_button" ) ? "checked" : ""; ?>/> <?php _e( 'After Add To Cart Button', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[field_location]" value="woocommerce_before_add_to_cart_form" <?php echo ( $fields_location == "woocommerce_before_add_to_cart_form" ) ? "checked" : ""; ?>/> <?php _e( 'Before Add To Cart Form', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[field_location]" value="woocommerce_after_add_to_cart_form" <?php echo ( $fields_location == "woocommerce_after_add_to_cart_form" ) ? "checked" : ""; ?>/> <?php _e( 'After Add To Cart Form', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[field_location]" value="woocommerce_before_single_product_summary" <?php echo ( $fields_location == "woocommerce_before_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'Before Product Summary', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[field_location]" value="woocommerce_after_single_product_summary" <?php echo ( $fields_location == "woocommerce_after_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'After Product Summary', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[field_location]" value="woocommerce_single_product_summary" <?php echo ( $fields_location == "woocommerce_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'Product Summary', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>
				<!-- 
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Hide on Drop Down Cart', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Hide custom field meta on Drop Down Cart module.', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wccpf-field-types-meta">
							<ul class="wccpf-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[hide_on_dropdown]" value="yes" <?php echo ( $hide_on_dropdown == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[hide_on_dropdown]" value="no" <?php echo ( $hide_on_dropdown == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>
				 -->	
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Fields Cloning', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Display custom fields per product count. Whenever user increases the product quantity, all custom fields will be cloned.!, the', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wccpf-field-types-meta">
							<ul class="wccpf-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[fields_cloning]" value="yes" <?php echo ( $fields_cloning == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[fields_cloning]" value="no" <?php echo ( $fields_cloning == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>				
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Group Meta', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Custom meta data will be grouped and displayed in cart & checkout. won\'t work if group fields option choosed.', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wccpf-field-types-meta">
							<ul class="wccpf-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[group_meta_on_cart]" value="yes" <?php echo ( $group_meta_on_cart == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[group_meta_on_cart]" value="no" <?php echo ( $group_meta_on_cart == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Group Fields', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Custom fields will be grouped ( within each line item, per count ) and displayed in cart & checkout.', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wccpf-field-types-meta">
							<ul class="wccpf-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[group_fields_on_cart]" value="yes" <?php echo ( $group_fields_on_cart == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[group_fields_on_cart]" value="no" <?php echo ( $group_fields_on_cart == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>	
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Fields Group Title', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'If "Fields per Product Count" enabled, then you can assign a title for fields group.!', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wccpf-field-types-meta">
							<input type="text" name="wccpf_options[fields_group_title]" value="<?php echo esc_attr( $group_title ); ?>" placeholder="eg. Addiotnal Options : "/>						
						</div>
					</td>
				</tr>				
			</table>			
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
	</div>
	
<?php 

}

?>