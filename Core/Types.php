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
			'label'                 => esc_html__($data['name_singular'], 'waymark'),
			'description'           => '',
			'labels'                => array(
				'name'                  => esc_html__($data['name_plural'] . '', 'waymark'),
				'singular_name'         => esc_html__($data['name_singular'], 'waymark'),
				'menu_name'             => esc_html__($data['name_plural'], 'waymark'),
				'name_admin_bar'        => esc_html__($data['name_singular'], 'waymark'),
				'archives'              => esc_html__($data['name_singular'] . ' Archives', 'waymark'),
				'attributes'            => esc_html__($data['name_singular'] . ' Attributes', 'waymark'),
				'parent_item_colon'     => esc_html__('Parent ' . $data['name_singular'] . ':', 'waymark'),
				'all_items'             => esc_html__('All ' . $data['name_plural'], 'waymark'),
				'add_new_item'          => esc_html__('Add New ' . $data['name_singular'], 'waymark'),
				'add_new'               => esc_html__('Add New', 'waymark'),
				'new_item'              => esc_html__('New ' . $data['name_singular'], 'waymark'),
				'edit_item'             => esc_html__('Edit ' . $data['name_singular'], 'waymark'),
				'update_item'           => esc_html__('Update ' . $data['name_singular'], 'waymark'),
				'view_item'             => esc_html__('View ' . $data['name_singular'], 'waymark'),
				'view_items'            => esc_html__('View ' . $data['name_plural'], 'waymark'),
				'search_items'          => esc_html__('Search ' . $data['name_singular'], 'waymark'),
				'not_found'             => esc_html__('Not found', 'waymark'),
				'not_found_in_trash'    => esc_html__('Not found in Trash', 'waymark'),
				'featured_image'        => esc_html__('Featured Image', 'waymark'),
				'set_featured_image'    => esc_html__('Set featured image', 'waymark'),
				'remove_featured_image' => esc_html__('Remove featured image', 'waymark'),
				'use_featured_image'    => esc_html__('Use as featured image', 'waymark'),
				'insert_into_item'      => esc_html__('Insert into ' . $data['name_singular'], 'waymark'),
				'uploaded_to_this_item' => esc_html__('Uploaded to this ' . $data['name_singular'], 'waymark'),
				'items_list'            => esc_html__($data['name_singular'] . ' list', 'waymark'),
				'items_list_navigation' => esc_html__($data['name_plural'] . ' list navigation', 'waymark'),
				'filter_items_list'     => esc_html__('Filter ' . $data['name_singular'] . ' list', 'waymark'),
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