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

$error_field_type = '<b>' . __( 'Error', 'wc-fields-factory' ) . '</b> ' . __( 'Field type does not exist', 'wc-fields-factory' );

?>

<!-- Hidden Fields -->
<div style="display:none;">
	<input type="hidden" name="wccpf_nonce" value="<?php echo wp_create_nonce( 'field_group' ); ?>" />
</div>
<!-- / Hidden Fields -->

<!-- Fields Header -->
<div class="fields_header">
	<table class="wccpf_table">
		<thead>
			<tr>
				<th><?php _e( 'Field Order', 'wc-fields-factory' ); ?></th>
				<th><?php _e( 'Field Label', 'wc-fields-factory' ); ?></th>
				<th><?php _e( 'Field Name', 'wc-fields-factory' ); ?></th>
				<th><?php _e( 'Field Type', 'wc-fields-factory' ); ?></th>			
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
		<?php _e( 'Zero product fields.! Use the', 'wc-fields-factory' ); ?> <strong><?php _e( 'Fields Factory', 'wc-fields-factory' ); ?></strong> <?php _e( 'form to create your custom product fields.!', 'wc-fields-factory' ); ?>
	</div>	
	
</div>