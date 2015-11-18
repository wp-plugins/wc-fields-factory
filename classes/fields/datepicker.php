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
					<label for="post_type"><?php _e( 'Place Holder', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Place holder text for this Text Box', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="text" data-param="placeholder">
						<input type="text" id="wccpf-field-type-meta-placeholder" value="" />
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Read Only', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Make text field read only, so it won\'t pulls up mobile key board ( on mobile browsers )', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="radio" data-param="readonly">
						<ul class="wccpf-field-layout-horizontal">
							<li><label><input type="radio" name="wccpf-field-type-meta-readonly" value="yes" checked/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-readonly" value="no" /> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
						</ul>
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Disable Dates', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Prevent user from selecting past, present or future dates', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="radio" data-param="disable_date">
						<ul class="wccpf-field-layout-horizontal">
							<li><label><input type="radio" name="wccpf-field-type-meta-disable_date" value="none" checked/> <?php _e( 'Show All Date', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf-field-type-meta-disable_date" value="past" /> <?php _e( 'Disable Past Dates', 'wc-fields-factory' ); ?></label></li>							
							<li><label><input type="radio" name="wccpf-field-type-meta-disable_date" value="future" /> <?php _e( 'Disable Future Dates', 'wc-fields-factory' ); ?></label></li>							
						</ul>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Disable Days', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Prevent user from selecting particular days', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wccpf-field-types-meta" data-type="check" data-param="disable_days">
						<ul class="wccpf-field-layout-horizontal">
							<li><label><input type="checkbox" name="wccpf-field-type-meta-disable_days" value="sunday" /> <?php _e( 'Sunday', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="checkbox" name="wccpf-field-type-meta-disable_days" value="monday" /> <?php _e( 'Monday', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="checkbox" name="wccpf-field-type-meta-disable_days" value="tuesday" /> <?php _e( 'Tuesday', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="checkbox" name="wccpf-field-type-meta-disable_days" value="wednesday" /> <?php _e( 'Wednesday', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="checkbox" name="wccpf-field-type-meta-disable_days" value="thursday" /> <?php _e( 'Thursday', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="checkbox" name="wccpf-field-type-meta-disable_days" value="friday" /> <?php _e( 'Friday', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="checkbox" name="wccpf-field-type-meta-disable_days" value="saturday" /> <?php _e( 'Saturday', 'wc-fields-factory' ); ?></label></li>
						</ul>						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Date Format', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'The Date Format that will be used display & save the value', 'wc-fields-factory' ); ?></p>
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
	
	function render_product_field( $field ) { 
		
		$wccpf_options = get_option( 'wccpf_options' );
		$wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
		$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		$name_index = $fields_cloning == "yes" ? "_1" : "";
	
		ob_start(); ?>
		
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field ); ?>
			
			<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccpf-field wccpf-datepicker wccpf-datepicker-<?php echo esc_attr( $field["name"] ); ?>" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" value="" <?php echo ( $field["readonly"] == "yes" ) ? "readonly" : ""; ?>/>
			
			<?php do_action( 'wccpf/after/field/rendering', $field ); ?>
		
		<?php else : ?>
		
		<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class', '' ); ?>" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label for="<?php echo esc_attr( $field["name"] . $name_index ); ?>"><?php echo esc_html( $field["label"] ); ?></label></td>
					<td class="wccpf_value">
						<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccpf-field wccpf-datepicker wccpf-datepicker-<?php echo esc_attr( $field["name"] ); ?>" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" value="" <?php echo ( $field["readonly"] == "yes" ) ? "readonly" : ""; ?>/>
					</td>
				</tr>
			</tbody>
		</table>	
		
		<?php endif; ?>
		
		<script type="text/javascript">
		(function($) {
			$( document ).ready(function() {
				<?php $dformat = isset( $field["date_format"] ) ? 'dateFormat:'.esc_attr( $field["date_format"] ) : ''; ?>
				$("body").on("focus", ".wccpf-datepicker-<?php echo esc_attr( $field["name"] ); ?>", function(){
					$(this).datepicker({
						<?php if( $field["date_format"] != "" ){
							echo "dateFormat:'".esc_attr( $field["date_format"] )."'";
						} else {
							echo "dateFormat:'dd-mm-yy'";
						}					
						if( is_array( $field["disable_date"] ) && count( $field["disable_date"] ) > 0 ) {
							if( array_search( "future", $field["disable_date"] ) !== false ) {
								echo ",maxDate: 0";
							}
							if( array_search( "past", $field["disable_date"] ) !== false ) {
								echo ",minDate: new Date()";
							}											
						}
						if( is_array( $field["disable_days"] ) && count( $field["disable_days"] ) > 0 ) {
							$conditions = array();
							$callback = "function( date ){ var day = date.getDay();";
							if( array_search( "sunday", $field["disable_days"] ) !== false ) {
								$conditions[] = "( day != 0 )";
							}
							if( array_search( "monday", $field["disable_days"] ) !== false ) {
								$conditions[] = "( day != 1 )";
							}
							if( array_search( "tuesday", $field["disable_days"] ) !== false ) {
								$conditions[] = "( day != 2 )";
							}
							if( array_search( "wednesday", $field["disable_days"] ) !== false ) {
								$conditions[] = "( day != 3 )";
							}
							if( array_search( "thursday", $field["disable_days"] ) !== false ) {
								$conditions[] = "( day != 4 )";
							}
							if( array_search( "friday", $field["disable_days"] ) !== false ) {
								$conditions[] = "( day != 5 )";
							}
							if( array_search( "saturday", $field["disable_days"] ) !== false ) {
								$conditions[] = "( day != 6 )";
							}
							$callback .= "return [( ". implode( "&&", $conditions ) ." )];  }";
							echo ",beforeShowDay: ".$callback; 
						}
						?>									 
					});
				});				
			});
		})(jQuery);
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