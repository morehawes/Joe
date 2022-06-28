<?php

class Waymark_CSS {

	static private $chunks = array();
	
	static function init() {
		self::add_chunk('/* ' . Waymark_Config::get_name(true, true) . ' v' . Waymark_Config::get_version() . ' */');

		//Front
		add_action( 'wp_enqueue_scripts', [ get_called_class(), 'enqueue_styles' ] );		
		add_action( 'wp_head', [ get_called_class(), 'head' ] );		
		
		//Admin
		add_action('admin_enqueue_scripts', [ get_called_class(), 'enqueue_scripts' ] );								
		add_action('admin_head', [ get_called_class(), 'head' ] );		
	}

	static function add_chunk($chunk) {	
		self::$chunks[] = $chunk . "\n";
	}

	static function head() {
		if(! sizeof(self::$chunks)) {
			return;
		}
		
		echo "\n" . '<!-- START ' . Waymark_Config::get_name(true, true) . ' Head CSS -->' . "\n";
		echo '<style type="text/css">' . "\n";

		foreach(self::$chunks as $chunk) {
			 echo $chunk;
		}

		echo '</style>' . "\n";
		echo '<!-- END ' . Waymark_Config::get_name(true, true) . ' Head CSS -->' . "\n\n";			
	}
	
	//Front

	static function enqueue_styles() {
		wp_register_style('waymark_front_css', Waymark_Helper::asset_url('css/front.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark_front_css');	

		wp_register_style('waymark_admin_css', Waymark_Helper::asset_url('css/admin.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark_admin_css');	

		//CSS
		self::add_chunk('
div.waymark-container .waymark-map .elevation-polyline { stroke: ' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_colour') . '; }
div.waymark-container .waymark-elevation .elevation-control.elevation .area { fill: ' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_colour') . ';	}
');
	}	

	//Admin 

	static function enqueue_scripts() {
		//CSS
		wp_register_style('waymark_admin_css', Waymark_Helper::asset_url('css/admin.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark_admin_css');	
	}
	
	static function http_render() {
		header('Content-Type: text/css');
		
		foreach(self::$chunks as $chunk) {
			 echo $chunk . "\n";
		}

		die;		
	}		
}

Waymark_CSS::init();