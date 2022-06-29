<?php

//Classes
require_once('Classes/Class.php');

//Helpers
require_once('Helpers/Helper.php');
require_once('Helpers/Input.php');
require_once('Helpers/Cache.php');
require_once('Helpers/JS.php');
require_once('Helpers/CSS.php');

//Core
require_once('Core/Config.php');
require_once('Core/Settings.php');
require_once('Core/Types.php');
require_once('Core/Taxonomies.php');
require_once('Core/Shortcode.php');

add_action('admin_head', function($data) {
	Joe_Helper::debug(Joe_Config::get_data(), false);
});