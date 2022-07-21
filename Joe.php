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
 
//Classes
// require_once('Classes/Class.php');
// require_once('Classes/Request.php');
// 
// //Helpers
// require_once('Helpers/Input.php');
// require_once('Helpers/Cache.php');
// require_once('Helpers/Assets.php');
// require_once('Helpers/Config.php');
// require_once('Helpers/Helper.php');
// 
// //Core
// require_once('Core/Types.php');
// require_once('Core/Taxonomies.php');
// 
// //Admin?
// require_once('Core/Admin.php');
// require_once('Core/Menu.php');
// require_once('Core/Settings.php');
// 
// //Front?
// require_once('Core/Front.php');
// require_once('Core/Shortcode.php');

add_action('admin_head', function($data) {
 	Joe_Assets::js_inline('console.log("Joe v' . Joe_Config::get_version() . ' was here!");');
});