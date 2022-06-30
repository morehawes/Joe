<?php

class Joe_Assets {

	static private $head = [
		'css' => [
			'inline' => [],
			'enqueue' => []
		],
		'js' => [
			'enqueue' => []
		]		
	];

	static private $foot = [
		'js' => [
			'inline' => [],
			'enqueue' => []
		]		
	];
	
	static function init() {
		static::css_inline('/* ' . Joe_Config::get_name(true, true) . ' v' . Joe_Config::get_version() . ' */');

		//Front
		add_action( 'wp_enqueue_scripts', [ get_called_class(), 'enqueue_styles' ] );		
		add_action( 'wp_head', [ get_called_class(), 'head' ] );		
		
		//Admin
		add_action( 'admin_enqueue_scripts', [ get_called_class(), 'enqueue_styles' ] );								
		add_action( 'admin_head', [ get_called_class(), 'head' ] );		
	}

	static function css_inline($css) {	
		static::$head['css']['inline'][] = $css . "\n";
	}

	static function css_enqueue($css) {	
		static::$head['css']['enqueue'][] = $css . "\n";
	}

	static function head() {
		if(! sizeof(static::$head['css']['inline'])) {
			return;
		}
		
		echo "\n" . '<!-- START ' . Joe_Config::get_name(true, true) . ' Head CSS -->' . "\n";
		echo '<style type="text/css">' . "\n";

		foreach(static::$head['css']['inline'] as $css) {
			 echo $css;
		}

		echo '</style>' . "\n";
		echo '<!-- END ' . Joe_Config::get_name(true, true) . ' Head CSS -->' . "\n\n";			
	}
	
	//Front
	static function enqueue_styles() {
		if(! sizeof(static::$head['css']['enqueue'])) {
			return;
		}
		
		$count = 1;
		foreach(static::$head['css']['enqueue'] as $url) {
			$id = Joe_Helper::slug_prefix('css_' . $count);
			
			wp_register_style($id, $url, [], Joe_Config::get_version());
			wp_enqueue_style($id);
			
			$count++;			
		}

		//Front
		if(! is_admin()) {
			//Elevation
			static::css_inline('
div.waymark-container .waymark-map .elevation-polyline { stroke: ' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_colour') . '; }
div.waymark-container .waymark-elevation .elevation-control.elevation .area { fill: ' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_colour') . ';	}
');
		//Admin		
		} else {
			wp_register_style('waymark_admin_css', Waymark_Helper::asset_url('css/admin.min.css'), array(), Waymark_Config::get_version());
			wp_enqueue_style('waymark_admin_css');	
		}		
	}
}
Joe_Assets::init();