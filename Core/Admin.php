<?php

class Joe_Admin {

	protected $current_screen;

	function __construct() {
		//Admin only
		if(! $this->is_admin()) {
			return;
		}

		add_action('current_screen', array($this, 'get_current_screen'));	
		add_action('admin_head', array($this, 'admin_head'));			
	}
	
	function is_admin() {
		return is_admin();
	}
	
	function get_current_screen() {
		$this->current_screen = get_current_screen();
	}

	function admin_head() {
		echo '<meta name="' . Joe_Config::get_name(true, true) . ' Version" content="' . Joe_Config::get_version() . '" />' . "\n";	
	}
}