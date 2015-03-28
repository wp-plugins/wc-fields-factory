<?php
/*
*  Meta box - Custom Product Fields
*  Template for creating or updating custom product fields
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post, $field_types;

$fields = apply_filters('wccpf/product_field_group/get_fields', array());

// conditional logic dummy data
$conditional_logic_rule = array(
	'field' => '',
	'operator' => '==',
	'value' => ''
);

$error_field_type = '<b>' . __('Error', 'acf') . '</b> ' . __('Field type does not exist', 'acf');

?>

<!-- Hidden Fields -->
<div style="display:none;">
	<input type="hidden" name="acf_nonce" value="<?php echo wp_create_nonce( 'field_group' ); ?>" />
</div>
<!-- / Hidden Fields -->

<!-- Fields Header -->
<div class="fields_header">
	<table class="wccpf_table">
		<thead>
			<tr>
				<th>Field Order</th>
				<th>Field Label</th>
				<th>Field Name</th>
				<th>Field Type</th>			
			</tr>
		</thead>
	</table>
</div>
<!-- / Fields Header -->

<div class="fields">
	
	<div id="wccpf-fields-set" class="sortable ui-sortable">
		<?php
		 
			$fields = apply_filters( 'wccpf/load/fields', $post->ID );
			if( is_array( $fields ) ) {
				echo apply_filters( 'wccpf/build/fields', $fields );
			} else {
				$fields = array();	
			}			
			
		?>
	</div>
	
	<div id="wccpf-empty-field-set" style="display:<?php echo count( $fields ) < 1 ? 'block' : 'none'; ?>">
		Zero product fields.! Use the <strong>Fields Factory</strong> form to create your custom product fields.!
	</div>	
	
</div>