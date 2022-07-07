<?php

class Joe_Admin {

	protected $current_screen;

	function __construct() {
		//Admin only
		if(! is_admin()) {
			return;
		}

		add_action('admin_init', array($this, 'load_assets'));
		add_action('current_screen', array($this, 'get_current_screen'));	
		add_action('admin_head', array($this, 'admin_head'));			
	}
	
	function load_assets() {
 		Joe_Assets::js_onready('jQuery("body").addClass("joe-admin");');						 		
 		
		//Enqueue
		Joe_Assets::css_enqueue(Joe_Helper::plugin_url('Joe/Assets/css/admin.min.css'));			

		Joe_Assets::js_enqueue([
			'id' => 'joe_admin_js',
			'url' => Joe_Helper::plugin_url('Joe/Assets/js/admin.min.js'),
			'deps' => [ 
				'jquery',
				'jquery-ui-sortable',
				'jquery-effects-core',
 				'wp-color-picker'
			],
			'data' => [
				'multi_value_seperator' => Joe_Config::get_item('multi_value_seperator'),			
				'lang' => [
					//Editor
					'repeatable_delete_title' => esc_attr__('Remove!', 'waymark'),
					'error_message_prefix' => Joe_Config::get_name() . ' ' . esc_attr__('Error', 'waymark'),	
					'info_message_prefix' => Joe_Config::get_name() . ' ' . esc_attr__('Info', 'waymark'),
					'success_message_prefix' => Joe_Config::get_name() . ' ' . esc_attr__('Success', 'waymark'),
					'warning_message_prefix' => Joe_Config::get_name() . ' ' . esc_attr__('Warning', 'waymark')
				]						
			]
		]);				
	}	
	
	function get_current_screen() {
		$this->current_screen = get_current_screen();
	}

	function admin_head() {
		echo '<meta name="' . Joe_Config::get_name(true, true) . ' Version" content="' . Joe_Config::get_version() . '" />' . "\n";	
	}
}