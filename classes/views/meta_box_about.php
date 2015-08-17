<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

$version = apply_filters('acf/get_info', 'version');
$dir = apply_filters('acf/get_info', 'dir');
$path = apply_filters('acf/get_info', 'path');

?>

<div id="wccpf-col-right">

	<div class="wp-box">
		<div class="inner">
			<h2>WC Fields Factory <?php echo $version; ?></h2>
						
			<h3>Resources</h3>
			<ul>
				<li><a href="http://sarkware.com" target="_blank">Documentation</a></li>				
			</ul>
		</div>
		<div class="footer footer-blue">
			<ul class="hl">
				<li>Developed by <a href="http://iamsark.com" title="Iam Sark" target="_blank">Saravana Kumar K</a></li>
			</ul>
		</div>
	</div>
</div>