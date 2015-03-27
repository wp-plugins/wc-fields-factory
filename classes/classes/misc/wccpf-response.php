<?php 
/**
 * @author 		: Saravana Kumar K
 * @copyright	: sarkware.com
 * @todo		: Wrapper module for all wccpf related Ajax response.
 * 				  All Ajax response from wccpf will be converted to "wccpf_response" object and
 * 				  made available to the context through "wccpf()->response".
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_response {	
	
	function __construct() {
		add_filter( 'wccpf/response', array( $this, 'prepare_response' ), 5, 3 );
	}	
	
	function prepare_response( $status, $msg, $data ) {
		return json_encode( array ( 
			"status" => $status, 
			"message"=>$msg, 
			"data"=>$data )
		);
	}	
	
}

new wccpf_response();

?>