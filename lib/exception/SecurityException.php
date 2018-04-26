<?php


namespace lib\exception;

use \Exception;

class SecurityException extends Exception
{
	const FORMATTED_NAME = "Security Exception";
	
	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}
	
	
}