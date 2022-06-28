<?php

class Joe_CSS {

	static private $chunks = array();
	
	static function init() {
		static::add_chunk('/* ' . Joe_Config::get_name(true, true) . ' v' . Joe_Config::get_version() . ' */');

		//Front
		add_action( 'wp_enqueue_scripts', [ get_called_class(), 'enqueue_styles' ] );		
		add_action( 'wp_head', [ get_called_class(), 'head' ] );		
		
		//Admin
		add_action( 'admin_enqueue_scripts', [ get_called_class(), 'enqueue_styles' ] );								
		add_action( 'admin_head', [ get_called_class(), 'head' ] );		
	}

	static function add_chunk($chunk) {	
		static::$chunks[] = $chunk . "\n";
	}

	static function head() {
		if(! sizeof(static::$chunks)) {
			return;
		}
		
		echo "\n" . '<!-- START ' . Joe_Config::get_name(true, true) . ' Head CSS -->' . "\n";
		echo '<style type="text/css">' . "\n";

		foreach(static::$chunks as $chunk) {
			 echo $chunk;
		}

		echo '</style>' . "\n";
		echo '<!-- END ' . Joe_Config::get_name(true, true) . ' Head CSS -->' . "\n\n";			
	}
	
	//Front
	static function enqueue_styles() {}		
}