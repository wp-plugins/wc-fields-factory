<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_field_textarea extends wccpf_product_field {
	
	function __construct() {
		$this->name 		= 'textarea';
		$this->label 		= "Text Area";
		$this->required 	= false;
		$this->message	 	= "This field can't be Empty";
		$this->params 		= array(
			'placeholder'	=>	'',
			'default_value'	=>	'',
			'maxlength'		=>	'',
			'rows' 			=> ''
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
						<input type="text" id="wccpf-field-type-meta-message" value="<?php echo esc_attr( $this->message ); ?>" />						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type">Place Holder</label>
					<p class="description">Place holder text for this Text Box</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="text" data-param="placeholder">
						<input type="text" id="wccpf-field-type-meta-placeholder" value="" />
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type">Default Value</label>
					<p class="description">Default value for this Text Box</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="text" data-param="default_value">
						<input type="text" id="wccpf-field-type-meta-default_value" value="" />
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type">Maximum characters</label>
					<p class="description">Leave it blank for no limit</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="text" data-param="maxlength">
						<input type="text" id="wccpf-field-type-meta-maxlength" value="" />
					</div>
				</td>
			</tr>	
			
			<tr>
				<td class="summary">
					<label for="post_type">Rows</label>
					<p class="description">Set the textarea height</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="text" data-param="rows">
						<input type="text" id="wccpf-field-type-meta-rows" value="4" />
					</div>
				</td>
			</tr>	
					
		<?php	
		return ob_get_clean();
	}
	
	function render_product_field( $field ) { ob_start(); ?>
	
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field["name"], $field["label"] ); ?>
			
			<textarea name="<?php echo esc_attr( $field["name"] ); ?>" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" rows="<?php echo esc_attr( $field["rows"] ); ?>" maxlength="<?php echo esc_attr( $field["maxlength"] ); ?>"><?php echo esc_html( $field["default_value"] ); ?></textarea>
			
			<?php do_action( 'wccpf/after/field/rendering' ); ?>
		
		<?php else : ?>	
		
		<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class', '' ); ?>" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label for="<?php echo esc_attr( $field["name"] ); ?>"><?php echo esc_html( $field["label"] ); ?></label></td>
					<td class="wccpf_value">
						<textarea name="<?php echo esc_attr( $field["name"] ); ?>" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" rows="<?php echo esc_attr( $field["rows"] ); ?>" maxlength="<?php echo esc_attr( $field["maxlength"] ); ?>"><?php echo esc_html( $field["default_value"] ); ?></textarea>
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

new wccpf_field_textarea();

?>