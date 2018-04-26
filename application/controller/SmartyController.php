<?php

namespace controller;


use lib\responder\StandardResponder;

use lib\Session;

require("../application/url_paths.ini.php");
class SmartyController extends ViewController {

	const DISPLAY_TYPE_SMARTY 		= 1;
	const DISPLAY_TYPE_PLAIN_TEXT 	= 2;
	
	protected $smarty;
	protected $template;
	
	
	public $displayType = self::DISPLAY_TYPE_SMARTY;
	

	public function __construct(){
		parent::__construct();
		$this->initSmarty();
		$this->setPaths();
	}
	
	protected function initSmarty()
	{
		require_once(LIB_BASE_PATH . "/smarty/Smarty.class.php");
	
		$this->smarty = new \Smarty();
		$this->smarty->compile_check = true;
		//$this->smarty->use_include_path = true;
		\Smarty::muteExpectedErrors();
		$this->templateData = new TemplateData($this->smarty);
		
		$this->smarty->assign("protocolbase", $this->protocol);
		$this->smarty->assign("basePath", $this->protocol . $this->host );
		$user = $this->getCurrentUser();
		
		$this->smarty->assign("loggedIn", !empty($user));
		$this->smarty->assign("sessionEmbed",Session::$sessionName . "=" . session_id());
		
	}
	
	public function __call($name, $arguments){
	    if(method_exists($this->smarty, $name))
	    {
	        return call_user_func_array(array($this->smarty, $name), $arguments);
	    }
	    
	}
	
	protected function setPaths()
	{
		$nameArray = $this->namePieces();
		
		$resolvedName = implode("/", $nameArray);
		
		//Zach edited this (Please double check)
		$naturalPath = $nameArray[0];
		
		$this->smarty->template_dir = VIEW_BASE_PATH. '/' . $naturalPath . '/';
		$this->smarty->compile_dir  = VIEW_BASE_PATH.'/smarty/' . $naturalPath. '/templates_c/';
		$this->smarty->config_dir   = VIEW_BASE_PATH.'/smarty/' . $naturalPath . '/configs/';
		$this->smarty->cache_dir    = VIEW_BASE_PATH.'/smarty/' . $naturalPath. '/cache/';
		$this->smarty->assign("pathContent", "/" . $naturalPath);
		$this->smarty->assign("fullURL", $this->protocol . $this->fullURL());
		if(count($nameArray))
		{
		    $this->smarty->assign("pathRoot", "/" . $nameArray[0]);
		}
		else $this->smarty->assign("pathRoot", "");
		
	}

	protected function notLoggedIn($lastAction = null)
	{
		if($this->forcePlainText) self::returnStatusCode(401, "Not Logged In");
		else 
		{
			$this->startSession();
			if(!$lastAction) $this->session->lastAction = $this->protocol . $this->fullURL(); 
			else $this->session->lastAction = $lastAction;
			$hostParts = explode(".", $this->host);
			if(count($hostParts) > 3)
			{
				$subHost = $hostParts[0] . ".";
			}else $subHost = '';
			
			header("Location: http://" . $subHost . LOGIN_BASE . "." . self::getPrimaryDomain() . PATH_LOGIN);
			exit();
		}
	}
	
	public function loadTemplate($template)
	{
		if(strpos($template, "/")) die("Invalid request");
		$this->smarty->template_dir = VIEW_BASE_PATH.'/loadables/';
		$this->smarty->assign("urlbase", $_SERVER['HTTP_HOST']);
		
		$this->template = $template . ".tpl";
	}
	
	public function display()
	{
        if(defined("KRUN") && strpos($_SERVER['REQUEST_URI'], "/kpatch") === false) return;
		else $this->smarty->display($this->template);
	}
	
	public function supplyPaginationData($limit,$offset,$total,$url)
	{
		$to = $offset + $limit;
		if($to > $total) $to = $total;
		if($to == 0) $to = 1;

		$offsetCount = ceil($offset / $limit);
		$pageCount = ceil($total / $limit);
		$pages = array();

		for ($i = 0; $i < $pageCount; $i++) {
			$poffset = $i * $limit;
			$selected = $i == $offsetCount;
			array_push($pages, array('offset' => $poffset, 'display' => $i+1, 'exclude' => false, 'selected' => $selected));
		}

		$paginationData = array(
			'url' => $url,
			'total' => $total,
			'from' => $offset+1,
			'to' => $to,
			'pages' => $pages
		);

		$this->templateData->paginationData = $paginationData;
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
		
		$msg = $msg ? $msg : $messages[$code];
		$headerMsg = is_array($msg) ? $msg['message'] : $msg;
		header("Status: " . $headerMsg, true, $code);
		
		if($this->displayType == self::DISPLAY_TYPE_SMARTY && !$this->forcePlainText)
		{
		    @ob_clean();
			$this->smarty->template_dir = VIEW_BASE_PATH. '/httpstatus/';
			$this->smarty->compile_dir  = VIEW_BASE_PATH. '/smarty/httpstatus/templates_c/';
			$this->smarty->config_dir   = VIEW_BASE_PATH. '/smarty/httpstatus/configs/';
			$this->smarty->cache_dir    = VIEW_BASE_PATH. '/smarty/httpstatus/cache/';
			$this->smarty->assign("statusCode", $code);
			$this->smarty->assign("statusMessage", $msg);
			
			switch($code)
			{
				default:
				
				$this->template = "default.tpl";
				
				$this->smarty->display($this->template);
				
				break;
			}
		}
		else
		{
			$responder = new StandardResponder($this->responderType);
			$responder->startResponse();
			$responder->writeResponseData($msg);
			$responder->endResponse();
		}
		
		exit();
		
	}
}

class TemplateData
{
    private $smarty;
    public function __construct($smarty)
    {
        $this->smarty = $smarty;
    }
    
	public function __set($key, $value)
	{
	    $this->smarty->assign($key, $value);
	}
}