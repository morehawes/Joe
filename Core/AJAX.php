<?php

class Joe_AJAX {
	function __construct() {
		//Add nonce
		add_action('init', function() {
			Joe_JS::add_chunk('var ' . Joe_Config::get_item('plugin_slug') . '_security = "' . wp_create_nonce(Joe_Config::get_item('nonce_string')) . '";');					
		});
	}
}