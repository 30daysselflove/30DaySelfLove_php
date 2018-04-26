<?php

/**
 * 
 * @author Adam Dougherty
 *
 */
namespace lib;


use lib\exception\SecurityException;


class Session
{
	const PERMANENT_COOKIE_LENGTH = 7776000; //90 Days
    public static $sessionName = DEFAULT_SESSION_NAME;
	public $newSession;
	public static $CSRFToken;
	public $started = false;
	public static $ready = false;

	public $secure = true;
	
	private static $instance;
	
	public static function getInstance($expiration = null) 
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c($expiration);
        }

        return self::$instance;
    }	
    
    
	protected function __construct($expiration = null)
	{
		if($expiration === null && !self::check()) session_set_cookie_params(0, "/", COOKIE_DOMAIN);
		else if($expiration !== null)
		{
			session_set_cookie_params($expiration, "/", COOKIE_DOMAIN); 
		}
  		$this->newSession = !self::check();
		
  		session_name(self::$sessionName);
  		
  		if(!$this->newSession)
  		{
  			if(!isset($_COOKIE[self::$sessionName]))
  			{
  				session_id($_REQUEST[self::$sessionName]);
  			}
  		}
  		
		session_start();
		$this->secure();
		self::$ready = $this->started;
	}
	
	public static function check()
	{
		$sessionCookieName = self::$sessionName;
	
		return isset($_COOKIE[$sessionCookieName]) || isset($_REQUEST[$sessionCookieName]);
	}
	
	public function destroy()
	{
		session_unset();
		session_destroy();
		session_write_close();
		Cookie::delete(session_name());
		session_regenerate_id(true);
		
	}
	
	/*
	 * Special helper function for session facilated logins. Sets the user and regenerates the session id. 
	 */
	public function login($user, $expiration = 0)
	{
		session_set_cookie_params($expiration, "/", COOKIE_DOMAIN);
		Cookie::delete(session_name());
		session_regenerate_id(true);
		$this->user = $user;
	}
	
	public function regen()
	{
		session_regenerate_id(true);
	}
	
	public function __get($key)
	{
		
		$val = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
		return $val;
	}
	
	public function __set($key, $value)
	{
		if($key == "user")
		{
			$this->loggedIn = true;
		}
		$_SESSION[$key] = $value;
	}
	
	public function __isset($key)
	{
		return isset($_SESSION[$key]);
	}
	public function __unset($key)
	{
		unset($_SESSION[$key]);
	}
	protected function generateRandomToken()
	{
		$token = uniqid();
		return $token;
	}
	/**
	 * Some rudimentary PHP session security checks
	 */
	
	protected function secure()
	{
		
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if($this->newSession)
		{
			$this->UAToken = $ua; //User Agent Token
			$this->CToken = 	self::$CSRFToken = $this->generateRandomToken();	//CSRF Protection Token
			setcookie("secureToken", self::$CSRFToken, 0, "/", COOKIE_DOMAIN);
			return true;
		}
	
		self::$CSRFToken = $this->CToken;
	
		$providedToken = isset($_REQUEST['CToken']) ? $_REQUEST['CToken'] : null;
		if(self::$CSRFToken != $providedToken) 
		{
			$this->secure = false;
			if($_SERVER['REQUEST_METHOD'] == "POST") return false;
		}
		
		
		
	}
	
	
	

	
	
	
}