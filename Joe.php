<?php

//Classes
require_once('Classes/Class.php');
require_once('Classes/Request.php');

//Helpers
require_once('Helpers/Input.php');
require_once('Helpers/Cache.php');

//Core
require_once('Core/Config.php');
require_once('Core/Helper.php');
require_once('Static/Assets.php');

require_once('Core/JS.php');

require_once('Core/Settings.php');
require_once('Core/Types.php');
require_once('Core/Taxonomies.php');
require_once('Core/Shortcode.php');
require_once('Core/Admin.php');
require_once('Core/Front.php');

add_action('admin_head', function($data) {
	Joe_Helper::debug(Joe_Config::get_data(), false);
});