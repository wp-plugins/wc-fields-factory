<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_field_colorpicker extends wccpf_product_field {
	
	function __construct() {
		$this->name 		= 'colorpicker';
		$this->label 		= "Color Picker";
		$this->required 	= false;
		$this->message 		= "This field can't be Empty";
		$this->params 		= array(				
				'placeholder'	=>	''
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
					<label for="post_type"><?php _e( 'Color Format', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'How you want the color value', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="radio" data-param="color_format">
						<ul class="wccpf-field-layout-horizontal">
							<li><label><input type="radio" name="wccpf-field-type-meta-color_format" value="hex" checked /> <?php _e( 'HEX', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-color_format" value="hex3" /> <?php _e( 'HEX3', 'wc-fields-factory' ); ?></label></li>							
							<li><label><input type="radio" name="wccpf-field-type-meta-color_format" value="hsl"  /> <?php _e( 'HSL', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-color_format" value="rgb" /> <?php _e( 'RGB', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-color_format" value="name" /> <?php _e( 'Name', 'wc-fields-factory' ); ?></label></li>
						</ul>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Palettes', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Instead of showing only the color picker, you can show them personalized palettes, where customer chooce one of the color provided by you.', 'wc-fields-factory' ); ?><br/><br/>#fff, #ccc, #555<br/>#f00, #0f0, #00f</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="textarea" data-param="palettes">
						<textarea rows="6" id="wccpf-field-type-meta-palettes"></textarea>						
					</div>
				</td>
			</tr>				
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Show Palette Only', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Want show only the palette.? or along with the color picker.?', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="radio" data-param="show_palette_only">
						<ul class="wccpf-field-layout-horizontal">
							<li><label><input type="radio" name="wccpf-field-type-meta-show_palette_only" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-show_palette_only" value="no" checked/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
						</ul>						
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
			
			<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccpf-field wccpf-color wccpf-color-<?php echo esc_attr( $field["name"] ); ?>" value="" />
			
			<?php do_action( 'wccpf/after/field/rendering', $field ); ?>
		
		<?php else : ?>
		
		<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class', '' ); ?>" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label for="<?php echo esc_attr( $field["name"] . $name_index ); ?>"><?php echo esc_html( $field["label"] ); ?></label></td>
					<td class="wccpf_value">
						<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccpf-field wccpf-color wccpf-color-<?php echo esc_attr( $field["name"] ); ?>" value="" />
					</td>
				</tr>
			</tbody>
		</table>	
		
		<?php endif;		
		return ob_get_clean();
	}
	
	function validate( $val ) {
		return ( isset( $val ) && !empty( $val ) ) ? true : false;
	}
}

new wccpf_field_colorpicker();

?>