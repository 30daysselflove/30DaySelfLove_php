<?php

namespace lib\responder;

class ResponderJSON
{
	const TYPE_JSON = 1;
	const TYPE_JSONP = 2;
	
	private static $instance;
	
	protected $type 	= self::TYPE_JSON;
	protected $started 	= false;
	protected $ended 	= false;
	
	public function __construct()
	{
		if(isset($_REQUEST["callback"])) $this->type = self::TYPE_JSONP;
		if($this->type == self::TYPE_JSON) header('Content-Type: text/plain');
		else header('Content-Type: application/javascript');
	}
	
	public function startResponse()
	{
		if($this->started) return;
		if($this->type == self::TYPE_JSONP) echo $_GET['callback']."(";
		$this->started = true;
	}
	
	public function writeResponseData($data)
	{
		$this->startResponse();
		echo json_encode($data);
	}
	
	public function endResponse()
	{
		if($this->type == self::TYPE_JSONP) echo ")";
		$this->ended = true;
	}
	
	public function __destruct()
	{
		if(!$this->ended)$this->endResponse();
	}
}