<?php


namespace lib\exception;

use \Exception;
class BadMethodCallException extends Exception
{
	const FORMATTED_NAME = "Invalid Method Call Exception";
	
	
	
	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}
	

}