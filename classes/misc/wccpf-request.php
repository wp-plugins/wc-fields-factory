<?php 
/**
 * @author 		: Saravana Kumar K
 * @copyright	: sarkware.com
 * @todo		: Wrapper module for all wccpf related Ajax request.
 * 				  All Ajax request target for wccpf will be converted to "wccpf_request" object and
 * 				  made available to the context through "wccpf()->request".
 * 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_request {
	
	function __construct() {
		add_filter( 'wccpf/request', array( $this, 'prepare_request' ) );
	}
	
	function prepare_request() {
		if( isset( $_REQUEST["WCCPF_AJAX_PARAM"] ) ) {	
			$payload = json_decode( str_replace('\"','"', $_REQUEST["WCCPF_AJAX_PARAM"] ), true );			
			return array (
				"type" => $payload["request"],
				"context" => $payload["context"],
				"post" => $payload["post"],
				"payload" => $payload["payload"]
			);
		}
	}
	
}

new wccpf_request();

?>