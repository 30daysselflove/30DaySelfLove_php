<?php
namespace controller;
//Basic controller class

use lib\responder\ResponderJSON;

use lib\responder\StandardResponder;


abstract class controller
{
  
	public $responderType = StandardResponder::RESPONSE_TYPE_JSON;
	public $host;
	public $protocol;
	public $actionContext;
	protected $forcePlainText = false;
	public $templateData;
	
	/**
	 * @var core\memcache\CacheCentralStation
	 */
	protected $cache;
	protected $cacheDataArray;
	protected $cacheDataChanged = false;
	protected $cacheBaseSuffix = '';

	public static function getPrimaryDomain()
	{
		$host = $_SERVER['HTTP_HOST'];
		$hostParts = explode(".", $host);
		$length = count($hostParts);
		return $hostParts[$length - 2] . "." . $hostParts[$length-1];
	}
	
	public function __construct()
	{
		$this->host = $_SERVER['HTTP_HOST'];


		$this->protocol =  empty($_SERVER['HTTPS']) ? "http://" : "https://";
		set_exception_handler(array($this, "_exceptionHandler"));
		set_error_handler(array($this, "_errorHandler"));
	}
	
	public function fullURL()
	{
		return $this->host . $_SERVER['REQUEST_URI'];
	}

    public function requestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }

	public function _exceptionHandler($e)
	{
	    if(error_reporting() === 0) return;
		if(IN_DEV) $this->returnStatusCode(400, array("type" => "exception", "message" => $e->getMessage(), "code" => $e->getCode(), "trace" => $e->getTraceAsString()));
		else $this->returnStatusCode(400);
		return;
	}

	public function _errorHandler($errno, $errstr, $errfile, $errline)
	{
	    if(error_reporting() === 0) return;
	    if(IN_DEV) $this->returnStatusCode(500, array("type" => "error", "message" => $errstr, "code" => $errno, "trace" => array("file" => $errfile, "line" => $errline)));
	    else $this->returnStatusCode(500);
	    return;
	}
	
	protected function getCacheKeyBase()
	{
		$args = func_get_args();
		$baseSuffix = "";
		if($args) $baseSuffix = "_" . implode("_", $args);
		$classPath =  get_class($this);
		$classParts = explode("\\", $classPath);
		$classParts[0] = "con";
		$cacheKey = implode("_", $classParts) . $baseSuffix;
		return $cacheKey;
	}
	
	public function url()
	{
		$class = get_class($this);
		$classParts = explode("\\", $class);
		array_shift($classParts); //Shift off the controller component
		$subdomain = array_shift($classParts);
		if($classParts[0] == "prime")
		{
			array_shift($classParts);
		}
		$url = "$subdomain." . self::getPrimaryDomain() . "/" . implode("/", $classParts);
		return ($url); //File based controller pathing. Return the classname
	}
	
	protected function getSubDomain()
	{
		$class = get_class($this);
		$classParts = explode("\\", $class);
		if(count($classParts) > 2) return $classParts[1]; //Directory based controller pathing. Return the second part of the namespace, which equates to the subdomain
		return end($classParts); //File based controller pathing. Return the classname
	}
	protected function getClassName()
	{
		$class = get_class($this);
		$classParts = explode("\\", $class);
		return end($classParts);
	}
	
	protected function requireHTTPS()
	{
		if(!isset($_SERVER['HTTPS'])){ 
			header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			exit();
		}
	}


	
	protected function namePieces()
	{
	    $className = get_class($this);
	    $classNameArray = explode("\\", $className);
	    array_shift($classNameArray);
	    if(end($classNameArray) == "prime") array_pop($classNameArray);
	    return $classNameArray;
	}
	
	protected function notLoggedIn()
	{
		if($this->forcePlainText) self::returnStatusCode(401, "Not Logged In");
		else $this->returnStatusCode(401, "Not Logged In");
	}
	
	protected function respond($response = "")
	{
		if($response instanceof ISerializable)
		{
			$response = json_encode(utf8_encode($response->serializableForm()));
		}
		$responder = new StandardResponder($this->responderType);
		$responder->startResponse();
		$responseArray = array("response" => $response);
		$responder->writeResponseData($responseArray);
		$responder->endResponse();
	}
	
	public function respondAndExit($response = "")
	{
	    $this->respond($response);
	    exit();
	}
	
	public function requireHTTPPOST()
	{
		if($_SERVER['REQUEST_METHOD'] != "POST" && $_SERVER['REQUEST_METHOD'] != "PUT") $this->returnStatusCode(405, "Expected POST");
	}
	
	public function requireHTTPDELETE()
	{
		if($_SERVER['REQUEST_METHOD'] != "DELETE") $this->returnStatusCode(405, "Expected DELETE");
	}
	
	public function requireHTTPGET()
	{
		if($_SERVER['REQUEST_METHOD'] != "GET") $this->returnStatusCode(405, "Expected GET");
	}

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == "POST";
    }
	
	public function returnStatusCode($code, $msg = null)
	{
		$messages = array(
		    // Client Error 4xx
		    400 => 'Bad Request',
		    401 => 'Unauthorized',
		    402 => 'Payment Required',
		    403 => 'Forbidden',
		    404 => 'Not Found',
		    405 => 'Method Not Allowed',
		    406 => 'Not Acceptable',
		    407 => 'Proxy Authentication Required',
		    408 => 'Request Timeout',
		    409 => 'Conflict',
		    410 => 'Gone',
		    411 => 'Length Required',
		    412 => 'Precondition Failed',
		    413 => 'Request Entity Too Large',
		    414 => 'Request-URI Too Long',
		    415 => 'Unsupported Media Type',
		    416 => 'Requested Range Not Satisfiable',
		    417 => 'Expectation Failed',
		
		    // Server Error 5xx
		    500 => 'Internal Server Error',
		    501 => 'Not Implemented',
		    502 => 'Bad Gateway',
		    503 => 'Service Unavailable',
		    504 => 'Gateway Timeout',
		    505 => 'HTTP Version Not Supported',
		    509 => 'Bandwidth Limit Exceeded'
		);
		
		$responder = new StandardResponder($this->responderType);
		$msg = $msg ? $msg : $messages[$code];
		$headerMsg = is_array($msg) ? $msg['message'] : $msg;
		header("Status: " . $headerMsg, true, $code);
		$responder->startResponse();
		$responder->writeResponseData($msg);
		$responder->endResponse();
		exit();
	}
}


