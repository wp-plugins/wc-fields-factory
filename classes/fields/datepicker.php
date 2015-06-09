<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_field_datepicker extends wccpf_product_field {
	
	function __construct() {
		$this->name 		= 'datepicker';
		$this->label 		= "Date Picker";
		$this->required 	= false;
		$this->message 		= "This field can't be Empty";
		$this->params 		= array(				
				'placeholder'	=>	'',
				'date_format'	=>	''
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
					<label for="post_type">Date Format</label>
					<p class="description">The Date Format that will be used display & save the value</p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="text" data-param="date_format">
						<input type="text" id="wccpf-field-type-meta-date_format" value="" placeholder="dd-mm-yy"/>
					</div>
				</td>
			</tr>
						
		<?php	
		return ob_get_clean();
	}
	
	function render_product_field( $field ) { ob_start(); ?>
		
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field["name"], $field["label"] ); ?>
			
			<input type="text" name="<?php echo esc_attr( $field["name"] ); ?>" class="wccpf_datepicker_field" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" value="" />
			
			<?php do_action( 'wccpf/after/field/rendering' ); ?>
		
		<?php else : ?>
		
		<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class' ); ?>" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label for="<?php echo esc_attr( $field["name"] ); ?>"><?php echo esc_html( $field["label"] ); ?></label></td>
					<td class="wccpf_value">
						<input type="text" name="<?php echo esc_attr( $field["name"] ); ?>" class="wccpf_datepicker_field" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" value="" />
					</td>
				</tr>
			</tbody>
		</table>	
		
		<?php endif; ?>
		
		<script type="text/javascript">
			var $ = jQuery;
			$( document ).ready(function() {
				<?php $dformat = isset( $field["date_format"] ) ? 'dateFormat:'.esc_attr( $field["date_format"] ) : ''; ?>
				$( "input[name=<?php echo esc_attr( $field["name"] ); ?>]" ).datepicker({
					<?php if( $field["date_format"] != "" ){
						echo "dateFormat:'".esc_attr( $field["date_format"] )."'";
					} else {
						echo "dateFormat:'dd-mm-yy'";
					} ?>					 
				});
			});
		</script>
		
		<?php 
		return ob_get_clean();
	}
	
	function validate( $val ) {
		return ( isset( $val ) && !empty( $val ) ) ? true : false;
	}
}

new wccpf_field_datepicker();

?>