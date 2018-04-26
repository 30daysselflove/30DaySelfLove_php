<?php


namespace lib\exception;

use \Exception;
class ArgumentException extends BadMethodCallException
{
	const FORMATTED_NAME = "Argument Exception";

	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}
	
	
}