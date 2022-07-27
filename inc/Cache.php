<?php

class Joe_Cache {
	
	static function set_item($cache_id, $cache_content, $cache_minutes = 0) {
		$cache_seconds = $cache_minutes * 60;

		set_transient(Joe_Helper::slug_prefix($cache_id, '_', false), $cache_content, $cache_seconds);						
	}
	
	static function get_item($cache_id) {
		return get_transient(Joe_Helper::slug_prefix($cache_id, '_', false));
	}

	static function get_stale($cache_id) {
		global $wpdb;

		$stale = [];

		$results = $wpdb->get_results(
			$wpdb->prepare("
				SELECT option_name, option_value
				FROM $wpdb->options
				WHERE option_name LIKE '%s'
			", '_transient_%' . Joe_Helper::slug_prefix($cache_id, '_', false)
			)
		, ARRAY_A);
		
		if(sizeof($results)) {
			foreach($results as $result) {
				if($result['option_name'] == '_transient_timeout_' . Joe_Helper::slug_prefix($cache_id, '_', false)) {
					$stale['timeout'] = $result['option_value']; 			
				} elseif($result['option_name'] == '_transient_' . Joe_Helper::slug_prefix($cache_id, '_', false)) {
					$stale['value'] = $result['option_value']; 			
				}
			}
		
			//Both are required
			if(isset($stale['timeout']) && isset($stale['value'])) {
				$stale['stale_seconds'] = time() - $stale['timeout'];
			
				//Has expired
				if($stale['stale_seconds']) {
					return $stale;
				}
			}
		} 		
		
 		return $stale;
	}
	
// 	static function flush() {
// 		global $wpdb;
// 		
// 		$wpdb->query("DELETE FROM " . $wpdb->options . " WHERE option_name LIKE '_transient_%" . self::$cache_prefix . "%'");
// 	}
}