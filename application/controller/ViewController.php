<?php

namespace controller;


class ViewController extends SessionController {
	
	/**
	 * 
	 * Set by Model controllers via view context routing to provide the view controller with
	 * the response data
	 * Automically set to the REQUEST array to abstract away the array to use when attemtping to render dynamic data
	 * @var array
	 */
	public $responseContext;
	
	public function __construct()
	{
		parent::__construct();
		$this->responseContext = $_REQUEST;
	}

	public function redirectHome()
	{
		header("Location: " . $this->protocol . $this->host);
		exit();
	}
}