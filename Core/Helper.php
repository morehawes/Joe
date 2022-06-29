<?php

class Joe_Helper {

	static public function plugin_about() {
		$out = '	<div id="' . Joe_Helper::css_prefix('about') . '">' . "\n";		
		$out .= Joe_Config::get_item('plugin_about');
		$out .= '	</div>' . "\n";		
		
		return $out;
	}	

	static public function debug($thing, $die = true) {
// 		if(! static::is_debug()) {
// 			return;	
// 		}

		//Clear other output
// 		if($die) {
// 			@ ob_end_clean();
// 		}
			
		echo '<textarea onclick="jQuery(this).hide()" style="background:rgba(255,255,255,.8);position:absolute;top:30px;right:0;width:400px;height:400px;padding:15px;z-index:+10000000"><pre>';
		print_r($thing);
		echo '</pre></textarea>';
		if($die) {
			die;
		}
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

	public static function allowable_file($ext = '', $mime = '', $file_image = 'file') {
		$allowable_mimes = Joe_Config::get_item('mimes', $file_image);
		
		//Valid extension
		if(array_key_exists($ext, $allowable_mimes)) {
			if($mime === false) {
				return true;
			}
			
			//Check MIME
			//Single
			if(is_string($allowable_mimes[$ext])) {
				return $mime == $allowable_mimes[$ext];
			//Multiple
			} elseif(is_array($allowable_mimes[$ext])) {
				return in_array($mime, $allowable_mimes[$ext]);
			}
		}
		
		return false;
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
}