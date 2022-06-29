<?php

class Joe_AJAX {
	function __construct() {
		//Add nonce
		add_action('init', function() {
			Waymark_JS::add_chunk('var ' . Waymark_Config::get_item('plugin_slug') . '_security = "' . wp_create_nonce(Waymark_Config::get_item('nonce_string')) . '";');					
		});
	}

	function read_file() {
		$response = [];
		
		//If we have files
		if(sizeof($_FILES)) {
			//Each file
			foreach($_FILES as $file_key => $file_data) {
				$response = $file_data;								
			
				//If no WP error
				if(! $file_data['error']) {				
					switch($file_key) {
						//Read file contents
						case 'add_file' :
							//Attempt to read file
							$file_contents = Joe_Input::get_file_contents($file_data);
							
							//Good to proceed
							if($file_contents) {
								$response = array_merge($response, $file_contents);
							//Unknown error
							} else {
								$response['error'] = esc_html__('Could not read the file.', 'waymark');
							}	
							
							break;
						case 'marker_photo' :
						case 'add_photo' :
 							//Upload
 							$upload_response = media_handle_upload($file_key, 0);
							
							//Success
 							if(! is_wp_error($upload_response)) {
								$attachment_id = $upload_response;
								$response['id'] = $attachment_id;

								//Get URL
								$attachment_url = wp_get_attachment_url($attachment_id);
								$response['url'] = $attachment_url;
							
								//Meta?
								$attachment_metadata = wp_get_attachment_metadata($attachment_id);
								
								//Only when adding an image
								if($file_key == 'add_photo') {
									//Image Meta
									if(array_key_exists('image_meta', $attachment_metadata) && is_array($attachment_metadata['image_meta'])) {
										//Location EXIF
										if(array_key_exists('GPSLatitudeNum', $attachment_metadata['image_meta']) && array_key_exists('GPSLongitudeNum', $attachment_metadata['image_meta'])) {
											$response = array_merge($response, array(
												'GPSLatitudeNum' => $attachment_metadata['image_meta']['GPSLatitudeNum'],
												'GPSLongitudeNum' => $attachment_metadata['image_meta']['GPSLongitudeNum']										
											));							
										}
									}
								}

								//Sizes
								if(array_key_exists('sizes', $attachment_metadata) && is_array($attachment_metadata['sizes'])) {
									//Each size
									foreach($attachment_metadata['sizes'] as $size_key => &$size) {
										//Add URL
										$size['url'] = wp_get_attachment_image_url($attachment_id, $size_key);
									}
							
									$response = array_merge($response, array(
										'sizes' => $attachment_metadata['sizes']
									));
								}								
							//Error
 							} else {
								//WP Error
 								if($upload_response->has_errors()) {
									//Use it
									$response['error'] = $upload_response->get_error_message();
 								}
 							}

 							break;
					}								
				//WP error
				}
			}						
		}
		
		//No response?
		if(! sizeof($response)) {
			$response['error'] = esc_html__('Unknown file upload error.', 'waymark');
		}

		header('Content-Type: text/javascript');
		echo json_encode($response);
		die;
	}	
}