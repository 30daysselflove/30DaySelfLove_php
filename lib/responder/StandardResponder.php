<?php
namespace lib\responder;

use lib\exception\ArgumentException;


class StandardResponder
{
	const RESPONDER_PREFIX = "Responder";
	const RESPONSE_TYPE_JSON = "json";
	const RESPONSE_TYPE_XML = "xml";
	const RESPONSE_TYPE_DEFAULT = DEFAULT_RESPONSE_TYPE;
	public $currentResponseType;
	
	private $currentResponder;
	
	public function writeResponseData($data)
	{
		$this->currentResponder->writeResponseData($data);
	}
	
	public function startResponse()
	{
		$this->currentResponder->startResponse();
	}
	
	public function endResponse()
	{
		$this->currentResponder->endResponse();
	}
	
	public function __construct($type = null)
	{
		if($type) $this->currentResponseType = $type;
		elseif(!empty($_REQUEST['format'])) $this->currentResponseType = $_REQUEST['format'];
		else $this->currentResponseType = self::RESPONSE_TYPE_DEFAULT;
		$responderClassName = "\\lib\\responder\\" . self::RESPONDER_PREFIX . strtoupper($this->currentResponseType);
		$this->currentResponder = new $responderClassName(); 
	}
	
	
}