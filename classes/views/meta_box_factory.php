<?php 
	global $post;
	
	if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<div id="wccpf_fields_factory" action="POST">

	<table class="wccpf_table wccpf_fields_factory_header">
		<tr>
			<td>
				<select class="select" id="wccpf-field-type-meta-type">
					<option value="text">Text</option>
					<option value="number">Number</option>
					<option value="email">Email</option>
					<option value="textarea">Text Area</option>
					<option value="checkbox">Check Box</option>
					<option value="radio">Radio Button</option>
					<option value="select">Select</option>				
				</select>
			</td>
			<td><input type="text" id="wccpf-field-type-meta-label" value="" placeholder="Label"/></td>
			<td><input type="text" id="wccpf-field-type-meta-name" value="" placeholder="Name" readonly/></td>
			<td><a href="#" class="wccpf-add-new-field button button-primary">+ Add Field</a></td>
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
						<a href="#" class="wccpf-cancel-update-field-btn button">Cancel</a>
						<a href="#" data-key="" class="button wccpf-meta-option-delete">Delete</a>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>