<?php


namespace lib\exception;

use \Exception;
class DatabaseException extends Exception
{
	const FORMATTED_NAME = "Database Exception";
	
	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}
	
}