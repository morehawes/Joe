<?php

//Helpers
require_once('Helpers/Helper.php');
require_once('Helpers/Input.php');
require_once('Helpers/Cache.php');

//Core
require_once('Core/Config.php');
require_once('Core/Settings.php');

add_action('admin_head', function($data) {
	Joe_Helper::debug(Joe_Config::get_data(), false);
});