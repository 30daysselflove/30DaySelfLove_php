<?php

namespace components;

class EmailTemplate
{
	public $tpl;
	protected $smarty;
	
	public function __construct($tpl, $vars = null)
	{
		require_once(LIB_BASE_PATH . "/smarty/Smarty.class.php");
		$this->tpl = $tpl;
		$this->smarty = new \Smarty();
		$this->smarty->template_dir = VIEW_BASE_PATH. '/emails/';
		$this->smarty->compile_dir  = VIEW_BASE_PATH.'/smarty/emails/templates_c/';
		$this->smarty->config_dir   = VIEW_BASE_PATH.'/smarty/emails/configs/';
		$this->smarty->cache_dir    = VIEW_BASE_PATH.'/smarty/emails/cache/';
		$this->smarty->compile_check = true;
		$this->smarty->assign("imgBase", "http://" . BASE_DOMAIN .  "/www/images/");
		$this->smarty->assign("baseDomain", "http://" . BASE_DOMAIN);
		if($vars) $this->smarty->assign($vars);
		//$this->smarty->use_include_path = true;
	}
	
	
	public function __set($key, $value)
	{
		$this->smarty->assign($key, $value);
	}
	
	public function render()
	{
		return $this->smarty->fetch($this->tpl);
	}
}