<?php

class Joe_Log {

	private static $log = [];
	private static $by_type = [];
	private static $by_code = [];
	
	private static $count = 0;
	private static $latest = null;
	
	private static $in_error = false;
	private static $in_success = false;
	
	private static $output_type = 'console';
	
	public static function reset() {
		static::$log = [];
		static::$by_type = [];
		static::$count = 0;
		static::$in_error = false;		
		static::$in_success = false;		
	}	

	public static function set_output_type($type) {
		static::$output_type = $type;
	}

	public static function in_error() {
		if(static::$in_error === true) {
			return static::latest();
		}
		
		return false;
	}

	public static function in_success() {
		if(static::$in_success === true) {
			return static::latest();
		}
		
		return false;
	}
	
	public static function latest($type = null) {
		$out = [];
		
		if(! $type) {
			$out = static::$log[sizeof(static::$log)-1];
		} elseif(is_array(static::$by_type[$type]) && sizeof(static::$by_type[$type])) {
			$out = static::$by_type[$type][sizeof(static::$by_type[$type])-1];
		}
		
		return $out;
	}	
	
	public static function add($message = '', $type = 'log', $code = 'info') {	
		//Flags
		if($type == 'success') {
			static::$in_success = true;
			static::$in_error = false;			
		} elseif($type == 'error') {
			static::$in_error = true;
			static::$in_success = false;
		}
		
		$item = [
			'microtime' => time(),
			'type' => $type,
			'code' => $code,
			'message' => $message
		];

		static::$log[] = $item;
		static::$by_type[$type] = $item;
		static::$by_code[$code] = $item;
		
		static::$latest = $item;
					
		static::$count++;
	}

	public static function size() {
		return static::$count;
	}

	public static function has(string $code) {
		if(array_key_exists($code, static::$by_code)) {
			return static::$by_code[$code];
		}
		
		return false;
	}
	
	public static function out($content = '') {
		switch(static::$output_type) {
			case 'notice' :
				$latest = Joe_Log::latest();
				$type = isset($latest['type']) ? $latest['type'] : '';
				
				Joe_Assets::js_onready('joe_admin_message("' . $content . '", "' . $type . '")');
		
				break;

			default :
			case 'console' :
				Joe_Assets::js_inline('console.log("' . $content . '");');
		
				break;

		}
	}
	
	public static function render() {
		$log_content = '';
		
		foreach(static::$log as $item) {
			$log_content .= static::draw_item($item);
		}
		
		if($log_content) {
			static::out($log_content);		
		}
	}

	public static function render_item(array $item) {
		if($item_content = static::draw_item($item)) {
			static::out($item_content);
		}
	}

	public static function draw_item(array $item) {
		if(empty($item) || ! isset($item['message']) || ! isset($item['type'])) {
			return false;
		}
		
		$code = isset($item['code']) ? $item['code'] : '';

		switch(static::$output_type) {
			case 'notice' :
				return '<br /> &ndash; [' . ucwords($item['type']) . '] ' . $item['message'] . ' (' . $code . ')';
			default :
			case 'console' :
				if($code) {
					$code = '=' . $code;
				}
				return '[' . Joe_Config::get_name() . ' ' . ucwords($item['type']) . $code . '] ' . $item['message'] . '\n';
		}
		
		return false;
	}	
}