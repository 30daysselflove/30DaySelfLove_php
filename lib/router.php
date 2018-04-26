<?php
/**
 * 
 * @author Adam Dougherty
 *
 */
namespace lib;

use controller\ActiveViewController;

use controller\controller;

class router {
	
	protected $path;
	protected $args = array();
	function __construct() {
		
	}
	
	public function setPath($path) {
    	$path = trim($path, '/\\') . "/";

        if (is_dir($path) == false) {
        	throw new Exception ('Invalid controller path: `' . $path . '`');
        }

        $this->path = $path;
	}
	
	/**
	 * 
	 * Checks the format key from the request array for valid format options. 
	 * If the format is not valid, it returns it to be added back onto the route
	 */
	protected function checkRequestFormat()
	{
	  	$format = "";
	  	if (isset($_REQUEST['format']) ) {
		  $format = $_REQUEST['format'];
		}
		if(preg_match("/^(jpg|png|gif)/", $format)) //were just checking for image types right now
		{
			unset($_REQUEST['format']);
			return ".$format";
		}
	}
	
	protected function getController(&$file, &$controller, &$action, &$args) {

		//Route is a "/" (foward slash) delimited query string which defines what controller and the subsequent method to use
	

        $route = (empty($_REQUEST['route'])) ? '' : $_REQUEST['route'];

        if (count(explode(".", $_SERVER['HTTP_HOST'])) < 3) { header("Location: http://" . BASE_DOMAIN); }

        $format = $this->checkRequestFormat();
		$route .= $format;
        // Get separate parts
        $route = trim($route, '/\\');
        $parts = explode('/', $route);
		$basePart = $parts[0];
        /*
         * Find the right controller
         * The first check determines if route starts with api. If it does, we manually set up the api controller and its paramaters
         * otherwise we just automatically route to a controller
         */
        $cmd_path = $this->path;
		$namespace = "";

        	//print_r($parts);
	        foreach ($parts as $part) {

                $part = str_replace("-", "_", $part);
                $fullpath = $cmd_path . $part;
                 
                // Is there a dir with this path?
               
                if (is_dir($fullpath)) {
                	 
                	
                        $cmd_path .= $part . DIRECTORY_SEPARATOR;
                       	$namespace .= $part . "\\"; //Class namespace creation
                       	
                        array_shift($parts);
                        continue;
                }

                // Find the file
                if (is_file($fullpath . '.php')) {
                
                        $controller = $part;
                        array_shift($parts);
                        $action = array_shift($parts);
                        break;
                }
	        }
        

        if (empty($controller)) 
        {
        	 $controller = 'prime'; 
        	 $action = array_shift($parts);
        };

        
        // Get action
       
        if (empty($action)) { $action = 'index'; }

        
        
        $file = $cmd_path . $controller . '.php';
        $controller = "controller\\$namespace$controller";
		$args = $parts;
	}


	public function delegate() {
        // Analyze route


        $this->getController($file, $controller, $action, $args);
        // File available?
        if (is_readable($file) == false) {
                die ('404 Not Found');
                
        }

        // Initiate the controller

        $class = "$controller";
        try
        {
            $controller = new $class();
        }
        catch (Exception $e)
        {
        	
        }

        if(!($controller instanceof controller)) die("404");

        $controller->actionContext = $action;
		if($controller instanceof ActiveViewController)
       	{
       		//Active view controllers have their own internal action routing mechanism
       		$controller->activate();
       		return;
       	}
     	
        // Action available?
        //var_dump($controller);


        if(is_callable(array($class, $action)) == false)
        {

        	array_unshift($args, $action);
        	$action = "index";
        }
     
        //Execute Action
        if(!empty($args))
        {
        	call_user_func_array(array($controller, $action), $args);
        }
        else $controller->$action();	

	}

	  
}
