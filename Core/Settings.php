<?php

class Joe_Settings {
	protected static $current_settings = [];
	
	public static $tabs = [];	
	public static $settings_nav = [];

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
}