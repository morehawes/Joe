<?php

class Joe_JS {
	
	static protected $chunks = array();
	static protected $calls = array();
	
	static function init() {
		//Both
		add_action('init', function() {
			Joe_JS::add_chunk('//' . Joe_Config::get_name() . ' v' . Joe_Config::get_version());
		});
		
		//Front
		add_action( 'wp_footer', [ get_called_class(), 'footer' ] );					
		add_action( 'wp_enqueue_scripts', [ get_called_class(), 'wp_enqueue_scripts' ] );							
		
		//Admin		
		add_action( 'admin_footer', [ get_called_class(), 'footer' ] );					
		add_action( 'admin_enqueue_scripts', [ get_called_class(), 'admin_enqueue_scripts'] );										
	}

	static function add_chunk($chunk) {	
		if((! in_array($chunk[strlen($chunk)-1], array(';', "\n")) && (strpos($chunk, '//') === false))) {
			$chunk .= ';';
		}
		static::$chunks[] = $chunk;
	}

	static function add_call($call) {	
		if(! in_array($call, static::$calls)) {
			static::$calls[] = $call;			
		}
	}

	static function footer() {
		echo "\n" . '<!-- START ' . Joe_Config::get_name(true, true) . ' Footer JS -->' . "\n";
		echo '<script type="text/javascript">' . "\n";
		//Lines
		foreach(static::$chunks as $chunk) {
			 echo $chunk . "\n";
		}
		
		//Calls
		if(sizeof(static::$calls)) {
			echo "\n" . 'jQuery(document).ready(function() {' . "\n";
			foreach(static::$calls as $call) {
				echo "	" . $call . ";\n";
			}		
			echo '});' . "\n";
		}
		echo '</script>' . "\n";
		echo '<!-- END ' . Joe_Config::get_name(true, true) . ' Footer JS -->' . "\n\n";			
	}	
	
	//Front
	
	static function wp_enqueue_scripts() {}	
	
	//Admin
	
	static function admin_enqueue_scripts() {}
}