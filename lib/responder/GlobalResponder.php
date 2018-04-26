<?php
namespace lib\responder;

use lib\exception\ArgumentException;

class GlobalResponder
{
	const RESPONDER_PREFIX = "Responder";
	public static $currentResponseType = DEFAULT_RESPONSE_TYPE;
	
	private static $currentResponder;
	
	public static function writeResponseData($data)
	{
		if(!self::$currentResponder) self::setResponder(self::$currentResponseType);
		self::$currentResponder->writeResponseData($data);
	}
	
	public static function startResponse()
	{
		if(!self::$currentResponder) self::setResponder(self::$currentResponseType);
		self::$currentResponder->startResponse();
	}
	
	public static function endResponse()
	{
		if(!self::$currentResponder) self::setResponder(self::$currentResponseType);
		self::$currentResponder->endResponse();
	}
	
	public static function setResponder($type)
	{
		if(self::$currentResponder) throw new ArgumentException(EXCEPTION_RESPONDER_ALREADY_SET);
		$responderClassName = "\\core\\responder\\" . self::RESPONDER_PREFIX . strtoupper($type); 
		self::$currentResponder = new $responderClassName(); 
	}
	
	
}