<?php

class Joe_Config {
	//Set defaults
	protected static $default = [];
	protected static $data = [
		'multi_value_seperator' => '__multi__',
		'cache_prefix' => 'Joe_Cache_',
		'settings_id' => 'Joe_Settings',
		'css_prefix' => 'joe-',
		'plugin_text_domain' => 'joe',
		'plugin_about' => '<img alt="Joe\'s mug" src="//www.josephhawes.co.uk/assets/images/Joe1BW.jpg" /><p class="waymark-first"><b>Joe</b></p>'
	];

	public static function set_item($key = null, $value) {
		if(array_key_exists($key, static::$data)) {
			static::$data[$key] = $value;
		}
	}

	public static function get_item($key, $key_2 = null, $is_repeatable = false) {	
		//Joe_Helper::debug(static::$data);

		if(array_key_exists($key, static::$data)) {
			if(is_array(static::$data[$key]) && array_key_exists($key_2, static::$data[$key])) {
				//Single value
				if(! $is_repeatable) {
					return static::$data[$key][$key_2];
				//Multi-value
				} else {
					//Convert
					$values = static::$data[$key][$key_2];
					
					//Pad if necessary
					$max_size = null;
					foreach($values as $key => &$value) {
						//Must be an array
						if(! is_array($value)) {
							continue;
						}
						
						if($max_size !== null && sizeof($value) != $max_size) {
							$value = array_pad(array(), $max_size, $value);
						} else {
							$max_size = sizeof($value);
						}
					}
					
					$values = Joe_Helper::convert_values_to_single_value($values);
					$values = Joe_Helper::convert_single_value_to_array($values);				
			
					return $values;
				}
			} else {
				if(! $is_repeatable) {
					return static::$data[$key];
				} else {
					return [];
				}
			}			
		} else {
			return null;
		}			
	}

	public static function get_data() {	
		return static::$data;
	}	

	public static function get_default($tab, $group, $key) {	
		if(array_key_exists($tab, static::$default) && array_key_exists($group, static::$default[$tab]) && array_key_exists($key, static::$default[$tab][$group])) {
			return static::$default[$tab][$group][$key];
		} else {
			return false;
		}	
	}

	public static function get_setting($tab, $group, $key) {
		if(array_key_exists($tab, static::$data) && array_key_exists($group, static::$data[$tab]) && array_key_exists($key, static::$data[$tab][$group])) {			
			return static::$data[$tab][$group][$key];
		} else {
			return false;
		}	
	}

	//Helpers
	public static function get_name($short = false, $really_short = false) {
		if(! $short) {
			return static::get_item('plugin_name');				
		} else {
			if(! $really_short) {
				return static::get_item('plugin_name_short');															
			} else {
				return strip_tags(static::get_item('plugin_name_short'));															
			}
		}		
	}	

	public static function get_version() {
		return static::get_item('plugin_version');	
	}	
	
	public static function get_settings_parameters($tab_id = null, $group_id = null) {
		$settings = array();
		
		//If only getting a secific section
		if(array_key_exists($tab_id, static::$parameters) && array_key_exists($group_id, static::$parameters[$tab_id])) {
			$group_data = static::$parameters[$tab_id][$group_id];
			//Iterate over each parameter
			foreach($group_data as $parameter_data) {
				if(array_key_exists('setting', $parameter_data) && $parameter_data['setting']) {
					$settings[] = $parameter_data;
				}
			}								
		}
	
		return $settings;		
	}

	public static function convert_values_to_single_value($array_in) {
		$array_out = array();
		
		if(! is_array($array_in)) {
			return $array_out;
		}
					
		foreach($array_in as $key => $value) {
			//Single value
			if(! is_array($value)) {
				//Use that
				$array_out[$key] = $value;
			//Multiple values
			} else {
				//Single value, use that
				$array_out[$key] = implode(static::get_item('multi_value_seperator'), $value);
			}
		}	
		
		return $array_out;
	}	
}