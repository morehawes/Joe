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
			return static::$latest;
		}
		
		return false;
	}

	public static function in_success() {
		if(static::$in_success === true) {
			return static::$latest;
		}
		
		return false;
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

		static::$log[$type][$code] = $item;
		
		static::$latest = $item;
					
		static::$count++;
	}

	public static function size() {
		return static::$count;
	}
	
	public static function output($response_type = 'print_r') {
		return print_r(static::$log, true);
	}
}