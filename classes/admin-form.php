<?php 
/**
 * @author 		: Saravana Kumar K
 * @copyright	: sarkware.com
 * @todo		: One of the core class which generates all WCCPF related meta boxs in Admin Screen
 *
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_admin_form {
	
	var $settings;
	
	function __construct() {
		$this->settings = apply_filters('wccpf/get_info', 'all');
		add_action( 'admin_head-post.php', array( $this, 'wccpf_post_single_view' ) );
		add_action( 'admin_head-post-new.php',  array( $this, 'wccpf_post_single_view' ) );
		add_action( 'wccpf/admin/head', array( $this, 'wccpf_admin_head' ) );
		add_filter( 'manage_edit-wccpf_columns', array( $this, 'wccpf_columns' ) ) ;
		add_action( 'manage_wccpf_posts_custom_column', array( $this, 'wccpf_post_listing' ), 10, 2 );		
	}

	function wccpf_post_single_view() {		
		if( $this->wccpf_check_screen() ) {			
			add_meta_box('wccpf_fields', "Fields", array($this, 'wccpf_fields'), 'wccpf', 'normal', 'high');
			add_meta_box('wccpf_factory', "Fields Factory", array($this, 'wccpf_factory'), 'wccpf', 'normal', 'high');
			add_meta_box('wccpf_conditions', "Conditions", array($this, 'wccpf_logics'), 'wccpf', 'normal', 'high');
			do_action( 'wccpf/admin/head' );
			$this->wccpf_admin_enqueue_scripts();
		}				
	}
	
	function wccpf_columns( $columns ) {
	
		$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => __( 'Title' ),
				'fields' => __( 'Fields' ),
				'date' => __( 'Date' )
		);
	
		return $columns;
	}
	
	function wccpf_post_listing( $column, $post_id ) {
		global $post;
		
		switch( $column ) {
			case 'fields' : 
				$count =0;
				$keys = get_post_custom_keys( $post_id );
				
				if($keys) {
					foreach($keys as $key) {
						if( strpos($key, 'wccpf_') !== false && strpos($key, 'group_rules') === false ) {
							$count++;
						}
					}
				}					
				echo $count;				
			break;
		}
	}
	
	function wccpf_fields() {
		include( $this->settings['path'] . 'classes/views/meta_box_fields.php' );
	}
	
	function wccpf_factory() {
		include( $this->settings['path'] . 'classes/views/meta_box_factory.php' );
	}
	
	function wccpf_logics() {
		include( $this->settings['path'] . 'classes/views/meta_box_conditions.php' );
	}
	
	function wccpf_admin_enqueue_scripts() {
		if( $this->wccpf_check_screen() ) {
			wp_enqueue_script(array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-tabs',
				'jquery-ui-sortable',
				'wp-color-picker',
				'wccpf-script'		
			));				
			wp_enqueue_style(array(
				'thickbox',
				'wp-color-picker',
				'wccpf-style'
			));
		}
	}
	
	function wccpf_check_screen() {		
		return get_current_screen() -> id == "wccpf" || get_current_screen() -> id == "wccpf-options";
	}
	
	function wccpf_admin_head() {
		global $post; ?>
<script type="text/javascript">
var wccpf_var = {
	post_id : <?php echo $post->ID; ?>,
	nonce  : "<?php echo wp_create_nonce( 'wccpf_nonce' ); ?>",
	admin_url : "<?php echo admin_url(); ?>",
	ajaxurl : "<?php echo admin_url( 'admin-ajax.php' ); ?>"	 
};		
</script>
<?php
	}

}

new wccpf_admin_form();

?>