<?php
	
class Joe_Taxonomies {

	protected static $taxonomies = [];
	
	public static function init() {
		add_action( 'init', [ get_called_class(), 'register_taxonomies' ] );
	}	

	public static function register_taxonomies() {
		foreach(static::$taxonomies as $tax_key => $tax_data) {
			$taxonomy = [
				'key' =>	$tax_key,
				'type' => $tax_data['types'],
				'args' => array_merge([
					'labels'=> static::create_tax_labels($tax_data),
					'rewrite' => array(
						'slug' =>  $tax_data['slug']
					),
					'hierarchical' => true,
					'public' => true,
					'show_ui' => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'show_tagcloud' => true
				], $tax_data['args'])
			];

			register_taxonomy($taxonomy['key'], $taxonomy['type'], $taxonomy['args']);			
		}
	}	
	
	protected static function create_tax_labels($data) {
		if(! isset($data['name']['singular'])) {
			return null;
		}
		
		if(! isset($data['name']['plural'])) {
			$data['name']['plural'] = $data['name']['singular'];
		}	
		
		return [
			'name' => $data['name']['plural'],
			'singular_name' => $data['name']['singular'],
			'menu_name' => $data['name']['singular'],
			'all_items' => esc_html__('All ' . $data['name']['plural'], 'waymark'),
			'parent_item' => esc_html__('Parent', 'waymark'),
			'parent_item_colon' => esc_html__('Parent ' . $data['name']['singular'] . ':', 'waymark'),
			'new_item_name' => esc_html__('New ' . $data['name']['singular'] . ' Name', 'waymark'),
			'add_new_item' => esc_html__('Create ' . $data['name']['singular'], 'waymark'),
			'edit_item' => esc_html__('Edit ' . $data['name']['singular'], 'waymark'),
			'update_item' => esc_html__('Update ' . $data['name']['singular'], 'waymark'),
			'view_item' => esc_html__('View ' . $data['name']['singular'], 'waymark'),
			'separate_items_with_commas' => esc_html__('Separate ' . $data['name']['plural'] . ' with commas', 'waymark'),
			'add_or_remove_items' => esc_html__('Add or remove ' . $data['name']['plural'], 'waymark'),
			'choose_from_most_used' => esc_html__('Choose from the most used', 'waymark'),
			'popular_items' => esc_html__('Popular ' . $data['name']['plural'], 'waymark'),
			'search_items' => esc_html__('Search ' . $data['name']['plural'], 'waymark'),
			'not_found' => esc_html__('Not Found', 'waymark'),
			'no_terms' => esc_html__('No ' . $data['name']['plural'], 'waymark'),
			'items_list' => esc_html__($data['name']['singular'] . ' list', 'waymark'),
			'items_list_navigation' => esc_html__($data['name']['singular'] . ' list navigation', 'waymark'),
		];
	}

	
}