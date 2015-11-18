<?php 
	global $post;
	
	if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<div id="wccpf_fields_factory" action="POST">

	<table class="wccpf_table wccpf_fields_factory_header">
		<tr>
			<td>
				<select class="select" id="wccpf-field-type-meta-type">
					<option value="text"><?php _e( 'Text', 'wc-fields-factory' ); ?></option>
					<option value="number"><?php _e( 'Number', 'wc-fields-factory' ); ?></option>
					<option value="email"><?php _e( 'Email', 'wc-fields-factory' ); ?></option>
					<option value="textarea"><?php _e( 'Text Area', 'wc-fields-factory' ); ?></option>
					<option value="checkbox"><?php _e( 'Check Box', 'wc-fields-factory' ); ?></option>
					<option value="radio"><?php _e( 'Radio Button', 'wc-fields-factory' ); ?></option>
					<option value="select"><?php _e( 'Select', 'wc-fields-factory' ); ?></option>	
					<option value="datepicker"><?php _e( 'Date Picker', 'wc-fields-factory' ); ?></option>			
					<option value="colorpicker"><?php _e( 'Color Picker', 'wc-fields-factory' ); ?></option>	
					<option value="file"><?php _e( 'File', 'wc-fields-factory' ); ?></option>			
				</select>
			</td>
			<td><input type="text" id="wccpf-field-type-meta-label" value="" placeholder="Label"/></td>
			<td><input type="text" id="wccpf-field-type-meta-name" value="" placeholder="Name" readonly/></td>
			<td><a href="#" class="wccpf-add-new-field button button-primary">+ <?php _e( 'Add Field', 'wc-fields-factory' ); ?></a></td>
		</tr>
	</table>

	<div class="wccpf-field-types-meta-container">
		<table class="wccpf_table">
			<tbody id="wccpf-field-types-meta-body">				
				<?php echo apply_filters( 'wccpf/render_admin_field/type=text', $post->ID ) ?>				
			</tbody>
			<tfoot id="wccpf-field-factory-footer" style="display:none">
				<tr>
					<td></td>
					<td style="text-align: right;">
						<a href="#" class="wccpf-cancel-update-field-btn button"><?php _e( 'Cancel', 'wc-fields-factory' ); ?></a>
						<a href="#" data-key="" class="button wccpf-meta-option-delete"><?php _e( 'Delete', 'wc-fields-factory' ); ?></a>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>