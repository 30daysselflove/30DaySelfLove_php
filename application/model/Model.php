<?php

namespace model;

abstract class Model
{
	public $lastErrorTitle;
	public $lastErrorDesc;
	private $shortCache = array();

	protected function setShortCache($key, $value)
	{
		$this->shortCache[$key] = $value;
	}
	
	protected function getShortCache($key)
	{
		if(isset($this->shortCache[$key])) return $this->shortCache[$key];
		else return null;
	}
	
	protected function clearShortCache()
	{
		$this->shortCache = array();
	}
	
	
	
}