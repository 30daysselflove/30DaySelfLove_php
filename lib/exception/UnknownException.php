<?php


namespace lib\exception;

use \Exception;
class UnknownException extends Exception
{
	const FORMATTED_NAME = "Runtime Exception";
	
	
	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}

}