<?php

class Joe_Settings {
	protected static $current_settings = [];
	
	public static $tabs = [];	
	public static $settings_nav = [];


	public static function init() {
		//Execute action?
// 		if(sizeof($_POST)) {
// 			//Clear cache
// 			if(isset($_POST[Joe_Config::get_item('settings_id')]['advanced']['performance']['clear_cache'])) {	
// 				static::execute_action('clear_cache');			
// 			}
// 		}
		
		add_action( 'admin_init', [ 'Joe_Settings', 'register_settings'] );				
    add_action( 'admin_notices', [ 'Joe_Settings', 'admin_notices' ] );	
	}

	public static function get_settings() {
		return static::$current_settings;
	}


	public static function register_settings(){
		register_setting( Joe_Config::get_item('settings_id'), Joe_Config::get_item( 'settings_id' ), [ get_called_class() , 'sanitize_callback' ] );

		//For each tab		
		foreach(static::$tabs as $tab_key => $tab_data) {		
			//For each section
			foreach($tab_data['sections'] as $section_key => $section_data) {		
				//Set if blank if unset		
				$section_data['title'] = (isset($section_data['title'])) ? $section_data['title'] : '';
				
				//Create section
				add_settings_section($section_key, $section_data['title'], [ get_called_class(), 'section_text' ] , Joe_Config::get_item('settings_id'));		
				
				//For each field in section
				if(is_array($section_data['fields']) && sizeof($section_data['fields'])) {
					foreach($section_data['fields'] as $field) {
						//Get set_value
						if(array_key_exists($tab_key, static::$current_settings) && array_key_exists($section_key, static::$current_settings[$tab_key])) {
							if(array_key_exists($field['name'], static::$current_settings[$tab_key][$section_key])) {
								$field['set_value'] = static::$current_settings[$tab_key][$section_key][$field['name']];
							}
						}
						
						//Modify name for multi-dimensional array
						$field['name'] = Joe_Config::get_item('settings_id') . '[' . $tab_key . '][' . $section_key . '][' . $field['name'] . ']';
						
						//Repeatable section
						if(isset($section_data['repeatable']) && $section_data['repeatable']) {
							//Get count
							$repeatable_count = Joe_Helper::get_section_repeatable_count($section_data);
							
							//Must be an array
							if(! is_array($field['default']) ) {
								//Make array
								$field['default'] = Joe_Helper::convert_single_value_to_array($field['default']);
							}
							
							//Array size must match
							if(sizeof($field['default']) < $repeatable_count) {
								//Pad
								$field['default'] = array_pad($field['default'], $repeatable_count, $field['default'][0]);	 							
							}							
						}	

						add_settings_field($field['name'], $field['title'], [ get_called_class(), 'create_input' ], Joe_Config::get_item('settings_id'), $section_key, $field);														
					}						
				}			
			}			
		}
	}

	public static function admin_notices() {	
		if(isset($_GET['settings-updated'])) {
			//Settings updates
			if($_GET['settings-updated'] == 'true') {
				echo '<div class="' . Joe_Helper::css_prefix() . 'notice notice notice-success is-dismissible"><p>' . esc_html__('Settings Updated', Joe_Config::get_item('plugin_text_domain')) . '.</p></div>';				
			}

//Action			
// 			 elseif($_GET['settings-updated'] == 'joe_action') {
// 				echo '<div class="' . Joe_Helper::css_prefix() . 'notice notice notice-success is-dismissible"><p>' . esc_html__('Action Complete', Joe_Config::get_item('plugin_text_domain')) . '.</p></div>';				
// 			}
		}
	}

	public static function content_admin_page() {
		echo '<div id="' . Joe_Helper::css_prefix() . 'admin-container">' . "\n";

		echo Joe_Helper::plugin_about();

		echo '	<div class="card">' . "\n";	
// 		echo '		<h1>' . esc_html__('Settings', Joe_Config::get_item('plugin_text_domain')) . '</h1>' . "\n";

		//Tabs
		$active_content = (isset($_GET['content'])) ? $_GET['content'] : Joe_Config::get_item('settings_default_tab');
		static::settings_nav($active_content);

		//Open form
		echo '		<form action="' . admin_url('options.php') . '" method="post">' . "\n";
		settings_fields(Joe_Config::get_item('settings_id'));

		//For each tab		
		foreach(static::$tabs as $tab_key => $tab_data) {
			$style = '';
// 			if($active_tab != $tab_key) {
// 				$style = ' style="display:none;"';
// 			}
			echo '	<div class="' . Joe_Helper::css_prefix() . 'settings-tab ' . Joe_Helper::css_prefix() . 'settings-tab-' . $tab_key . '"' . $style . '>' . "\n";

			//Tab description?
			if(array_key_exists('description', $tab_data)) {
				echo '	<div class="' . Joe_Helper::css_prefix() . 'settings-tab-description">' . $tab_data['description'] . '</div>' . "\n";
			}

			//For each section
			foreach($tab_data['sections'] as $section_key => $section_data) {
				$class = (isset($section_data['class'])) ? ' ' . $section_data['class'] : '';
				echo '		<div class="' . Joe_Helper::css_prefix() . 'settings-section ' . Joe_Helper::css_prefix() . 'settings-section-' . $section_key . $class . '">' . "\n";
				
				//Help
				if(array_key_exists('help', $section_data) && isset($section_data['help']['url'])) {
					$help_text = (isset($section_data['help']['text'])) ? $section_data['help']['text'] : 'View Help &raquo;';

					echo '		<a class="' . Joe_Helper::css_prefix() . 'docs-link button" href="' . $section_data['help']['url'] . '" target="_blank">' . $help_text . '</a>' . "\n";				
				}
				
				//Title
				if(isset($section_data['title'])) {
					echo '		<h2>' . $section_data['title'] . '</h2>' . "\n";
				}

				//Description
				if(array_key_exists('description', $section_data)) {
					echo '		<div class="' . Joe_Helper::css_prefix() . 'settings-section-description">' . $section_data['description'] . '</div>' . "\n";
				}		
				
				//Repeatable?
				if(array_key_exists('repeatable', $section_data) && $section_data['repeatable']) {
					echo '<div class="' . Joe_Helper::css_prefix() . 'repeatable" data-count="0">' . "\n";
				}
				
        echo '		<table class="form-table">' . "\n";
        do_settings_fields(Joe_Config::get_item('settings_id'), $section_key);					
        echo '		</table>' . "\n";        

				//Repeatable?
				if(array_key_exists('repeatable', $section_data) && $section_data['repeatable']) {
					echo '</div>' . "\n";
				}

				//Footer
				if(array_key_exists('footer', $section_data)) {
					echo '	<div class="' . Joe_Helper::css_prefix() . 'settings-section-footer">' . $section_data['footer'] . '</div>' . "\n";
				}
				
				echo '</div>' . "\n";
			}
			
			echo '	</div>' . "\n";			
		}

		submit_button(null, 'primary large');
		echo '		</form>' . "\n";
		
		echo '	</div>' . "\n";
		echo '</div>' . "\n";
	}	

	public static function create_input($field) {
		//Set value
		if(array_key_exists('set_value', $field)) {
			$set_value = $field['set_value'];
		} else {
			$set_value = null;
		}

		echo Joe_Input::create_field($field, $set_value, false);
	}	

	public static function section_text($args) {
		//Unused
	}
	
	public static function sanitize_callback($input_data) {
		//For each tab
		foreach(static::$tabs as $tab_key => $tab_data) {
			//If we have sections
			if(array_key_exists('sections', $tab_data)) {
				//Iterate over each section
				foreach($tab_data['sections'] as $section_key => $section_data) {
					//If section has fields
					if(array_key_exists('fields', $section_data)) {
						//For each field
						foreach($section_data['fields'] as $field_key => $field_definition) {
							//If this field was submitted
							if(isset($input_data[$tab_key][$section_key][$field_definition['name']])) {															
								$value = $input_data[$tab_key][$section_key][$field_definition['name']];
								
								//If no input processing specified
								if(! array_key_exists('input_processing', $field_definition)) {
									//Make safe by default
									$field_definition['input_processing'][] = 'htmlspecialchars($param_value)';								
								}
																						
								//Process the input
								$input_data[$tab_key][$section_key][$field_definition['name']] = Joe_Input::process_input($field_definition, $value);
							}
						}					
					}
				}				
			}
		}
		
		return $input_data;
	}	

	public static function settings_nav($current = 'tiles') {
		echo '<div id="' . Joe_Helper::css_prefix() . 'settings-nav" data-init_tab_key="' . $current . '">' . "\n";
		echo '	<select>' . "\n";

		foreach(static::$settings_nav as $content_id => $content_title) {
			if(strpos($content_id, 'label') === 0) {
				echo '	<option disabled="disabled">' . $content_title . '</option>' . "\n";				
			} else {
				echo '	<option value="' . $content_id . '"' . (($current == $content_id) ? ' selected="selected"' : '') . '>' . $content_title . '</option>' . "\n";				
			}
		}

		echo '	</select>' . "\n";
		echo '</div>' . "\n";
	}

// 	public static function execute_action($action) {
// 		switch($action) {
// 			//Clear cache
// 			case 'clear_cache' :
// 				Joe_Cache::flush();
// 				
// 				break;
// 		}
// 		
// 		wp_redirect(admin_url('admin.php?page=joe-settings&tab=advanced&settings-updated=joe_action'));
// 
// 		die;
// 	}	
}