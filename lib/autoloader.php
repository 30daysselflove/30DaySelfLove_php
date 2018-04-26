<?php

/**
 * 
 * @author Adam Dougherty
 *
 */
class autoloader
{
	protected $namespaceLocations = array(
		"lib" => LIB_BASE_PATH,
		"controller" => CONTROLLER_BASE_PATH,
		"view"		=> VIEW_BASE_PATH,
		"model" => MODEL_BASE_PATH,
		"components" => COMPONENT_BASE_PATH
	);
	const LOADER_FUNCTION = "namespaceLoad";
	function __construct()
	{
		spl_autoload_register(array($this, self::LOADER_FUNCTION));
		spl_autoload_register(array($this, "defaultLoad"));
	}
	
	public function namespaceLoad($class)
	{
	
		$class = trim($class, "/\\");
		
		$namespaceArray = explode("\\", $class);
		$baseNS = array_shift($namespaceArray);
		if(empty($this->namespaceLocations[$baseNS])) return;
		$basePath = $this->namespaceLocations[$baseNS];
		
		if(!empty($basePath))
		{
			$classPath = $basePath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $namespaceArray);
			
			include_once $_SERVER['DOCUMENT_ROOT'] . "/$classPath.php";
		}
	}
	
	public function defaultLoad($class)
	{
		
		$class = trim($class, "/\\");
		$namespaceArray = explode("\\", $class);
		$class = implode(DIRECTORY_SEPARATOR, $namespaceArray);
		
		$classPath = LIB_BASE_PATH . DIRECTORY_SEPARATOR . $class;
		if(!is_file($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "$classPath.php")) return;
		include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "$classPath.php";
		
	
	}
	
	
}