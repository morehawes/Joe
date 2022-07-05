<?php
	
class Joe_Taxonomies {

	protected $taxonomies = [];
	
	public function __construct() {
		add_action( 'init', [ $this, 'register_taxonomies' ] );
	}	

	public function register_taxonomies() {
		foreach($this->taxonomies as $tax_key => $tax_data) {
			$taxonomy = [
				'key' =>	$tax_key,
				'type' => $tax_data['types'],
				'args' => array_merge([
					'labels'=> $this->create_tax_labels($tax_data),
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
	
	protected function create_tax_labels($data) {
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
			'all_items' => esc_html__('All ' . $data['name']['plural'], Joe_Config::get_item('plugin_text_domain')),
			'parent_item' => esc_html__('Parent', Joe_Config::get_item('plugin_text_domain')),
			'parent_item_colon' => esc_html__('Parent ' . $data['name']['singular'] . ':', Joe_Config::get_item('plugin_text_domain')),
			'new_item_name' => esc_html__('New ' . $data['name']['singular'] . ' Name', Joe_Config::get_item('plugin_text_domain')),
			'add_new_item' => esc_html__('Create ' . $data['name']['singular'], Joe_Config::get_item('plugin_text_domain')),
			'edit_item' => esc_html__('Edit ' . $data['name']['singular'], Joe_Config::get_item('plugin_text_domain')),
			'update_item' => esc_html__('Update ' . $data['name']['singular'], Joe_Config::get_item('plugin_text_domain')),
			'view_item' => esc_html__('View ' . $data['name']['singular'], Joe_Config::get_item('plugin_text_domain')),
			'separate_items_with_commas' => esc_html__('Separate ' . $data['name']['plural'] . ' with commas', Joe_Config::get_item('plugin_text_domain')),
			'add_or_remove_items' => esc_html__('Add or remove ' . $data['name']['plural'], Joe_Config::get_item('plugin_text_domain')),
			'choose_from_most_used' => esc_html__('Choose from the most used', Joe_Config::get_item('plugin_text_domain')),
			'popular_items' => esc_html__('Popular ' . $data['name']['plural'], Joe_Config::get_item('plugin_text_domain')),
			'search_items' => esc_html__('Search ' . $data['name']['plural'], Joe_Config::get_item('plugin_text_domain')),
			'not_found' => esc_html__('Not Found', Joe_Config::get_item('plugin_text_domain')),
			'no_terms' => esc_html__('No ' . $data['name']['plural'], Joe_Config::get_item('plugin_text_domain')),
			'items_list' => esc_html__($data['name']['singular'] . ' list', Joe_Config::get_item('plugin_text_domain')),
			'items_list_navigation' => esc_html__($data['name']['singular'] . ' list navigation', Joe_Config::get_item('plugin_text_domain')),
		];
	}
}