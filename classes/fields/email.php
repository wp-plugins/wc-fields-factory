<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_field_email extends wccpf_product_field {
	
	function __construct() {
		$this->name 		= 'email';
		$this->label 		= "Email";
		$this->required 	= false;
		$this->message 		= "This field can't be Empty";
		$this->params 		= array(
			'placeholder'	=>	'',
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
						
		<?php	
		return ob_get_clean();
	}
	
	function render_product_field( $field ) { ob_start(); ?>
	
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field["name"], $field["label"] ); ?>
			
			<input type="email" name="<?php echo esc_attr( $field["name"] ); ?>" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" maxlength="<?php echo esc_attr( $field["maxlength"] ); ?>" />
			
			<?php do_action( 'wccpf/after/field/rendering' ); ?>
		
		<?php else : ?>
		
		<table class="wccpf_fields_table variations" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label for="<?php echo esc_attr( $field["name"] ); ?>"><?php echo esc_attr( $field["label"] ); ?></label></td>
					<td class="wccpf_value">
						<input type="email" name="<?php echo esc_attr( $field["name"] ); ?>" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" maxlength="<?php echo esc_attr( $field["maxlength"] ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>	
		
		<?php endif; ?>
		
	<?php return ob_get_clean();
	}
	
	function validate( $val ) {
		if( !filter_var( $val, FILTER_VALIDATE_EMAIL ) === false ) { 
			return true; 
		} else { 
			return false; 
		}
	}
	
}

new wccpf_field_email();

?>