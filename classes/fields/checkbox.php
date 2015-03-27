<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_field_checkbox extends wccpf_product_field {
	
	function __construct() {
		$this->name 		= 'checkbox';
		$this->label 		= "Check Box";
		$this->required 	= false;
		$this->message 		= "This field can't be Empty";
		$this->params 		= array(
			'layout'		=>	'vertical',
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
					<p class="description">Enter each default options on a new line</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="textarea" data-param="default_value">
						<textarea rows="6" id="wccpf-field-type-meta-default_value"></textarea>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type">Layout</label>
					<p class="description">Row wise (or) Column wise</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="radio" data-param="layout">
						<ul class="wccpf-field-layout-horizontal">
							<li><label><input type="radio" name="wccpf-field-type-meta-layout" value="horizontal" checked /> Horizontal</label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-layout" value="vertical" /> Vertical</label></li>
						</ul>						
					</div>
				</td>
			</tr>
									
		<?php
		return ob_get_clean();	
	}
	
	function render_product_field( $field ) { 
		$layout = ( $field['layout'] == "horizontal" ) ? "wccpf-field-layout-horizontal" : "wccpf-field-layout-vertical"; 
		ob_start();	
	?>
	
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field["name"], $field["label"] ); ?>
			
			<ul class="<?php echo $layout; ?>">
			<?php 	
				$attr = '';
				$choices = explode( "\n", $field["choices"] );
				$defaults = explode( "\n", $field["default_value"] );

				foreach ( $choices as $choice ) {
					if( in_array( $choice, $defaults ) ) {
						$attr = 'checked="yes"';
					} else {
						$attr = '';
					}
					$key_val = explode( "|", $choice ); ?>
					<li><label><input type="checkbox" name="<?php echo esc_attr( $field["name"] ); ?>[]" value="<?php echo esc_attr( trim( $key_val[0] ) ) ?>" <?php echo $attr; ?>/> <?php echo esc_attr( trim( $key_val[1] ) ); ?></label></li>										
				<?php } ?>										
			</ul>
			
			<?php do_action( 'wccpf/after/field/rendering' ); ?>
		
		<?php else : ?>
	
		<table class="wccpf_fields_table variations" cellspacing="0">
			<tbody>
				<tr>
					<td class="label"><label for="<?php echo esc_attr( $field["name"] ); ?>"><?php echo esc_attr( $field["label"] ); ?></label></td>
					<td class="value">
						<ul class="<?php echo $layout; ?>">
						<?php 	
							$attr = '';
							$choices = explode( "\n", $field["choices"] );
							$defaults = explode( "\n", $field["default_value"] );

							foreach ( $choices as $choice ) {
								if( in_array( $choice, $defaults ) ) {
									$attr = 'checked="yes"';
								} else {
									$attr = '';
								}
								$key_val = explode( "|", $choice ); ?>
								<li><label><input type="checkbox" name="<?php echo esc_attr( $field["name"] ); ?>[]" value="<?php echo esc_attr( trim( $key_val[0] ) ) ?>" <?php echo $attr; ?>/> <?php echo esc_attr( trim( $key_val[1] ) ); ?></label></li>										
							<?php } ?>										
						</ul>
					</td>
				</tr>
			</tbody>
		</table>		
		
		<?php endif; ?>
			
	<?php return ob_get_clean();	
	}
	
	function validate( $val ) {
		return empty( $val ) ? false : true;
	}
	
}

new wccpf_field_checkbox();

?>