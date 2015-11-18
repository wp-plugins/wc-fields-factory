<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_field_select extends wccpf_product_field {
	
	function __construct() {
		$this->name 		= 'select';
		$this->label 		= "Combo Box";
		$this->required 	= false;
		$this->message 		= "This field can't be Empty";
		$this->params 		= array(
			'choices'		=>	array(),
			'default_value'	=>	''			
		);
	
		parent::__construct();
	}
	
	function render_admin_field() { ob_start(); ?>
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Required', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Is this field Mandatory', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="radio" data-param="required">
						<ul class="wccpf-field-layout-horizontal">
							<li><label><input type="radio" name="wccpf-field-type-meta-required" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-required" value="no" checked/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
						</ul>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Message', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Message to display whenever the validation failed', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="text" data-param="message">
						<input type="text" id="wccpf-field-type-meta-message" value="<?php echo esc_attr( $this->message ); ?>" />						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Visibility', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'However this field will be saved with Order Meta, regardless of this visibility option.', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="radio" data-param="visibility">
						<ul class="wccpf-field-layout-vertical">
							<li><label><input type="radio" name="wccpf-field-type-meta-visibility" value="yes" checked /> <?php _e( 'Show in Cart & Checkout Page', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-visibility" value="no" /> <?php _e( 'Hide in Cart & Checkout Page', 'wc-fields-factory' ); ?></label></li>							
						</ul>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Options', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Enter each options on a new line like this', 'wc-fields-factory' ); ?><br/><br/>red|Red<br/>blue|Blue</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="textarea" data-param="choices">
						<textarea rows="6" id="wccpf-field-type-meta-choices"></textarea>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Default Options', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Default selected option', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="textarea" data-param="default_value">
						<input type="text" id="wccpf-field-type-meta-default_value" />						
					</div>
				</td>
			</tr>		
												
		<?php
		return ob_get_clean();		
	}
	
	function render_product_field( $field ) { 
		
		$wccpf_options = get_option( 'wccpf_options' );
		$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
		$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		$name_index = $fields_cloning == "yes" ? "_1" : "";
		
		ob_start(); ?>
	
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field ); ?>
			
			<select class="wccpf-field" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>">
			<?php 							
			$attr = '';
			$choices = explode( ";", $field["choices"] );					
			foreach ( $choices as $choice ) {			
				if( $choice == $field["default_value"] ) {
					$attr = 'selected="selected"';
				} else {
					$attr = '';
				}
				$key_val = explode( "|", $choice );
				echo '<option value="'. esc_attr( trim( $key_val[0] ) ) .'" '. $attr .'>'. esc_html( trim( $key_val[1] ) ) .'</option>';
			} ?>		
			</select>
			
			<?php do_action( 'wccpf/after/field/rendering', $field ); ?>
		
		<?php else : ?>

		<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class', '' ); ?>" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label for="<?php echo esc_attr( $field["name"] . $name_index ); ?>"><?php echo esc_html( $field["label"] ); ?></label></td>
					<td class="wccpf_value">
						<select class="wccpf-field" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>">
						<?php 							
						$attr = '';
						
						$choices = explode( ";", $field["choices"] );					
						foreach ( $choices as $choice ) {			
							if( $choice == $field["default_value"] ) {
								$attr = 'selected="selected"';
							} else {
								$attr = '';
							}
							$key_val = explode( "|", $choice );
							echo '<option value="'. esc_attr( trim( $key_val[0] ) ) .'" '. $attr .'>'. esc_html( trim( $key_val[1] ) ) .'</option>';
						} ?>		
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		
		<?php endif; ?>
		
	<?php return ob_get_clean();	
	}
	
	function validate( $val ) {
		return ( isset( $val ) && !empty( $val ) ) ? true : false;
	}
	
}

new wccpf_field_select();

?>