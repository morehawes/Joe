<?php

add_filter('the_content', function($c) {
	return '<p style="color:red">Joe!</p>' . $c;
});

//Helpers
require_once('Helpers/Helper.php');
require_once('Helpers/Input.php');
require_once('Helpers/Cache.php');

//Core
require_once('Core/Config.php');