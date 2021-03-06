<?php

abstract class Joe_Request {
	protected $config;
	protected $parameters;
	protected $request_type = false;
	protected $request_endpoint;
	private $request;
	private $response;
		
	public function get_request() {
		return $this->request;
	}

	public function get_response() {
		return $this->response;
	}
	
	public function get_request_type() {
		return $this->request_type;
	}

	public function get_config(string $key = '') {
		if($key == '') {
			return $this->config;
		}
		
		if(array_key_exists($key, $this->config)) {
			return $this->config[$key];		
		}
		
		return null;
	}

	private function set_request($request) {
		$this->request = $request;
	}

	private function set_response($response) {
		$this->response = $response;
	}
	
	public function set_config(string $key, $value) {
		$this->config[$key] = $value;
	}
	
	/**
	 * Define abstract functions
	 */
	abstract function build_request_parameters($params = []);
	abstract function process_response($response);

	/**
	 * Set parameters
	 */
	function set_parameters($params_in) {
		foreach($params_in as $param_key => $param_value) {
			$this->parameters[$param_key] = $param_value;
		}
	}

	/**
	 * Build request URL
	 */	
	function build_request(array $params_in) {
		$request = $this->request_endpoint . '?';
		
		//%20, not + for spaces
		//https://www.php.net/manual/en/function.http-build-query.php
		$request .= http_build_query($params_in, '', null, PHP_QUERY_RFC3986);

		return $request;
	}	

	/**
	 * Get the response
	 */
	function get_processed_response() {
		//Get request params
		$request_params = $this->build_request_parameters($this->parameters);		

		//Build request
		$request = $this->build_request($request_params);
		$this->set_request($request);
		
		//Determine cache ID
		$cache_id = 'Request_' . Joe_Cache::do_hash($request);

		//Cached response	
		if($cached_response = Joe_Cache::get_item($cache_id)) {			
			//Get raw response from cache
			$response_raw = $cached_response;
			
			//Process it
			$response_processed = $this->process_response($response_raw);		
		//No cache
		} else {
			$response_raw = $this->perform_request($request);		
								
			//Process response
			$response_processed = $this->process_response($response_raw);
			
			//Only cache success
			if(array_key_exists('status', $response_processed) && $response_processed['status'] == 'success') {
				//Insert into cache
				Joe_Cache::set_item($cache_id, $response_raw);			
			}
		}
		
		$this->set_response($response_raw);
								
		return $response_processed;
	}

	/**
	 * Run the request
	 */
	function perform_request($request) {
		Joe_Log::add('Performing remote request: ' . $request, 'info');

		return wp_remote_get($request);
	}
}