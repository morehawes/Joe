<?php

/**
  * ===============================
  *              Joe
  *          by Joe Hawes
  *       www.morehawes.co.uk
	* ===============================
  **/

spl_autoload_register(function($class_name) {
	if(strpos($class_name, 'Joe_') === 0) {
		require 'inc/' . str_replace('Joe_', '', $class_name . '.php') ;	
	}
});

add_action('admin_head', function($data) {
 	Joe_Log::add('Joe v' . Joe_Config::get_version() . ' was here!', 'info', 'plugin_init');
});