<?php
	
class Joe_Shortcode {
	static function init() {
		if($shortcode = Joe_Config::get_item('shortcode')) {
			add_shortcode($shortcode, [ get_called_class(), 'handle_shortcode' ] );		
		}
	}
	
	public static function handle_shortcode($shortcode_data, $content = null) {}
}