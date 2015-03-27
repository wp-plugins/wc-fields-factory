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
					<label for="post_type">Required</label>
					<p class="description">Is this field Mandatory</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="radio" data-param="required">
						<ul class="wccpf-field-layout-horizontal">
							<li><label><input type="radio" name="wccpf-field-type-meta-required" value="yes" /> Yes</label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-required" value="no" checked/> No</label></li>
						</ul>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type">Message</label>
					<p class="description">Message to display whenever the validation failed</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="text" data-param="message">
						<input type="text" id="wccpf-field-type-meta-message" value="<?php echo $this->message; ?>" />						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type">Options</label>
					<p class="description">Enter each options on a new line like this<br/><br/>red|Red<br/>blue|Blue</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="textarea" data-param="choices">
						<textarea rows="6" id="wccpf-field-type-meta-choices"></textarea>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type">Default Options</label>
					<p class="description">Default selected option</p>
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
	
	function render_product_field( $field ) { ob_start(); ?>
		<table class="wccpf_fields_table variations" cellspacing="0">
			<tbody>
				<tr>
					<td class="label"><label for="<?php echo esc_attr( $field["name"] ); ?>"><?php echo esc_attr( $field["label"] ); ?></label></td>
					<td class="value">
						<select name="<?php echo esc_attr( $field["name"] ); ?>">
						<?php 							
						$attr = '';
						$choices = explode( "\n", $field["choices"] );					
						foreach ( $choices as $choice ) {			
							if( $choice == $field["default_value"] ) {
								$attr = 'selected="selected"';
							} else {
								$attr = '';
							}
							$key_val = explode( "|", $choice );
							echo '<option value="'. esc_attr( trim( $key_val[0] ) ) .'" '. $attr .'>'. esc_attr( trim( $key_val[1] ) ) .'</option>';
						} ?>		
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	<?php return ob_get_clean();	
	}
	
	function validate( $val ) {
		return ( isset( $val ) && !empty( $val ) ) ? true : false;
	}
	
}

new wccpf_field_select();

?>