<?php

class Joe_Admin {
	function __construct() {
		//Admin only
		if(! is_admin()) {
			return;
		}
		
		add_action('admin_head', array($this, 'admin_head'));			
	}

	function admin_head() {
		echo '<meta name="' . Waymark_Config::get_name(true, true) . ' Version" content="' . Waymark_Config::get_version() . '" />' . "\n";	
	}
}