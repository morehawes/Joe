<?php

class Joe_Helper {

	static public function make_hash($data, $length = 6) {
		if(! is_string($data)) {
			$data = json_encode($data);
		}
		
		return substr(md5($data), 0, $length);
	}

	static public function site_url($url_path = '') {
		return Joe_Config::get_item('site_url') . $url_path;
	}

	static public function plugin_url($file_path = '') {	
		return plugin_dir_url('') . Joe_Config::get_item('plugin_slug') . '/' . $file_path;
	}

	static public function asset_url($file_path = '') {	
		return plugin_dir_url('') . Joe_Config::get_item('plugin_slug') . '/assets/' . $file_path;
	}
	
	static public function http_url($data = array()) {
		return trim(add_query_arg(array_merge(array('waymark_http' => '1'), $data), home_url('/')), '/');
	}

	static public function plugin_name($short = false) {
		if(! $short) {
			return Joe_Config::get_item('plugin_name');
		} else {
			return Joe_Config::get_item('plugin_name_short');
		}
	}

	static public function plugin_about() {
		$out = '	<div id="' . Joe_Helper::css_prefix('about') . '">' . "\n";		
		$out .= Joe_Config::get_item('plugin_about');
		$out .= '	</div>' . "\n";		
		
		return $out;
	}	

	static public function debug($thing, $die = false) {
		if(! $die) {			
			echo '<textarea onclick="jQuery(this).hide()" style="background:rgba(255,255,255,.8);position:absolute;top:30px;right:0;width:400px;height:400px;padding:15px;z-index:+10000000"><pre>';
		}

		print_r($thing);

		if(! $die) {			
			echo '</pre></textarea>';
		} else {
			die;
		}
	}

	static public function make_key($str, $prefix = '', $use_underscores = true) {
		$str = str_replace(' ', '_', $str);

		if($prefix) {
			$str = $prefix . '_' . $str;	
		}
		
		//Like in JS
		if(! $use_underscores) {
			$str = str_replace('_', '', $str);		
		}
		
		$str = strtolower($str);
		$str = preg_replace('/[^a-z0-9+_]+/i', '', $str);
		
		return $str;
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
				$array_out[$key] = implode(Joe_Config::get_item('multi_value_seperator'), $value);
			}
		}	
		
		return $array_out;
	}
	
	public static function convert_single_value_to_array($value_in) {
		//Array
		if(is_array($value_in)) {
			$array_out = array();
		
			foreach($value_in as $key => $value) {
				$multi = explode(Joe_Config::get_item('multi_value_seperator'), $value);			

				$count = 0;
				foreach($multi as $m) {
					$array_out[$count][$key] = $m;
	//				Joe_Helper::debug($m, false);
				
					$count++;
				}			
			}	
		
			return $array_out;		
		//String
		} else {
			return explode(Joe_Config::get_item('multi_value_seperator'), $value_in);			
		}
	}		

	static public function get_section_repeatable_count($section_data) {
		$first_field = $section_data['fields'][array_keys($section_data['fields'])[0]];
		
		if(is_array($first_field['default'])) {
			return sizeof($first_field['default']);
		}

		return false;	
	}	

	public static function css_prefix($text = '')	{
		return Joe_Config::get_item('css_prefix') . $text;
	}

	public static function slug_prefix($text = '')	{
		return Joe_Config::get_item('plugin_slug') . '_' . $text;
	}

	public static function array_string_to_array($string) {
		$string = str_replace(array('[',']','"','"'), array('','','',''), $string);
		
		return self::comma_string_to_array($string);
	}
	
	public static function comma_string_to_array($string) {
		//Process options
		$options_exploded = explode(',', $string);
		$options_array = array();
		foreach($options_exploded as $option) {
			$value = trim($option);
			$key = self::make_key($value);
		
			$options_array[$key] = $value;
		}
	
		return $options_array;
	}

	public static function multi_use_as_key($array_in, $as_key = false) {
		$array_out = array();
			
		$count = 0;
		foreach($array_in as $data) {
			if(is_array($data) && $as_key && array_key_exists($as_key, $data)) {
				$out_key = self::make_key($data[$as_key]);
			} else {
				$out_key = $count;
			}

			$array_out[$out_key] = $data;			

			$count++;						
		 }	
		
		return $array_out;
	}		

	static public function flatten_meta($data_in) {
		$data_out = array();		
		
		if(is_array($data_in)) {
			foreach($data_in as $data_key => $data_value) {
				$data_out[$data_key] = $data_value[0];
			}		
		}
		
		return $data_out;		
	}		

	static public function repeatable_setting_option_array($tab, $section, $key) {
		$options_array = array();
		$values = Joe_Config::get_item($tab, $section, true);
		
		if(! is_array($values)) {
			return null;
		}
		
		foreach($values as $s) {
			//If exists
			if(array_key_exists($key, $s)) {
				//Add as option
				$options_array[Joe_Helper::make_key($s[$key])] = $s[$key];
			}		
		}
		
		return $options_array;
	}	

	public static function assoc_array_table($assoc_array) {
		if(! is_array($assoc_array) || ! sizeof($assoc_array)) {
			return false;
		}

		$table = '<table class="' . Joe_Helper::css_prefix() . '-assoc_array">';
					
		foreach($assoc_array as $key => $value) {
			$table .= '<tr class="' . Joe_Helper::css_prefix() . '-assoc_array-' . $key . '">';
			$table .= '<th>' . $key . '</th>';
			$table .= '<td>' . $value . '</td>';
			$table .= '</tr>';
		}
		
		$table .= '</table>';
	
		return $table;
	}	
}