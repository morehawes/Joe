<?php

class Joe_Log {

	private static $log = [];
	private static $by_type = [];
	
	private static $count = 0;
	private static $latest = null;
	
	private static $in_error = false;
	private static $in_success = false;
	
	public static function reset() {
		static::$log = [];
		static::$by_type = [];
		static::$count = 0;
		static::$in_error = false;		
		static::$in_success = false;		
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
		
		static::$latest = $item;
					
		static::$count++;
	}

	public static function size() {
		return static::$count;
	}
	
	public static function render($render_type = 'console') {
		foreach(static::$log as $item) {
			static::render_item($item, $render_type);
		}
	}

	public static function render_item(array $item, $render_type = 'console') {
		if(empty($item) || ! isset($item['message']) || ! isset($item['type'])) {
			return false;
		}
		
		$code = isset($item['code']) ? '=' . $item['code'] : '';

		switch($render_type) {
			case 'notice' :
				Joe_Assets::js_onready('joe_admin_message("' . $item['message'] . '", "' . $item['type'] . '")');
			
				break;

			default :
			case 'console' :
				Joe_Assets::js_inline('console.log("[' . Joe_Config::get_name() . ' ' . ucwords($item['type']) . $code . '] ' . $item['message'] . '");');
			
				break;

		}
	}
}
