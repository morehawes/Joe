<?php

class Joe_Front {
	function __construct() {
		//Don't do anything if we're in the back-end
		if(is_admin()) {
			return;
		}
		
		add_action('wp_head', array($this, 'wp_head'));			
	}

	function wp_head() {
		echo '<meta name="' . Waymark_Config::get_name(true, true) . ' Version" content="' . Waymark_Config::get_version() . '" />' . "\n";	
	}
}