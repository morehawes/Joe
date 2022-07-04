<?php

class Joe_Menu {

	protected $capability = 'edit_posts';
	protected $menu = [
		'links' => [
			'title' => null,
			'url' => null			
		]
	];

	function __construct() {
		add_action('admin_init', array($this, 'create_menu'), 11);		
		add_action('admin_menu', array($this, 'modify_menu'), 11);		
	}
	
	function create_menu() {
		//Top-level
		//https://developer.wordpress.org/reference/functions/add_menu_page/
		add_menu_page(
			Joe_Config::get_name(true),	//Page Title
			Joe_Config::get_name(true),	//Menu Title
			$this->capability,							//Capability
			Joe_Config::get_item('menu_slug'),								//Menu Slug
			'',															//Callback
			'none',													//Icon URL
			21															//Position
		);		
		
		//https://developer.wordpress.org/reference/functions/add_submenu_page/
		foreach($this->menu['links'] as $page) {
		 add_submenu_page(
				Joe_Config::get_item('menu_slug'),								//Parent Slug 
				$page['title'],									//Page Title
				$page['title'],									//Menu Title
				$this->capability,							//Capability
				$page['url'],										//Menu Slug (URL)
				//Callback
				//Position
			);
		}
	}	

	function modify_menu() {
    global $menu, $submenu;   
    
		//Waymark menu
		foreach($menu  as &$m) {
			if($m[2] == Joe_Config::get_item('menu_slug')) {
				if(! isset($m[4])) {
					$m[4] = '';
				} else {
					$m[4] = ' ';												
				}
			
				$m[4] .= Joe_Helper::css_prefix('admin-menu');
			}
		}
		
		//Waymark sub menu
		if(array_key_exists(Joe_Config::get_item('menu_slug'), $submenu)) {	
			foreach($submenu[Joe_Config::get_item('menu_slug')] as &$sub_menu) {
				$sub_menu[4] = Joe_Helper::css_prefix('submenu-item');						
			}
		}				
	}	
}