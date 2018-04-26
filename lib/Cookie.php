<?php
namespace lib;
class Cookie {
	
	public static $implicitExpiration = 0;
    public static $data;
	
	public static function set($name, $value, $expire, $path= null, $domain = null){
	    $myDomain = $domain ? $domain : COOKIE_DOMAIN;
	    $myPath = $path ? $path : COOKIE_PATH;
		setcookie($name, $value, $expire, $myPath, $myDomain);
	}
	
	public static function delete($name, $path = null, $domain = null){
	   	$myDomain = $domain ? $domain : COOKIE_DOMAIN;
	   	$myPath = $path ? $path : COOKIE_PATH;
		setcookie($name, "", time() - 9200, $myPath, $myDomain);
	}
	
	
	public static function domainCookie($domain)
	{
	    return new CookieData(null, $domain);
	}
	
}

class CookieData
{
    private $path;
    private $domain;
    
    public function __construct($path = null, $domain = null)
    {
		$this->path = $path;
		$this->domain = $domain;        
    }
    public function __set($key, $value)
    {
        Cookie::set($key, $value, Cookie::$implicitExpiration, $this->path, $this->domain);
    }
    
    public function delete($key)
    {
        Cookie::delete($key, $this->path, $this->domain);
    }
    
    public function __get($key)
    {
        return $_COOKIE[$key];
    }
}

Cookie::$data = new CookieData();