<?php

add_filter('the_content', function($c) {
	return '<p style="color:red">Joe!</p>' . $c;
});

require_once('Helpers/Joe_Helper.php');
require_once('Helpers/Joe_Input.php');
require_once('Helpers/Joe_Cache.php');