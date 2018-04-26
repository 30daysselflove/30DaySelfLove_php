<?php
namespace controller;

use lib\timthumb\timthumb;

class media extends controller
{
	public function image()
	{
		$args = func_get_args();
		$format = isset($_GET['format']) ? "." . $_GET['format'] : "";
		if(empty($args)) $this->http404();
		
		$src = implode("/", $args). strtolower($format);
		
		timthumb::start($src);
	}
	
	public function video()
	{
		
	}
	
	public function audio()
	{
		
	}
	
	public function flash()
	{
		
	}
}