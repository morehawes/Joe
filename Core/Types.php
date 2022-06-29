<?php
	
class Joe_Types {
	protected static $types;
	
	public static function init() {
		add_action( 'init', [ get_called_class(), 'register_types' ], 0 );			
	}	
	
	protected static function create_post_type_args($data = []) {
		if(! isset($data['slug']) || ! isset($data['name_singular'])) {
			return null;
		}
		
		if(! isset($data['name_plural'])) {
			$data['name_plural'] = $data['name_singular'];
		}		
		
		return [
			'label'                 => esc_html__($data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
			'description'           => '',
			'labels'                => array(
				'name'                  => esc_html__($data['name_plural'] . '', Joe_Config::get_item('plugin_text_domain')),
				'singular_name'         => esc_html__($data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'menu_name'             => esc_html__($data['name_plural'], Joe_Config::get_item('plugin_text_domain')),
				'name_admin_bar'        => esc_html__($data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'archives'              => esc_html__($data['name_singular'] . ' Archives', Joe_Config::get_item('plugin_text_domain')),
				'attributes'            => esc_html__($data['name_singular'] . ' Attributes', Joe_Config::get_item('plugin_text_domain')),
				'parent_item_colon'     => esc_html__('Parent ' . $data['name_singular'] . ':', Joe_Config::get_item('plugin_text_domain')),
				'all_items'             => esc_html__('All ' . $data['name_plural'], Joe_Config::get_item('plugin_text_domain')),
				'add_new_item'          => esc_html__('Add New ' . $data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'add_new'               => esc_html__('Add New', Joe_Config::get_item('plugin_text_domain')),
				'new_item'              => esc_html__('New ' . $data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'edit_item'             => esc_html__('Edit ' . $data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'update_item'           => esc_html__('Update ' . $data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'view_item'             => esc_html__('View ' . $data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'view_items'            => esc_html__('View ' . $data['name_plural'], Joe_Config::get_item('plugin_text_domain')),
				'search_items'          => esc_html__('Search ' . $data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'not_found'             => esc_html__('Not found', Joe_Config::get_item('plugin_text_domain')),
				'not_found_in_trash'    => esc_html__('Not found in Trash', Joe_Config::get_item('plugin_text_domain')),
				'featured_image'        => esc_html__('Featured Image', Joe_Config::get_item('plugin_text_domain')),
				'set_featured_image'    => esc_html__('Set featured image', Joe_Config::get_item('plugin_text_domain')),
				'remove_featured_image' => esc_html__('Remove featured image', Joe_Config::get_item('plugin_text_domain')),
				'use_featured_image'    => esc_html__('Use as featured image', Joe_Config::get_item('plugin_text_domain')),
				'insert_into_item'      => esc_html__('Insert into ' . $data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'uploaded_to_this_item' => esc_html__('Uploaded to this ' . $data['name_singular'], Joe_Config::get_item('plugin_text_domain')),
				'items_list'            => esc_html__($data['name_singular'] . ' list', Joe_Config::get_item('plugin_text_domain')),
				'items_list_navigation' => esc_html__($data['name_plural'] . ' list navigation', Joe_Config::get_item('plugin_text_domain')),
				'filter_items_list'     => esc_html__('Filter ' . $data['name_singular'] . ' list', Joe_Config::get_item('plugin_text_domain')),
			),
			'supports'              => array('title', 'author', 'revisions', 'thumbnail'),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => array($data['slug'], 'with_front' => false),
			'capability_type'       => 'post'			
		];
	}
	
	public static function register_types() {
		$types = array();
		
		foreach(static::$types as $type_id => $type_data) {
			$types[] = $type_id;
						
			register_post_type($type_id, $type_data);			
		}

		Joe_Config::set_item('custom_types', $types);			
	}
// 
// 	private static function delete_posts() {
// 		//For each custom type
// 		foreach(static::$types as $type_id => $type_data) {
// 			//Get posts
// 			$posts = get_posts(array(
// 				'post_type' => $type_id
// 			));
// 			
// 			//For each post
// 			foreach($posts as $post) {
// 				//Force delete post
// 				wp_delete_post($post->ID, true);
// 			}
// 		}
// 	}
}