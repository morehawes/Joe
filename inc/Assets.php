<?php

class Joe_Assets {

	static private $head = [
		'css' => [
			'inline' => [],
			'enqueue' => []
		],
		'js' => [
			'inline' => [],
			'enqueue' => []
		]		
	];

	static private $foot = [
		'js' => [
			'inline' => [],
			'onready' => [],
			'enqueue' => []
		]		
	];
	
	static function init() {
		//Front
		add_action( 'wp_enqueue_scripts', [ self, 'enqueue_styles' ] );		
		add_action( 'wp_enqueue_scripts', [ self, 'enqueue_scripts' ] );		
		add_action( 'wp_head', [ self, 'head' ] );		
		add_action( 'wp_footer', [ self, 'footer' ] );		
		
		//Admin
		add_action( 'admin_enqueue_scripts', [ self, 'enqueue_styles' ] );								
		add_action( 'admin_enqueue_scripts', [ self, 'enqueue_scripts' ] );								
		add_action( 'admin_head', [ self, 'head' ] );		
		add_action( 'admin_footer', [ self, 'footer' ] );		
	}
	
	// CSS
	
	static function css_inline($css = '') {	
		if($css) {
			self::$head['css']['inline'][] = $css . "\n";
		}
	}

	static function css_enqueue($url = '') {	
		if($url) {
			self::$head['css']['enqueue'][] = $url;
		}
	}
	
	// JS

	static function js_inline($js = '') {	
		if($js) {
			if((! in_array($js[strlen($js)-1], array(';', "\n")) && (strpos($js, '//') === false))) {
				$js .= ';';
			}
			self::$foot['js']['inline'][] = $js;
		}
	}

	static function js_onready($js = '') {	
		if($js) {
			self::$foot['js']['onready'][] = $js;
		}
	}
	
	static function js_enqueue($enqueue = []) {	
		if($enqueue) {
			//Default
			if(! isset($enqueue['in_footer'])) {
				$enqueue['in_footer'] = true;
			}

			if($enqueue['in_footer']) {
				self::$foot['js']['enqueue'][] = $enqueue;			
			} else {
				self::$head['js']['enqueue'][] = $enqueue;			
			}
		}
	}

	static function head() {
		if(! sizeof(self::$head['css']['inline']) && ! sizeof(self::$head['js']['inline'])) {
			return;
		}
	
		echo "\n" . '<!-- START ' . Joe_Config::get_name(true, true) . ' Head CSS -->' . "\n";
		echo '<style type="text/css">' . "\n";

		echo '/* ' . Joe_Config::get_name(true, true) . ' v' . Joe_Config::get_version() . ' */' . "\n";

		foreach(self::$head['css']['inline'] as $css) {
			 echo $css;
		}

		echo '</style>' . "\n";
		echo '<!-- END ' . Joe_Config::get_name(true, true) . ' Head CSS -->' . "\n\n";			

		echo '</script>' . "\n";
		echo '<!-- END ' . Joe_Config::get_name(true, true) . ' Head JS -->' . "\n\n";			
	}
	
	static function footer() {
// 		if(! sizeof(self::$foot['js']['inline']) + sizeof(self::$foot['js']['onready'])) {
// 			return;
// 		}
			
		echo "\n" . '<!-- START ' . Joe_Config::get_name(true, true) . ' Footer JS -->' . "\n";
		echo '<script type="text/javascript">' . "\n";

		echo '	//' . Joe_Config::get_name(true, true) . ' v' . Joe_Config::get_version() . "\n";

		//Inline
		foreach(self::$foot['js']['inline'] as $js) {
			 echo $js;
		}
		
		//Calls
		if(sizeof(self::$foot['js']['onready'])) {
			echo "\n" . 'jQuery(document).ready(function() {' . "\n";
			foreach(self::$foot['js']['onready'] as $js) {
				echo "	" . $js . ";\n";
			}		
			echo '});' . "\n";
		}
		echo '</script>' . "\n";
		echo '<!-- END ' . Joe_Config::get_name(true, true) . ' Footer JS -->' . "\n\n";			
	}	
	
	static function enqueue_styles() {
		if(! sizeof(self::$head['css']['enqueue'])) {
			return;
		}
		
		$count = 1;
		foreach(self::$head['css']['enqueue'] as $url) {
			$id = Joe_Helper::slug_prefix($count);
			
			wp_register_style($id, $url, [], Joe_Config::get_version());
			wp_enqueue_style($id);
			
			$count++;			
		}
	}

	static function enqueue_scripts() {

		$enqueues = array_merge(
			self::$foot['js']['enqueue'],
			self::$head['js']['enqueue']
		);
		
		if(! sizeof($enqueues)) {
			return;
		}

		$count = 1;
		foreach($enqueues as $enqueue) {
			//URL
			if(! isset($enqueue['url'])) {
				continue;
			}
			
			//ID
			if(! isset($enqueue['id']) || ! $enqueue['id']) {
				$enqueue['id'] = Joe_Helper::slug_prefix($count);			
			}
			
			//Deps
			if(! isset($enqueue['deps']) || ! sizeof($enqueue['deps'])) {
				$enqueue['deps'] = [];			
			}			

			//Footer
			if(! isset($enqueue['in_footer'])) {
				$enqueue['in_footer'] = true;
			}	
			
			//Register
			wp_register_script($enqueue['id'], $enqueue['url'], $enqueue['deps'], Joe_Config::get_version(), $enqueue['in_footer']);		
			
			//Localize
			if(isset($enqueue['data']) && sizeof($enqueue['data'])) {
				wp_localize_script($enqueue['id'], $enqueue['id'], $enqueue['data']);
			}
			
			//Enqueue
			wp_enqueue_script($enqueue['id']);								

			$count++;			
		}
	}	
}