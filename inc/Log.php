<?php

class Joe_Log {

	private static $log = [];
	
	private static $count = 0;
	private static $latest = null;
	
	private static $in_error = false;
	private static $in_success = false;
	
	public static function reset() {
		static::$log = [];
		static::$count = 0;
		static::$latest = null;		
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
		
		if((! $type && is_array(static::$latest)) || is_array(static::$latest)) {
			$out = static::$latest;
		} elseif(is_array(static::$log[$type])) {
			$out = static::$log[$type][sizeof($log[$type])-1];
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
			'microtime' => microtime(),
			'type' => $type,
			'code' => $code,
			'message' => $message
		];

		static::$log[$type][] = $item;
		
		static::$latest = $item;
					
		static::$count++;
	}

	public static function size() {
		return static::$count;
	}
	
	public static function render($response_type = 'print_r') {
		return print_r(static::$log, true);
	}

	public static function render_item(array $item, $render_type = 'notice') {
		if(empty($item) || ! isset($item['message']) || ! isset($item['type'])) {
			return false;
		}

		switch($render_type) {
			case 'console' :
				Joe_Assets::js_inline('console.log("[' . Joe_Config::get_name() . ' ' . ucwords($item['type']) . '] ' . $item['message'] . '");');
			
				break;

			default :
			case 'notice' :
				Joe_Assets::js_onready('joe_admin_message("' . $item['message'] . '", "' . $item['type'] . '")');
			
				break;

		}
	}
}
