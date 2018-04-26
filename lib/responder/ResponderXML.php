<?php

namespace lib\responder;

class ResponderXML
{
	private static $instance;
	protected $ended;
	
	public static function getInstance() 
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c();
        }

        return self::$instance;
    }
	
	private function __construct()
	{
		header('Content-Type: text/xml; charset=ISO-8859-4');
		echo "<?xml version='1.0' encoding='UTF-8'?>";
		echo "<response>";
	}
	
	public function quickResponse($action, $data)
	{
		echo "<success action='$action'>$data</success>";
	}
	
	
	public function error($error_num, $error_msg, $attribs = array())
	{
		
		echo "<error";
		foreach ($attribs as $key => $value) {
    		echo " $key='$value'";
		}
		echo ">";
		echo "<number>$error_num</number>";
		echo "<message>$error_msg</message>";
		echo "</error>";
	}
	
	public function endResponse()
	{
		
		echo "</cmsResponse>";
	
		$this->ended = 1;
	}
	
	public function __destruct()
	{
		if(!$this->ended)$this->endResponse();
	}
}