<?php

class Joe_Menu {

	protected $admin_url_request = '';
	
	protected $capability = 'edit_posts';
	protected $menu = [
		'links' => [
			'title' => null,
			'url' => null			
		]
	];

	function __construct() {
		$this->admin_url_request = basename($_SERVER['REQUEST_URI']);
	
		add_action('admin_menu', array($this, 'admin_menu'));		
// 		add_action('admin_menu', array($this, 'modify_menu'), 1000);			

// 		Joe_Assets::css_inline('
// .wp-has-submenu.toplevel_page_waymark-top ul.wp-submenu li.wp-first-item {
// display: none;
// }
// 		');		
	}
	
	function admin_menu() {
		//Top-level
		//https://developer.wordpress.org/reference/functions/add_menu_page/
		add_menu_page(
			Waymark_Config::get_name(true),	//Page Title
			Waymark_Config::get_name(true),	//Menu Title
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

		//Maps
//     add_submenu_page(Joe_Config::get_item('menu_slug'), esc_html__('Maps', 'waymark'), esc_html__('Maps', 'waymark'), 'edit_posts', 'edit.php?post_type=waymark_map'); 
//     add_submenu_page(Joe_Config::get_item('menu_slug'), esc_html__('New Map', 'waymark'), esc_html__('New Map', 'waymark'), 'edit_posts', 'post-new.php?post_type=waymark_map'); 
// 
// 		//Collections
//     add_submenu_page(Joe_Config::get_item('menu_slug'), esc_html__('Collections', 'waymark'), esc_html__('Collections', 'waymark'), 'manage_categories', 'edit-tags.php?taxonomy=waymark_collection&post_type=waymark_map'); 
// 
// 		//Queries
// 		if(Waymark_Config::get_setting('query', 'features', 'enable_taxonomy')) {
// 			add_submenu_page(Joe_Config::get_item('menu_slug'), esc_html__('Queries', 'waymark'), esc_html__('Queries', 'waymark'), 'manage_categories', 'edit-tags.php?taxonomy=waymark_query&post_type=waymark_map'); 
// 		}
// 
// 		//Help
// 		add_submenu_page(Joe_Config::get_item('menu_slug'), esc_html__('Documentation', 'waymark'), esc_html__('Docs', 'waymark') . ' <i style="font-size:12px" class="fa fa-external-link"></i>', 'edit_posts', 'https://www.waymark.dev/docs/');
// 		
	}	
}

class Waymark_Menu extends Joe_Menu {

	function __construct() {
		parent::__construct();
		
		$this->menu['links'] = [
			[
				'title' => 'Maps',
				'url' => admin_url('edit.php?post_type=waymark_map')
			],
			[
				'title' => 'Collections',
				'url' => admin_url('edit-tags.php?taxonomy=waymark_collection&post_type=waymark_map')
			],			
			[
				'title' => 'Help',
				'url' => 'https://www.waymark.dev/help'			
			]
		];
	}
	
	function modify_menu() {
    global $menu, $submenu, $pagenow;   
    
		//Waymark menu
		foreach($menu  as &$m) {
			if($m[2] == Joe_Config::get_item('menu_slug')) {
				//Collections
				if($pagenow == 'edit-tags.php' && array_key_exists('taxonomy', $_GET) && $_GET['taxonomy'] == 'waymark_collection') {
//					$m[4] .= ' wp-has-current-submenu';						
				}
				
				//Map Posts				
				if($pagenow == 'post.php' && array_key_exists('post', $_GET))  {
					$post_type = get_post_type($_GET['post']);
					if(Waymark_Config::is_custom_type($post_type)) {
						$m[4] .= ' wp-has-current-submenu';						
					}
				}
			}
		}
		
		//Waymark sub menu
		if(array_key_exists(Joe_Config::get_item('menu_slug'), $submenu)) {	
			foreach($submenu[Joe_Config::get_item('menu_slug')] as &$sub_menu) {					
				//Hide New Type links
				if(in_array($sub_menu[0], array(esc_html__('New Map', 'waymark')))) {
					$sub_menu[4] = 'hidden';
				}					
				
				//Make "Object" link active when adding new
				if(
					$sub_menu[0] == esc_html__('Maps', 'waymark') && $this->admin_url_request == 'post-new.php?post_type=waymark_map'
					||
					$sub_menu[0] == esc_html__('Collections', 'waymark') && (strpos($this->admin_url_request, 'edit-tags.php?taxonomy=waymark_collection') !== false)
					) {
					$sub_menu[4] = 'current';											
				}
			}
		}		
	}
}
new Waymark_Menu;
