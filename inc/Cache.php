<?php

class Joe_Cache {
	
	static function set_item($cache_id, $cache_content, $cache_minutes = 0) {
		$cache_seconds = $cache_minutes * 60;

		set_transient(Joe_Helper::slug_prefix($cache_id, '_', false), $cache_content, $cache_seconds);						
	}
	
	static function get_item($cache_id) {
		self::backup_item($cache_id);

		return get_transient(Joe_Helper::slug_prefix($cache_id, '_', false));
	}

	static function backup_item($cache_id) {
		global $wpdb;

		$timeout = $wpdb->get_var(
			$wpdb->prepare("
				SELECT option_value
				FROM $wpdb->options
				WHERE option_name = '%s'
			", '_transient_timeout_' . Joe_Helper::slug_prefix($cache_id, '_', false)
			)
		);
		
// 		$query = "
// 			SELECT *
// 			FROM " . $wpdb->options . "
// 			WHERE option_name LIKE '_transient_%" . Joe_Helper::slug_prefix($cache_id, '_', false) . "%'";
// 		
// 		$result = $wpdb->get_results($query);

// 		$query = sprintf("
// 			SELECT option_value
// 			FROM $wpdb->options
// 			WHERE option_name = '_transient_timeout_%s'
//     ", Joe_Helper::slug_prefix($cache_id, '_', false)
// 		);

 		Joe_Helper::debug($timeout, false);
	}
	
	static function flush() {
		global $wpdb;
		
		//$wpdb->query("DELETE FROM " . $wpdb->options . " WHERE option_name LIKE '_transient_%" . self::$cache_prefix . "%'");
	}
}